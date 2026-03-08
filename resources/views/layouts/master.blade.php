<!DOCTYPE html>
<html lang="id">

<head>
    {{-- PWA --}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#00d4ff">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(reg => {
                    console.log('[SIGAP PWA] SW registered:', reg.scope);
                });
            });
        }
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — SIGAP</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- Bootstrap Icons (ikon saja, tanpa CSS Bootstrap) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Leaflet (untuk halaman peta) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>

    {{-- Tailwind CSS + App CSS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

            {{-- Nav Links --}}
            <div class="flex items-center gap-1 flex-wrap">

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
        </div>
    </nav>

    {{-- ══ PAGE CONTENT ══ --}}
    <main class="max-w-7xl mx-auto px-4 py-8">
        @yield('content')
    </main>

</body>

</html>