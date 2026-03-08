@extends('layouts.master')
@section('title', 'Detail Laporan #' . $report->id)

@push('styles')
<style>
#detail-map { height: 200px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.10); margin-top: 0.5rem; }
.chat-item { position: relative; padding: 0.9rem 1rem; border-radius: 10px; margin-bottom: 0.75rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); }
.chat-item::before { content:''; position:absolute; left:0; top:0; bottom:0; width:3px; border-radius:3px 0 0 3px; background: linear-gradient(180deg, var(--color-neon-purple), var(--color-neon-cyan)); }
.chat-img { max-height: 130px; border-radius: 8px; margin-top: 0.6rem; border: 1px solid rgba(255,255,255,0.10); }
</style>
@endpush

@section('content')

{{-- Back button --}}
<a href="{{ route('admin.dashboard') }}"
   class="inline-flex items-center gap-2 text-muted-futur text-sm mb-5 no-underline hover:text-neon-cyan transition-colors">
    <i class="bi bi-arrow-left-circle"></i> Kembali ke Dashboard
</a>

@if(session('success'))
<div class="flash-success mb-5"><i class="bi bi-check-circle"></i>{{ session('success') }}</div>
@endif

{{-- 2-Column Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-5">

    {{-- ══ LEFT: DETAIL ══ --}}
    <div class="panel">
        <div class="panel-header panel-header-cyan">
            <i class="bi bi-file-earmark-text"></i>
            Detail Pengaduan #{{ $report->id }}
        </div>
        <div class="p-6">

            {{-- Status --}}
            <div class="mb-5">
                @if ($report->status === '0')
                    <span class="badge-pending"><i class="bi bi-clock"></i>Menunggu</span>
                @elseif ($report->status === 'proses')
                    <span class="badge-proses"><i class="bi bi-arrow-repeat"></i>Sedang Diproses</span>
                @else
                    <span class="badge-selesai"><i class="bi bi-check2-circle"></i>Selesai</span>
                @endif
            </div>

            {{-- Fields --}}
            @foreach([
                ['bi-card-heading', 'Judul Laporan',  $report->title,                       'large'],
                ['bi-person',       'Pelapor',         $report->user->name . ' (@' . $report->user->username . ')', ''],
                ['bi-telephone',    'No. Telepon',     $report->user->telp ?? '-',           ''],
                ['bi-calendar3',    'Tanggal Laporan', $report->created_at->format('d F Y, H:i') . ' WIB', ''],
            ] as [$icon, $lbl, $val, $size])
            <div class="mb-4">
                <div class="label-futur"><i class="bi {{ $icon }} mr-1"></i>{{ $lbl }}</div>
                <div class="text-sm {{ $size === 'large' ? 'text-base font-semibold' : '' }}" style="color: var(--color-primary-text); line-height:1.65;">
                    {{ $val }}
                </div>
            </div>
            @endforeach

            @if($report->location)
            <div class="mb-4">
                <div class="label-futur"><i class="bi bi-geo-alt mr-1"></i>Lokasi Kejadian</div>
                <div class="text-sm" style="color: var(--color-primary-text);">{{ $report->location }}</div>
            </div>
            @endif

            <div class="mb-4">
                <div class="label-futur"><i class="bi bi-chat-left-text mr-1"></i>Isi Keluhan</div>
                <div class="text-sm leading-relaxed" style="color: var(--color-primary-text);">{{ $report->description }}</div>
            </div>

            {{-- Map --}}
            @if($report->latitude && $report->longitude)
            <div class="mb-4">
                <div class="label-futur"><i class="bi bi-map mr-1"></i>Titik Lokasi</div>
                <div id="detail-map"></div>
            </div>
            @endif

            {{-- Report image --}}
            @if ($report->image)
            <div>
                <div class="label-futur"><i class="bi bi-image mr-1"></i>Foto Bukti</div>
                <img src="{{ asset('storage/' . $report->image) }}"
                     class="w-full object-cover rounded-xl mt-1 max-h-72"
                     style="border: 1px solid rgba(255,255,255,0.10);"
                     alt="Bukti Laporan">
            </div>
            @endif

        </div>
    </div>

    {{-- ══ RIGHT: RESPONSE ══ --}}
    <div class="panel self-start">
        <div class="panel-header panel-header-purple">
            <i class="bi bi-shield-check"></i> Verifikasi & Tanggapan
        </div>
        <div class="p-5">

            {{-- Status Form --}}
            <label class="label-futur">Ubah Status Laporan</label>
            <form action="{{ route('report.update', $report->id) }}" method="POST">
                @csrf @method('PUT')
                <select name="status" class="select-futur" onchange="this.form.submit()">
                    <option value="0"       {{ $report->status === '0'       ? 'selected':'' }}>⏳ Menunggu</option>
                    <option value="proses"  {{ $report->status === 'proses'  ? 'selected':'' }}>🔄 Sedang Diproses</option>
                    <option value="selesai" {{ $report->status === 'selesai' ? 'selected':'' }}>✅ Selesai</option>
                </select>
                <p class="text-muted-futur text-xs mt-1.5">
                    <i class="bi bi-info-circle mr-1"></i>Berubah otomatis saat dipilih
                </p>
            </form>

            <div class="divider-futur"></div>

            {{-- Response Form --}}
            <label class="label-futur">Kirim Tanggapan ke Warga</label>
            <form action="{{ route('response.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="report_id" value="{{ $report->id }}">
                <div class="mb-3">
                    <textarea name="response_text" class="input-futur" rows="4"
                              placeholder="Ketik balasan untuk warga..." required
                              style="resize:vertical;"></textarea>
                </div>
                <div class="mb-4">
                    <label class="label-futur">Foto Bukti (Opsional)</label>
                    <input type="file" name="image" class="input-futur" accept="image/*"
                           style="padding:0.5rem 1rem; cursor:pointer;">
                </div>
                <button type="submit"
                        class="btn-neon w-full inline-flex items-center justify-center gap-2 py-2.5 text-sm">
                    <i class="bi bi-send"></i> KIRIM TANGGAPAN
                </button>
            </form>

            <div class="divider-futur"></div>

            {{-- Chat history --}}
            <div class="flex items-center justify-between mb-3">
                <span class="label-futur m-0">Riwayat Percakapan</span>
                <span class="text-neon-cyan text-xs font-bold">{{ $report->responses->count() }}</span>
            </div>

            @if ($report->responses->count() > 0)
                @foreach ($report->responses->sortByDesc('created_at') as $res)
                <div class="chat-item">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-bold text-neon-purple">
                            <i class="bi bi-shield-fill mr-1"></i>{{ $res->user->name }}
                        </span>
                        <span class="text-muted-futur text-xs">{{ $res->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs leading-relaxed m-0" style="color: var(--color-primary-text);">
                        {{ $res->response_text }}
                    </p>
                    @if ($res->image)
                    <img src="{{ asset('storage/' . $res->image) }}" class="chat-img" alt="Bukti Admin">
                    @endif
                </div>
                @endforeach
            @else
            <div class="text-center py-8 text-muted-futur">
                <i class="bi bi-chat-square-dots text-4xl opacity-20 block mb-2"></i>
                <small>Belum ada tanggapan.</small>
            </div>
            @endif

        </div>
    </div>

</div>

@if($report->latitude && $report->longitude)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var map = L.map('detail-map', { zoomControl: false, scrollWheelZoom: false })
            .setView([{{ $report->latitude }}, {{ $report->longitude }}], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OSM' }).addTo(map);
        L.marker([{{ $report->latitude }}, {{ $report->longitude }}]).addTo(map)
            .bindPopup('<b>{{ addslashes($report->location ?? "Lokasi Laporan") }}</b>').openPopup();
    });
</script>
@endif
@endsection