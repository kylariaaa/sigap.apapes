@extends('layouts.master')

@section('title', 'Tulis Pengaduan')

@section('content')
<div class="row">

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
                        <input
                            type="text"
                            name="location"
                            class="form-control"
                            placeholder="Contoh: Depan Pasar"
                        >
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
