<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validate the email for the given request.
     */
    protected function validateEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%+-]+@belajar\.id$/'
            ]
        ], [
            'email.regex' => 'Email harus menggunakan domain @belajar.id',
        ]);
    }

    /**
     * Get the response for a successful password reset link.
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return back()->with('status', 'Link reset password telah dikirim ke email Anda!');
    }

    /**
     * Get the response for a failed password reset link.
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()
            ->withErrors(['email' => 'Email tidak ditemukan atau tidak valid'])
            ->withInput($request->only('email'));
    }
}
