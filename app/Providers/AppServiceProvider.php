<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// Import class URL agar kita bisa memaksa skema HTTPS
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Memaksa aplikasi menggunakan HTTPS jika berada di lingkungan production.
         * Ini mencegah error "Mixed Content" saat deploy di Railway.
         */
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
