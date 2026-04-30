@extends('layouts.mahasiswa')

@section('title', 'Dashboard')
@section('page-title', 'Halo, ' . $mahasiswa->nama . '! 👋')
@section('page-sub', 'Selamat datang di Dashboard Akademik')

@push('styles')
<style>
/* ══ DASHBOARD SPECIFIC ══════════════════════════════ */

/* Stat cards — colored top border + icon */
.stat-card-v2 {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 0;
    overflow: hidden;
    transition: transform .18s, box-shadow .18s;
    height: 100%;
}
.stat-card-v2:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,.1);
}
.stat-card-accent {
    height: 4px;
    border-radius: 0;
}
.stat-card-body {
    padding: 18px 20px 18px;
    display: flex;
    gap: 14px;
    align-items: flex-start;
}
.stat-icon-box {
    width: 44px; height: 44px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.stat-card-info { flex: 1; min-width: 0; }
.stat-card-label {
    font-size: 12px; font-weight: 600; color: var(--text-2);
    margin-bottom: 4px; white-space: nowrap;
    overflow: hidden; text-overflow: ellipsis;
}
.stat-card-value {
    font-size: 32px; font-weight: 800; line-height: 1;
    letter-spacing: -1.5px; margin-bottom: 6px;
}
.stat-card-note {
    font-size: 11.5px; color: var(--text-2);
    display: flex; align-items: center; gap: 4px;
}
.stat-card-badge {
    display: inline-flex; align-items: center; gap: 3px;
    padding: 2px 8px; border-radius: 20px;
    font-size: 11px; font-weight: 700;
}
.badge-up   { background: #DCFCE7; color: #15803D; }
.badge-warn { background: #FEF9C3; color: #854D0E; }
.badge-down { background: #FEE2E2; color: #991B1B; }

/* Chart cards */
.chart-card-v2 {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 22px;
    height: 100%;
}
.chart-head-v2 {
    display: flex; align-items: flex-start;
    justify-content: space-between; margin-bottom: 6px;
}
.chart-title-v2 { font-size: 15px; font-weight: 700; color: var(--text-1); }
.chart-sub-v2   { font-size: 12px; color: var(--text-2); margin-top: 2px; }

/* Donut fix */
.donut-wrap-v2 {
    display: flex; align-items: center;
    gap: 20px; margin-top: 16px;
}
.donut-canvas-box {
    flex-shrink: 0; width: 148px; height: 148px;
    position: relative;
}
.donut-canvas-box canvas {
    width: 148px !important; height: 148px !important; display: block;
}
.donut-center-text {
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    text-align: center; pointer-events: none;
}
.donut-center-num  { font-size: 20px; font-weight: 800; color: var(--text-1); line-height: 1; }
.donut-center-sub  { font-size: 10px; color: var(--text-2); font-weight: 500; margin-top: 2px; }

.legend-v2 { flex: 1; display: flex; flex-direction: column; gap: 10px; }
.legend-v2-row {
    display: flex; align-items: center;
    justify-content: space-between; gap: 8px;
}
.legend-v2-left { display: flex; align-items: center; gap: 8px; }
.legend-v2-dot  { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.legend-v2-label { font-size: 12.5px; color: var(--text-2); }
.legend-v2-val  { font-size: 12.5px; font-weight: 700; color: var(--text-1); }
.legend-v2-bar  {
    width: 100%; height: 4px; background: #F1F5F9;
    border-radius: 2px; margin-top: 4px; overflow: hidden;
}
.legend-v2-bar-fill { height: 100%; border-radius: 2px; transition: width .5s ease; }

/* Table v2 */
.tbl-card-v2 {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 20px 22px;
}
.tbl-head-v2 {
    display: flex; align-items: center;
    justify-content: space-between;
    margin-bottom: 16px; gap: 10px; flex-wrap: wrap;
}
.tbl-title-v2 { font-size: 15px; font-weight: 700; color: var(--text-1); }
.tbl-sub-v2   { font-size: 11.5px; color: var(--text-2); margin-top: 1px; }

.ac-table-v2 { width: 100%; border-collapse: collapse; }
.ac-table-v2 thead th {
    font-size: 11.5px; font-weight: 600; color: var(--text-2);
    padding: 0 12px 10px; text-align: left;
    border-bottom: 1.5px solid var(--border);
    white-space: nowrap;
}
.ac-table-v2 tbody tr {
    border-bottom: 1px solid #F8FAFC;
    transition: background .12s;
}
.ac-table-v2 tbody tr:last-child { border-bottom: none; }
.ac-table-v2 tbody tr:hover { background: #F8FAFF; }
.ac-table-v2 tbody td { padding: 11px 12px; font-size: 13.5px; }

/* Grade pill */
.grade-pill {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px; border-radius: 50%;
    font-size: 12px; font-weight: 800;
}
.grade-A { background: #DCFCE7; color: #15803D; }
.grade-B { background: #DBEAFE; color: #1D4ED8; }
.grade-C { background: #FEF9C3; color: #854D0E; }
.grade-D { background: #FEE2E2; color: #991B1B; }
.grade-E { background: #FEE2E2; color: #7F1D1D; }

/* Score bar mini */
.score-bar {
    width: 60px; height: 5px;
    background: #F1F5F9; border-radius: 3px;
    overflow: hidden; display: inline-block;
    vertical-align: middle; margin-left: 6px;
}
.score-bar-fill { height: 100%; border-radius: 3px; }

/* Info chips di bawah tabel */
.tbl-footer {
    display: flex; align-items: center; gap: 10px;
    margin-top: 14px; padding-top: 12px;
    border-top: 1px solid var(--border);
    flex-wrap: wrap;
}
.info-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px;
    font-size: 11.5px; font-weight: 600;
    background: #F1F5F9; color: var(--text-2);
}
.info-chip i { font-size: 12px; }

/* Absensi status row */
.absen-dot {
    width: 8px; height: 8px; border-radius: 50%;
    display: inline-block; margin-right: 4px;
}

/* IPK progress bar */
.ipk-bar {
    width: 100%; height: 6px; background: #EFF6FF;
    border-radius: 3px; margin-top: 10px; overflow: hidden;
}
.ipk-bar-fill {
    height: 100%; border-radius: 3px;
    background: linear-gradient(90deg, #2563EB, #60A5FA);
    transition: width .8s ease;
}

/* Section separator */
.section-label {
    font-size: 11px; font-weight: 700; color: var(--text-3);
    text-transform: uppercase; letter-spacing: 1px;
    margin-bottom: 12px; margin-top: 4px;
    display: flex; align-items: center; gap: 8px;
}
.section-label::after {
    content: ''; flex: 1; height: 1px; background: var(--border);
}

@media (max-width: 576px) {
    .stat-card-value { font-size: 26px; }
    .donut-canvas-box { width: 120px; height: 120px; }
    .donut-canvas-box canvas { width: 120px !important; height: 120px !important; }
    .donut-center-num { font-size: 16px; }
}
</style>
@endpush

@section('content')

@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg, #1E3A8A 0%, #2563EB 55%, #3B82F6 100%)',
    'icon'         => 'bi-grid-1x2-fill',
    'title'        => 'Selamat datang, ' . $mahasiswa->nama . '! 👋',
    'sub'          => 'Semester ' . $semesterAktif . ' · ' . ($mahasiswa->kelas->tahun_akademik ?? '2024/2025') . ' · ' . ($mahasiswa->kelas->nama ?? ''),
    'chips'        => [
        ['icon' => 'bi-mortarboard-fill',  'label' => 'Semester ' . $semesterAktif],
        ['icon' => 'bi-book-fill',         'label' => $nilais->count() . ' Mata Kuliah'],
        ['icon' => 'bi-calendar2-check',   'label' => $absensis->count() . ' Kelas Aktif'],
        ['icon' => 'bi-graph-up-arrow',    'label' => 'IPK ' . number_format($ipk, 2)],
    ],
    'badge_num'    => number_format($ipk, 2),
    'badge_label'  => "IPK\nKumulatif",
    'badge2_num'   => $nilais->count(),
    'badge2_label' => "Mata\nKuliah",
])

{{-- ══ STAT CARDS ══ --}}
<div class="section-label">Ringkasan Akademik</div>
<div class="row g-3 mb-4">

    {{-- IPK --}}
    <div class="col-sm-4 col-12">
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background: linear-gradient(90deg,#2563EB,#60A5FA);"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:#EFF6FF;">
                    <i class="bi bi-trophy-fill" style="color:#2563EB;"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Indeks Prestasi Kumulatif</div>
                    <div class="stat-card-value" style="color:#2563EB;">{{ number_format($ipk, 2) }}</div>
                    <div class="ipk-bar">
                        <div class="ipk-bar-fill" style="width:{{ ($ipk/4)*100 }}%;"></div>
                    </div>
                    <div class="stat-card-note mt-2">
                        @if($ipk >= 3.5)
                            <span class="stat-card-badge badge-up"><i class="bi bi-arrow-up"></i> Sangat Memuaskan</span>
                        @elseif($ipk >= 3.0)
                            <span class="stat-card-badge badge-up"><i class="bi bi-check"></i> Memuaskan</span>
                        @elseif($ipk >= 2.5)
                            <span class="stat-card-badge badge-warn"><i class="bi bi-dash"></i> Cukup</span>
                        @else
                            <span class="stat-card-badge badge-down"><i class="bi bi-arrow-down"></i> Perlu Ditingkatkan</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Nilai D/E --}}
    <div class="col-sm-4 col-12">
        @php $jumlahDE = $nilaiDE->count(); @endphp
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background: {{ $jumlahDE > 0 ? 'linear-gradient(90deg,#EF4444,#FCA5A5)' : 'linear-gradient(90deg,#22C55E,#86EFAC)' }};"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:{{ $jumlahDE > 0 ? '#FEF2F2' : '#F0FDF4' }};">
                    <i class="bi bi-{{ $jumlahDE > 0 ? 'exclamation-triangle-fill' : 'check-circle-fill' }}"
                       style="color:{{ $jumlahDE > 0 ? '#EF4444' : '#22C55E' }};"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Jumlah Nilai Kategori D/E</div>
                    <div class="stat-card-value" style="color:{{ $jumlahDE > 0 ? '#EF4444' : '#22C55E' }};">
                        {{ $jumlahDE }}
                        <span style="font-size:13px;font-weight:500;color:var(--text-2);">mata kuliah</span>
                    </div>
                    <div class="stat-card-note">
                        @if($jumlahDE > 0)
                            <span class="stat-card-badge badge-down">
                                <i class="bi bi-exclamation-circle"></i> Perlu perhatian segera
                            </span>
                        @else
                            <span class="stat-card-badge badge-up">
                                <i class="bi bi-shield-check"></i> Semua nilai aman
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alpha --}}
    @php $totalAlpha = $absensis->sum('jam_alpha'); @endphp
    <div class="col-sm-4 col-12">
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background: {{ $totalAlpha >= 18 ? 'linear-gradient(90deg,#EF4444,#FCA5A5)' : ($totalAlpha >= 14 ? 'linear-gradient(90deg,#F59E0B,#FCD34D)' : 'linear-gradient(90deg,#22C55E,#86EFAC)') }};"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:{{ $totalAlpha >= 14 ? '#FEF3C7' : '#F0FDF4' }};">
                    <i class="bi bi-clock-fill" style="color:{{ $totalAlpha >= 18 ? '#EF4444' : ($totalAlpha >= 14 ? '#F59E0B' : '#22C55E') }};"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Total Jam Ketidakhadiran</div>
                    <div class="stat-card-value" style="color:{{ $totalAlpha >= 18 ? '#EF4444' : ($totalAlpha >= 14 ? '#F59E0B' : 'var(--text-1)') }};">
                        {{ $totalAlpha }}
                        <span style="font-size:16px;font-weight:600;color:var(--text-2);">/ 18 jam</span>
                    </div>
                    {{-- Progress to limit --}}
                    <div class="ipk-bar" style="background:#FEF3C7;">
                        <div class="ipk-bar-fill" style="width:{{ min(($totalAlpha/18)*100, 100) }}%; background:{{ $totalAlpha >= 18 ? '#EF4444' : ($totalAlpha >= 14 ? '#F59E0B' : '#22C55E') }};"></div>
                    </div>
                    <div class="stat-card-note mt-2">
                        @if($totalAlpha >= 18)
                            <span class="stat-card-badge badge-down">⛔ Melewati batas UAS!</span>
                        @elseif($totalAlpha >= 14)
                            <span class="stat-card-badge badge-warn">⚠ {{ 18 - $totalAlpha }} jam lagi batas</span>
                        @else
                            <span class="stat-card-badge badge-up">✅ Aman</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ CHARTS ══ --}}
<div class="section-label">Laporan Visual</div>
<div class="row g-3 mb-4">

    {{-- Bar Chart --}}
    <div class="col-lg-7 col-12">
        <div class="chart-card-v2">
            <div class="chart-head-v2">
                <div>
                    <div class="chart-title-v2">Laporan Nilai</div>
                    <div class="chart-sub-v2">Nilai akhir per mata kuliah semester {{ $semesterAktif }}</div>
                </div>
                <div class="filter-wrap">
                    <button class="btn-filter" id="filterBarBtn">
                        <i class="bi bi-sliders2" style="font-size:12px;"></i> Filter
                    </button>
                    <div class="filter-menu" id="filterBarMenu">
                        <div class="filter-menu-label">Select Filter</div>
                        <div class="filter-opt active" data-val="all">Semua Mata Kuliah</div>
                        <div class="filter-opt" data-val="de">Nilai D/E Saja</div>
                        <div class="filter-opt" data-val="ab">Nilai A/B Saja</div>
                    </div>
                </div>
            </div>
            <div style="position:relative; height:220px; margin-top:14px;">
                <canvas id="nilaiChart"></canvas>
            </div>
            {{-- Chart legend --}}
            <div class="d-flex gap-3 flex-wrap mt-3">
                @foreach(['A'=>['#22C55E','Sangat Baik'],'B'=>['#3B82F6','Baik'],'C'=>['#FBBF24','Cukup'],'D'=>['#F97316','Kurang'],'E'=>['#EF4444','Sangat Kurang']] as $g => $info)
                <div style="display:flex;align-items:center;gap:5px;font-size:11.5px;color:var(--text-2);">
                    <div style="width:8px;height:8px;border-radius:2px;background:{{ $info[0] }};flex-shrink:0;"></div>
                    {{ $g }} – {{ $info[1] }}
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Donut --}}
    <div class="col-lg-5 col-12">
        <div class="chart-card-v2">
            <div class="chart-head-v2">
                <div>
                    <div class="chart-title-v2">Laporan Absensi</div>
                    <div class="chart-sub-v2">Distribusi kehadiran semester {{ $semesterAktif }}</div>
                </div>
                <div class="filter-wrap">
                    <button class="btn-filter">
                        <i class="bi bi-sliders2" style="font-size:12px;"></i> Filter
                    </button>
                    <div class="filter-menu">
                        <div class="filter-menu-label">Periode</div>
                        <div class="filter-opt active">Semester Ini</div>
                    </div>
                </div>
            </div>

            @php
                $sumHadir = $absensis->sum('jam_hadir');
                $sumIzin  = $absensis->sum('jam_izin');
                $sumSakit = $absensis->sum('jam_sakit');
                $sumAlp   = $absensis->sum('jam_alpha');
                $sumAll   = $sumHadir + $sumIzin + $sumSakit + $sumAlp;
                $pctH = $sumAll > 0 ? round($sumHadir/$sumAll*100) : 0;
                $pctI = $sumAll > 0 ? round($sumIzin/$sumAll*100) : 0;
                $pctS = $sumAll > 0 ? round($sumSakit/$sumAll*100) : 0;
                $pctA = $sumAll > 0 ? round($sumAlp/$sumAll*100) : 0;
            @endphp

            <div class="donut-wrap-v2">
                <div class="donut-canvas-box">
                    <canvas id="absensiChart" width="148" height="148"></canvas>
                    <div class="donut-center-text">
                        <div class="donut-center-num">{{ $pctH }}%</div>
                        <div class="donut-center-sub">Hadir</div>
                    </div>
                </div>
                <div class="legend-v2">
                    <div>
                        <div class="legend-v2-row">
                            <div class="legend-v2-left">
                                <div class="legend-v2-dot" style="background:#22C55E;"></div>
                                <span class="legend-v2-label">Hadir</span>
                            </div>
                            <span class="legend-v2-val">{{ $sumHadir }}j</span>
                        </div>
                        <div class="legend-v2-bar"><div class="legend-v2-bar-fill" style="width:{{ $pctH }}%;background:#22C55E;"></div></div>
                    </div>
                    <div>
                        <div class="legend-v2-row">
                            <div class="legend-v2-left">
                                <div class="legend-v2-dot" style="background:#FBBF24;"></div>
                                <span class="legend-v2-label">Izin</span>
                            </div>
                            <span class="legend-v2-val">{{ $sumIzin }}j</span>
                        </div>
                        <div class="legend-v2-bar"><div class="legend-v2-bar-fill" style="width:{{ $pctI }}%;background:#FBBF24;"></div></div>
                    </div>
                    <div>
                        <div class="legend-v2-row">
                            <div class="legend-v2-left">
                                <div class="legend-v2-dot" style="background:#3B82F6;"></div>
                                <span class="legend-v2-label">Sakit</span>
                            </div>
                            <span class="legend-v2-val">{{ $sumSakit }}j</span>
                        </div>
                        <div class="legend-v2-bar"><div class="legend-v2-bar-fill" style="width:{{ $pctS }}%;background:#3B82F6;"></div></div>
                    </div>
                    <div>
                        <div class="legend-v2-row">
                            <div class="legend-v2-left">
                                <div class="legend-v2-dot" style="background:#EF4444;"></div>
                                <span class="legend-v2-label">Alpha</span>
                            </div>
                            <span class="legend-v2-val" style="color:{{ $sumAlp >= 14 ? '#EF4444' : 'var(--text-1)' }};">{{ $sumAlp }}j</span>
                        </div>
                        <div class="legend-v2-bar"><div class="legend-v2-bar-fill" style="width:{{ $pctA }}%;background:#EF4444;"></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ TABEL ══ --}}
