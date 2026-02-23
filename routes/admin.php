<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| This file contains all admin-specific routes.
| Prefix: /admin
| Middleware: auth, admin
|
*/

Route::middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/chart-data', [Admin\DashboardController::class, 'getChartData'])->name('admin.chart-data');

    // Moderation
    Route::prefix('moderation')->name('admin.moderation.')->group(function () {
        Route::get('/', [Admin\ModerationController::class, 'index'])->name('index');
        Route::get('/pending', [Admin\ModerationController::class, 'pending'])->name('pending');
        Route::get('/{id}', [Admin\ModerationController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [Admin\ModerationController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [Admin\ModerationController::class, 'reject'])->name('reject');
        Route::post('/bulk-approve', [Admin\ModerationController::class, 'bulkApprove'])->name('bulk-approve');
        Route::post('/bulk-reject', [Admin\ModerationController::class, 'bulkReject'])->name('bulk-reject');
        Route::get('/pending/count', [Admin\ModerationController::class, 'pendingCount'])->name('pending.count');
    });

    // User Management
    Route::prefix('users')->name('admin.users.')->group(function () {
        Route::get('/', [Admin\UserManagementController::class, 'index'])->name('index');
        Route::get('/export', [Admin\UserManagementController::class, 'export'])->name('export');
        Route::get('/create-admin', [Admin\UserManagementController::class, 'createAdmin'])->name('create-admin');
        Route::post('/store-admin', [Admin\UserManagementController::class, 'storeAdmin'])->name('store-admin');
        Route::get('/{id}', [Admin\UserManagementController::class, 'show'])->name('show');
        Route::post('/{id}/toggle-status', [Admin\UserManagementController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{id}', [Admin\UserManagementController::class, 'destroy'])->name('destroy');
    });

    // Transaction Monitoring
    Route::prefix('transactions')->name('admin.transactions.')->group(function () {
        Route::get('/', [Admin\TransactionMonitorController::class, 'index'])->name('index');
        Route::get('/export', [Admin\TransactionMonitorController::class, 'export'])->name('export');
        Route::get('/{id}', [Admin\TransactionMonitorController::class, 'show'])->name('show');
        Route::post('/{id}/complete', [Admin\TransactionMonitorController::class, 'complete'])->name('complete');
        Route::post('/{id}/cancel', [Admin\TransactionMonitorController::class, 'cancel'])->name('cancel');
    });

    // Settings
    Route::prefix('settings')->name('admin.settings.')->group(function () {
        Route::get('/', [Admin\SettingController::class, 'index'])->name('index');
        Route::put('/', [Admin\SettingController::class, 'update'])->name('update');
    });

    // Reports
    Route::prefix('reports')->name('admin.reports.')->group(function () {
        Route::get('/transactions', [Admin\ReportController::class, 'transactions'])->name('transactions');
        Route::get('/users', [Admin\ReportController::class, 'users'])->name('users');
        Route::get('/items', [Admin\ReportController::class, 'items'])->name('items');
        Route::get('/export/{type}', [Admin\ReportController::class, 'export'])->name('export');
    });

    // System
    Route::prefix('system')->name('admin.system.')->group(function () {
        Route::get('/logs', [Admin\SystemController::class, 'logs'])->name('logs');
        Route::post('/cache/clear', [Admin\SystemController::class, 'clearCache'])->name('cache.clear');
        Route::get('/info', [Admin\SystemController::class, 'info'])->name('info');
    });
});

// ==================== ADMIN AUTH FALLBACK ====================
Route::fallback(function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'admin'])->name('admin.fallback');
