@extends('layouts.master')

@section('title', 'Tulis Pengaduan')

@section('content')
<div class="row">
    {{-- TEKS BERJALAN --}}
<div class="alert alert-info" role="alert">
    <marquee direction="left" scrollamount="8">
        👋 Selamat Datang, <strong>{{ Auth::user()->name }}</strong>! 
        Silakan laporkan keluhan Anda dengan jujur dan sopan. 
        Admin kami siaga 24 Jam untuk melayani Anda.
    </marquee>
</div>

    {{-- KOLOM KIRI: FORM LAPOR --}}
    <div class="col-md-5">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                Tulis Laporan Baru
            </div>

            <div class="card-body">
                <form action="{{ route('user.lapor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Judul Laporan</label>
                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            placeholder="Contoh: Jalan Berlubang"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Isi Keluhan</label>
                        <textarea
                            name="description"
                            class="form-control"
                            rows="4"
                            required
                        ></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi Kejadian</label>
                        <div class="mb-3">
                            <label>Lokasi Kejadian</label>

                            {{-- INPUT ALAMAT --}}
                            <input type="text" name="location" id="location_text" class="form-control mb-2"
                                placeholder="Geser marker di peta, alamat akan muncul di sini..." required>

                            {{-- WADAH PETA --}}
                            <div id="map" style="height:300px; border-radius:10px; border:1px solid #ccc;"></div>

                            {{-- KOORDINAT TERSEMBUNYI --}}
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                        </div>

                        <script>
                        // Setting Awal Peta
                        var map = L.map('map').setView([-6.200000, 106.816666], 13);

                        // Ubin Peta OpenStreetMap
                        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap'
                        }).addTo(map);

                        // Marker
                        var marker = L.marker([-6.200000, 106.816666], { draggable: true }).addTo(map);

                        // Fungsi Reverse Geocoding
                        function getAddress(lat, lng) {

                            document.getElementById("location_text").value = "Sedang mencari nama lokasi...";

                            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                                .then(response => response.json())
                                .then(data => {

                                    document.getElementById("location_text").value = data.display_name;

                                })
                                .catch(error => {

                                    document.getElementById("location_text").value = "Alamat tidak ditemukan (Isi manual saja)";
                                    console.error(error);

                                });
                        }

                        // Saat Marker Digeser
                        marker.on('dragend', function () {

                            var coord = marker.getLatLng();

                            document.getElementById("latitude").value = coord.lat;
                            document.getElementById("longitude").value = coord.lng;

                            getAddress(coord.lat, coord.lng);
                        });

                        // Saat Peta Diklik
                        map.on('click', function (e) {

                            marker.setLatLng(e.latlng);

                            document.getElementById("latitude").value = e.latlng.lat;
                            document.getElementById("longitude").value = e.latlng.lng;

                            getAddress(e.latlng.lat, e.latlng.lng);

                        });
                        </script>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bukti Foto</label>
                        <input
                            type="file"
                            name="image"
                            class="form-control"
                        >
                        <small class="text-muted">
                            Format JPG/PNG, Maks 2MB
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        KIRIM LAPORAN
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: RIWAYAT LAPORAN --}}
    <div class="col-md-7">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                Riwayat Laporan Saya
            </div>

            <div class="card-body">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Judul</th>
                            <th>Status & Balasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($myReports as $item)
                            <tr>
                                <td>
                                    {{ $item->created_at->format('d/m/Y') }}
                                </td>

                                <td>
                                    <strong>{{ $item->title }}</strong><br>
                                    <small class="text-muted">
                                        {{ \Illuminate\Support\Str::limit($item->description, 30) }}
                                    </small>
                                </td>

                                <td>
                                    {{-- STATUS --}}
                                    @if ($item->status === '0')
                                        <span class="badge bg-danger">Menunggu</span>
                                    @elseif ($item->status === 'proses')
                                        <span class="badge bg-warning text-dark">Diproses</span>
                                    @else
                                        <span class="badge bg-success">Selesai</span>
                                    @endif

                                    {{-- BALASAN ADMIN --}}
                                    @if ($item->responses->count() > 0)
                                        <div class="mt-2 p-2 border rounded bg-light">
                                            <small>
                                                <strong>Admin:</strong>
                                                {{ $item->responses->last()->response_text }}
                                            </small>
                                            @if ($item->responses->last()->image)
                                                <div class="mt-1">
                                                    <img
                                                        src="{{ asset('storage/' . $item->responses->last()->image) }}"
                                                        class="img-fluid rounded"
                                                        style="max-height: 150px;"
                                                        alt="Bukti dari Admin"
                                                    >
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($myReports->isEmpty())
                    <p class="text-center text-muted mb-0">
                        Belum ada laporan.
                    </p>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
