<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Models\Item;
use App\Models\ApprovalLog;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Display a listing of user's items.
     */
    public function index()
    {
        $items = Item::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.items.index', compact('items'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        return view('user.items.create');
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(ItemRequest $request)
    {
        // Logging untuk debug
        Log::info('=== STORE METHOD STARTED ===');
        Log::info('User ID: ' . Auth::id());
        Log::info('Request data: ', $request->all());

        DB::beginTransaction();

        try {
            // Upload images
            $images = [];
            if ($request->hasFile('images')) {
                Log::info('Processing ' . count($request->file('images')) . ' images');
                foreach ($request->file('images') as $key => $image) {
                    $path = $this->fileUploadService->uploadItemImage($image);
                    $images[] = $path;
                    Log::info('Image ' . $key . ' uploaded to: ' . $path);
                }
            } else {
                Log::info('No images uploaded');
            }

            // Prepare data
            $data = [
                'user_id' => Auth::id(),
                'name' => $request->name,
                'description' => $request->description,
                'category' => $request->category,
                'type' => $request->type,
                'price' => ($request->type === 'sale') ? $request->price : 0,
                'condition' => $request->condition,
                'images' => $images, // Langsung array, tidak perlu json_encode
                'legacy_message' => $request->legacy_message,
                'status' => 'pending'
            ];

            Log::info('Data to be saved: ', $data);

            // Create item
            $item = Item::create($data);

            Log::info('Item created successfully with ID: ' . $item->id);

            DB::commit();

            return redirect()->route('items.index')
                ->with('success', 'Barang berhasil diupload dan menunggu moderasi admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ERROR: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()->with('error', 'Gagal mengupload barang: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified item.
     */
    public function show($id)
    {
        $item = Item::with('user')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Increment views
        $item->increment('views_count');

        // Get approval log if rejected
        $approval_log = null;
        if ($item->status === 'rejected') {
            $approval_log = ApprovalLog::with('admin')
                ->where('item_id', $item->id)
                ->latest()
                ->first();
        }

        return view('user.items.show', compact('item', 'approval_log'));
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit($id)
    {
        $item = Item::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        return view('user.items.edit', compact('item'));
    }

    /**
     * Update the specified item in storage.
     */
    public function update(ItemRequest $request, $id)
    {
        $item = Item::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        DB::beginTransaction();

        try {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'category' => $request->category,
                'type' => $request->type,
                'condition' => $request->condition,
                'legacy_message' => $request->legacy_message,
            ];

            // Update price if sale
            if ($request->type === 'sale') {
                $data['price'] = $request->price;
            } else {
                $data['price'] = 0;
            }

            // Update images if new images uploaded
            if ($request->hasFile('images')) {
                // Delete old images
                $oldImages = $item->images ?? [];
                if (is_array($oldImages)) {
                    foreach ($oldImages as $oldImage) {
                        $this->fileUploadService->deleteFile($oldImage);
                    }
                }

                // Upload new images
                $images = [];
                foreach ($request->file('images') as $image) {
                    $path = $this->fileUploadService->uploadItemImage($image);
                    $images[] = $path;
                }
                $data['images'] = $images;
            }

            $item->update($data);

            DB::commit();

            return redirect()->route('items.show', $item->id)
                ->with('success', 'Barang berhasil diupdate.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate barang: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified item from storage.
     */
    public function destroy($id)
    {
        $item = Item::where('user_id', Auth::id())->findOrFail($id);

        // Delete images
        $images = $item->images ?? [];
        if (is_array($images)) {
            foreach ($images as $image) {
                $this->fileUploadService->deleteFile($image);
            }
        }

        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Get user's items statistics.
     */
    public function getStats()
    {
        $userId = Auth::id();

        $stats = [
            'total' => Item::where('user_id', $userId)->count(),
            'pending' => Item::where('user_id', $userId)->where('status', 'pending')->count(),
            'approved' => Item::where('user_id', $userId)->where('status', 'approved')->count(),
            'rejected' => Item::where('user_id', $userId)->where('status', 'rejected')->count(),
            'sold' => Item::where('user_id', $userId)->where('status', 'sold')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Toggle item status (for admin)
     */
    public function toggleStatus(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected,sold,pending_transaction'
        ]);

        $item->update([
            'status' => $request->status,
            'approved_by' => $request->status === 'approved' ? Auth::id() : null,
            'approved_at' => $request->status === 'approved' ? now() : null,
            'rejection_reason' => $request->status === 'rejected' ? $request->reason : null,
        ]);

        // Create approval log
        if ($request->status === 'approved' || $request->status === 'rejected') {
            ApprovalLog::create([
                'item_id' => $item->id,
                'admin_id' => Auth::id(),
                'action' => $request->status,
                'reason' => $request->reason ?? null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status barang berhasil diupdate',
            'status' => $item->status
        ]);
    }
}