<div class="section-label">Detail Data</div>
<div class="row g-3">

    {{-- Nilai Akademik --}}
    <div class="col-lg-6 col-12">
        <div class="tbl-card-v2">
            <div class="tbl-head-v2">
                <div>
                    <div class="tbl-title-v2">Nilai Akademik</div>
                    <div class="tbl-sub-v2">Semester {{ $semesterAktif }} • {{ $nilais->count() }} mata kuliah</div>
                </div>
                <div class="tbl-actions">
                    <div class="search-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Search" id="searchNilai">
                    </div>
                    <div class="filter-wrap">
                        <button class="btn-filter" id="filterNilaiTblBtn">
                            <i class="bi bi-sliders2" style="font-size:12px;"></i>
                        </button>
                        <div class="filter-menu" id="filterNilaiTblMenu">
                            <div class="filter-menu-label">Filter Nilai</div>
                            <div class="filter-opt active" data-val="">Semua</div>
                            <div class="filter-opt" data-val="baik">Nilai Baik (A/B)</div>
                            <div class="filter-opt" data-val="perhatian">Perlu Perhatian (D/E)</div>
                        </div>
                    </div>
                    <a href="{{ route('mahasiswa.nilai') }}" class="btn-outline">View All</a>
                </div>
            </div>

            <div style="overflow-x:auto;">
                <table class="ac-table-v2">
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th style="text-align:center;">SKS</th>
                            <th style="text-align:center;">Nilai</th>
                            <th style="text-align:center;">Grade</th>
                        </tr>
                    </thead>
                    <tbody id="nilaiTableBody">
                        @forelse($nilais->take(7) as $nilai)
                        @php
                            $isDE = in_array($nilai->grade, ['D','E']);
                            $scoreWidth = min($nilai->nilai_akhir, 100);
                            $scoreColor = $nilai->grade === 'A' ? '#22C55E'
                                : ($nilai->grade === 'B' ? '#3B82F6'
                                : ($nilai->grade === 'C' ? '#FBBF24'
                                : '#EF4444'));
                        @endphp
                        <tr data-matkul="{{ strtolower($nilai->mataKuliah->nama) }}"
                            data-status="{{ $isDE ? 'perhatian' : 'baik' }}">
                            <td>
                                <div style="font-weight:500;color:var(--text-1);">{{ $nilai->mataKuliah->nama }}</div>
                                <div style="font-size:11px;color:var(--text-3);margin-top:1px;">{{ $nilai->mataKuliah->kode }}</div>
                            </td>
                            <td style="text-align:center;color:var(--text-2);">{{ $nilai->mataKuliah->sks }}</td>
                            <td style="text-align:center;">
                                <div style="display:flex;align-items:center;gap:6px;justify-content:center;">
                                    <span style="font-weight:700;font-size:13.5px;color:{{ $scoreColor }};">
                                        {{ number_format($nilai->nilai_akhir, 1) }}
                                    </span>
                                    <div class="score-bar">
                                        <div class="score-bar-fill" style="width:{{ $scoreWidth }}%;background:{{ $scoreColor }};"></div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <span class="grade-pill grade-{{ $nilai->grade }}">{{ $nilai->grade }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;padding:28px;color:var(--text-3);">
                                <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:6px;"></i>
                                Belum ada data nilai.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="tbl-footer">
                <div class="info-chip" style="background:#EFF6FF;color:#1D4ED8;">
                    <i class="bi bi-star-fill"></i>
                    IP: {{ number_format($ipSemester, 2) }}
                </div>
                <div class="info-chip">
                    <i class="bi bi-book"></i>
                    {{ $nilais->sum('mataKuliah.sks') }} SKS
                </div>
                @if($nilaiDE->count() > 0)
                <div class="info-chip" style="background:#FEE2E2;color:#991B1B;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    {{ $nilaiDE->count() }} nilai D/E
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Riwayat Absensi --}}
    <div class="col-lg-6 col-12">
        <div class="tbl-card-v2">
            <div class="tbl-head-v2">
                <div>
                    <div class="tbl-title-v2">Riwayat Absensi</div>
                    <div class="tbl-sub-v2">Semester {{ $semesterAktif }} • {{ $absensis->count() }} mata kuliah</div>
                </div>
                <div class="tbl-actions">
                    <div class="search-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Search" id="searchAbsensi">
                    </div>
                    <div class="filter-wrap">
                        <button class="btn-filter" id="filterAbsenBtn">
                            <i class="bi bi-sliders2" style="font-size:12px;"></i>
                        </button>
                        <div class="filter-menu" id="filterAbsenMenu">
                            <div class="filter-menu-label">Filter Status</div>
                            <div class="filter-opt active" data-val="">Semua</div>
                            <div class="filter-opt" data-val="hadir">Hadir</div>
                            <div class="filter-opt" data-val="izin">Izin</div>
                            <div class="filter-opt" data-val="sakit">Sakit</div>
                            <div class="filter-opt" data-val="alpha">Alpha</div>
                        </div>
                    </div>
                    <a href="{{ route('mahasiswa.absensi') }}" class="btn-outline">View All</a>
                </div>
            </div>

            <div style="overflow-x:auto;">
                <table class="ac-table-v2">
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th style="text-align:center;">Hadir</th>
                            <th style="text-align:center;">Alpha</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="absenTableBody">
                        @forelse($absensis->take(7) as $absen)
                        @php
                            $status = 'hadir';
                            if ($absen->jam_alpha > 0)     $status = 'alpha';
                            elseif ($absen->jam_izin > 0)  $status = 'izin';
                            elseif ($absen->jam_sakit > 0) $status = 'sakit';
                            $totalJam = $absen->jam_hadir + $absen->jam_izin + $absen->jam_sakit + $absen->jam_alpha;
                            $pct = $totalJam > 0 ? round($absen->jam_hadir / $totalJam * 100) : 0;
                        @endphp
                        <tr data-matkul="{{ strtolower($absen->mataKuliah->nama) }}"
                            data-status="{{ $status }}">
                            <td>
                                <div style="font-weight:500;color:var(--text-1);">{{ $absen->mataKuliah->nama }}</div>
                                <div style="font-size:11px;color:var(--text-3);margin-top:1px;">{{ $absen->mataKuliah->kode }}</div>
                            </td>
                            <td style="text-align:center;">
                                <div style="display:flex;flex-direction:column;align-items:center;gap:2px;">
                                    <span style="font-size:13px;font-weight:700;color:{{ $pct >= 75 ? '#22C55E' : '#EF4444' }};">{{ $pct }}%</span>
                                    <div style="width:40px;height:3px;background:#F1F5F9;border-radius:2px;overflow:hidden;">
                                        <div style="height:100%;width:{{ $pct }}%;background:{{ $pct >= 75 ? '#22C55E' : '#EF4444' }};border-radius:2px;"></div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <span style="font-weight:700;color:{{ $absen->jam_alpha >= 18 ? '#EF4444' : ($absen->jam_alpha >= 14 ? '#F59E0B' : 'var(--text-2)') }};">
                                    {{ $absen->jam_alpha }}j
                                    @if($absen->jam_alpha >= 18) ⛔
                                    @elseif($absen->jam_alpha >= 14) ⚠️
                                    @endif
                                </span>
                            </td>
                            <td>
                                @if($status === 'hadir')
                                    <span class="badge badge-green">Hadir</span>
                                @elseif($status === 'izin')
                                    <span class="badge badge-yellow">Izin</span>
                                @elseif($status === 'sakit')
                                    <span class="badge badge-blue">Sakit</span>
                                @else
                                    <span class="badge badge-red">Alpha</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;padding:28px;color:var(--text-3);">
                                <i class="bi bi-calendar-x" style="font-size:24px;display:block;margin-bottom:6px;"></i>
                                Belum ada data absensi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="tbl-footer">
                <div class="info-chip" style="background:#F0FDF4;color:#15803D;">
                    <i class="bi bi-person-check-fill"></i>
                    Hadir: {{ $absensis->sum('jam_hadir') }}j
                </div>
                <div class="info-chip" style="background:#FEF2F2;color:#991B1B;">
                    <i class="bi bi-x-circle-fill"></i>
                    Alpha: {{ $absensis->sum('jam_alpha') }}j
                </div>
                @if($absensiKritis->count() > 0)
                <div class="info-chip" style="background:#FEF2F2;color:#991B1B;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    {{ $absensiKritis->count() }} MK kritis
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// ── Data ─────────────────────────────────────────────
@php
    $cLabels = []; $cGrades = []; $cValues = [];
    foreach($nilais as $n) {
        $nm = mb_strlen($n->mataKuliah->nama) > 14
            ? mb_substr($n->mataKuliah->nama,0,12).'..' : $n->mataKuliah->nama;
        $cLabels[] = $nm;
        $cGrades[] = $n->grade;
        $cValues[] = round((float)$n->nilai_akhir, 1);
    }
