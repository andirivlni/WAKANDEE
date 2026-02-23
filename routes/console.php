<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ==================== CUSTOM COMMANDS ====================

// Cleanup pending items older than 30 days
Artisan::command('items:cleanup-pending', function () {
    $this->info('Cleaning up pending items...');

    $count = App\Models\Item::where('status', 'pending')
        ->where('created_at', '<', now()->subDays(30))
        ->delete();

    $this->info("Deleted {$count} pending items.");
})->purpose('Delete pending items older than 30 days');

// Cleanup pending transactions older than 7 days
Artisan::command('transactions:cleanup-pending', function () {
    $this->info('Cleaning up pending transactions...');

    $count = App\Models\Transaction::where('payment_status', 'pending')
        ->where('created_at', '<', now()->subDays(7))
        ->update(['payment_status' => 'cancelled']);

    $this->info("Cancelled {$count} pending transactions.");
})->purpose('Cancel pending transactions older than 7 days');

// Generate sitemap
Artisan::command('sitemap:generate', function () {
    $this->info('Generating sitemap...');

    // Sitemap generation logic here
    $sitemap = [];

    // Add static pages
    $sitemap[] = ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'];
    $sitemap[] = ['loc' => url('/catalog'), 'priority' => '0.9', 'changefreq' => 'hourly'];
    $sitemap[] = ['loc' => url('/tentang'), 'priority' => '0.5', 'changefreq' => 'monthly'];
    $sitemap[] = ['loc' => url('/faq'), 'priority' => '0.5', 'changefreq' => 'monthly'];
    $sitemap[] = ['loc' => url('/cara-kerja'), 'priority' => '0.6', 'changefreq' => 'monthly'];

    // Add approved items
    $items = App\Models\Item::where('status', 'approved')->latest()->limit(1000)->get();
    foreach ($items as $item) {
        $sitemap[] = [
            'loc' => url('/catalog/' . $item->id),
            'priority' => '0.8',
            'changefreq' => 'weekly',
            'lastmod' => $item->updated_at->toW3cString()
        ];
    }

    // Generate XML
    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    foreach ($sitemap as $url) {
        $xml .= '<url>';
        $xml .= '<loc>' . $url['loc'] . '</loc>';
        $xml .= '<priority>' . $url['priority'] . '</priority>';
        $xml .= '<changefreq>' . $url['changefreq'] . '</changefreq>';
        if (isset($url['lastmod'])) {
            $xml .= '<lastmod>' . $url['lastmod'] . '</lastmod>';
        }
        $xml .= '</url>';
    }

    $xml .= '</urlset>';

    // Save to file
    Storage::disk('public')->put('sitemap.xml', $xml);

    $this->info('Sitemap generated successfully!');
})->purpose('Generate sitemap.xml');

// ==================== SCHEDULED TASKS ====================
Schedule::command('items:cleanup-pending')->daily();
Schedule::command('transactions:cleanup-pending')->daily();
Schedule::command('sitemap:generate')->weekly();
