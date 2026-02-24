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
    Route::get('/catalog', [Api\CatalogController::class, 'index']);
    Route::get('/catalog/{id}', [Api\CatalogController::class, 'show']);
    Route::get('/categories', [Api\CatalogController::class, 'categories']);
    Route::get('/schools', [Api\CatalogController::class, 'schools']);
    Route::get('/featured-items', [Api\CatalogController::class, 'featured']);

    // Search
    Route::get('/search', [Api\SearchController::class, 'index']);
    Route::get('/search/suggestions', [Api\SearchController::class, 'suggestions']);

    // Statistics
    Route::get('/stats', [Api\StatsController::class, 'index']);
});

// ==================== PROTECTED API ====================
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // User
    Route::get('/user', [Api\UserController::class, 'show']);
    Route::put('/user', [Api\UserController::class, 'update']);
    Route::post('/user/photo', [Api\UserController::class, 'uploadPhoto']);
    Route::delete('/user/photo', [Api\UserController::class, 'removePhoto']);

    // Items
    Route::get('/items', [Api\ItemController::class, 'index']);
    Route::post('/items', [Api\ItemController::class, 'store']);
    Route::get('/items/{id}', [Api\ItemController::class, 'show']);
    Route::put('/items/{id}', [Api\ItemController::class, 'update']);
    Route::delete('/items/{id}', [Api\ItemController::class, 'destroy']);
    Route::post('/items/{id}/toggle-status', [Api\ItemController::class, 'toggleStatus']);

    // Wishlist
    Route::get('/wishlist', [Api\WishlistController::class, 'index']);
    Route::post('/wishlist/toggle/{item_id}', [Api\WishlistController::class, 'toggle']);
    Route::delete('/wishlist/{id}', [Api\WishlistController::class, 'destroy']);

    // Transactions
    Route::get('/transactions', [Api\TransactionController::class, 'index']);
    Route::post('/transactions', [Api\TransactionController::class, 'store']);
    Route::get('/transactions/{id}', [Api\TransactionController::class, 'show']);
    Route::post('/transactions/{id}/payment', [Api\TransactionController::class, 'payment']);
    Route::post('/transactions/{id}/confirm', [Api\TransactionController::class, 'confirm']);
    Route::post('/transactions/{id}/cancel', [Api\TransactionController::class, 'cancel']);

    // Notifications
    Route::get('/notifications', [Api\NotificationController::class, 'index']);
    Route::get('/notifications/unread', [Api\NotificationController::class, 'unread']);
    Route::post('/notifications/{id}/read', [Api\NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [Api\NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [Api\NotificationController::class, 'destroy']);
});

// ==================== WEBHOOKS ====================
Route::prefix('webhooks')->group(function () {
    Route::post('/payment/qris', [Api\WebhookController::class, 'qris']);
    Route::post('/payment/qris/callback', [Api\WebhookController::class, 'qrisCallback']);
});