@endphp

var LABELS = @json($cLabels);
var GRADES = @json($cGrades);
var VALUES = @json($cValues);

var G2Y = {A:5,B:4,C:3,D:2,E:1};
var G2C = {A:'#22C55E',B:'#3B82F6',C:'#FBBF24',D:'#F97316',E:'#EF4444'};
var barData   = GRADES.map(function(g){ return G2Y[g]||0; });
var barColors = GRADES.map(function(g){ return G2C[g]||'#2563EB'; });

// ── BAR CHART ──────────────────────────────────────
var barCtx   = document.getElementById('nilaiChart').getContext('2d');
var barChart = new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: LABELS,
        datasets: [{
            data: barData,
            backgroundColor: barColors,
            borderRadius: 6,
            borderSkipped: false,
            maxBarThickness: 40,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0F172A',
                padding: 10,
                cornerRadius: 8,
                callbacks: {
                    title: function(items) { return LABELS[items[0].dataIndex]; },
                    label: function(ctx) {
                        var map = ['','E','D','C','B','A'];
                        return [
                            ' Grade  : ' + (map[ctx.raw]||''),
                            ' Nilai  : ' + VALUES[ctx.dataIndex]
                        ];
                    }
                }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: {
                    font: { family:'Plus Jakarta Sans', size:11 },
                    color:'#64748B', maxRotation:45, minRotation:0
                }
            },
            y: {
                min:0, max:5,
                ticks: {
                    stepSize:1,
                    callback: function(v){ return ['','E','D','C','B','A'][v]||''; },
                    font: { family:'Plus Jakarta Sans', size:11 }, color:'#64748B'
                },
                grid: { color:'#F8FAFC' },
                border: { display:false }
            }
        }
    }
});

