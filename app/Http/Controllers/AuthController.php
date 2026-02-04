<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\User;

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
            'email'    => 'email|required',
            'password' => 'required',
        ]);

        // Cek ke Database
        if (Auth::attempt($credentials)) {
            // Regenerasi session (keamanan)
            $request->session()->regenerate();

            // Cek Role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
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

    // 1. Menampilkan Form Register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // 2. Proses Registrasi Warga Baru
    public function register(Request $request)
    {
        // VALIDASI INPUT
        $data = $request->validate([
            'nik'      => 'required|numeric|unique:users,nik',
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'email'    => 'nullable|email|unique:users,email',
            'telp'     => 'required|numeric',
        ]);

        // SIMPAN KE DATABASE
        User::create([
            'nik'      => $data['nik'],
            'name'     => $data['name'],
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']), // Enkripsi password
            'telp'     => $data['telp'],
            'role'     => 'masyarakat', // Role default
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Akun berhasil dibuat! Silakan login.');
    }
}
