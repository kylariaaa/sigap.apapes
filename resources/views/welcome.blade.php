@extends('layouts.master')
@section('title', 'Beranda SIGAP')

@push('styles')
    <style>
        /* ─── Hero ─── */
        .hero-wrap {
            position: relative;
            text-align: center;
            overflow: hidden;
            border-radius: 1.5rem;
            margin-bottom: 2rem;
            padding: 5rem 1.5rem 4rem;
            background: linear-gradient(160deg, #ede9fe 0%, #dbeafe 40%, #f0fdf4 75%, #fefce8 100%);
            border: 1px solid #e2e8f0;
        }

        /* subtle dot-grid overlay */
        .hero-wrap::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(99, 102, 241, .15) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem .95rem;
            border-radius: 9999px;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            background: rgba(255, 255, 255, .7);
            border: 1.5px solid #c7d2fe;
            color: #4f46e5;
            backdrop-filter: blur(4px);
            margin-bottom: 1.5rem;
        }

        .hero-title {
            font-size: clamp(2.4rem, 6vw, 4rem);
            font-weight: 900;
            line-height: 1.1;
            color: #0f172a;
            margin-bottom: .6rem;
        }

        .hero-title .hl {
            color: #0ea5e9;
        }

        .hero-subtitle {
            font-size: clamp(1rem, 2.5vw, 1.3rem);
            font-weight: 500;
            color: #475569;
            margin-bottom: 1.1rem;
        }

        .hero-desc {
            max-width: 500px;
            margin: 0 auto 2rem;
            font-size: .95rem;
            line-height: 1.75;
            color: #64748b;
        }

        .hero-desc .kw-cepat {
            color: #0ea5e9;
            font-weight: 700;
        }

        .hero-desc .kw-aman {
            color: #f97316;
            font-weight: 700;
        }

        .hero-desc .kw-transparan {
            color: #22c55e;
            font-weight: 700;
        }

        .hero-cta {
            display: flex;
            gap: .75rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 2.5rem;
        }

        /* stats */
        .hero-stats {
            display: flex;
            gap: 3rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .hstat-num {
            font-size: 1.6rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: .2rem;
        }

        .hstat-lbl {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #94a3b8;
        }

        /* feature strip */
        .feat-strip {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            font-size: .82rem;
            color: #64748b;
            font-weight: 500;
        }

        .feat-strip span {
            display: flex;
            align-items: center;
            gap: .35rem;
        }

        /* feature cards */
        .feature-card {
            background: var(--color-surface-1, #f8fafc);
            border: 1px solid var(--color-border, #e2e8f0);
            border-radius: 12px;
            padding: 1.5rem;
            transition: border-color .2s, transform .2s;
        }

        .feature-card:hover {
            border-color: #bae6fd;
            transform: translateY(-2px);
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: #eff6ff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #0ea5e9;
            margin-bottom: 1rem;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fu1 {
            animation: fadeUp .55s ease both;
        }

        .fu2 {
            animation: fadeUp .55s .08s ease both;
        }

        .fu3 {
            animation: fadeUp .55s .16s ease both;
        }

        .fu4 {
            animation: fadeUp .55s .24s ease both;
        }

        .fu5 {
            animation: fadeUp .55s .32s ease both;
        }

        .fu6 {
            animation: fadeUp .55s .40s ease both;
        }
    </style>
@endpush

@section('content')

    {{-- ── Hero ── --}}
    <div class="hero-wrap">
        {{-- Badge --}}
        <div class="fu1">
            <span class="hero-badge">
                <i class="bi bi-shield-check"></i>
                Sistem Pengaduan Digital
            </span>
        </div>

        {{-- Title --}}
        <h1 class="hero-title fu2">
            Suarakan <span class="hl">SIGAP</span>
        </h1>

        {{-- Subtitle --}}
        <p class="hero-subtitle fu3">Sistem Informasi Pengaduan Masyarakat</p>

        {{-- Description --}}
        <p class="hero-desc fu3">
            Laporkan keluhan infrastruktur &amp; layanan publik di lingkungan Anda.<br>
            Platform digital yang <span class="kw-cepat">cepat</span>,
            <span class="kw-aman">aman</span>, dan
            <span class="kw-transparan">transparan</span> untuk warga dan petugas.
        </p>

        {{-- CTA Buttons --}}
        <div class="hero-cta fu4">
            <a href="{{ route('user.lapor') }}" class="btn btn-primary">
                <i class="bi bi-megaphone-fill"></i> Tulis Pengaduan
            </a>
            @guest
                <a href="{{ route('register') }}" class="btn btn-ghost">
                    <i class="bi bi-person-plus"></i> Daftar Akun
                </a>
            @endguest
        </div>

        {{-- Stats --}}
        <div class="hero-stats fu5">
            <div>
                <div class="hstat-num" style="color:#0ea5e9">24/7</div>
                <div class="hstat-lbl">Siaga</div>
            </div>
            <div>
                <div class="hstat-num" style="color:#22c55e">100%</div>
                <div class="hstat-lbl">Transparan</div>
            </div>
            <div>
                <div class="hstat-num" style="color:#a855f7">Gratis</div>
                <div class="hstat-lbl">Layanan</div>
            </div>
        </div>

        {{-- Feature strip --}}
        <div class="feat-strip fu6">
            <span><i class="bi bi-geo-alt-fill" style="color:#0ea5e9"></i> Peta Interaktif</span>
            <span><i class="bi bi-image" style="color:#f97316"></i> Upload Foto Bukti</span>
            <span><i class="bi bi-bell-fill" style="color:#22c55e"></i> Notifikasi Real-time</span>
            <span><i class="bi bi-phone" style="color:#a855f7"></i> Bisa Diinstal (PWA)</span>
        </div>
    </div>

    {{-- ── Feature Cards ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
                ['bi-geo-alt-fill', 'Peta Interaktif', 'Tentukan lokasi langsung dari peta dengan akurasi GPS.'],
                ['bi-image', 'Upload Foto Bukti', 'Lampirkan foto sebagai bukti pendukung laporan Anda.'],
                ['bi-chat-dots', 'Balasan Petugas', 'Pantau respons dan tindak lanjut dari petugas terkait.'],
                ['bi-phone', 'Bisa Diinstal (PWA)', 'Install SIGAP di ponsel Anda tanpa perlu App Store.'],
            ] as [$icon, $title, $desc])
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi {{ $icon }}"></i></div>
                    <div class="font-semibold text-sm mb-1" style="color:#0f172a">{{ $title }}</div>
                    <div style="font-size:.8rem; line-height:1.6; color:#64748b">{{ $desc }}</div>
                </div>
        @endforeach
    </div>

@endsection