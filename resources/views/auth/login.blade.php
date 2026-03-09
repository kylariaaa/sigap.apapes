@extends('layouts.master')
@section('title', 'Masuk')

@push('styles')
    <style>
        .auth-split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: calc(100vh - 56px);
            margin: -2rem -1rem;
        }

        @media (max-width: 768px) {
            .auth-split {
                grid-template-columns: 1fr;
            }

            .auth-decor {
                display: none;
            }
        }

        .auth-form-side {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            background: var(--color-surface-0);
        }

        .auth-decor {
            background: var(--color-surface-1);
            border-left: 1px solid var(--color-border);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .decor-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid var(--color-accent-border);
            animation: pulse-ring 4s ease-in-out infinite;
        }

        @keyframes pulse-ring {

            0%,
            100% {
                opacity: 0.4;
                transform: scale(1);
            }

            50% {
                opacity: 0.15;
                transform: scale(1.05);
            }
        }

        .auth-form-box {
            width: 100%;
            max-width: 360px;
        }

        .input-row {
            margin-bottom: 1rem;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .slide-in {
            animation: slideIn 0.45s ease both;
        }
    </style>
@endpush

@section('content')
    <div class="auth-split">

        {{-- ── Left: Form ── --}}
        <div class="auth-form-side">
            <div class="auth-form-box slide-in">

                {{-- Logo --}}
                <div class="flex items-center gap-2 mb-8">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-sm font-black"
                        style="background: var(--color-accent); color: #fff;">S</span>
                    <span class="font-bold text-secondary">SIGAP / Masuk</span>
                </div>

                <h2 class="font-extrabold text-2xl mb-1" style="color: var(--color-text-primary);">Selamat datang kembali
                </h2>
                <p class="text-secondary text-sm mb-6">Masuk untuk melanjutkan ke akun Anda</p>

                @if(session()->has('loginError'))
                    <div class="alert-error mb-4"><i class="bi bi-exclamation-triangle mr-1"></i>{{ session('loginError') }}
                    </div>
                @endif

                <form action="{{ route('login.store') }}" method="POST" autocomplete="off">
                    @csrf

                    <div class="input-row">
                        <label class="label">Username</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-person iicon"></i>
                            <input type="text" name="username" class="input" id="username" placeholder="Masukkan username"
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
                    <a href="{{ route('register') }}" class="text-accent font-semibold" style="text-decoration:none;">Daftar
                        di sini</a>
                </p>

            </div>
        </div>

        {{-- ── Right: Decorative ── --}}
        <div class="auth-decor">
            <div class="decor-ring" style="width:300px; height:300px; animation-delay:0s;"></div>
            <div class="decor-ring" style="width:450px; height:450px; animation-delay:1s;"></div>
            <div class="decor-ring" style="width:600px; height:600px; animation-delay:2s;"></div>

            <div class="relative z-10 text-center">
                <div class="text-5xl mb-4">🏛️</div>
                <h3 class="font-bold text-lg mb-2" style="color: var(--color-text-primary);">Pelayanan Publik Digital</h3>
                <p class="text-secondary text-sm max-w-xs" style="line-height:1.7;">
                    Laporan Anda adalah kontribusi nyata untuk lingkungan yang lebih baik.
                    Bersama SIGAP, suara warga didengar.
                </p>
                <div class="flex justify-center gap-3 mt-6 flex-wrap">
                    @foreach(['Cepat', 'Aman', 'Transparan'] as $p)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold"
                            style="background: var(--color-accent-dim); color: var(--color-accent-light); border: 1px solid var(--color-accent-border);">
                            {{ $p }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
@endsection