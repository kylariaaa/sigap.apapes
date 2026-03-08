@extends('layouts.master')
@section('title', 'Daftar Akun Warga')

@push('styles')
    <style>
        .auth-bg-blob-1 {
            background: radial-gradient(ellipse 60% 60% at 70% 40%, rgba(0, 212, 255, 0.06), transparent);
        }

        .auth-bg-blob-2 {
            background: radial-gradient(ellipse 50% 50% at 30% 60%, rgba(168, 85, 247, 0.06), transparent);
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

        <div class="auth-bg-blob-1 fixed inset-0 pointer-events-none"></div>
        <div class="auth-bg-blob-2 fixed inset-0 pointer-events-none"></div>

        <div class="slide-up relative z-10 w-full max-w-lg">
            <div class="panel p-8" style="box-shadow: 0 25px 60px rgba(0,0,0,0.4), 0 0 0 1px rgba(168,85,247,0.06);">

                {{-- Logo --}}
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl text-3xl mb-3"
                        style="background: linear-gradient(135deg, var(--color-neon-purple), var(--color-neon-cyan)); box-shadow: 0 0 30px rgba(168,85,247,0.25);">
                        🛡️
                    </div>
                    <h1 class="text-xl font-black text-white">Buat Akun Baru</h1>
                    <p class="text-muted-futur text-sm mt-1">Daftar sebagai warga SIGAP</p>
                </div>

                {{-- Error block --}}
                @if ($errors->any())
                    <div class="flash-error mb-5">
                        <i class="bi bi-exclamation-triangle mr-2"></i>
                        <strong>Perbaiki kesalahan:</strong>
                        <ul class="mt-1 ps-4 list-disc text-sm">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="flash-success mb-5">
                        <i class="bi bi-check-circle"></i>{{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('register.store') }}" method="POST">
                    @csrf

                    {{-- NIK & Telp row --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="label-futur">NIK</label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-card-text icon"></i>
                                <input type="number" name="nik" class="input-futur" placeholder="16 Digit NIK"
                                    value="{{ old('nik') }}" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="label-futur">No. Telepon</label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-telephone icon"></i>
                                <input type="text" name="telp" class="input-futur" placeholder="08xxxxxxxxxx"
                                    value="{{ old('telp') }}" required>
                            </div>
                        </div>
                    </div>

                    {{-- Nama --}}
                    <div class="mb-4">
                        <label class="label-futur">Nama Lengkap</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-person icon"></i>
                            <input type="text" name="name" class="input-futur" placeholder="Sesuai KTP"
                                value="{{ old('name') }}" required>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="label-futur">Email</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-envelope icon"></i>
                            <input type="email" name="email" class="input-futur" placeholder="email@contoh.com"
                                value="{{ old('email') }}">
                        </div>
                    </div>

                    {{-- Username & Password row --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="label-futur">Username</label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-at icon"></i>
                                <input type="text" name="username" class="input-futur" placeholder="Username unik"
                                    value="{{ old('username') }}" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="label-futur">Password</label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-lock icon"></i>
                                <input type="password" name="password" class="input-futur" placeholder="Min. 6 karakter"
                                    required>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="btn-neon w-full inline-flex items-center justify-center gap-2 py-3 text-sm mt-1"
                        style="background: linear-gradient(135deg, var(--color-neon-purple), var(--color-neon-cyan));">
                        <i class="bi bi-person-check"></i> DAFTAR SEKARANG
                    </button>
                </form>

                <p class="text-center text-sm text-muted-futur mt-5">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-neon-cyan font-semibold no-underline hover:underline">
                        Login di sini
                    </a>
                </p>

            </div>
        </div>
    </div>
@endsection