@extends('layouts.master')
@section('title', 'Beranda')

@push('styles')
    <style>
        /* Star animation */
        .star {
            position: absolute;
            border-radius: 9999px;
            background: #fff;
            animation: twinkle var(--dur) ease-in-out infinite alternate;
            opacity: 0;
        }

        @keyframes twinkle {
            0% {
                opacity: 0;
                transform: scale(0.5);
            }

            100% {
                opacity: var(--max-op);
                transform: scale(1.2);
            }
        }

        /* Animate grid */
        .hero-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* Glow bg blobs */
        .blob-1 {
            background: radial-gradient(ellipse 80% 50% at 20% 40%, rgba(0, 212, 255, 0.08), transparent);
        }

        .blob-2 {
            background: radial-gradient(ellipse 60% 50% at 80% 60%, rgba(168, 85, 247, 0.07), transparent);
        }

        .blob-3 {
            background: radial-gradient(ellipse 50% 40% at 50% 100%, rgba(0, 255, 136, 0.05), transparent);
        }

        /* Fade up animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .anim-1 {
            animation: fadeInDown 0.6s ease both;
        }

        .anim-2 {
            animation: fadeInUp 0.7s ease 0.1s both;
        }

        .anim-3 {
            animation: fadeInUp 0.7s ease 0.2s both;
        }

        .anim-4 {
            animation: fadeInUp 0.7s ease 0.3s both;
        }

        .anim-5 {
            animation: fadeInUp 0.7s ease 0.45s both;
        }

        .anim-6 {
            animation: fadeInUp 0.7s ease 0.6s both;
        }

        /* neon glow text */
        .glow-title {
            filter: drop-shadow(0 0 30px rgba(0, 212, 255, 0.4));
        }
    </style>
@endpush

@section('content')
    {{-- Hero Section --}}
    <div class="relative flex items-center justify-center text-center overflow-hidden py-20 -mx-4"
        style="min-height: calc(100vh - 80px);">

        {{-- Background layers --}}
        <div class="blob-1 absolute inset-0 pointer-events-none"></div>
        <div class="blob-2 absolute inset-0 pointer-events-none"></div>
        <div class="blob-3 absolute inset-0 pointer-events-none"></div>
        <div class="hero-grid pointer-events-none"></div>
        <div class="absolute inset-0 overflow-hidden pointer-events-none" id="stars-wrap"></div>

        {{-- Content --}}
        <div class="relative z-10 px-4 max-w-3xl mx-auto">

            {{-- Tag --}}
            <div class="anim-1 inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase mb-6"
                style="background: rgba(0,212,255,0.1); border: 1px solid rgba(0,212,255,0.3); color: var(--color-neon-cyan);">
                <i class="bi bi-shield-check"></i> Sistem Pengaduan Digital
            </div>

            {{-- Title --}}
            <h1 class="anim-2 font-black leading-tight mb-5" style="font-size: clamp(2.5rem, 6vw, 4.5rem);">
                Suarakan
                <span class="brand-gradient glow-title">SIGAP</span>
                <br>
                <span class="text-muted-futur font-medium" style="font-size: 0.55em;">
                    Sistem Informasi Pengaduan Masyarakat
                </span>
            </h1>

            {{-- Desc --}}
            <p class="anim-3 text-muted-futur text-lg leading-relaxed max-w-xl mx-auto mb-8">
                Laporkan keluhan infrastruktur & layanan publik di lingkungan Anda.
                Platform digital yang <strong class="text-neon-cyan">cepat</strong>,
                <strong class="text-neon-purple">aman</strong>, dan
                <strong class="text-neon-green">transparan</strong> untuk warga dan petugas.
            </p>

            {{-- CTA Buttons --}}
            <div class="anim-4 flex gap-4 justify-center flex-wrap">
                <a href="{{ route('user.lapor') }}"
                    class="btn-neon inline-flex items-center gap-2 px-8 py-3 text-base no-underline">
                    <i class="bi bi-megaphone-fill"></i> Tulis Pengaduan
                </a>
                @guest
                    <a href="{{ route('register') }}"
                        class="btn-neon-outline inline-flex items-center gap-2 px-8 py-3 text-base no-underline">
                        <i class="bi bi-person-plus"></i> Daftar Akun
                    </a>
                @endguest
            </div>

            {{-- Stats --}}
            <div class="anim-5 flex items-center justify-center gap-8 flex-wrap mt-10">
                <div class="text-center">
                    <span class="block text-4xl font-black text-neon-cyan">24/7</span>
                    <span class="text-xs uppercase tracking-widest text-muted-futur font-bold">Siaga</span>
                </div>
                <div class="w-px h-10 bg-white/10"></div>
                <div class="text-center">
                    <span class="block text-4xl font-black text-neon-green">100%</span>
                    <span class="text-xs uppercase tracking-widest text-muted-futur font-bold">Transparan</span>
                </div>
                <div class="w-px h-10 bg-white/10"></div>
                <div class="text-center">
                    <span class="block text-4xl font-black text-neon-purple">Gratis</span>
                    <span class="text-xs uppercase tracking-widest text-muted-futur font-bold">Layanan</span>
                </div>
            </div>

            {{-- Feature Pills --}}
            <div class="anim-6 flex gap-3 justify-center flex-wrap mt-8">
                @foreach([
                        ['bi-geo-alt-fill', 'Peta Interaktif'],
                        ['bi-image', 'Upload Foto Bukti'],
                        ['bi-bell-fill', 'Notifikasi Real-time'],
                        ['bi-phone', 'Bisa Diinstal (PWA)'],
                    ] as [$icon, $label])
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm text-muted-futur font-medium"
                          style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                        <i class="bi {{ $icon }} text-neon-cyan"></i>{{ $label }}
                    </span>
                @endforeach
            </div>

        </div>
    </div>

    <script>
        const wrap = document.getElementById('stars-wrap');
        for (let i = 0; i < 80; i++)   {
              const s = document.createElement('div');
            const size =  1  + Math.rando m () * 2;
            s.className = 'sta r ';
              s.style.cssText = `
                  left:${Math.random()*100}%; top:${Math.random()*100}%;
                width:${size}px; height:${size}px;
                --dur:${2+Math.random()*4}s;
                --max-op:${0.2+Math.random()*0.6};
                animation-delay:${Math.random()*4}s;
            `;
            wrap.appendChild(s);
        }
    </script>
@endsection