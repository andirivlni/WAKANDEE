<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display a listing of user's wishlist.
     */
    public function index()
    {
        $wishlist = Wishlist::with('item.user')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('user.wishlist.index', compact('wishlist'));
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
            $message = 'Barang dihapus dari wishlist.';
            $status = 'removed';
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'item_id' => $item_id
            ]);
            $message = 'Barang ditambahkan ke wishlist.';
            $status = 'added';
        }

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Remove item from wishlist.
     */
    public function destroy($id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $wishlist->delete();

        return redirect()->route('wishlist.index')
            ->with('success', 'Barang dihapus dari wishlist.');
    }
}
