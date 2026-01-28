<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;

class AuthController extends Controller
{
    // 1. Tampilkan Form Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        // Validasi Input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Cek ke Database
        if (Auth::attempt($credentials)) {
            // Regenerasi session (keamanan)
            $request->session()->regenerate();

            // Cek Role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }else {
                return redirect()->route('user.lapor');
        }
    }

        // Jika Login Gagal
        return back()->withErrors([
            'email' => 'Email atau password salah!',
        ])->onlyInput('email');
    }

    // 3. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function dashboard()
    {
        $reports = Report::orderBy('created_at', 'desc')->get();
        return view('admin.dashboard', compact('reports'));
    }
}
