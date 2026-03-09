@extends('layouts.master')
@section('title', 'Detail Laporan #' . $report->id)
@section('page-title', 'Detail Laporan #' . $report->id)

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('styles')
    <style>
        #detail-map {
            height: 180px;
            border-radius: 8px;
            border: 1px solid var(--color-border);
            margin-top: 0.5rem;
        }

        .response-item {
            position: relative;
            background: var(--color-surface-2);
            border: 1px solid var(--color-border);
            border-radius: 8px;
            padding: 0.85rem 1rem;
            margin-bottom: 0.6rem;
        }

        .response-img {
            max-height: 120px;
            border-radius: 6px;
            margin-top: 0.5rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @if($report->latitude && $report->longitude)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var map = L.map('detail-map', { zoomControl: false, scrollWheelZoom: false })
                    .setView([{{ $report->latitude }}, {{ $report->longitude }}], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '©OSM' }).addTo(map);
                L.marker([{{ $report->latitude }}, {{ $report->longitude }}]).addTo(map)
                    .bindPopup('{{ addslashes($report->location ?? "Lokasi") }}').openPopup();
            });
        </script>
    @endif
@endpush

@section('content')

    {{-- Back link --}}
    <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost btn-sm mb-5">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-5">

        {{-- ── LEFT: DETAIL ── --}}
        <div class="space-y-4">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-file-text"></i> Informasi Laporan
                    <span class="ml-auto">
                        @if ($report->status === '0')
                            <span class="badge badge-new">Menunggu</span>
                        @elseif ($report->status === 'proses')
                            <span class="badge badge-process">Diproses</span>
                        @else
                            <span class="badge badge-done">Selesai</span>
                        @endif
                    </span>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">

                    <div class="sm:col-span-2">
                        <div class="label">Judul Laporan</div>
                        <div class="font-bold text-base" style="color: var(--color-text-primary);">{{ $report->title }}
                        </div>
                    </div>

                    <div>
                        <div class="label">Pelapor</div>
                        <div class="text-sm">{{ $report->user->name }}</div>
                        <div class="text-dim text-xs">@{{ $report->user->username }}</div>
                    </div>

                    <div>
                        <div class="label">No. Telepon</div>
                        <div class="text-sm">{{ $report->user->telp ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="label">Tanggal Laporan</div>
                        <div class="text-sm">{{ $report->created_at->format('d F Y, H:i') }} WIB</div>
                    </div>

                    @if($report->location)
                        <div>
                            <div class="label">Lokasi</div>
                            <div class="text-sm">{{ $report->location }}</div>
                        </div>
                    @endif

                    <div class="sm:col-span-2">
                        <div class="label">Isi Keluhan</div>
                        <div class="text-sm leading-relaxed" style="color: var(--color-text-secondary);">
                            {{ $report->description }}</div>
                    </div>

                    @if($report->latitude && $report->longitude)
                        <div class="sm:col-span-2">
                            <div class="label">Titik Lokasi di Peta</div>
                            <div id="detail-map"></div>
                        </div>
                    @endif

                    @if ($report->image)
                        <div class="sm:col-span-2">
                            <div class="label">Foto Bukti Pelapor</div>
                            <img src="{{ asset('storage/' . $report->image) }}"
                                class="w-full object-cover rounded-xl mt-1 max-h-64"
                                style="border: 1px solid var(--color-border);" alt="Bukti Laporan">
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- ── RIGHT: ACTIONS ── --}}
        <div class="space-y-4">

            {{-- Status Update --}}
            <div class="card">
                <div class="card-header"><i class="bi bi-sliders"></i> Ubah Status</div>
                <div class="p-4">
                    <form action="{{ route('report.update', $report->id) }}" method="POST">
                        @csrf @method('PUT')
                        <select name="status" class="select mb-3" onchange="this.form.submit()">
                            <option value="0" {{ $report->status === '0' ? 'selected' : '' }}>⏳ Menunggu</option>
                            <option value="proses" {{ $report->status === 'proses' ? 'selected' : '' }}>🔄 Sedang Diproses
                            </option>
                            <option value="selesai" {{ $report->status === 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                        </select>
                        <p class="text-dim text-xs"><i class="bi bi-info-circle mr-1"></i>Otomatis tersimpan saat dipilih
                        </p>
                    </form>
                </div>
            </div>

            {{-- Send Response --}}
            <div class="card">
                <div class="card-header"><i class="bi bi-send"></i> Kirim Tanggapan</div>
                <div class="p-4">
                    <form action="{{ route('response.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="report_id" value="{{ $report->id }}">
                        <div class="mb-3">
                            <textarea name="response_text" class="input" rows="4"
                                placeholder="Tulis tanggapan untuk warga..." required style="resize:vertical;"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="label">Foto Bukti Tindak Lanjut</label>
                            <input type="file" name="image" class="input" accept="image/*"
                                style="padding: 0.45rem 0.9rem; cursor: pointer;">
                        </div>
                        <button type="submit" class="btn btn-primary btn-full">
                            <i class="bi bi-send"></i> Kirim
                        </button>
                    </form>
                </div>
            </div>

            {{-- Response history --}}
            <div class="card">
                <div class="card-header justify-between">
                    <span><i class="bi bi-chat-left-dots mr-1"></i>Riwayat Tanggapan</span>
                    <span class="text-accent font-bold text-xs">{{ $report->responses->count() }}</span>
                </div>
                <div class="p-4">
                    @forelse($report->responses->sortByDesc('created_at') as $res)
                        <div class="response-item">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-semibold text-accent">{{ $res->user->name }}</span>
                                <span class="text-dim text-xs">{{ $res->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm mb-0 leading-relaxed" style="color: var(--color-text-secondary);">
                                {{ $res->response_text }}</p>
                            @if($res->image)
                                <img src="{{ asset('storage/' . $res->image) }}" class="response-img" alt="Bukti Admin">
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-6 text-dim text-sm">
                            <i class="bi bi-chat-square"
                                style="font-size:1.8rem; opacity:0.2; display:block; margin-bottom:0.5rem;"></i>
                            Belum ada tanggapan
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

@endsection