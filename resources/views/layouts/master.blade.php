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
</head>

<body>

    {{-- ════════════════════════════════
    ADMIN LAYOUT: Sidebar + Topbar
    ════════════════════════════════ --}}
    @auth
        @if(Auth::user()->role === 'admin')

            {{-- Sidebar --}}
            <aside class="sidebar" id="sidebar">
                <div class="sidebar-brand">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand-logo">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-xs font-black"
                            style="background: var(--color-accent); color: #fff;">S</span>
                        SIGAP
                    </a>
                    <div class="mt-1" style="font-size:0.7rem; color: var(--color-text-dim);">
                        Admin Panel
                    </div>
                </div>

                <nav class="flex-1 py-4 overflow-y-auto">
                    <div class="sidebar-section-label">Menu Utama</div>
                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid"></i> Dashboard
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
                </div>
            </nav>

            <main class="max-w-5xl mx-auto px-4 py-6 pb-mobile">
                @if(session('success'))
                    <div class="alert-success mb-5"><i class="bi bi-check-circle"></i>{{ session('success') }}</div>
                @endif
                @yield('content')
            </main>

            {{-- Mobile Bottom Nav --}}
            <nav class="bottom-nav">
                <a href="{{ route('user.lapor') }}"
                    class="bottom-nav-item {{ request()->routeIs('user.lapor') ? 'active' : '' }}">
                    <i class="bi bi-megaphone"></i>
                    <span>Laporan</span>
                </a>
                <a href="{{ route('user.lapor') }}#history" class="bottom-nav-item">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="bottom-nav-item border-0" style="background:none; cursor:pointer;">
                        <i class="bi bi-power"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </nav>

        @endif
    @endauth

    {{-- ════════════════════════════
    GUEST LAYOUT: Simple topnav
    ════════════════════════════ --}}
    @guest
        <nav class="topnav">
            <a href="/" class="topnav-brand">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-md text-xs font-black"
                    style="background: var(--color-accent); color: #fff;">S</span>
                SIGAP
            </a>
            <div class="flex items-center gap-1">
                <a href="{{ route('login') }}" class="topnav-link">
                    <i class="bi bi-box-arrow-in-right"></i>Masuk
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                    Daftar
                </a>
            </div>
        </nav>

        <main class="max-w-6xl mx-auto px-4 py-8">
            @yield('content')
        </main>
    @endguest

    {{-- Sidebar overlay on mobile --}}
    @auth
        @if(Auth::user()->role === 'admin')
            <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden"
                onclick="document.getElementById('sidebar').classList.remove('open'); this.classList.add('hidden');"></div>
            <script>
                const sidebarToggle = document.getElementById('sidebar-toggle');
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                if (window.innerWidth <= 768) {
                    sidebarToggle.style.display = 'flex';
                }
                sidebar.addEventListener('transitionend', () => {
                    if (sidebar.classList.contains('open')) {
                        overlay.classList.remove('hidden');
                    } else {
                        overlay.classList.add('hidden');
                    }
                });
            </script>
        @endif
    @endauth

    @stack('scripts')
</body>

</html>