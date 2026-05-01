@extends('layouts.mahasiswa')
 
@section('title', 'Riwayat Absensi')
@section('page-title', 'Riwayat Absensi')
@section('page-sub', 'Data kehadiran Anda per mata kuliah · Semester ' . $semesterAktif)
 
@push('styles')
<style>
.absen-mini {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 16px 20px;
}
.absen-mini-label {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .8px;
    margin-bottom: 6px;
}
.absen-mini-val {
    font-size: 26px; font-weight: 800;
    line-height: 1; color: var(--text-1);
}
.absen-mini-unit {
    font-size: 13px; font-weight: 500;
    color: var(--text-2); margin-left: 2px;
}
.absen-bar {
    height: 4px; background: #F1F5F9;
    border-radius: 2px; margin-top: 10px; overflow: hidden;
}
.absen-bar-fill { height: 100%; border-radius: 2px; }
.absen-warn {
    font-size: 11px; font-weight: 600;
    margin-top: 5px;
}
</style>
@endpush
 
@section('content')
 
{{-- Hitung dulu sebelum banner --}}
@php
    $sumHadir = $absensis->sum('jam_hadir');
    $sumIzin  = $absensis->sum('jam_izin');
    $sumSakit = $absensis->sum('jam_sakit');
    $sumAlpha = $absensis->sum('jam_alpha');
    $sumAll   = $sumHadir + $sumIzin + $sumSakit + $sumAlpha;
    $pctHadir = $sumAll > 0 ? round($sumHadir / $sumAll * 100) : 0;
@endphp
 
{{-- ══ BANNER ══ --}}
@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg, #1E3A5F 0%, #0891B2 55%, #22D3EE 100%)',
    'icon'         => 'bi-calendar2-check-fill',
    'title'        => 'Riwayat Absensi',
    'sub'          => 'Data kehadiran per mata kuliah · Semester ' . $semesterAktif,
    'chips'        => [
        ['icon' => 'bi-person-check-fill', 'label' => $sumHadir . ' Jam Hadir'],
        ['icon' => 'bi-x-circle-fill',     'label' => $sumAlpha . ' Jam Alpha'],
        ['icon' => 'bi-percent',           'label' => $pctHadir . '% Kehadiran'],
        ['icon' => $sumAlpha >= 14 ? 'bi-exclamation-triangle-fill' : 'bi-shield-check-fill',
                                           'label' => $sumAlpha >= 18 ? 'Status: Kritis!' : ($sumAlpha >= 14 ? 'Status: Waspada' : 'Status: Aman')],
    ],
    'badge_num'    => $pctHadir . '%',
    'badge_label'  => "Total\nKehadiran",
    'badge2_num'   => $sumAlpha . 'j',
    'badge2_label' => "Total\nAlpha",
])
 
{{-- ══ FILTER SEMESTER ══ --}}
<div style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
 
    {{-- Kiri: info semester --}}
    <div>
        <div style="font-size:15px;font-weight:700;color:var(--text-1);">
            Absensi Semester {{ $semester }}
            @if($semester == $semesterAktif)
                <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700;background:#DCFCE7;color:#15803D;margin-left:6px;">
                    <i class="bi bi-circle-fill" style="font-size:7px;"></i> Aktif
                </span>
            @endif
        </div>
        <div style="font-size:12px;color:var(--text-2);margin-top:2px;">
            {{ $absensis->count() }} mata kuliah ·
            Kehadiran: <strong style="color:{{ $pctHadir >= 75 ? '#22C55E' : '#EF4444' }};">{{ $pctHadir }}%</strong> ·
            Alpha: <strong style="color:{{ $sumAlpha >= 18 ? '#EF4444' : ($sumAlpha >= 14 ? '#F59E0B' : 'var(--text-1)') }};">{{ $sumAlpha }} jam</strong>
        </div>
    </div>
 
    {{-- Kanan: pills semester --}}
    <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
        <span style="font-size:11.5px;color:var(--text-3);font-weight:600;margin-right:4px;">Semester:</span>
        @foreach($semesterList as $sem)
        <a href="{{ route('mahasiswa.absensi', ['semester' => $sem]) }}"
           style="
               display:inline-flex;align-items:center;justify-content:center;
               min-width:36px;height:32px;padding:0 12px;
               border-radius:20px;font-size:12.5px;font-weight:700;
               text-decoration:none;transition:all .15s;
               {{ $sem == $semester
                   ? 'background:var(--blue);color:#fff;box-shadow:0 2px 8px rgba(37,99,235,.3);'
                   : 'background:#F1F5F9;color:var(--text-2);border:1px solid var(--border);' }}
           "
           onmouseover="{{ $sem != $semester ? "this.style.background='#E2E8F0'" : '' }}"
           onmouseout="{{ $sem != $semester ? "this.style.background='#F1F5F9'" : '' }}">
            {{ $sem }}
        </a>
        @endforeach
    </div>
