<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();

                return redirect()->route('login')
                    ->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi admin.');
            }

            // Check if email is from belajar.id (additional validation)
            if ($user->role === 'user' && !preg_match('/^[a-zA-Z0-9._%+-]+@belajar\.id$/', $user->email)) {
                Auth::logout();

                return redirect()->route('login')
                    ->with('error', 'Email harus menggunakan domain @belajar.id.');
            }
        }

        return $next($request);
    }
}
