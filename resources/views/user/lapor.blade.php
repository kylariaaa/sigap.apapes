@extends('layouts.master')
@section('title', 'Tulis Pengaduan')

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('styles')
<style>
    /* Ticker */
    @keyframes ticker {
        0%   { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
    .ticker-scroll {
        display: inline-block;
        white-space: nowrap;
        animation: ticker 25s linear infinite;
    }

    /* Map */
    .map-wrap { position: relative; margin-top: 0.5rem; }
    #map {
        height: 240px;
        border-radius: 8px;
        border: 2px solid var(--color-border-strong);
    }

    /* Locate Me button */
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
        border-radius: 999px;
        border: 2px solid var(--color-accent, #0284c7);
        background: rgba(2, 132, 199, 0.12);
        color: var(--color-accent, #0284c7);
        cursor: pointer;
        backdrop-filter: blur(6px);
        box-shadow: 0 2px 12px rgba(2, 132, 199, 0.25);
        transition: background 0.2s, transform 0.15s;
        white-space: nowrap;
    }
    #locate-btn:hover  { background: rgba(2, 132, 199, 0.25); }
    #locate-btn:active { transform: translateX(-50%) scale(0.96); }
    #locate-btn.loading { opacity: 0.7; pointer-events: none; }

    /* ── Chat History ── */
    .history-item {
        padding: 1.25rem;
        border-bottom: 2px solid var(--color-border);
    }
    .history-item:last-child { border-bottom: none; }

    .chat-area {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
        margin-top: 0.75rem;
    }

    /* User bubble (left) */
    .bubble-user {
        display: flex;
        align-items: flex-end;
        gap: 0.6rem;
    }
    .bubble-user .bub-avatar {
        width: 30px; height: 30px;
        border-radius: 9999px;
        background: var(--color-surface-2);
        border: 2px solid var(--color-border-strong);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem;
        color: var(--color-text-secondary);
        flex-shrink: 0;
    }
    .bubble-user .bub-content {
        background: var(--color-surface-2);
        border: 1px solid var(--color-border-strong);
        border-radius: 0 10px 10px 10px;
        padding: 0.65rem 0.9rem;
        max-width: 82%;
    }

    /* Admin bubble (right) */
    .bubble-admin {
        display: flex;
        align-items: flex-end;
        flex-direction: row-reverse;
        gap: 0.6rem;
    }
    .bubble-admin .bub-avatar {
        width: 30px; height: 30px;
        border-radius: 9999px;
        background: var(--color-accent-dim);
        border: 2px solid var(--color-accent-border);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem;
        color: var(--color-accent-light);
        flex-shrink: 0;
    }
    .bubble-admin .bub-content {
        background: var(--color-accent-dim);
        border: 1px solid var(--color-accent-border);
        border-radius: 10px 0 10px 10px;
        padding: 0.65rem 0.9rem;
        max-width: 82%;
        text-align: right;
    }
    .bub-meta {
        font-size: 0.68rem;
        font-weight: 600;
        opacity: 0.6;
        margin-bottom: 0.25rem;
    }
    .bub-text {
        font-size: 0.83rem;
        line-height: 1.5;
        color: var(--color-text-primary);
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
    document.addEventListener('DOMContentLoaded', function () {
        var map = L.map('map').setView([-6.200000, 106.816666], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        var marker = L.marker([-6.200000, 106.816666], { draggable: true }).addTo(map);

        function fetchAddress(lat, lng) {
            document.getElementById('latitude').value  = lat;
            document.getElementById('longitude').value = lng;
            fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + lat + '&lon=' + lng, {
                headers: { 'Accept-Language': 'id' }
            })
            .then(r => r.json())
            .then(d => {
                document.getElementById('location_text').value =
                    d.display_name || ('Koordinat: ' + lat.toFixed(5) + ', ' + lng.toFixed(5));
            })
            .catch(() => {
                document.getElementById('location_text').value =
                    'Koordinat: ' + lat.toFixed(5) + ', ' + lng.toFixed(5);
            });
        }

        // Auto-locate on load
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (pos) {
                map.setView([pos.coords.latitude, pos.coords.longitude], 16);
                marker.setLatLng([pos.coords.latitude, pos.coords.longitude]);
                fetchAddress(pos.coords.latitude, pos.coords.longitude);
            }, function () { /* denied — keep default */ }, { timeout: 6000 });
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
                        var lat = pos.coords.latitude, lng = pos.coords.longitude;
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
@endpush

@section('content')

    {{-- Ticker --}}
    <div class="flex items-center gap-3 px-4 py-2.5 rounded-full mb-6 overflow-hidden text-sm"
        style="background: var(--color-accent-dim); border: 2px solid var(--color-accent-border); color: var(--color-accent);">
        <i class="bi bi-broadcast shrink-0"></i>
        <div class="overflow-hidden flex-1">
            <span class="ticker-scroll">
                👋 Selamat Datang, <strong>{{ Auth::user()?->name }}</strong>! &nbsp;|&nbsp;
                Laporkan keluhan Anda dengan jujur dan sopan. &nbsp;|&nbsp;
                Tim SIGAP siaga 24 jam untuk melayani Anda. &nbsp;|&nbsp;
                Laporan Anda akan segera ditindaklanjuti. ⚡
            </span>
        </div>
    </div>

    {{-- Page title --}}
    <div class="mb-6">
        <h1 class="font-extrabold text-2xl" style="color: var(--color-text-primary);">Pengaduan Warga</h1>
        <p class="text-secondary text-sm mt-1">Sampaikan laporan Anda, kami siap menindaklanjuti.</p>
    </div>

    {{-- 2-col layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-[420px_1fr] xl:grid-cols-[450px_1fr] gap-6">

        {{-- ══ LEFT: FORM ══ --}}
        <div>
            @if (session('success'))
                <div class="alert-success mb-4">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert-error mb-4">
                    <i class="bi bi-exclamation-triangle mr-2"></i>
                    <ul class="mt-1 ps-4 list-disc text-sm">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-header" style="color: var(--color-accent); border-left: 3px solid var(--color-accent);">
                    <i class="bi bi-megaphone-fill"></i> Tulis Laporan Baru
                </div>
                <div class="p-5">
                    <form action="{{ route('user.lapor.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Judul --}}
                        <div class="mb-4">
                            <label class="label"><i class="bi bi-card-heading mr-1"></i>Judul Laporan <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="input"
                                placeholder="Contoh: Jalan Berlubang di RT 05"
                                value="{{ old('title') }}" required>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-4">
                            <label class="label"><i class="bi bi-chat-left-text mr-1"></i>Isi Keluhan <span class="text-danger">*</span></label>
                            <textarea name="description" class="input" rows="4"
                                placeholder="Jelaskan masalah secara detail..." required
                                style="resize:vertical;">{{ old('description') }}</textarea>
                        </div>

                        {{-- Lokasi + Peta --}}
                        <div class="mb-4">
                            <label class="label"><i class="bi bi-geo-alt mr-1"></i>Lokasi Kejadian <span class="text-danger">*</span></label>
                            <input type="text" name="location" id="location_text" class="input"
                                placeholder="Geser marker di peta untuk deteksi alamat..."
                                value="{{ old('location') }}" required readonly
                                style="cursor:default; margin-bottom:.5rem; background: var(--color-surface-2);">
                            <div class="map-wrap">
                                <div id="map"></div>
                                <button type="button" id="locate-btn">
                                    <i class="bi bi-crosshair2"></i> Lokasi Saya
                                </button>
                            </div>
                            <p class="text-dim text-xs mt-1.5">
                                <i class="bi bi-info-circle mr-1"></i>Klik atau geser marker untuk menentukan titik koordinat
                            </p>
                            <input type="hidden" name="latitude"  id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                        </div>

                        {{-- Foto --}}
                        <div class="mb-5">
                            <label class="label"><i class="bi bi-image mr-1"></i>Foto Bukti <span class="text-dim">(Opsional)</span></label>
                            <input type="file" name="image" class="input" accept="image/*"
                                style="padding:0.5rem 1rem; cursor:pointer;">
                            <p class="text-dim text-xs mt-1">Format JPG/PNG, maks. 2MB</p>
                        </div>

                        <button type="submit" class="btn btn-primary btn-full">
                            <i class="bi bi-send-fill"></i> KIRIM LAPORAN
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ══ RIGHT: HISTORY ══ --}}
        <div>
            <div class="card">
                <div class="card-header flex justify-between" style="color: var(--color-ok); border-left: 3px solid var(--color-ok);">
                    <span><i class="bi bi-clock-history"></i> Riwayat Laporan Saya</span>
                    <span class="text-ok font-bold">{{ $myReports->count() }} Laporan</span>
                </div>

                @if ($myReports->isEmpty())
                    <div class="text-center py-16">
                        <i class="bi bi-folder-x" style="font-size: 3rem; opacity:0.3; display:block; margin-bottom:1rem; color:var(--color-text-dim);"></i>
                        <p class="text-sm font-medium text-secondary">Anda belum pernah mengirim laporan.</p>
                    </div>
                @else
                    <div>
                        @foreach ($myReports as $item)
                        <div class="history-item">
                            {{-- Header --}}
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <div class="font-extrabold text-base mb-1" style="color: var(--color-text-primary);">{{ $item->title }}</div>
                                    <div class="text-xs text-secondary flex items-center gap-2 font-medium flex-wrap">
                                        <span><i class="bi bi-calendar3 mr-1"></i>{{ $item->created_at->format('d M Y, H:i') }}</span>
                                        @if($item->location)
                                        <span class="text-dim text-xs truncate max-w-[200px]" title="{{ $item->location }}">
                                            • <i class="bi bi-geo-alt-fill mx-1"></i>{{ Str::limit($item->location, 40) }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2">
                                    @if ($item->status == '0')
                                        <span class="badge badge-new">⏳ Menunggu</span>
                                    @elseif($item->status == 'proses')
                                        <span class="badge badge-process">🔄 Diproses</span>
                                    @else
                                        <span class="badge badge-done">✅ Selesai</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Chat area --}}
                            <div class="chat-area">

                                {{-- User bubble (left) --}}
                                <div class="bubble-user">
                                    <div class="bub-avatar"><i class="bi bi-person-fill"></i></div>
                                    <div class="bub-content">
                                        <div class="bub-meta"><i class="bi bi-calendar3 mr-1"></i>{{ $item->created_at->format('d M Y, H:i') }} &middot; Anda</div>
                                        <div class="bub-text">{{ \Illuminate\Support\Str::limit($item->description, 160) }}</div>
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="Foto Laporan"
                                                 class="resp-img" style="display:block;">
                                        @endif
                                    </div>
                                </div>

                                {{-- Admin bubbles (right) --}}
                                @if ($item->responses->count() > 0)
                                    @foreach ($item->responses as $resp)
                                    <div class="bubble-admin">
                                        <div class="bub-avatar"><i class="bi bi-shield-fill"></i></div>
                                        <div class="bub-content">
                                            <div class="bub-meta" style="color:var(--color-accent);"><i class="bi bi-calendar2 mr-1"></i>{{ $resp->created_at->format('d M Y, H:i') }} &middot; Petugas</div>
                                            <div class="bub-text" style="color:var(--color-text-primary);">{{ $resp->response_text }}</div>
                                            @if ($resp->image)
                                                <img src="{{ asset('storage/' . $resp->image) }}" class="resp-img" alt="Bukti" style="margin-left:auto;">
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-xs font-semibold text-dim" style="text-align:center; opacity:0.6; padding:0.5rem 0;">
                                        <i class="bi bi-hourglass mr-1"></i>Menunggu tanggapan petugas...
                                    </div>
                                @endif

                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

@endsection