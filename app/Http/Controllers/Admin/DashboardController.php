<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\ApprovalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
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
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Statistik utama
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_items' => Item::count(),
            'pending_items' => Item::where('status', 'pending')->count(),
            'approved_items' => Item::where('status', 'approved')->count(),
            'rejected_items' => Item::where('status', 'rejected')->count(),
            'total_transactions' => Transaction::count(),
            'completed_transactions' => Transaction::where('payment_status', 'completed')->count(),
            'total_revenue' => Transaction::where('payment_status', 'completed')->sum('admin_fee'),
        ];

        // Grafik transaksi per hari (7 hari terakhir)
        $transactions_chart = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN payment_status = "completed" THEN 1 ELSE 0 END) as completed')
        )
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Grafik moderasi per hari (7 hari terakhir)
        $moderation_chart = ApprovalLog::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN action = "approved" THEN 1 ELSE 0 END) as approved'),
            DB::raw('SUM(CASE WHEN action = "rejected" THEN 1 ELSE 0 END) as rejected')
        )
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Top users by items
        $top_users = User::withCount('items')
            ->where('role', 'user')
            ->orderBy('items_count', 'desc')
            ->take(5)
            ->get();

        // Recent activities
        $recent_moderations = ApprovalLog::with(['admin', 'item'])
            ->latest()
            ->take(10)
            ->get();

        $recent_transactions = Transaction::with(['buyer', 'seller', 'item'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'transactions_chart',
            'moderation_chart',
            'top_users',
            'recent_moderations',
            'recent_transactions'
        ));
    }
}
