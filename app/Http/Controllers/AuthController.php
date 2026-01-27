<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            switch ($role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');

                case 'masyarakat':
                    return redirect()->route('user.lapor');

                // case 'petugas':
                //     return redirect()->route('petugas.dashboard');

                default:
                    // ⛔ ROLE TIDAK VALID → LOGOUTKAN
                    Auth::logout();
                    return redirect()->route('login')
                        ->withErrors(['email' => 'Role user tidak dikenali']);
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah!',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
