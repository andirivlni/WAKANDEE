<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'email';
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@belajar\.id$/'],
            'password' => 'required|string',
        ], [
            'email.regex' => 'Email harus menggunakan domain @belajar.id',
        ]);
    }

    protected function credentials(Request $request)
    {
        return [
            'email' => $request->email,
            'password' => $request->password,
            'is_active' => true
        ];
    }

    /**
     * The user has been authenticated.
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'admin') {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/dashboard');
    }
}
