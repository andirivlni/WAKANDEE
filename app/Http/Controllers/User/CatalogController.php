<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatalogController extends Controller
{
    /**
     * Display a listing of approved items.
     */
    public function index(Request $request)
    {
        $query = Item::with('user')
            ->where('status', 'approved')
            ->latest();

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by type (gift/sale)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Filter by school
        if ($request->filled('school')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('school', 'LIKE', '%' . $request->school . '%');
            });
        }

        // Filter by price range (for sale items)
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->where('type', 'sale')
                ->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        // Search by keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%')
                  ->orWhere('legacy_message', 'LIKE', '%' . $search . '%');
            });
        }

        $items = $query->paginate(12)->withQueryString();

        // Get user's wishlist items if logged in
        $wishlist_ids = [];
        if (Auth::check()) {
            $wishlist_ids = Wishlist::where('user_id', Auth::id())
                ->pluck('item_id')
                ->toArray();
        }

        // Get unique schools for filter
        $schools = \App\Models\User::where('role', 'user')
            ->whereNotNull('school')
            ->distinct()
            ->pluck('school')
            ->take(20);

        return view('user.catalog.index', compact('items', 'wishlist_ids', 'schools'));
    }

    /**
     * Display the specified item.
     */
    public function show($id)
    {
        $item = Item::with('user')
            ->where('status', 'approved')
            ->findOrFail($id);

        // Increment views
        $item->increment('views_count');

        // Check if item is in user's wishlist
        $is_wishlisted = false;
        if (Auth::check()) {
            $is_wishlisted = Wishlist::where('user_id', Auth::id())
                ->where('item_id', $item->id)
                ->exists();
        }

        // Related items (same category, approved)
        $related_items = Item::with('user')
            ->where('status', 'approved')
            ->where('category', $item->category)
            ->where('id', '!=', $item->id)
            ->latest()
            ->take(4)
            ->get();

        return view('user.catalog.detail', compact('item', 'is_wishlisted', 'related_items'));
    }
}
