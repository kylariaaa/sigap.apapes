@extends('layouts.master')
@section('title', 'Daftar Akun')

@push('styles')
    <style>
        .auth-center-wrap {
            min-height: calc(100vh - 120px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-form-box {
            width: 100%;
            max-width: 420px;
            background: var(--color-surface-1);
            border: 1px solid var(--color-border);
            border-radius: 1rem;
            padding: 2.5rem 2rem;
        }

        .input-row {
            margin-bottom: 1rem;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-16px);
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
    <div class="auth-center-wrap">
        <div class="auth-form-box slide-in">
            <div class="flex items-center gap-2 mb-7">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-sm font-black"
                    style="background: var(--color-accent); color: #fff;">S</span>
                <span class="font-bold text-secondary">SIGAP / Daftar</span>
            </div>

            <h2 class="font-extrabold text-2xl mb-1" style="color: var(--color-text-primary);">Buat Akun Warga</h2>
            <p class="text-secondary text-sm mb-6">Isi data diri sesuai KTP untuk mendaftar</p>

            @if ($errors->any())
                <div class="alert-error mb-4">
                    <i class="bi bi-exclamation-triangle mr-1"></i>
                    <ul class="mt-1 mb-0 list-disc ps-4 text-sm">
                        @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST">
                @csrf
                {{-- NIK & Telp --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="input-row">
                        <label class="label">NIK <span class="text-danger">*</span></label>
                        <input type="number" name="nik" class="input" id="nik" placeholder="16 digit"
                            value="{{ old('nik') }}" required>
                    </div>
                    <div class="input-row">
                        <label class="label">No. Telepon <span class="text-danger">*</span></label>
                        <input type="text" name="telp" class="input" placeholder="08xx" value="{{ old('telp') }}" required>
                    </div>
                </div>
                {{-- Nama --}}
                <div class="input-row">
                    <label class="label">Nama Lengkap <span class="text-danger">*</span></label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-person iicon"></i>
                        <input type="text" name="name" class="input" placeholder="Sesuai KTP" value="{{ old('name') }}"
                            required>
                    </div>
                </div>
                {{-- Email --}}
                <div class="input-row">
                    <label class="label">Email <span class="text-dim">(opsional)</span></label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-envelope iicon"></i>
                        <input type="email" name="email" class="input" placeholder="email@contoh.com"
                            value="{{ old('email') }}">
                    </div>
                </div>
                {{-- Username & Password --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="input-row">
                        <label class="label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="input" placeholder="Unik" value="{{ old('username') }}"
                            required>
                    </div>
                    <div class="input-row">
                        <label class="label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="input" placeholder="Min. 6 karakter" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-full mt-1">
                    <i class="bi bi-person-check"></i> Daftar Sekarang
                </button>
            </form>

            <p class="text-center text-sm text-secondary mt-5">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-accent font-semibold" style="text-decoration:none;">Masuk di
                    sini</a>
            </p>
        </div>
    </div>
@endsection