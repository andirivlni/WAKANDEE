<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
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
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%')
                  ->orWhere('school', 'LIKE', '%' . $search . '%');
            });
        }

        $users = $query->withCount(['items', 'soldTransactions', 'boughtTransactions'])
            ->latest()
            ->paginate(20);

        // Stats
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'active_users' => User::where('role', 'user')->where('is_active', true)->count(),
            'inactive_users' => User::where('role', 'user')->where('is_active', false)->count(),
            'total_admins' => User::where('role', 'admin')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::withCount(['items', 'soldTransactions', 'boughtTransactions'])
            ->findOrFail($id);

        // User's items
        $items = Item::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // User's transactions as buyer
        $bought_transactions = Transaction::with(['item', 'seller'])
            ->where('buyer_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // User's transactions as seller
        $sold_transactions = Transaction::with(['item', 'buyer'])
            ->where('seller_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // Stats
        $stats = [
            'total_items' => Item::where('user_id', $user->id)->count(),
            'approved_items' => Item::where('user_id', $user->id)->where('status', 'approved')->count(),
            'pending_items' => Item::where('user_id', $user->id)->where('status', 'pending')->count(),
            'total_sold' => Transaction::where('seller_id', $user->id)->where('payment_status', 'completed')->count(),
            'total_bought' => Transaction::where('buyer_id', $user->id)->where('payment_status', 'completed')->count(),
            'total_spent' => Transaction::where('buyer_id', $user->id)->where('payment_status', 'completed')->sum('amount'),
            'total_earned' => Transaction::where('seller_id', $user->id)->where('payment_status', 'completed')->sum('amount'),
        ];

        return view('admin.users.show', compact('user', 'items', 'bought_transactions', 'sold_transactions', 'stats'));
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        // Create notification for user
        Notification::create([
            'user_id' => $user->id,
            'type' => 'account_status',
            'title' => 'Status Akun Diubah',
            'message' => 'Akun Anda telah ' . $status . ' oleh admin.',
        ]);

        return back()->with('success', 'Status pengguna berhasil ' . $status . '.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        DB::beginTransaction();

        try {
            // Delete user's items images
            foreach ($user->items as $item) {
                $images = json_decode($item->images, true) ?? [];
                foreach ($images as $image) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($image);
                }
            }

            // Delete user
            $user->delete();

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'Pengguna berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Create admin user form.
     */
    public function createAdmin()
    {
        return view('admin.users.create-admin');
    }

    /**
     * Store admin user.
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin baru berhasil ditambahkan.');
    }
}
