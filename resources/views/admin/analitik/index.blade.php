{{-- resources/views/admin/analitik/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Analitik Tren Akademik')
@section('page-title', 'Analitik Tren Akademik')
@section('page-sub', 'Analisis tren dan prediksi performa akademik per angkatan')

@push('styles')
<style>
/* ── Trend Card ──────────────────────────────────── */
.trend-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;}
.trend-card-head{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;}
.trend-card-title{font-size:14px;font-weight:700;color:var(--text-1);}
.trend-card-sub{font-size:11.5px;color:var(--text-2);margin-top:2px;}

/* ── Prediksi Box ────────────────────────────────── */
.pred-box{background:#F8FAFF;border:1.5px solid #BFDBFE;border-radius:10px;padding:14px 16px;margin-top:14px;}
.pred-label{font-size:11px;font-weight:700;color:var(--blue);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;}
.pred-val{font-size:32px;font-weight:800;color:var(--blue);letter-spacing:-1px;line-height:1;}
.pred-desc{font-size:11.5px;color:var(--text-2);margin-top:6px;line-height:1.5;}

/* ── Trend Badge ─────────────────────────────────── */
.trend-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;}
.trend-naik{background:#DCFCE7;color:#15803D;}
.trend-turun{background:#FEE2E2;color:#991B1B;}
.trend-stabil{background:#F1F5F9;color:#475569;}

/* ── Summary Table ───────────────────────────────── */
.sum-table{width:100%;border-collapse:collapse;}
.sum-table thead th{font-size:11px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.6px;padding:8px 14px;border-bottom:1.5px solid var(--border);background:#FAFBFF;white-space:nowrap;}
.sum-table tbody tr{border-bottom:1px solid #F8FAFC;transition:background .12s;}
.sum-table tbody tr:last-child{border-bottom:none;}
.sum-table tbody tr:hover{background:#F8FAFF;}
.sum-table tbody td{padding:12px 14px;font-size:13px;vertical-align:middle;}

/* ── IPK Bar ─────────────────────────────────────── */
.ipk-mini-bar{width:80px;height:5px;background:#F1F5F9;border-radius:3px;overflow:hidden;display:inline-block;vertical-align:middle;margin-left:6px;}
.ipk-mini-fill{height:100%;border-radius:3px;}

/* ── Angkatan Selector ───────────────────────────── */
.angkatan-pills{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:16px;}
.angkatan-pill{padding:6px 16px;border-radius:20px;font-size:12.5px;font-weight:600;cursor:pointer;border:1.5px solid var(--border);color:var(--text-2);background:var(--white);transition:all .15s;}
.angkatan-pill:hover{border-color:var(--blue);color:var(--blue);}
.angkatan-pill.active{background:var(--blue);color:#fff;border-color:var(--blue);}

/* ── Chart Container ─────────────────────────────── */
.chart-box{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:22px;}
.chart-box-head{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px;}
.chart-box-title{font-size:15px;font-weight:700;color:var(--text-1);}
.chart-box-sub{font-size:12px;color:var(--text-2);margin-top:2px;}

/* ── ARIMA Detail ────────────────────────────────── */
.arima-detail{background:#F8FAFF;border:1px solid #DBEAFE;border-radius:10px;padding:14px;margin-top:14px;}
.arima-title{font-size:11px;font-weight:700;color:var(--blue);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;display:flex;align-items:center;gap:6px;}
.arima-row{display:flex;justify-content:space-between;align-items:center;padding:5px 0;border-bottom:1px solid #EFF6FF;font-size:12px;}
.arima-row:last-child{border-bottom:none;}
.arima-key{color:var(--text-2);font-weight:500;}
.arima-val{font-weight:700;color:var(--text-1);font-family:monospace;}

/* ── Metode Cards ────────────────────────────────── */
.metode-card{background:#FAFBFF;border:1px solid var(--border);border-radius:10px;padding:16px;display:flex;gap:12px;align-items:flex-start;}

@media(max-width:992px){
    .col-lg-4-right{margin-top:16px;}
}
@media(max-width:768px){
    .hide-mobile{display:none!important;}
    .pred-val{font-size:24px;}
    .angkatan-pills{gap:4px;}
    .angkatan-pill{font-size:11.5px;padding:5px 12px;}
}
</style>
@endpush

@section('content')

{{-- Banner --}}
@include('components.page-banner', [
    'gradient'  => 'linear-gradient(135deg, #0C1445 0%, #1E3A8A 40%, #2563EB 100%)',
    'icon'      => 'bi-graph-up-arrow',
    'title'     => 'Analitik Tren Akademik',
    'sub'       => 'Time Series Analysis · ARIMA (0,1,1) · Prediksi Performa Per Angkatan',
    'chips'     => [
        ['icon'=>'bi-mortarboard-fill', 'label'=> $angkatanList->count() . ' Angkatan'],
        ['icon'=>'bi-graph-up-arrow',   'label'=>'Metode ARIMA (0,1,1)'],
        ['icon'=>'bi-calendar-check',   'label'=>'Prediksi 1 Semester ke Depan'],
    ],
    'badge_num'   => $angkatanList->count(),
    'badge_label' => "Total\nAngkatan",
])

{{-- ══ RINGKASAN PER ANGKATAN ══ --}}
<div class="section-label">Ringkasan Tren per Angkatan</div>
<div class="card-white tbl-card-v2 mb-4">
    <div class="tbl-head-v2">
        <div>
            <div class="tbl-title-v2">Perbandingan Antar Angkatan</div>
            <div class="tbl-sub-v2">Rata-rata nilai akademik, tren, dan prediksi semester berikutnya</div>
        </div>
    </div>
    <div style="overflow-x:auto;">
        <table class="sum-table">
            <thead>
                <tr>
                    <th>Angkatan</th>
                    <th style="text-align:center;">Rata-rata Saat Ini</th>
                    <th class="hide-mobile" style="text-align:center;">Semester Data</th>
                    <th style="text-align:center;">Tren</th>
                    <th style="text-align:center;">Prediksi Sem Depan</th>
                    <th style="text-align:center;">Perubahan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ringkasan as $r)
                @php $selisih = $r['prediksi'] !== null ? round($r['prediksi'] - $r['rata_saat_ini'], 2) : null; @endphp
                <tr>
                    <td><div style="font-weight:700;font-size:14px;color:var(--text-1);">Angkatan {{ $r['angkatan'] }}</div></td>
                    <td style="text-align:center;">
                        <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                            <span style="font-weight:700;font-size:14px;color:{{ $r['rata_saat_ini'] >= 3.0 ? '#22C55E' : ($r['rata_saat_ini'] >= 2.5 ? '#F59E0B' : '#EF4444') }};">
                                {{ number_format($r['rata_saat_ini'], 2) }}
                            </span>
                            <div class="ipk-mini-bar">
                                <div class="ipk-mini-fill" style="width:{{ min(($r['rata_saat_ini']/4)*100,100) }}%;background:{{ $r['rata_saat_ini'] >= 3.0 ? '#22C55E' : ($r['rata_saat_ini'] >= 2.5 ? '#F59E0B' : '#EF4444') }};"></div>
                            </div>
                        </div>
                    </td>
                    <td class="hide-mobile" style="text-align:center;font-size:13px;color:var(--text-2);">{{ $r['semester_count'] }} semester</td>
                    <td style="text-align:center;">
                        @if($r['trend'] === 'naik')
                            <span class="trend-badge trend-naik"><i class="bi bi-arrow-up-right"></i> Meningkat</span>
                        @elseif($r['trend'] === 'turun')
                            <span class="trend-badge trend-turun"><i class="bi bi-arrow-down-right"></i> Menurun</span>
                        @else
                            <span class="trend-badge trend-stabil"><i class="bi bi-dash"></i> Stabil</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if($r['prediksi'] !== null)
                        <span style="font-weight:700;font-size:14px;color:var(--blue);">{{ number_format($r['prediksi'], 2) }}</span>
                        @else
                        <span style="color:var(--text-3);font-size:12px;">Data kurang</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if($selisih !== null)
                        <span style="font-weight:700;font-size:13px;color:{{ $selisih >= 0 ? '#22C55E' : '#EF4444' }};">
                            {{ $selisih >= 0 ? '+' : '' }}{{ number_format($selisih, 2) }}
                            <i class="bi bi-arrow-{{ $selisih >= 0 ? 'up' : 'down' }}-right" style="font-size:11px;"></i>
                        </span>
                        @else
                        <span style="color:var(--text-3);">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:32px;color:var(--text-3);">
                        <i class="bi bi-bar-chart-line" style="font-size:32px;display:block;margin-bottom:8px;opacity:.3;"></i>
                        Belum ada data nilai untuk dianalisis.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ══ VISUALISASI ══ --}}
<div class="section-label">Visualisasi Tren per Angkatan</div>

{{-- Selector --}}
<div class="angkatan-pills">
    @foreach($angkatanList as $idx => $angkatan)
    <button class="angkatan-pill {{ $idx === 0 ? 'active' : '' }}"
            data-angkatan="{{ $angkatan }}"
            onclick="loadChart({{ $angkatan }}, this)">
        Angkatan {{ $angkatan }}
    </button>
    @endforeach
</div>

{{-- Chart + Sidebar — FIX: align-items:flex-start agar kanan tidak stretch --}}
<div class="row g-3 mb-4" style="align-items:flex-start;">

    {{-- Kiri: Chart --}}
    <div class="col-lg-8">
        <div class="chart-box">
            <div class="chart-box-head">
                <div>
                    <div class="chart-box-title" id="chartTitle">Tren Rata-rata Nilai — Angkatan {{ $angkatanList->first() }}</div>
                    <div class="chart-box-sub">Historis per semester + prediksi ARIMA (0,1,1)</div>
                </div>
                <div id="chartTrendBadge"></div>
            </div>
            <div style="position:relative;height:260px;">
                <canvas id="trendChart"></canvas>
            </div>
            <div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:12px;padding-top:12px;border-top:1px solid var(--border);">
                <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-2);">
                    <div style="width:20px;height:3px;background:#2563EB;border-radius:2px;"></div> Data Historis
                </div>
                <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-2);">
                    <div style="width:8px;height:8px;border-radius:50%;background:#F59E0B;"></div> Titik Prediksi
                </div>
                <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-2);">
                    <div style="width:20px;height:2px;background:transparent;border-top:2px dashed #F59E0B;"></div> Garis Prediksi
                </div>
            </div>
        </div>
    </div>

    {{-- Kanan: Prediksi + ARIMA — FIX: height:auto, tidak ada height:100% --}}
    <div class="col-lg-4">

        {{-- Hasil Prediksi --}}
        <div class="trend-card mb-3">
            <div class="trend-card-head">
                <div>
                    <div class="trend-card-title">Hasil Prediksi</div>
                    <div class="trend-card-sub">Semester berikutnya</div>
                </div>
                <i class="bi bi-cpu-fill" style="font-size:18px;color:var(--blue);opacity:.5;"></i>
            </div>
            <div class="pred-box">
                <div class="pred-label">Prediksi Rata-rata Nilai</div>
                <div class="pred-val" id="predValue">—</div>
                <div class="pred-desc" id="predDesc">Memuat data...</div>
            </div>
            <div style="margin-top:10px;" id="predTrend"></div>
        </div>

        {{-- Detail Kalkulasi --}}
        <div class="trend-card">
            <div class="trend-card-head">
                <div>
                    <div class="trend-card-title">Detail Kalkulasi ARIMA</div>
                    <div class="trend-card-sub">Model ARIMA (0,1,1)</div>
                </div>
                <i class="bi bi-calculator-fill" style="font-size:18px;color:#7C3AED;opacity:.5;"></i>
            </div>
            <div class="arima-detail">
                <div class="arima-title"><i class="bi bi-info-circle-fill"></i> Proses Perhitungan</div>
                <div id="arimaRows">
                    <div style="text-align:center;padding:12px;color:var(--text-3);font-size:12px;">
                        Pilih angkatan untuk melihat detail
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ══ PENJELASAN METODE ══ --}}
<div class="section-label">Tentang Metode</div>
<div class="card-white tbl-card-v2">
    <div class="row g-3">
        <div class="col-md-4">
            <div class="metode-card">
                <div style="width:40px;height:40px;border-radius:10px;background:#EFF6FF;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">
                    <i class="bi bi-1-circle-fill" style="color:#2563EB;"></i>
                </div>
                <div>
                    <div style="font-size:13px;font-weight:700;color:var(--text-1);margin-bottom:4px;">Pengumpulan Data</div>
                    <div style="font-size:12px;color:var(--text-2);line-height:1.6;">
                        Data nilai akademik diambil dari basis data, dikelompokkan berdasarkan angkatan dan semester, lalu dihitung rata-ratanya membentuk data time series.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metode-card">
                <div style="width:40px;height:40px;border-radius:10px;background:#F5F3FF;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">
                    <i class="bi bi-2-circle-fill" style="color:#7C3AED;"></i>
                </div>
                <div>
                    <div style="font-size:13px;font-weight:700;color:var(--text-1);margin-bottom:4px;">Differencing (d=1)</div>
                    <div style="font-size:12px;color:var(--text-2);line-height:1.6;">
                        Satu kali differencing dilakukan untuk menstabilkan data time series dengan menghitung selisih nilai antar semester yang berurutan.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metode-card">
                <div style="width:40px;height:40px;border-radius:10px;background:#FFF7ED;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">
                    <i class="bi bi-3-circle-fill" style="color:#EA580C;"></i>
                </div>
                <div>
                    <div style="font-size:13px;font-weight:700;color:var(--text-1);margin-bottom:4px;">Prediksi ARIMA(0,1,1)</div>
                    <div style="font-size:12px;color:var(--text-2);line-height:1.6;">
                        Rata-rata perubahan dari hasil differencing ditambahkan ke nilai terakhir untuk menghasilkan prediksi rata-rata nilai semester berikutnya.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
var currentChart = null;
var trendDataAll = @json($trendData);

function loadChart(angkatan, btn) {
    document.querySelectorAll('.angkatan-pill').forEach(p => p.classList.remove('active'));
    if (btn) btn.classList.add('active');

    var data = trendDataAll[angkatan];
    if (!data || !data.historis) return;

    var historis = data.historis;
    var prediksi = data.prediksi;
    var diff     = data.differencing || [];
    var histKeys = Object.keys(historis);
    var histVals = Object.values(historis);
    var semTerakhir = Math.max(...histKeys.map(Number));

    // Labels: semester historis + prediksi
    var labels = histKeys.map(s => 'Semester ' + s);
    if (prediksi !== null) labels.push('Sem ' + (semTerakhir + 1) + ' (Prediksi)');

    // Dataset historis: nilai + null di akhir
    var dataHistoris = [...histVals];
    if (prediksi !== null) dataHistoris.push(null);

    // Dataset prediksi: null sampai titik terakhir historis, lalu nilai prediksi
    var dataPrediksi = Array(histVals.length - 1).fill(null);
    dataPrediksi.push(histVals[histVals.length - 1]); // sambung dari nilai terakhir
    if (prediksi !== null) dataPrediksi.push(prediksi);

    if (currentChart) currentChart.destroy();

    var ctx = document.getElementById('trendChart').getContext('2d');
    currentChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Historis',
                    data: dataHistoris,
                    borderColor: '#2563EB',
                    backgroundColor: 'rgba(37,99,235,.07)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 5,
                    pointBackgroundColor: '#2563EB',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    borderWidth: 2.5,
                    spanGaps: false,
                },
                {
                    label: 'Prediksi',
                    data: dataPrediksi,
                    borderColor: '#F59E0B',
                    borderDash: [6, 3],
                    tension: 0,
                    pointRadius: dataPrediksi.map((v, i) => i === dataPrediksi.length - 1 ? 8 : 0),
                    pointBackgroundColor: '#F59E0B',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    borderWidth: 2,
                    spanGaps: false,
                    fill: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A', padding: 12, cornerRadius: 8,
                    callbacks: {
                        label: function(c) {
                            if (c.raw === null) return null;
                            return (c.datasetIndex === 1 ? ' Prediksi: ' : ' Historis: ') + Number(c.raw).toFixed(2);
                        }
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748B' } },
                y: {
                    min: 0, max: 4,
                    grid: { color: '#F8FAFC' },
                    ticks: { stepSize: 0.5, font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748B' },
                    border: { display: false }
                }
            }
        }
    });

    // Update title
    document.getElementById('chartTitle').textContent = 'Tren Rata-rata Nilai — Angkatan ' + angkatan;

    // Update prediksi info
    if (prediksi !== null) {
        document.getElementById('predValue').textContent = Number(prediksi).toFixed(2);
        var lastVal = histVals[histVals.length - 1];
        var selisih = (prediksi - lastVal).toFixed(2);
        var naik    = prediksi >= lastVal;
        document.getElementById('predDesc').innerHTML =
            'Prediksi Semester ' + (semTerakhir + 1) +
            ' &nbsp;·&nbsp; <span style="color:' + (naik ? '#22C55E' : '#EF4444') + ';font-weight:700;">' +
            (naik ? '▲ +' : '▼ ') + selisih + ' dari semester sebelumnya</span>';

        var trendVal = histVals[histVals.length-1] - histVals[0];
        var badge = trendVal > 0.05
            ? '<span class="trend-badge trend-naik"><i class="bi bi-arrow-up-right"></i> Tren Meningkat</span>'
            : trendVal < -0.05
            ? '<span class="trend-badge trend-turun"><i class="bi bi-arrow-down-right"></i> Tren Menurun</span>'
            : '<span class="trend-badge trend-stabil"><i class="bi bi-dash"></i> Tren Stabil</span>';
        document.getElementById('predTrend').innerHTML = badge;
        document.getElementById('chartTrendBadge').innerHTML = badge;
    } else {
        document.getElementById('predValue').textContent = '—';
        document.getElementById('predDesc').textContent = 'Data belum cukup untuk prediksi';
        document.getElementById('predTrend').innerHTML = '';
        document.getElementById('chartTrendBadge').innerHTML = '';
    }

    // Update ARIMA detail
    var html = '';
    if (diff.length > 0) {
        html += '<div style="font-size:10.5px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Differencing (d=1)</div>';
        diff.forEach(function(d, i) {
            html += '<div class="arima-row">' +
                '<span class="arima-key">Sem ' + histKeys[i] + ' → ' + histKeys[i+1] + '</span>' +
                '<span class="arima-val" style="color:' + (d >= 0 ? '#22C55E' : '#EF4444') + ';">' +
                (d >= 0 ? '+' : '') + d.toFixed(4) + '</span></div>';
        });

        var meanDiff = diff.reduce((a,b) => a+b, 0) / diff.length;
        html += '<div style="font-size:10.5px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.5px;margin:10px 0 6px;">Kalkulasi Prediksi</div>';
        html += '<div class="arima-row"><span class="arima-key">Mean Diff</span><span class="arima-val">' + meanDiff.toFixed(4) + '</span></div>';
        html += '<div class="arima-row"><span class="arima-key">Nilai Terakhir</span><span class="arima-val">' + histVals[histVals.length-1].toFixed(2) + '</span></div>';
        html += '<div class="arima-row"><span class="arima-key">Formula</span><span class="arima-val" style="font-size:10px;">' +
            histVals[histVals.length-1].toFixed(2) + ' + (' + meanDiff.toFixed(4) + ')</span></div>';
        if (prediksi !== null) {
            html += '<div class="arima-row" style="background:#EFF6FF;border-radius:6px;padding:5px 8px;margin-top:4px;">' +
                '<span class="arima-key" style="color:var(--blue);font-weight:700;">Hasil Prediksi</span>' +
                '<span class="arima-val" style="color:var(--blue);font-size:14px;">' + Number(prediksi).toFixed(2) + '</span></div>';
        }
    } else {
        html = '<div style="text-align:center;padding:12px;color:var(--text-3);font-size:12px;">Data differencing belum tersedia</div>';
    }
    document.getElementById('arimaRows').innerHTML = html;
}

window.addEventListener('DOMContentLoaded', function() {
    var first = {{ $angkatanList->first() ?? 'null' }};
    if (first) loadChart(first, document.querySelector('.angkatan-pill'));
});
</script>
@endpush