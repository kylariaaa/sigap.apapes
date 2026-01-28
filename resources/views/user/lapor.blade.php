@extends('layouts.master')
@section('title', 'Tulis Pengaduan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">

        {{-- Pesan Sukses --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- Pesan Error Validasi (Global) --}}
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tulis Laporan Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('user.lapor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Judul Laporan</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}" placeholder="Contoh: Jalan Berlubang" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Isi Keluhan</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                            rows="5" required>{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi Kejadian</label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                            value="{{ old('location') }}" placeholder="Contoh: Depan Pasar Cibinong" required>
                        @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bukti Foto</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                        <div class="form-text">Format: JPG, JPEG, PNG. Maksimal 2MB.</div>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">KIRIM LAPORAN</button>
                        <a href="{{ url()->previous() }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mt-4">
    <div class="card-header bg-success text-white">
        Riwayat Laporan Saya
    </div>

    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Judul</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($myReports as $item)
                    <tr>
                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                        <td>{{ $item->title }}</td>
                        <td>
                            @if ($item->status == 0)
                                <span class="badge bg-danger">Menunggu</span>
                            @elseif ($item->status == 1)
                                <span class="badge bg-warning">Diproses</span>
                            @else
                                <span class="badge bg-success">Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            Belum ada laporan yang dibuat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
    </div>
</div>
@endsection
