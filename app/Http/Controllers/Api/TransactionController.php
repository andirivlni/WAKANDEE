<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Item;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * Display a listing of user's transactions.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Transaction::with(['item', 'seller', 'buyer'])
            ->where(function($q) use ($user) {
                $q->where('buyer_id', $user->id)
                  ->orWhere('seller_id', $user->id);
            });

        // Filter by status
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        // Filter by type (bought/sold)
        if ($request->filled('type')) {
            if ($request->type === 'bought') {
                $query->where('buyer_id', $user->id);
            } elseif ($request->type === 'sold') {
                $query->where('seller_id', $user->id);
            }
        }

        $transactions = $query->latest()->paginate(20);

        // Transform data
        $transactions->getCollection()->transform(function ($trx) {
            return [
                'id' => $trx->id,
                'transaction_code' => $trx->transaction_code,
                'amount' => $trx->amount,
                'admin_fee' => $trx->admin_fee,
                'total_amount' => $trx->total_amount,
                'formatted_amount' => 'Rp ' . number_format($trx->amount, 0, ',', '.'),
                'formatted_total' => 'Rp ' . number_format($trx->total_amount, 0, ',', '.'),
                'payment_method' => $trx->payment_method,
                'payment_method_label' => $trx->payment_method_label,
                'payment_status' => $trx->payment_status,
                'payment_status_label' => $trx->payment_status_label,
                'delivery_method' => $trx->delivery_method,
                'delivery_method_label' => $trx->delivery_method_label,
                'dropoff_point' => $trx->dropoff_point,
                'notes' => $trx->notes,
                'created_at' => $trx->created_at->format('d/m/Y H:i'),
                'created_at_human' => $trx->created_at->diffForHumans(),
                'paid_at' => $trx->paid_at ? $trx->paid_at->format('d/m/Y H:i') : null,
                'completed_at' => $trx->completed_at ? $trx->completed_at->format('d/m/Y H:i') : null,
                'item' => [
                    'id' => $trx->item->id,
                    'name' => $trx->item->name,
                    'category' => $trx->item->category_label,
                    'condition' => $trx->item->condition_label,
                    'image' => $trx->item->images ? asset('storage/' . $trx->item->images[0]) : null,
                ],
                'buyer' => [
                    'id' => $trx->buyer->id,
                    'name' => $trx->buyer->name,
                    'email' => $trx->buyer->email,
                    'school' => $trx->buyer->school,
                ],
                'seller' => [
                    'id' => $trx->seller->id,
                    'name' => $trx->seller->name,
                    'email' => $trx->seller->email,
                    'school' => $trx->seller->school,
                ],
                'is_buyer' => $trx->buyer_id === Auth::id(),
                'is_seller' => $trx->seller_id === Auth::id(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    /**
     * Display the specified transaction.
     */
    public function show($id)
    {
        $transaction = Transaction::with(['item', 'seller', 'buyer'])
            ->where(function($q) {
                $q->where('buyer_id', Auth::id())
                  ->orWhere('seller_id', Auth::id());
            })
            ->findOrFail($id);

        $data = [
            'id' => $transaction->id,
            'transaction_code' => $transaction->transaction_code,
            'amount' => $transaction->amount,
            'admin_fee' => $transaction->admin_fee,
            'total_amount' => $transaction->total_amount,
            'formatted_amount' => 'Rp ' . number_format($transaction->amount, 0, ',', '.'),
            'formatted_total' => 'Rp ' . number_format($transaction->total_amount, 0, ',', '.'),
            'payment_method' => $transaction->payment_method,
            'payment_method_label' => $transaction->payment_method_label,
            'payment_status' => $transaction->payment_status,
            'payment_status_label' => $transaction->payment_status_label,
            'delivery_method' => $transaction->delivery_method,
            'delivery_method_label' => $transaction->delivery_method_label,
            'dropoff_point' => $transaction->dropoff_point,
            'notes' => $transaction->notes,
            'bank_name' => $transaction->bank_name,
            'account_number' => $transaction->account_number,
            'account_name' => $transaction->account_name,
            'payment_proof' => $transaction->payment_proof ? asset('storage/' . $transaction->payment_proof) : null,
            'created_at' => $transaction->created_at->format('d/m/Y H:i'),
            'created_at_human' => $transaction->created_at->diffForHumans(),
            'paid_at' => $transaction->paid_at ? $transaction->paid_at->format('d/m/Y H:i') : null,
            'completed_at' => $transaction->completed_at ? $transaction->completed_at->format('d/m/Y H:i') : null,
            'item' => [
                'id' => $transaction->item->id,
                'name' => $transaction->item->name,
                'description' => $transaction->item->description,
                'category' => $transaction->item->category_label,
                'condition' => $transaction->item->condition_label,
                'type' => $transaction->item->type_label,
                'images' => collect($transaction->item->images)->map(function($img) {
                    return asset('storage/' . $img);
                }),
                'legacy_message' => $transaction->item->legacy_message,
            ],
            'buyer' => [
                'id' => $transaction->buyer->id,
                'name' => $transaction->buyer->name,
                'email' => $transaction->buyer->email,
                'phone' => $transaction->buyer->phone,
                'school' => $transaction->buyer->school,
                'grade' => $transaction->buyer->grade,
                'profile_photo' => $transaction->buyer->profile_photo ? asset('storage/' . $transaction->buyer->profile_photo) : null,
            ],
            'seller' => [
                'id' => $transaction->seller->id,
                'name' => $transaction->seller->name,
                'email' => $transaction->seller->email,
                'phone' => $transaction->seller->phone,
                'school' => $transaction->seller->school,
                'grade' => $transaction->seller->grade,
                'profile_photo' => $transaction->seller->profile_photo ? asset('storage/' . $transaction->seller->profile_photo) : null,
            ],
            'is_buyer' => $transaction->buyer_id === Auth::id(),
            'is_seller' => $transaction->seller_id === Auth::id(),
            'can_confirm' => $transaction->payment_status === 'paid' && $transaction->buyer_id === Auth::id(),
            'can_cancel' => $transaction->payment_status === 'pending' && $transaction->buyer_id === Auth::id(),
            'can_pay' => $transaction->payment_status === 'pending' && $transaction->buyer_id === Auth::id(),
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Store a newly created transaction (checkout).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|exists:items,id',
            'payment_method' => 'required|in:bank_transfer,cod',
            'delivery_method' => 'required|in:dropoff,cod',
            'dropoff_point' => 'required_if:delivery_method,dropoff|nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $item = Item::where('status', 'approved')->findOrFail($request->item_id);

        // Cek apakah user membeli barang sendiri
        if ($item->user_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat membeli barang sendiri'
            ], 422);
        }

        // Cek apakah barang sedang dalam transaksi
        $existingTransaction = Transaction::where('item_id', $item->id)
            ->whereIn('payment_status', ['pending', 'paid'])
            ->first();

        if ($existingTransaction) {
            return response()->json([
                'success' => false,
                'message' => 'Barang sedang dalam proses transaksi'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $admin_fee = ($item->type === 'sale') ? 1000 : 0;
            $total_amount = ($item->price ?? 0) + $admin_fee;
            $transaction_code = 'TRX-' . strtoupper(Str::random(10)) . '-' . date('Ymd');

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
                'notes' => $request->notes,
            ]);

            if ($request->payment_method === 'bank_transfer') {
                $transaction->update([
                    'bank_name' => 'BCA',
                    'account_number' => '1234567890',
                    'account_name' => 'WAKANDE',
                ]);
            }

            $item->update(['status' => 'pending_transaction']);

            // Buat notifikasi untuk penjual
            Notification::create([
                'user_id' => $item->user_id,
                'type' => 'new_transaction',
                'title' => 'Ada Transaksi Baru',
                'message' => Auth::user()->name . ' ingin membeli ' . $item->name,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'transaction_code' => $transaction->transaction_code,
                    'total_amount' => $total_amount,
                    'payment_method' => $request->payment_method,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm payment (upload proof)
     */
    public function confirmPayment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_proof' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $transaction = Transaction::where('buyer_id', Auth::id())
            ->where('payment_status', 'pending')
            ->findOrFail($id);

        DB::beginTransaction();

        try {
            $path = $request->file('payment_proof')->store('payments', 'public');

            $transaction->update([
                'payment_status' => 'paid',
                'payment_proof' => $path,
                'paid_at' => now(),
            ]);

            Notification::create([
                'user_id' => $transaction->seller_id,
                'type' => 'payment_confirmed',
                'title' => 'Pembayaran Dikonfirmasi',
                'message' => 'Pembayaran untuk ' . $transaction->item->name . ' telah diterima',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dikonfirmasi'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal konfirmasi pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm delivery
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

            $transaction->item->update(['status' => 'sold']);

            Notification::create([
                'user_id' => $transaction->seller_id,
                'type' => 'delivery_confirmed',
                'title' => 'Barang Telah Diterima',
                'message' => Auth::user()->name . ' telah menerima ' . $transaction->item->name,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Penerimaan barang berhasil dikonfirmasi'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal konfirmasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel transaction
     */
    public function cancel($id)
    {
        $transaction = Transaction::where('buyer_id', Auth::id())
            ->where('payment_status', 'pending')
            ->findOrFail($id);

        DB::beginTransaction();

        try {
            $transaction->update(['payment_status' => 'cancelled']);
            $transaction->item->update(['status' => 'approved']);

            Notification::create([
                'user_id' => $transaction->seller_id,
                'type' => 'transaction_cancelled',
                'title' => 'Transaksi Dibatalkan',
                'message' => Auth::user()->name . ' membatalkan pesanan untuk ' . $transaction->item->name,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibatalkan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction statistics for user
     */
    public function stats()
    {
        $user = Auth::id();

        $stats = [
            'total' => Transaction::where('buyer_id', $user)->orWhere('seller_id', $user)->count(),
            'pending' => Transaction::where('buyer_id', $user)->where('payment_status', 'pending')->count(),
            'paid' => Transaction::where('buyer_id', $user)->where('payment_status', 'paid')->count(),
            'completed' => Transaction::where(function($q) use ($user) {
                $q->where('buyer_id', $user)->orWhere('seller_id', $user);
            })->where('payment_status', 'completed')->count(),
            'cancelled' => Transaction::where('buyer_id', $user)->where('payment_status', 'cancelled')->count(),
            'as_buyer' => Transaction::where('buyer_id', $user)->count(),
            'as_seller' => Transaction::where('seller_id', $user)->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
