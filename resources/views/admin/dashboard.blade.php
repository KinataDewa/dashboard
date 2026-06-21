@extends('layouts.admin')
@section('title','Admin Dashboard')

@push('styles')
<style>
/* ══════════════════════════════════════
   ADMIN DASHBOARD — CLEAN MINIMAL v3
   ══════════════════════════════════════ */

/* Subtle page-level gradient overlay */
.page-wrap {
    background:
        radial-gradient(ellipse at 0% 0%,   rgba(37,99,235,.035) 0%, transparent 55%),
        radial-gradient(ellipse at 100% 90%, rgba(124,58,237,.025) 0%, transparent 50%);
}

/* ── Section label ────────────────────── */
.db-sec {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 10.5px;
    font-weight: 800;
    color: #94A3B8;
    text-transform: uppercase;
    letter-spacing: 1.3px;
    margin-bottom: 14px;
    margin-top: 6px;
}
.db-sec::before {
    content: '';
    width: 3px;
    height: 15px;
    border-radius: 2px;
    background: linear-gradient(180deg, #2563EB, #7C3AED);
    flex-shrink: 0;
}
.db-sec::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, #E2E8F0, transparent);
}

/* ── Stat cards ───────────────────────── */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 14px;
    margin-bottom: 28px;
}
.db-stat {
    background: #fff;
    border: 1px solid #EDF0F7;
    border-radius: 13px;
    box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 1px 2px rgba(0,0,0,.03);
    overflow: hidden;
    transition: transform .2s cubic-bezier(.16,1,.3,1), box-shadow .2s;
    cursor: default;
}
.db-stat:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,.08), 0 2px 6px rgba(0,0,0,.04);
}
.db-stat-bar { height: 3px; }
.db-stat-body {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 16px 8px;
    position: relative;
}
.db-stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    flex-shrink: 0;
}
.db-stat-info { flex: 1; min-width: 0; }
.db-stat-label {
    font-size: 10.5px;
    font-weight: 700;
    color: #94A3B8;
    text-transform: uppercase;
    letter-spacing: .5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 3px;
}
.db-stat-num {
    font-size: 26px;
    font-weight: 900;
    line-height: 1;
    letter-spacing: -1.2px;
}
.db-stat-foot { padding: 0 16px 13px; }
.db-stat-badge {
    display: inline-flex;
    align-items: center;
    padding: 2px 9px;
    border-radius: 20px;
    font-size: 10.5px;
    font-weight: 700;
}

