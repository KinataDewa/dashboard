@extends('layouts.admin')
@section('title','Data Mahasiswa')
@section('page-title','Data Mahasiswa')
@section('page-sub','Kelola seluruh data mahasiswa aktif')

@push('styles')
<style>
/* ── Filter Bar ──────────────────────────────────── */
.filter-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 16px;
}
.filter-bar .search-wrap {
    flex: 1;
    min-width: 160px;
    max-width: 280px;
}
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
    transition: border-color .2s;
}
.filter-select:focus { border-color: var(--blue); }

/* ── Table ───────────────────────────────────────── */
.mhs-table-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 0 0 var(--radius) var(--radius);
}
.mhs-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 520px;
}
.mhs-table thead th {
    font-size: 11px;
    font-weight: 700;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .7px;
    padding: 10px 14px;
    border-bottom: 1.5px solid var(--border);
    background: #FAFBFF;
    white-space: nowrap;
}
.mhs-table thead th:first-child { border-radius: 0; }
.mhs-table tbody tr {
    border-bottom: 1px solid #F1F5F9;
    transition: background .12s;
}
.mhs-table tbody tr:last-child { border-bottom: none; }
.mhs-table tbody tr:hover { background: #F8FAFF; }
.mhs-table tbody td { padding: 12px 14px; vertical-align: middle; }

/* Avatar */
.mhs-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: var(--blue);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 800;
    flex-shrink: 0;
}

