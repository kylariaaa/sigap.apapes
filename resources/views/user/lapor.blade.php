@extends('layouts.master')
@section('title', 'Tulis Pengaduan')

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('styles')
    <style>
        /* Ticker animation */
        @keyframes ticker {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .ticker-scroll {
            display: inline-block;
            white-space: nowrap;
            animation: ticker 25s linear infinite;
        }

        /* Map */
        .map-wrap {
            position: relative;
            margin-top: 0.5rem;
        }

        #map {
            height: 240px;
            border-radius: 8px;
            border: 2px solid var(--color-border-strong);
        }

        /* Locate Me button overlay */
        #locate-btn {
            position: absolute;
            bottom: 12px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1.1rem;
            font-size: 0.82rem;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            border-radius: 999px;
            border: 2px solid var(--color-accent, #00d4ff);
            background: rgba(0, 212, 255, 0.12);
            color: var(--color-accent, #00d4ff);
            cursor: pointer;
            backdrop-filter: blur(6px);
            box-shadow: 0 2px 12px rgba(0, 212, 255, 0.25);
            transition: background 0.2s, transform 0.15s;
            white-space: nowrap;
        }

        #locate-btn:hover {
            background: rgba(0, 212, 255, 0.25);
        }

        #locate-btn:active {
            transform: translateX(-50%) scale(0.96);
        }

        #locate-btn.loading {
            opacity: 0.7;
            pointer-events: none;
        }

        /* Timeline */
        .tl-wrap {
            padding-left: 1.25rem;
            border-left: 2px solid var(--color-accent-border);
            margin-top: 0.75rem;
        }

        .tl-dot {
            position: absolute;
            left: -1.55rem;
            top: 6px;
            width: 12px;
            height: 12px;
            background: var(--color-accent);
            border-radius: 9999px;
            border: 2px solid #fff;
        }

        .tl-box {
            background: #ffffff;
            border: 2px solid var(--color-border-strong);
            border-radius: 8px;
            padding: 0.85rem 1rem;
            box-shadow: 2px 2px 0 var(--color-border);
        }

        .tl-img {
            max-height: 100px;
            border-radius: 6px;
            margin-top: 0.65rem;
            border: 2px solid var(--color-border-strong);
        }

        /* History table */
        .hist-td {
            padding: 1rem 1rem;
            vertical-align: top;
            border-bottom: 1px solid var(--color-border);
        }

        .hist-th {
            padding: 0.85rem 1rem;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 800;
            color: var(--color-text-secondary);
            border-bottom: 2px solid var(--color-border-strong);
            background: var(--color-surface-2);
            text-align: left;
        }
    </style>
<style>
    /* Map */
    #map {
        height: 220px;
        border-radius: 8px;
        border: 2px solid var(--color-border-strong);
        margin-top: 0.5rem;
    }

    /* Timeline/History specific styling */
    .history-item {
        padding: 1.25rem;
        border-bottom: 2px solid var(--color-border-strong);
    }

    .history-item:last-child {
        border-bottom: none;
    }

    .resp-box {
        margin-top: 1rem;
        padding: 1rem;
        background: var(--color-surface-2);
        border: 2px solid var(--color-border-strong);
        border-radius: 8px;
        position: relative;
    }

    .resp-box::before {
        content: '';
        position: absolute;
        left: 1rem;
        top: -10px;
        width: 20px;
        height: 10px;
        background: var(--color-surface-2);
        border-top: 2px solid var(--color-border-strong);
        border-left: 2px solid var(--color-border-strong);
        transform: rotate(45deg);
        z-index: 1;
    }

    .resp-img {
        max-height: 100px;
        border-radius: 6px;
        margin-top: 0.5rem;
        border: 2px solid var(--color-border-strong);
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Map logic
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('map').setView([-6.200000, 106.816666], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '©OSM'
        }).addTo(map);

        var marker = L.marker([-6.200000, 106.816666], {
            draggable: true
        }).addTo(map);

        function fetchAddress(lat, lng) {
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + lat + '&lon=' + lng, {
                    headers: {
                        'Accept-Language': 'id'
                    }
                })
                .then(r => r.json())
                .then(d => {
                    document.getElementById('location_text').value = d.display_name || ('Koordinat: ' + lat.toFixed(5) + ', ' + lng.toFixed(5));
                })
                .catch(() => {
                    document.getElementById('location_text').value = 'Koordinat: ' + lat.toFixed(5) + ', ' + lng.toFixed(5);
                });
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                map.setView([pos.coords.latitude, pos.coords.longitude], 16);
                marker.setLatLng([pos.coords.latitude, pos.coords.longitude]);
                fetchAddress(pos.coords.latitude, pos.coords.longitude);
            });
        }

        marker.on('dragend', () => {
            var c = marker.getLatLng();
            fetchAddress(c.lat, c.lng);
        });
        map.on('click', e => {
            marker.setLatLng(e.latlng);
            fetchAddress(e.latlng.lat, e.latlng.lng);
        });
    });
