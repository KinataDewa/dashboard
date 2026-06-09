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
    min-width: 28px; height: 28px; border-radius: 50%;
    font-size: 12px; font-weight: 800; padding: 0 4px;
}
.grade-A  { background: #DCFCE7; color: #15803D; }
.grade-Bp { background: #DBEAFE; color: #1E40AF; }
.grade-B  { background: #EFF6FF; color: #1D4ED8; }
.grade-Cp { background: #FEF9C3; color: #78350F; }
.grade-C  { background: #F5F3FF; color: #6D28D9; }
.grade-D  { background: #FEE2E2; color: #991B1B; }
.grade-E  { background: #FEE2E2; color: #7F1D1D; }

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
@media(max-width:480px){
    .donut-wrap-v2{flex-direction:column;align-items:center;}
    .legend-v2{width:100%;}
    .tbl-head-v2{flex-direction:column;align-items:stretch;}
    .tbl-actions{justify-content:flex-start;flex-wrap:wrap;}
}

.mhs-alert-wrap {
    position: relative;
    background: linear-gradient(135deg, #1A0A0A 0%, #7F1D1D 40%, #991B1B 100%);
    border-radius: 14px;
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(239,68,68,.25), 0 2px 8px rgba(239,68,68,.15);
    animation: mhsAlertIn .4s cubic-bezier(.16,1,.3,1) both;
    flex-wrap: wrap;
}
@keyframes mhsAlertIn {
    from { opacity:0; transform: translateY(-12px) scale(.98); }
    to   { opacity:1; transform: translateY(0) scale(1); }
}
@keyframes mhsAlertOut {
    from { opacity:1; transform:translateY(0) scale(1); max-height:300px; margin-bottom:24px; padding:20px 24px; }
    to   { opacity:0; transform:translateY(-8px) scale(.97); max-height:0; margin-bottom:0; padding:0 24px; }
}
.mhs-alert-wrap::before {
    content:''; position:absolute; inset:0;
    background-image: radial-gradient(circle, rgba(255,255,255,.06) 1px, transparent 1px);
    background-size: 24px 24px; pointer-events:none;
}
.mhs-alert-wrap::after {
    content:''; position:absolute; top:0; left:-100%;
    width:60%; height:100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.04), transparent);
    animation: mhsGlowSweep 4s ease infinite; pointer-events:none;
}
@keyframes mhsGlowSweep {
    0%   { left:-60%; }
    100% { left:140%; }
}
.mhs-pulse-ring {
    position:absolute; left:28px; top:50%;
    transform: translateY(-50%);
    width:52px; height:52px; border-radius:50%;
    background: rgba(239,68,68,.2);
    animation: mhsRingPulse 2s ease-out infinite; pointer-events:none;
}
@keyframes mhsRingPulse {
    0%   { transform:translateY(-50%) scale(1);   opacity:.8; }
    70%  { transform:translateY(-50%) scale(1.8); opacity:0; }
    100% { transform:translateY(-50%) scale(1);   opacity:0; }
}
.mhs-alert-left {
    display:flex; align-items:flex-start;
    gap:16px; flex:1; min-width:0;
    position:relative; z-index:1;
}
.mhs-alert-icon {
    width:44px; height:44px; border-radius:12px;
    background:rgba(255,255,255,.15);
    border:1px solid rgba(255,255,255,.2);
    display:flex; align-items:center; justify-content:center;
    font-size:20px; color:#FCA5A5; flex-shrink:0;
    animation: mhsIconShake 3s ease infinite;
}
@keyframes mhsIconShake {
    0%,90%,100% { transform:rotate(0deg); }
    92%  { transform:rotate(-8deg); }
    94%  { transform:rotate(8deg); }
    96%  { transform:rotate(-4deg); }
    98%  { transform:rotate(4deg); }
}
.mhs-alert-content { min-width:0; }
.mhs-alert-tag {
    display:inline-flex; align-items:center;
    background:rgba(255,255,255,.15);
    border:1px solid rgba(255,255,255,.25);
    border-radius:20px; padding:2px 10px;
    font-size:11px; font-weight:700;
    color:#FCA5A5; letter-spacing:.5px; margin-bottom:6px;
}
.mhs-alert-title {
    font-size:15px; font-weight:800; color:#fff;
    line-height:1.3; margin-bottom:5px; letter-spacing:-.2px;
}
.mhs-alert-desc {
    font-size:12.5px; color:rgba(255,255,255,.7); line-height:1.5;
}
.mhs-alert-desc strong { color:#FCA5A5; font-weight:700; }
 
/* Pills */
.mhs-alert-pills { display:flex; gap:7px; flex-wrap:wrap; margin-top:10px; }
.mhs-alert-pill {
    display:inline-flex; align-items:center; gap:5px;
    background:rgba(255,255,255,.12);
    border:1px solid rgba(255,255,255,.2);
    border-radius:20px; padding:3px 11px;
    font-size:11.5px; font-weight:600; color:#fff;
}
.mhs-alert-pill.pill-danger {
    background:rgba(239,68,68,.25);
    border-color:rgba(239,68,68,.4);
    color:#FCA5A5;
}
 
/* Right */
.mhs-alert-right {
    display:flex; align-items:center; gap:10px;
    flex-shrink:0; position:relative; z-index:1;
}
.mhs-alert-btn {
    background:#fff; color:#991B1B;
    border:none; border-radius:9px;
    padding:10px 18px; font-size:13px; font-weight:700;
    font-family:'Plus Jakarta Sans',sans-serif;
    cursor:pointer; text-decoration:none;
    display:inline-flex; align-items:center; gap:7px;
    transition:all .2s; white-space:nowrap;
    box-shadow:0 2px 8px rgba(0,0,0,.2);
}
.mhs-alert-btn:hover {
    background:#FEF2F2; color:#7F1D1D;
    transform:translateY(-2px);
    box-shadow:0 6px 16px rgba(0,0,0,.25);
}
.mhs-alert-close {
    width:34px; height:34px; border-radius:8px;
    background:rgba(255,255,255,.1);
    border:1px solid rgba(255,255,255,.15);
    color:rgba(255,255,255,.7); cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    font-size:13px; transition:all .2s; flex-shrink:0;
}
.mhs-alert-close:hover {
    background:rgba(255,255,255,.2); color:#fff;
    border-color:rgba(255,255,255,.3); transform:scale(1.05);
}
 
@media (max-width:768px) {
    .mhs-alert-wrap   { padding:16px 18px; gap:14px; }
    .mhs-alert-right  { width:100%; justify-content:space-between; }
    .mhs-alert-btn    { flex:1; justify-content:center; }
    .mhs-pulse-ring   { display:none; }
    .mhs-alert-title  { font-size:14px; }
    .mhs-alert-desc   { font-size:12px; }
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

{{-- ══ ALERT MAHASISWA ══ --}}
@php
    // $totalAlpha dan $kategoriRisiko sudah di-pass dari controller
    $spLabels = [
        'ps'  => 'Putus Studi',
        'sp3' => 'SP III',
        'sp2' => 'SP II',
        'sp1' => 'SP I',
    ];
    $hasAlphaRisk = !empty(array_intersect($kategoriRisiko, ['ps','sp3','sp2','sp1']));
    $hasNilaiE    = in_array('nilai_e', $kategoriRisiko);
    $hasNilaiD    = in_array('nilai_d', $kategoriRisiko);
    $hasIpsRendah = in_array('ips_rendah', $kategoriRisiko);
    $showAlert    = !empty($kategoriRisiko);
    $spAktif      = collect(['ps','sp3','sp2','sp1'])->first(fn($k) => in_array($k, $kategoriRisiko));
@endphp

@if($showAlert)
<div class="mhs-alert-wrap" id="mhsAlert">
    <div class="mhs-pulse-ring"></div>

    <div class="mhs-alert-left">
        <div class="mhs-alert-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>

        <div class="mhs-alert-content">
            <div class="mhs-alert-tag">⚡ Peringatan Akademik</div>

            @if($spAktif === 'ps')
                <div class="mhs-alert-title">Status Putus Studi — Segera Hubungi Jurusan!</div>
                <div class="mhs-alert-desc">
                    Total alpha Anda telah mencapai <strong>{{ $totalAlpha }} jam</strong>,
                    melampaui batas <strong>56 jam (Putus Studi)</strong>.
                    Hubungi Dosen PA dan Jurusan segera!
                </div>
            @elseif($spAktif === 'sp3')
                <div class="mhs-alert-title">Surat Peringatan III — Tindakan Segera Diperlukan!</div>
                <div class="mhs-alert-desc">
                    Total alpha Anda <strong>{{ $totalAlpha }} jam</strong> (batas SP III: 47 jam).
                    {{ (56 - $totalAlpha) > 0 ? (56 - $totalAlpha) . ' jam lagi ke batas Putus Studi.' : 'Telah melampaui batas Putus Studi!' }}
                    Segera konsultasi dengan Dosen PA!
                </div>
            @elseif($spAktif === 'sp2')
                <div class="mhs-alert-title">Surat Peringatan II — Risiko Tinggi!</div>
                <div class="mhs-alert-desc">
                    Total alpha Anda <strong>{{ $totalAlpha }} jam</strong> (batas SP II: 36 jam).
                    {{ (47 - $totalAlpha) }} jam lagi ke batas SP III. Perbaiki kehadiran segera!
                </div>
            @elseif($spAktif === 'sp1')
                <div class="mhs-alert-title">Surat Peringatan I — Perhatikan Kehadiran Anda!</div>
                <div class="mhs-alert-desc">
                    Total alpha Anda <strong>{{ $totalAlpha }} jam</strong> (batas SP I: 18 jam).
                    {{ (36 - $totalAlpha) }} jam lagi ke batas SP II. Jaga kehadiran Anda!
                </div>
            @elseif($hasNilaiE)
                <div class="mhs-alert-title">Terdapat Nilai E di Semester Ini!</div>
                <div class="mhs-alert-desc">
                    Nilai E merupakan indikator risiko akademik yang dapat mempengaruhi IPS dan kelulusan.
                    Segera konsultasikan dengan <strong>Dosen Pembimbing Akademik</strong>.
                </div>
            @elseif($hasNilaiD)
                <div class="mhs-alert-title">Nilai D Lebih dari 3 Mata Kuliah!</div>
                <div class="mhs-alert-desc">
                    Anda memiliki lebih dari 3 mata kuliah dengan nilai D di semester ini.
                    Kondisi ini termasuk indikator risiko akademik. Hubungi Dosen PA!
                </div>
            @elseif($hasIpsRendah)
                <div class="mhs-alert-title">IPS Semester Ini di Bawah 2.00!</div>
                <div class="mhs-alert-desc">
                    IPS &lt; 2.00 merupakan indikator risiko akademik sesuai Pedoman Akademik D4 TI Polinema.
                    Segera konsultasikan strategi peningkatan dengan <strong>Dosen PA</strong>.
                </div>
            @endif

            {{-- Detail pills --}}
            <div class="mhs-alert-pills">
                @if($spAktif)
                <span class="mhs-alert-pill pill-danger">
                    <i class="bi bi-clock-history"></i>
                    {{ $totalAlpha }}j Alpha — {{ $spLabels[$spAktif] }}
                </span>
                @endif
                @if($hasNilaiE)
                <span class="mhs-alert-pill pill-danger">
                    <i class="bi bi-journal-x"></i>
                    Ada Nilai E
                </span>
                @endif
                @if($hasNilaiD)
                <span class="mhs-alert-pill pill-danger">
                    <i class="bi bi-journal-minus"></i>
                    D &gt; 3 MK
                </span>
                @endif
                @if($hasIpsRendah)
                <span class="mhs-alert-pill pill-danger">
                    <i class="bi bi-graph-down"></i>
                    IPS &lt; 2.00
                </span>
                @endif
            </div>
        </div>
    </div>

    <div class="mhs-alert-right">
        <a href="{{ route('mahasiswa.nilai') }}" class="mhs-alert-btn">
            <i class="bi bi-eye-fill"></i>
            Lihat Detail
        </a>
        <button class="mhs-alert-close" id="mhsAlertClose" title="Tutup">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
</div>
@endif

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
    <div class="col-sm-4 col-12">
        @php
            $alphaColor  = $totalAlpha >= 47 ? '#7F1D1D'
                : ($totalAlpha >= 36 ? '#DC2626'
                : ($totalAlpha >= 18 ? '#EF4444'
                : ($totalAlpha >= 14 ? '#F59E0B' : '#22C55E')));
            $alphaAccent = $totalAlpha >= 18
                ? 'linear-gradient(90deg,#EF4444,#FCA5A5)'
                : ($totalAlpha >= 14 ? 'linear-gradient(90deg,#F59E0B,#FCD34D)'
                : 'linear-gradient(90deg,#22C55E,#86EFAC)');
            $spBadge = match(true) {
                $totalAlpha >= 56 => ['label' => '⛔ Putus Studi', 'class' => 'badge-down'],
                $totalAlpha >= 47 => ['label' => '⛔ SP III', 'class' => 'badge-down'],
                $totalAlpha >= 36 => ['label' => '⚠ SP II', 'class' => 'badge-down'],
                $totalAlpha >= 18 => ['label' => '⚠ SP I', 'class' => 'badge-warn'],
                $totalAlpha >= 14 => ['label' => '⚡ ' . (18 - $totalAlpha) . 'j lagi SP I', 'class' => 'badge-warn'],
                default           => ['label' => '✅ Aman', 'class' => 'badge-up'],
            };
        @endphp
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background: {{ $alphaAccent }};"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:{{ $totalAlpha >= 14 ? '#FEF3C7' : '#F0FDF4' }};">
                    <i class="bi bi-clock-fill" style="color:{{ $alphaColor }};"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Total Jam Ketidakhadiran</div>
                    <div class="stat-card-value" style="color:{{ $alphaColor }};">
                        {{ $totalAlpha }}
                        <span style="font-size:16px;font-weight:600;color:var(--text-2);">jam</span>
                    </div>
                    {{-- Progress: 56j = batas Putus Studi --}}
                    <div class="ipk-bar" style="background:#FEF3C7;">
                        <div class="ipk-bar-fill" style="width:{{ min(($totalAlpha/56)*100, 100) }}%; background:{{ $alphaColor }};"></div>
                    </div>
                    <div class="stat-card-note mt-2">
                        <span class="stat-card-badge {{ $spBadge['class'] }}">{{ $spBadge['label'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @php
    $kompenAktif = $mahasiswa->getKompensasiSemester($semesterAktif);
@endphp
@if($kompenAktif)
<div class="col-sm-4 col-12">
    <div class="stat-card-v2">
        <div class="stat-card-accent" style="background:{{ $kompenAktif->isLunas() ? 'linear-gradient(90deg,#22C55E,#86EFAC)' : 'linear-gradient(90deg,#F59E0B,#FCD34D)' }};"></div>
        <div class="stat-card-body">
            <div class="stat-icon-box" style="background:{{ $kompenAktif->isLunas() ? '#F0FDF4' : '#FEF3C7' }};">
                <i class="bi bi-clipboard2-check-fill" style="color:{{ $kompenAktif->isLunas() ? '#22C55E' : '#F59E0B' }};"></i>
            </div>
            <div class="stat-card-info">
                <div class="stat-card-label">Kompensasi Sem {{ $semesterAktif }}</div>
                <div class="stat-card-value" style="color:{{ $kompenAktif->isLunas() ? '#22C55E' : '#F59E0B' }};font-size:24px;">
                    {{ $kompenAktif->jam_kompen_wajib }}<span style="font-size:14px;font-weight:500;color:var(--text-2);"> jam</span>
                </div>
                <div class="stat-card-note mt-1">
                    @if($kompenAktif->isLunas())
                        <span class="stat-card-badge badge-up"><i class="bi bi-check-circle-fill"></i> Lunas</span>
                    @else
                        <span class="stat-card-badge badge-warn"><i class="bi bi-hourglass-split"></i> Pending</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif --}}

{{-- @if(isset($kompenAktif) && $kompenAktif && !$kompenAktif->isLunas())
<div class="card-white" style="border-left:4px solid #F59E0B;border-radius:12px;padding:16px 20px;margin-top:20px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
    <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:40px;height:40px;border-radius:10px;background:#FEF3C7;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">⏳</div>
        <div>
            <div style="font-size:14px;font-weight:700;color:var(--text-1);">Kompensasi Semester {{ $semesterAktif }} Belum Lunas</div>
            <div style="font-size:12.5px;color:var(--text-2);margin-top:2px;">
                Anda wajib menyelesaikan <strong style="color:#92400E;">{{ $kompenAktif->jam_kompen_wajib }} jam</strong> kompensasi
                ({{ $kompenAktif->sp_label }} — {{ $kompenAktif->jam_alpha }} jam alpha).
                Hubungi admin untuk surat kompensasi.
            </div>
        </div>
    </div>
    <span style="background:#FEF3C7;color:#92400E;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;white-space:nowrap;">
        {{ $kompenAktif->sp_label }} · Pending
    </span>
</div>
@endif --}}

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
                @foreach(['A'=>['#22C55E','Sangat Baik'],'B+'=>['#3B82F6','Baik Sekali'],'B'=>['#60A5FA','Baik'],'C+'=>['#FBBF24','Cukup Baik'],'C'=>['#A78BFA','Cukup'],'D'=>['#F97316','Kurang'],'E'=>['#EF4444','Sangat Kurang']] as $g => $info)
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
                    <div class="chart-sub-v2" id="absensiChartSub">Distribusi kehadiran semester {{ $semesterAktif }}</div>
                </div>
                <div class="filter-wrap">
                    <button class="btn-filter" id="filterAbsensiDonutBtn">
                        <i class="bi bi-sliders2" style="font-size:12px;"></i> Filter
                    </button>
                    <div class="filter-menu" id="filterAbsensiDonutMenu">
                        <div class="filter-menu-label">Pilih Semester</div>
                        @foreach($semesterListAbsensi as $sem)
                        <div class="filter-opt {{ (int)$sem === (int)$semesterAktif ? 'active' : '' }}" data-val="{{ $sem }}">Semester {{ $sem }}</div>
                        @endforeach
                        @if(!in_array((int)$semesterAktif, array_map('intval', $semesterListAbsensi)))
                        <div class="filter-opt active" data-val="{{ $semesterAktif }}">Semester {{ $semesterAktif }}</div>
                        @endif
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
                        <div class="donut-center-num" id="donutCenterNum">{{ $pctH }}%</div>
                        <div class="donut-center-sub" id="donutCenterSub">Hadir</div>
                    </div>
                </div>
                <div class="legend-v2">
                    <div>
                        <div class="legend-v2-row">
                            <div class="legend-v2-left">
                                <div class="legend-v2-dot" style="background:#22C55E;"></div>
                                <span class="legend-v2-label">Hadir</span>
                            </div>
                            <span class="legend-v2-val" id="legendHadirVal">{{ $sumHadir }}j</span>
                        </div>
                        <div class="legend-v2-bar"><div class="legend-v2-bar-fill" id="legendHadirBar" style="width:{{ $pctH }}%;background:#22C55E;"></div></div>
                    </div>
                    <div>
                        <div class="legend-v2-row">
                            <div class="legend-v2-left">
                                <div class="legend-v2-dot" style="background:#FBBF24;"></div>
                                <span class="legend-v2-label">Izin</span>
                            </div>
                            <span class="legend-v2-val" id="legendIzinVal">{{ $sumIzin }}j</span>
                        </div>
                        <div class="legend-v2-bar"><div class="legend-v2-bar-fill" id="legendIzinBar" style="width:{{ $pctI }}%;background:#FBBF24;"></div></div>
                    </div>
                    <div>
                        <div class="legend-v2-row">
                            <div class="legend-v2-left">
                                <div class="legend-v2-dot" style="background:#3B82F6;"></div>
                                <span class="legend-v2-label">Sakit</span>
                            </div>
                            <span class="legend-v2-val" id="legendSakitVal">{{ $sumSakit }}j</span>
                        </div>
                        <div class="legend-v2-bar"><div class="legend-v2-bar-fill" id="legendSakitBar" style="width:{{ $pctS }}%;background:#3B82F6;"></div></div>
                    </div>
                    <div>
                        <div class="legend-v2-row">
                            <div class="legend-v2-left">
                                <div class="legend-v2-dot" style="background:#EF4444;"></div>
                                <span class="legend-v2-label">Alpha</span>
                            </div>
                            <span class="legend-v2-val" id="legendAlphaVal" style="color:{{ $sumAlp >= 14 ? '#EF4444' : 'var(--text-1)' }};">{{ $sumAlp }}j</span>
                        </div>
                        <div class="legend-v2-bar"><div class="legend-v2-bar-fill" id="legendAlphaBar" style="width:{{ $pctA }}%;background:#EF4444;"></div></div>
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
                            $scoreColor = match($nilai->grade) {
                                'A'  => '#22C55E',
                                'B+' => '#3B82F6',
                                'B'  => '#60A5FA',
                                'C+' => '#FBBF24',
                                'C'  => '#A78BFA',
                                default => '#EF4444',
                            };
                            $gradeClass = str_replace('+', 'p', $nilai->grade);
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
                                <span class="grade-pill grade-{{ $gradeClass }}">{{ $nilai->grade }}</span>
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

{{-- ══ RIWAYAT KOMPENSASI ══ --}}
<div class="section-label" style="margin-top:24px;">Riwayat Kompensasi</div>
<div class="tbl-card-v2">
    <div class="tbl-head-v2">
        <div>
            <div class="tbl-title-v2">Riwayat Kompensasi</div>
            <div class="tbl-sub-v2">Seluruh semester · {{ $kompensasis->count() }} data</div>
        </div>
    </div>

    @if($kompensasis->isEmpty())
    <div style="text-align:center;padding:36px 24px;color:var(--text-3);">
        <i class="bi bi-clipboard2-x" style="font-size:28px;display:block;margin-bottom:8px;opacity:.5;"></i>
        Tidak ada riwayat kompensasi.
    </div>
    @else
    <div style="overflow-x:auto;">
        <table class="ac-table-v2">
            <thead>
                <tr>
                    <th>Semester</th>
                    <th style="text-align:center;">Jam Alpha</th>
                    <th style="text-align:center;">Jam Kompensasi Wajib</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;">TTD Admin</th>
                    <th style="text-align:center;">TTD Kajur</th>
                    <th style="text-align:center;">Tanggal Lunas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kompensasis as $kompen)
                <tr>
                    <td>
                        <div style="font-weight:600;color:var(--text-1);">Semester {{ $kompen->semester }}</div>
                        @if($kompen->tahun_akademik)
                        <div style="font-size:11px;color:var(--text-3);margin-top:1px;">{{ $kompen->tahun_akademik }}</div>
                        @endif
                    </td>
                    <td style="text-align:center;font-weight:700;color:#EF4444;">{{ $kompen->jam_alpha }}j</td>
                    <td style="text-align:center;font-weight:700;color:var(--text-1);">{{ $kompen->jam_kompen_wajib }}j</td>
                    <td style="text-align:center;">
                        @if($kompen->status === 'lunas')
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;background:#DCFCE7;color:#15803D;">
                                <i class="bi bi-check-circle-fill" style="font-size:10px;"></i> Lunas
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;background:#FEF9C3;color:#854D0E;">
                                <i class="bi bi-hourglass-split" style="font-size:10px;"></i> Belum Lunas
                            </span>
                        @endif
                    </td>
                    <td style="text-align:center;font-size:16px;">{{ $kompen->ttd_admin ? '✅' : '⏳' }}</td>
                    <td style="text-align:center;font-size:16px;">{{ $kompen->ttd_kajur ? '✅' : '⏳' }}</td>
                    <td style="text-align:center;font-size:13px;color:var(--text-2);">
                        {{ $kompen->tanggal_lunas ? $kompen->tanggal_lunas->format('d M Y') : '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
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

var G2Y = {A:5,'B+':4.5,B:4,'C+':3.5,C:3,D:2,E:1};
var G2C = {A:'#22C55E','B+':'#3B82F6',B:'#60A5FA','C+':'#FBBF24',C:'#A78BFA',D:'#F97316',E:'#EF4444'};
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
                        return [
                            ' Grade  : ' + GRADES[ctx.dataIndex],
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
                    callback: function(v){ var m={0:'',1:'E',2:'D',3:'C',4:'B',5:'A'}; return m[v]||''; },
                    font: { family:'Plus Jakarta Sans', size:11 }, color:'#64748B'
                },
                grid: { color:'#F8FAFC' },
                border: { display:false }
            }
        }
    }
});

// ── DONUT CHART ────────────────────────────────────
var ABSENSI_DATA = @json($absensiPerSemester);
var donutCtx = document.getElementById('absensiChart').getContext('2d');
var donutChart = new Chart(donutCtx, {
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

// ── FILTER SEMESTER DONUT ABSENSI ──────────────────
document.getElementById('filterAbsensiDonutBtn').addEventListener('filterChange', function(e) {
    var sem = parseInt(e.detail.value);
    var d   = ABSENSI_DATA[sem] || {hadir:0, izin:0, sakit:0, alpha:0};
    var tot = d.hadir + d.izin + d.sakit + d.alpha;
    var pH  = tot > 0 ? Math.round(d.hadir / tot * 100) : 0;
    var pI  = tot > 0 ? Math.round(d.izin  / tot * 100) : 0;
    var pS  = tot > 0 ? Math.round(d.sakit / tot * 100) : 0;
    var pA  = tot > 0 ? Math.round(d.alpha / tot * 100) : 0;

    donutChart.data.datasets[0].data = [d.alpha, d.izin, d.sakit, d.hadir];
    donutChart.update();

    document.getElementById('absensiChartSub').textContent  = 'Distribusi kehadiran semester ' + sem;
    document.getElementById('donutCenterNum').textContent    = pH + '%';

    document.getElementById('legendHadirVal').textContent  = d.hadir + 'j';
    document.getElementById('legendHadirBar').style.width  = pH + '%';
    document.getElementById('legendIzinVal').textContent   = d.izin  + 'j';
    document.getElementById('legendIzinBar').style.width   = pI + '%';
    document.getElementById('legendSakitVal').textContent  = d.sakit + 'j';
    document.getElementById('legendSakitBar').style.width  = pS + '%';
    document.getElementById('legendAlphaVal').textContent  = d.alpha + 'j';
    document.getElementById('legendAlphaVal').style.color  = d.alpha >= 18 ? '#EF4444' : 'var(--text-1)';
    document.getElementById('legendAlphaBar').style.width  = pA + '%';
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
<script>
(function() {
    var el   = document.getElementById('mhsAlert');
    var btnX = document.getElementById('mhsAlertClose');
    if (!el || !btnX) return;
 
    btnX.addEventListener('click', function() {
        el.style.animation = 'mhsAlertOut .35s cubic-bezier(.4,0,1,1) forwards';
        setTimeout(function() {
            el.style.display = 'none';
        }, 340);
    });
})();
</script>
@endpush