/* Status badge */
.status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px;
    font-size: 11.5px; font-weight: 700;
    white-space: nowrap;
}
.status-aktif   { background: #DCFCE7; color: #166534; }
.status-nonaktif{ background: #FEE2E2; color: #991B1B; }
.status-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: currentColor;
}

/* Kelas badge */
.kelas-badge {
    display: inline-flex; align-items: center;
    padding: 3px 10px; border-radius: 6px;
    font-size: 12px; font-weight: 700;
    background: #EFF6FF; color: #1D4ED8;
    white-space: nowrap;
}

/* Action buttons */
.action-wrap {
    display: flex; gap: 5px;
    align-items: center; justify-content: center;
}
.btn-detail {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 5px 10px; border-radius: 7px;
    font-size: 12px; font-weight: 600;
    background: var(--blue); color: #fff;
    text-decoration: none; border: none;
    cursor: pointer; transition: all .15s;
    white-space: nowrap;
}
.btn-detail:hover { background: var(--blue-d); transform: translateY(-1px); }
.btn-icon {
    width: 30px; height: 30px;
    border-radius: 7px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 13px; cursor: pointer;
    border: 1.5px solid transparent;
    transition: all .15s; text-decoration: none;
}
.btn-icon-edit  { background: #EFF6FF; color: #2563EB; border-color: #BFDBFE; }
.btn-icon-edit:hover  { background: #DBEAFE; }
.btn-icon-del   { background: #FEF2F2; color: #EF4444; border-color: #FECACA; }
.btn-icon-del:hover   { background: #FEE2E2; }

/* Empty state */
.empty-state-v2 {
    text-align: center; padding: 48px 20px;
    color: var(--text-3);
}
.empty-state-v2 i { font-size: 40px; opacity: .3; display: block; margin-bottom: 10px; }
.empty-state-v2 p { font-size: 13.5px; }

/* ── Pagination ──────────────────────────────────── */
.pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-top: 1px solid var(--border);
    flex-wrap: wrap;
    gap: 10px;
}
.pagination-info {
    font-size: 13px;
    color: var(--text-2);
}
.pagination-info strong { color: var(--text-1); }
.pagination-btns {
    display: flex; gap: 4px; flex-wrap: wrap;
}
.pg-btn {
    min-width: 34px; height: 34px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 8px; font-size: 13px; font-weight: 600;
    border: 1.5px solid var(--border);
    background: var(--white); color: var(--text-2);
    text-decoration: none; cursor: pointer;
    transition: all .15s; padding: 0 8px;
    white-space: nowrap;
}
.pg-btn:hover:not(.active):not(.disabled) {
    border-color: var(--blue); color: var(--blue); background: #EFF6FF;
}
.pg-btn.active {
    background: var(--blue); color: #fff;
    border-color: var(--blue);
    box-shadow: 0 2px 8px rgba(37,99,235,.3);
}
.pg-btn.disabled {
    opacity: .4; cursor: not-allowed; pointer-events: none;
}

/* ── Responsive ──────────────────────────────────── */
@media(max-width:768px){
    .hide-mobile { display: none !important; }
    .filter-bar .search-wrap { max-width: 100%; }
    .filter-bar { gap: 8px; }
    .mhs-table { min-width: 380px; }
    .btn-detail span { display: none; }
    .btn-detail { padding: 5px 8px; }
    .pagination-wrap { justify-content: center; }
    .pagination-info { width: 100%; text-align: center; }
}
@media(max-width:576px){
    .hide-sm { display: none !important; }
    .mhs-table { min-width: 320px; }
    .filter-select { width: 100%; }
    .filter-bar .search-wrap { max-width: 100%; }
    .tbl-card-v2 { padding: 14px; }
    .mhs-avatar { width: 30px; height: 30px; font-size: 12px; }
    .mhs-table thead th,
    .mhs-table tbody td { padding: 10px 10px; }
}
</style>
@endpush

@section('topbar-actions')
<a href="{{ route('admin.mahasiswa.create') }}" class="btn-primary">
    <i class="bi bi-plus-lg"></i>
    <span class="btn-txt"> Tambah Mahasiswa</span>
</a>
@endsection

@section('content')

{{-- Banner --}}
@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg, #1E3A8A 0%, #2563EB 55%, #60A5FA 100%)',
    'icon'         => 'bi-people-fill',
    'title'        => 'Data Mahasiswa',
    'sub'          => 'Kelola seluruh data mahasiswa aktif Jurusan Teknologi Informasi',
    'chips'        => [
        ['icon' => 'bi-people-fill',       'label' => $mahasiswas->total() . ' Mahasiswa Terdaftar'],
        ['icon' => 'bi-grid-3x3-gap-fill', 'label' => $kelasList->count() . ' Kelas'],
        ['icon' => 'bi-person-check-fill', 'label' => 'Status Aktif'],
    ],
    'badge_num'    => $mahasiswas->total(),
    'badge_label'  => "Total\nMahasiswa",
])

<div class="card-white tbl-card-v2">

    {{-- ── Header + Filter ── --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:16px;">
        <div>
            <div class="tbl-title-v2">Daftar Mahasiswa</div>
            <div class="tbl-sub-v2">
                Menampilkan <strong>{{ $mahasiswas->firstItem() }}–{{ $mahasiswas->lastItem() }}</strong>
                dari <strong>{{ $mahasiswas->total() }}</strong> mahasiswa
                @if(request('search') || request('kelas'))
                · <span style="color:var(--blue);">Filter aktif</span>
                @endif
            </div>
        </div>
        @if(request('search') || request('kelas'))
        <a href="{{ route('admin.mahasiswa.index') }}"
           style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;color:#EF4444;text-decoration:none;padding:5px 12px;border:1.5px solid #FECACA;border-radius:20px;background:#FEF2F2;white-space:nowrap;">
            <i class="bi bi-x-circle-fill"></i> Reset Filter
        </a>
        @endif
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('admin.mahasiswa.index') }}">
        <div class="filter-bar">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari NIM atau nama...">
            </div>
            <select name="kelas" class="filter-select">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $kelas)
                <option value="{{ $kelas->id }}" {{ request('kelas')==$kelas->id ? 'selected':'' }}>
                    {{ $kelas->nama }}
                </option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary" style="padding:8px 16px;white-space:nowrap;">
                <i class="bi bi-funnel-fill"></i> Filter
            </button>
        </div>
    </form>

    {{-- ── Tabel ── --}}
    <div class="mhs-table-wrap">
        <table class="mhs-table">
            <thead>
                <tr>
                    <th style="width:40px;text-align:center;">#</th>
                    <th>Mahasiswa</th>
                    <th class="hide-mobile" style="text-align:center;">Kelas</th>
                    <th class="hide-mobile hide-sm" style="">Dosen PA</th>
                    <th class="hide-sm" style="text-align:center;">Angkatan</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;width:130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswas as $i => $mhs)
                @php
                    $colors = ['#2563EB','#16A34A','#7C3AED','#0891B2','#DB2777','#EA580C','#0D9488'];
                    $color  = $colors[($mahasiswas->firstItem() + $i - 1) % count($colors)];
                @endphp
                <tr>
                    <td style="text-align:center;font-size:12px;color:var(--text-3);font-weight:500;">
                        {{ $mahasiswas->firstItem() + $i }}
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="mhs-avatar" style="background:{{ $color }};">
                                {{ strtoupper(substr($mhs->nama, 0, 1)) }}
                            </div>
                            <div style="min-width:0;">
                                <div style="font-weight:600;color:var(--text-1);font-size:13.5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;">
                                    {{ $mhs->nama }}
                                </div>
                                <div style="font-size:11px;color:var(--text-3);font-family:monospace;margin-top:1px;">
                                    {{ $mhs->nim }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="hide-mobile" style="text-align:center;">
                        <span class="kelas-badge">{{ $mhs->kelas->nama ?? '-' }}</span>
                    </td>
                    <td class="hide-mobile hide-sm" style="font-size:12.5px;color:var(--text-2);max-width:160px;">
                        <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $mhs->dosenPa->nama ?? '-' }}
                        </div>
                    </td>
                    <td class="hide-sm" style="text-align:center;font-size:13px;font-weight:600;color:var(--text-2);">
                        {{ $mhs->angkatan }}
                    </td>
                    <td style="text-align:center;">
                        <span class="status-badge {{ $mhs->status==='aktif' ? 'status-aktif' : 'status-nonaktif' }}">
                            <span class="status-dot"></span>
                            {{ ucfirst($mhs->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-wrap">
                            <a href="{{ route('admin.mahasiswa.show', $mhs->id) }}" class="btn-detail">
                                <i class="bi bi-eye-fill"></i>
                                <span>Detail</span>
                            </a>
                            <a href="{{ route('admin.mahasiswa.edit', $mhs->id) }}" class="btn-icon btn-icon-edit" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="{{ route('admin.mahasiswa.destroy', $mhs->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus {{ addslashes($mhs->nama) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon btn-icon-del" title="Hapus">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state-v2">
                            <i class="bi bi-people"></i>
                            <p>
                                @if(request('search'))
                                    Tidak ada mahasiswa dengan nama/NIM <strong>"{{ request('search') }}"</strong>
                                @else
                                    Belum ada data mahasiswa.
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Pagination ── --}}
    @if($mahasiswas->hasPages())
    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan <strong>{{ $mahasiswas->firstItem() }}–{{ $mahasiswas->lastItem() }}</strong>
            dari <strong>{{ $mahasiswas->total() }}</strong> data
        </div>
        <div class="pagination-btns">
            {{-- Prev --}}
            @if($mahasiswas->onFirstPage())
                <span class="pg-btn disabled"><i class="bi bi-chevron-left"></i></span>
            @else
                <a href="{{ $mahasiswas->withQueryString()->previousPageUrl() }}" class="pg-btn">
                    <i class="bi bi-chevron-left"></i>
                </a>
            @endif

            {{-- Page numbers --}}
            @php
                $current  = $mahasiswas->currentPage();
                $last     = $mahasiswas->lastPage();
                $window   = 2; // berapa halaman di kiri/kanan current
                $from     = max(1, $current - $window);
                $to       = min($last, $current + $window);
            @endphp

            @if($from > 1)
                <a href="{{ $mahasiswas->withQueryString()->url(1) }}" class="pg-btn">1</a>
                @if($from > 2)
                    <span class="pg-btn disabled">…</span>
                @endif
            @endif

            @for($p = $from; $p <= $to; $p++)
                @if($p == $current)
                    <span class="pg-btn active">{{ $p }}</span>
                @else
                    <a href="{{ $mahasiswas->withQueryString()->url($p) }}" class="pg-btn">{{ $p }}</a>
                @endif
            @endfor

            @if($to < $last)
                @if($to < $last - 1)
                    <span class="pg-btn disabled">…</span>
                @endif
                <a href="{{ $mahasiswas->withQueryString()->url($last) }}" class="pg-btn">{{ $last }}</a>
            @endif

            {{-- Next --}}
            @if($mahasiswas->hasMorePages())
                <a href="{{ $mahasiswas->withQueryString()->nextPageUrl() }}" class="pg-btn">
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <span class="pg-btn disabled"><i class="bi bi-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif

</div>
@endsection