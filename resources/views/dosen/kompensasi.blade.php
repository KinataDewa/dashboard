@extends('layouts.dosen')

@section('title', 'Kompensasi Mahasiswa')
@section('page-title', 'Kompensasi Mahasiswa')
@section('page-sub', 'Rekap kompensasi mahasiswa bimbingan Anda')

@push('styles')
<style>
/* ── Summary Cards ───────────────────────────── */
.sum-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 24px; }
.sum-card  { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
.sum-card-bar { height: 3px; }
.sum-card-body { padding: 14px 16px; }
.sum-card-val  { font-size: 30px; font-weight: 800; line-height: 1; letter-spacing: -1px; }
.sum-card-lbl  { font-size: 12px; color: var(--text-2); margin-top: 4px; font-weight: 500; }
.sum-card-pct  { font-size: 11px; font-weight: 700; margin-top: 6px; display: inline-flex; align-items: center; gap: 3px; padding: 2px 8px; border-radius: 99px; }

/* ── Tabel ───────────────────────────────────── */
.kompen-table { width: 100%; border-collapse: collapse; }
.kompen-table thead th {
    font-size: 11px; font-weight: 700; color: var(--text-3);
    text-transform: uppercase; letter-spacing: .6px;
    padding: 10px 14px; border-bottom: 1.5px solid var(--border);
    background: #FAFBFF; white-space: nowrap;
}
.kompen-table tbody tr { border-bottom: 1px solid #F8FAFC; transition: background .1s; }
.kompen-table tbody tr:last-child { border-bottom: none; }
.kompen-table tbody tr:hover { background: #FAFBFF; }
.kompen-table tbody td { padding: 12px 14px; vertical-align: middle; font-size: 13px; }
.kompen-table tbody tr.row-first td { border-top: 1.5px solid var(--border); }

/* ── Avatar & helpers ────────────────────────── */
.avatar { width: 34px; height: 34px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #fff; flex-shrink: 0; }
.no-circle { width: 28px; height: 28px; border-radius: 50%; background: #F1F5F9; color: var(--text-2); display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; }

/* ── Empty state ─────────────────────────────── */
.empty-state { text-align: center; padding: 60px 20px; color: var(--text-3); }
.empty-state i { font-size: 48px; display: block; margin-bottom: 12px; opacity: .25; }

@media (max-width: 768px) {
    .sum-cards { grid-template-columns: repeat(2, 1fr); }
    .hide-mobile { display: none !important; }
}
@media (max-width: 480px) {
    .sum-cards { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

@php
    $totalWajibKompen     = $dataMahasiswa->count();
    $totalSisaKeseluruhan = $dataMahasiswa->sum('jam_sisa');
    $totalLunas           = $dataMahasiswa->filter(fn($m) => $m->status === 'lunas')->count();
    $totalMasihSisa       = $dataMahasiswa->filter(fn($m) => $m->status !== 'lunas')->count();
@endphp

{{-- ══ BANNER ══ --}}
@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg, #065F46 0%, #059669 50%, #34D399 100%)',
    'icon'         => 'bi-clipboard2-check-fill',
    'title'        => 'Kompensasi Mahasiswa Bimbingan',
    'sub'          => ($dosen->nama ?? auth()->user()->name) . ' · Semester ' . $semesterAktif . ' · ' . now()->format('d F Y'),
    'chips'        => [
        ['icon' => 'bi-people-fill',     'label' => $totalWajibKompen . ' Mahasiswa Wajib Kompen'],
        ['icon' => 'bi-hourglass-split', 'label' => $totalSisaKeseluruhan . ' Jam Sisa Kompen Total'],
    ],
    'badge_num'    => $totalWajibKompen,
    'badge_label'  => "Wajib\nKompen",
    'badge2_num'   => $totalSisaKeseluruhan,
    'badge2_label' => "Jam\nSisa",
])

{{-- ══ FILTER SEMESTER ══
@if($semesterList->count() > 1) --}}
{{-- <form method="GET" action="{{ route('kompensasi.index') }}" style="margin-bottom:16px;"> --}}
    {{-- <div style="background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:12px 18px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <span style="font-size:12px;font-weight:700;color:var(--text-2);">Semester:</span>
        <select name="semester" onchange="this.form.submit()"
                style="padding:5px 10px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;background:var(--white);cursor:pointer;">
            @foreach($semesterList as $sem)
            <option value="{{ $sem }}" {{ $sem == $semesterAktif ? 'selected' : '' }}>Semester {{ $sem }}</option>
            @endforeach
        </select>
        <span style="font-size:12px;color:var(--text-3);">Menampilkan data kompensasi semester {{ $semesterAktif }}</span>
    </div>
</form>
@endif --}}

{{-- ══ SUMMARY CARDS ══ --}}
<div class="sum-cards">
    {{-- 1. Wajib Kompen --}}
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#2563EB,#93C5FD);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#2563EB;">{{ $totalWajibKompen }}</div>
            <div class="sum-card-lbl">Mahasiswa Wajib Kompen</div>
            <div class="sum-card-pct" style="background:#DBEAFE;color:#1D4ED8;">
                <i class="bi bi-person-fill" style="font-size:10px;"></i> Alpha &geq; 18 jam
            </div>
        </div>
    </div>

    {{-- 2. Lunas Semua --}}
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#16A34A,#86EFAC);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#16A34A;">{{ $totalLunas }}</div>
            <div class="sum-card-lbl">Mahasiswa Lunas Semua Kompen</div>
            <div class="sum-card-pct" style="background:#DCFCE7;color:#166534;">
                <i class="bi bi-check-circle-fill" style="font-size:10px;"></i> Semua semester lunas
            </div>
        </div>
    </div>

    {{-- 3. Masih Ada Sisa --}}
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#EF4444,#FCA5A5);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#EF4444;">{{ $totalMasihSisa }}</div>
            <div class="sum-card-lbl">Mahasiswa Masih Ada Sisa Kompen</div>
            <div class="sum-card-pct" style="background:#FEE2E2;color:#991B1B;">
                <i class="bi bi-exclamation-circle-fill" style="font-size:10px;"></i> {{ $totalSisaKeseluruhan }} jam sisa total
            </div>
        </div>
    </div>
</div>

{{-- ══ TABEL ══ --}}
<div class="card-white tbl-card-v2">
    <div class="tbl-head-v2">
        <div>
            <div class="tbl-title-v2">Daftar Kompensasi Mahasiswa</div>
            <div class="tbl-sub-v2">
                {{ $totalWajibKompen }} mahasiswa dengan alpha &geq; 18 jam di minimal 1 semester
            </div>
        </div>
        @if($dataMahasiswa->isNotEmpty())
        <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="cariMhs" placeholder="Cari nama / NIM...">
        </div>
        @endif
    </div>

    @if($dataMahasiswa->isEmpty())
    <div class="empty-state">
        <i class="bi bi-clipboard2-check"></i>
        <div style="font-size:14px;font-weight:600;color:var(--text-2);margin-bottom:4px;">
            Tidak ada mahasiswa wajib kompen
        </div>
        <div style="font-size:12px;">
            Semua mahasiswa bimbingan Anda memiliki alpha di bawah 18 jam
        </div>
    </div>
    @else
    <div style="overflow-x:auto;">
        <table class="kompen-table" id="kompenTable">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Mahasiswa</th>
                    <th style="text-align:center;" class="hide-mobile">Semester</th>
                    <th style="text-align:center;">Jam Alpha</th>
                    <th style="text-align:center;" class="hide-mobile">Wajib Kompen</th>
                    <th style="text-align:center;" class="hide-mobile">Sudah</th>
                    <th style="text-align:center;">Sisa</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;" class="hide-mobile">TTD Admin</th>
                    <th style="text-align:center;" class="hide-mobile">TTD Kajur</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dataMahasiswa as $i => $item)
                @php
                    $mhs       = $item->mahasiswa;
                    $colors    = ['#2563EB','#EF4444','#8B5CF6','#F59E0B','#0891B2','#DB2777','#16A34A'];
                    $aColor    = $colors[$i % count($colors)];
                    $searchKey = strtolower($mhs->nama . ' ' . $mhs->nim);
                    $isLunas   = $item->status === 'lunas';
                @endphp
                <tr class="row-first"
                    data-mhs-id="{{ $mhs->id }}"
                    data-search="{{ $searchKey }}"
                    style="{{ !$isLunas ? 'background:rgba(239,68,68,.02);' : '' }}">
                    <td style="text-align:center;vertical-align:middle;">
                        <div class="no-circle" style="{{ !$isLunas ? 'background:#FEE2E2;color:#991B1B;' : '' }}">
                            {{ $i + 1 }}
                        </div>
                    </td>
                    <td style="vertical-align:middle;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="avatar" style="background:{{ $aColor }};">
                                {{ strtoupper(substr($mhs->nama, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:13.5px;">{{ $mhs->nama }}</div>
                                <div style="font-size:11.5px;color:var(--text-2);font-family:monospace;">{{ $mhs->nim }}</div>
                                @if($isLunas)
                                <span style="display:inline-flex;align-items:center;gap:3px;background:#DCFCE7;color:#166534;border-radius:99px;padding:1px 7px;font-size:10px;font-weight:700;margin-top:3px;">
                                    <i class="bi bi-check-circle-fill"></i> Lunas
                                </span>
                                @else
                                <span style="display:inline-flex;align-items:center;gap:3px;background:#FEF3C7;color:#92400E;border-radius:99px;padding:1px 7px;font-size:10px;font-weight:700;margin-top:3px;">
                                    <i class="bi bi-hourglass-split"></i> {{ $item->jam_sisa }} jam sisa
                                </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center;" class="hide-mobile">
                        <span style="background:#F1F5F9;color:var(--text-2);border-radius:6px;padding:3px 10px;font-size:12px;font-weight:700;">
                            Sem {{ $item->semester }}
                        </span>
                    </td>
                    <td style="text-align:center;font-weight:700;color:#EF4444;">
                        {{ $item->jam_alpha }} jam
                    </td>
                    <td style="text-align:center;font-weight:600;" class="hide-mobile">
                        {{ $item->jam_kompen_wajib }} jam
                    </td>
                    <td style="text-align:center;" class="hide-mobile">
                        <span style="color:#16A34A;font-weight:600;">{{ $item->jam_kompen_selesai }} jam</span>
                    </td>
                    <td style="text-align:center;">
                        @if($item->jam_sisa > 0)
                        <span style="color:#EF4444;font-weight:800;">{{ $item->jam_sisa }} jam</span>
                        @else
                        <span style="color:#16A34A;font-weight:800;">0 jam</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if($isLunas)
                        <span class="badge badge-green">
                            <i class="bi bi-check-circle-fill" style="font-size:10px;margin-right:3px;"></i> Lunas
                        </span>
                        @else
                        <span class="badge badge-red">
                            <i class="bi bi-clock-fill" style="font-size:10px;margin-right:3px;"></i> Belum Lunas
                        </span>
                        @endif
                    </td>
                    <td style="text-align:center;font-size:16px;" class="hide-mobile">
                        {{ $item->ttd_admin ? '✅' : '⏳' }}
                    </td>
                    <td style="text-align:center;font-size:16px;" class="hide-mobile">
                        {{ $item->ttd_kajur ? '✅' : '⏳' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="tbl-footer">
        <span class="info-chip"><i class="bi bi-people-fill"></i> {{ $totalWajibKompen }} Mahasiswa · Sem {{ $semesterAktif }}</span>
        <span class="info-chip"><i class="bi bi-check-circle-fill" style="color:#16A34A;"></i> {{ $totalLunas }} Lunas</span>
        <span class="info-chip"><i class="bi bi-exclamation-circle-fill" style="color:#EF4444;"></i> {{ $totalMasihSisa }} Belum Lunas</span>
        <span class="info-chip"><i class="bi bi-hourglass-split"></i> {{ $totalSisaKeseluruhan }} Jam Sisa</span>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
(function () {
    var input = document.getElementById('cariMhs');
    if (!input) return;

    input.addEventListener('input', function () {
        var q = this.value.toLowerCase().trim();

        document.querySelectorAll('tr[data-mhs-id]').forEach(function (tr) {
            var search = (tr.dataset.search || '').toLowerCase();
            tr.style.display = (!q || search.includes(q)) ? '' : 'none';
        });
    });
})();
</script>
@endpush
