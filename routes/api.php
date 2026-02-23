<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// ==================== PUBLIC API ====================
Route::prefix('v1')->group(function () {

    // Catalog
    Route::get('/catalog', [Api\CatalogController::class, 'index'])->name('api.catalog.index');
    Route::get('/catalog/{id}', [Api\CatalogController::class, 'show'])->name('api.catalog.show');
    Route::get('/categories', [Api\CatalogController::class, 'categories'])->name('api.categories');
    Route::get('/schools', [Api\CatalogController::class, 'schools'])->name('api.schools');
    Route::get('/featured-items', [Api\CatalogController::class, 'featured'])->name('api.featured');

    // Search
    Route::get('/search', [Api\SearchController::class, 'index'])->name('api.search');
    Route::get('/search/suggestions', [Api\SearchController::class, 'suggestions'])->name('api.search.suggestions');

    // Statistics
    Route::get('/stats', [Api\StatsController::class, 'index'])->name('api.stats');
});

// ==================== PROTECTED API ====================
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // User
    Route::get('/user', [Api\UserController::class, 'show'])->name('api.user.show');
    Route::put('/user', [Api\UserController::class, 'update'])->name('api.user.update');
    Route::post('/user/photo', [Api\UserController::class, 'uploadPhoto'])->name('api.user.photo');
    Route::delete('/user/photo', [Api\UserController::class, 'removePhoto'])->name('api.user.photo.remove');

    // Items
    Route::apiResource('items', Api\ItemController::class)->except(['create', 'edit']);
    Route::post('/items/{id}/toggle-status', [Api\ItemController::class, 'toggleStatus'])->name('api.items.toggle');

    // Wishlist
    Route::get('/wishlist', [Api\WishlistController::class, 'index'])->name('api.wishlist.index');
    Route::post('/wishlist/toggle/{item_id}', [Api\WishlistController::class, 'toggle'])->name('api.wishlist.toggle');
    Route::delete('/wishlist/{id}', [Api\WishlistController::class, 'destroy'])->name('api.wishlist.destroy');

    // Transactions
    Route::get('/transactions', [Api\TransactionController::class, 'index'])->name('api.transactions.index');
    Route::post('/transactions', [Api\TransactionController::class, 'store'])->name('api.transactions.store');
    Route::get('/transactions/{id}', [Api\TransactionController::class, 'show'])->name('api.transactions.show');
    Route::post('/transactions/{id}/payment', [Api\TransactionController::class, 'payment'])->name('api.transactions.payment');
    Route::post('/transactions/{id}/confirm', [Api\TransactionController::class, 'confirm'])->name('api.transactions.confirm');
    Route::post('/transactions/{id}/cancel', [Api\TransactionController::class, 'cancel'])->name('api.transactions.cancel');

    // Notifications
    Route::get('/notifications', [Api\NotificationController::class, 'index'])->name('api.notifications.index');
    Route::get('/notifications/unread', [Api\NotificationController::class, 'unread'])->name('api.notifications.unread');
    Route::post('/notifications/{id}/read', [Api\NotificationController::class, 'markAsRead'])->name('api.notifications.read');
    Route::post('/notifications/mark-all-read', [Api\NotificationController::class, 'markAllAsRead'])->name('api.notifications.mark-all-read');
    Route::delete('/notifications/{id}', [Api\NotificationController::class, 'destroy'])->name('api.notifications.destroy');
});

// ==================== WEBHOOKS ====================
Route::prefix('webhooks')->group(function () {
    Route::post('/payment/qris', [Api\WebhookController::class, 'qris'])->name('webhook.qris');
    Route::post('/payment/qris/callback', [Api\WebhookController::class, 'qrisCallback'])->name('webhook.qris.callback');
});
