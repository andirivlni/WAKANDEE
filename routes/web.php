<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/items/create', [App\Http\Controllers\User\ItemController::class, 'create'])->name('items.create');
Route::post('/items', [App\Http\Controllers\User\ItemController::class, 'store'])->name('items.store');

// ==================== PUBLIC ROUTES ====================
Route::get('/', [App\Http\Controllers\User\HomeController::class, 'index'])->name('home');

Route::prefix('catalog')->name('catalog.')->group(function () {
    Route::get('/', [App\Http\Controllers\User\CatalogController::class, 'index'])->name('index');
    Route::get('/{id}', [App\Http\Controllers\User\CatalogController::class, 'show'])->name('show');
});

// ==================== AUTHENTICATION ROUTES ====================
Auth::routes(['verify' => true, 'reset' => false]);

// ==================== USER PROTECTED ROUTES ====================
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('items')->name('items.')->group(function () {
        Route::get('/', [App\Http\Controllers\User\ItemController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\User\ItemController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\User\ItemController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\User\ItemController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\User\ItemController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [App\Http\Controllers\User\TransactionController::class, 'index'])->name('index');
        Route::get('/checkout/{item_id}', [App\Http\Controllers\User\TransactionController::class, 'checkout'])->name('checkout');
        Route::post('/store', [App\Http\Controllers\User\TransactionController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\User\TransactionController::class, 'show'])->name('show');
        Route::get('/{id}/payment', [App\Http\Controllers\User\TransactionController::class, 'payment'])->name('payment');
        Route::post('/{id}/process-payment', [App\Http\Controllers\User\TransactionController::class, 'processPayment'])->name('process-payment');
        Route::get('/payment/success/{id}', [App\Http\Controllers\User\TransactionController::class, 'success'])->name('success');
        Route::post('/{id}/confirm', [App\Http\Controllers\User\TransactionController::class, 'confirmDelivery'])->name('confirm');
        Route::post('/{id}/cancel', [App\Http\Controllers\User\TransactionController::class, 'cancel'])->name('cancel');

        // TAMBAHKAN ROUTE INI UNTUK AJAX CONFIRM
        Route::post('/confirm/{id}', [App\Http\Controllers\User\TransactionController::class, 'confirm'])->name('confirm.ajax');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\User\ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [App\Http\Controllers\User\ProfileController::class, 'update'])->name('update');
        Route::put('/password', [App\Http\Controllers\User\ProfileController::class, 'updatePassword'])->name('password');
        Route::delete('/photo', [App\Http\Controllers\User\ProfileController::class, 'removePhoto'])->name('photo.remove');
    });

    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [App\Http\Controllers\User\WishlistController::class, 'index'])->name('index');
        Route::post('/toggle/{item_id}', [App\Http\Controllers\User\WishlistController::class, 'toggle'])->name('toggle');
        Route::delete('/{id}', [App\Http\Controllers\User\WishlistController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\User\NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [App\Http\Controllers\User\NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [App\Http\Controllers\User\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{id}', [App\Http\Controllers\User\NotificationController::class, 'destroy'])->name('destroy');
    });
});

// ==================== ADMIN ROUTES ====================
// Menggunakan middleware ['auth', 'admin']
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/chart-data', [App\Http\Controllers\Admin\DashboardController::class, 'getChartData'])->name('chart-data');

    Route::prefix('moderation')->name('moderation.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ModerationController::class, 'index'])->name('index');
        Route::get('/pending', [App\Http\Controllers\Admin\ModerationController::class, 'pending'])->name('pending');
        Route::get('/{id}', [App\Http\Controllers\Admin\ModerationController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [App\Http\Controllers\Admin\ModerationController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [App\Http\Controllers\Admin\ModerationController::class, 'reject'])->name('reject');
        Route::post('/bulk-approve', [App\Http\Controllers\Admin\ModerationController::class, 'bulkApprove'])->name('bulk-approve');
        Route::post('/bulk-reject', [App\Http\Controllers\Admin\ModerationController::class, 'bulkReject'])->name('bulk-reject');
        Route::get('/pending/count', [App\Http\Controllers\Admin\ModerationController::class, 'pendingCount'])->name('pending.count');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Admin\UserManagementController::class, 'show'])->name('show');
        Route::post('/{id}/toggle-status', [App\Http\Controllers\Admin\UserManagementController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{id}', [App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('destroy');
        Route::get('/export', [App\Http\Controllers\Admin\UserManagementController::class, 'export'])->name('export');
        Route::get('/create-admin', [App\Http\Controllers\Admin\UserManagementController::class, 'createAdmin'])->name('create-admin');
        Route::post('/store-admin', [App\Http\Controllers\Admin\UserManagementController::class, 'storeAdmin'])->name('store-admin');
    });

    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\TransactionMonitorController::class, 'index'])->name('index');

        // 1. PINDAHKAN EXPORT KE ATAS {id}
        Route::get('/export', [App\Http\Controllers\Admin\TransactionMonitorController::class, 'export'])->name('export');

        // 2. Rute dengan parameter dinamis ditaruh setelahnya
        Route::get('/{id}', [App\Http\Controllers\Admin\TransactionMonitorController::class, 'show'])->name('show');
        Route::post('/{id}/complete', [App\Http\Controllers\Admin\TransactionMonitorController::class, 'complete'])->name('complete');
        Route::post('/{id}/cancel', [App\Http\Controllers\Admin\TransactionMonitorController::class, 'cancel'])->name('cancel');

        // TAMBAHKAN ROUTE UNTUK AJAX (tanpa menghapus yang existing)
        Route::post('/complete/{id}', [App\Http\Controllers\Admin\TransactionMonitorController::class, 'complete'])->name('complete.ajax');
        Route::post('/cancel/{id}', [App\Http\Controllers\Admin\TransactionMonitorController::class, 'cancel'])->name('cancel.ajax');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('index');
        Route::put('/', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('update');
    });
});

// ==================== API ROUTES ====================
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/catalog', [App\Http\Controllers\Api\CatalogController::class, 'index'])->name('catalog');
    Route::get('/categories', [App\Http\Controllers\Api\CatalogController::class, 'categories'])->name('categories');

    Route::middleware('auth')->group(function () {
        Route::post('/wishlist/toggle/{item_id}', [App\Http\Controllers\Api\WishlistController::class, 'toggle'])->name('wishlist.toggle');
        Route::get('/notifications/unread', [App\Http\Controllers\Api\NotificationController::class, 'unread'])->name('notifications.unread');
        Route::get('/my-transactions', [TransactionController::class, 'index'])->name('transactions.index');
    });
});

// ==================== FALLBACK & STATIC ====================
Route::view('/tentang', 'pages.about')->name('about');
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
