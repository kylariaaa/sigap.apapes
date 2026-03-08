@extends('layouts.master')
@section('title', 'Login Pengguna')

@push('styles')
    <style>
        .auth-bg-blob-1 {
            background: radial-gradient(ellipse 60% 60% at 30% 50%, rgba(0, 212, 255, 0.06), transparent);
        }

        .auth-bg-blob-2 {
            background: radial-gradient(ellipse 50% 50% at 70% 50%, rgba(168, 85, 247, 0.06), transparent);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-up {
            animation: slideUp 0.5s ease both;
        }

        .input-icon-wrap {
            position: relative;
        }

        .input-icon-wrap .icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-muted-text);
            pointer-events: none;
            z-index: 5;
            font-size: 0.95rem;
        }

        .input-icon-wrap .input-futur {
            padding-left: 2.75rem;
        }
    </style>
@endpush

@section('content')
    <div class="relative flex items-center justify-center min-h-[calc(100vh-120px)] -mt-8 py-8">

        {{-- Background blobs --}}
        <div class="auth-bg-blob-1 fixed inset-0 pointer-events-none"></div>
        <div class="auth-bg-blob-2 fixed inset-0 pointer-events-none"></div>

        <div class="slide-up relative z-10 w-full max-w-md">
            <div class="panel p-8" style="box-shadow: 0 25px 60px rgba(0,0,0,0.4), 0 0 0 1px rgba(0,212,255,0.06);">

                {{-- Logo --}}
                <div class="text-center mb-7">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl text-3xl mb-3"
                        style="background: linear-gradient(135deg, var(--color-neon-cyan), var(--color-neon-purple)); box-shadow: 0 0 30px rgba(0,212,255,0.25);">
                        ⚡
                    </div>
                    <h1 class="text-xl font-black text-white">Selamat Datang</h1>
                    <p class="text-muted-futur text-sm mt-1">Masuk ke akun SIGAP Anda</p>
                </div>

                {{-- Session error --}}
                @if(session()->has('loginError'))
                    <div class="flash-error mb-5">
                        <i class="bi bi-exclamation-triangle mr-2"></i>{{ session('loginError') }}
                    </div>
                @endif

                <form action="{{ route('login.store') }}" method="POST" autocomplete="off">
                    @csrf

                    {{-- Username --}}
                    <div class="mb-4">
                        <label class="label-futur">Username</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-person icon"></i>
                            <input type="text" name="username" class="input-futur" placeholder="Masukkan username"
                                value="{{ old('username') }}" required autofocus>
                        </div>
                        @error('username')
                            <p class="text-neon-red text-xs mt-1 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-6">
                        <label class="label-futur">Password</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-lock icon"></i>
                            <input type="password" name="password" class="input-futur" placeholder="Masukkan password"
                                required>
                        </div>
                        @error('password')
                            <p class="text-neon-red text-xs mt-1 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="btn-neon w-full inline-flex items-center justify-center gap-2 py-3 text-sm">
                        <i class="bi bi-box-arrow-in-right"></i> MASUK SEKARANG
                    </button>
                </form>

                {{-- Divider --}}
                <div class="flex items-center gap-3 my-5">
                    <div class="flex-1 h-px bg-white/10"></div>
                    <span class="text-muted-futur text-xs">atau</span>
                    <div class="flex-1 h-px bg-white/10"></div>
                </div>

                <p class="text-center text-sm text-muted-futur">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-neon-cyan font-semibold no-underline hover:underline">
                        Daftar di sini
                    </a>
                </p>

            </div>
        </div>
    </div>
@endsection