@extends('layouts.master')
@section('title', 'Daftar Akun')

@push('styles')
<style>
.auth-split {
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: calc(100vh - 56px);
    margin: -2rem -1rem;
}
@media (max-width: 768px) {
    .auth-split { grid-template-columns: 1fr; }
    .auth-decor-reg { display: none; }
}
.auth-form-side {
    display: flex; align-items: center; justify-content: center;
    padding: 2.5rem 2rem;
    background: var(--color-surface-0);
    overflow-y: auto;
}
.auth-decor-reg {
    background: var(--color-surface-2);
    border-left: 1px solid var(--color-border);
    display: flex; flex-direction: column;
    justify-content: center;
    padding: 3rem 2.5rem;
}
.auth-form-box { width: 100%; max-width: 380px; }
.input-row { margin-bottom: 1rem; }
@keyframes slideIn { from { opacity:0; transform: translateX(-20px); } to { opacity:1; transform: translateX(0); } }
.slide-in { animation: slideIn 0.45s ease both; }
.step-item {
    display: flex; align-items: flex-start; gap: 1rem;
    padding: 0.9rem 0;
    border-bottom: 1px solid var(--color-border);
}
.step-item:last-child { border-bottom: none; }
.step-num {
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--color-accent-dim);
    color: var(--color-accent-light);
    font-size: 0.75rem;
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
</style>
@endpush

@section('content')
<div class="auth-split">

    {{-- ── Left: Form ── --}}
    <div class="auth-form-side">
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
                        <input type="number" name="nik" class="input" id="nik"
                               placeholder="16 digit" value="{{ old('nik') }}" required>
                    </div>
                    <div class="input-row">
                        <label class="label">No. Telepon <span class="text-danger">*</span></label>
                        <input type="text" name="telp" class="input"
                               placeholder="08xx" value="{{ old('telp') }}" required>
                    </div>
                </div>
                {{-- Nama --}}
                <div class="input-row">
                    <label class="label">Nama Lengkap <span class="text-danger">*</span></label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-person iicon"></i>
                        <input type="text" name="name" class="input" placeholder="Sesuai KTP"
                               value="{{ old('name') }}" required>
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
                        <input type="text" name="username" class="input"
                               placeholder="Unik" value="{{ old('username') }}" required>
                    </div>
                    <div class="input-row">
                        <label class="label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="input"
                               placeholder="Min. 6 karakter" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-full mt-1">
                    <i class="bi bi-person-check"></i> Daftar Sekarang
                </button>
            </form>

            <p class="text-center text-sm text-secondary mt-5">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-accent font-semibold" style="text-decoration:none;">Masuk di sini</a>
            </p>
        </div>
    </div>

    {{-- ── Right: Steps info ── --}}
    <div class="auth-decor-reg p-8 lg:p-12">
        <div class="font-bold text-sm mb-4 text-secondary uppercase tracking-widest">Cara bergabung</div>
        <div class="step-item">
            <span class="step-num">1</span>
            <div>
                <div class="font-semibold text-sm mb-0.5" style="color: var(--color-text-primary);">Daftarkan akun</div>
                <div class="text-secondary text-xs" style="line-height:1.6;">Isi formulir dengan data KTP yang valid</div>
            </div>
        </div>
        <div class="step-item">
            <span class="step-num">2</span>
            <div>
                <div class="font-semibold text-sm mb-0.5" style="color: var(--color-text-primary);">Tulis laporan</div>
                <div class="text-secondary text-xs" style="line-height:1.6;">Deskripsikan masalah dengan jelas + peta lokasi</div>
            </div>
        </div>
        <div class="step-item">
            <span class="step-num">3</span>
            <div>
                <div class="font-semibold text-sm mb-0.5" style="color: var(--color-text-primary);">Pantau progres</div>
                <div class="text-secondary text-xs" style="line-height:1.6;">Lacak status & baca balasan petugas kapan saja</div>
            </div>
        </div>
        <div class="mt-6 p-4 rounded-xl" style="background: var(--color-accent-dim); border: 1px solid var(--color-accent-border);">
            <div class="text-accent font-semibold text-sm mb-1"><i class="bi bi-phone mr-1"></i>Install sebagai Aplikasi</div>
            <div class="text-secondary text-xs" style="line-height:1.6;">
                Di browser Chrome/Safari, ketuk "Tambahkan ke layar utama" untuk menginstal SIGAP sebagai aplikasi native.
            </div>
        </div>
    </div>

</div>
@endsection