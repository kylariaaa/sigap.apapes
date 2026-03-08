@extends('layouts.master')
@section('title', 'Dashboard Admin')

@section('content')

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4 mb-7">
        <div>
            <h1 class="text-3xl font-black brand-gradient mb-1">⚡ Executive Dashboard</h1>
            <p class="text-muted-futur text-sm">Pusat Kendali — Sistem Informasi Pengaduan Masyarakat</p>
        </div>
        <a href="{{ route('report.export') }}"
            class="btn-danger-futur inline-flex items-center gap-2 px-6 py-2.5 text-sm no-underline">
            <i class="bi bi-file-earmark-pdf"></i> Download PDF
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        @php
            $cards = [
                ['label' => 'Total Laporan', 'value' => $reports->count(), 'icon' => 'bi-stack', 'color' => '#00d4ff', 'glow' => 'rgba(0,212,255,0.12)', 'top' => 'linear-gradient(90deg,#00d4ff,#3a7bd5)'],
                ['label' => 'Menunggu', 'value' => $reports->where('status', '0')->count(), 'icon' => 'bi-hourglass-split', 'color' => '#ff4444', 'glow' => 'rgba(255,68,68,0.12)', 'top' => 'linear-gradient(90deg,#ff4444,#ff6b6b)'],
                ['label' => 'Diproses', 'value' => $reports->where('status', 'proses')->count(), 'icon' => 'bi-arrow-repeat', 'color' => '#ffcc00', 'glow' => 'rgba(255,204,0,0.12)', 'top' => 'linear-gradient(90deg,#ffcc00,#ffa600)'],
                ['label' => 'Selesai', 'value' => $reports->where('status', 'selesai')->count(), 'icon' => 'bi-check2-circle', 'color' => '#00ff88', 'glow' => 'rgba(0,255,136,0.12)', 'top' => 'linear-gradient(90deg,#00ff88,#00c566)'],
            ];
        @endphp

        @foreach($cards as $card)
            <div class="panel relative overflow-hidden transition-transform hover:-translate-y-1"
                style="border-top: 3px solid transparent; border-image: {{ $card['top'] }} 1;">
                <div class="p-5">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl mb-4"
                        style="background: {{ $card['glow'] }}; color: {{ $card['color'] }}">
                        <i class="bi {{ $card['icon'] }}"></i>
                    </div>
                    <div class="text-4xl font-black" style="color: {{ $card['color'] }}">
                        {{ $card['value'] }}
                    </div>
                    <div class="text-xs font-bold uppercase tracking-widest text-muted-futur mt-1">
                        {{ $card['label'] }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Data Table --}}
    <div class="panel">
        <div class="panel-header panel-header-cyan justify-between">
            <span class="flex items-center gap-2">
                <i class="bi bi-clipboard2-data"></i> Daftar Pengaduan Masuk
            </span>
            <span class="badge-pending text-xs">
                {{ $reports->where('status', '0')->count() }} Menunggu
            </span>
        </div>

        @if($reports->isEmpty())
            <div class="text-center py-16 text-muted-futur">
                <i class="bi bi-inbox text-5xl opacity-20 block mb-3"></i>
                <p>Belum ada laporan masuk.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table-futur">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Pelapor</th>
                            <th>Judul Laporan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $i => $item)
                            <tr>
                                <td>
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs font-bold text-neon-cyan"
                                        style="background: rgba(0,212,255,0.08);">
                                        {{ $i + 1 }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted-futur text-xs">
                                        <i class="bi bi-calendar3 mr-1"></i>
                                        {{ $item->created_at->format('d M Y') }}<br>
                                        <span class="opacity-60">{{ $item->created_at->format('H:i') }} WIB</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="font-semibold text-sm">
                                        <i class="bi bi-person-circle text-neon-cyan mr-1"></i>
                                        {{ $item->user->name }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-sm font-medium">{{ Str::limit($item->title, 45) }}</div>
                                    @if($item->location)
                                        <div class="text-xs text-muted-futur mt-0.5">
                                            <i class="bi bi-geo-alt mr-1"></i>{{ Str::limit($item->location, 35) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->status == '0')
                                        <span class="badge-pending"><i class="bi bi-clock"></i>Menunggu</span>
                                    @elseif ($item->status == 'proses')
                                        <span class="badge-proses"><i class="bi bi-arrow-repeat"></i>Diproses</span>
                                    @else
                                        <span class="badge-selesai"><i class="bi bi-check2"></i>Selesai</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('report.show', $item->id) }}"
                                        class="btn-neon-outline inline-flex items-center gap-1 px-3 py-1.5 text-xs no-underline">
                                        <i class="bi bi-eye"></i>Proses
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