</div>
 
{{-- Summary cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="absen-mini">
            <div class="absen-mini-label" style="color:#22C55E;">Hadir</div>
            <div class="absen-mini-val">{{ $sumHadir }}<span class="absen-mini-unit">jam</span></div>
            <div class="absen-bar">
                <div class="absen-bar-fill" style="width:{{ $sumAll>0 ? round($sumHadir/$sumAll*100) : 0 }}%;background:#22C55E;"></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="absen-mini">
            <div class="absen-mini-label" style="color:#FBBF24;">Izin</div>
            <div class="absen-mini-val">{{ $sumIzin }}<span class="absen-mini-unit">jam</span></div>
            <div class="absen-bar">
                <div class="absen-bar-fill" style="width:{{ $sumAll>0 ? round($sumIzin/$sumAll*100) : 0 }}%;background:#FBBF24;"></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="absen-mini">
            <div class="absen-mini-label" style="color:#3B82F6;">Sakit</div>
            <div class="absen-mini-val">{{ $sumSakit }}<span class="absen-mini-unit">jam</span></div>
            <div class="absen-bar">
                <div class="absen-bar-fill" style="width:{{ $sumAll>0 ? round($sumSakit/$sumAll*100) : 0 }}%;background:#3B82F6;"></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="absen-mini" style="{{ $sumAlpha >= 14 ? 'border-left:3px solid #EF4444;' : '' }}">
            <div class="absen-mini-label" style="color:#EF4444;">Alpha</div>
            <div class="absen-mini-val" style="color:{{ $sumAlpha >= 14 ? '#EF4444' : 'var(--text-1)' }};">
                {{ $sumAlpha }}<span class="absen-mini-unit">/ 18 jam</span>
            </div>
            <div class="absen-bar">
                <div class="absen-bar-fill" style="width:{{ min(($sumAlpha/18*100),100) }}%;background:{{ $sumAlpha>=18 ? '#EF4444' : ($sumAlpha>=14 ? '#F59E0B' : '#22C55E') }};"></div>
            </div>
            @if($sumAlpha >= 14)
            <div class="absen-warn" style="color:{{ $sumAlpha>=18 ? '#EF4444' : '#F59E0B' }};">
                {{ $sumAlpha >= 18 ? '⛔ Melewati batas UAS!' : '⚠ ' . (18-$sumAlpha) . ' jam lagi batas' }}
            </div>
            @endif
        </div>
    </div>
</div>
 
{{-- Tabel --}}
<div class="card-white" style="padding:20px 22px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;gap:12px;flex-wrap:wrap;">
        <div>
            <div style="font-size:15px;font-weight:700;color:var(--text-1);">Riwayat Absensi per Mata Kuliah</div>
            <div style="font-size:12px;color:var(--text-2);margin-top:2px;">
                Semester {{ $semesterAktif }} · {{ $absensis->count() }} mata kuliah ·
                Total kehadiran: <strong style="color:{{ $pctHadir>=75 ? '#22C55E' : '#EF4444' }};">{{ $pctHadir }}%</strong>
            </div>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Cari mata kuliah..." id="searchAbsensi">
            </div>
            <div class="filter-wrap">
                <button class="btn-filter" id="filterAbsenBtn">
                    <i class="bi bi-sliders2" style="font-size:12px;"></i> Filter
                </button>
                <div class="filter-menu" id="filterAbsenMenu">
                    <div class="filter-menu-label">Filter Status</div>
                    <div class="filter-opt active" data-val="">Semua</div>
                    <div class="filter-opt" data-val="aman">✓ Aman</div>
                    <div class="filter-opt" data-val="waspada">⚠ Waspada</div>
                    <div class="filter-opt" data-val="kritis">⛔ Kritis</div>
                </div>
            </div>
        </div>
    </div>
 
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="border-bottom:1.5px solid var(--border);">
                    <th style="font-size:11.5px;font-weight:600;color:var(--text-2);padding:0 12px 10px;text-align:left;">No</th>
                    <th style="font-size:11.5px;font-weight:600;color:var(--text-2);padding:0 12px 10px;text-align:left;">Mata Kuliah</th>
                    <th style="font-size:11.5px;font-weight:600;color:var(--text-2);padding:0 12px 10px;text-align:center;">Pertemuan</th>
                    <th style="font-size:11.5px;font-weight:600;color:var(--text-2);padding:0 12px 10px;text-align:center;">Tanggal</th>
                    <th style="font-size:11.5px;font-weight:600;color:#22C55E;padding:0 12px 10px;text-align:center;">Hadir</th>
                    <th style="font-size:11.5px;font-weight:600;color:#FBBF24;padding:0 12px 10px;text-align:center;">Izin</th>
                    <th style="font-size:11.5px;font-weight:600;color:#3B82F6;padding:0 12px 10px;text-align:center;">Sakit</th>
                    <th style="font-size:11.5px;font-weight:600;color:#EF4444;padding:0 12px 10px;text-align:center;">Alpha</th>
                    <th style="font-size:11.5px;font-weight:600;color:var(--text-2);padding:0 12px 10px;text-align:center;">% Hadir</th>
                    <th style="font-size:11.5px;font-weight:600;color:var(--text-2);padding:0 12px 10px;">Status</th>
                </tr>
            </thead>
            <tbody id="absenBody">
                @forelse($absensis as $i => $absen)
                @php
                    $total     = $absen->jam_hadir + $absen->jam_izin + $absen->jam_sakit + $absen->jam_alpha;
                    $pct       = $total > 0 ? round($absen->jam_hadir / $total * 100) : 0;
                    $alpha     = $absen->jam_alpha;
                    $statusVal = $alpha >= 18 ? 'kritis' : ($alpha >= 14 ? 'waspada' : 'aman');
                    $rowBg     = $alpha >= 18 ? 'rgba(239,68,68,.03)' : ($alpha >= 14 ? 'rgba(245,158,11,.03)' : 'transparent');
                @endphp
                <tr data-matkul="{{ strtolower($absen->mataKuliah->nama) }}"
                    data-status="{{ $statusVal }}"
                    style="border-bottom:1px solid #F8FAFC;background:{{ $rowBg }};transition:background .12s;"
                    onmouseover="this.style.background='#F8FAFF'"
                    onmouseout="this.style.background='{{ $rowBg }}'">
                    <td style="padding:12px;font-size:12px;color:var(--text-3);{{ $alpha>=18 ? 'border-left:3px solid #EF4444;' : ($alpha>=14 ? 'border-left:3px solid #F59E0B;' : '') }}">{{ $i+1 }}</td>
                    <td style="padding:12px;">
                        <div style="font-weight:500;color:var(--text-1);font-size:13.5px;">{{ $absen->mataKuliah->nama }}</div>
                        <div style="font-size:11px;color:var(--text-3);font-family:monospace;margin-top:1px;">{{ $absen->mataKuliah->kode }}</div>
                    </td>
                    <td style="padding:12px;text-align:center;color:var(--text-2);font-size:13px;">Ke-{{ $absen->pertemuan_ke ?? 14 }}</td>
                    <td style="padding:12px;text-align:center;color:var(--text-2);font-size:13px;white-space:nowrap;">
                        @if($absen->tanggal)
                            {{ \Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }}
                        @else
                            <span style="color:var(--text-3);">—</span>
                        @endif
                    </td>
                    <td style="padding:12px;text-align:center;font-weight:700;color:#22C55E;">{{ $absen->jam_hadir }}</td>
                    <td style="padding:12px;text-align:center;font-weight:600;color:#FBBF24;">{{ $absen->jam_izin }}</td>
                    <td style="padding:12px;text-align:center;font-weight:600;color:#3B82F6;">{{ $absen->jam_sakit }}</td>
                    <td style="padding:12px;text-align:center;font-weight:700;color:{{ $alpha>=18 ? '#EF4444' : ($alpha>=14 ? '#F59E0B' : 'var(--text-2)') }};">
                        {{ $alpha }}{{ $alpha>=18 ? ' ⛔' : ($alpha>=14 ? ' ⚠️' : '') }}
                    </td>
                    <td style="padding:12px;text-align:center;">
                        <div style="display:flex;align-items:center;gap:6px;justify-content:center;">
                            <div style="width:48px;height:5px;background:#F1F5F9;border-radius:3px;overflow:hidden;">
                                <div style="height:100%;width:{{ $pct }}%;background:{{ $pct>=75 ? '#22C55E' : ($pct>=60 ? '#F59E0B' : '#EF4444') }};border-radius:3px;"></div>
                            </div>
                            <span style="font-size:12px;font-weight:700;color:{{ $pct>=75 ? '#22C55E' : ($pct>=60 ? '#F59E0B' : '#EF4444') }};">{{ $pct }}%</span>
                        </div>
                    </td>
                    <td style="padding:12px;">
                        @if($statusVal === 'kritis')
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:700;background:#FEE2E2;color:#991B1B;">⛔ Kritis</span>
                        @elseif($statusVal === 'waspada')
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:700;background:#FEF3C7;color:#92400E;">⚠ Waspada</span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:700;background:#DCFCE7;color:#166534;">✓ Aman</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;padding:40px;color:var(--text-3);">
                        <i class="bi bi-calendar-x" style="font-size:32px;display:block;margin-bottom:8px;"></i>
                        Belum ada data absensi untuk semester ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
 
    {{-- Footer summary --}}
    <div style="display:flex;align-items:center;gap:10px;margin-top:16px;padding-top:14px;border-top:1px solid var(--border);flex-wrap:wrap;">
        <div style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#F0FDF4;color:#15803D;">
            <i class="bi bi-person-check-fill"></i> Hadir: {{ $sumHadir }}j
        </div>
        <div style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#FEF9C3;color:#854D0E;">
            <i class="bi bi-journal-x"></i> Izin: {{ $sumIzin }}j
        </div>
        <div style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#DBEAFE;color:#1E40AF;">
            <i class="bi bi-thermometer"></i> Sakit: {{ $sumSakit }}j
        </div>
        <div style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:20px;font-size:12px;font-weight:600;{{ $sumAlpha>=14 ? 'background:#FEE2E2;color:#991B1B;' : 'background:#F1F5F9;color:var(--text-2);' }}">
            <i class="bi bi-x-circle-fill"></i> Alpha: {{ $sumAlpha }}j
        </div>
        <div style="margin-left:auto;display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#EFF6FF;color:#1D4ED8;">
            <i class="bi bi-percent"></i> Total kehadiran: {{ $pctHadir }}%
        </div>
    </div>
</div>
 
@endsection
 
@push('scripts')
<script>
document.getElementById('searchAbsensi').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#absenBody tr').forEach(function(r) {
        r.style.display = (r.dataset.matkul||'').includes(q) ? '' : 'none';
    });
});
document.getElementById('filterAbsenBtn').addEventListener('filterChange', function(e) {
    var val = e.detail.value;
    document.querySelectorAll('#absenBody tr').forEach(function(r) {
        if (!val) { r.style.display=''; return; }
        r.style.display = r.dataset.status===val ? '' : 'none';
    });
});
</script>
@endpush
 