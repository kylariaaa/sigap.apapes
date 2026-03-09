<!DOCTYPE html>
<html lang="id">

<head>
    {{-- PWA --}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#faf7f2">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="SIGAP">
    <link rel="apple-touch-icon" href="{{ asset('logo-192.png') }}">

    {{-- Service Worker --}}
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

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Per-page head (Leaflet, etc.) --}}
    @stack('head')

    {{-- Tailwind + App assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-deep text-primary-text min-h-screen font-sans">

    {{-- ══════════════════════════════════════════════════
    LAYOUT SWITCH
    ══════════════════════════════════════════════════ --}}

    @auth
        @if(Auth::user()->role === 'admin')

            {{-- ══ ADMIN LAYOUT: Sidebar + Topbar ══ --}}
            <div style="display:flex; min-height:100vh;">

                {{-- Sidebar --}}
                <aside class="sidebar" id="sidebar">
                    <div class="sidebar-brand">
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand-logo">
                            <span
                                style="width:28px;height:28px;background:var(--color-accent);border-radius:8px;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:0.85rem;">S</span>
                            SIGAP
                        </a>
                    </div>

                    <nav style="flex:1; padding: 0.75rem 0; overflow-y:auto;">
                        <div class="sidebar-section-label">Menu</div>
                        <a href="{{ route('admin.dashboard') }}"
                            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-grid-3x3-gap"></i> Dashboard
                        </a>
                        <a href="{{ route('report.export') }}" class="sidebar-link">
                            <i class="bi bi-file-earmark-pdf"></i> Export PDF
                        </a>
                    </nav>

                    <div style="padding: 1rem 1.25rem; border-top: 1px solid var(--color-border);">
                        <div
                            style="font-size: 0.8rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 0.15rem;">
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
                <div class="with-sidebar" style="flex:1; display:flex; flex-direction:column;">
                    <header class="topbar">
                        <div class="flex items-center gap-3">
                            <button id="sidebar-toggle" class="btn btn-ghost btn-sm" style="display:none;"
                                onclick="document.getElementById('sidebar').classList.toggle('open')">
                                <i class="bi bi-list text-lg"></i>
                            </button>
                            <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span style="font-size:0.78rem; color:var(--color-text-dim);">
                                <i class="bi bi-circle-fill"
                                    style="color:var(--color-ok); font-size:0.4rem; vertical-align:middle;"></i>
                                Sistem Aktif
                            </span>
                        </div>
                    </header>

                    <main class="p-5 lg:p-6" style="flex:1;">
                        @if(session('success'))
                            <div class="alert-success mb-5"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
                        @endif
                        @yield('content')
                    </main>
                </div>
            </div>

        @else

            {{-- ══ WARGA LAYOUT: Topnav + Content ══ --}}
            <nav class="topnav">
                <a href="{{ route('user.lapor') }}" class="topnav-brand">
                    <span
                        style="width:26px;height:26px;background:var(--color-accent);border-radius:7px;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:0.8rem;font-weight:800;">S</span>
                    SIGAP
                </a>

                {{-- Desktop: user info + logout --}}
                <div class="flex items-center gap-1" id="desktop-user">
                    <span style="font-size:0.78rem; color:var(--color-text-dim);">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-sm">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>

                {{-- Hamburger (mobile only) --}}
                <button id="hamburger-btn" aria-label="Toggle menu" aria-expanded="false"
                    style="display:none; background:none; border:none; cursor:pointer; padding:0.35rem 0.5rem; border-radius:6px; color:var(--color-text-primary); font-size:1.5rem; line-height:1; transition:opacity 0.2s;">
                    <i class="bi bi-list" id="hamburger-icon"></i>
                </button>
            </nav>

            {{-- Mobile dropdown menu --}}
            <div id="mobile-menu" role="navigation"
                style="display:none; flex-direction:column; gap:0.25rem; padding:0.75rem 1rem 1rem; border-top:1px solid var(--color-border); background:var(--color-surface-1);">
                <a class="nav-link-futur" href="{{ route('user.lapor') }}">
                    <i class="bi bi-megaphone"></i> Tulis Pengaduan
                </a>
                <span class="nav-link-futur" style="cursor:default; opacity:0.7; font-size:0.85rem;">
                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                </span>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn-logout" style="width:100%; text-align:left; justify-content:flex-start;">
                        <i class="bi bi-power"></i> Keluar
                    </button>
                </form>
            </div>

            <main class="max-w-7xl mx-auto px-4 py-8">
                @yield('content')
            </main>

        @endif

    @else

        {{-- ══ GUEST LAYOUT: Public Navbar ══ --}}
        <nav class="navbar-futur sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
                <a href="/" class="brand-gradient text-xl font-black tracking-wider no-underline">
                    ⚡ SIGAP
                </a>
                <div class="flex items-center gap-2">
                    <a class="nav-link-futur" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                    <a class="nav-link-futur" href="{{ route('register') }}">
                        <i class="bi bi-person-plus"></i> Daftar
                    </a>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 py-8">
            @yield('content')
        </main>

    @endauth

    {{-- Scripts --}}
    @stack('scripts')

    {{-- Hamburger toggle (warga only) --}}
    <script>
        (function () {
            var btn = document.getElementById('hamburger-btn');
            var menu = document.getElementById('mobile-menu');
            var icon = document.getElementById('hamburger-icon');
            if (!btn || !menu) return;

            // Show hamburger on small screens
            function checkWidth() {
                if (window.innerWidth <= 640) {
                    btn.style.display = 'inline-flex';
                    var deskUser = document.getElementById('desktop-user');
                    if (deskUser) deskUser.style.display = 'none';
                } else {
                    btn.style.display = 'none';
                    menu.style.display = 'none';
                    var deskUser = document.getElementById('desktop-user');
                    if (deskUser) deskUser.style.display = 'flex';
                }
            }
            checkWidth();
            window.addEventListener('resize', checkWidth);

            btn.addEventListener('click', function () {
                var isOpen = menu.style.display === 'flex';
                menu.style.display = isOpen ? 'none' : 'flex';
                btn.setAttribute('aria-expanded', !isOpen);
                if (icon) icon.className = isOpen ? 'bi bi-list' : 'bi bi-x-lg';
            });
        })();
    </script>

</body>

</html>