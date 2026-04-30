@extends('layouts.dosen')

@section('title', 'Data Mahasiswa')
@section('page-title', 'Data Mahasiswa Bimbingan')
@section('page-sub', $dosen->nama . ' · ' . $mahasiswas->count() . ' mahasiswa')

@push('styles')
<style>
.grade-pill{display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:50%;font-size:11px;font-weight:800;}
.grade-A{background:#DCFCE7;color:#15803D;}
.grade-B{background:#DBEAFE;color:#1D4ED8;}
.grade-C{background:#FEF9C3;color:#854D0E;}
.grade-D{background:#FEE2E2;color:#991B1B;}
.grade-E{background:#FEE2E2;color:#7F1D1D;}
</style>
@endpush

@section('content')

{{-- ══ BANNER ══ --}}
@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg, #1E3A8A 0%, #1D4ED8 55%, #3B82F6 100%)',
    'icon'         => 'bi-people-fill',
    'title'        => 'Data Mahasiswa Bimbingan',
    'sub'          => ($dosen->nama ?? auth()->user()->name) . ' · Klik Detail untuk melihat rekap lengkap',
    'chips'        => [
        ['icon' => 'bi-people-fill',              'label' => $mahasiswas->count() . ' Mahasiswa'],
        ['icon' => 'bi-exclamation-triangle-fill','label' => $mahasiswas->filter(fn($m) => $m->is_berisiko ?? $m->isBerisiko())->count() . ' Berisiko'],
        ['icon' => 'bi-check-circle-fill',        'label' => $mahasiswas->filter(fn($m) => !($m->is_berisiko ?? $m->isBerisiko()))->count() . ' Aman'],
    ],
    'badge_num'    => $mahasiswas->count(),
    'badge_label'  => "Total\nMahasiswa",
])

{{-- Summary --}}
@php
    $jmlBerisiko = $mahasiswas->filter(fn($m) => $m->is_berisiko ?? $m->isBerisiko())->count();
    $rataIpk = $mahasiswas->avg(fn($m) => $m->ipk_val ?? $m->ipk);
@endphp

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background:linear-gradient(90deg,#2563EB,#60A5FA);"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:#EFF6FF;"><i class="bi bi-people-fill" style="color:#2563EB;"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Total Mahasiswa</div>
                    <div class="stat-card-value" style="color:#2563EB;">{{ $mahasiswas->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background:{{ $jmlBerisiko>0 ? 'linear-gradient(90deg,#EF4444,#FCA5A5)' : 'linear-gradient(90deg,#22C55E,#86EFAC)' }};"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:{{ $jmlBerisiko>0 ? '#FEF2F2' : '#F0FDF4' }};"><i class="bi bi-exclamation-triangle-fill" style="color:{{ $jmlBerisiko>0 ? '#EF4444' : '#22C55E' }};"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Mahasiswa Berisiko</div>
                    <div class="stat-card-value" style="color:{{ $jmlBerisiko>0 ? '#EF4444' : '#22C55E' }};">{{ $jmlBerisiko }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background:linear-gradient(90deg,#F59E0B,#FCD34D);"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:#FFFBEB;"><i class="bi bi-award-fill" style="color:#F59E0B;"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Rata-rata IPK</div>
                    <div class="stat-card-value" style="color:#F59E0B;">{{ number_format($rataIpk, 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section-label">Daftar Mahasiswa</div>
<div class="card-white tbl-card-v2">
    <div class="tbl-head-v2">
        <div>
            <div class="tbl-title-v2">Data Mahasiswa</div>
            <div class="tbl-sub-v2">Klik Detail untuk melihat nilai & absensi lengkap</div>
        </div>
        <div class="tbl-actions">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Cari nama / NIM..." id="searchMhs">
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
                    <th style="text-align:center;">Alpha Total</th>
                    <th style="text-align:center;">Grade Min</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="mhsBody">
                @forelse($mahasiswas as $i => $mhs)
                @php
                    $ipkMhs   = $mhs->ipk_val ?? $mhs->ipk;
                    $berisiko = $mhs->is_berisiko ?? $mhs->isBerisiko();
                    $totalAlp = $mhs->absensis->sum('jam_alpha');
                    $grades   = $mhs->nilais->pluck('grade');
                    $gradeMin = $grades->isEmpty() ? '—'
                        : ($grades->contains('E') ? 'E' : ($grades->contains('D') ? 'D'
                        : ($grades->contains('C') ? 'C' : ($grades->contains('B') ? 'B' : 'A'))));
                    $colors = ['#2563EB','#16A34A','#7C3AED','#F59E0B','#EF4444','#0891B2','#DB2777'];
                    $aColor = $colors[$i % count($colors)];
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
                                <div style="font-weight:600;color:var(--text-1);">{{ $mhs->nama }}</div>
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
                    <td style="text-align:center;">
                        <span style="font-weight:700;color:{{ $totalAlp>=18 ? '#EF4444' : ($totalAlp>=14 ? '#F59E0B' : 'var(--text-2)') }};">
                            {{ $totalAlp }}j
                            {{ $totalAlp>=18 ? '⛔' : ($totalAlp>=14 ? '⚠️' : '') }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        @if($gradeMin !== '—')
                            <span class="grade-pill grade-{{ $gradeMin }}">{{ $gradeMin }}</span>
                        @else
                            <span style="color:var(--text-3);font-size:13px;">—</span>
                        @endif
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
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:36px;color:var(--text-3);">
                        <i class="bi bi-people" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                        Belum ada mahasiswa bimbingan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
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
</script>
@endpush