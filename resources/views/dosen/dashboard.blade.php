@extends('layouts.dosen')

@section('title', 'Dashboard DPA')
@section('page-title', 'Dashboard DPA')
@section('page-sub', ($dosen->nama ?? '') . ' · ' . $totalMahasiswa . ' Mahasiswa Bimbingan')

@push('styles')
<style>
.ipk-bar{width:100%;height:4px;background:#EFF6FF;border-radius:2px;margin-top:6px;overflow:hidden;}
.ipk-bar-fill{height:100%;border-radius:2px;background:linear-gradient(90deg,#2563EB,#60A5FA);}
.grade-pill{display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:50%;font-size:11px;font-weight:800;}
.grade-A{background:#DCFCE7;color:#15803D;}
.grade-B{background:#DBEAFE;color:#1D4ED8;}
.grade-C{background:#FEF9C3;color:#854D0E;}
.grade-D{background:#FEE2E2;color:#991B1B;}
.grade-E{background:#FEE2E2;color:#7F1D1D;}
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
</style>
@endpush

@section('content')

{{-- ══ BANNER ══ --}}
@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg, #1E3A8A 0%, #4338CA 55%, #6366F1 100%)',
    'icon'         => 'bi-person-badge-fill',
    'title'        => 'Dashboard DPA — ' . ($dosen->nama ?? auth()->user()->name),
    'sub'          => 'Panel Dosen Pembimbing Akademik · Tahun Akademik 2024/2025',
    'chips'        => [
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

{{-- STAT CARDS --}}
<div class="section-label">Ringkasan Kelas</div>
<div class="row g-3 mb-4">
    <div class="col-sm-3 col-6">
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
    </div>
    <div class="col-sm-3 col-6">
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
    </div>
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
    <div class="col-sm-3 col-6">
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
                        <span class="stat-card-badge" style="background:#F5F3FF;color:#7C3AED;">Semua matkul</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ALERT --}}
@if($totalBerisiko > 0)
<div class="risk-alert-wrap" id="riskAlertDosen">
    <div class="risk-pulse-ring"></div>
 
    <div class="risk-alert-left">
        <div class="risk-alert-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="risk-alert-content">
            <div class="risk-alert-tag">⚡ Tindakan Diperlukan</div>
            <div class="risk-alert-title">
                {{ $totalBerisiko }} Mahasiswa Bimbingan Anda Berisiko!
            </div>
            <div class="risk-alert-desc">
                Terdapat mahasiswa dengan <strong>nilai D/E</strong> atau <strong>absensi ≥18 jam</strong>.
                Segera lakukan bimbingan akademik sebelum batas waktu perbaikan nilai.
            </div>
        </div>
    </div>
 
    <div class="risk-alert-right">
        <a href="{{ route('dosen.kelas') }}" class="risk-alert-btn">
            <i class="bi bi-arrow-right-circle-fill"></i>
            Lihat & Tangani Sekarang
        </a>
        <button class="risk-alert-close" id="riskCloseDosen" title="Tutup">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
</div>
@endif

{{-- CHARTS --}}
<div class="section-label">Laporan Visual Kelas</div>
<div class="row g-3 mb-4">

    {{-- Bar Chart Distribusi Nilai --}}
    <div class="col-lg-7">
        <div class="chart-card-v2">
            <div class="chart-head-v2">
                <div>
                    <div class="chart-title-v2">Distribusi Nilai Kelas</div>
                    <div class="chart-sub-v2">Jumlah mahasiswa per grade · Semua mata kuliah</div>
                </div>
            </div>
            <div style="position:relative;height:220px;margin-top:14px;">
                <canvas id="nilaiChart"></canvas>
            </div>
            {{-- Grade legend --}}
            <div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:12px;">
                @foreach(['A'=>['#22C55E','Sangat Baik'],'B'=>['#3B82F6','Baik'],'C'=>['#FBBF24','Cukup'],'D'=>['#F97316','Kurang'],'E'=>['#EF4444','Sangat Kurang']] as $g => $info)
                <div style="display:flex;align-items:center;gap:5px;font-size:11.5px;color:var(--text-2);">
                    <div style="width:8px;height:8px;border-radius:2px;background:{{ $info[0] }};flex-shrink:0;"></div>
                    {{ $g }} — {{ $info[1] }}
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Donut Absensi --}}
    <div class="col-lg-5">
        <div class="chart-card-v2">
            <div class="chart-head-v2">
                <div>
                    <div class="chart-title-v2">Rekap Absensi Kelas</div>
                    <div class="chart-sub-v2">Total jam kehadiran seluruh mahasiswa</div>
                </div>
            </div>

            @php
                $totalH = $mahasiswas->sum(fn($m) => $m->absensis->sum('jam_hadir'));
                $totalI = $mahasiswas->sum(fn($m) => $m->absensis->sum('jam_izin'));
                $totalS = $mahasiswas->sum(fn($m) => $m->absensis->sum('jam_sakit'));
                $totalA = $mahasiswas->sum(fn($m) => $m->absensis->sum('jam_alpha'));
                $totalAll = $totalH + $totalI + $totalS + $totalA;
                $pctH = $totalAll > 0 ? round($totalH/$totalAll*100) : 0;
                $pctI = $totalAll > 0 ? round($totalI/$totalAll*100) : 0;
                $pctS = $totalAll > 0 ? round($totalS/$totalAll*100) : 0;
                $pctA = $totalAll > 0 ? round($totalA/$totalAll*100) : 0;
            @endphp

            <div class="donut-wrap-v2">
                <div class="donut-canvas-box">
                    <canvas id="absensiChart" width="140" height="140"></canvas>
                    <div class="donut-center">
                        <div class="donut-center-num">{{ $pctH }}%</div>
                        <div class="donut-center-sub">Hadir</div>
                    </div>
                </div>
                <div class="legend-v2">
                    @foreach([
                        ['#22C55E','Hadir',$totalH,$pctH],
                        ['#FBBF24','Izin',$totalI,$pctI],
                        ['#3B82F6','Sakit',$totalS,$pctS],
                        ['#EF4444','Alpha',$totalA,$pctA],
                    ] as [$color,$label,$val,$pct])
                    <div>
                        <div class="legend-v2-row">
                            <div class="legend-v2-left">
                                <div class="legend-v2-dot" style="background:{{ $color }};"></div>
                                <span class="legend-v2-label">{{ $label }}</span>
                            </div>
                            <span class="legend-v2-val" style="{{ $label==='Alpha' && $val>0 ? 'color:#EF4444;' : '' }}">{{ $val }}j</span>
                        </div>
                        <div class="legend-bar"><div class="legend-bar-fill" style="width:{{ $pct }}%;background:{{ $color }};"></div></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TABEL MAHASISWA --}}
<div class="section-label">Data Mahasiswa Bimbingan</div>
<div class="card-white tbl-card-v2">
    <div class="tbl-head-v2">
        <div>
            <div class="tbl-title-v2">Performa Mahasiswa</div>
            <div class="tbl-sub-v2">{{ $kelas->first()->nama ?? '' }} · Semester {{ $kelas->first()->semester ?? '' }}</div>
        </div>
        <div class="tbl-actions">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Cari mahasiswa..." id="searchMhs">
            </div>
            <div class="filter-wrap">
                <button class="btn-filter" id="filterMhsBtn">
                    <i class="bi bi-sliders2" style="font-size:12px;"></i> Filter
                </button>
                <div class="filter-menu" id="filterMhsMenu">
                    <div class="filter-menu-label">Filter Status</div>
                    <div class="filter-opt active" data-val="">Semua</div>
                    <div class="filter-opt" data-val="berisiko">⚠ Berisiko</div>
                    <div class="filter-opt" data-val="aman">✓ Aman</div>
                </div>
            </div>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="ac-table-v2">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Mahasiswa</th>
                    <th style="text-align:center;">IPK</th>
                    <th style="text-align:center;">IP Sem</th>
                    <th style="text-align:center;">Alpha</th>
                    <th style="text-align:center;">Grade Min</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="mhsBody">
                @foreach($mahasiswas as $i => $mhs)
                @php
                    $ipkMhs   = $mhs->ipk_val ?? $mhs->ipk;
                    $semAktif = $mhs->kelas->semester ?? 6;
                    $ipSem    = $mhs->getIpSemester($semAktif);
                    $berisiko = $mhs->is_berisiko ?? $mhs->isBerisiko();
                    $totalAlp = $mhs->absensis->sum('jam_alpha');
                    $grades   = $mhs->nilais->pluck('grade');
                    $gradeMin = $grades->contains('E') ? 'E' : ($grades->contains('D') ? 'D' : ($grades->contains('C') ? 'C' : ($grades->contains('B') ? 'B' : 'A')));
                    $colors   = ['#2563EB','#16A34A','#7C3AED','#F59E0B','#EF4444','#0891B2','#DB2777'];
                    $aColor   = $colors[$i % count($colors)];
                @endphp
                <tr data-nama="{{ strtolower($mhs->nama) }}"
                    data-status="{{ $berisiko ? 'berisiko' : 'aman' }}"
                    style="{{ $berisiko ? 'background:rgba(239,68,68,.03);' : '' }}">
                    <td class="muted" style="{{ $berisiko ? 'border-left:3px solid #EF4444;' : '' }}">{{ $i+1 }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:50%;background:{{ $aColor }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">
                                {{ strtoupper(substr($mhs->nama,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;color:var(--text-1);font-size:13.5px;">{{ $mhs->nama }}</div>
                                <div style="font-size:11px;color:var(--text-3);font-family:monospace;">{{ $mhs->nim }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <div style="font-weight:700;font-size:14px;color:{{ $ipkMhs < 2.5 ? '#EF4444' : ($ipkMhs >= 3.5 ? '#22C55E' : 'var(--text-1)') }};">
                            {{ number_format($ipkMhs, 2) }}
                        </div>
                        <div style="width:50px;height:4px;background:#F1F5F9;border-radius:2px;overflow:hidden;margin:4px auto 0;">
                            <div style="height:100%;width:{{ ($ipkMhs/4)*100 }}%;background:var(--blue);border-radius:2px;"></div>
                        </div>
                    </td>
                    <td style="text-align:center;font-size:13px;font-weight:600;color:{{ $ipSem < 2.5 ? '#EF4444' : 'var(--text-1)' }};">
                        {{ number_format($ipSem, 2) }}
                    </td>
                    <td style="text-align:center;font-weight:700;color:{{ $totalAlp>=18 ? '#EF4444' : ($totalAlp>=14 ? '#F59E0B' : 'var(--text-2)') }};">
                        {{ $totalAlp }}j {{ $totalAlp>=18 ? '⛔' : ($totalAlp>=14 ? '⚠️' : '') }}
                    </td>
                    <td style="text-align:center;">
                        <span class="grade-pill grade-{{ $gradeMin }}">{{ $gradeMin }}</span>
                    </td>
                    <td>
                        @if($berisiko)
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:700;background:#FEE2E2;color:#991B1B;">
                                <i class="bi bi-exclamation-circle-fill"></i> Berisiko
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:700;background:#DCFCE7;color:#166534;">
                                <i class="bi bi-check-circle-fill"></i> Aman
                            </span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('dosen.mahasiswa.detail', $mhs->id) }}" class="btn-outline" style="font-size:12px;padding:5px 12px;">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="display:flex;align-items:center;gap:10px;margin-top:14px;padding-top:12px;border-top:1px solid var(--border);flex-wrap:wrap;">
        <div style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:11.5px;font-weight:600;background:#EFF6FF;color:#1D4ED8;">
            <i class="bi bi-people-fill"></i> {{ $totalMahasiswa }} mahasiswa
        </div>
        @if($totalBerisiko > 0)
        <div style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:11.5px;font-weight:600;background:#FEE2E2;color:#991B1B;">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ $totalBerisiko }} berisiko
        </div>
        @endif
        <div style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:11.5px;font-weight:600;background:#FFFBEB;color:#92400E;">
            <i class="bi bi-award"></i> IPK rata-rata {{ number_format($rataRataIpk, 2) }}
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
@php
    $gradeCount = ['A'=>0,'B'=>0,'C'=>0,'D'=>0,'E'=>0];
    foreach($mahasiswas as $mhs) {
        foreach($mhs->nilais as $n) {
            if(isset($gradeCount[$n->grade])) {
                $gradeCount[$n->grade]++;
            }
        }
    }
@endphp

var gradeLabels = ['A','B','C','D','E'];
var gradeData   = [{{ $gradeCount['A'] }},{{ $gradeCount['B'] }},{{ $gradeCount['C'] }},{{ $gradeCount['D'] }},{{ $gradeCount['E'] }}];
var gradeColors = ['#22C55E','#3B82F6','#FBBF24','#F97316','#EF4444'];

var barCtx = document.getElementById('nilaiChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: gradeLabels,
        datasets: [{
            label: 'Jumlah Mahasiswa',
            data: gradeData,
            backgroundColor: gradeColors,
            borderRadius: 6,
            borderSkipped: false,
            maxBarThickness: 48,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0F172A', padding:10, cornerRadius:8,
                callbacks: { label: function(c) { return ' ' + c.raw + ' mahasiswa'; } }
            }
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { family:'Plus Jakarta Sans', size:12 }, color:'#64748B' } },
            y: { beginAtZero: true, ticks: { stepSize:1, font: { family:'Plus Jakarta Sans', size:12 }, color:'#64748B' }, grid: { color:'#F8FAFC' }, border: { display:false } }
        }
    }
});

var donutCtx = document.getElementById('absensiChart').getContext('2d');
new Chart(donutCtx, {
    type: 'doughnut',
    data: {
        labels: ['Hadir','Izin','Sakit','Alpha'],
        datasets: [{
            data: [{{ $totalH ?? 0 }},{{ $totalI ?? 0 }},{{ $totalS ?? 0 }},{{ $totalA ?? 0 }}],
            backgroundColor: ['#22C55E','#FBBF24','#3B82F6','#EF4444'],
            borderWidth: 3, borderColor: '#FFFFFF', hoverOffset: 5,
        }]
    },
    options: {
        responsive: false, maintainAspectRatio: false, cutout: '68%',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0F172A', padding:10, cornerRadius:8,
                callbacks: { label: function(c) { return ' '+c.label+': '+c.raw+' jam'; } }
            }
        }
    }
});

document.getElementById('searchMhs').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#mhsBody tr').forEach(function(r) {
        r.style.display = (r.dataset.nama||'').includes(q) ? '' : 'none';
    });
});

document.getElementById('filterMhsBtn').addEventListener('filterChange', function(e) {
    var val = e.detail.value;
    document.querySelectorAll('#mhsBody tr').forEach(function(r) {
        if (!val) { r.style.display=''; return; }
        r.style.display = r.dataset.status===val ? '' : 'none';
    });
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