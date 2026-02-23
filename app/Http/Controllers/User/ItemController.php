<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::where('user_id', Auth::id())->latest()->paginate(10);
        return view('user.items.index', compact('items'));
    }

    public function create()
    {
        return view('user.items.create');
    }

    public function store(Request $request)
    {
        Log::info('=== STORE HIT ===', ['input' => $request->all()]);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'category' => 'required',
            'type' => 'required',
            'condition' => 'required',
            'legacy_message' => 'required|string|min:10',
            'images.*' => 'nullable|image|max:2048',
        ]);

        // Upload images simpel
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('items', 'public');
                $imagePaths[] = $path;
            }
        }

        Item::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'type' => $request->type,
            'price' => ($request->type === 'sale') ? ($request->price ?? 0) : 0,
            'condition' => $request->condition,
            'images' => $imagePaths,
            'legacy_message' => $request->legacy_message,
            'status' => 'pending',
        ]);

        return redirect()->route('items.index')->with('success', 'Barang berhasil diupload!');
    }

    public function show($id)
    {
        $item = Item::where('user_id', Auth::id())->findOrFail($id);
        $item->increment('views_count');
        return view('user.items.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Item::where('user_id', Auth::id())->where('status', 'pending')->findOrFail($id);
        return view('user.items.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'category' => 'required',
            'type' => 'required',
            'condition' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'type' => $request->type,
            'condition' => $request->condition,
            'legacy_message' => $request->legacy_message,
            'price' => ($request->type === 'sale') ? $request->price : 0,
        ];

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('items', 'public');
            }
            $data['images'] = $images;
        }

        $item->update($data);
        return redirect()->route('items.index')->with('success', 'Barang diupdate.');
    }

    public function destroy($id)
    {
        $item = Item::where('user_id', Auth::id())->findOrFail($id);
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Barang dihapus.');
    }
}