@extends('layouts.dosen')

@php
    $namaKelas = $kelas->isNotEmpty() ? $kelas->pluck('nama')->implode(', ') : 'Belum ada kelas';
    $tahunAkad = $kelas->isNotEmpty() ? ($kelas->first()->tahun_akademik ?? '2024/2025') : '2024/2025';
@endphp

@section('title', 'Dashboard DPA')
@section('page-title', 'Dashboard DPA')
@section('page-sub', 'Kelas ' . $namaKelas . ' · Semester ' . $semesterAktif)

@push('styles')
<style>
.ipk-bar{width:100%;height:4px;background:#EFF6FF;border-radius:2px;margin-top:6px;overflow:hidden;}
.ipk-bar-fill{height:100%;border-radius:2px;background:linear-gradient(90deg,#2563EB,#60A5FA);}
.chart-card-v2{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;}
.chart-head-v2{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:6px;}
.chart-title-v2{font-size:15px;font-weight:700;color:var(--text-1);}
.chart-sub-v2{font-size:12px;color:var(--text-2);margin-top:2px;}
.donut-wrap-v2{display:flex;align-items:center;gap:20px;margin-top:16px;}
.donut-canvas-box{flex-shrink:0;width:140px;height:140px;position:relative;}
.donut-canvas-box canvas{width:140px !important;height:140px !important;display:block;}
.donut-center{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;pointer-events:none;}
.donut-center-num{font-size:20px;font-weight:800;color:var(--text-1);line-height:1;}
.donut-center-sub{font-size:10px;color:var(--text-2);font-weight:500;margin-top:2px;}
.legend-v2{flex:1;display:flex;flex-direction:column;gap:9px;}
.legend-v2-row{display:flex;align-items:center;justify-content:space-between;gap:8px;}
.legend-v2-left{display:flex;align-items:center;gap:7px;}
.legend-v2-dot{width:9px;height:9px;border-radius:50%;flex-shrink:0;}
.legend-v2-label{font-size:12.5px;color:var(--text-2);}
.legend-v2-val{font-size:12.5px;font-weight:700;color:var(--text-1);}
.legend-bar{width:100%;height:4px;background:#F1F5F9;border-radius:2px;margin-top:3px;overflow:hidden;}
.legend-bar-fill{height:100%;border-radius:2px;}

/* ── Stat card clickable ─────────────────────────── */
.stat-card-link{display:block;text-decoration:none;color:inherit;}
.stat-card-link .stat-card-v2{cursor:pointer;transition:transform .18s ease,box-shadow .18s ease;}
.stat-card-link:hover .stat-card-v2{transform:translateY(-3px);box-shadow:0 8px 24px rgba(0,0,0,.11);}
.stat-card-link:active .stat-card-v2{transform:translateY(-1px);}

/* ── Status Bar (NEW) ────────────────────────────── */
.status-summary{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:16px 20px;display:flex;align-items:center;gap:20px;flex-wrap:wrap;}
.status-bar-wrap{flex:1;min-width:180px;}
.status-bar{width:100%;height:8px;background:#F1F5F9;border-radius:4px;overflow:hidden;display:flex;}
.status-bar-safe{height:100%;background:#22C55E;transition:width .6s ease;}
.status-bar-risk{height:100%;background:#EF4444;transition:width .6s ease;}
.status-item{display:flex;align-items:center;gap:8px;flex-shrink:0;}
.status-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;}
.status-item-val{font-size:16px;font-weight:800;color:var(--text-1);line-height:1;}
.status-item-lbl{font-size:11px;color:var(--text-2);margin-top:1px;}

/* ── Tabel (NEW) ─────────────────────────────────── */
.mhs-table{width:100%;border-collapse:collapse;}
.mhs-table thead th{font-size:11px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.6px;padding:8px 12px;border-bottom:1.5px solid var(--border);background:#FAFBFF;white-space:nowrap;}
.mhs-table tbody tr{border-bottom:1px solid #F8FAFC;transition:background .12s;}
.mhs-table tbody tr:last-child{border-bottom:none;}
.mhs-table tbody tr:hover{background:#F8FAFF;}
.mhs-table tbody td{padding:11px 12px;vertical-align:middle;font-size:13px;}
.mhs-table tbody tr.row-risk{background:rgba(239,68,68,.025);}
.mhs-table tbody tr.row-risk td:first-child{border-left:3px solid #EF4444;}

@media(max-width:768px){
    .hide-mobile{display:none!important;}
    .status-bar-wrap{width:100%;}
}
@media(max-width:576px){
    .hide-sm{display:none!important;}
    .donut-canvas-box{width:120px;height:120px;}
    .donut-canvas-box canvas{width:120px!important;height:120px!important;}
    .donut-center-num{font-size:16px;}
}
@media(max-width:480px){
    .donut-wrap-v2{flex-direction:column;align-items:center;}
    .legend-v2{width:100%;}
}

/* ── Mini Summary Cards (Berisiko Dashboard) ─── */
.mini-sum-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 16px; }
.mini-sum-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
.mini-sum-card-bar { height: 3px; }
.mini-sum-card-body { padding: 12px 16px; }
.mini-sum-card-val { font-size: 26px; font-weight: 800; line-height: 1; letter-spacing: -0.5px; }
.mini-sum-card-lbl { font-size: 11.5px; color: var(--text-2); margin-top: 4px; font-weight: 500; }

/* ── Risk Table ──────────────────────────────── */
.risk-table { width: 100%; border-collapse: collapse; }
.risk-table thead th { font-size: 11px; font-weight: 700; color: var(--text-3); text-transform: uppercase; letter-spacing: .6px; padding: 10px 14px; border-bottom: 1.5px solid var(--border); background: #FAFBFF; white-space: nowrap; }
.risk-table tbody tr { border-bottom: 1px solid #F8FAFC; transition: background .1s; }
.risk-table tbody tr:last-child { border-bottom: none; }
.risk-table tbody tr:hover { background: #FAFBFF; }
.risk-table tbody td { padding: 12px 14px; vertical-align: middle; font-size: 13px; }
.badge-risiko { display: inline-flex; align-items: center; gap: 2px; border-radius: 99px; padding: 2px 7px; font-size: 10.5px; font-weight: 700; white-space: nowrap; margin: 1px; }
.no-circle { width: 28px; height: 28px; border-radius: 50%; background: #F1F5F9; color: var(--text-2); display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; }
.risk-empty-state { text-align: center; padding: 52px 20px; }
</style>
@endpush

@section('content')

{{-- ══ BANNER ══ --}}
@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg, #1E3A8A 0%, #4338CA 55%, #6366F1 100%)',
    'icon'         => 'bi-person-badge-fill',
    'title'        => 'Dashboard DPA — ' . ($dosen->nama ?? auth()->user()->name),
    'sub'          => 'Kelas ' . $namaKelas . ' · Semester ' . $semesterAktif . ' · ' . $tahunAkad,
    'chips'        => [
        ['icon' => 'bi-building',                 'label' => 'Kelas ' . $namaKelas],
        ['icon' => 'bi-people-fill',              'label' => $totalMahasiswa . ' Mahasiswa Bimbingan'],
        ['icon' => 'bi-exclamation-triangle-fill','label' => $totalBerisiko . ' Berisiko'],
        ['icon' => 'bi-award-fill',               'label' => 'IPK Rata-rata ' . number_format($rataRataIpk, 2)],
        ['icon' => 'bi-graph-down-arrow',         'label' => $totalNilaiDE . ' Nilai D/E'],
    ],
    'badge_num'    => $totalMahasiswa,
    'badge_label'  => "Total\nMahasiswa",
    'badge2_num'   => $totalBerisiko,
    'badge2_label' => "Perlu\nBimbingan",
])

{{-- ══ ALERT (LAMA) ══ --}}
@if($berisikoTanpaCatatan > 0)
<div class="risk-alert-wrap" id="riskAlertDosen">
    <div class="risk-pulse-ring"></div>
    <div class="risk-alert-left">
        <div class="risk-alert-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="risk-alert-content">
            <div class="risk-alert-tag">⚡ Tindakan Diperlukan</div>
            <div class="risk-alert-title">
                {{ $berisikoTanpaCatatan }} Mahasiswa Bimbingan Anda Berisiko!
            </div>
            <div class="risk-alert-desc">
                Terdapat mahasiswa dengan <strong>nilai D/E</strong> atau <strong>absensi ≥18 jam</strong>.
                Segera lakukan bimbingan akademik sebelum batas waktu perbaikan nilai.
            </div>
        </div>
    </div>
    <div class="risk-alert-right">
        <a href="{{ route('dosen.berisiko.index') }}" class="risk-alert-btn">
            <i class="bi bi-arrow-right-circle-fill"></i>
            Lihat & Tangani Sekarang
        </a>
        <button class="risk-alert-close" id="riskCloseDosen" title="Tutup">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
</div>
@endif

{{-- ══ STAT CARDS (LAMA) ══ --}}
<div class="section-label">Ringkasan Kelas</div>
<div class="row g-3 mb-4">
    {{-- Card 1: Total Mahasiswa — klik ke halaman kelas/mahasiswa --}}
    <div class="col-sm-3 col-6">
        <a href="{{ route('dosen.kelas') }}" class="stat-card-link" title="Lihat data mahasiswa">
            <div class="stat-card-v2">
                <div class="stat-card-accent" style="background:linear-gradient(90deg,#2563EB,#60A5FA);"></div>
                <div class="stat-card-body">
                    <div class="stat-icon-box" style="background:#EFF6FF;">
                        <i class="bi bi-people-fill" style="color:#2563EB;"></i>
                    </div>
                    <div class="stat-card-info">
                        <div class="stat-card-label">Total Mahasiswa</div>
                        <div class="stat-card-value" style="color:#2563EB;">{{ $totalMahasiswa }}</div>
                        <div class="stat-card-note">
                            <span class="stat-card-badge badge-blue"><i class="bi bi-mortarboard"></i> Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Card 2: Mahasiswa Berisiko — klik ke halaman berisiko --}}
    <div class="col-sm-3 col-6">
        <a href="{{ route('dosen.berisiko.index') }}" class="stat-card-link" title="Lihat mahasiswa berisiko">
            <div class="stat-card-v2">
                <div class="stat-card-accent" style="background:{{ $totalBerisiko>0 ? 'linear-gradient(90deg,#EF4444,#FCA5A5)' : 'linear-gradient(90deg,#22C55E,#86EFAC)' }};"></div>
                <div class="stat-card-body">
                    <div class="stat-icon-box" style="background:{{ $totalBerisiko>0 ? '#FEF2F2' : '#F0FDF4' }};">
                        <i class="bi bi-{{ $totalBerisiko>0 ? 'exclamation-triangle-fill' : 'shield-check-fill' }}" style="color:{{ $totalBerisiko>0 ? '#EF4444' : '#22C55E' }};"></i>
                    </div>
                    <div class="stat-card-info">
                        <div class="stat-card-label">Mahasiswa Berisiko</div>
                        <div class="stat-card-value" style="color:{{ $totalBerisiko>0 ? '#EF4444' : '#22C55E' }};">{{ $totalBerisiko }}</div>
                        <div class="stat-card-note">
                            @if($totalBerisiko>0)
                                <span class="stat-card-badge badge-down">Perlu bimbingan</span>
                            @else
                                <span class="stat-card-badge badge-up">Semua aman</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Card 3: Rata-rata IPK — tidak perlu link --}}
    <div class="col-sm-3 col-6">
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background:linear-gradient(90deg,#F59E0B,#FCD34D);"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:#FFFBEB;">
                    <i class="bi bi-award-fill" style="color:#F59E0B;"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Rata-rata IPK</div>
                    <div class="stat-card-value" style="color:#F59E0B;">{{ number_format($rataRataIpk, 2) }}</div>
                    <div class="ipk-bar"><div class="ipk-bar-fill" style="width:{{ ($rataRataIpk/4)*100 }}%;background:linear-gradient(90deg,#F59E0B,#FCD34D);"></div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Total Nilai D/E — klik ke berisiko dengan filter nilai --}}
    <div class="col-sm-3 col-6">
        <a href="{{ route('dosen.berisiko.index') }}?jenis=nilai" class="stat-card-link" title="Lihat mahasiswa dengan nilai D/E">
            <div class="stat-card-v2">
                <div class="stat-card-accent" style="background:linear-gradient(90deg,#7C3AED,#A78BFA);"></div>
                <div class="stat-card-body">
                    <div class="stat-icon-box" style="background:#F5F3FF;">
                        <i class="bi bi-graph-down-arrow" style="color:#7C3AED;"></i>
                    </div>
                    <div class="stat-card-info">
                        <div class="stat-card-label">Total Nilai D/E</div>
                        <div class="stat-card-value" style="color:#7C3AED;">{{ $totalNilaiDE }}</div>
                        <div class="stat-card-note">
                            <span class="stat-card-badge" style="background:#F5F3FF;color:#7C3AED;">Sem. terakhir</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- ══ STATUS BAR (NEW) ══ --}}
@php
    $totalAman     = $totalMahasiswa - $totalBerisiko;
    $pctAman       = $totalMahasiswa > 0 ? round($totalAman / $totalMahasiswa * 100) : 0;
    $pctRisiko     = $totalMahasiswa > 0 ? round($totalBerisiko / $totalMahasiswa * 100) : 0;
    $kompenPending = $mahasiswas->filter(fn($m) => $m->kompensasis->where('status','pending')->isNotEmpty());
@endphp
<div class="status-summary mb-4">
    <div style="flex-shrink:0;">
        <div style="font-size:12px;font-weight:700;color:var(--text-2);">Status Keseluruhan</div>
        <div style="font-size:11px;color:var(--text-3);margin-top:1px;">{{ $totalMahasiswa }} mahasiswa bimbingan</div>
    </div>
    <div class="status-bar-wrap">
        <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
            <span style="font-size:11.5px;font-weight:700;color:#22C55E;">{{ $totalAman }} Aman ({{ $pctAman }}%)</span>
            <span style="font-size:11.5px;font-weight:700;color:#EF4444;">{{ $totalBerisiko }} Berisiko ({{ $pctRisiko }}%)</span>
        </div>
        <div class="status-bar">
            <div class="status-bar-safe" style="width:{{ $pctAman }}%;"></div>
            <div class="status-bar-risk" style="width:{{ $pctRisiko }}%;"></div>
        </div>
    </div>
    <div style="display:flex;gap:16px;flex-shrink:0;">
        <div class="status-item">
            <div class="status-dot" style="background:#22C55E;"></div>
            <div>
                <div class="status-item-val">{{ $totalAman }}</div>
                <div class="status-item-lbl">Aman</div>
            </div>
        </div>
        <div class="status-item">
            <div class="status-dot" style="background:#EF4444;"></div>
            <div>
                <div class="status-item-val" style="color:#EF4444;">{{ $totalBerisiko }}</div>
                <div class="status-item-lbl">Berisiko</div>
            </div>
        </div>
        <div class="status-item">
            <div class="status-dot" style="background:#F59E0B;"></div>
            <div>
                <div class="status-item-val" style="color:#F59E0B;">{{ $kompenPending->count() }}</div>
                <div class="status-item-lbl">Kompen</div>
            </div>
        </div>
    </div>
</div>

{{-- ══ CHARTS (LAMA) ══ --}}
<div class="section-label">Laporan Visual Kelas</div>
<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="chart-card-v2">
            <div class="chart-head-v2">
                <div>
                    <div class="chart-title-v2">Distribusi Nilai Kelas</div>
                    <div class="chart-sub-v2">Jumlah nilai per grade · Semester terakhir</div>
                </div>
            </div>
            <div style="position:relative;height:220px;margin-top:14px;">
                <canvas id="nilaiChart"></canvas>
            </div>
            <div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:12px;">
                @foreach(['A'=>['#22C55E','Sangat Baik'],'B+'=>['#60A5FA','Baik+'],'B'=>['#3B82F6','Baik'],'C+'=>['#FBBF24','Cukup+'],'C'=>['#FDE68A','Cukup'],'D'=>['#F97316','Kurang'],'E'=>['#EF4444','Sangat Kurang']] as $g => $info)
                <div style="display:flex;align-items:center;gap:5px;font-size:11.5px;color:var(--text-2);">
                    <div style="width:8px;height:8px;border-radius:2px;background:{{ $info[0] }};flex-shrink:0;"></div>
                    {{ $g }} — {{ $info[1] }}
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="chart-card-v2">
            <div class="chart-head-v2">
                <div>
                    <div class="chart-title-v2">Rekap Absensi Kelas</div>
                    <div class="chart-sub-v2">Jam alpha, izin &amp; sakit · Semester terakhir</div>
                </div>
            </div>
            @php
                $totalAll = $totalI + $totalS + $totalA;
                $pctI = $totalAll > 0 ? round($totalI / $totalAll * 100) : 0;
                $pctS = $totalAll > 0 ? round($totalS / $totalAll * 100) : 0;
                $pctA = $totalAll > 0 ? round($totalA / $totalAll * 100) : 0;
            @endphp
            <div class="donut-wrap-v2">
                <div class="donut-canvas-box">
                    <canvas id="absensiChart" width="140" height="140"></canvas>
                    <div class="donut-center">
                        <div class="donut-center-num">{{ $totalA }}j</div>
                        <div class="donut-center-sub">Alpha</div>
                    </div>
                </div>
                <div class="legend-v2">
                    @foreach([['#EF4444','Alpha',$totalA,$pctA],['#FBBF24','Izin',$totalI,$pctI],['#3B82F6','Sakit',$totalS,$pctS]] as [$color,$label,$val,$pct])
                    <div>
                        <div class="legend-v2-row">
                            <div class="legend-v2-left">
                                <div class="legend-v2-dot" style="background:{{ $color }};"></div>
                                <span class="legend-v2-label">{{ $label }}</span>
                            </div>
                            <span class="legend-v2-val" style="{{ $label==='Alpha' && $val>0 ? 'color:#EF4444;' : '' }}">
                                {{ $val }}j <span style="font-weight:400;color:var(--text-3);font-size:11px;">({{ $pct }}%)</span>
                            </span>
                        </div>
                        <div class="legend-bar"><div class="legend-bar-fill" style="width:{{ $pct }}%;background:{{ $color }};"></div></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ ALERT KOMPENSASI (NEW — ringkas) ══ --}}
@if($kompenPending->count() > 0)
<div style="display:flex;align-items:center;justify-content:space-between;gap:12px;background:#FFFBEB;border:1px solid #FDE68A;border-left:4px solid #F59E0B;border-radius:10px;padding:11px 16px;margin-bottom:16px;flex-wrap:wrap;">
    <div style="display:flex;align-items:center;gap:10px;">
        <i class="bi bi-clipboard2-check-fill" style="color:#F59E0B;font-size:16px;flex-shrink:0;"></i>
        <div>
            <div style="font-size:13px;font-weight:700;color:#92400E;">
                {{ $kompenPending->count() }} mahasiswa belum lunas kompensasi
            </div>
            <div style="font-size:11.5px;color:#78350F;margin-top:1px;">
                Ingatkan mahasiswa untuk menyelesaikan kompensasi alpha sesegera mungkin
            </div>
        </div>
    </div>
    <span style="background:#FEF3C7;color:#92400E;padding:3px 12px;border-radius:20px;font-size:11px;font-weight:700;white-space:nowrap;">
        ⏳ {{ $kompenPending->count() }} Pending
    </span>
</div>
@endif

{{-- ══ SEMESTER PILLS ══
@if($semesterList->count() > 1)
<div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;flex-wrap:wrap;">
    <span style="font-size:12px;font-weight:700;color:var(--text-2);">Semester:</span>
    @foreach($semesterList as $sem)
    <a href="{{ route('dosen.dashboard', ['semester' => $sem]) }}"
       style="padding:5px 14px;border-radius:20px;font-size:12.5px;font-weight:700;text-decoration:none;transition:all .15s;
              {{ $sem == $semesterAktif
                  ? 'background:var(--blue);color:#fff;box-shadow:0 2px 8px rgba(37,99,235,.3);'
                  : 'background:#F1F5F9;color:var(--text-2);border:1px solid var(--border);' }}">
        Semester {{ $sem }}
    </a>
    @endforeach
</div>
@endif --}}

{{-- ══ TABEL MAHASISWA BERISIKO ══ --}}
<div class="section-label">Data Mahasiswa Bimbingan</div>

{{-- Mini Summary Cards
<div class="mini-sum-cards">
    <div class="mini-sum-card">
        <div class="mini-sum-card-bar" style="background:linear-gradient(90deg,#2563EB,#60A5FA);"></div>
        <div class="mini-sum-card-body">
            <div class="mini-sum-card-val" style="color:#2563EB;">{{ $totalMahasiswa }}</div>
            <div class="mini-sum-card-lbl">Total Mahasiswa</div>
        </div>
    </div>
    <div class="mini-sum-card">
        <div class="mini-sum-card-bar" style="background:{{ $totalBerisiko > 0 ? 'linear-gradient(90deg,#EF4444,#FCA5A5)' : 'linear-gradient(90deg,#22C55E,#86EFAC)' }};"></div>
        <div class="mini-sum-card-body">
            <div class="mini-sum-card-val" style="color:{{ $totalBerisiko > 0 ? '#EF4444' : '#22C55E' }};">{{ $totalBerisiko }}</div>
            <div class="mini-sum-card-lbl">Berisiko</div>
        </div>
    </div>
    <div class="mini-sum-card">
        <div class="mini-sum-card-bar" style="background:linear-gradient(90deg,#F59E0B,#FCD34D);"></div>
        <div class="mini-sum-card-body">
            <div class="mini-sum-card-val" style="color:#F59E0B;">{{ number_format($rataRataIpk, 2) }}</div>
            <div class="mini-sum-card-lbl">Rata-rata IPK</div>
        </div>
    </div>
</div> --}}

<div class="card-white tbl-card-v2">
    <div class="tbl-head-v2">
        <div>
            <div class="tbl-title-v2">Mahasiswa Berisiko</div>
            <div class="tbl-sub-v2">
                {{ $totalBerisiko }} dari {{ $totalMahasiswa }} mahasiswa perlu perhatian · Semester {{ $semesterAktif }}
            </div>
        </div>
        <a href="{{ route('dosen.berisiko.index') }}"
           style="display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;font-size:13px;font-weight:700;background:var(--blue);color:#fff;text-decoration:none;white-space:nowrap;flex-shrink:0;">
            <i class="bi bi-arrow-right-circle-fill" style="font-size:12px;"></i>
            Lihat Semua
        </a>
    </div>

    @php
        $mhsBerisikoTop = $mahasiswaBerisiko->take(10)->values();
        $badgeMapDash = [
            'ps'         => ['bg' => '#FEE2E2', 'color' => '#7F1D1D', 'icon' => 'bi-x-octagon-fill',          'label' => 'Putus Studi'],
            'sp3'        => ['bg' => '#FEE2E2', 'color' => '#DC2626', 'icon' => 'bi-alarm-fill',              'label' => 'SP III'],
            'sp2'        => ['bg' => '#FEF3C7', 'color' => '#EA580C', 'icon' => 'bi-clock-fill',              'label' => 'SP II'],
            'sp1'        => ['bg' => '#FEF9C3', 'color' => '#D97706', 'icon' => 'bi-clock-history',           'label' => 'SP I'],
            'nilai_e'    => ['bg' => '#FEE2E2', 'color' => '#991B1B', 'icon' => 'bi-x-circle-fill',           'label' => 'Nilai E'],
            'nilai_d'    => ['bg' => '#FEF9C3', 'color' => '#B45309', 'icon' => 'bi-exclamation-circle-fill', 'label' => 'D >3'],
            'ips_rendah' => ['bg' => '#EDE9FE', 'color' => '#5B21B6', 'icon' => 'bi-graph-down-arrow',        'label' => 'IPS < 2'],
        ];
        $colorsDash = ['#2563EB','#EF4444','#8B5CF6','#F59E0B','#0891B2','#DB2777','#16A34A'];
    @endphp

    @if($mhsBerisikoTop->isEmpty())
    <div class="risk-empty-state">
        <i class="bi bi-shield-check-fill" style="font-size:52px;color:#22C55E;display:block;margin-bottom:14px;"></i>
        <div style="font-size:16px;font-weight:700;color:#166534;margin-bottom:6px;">Semua mahasiswa aman ✅</div>
        <div style="font-size:13px;color:var(--text-3);">Tidak ada mahasiswa bimbingan yang berisiko pada semester ini.</div>
    </div>
    @else
    <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
        <table class="risk-table" style="min-width:560px;">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Mahasiswa</th>
                    <th style="text-align:center;">IPK</th>
                    <th style="text-align:center;" class="hide-mobile">IPS</th>
                    <th style="text-align:center;" class="hide-mobile">Nilai D/E</th>
                    <th style="text-align:center;">Alpha</th>
                    <th style="text-align:center;">Kategori Risiko</th>
                    <th style="width:80px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($mhsBerisikoTop as $i => $mhs)
                @php
                    $aColorD   = $colorsDash[$i % count($colorsDash)];
                    $ipkD      = $mhs->ipk_val ?? $mhs->ipk;
                    $ipSemD    = $mhs->getIpSemester($semesterAktif);
                    $totalAlpD = $mhs->absensis->where('semester', $semesterAktif)->sum('jam_alpha');
                    $jmlDED    = $mhs->nilais->where('semester', $semesterAktif)->whereIn('grade', ['D','E'])->count();
                    $katD      = $mhs->getKategoriRisiko();
                    $isKritisD = in_array('ps', $katD) || in_array('sp3', $katD);
                    $extraD    = max(0, count($katD) - 2);
                @endphp
                <tr style="{{ $isKritisD ? 'background:rgba(239,68,68,.025);' : '' }}">
                    <td>
                        <div class="no-circle" style="{{ $isKritisD ? 'background:#FEE2E2;color:#991B1B;' : '' }}">
                            {{ $i + 1 }}
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:50%;background:{{ $aColorD }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">
                                {{ strtoupper(substr($mhs->nama, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:13.5px;color:var(--text-1);">{{ $mhs->nama }}</div>
                                <div style="font-size:11px;color:var(--text-3);font-family:monospace;">{{ $mhs->nim }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <div style="font-weight:700;font-size:14px;color:{{ $ipkD < 2.5 ? '#EF4444' : 'var(--text-1)' }};">
                            {{ number_format($ipkD, 2) }}
                        </div>
                        <div style="width:44px;height:4px;background:#F1F5F9;border-radius:2px;overflow:hidden;margin:4px auto 0;">
                            <div style="height:100%;width:{{ min(($ipkD/4)*100,100) }}%;background:{{ $ipkD < 2.5 ? '#EF4444' : '#2563EB' }};border-radius:2px;"></div>
                        </div>
                    </td>
                    <td class="hide-mobile" style="text-align:center;">
                        <span style="font-weight:700;font-size:14px;color:{{ $ipSemD < 2.0 ? '#7C3AED' : 'var(--text-1)' }};">
                            {{ number_format($ipSemD, 2) }}
                        </span>
                    </td>
                    <td class="hide-mobile" style="text-align:center;">
                        @if($jmlDED > 0)
                        <span style="font-weight:800;font-size:15px;color:#F59E0B;">{{ $jmlDED }}</span>
                        @else
                        <span style="color:var(--text-3);font-size:12px;">—</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <span style="font-weight:800;font-size:15px;color:{{ $totalAlpD >= 18 ? '#8B5CF6' : 'var(--text-2)' }};">
                            {{ $totalAlpD }}j
                        </span>
                    </td>
                    <td style="text-align:center;">
                        @foreach(array_slice($katD, 0, 2) as $kat)
                        @php $b = $badgeMapDash[$kat] ?? ['bg'=>'#F1F5F9','color'=>'#64748B','icon'=>'bi-exclamation','label'=>$kat]; @endphp
                        <span class="badge-risiko" style="background:{{ $b['bg'] }};color:{{ $b['color'] }};">
                            <i class="bi {{ $b['icon'] }}" style="font-size:9px;"></i>
                            {{ $b['label'] }}
                        </span>
                        @endforeach
                        @if($extraD > 0)
                        <span class="badge-risiko" style="background:#F1F5F9;color:#64748B;">+{{ $extraD }} lagi</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <a href="{{ route('dosen.mahasiswa.detail', $mhs->id) }}?semester={{ $semesterAktif }}"
                           style="display:inline-flex;align-items:center;gap:3px;padding:5px 10px;border-radius:7px;font-size:12px;font-weight:600;background:var(--blue);color:#fff;text-decoration:none;">
                            <i class="bi bi-eye-fill" style="font-size:11px;"></i> Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($totalBerisiko > 10)
    <div style="text-align:center;padding:10px 14px;margin-top:4px;border-top:1px solid var(--border);">
        <a href="{{ route('dosen.berisiko.index') }}"
           style="font-size:12.5px;font-weight:700;color:var(--blue);text-decoration:none;">
            + {{ $totalBerisiko - 10 }} mahasiswa berisiko lainnya · Lihat Semua →
        </a>
    </div>
    @endif
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script>
// Bar chart distribusi nilai — data dari semester terakhir (dihitung di controller)
new Chart(document.getElementById('nilaiChart').getContext('2d'), {
    plugins: [ChartDataLabels],
    type: 'bar',
    data: {
        labels: ['A', 'B+', 'B', 'C+', 'C', 'D', 'E'],
        datasets: [{
            data: [
                {{ $gradeDistribusi['A'] }},
                {{ $gradeDistribusi['B+'] }},
                {{ $gradeDistribusi['B'] }},
                {{ $gradeDistribusi['C+'] }},
                {{ $gradeDistribusi['C'] }},
                {{ $gradeDistribusi['D'] }},
                {{ $gradeDistribusi['E'] }}
            ],
            backgroundColor: ['#22C55E', '#60A5FA', '#3B82F6', '#FBBF24', '#FDE68A', '#F97316', '#EF4444'],
            borderRadius: 6,
            borderSkipped: false,
            maxBarThickness: 48
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0F172A',
                padding: 10,
                cornerRadius: 8,
                callbacks: { label: c => ' ' + c.raw + ' nilai' }
            },
            datalabels: {
                anchor: 'end',
                align: 'top',
                offset: 2,
                formatter: val => val > 0 ? val : '',
                font: { family: 'Plus Jakarta Sans', weight: '700', size: 12 },
                color: '#1E293B'
            }
        },
        layout: { padding: { top: 20 } },
        scales: {
            x: {
                grid: { display: false },
                ticks: { font: { family: 'Plus Jakarta Sans', size: 12 }, color: '#64748B' }
            },
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, font: { family: 'Plus Jakarta Sans', size: 12 }, color: '#64748B' },
                grid: { color: '#F8FAFC' },
                border: { display: false }
            }
        }
    }
});

// Donut chart absensi — data dari semester terakhir (dihitung di controller)
new Chart(document.getElementById('absensiChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Alpha', 'Izin', 'Sakit'],
        datasets: [{
            data: [{{ $totalA ?? 0 }}, {{ $totalI ?? 0 }}, {{ $totalS ?? 0 }}],
            backgroundColor: ['#EF4444', '#FBBF24', '#3B82F6'],
            borderWidth: 3,
            borderColor: '#FFFFFF',
            hoverOffset: 5
        }]
    },
    options: {
        responsive: false,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0F172A',
                padding: 10,
                cornerRadius: 8,
                callbacks: {
                    label: c => {
                        const total = c.dataset.data.reduce((a, b) => a + b, 0);
                        const pct   = total > 0 ? Math.round(c.raw / total * 100) : 0;
                        return ' ' + c.label + ': ' + c.raw + ' jam (' + pct + '%)';
                    }
                }
            }
        }
    }
});


(function() {
    var el   = document.getElementById('riskAlertDosen');
    var btnX = document.getElementById('riskCloseDosen');
    if (!el || !btnX) return;
    btnX.addEventListener('click', function() {
        el.style.animation = 'alertSlideOut .35s cubic-bezier(.4,0,1,1) forwards';
        setTimeout(function() { el.style.display = 'none'; }, 340);
    });
})();
</script>
@endpush