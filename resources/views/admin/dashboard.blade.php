@extends('layouts.master')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard Pengaduan')

@section('content')

    {{-- Welcome strip --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-bold text-lg" style="color: var(--color-text-primary);">Ringkasan Pengaduan</h1>
            <p class="text-secondary text-sm">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <a href="{{ route('report.export') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </a>
    </div>

    {{-- Stat tiles --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @php
            $tiles = [
                ['total', $reports->count(), 'Semua Laporan', 'bi-stack', 'text-accent', 'bg-s2'],
                ['pending', $reports->where('status', '0')->count(), 'Menunggu', 'bi-hourglass-split', 'text-danger', 'bg-s2'],
                ['proses', $reports->where('status', 'proses')->count(), 'Diproses', 'bi-arrow-repeat', 'text-warn', 'bg-s2'],
                ['selesai', $reports->where('status', 'selesai')->count(), 'Selesai', 'bi-check2-circle', 'text-ok', 'bg-s2'],
            ];
        @endphp

        @foreach($tiles as [$key, $val, $lbl, $icon, $color, $bg])
            <div class="stat-tile">
                <div class="flex items-start justify-between mb-3">
                    <span class="{{ $color }}" style="font-size: 1.1rem;"><i class="bi {{ $icon }}"></i></span>
                </div>
                <div class="stat-num {{ $color }}">{{ $val }}</div>
                <div class="stat-label">{{ $lbl }}</div>
            </div>
        @endforeach
    </div>

    {{-- Table panel --}}
    <div class="card">
        <div class="card-header justify-between">
            <span><i class="bi bi-list-ul mr-2"></i>Daftar Laporan Masuk</span>
            <span class="badge badge-new">{{ $reports->where('status', '0')->count() }} Baru</span>
        </div>

        @if($reports->isEmpty())
            <div class="text-center py-16 text-secondary">
                <i class="bi bi-inbox" style="font-size:2.5rem; opacity:0.2; display:block; margin-bottom:0.75rem;"></i>
                <p class="text-sm">Belum ada laporan masuk.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Pelapor</th>
                            <th>Laporan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $i => $item)
                            <tr>
                                <td>
                                    <span class="text-dim font-mono text-xs">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <div class="text-xs" style="color: var(--color-text-secondary);">
                                        {{ $item->created_at->format('d M Y') }}
                                    </div>
                                    <div class="text-dim text-xs">{{ $item->created_at->format('H:i') }}</div>
                                </td>
                                <td>
                                    <div class="text-sm font-medium">{{ $item->user->name }}</div>
                                    <div class="text-dim text-xs">@{{ $item->user->username }}</div>
                                </td>
                                <td class="max-w-xs">
                                    <div class="text-sm font-medium truncate" style="max-width:220px;">{{ $item->title }}</div>
                                    @if($item->location)
                                        <div class="text-dim text-xs flex items-center gap-1 mt-0.5">
                                            <i class="bi bi-geo-alt"></i>
                                            <span class="truncate" style="max-width:180px;">{{ $item->location }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->status == '0')
                                        <span class="badge badge-new">Menunggu</span>
                                    @elseif ($item->status == 'proses')
                                        <span class="badge badge-process">Diproses</span>
                                    @else
                                        <span class="badge badge-done">Selesai</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('report.show', $item->id) }}" class="btn btn-secondary btn-sm">
                                        <i class="bi bi-arrow-right"></i> Buka
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection