@extends('layouts.admin')
@section('title', 'Analitik Tren Akademik')
@section('page-title', 'Analitik Tren Akademik')
@section('page-sub', 'Analisis tren dan prediksi performa akademik per angkatan')

@push('styles')
<style>
.trend-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;}
.trend-card-head{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;}
.trend-card-title{font-size:14px;font-weight:700;color:var(--text-1);}
.trend-card-sub{font-size:11.5px;color:var(--text-2);margin-top:2px;}
.pred-box{background:#F8FAFF;border:1.5px solid #BFDBFE;border-radius:10px;padding:14px 16px;margin-top:14px;}
.pred-label{font-size:11px;font-weight:700;color:var(--blue);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;}
.pred-val{font-size:32px;font-weight:800;color:var(--blue);letter-spacing:-1px;line-height:1;}
.pred-desc{font-size:11.5px;color:var(--text-2);margin-top:6px;line-height:1.5;}
.interpretasi-box{margin-top:10px;font-size:12px;line-height:1.7;padding:10px 12px;background:#F8FAFF;border-radius:8px;border:1px solid #DBEAFE;min-height:20px;}
.trend-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;}
.trend-naik{background:#DCFCE7;color:#15803D;}
.trend-turun{background:#FEE2E2;color:#991B1B;}
.trend-stabil{background:#F1F5F9;color:#475569;}
.sum-table{width:100%;border-collapse:collapse;}
.sum-table thead th{font-size:11px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.6px;padding:8px 14px;border-bottom:1.5px solid var(--border);background:#FAFBFF;white-space:nowrap;}
.sum-table tbody tr{border-bottom:1px solid #F8FAFC;transition:background .12s;}
.sum-table tbody tr:last-child{border-bottom:none;}
.sum-table tbody tr:hover{background:#F8FAFF;}
.sum-table tbody td{padding:12px 14px;font-size:13px;vertical-align:middle;}
.ipk-mini-bar{width:80px;height:5px;background:#F1F5F9;border-radius:3px;overflow:hidden;display:inline-block;vertical-align:middle;margin-left:6px;}
.ipk-mini-fill{height:100%;border-radius:3px;}
.chart-box{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:22px;}
.chart-box-head{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px;}
.chart-box-title{font-size:15px;font-weight:700;color:var(--text-1);}
.chart-box-sub{font-size:12px;color:var(--text-2);margin-top:2px;}
.arima-detail{background:#F8FAFF;border:1px solid #DBEAFE;border-radius:10px;padding:14px;margin-top:14px;}
.arima-title{font-size:11px;font-weight:700;color:var(--blue);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;display:flex;align-items:center;gap:6px;}
.arima-row{display:flex;justify-content:space-between;align-items:center;padding:5px 0;border-bottom:1px solid #EFF6FF;font-size:12px;}
.arima-row:last-child{border-bottom:none;}
.arima-key{color:var(--text-2);font-weight:500;}
.arima-val{font-weight:700;color:var(--text-1);font-family:monospace;}
.metode-card{background:#FAFBFF;border:1px solid var(--border);border-radius:10px;padding:14px;display:flex;gap:10px;align-items:flex-start;}

/* Evaluasi section */
.eval-metric-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;}
.eval-metric{background:#F8FAFF;border:1px solid #DBEAFE;border-radius:10px;padding:14px;text-align:center;}
.eval-metric-label{font-size:10.5px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.6px;margin-bottom:4px;}
.eval-metric-val{font-size:26px;font-weight:800;color:var(--blue);letter-spacing:-1px;line-height:1.1;}
.eval-metric-desc{font-size:11px;color:var(--text-3);margin-top:3px;}
.eval-badge{display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:11.5px;font-weight:700;}
.eval-badge-green{background:#DCFCE7;color:#15803D;}
.eval-badge-blue{background:#DBEAFE;color:#1D4ED8;}
.eval-badge-yellow{background:#FEF9C3;color:#92400E;}
.eval-badge-red{background:#FEE2E2;color:#991B1B;}
.eval-table{width:100%;border-collapse:collapse;font-size:12.5px;}
.eval-table thead th{font-size:10.5px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.5px;padding:7px 10px;border-bottom:1.5px solid var(--border);background:#FAFBFF;white-space:nowrap;}
.eval-table tbody tr{border-bottom:1px solid #F8FAFC;transition:background .1s;}
.eval-table tbody tr:last-child{border-bottom:none;}
.eval-table tbody tr:hover{background:#F8FAFF;}
.eval-table tbody td{padding:9px 10px;vertical-align:middle;}

/* Filter row */
.filter-row{display:flex;align-items:center;gap:12px;margin-bottom:16px;flex-wrap:wrap;}
.filter-row label{font-size:13px;font-weight:600;color:var(--text-2);}
.filter-select{padding:7px 32px 7px 12px;border:1.5px solid var(--border);border-radius:9px;font-size:13px;font-weight:600;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-1);background:var(--white);cursor:pointer;outline:none;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;min-width:160px;transition:border-color .15s;}
.filter-select:focus{border-color:var(--blue);}

@media(max-width:768px){
    .hide-mobile{display:none!important;}
    .pred-val{font-size:24px;}
    .eval-metric-grid{grid-template-columns:1fr;}
}
</style>
@endpush

@section('content')

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

{{-- ══ RINGKASAN ══ --}}
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
                @php
                    $selisih = $r['prediksi'] !== null ? round($r['prediksi'] - $r['rata_saat_ini'], 2) : null;
                    $color   = $r['rata_saat_ini'] >= 3.0 ? '#22C55E' : ($r['rata_saat_ini'] >= 2.5 ? '#F59E0B' : '#EF4444');
                @endphp
                <tr>
                    <td><div style="font-weight:700;font-size:14px;color:var(--text-1);">Angkatan {{ $r['angkatan'] }}</div></td>
                    <td style="text-align:center;">
                        <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                            <span style="font-weight:700;font-size:14px;color:{{ $color }};">
                                {{ number_format($r['rata_saat_ini'], 2) }}
                            </span>
                            <div class="ipk-mini-bar">
                                <div class="ipk-mini-fill" style="width:{{ min(($r['rata_saat_ini']/4)*100, 100) }}%;background:{{ $color }};"></div>
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

<div class="filter-row">
    <label for="angkatanSelect"><i class="bi bi-funnel-fill" style="color:var(--blue);"></i> Filter Angkatan:</label>
    <select id="angkatanSelect" class="filter-select" onchange="loadChart(this.value)">
        @foreach($angkatanList as $angkatan)
        <option value="{{ $angkatan }}">Angkatan {{ $angkatan }}</option>
        @endforeach
    </select>
</div>

{{-- ══ LAYOUT: Kiri=Chart+Evaluasi+Metode | Kanan=Prediksi+Kalkulasi ══ --}}
<div class="row g-3 mb-4" style="align-items:flex-start;">

    {{-- KIRI --}}
    <div class="col-lg-8 d-flex flex-column gap-3">

        {{-- Chart --}}
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
                <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-2);">
                    <div style="width:8px;height:8px;border-radius:50%;background:#EF4444;border:2px solid #fff;box-shadow:0 0 0 1.5px #EF4444;"></div> Data Uji
                </div>
            </div>
        </div>

        {{-- Rata-rata IPK per Semester --}}
        <div class="trend-card" id="ipkTableSection" style="display:none;">
            <div class="trend-card-head">
                <div>
                    <div class="trend-card-title">Rata-rata IPK per Semester</div>
                    <div class="trend-card-sub">IPK aktual angkatan vs prediksi ARIMA (walk-forward)</div>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table class="eval-table">
                    <thead>
                        <tr>
                            <th>Semester</th>
                            <th style="text-align:right;">Rata-rata IPK Real</th>
                            <th style="text-align:right;">Prediksi ARIMA</th>
                            <th style="text-align:right;">Selisih</th>
                            <th style="text-align:center;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="ipkTableBody">
                        <tr><td colspan="5" style="text-align:center;padding:16px;color:var(--text-3);">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Evaluasi Akurasi ARIMA --}}
        <div class="trend-card" id="evaluasiSection" style="display:none;">
            <div class="trend-card-head">
                <div>
                    <div class="trend-card-title">Evaluasi Akurasi ARIMA</div>
                    <div class="trend-card-sub">Train: semua kecuali semester terakhir · Test: semester terakhir</div>
                </div>
                <span id="evalBadge"></span>
            </div>

            <div class="eval-metric-grid">
                <div class="eval-metric">
                    <div class="eval-metric-label">MAE</div>
                    <div class="eval-metric-val" id="evalMAE">—</div>
                    <div class="eval-metric-desc">Mean Absolute Error</div>
                </div>
                <div class="eval-metric">
                    <div class="eval-metric-label">MAPE</div>
                    <div class="eval-metric-val" id="evalMAPE">—</div>
                    <div class="eval-metric-desc">Mean Absolute Percentage Error</div>
                </div>
            </div>

            <div style="font-size:11px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">
                Detail Prediksi Walk-Forward (Semester 2 s.d. Terakhir)
            </div>
            <div style="overflow-x:auto;">
                <table class="eval-table">
                    <thead>
                        <tr>
                            <th>Semester</th>
                            <th style="text-align:right;">Nilai Aktual</th>
                            <th style="text-align:right;">Nilai Prediksi</th>
                            <th style="text-align:right;">Selisih</th>
                            <th style="text-align:right;">% Error</th>
                        </tr>
                    </thead>
                    <tbody id="evalTableBody">
                        <tr><td colspan="5" style="text-align:center;padding:16px;color:var(--text-3);">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tentang Metode
        <div class="card-white tbl-card-v2">
            <div class="tbl-head-v2" style="margin-bottom:12px;">
                <div>
                    <div class="tbl-title-v2">Tentang Metode ARIMA (0,1,1)</div>
                    <div class="tbl-sub-v2">Tahapan analisis tren dan prediksi nilai akademik</div>
                </div>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="metode-card">
                        <div style="width:36px;height:36px;border-radius:10px;background:#EFF6FF;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">
                            <i class="bi bi-1-circle-fill" style="color:#2563EB;"></i>
                        </div>
                        <div>
                            <div style="font-size:12.5px;font-weight:700;color:var(--text-1);margin-bottom:3px;">Pengumpulan Data</div>
                            <div style="font-size:11.5px;color:var(--text-2);line-height:1.6;">
                                IPK per semester dihitung dari bobot grade (A=4.0, B+=3.5 … E=0) × SKS, dirata-ratakan per mahasiswa lalu per angkatan. Min. 3 semester untuk prediksi.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metode-card">
                        <div style="width:36px;height:36px;border-radius:10px;background:#F5F3FF;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">
                            <i class="bi bi-2-circle-fill" style="color:#7C3AED;"></i>
                        </div>
                        <div>
                            <div style="font-size:12.5px;font-weight:700;color:var(--text-1);margin-bottom:3px;">Differencing (d=1)</div>
                            <div style="font-size:11.5px;color:var(--text-2);line-height:1.6;">
                                Satu kali differencing untuk menstabilkan data time series dengan menghitung selisih nilai antar semester berurutan.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metode-card">
                        <div style="width:36px;height:36px;border-radius:10px;background:#FFF7ED;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">
                            <i class="bi bi-3-circle-fill" style="color:#EA580C;"></i>
                        </div>
                        <div>
                            <div style="font-size:12.5px;font-weight:700;color:var(--text-1);margin-bottom:3px;">Prediksi ARIMA(0,1,1)</div>
                            <div style="font-size:11.5px;color:var(--text-2);line-height:1.6;">
                                Rata-rata perubahan differencing ditambahkan ke nilai terakhir untuk menghasilkan prediksi semester berikutnya.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>

    {{-- KANAN --}}
    <div class="col-lg-4 d-flex flex-column gap-3">

        {{-- Hasil Prediksi --}}
        <div class="trend-card">
            <div class="trend-card-head">
                <div>
                    <div class="trend-card-title">Hasil Prediksi</div>
                    <div class="trend-card-sub">Semester berikutnya</div>
                </div>
                <i class="bi bi-cpu-fill" style="font-size:18px;color:var(--blue);opacity:.5;"></i>
            </div>
            <div class="pred-box">
                <div class="pred-label">Prediksi IPK</div>
                <div class="pred-val" id="predValue">—</div>
                <div class="pred-desc" id="predDesc">Memuat data...</div>
            </div>
            <div style="margin-top:10px;" id="predTrend"></div>
            <div class="interpretasi-box" id="predInterpretasi"></div>
        </div>

        {{-- Detail Kalkulasi ARIMA --}}
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

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
var currentChart = null;
var trendDataAll = @json($trendData);

function loadChart(angkatan) {
    var data = trendDataAll[angkatan];
    if (!data || !data.historis) return;

    var historis    = data.historis;
    var prediksi    = data.prediksi;
    var diff        = data.differencing || [];
    var evaluasi    = data.evaluasi || null;
    var walkforward = data.walkforward || [];

    var histKeys    = Object.keys(historis).map(Number);
    var histVals    = Object.values(historis).map(Number);
    var semTerakhir = Math.max(...histKeys);
    var n           = histVals.length;

    // Labels
    var labels = histKeys.map(s => 'Semester ' + s);
    if (prediksi !== null) labels.push('Sem ' + (semTerakhir + 1) + ' (Prediksi)');

    // Dataset 1: Historis (biru)
    var dataHistoris = [...histVals];
    if (prediksi !== null) dataHistoris.push(null);

    // Dataset 2: Prediksi (oranye dashed) — sambung dari titik terakhir ke prediksi
    var dataPrediksi = Array(n - 1).fill(null);
    dataPrediksi.push(histVals[n - 1]);
    if (prediksi !== null) dataPrediksi.push(prediksi);

    // Dataset 3: Data Uji (merah, titik saja) — aktual semester terakhir sebagai titik evaluasi
    var dataUji = Array(n - 1).fill(null);
    dataUji.push(histVals[n - 1]);
    if (prediksi !== null) dataUji.push(null);

    if (currentChart) currentChart.destroy();

    var ctx = document.getElementById('trendChart').getContext('2d');
    currentChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Data Historis',
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
                    pointRadius: dataPrediksi.map((v, i) => (v !== null && i >= n - 1) ? 8 : 0),
                    pointBackgroundColor: '#F59E0B',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    borderWidth: 2,
                    spanGaps: false,
                    fill: false,
                },
                {
                    label: 'Data Uji',
                    data: dataUji,
                    borderColor: '#EF4444',
                    backgroundColor: '#EF4444',
                    pointRadius: dataUji.map(v => v !== null ? 9 : 0),
                    pointBackgroundColor: '#EF4444',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2.5,
                    borderWidth: 0,
                    spanGaps: false,
                    fill: false,
                    showLine: false,
                },
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
                        title: function(items) {
                            return items[0]?.label || '';
                        },
                        label: function(c) {
                            if (c.raw === null) return null;
                            var labels = ['Data Historis', 'Prediksi', 'Data Uji'];
                            return ' ' + (labels[c.datasetIndex] || '') + ': ' + Number(c.raw).toFixed(2);
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748B' }
                },
                y: {
                    min: 0, max: 4,
                    grid: { color: '#F8FAFC' },
                    ticks: {
                        stepSize: 0.5,
                        font: { family: 'Plus Jakarta Sans', size: 11 },
                        color: '#64748B'
                    },
                    border: { display: false }
                }
            }
        }
    });

    // Update judul chart
    document.getElementById('chartTitle').textContent = 'Tren Rata-rata IPK — Angkatan ' + angkatan;

    // Hitung mean diff dan tren
    var meanDiff = diff.length > 0 ? diff.reduce((a, b) => a + b, 0) / diff.length : 0;
    var absMean  = Math.abs(meanDiff);

    if (prediksi !== null) {
        document.getElementById('predValue').textContent = Number(prediksi).toFixed(2);

        var lastVal  = histVals[n - 1];
        var selisih  = prediksi - lastVal;
        var naik     = selisih >= 0;

        document.getElementById('predDesc').innerHTML =
            'Semester ' + (semTerakhir + 1) + ' diprediksi <strong>' + Number(prediksi).toFixed(2) + '</strong>' +
            ' &nbsp;<span style="color:' + (naik ? '#22C55E' : '#EF4444') + ';font-weight:700;">' +
            (naik ? '▲ +' : '▼ ') + Math.abs(selisih).toFixed(4) + ' dari semester sebelumnya</span>';

        // Badge tren
        var trendLabel, trendClass;
        if (absMean < 0.02)    { trendLabel = 'Tren Stabil';    trendClass = 'trend-stabil'; }
        else if (meanDiff > 0) { trendLabel = 'Tren Meningkat'; trendClass = 'trend-naik';   }
        else                   { trendLabel = 'Tren Menurun';   trendClass = 'trend-turun';  }

        var badge = '<span class="trend-badge ' + trendClass + '">' +
            '<i class="bi bi-' + (absMean < 0.02 ? 'dash' : (meanDiff > 0 ? 'arrow-up-right' : 'arrow-down-right')) + '"></i> ' + trendLabel +
            '</span>';
        document.getElementById('predTrend').innerHTML = badge;
        document.getElementById('chartTrendBadge').innerHTML = badge;

        // Interpretasi informatif
        var trendDesc = absMean < 0.02 ? 'Stabil' : (meanDiff > 0 ? 'Naik' : 'Menurun');

        var html = '<div style="margin-bottom:5px;"><strong>Tren ' + trendDesc +
            '</strong> — rata-rata perubahan IPK <strong style="color:' + (meanDiff >= 0 ? '#16A34A' : '#EF4444') + ';">' +
            (meanDiff >= 0 ? '+' : '') + meanDiff.toFixed(4) + ' per semester</strong></div>';

        html += '<div style="margin-bottom:5px;">Semester berikutnya diprediksi IPK <strong>' + Number(prediksi).toFixed(2) + '</strong>' +
            ' (' + (naik ? 'naik' : 'turun') + ' ' + Math.abs(selisih).toFixed(4) + ' dari semester sebelumnya)</div>';

        // Rekomendasi berdasarkan tren (skala IPK 0-4)
        var rekomendasi;
        if (absMean < 0.02) {
            rekomendasi = '📈 Performa konsisten dan stabil.';
        } else if (meanDiff > 0) {
            rekomendasi = '✅ Performa meningkat. Pertahankan kualitas pembelajaran.';
        } else if (absMean >= 0.1) {
            rekomendasi = '⚠️ Perlu perhatian. Tren penurunan signifikan terdeteksi.';
        } else {
            rekomendasi = '📊 Tren sedikit menurun, masih dalam batas wajar.';
        }
        html += '<div>' + rekomendasi + '</div>';

        var intEl = document.getElementById('predInterpretasi');
        if (intEl) intEl.innerHTML = html;

    } else {
        document.getElementById('predValue').textContent = '—';
        document.getElementById('predDesc').textContent = 'Data belum cukup (min. 3 semester)';
        document.getElementById('predTrend').innerHTML  = '';
        document.getElementById('chartTrendBadge').innerHTML = '';
        var intEl = document.getElementById('predInterpretasi');
        if (intEl) intEl.innerHTML = '<span style="color:var(--text-3);">Tambahkan data minimal 3 semester untuk melihat prediksi dan interpretasi.</span>';
    }

    // ARIMA detail
    var html = '';
    if (diff.length > 0) {
        html += '<div style="font-size:10.5px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Differencing (d=1)</div>';
        diff.forEach(function(d, i) {
            html += '<div class="arima-row">' +
                '<span class="arima-key">Sem ' + histKeys[i] + ' → ' + histKeys[i+1] + '</span>' +
                '<span class="arima-val" style="color:' + (d >= 0 ? '#22C55E' : '#EF4444') + ';">' +
                (d >= 0 ? '+' : '') + d.toFixed(4) + '</span></div>';
        });
        html += '<div style="font-size:10.5px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.5px;margin:10px 0 6px;">Kalkulasi Prediksi</div>';
        html += '<div class="arima-row"><span class="arima-key">Mean Diff</span><span class="arima-val">' + meanDiff.toFixed(4) + '</span></div>';
        html += '<div class="arima-row"><span class="arima-key">Nilai Terakhir</span><span class="arima-val">' + histVals[n-1].toFixed(2) + '</span></div>';
        html += '<div class="arima-row"><span class="arima-key">Formula</span><span class="arima-val" style="font-size:10px;">' +
            histVals[n-1].toFixed(2) + (meanDiff >= 0 ? ' + ' : ' ') + '(' + meanDiff.toFixed(4) + ')</span></div>';
        if (prediksi !== null) {
            html += '<div class="arima-row" style="background:#EFF6FF;border-radius:6px;padding:5px 8px;margin-top:4px;">' +
                '<span class="arima-key" style="color:var(--blue);font-weight:700;">Hasil Prediksi</span>' +
                '<span class="arima-val" style="color:var(--blue);font-size:14px;">' + Number(prediksi).toFixed(2) + '</span></div>';
        }
    } else {
        html = '<div style="text-align:center;padding:12px;color:var(--text-3);font-size:12px;">Data differencing belum tersedia</div>';
    }
    document.getElementById('arimaRows').innerHTML = html;

    // Tabel IPK per semester
    updateIpkTable(histKeys, histVals, walkforward, semTerakhir, evaluasi);

    // Evaluasi section
    updateEvaluasi(evaluasi, walkforward);
}

function updateIpkTable(histKeys, histVals, walkforward, semTerakhir, evaluasi) {
    var section = document.getElementById('ipkTableSection');
    if (!histKeys.length) { section.style.display = 'none'; return; }
    section.style.display = 'block';

    // Map semester → prediksi walk-forward
    var wfMap = {};
    (walkforward || []).forEach(function(row) { wfMap[row.semester] = row.prediksi; });

    var semLast = evaluasi ? evaluasi.sem_last : null;

    var rows = histKeys.map(function(sem, idx) {
        var aktual  = histVals[idx];
        var pred    = (wfMap[sem] !== undefined) ? wfMap[sem] : null;
        var selisih = (pred !== null) ? (aktual - pred) : null;

        // Warna selisih berdasarkan rentang
        var selColor = '#64748B';
        if (selisih !== null) {
            var absS = Math.abs(selisih);
            if (absS <= 0.1)      selColor = '#16A34A';
            else if (absS <= 0.2) selColor = '#D97706';
            else                   selColor = '#EF4444';
        }

        var ipkColor = aktual >= 3.0 ? '#16A34A' : (aktual >= 2.5 ? '#D97706' : '#EF4444');
        var isLast   = (sem === semLast || sem === semTerakhir && semLast === null);

        var keterangan = isLast
            ? '<span style="font-size:10px;background:#FEF3C7;color:#92400E;padding:2px 7px;border-radius:4px;font-weight:700;">Data Uji</span>'
            : '<span style="font-size:10px;background:#EFF6FF;color:#1D4ED8;padding:2px 7px;border-radius:4px;font-weight:600;">Data Historis</span>';

        return '<tr style="' + (isLast ? 'background:#FFF8F0;' : '') + '">' +
            '<td><strong>Semester ' + sem + '</strong></td>' +
            '<td style="text-align:right;font-weight:700;color:' + ipkColor + ';">' + aktual.toFixed(2) + '</td>' +
            '<td style="text-align:right;color:var(--blue);font-weight:600;">' +
            (pred !== null ? pred.toFixed(2) : '<span style="color:var(--text-3);">—</span>') + '</td>' +
            '<td style="text-align:right;font-weight:700;color:' + selColor + ';">' +
            (selisih !== null
                ? (selisih >= 0 ? '+' : '') + selisih.toFixed(2)
                : '<span style="color:var(--text-3);">—</span>') + '</td>' +
            '<td style="text-align:center;">' + keterangan + '</td>' +
            '</tr>';
    }).join('');

    document.getElementById('ipkTableBody').innerHTML = rows;
}

function updateEvaluasi(evaluasi, walkforward) {
    var section = document.getElementById('evaluasiSection');
    if (!evaluasi || !walkforward || walkforward.length === 0) {
        section.style.display = 'none';
        return;
    }
    section.style.display = 'block';

    document.getElementById('evalMAE').textContent  = Number(evaluasi.mae).toFixed(2);
    document.getElementById('evalMAPE').textContent = Number(evaluasi.mape).toFixed(2) + '%';

    // Badge interpretasi
    var mape      = evaluasi.mape;
    var badgeText, badgeClass;
    if (mape < 10)       { badgeText = 'Sangat Akurat'; badgeClass = 'eval-badge-green'; }
    else if (mape < 20)  { badgeText = 'Akurat';        badgeClass = 'eval-badge-blue';  }
    else if (mape < 50)  { badgeText = 'Cukup Akurat';  badgeClass = 'eval-badge-yellow';}
    else                  { badgeText = 'Kurang Akurat'; badgeClass = 'eval-badge-red';   }
    document.getElementById('evalBadge').innerHTML =
        '<span class="eval-badge ' + badgeClass + '"><i class="bi bi-speedometer2"></i> ' + badgeText + '</span>';

    // Tabel walkforward
    var tbody = document.getElementById('evalTableBody');
    tbody.innerHTML = walkforward.map(function(row) {
        var isLast    = (row.semester === evaluasi.sem_last);
        var rowStyle  = isLast ? 'background:#FFF8F0;' : '';
        var selColor  = row.selisih >= 0 ? '#16A34A' : '#EF4444';
        return '<tr style="' + rowStyle + '">' +
            '<td><strong>Semester ' + row.semester + '</strong>' +
            (isLast ? ' <span style="font-size:10px;background:#FEF3C7;color:#92400E;padding:1px 6px;border-radius:4px;font-weight:600;">Data Uji</span>' : '') +
            '</td>' +
            '<td style="text-align:right;font-weight:600;">' + Number(row.aktual).toFixed(2) + '</td>' +
            '<td style="text-align:right;color:var(--blue);font-weight:600;">' + Number(row.prediksi).toFixed(2) + '</td>' +
            '<td style="text-align:right;font-weight:700;color:' + selColor + ';">' +
            (row.selisih >= 0 ? '+' : '') + Number(row.selisih).toFixed(2) + '</td>' +
            '<td style="text-align:right;color:var(--text-2);">' + Number(row.pct_error).toFixed(2) + '%</td>' +
            '</tr>';
    }).join('');
}

window.addEventListener('DOMContentLoaded', function() {
    var first = {{ $angkatanList->first() ?? 'null' }};
    if (first) loadChart(first);
});
</script>
@endpush
