@extends('layouts.master')
@section('title', 'Masuk')

@push('styles')
    <style>
        .auth-wrap {
            min-height: calc(100vh - 56px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-box {
            width: 100%;
            max-width: 400px;
            background: var(--color-surface-1);
            border: 2px solid var(--color-border-strong);
            border-radius: 16px;
            padding: 2.5rem 2rem;
            box-shadow: 4px 4px 0 var(--color-border);
        }

        .input-row {
            margin-bottom: 1rem;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-in {
            animation: slideIn 0.45s ease both;
        }
    </style>
@endpush

@section('content')
    <div class="auth-wrap">
        <div class="auth-box slide-in">

            {{-- Logo --}}
            <div class="flex items-center gap-2 mb-7">
                <span
                    style="width:32px;height:32px;background:var(--color-accent);border-radius:9px;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:0.9rem;">S</span>
                <span class="font-bold" style="color:var(--color-text-secondary);">SIGAP / Masuk</span>
            </div>

            <h2 class="font-extrabold text-2xl mb-1" style="color: var(--color-text-primary);">Selamat datang kembali</h2>
            <p class="text-secondary text-sm mb-6">Masuk untuk melanjutkan ke akun Anda</p>

            @if(session()->has('loginError'))
                <div class="alert-error mb-4"><i class="bi bi-exclamation-triangle mr-1"></i>{{ session('loginError') }}</div>
            @endif

            <form action="{{ route('login.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="input-row">
                    <label class="label">Username</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-person iicon"></i>
                        <input type="text" name="username" class="input" placeholder="Masukkan username"
                            value="{{ old('username') }}" required autofocus>
                    </div>
                    @error('username')
                        <p class="text-danger text-xs mt-1"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <div class="input-row">
                    <label class="label">Password</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-lock iicon"></i>
                        <input type="password" name="password" class="input" placeholder="Kata sandi" required>
                    </div>
                    @error('password')
                        <p class="text-danger text-xs mt-1"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-full mt-2">
                    <i class="bi bi-arrow-right-circle"></i> Masuk Sekarang
                </button>
            </form>

            <p class="text-center text-sm text-secondary mt-5">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-accent font-semibold" style="text-decoration:none;">Daftar di
                    sini</a>
            </p>
        </div>
    </div>
@endsection