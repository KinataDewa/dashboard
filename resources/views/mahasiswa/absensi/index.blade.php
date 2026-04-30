@extends('layouts.mahasiswa')

@section('title', 'Riwayat Absensi')
@section('page-title', 'Riwayat Absensi')
@section('page-sub', 'Data kehadiran Anda per mata kuliah')

@push('styles')
<style>
.score-bar{width:50px;height:4px;background:#F1F5F9;border-radius:2px;overflow:hidden;display:inline-block;vertical-align:middle;margin-left:6px;}
.score-bar-fill{height:100%;border-radius:2px;}
.stat-mini{padding:14px 16px;}
.stat-mini-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.7px;margin-bottom:6px;}
.stat-mini-val{font-size:24px;font-weight:800;line-height:1;}
.stat-mini-unit{font-size:13px;font-weight:500;color:var(--text-2);margin-left:2px;}
</style>
@endpush

@section('content')

{{-- Filter --}}
<form method="GET" action="{{ route('mahasiswa.absensi') }}" class="semester-bar">
    <select name="semester" class="select-semester">
        <option value="{{ $semesterAktif }}">
            {{ $mahasiswa->kelas->tahun_akademik ?? '2024/2025' }}
            {{ $semesterAktif % 2 == 0 ? 'Genap' : 'Ganjil' }}
            — Semester {{ $semesterAktif }}
        </option>
    </select>
    <button type="submit" class="btn-primary">Filter</button>
</form>

{{-- Summary mini cards --}}
@php
    $sumHadir = $absensis->sum('jam_hadir');
    $sumIzin  = $absensis->sum('jam_izin');
    $sumSakit = $absensis->sum('jam_sakit');
    $sumAlpha = $absensis->sum('jam_alpha');
    $sumAll   = $sumHadir + $sumIzin + $sumSakit + $sumAlpha;
    $pctHadir = $sumAll > 0 ? round($sumHadir / $sumAll * 100) : 0;
@endphp

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card-white stat-mini">
            <div class="stat-mini-label" style="color:#22C55E;">Hadir</div>
            <div class="stat-mini-val" style="color:var(--text-1);">
                {{ $sumHadir }}<span class="stat-mini-unit">jam</span>
            </div>
            <div style="margin-top:8px;height:4px;background:#F1F5F9;border-radius:2px;overflow:hidden;">
                <div style="height:100%;width:{{ $sumAll > 0 ? round($sumHadir/$sumAll*100) : 0 }}%;background:#22C55E;border-radius:2px;"></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-white stat-mini">
            <div class="stat-mini-label" style="color:#FBBF24;">Izin</div>
            <div class="stat-mini-val" style="color:var(--text-1);">
                {{ $sumIzin }}<span class="stat-mini-unit">jam</span>
            </div>
            <div style="margin-top:8px;height:4px;background:#F1F5F9;border-radius:2px;overflow:hidden;">
                <div style="height:100%;width:{{ $sumAll > 0 ? round($sumIzin/$sumAll*100) : 0 }}%;background:#FBBF24;border-radius:2px;"></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-white stat-mini">
            <div class="stat-mini-label" style="color:#3B82F6;">Sakit</div>
            <div class="stat-mini-val" style="color:var(--text-1);">
                {{ $sumSakit }}<span class="stat-mini-unit">jam</span>
            </div>
            <div style="margin-top:8px;height:4px;background:#F1F5F9;border-radius:2px;overflow:hidden;">
                <div style="height:100%;width:{{ $sumAll > 0 ? round($sumSakit/$sumAll*100) : 0 }}%;background:#3B82F6;border-radius:2px;"></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-white stat-mini" style="{{ $sumAlpha >= 14 ? 'border-left:3px solid #EF4444;' : '' }}">
            <div class="stat-mini-label" style="color:#EF4444;">Alpha</div>
            <div class="stat-mini-val" style="color:{{ $sumAlpha >= 14 ? '#EF4444' : 'var(--text-1)' }};">
                {{ $sumAlpha }}<span class="stat-mini-unit">jam</span>
            </div>
            <div style="margin-top:8px;height:4px;background:#F1F5F9;border-radius:2px;overflow:hidden;">
                <div style="height:100%;width:{{ min(($sumAlpha/18*100), 100) }}%;background:#EF4444;border-radius:2px;"></div>
            </div>
            @if($sumAlpha >= 14)
            <div style="font-size:10.5px;color:#EF4444;font-weight:600;margin-top:4px;">
                {{ $sumAlpha >= 18 ? '⛔ Melewati batas!' : '⚠ ' . (18 - $sumAlpha) . ' jam lagi batas' }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Tabel --}}
<div class="card-white tbl-card-v2">
    <div class="tbl-head-v2">
        <div>
            <div class="tbl-title-v2">Riwayat Absensi per Mata Kuliah</div>
            <div class="tbl-sub-v2">
                Semester {{ $semesterAktif }} · {{ $absensis->count() }} mata kuliah ·
                Total kehadiran: <strong style="color:var(--blue);">{{ $pctHadir }}%</strong>
            </div>
        </div>
        <div class="tbl-actions">
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
                    <div class="filter-opt" data-val="aman">Aman (&lt;14j alpha)</div>
                    <div class="filter-opt" data-val="waspada">Waspada (14–17j)</div>
                    <div class="filter-opt" data-val="kritis">Kritis (≥18j)</div>
                </div>
            </div>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="ac-table-v2">
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th>Mata Kuliah</th>
                    <th style="text-align:center;">Pertemuan</th>
                    <th style="text-align:center;">Tanggal</th>
                    <th style="text-align:center;">Hadir</th>
                    <th style="text-align:center;">Izin</th>
                    <th style="text-align:center;">Sakit</th>
                    <th style="text-align:center;">Alpha</th>
                    <th style="text-align:center;">% Hadir</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="absenBody">
                @forelse($absensis as $i => $absen)
                @php
                    $total  = $absen->jam_hadir + $absen->jam_izin + $absen->jam_sakit + $absen->jam_alpha;
                    $pct    = $total > 0 ? round($absen->jam_hadir / $total * 100) : 0;
                    $alpha  = $absen->jam_alpha;
                    $statusVal = $alpha >= 18 ? 'kritis' : ($alpha >= 14 ? 'waspada' : 'aman');
                @endphp
                <tr data-matkul="{{ strtolower($absen->mataKuliah->nama) }}"
                    data-status="{{ $statusVal }}"
                    style="{{ $alpha >= 18 ? 'background:rgba(239,68,68,.03);' : '' }}">
                    <td class="muted">{{ $i + 1 }}</td>
                    <td style="{{ $alpha >= 18 ? 'border-left:3px solid #EF4444;' : '' }}">
                        <div style="font-weight:500;">{{ $absen->mataKuliah->nama }}</div>
                        <div style="font-size:11px;color:var(--text-3);font-family:monospace;">{{ $absen->mataKuliah->kode }}</div>
                    </td>
                    <td style="text-align:center;color:var(--text-2);">
                        Ke-{{ $absen->pertemuan_ke ?? 14 }}
                    </td>
                    <td style="text-align:center;color:var(--text-2);font-size:13px;white-space:nowrap;">
                        @if($absen->tanggal)
                            {{ \Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }}
                        @else
                            <span style="color:var(--text-3);">—</span>
                        @endif
                    </td>
                    <td style="text-align:center;font-weight:600;color:#22C55E;">{{ $absen->jam_hadir }}</td>
                    <td style="text-align:center;font-weight:600;color:#FBBF24;">{{ $absen->jam_izin }}</td>
                    <td style="text-align:center;font-weight:600;color:#3B82F6;">{{ $absen->jam_sakit }}</td>
                    <td style="text-align:center;font-weight:700;color:{{ $alpha >= 18 ? '#EF4444' : ($alpha >= 14 ? '#F59E0B' : 'var(--text-2)') }};">
                        {{ $alpha }}
                        @if($alpha >= 18) ⛔
                        @elseif($alpha >= 14) ⚠️
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;align-items:center;gap:6px;justify-content:center;">
                            <div style="width:44px;height:5px;background:#F1F5F9;border-radius:3px;overflow:hidden;">
                                <div style="height:100%;width:{{ $pct }}%;background:{{ $pct >= 75 ? '#22C55E' : '#EF4444' }};border-radius:3px;"></div>
                            </div>
                            <span style="font-size:12px;font-weight:700;color:{{ $pct >= 75 ? '#22C55E' : '#EF4444' }};">{{ $pct }}%</span>
                        </div>
                    </td>
                    <td>
                        @if($statusVal === 'kritis')
                            <span class="badge badge-red">⛔ Melewati Batas</span>
                        @elseif($statusVal === 'waspada')
                            <span class="badge" style="background:#FEF3C7;color:#92400E;">⚠ Waspada</span>
                        @else
                            <span class="badge badge-green">✓ Aman</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;padding:36px;color:var(--text-3);">
                        <i class="bi bi-calendar-x" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                        Belum ada data absensi untuk semester ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="tbl-footer">
        <div class="info-chip" style="background:#F0FDF4;color:#15803D;">
            <i class="bi bi-person-check-fill"></i> Hadir: {{ $sumHadir }}j
        </div>
        <div class="info-chip" style="background:#FEF9C3;color:#854D0E;">
            <i class="bi bi-journal-x"></i> Izin: {{ $sumIzin }}j
        </div>
        <div class="info-chip" style="background:#DBEAFE;color:#1E40AF;">
            <i class="bi bi-thermometer"></i> Sakit: {{ $sumSakit }}j
        </div>
        <div class="info-chip" style="{{ $sumAlpha >= 14 ? 'background:#FEE2E2;color:#991B1B;' : '' }}">
            <i class="bi bi-x-circle-fill"></i> Alpha: {{ $sumAlpha }}j
        </div>
        <div class="info-chip" style="background:#EFF6FF;color:#1D4ED8;margin-left:auto;">
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