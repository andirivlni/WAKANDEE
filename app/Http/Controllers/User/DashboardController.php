<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Statistik user
        $stats = [
            'total_items' => Item::where('user_id', $user->id)->count(),
            'pending_items' => Item::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved_items' => Item::where('user_id', $user->id)->where('status', 'approved')->count(),
            'sold_items' => Transaction::where('seller_id', $user->id)
                ->where('payment_status', 'completed')
                ->count(),
            'bought_items' => Transaction::where('buyer_id', $user->id)
                ->where('payment_status', 'completed')
                ->count(),
            'wishlist_count' => Wishlist::where('user_id', $user->id)->count(),
            'total_savings' => Transaction::where('buyer_id', $user->id)
                ->where('payment_status', 'completed')
                ->sum('amount')
        ];

        // Barang pending
        $pending_items = Item::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Transaksi terbaru sebagai pembeli
        $recent_purchases = Transaction::with(['item', 'seller'])
            ->where('buyer_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Transaksi terbaru sebagai penjual
        $recent_sales = Transaction::with(['item', 'buyer'])
            ->where('seller_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'stats',
            'pending_items',
            'recent_purchases',
            'recent_sales'
        ));
    }
}
