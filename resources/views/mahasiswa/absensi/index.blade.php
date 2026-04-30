@extends('layouts.mahasiswa')
 
@section('title', 'Riwayat Absensi')
@section('page-title', 'Riwayat Absensi')
@section('page-sub', 'Data kehadiran Anda per mata kuliah')
 
@section('content')
 
{{-- Filter --}}
<form method="GET" action="{{ route('mahasiswa.absensi') }}" class="semester-bar">
    <select name="semester" class="select-semester">
        <option value="{{ $semesterAktif }}">
            {{ $mahasiswa->kelas->tahun_akademik ?? '2024/2025' }} Genap — Semester {{ $semesterAktif }}
        </option>
    </select>
    <button type="submit" class="btn-primary">Filter</button>
</form>
 
{{-- Summary mini cards --}}
<div class="row g-2 mb-4">
    @php
        $sumHadir = $absensis->sum('jam_hadir');
        $sumIzin  = $absensis->sum('jam_izin');
        $sumSakit = $absensis->sum('jam_sakit');
        $sumAlpha = $absensis->sum('jam_alpha');
        $sumAll   = $sumHadir + $sumIzin + $sumSakit + $sumAlpha;
        $pctHadir = $sumAll > 0 ? round($sumHadir / $sumAll * 100) : 0;
    @endphp
    <div class="col-6 col-md-3">
        <div class="card-white" style="padding:14px 16px;">
            <div style="font-size:11px;font-weight:600;color:#22C55E;text-transform:uppercase;letter-spacing:.7px;margin-bottom:6px;">Hadir</div>
            <div style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1;">{{ $sumHadir }}<span style="font-size:13px;color:var(--text-2);font-weight:500;"> jam</span></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-white" style="padding:14px 16px;">
            <div style="font-size:11px;font-weight:600;color:#FBBF24;text-transform:uppercase;letter-spacing:.7px;margin-bottom:6px;">Izin</div>
            <div style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1;">{{ $sumIzin }}<span style="font-size:13px;color:var(--text-2);font-weight:500;"> jam</span></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-white" style="padding:14px 16px;">
            <div style="font-size:11px;font-weight:600;color:#3B82F6;text-transform:uppercase;letter-spacing:.7px;margin-bottom:6px;">Sakit</div>
            <div style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1;">{{ $sumSakit }}<span style="font-size:13px;color:var(--text-2);font-weight:500;"> jam</span></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-white" style="padding:14px 16px;">
            <div style="font-size:11px;font-weight:600;color:#EF4444;text-transform:uppercase;letter-spacing:.7px;margin-bottom:6px;">Alpha</div>
            <div style="font-size:24px;font-weight:800;color:{{ $sumAlpha >= 14 ? '#EF4444' : 'var(--text-1)' }};line-height:1;">
                {{ $sumAlpha }}<span style="font-size:13px;color:var(--text-2);font-weight:500;"> jam</span>
            </div>
        </div>
    </div>
</div>
 
<div class="card-white tbl-card">
    <div class="tbl-head">
        <div class="tbl-title">Riwayat Absensi</div>
        <div class="tbl-actions">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Search" id="searchAbsensi">
            </div>
            <div class="filter-wrap">
                <button class="btn-filter" id="filterAbsenBtn">
                    <i class="bi bi-sliders2" style="font-size:12px;"></i> Filter
                </button>
                <div class="filter-menu" id="filterAbsenMenu">
                    <div class="filter-menu-label">Select Filter</div>
                    <div class="filter-opt active" data-val="">Semua</div>
                    <div class="filter-opt" data-val="hadir">Hadir</div>
                    <div class="filter-opt" data-val="izin">Izin</div>
                    <div class="filter-opt" data-val="sakit">Sakit</div>
                    <div class="filter-opt" data-val="alpha">Alpha</div>
                </div>
            </div>
        </div>
    </div>
 
    <div style="overflow-x:auto;">
        <table class="ac-table">
            <thead>
                <tr>
                    <th style="width:48px;">No</th>
                    <th>Mata Kuliah</th>
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
                    $status = 'hadir';
                    if ($absen->jam_alpha > 0)     $status = 'alpha';
                    elseif ($absen->jam_izin > 0)  $status = 'izin';
                    elseif ($absen->jam_sakit > 0) $status = 'sakit';
                @endphp
                <tr data-matkul="{{ strtolower($absen->mataKuliah->nama) }}"
                    data-status="{{ $status }}">
                    <td class="muted">{{ $i + 1 }}</td>
                    <td style="font-weight:500;">{{ $absen->mataKuliah->nama }}</td>
                    <td class="muted" style="text-align:center;">{{ $absen->jam_hadir }}</td>
                    <td class="muted" style="text-align:center;">{{ $absen->jam_izin }}</td>
                    <td class="muted" style="text-align:center;">{{ $absen->jam_sakit }}</td>
                    <td style="text-align:center;font-weight:700;color:{{ $absen->jam_alpha >= 18 ? '#EF4444' : ($absen->jam_alpha >= 14 ? '#F97316' : 'var(--text-1)') }};">
                        {{ $absen->jam_alpha }}
                        @if($absen->jam_alpha >= 18) <i class="bi bi-exclamation-triangle-fill" style="color:#EF4444;font-size:11px;"></i> @endif
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;align-items:center;gap:6px;justify-content:center;">
                            <div style="width:50px;height:5px;background:#E2E8F0;border-radius:3px;overflow:hidden;">
                                <div style="height:100%;width:{{ $pct }}%;background:{{ $pct>=75 ? '#22C55E' : '#EF4444' }};border-radius:3px;"></div>
                            </div>
                            <span style="font-size:12px;font-weight:600;color:{{ $pct>=75 ? '#22C55E' : '#EF4444' }};">{{ $pct }}%</span>
                        </div>
                    </td>
                    <td>
                        @if($absen->jam_alpha >= 18)
                            <span class="badge badge-red">⛔ Melewati Batas</span>
                        @elseif($absen->jam_alpha >= 14)
                            <span class="badge" style="background:#FEF3C7;color:#92400E;">⚠ Waspada</span>
                        @else
                            <span class="badge badge-green">Aman</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:36px;color:var(--text-3);">
                        <i class="bi bi-calendar-x" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                        Belum ada data absensi untuk semester ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
 
@endsection
 
@push('scripts')
<script>
document.getElementById('searchAbsensi').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#absenBody tr').forEach(function(row) {
        row.style.display = (row.dataset.matkul||'').includes(q) ? '' : 'none';
    });
});
 
document.getElementById('filterAbsenBtn').addEventListener('filterChange', function(e) {
    var val = e.detail.value;
    document.querySelectorAll('#absenBody tr').forEach(function(row) {
        if (!val) { row.style.display = ''; return; }
        row.style.display = row.dataset.status === val ? '' : 'none';
    });
});
</script>
@endpush