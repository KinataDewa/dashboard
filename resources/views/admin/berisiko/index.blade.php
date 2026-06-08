@extends('layouts.admin')
@section('title', 'Mahasiswa Berisiko')
@section('page-title', 'Mahasiswa Berisiko')
@section('page-sub', 'Rekap mahasiswa berisiko akademik · Bahan Rapat Akhir Semester')

@push('styles')
<style>
/* ── Summary Cards ───────────────────────────── */
.sum-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 24px; }
.sum-card  { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
.sum-card-bar { height: 3px; }
.sum-card-body { padding: 14px 16px; }
.sum-card-val  { font-size: 30px; font-weight: 800; line-height: 1; letter-spacing: -1px; }
.sum-card-lbl  { font-size: 12px; color: var(--text-2); margin-top: 4px; font-weight: 500; }
.sum-card-pct  { font-size: 11px; font-weight: 700; margin-top: 6px; display: inline-flex; align-items: center; gap: 3px; padding: 2px 8px; border-radius: 99px; }
.sum-card-detail { font-size: 10.5px; color: var(--text-3); margin-top: 6px; line-height: 1.7; }

/* ── Filter Bar ──────────────────────────────── */
.filter-bar {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.filter-label { font-size: 12px; font-weight: 700; color: var(--text-2); white-space: nowrap; }
.filter-select {
    padding: 8px 12px;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    font-size: 13px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-1);
    background: var(--white);
    outline: none;
    cursor: pointer;
    transition: border-color .15s;
}
.filter-select:focus { border-color: var(--blue); }

.jenis-pills { display: flex; gap: 6px; flex-wrap: wrap; }
.jenis-pill {
    padding: 5px 10px;
    border-radius: 99px;
    font-size: 11.5px;
    font-weight: 600;
    border: 1.5px solid var(--border);
    color: var(--text-2);
    background: var(--white);
    cursor: pointer;
    text-decoration: none;
    transition: all .15s;
    white-space: nowrap;
}
.jenis-pill:hover { border-color: var(--blue); color: var(--blue); }
.jenis-pill.active-semua      { background: #EF4444; color: #fff; border-color: #EF4444; }
.jenis-pill.active-alpha      { background: #EA580C; color: #fff; border-color: #EA580C; }
.jenis-pill.active-nilai-e    { background: #991B1B; color: #fff; border-color: #991B1B; }
.jenis-pill.active-nilai-d    { background: #B45309; color: #fff; border-color: #B45309; }
.jenis-pill.active-ips-rendah { background: #7C3AED; color: #fff; border-color: #7C3AED; }

.btn-print {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--blue);
    color: #fff;
    border: none;
    cursor: pointer;
    transition: all .15s;
    text-decoration: none;
}
.btn-print:hover { background: #1D4ED8; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,.25); }

/* ── Tabel ───────────────────────────────────── */
.risk-table { width: 100%; border-collapse: collapse; }
.risk-table thead th {
    font-size: 11px; font-weight: 700; color: var(--text-3);
    text-transform: uppercase; letter-spacing: .6px;
    padding: 10px 14px; border-bottom: 1.5px solid var(--border);
    background: #FAFBFF; white-space: nowrap;
}
.risk-table tbody tr { border-bottom: 1px solid #F8FAFC; transition: background .1s; }
.risk-table tbody tr:last-child { border-bottom: none; }
.risk-table tbody tr:hover { background: #FAFBFF; }
.risk-table tbody td { padding: 12px 14px; vertical-align: middle; font-size: 13px; }

/* ── Kategori badge ──────────────────────────── */
.badge-risiko { display: inline-flex; align-items: center; gap: 2px; border-radius: 99px; padding: 2px 7px; font-size: 10.5px; font-weight: 700; white-space: nowrap; margin: 1px; }

/* ── Nomor urut ──────────────────────────────── */
.no-circle {
    width: 28px; height: 28px; border-radius: 50%;
    background: #F1F5F9; color: var(--text-2);
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700;
}

/* ── Avatar ──────────────────────────────────── */
.avatar {
    width: 34px; height: 34px; border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700; color: #fff;
    flex-shrink: 0;
}

/* ── Alpha bar ───────────────────────────────── */
.alpha-bar-wrap { width: 60px; height: 4px; background: #F1F5F9; border-radius: 2px; overflow: hidden; margin-top: 4px; }
.alpha-bar-fill { height: 100%; border-radius: 2px; }

/* ── Empty state ─────────────────────────────── */
.empty-state { text-align: center; padding: 60px 20px; color: var(--text-3); }
.empty-state i { font-size: 48px; display: block; margin-bottom: 12px; opacity: .25; }
.empty-state p { font-size: 14px; }

/* ── PRINT STYLES ────────────────────────────── */
@media print {
    body * { visibility: hidden; }
    #printArea, #printArea * { visibility: visible; }
    #printArea {
        position: absolute; top: 0; left: 0;
        width: 100%; padding: 20px;
    }
    .no-print { display: none !important; }
    .risk-table tbody tr { break-inside: avoid; }
    .print-header { display: block !important; }
}

.print-header {
    display: none;
    margin-bottom: 20px;
}
.print-header h2 { font-size: 18px; font-weight: 800; color: #0F172A; }
.print-header p  { font-size: 12px; color: #64748B; margin-top: 4px; }

@media (max-width: 768px) {
    .sum-cards { grid-template-columns: repeat(2, 1fr); }
    .hide-mobile { display: none !important; }
}
@media (max-width: 480px) {
    .sum-cards { grid-template-columns: repeat(2, 1fr); }
}
</style>
@endpush

@section('content')

{{-- Banner --}}
@include('components.page-banner', [
    'gradient'    => 'linear-gradient(135deg, #7F1D1D 0%, #991B1B 45%, #EF4444 100%)',
    'icon'        => 'bi-exclamation-triangle-fill',
    'title'       => 'Rekap Mahasiswa Berisiko Akademik',
    'sub'         => 'Bahan Rapat Akhir Semester · Jurusan Teknologi Informasi · Polinema',
    'chips'       => [
        ['icon' => 'bi-people-fill',               'label' => $summary['total_mahasiswa'] . ' Total Mahasiswa'],
        ['icon' => 'bi-exclamation-triangle-fill', 'label' => $summary['total_berisiko'] . ' Mahasiswa Berisiko'],
        ['icon' => 'bi-x-octagon-fill',            'label' => $summary['ps'] . ' Putus Studi'],
        ['icon' => 'bi-alarm-fill',                'label' => $summary['sp3'] . ' SP III'],
    ],
    'badge_num'   => $summary['total_berisiko'],
    'badge_label' => "Total\nBerisiko",
])

{{-- ══ SUMMARY CARDS ══ --}}
<div class="sum-cards">
    {{-- 1. Total Berisiko --}}
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#EF4444,#FCA5A5);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#EF4444;">{{ $summary['total_berisiko'] }}</div>
            <div class="sum-card-lbl">Total Berisiko</div>
            <div class="sum-card-pct" style="background:#FEE2E2;color:#991B1B;">
                dari {{ $summary['total_mahasiswa'] }} mahasiswa
            </div>
        </div>
    </div>

    {{-- 2. Berisiko Alpha (SP I + SP II + SP III + PS) --}}
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#EA580C,#FCD34D);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#EA580C;">
                {{ $summary['sp1'] + $summary['sp2'] + $summary['sp3'] + $summary['ps'] }}
            </div>
            <div class="sum-card-lbl">Berisiko Alpha</div>
            <div class="sum-card-detail">
                SP I: <b>{{ $summary['sp1'] }}</b> &nbsp;|&nbsp;
                SP II: <b>{{ $summary['sp2'] }}</b> &nbsp;|&nbsp;
                SP III: <b>{{ $summary['sp3'] }}</b> &nbsp;|&nbsp;
                PS: <b>{{ $summary['ps'] }}</b>
            </div>
        </div>
    </div>

    {{-- 3. Berisiko Nilai (Nilai E + D>3) --}}
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#D97706,#FCD34D);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#D97706;">
                {{ $summary['nilai_e'] + $summary['nilai_d'] }}
            </div>
            <div class="sum-card-lbl">Berisiko Nilai</div>
            <div class="sum-card-detail">
                Nilai E: <b>{{ $summary['nilai_e'] }}</b> &nbsp;|&nbsp;
                D&gt;3: <b>{{ $summary['nilai_d'] }}</b>
            </div>
        </div>
    </div>

    {{-- 4. IPS Rendah --}}
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#7C3AED,#A78BFA);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#7C3AED;">{{ $summary['ips_rendah'] }}</div>
            <div class="sum-card-lbl">IPS &lt; 2.00</div>
            <div class="sum-card-pct" style="background:#EDE9FE;color:#5B21B6;">
                <i class="bi bi-graph-down-arrow" style="font-size:10px;"></i> IPS semester rendah
            </div>
        </div>
    </div>
</div>

{{-- ══ FILTER BAR ══ --}}
<form method="GET" action="{{ route('admin.berisiko.index') }}" id="filterForm">
<div class="filter-bar no-print">
    <span class="filter-label">Filter Kelas:</span>
    <select name="kelas_id" class="filter-select" onchange="document.getElementById('filterForm').submit()">
        <option value="">Semua Kelas</option>
        @foreach($kelasList as $kelas)
        <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
            {{ $kelas->nama }}
        </option>
        @endforeach
    </select>

    <span class="filter-label" style="margin-left:8px;">Kategori:</span>
    <div class="jenis-pills">
        <a href="{{ request()->fullUrlWithQuery(['jenis' => 'semua']) }}"
           class="jenis-pill {{ $filterJenis === 'semua' ? 'active-semua' : '' }}">
            Semua ({{ $summary['total_berisiko'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['jenis' => 'alpha']) }}"
           class="jenis-pill {{ $filterJenis === 'alpha' ? 'active-alpha' : '' }}">
            Alpha ({{ $summary['sp1'] + $summary['sp2'] + $summary['sp3'] + $summary['ps'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['jenis' => 'nilai_e']) }}"
           class="jenis-pill {{ $filterJenis === 'nilai_e' ? 'active-nilai-e' : '' }}">
            Nilai E ({{ $summary['nilai_e'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['jenis' => 'nilai_d']) }}"
           class="jenis-pill {{ $filterJenis === 'nilai_d' ? 'active-nilai-d' : '' }}">
            D&gt;3 Matkul ({{ $summary['nilai_d'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['jenis' => 'ips_rendah']) }}"
           class="jenis-pill {{ $filterJenis === 'ips_rendah' ? 'active-ips-rendah' : '' }}">
            IPS&lt;2.00 ({{ $summary['ips_rendah'] }})
        </a>
    </div>

    <button type="button" class="btn-print" onclick="printRekap()">
        <i class="bi bi-printer-fill"></i>
        Cetak / Export
    </button>
</div>
</form>

{{-- ══ TABEL REKAP ══ --}}
<div id="printArea">

    {{-- Header untuk print --}}
    <div class="print-header">
        <h2>Rekap Mahasiswa Berisiko Akademik</h2>
        <p>Jurusan Teknologi Informasi · Politeknik Negeri Malang · Dicetak: {{ now()->format('d F Y, H:i') }}</p>
        <p>Total berisiko: {{ $summary['total_berisiko'] }} dari {{ $summary['total_mahasiswa'] }} mahasiswa</p>
    </div>

    <div class="card-white tbl-card-v2">
        <div class="tbl-head-v2 no-print">
            <div>
                <div class="tbl-title-v2">Daftar Mahasiswa Berisiko</div>
                <div class="tbl-sub-v2">
                    Menampilkan {{ $mahasiswaBerisiko->count() }} mahasiswa berisiko
                    @if($kelasId) · Filter: {{ $kelasList->find($kelasId)->nama ?? '' }} @endif
                </div>
            </div>
            <div class="search-wrap no-print">
                <i class="bi bi-search"></i>
                <input type="text" id="cariMhs" placeholder="Cari nama / NIM...">
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table class="risk-table" id="riskTable">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Mahasiswa</th>
                        <th class="hide-mobile">Kelas</th>
                        <th style="text-align:center;">IPK</th>
                        <th style="text-align:center;">IPS</th>
                        <th style="text-align:center;">Nilai D/E</th>
                        <th style="text-align:center;">Alpha</th>
                        <th style="text-align:center;">Kategori Risiko</th>
                    </tr>
                </thead>
                <tbody id="riskBody">
                    @forelse($mahasiswaBerisiko as $i => $mhs)
                    @php
                        $colors     = ['#2563EB','#EF4444','#8B5CF6','#F59E0B','#0891B2','#DB2777','#16A34A'];
                        $aColor     = $colors[$i % count($colors)];
                        $isKritis = in_array('ps', $mhs['kategori']) || in_array('sp3', $mhs['kategori']);
                        $badgeMap = [
                            'ps'         => ['bg' => '#FEE2E2', 'color' => '#7F1D1D', 'icon' => 'bi-x-octagon-fill',         'label' => 'Putus Studi'],
                            'sp3'        => ['bg' => '#FEE2E2', 'color' => '#DC2626', 'icon' => 'bi-alarm-fill',             'label' => 'SP III'],
                            'sp2'        => ['bg' => '#FEF3C7', 'color' => '#EA580C', 'icon' => 'bi-clock-fill',             'label' => 'SP II'],
                            'sp1'        => ['bg' => '#FEF9C3', 'color' => '#D97706', 'icon' => 'bi-clock-history',          'label' => 'SP I'],
                            'nilai_e'    => ['bg' => '#FEE2E2', 'color' => '#991B1B', 'icon' => 'bi-x-circle-fill',          'label' => 'Nilai E'],
                            'nilai_d'    => ['bg' => '#FEF9C3', 'color' => '#B45309', 'icon' => 'bi-exclamation-circle-fill','label' => 'D >3'],
                            'ips_rendah' => ['bg' => '#EDE9FE', 'color' => '#5B21B6', 'icon' => 'bi-graph-down-arrow',       'label' => 'IPS < 2'],
                        ];
                    @endphp
                    <tr data-nama="{{ strtolower($mhs['nama']) }}" data-nim="{{ $mhs['nim'] }}"
                        style="{{ $isKritis ? 'background:rgba(239,68,68,.025);' : '' }}">

                        <td>
                            <div class="no-circle" style="{{ $isKritis ? 'background:#FEE2E2;color:#991B1B;' : '' }}">
                                {{ $i + 1 }}
                            </div>
                        </td>

                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="avatar" style="background:{{ $aColor }};">
                                    {{ strtoupper(substr($mhs['nama'], 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:700;font-size:13.5px;color:var(--text-1);">
                                        {{ $mhs['nama'] }}
                                    </div>
                                    <div style="font-size:11px;color:var(--text-3);font-family:monospace;">
                                        {{ $mhs['nim'] }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="hide-mobile" style="font-size:12.5px;color:var(--text-2);">
                            {{ $mhs['kelas'] }}
                        </td>

                        <td style="text-align:center;">
                            <span style="font-weight:700;font-size:14px;color:{{ $mhs['ipk'] < 2.5 ? '#EF4444' : 'var(--text-1)' }};">
                                {{ $mhs['ipk'] }}
                            </span>
                        </td>

                        <td style="text-align:center;">
                            <span style="font-weight:700;font-size:14px;color:{{ $mhs['ips'] < 2.0 ? '#7C3AED' : 'var(--text-1)' }};">
                                {{ $mhs['ips'] }}
                            </span>
                        </td>

                        <td style="text-align:center;">
                            @if($mhs['jumlah_de'] > 0)
                            <span style="font-weight:800;font-size:15px;color:#F59E0B;">
                                {{ $mhs['jumlah_de'] }}
                            </span>
                            @else
                            <span style="color:var(--text-3);font-size:12px;">—</span>
                            @endif
                        </td>

                        <td style="text-align:center;">
                            <span style="font-weight:800;font-size:15px;color:{{ $mhs['total_alpha'] >= 18 ? '#8B5CF6' : 'var(--text-2)' }};">
                                {{ $mhs['total_alpha'] }}j
                            </span>
                        </td>

                        <td style="text-align:center;">
                            @php
                                $shown = array_slice($mhs['kategori'], 0, 2);
                                $extra = count($mhs['kategori']) - 2;
                            @endphp
                            @foreach($shown as $kat)
                            @php $b = $badgeMap[$kat] ?? ['bg'=>'#F1F5F9','color'=>'#64748B','icon'=>'bi-exclamation','label'=>$kat]; @endphp
                            <span class="badge-risiko" style="background:{{ $b['bg'] }};color:{{ $b['color'] }};">
                                <i class="bi {{ $b['icon'] }}" style="font-size:9px;"></i>
                                {{ $b['label'] }}
                            </span>
                            @endforeach
                            @if($extra > 0)
                            <span class="badge-risiko" style="background:#F1F5F9;color:#64748B;">+{{ $extra }} lagi</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="bi bi-shield-check-fill" style="color:#22C55E;"></i>
                                <p style="font-size:16px;font-weight:700;color:#166534;margin-bottom:4px;">
                                    Tidak ada mahasiswa berisiko!
                                </p>
                                <p>Semua mahasiswa memiliki performa akademik yang baik.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer tabel --}}
        <div style="display:flex;align-items:center;gap:8px;margin-top:12px;padding-top:10px;border-top:1px solid var(--border);flex-wrap:wrap;" class="no-print">
            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:600;background:#FEE2E2;color:#7F1D1D;">
                <i class="bi bi-x-octagon-fill"></i> {{ $summary['ps'] }} Putus Studi
            </span>
            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:600;background:#FEE2E2;color:#DC2626;">
                <i class="bi bi-alarm-fill"></i> {{ $summary['sp3'] }} SP III
            </span>
            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:600;background:#FEF3C7;color:#EA580C;">
                <i class="bi bi-clock-fill"></i> {{ $summary['sp2'] }} SP II
            </span>
            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:600;background:#FEF9C3;color:#D97706;">
                <i class="bi bi-clock-history"></i> {{ $summary['sp1'] }} SP I
            </span>
            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:600;background:#FEE2E2;color:#991B1B;">
                <i class="bi bi-x-circle-fill"></i> {{ $summary['nilai_e'] }} Nilai E
            </span>
            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:600;background:#FEF9C3;color:#B45309;">
                <i class="bi bi-exclamation-circle-fill"></i> {{ $summary['nilai_d'] }} D&gt;3 Matkul
            </span>
            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:600;background:#EDE9FE;color:#5B21B6;">
                <i class="bi bi-graph-down-arrow"></i> {{ $summary['ips_rendah'] }} IPS&lt;2.00
            </span>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Search
document.getElementById('cariMhs').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#riskBody tr[data-nama]').forEach(function(r) {
        var nama = (r.dataset.nama || '').toLowerCase();
        var nim  = (r.dataset.nim  || '').toLowerCase();
        r.style.display = (nama.includes(q) || nim.includes(q)) ? '' : 'none';
    });
});

// Print
function printRekap() {
    window.print();
}
</script>
@endpush