// ── DONUT CHART ────────────────────────────────────
var donutCtx = document.getElementById('absensiChart').getContext('2d');
new Chart(donutCtx, {
    type: 'doughnut',
    data: {
        labels: ['Alpha','Izin','Sakit','Hadir'],
        datasets: [{
            data: [
                {{ $absensis->sum('jam_alpha') }},
                {{ $absensis->sum('jam_izin') }},
                {{ $absensis->sum('jam_sakit') }},
                {{ $absensis->sum('jam_hadir') }}
            ],
            backgroundColor: ['#EF4444','#FBBF24','#3B82F6','#22C55E'],
            borderWidth: 3,
            borderColor: '#FFFFFF',
            hoverOffset: 5,
        }]
    },
    options: {
        responsive: false, maintainAspectRatio: false, cutout:'68%',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0F172A', padding:10, cornerRadius:8,
                callbacks: {
                    label: function(c){ return ' '+c.label+': '+c.raw+' jam'; }
                }
            }
        }
    }
});

// ── FILTER BAR ─────────────────────────────────────
document.getElementById('filterBarBtn').addEventListener('filterChange', function(e) {
    var val = e.detail.value;
    var idx = [];
    GRADES.forEach(function(g,i) {
        if (val==='de' && ['D','E'].indexOf(g)>-1) idx.push(i);
        else if (val==='ab' && ['A','B'].indexOf(g)>-1) idx.push(i);
        else if (!val||val==='all') idx.push(i);
    });
    barChart.data.labels                      = idx.map(function(i){return LABELS[i];});
    barChart.data.datasets[0].data            = idx.map(function(i){return barData[i];});
    barChart.data.datasets[0].backgroundColor = idx.map(function(i){return barColors[i];});
    barChart.update();
});

// ── FILTER ABSENSI ─────────────────────────────────
document.getElementById('filterAbsenBtn').addEventListener('filterChange', function(e) {
    var val = e.detail.value;
    document.querySelectorAll('#absenTableBody tr').forEach(function(r) {
        if (!val) { r.style.display=''; return; }
        r.style.display = r.dataset.status===val ? '' : 'none';
    });
});

// ── FILTER TABEL NILAI (dashboard) ──────────────────
document.getElementById('filterNilaiTblBtn').addEventListener('filterChange', function(e) {
    var val = e.detail.value;
    document.querySelectorAll('#nilaiTableBody tr').forEach(function(r) {
        if (!val) { r.style.display=''; return; }
        r.style.display = r.dataset.status===val ? '' : 'none';
    });
});

// ── SEARCH ─────────────────────────────────────────
document.getElementById('searchNilai').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#nilaiTableBody tr').forEach(function(r) {
        r.style.display = (r.dataset.matkul||'').includes(q) ? '' : 'none';
    });
});
document.getElementById('searchAbsensi').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#absenTableBody tr').forEach(function(r) {
        r.style.display = (r.dataset.matkul||'').includes(q) ? '' : 'none';
    });
});
</script>
@endpush