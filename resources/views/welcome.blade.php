@extends('layouts.master')
@section('title', 'Beranda SIGAP')

@push('styles')
    <style>
        .hero-grid {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(var(--color-border) 1px, transparent 1px),
                linear-gradient(90deg, var(--color-border) 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.5;
        }

        .feature-card {
            background: var(--color-surface-1);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            padding: 1.5rem;
            transition: border-color 0.2s, transform 0.2s;
        }

        .feature-card:hover {
            border-color: var(--color-accent-border);
            transform: translateY(-2px);
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: var(--color-accent-dim);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: var(--color-accent-light);
            margin-bottom: 1rem;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up {
            animation: fadeUp 0.6s ease both;
        }

        .fade-up-2 {
            animation: fadeUp 0.6s ease 0.1s both;
        }

        .fade-up-3 {
            animation: fadeUp 0.6s ease 0.2s both;
        }

        .fade-up-4 {
            animation: fadeUp 0.6s ease 0.3s both;
        }
    </style>
@endpush

@section('content')
    {{-- Hero --}}
    <div class="relative text-center py-16 lg:py-24 overflow-hidden rounded-2xl mb-8"
        style="background: var(--color-surface-1); border: 1px solid var(--color-border);">
        <div class="hero-grid"></div>
        <div class="relative z-10 px-4">
            <div class="fade-up inline-flex items-center gap-2 mb-5 px-3 py-1.5 rounded-full text-sm font-semibold"
                style="background: var(--color-accent-dim); color: var(--color-accent-light); border: 1px solid var(--color-accent-border);">
                <i class="bi bi-shield-check"></i>
                Layanan Pengaduan Masyarakat Digital
            </div>

            <h1 class="fade-up-2 font-extrabold mb-4"
                style="font-size: clamp(2rem, 5vw, 3.5rem); line-height: 1.15; color: var(--color-text-primary);">
                Sampaikan Aspirasi Anda<br>
                <span style="color: var(--color-accent-light);">Melalui SIGAP</span>
            </h1>

            <p class="fade-up-3 max-w-lg mx-auto text-base mb-8"
                style="color: var(--color-text-secondary); line-height: 1.7;">
                Platform pengaduan infrastruktur & layanan publik yang cepat, transparan,
                dan dapat diakses dari perangkat apa pun — termasuk smartphone.
            </p>

            <div class="fade-up-4 flex gap-3 justify-center flex-wrap">
                <a href="{{ route('user.lapor') }}" class="btn btn-primary">
                    <i class="bi bi-megaphone"></i> Tulis Pengaduan
                </a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-ghost">
                        <i class="bi bi-person-plus"></i> Buat Akun
                    </a>
                @endguest
            </div>
        </div>
    </div>

    {{-- Stats strip --}}
    <div class="grid grid-cols-3 gap-3 mb-8">
        @foreach([
                ['24/7', 'Tim Siaga', 'bi-headset', 'ok'],
                ['100%', 'Transparan', 'bi-eye', 'warn'],
                ['Gratis', 'Tanpa Biaya', 'bi-gift', 'accent'],
            ] as [$num, $lbl, $icon, $type])
            <div class="stat-tile text-center">
                <div class="text-xl f
                   ont-black mb-1
                    {{ $type === 'ok' ? 'text-ok' : ($type === 'warn' ? 'text-warn' : 'text-accent') }}">
                    {{ $num }}
                </div>
                <div class="text-dim" style="font-size:0.72rem; text-transform: uppercase; letter-spacing: 0.07em; font-weight: 600;">
                    {{ $lbl }}
                </div>
            </div>
        @endforeach
    </div>

    {{-- Feature grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
                ['bi-geo-alt-fill', 'Peta Interaktif', 'Tentukan lokasi langsung dari peta dengan akurasi GPS.'],
                ['bi-image', 'Upload Foto Bukti', 'Lampirkan foto sebagai bukti pendukung laporan Anda.'],
                ['bi-chat-dots', 'Balasan Petugas', 'Pantau respons dan tindak lanjut dari petugas terkait.'],
                ['bi-phone', 'Bisa Diinstal (PWA)', 'Install SIGAP di ponsel Anda tanpa perlu App Store.'],
            ] as [$icon, $title, $desc])
            <div class="feature-card">
                <div class="feature-icon"><i class="bi {{ $icon }}"></i></div>
                <div class="font-semibold text-sm mb-1" style="color: var(--color-text-primary);">{{ $title }}</div>
                <div class="text-secondary" style="font-size: 0.8rem; line-height: 1.6;">{{ $desc }}</div>
            </div>
        @endforeach
    </div>
@endsection