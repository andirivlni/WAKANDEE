<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search items
     */
    public function index(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return response()->json([]);
        }

        $items = Item::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->where('status', 'approved')
            ->with('user')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category_label,
                    'condition' => $item->condition_label,
                    'price' => $item->price,
                    'type' => $item->type,
                    'user' => $item->user->name,
                    'image' => $item->images ? asset('storage/' . $item->images[0]) : null,
                ];
            });

        return response()->json($items);
    }
}
