<!DOCTYPE html>
<html lang="id">

<head>
    {{-- ── PWA ── --}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    {{-- PWA: Android Chrome --}}
    <meta name="theme-color" content="#171923">
    <meta name="mobile-web-app-capable" content="yes">

    {{-- PWA: Apple iOS Safari --}}
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="SIGAP">
    <link rel="apple-touch-icon" href="{{ asset('logo-192.png') }}">

    {{-- PWA: Service Worker --}}
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(r => console.log('[SIGAP] SW ready:', r.scope))
                    .catch(e => console.warn('[SIGAP] SW error:', e));
            });
        }
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title') — SIGAP</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    {{-- Bootstrap Icons only --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Leaflet (lazy loaded per-page) --}}
    @stack('head')

    {{-- Tailwind + App assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Mobile navbar drawer ── */
        #mobile-menu {
            display: none;
            flex-direction: column;
            gap: 0.25rem;
            padding: 0.75rem 1rem 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: inherit;
        }

        #mobile-menu.open {
            display: flex;
        }

        #mobile-menu .nav-link-futur,
        #mobile-menu .btn-logout {
            width: 100%;
            text-align: left;
            justify-content: flex-start;
        }

        /* Hamburger button */
        #hamburger-btn {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.35rem 0.5rem;
            border-radius: 6px;
            color: var(--color-text-primary, #fff);
            font-size: 1.5rem;
            line-height: 1;
            transition: opacity 0.2s;
        }

        #hamburger-btn:hover {
            opacity: 0.7;
        }

        /* Desktop nav: show links, hide hamburger */
        #desktop-links {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            flex-wrap: wrap;
        }

        @media (max-width: 640px) {
            #hamburger-btn {
                display: inline-flex;
                align-items: center;
            }

            #desktop-links {
                display: none !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="bg-deep text-primary-text min-h-screen font-sans">

    {{-- ══ STICKY NAVBAR ══ --}}
    <nav class="navbar-futur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">

            {{-- Brand --}}
            <a href="/" class="brand-gradient text-xl font-black tracking-wider no-underline">
                ⚡ SIGAP
            </a>

            {{-- Desktop Nav Links --}}
            <div id="desktop-links">
                @guest
                    <a class="nav-link-futur" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right"></i>Login
                    </a>
                    <a href="{{ route('report.export') }}" class="sidebar-link">
                        <i class="bi bi-file-earmark-pdf"></i> Export PDF
                    </a>
                </nav>

                <div style="padding: 1rem 1.25rem; border-top: 1px solid var(--color-border);">
                    <div style="font-size: 0.8rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 0.15rem;">
                        {{ Auth::user()->name }}
                    </div>
                    <div style="font-size: 0.7rem; color: var(--color-text-dim);">Administrator</div>
                    <form action="{{ route('logout') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm w-full justify-center">
                            <i class="bi bi-power"></i> Keluar
                        </button>
                    </form>
                </div>
            </aside>

            {{-- Main area --}}
            <div class="with-sidebar pb-mobile">
                <header class="topbar">
                    <div class="flex items-center gap-3">
                        {{-- Mobile hamburger --}}
                        <button id="sidebar-toggle" class="btn btn-ghost btn-sm" style="display:none;"
                            onclick="document.getElementById('sidebar').classList.toggle('open')">
                            <i class="bi bi-list text-lg"></i>
                        </button>
                        <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span style="font-size:0.78rem; color:var(--color-text-dim);">
                            <i class="bi bi-circle-fill"
                                style="color:var(--color-ok); font-size:0.4rem; vertical-align: middle;"></i>
                            Sistem Aktif
                        </span>
                    </div>
                </header>

                <main class="p-5 lg:p-6">
                    @if(session('success'))
                        <div class="alert-success mb-5"><i class="bi bi-check-circle"></i>{{ session('success') }}</div>
                    @endif
                    @yield('content')
                </main>
            </div>

        @else
            {{-- ════════════════════════════════
            WARGA LAYOUT: Topnav + Bottom Nav
            ════════════════════════════════ --}}

            <nav class="topnav">
                <a href="{{ route('user.lapor') }}" class="topnav-brand">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-md text-xs font-black"
                        style="background: var(--color-accent); color: #fff;">S</span>
                    SIGAP
                </a>
                <div class="flex items-center gap-1">
                    <span style="font-size:0.78rem; color:var(--color-text-dim);">
                        {{ Auth::user()->name }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-sm">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                @endauth
            </div>

            {{-- Hamburger (mobile only) --}}
            <button id="hamburger-btn" aria-label="Toggle menu" aria-expanded="false">
                <i class="bi bi-list" id="hamburger-icon"></i>
            </button>

        </div>

        {{-- Mobile Dropdown Menu --}}
        <div id="mobile-menu" role="navigation">
            @guest
                <a class="nav-link-futur" href="{{ route('login') }}">
                    <i class="bi bi-box-arrow-in-right"></i>Login
                </a>
                <a class="nav-link-futur" href="{{ route('register') }}">
                    <i class="bi bi-person-plus"></i>Daftar
                </a>
            @endguest

            @auth
                @if(Auth::user()->role === 'admin')
                    <a class="nav-link-futur" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-grid-3x3-gap"></i>Dashboard
                    </a>
                    <a class="nav-link-futur" href="{{ route('report.export') }}">
                        <i class="bi bi-file-earmark-pdf"></i>Export PDF
                    </a>
                @elseif(Auth::user()->role === 'masyarakat')
                    <a class="nav-link-futur" href="{{ route('user.lapor') }}">
                        <i class="bi bi-megaphone"></i>Tulis Pengaduan
                    </a>
                @endif

                <span class="nav-link-futur cursor-default opacity-70 text-sm">
                    <i class="bi bi-person-circle"></i>{{ Auth::user()->name }}
                </span>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="bi bi-power"></i>Keluar
                    </button>
                </form>
            @endauth
        </div>
    </nav>

    {{-- ══ PAGE CONTENT ══ --}}
    <main class="max-w-7xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    {{-- Hamburger toggle script --}}
    <script>
        (function () {
            var btn = document.getElementById('hamburger-btn');
            var menu = document.getElementById('mobile-menu');
            var icon = document.getElementById('hamburger-icon');
            if (!btn) return;
            btn.addEventListener('click', function () {
                var open = menu.classList.toggle('open');
                btn.setAttribute('aria-expanded', open);
                icon.className = open ? 'bi bi-x-lg' : 'bi bi-list';
            });
        })();
    </script>

</body>

</html>