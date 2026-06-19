@extends('layouts.admin')
@section('title','Admin Dashboard')
@section('page-title','Overview Sistem')
@section('page-sub','Jurusan Teknologi Informasi — Politeknik Negeri Malang')

@section('topbar-actions')
<a href="{{ route('admin.import.index') }}" class="btn-primary">
    <i class="bi bi-file-earmark-arrow-up"></i> Import Data
</a>
@endsection

@push('styles')
<style>
.risk-alert-wrap {
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
    animation: alertSlideIn .4s cubic-bezier(.16,1,.3,1) both;
    flex-wrap: wrap;
}
@keyframes alertSlideIn {
    from { opacity:0; transform: translateY(-12px) scale(.98); }
    to   { opacity:1; transform: translateY(0) scale(1); }
}
@keyframes alertSlideOut {
    from { opacity:1; transform: translateY(0) scale(1); max-height:200px; margin-bottom:24px; padding:20px 24px; }
    to   { opacity:0; transform: translateY(-8px) scale(.97); max-height:0; margin-bottom:0; padding:0 24px; }
}
.risk-alert-wrap::before {
    content: '';
    position: absolute; inset: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,.06) 1px, transparent 1px);
    background-size: 24px 24px;
    pointer-events: none;
}
.risk-alert-wrap::after {
    content: '';
    position: absolute;
    top: 0; left: -100%;
    width: 60%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.04), transparent);
    animation: glowSweep 4s ease infinite;
    pointer-events: none;
}
@keyframes glowSweep {
    0%   { left: -60%; }
    100% { left: 140%; }
}
.risk-pulse-ring {
    position: absolute;
    left: 28px; top: 50%;
    transform: translateY(-50%);
    width: 52px; height: 52px;
    border-radius: 50%;
    background: rgba(239,68,68,.2);
    animation: ringPulse 2s ease-out infinite;
    pointer-events: none;
}
@keyframes ringPulse {
    0%   { transform: translateY(-50%) scale(1);   opacity:.8; }
    70%  { transform: translateY(-50%) scale(1.8); opacity:0; }
    100% { transform: translateY(-50%) scale(1);   opacity:0; }
}
.risk-alert-left {
    display: flex; align-items: flex-start;
    gap: 16px; flex: 1; min-width: 0;
    position: relative; z-index: 1;
}
.risk-alert-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #FCA5A5; flex-shrink: 0;
    animation: iconShake 3s ease infinite;
}
@keyframes iconShake {
    0%,90%,100% { transform: rotate(0deg); }
    92%  { transform: rotate(-8deg); }
    94%  { transform: rotate(8deg); }
    96%  { transform: rotate(-4deg); }
    98%  { transform: rotate(4deg); }
}
.risk-alert-content { min-width: 0; }
.risk-alert-tag {
    display: inline-flex; align-items: center;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 20px; padding: 2px 10px;
    font-size: 11px; font-weight: 700;
    color: #FCA5A5; letter-spacing: .5px; margin-bottom: 6px;
}
.risk-alert-title {
    font-size: 15px; font-weight: 800;
    color: #fff; line-height: 1.3;
    margin-bottom: 5px; letter-spacing: -.2px;
}
.risk-alert-desc {
    font-size: 12.5px; color: rgba(255,255,255,.7); line-height: 1.5;
}
.risk-alert-desc strong { color: #FCA5A5; font-weight: 700; }
.risk-alert-right {
    display: flex; align-items: center; gap: 10px;
    flex-shrink: 0; position: relative; z-index: 1;
}
.risk-alert-btn {
    background: #fff; color: #991B1B;
    border: none; border-radius: 9px;
    padding: 10px 18px; font-size: 13px; font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer; text-decoration: none;
    display: inline-flex; align-items: center; gap: 7px;
    transition: all .2s; white-space: nowrap;
    box-shadow: 0 2px 8px rgba(0,0,0,.2);
}
.risk-alert-btn:hover {
    background: #FEF2F2; color: #7F1D1D;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,.25);
}
.risk-alert-btn i { font-size: 15px; }
.risk-alert-close {
    width: 34px; height: 34px; border-radius: 8px;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.15);
    color: rgba(255,255,255,.7); cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; transition: all .2s; flex-shrink: 0;
}
.risk-alert-close:hover {
    background: rgba(255,255,255,.2); color: #fff;
    border-color: rgba(255,255,255,.3); transform: scale(1.05);
}
@media (max-width: 768px) {
    .risk-alert-wrap  { padding: 16px 18px; gap: 14px; }
    .risk-alert-right { width: 100%; justify-content: space-between; }
    .risk-alert-btn   { flex: 1; justify-content: center; }
    .risk-pulse-ring  { display: none; }
    .risk-alert-title { font-size: 14px; }
}

