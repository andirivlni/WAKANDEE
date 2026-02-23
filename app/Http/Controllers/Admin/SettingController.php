<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    /**
     * Display settings page.
     */
    public function index()
    {
        return view('admin.settings.index');
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_description' => 'nullable|string',
            'maintenance_mode' => 'nullable|boolean',
        ]);

        // Update .env or settings table
        $this->updateEnv([
            'APP_NAME' => $request->app_name,
            'APP_DESCRIPTION' => $request->app_description,
        ]);

        if ($request->has('maintenance_mode')) {
            if ($request->maintenance_mode) {
                Artisan::call('down');
            } else {
                Artisan::call('up');
            }
        }

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    /**
     * Update .env file
     */
    private function updateEnv($data)
    {
        $envFile = base_path('.env');
        $env = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            $env = preg_replace("/{$key}=.*/", "{$key}={$value}", $env);
        }

        file_put_contents($envFile, $env);
    }
}
