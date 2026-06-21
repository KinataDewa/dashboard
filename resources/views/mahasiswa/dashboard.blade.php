@extends('layouts.mahasiswa')

@section('title', 'Dashboard')
@section('page-title', 'Halo, ' . $mahasiswa->nama . '!')
@section('page-sub', 'Selamat datang di Dashboard Akademik')

@push('styles')
<style>
/* ── THEME UPGRADE ──────────────────────────────────── */
:root {
    --blue:      #3b82f6;
    --blue-hover:#2563eb;
    --blue-light:#eff6ff;
    --indigo:    #6366f1;
    --violet:    #8b5cf6;
    --green:     #22c55e;
    --amber:     #f59e0b;
    --red:       #ef4444;
}

/* Subtle radial glow on page */
body::before {
    content:''; position:fixed; inset:0; pointer-events:none; z-index:0;
    background:
        radial-gradient(ellipse at 15% 0%,  rgba(59,130,246,.05) 0%, transparent 55%),
        radial-gradient(ellipse at 85% 100%, rgba(99,102,241,.04) 0%, transparent 55%);
}
.page-wrap { position:relative; z-index:1; }

/* ── SECTION LABELS ─────────────────────────────────── */
.section-label {
    display:flex; align-items:center; gap:10px;
    font-size:10.5px; font-weight:800; color:#64748b;
    text-transform:uppercase; letter-spacing:1.2px;
    margin-bottom:14px; margin-top:6px;
}
.section-label::before {
    content:''; width:3px; height:15px; border-radius:2px; flex-shrink:0;
    background:linear-gradient(180deg, #3b82f6, #8b5cf6);
}
.section-label::after {
    content:''; flex:1; height:1px;
    background:linear-gradient(90deg, #e2e8f0, transparent);
}

/* ── STAT CARDS ─────────────────────────────────────── */
.stat-card-v2 {
    background:#fff;
    border:1px solid rgba(59,130,246,.1);
    border-radius:16px;
    box-shadow:0 4px 20px rgba(59,130,246,.07), 0 1px 4px rgba(0,0,0,.04);
    overflow:hidden;
    transition:transform .22s, box-shadow .22s;
    height:100%;
}
.stat-card-v2:hover {
    transform:translateY(-5px);
    box-shadow:0 16px 40px rgba(59,130,246,.13), 0 2px 8px rgba(0,0,0,.07);
}
.stat-card-accent { height:5px; border-radius:0; }
.stat-card-body   { padding:20px 22px; display:flex; gap:15px; align-items:flex-start; }
.stat-icon-box {
    width:48px; height:48px; border-radius:13px;
    display:flex; align-items:center; justify-content:center;
    font-size:21px; flex-shrink:0;
}
.stat-card-info   { flex:1; min-width:0; }
.stat-card-label  { font-size:11.5px; font-weight:600; color:#64748b; margin-bottom:5px; }
.stat-card-value  { font-size:34px; font-weight:900; line-height:1; letter-spacing:-2px; margin-bottom:8px; }
.stat-card-note   { font-size:11.5px; color:#64748b; display:flex; align-items:center; gap:5px; }
.stat-card-badge  { display:inline-flex; align-items:center; gap:3px; padding:3px 9px; border-radius:20px; font-size:11px; font-weight:700; }
.badge-up   { background:#dcfce7; color:#15803d; }
.badge-warn { background:#fef9c3; color:#854d0e; }
.badge-down { background:#fee2e2; color:#991b1b; }

/* IPK gradient text */
.ipk-num { background:linear-gradient(135deg,#2563eb,#60a5fa); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }

/* IPK progress bar */
.ipk-bar {
    width:100%; height:6px; background:#e0e7ff;
    border-radius:3px; margin:8px 0 6px; overflow:hidden;
}
.ipk-bar-fill {
    height:100%; border-radius:3px;
    background:linear-gradient(90deg,#3b82f6,#818cf8);
    transition:width 1s cubic-bezier(.4,0,.2,1);
}

/* ── CHART CARDS ────────────────────────────────────── */
.chart-card-v2 {
    background:linear-gradient(160deg,#fff 0%,#f8f9ff 100%);
    border:1px solid rgba(59,130,246,.1);
    border-radius:16px;
    box-shadow:0 4px 20px rgba(59,130,246,.07), 0 1px 4px rgba(0,0,0,.04);
    padding:24px;
    height:100%;
}
.chart-head-v2 { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:8px; }
.chart-title-v2 { font-size:15px; font-weight:700; color:#0f172a; }
.chart-sub-v2   { font-size:12px; color:#64748b; margin-top:2px; }

/* Donut */
.donut-wrap-v2 { display:flex; align-items:center; gap:20px; margin-top:16px; }
.donut-canvas-box { flex-shrink:0; width:150px; height:150px; position:relative; }
.donut-canvas-box canvas { width:150px !important; height:150px !important; display:block; }
.donut-center-text {
    position:absolute; top:50%; left:50%;
    transform:translate(-50%,-50%); text-align:center; pointer-events:none;
}
.donut-center-num { font-size:22px; font-weight:900; color:#0f172a; line-height:1; letter-spacing:-1px; }
.donut-center-sub { font-size:10px; color:#94a3b8; font-weight:500; margin-top:2px; }

.legend-v2 { flex:1; display:flex; flex-direction:column; gap:11px; }
.legend-v2-row { display:flex; align-items:center; justify-content:space-between; gap:8px; }
.legend-v2-left { display:flex; align-items:center; gap:8px; }
.legend-v2-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
.legend-v2-label { font-size:12.5px; color:#64748b; }
.legend-v2-val { font-size:12.5px; font-weight:700; color:#0f172a; }
.legend-v2-bar { width:100%; height:4px; background:#f1f5f9; border-radius:2px; margin-top:4px; overflow:hidden; }
.legend-v2-bar-fill { height:100%; border-radius:2px; transition:width .6s ease; }

/* ── TABLE CARDS ────────────────────────────────────── */
.tbl-card-v2 {
    background:#fff;
    border:1px solid rgba(59,130,246,.1);
    border-radius:16px;
    box-shadow:0 4px 20px rgba(59,130,246,.07), 0 1px 4px rgba(0,0,0,.04);
    padding:22px 24px;
}
.tbl-head-v2 { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:16px; gap:10px; flex-wrap:wrap; }
.tbl-title-v2 { font-size:15px; font-weight:700; color:#0f172a; }
.tbl-sub-v2   { font-size:11.5px; color:#64748b; margin-top:2px; }

.ac-table-v2 { width:100%; border-collapse:collapse; }
.ac-table-v2 thead th {
    font-size:10.5px; font-weight:700; color:#64748b;
    padding:0 12px 12px; text-align:left;
    border-bottom:2px solid #f1f5f9;
    text-transform:uppercase; letter-spacing:.7px;
    white-space:nowrap;
    background:linear-gradient(180deg,transparent,rgba(59,130,246,.015));
}
.ac-table-v2 tbody tr { border-bottom:1px solid #f8fafc; transition:background .15s; }
.ac-table-v2 tbody tr:last-child { border-bottom:none; }
.ac-table-v2 tbody tr:hover { background:linear-gradient(90deg,#f0f4ff,#f8faff); }
.ac-table-v2 tbody td { padding:12px 12px; font-size:13.5px; }

/* Grade pills */
.grade-pill { display:inline-flex; align-items:center; justify-content:center; min-width:30px; height:30px; border-radius:8px; font-size:12px; font-weight:800; padding:0 6px; }
.grade-A  { background:linear-gradient(135deg,#dcfce7,#bbf7d0); color:#15803d; }
.grade-Bp { background:linear-gradient(135deg,#dbeafe,#bfdbfe); color:#1e40af; }
.grade-B  { background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#1d4ed8; }
.grade-Cp { background:linear-gradient(135deg,#fef9c3,#fef08a); color:#78350f; }
.grade-C  { background:linear-gradient(135deg,#f5f3ff,#ede9fe); color:#6d28d9; }
.grade-D  { background:linear-gradient(135deg,#fee2e2,#fecaca); color:#991b1b; }
.grade-E  { background:linear-gradient(135deg,#fee2e2,#fca5a5); color:#7f1d1d; }

/* Score bar */
.score-bar { width:60px; height:5px; background:#f1f5f9; border-radius:3px; overflow:hidden; display:inline-block; vertical-align:middle; margin-left:6px; }
.score-bar-fill { height:100%; border-radius:3px; }

/* Semester select */
.sem-select {
    padding:6px 12px; border:1.5px solid #e2e8f0; border-radius:9px;
    font-size:12.5px; font-weight:600; color:#0f172a; background:#fff;
    cursor:pointer; outline:none; font-family:'Plus Jakarta Sans',sans-serif;
    transition:border-color .15s;
}
.sem-select:focus { border-color:var(--blue); }

/* Info chips */
.tbl-footer { display:flex; align-items:center; gap:8px; margin-top:14px; padding-top:12px; border-top:1px solid #f1f5f9; flex-wrap:wrap; }
.info-chip  { display:inline-flex; align-items:center; gap:5px; padding:4px 11px; border-radius:20px; font-size:11.5px; font-weight:600; background:#f1f5f9; color:#64748b; }
.info-chip i { font-size:12px; }

/* Absensi colored values */
.val-alpha { font-weight:700; }
.val-izin  { color:#f59e0b; font-weight:600; }
.val-sakit { color:#3b82f6; font-weight:600; }

/* ── ALERT ──────────────────────────────────────────── */
.mhs-alert-wrap {
    position:relative;
    background:linear-gradient(135deg,#1a0a0a 0%,#7f1d1d 40%,#991b1b 100%);
    border-radius:16px; padding:22px 26px;
    margin-bottom:26px;
    display:flex; align-items:center; justify-content:space-between; gap:16px;
    overflow:hidden; flex-wrap:wrap;
    box-shadow:0 8px 32px rgba(239,68,68,.25), 0 2px 8px rgba(239,68,68,.12);
    animation:alertIn .4s cubic-bezier(.16,1,.3,1) both;
}
@keyframes alertIn { from{opacity:0;transform:translateY(-14px) scale(.98);} to{opacity:1;transform:translateY(0) scale(1);} }
@keyframes alertOut { from{opacity:1;max-height:400px;margin-bottom:26px;padding:22px 26px;} to{opacity:0;max-height:0;margin-bottom:0;padding:0;} }

.mhs-alert-wrap::before {
    content:''; position:absolute; inset:0;
    background-image:radial-gradient(circle,rgba(255,255,255,.055) 1px,transparent 1px);
    background-size:24px 24px; pointer-events:none;
}
.mhs-alert-wrap::after {
    content:''; position:absolute; top:0; left:-100%; width:60%; height:100%;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.04),transparent);
    animation:sweepGlow 4s ease infinite; pointer-events:none;
}
@keyframes sweepGlow { 0%{left:-60%;} 100%{left:140%;} }

.mhs-pulse-ring {
    position:absolute; left:28px; top:50%; transform:translateY(-50%);
    width:54px; height:54px; border-radius:50%;
    background:rgba(239,68,68,.2);
    animation:ringPulse 2s ease-out infinite; pointer-events:none;
}
@keyframes ringPulse { 0%{transform:translateY(-50%) scale(1);opacity:.8;} 70%{transform:translateY(-50%) scale(1.85);opacity:0;} 100%{transform:translateY(-50%) scale(1);opacity:0;} }

.mhs-alert-left { display:flex; align-items:flex-start; gap:16px; flex:1; min-width:0; position:relative; z-index:1; }
.mhs-alert-icon {
    width:46px; height:46px; border-radius:13px;
    background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.2);
    display:flex; align-items:center; justify-content:center;
    font-size:21px; color:#fca5a5; flex-shrink:0;
    animation:iconShake 3s ease infinite;
}
@keyframes iconShake { 0%,88%,100%{transform:rotate(0);} 91%{transform:rotate(-8deg);} 94%{transform:rotate(8deg);} 97%{transform:rotate(-4deg);} }
.mhs-alert-content { min-width:0; }
.mhs-alert-tag { display:inline-flex; align-items:center; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); border-radius:20px; padding:2px 11px; font-size:11px; font-weight:700; color:#fca5a5; letter-spacing:.5px; margin-bottom:7px; }
.mhs-alert-title { font-size:15px; font-weight:800; color:#fff; line-height:1.3; margin-bottom:5px; letter-spacing:-.2px; }
.mhs-alert-desc  { font-size:12.5px; color:rgba(255,255,255,.7); line-height:1.5; }
.mhs-alert-desc strong { color:#fca5a5; font-weight:700; }
.mhs-alert-pills { display:flex; gap:7px; flex-wrap:wrap; margin-top:10px; }
.mhs-alert-pill { display:inline-flex; align-items:center; gap:5px; background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.2); border-radius:20px; padding:3px 11px; font-size:11.5px; font-weight:600; color:#fff; }
.mhs-alert-pill.pill-danger { background:rgba(239,68,68,.25); border-color:rgba(239,68,68,.4); color:#fca5a5; }
.mhs-alert-right { display:flex; align-items:center; gap:10px; flex-shrink:0; position:relative; z-index:1; }
.mhs-alert-btn {
    background:#fff; color:#991b1b; border:none; border-radius:10px;
    padding:10px 18px; font-size:13px; font-weight:700;
    font-family:'Plus Jakarta Sans',sans-serif; cursor:pointer;
    text-decoration:none; display:inline-flex; align-items:center; gap:7px;
    transition:all .2s; white-space:nowrap;
    box-shadow:0 2px 8px rgba(0,0,0,.2);
}
.mhs-alert-btn:hover { background:#fef2f2; color:#7f1d1d; transform:translateY(-2px); box-shadow:0 6px 16px rgba(0,0,0,.25); }
.mhs-alert-close {
    width:36px; height:36px; border-radius:9px;
    background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.15);
    color:rgba(255,255,255,.7); cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    font-size:13px; transition:all .2s; flex-shrink:0;
}
.mhs-alert-close:hover { background:rgba(255,255,255,.2); color:#fff; border-color:rgba(255,255,255,.3); transform:scale(1.06); }

/* ── FADE-IN ROWS ───────────────────────────────────── */
@keyframes fadeUp { from{opacity:0;transform:translateY(18px);} to{opacity:1;transform:translateY(0);} }
.dash-row { animation:fadeUp .5s cubic-bezier(.16,1,.3,1) both; }
.dash-row:nth-child(1){animation-delay:.05s;}
.dash-row:nth-child(2){animation-delay:.12s;}
.dash-row:nth-child(3){animation-delay:.19s;}
.dash-row:nth-child(4){animation-delay:.26s;}

/* ── MOTION SAFE ────────────────────────────────────── */
@media (prefers-reduced-motion: reduce) {
    .dash-row, .stat-card-v2, .mhs-alert-wrap, .aurora-orb { animation:none !important; }
    .stat-card-v2:hover { transform:none; }
}

/* ── MOBILE ─────────────────────────────────────────── */
@media (max-width:768px) {
    .mhs-alert-wrap  { padding:16px 18px; }
    .mhs-alert-right { width:100%; justify-content:space-between; }
    .mhs-alert-btn   { flex:1; justify-content:center; }
    .mhs-pulse-ring  { display:none; }
    .chart-card-v2   { padding:18px; }
    .tbl-card-v2     { padding:16px 18px; }
}
@media (max-width:576px) {
    .stat-card-value { font-size:27px; letter-spacing:-1.2px; }
    .donut-canvas-box { width:120px; height:120px; }
    .donut-canvas-box canvas { width:120px !important; height:120px !important; }
    .donut-center-num { font-size:17px; }
    .col-sks-hide { display:none; }
}
@media (max-width:480px) {
    .donut-wrap-v2 { flex-direction:column; align-items:center; }
    .legend-v2 { width:100%; }
    .tbl-head-v2 { flex-direction:column; align-items:stretch; }
    .tbl-actions { justify-content:flex-start; flex-wrap:wrap; }
}
</style>
@endpush

@section('content')

@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg,#1e3a8a 0%,#2563eb 55%,#3b82f6 100%)',
    'icon'         => 'bi-grid-1x2-fill',
    'title'        => 'Selamat datang, ' . $mahasiswa->nama . '!',
    'sub'          => 'Semester ' . $semesterAktif . ' · ' . ($mahasiswa->kelas->tahun_akademik ?? '2024/2025') . ' · ' . ($mahasiswa->kelas->nama ?? ''),
    'chips'        => [
        ['icon' => 'bi-mortarboard-fill',  'label' => 'Semester ' . $semesterAktif],
        ['icon' => 'bi-book-fill',         'label' => $nilais->count() . ' Mata Kuliah'],
        ['icon' => 'bi-x-circle-fill',     'label' => $totalAlpha . 'j Alpha'],
        ['icon' => 'bi-graph-up-arrow',    'label' => 'IPK ' . number_format($ipk, 2)],
    ],
    'badge_num'    => number_format($ipk, 2),
    'badge_label'  => "IPK\nKumulatif",
    'badge2_num'   => $nilais->count(),
    'badge2_label' => "Mata\nKuliah",
])

{{-- ══ ALERT ══ --}}
@php
    $spLabels = ['ps'=>'Putus Studi','sp3'=>'SP III','sp2'=>'SP II','sp1'=>'SP I'];
    $hasAlphaRisk = !empty(array_intersect($kategoriRisiko, ['ps','sp3','sp2','sp1']));
    $hasNilaiE    = in_array('nilai_e',    $kategoriRisiko);
    $hasNilaiD    = in_array('nilai_d',    $kategoriRisiko);
    $hasIpsRendah = in_array('ips_rendah', $kategoriRisiko);
    $showAlert    = !empty($kategoriRisiko);
    $spAktif      = collect(['ps','sp3','sp2','sp1'])->first(fn($k) => in_array($k, $kategoriRisiko));
@endphp

@if($showAlert)
<div class="mhs-alert-wrap dash-row" id="mhsAlert">
    <div class="mhs-pulse-ring"></div>
    <div class="mhs-alert-left">
        <div class="mhs-alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
        <div class="mhs-alert-content">
            <div class="mhs-alert-tag">Peringatan Akademik</div>
            @if($spAktif==='ps')
                <div class="mhs-alert-title">Status Putus Studi — Segera Hubungi Jurusan!</div>
                <div class="mhs-alert-desc">Total alpha Anda <strong>{{ $totalAlpha }} jam</strong>, melampaui batas <strong>56 jam (Putus Studi)</strong>. Hubungi Dosen PA dan Jurusan segera!</div>
            @elseif($spAktif==='sp3')
                <div class="mhs-alert-title">Surat Peringatan III — Tindakan Segera Diperlukan!</div>
                <div class="mhs-alert-desc">Total alpha <strong>{{ $totalAlpha }} jam</strong> (batas SP III: 47 jam). {{ (56-$totalAlpha)>0?(56-$totalAlpha).' jam lagi ke batas Putus Studi.':'Telah melampaui batas Putus Studi!' }} Segera konsultasi dengan Dosen PA!</div>
            @elseif($spAktif==='sp2')
                <div class="mhs-alert-title">Surat Peringatan II — Risiko Tinggi!</div>
                <div class="mhs-alert-desc">Total alpha <strong>{{ $totalAlpha }} jam</strong> (batas SP II: 36 jam). {{ (47-$totalAlpha) }} jam lagi ke batas SP III.</div>
            @elseif($spAktif==='sp1')
                <div class="mhs-alert-title">Surat Peringatan I — Perhatikan Kehadiran Anda!</div>
                <div class="mhs-alert-desc">Total alpha <strong>{{ $totalAlpha }} jam</strong> (batas SP I: 18 jam). {{ (36-$totalAlpha) }} jam lagi ke batas SP II.</div>
            @elseif($hasNilaiE)
                <div class="mhs-alert-title">Terdapat Nilai E di Semester Ini!</div>
                <div class="mhs-alert-desc">Nilai E merupakan indikator risiko akademik. Segera konsultasikan dengan <strong>Dosen Pembimbing Akademik</strong>.</div>
            @elseif($hasNilaiD)
                <div class="mhs-alert-title">Nilai D Lebih dari 3 Mata Kuliah!</div>
                <div class="mhs-alert-desc">Lebih dari 3 mata kuliah dengan nilai D termasuk indikator risiko akademik. Hubungi Dosen PA!</div>
            @elseif($hasIpsRendah)
                <div class="mhs-alert-title">IPS Semester Ini di Bawah 2.00!</div>
                <div class="mhs-alert-desc">IPS &lt; 2.00 merupakan indikator risiko akademik. Segera konsultasikan dengan <strong>Dosen PA</strong>.</div>
            @endif
            <div class="mhs-alert-pills">
                @if($spAktif)<span class="mhs-alert-pill pill-danger"><i class="bi bi-clock-history"></i>{{ $totalAlpha }}j Alpha — {{ $spLabels[$spAktif] }}</span>@endif
                @if($hasNilaiE)<span class="mhs-alert-pill pill-danger"><i class="bi bi-journal-x"></i>Ada Nilai E</span>@endif
                @if($hasNilaiD)<span class="mhs-alert-pill pill-danger"><i class="bi bi-journal-minus"></i>D &gt; 3 MK</span>@endif
                @if($hasIpsRendah)<span class="mhs-alert-pill pill-danger"><i class="bi bi-graph-down"></i>IPS &lt; 2.00</span>@endif
            </div>
        </div>
    </div>
    <div class="mhs-alert-right">
        <a href="{{ route('mahasiswa.nilai') }}" class="mhs-alert-btn"><i class="bi bi-eye-fill"></i>Lihat Detail</a>
        <button class="mhs-alert-close" id="mhsAlertClose" title="Tutup"><i class="bi bi-x-lg"></i></button>
    </div>
</div>
@endif

{{-- ══ STAT CARDS ══ --}}
<div class="section-label dash-row">Ringkasan Akademik</div>
<div class="row g-3 mb-4 dash-row">

    {{-- IPK --}}
    <div class="col-sm-4 col-12">
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background:linear-gradient(90deg,#2563eb,#818cf8,#a78bfa);"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">
                    <i class="bi bi-trophy-fill" style="color:#2563eb;"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Indeks Prestasi Kumulatif</div>
                    <div class="stat-card-value ipk-num" id="ipkVal">{{ number_format($ipk, 2) }}</div>
                    <div class="ipk-bar">
                        <div class="ipk-bar-fill" style="width:{{ ($ipk/4)*100 }}%;"></div>
                    </div>
                    <div class="stat-card-note">
                        @if($ipk >= 3.5)
                            <span class="stat-card-badge badge-up"><i class="bi bi-arrow-up"></i>Sangat Memuaskan</span>
                        @elseif($ipk >= 3.0)
                            <span class="stat-card-badge badge-up"><i class="bi bi-check2"></i>Memuaskan</span>
                        @elseif($ipk >= 2.5)
                            <span class="stat-card-badge badge-warn"><i class="bi bi-dash"></i>Cukup</span>
                        @else
                            <span class="stat-card-badge badge-down"><i class="bi bi-arrow-down"></i>Perlu Ditingkatkan</span>
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
            <div class="stat-card-accent" style="background:{{ $jumlahDE > 0 ? 'linear-gradient(90deg,#ef4444,#fca5a5)' : 'linear-gradient(90deg,#22c55e,#86efac)' }};"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:{{ $jumlahDE > 0 ? 'linear-gradient(135deg,#fef2f2,#fecaca)' : 'linear-gradient(135deg,#f0fdf4,#bbf7d0)' }};">
                    <i class="bi bi-{{ $jumlahDE > 0 ? 'exclamation-triangle-fill' : 'check-circle-fill' }}" style="color:{{ $jumlahDE > 0 ? '#ef4444' : '#22c55e' }};"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Jumlah Nilai Kategori D/E</div>
                    <div class="stat-card-value" style="{{ $jumlahDE > 0 ? 'color:#ef4444;' : 'background:linear-gradient(135deg,#22c55e,#16a34a);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;' }}">
                        {{ $jumlahDE }}
                        <span style="font-size:14px;font-weight:500;color:#94a3b8;-webkit-text-fill-color:#94a3b8;">mk</span>
                    </div>
                    <div class="stat-card-note">
                        @if($jumlahDE > 0)
                            <span class="stat-card-badge badge-down"><i class="bi bi-exclamation-circle"></i>Perlu perhatian segera</span>
                        @else
                            <span class="stat-card-badge badge-up"><i class="bi bi-shield-check"></i>Semua nilai aman</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alpha --}}
    <div class="col-sm-4 col-12">
        @php
            $alphaColor  = $totalAlpha >= 47 ? '#7f1d1d' : ($totalAlpha >= 36 ? '#dc2626' : ($totalAlpha >= 18 ? '#ef4444' : ($totalAlpha >= 14 ? '#f59e0b' : '#22c55e')));
            $alphaGrad   = $totalAlpha >= 18 ? 'linear-gradient(90deg,#ef4444,#fca5a5)' : ($totalAlpha >= 14 ? 'linear-gradient(90deg,#f59e0b,#fcd34d)' : 'linear-gradient(90deg,#22c55e,#86efac)');
            $alphaIconBg = $totalAlpha >= 18 ? 'linear-gradient(135deg,#fef2f2,#fecaca)' : ($totalAlpha >= 14 ? 'linear-gradient(135deg,#fffbeb,#fde68a)' : 'linear-gradient(135deg,#f0fdf4,#bbf7d0)');
            $spBadge = match(true) {
                $totalAlpha >= 56 => ['label' => 'Putus Studi', 'class' => 'badge-down'],
                $totalAlpha >= 47 => ['label' => 'SP III', 'class' => 'badge-down'],
                $totalAlpha >= 36 => ['label' => 'SP II', 'class' => 'badge-down'],
                $totalAlpha >= 18 => ['label' => 'SP I', 'class' => 'badge-warn'],
                $totalAlpha >= 14 => ['label' => (18-$totalAlpha).'j lagi SP I', 'class' => 'badge-warn'],
                default           => ['label' => 'Kehadiran Aman', 'class' => 'badge-up'],
            };
        @endphp
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background:{{ $alphaGrad }};"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:{{ $alphaIconBg }};">
                    <i class="bi bi-clock-fill" style="color:{{ $alphaColor }};"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Total Jam Ketidakhadiran</div>
                    <div class="stat-card-value" style="color:{{ $alphaColor }};">
                        {{ $totalAlpha }}<span style="font-size:16px;font-weight:600;color:#94a3b8;">j</span>
                    </div>
                    <div class="ipk-bar" style="background:{{ $totalAlpha >= 14 ? '#fef9c3' : '#f0fdf4' }};">
                        <div class="ipk-bar-fill" style="width:{{ min(($totalAlpha/56)*100,100) }}%; background:{{ $alphaColor }};"></div>
                    </div>
                    <div class="stat-card-note mt-1">
                        <span class="stat-card-badge {{ $spBadge['class'] }}">{{ $spBadge['label'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ CHARTS ══ --}}
<div class="section-label dash-row">Laporan Visual</div>
<div class="row g-3 mb-4 dash-row">

    {{-- Bar Chart --}}
    <div class="col-lg-7 col-12">
        <div class="chart-card-v2">
            <div class="chart-head-v2">
                <div>
                    <div class="chart-title-v2">Laporan Nilai</div>
                    <div class="chart-sub-v2" id="barChartSub">Nilai akhir per mata kuliah semester {{ $semesterAktif }}</div>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <select id="semFilterNilai" class="sem-select" onchange="onSemNilaiChange(this.value)">
                        @forelse($semesterListNilai as $sem)
                        <option value="{{ $sem }}" {{ (int)$sem===(int)$semesterAktif ? 'selected' : '' }}>Sem {{ $sem }}</option>
                        @empty
                        <option value="{{ $semesterAktif }}">Sem {{ $semesterAktif }}</option>
                        @endforelse
                    </select>
                    <div class="filter-wrap">
                        <button class="btn-filter" id="filterBarBtn"><i class="bi bi-sliders2" style="font-size:12px;"></i> Filter</button>
                        <div class="filter-menu" id="filterBarMenu">
                            <div class="filter-menu-label">Pilih Filter</div>
                            <div class="filter-opt active" data-val="all">Semua Mata Kuliah</div>
                            <div class="filter-opt" data-val="de">Nilai D/E Saja</div>
                            <div class="filter-opt" data-val="ab">Nilai A/B Saja</div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="position:relative;height:220px;margin-top:16px;">
                <canvas id="nilaiChart"></canvas>
            </div>
            <div class="d-flex gap-3 flex-wrap mt-3">
                @foreach(['A'=>['#22c55e','Sangat Baik'],'B+'=>['#3b82f6','Baik Sekali'],'B'=>['#60a5fa','Baik'],'C+'=>['#fbbf24','Cukup Baik'],'C'=>['#a78bfa','Cukup'],'D'=>['#f97316','Kurang'],'E'=>['#ef4444','Sangat Kurang']] as $g=>$info)
                <div style="display:flex;align-items:center;gap:5px;font-size:11px;color:#64748b;">
                    <div style="width:8px;height:8px;border-radius:3px;background:{{ $info[0] }};flex-shrink:0;"></div>
                    <span style="font-weight:600;">{{ $g }}</span> – {{ $info[1] }}
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
                    <button class="btn-filter" id="filterAbsensiDonutBtn"><i class="bi bi-sliders2" style="font-size:12px;"></i> Filter</button>
                    <div class="filter-menu" id="filterAbsensiDonutMenu">
                        <div class="filter-menu-label">Pilih Semester</div>
                        @foreach($semesterListAbsensi as $sem)
                        <div class="filter-opt {{ (int)$sem===(int)$semesterAktif?'active':'' }}" data-val="{{ $sem }}">Semester {{ $sem }}</div>
                        @endforeach
                        @if(!in_array((int)$semesterAktif,array_map('intval',$semesterListAbsensi)))
                        <div class="filter-opt active" data-val="{{ $semesterAktif }}">Semester {{ $semesterAktif }}</div>
                        @endif
                    </div>
                </div>
            </div>

            @php
                $sumIzin  = (int) $absensis->sum('jam_izin');
                $sumSakit = (int) $absensis->sum('jam_sakit');
                $sumAlp   = (int) $absensis->sum('jam_alpha');
                $sumAll   = $sumAlp + $sumIzin + $sumSakit;
                $pctI = $sumAll > 0 ? round($sumIzin/$sumAll*100) : 0;
                $pctS = $sumAll > 0 ? round($sumSakit/$sumAll*100) : 0;
                $pctA = $sumAll > 0 ? round($sumAlp/$sumAll*100) : 0;
            @endphp

            <div class="donut-wrap-v2">
                <div class="donut-canvas-box">
                    <canvas id="absensiChart" width="150" height="150"></canvas>
                    <div class="donut-center-text">
                        <div class="donut-center-num" id="donutCenterNum">{{ $sumAlp }}j</div>
                        <div class="donut-center-sub" id="donutCenterSub">Alpha</div>
                    </div>
                </div>
                <div class="legend-v2">
                    <div>
                        <div class="legend-v2-row">
                            <div class="legend-v2-left">
                                <div class="legend-v2-dot" style="background:#ef4444;box-shadow:0 0 6px rgba(239,68,68,.4);"></div>
                                <span class="legend-v2-label">Alpha</span>
                            </div>
                            <span class="legend-v2-val" id="legendAlphaVal" style="color:{{ $sumAlp>=14?'#ef4444':'#0f172a' }};">{{ $sumAlp }}j</span>
                        </div>
                        <div class="legend-v2-bar"><div class="legend-v2-bar-fill" id="legendAlphaBar" style="width:{{ $pctA }}%;background:#ef4444;"></div></div>
                    </div>
                    <div>
                        <div class="legend-v2-row">
                            <div class="legend-v2-left">
                                <div class="legend-v2-dot" style="background:#fbbf24;box-shadow:0 0 6px rgba(251,191,36,.4);"></div>
                                <span class="legend-v2-label">Izin</span>
                            </div>
                            <span class="legend-v2-val" id="legendIzinVal">{{ $sumIzin }}j</span>
                        </div>
                        <div class="legend-v2-bar"><div class="legend-v2-bar-fill" id="legendIzinBar" style="width:{{ $pctI }}%;background:#fbbf24;"></div></div>
                    </div>
                    <div>
                        <div class="legend-v2-row">
                            <div class="legend-v2-left">
                                <div class="legend-v2-dot" style="background:#3b82f6;box-shadow:0 0 6px rgba(59,130,246,.4);"></div>
                                <span class="legend-v2-label">Sakit</span>
                            </div>
                            <span class="legend-v2-val" id="legendSakitVal">{{ $sumSakit }}j</span>
                        </div>
                        <div class="legend-v2-bar"><div class="legend-v2-bar-fill" id="legendSakitBar" style="width:{{ $pctS }}%;background:#3b82f6;"></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ TABEL ══ --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:14px;margin-top:4px;" class="dash-row">
    <div class="section-label" style="margin:0;flex:1;">Detail Data</div>
    <div style="display:flex;align-items:center;gap:8px;">
        <span style="font-size:12px;font-weight:600;color:#64748b;">Semester</span>
        <select id="semFilterTbl" class="sem-select" onchange="onSemNilaiChange(this.value)">
            @forelse($semesterListNilai as $sem)
            <option value="{{ $sem }}" {{ (int)$sem===(int)$semesterAktif?'selected':'' }}>Semester {{ $sem }}</option>
            @empty
            <option value="{{ $semesterAktif }}">Semester {{ $semesterAktif }}</option>
            @endforelse
        </select>
    </div>
</div>

<div class="row g-3 dash-row">

    {{-- Nilai Akademik --}}
    <div class="col-lg-6 col-12">
        <div class="tbl-card-v2">
            <div class="tbl-head-v2">
                <div>
                    <div class="tbl-title-v2">Nilai Akademik</div>
                    <div class="tbl-sub-v2" id="nilaiTblSub">Semester {{ $semesterAktif }} • {{ $nilais->count() }} mata kuliah</div>
                </div>
                <div class="tbl-actions">
                    <div class="search-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Cari..." id="searchNilai">
                    </div>
                    <div class="filter-wrap">
                        <button class="btn-filter" id="filterNilaiTblBtn"><i class="bi bi-sliders2" style="font-size:12px;"></i></button>
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
                            <th class="col-sks-hide" style="text-align:center;">SKS</th>
                            <th style="text-align:center;">Nilai</th>
                            <th style="text-align:center;">Grade</th>
                        </tr>
                    </thead>
                    <tbody id="nilaiTableBody">
                        @forelse($nilais->take(7) as $nilai)
                        @php
                            $isDE = in_array($nilai->grade, ['D','E']);
                            $scoreWidth = min((float) $nilai->nilai_akhir, 100);
                            $scoreColor = match($nilai->grade) {
                                'A'  => '#22c55e', 'B+' => '#3b82f6',
                                'B'  => '#60a5fa', 'C+' => '#fbbf24',
                                'C'  => '#a78bfa', default => '#ef4444',
                            };
                            $gradeClass = str_replace('+','p',$nilai->grade);
                        @endphp
                        <tr data-matkul="{{ strtolower($nilai->mataKuliah->nama) }}" data-status="{{ $isDE?'perhatian':'baik' }}">
                            <td>
                                <div style="font-weight:600;color:#0f172a;">{{ $nilai->mataKuliah->nama }}</div>
                                <div style="font-size:11px;color:#94a3b8;margin-top:1px;">{{ $nilai->mataKuliah->kode }}</div>
                            </td>
                            <td class="col-sks-hide" style="text-align:center;color:#64748b;font-weight:600;">{{ $nilai->mataKuliah->sks }}</td>
                            <td style="text-align:center;">
                                <div style="display:flex;align-items:center;gap:6px;justify-content:center;">
                                    <span style="font-weight:800;font-size:13.5px;color:{{ $scoreColor }};">{{ number_format($nilai->nilai_akhir,1) }}</span>
                                    <div class="score-bar"><div class="score-bar-fill" style="width:{{ $scoreWidth }}%;background:{{ $scoreColor }};"></div></div>
                                </div>
                            </td>
                            <td style="text-align:center;"><span class="grade-pill grade-{{ $gradeClass }}">{{ $nilai->grade }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;padding:32px;color:#94a3b8;">
                                <i class="bi bi-inbox" style="font-size:28px;display:block;margin-bottom:8px;opacity:.5;"></i>
                                Belum ada data nilai.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="tbl-footer">
                <div class="info-chip" style="background:#eff6ff;color:#1d4ed8;">
                    <i class="bi bi-star-fill"></i>IP: {{ number_format($ipSemester,2) }}
                </div>
                <div class="info-chip"><i class="bi bi-book"></i>{{ $nilais->sum('mataKuliah.sks') }} SKS</div>
                @if($nilaiDE->count() > 0)
                <div class="info-chip" style="background:#fee2e2;color:#991b1b;">
                    <i class="bi bi-exclamation-triangle-fill"></i>{{ $nilaiDE->count() }} nilai D/E
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Kolom Kanan --}}
    <div class="col-lg-6 col-12 d-flex flex-column gap-3">

        {{-- Riwayat Absensi --}}
        <div class="tbl-card-v2">
            <div class="tbl-head-v2">
                <div>
                    <div class="tbl-title-v2">Riwayat Absensi</div>
                    <div class="tbl-sub-v2" id="absensiTblSub">Semester {{ $semesterAktif }} • {{ $allAbsensis->count() }} data</div>
                </div>
                <div class="tbl-actions">
                    <a href="{{ route('mahasiswa.absensi') }}" class="btn-outline">View All</a>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table class="ac-table-v2">
                    <thead>
                        <tr>
                            <th style="text-align:center;">Sem</th>
                            <th style="text-align:center;">Alpha</th>
                            <th style="text-align:center;">Izin</th>
                            <th style="text-align:center;">Sakit</th>
                        </tr>
                    </thead>
                    <tbody id="absensiTableBody">
                        @forelse($allAbsensis->sortByDesc('semester') as $absen)
                        @php $isAktif = (int)$absen->semester === (int)$semesterAktif; @endphp
                        <tr data-semester="{{ $absen->semester }}" {{ (int)$absen->semester!==(int)$semesterAktif?'style=display:none':'' }}>
                            <td style="text-align:center;">
                                <span style="font-weight:700;color:{{ $isAktif?'#3b82f6':'#0f172a' }};">{{ $absen->semester }}</span>
                                @if($isAktif)<span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:#22c55e;vertical-align:middle;margin-left:3px;box-shadow:0 0 5px #22c55e;"></span>@endif
                            </td>
                            @php $alphaColor = $absen->jam_alpha>=18?'#ef4444':($absen->jam_alpha>=14?'#f59e0b':'#64748b'); @endphp
                            <td style="text-align:center;color:{{ $alphaColor }};font-weight:700;">
                                <span>{{ $absen->jam_alpha }}</span>
                                @if($absen->jam_alpha>=18)<i class="bi bi-exclamation-circle-fill" style="color:#ef4444;font-size:11px;margin-left:2px;"></i>
                                @elseif($absen->jam_alpha>=14)<i class="bi bi-exclamation-triangle-fill" style="color:#f59e0b;font-size:11px;margin-left:2px;"></i>@endif
                            </td>
                            <td style="text-align:center;" class="val-izin">{{ $absen->jam_izin }}</td>
                            <td style="text-align:center;" class="val-sakit">{{ $absen->jam_sakit }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;padding:28px;color:#94a3b8;">
                                <i class="bi bi-calendar-x" style="font-size:26px;display:block;margin-bottom:8px;opacity:.5;"></i>
                                Belum ada data absensi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="tbl-footer">
                @php $absenAktif = $absensis->first(); @endphp
                @if($absenAktif)
                <div class="info-chip" style="background:#f0fdf4;color:#15803d;"><i class="bi bi-person-check-fill"></i>Hadir: {{ $absenAktif->jam_hadir }}j</div>
                <div class="info-chip" style="{{ $absenAktif->jam_alpha>=18?'background:#fee2e2;color:#991b1b;':'' }}"><i class="bi bi-x-circle-fill"></i>Alpha: {{ $absenAktif->jam_alpha }}j</div>
                @if($absenAktif->jam_alpha>=18)
                <div class="info-chip" style="background:#fee2e2;color:#991b1b;"><i class="bi bi-exclamation-triangle-fill"></i>SP I</div>
                @endif
                @endif
            </div>
        </div>

        {{-- Kompensasi --}}
        <div class="tbl-card-v2">
            <div class="tbl-head-v2">
                <div>
                    <div class="tbl-title-v2">Riwayat Kompensasi</div>
                    <div class="tbl-sub-v2">Seluruh semester · {{ $kompensasis->count() }} data</div>
                </div>
            </div>
            @if($kompensasis->isEmpty())
            <div style="text-align:center;padding:28px 24px;color:#94a3b8;">
                <i class="bi bi-clipboard2-x" style="font-size:26px;display:block;margin-bottom:8px;opacity:.45;"></i>
                Tidak ada riwayat kompensasi.
            </div>
            @else
            <div style="overflow-x:auto;">
                <table class="ac-table-v2">
                    <thead>
                        <tr>
                            <th>Semester</th>
                            <th style="text-align:center;">Alpha</th>
                            <th style="text-align:center;">Wajib</th>
                            <th style="text-align:center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kompensasis as $kompen)
                        <tr>
                            <td>
                                <div style="font-weight:600;color:#0f172a;">Semester {{ $kompen->semester }}</div>
                                @if($kompen->tahun_akademik)<div style="font-size:11px;color:#94a3b8;margin-top:1px;">{{ $kompen->tahun_akademik }}</div>@endif
                            </td>
                            <td style="text-align:center;font-weight:700;color:#ef4444;">{{ $kompen->jam_alpha }}j</td>
                            <td style="text-align:center;font-weight:700;color:#0f172a;">{{ $kompen->jam_kompen_wajib }}j</td>
                            <td style="text-align:center;">
                                @if($kompen->status==='lunas')
                                    <span class="badge badge-green"><i class="bi bi-check-circle-fill"></i> Lunas</span>
                                @else
                                    <span class="badge badge-yellow"><i class="bi bi-hourglass-split"></i> Belum Lunas</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
// ── Chart Data ──────────────────────────────────────
@php
    $cLabels=[]; $cGrades=[]; $cValues=[];
    foreach($nilais as $n) {
        $nm = mb_strlen($n->mataKuliah->nama)>14 ? mb_substr($n->mataKuliah->nama,0,12).'..' : $n->mataKuliah->nama;
        $cLabels[]=$nm; $cGrades[]=$n->grade; $cValues[]=round((float)$n->nilai_akhir,1);
    }
@endphp
var LABELS = @json($cLabels);
var GRADES = @json($cGrades);
var VALUES = @json($cValues);
var G2Y = {A:5,'B+':4.5,B:4,'C+':3.5,C:3,D:2,E:1};
var G2C = {A:'#22c55e','B+':'#3b82f6',B:'#60a5fa','C+':'#fbbf24',C:'#a78bfa',D:'#f97316',E:'#ef4444'};
var barData   = GRADES.map(function(g){ return G2Y[g]||0; });
var barColors = GRADES.map(function(g){ return G2C[g]||'#3b82f6'; });

// ── Bar Chart ───────────────────────────────────────
var barCtx   = document.getElementById('nilaiChart').getContext('2d');
var barChart = new Chart(barCtx, {
    type:'bar',
    data:{ labels:LABELS, datasets:[{ data:barData, backgroundColor:barColors, borderRadius:8, borderSkipped:false, maxBarThickness:44 }] },
    options:{
        responsive:true, maintainAspectRatio:false,
        plugins:{
            legend:{display:false},
            tooltip:{
                backgroundColor:'#0f172a', padding:12, cornerRadius:10, borderColor:'rgba(255,255,255,0.08)', borderWidth:1,
                callbacks:{
                    title:function(items){ return LABELS[items[0].dataIndex]; },
                    label:function(ctx){ return [' Grade  : '+GRADES[ctx.dataIndex], ' Nilai  : '+VALUES[ctx.dataIndex]]; }
                }
            }
        },
        scales:{
            x:{ grid:{display:false}, ticks:{font:{family:'Plus Jakarta Sans',size:11},color:'#94a3b8',maxRotation:45,minRotation:0} },
            y:{
                min:0, max:5,
                ticks:{ stepSize:1, callback:function(v){return {0:'',1:'E',2:'D',3:'C',4:'B',5:'A'}[v]||'';}, font:{family:'Plus Jakarta Sans',size:11}, color:'#94a3b8' },
                grid:{color:'rgba(241,245,249,1)'}, border:{display:false}
            }
        }
    }
});

// ── Donut Chart ─────────────────────────────────────
var ABSENSI_DATA = @json($absensiPerSemester);
var donutCtx = document.getElementById('absensiChart').getContext('2d');
var donutChart = new Chart(donutCtx, {
    type:'doughnut',
    data:{
        labels:['Alpha','Izin','Sakit'],
        datasets:[{ data:[{{ $sumAlp }},{{ $sumIzin }},{{ $sumSakit }}], backgroundColor:['#ef4444','#fbbf24','#3b82f6'], borderWidth:3, borderColor:'#fff', hoverOffset:6 }]
    },
    options:{ responsive:false, maintainAspectRatio:false, cutout:'70%',
        plugins:{ legend:{display:false}, tooltip:{ backgroundColor:'#0f172a', padding:10, cornerRadius:8, callbacks:{label:function(c){return ' '+c.label+': '+c.raw+' jam';}} } }
    }
});

// ── Filter Bar Chart ────────────────────────────────
document.getElementById('filterBarBtn').addEventListener('filterChange', function(e) {
    var val=e.detail.value, idx=[];
    GRADES.forEach(function(g,i){
        if(val==='de'&&['D','E'].indexOf(g)>-1) idx.push(i);
        else if(val==='ab'&&['A','B'].indexOf(g)>-1) idx.push(i);
        else if(!val||val==='all') idx.push(i);
    });
    barChart.data.labels=idx.map(function(i){return LABELS[i];});
    barChart.data.datasets[0].data=idx.map(function(i){return barData[i];});
    barChart.data.datasets[0].backgroundColor=idx.map(function(i){return barColors[i];});
    barChart.update();
});

// ── Filter Donut Absensi ────────────────────────────
document.getElementById('filterAbsensiDonutBtn').addEventListener('filterChange', function(e) {
    var sem=parseInt(e.detail.value);
    var d=ABSENSI_DATA[sem]||{hadir:0,izin:0,sakit:0,alpha:0};
    var tot=d.alpha+d.izin+d.sakit;
    donutChart.data.datasets[0].data=[d.alpha,d.izin,d.sakit];
    donutChart.update();
    document.getElementById('absensiChartSub').textContent='Distribusi kehadiran semester '+sem;
    document.getElementById('donutCenterNum').textContent=d.alpha+'j';
    document.getElementById('legendAlphaVal').textContent=d.alpha+'j';
    document.getElementById('legendAlphaVal').style.color=d.alpha>=18?'#ef4444':'#0f172a';
    document.getElementById('legendAlphaBar').style.width=(tot>0?Math.round(d.alpha/tot*100):0)+'%';
    document.getElementById('legendIzinVal').textContent=d.izin+'j';
    document.getElementById('legendIzinBar').style.width=(tot>0?Math.round(d.izin/tot*100):0)+'%';
    document.getElementById('legendSakitVal').textContent=d.sakit+'j';
    document.getElementById('legendSakitBar').style.width=(tot>0?Math.round(d.sakit/tot*100):0)+'%';
});

// ── Filter Semester Nilai ───────────────────────────
var NILAI_API_URL='{{ route("mahasiswa.api.nilai") }}';
function onSemNilaiChange(sem) {
    sem=parseInt(sem);
    document.getElementById('semFilterNilai').value=sem;
    document.getElementById('semFilterTbl').value=sem;
    document.getElementById('barChartSub').textContent='Nilai akhir per mata kuliah semester '+sem;
    document.getElementById('nilaiTblSub').textContent='Semester '+sem+' • Memuat…';
    var absensiRowCount=document.querySelectorAll('#absensiTableBody tr[data-semester="'+sem+'"]').length;
    document.getElementById('absensiTblSub').textContent='Semester '+sem+' • '+absensiRowCount+' data';
    document.querySelectorAll('#absensiTableBody tr[data-semester]').forEach(function(r){ r.style.display=parseInt(r.dataset.semester)===sem?'':'none'; });
    fetch(NILAI_API_URL+'?semester='+sem,{headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}})
    .then(function(r){return r.json();})
    .then(function(data){
        var nL=[],nD=[],nC=[],nG=[],nV=[];
        data.forEach(function(item){
            var nm=item.nama_mk.length>14?item.nama_mk.substring(0,12)+'..':item.nama_mk;
            nL.push(nm); nG.push(item.grade); nV.push(item.nilai_akhir);
            nD.push(G2Y[item.grade]||0); nC.push(G2C[item.grade]||'#3b82f6');
        });
        LABELS=nL; GRADES=nG; VALUES=nV; barData=nD; barColors=nC;
        barChart.data.labels=nL; barChart.data.datasets[0].data=nD; barChart.data.datasets[0].backgroundColor=nC; barChart.update();
        var tbody=document.getElementById('nilaiTableBody');
        if(!data.length){
            tbody.innerHTML='<tr><td colspan="4" style="text-align:center;padding:32px;color:#94a3b8;"><i class="bi bi-inbox" style="font-size:28px;display:block;margin-bottom:8px;opacity:.5;"></i>Belum ada data nilai.</td></tr>';
        } else {
            tbody.innerHTML=data.slice(0,7).map(function(item){
                var isDE=item.grade==='D'||item.grade==='E';
                var gc=item.grade.replace('+','p');
                var sw=Math.min(item.nilai_akhir,100);
                var col=G2C[item.grade]||'#3b82f6';
                return '<tr data-matkul="'+item.nama_mk.toLowerCase()+'" data-status="'+(isDE?'perhatian':'baik')+'">' +
                    '<td><div style="font-weight:600;color:#0f172a;">'+item.nama_mk+'</div><div style="font-size:11px;color:#94a3b8;margin-top:1px;">'+item.kode_mk+'</div></td>' +
                    '<td class="col-sks-hide" style="text-align:center;color:#64748b;font-weight:600;">'+item.sks+'</td>' +
                    '<td style="text-align:center;"><div style="display:flex;align-items:center;gap:6px;justify-content:center;"><span style="font-weight:800;font-size:13.5px;color:'+col+';">'+item.nilai_akhir.toFixed(1)+'</span><div class="score-bar"><div class="score-bar-fill" style="width:'+sw+'%;background:'+col+';"></div></div></div></td>' +
                    '<td style="text-align:center;"><span class="grade-pill grade-'+gc+'">'+item.grade+'</span></td></tr>';
            }).join('');
        }
        document.getElementById('nilaiTblSub').textContent='Semester '+sem+' • '+data.length+' mata kuliah';
    })
    .catch(function(){ document.getElementById('nilaiTblSub').textContent='Semester '+sem+' • Gagal memuat'; });
}

// ── Filter Tabel Nilai ──────────────────────────────
document.getElementById('filterNilaiTblBtn').addEventListener('filterChange', function(e) {
    var val=e.detail.value;
    document.querySelectorAll('#nilaiTableBody tr').forEach(function(r){
        if(!val){r.style.display='';return;}
        r.style.display=r.dataset.status===val?'':'none';
    });
});

// ── Search Nilai ────────────────────────────────────
document.getElementById('searchNilai').addEventListener('input', function() {
    var q=this.value.toLowerCase();
    document.querySelectorAll('#nilaiTableBody tr').forEach(function(r){
        r.style.display=(r.dataset.matkul||'').includes(q)?'':'none';
    });
});

// ── Alert Close ─────────────────────────────────────
(function(){
    var el=document.getElementById('mhsAlert');
    var btn=document.getElementById('mhsAlertClose');
    if(!el||!btn)return;
    btn.addEventListener('click',function(){
        el.style.animation='alertOut .32s cubic-bezier(.4,0,1,1) forwards';
        setTimeout(function(){ el.style.display='none'; },310);
    });
})();
</script>
@endpush
