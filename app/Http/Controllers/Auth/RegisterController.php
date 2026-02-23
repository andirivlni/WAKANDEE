<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

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
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@belajar\.id$/'
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'school' => ['required', 'string', 'max:255'],
            'grade' => ['required', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:20'],
        ], [
            'email.regex' => 'Email harus menggunakan domain @belajar.id',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'school.required' => 'Asal sekolah wajib diisi',
            'grade.required' => 'Kelas wajib diisi',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'school' => $data['school'],
            'grade' => $data['grade'],
            'phone' => $data['phone'] ?? null,
            'role' => 'user',
            'is_active' => true,
        ]);
    }

    /**
     * The user has been registered.
     */
    protected function registered(Request $request, $user)
    {
        return redirect($this->redirectTo)->with('success', 'Registrasi berhasil! Selamat datang di WAKANDE.');
    }
}
