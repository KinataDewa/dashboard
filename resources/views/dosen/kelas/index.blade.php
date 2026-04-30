@extends('layouts.dosen')
 
@section('title', 'Data Mahasiswa')
@section('page-title', 'Data Mahasiswa Bimbingan')
@section('page-sub', $dosen->nama . ' · ' . $mahasiswas->count() . ' mahasiswa')
 
@push('styles')
<style>
.student-avatar{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0;}
.grade-pill{display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:50%;font-size:11px;font-weight:800;}
.grade-A{background:#DCFCE7;color:#15803D;}
.grade-B{background:#DBEAFE;color:#1D4ED8;}
.grade-C{background:#FEF9C3;color:#854D0E;}
.grade-D{background:#FEE2E2;color:#991B1B;}
.grade-E{background:#FEE2E2;color:#7F1D1D;}
.risk-pill{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:700;}
.risk-high{background:#FEE2E2;color:#991B1B;}
.risk-low{background:#DCFCE7;color:#166534;}
.ipk-bar{width:100%;height:4px;background:#F1F5F9;border-radius:2px;margin-top:4px;overflow:hidden;}
.ipk-bar-fill{height:100%;border-radius:2px;background:linear-gradient(90deg,#2563EB,#60A5FA);}
</style>
@endpush
 
@section('content')
 
<div class="section-label">Daftar Mahasiswa Bimbingan</div>
 
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
                    <th style="text-align:center;">Alpha</th>
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
                    $gradeMin = $grades->contains('E') ? 'E'
                        : ($grades->contains('D') ? 'D'
                        : ($grades->contains('C') ? 'C'
                        : ($grades->contains('B') ? 'B' : 'A')));
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
                                <div style="font-weight:600;color:var(--text-1);">{{ $mhs->nama }}</div>
                                <div style="font-size:11px;color:var(--text-3);font-family:monospace;">{{ $mhs->nim }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <div style="font-weight:700;color:{{ $ipkMhs < 2.5 ? '#EF4444' : ($ipkMhs >= 3.5 ? '#22C55E' : 'var(--text-1)') }};">
                            {{ number_format($ipkMhs, 2) }}
                        </div>
                        <div class="ipk-bar" style="max-width:60px;margin:4px auto 0;">
                            <div class="ipk-bar-fill" style="width:{{ ($ipkMhs/4)*100 }}%;"></div>
                        </div>
                    </td>
                    <td style="text-align:center;font-weight:700;color:{{ $totalAlp >= 18 ? '#EF4444' : ($totalAlp >= 14 ? '#F59E0B' : 'var(--text-2)') }};">
                        {{ $totalAlp }}j
                        @if($totalAlp >= 18) ⛔ @elseif($totalAlp >= 14) ⚠️ @endif
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
 
    <div class="tbl-footer">
        <div class="info-chip" style="background:#EFF6FF;color:#1D4ED8;">
            <i class="bi bi-people-fill"></i> {{ $mahasiswas->count() }} mahasiswa
        </div>
        @php $jmlBerisiko = $mahasiswas->filter(fn($m) => $m->is_berisiko ?? $m->isBerisiko())->count(); @endphp
        @if($jmlBerisiko > 0)
        <div class="info-chip" style="background:#FEE2E2;color:#991B1B;">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ $jmlBerisiko }} berisiko
        </div>
        @endif
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