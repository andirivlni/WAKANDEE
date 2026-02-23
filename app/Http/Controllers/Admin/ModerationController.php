<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ApprovalLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ModerationController extends Controller
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
     * Display a listing of items for moderation.
     */
    public function index(Request $request)
    {
        $query = Item::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'pending');
        }

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
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('user', function($user) use ($search) {
                      $user->where('name', 'LIKE', '%' . $search . '%')
                           ->orWhere('email', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        $items = $query->latest()->paginate(15);

        // Count stats for tabs
        $counts = [
            'pending' => Item::where('status', 'pending')->count(),
            'approved' => Item::where('status', 'approved')->count(),
            'rejected' => Item::where('status', 'rejected')->count(),
        ];

        return view('admin.moderation.index', compact('items', 'counts'));
    }

    /**
     * Display the specified item for moderation.
     */
    public function show($id)
    {
        $item = Item::with('user')->findOrFail($id);

        // Get moderation history
        $moderation_history = ApprovalLog::with('admin')
            ->where('item_id', $item->id)
            ->latest()
            ->get();

        return view('admin.moderation.show', compact('item', 'moderation_history'));
    }

    /**
     * Approve the specified item.
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'note' => 'nullable|string|max:500',
        ]);

        $item = Item::where('status', 'pending')->findOrFail($id);

        DB::beginTransaction();

        try {
            // Update item status
            $item->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // Create approval log
            ApprovalLog::create([
                'item_id' => $item->id,
                'admin_id' => Auth::id(),
                'action' => 'approved',
                'reason' => $request->note,
            ]);

            // Create notification for user
            Notification::create([
                'user_id' => $item->user_id,
                'type' => 'item_approved',
                'title' => 'Barang Disetujui',
                'message' => 'Barang "' . $item->name . '" Anda telah disetujui dan sekarang tampil di katalog.',
            ]);

            DB::commit();

            return redirect()->route('admin.moderation.index')
                ->with('success', 'Barang berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui barang: ' . $e->getMessage());
        }
    }

    /**
     * Reject the specified item.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|min:10|max:1000',
        ]);

        $item = Item::where('status', 'pending')->findOrFail($id);

        DB::beginTransaction();

        try {
            // Update item status
            $item->update([
                'status' => 'rejected',
                'rejection_reason' => $request->reason,
            ]);

            // Create approval log
            ApprovalLog::create([
                'item_id' => $item->id,
                'admin_id' => Auth::id(),
                'action' => 'rejected',
                'reason' => $request->reason,
            ]);

            // Create notification for user
            Notification::create([
                'user_id' => $item->user_id,
                'type' => 'item_rejected',
                'title' => 'Barang Ditolak',
                'message' => 'Barang "' . $item->name . '" Anda ditolak. Alasan: ' . $request->reason,
            ]);

            DB::commit();

            return redirect()->route('admin.moderation.index')
                ->with('success', 'Barang berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak barang: ' . $e->getMessage());
        }
    }

    /**
     * Display pending items only.
     */
    public function pending()
    {
        $items = Item::with('user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        $counts = [
            'pending' => Item::where('status', 'pending')->count(),
            'approved' => Item::where('status', 'approved')->count(),
            'rejected' => Item::where('status', 'rejected')->count(),
        ];

        return view('admin.moderation.index', compact('items', 'counts'));
    }
}
