<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of items (for catalog)
     */
    public function index(Request $request)
    {
        $query = Item::with('user')
            ->where('status', 'approved');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by type (sale/gift)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Search by keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if (in_array($sortBy, ['name', 'price', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $items = $query->paginate(20);

        // Transform items
        $items->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'price' => $item->price,
                'type' => $item->type,
                'type_label' => $item->type_label,
                'category' => $item->category,
                'category_label' => $item->category_label,
                'condition' => $item->condition,
                'condition_label' => $item->condition_label,
                'images' => collect($item->images)->map(function ($image) {
                    return asset('storage/' . $image);
                }),
                'user' => [
                    'id' => $item->user->id,
                    'name' => $item->user->name,
                    'school' => $item->user->school,
                ],
                'legacy_message' => $item->legacy_message,
                'created_at' => $item->created_at->diffForHumans(),
                'is_wishlisted' => Auth::check() ? Wishlist::where('user_id', Auth::id())->where('item_id', $item->id)->exists() : false,
            ];
        });

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
            ->findOrFail($id);

        $data = [
            'id' => $item->id,
            'name' => $item->name,
            'description' => $item->description,
            'price' => $item->price,
            'type' => $item->type,
            'type_label' => $item->type_label,
            'category' => $item->category,
            'category_label' => $item->category_label,
            'condition' => $item->condition,
            'condition_label' => $item->condition_label,
            'images' => collect($item->images)->map(function ($image) {
                return asset('storage/' . $image);
            }),
            'user' => [
                'id' => $item->user->id,
                'name' => $item->user->name,
                'school' => $item->user->school,
                'grade' => $item->user->grade,
                'profile_photo' => $item->user->profile_photo ? asset('storage/' . $item->user->profile_photo) : null,
            ],
            'legacy_message' => $item->legacy_message,
            'created_at' => $item->created_at->format('d F Y'),
            'is_wishlisted' => Auth::check() ? Wishlist::where('user_id', Auth::id())->where('item_id', $item->id)->exists() : false,
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Store a newly created item.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:buku,seragam,alat_praktikum,lainnya',
            'condition' => 'required|in:baru,baik,layak',
            'type' => 'required|in:sale,gift',
            'price' => 'required_if:type,sale|nullable|numeric|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'legacy_message' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('items', 'public');
                $images[] = $path;
            }
        }

        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'condition' => $request->condition,
            'type' => $request->type,
            'price' => $request->type === 'sale' ? $request->price : 0,
            'images' => $images,
            'legacy_message' => $request->legacy_message,
            'status' => 'pending', // Perlu moderasi admin
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil diupload dan menunggu moderasi',
            'data' => $item
        ], 201);
    }

    /**
     * Update the specified item.
     */
    public function update(Request $request, $id)
    {
        $item = Item::where('user_id', Auth::id())->findOrFail($id);

        // Cek apakah item bisa diedit
        if ($item->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak bisa diedit karena sudah diproses'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'category' => 'sometimes|in:buku,seragam,alat_praktikum,lainnya',
            'condition' => 'sometimes|in:baru,baik,layak',
            'type' => 'sometimes|in:sale,gift',
            'price' => 'required_if:type,sale|nullable|numeric|min:0',
            'legacy_message' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only(['name', 'description', 'category', 'condition', 'type', 'legacy_message']);

        if ($request->has('type') && $request->type === 'sale') {
            $data['price'] = $request->price;
        } elseif ($request->has('type') && $request->type === 'gift') {
            $data['price'] = 0;
        }

        $item->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil diupdate',
            'data' => $item
        ]);
    }

    /**
     * Remove the specified item.
     */
    public function destroy($id)
    {
        $item = Item::where('user_id', Auth::id())->findOrFail($id);

        // Hapus gambar dari storage
        if ($item->images) {
            foreach ($item->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus'
        ]);
    }

    /**
     * Get categories list
     */
    public function categories()
    {
        $categories = [
            ['value' => 'buku', 'label' => 'Buku', 'icon' => 'book'],
            ['value' => 'seragam', 'label' => 'Seragam', 'icon' => 'person-badge'],
            ['value' => 'alat_praktikum', 'label' => 'Alat Praktikum', 'icon' => 'beaker'],
            ['value' => 'lainnya', 'label' => 'Lainnya', 'icon' => 'grid'],
        ];

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get conditions list
     */
    public function conditions()
    {
        $conditions = [
            ['value' => 'baru', 'label' => 'Baru'],
            ['value' => 'baik', 'label' => 'Baik'],
            ['value' => 'layak', 'label' => 'Layak'],
        ];

        return response()->json([
            'success' => true,
            'data' => $conditions
        ]);
    }

    /**
     * Get user's items
     */
    public function myItems()
    {
        $items = Item::where('user_id', Auth::id())
            ->withCount('transactions')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }
}
