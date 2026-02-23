<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display user's wishlist.
     */
    public function index()
    {
        $wishlist = Wishlist::with('item.user')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $wishlist
        ]);
    }

    /**
     * Toggle wishlist status.
     */
    public function toggle($item_id)
    {
        $item = Item::where('status', 'approved')->findOrFail($item_id);

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('item_id', $item_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $message = 'Item removed from wishlist';
            $status = 'removed';
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'item_id' => $item_id
            ]);
            $message = 'Item added to wishlist';
            $status = 'added';
        }

        return response()->json([
            'success' => true,
            'status' => $status,
            'message' => $message
        ]);
    }

    /**
     * Remove from wishlist.
     */
    public function destroy($id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $wishlist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from wishlist'
        ]);
    }
}
