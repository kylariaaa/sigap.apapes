@extends('layouts.master')
@section('title', 'Tulis Pengaduan')

@push('styles')
<style>
/* Ticker animation */
@keyframes ticker { 0% { transform: translateX(100%); } 100% { transform: translateX(-100%); } }
.ticker-scroll { display: inline-block; white-space: nowrap; animation: ticker 25s linear infinite; }

/* Map */
#map { height: 220px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.10); margin-top: 0.5rem; }

/* Timeline */
.tl-wrap { padding-left: 1.25rem; border-left: 2px solid rgba(0,212,255,0.18); }
.tl-dot {
    position: absolute; left: -1.55rem; top: 5px;
    width: 10px; height: 10px;
    background: var(--color-neon-cyan); border-radius: 9999px;
    border: 2px solid var(--color-deep);
}
.tl-box { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 10px; padding: 0.65rem 0.85rem; }
.tl-img { max-height: 80px; border-radius: 6px; margin-top: 0.5rem; border: 1px solid rgba(255,255,255,0.10); }

/* Slide-up for success toast */
@keyframes fadeInDown { from { opacity:0; transform: translateY(-10px); } to { opacity:1; transform: translateY(0); } }
.toast-anim { animation: fadeInDown 0.4s ease both; }

/* History table */
.hist-td { padding: 0.85rem 1rem; vertical-align: top; border-bottom: 1px solid rgba(255,255,255,0.04); }
.hist-th { padding: 0.75rem 1rem; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.08em; font-weight: 700; color: var(--color-muted-text); border-bottom: 1px solid rgba(255,255,255,0.08); text-align: left; }
</style>
@endpush

@section('content')

{{-- Ticker --}}
<div class="flex items-center gap-3 px-4 py-2 rounded-full mb-6 overflow-hidden text-sm"
     style="background: rgba(0,212,255,0.06); border: 1px solid rgba(0,212,255,0.18); color: var(--color-neon-cyan);">
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

{{-- 2-col layout --}}
<div class="grid grid-cols-1 lg:grid-cols-[400px_1fr] gap-5">

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

        <div class="panel">
            <div class="panel-header panel-header-cyan">
                <i class="bi bi-megaphone-fill"></i> Tulis Laporan Baru
            </div>
            <div class="p-5">
                <form action="{{ route('user.lapor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="label-futur"><i class="bi bi-card-heading mr-1"></i>Judul Laporan</label>
                        <input type="text" name="title" class="input-futur"
                               placeholder="Contoh: Jalan Berlubang di RT 05"
                               value="{{ old('title') }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="label-futur"><i class="bi bi-chat-left-text mr-1"></i>Isi Keluhan</label>
                        <textarea name="description" class="input-futur" rows="4"
                                  placeholder="Jelaskan masalah secara detail..."
                                  required style="resize:vertical;">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="label-futur"><i class="bi bi-geo-alt mr-1"></i>Lokasi Kejadian</label>
                        <input type="text" name="location" id="location_text" class="input-futur"
                               placeholder="Geser marker di peta untuk deteksi alamat..."
                               value="{{ old('location') }}" required readonly style="cursor:default; margin-bottom:.5rem;">
                        <div id="map"></div>
                        <p class="text-muted-futur text-xs mt-1.5">
                            <i class="bi bi-info-circle mr-1"></i>Klik atau geser marker untuk menentukan titik koordinat
                        </p>
                        <input type="hidden" name="latitude"  id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                    </div>

                    <div class="mb-5">
                        <label class="label-futur"><i class="bi bi-image mr-1"></i>Foto Bukti (Opsional)</label>
                        <input type="file" name="image" class="input-futur" accept="image/*"
                               style="padding:0.5rem 1rem; cursor:pointer;">
                        <p class="text-muted-futur text-xs mt-1">Format JPG/PNG, maks. 2MB</p>
                    </div>

                    <button type="submit"
                            class="btn-neon w-full inline-flex items-center justify-center gap-2 py-3 text-sm">
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
                        <th class="hist-th w-24">Tanggal</th>
                        <th class="hist-th">Laporan</th>
                        <th class="hist-th w-40">Status & Balasan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($myReports as $item)
                    <tr>
                        <td class="hist-td text-muted-futur text-xs whitespace-nowrap">
                            {{ $item->created_at->format('d M Y') }}
                        </td>
                        <td class="hist-td">
                            <div class="font-semibold text-sm">{{ $item->title }}</div>
                            <div class="text-muted-futur text-xs mt-0.5">
                                {{ \Illuminate\Support\Str::limit($item->description, 60) }}
                            </div>
                            @if($item->location)
                            <div class="text-xs text-neon-cyan mt-0.5">
                                <i class="bi bi-geo-alt mr-1"></i>{{ \Illuminate\Support\Str::limit($item->location, 40) }}
                            </div>
                            @endif
                        </td>
                        <td class="hist-td">
                            {{-- Badge --}}
                            <div class="mb-2">
                                @if ($item->status == '0')
                                    <span class="badge-pending" style="font-size:0.68rem;">⏳ Menunggu</span>
                                @elseif($item->status == 'proses')
                                    <span class="badge-proses" style="font-size:0.68rem;">🔄 Diproses</span>
                                @else
                                    <span class="badge-selesai" style="font-size:0.68rem;">✅ Selesai</span>
                                @endif
                            </div>
                            {{-- Timeline --}}
                            @if ($item->responses->count() > 0)
                            <div class="tl-wrap">
                                @foreach ($item->responses as $resp)
                                <div class="relative mb-3">
                                    <span class="tl-dot"></span>
                                    <div class="tl-box">
                                        <div class="text-neon-cyan text-xs font-semibold">
                                            {{ $resp->created_at->format('d M Y, H:i') }}
                                        </div>
                                        <p class="text-xs mt-0.5" style="color: var(--color-primary-text);">
                                            <strong>Petugas:</strong> {{ $resp->response_text }}
                                        </p>
                                        @if ($resp->image)
                                        <img src="{{ asset('storage/' . $resp->image) }}" class="tl-img" alt="Bukti">
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <span class="text-muted-futur text-xs italic">Belum ada balasan.</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>

{{-- Leaflet Map Logic --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    var map = L.map('map').setView([-6.200000, 106.816666], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19, attribution: '© OpenStreetMap'
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

    marker.on('dragend', () => { var c = marker.getLatLng(); fetchAddress(c.lat, c.lng); });
    map.on('click',  e => { marker.setLatLng(e.latlng); fetchAddress(e.latlng.lat, e.latlng.lng); });
});
</script>
@endsection