@extends('layouts.dosen')

@section('title', 'Dashboard DPA')
@section('page-title', 'Dashboard DPA')
@section('page-sub', ($dosen->nama ?? '') . ' · ' . $totalMahasiswa . ' Mahasiswa Bimbingan')

@push('styles')
<style>
.ipk-bar { width:100%;height:5px;background:#EFF6FF;border-radius:3px;margin-top:8px;overflow:hidden; }
.ipk-bar-fill { height:100%;border-radius:3px;background:linear-gradient(90deg,#2563EB,#60A5FA);transition:width .8s ease; }
.grade-pill {
    display:inline-flex;align-items:center;justify-content:center;
    width:26px;height:26px;border-radius:50%;
    font-size:11px;font-weight:800;
}
.grade-A{background:#DCFCE7;color:#15803D;}
.grade-B{background:#DBEAFE;color:#1D4ED8;}
.grade-C{background:#FEF9C3;color:#854D0E;}
.grade-D{background:#FEE2E2;color:#991B1B;}
.grade-E{background:#FEE2E2;color:#7F1D1D;}
.student-avatar {
    width:34px;height:34px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    font-size:13px;font-weight:700;color:#fff;flex-shrink:0;
}
.risk-pill {
    display:inline-flex;align-items:center;gap:4px;
    padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:700;
}
</style>
@endpush

@section('content')

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
                        <span class="stat-card-badge badge-blue">
                            <i class="bi bi-mortarboard"></i> Aktif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-3 col-6">
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background:{{ $totalBerisiko > 0 ? 'linear-gradient(90deg,#EF4444,#FCA5A5)' : 'linear-gradient(90deg,#22C55E,#86EFAC)' }};"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:{{ $totalBerisiko > 0 ? '#FEF2F2' : '#F0FDF4' }};">
                    <i class="bi bi-{{ $totalBerisiko > 0 ? 'exclamation-triangle-fill' : 'shield-check-fill' }}"
                       style="color:{{ $totalBerisiko > 0 ? '#EF4444' : '#22C55E' }};"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Mahasiswa Berisiko</div>
                    <div class="stat-card-value" style="color:{{ $totalBerisiko > 0 ? '#EF4444' : '#22C55E' }};">{{ $totalBerisiko }}</div>
                    <div class="stat-card-note">
                        @if($totalBerisiko > 0)
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
                    <div class="ipk-bar">
                        <div class="ipk-bar-fill" style="width:{{ ($rataRataIpk/4)*100 }}%;background:linear-gradient(90deg,#F59E0B,#FCD34D);"></div>
                    </div>
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
                        <span class="stat-card-badge" style="background:#F5F3FF;color:#7C3AED;">
                            Semua mata kuliah
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ALERT --}}
@if($totalBerisiko > 0)
<div style="background:#FEF2F2;border:1px solid #FECACA;border-left:4px solid #EF4444;border-radius:var(--radius-sm);padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;gap:12px;">
    <i class="bi bi-exclamation-triangle-fill" style="color:#EF4444;font-size:18px;flex-shrink:0;"></i>
    <div>
        <div style="font-size:13.5px;font-weight:700;color:#991B1B;">
            {{ $totalBerisiko }} mahasiswa terdeteksi berisiko akademik
        </div>
        <div style="font-size:12px;color:#B91C1C;margin-top:2px;">
            Terdapat nilai D/E atau absensi ≥18 jam. Segera lakukan bimbingan.
        </div>
    </div>
    <a href="{{ route('dosen.kelas') }}" class="btn-danger ms-auto">
        <i class="bi bi-arrow-right"></i> Lihat Detail
    </a>
</div>
@endif

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
                    <div class="filter-opt" data-val="berisiko">Berisiko</div>
                    <div class="filter-opt" data-val="aman">Aman</div>
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
            <tbody id="mhsTableBody">
                @foreach($mahasiswas as $i => $mhs)
                @php
                    $ipkMhs    = $mhs->ipk_val ?? $mhs->ipk;
                    $semAktif  = $mhs->kelas->semester ?? 6;
                    $ipSem     = $mhs->getIpSemester($semAktif);
                    $berisiko  = $mhs->is_berisiko ?? $mhs->isBerisiko();
                    $totalAlph = $mhs->absensis->sum('jam_alpha');
                    $gradeList = $mhs->nilais->pluck('grade');
                    $gradeMin  = $gradeList->contains('E') ? 'E'
                        : ($gradeList->contains('D') ? 'D'
                        : ($gradeList->contains('C') ? 'C'
                        : ($gradeList->contains('B') ? 'B' : 'A')));
                    $colors = ['#2563EB','#16A34A','#7C3AED','#F59E0B','#EF4444','#0891B2','#DB2777'];
                    $avatarColor = $colors[$i % count($colors)];
                @endphp
                <tr data-nama="{{ strtolower($mhs->nama) }}"
                    data-status="{{ $berisiko ? 'berisiko' : 'aman' }}"
                    style="{{ $berisiko ? 'background:rgba(239,68,68,.03);' : '' }}">
                    <td class="muted" style="{{ $berisiko ? 'border-left:3px solid #EF4444;' : '' }}">
                        {{ $i + 1 }}
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="student-avatar" style="background:{{ $avatarColor }};">
                                {{ strtoupper(substr($mhs->nama, 0, 1)) }}
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
                    </td>
                    <td style="text-align:center;">
                        <span style="font-size:13px;font-weight:600;color:{{ $ipSem < 2.5 ? '#EF4444' : 'var(--text-1)' }};">
                            {{ number_format($ipSem, 2) }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <span style="font-weight:700;color:{{ $totalAlph >= 18 ? '#EF4444' : ($totalAlph >= 14 ? '#F59E0B' : 'var(--text-2)') }};">
                            {{ $totalAlph }}j
                            @if($totalAlph >= 18) ⛔ @elseif($totalAlph >= 14) ⚠️ @endif
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <span class="grade-pill grade-{{ $gradeMin }}">{{ $gradeMin }}</span>
                    </td>
                    <td>
                        @if($berisiko)
                            <span class="risk-pill risk-high">
                                <i class="bi bi-exclamation-circle-fill"></i> Berisiko
                            </span>
                        @else
                            <span class="risk-pill risk-low">
                                <i class="bi bi-check-circle-fill"></i> Aman
                            </span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('dosen.mahasiswa.detail', $mhs->id) }}"
                           class="btn-outline" style="font-size:12px;padding:5px 12px;">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="tbl-footer">
        <div class="info-chip" style="background:#EFF6FF;color:#1D4ED8;">
            <i class="bi bi-people-fill"></i> {{ $totalMahasiswa }} mahasiswa
        </div>
        @if($totalBerisiko > 0)
        <div class="info-chip" style="background:#FEE2E2;color:#991B1B;">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ $totalBerisiko }} berisiko
        </div>
        @endif
        <div class="info-chip">
            <i class="bi bi-award"></i> IPK rata-rata {{ number_format($rataRataIpk, 2) }}
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('searchMhs').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#mhsTableBody tr').forEach(function(r) {
        r.style.display = (r.dataset.nama||'').includes(q) ? '' : 'none';
    });
});

document.getElementById('filterMhsBtn').addEventListener('filterChange', function(e) {
    var val = e.detail.value;
    document.querySelectorAll('#mhsTableBody tr').forEach(function(r) {
        if (!val) { r.style.display=''; return; }
        r.style.display = r.dataset.status===val ? '' : 'none';
    });
});
</script>
@endpush