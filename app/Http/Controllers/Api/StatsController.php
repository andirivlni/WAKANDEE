<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * Get general statistics for dashboard
     */
    public function index()
    {
        $stats = [
            'total_items' => Item::where('status', 'approved')->count(),
            'total_transactions' => Transaction::count(),
            'total_users' => User::count(),
            'pending_items' => Item::where('status', 'pending')->count(),
            'completed_transactions' => Transaction::where('payment_status', 'completed')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get chart data for dashboard
     */
    public function chart()
    {
        // Daily transactions for last 7 days
        $daily = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return response()->json($daily);
    }

    /**
     * Get category statistics
     */
    public function categories()
    {
        $categories = Item::select('category', DB::raw('COUNT(*) as total'))
            ->where('status', 'approved')
            ->groupBy('category')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->category => $item->total];
            });

        return response()->json($categories);
    }

    /**
     * Get user statistics (for admin)
     */
    public function users()
    {
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get transaction statistics
     */
    public function transactions()
    {
        $stats = [
            'total' => Transaction::count(),
            'pending' => Transaction::where('payment_status', 'pending')->count(),
            'paid' => Transaction::where('payment_status', 'paid')->count(),
            'completed' => Transaction::where('payment_status', 'completed')->count(),
            'cancelled' => Transaction::where('payment_status', 'cancelled')->count(),
            'revenue' => Transaction::where('payment_status', 'completed')->sum('admin_fee'),
        ];

        return response()->json($stats);
    }
}