/* ── Chart / info cards ───────────────── */
.db-card {
    background: #fff;
    border: 1px solid #EDF0F7;
    border-radius: 13px;
    box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 1px 2px rgba(0,0,0,.03);
    padding: 22px 24px;
    height: 100%;
}
.db-card-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}
.db-card-title { font-size: 14px; font-weight: 700; color: #0F172A; }
.db-card-sub   { font-size: 12px; color: #64748B; margin-top: 2px; }

.trend-badge  { display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700; }
.trend-naik   { background:#DCFCE7;color:#15803D; }
.trend-turun  { background:#FEE2E2;color:#991B1B; }
.trend-stabil { background:#F1F5F9;color:#475569; }

.db-select {
    padding: 6px 26px 6px 10px;
    border: 1.5px solid #E2E8F0;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #0F172A;
    background: #fff;
    cursor: pointer;
    outline: none;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' fill='none'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%2394A3B8' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    transition: border-color .15s;
}
.db-select:focus { border-color: #2563EB; }

.db-legend-row  { display:flex;align-items:center;gap:14px;flex-wrap:wrap;font-size:12px;color:#64748B; }
.db-legend-item { display:flex;align-items:center;gap:5px; }

.db-link {
    font-size: 12px;
    font-weight: 700;
    color: #2563EB;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
    transition: color .15s;
}
.db-link:hover { color: #1D4ED8; }

/* ── ARIMA panel boxes (used by JS) ───── */
.ap-box         { border-radius: 10px; padding: 14px 16px; margin-bottom: 10px; }
.ap-box:last-child { margin-bottom: 0; }
.ap-box-blue    { background: #F0F6FF; border: 1.5px solid #BFDBFE; }
.ap-box-amber   { background: #FFFBEB; border: 1.5px solid #FCD34D; }
.ap-box-green   { background: #F0FDF4; border: 1px solid #BBF7D0; }
.ap-lbl         { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .7px; margin-bottom: 4px; }
.ap-lbl-blue    { color: #1D4ED8; }
.ap-lbl-amber   { color: #D97706; }
.ap-lbl-green   { color: #16A34A; }
.ap-num         { font-size: 30px; font-weight: 900; line-height: 1; letter-spacing: -1.5px; }
.ap-num-blue    { color: #2563EB; }
.ap-num-amber   { color: #D97706; }
.ap-sub         { font-size: 11.5px; color: #64748B; margin-top: 5px; }
.ap-eval-row    { display:flex; gap:12px; }
.ap-eval-item   { flex:1; text-align:center; }
.ap-eval-num    { font-size: 20px; font-weight: 800; color: #16A34A; letter-spacing: -.8px; }
.ap-eval-lbl    { font-size: 9.5px; color: #6B7280; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; margin-top: 2px; }

/* ── Donut ────────────────────────────── */
.db-donut-wrap  { display:flex;align-items:center;gap:18px;margin-top:12px;flex-wrap:wrap; }
.db-donut-box   { position:relative;width:156px;height:156px;flex-shrink:0; }
.db-donut-center{
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%,-50%);
    text-align: center;
    pointer-events: none;
}
.db-donut-num { font-size: 24px; font-weight: 900; color: #0F172A; line-height: 1; letter-spacing: -1px; }
.db-donut-lbl { font-size: 9.5px; color: #94A3B8; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; margin-top: 2px; }

.db-leg-list    { display:flex;flex-direction:column;gap:7px;flex:1;min-width:110px; }
.db-leg-row     { display:flex;align-items:center;justify-content:space-between;gap:6px; }
.db-leg-left    { display:flex;align-items:center;gap:7px;min-width:0; }
.db-leg-dot     { width:8px;height:8px;border-radius:50%;flex-shrink:0; }
.db-leg-name    { font-size:12px;color:#64748B;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.db-leg-val     { font-size:12.5px;font-weight:800;color:#0F172A;flex-shrink:0; }

.db-insight {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 12px;
    padding-top: 11px;
    border-top: 1px solid #F1F5F9;
    font-size: 12px;
    color: #64748B;
    flex-wrap: wrap;
}
.db-insight strong { color: #0F172A; font-weight: 700; }
.db-insight i { color: #CBD5E1; flex-shrink: 0; }

/* ── Table ────────────────────────────── */
.db-tbl-wrap  {
    background: #fff;
    border: 1px solid #EDF0F7;
    border-radius: 13px;
    box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 1px 2px rgba(0,0,0,.03);
    overflow: hidden;
    margin-bottom: 24px;
}
.db-tbl-head  {
    padding: 18px 22px 14px;
    border-bottom: 1px solid #F4F7FB;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.db-tbl-title { font-size: 14px; font-weight: 700; color: #0F172A; }
.db-tbl-sub   { font-size: 12px; color: #64748B; margin-top: 2px; }

.db-tbl { width: 100%; border-collapse: collapse; }
.db-tbl thead tr { background: #FAFBFE; }
.db-tbl thead th {
    font-size: 10px;
    font-weight: 800;
    color: #94A3B8;
    text-transform: uppercase;
    letter-spacing: .8px;
    padding: 10px 16px;
    border-bottom: 1.5px solid #EEF2F8;
    text-align: left;
    white-space: nowrap;
}
.db-tbl tbody tr { border-bottom: 1px solid #F8FAFC; transition: background .12s; }
.db-tbl tbody tr:last-child { border-bottom: none; }
.db-tbl tbody tr:hover { background: #FAFBFF; }
.db-tbl tbody td { padding: 12px 16px; font-size: 13px; vertical-align: middle; }
.db-tbl .risk-row { background: rgba(239,68,68,.022) !important; }
.db-tbl .risk-row td:first-child { border-left: 3px solid #EF4444; }

.db-pct-wrap { display:flex;align-items:center;justify-content:center;gap:7px; }
.db-pct-bar  { width:52px;height:5px;background:#F1F5F9;border-radius:3px;overflow:hidden;flex-shrink:0; }
.db-pct-fill { height:100%;border-radius:3px;transition:width .5s; }
.db-ipk-bar  { width:48px;height:4px;background:#F1F5F9;border-radius:2px;overflow:hidden;display:inline-block;vertical-align:middle;margin-left:5px; }
.db-ipk-fill { height:100%;border-radius:2px; }

.db-pill     { display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:10.5px;font-weight:700;white-space:nowrap; }
.db-pill-red { background:#FEE2E2;color:#991B1B; }
.db-pill-grn { background:#DCFCE7;color:#166534; }

.db-tbl-foot { padding:12px 22px;border-top:1px solid #F4F7FB;display:flex;gap:8px;flex-wrap:wrap;align-items:center; }
.db-chip     { display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:20px;font-size:11.5px;font-weight:600; }

/* ── Animations ───────────────────────── */
@keyframes db-fade-up {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}
.db-ani   { animation: db-fade-up .45s cubic-bezier(.16,1,.3,1) both; }
.db-ani-1 { animation-delay: .04s; }
.db-ani-2 { animation-delay: .11s; }
.db-ani-3 { animation-delay: .18s; }
.db-ani-4 { animation-delay: .25s; }
.db-ani-5 { animation-delay: .32s; }

/* ── Responsive ───────────────────────── */
@media (max-width: 1199px) {
    .stat-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 767px) {
    .stat-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .db-card   { padding: 16px 18px; }
    .db-donut-wrap { flex-direction: column; align-items: center; gap: 14px; }
    .db-leg-list { flex-direction: row; flex-wrap: wrap; justify-content: center; gap: 6px 14px; }
    .db-leg-row  { flex: 0 0 auto; }
    .db-tbl-head { padding: 14px 16px 12px; }
    .db-tbl-foot { padding: 10px 16px; }
    .db-tbl tbody td { padding: 10px 12px; }
    .db-tbl thead th { padding: 9px 12px; }
}
@media (max-width: 575px) {
    .stat-grid { gap: 8px; }
    .db-stat-num { font-size: 22px; }
    .hide-xs { display: none !important; }
}
@media (prefers-reduced-motion: reduce) {
    .db-ani, .db-stat { animation: none !important; }
    .db-stat:hover { transform: none; }
}
</style>
@endpush

@section('content')

{{-- ══ BANNER (unchanged) ══ --}}
@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg, #0C1445 0%, #1E3A8A 40%, #2563EB 100%)',
    'icon'         => 'bi-speedometer2',
    'title'        => 'Overview Sistem — Jurusan TI',
    'sub'          => 'Politeknik Negeri Malang · Tahun Akademik 2024/2025',
    'chips'        => [
        ['icon' => 'bi-mortarboard-fill',         'label' => $totalMahasiswa . ' Mahasiswa'],
        ['icon' => 'bi-person-badge-fill',        'label' => $totalDosen . ' Dosen'],
        ['icon' => 'bi-book-fill',                'label' => $totalMatkul . ' Mata Kuliah'],
        ['icon' => 'bi-exclamation-triangle-fill','label' => $mahasiswaBerisiko . ' Berisiko'],
    ],
    'badge_num'    => $totalMahasiswa,
    'badge_label'  => "Total\nMahasiswa",
    'badge2_num'   => $mahasiswaBerisiko,
    'badge2_label' => "Perlu\nPerhatian",
])

{{-- ══ ALERT (unchanged) ══ --}}
@if($mahasiswaBerisiko > 0)
<div class="risk-alert-wrap" id="riskAlertAdmin">
    <div class="risk-pulse-ring"></div>
    <div class="risk-alert-left">
        <div class="risk-alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
        <div class="risk-alert-content">
            <div class="risk-alert-tag">Tindakan Diperlukan</div>
            <div class="risk-alert-title">{{ $mahasiswaBerisiko }} Mahasiswa Terdeteksi Berisiko Akademik</div>
            <div class="risk-alert-desc">
                Terdapat mahasiswa dengan <strong>nilai D/E</strong>, <strong>alpha ≥18 jam (SP I–PS)</strong>, atau <strong>IPS &lt;2.00</strong> di semester terakhir.
                Segera arahkan DPA untuk bimbingan agar tidak berdampak pada kelulusan.
            </div>
        </div>
    </div>
    <div class="risk-alert-right">
        <a href="{{ route('admin.mahasiswa.index') }}" class="risk-alert-btn">
            <i class="bi bi-arrow-right-circle-fill"></i> Lihat &amp; Tangani Sekarang
        </a>
        <button class="risk-alert-close" id="riskCloseAdmin" title="Tutup"><i class="bi bi-x-lg"></i></button>
    </div>
</div>
@endif

{{-- ══ STAT CARDS ══ --}}
@php
$stats = [
    ['label'=>'Mahasiswa Aktif',    'val'=>$totalMahasiswa,    'icon'=>'bi-mortarboard-fill',          'bar'=>'linear-gradient(90deg,#2563EB,#60A5FA)', 'ibg'=>'#EFF6FF', 'ic'=>'#2563EB', 'nclr'=>'#1E40AF', 'badge'=>'Terdaftar',      'bbg'=>'#DBEAFE','bc'=>'#1D4ED8'],
    ['label'=>'Total Dosen',        'val'=>$totalDosen,        'icon'=>'bi-person-badge-fill',         'bar'=>'linear-gradient(90deg,#16A34A,#86EFAC)', 'ibg'=>'#F0FDF4', 'ic'=>'#16A34A', 'nclr'=>'#166534', 'badge'=>'Aktif mengajar', 'bbg'=>'#DCFCE7','bc'=>'#166534'],
    ['label'=>'Mata Kuliah',        'val'=>$totalMatkul,       'icon'=>'bi-book-fill',                 'bar'=>'linear-gradient(90deg,#7C3AED,#A78BFA)', 'ibg'=>'#F5F3FF', 'ic'=>'#7C3AED', 'nclr'=>'#5B21B6', 'badge'=>'Semester ini',   'bbg'=>'#EDE9FE','bc'=>'#5B21B6'],
    ['label'=>'Kelas Aktif',        'val'=>$totalKelas,        'icon'=>'bi-grid-3x3-gap-fill',         'bar'=>'linear-gradient(90deg,#0891B2,#67E8F9)', 'ibg'=>'#ECFEFF', 'ic'=>'#0891B2', 'nclr'=>'#0E7490', 'badge'=>'Semua angkatan', 'bbg'=>'#CFFAFE','bc'=>'#0E7490'],
    ['label'=>'Mahasiswa Berisiko', 'val'=>$mahasiswaBerisiko, 'icon'=>'bi-exclamation-triangle-fill', 'bar'=>$mahasiswaBerisiko>0?'linear-gradient(90deg,#EF4444,#FCA5A5)':'linear-gradient(90deg,#22C55E,#86EFAC)', 'ibg'=>$mahasiswaBerisiko>0?'#FEF2F2':'#F0FDF4', 'ic'=>$mahasiswaBerisiko>0?'#EF4444':'#22C55E', 'nclr'=>$mahasiswaBerisiko>0?'#DC2626':'#16A34A', 'badge'=>$mahasiswaBerisiko>0?'Perlu penanganan':'Semua aman', 'bbg'=>$mahasiswaBerisiko>0?'#FEE2E2':'#DCFCE7', 'bc'=>$mahasiswaBerisiko>0?'#991B1B':'#166534'],
    ['label'=>'Semester Aktif',     'val'=>$semesterAktif,     'icon'=>'bi-calendar2-check-fill',      'bar'=>'linear-gradient(90deg,#DB2777,#F472B6)', 'ibg'=>'#FDF2F8', 'ic'=>'#DB2777', 'nclr'=>'#831843', 'badge'=>'Tahun ajaran',   'bbg'=>'#FCE7F3','bc'=>'#9D174D'],
];
@endphp

<div class="db-sec db-ani db-ani-1">Statistik Sistem</div>
<div class="stat-grid db-ani db-ani-1">
    @foreach($stats as $s)
    <div class="db-stat">
        <div class="db-stat-bar" style="background:{{ $s['bar'] }};"></div>
        <div class="db-stat-body">
            <div class="db-stat-icon" style="background:{{ $s['ibg'] }};color:{{ $s['ic'] }};">
                <i class="bi {{ $s['icon'] }}"></i>
            </div>
            <div class="db-stat-info">
                <div class="db-stat-label">{{ $s['label'] }}</div>
                <div class="db-stat-num" style="color:{{ $s['nclr'] }};">{{ $s['val'] }}</div>
            </div>
        </div>
        <div class="db-stat-foot">
            <span class="db-stat-badge" style="background:{{ $s['bbg'] }};color:{{ $s['bc'] }};">{{ $s['badge'] }}</span>
        </div>
    </div>
    @endforeach
</div>

{{-- ══ TREN IPK ARIMA ══ --}}
<div class="db-sec db-ani db-ani-2">Tren IPK per Angkatan</div>
<div class="row g-3 mb-4 db-ani db-ani-2">
    <div class="col-lg-7">
        <div class="db-card">
            <div class="db-card-head">
                <div>
                    <div class="db-card-title">Tren IPK &amp; Prediksi ARIMA</div>
                    <div class="db-card-sub">ARIMA(0,1,1) · Historis &amp; prediksi semester berikutnya</div>
                </div>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <span id="trenBadge" class="trend-badge trend-stabil">→ Memuat...</span>
                    @if($angkatanList->isNotEmpty())
                    <select id="angkatanSelect" class="db-select">
                        @foreach($angkatanList as $a)
                        <option value="{{ $a }}">Angkatan {{ $a }}</option>
                        @endforeach
                    </select>
                    @endif
                </div>
            </div>
            <div style="position:relative;height:220px;">
                <canvas id="arimaChart"></canvas>
            </div>
            <div style="margin-top:12px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                <div class="db-legend-row">
                    <div class="db-legend-item">
                        <div style="width:18px;height:2.5px;background:#2563EB;border-radius:1px;"></div>
                        <span>Historis</span>
                    </div>
                    <div class="db-legend-item">
                        <div style="width:18px;border-top:2.5px dashed #F59E0B;"></div>
                        <span>Prediksi</span>
                    </div>
                </div>
                <a href="{{ route('admin.analitik.index') }}" class="db-link">
                    Detail Analitik <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="db-card">
            <div class="db-card-title" style="margin-bottom:4px;">Ringkasan ARIMA</div>
            <div class="db-card-sub" id="arimaPanelSub">Memuat data...</div>
            <div id="arimaPanelContent" style="margin-top:14px;"></div>
        </div>
    </div>
</div>

{{-- ══ DISTRIBUSI AKADEMIK ══ --}}
<div class="db-sec db-ani db-ani-3">Distribusi Akademik</div>
<div class="row g-3 mb-4 db-ani db-ani-3 align-items-start">

    {{-- Donut Risiko --}}
    <div class="col-lg-5">
        <div class="db-card">
            <div class="db-card-head">
                <div>
                    <div class="db-card-title">Distribusi Mahasiswa Berisiko</div>
                    <div class="db-card-sub">Per kategori risiko · Semester {{ $semesterAktif }}</div>
                </div>
                <a href="{{ route('admin.berisiko.index') }}" class="db-link">
                    Lihat Detail <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            @php
                $riskKeys   = ['sp1','sp2','sp3','ps','nilai_e','nilai_d','ips_rendah'];
                $riskLabels = ['SP I','SP II','SP III','Putus Studi','Nilai E','D>3 MK','IPS<2.00'];
                $riskColors = ['#FCD34D','#FB923C','#EF4444','#991B1B','#7F1D1D','#6D28D9','#1D4ED8'];
                $riskMaxIdx = null; $riskMaxVal = -1;
                foreach ($riskKeys as $idx => $kat) {
                    if ($distribusiRisiko[$kat] > $riskMaxVal) { $riskMaxVal = $distribusiRisiko[$kat]; $riskMaxIdx = $idx; }
                }
            @endphp
            @if(array_sum($distribusiRisiko) > 0)
            <div class="db-donut-wrap">
                <div class="db-donut-box">
                    <canvas id="risikoChart" width="156" height="156"></canvas>
                    <div class="db-donut-center">
                        <div class="db-donut-num">{{ $mahasiswaBerisiko }}</div>
                        <div class="db-donut-lbl">Berisiko</div>
                    </div>
                </div>
                <div class="db-leg-list">
                    @foreach($riskKeys as $idx => $kat)
                    @if($distribusiRisiko[$kat] > 0)
                    <div class="db-leg-row">
                        <div class="db-leg-left">
                            <div class="db-leg-dot" style="background:{{ $riskColors[$idx] }};"></div>
                            <span class="db-leg-name">{{ $riskLabels[$idx] }}</span>
                        </div>
                        <span class="db-leg-val">{{ $distribusiRisiko[$kat] }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @if($riskMaxIdx !== null && $riskMaxVal > 0)
            <div class="db-insight">
                <i class="bi bi-info-circle-fill"></i>
                Kategori terbanyak: <strong>{{ $riskLabels[$riskMaxIdx] }}</strong> ({{ $riskMaxVal }} mahasiswa)
            </div>
            @endif
            @else
            <div style="text-align:center;padding:40px 16px;color:#94A3B8;">
                <i class="bi bi-shield-check-fill" style="font-size:40px;color:#22C55E;display:block;margin-bottom:10px;"></i>
                <div style="font-weight:700;font-size:13.5px;color:#166534;">Tidak ada mahasiswa berisiko</div>
                <div style="font-size:12px;margin-top:3px;">Semua mahasiswa aman di semester ini</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Bar Grade --}}
    <div class="col-lg-7">
        <div class="db-card">
            <div class="db-card-head">
                <div>
                    <div class="db-card-title">Distribusi Grade Nilai</div>
                    <div class="db-card-sub">Jumlah nilai per grade · Semester {{ $semesterAktif }}</div>
                </div>
                <select id="gradeAngkatanSel" class="db-select">
                    <option value="">Semua Angkatan</option>
                    @foreach($angkatanList as $a)
                    <option value="{{ $a }}">Angkatan {{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div style="position:relative;height:228px;margin-top:4px;">
                <canvas id="gradeChart"></canvas>
            </div>
            @php
                $totalGradeAll = array_sum($distribusiGrade);
                $maxGradeKey   = array_search(max($distribusiGrade), $distribusiGrade);
                $maxGradeVal   = $maxGradeKey !== false ? $distribusiGrade[$maxGradeKey] : 0;
                $maxGradePct   = $totalGradeAll > 0 ? round($maxGradeVal / $totalGradeAll * 100) : 0;
            @endphp
            @if($totalGradeAll > 0 && $maxGradeKey !== false)
            <div class="db-insight" id="gradeInsight">
                <i class="bi bi-bar-chart-fill"></i>
                Grade <strong>{{ $maxGradeKey }}</strong> mendominasi — {{ $maxGradeVal }} dari {{ $totalGradeAll }} nilai (<strong>{{ $maxGradePct }}%</strong>)
            </div>
            @else
            <div class="db-insight" id="gradeInsight" style="display:none;"></div>
            @endif
        </div>
    </div>
</div>

{{-- ══ TABEL KELAS ══ --}}
<div class="db-sec db-ani db-ani-4">Ringkasan Akademik per Kelas</div>
<div class="db-tbl-wrap db-ani db-ani-4">
    <div class="db-tbl-head">
        <div>
            <div class="db-tbl-title">Performa per Kelas</div>
            <div class="db-tbl-sub">Semester {{ $semesterAktif }} · {{ count($ringkasanKelas) }} kelas aktif</div>
        </div>
    </div>

    @if(empty($ringkasanKelas))
    <div style="text-align:center;padding:48px;color:#94A3B8;">
        <i class="bi bi-grid-3x3-gap" style="font-size:32px;display:block;margin-bottom:10px;opacity:.3;"></i>
        <div style="font-size:13.5px;font-weight:600;">Belum ada data kelas untuk semester ini.</div>
    </div>
    @else
    <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
        <table class="db-tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kelas</th>
                    <th style="text-align:center;">Total</th>
                    <th style="text-align:center;">Berisiko</th>
                    <th style="text-align:center;">% Risiko</th>
                    <th style="text-align:center;" class="hide-xs">Rata-rata IPK</th>
                    <th style="text-align:center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ringkasanKelas as $i => $kls)
                @php $perlu = $kls['pct_risiko'] > 30; @endphp
                <tr class="{{ $perlu ? 'risk-row' : '' }}">
                    <td style="font-size:11.5px;color:#CBD5E1;font-weight:600;">{{ $i + 1 }}</td>
                    <td style="font-weight:700;color:#0F172A;">{{ $kls['kelas'] }}</td>
                    <td style="text-align:center;color:#64748B;font-weight:600;">{{ $kls['total'] }}</td>
                    <td style="text-align:center;">
                        <span style="font-weight:800;font-size:14px;color:{{ $kls['berisiko'] > 0 ? '#EF4444' : '#22C55E' }};">
                            {{ $kls['berisiko'] }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <div class="db-pct-wrap">
                            <span style="font-size:12px;font-weight:700;color:{{ $perlu ? '#EF4444' : '#64748B' }};min-width:32px;text-align:right;">
                                {{ $kls['pct_risiko'] }}%
                            </span>
                            <div class="db-pct-bar">
                                <div class="db-pct-fill" style="width:{{ min($kls['pct_risiko'],100) }}%;background:{{ $perlu ? '#EF4444' : '#22C55E' }};"></div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center;" class="hide-xs">
                        <div style="display:flex;align-items:center;justify-content:center;gap:5px;">
                            <span style="font-weight:800;font-size:13px;color:{{ $kls['ipk'] < 2.5 ? '#EF4444' : ($kls['ipk'] >= 3.5 ? '#22C55E' : '#0F172A') }};">
                                {{ number_format($kls['ipk'], 2) }}
                            </span>
                            <div class="db-ipk-bar">
                                <div class="db-ipk-fill" style="width:{{ min(($kls['ipk']/4)*100,100) }}%;background:{{ $kls['ipk'] < 2.5 ? '#EF4444' : '#2563EB' }};"></div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        @if($perlu)
                        <span class="db-pill db-pill-red">
                            <i class="bi bi-exclamation-triangle-fill" style="font-size:9px;"></i> Perlu Perhatian
                        </span>
                        @else
                        <span class="db-pill db-pill-grn">
                            <i class="bi bi-check-circle-fill" style="font-size:9px;"></i> Baik
                        </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @php
        $kelasMaxRisk = collect($ringkasanKelas)->sortByDesc('pct_risiko')->first();
        $kelasAman    = collect($ringkasanKelas)->where('pct_risiko', 0)->pluck('kelas');
    @endphp
    <div class="db-tbl-foot">
        @if($kelasMaxRisk && $kelasMaxRisk['pct_risiko'] > 0)
        <span class="db-chip" style="background:#FEE2E2;color:#991B1B;">
            <i class="bi bi-exclamation-triangle-fill" style="font-size:10px;"></i>
            Risiko tertinggi: {{ $kelasMaxRisk['kelas'] }} ({{ $kelasMaxRisk['pct_risiko'] }}%)
        </span>
        @endif
        @if($kelasAman->isNotEmpty())
        <span class="db-chip" style="background:#DCFCE7;color:#166534;">
            <i class="bi bi-check-circle-fill" style="font-size:10px;"></i>
            Paling stabil: {{ $kelasAman->implode(', ') }}
        </span>
        @endif
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script>
Chart.register(ChartDataLabels);

// ── Alert close ──────────────────────────────────────────────────
(function() {
    var el   = document.getElementById('riskAlertAdmin');
    var btnX = document.getElementById('riskCloseAdmin');
    if (!el || !btnX) return;
    btnX.addEventListener('click', function() {
        el.style.animation = 'alertSlideOut .35s cubic-bezier(.4,0,1,1) forwards';
        setTimeout(function() { el.style.display = 'none'; }, 340);
    });
})();

// ── ARIMA Chart ──────────────────────────────────────────────────
var arimaInst = null;

function loadArima(angkatan) {
    if (!angkatan) return;
    fetch('{{ route("admin.analitik.chart-data") }}?angkatan=' + encodeURIComponent(angkatan))
        .then(function(r) { return r.json(); })
        .then(function(d) { renderArima(d); renderArimaPanel(d); })
        .catch(function() {});
}

function renderArima(d) {
    var ctx = document.getElementById('arimaChart');
    if (!ctx) return;
    if (arimaInst) { arimaInst.destroy(); arimaInst = null; }

    var hist   = d.historis || [];
    var labels = d.labels   || [];
    var pred   = d.prediksi != null ? d.prediksi : null;

    var histData = hist.slice();
    if (pred !== null) histData.push(null);

    var datasets = [{
        label: 'Historis',
        data: histData,
        borderColor: '#2563EB',
        backgroundColor: 'rgba(37,99,235,.07)',
        fill: true, tension: 0.3, borderWidth: 2.5,
        pointRadius: histData.map(function(v, i) { return i < hist.length ? 4 : 0; }),
        pointBackgroundColor: '#2563EB',
        pointHoverRadius: 6,
    }];

    if (pred !== null && hist.length > 0) {
        var conn = new Array(hist.length - 1).fill(null);
        conn.push(hist[hist.length - 1]);
        conn.push(pred);
        datasets.push({
            label: 'Prediksi',
            data: conn,
            borderColor: '#F59E0B',
            borderDash: [6, 4],
            borderWidth: 2,
            pointRadius: conn.map(function(v, i) { return i === conn.length - 1 ? 6 : 0; }),
            pointBackgroundColor: '#F59E0B',
            fill: false, tension: 0,
        });
    }

    arimaInst = new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: { labels: labels, datasets: datasets },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A', padding: 10, cornerRadius: 8,
                    callbacks: { label: function(c) { return c.raw !== null ? ' IPK: ' + Number(c.raw).toFixed(2) : null; } }
                },
                datalabels: { display: false },
            },
            scales: {
                y: { min: 0, max: 4, ticks: { stepSize: 0.5, font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#94A3B8' }, grid: { color: '#F8FAFC' }, border: { display: false } },
                x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#94A3B8', maxRotation: 0 } }
            }
        }
    });

    var badge = document.getElementById('trenBadge');
    if (badge && hist.length >= 2) {
        var diffs = [], i;
        for (i = 1; i < hist.length; i++) diffs.push(hist[i] - hist[i - 1]);
        var mean = diffs.reduce(function(a, b) { return a + b; }, 0) / diffs.length;
        if (Math.abs(mean) < 0.02) { badge.className = 'trend-badge trend-stabil'; badge.textContent = '→ Stabil'; }
        else if (mean > 0)          { badge.className = 'trend-badge trend-naik';   badge.textContent = '↑ Naik'; }
        else                        { badge.className = 'trend-badge trend-turun';  badge.textContent = '↓ Turun'; }
    }
}

function renderArimaPanel(d) {
    var sub  = document.getElementById('arimaPanelSub');
    var cont = document.getElementById('arimaPanelContent');
    if (!sub || !cont) return;
    var hist = d.historis || [];
    if (!hist.length) { sub.textContent = 'Belum ada data untuk angkatan ini'; cont.innerHTML = ''; return; }
    var last    = hist[hist.length - 1];
    var pred    = d.prediksi != null ? d.prediksi : null;
    var selisih = pred !== null ? (pred - last).toFixed(2) : null;
    var isNaik  = selisih !== null && parseFloat(selisih) > 0;
    sub.textContent = hist.length + ' semester data historis';

    var evalHtml = '';
    if (d.evaluasi) {
        evalHtml = '<div class="ap-box ap-box-green">'
            + '<div class="ap-lbl ap-lbl-green">Evaluasi Model</div>'
            + '<div class="ap-eval-row">'
            + '<div class="ap-eval-item"><div class="ap-eval-num">' + Number(d.evaluasi.mae).toFixed(2) + '</div><div class="ap-eval-lbl">MAE</div></div>'
            + '<div class="ap-eval-item"><div class="ap-eval-num">' + Number(d.evaluasi.mape).toFixed(1) + '%</div><div class="ap-eval-lbl">MAPE</div></div>'
            + '</div></div>';
    }

    var predHtml = '';
    if (pred !== null) {
        predHtml = '<div class="ap-box ap-box-amber">'
            + '<div class="ap-lbl ap-lbl-amber">Prediksi Semester Berikutnya</div>'
            + '<div class="ap-num ap-num-amber">' + Number(pred).toFixed(2) + '</div>'
            + '<div class="ap-sub">' + (isNaik ? '↑ +' : '↓ ') + selisih + ' dari saat ini</div>'
            + '</div>';
    }

    cont.innerHTML = '<div class="ap-box ap-box-blue">'
        + '<div class="ap-lbl ap-lbl-blue">IPK Rata-rata Saat Ini</div>'
        + '<div class="ap-num ap-num-blue">' + Number(last).toFixed(2) + '</div>'
        + '</div>' + predHtml + evalHtml;
}

var angkSel = document.getElementById('angkatanSelect');
if (angkSel) {
    angkSel.addEventListener('change', function() { loadArima(this.value); });
    loadArima(angkSel.value);
}

// ── Risiko Donut ─────────────────────────────────────────────────
@if(array_sum($distribusiRisiko) > 0)
new Chart(document.getElementById('risikoChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['SP I','SP II','SP III','Putus Studi','Nilai E','D>3 MK','IPS<2.00'],
        datasets: [{
            data: [{{ $distribusiRisiko['sp1'] }},{{ $distribusiRisiko['sp2'] }},{{ $distribusiRisiko['sp3'] }},{{ $distribusiRisiko['ps'] }},{{ $distribusiRisiko['nilai_e'] }},{{ $distribusiRisiko['nilai_d'] }},{{ $distribusiRisiko['ips_rendah'] }}],
            backgroundColor: ['#FCD34D','#FB923C','#EF4444','#991B1B','#7F1D1D','#6D28D9','#1D4ED8'],
            borderWidth: 3, borderColor: '#fff', hoverOffset: 5,
        }]
    },
    options: {
        responsive: false, maintainAspectRatio: false, cutout: '68%',
        plugins: {
            legend: { display: false },
            tooltip: { backgroundColor: '#0F172A', padding: 10, cornerRadius: 8, callbacks: { label: function(c) { return ' ' + c.label + ': ' + c.raw + ' mahasiswa'; } } },
            datalabels: { display: false },
        }
    }
});
@endif

// ── Grade Bar Chart ──────────────────────────────────────────────
var gradeInst = null;
var initGrade = @json(array_values($distribusiGrade));

function renderGradeChart(vals) {
    var ctx = document.getElementById('gradeChart');
    if (!ctx) return;
    if (gradeInst) { gradeInst.destroy(); gradeInst = null; }
    gradeInst = new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['A','B+','B','C+','C','D','E'],
            datasets: [{
                data: vals,
                backgroundColor: ['#22C55E','#3B82F6','#60A5FA','#FBBF24','#A78BFA','#F97316','#EF4444'],
                borderRadius: 7, maxBarThickness: 44,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#0F172A', padding: 10, cornerRadius: 8 },
                datalabels: { anchor: 'end', align: 'top', offset: 2, formatter: function(v) { return v > 0 ? v : ''; }, font: { family: 'Plus Jakarta Sans', weight: '700', size: 11 }, color: '#1E293B' }
            },
            layout: { padding: { top: 22 } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 12 }, color: '#94A3B8' } },
                y: { beginAtZero: true, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#94A3B8' }, grid: { color: '#F8FAFC' }, border: { display: false } }
            }
        }
    });
}

function updateGradeInsight(d) {
    var el = document.getElementById('gradeInsight');
    if (!el) return;
    var entries = Object.entries(d);
    if (!entries.length) { el.innerHTML = ''; return; }
    var total = entries.reduce(function(s, e) { return s + e[1]; }, 0);
    var maxEntry = entries.reduce(function(a, b) { return b[1] > a[1] ? b : a; }, entries[0]);
    var pct = total > 0 ? Math.round(maxEntry[1] / total * 100) : 0;
    el.innerHTML = '<i class="bi bi-bar-chart-fill"></i> Grade <strong>' + maxEntry[0] + '</strong> mendominasi — ' + maxEntry[1] + ' dari ' + total + ' nilai (<strong>' + pct + '%</strong>)';
}

function loadGrade() {
    var ang = document.getElementById('gradeAngkatanSel').value;
    fetch('{{ route("admin.api.distribusi-grade") }}?semester={{ $semesterAktif }}&angkatan=' + encodeURIComponent(ang))
        .then(function(r) { return r.json(); })
        .then(function(d) { renderGradeChart(Object.values(d)); updateGradeInsight(d); })
        .catch(function() {});
}

document.getElementById('gradeAngkatanSel').addEventListener('change', loadGrade);
renderGradeChart(initGrade);
</script>
@endpush
