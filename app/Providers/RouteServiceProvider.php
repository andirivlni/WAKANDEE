<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * The path to the admin dashboard.
     *
     * @var string
     */
    public const ADMIN_HOME = '/admin';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('transactions', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('uploads', function (Request $request) {
            return Limit::perHour(20)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // Web routes
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // API routes
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // Admin routes (separate file for better organization)
            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        });

        // Route model binding customizations
        $this->configureRouteModelBindings();
    }

    /**
     * Configure custom route model bindings.
     */
    protected function configureRouteModelBindings(): void
    {
        // Bind item by id only for approved items in public catalog
        Route::bind('catalog_item', function ($value) {
            return \App\Models\Item::where('id', $value)
                ->where('status', 'approved')
                ->firstOrFail();
        });

        // Bind item for user routes - only user's own items
        Route::bind('user_item', function ($value) {
            return \App\Models\Item::where('id', $value)
                ->where('user_id', auth()->id())
                ->firstOrFail();
        });

        // Bind transaction for user routes - only user's own transactions
        Route::bind('user_transaction', function ($value) {
            return \App\Models\Transaction::where('id', $value)
                ->where(function ($query) {
                    $query->where('buyer_id', auth()->id())
                        ->orWhere('seller_id', auth()->id());
                })
                ->firstOrFail();
        });

        // Bind item for admin moderation
        Route::bind('moderation_item', function ($value) {
            return \App\Models\Item::where('id', $value)
                ->whereIn('status', ['pending', 'approved', 'rejected'])
                ->firstOrFail();
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->input('email') ?: $request->ip());
        });

        RateLimiter::for('moderation', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });
    }
}
