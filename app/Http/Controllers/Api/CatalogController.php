<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

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

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $items = $query->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    /**
     * Display the specified item.
     */
    public function show($id)
    {
        $item = Item::with('user')
            ->where('status', 'approved')
            ->find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        // Increment views
        $item->increment('views_count');

        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }

    /**
     * Get all categories with counts.
     */
    public function categories()
    {
        $categories = Item::where('status', 'approved')
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get all unique schools.
     */
    public function schools()
    {
        $schools = Item::where('status', 'approved')
            ->with('user')
            ->get()
            ->pluck('user.school')
            ->unique()
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $schools
        ]);
    }

    /**
     * Get featured items.
     */
    public function featured()
    {
        $items = Item::with('user')
            ->where('status', 'approved')
            ->latest()
            ->take(8)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }
}
