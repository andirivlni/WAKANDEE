<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Statistik untuk hero section
        $stats = [
            'total_items' => Item::where('status', 'approved')->count(),
            'total_transactions' => Transaction::count(),
            'total_students' => \App\Models\User::where('role', 'user')->count(),
            'total_savings' => Transaction::where('payment_status', 'completed')->sum('amount')
        ];

        // Featured items (barang terbaru yang sudah diapprove)
        $featured_items = Item::with('user')
            ->where('status', 'approved')
            ->latest()
            ->take(8)
            ->get();

        // Kategori dengan count
        $categories = [
            'buku' => Item::where('status', 'approved')->where('category', 'buku')->count(),
            'seragam' => Item::where('status', 'approved')->where('category', 'seragam')->count(),
            'alat_praktikum' => Item::where('status', 'approved')->where('category', 'alat_praktikum')->count(),
            'lainnya' => Item::where('status', 'approved')->where('category', 'lainnya')->count(),
        ];

        return view('home.index', compact('stats', 'featured_items', 'categories'));
    }
}