/* ─── Dashboard insight sections ─── */
.chart-card-v2{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;}
.chart-head-v2{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:10px;}
.chart-title-v2{font-size:15px;font-weight:700;color:var(--text-1);}
.chart-sub-v2{font-size:12px;color:var(--text-2);margin-top:2px;}
.trend-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;}
.trend-naik{background:#DCFCE7;color:#15803D;}
.trend-turun{background:#FEE2E2;color:#991B1B;}
.trend-stabil{background:#F1F5F9;color:#475569;}
.filter-select{padding:6px 28px 6px 10px;border:1.5px solid var(--border);border-radius:8px;font-size:12.5px;font-weight:600;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-1);background:var(--white);cursor:pointer;outline:none;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' fill='none'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%2394A3B8' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 8px center;}
.filter-select:focus{border-color:var(--blue);}
.donut-wrap{display:flex;align-items:center;gap:16px;margin-top:12px;flex-wrap:wrap;}
.donut-canvas-box{flex-shrink:0;width:168px;height:168px;position:relative;}
.donut-center{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;pointer-events:none;}
.donut-center-num{font-size:25px;font-weight:800;color:var(--text-1);line-height:1;}
.donut-center-sub{font-size:10px;color:var(--text-2);font-weight:500;margin-top:2px;}
.risk-legend-list{display:flex;flex-direction:column;gap:8px;flex:1;}
.risk-legend-row{display:flex;align-items:center;justify-content:space-between;font-size:12.5px;padding:4px 0;}
.risk-legend-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;margin-right:8px;}
.risk-insight-note,.grade-insight{margin-top:14px;padding-top:12px;border-top:1px solid var(--border);font-size:12px;color:var(--text-2);display:flex;align-items:center;gap:6px;}
.risk-insight-note strong,.grade-insight strong{color:var(--text-1);font-weight:700;}
.kelas-tbl{width:100%;border-collapse:collapse;}
.kelas-tbl thead th{font-size:11px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.6px;padding:9px 14px;border-bottom:1.5px solid var(--border);background:#FAFBFF;white-space:nowrap;}
.kelas-tbl tbody tr{border-bottom:1px solid #F8FAFC;transition:background .1s;}
.kelas-tbl tbody tr:last-child{border-bottom:none;}
.kelas-tbl tbody tr:hover{background:#FAFBFF;}
.kelas-tbl tbody td{padding:11px 14px;font-size:13px;vertical-align:middle;}
.ipk-mini-bar{width:60px;height:4px;background:#F1F5F9;border-radius:2px;display:inline-block;vertical-align:middle;overflow:hidden;}
.ipk-mini-fill{height:100%;border-radius:2px;}
@media(max-width:768px){.donut-wrap{flex-direction:column;align-items:center;}.hide-sm{display:none!important;}}
</style>
@endpush

@section('content')

{{-- Banner --}}
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

{{-- ══ ALERT BERISIKO ══ --}}
@if($mahasiswaBerisiko > 0)
<div class="risk-alert-wrap" id="riskAlertAdmin">
    <div class="risk-pulse-ring"></div>
    <div class="risk-alert-left">
        <div class="risk-alert-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="risk-alert-content">
            <div class="risk-alert-tag">⚡ Tindakan Diperlukan</div>
            <div class="risk-alert-title">
                {{ $mahasiswaBerisiko }} Mahasiswa Terdeteksi Berisiko Akademik
            </div>
            <div class="risk-alert-desc">
                Terdapat mahasiswa dengan <strong>nilai D/E</strong>, <strong>alpha ≥18 jam (SP I–PS)</strong>, atau <strong>IPS &lt;2.00</strong> di semester terakhir.
                Segera arahkan DPA untuk bimbingan agar tidak berdampak pada kelulusan.
            </div>
        </div>
    </div>
    <div class="risk-alert-right">
        <a href="{{ route('admin.mahasiswa.index') }}" class="risk-alert-btn">
            <i class="bi bi-arrow-right-circle-fill"></i>
            Lihat & Tangani Sekarang
        </a>
        <button class="risk-alert-close" id="riskCloseAdmin" title="Tutup">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
</div>
@endif

{{-- ══ STAT CARDS ══ --}}
<div class="section-label">Statistik Sistem</div>
<div class="row g-3 mb-3">
    @php
    $totalKompenPending = \App\Models\Kompensasi::where('status','pending')->count();
    $stats = [
        ['label'=>'Mahasiswa Aktif',    'val'=>$totalMahasiswa,    'icon'=>'bi-mortarboard-fill',         'accent'=>'linear-gradient(90deg,#2563EB,#60A5FA)', 'ibg'=>'#EFF6FF', 'ic'=>'#2563EB', 'badge'=>'Terdaftar',      'bbg'=>'#DBEAFE', 'bc'=>'#1D4ED8'],
        ['label'=>'Total Dosen',        'val'=>$totalDosen,        'icon'=>'bi-person-badge-fill',        'accent'=>'linear-gradient(90deg,#16A34A,#86EFAC)', 'ibg'=>'#F0FDF4', 'ic'=>'#16A34A', 'badge'=>'Aktif mengajar', 'bbg'=>'#DCFCE7', 'bc'=>'#166534'],
        ['label'=>'Mata Kuliah',        'val'=>$totalMatkul,       'icon'=>'bi-book-fill',                'accent'=>'linear-gradient(90deg,#7C3AED,#A78BFA)', 'ibg'=>'#F5F3FF', 'ic'=>'#7C3AED', 'badge'=>'Semester aktif', 'bbg'=>'#EDE9FE', 'bc'=>'#5B21B6'],
        ['label'=>'Kelas Aktif',        'val'=>$totalKelas,        'icon'=>'bi-grid-3x3-gap-fill',        'accent'=>'linear-gradient(90deg,#0891B2,#67E8F9)', 'ibg'=>'#ECFEFF', 'ic'=>'#0891B2', 'badge'=>'Semua angkatan', 'bbg'=>'#CFFAFE', 'bc'=>'#0E7490'],
        ['label'=>'Mahasiswa Berisiko', 'val'=>$mahasiswaBerisiko, 'icon'=>'bi-exclamation-triangle-fill','accent'=>'linear-gradient(90deg,#94A3B8,#CBD5E1)', 'ibg'=>'#F8FAFC', 'ic'=>$mahasiswaBerisiko>0 ? '#EF4444' : '#22C55E', 'badge'=>$mahasiswaBerisiko>0 ? 'Perlu penanganan' : 'Semua aman', 'bbg'=>$mahasiswaBerisiko>0 ? '#FEE2E2' : '#DCFCE7', 'bc'=>$mahasiswaBerisiko>0 ? '#991B1B' : '#166534'],
        ['label'=>'Kompen Pending', 'val'=>$totalKompenPending, 'icon'=>'bi-clipboard2-check-fill', 'accent'=>$totalKompenPending>0 ? 'linear-gradient(90deg,#F59E0B,#FCD34D)' : 'linear-gradient(90deg,#22C55E,#86EFAC)', 'ibg'=>$totalKompenPending>0 ? '#FEF3C7' : '#F0FDF4', 'ic'=>$totalKompenPending>0 ? '#F59E0B' : '#22C55E', 'badge'=>$totalKompenPending>0 ? 'Perlu ditangani' : 'Semua lunas', 'bbg'=>$totalKompenPending>0 ? '#FEF9C3' : '#DCFCE7', 'bc'=>$totalKompenPending>0 ? '#854D0E' : '#166534'],
    ];
    @endphp

    @foreach($stats as $stat)
    <div class="col-md col-6">
        <div style="position:relative;background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;transition:transform .18s,box-shadow .18s;height:100%;"
             onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)'"
             onmouseout="this.style.transform='';this.style.boxShadow='var(--shadow)'">
            <div style="height:3px;background:{{ $stat['accent'] }};"></div>
            <i class="bi {{ $stat['icon'] }}" style="position:absolute;right:-8px;bottom:-12px;font-size:68px;color:{{ $stat['ic'] }};opacity:.06;pointer-events:none;"></i>
            <div style="padding:16px 18px;position:relative;z-index:1;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:40px;height:40px;border-radius:10px;background:{{ $stat['ibg'] }};display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">
                        <i class="bi {{ $stat['icon'] }}" style="color:{{ $stat['ic'] }};"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:11.5px;font-weight:600;color:var(--text-2);margin-bottom:2px;">{{ $stat['label'] }}</div>
                        <div style="font-size:28px;font-weight:800;line-height:1;color:{{ $stat['ic'] }};letter-spacing:-1px;">{{ $stat['val'] }}</div>
                    </div>
                </div>
                <div style="margin-top:10px;">
                    <span style="display:inline-flex;align-items:center;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700;background:{{ $stat['bbg'] }};color:{{ $stat['bc'] }};">
                        {{ $stat['badge'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ══ SECTION 1: TREN IPK ARIMA ══ --}}
<div class="section-label">Tren IPK per Angkatan</div>
<div class="row g-3 mb-3">
    <div class="col-lg-7">
        <div class="chart-card-v2">
            <div class="chart-head-v2">
                <div>
                    <div class="chart-title-v2">Tren IPK & Prediksi ARIMA</div>
                    <div class="chart-sub-v2">ARIMA(0,1,1) · Data historis & prediksi semester berikutnya</div>
                </div>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <span id="trenBadge" class="trend-badge trend-stabil">→ Memuat...</span>
                    @if($angkatanList->isNotEmpty())
                    <select id="angkatanSelect" class="filter-select">
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
                <div style="display:flex;gap:16px;font-size:11.5px;color:var(--text-2);">
                    <div style="display:flex;align-items:center;gap:5px;">
                        <div style="width:20px;height:2px;background:#2563EB;border-radius:1px;"></div> Historis
                    </div>
                    <div style="display:flex;align-items:center;gap:5px;">
                        <div style="width:20px;border-top:2px dashed #F59E0B;"></div> Prediksi
                    </div>
                </div>
                <a href="{{ route('admin.analitik.index') }}" style="font-size:12.5px;font-weight:700;color:var(--blue);text-decoration:none;">
                    Lihat Detail Analitik <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="chart-card-v2" style="height:100%;">
            <div class="chart-title-v2">Ringkasan ARIMA</div>
            <div class="chart-sub-v2" id="arimaPanelSub">Memuat data...</div>
            <div id="arimaPanelContent" style="margin-top:16px;"></div>
        </div>
    </div>
</div>

{{-- ══ SECTION 2: DISTRIBUSI ══ --}}
<div class="section-label">Distribusi Akademik</div>
<div class="row g-3 mb-3 align-items-start">
    {{-- Donut Risiko --}}
    <div class="col-lg-5">
        <div class="chart-card-v2">
            <div class="chart-head-v2">
                <div>
                    <div class="chart-title-v2">Distribusi Mahasiswa Berisiko</div>
                    <div class="chart-sub-v2">Per kategori risiko · Semester {{ $semesterAktif }}</div>
                </div>
                <a href="{{ route('admin.berisiko.index') }}" style="font-size:12px;font-weight:700;color:var(--blue);text-decoration:none;white-space:nowrap;">
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
            <div class="donut-wrap">
                <div class="donut-canvas-box">
                    <canvas id="risikoChart" width="168" height="168"></canvas>
                    <div class="donut-center">
                        <div class="donut-center-num">{{ $mahasiswaBerisiko }}</div>
                        <div class="donut-center-sub">Berisiko</div>
                    </div>
                </div>
                <div class="risk-legend-list">
                    @foreach($riskKeys as $idx => $kat)
                    @if($distribusiRisiko[$kat] > 0)
                    <div class="risk-legend-row">
                        <div style="display:flex;align-items:center;">
                            <div class="risk-legend-dot" style="background:{{ $riskColors[$idx] }};"></div>
                            <span style="color:var(--text-2);">{{ $riskLabels[$idx] }}</span>
                        </div>
                        <span style="font-weight:700;color:var(--text-1);">{{ $distribusiRisiko[$kat] }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @if($riskMaxIdx !== null && $riskMaxVal > 0)
            <div class="risk-insight-note">
                <i class="bi bi-info-circle-fill" style="color:var(--text-3);"></i>
                Kategori terbanyak: <strong>{{ $riskLabels[$riskMaxIdx] }}</strong> ({{ $riskMaxVal }} mahasiswa)
            </div>
            @endif
            @else
            <div style="text-align:center;padding:40px 16px;color:var(--text-3);">
                <i class="bi bi-shield-check-fill" style="font-size:40px;color:#22C55E;display:block;margin-bottom:10px;"></i>
                <div style="font-weight:600;color:#166534;">Tidak ada mahasiswa berisiko</div>
                <div style="font-size:12px;margin-top:4px;">Semua mahasiswa aman di semester ini</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Bar Grade --}}
    <div class="col-lg-7">
        <div class="chart-card-v2">
            <div class="chart-head-v2">
                <div>
                    <div class="chart-title-v2">Distribusi Grade Mahasiswa</div>
                    <div class="chart-sub-v2">Jumlah nilai per grade · Semester {{ $semesterAktif }}</div>
                </div>
                <select id="gradeAngkatanSel" class="filter-select">
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
            @if($maxGradeKey !== false)
            <div class="grade-insight" id="gradeInsight">
                <i class="bi bi-bar-chart-fill" style="color:var(--text-3);"></i>
                Grade <strong>{{ $maxGradeKey }}</strong> mendominasi — {{ $maxGradeVal }} dari {{ $totalGradeAll }} nilai (<strong>{{ $maxGradePct }}%</strong>)
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ══ SECTION 3: TABEL RINGKASAN PER KELAS ══ --}}
<div class="section-label">Ringkasan Akademik per Kelas</div>
<div class="card-white tbl-card-v2 mb-3">
    <div class="tbl-head-v2">
        <div>
            <div class="tbl-title-v2">Performa per Kelas</div>
            <div class="tbl-sub-v2">Semester {{ $semesterAktif }} · {{ count($ringkasanKelas) }} kelas aktif</div>
        </div>
    </div>
    @if(empty($ringkasanKelas))
    <div style="text-align:center;padding:36px;color:var(--text-3);">
        <i class="bi bi-grid-3x3-gap" style="font-size:32px;display:block;margin-bottom:10px;opacity:.3;"></i>
        Belum ada data kelas untuk semester ini.
    </div>
    @else
    <div style="overflow-x:auto;">
        <table class="kelas-tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kelas</th>
                    <th style="text-align:center;">Total</th>
                    <th style="text-align:center;">Berisiko</th>
                    <th style="text-align:center;">% Risiko</th>
                    <th style="text-align:center;" class="hide-sm">Rata-rata IPK</th>
                    <th style="text-align:center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ringkasanKelas as $i => $kls)
                @php $perlu = $kls['pct_risiko'] > 30; @endphp
                <tr style="{{ $perlu ? 'background:rgba(239,68,68,.025);' : '' }}">
                    <td style="font-size:12px;color:var(--text-3);{{ $perlu ? 'border-left:3px solid #EF4444;' : '' }}">{{ $i+1 }}</td>
                    <td style="font-weight:700;">{{ $kls['kelas'] }}</td>
                    <td style="text-align:center;">{{ $kls['total'] }}</td>
                    <td style="text-align:center;font-weight:700;color:{{ $kls['berisiko'] > 0 ? '#EF4444' : '#22C55E' }};">
                        {{ $kls['berisiko'] }}
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;align-items:center;gap:6px;justify-content:center;">
                            <span style="font-weight:700;font-size:12.5px;color:{{ $perlu ? '#EF4444' : 'var(--text-2)' }};">{{ $kls['pct_risiko'] }}%</span>
                            <div style="width:56px;height:7px;background:#E2E8F0;border-radius:4px;overflow:hidden;">
                                <div style="height:100%;width:{{ min($kls['pct_risiko'],100) }}%;background:{{ $perlu ? '#EF4444' : '#22C55E' }};border-radius:4px;"></div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center;" class="hide-sm">
                        <span style="font-weight:700;color:{{ $kls['ipk'] < 2.5 ? '#EF4444' : ($kls['ipk'] >= 3.5 ? '#22C55E' : 'var(--text-1)') }};">
                            {{ number_format($kls['ipk'], 2) }}
                        </span>
                        <div class="ipk-mini-bar">
                            <div class="ipk-mini-fill" style="width:{{ min(($kls['ipk']/4)*100,100) }}%;background:{{ $kls['ipk'] < 2.5 ? '#EF4444' : '#2563EB' }};"></div>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        @if($perlu)
                        <span style="display:inline-flex;align-items:center;gap:3px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;background:#FEE2E2;color:#991B1B;">
                            <i class="bi bi-exclamation-triangle-fill" style="font-size:10px;"></i> Perlu Perhatian
                        </span>
                        @else
                        <span style="display:inline-flex;align-items:center;gap:3px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;background:#DCFCE7;color:#166534;">
                            <i class="bi bi-check-circle-fill" style="font-size:10px;"></i> Baik
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
    <div class="tbl-footer">
        @if($kelasMaxRisk && $kelasMaxRisk['pct_risiko'] > 0)
        <div class="info-chip" style="background:#FEE2E2;color:#991B1B;">
            <i class="bi bi-exclamation-triangle-fill"></i> Risiko tertinggi: {{ $kelasMaxRisk['kelas'] }} ({{ $kelasMaxRisk['pct_risiko'] }}%)
        </div>
        @endif
        @if($kelasAman->isNotEmpty())
        <div class="info-chip" style="background:#DCFCE7;color:#166534;">
            <i class="bi bi-check-circle-fill"></i> Paling stabil: {{ $kelasAman->implode(', ') }}
        </div>
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

// ── Alert Close ──────────────────────────────────────────────────
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
        backgroundColor: 'rgba(37,99,235,.08)',
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
                y: { min: 0, max: 4, ticks: { stepSize: 0.5, font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748B' }, grid: { color: '#F8FAFC' }, border: { display: false } },
                x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748B', maxRotation: 0 } }
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
        evalHtml = '<div style="background:#F0FDF4;border:1px solid #86EFAC;border-radius:10px;padding:12px;">'
            + '<div style="font-size:10.5px;font-weight:700;color:#16A34A;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Evaluasi Model</div>'
            + '<div style="display:flex;gap:12px;">'
            + '<div style="text-align:center;flex:1;"><div style="font-size:18px;font-weight:800;color:#16A34A;">' + Number(d.evaluasi.mae).toFixed(2) + '</div><div style="font-size:10px;color:#6B7280;font-weight:600;">MAE</div></div>'
            + '<div style="text-align:center;flex:1;"><div style="font-size:18px;font-weight:800;color:#16A34A;">' + Number(d.evaluasi.mape).toFixed(1) + '%</div><div style="font-size:10px;color:#6B7280;font-weight:600;">MAPE</div></div>'
            + '</div></div>';
    }

    var predHtml = '';
    if (pred !== null) {
        predHtml = '<div style="background:#FFFBEB;border:1.5px solid #FCD34D;border-radius:10px;padding:14px;margin-bottom:12px;">'
            + '<div style="font-size:10.5px;font-weight:700;color:#D97706;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Prediksi Semester Berikutnya</div>'
            + '<div style="font-size:32px;font-weight:800;color:#D97706;letter-spacing:-1px;line-height:1;">' + Number(pred).toFixed(2) + '</div>'
            + '<div style="font-size:11.5px;color:var(--text-2);margin-top:6px;">' + (isNaik ? '↑ +' : '↓ ') + selisih + ' dari saat ini</div>'
            + '</div>';
    }

    cont.innerHTML = '<div style="background:#F8FAFF;border:1.5px solid #BFDBFE;border-radius:10px;padding:14px;margin-bottom:12px;">'
        + '<div style="font-size:10.5px;font-weight:700;color:var(--blue);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">IPK Rata-rata Saat Ini</div>'
        + '<div style="font-size:32px;font-weight:800;color:var(--blue);letter-spacing:-1px;line-height:1;">' + Number(last).toFixed(2) + '</div>'
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
                borderRadius: 6, maxBarThickness: 44,
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
                x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 12 }, color: '#64748B' } },
                y: { beginAtZero: true, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748B' }, grid: { color: '#F8FAFC' }, border: { display: false } }
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
    el.innerHTML = '<i class="bi bi-bar-chart-fill" style="color:var(--text-3);"></i> Grade <strong>' + maxEntry[0] + '</strong> mendominasi — ' + maxEntry[1] + ' dari ' + total + ' nilai (<strong>' + pct + '%</strong>)';
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