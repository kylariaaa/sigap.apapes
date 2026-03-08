@extends('layouts.master')
@section('title', 'Tulis Pengaduan')

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('styles')
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

{{-- Welcome text --}}
<div class="mb-6">
    <h1 class="font-extrabold text-2xl" style="color: var(--color-text-primary);">Pengaduan Warga</h1>
    <p class="text-secondary text-sm mt-1">Sampaikan laporan Anda, kami siap menindaklanjuti.</p>
</div>

{{-- 2-COL LAYOUT: LEFT = FORM, RIGHT = HISTORY --}}
<div class="grid grid-cols-1 lg:grid-cols-[420px_1fr] xl:grid-cols-[450px_1fr] gap-6">

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

        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-square"></i> Tulis Laporan Baru</div>
            <div class="p-5">
                <form action="{{ route('user.lapor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="label">Judul Laporan <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="input"
                            placeholder="Contoh: Lampu Jalan Mati di Jl. Merdeka"
                            value="{{ old('title') }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="label">Detail Kejadian <span class="text-danger">*</span></label>
                        <textarea name="description" class="input" rows="4"
                            placeholder="Jelaskan secara rinci..."
                            required style="resize:vertical;">{{ old('description') }}</textarea>
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

                    <div class="mb-6">
                        <label class="label">Unggah Foto Bukti <span class="text-dim">(Opsional)</span></label>
                        <input type="file" name="image" class="input" accept="image/*"
                            style="padding:0.45rem 1rem; cursor:pointer; background: #fff;">
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
            <div class="card-header flex justify-between">
                <span><i class="bi bi-clock-history"></i> Riwayat Pengaduan Anda</span>
                <span class="badge badge-process">{{ $myReports->count() }} Laporan</span>
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

@endsection