</script>
@endpush

@section('content')

    {{-- Ticker --}}
    <div class="flex items-center gap-3 px-4 py-2.5 rounded-full mb-6 overflow-hidden text-sm"
        style="background: var(--color-accent-dim); border: 2px solid var(--color-accent-border); color: var(--color-accent);">
        <i class="bi bi-broadcast shrink-0"></i>
        <div class="overflow-hidden flex-1">
            <span class="ticker-scroll">
                👋 Selamat Datang, <strong>{{ Auth::user()->name }}</strong>! &nbsp;|&nbsp;
                Laporkan keluhan Anda dengan jujur dan sopan. &nbsp;|&nbsp;
                Tim SIGAP siaga 24 jam untuk melayani Anda. &nbsp;|&nbsp;
                Laporan Anda akan segera ditindaklanjuti. ⚡
            </span>
        </div>
    </div>
{{-- Welcome text --}}
<div class="mb-6">
    <h1 class="font-extrabold text-2xl" style="color: var(--color-text-primary);">Pengaduan Warga</h1>
    <p class="text-secondary text-sm mt-1">Sampaikan laporan Anda, kami siap menindaklanjuti.</p>
</div>

    {{-- 2-col layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-[400px_1fr] gap-5">
{{-- 2-COL LAYOUT: LEFT = FORM, RIGHT = HISTORY --}}
<div class="grid grid-cols-1 lg:grid-cols-[420px_1fr] xl:grid-cols-[450px_1fr] gap-6">

        {{-- ══ LEFT: FORM LAPOR ══ --}}
        <div>
            @if (session('success'))
                <div class="flash-success toast-anim mb-4">
                    <i class="bi bi-check-circle-fill"></i>{{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="flash-error mb-4">
                    <i class="bi bi-exclamation-triangle mr-2"></i>
                    <strong>Ada kesalahan:</strong>
                    <ul class="mt-1 ps-4 list-disc text-sm">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif
    {{-- ══ LEFT: FORM ══ --}}
    <div>
        @if ($errors->any())
        <div class="alert-error mb-4">
            <i class="bi bi-exclamation-triangle mr-2"></i>
            <ul class="mt-1 mb-0 ps-4 list-disc text-sm">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

            <div class="panel">
                <div class="panel-header panel-header-cyan">
                    <i class="bi bi-megaphone-fill"></i> Tulis Laporan Baru
                </div>
                <div class="p-5">
                    <form action="{{ route('user.lapor.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-square"></i> Tulis Laporan Baru</div>
            <div class="p-5">
                <form action="{{ route('user.lapor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                        <div class="mb-4">
                            <label class="label-futur"><i class="bi bi-card-heading mr-1"></i>Judul Laporan</label>
                            <input type="text" name="title" class="input-futur"
                                placeholder="Contoh: Jalan Berlubang di RT 05" value="{{ old('title') }}" required>
                        </div>
                    <div class="mb-4">
                        <label class="label">Judul Laporan <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="input"
                            placeholder="Contoh: Lampu Jalan Mati di Jl. Merdeka"
                            value="{{ old('title') }}" required>
                    </div>

                        <div class="mb-4">
                            <label class="label-futur"><i class="bi bi-chat-left-text mr-1"></i>Isi Keluhan</label>
                            <textarea name="description" class="input-futur" rows="4"
                                placeholder="Jelaskan masalah secara detail..." required
                                style="resize:vertical;">{{ old('description') }}</textarea>
                        </div>
                    <div class="mb-4">
                        <label class="label">Detail Kejadian <span class="text-danger">*</span></label>
                        <textarea name="description" class="input" rows="4"
                            placeholder="Jelaskan secara rinci..."
                            required style="resize:vertical;">{{ old('description') }}</textarea>
                    </div>

                        <div class="mb-4">
                            <label class="label-futur"><i class="bi bi-geo-alt mr-1"></i>Lokasi Kejadian</label>
                            <input type="text" name="location" id="location_text" class="input-futur"
                                placeholder="Geser marker di peta untuk deteksi alamat..." value="{{ old('location') }}"
                                required readonly style="cursor:default; margin-bottom:.5rem;">
                            <div class="map-wrap">
                                <div id="map"></div>
                                <button type="button" id="locate-btn">
                                    <i class="bi bi-crosshair2"></i> Lokasi Saya
                                </button>
                            </div>
                            <p class="text-muted-futur text-xs mt-1.5">
                                <i class="bi bi-info-circle mr-1"></i>Klik atau geser marker untuk menentukan titik
                                koordinat
                            </p>
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                        </div>
                    <div class="mb-4">
                        <label class="label">Titik Lokasi Peta <span class="text-danger">*</span></label>
                        <input type="text" name="location" id="location_text" class="input mb-2"
                            placeholder="Alamat akan muncul jika marker digeser..."
                            value="{{ old('location') }}" required readonly style="background: var(--color-surface-2);">
                        <div id="map"></div>
                        <p class="text-dim text-xs mt-2 font-medium">
                            <i class="bi bi-cursor-fill mr-1"></i>Arahkan pin merah ke lokasi persis kejadian.
                        </p>
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                    </div>

                        <div class="mb-5">
                            <label class="label-futur"><i class="bi bi-image mr-1"></i>Foto Bukti (Opsional)</label>
                            <input type="file" name="image" class="input-futur" accept="image/*"
                                style="padding:0.5rem 1rem; cursor:pointer;">
                            <p class="text-muted-futur text-xs mt-1">Format JPG/PNG, maks. 2MB</p>
                        </div>
                    <div class="mb-6">
                        <label class="label">Unggah Foto Bukti <span class="text-dim">(Opsional)</span></label>
                        <input type="file" name="image" class="input" accept="image/*"
                            style="padding:0.45rem 1rem; cursor:pointer; background: #fff;">
                    </div>

                        <button type="submit"
                            class="btn-neon w-full inline-flex items-center justify-center gap-2 py-3 text-sm">
                            <i class="bi bi-send-fill"></i> KIRIM LAPORAN
                        </button>
                    </form>
                </div>
            </div>
        </div>
                    <button type="submit" class="btn btn-primary btn-full">
                        <i class="bi bi-send-fill"></i> KIRIM LAPORAN
                    </button>
                </form>
            </div>
        </div>
    </div>

        {{-- ══ RIGHT: HISTORY ══ --}}
        <div class="panel">
            <div class="panel-header panel-header-green justify-between">
                <span class="flex items-center gap-2">
                    <i class="bi bi-clock-history"></i> Riwayat Laporan Saya
                </span>
                <span class="text-neon-green font-bold">{{ $myReports->count() }}</span>
            </div>
    {{-- ══ RIGHT: HISTORY ══ --}}
    <div>
        <div class="card">
            <div class="card-header flex justify-between">
                <span><i class="bi bi-clock-history"></i> Riwayat Pengaduan Anda</span>
                <span class="badge badge-process">{{ $myReports->count() }} Laporan</span>
            </div>

            @if ($myReports->isEmpty())
                <div class="text-center py-16 text-muted-futur">
                    <i class="bi bi-inbox text-5xl opacity-20 block mb-3"></i>
                    <p class="text-sm">Belum ada laporan yang dikirim.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                                        <tr>
                            <th class="hist-th w-28">Tanggal</th>
                            <th class="hist-th">Laporan</th>
                            <th class="hist-th w-52">Status & Balasan</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($myReports as $item)
                                <tr style="background: #fff;">
                                    <td class="hist-td"
                                        style="color: var(--color-text-secondary); font-size: 0.85rem; white-space: nowrap; font-weight: 600;">
                                        {{ $item->created_at->format('d M Y') }}
                                    </td>
                                    <td class="hist-td">
                                        <div class="font-bold text-base" style="color: var(--color-text-primary);">{{ $item->title }}</div>
                                        <div class="mt-1"
                                            style="color: var(--color-text-secondary); font-size: 0.875rem; line-height: 1.5;">
                                            {{ \Illuminate\Support\Str::limit($item->description, 80) }}
                                                    </div>
                                            @if($item->location)
                                                <div class="mt-1"
                                                    style="color: var(--color-accent); font-size: 0.82rem; font-weight: 600;">
                                                        <i class="bi bi-geo-alt-fill mr-1"></i>{{ \Illuminate\Support\Str::limit($item->location, 50) }}
                                                </div>
                                            @endif
                                    </td>
                                    <td class="hist-td">
                                        {{-- Badge --}}
                                        <div class="mb-3">
                                                @if ($item->status == '0')
                                                    <span class="badge-pending" style="font-size:0.8rem; padding: 0.4em 0.9em;">⏳
                                                        Menunggu</span>
                                                @elseif($item->status == 'proses')
                                                <span class="badge-proses" style="font-size:0.8rem; padding: 0.4em 0.9em;">🔄
                                                    Diproses</span>
                                            @else
                                                    <span class="badge-selesai" style="font-size:0.8rem; padding: 0.4em 0.9em;">✅
                                                        Selesai</span>
                                                @endif
                                        </div>
                                        {{-- Timeline --}}
                                        @if ($item->responses->count() > 0)
                                            <div class="tl-wrap">
                                                @foreach ($item->responses as $resp)
                                                    <div class="relative mb-3">
                                                            <span class="tl-dot"></span>
                                                        <div class="tl-box">
                                                                   <div class="font-bold text-xs mb-1" style="color: var(--color-accent);">
                                                                <i class="bi bi-calendar2 mr-1"></i>{{ $resp->created_at->format('d M Y, H:i') }}
                                                            </div>
                                                                        <p class="mb-0" style="font-size: 0.9rem; color:
                                                            var(--color-text-primary); line-height: 1.55;">
                                                            <span class="font-extrabold">Petugas:</span> {{ $resp->response_text }}
                                                                    </p>
                                                            @if ($resp->image)
                                                                <img src="{{ asset('storage/' . $resp->image) }}" class="tl-img" alt="Bukti">
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span style="color: var(--color-text-dim); font-size: 0.85rem; font-style: italic;">Belum
                                                ada balasan.</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
            @if ($myReports->isEmpty())
            <div class="text-center py-16 text-secondary">
                <i class="bi bi-folder-x" style="font-size: 3rem; opacity:0.3; display:block; margin-bottom:1rem;"></i>
                <p class="text-sm font-medium">Anda belum pernah mengirim laporan.</p>
            </div>
            @else
            <div>
                @foreach ($myReports as $item)
                <div class="history-item">
                    {{-- Header report --}}
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <div class="font-extrabold text-base mb-1" style="color: var(--color-text-primary);">{{ $item->title }}</div>
                            <div class="text-xs text-secondary flex items-center gap-2 font-medium">
                                <span><i class="bi bi-calendar3 mr-1"></i>{{ $item->created_at->format('d M Y, H:i') }}</span>
                                @if($item->location)
                                <span class="text-dim text-xs truncate max-w-[200px]" title="{{ $item->location }}">
                                    • <i class="bi bi-geo-alt-fill mx-1"></i>{{ $item->location }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="shrink-0">
                            @if ($item->status == '0')
                            <span class="badge badge-new">Menunggu</span>
                            @elseif($item->status == 'proses')
                            <span class="badge badge-process">Diproses</span>
                            @else
                            <span class="badge badge-done">Selesai</span>
                            @endif
                        </div>
                    </div>

                    {{-- Isi Laporan --}}
                    <p class="text-sm leading-relaxed text-secondary mb-3">{{ $item->description }}</p>

                    {{-- Bukti --}}
                    @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="Foto Laporan"
                        class="rounded-lg mb-3" style="max-height: 120px; border: 2px solid var(--color-border-strong);">
                    @endif

                    {{-- Tanggapan admin --}}
                    @if ($item->responses->count() > 0)
                    @foreach ($item->responses as $resp)
                    <div class="resp-box">
                        <div class="relative z-10">
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="text-accent font-extrabold text-xs"><i class="bi bi-shield-check mr-1"></i>{{ $resp->user->name }}</span>
                                <span class="text-dim text-xs">{{ $resp->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm mb-0 text-primary font-medium">{{ $resp->response_text }}</p>
                            @if ($resp->image)
                            <img src="{{ asset('storage/' . $resp->image) }}" class="resp-img" alt="Bukti Admin">
                            @endif
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-xs font-semibold text-dim mt-2"><i class="bi bi-hourglass mr-1"></i>Menunggu tanggapan petugas...</div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    </div>

    {{-- Leaflet Map Logic --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var map = L.map('map').setView([-6.200000, 106.816666], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            var marker = L.marker([-6.200000, 106.816666], {
                draggable: true
            }).addTo(map);

            function fetchAddress(lat, lng) {
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + lat + '&lon=' + lng, {
                    headers: {
                        'Accept-Language': 'id'
                    }
                })
                    .then(r => r.json())
                    .then(d => {
                        document.getElementById('location_text').value =
                            d.display_name || ('Koordinat: ' + lat.toFixed(5) + ', ' + lng.toFixed(5));
                    })
                    .catch(() => {
                        document.getElementById('location_text').value = 'Koordinat: ' + lat.toFixed(5) + ', ' + lng.toFixed(5);
                    });
            }

            // Try auto-locate silently on load (no prompt on deny)
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (pos) {
                    map.setView([pos.coords.latitude, pos.coords.longitude], 16);
                    marker.setLatLng([pos.coords.latitude, pos.coords.longitude]);
                    fetchAddress(pos.coords.latitude, pos.coords.longitude);
                }, function () { /* denied – keep default view */ }, { timeout: 6000 });
            }

            // Manual locate button
            var locateBtn = document.getElementById('locate-btn');
            if (locateBtn) {
                locateBtn.addEventListener('click', function () {
                    if (!navigator.geolocation) {
                        alert('Browser Anda tidak mendukung geolokasi.');
                        return;
                    }
                    locateBtn.classList.add('loading');
                    locateBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Mencari...';
                    navigator.geolocation.getCurrentPosition(
                        function (pos) {
                            var lat = pos.coords.latitude;
                            var lng = pos.coords.longitude;
                            map.setView([lat, lng], 17);
                            marker.setLatLng([lat, lng]);
                            fetchAddress(lat, lng);
                            locateBtn.classList.remove('loading');
                            locateBtn.innerHTML = '<i class="bi bi-crosshair2"></i> Lokasi Saya';
                        },
                        function () {
                            locateBtn.classList.remove('loading');
                            locateBtn.innerHTML = '<i class="bi bi-crosshair2"></i> Lokasi Saya';
                            alert('Akses lokasi ditolak. Aktifkan izin lokasi pada browser Anda.');
                        },
                        { enableHighAccuracy: true, timeout: 10000 }
                    );
                });
            }

            marker.on('dragend', () => {
                var c = marker.getLatLng();
                fetchAddress(c.lat, c.lng);
            });
            map.on('click', e => {
                marker.setLatLng(e.latlng);
                fetchAddress(e.latlng.lat, e.latlng.lng);
            });
        });
    </script>
@endsection