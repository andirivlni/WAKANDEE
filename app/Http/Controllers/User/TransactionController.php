<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\Notification;
use App\Services\PaymentService;
use App\Services\QRISService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    protected $paymentService;
    protected $qrisService;

    public function __construct(PaymentService $paymentService, QRISService $qrisService)
    {
        $this->paymentService = $paymentService;
        $this->qrisService = $qrisService;
    }

    /**
     * Display a listing of user's transactions.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $transactions = Transaction::with(['item', 'seller', 'buyer'])
            ->where(function($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhere('seller_id', $user->id);
            })
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $transactions->where('payment_status', $request->status);
        }

        // Filter by type (sold/bought)
        if ($request->filled('type')) {
            if ($request->type === 'bought') {
                $transactions->where('buyer_id', $user->id);
            } elseif ($request->type === 'sold') {
                $transactions->where('seller_id', $user->id);
            }
        }

        $transactions = $transactions->paginate(10);

        return view('user.transactions.index', compact('transactions'));
    }

    /**
     * Show checkout page.
     */
    public function checkout($item_id)
    {
        $item = Item::with('user')
            ->where('status', 'approved')
            ->where('id', $item_id)
            ->firstOrFail();

        // Check if user is trying to buy their own item
        if ($item->user_id === Auth::id()) {
            return redirect()->route('catalog.show', $item->id)
                ->with('error', 'Anda tidak dapat membeli barang Anda sendiri.');
        }

        // Check if item is already in transaction
        $existingTransaction = Transaction::where('item_id', $item->id)
            ->whereIn('payment_status', ['pending', 'paid'])
            ->first();

        if ($existingTransaction) {
            return redirect()->route('catalog.show', $item->id)
                ->with('error', 'Barang ini sedang dalam proses transaksi.');
        }

        return view('user.transactions.checkout', compact('item'));
    }

    /**
     * Store a newly created transaction.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'payment_method' => 'required|in:bank_transfer,cod', // QRIS diganti bank_transfer
            'delivery_method' => 'required|in:dropoff,cod',
            'dropoff_point' => 'required_if:delivery_method,dropoff|nullable|string|max:255',
        ]);

        $item = Item::findOrFail($request->item_id);

        // Check if item is still available
        if ($item->status !== 'approved') {
            return back()->with('error', 'Barang tidak tersedia.');
        }

        // Check if user is buying their own item
        if ($item->user_id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat membeli barang Anda sendiri.');
        }

        DB::beginTransaction();

        try {
            // Calculate admin fee for sale items
            $admin_fee = ($item->type === 'sale') ? 1000 : 0;
            $total_amount = ($item->price ?? 0) + $admin_fee;

            // Generate transaction code
            $transaction_code = 'TRX-' . strtoupper(Str::random(10)) . '-' . date('Ymd');

            // Create transaction
            $transaction = Transaction::create([
                'transaction_code' => $transaction_code,
                'item_id' => $item->id,
                'seller_id' => $item->user_id,
                'buyer_id' => Auth::id(),
                'amount' => $item->price ?? 0,
                'admin_fee' => $admin_fee,
                'total_amount' => $total_amount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'delivery_method' => $request->delivery_method,
                'dropoff_point' => $request->dropoff_point,
            ]);

            // Jika payment method bank_transfer, isi dengan info bank statis
            if ($request->payment_method === 'bank_transfer') {
                $transaction->update([
                    'bank_name' => 'BCA',
                    'account_number' => '1234567890',
                    'account_name' => 'WAKANDE',
                ]);
            }

            // Update item status to pending transaction
            $item->update(['status' => 'pending_transaction']);

            // Create notification for seller
            Notification::create([
                'user_id' => $item->user_id,
                'type' => 'new_transaction',
                'title' => 'Ada Transaksi Baru',
                'message' => Auth::user()->name . ' ingin membeli ' . $item->name,
            ]);

            DB::commit();

            if ($request->payment_method === 'bank_transfer') {
                return redirect()->route('transactions.payment', $transaction->id)
                    ->with('success', 'Silakan transfer ke rekening WAKANDE dan upload bukti pembayaran.');
            } else {
                return redirect()->route('transactions.show', $transaction->id)
                    ->with('success', 'Transaksi berhasil dibuat. Silakan lakukan serah terima barang.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified transaction.
     */
    public function show($id)
    {
        $transaction = Transaction::with(['item', 'seller', 'buyer'])
            ->where(function($query) {
                $query->where('buyer_id', Auth::id())
                    ->orWhere('seller_id', Auth::id());
            })
            ->findOrFail($id);

        return view('user.transactions.show', compact('transaction'));
    }

    /**
     * Process payment page (untuk bank transfer).
     */
    public function payment($id)
    {
        $transaction = Transaction::with('item')
            ->where('buyer_id', Auth::id())
            ->where('payment_status', 'pending')
            ->findOrFail($id);

        if ($transaction->payment_method !== 'bank_transfer') {
            return redirect()->route('transactions.show', $transaction->id);
        }

        return view('user.transactions.bank_transfer', compact('transaction'));
    }

    /**
     * Process payment submission (upload bukti transfer).
     */
    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        $transaction = Transaction::where('buyer_id', Auth::id())
            ->where('payment_status', 'pending')
            ->findOrFail($id);

        DB::beginTransaction();

        try {
            // Upload payment proof
            $path = $request->file('payment_proof')->store('payments', 'public');

            // Update transaction
            $transaction->update([
                'payment_status' => 'paid',
                'payment_proof' => $path,
                'paid_at' => now(),
            ]);

            // Create notification for seller
            Notification::create([
                'user_id' => $transaction->seller_id,
                'type' => 'payment_confirmed',
                'title' => 'Pembayaran Dikonfirmasi',
                'message' => 'Pembayaran untuk ' . $transaction->item->name . ' telah diterima.',
            ]);

            DB::commit();

            return redirect()->route('transactions.success', $transaction->id)
                ->with('success', 'Pembayaran berhasil diproses.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Payment success page.
     */
    public function success($id)
    {
        $transaction = Transaction::with('item')
            ->where('buyer_id', Auth::id())
            ->findOrFail($id);

        return view('user.transactions.success', compact('transaction'));
    }

    /**
     * Confirm delivery (for COD/dropoff).
     */
    public function confirmDelivery($id)
    {
        $transaction = Transaction::where('buyer_id', Auth::id())
            ->where('payment_status', 'paid')
            ->findOrFail($id);

        DB::beginTransaction();

        try {
            $transaction->update([
                'payment_status' => 'completed',
                'completed_at' => now(),
            ]);

            // Update item status
            $transaction->item->update(['status' => 'sold']);

            // Create notification for seller
            Notification::create([
                'user_id' => $transaction->seller_id,
                'type' => 'delivery_confirmed',
                'title' => 'Barang Telah Diterima',
                'message' => $transaction->buyer->name . ' telah menerima ' . $transaction->item->name,
            ]);

            DB::commit();

            return redirect()->route('transactions.show', $transaction->id)
                ->with('success', 'Konfirmasi penerimaan barang berhasil.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal konfirmasi: ' . $e->getMessage());
        }
    }

    /**
     * AJAX Confirm (Modified to allow both buyer and seller)
     */
    public function confirm($id)
    {
        $transaction = Transaction::where(function($query) {
                $query->where('buyer_id', Auth::id())
                      ->orWhere('seller_id', Auth::id());
            })
            ->where('payment_status', 'paid')
            ->findOrFail($id);

        try {
            DB::beginTransaction();
            $transaction->update([
                'payment_status' => 'completed',
                'completed_at' => now(),
            ]);
            $transaction->item->update(['status' => 'sold']);

            // Notifikasi untuk pihak lawan (jika buyer yang konfirm, notif ke seller, dst)
            $notifUserId = ($transaction->buyer_id == Auth::id())
                ? $transaction->seller_id
                : $transaction->buyer_id;

            Notification::create([
                'user_id' => $notifUserId,
                'type' => 'delivery_confirmed',
                'title' => 'Barang Telah Diterima',
                'message' => Auth::user()->name . ' telah mengkonfirmasi penerimaan ' . $transaction->item->name,
            ]);

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * AJAX Cancel (Added for SweetAlert)
     */
    public function cancel($id)
    {
        $transaction = Transaction::where('buyer_id', Auth::id())
            ->where('payment_status', 'pending')
            ->findOrFail($id);

        try {
            DB::beginTransaction();
            $transaction->update(['payment_status' => 'cancelled']);
            $transaction->item->update(['status' => 'approved']); // Kembalikan status barang agar bisa dibeli lagi

            Notification::create([
                'user_id' => $transaction->seller_id,
                'type' => 'transaction_cancelled',
                'title' => 'Transaksi Dibatalkan',
                'message' => 'Pembeli membatalkan pesanan untuk ' . $transaction->item->name,
            ]);
            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
