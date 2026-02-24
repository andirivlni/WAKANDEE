<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionMonitorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['buyer', 'seller', 'item']);

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by transaction code or user
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_code', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('buyer', function($buyer) use ($search) {
                      $buyer->where('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('email', 'LIKE', '%' . $search . '%');
                  })
                  ->orWhereHas('seller', function($seller) use ($search) {
                      $seller->where('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('email', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        $transactions = $query->latest()->paginate(20);

        // Stats
        $stats = [
            'total_transactions' => Transaction::count(),
            'completed_transactions' => Transaction::where('payment_status', 'completed')->count(),
            'pending_transactions' => Transaction::where('payment_status', 'pending')->count(),
            'paid_transactions' => Transaction::where('payment_status', 'paid')->count(),
            'cancelled_transactions' => Transaction::where('payment_status', 'cancelled')->count(),
            'total_revenue' => Transaction::where('payment_status', 'completed')->sum('admin_fee'),
            'total_amount' => Transaction::where('payment_status', 'completed')->sum('amount'),
        ];

        // Daily transactions for chart
        $daily_transactions = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN payment_status = "completed" THEN 1 ELSE 0 END) as completed'),
            DB::raw('SUM(admin_fee) as revenue')
        )
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return view('admin.transactions.index', compact('transactions', 'stats', 'daily_transactions'));
    }

    /**
     * Display the specified transaction.
     */
    public function show($id)
    {
        $transaction = Transaction::with(['buyer', 'seller', 'item'])
            ->findOrFail($id);

        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Update transaction status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,completed,cancelled',
            'note' => 'nullable|string|max:500',
        ]);

        $transaction = Transaction::findOrFail($id);
        $old_status = $transaction->payment_status;

        $transaction->update([
            'payment_status' => $request->payment_status,
            'note' => $request->note,
        ]);

        // If cancelled, update item status back to approved
        if ($request->payment_status === 'cancelled' && $old_status !== 'cancelled') {
            $transaction->item->update(['status' => 'approved']);
        }

        // If completed, ensure item status is sold
        if ($request->payment_status === 'completed' && $old_status !== 'completed') {
            $transaction->item->update(['status' => 'sold']);
            $transaction->update(['completed_at' => now()]);
        }

        return back()->with('success', 'Status transaksi berhasil diperbarui.');
    }

    /**
     * Complete transaction via AJAX
     */
    public function complete($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);

            DB::beginTransaction();

            $transaction->update([
                'payment_status' => 'completed',
                'completed_at' => now()
            ]);

            $transaction->item->update(['status' => 'sold']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diselesaikan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel transaction via AJAX
     */
    public function cancel($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);

            DB::beginTransaction();

            $transaction->update([
                'payment_status' => 'cancelled'
            ]);

            $transaction->item->update(['status' => 'approved']);

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
     * Export transactions report.
     */
    public function export(Request $request)
    {
        $query = Transaction::with(['buyer', 'seller', 'item']);

        // Apply filters
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->get();

        // Generate CSV
        $filename = 'transactions_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'Kode Transaksi',
                'Tanggal',
                'Pembeli',
                'Penjual',
                'Barang',
                'Tipe',
                'Harga',
                'Biaya Admin',
                'Total',
                'Metode Pembayaran',
                'Status',
                'Tanggal Selesai'
            ]);

            // Data
            foreach ($transactions as $trx) {
                fputcsv($file, [
                    $trx->transaction_code,
                    $trx->created_at->format('d/m/Y H:i'),
                    $trx->buyer->name ?? '-',
                    $trx->seller->name ?? '-',
                    $trx->item->name ?? '-',
                    $trx->item->type ?? '-',
                    $trx->amount,
                    $trx->admin_fee,
                    $trx->amount + $trx->admin_fee,
                    $trx->payment_method,
                    $trx->payment_status,
                    $trx->completed_at ? date('d/m/Y H:i', strtotime($trx->completed_at)) : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
