@extends('layouts.master')

@section('title', 'Login Pengguna')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-4">

            {{-- Tambahan: Menampilkan Alert jika ada error global (misal: "Akun tidak ditemukan") --}}
            @if(session()->has('loginError'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('loginError') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    Silakan Masuk
                </div>

                <div class="card-body">
                    <form action="{{ route('login.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input
                                type="text"
                                name="username"
                                id="username"
                                {{-- Tambahkan logika class error di sini --}}
                                class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username') }}"
                                required autofocus
                            >

                            {{-- Menampilkan pesan error khusus Email --}}
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                {{-- Tambahkan logika class error di sini --}}
                                class="form-control @error('password') is-invalid @enderror"
                                required
                            >

                            {{-- Menampilkan pesan error khusus Password --}}
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            LOGIN
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
