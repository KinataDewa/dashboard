@extends('layouts.admin')
@section('title', 'Data Kompensasi')
@section('page-title', 'Kompensasi Alpha')
@section('page-sub', 'Kelola data kompensasi ketidakhadiran mahasiswa')

@push('styles')
<style>
.sp-badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:700;white-space:nowrap;}
.status-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:700;white-space:nowrap;}
.ttd-wrap{display:flex;gap:4px;justify-content:center;}
.ttd-dot{width:10px;height:10px;border-radius:50%;display:inline-block;}
.filter-bar{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;align-items:center;}
.filter-select{padding:8px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-1);background:var(--white);outline:none;cursor:pointer;}
.filter-select:focus{border-color:var(--blue);}
@media(max-width:768px){
    .hide-mobile{display:none;}
    .filter-bar .search-wrap{flex:1;min-width:140px;}
}
@media(max-width:576px){
    .hide-sm{display:none;}
}
</style>
@endpush

@section('topbar-actions')
<a href="{{ route('admin.kompensasi.create') }}" class="btn-primary">
    <i class="bi bi-plus-lg"></i> Buat Kompensasi
</a>
@endsection

@section('content')

{{-- Banner --}}
@include('components.page-banner', [
    'gradient'  => 'linear-gradient(135deg, #1A0A0A 0%, #7F1D1D 50%, #DC2626 100%)',
    'icon'      => 'bi-clipboard2-check-fill',
    'title'     => 'Kompensasi Alpha',
    'sub'       => 'Kelola surat dan status kompensasi ketidakhadiran mahasiswa',
    'chips'     => [
        ['icon'=>'bi-hourglass-split',       'label'=> $totalPending . ' Pending'],
        ['icon'=>'bi-check-circle-fill',     'label'=> $totalLunas . ' Lunas'],
        ['icon'=>'bi-clipboard2-fill',       'label'=> $totalSemua . ' Total'],
    ],
    'badge_num'   => $totalPending,
    'badge_label' => "Perlu\nDitangani",
    'badge2_num'  => $totalLunas,
    'badge2_label'=> "Sudah\nLunas",
])

{{-- Alert success --}}
@if(session('success'))
<div style="background:#F0FDF4;border:1px solid #86EFAC;border-left:3px solid #22C55E;border-radius:9px;padding:11px 14px;font-size:13px;color:#166534;display:flex;align-items:center;gap:8px;margin-bottom:16px;">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif

<div class="card-white tbl-card-v2">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:16px;">
        <div>
            <div class="tbl-title-v2">Daftar Kompensasi</div>
            <div class="tbl-sub-v2">Total <strong>{{ $kompensasis->total() }}</strong> data kompensasi</div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            @if(request()->hasAny(['search','status','semester']))
            <a href="{{ route('admin.kompensasi.index') }}"
               style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;color:#EF4444;text-decoration:none;padding:5px 12px;border:1.5px solid #FECACA;border-radius:20px;background:#FEF2F2;">
                <i class="bi bi-x-circle-fill"></i> Reset Filter
            </a>
            @endif
            <a href="{{ route('admin.kompensasi.create') }}"
               style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:8px;font-size:13.5px;font-weight:600;background:var(--blue);color:#fff;text-decoration:none;">
                <i class="bi bi-plus-circle-fill" style="font-size:13px;"></i>
                Tambah Kompensasi
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.kompensasi.index') }}">
        <div class="filter-bar">
            <div class="search-wrap" style="flex:1;min-width:160px;max-width:260px;">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / NIM...">
            </div>
            <select name="status" class="filter-select">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status')=='pending' ? 'selected':'' }}>⏳ Pending</option>
                <option value="lunas"   {{ request('status')=='lunas'   ? 'selected':'' }}>✅ Lunas</option>
            </select>
            <select name="semester" class="filter-select">
                <option value="">Semua Semester</option>
                @foreach($semesterList as $sem)
                <option value="{{ $sem }}" {{ request('semester')==$sem ? 'selected':'' }}>Semester {{ $sem }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary" style="padding:8px 16px;white-space:nowrap;">
                <i class="bi bi-funnel-fill"></i> Filter
            </button>
        </div>
    </form>

    {{-- Tabel --}}
    <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
        <table class="ac-table-v2" style="min-width:600px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mahasiswa</th>
                    <th style="text-align:center;">Semester</th>
                    <th style="text-align:center;">Alpha</th>
                    <th style="text-align:center;">SP</th>
                    <th style="text-align:center;">Jam Kompen</th>
                    <th class="hide-mobile" style="text-align:center;">TTD</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kompensasis as $i => $komp)
                <tr style="{{ $komp->status==='pending' ? 'background:rgba(245,158,11,.02);' : '' }}">
                    <td style="font-size:12px;color:var(--text-3);">{{ $kompensasis->firstItem() + $i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:9px;">
                            <div style="width:34px;height:34px;border-radius:50%;background:var(--blue);color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">
                                {{ strtoupper(substr($komp->mahasiswa->nama,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13.5px;color:var(--text-1);">{{ $komp->mahasiswa->nama }}</div>
                                <div style="font-size:11px;color:var(--text-3);font-family:monospace;">{{ $komp->mahasiswa->nim }} · {{ $komp->mahasiswa->kelas->nama ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center;font-weight:600;color:var(--text-2);">{{ $komp->semester }}</td>
                    <td style="text-align:center;font-weight:700;color:#EF4444;">{{ $komp->jam_alpha }}j</td>
                    <td style="text-align:center;">
                        <span class="sp-badge" style="background:{{ $komp->sp_bg }};color:{{ $komp->sp_color }};">
                            {{ $komp->sp_label }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <div style="font-weight:800;font-size:15px;color:var(--text-1);">{{ $komp->jam_kompen_wajib }}j</div>
                        @if($komp->multiplier > 1)
                        <div style="font-size:10.5px;color:#EF4444;font-weight:600;">×{{ $komp->multiplier }} (terlambat)</div>
                        @endif
                    </td>
                    <td class="hide-mobile" style="text-align:center;">
                        <div class="ttd-wrap">
                            <span title="TTD Admin" style="display:inline-flex;align-items:center;gap:3px;font-size:11px;font-weight:600;color:{{ $komp->ttd_admin ? '#166534' : '#9CA3AF' }};">
                                <span class="ttd-dot" style="background:{{ $komp->ttd_admin ? '#22C55E' : '#D1D5DB' }};"></span>
                                Admin
                            </span>
                            <span style="color:var(--text-3);">·</span>
                            <span title="TTD Kajur" style="display:inline-flex;align-items:center;gap:3px;font-size:11px;font-weight:600;color:{{ $komp->ttd_kajur ? '#166534' : '#9CA3AF' }};">
                                <span class="ttd-dot" style="background:{{ $komp->ttd_kajur ? '#22C55E' : '#D1D5DB' }};"></span>
                                Kajur
                            </span>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        @if($komp->status === 'lunas')
                        <span class="status-badge" style="background:#DCFCE7;color:#166534;">
                            <i class="bi bi-check-circle-fill"></i> Lunas
                        </span>
                        @else
                        <span class="status-badge" style="background:#FEF3C7;color:#92400E;">
                            <i class="bi bi-hourglass-split"></i> Pending
                        </span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <a href="{{ route('admin.kompensasi.show', $komp->id) }}"
                           style="display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:7px;font-size:12px;font-weight:600;background:var(--blue);color:#fff;text-decoration:none;">
                            <i class="bi bi-eye-fill"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:48px;color:var(--text-3);">
                        <i class="bi bi-clipboard2-x" style="font-size:36px;display:block;margin-bottom:10px;opacity:.3;"></i>
                        Belum ada data kompensasi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
@if($kompensasis->hasPages())
<div style="display:flex;align-items:center;justify-content:space-between;padding:16px 4px 4px;flex-wrap:wrap;gap:10px;border-top:1px solid var(--border);margin-top:8px;">
    <div style="font-size:13px;color:var(--text-2);">
        Menampilkan <strong>{{ $kompensasis->firstItem() }}–{{ $kompensasis->lastItem() }}</strong>
        dari <strong>{{ $kompensasis->total() }}</strong> data
    </div>
    <div style="display:flex;gap:4px;flex-wrap:wrap;">
        @php
            $cur  = $kompensasis->currentPage();
            $last = $kompensasis->lastPage();
            $from = max(1, $cur - 2);
            $to   = min($last, $cur + 2);
            $pgStyle       = 'min-width:34px;height:34px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;border:1.5px solid var(--border);background:var(--white);color:var(--text-2);text-decoration:none;font-size:13px;';
            $pgActiveStyle = 'min-width:34px;height:34px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;border:1.5px solid var(--blue);background:var(--blue);color:#fff;font-size:13px;font-weight:700;';
            $pgDisabledStyle = 'min-width:34px;height:34px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;border:1.5px solid var(--border);background:var(--white);color:var(--text-3);opacity:.4;font-size:13px;';
        @endphp

        {{-- Prev --}}
        @if($kompensasis->onFirstPage())
            <span style="{{ $pgDisabledStyle }}"><i class="bi bi-chevron-left"></i></span>
        @else
            <a href="{{ $kompensasis->withQueryString()->previousPageUrl() }}" style="{{ $pgStyle }}"><i class="bi bi-chevron-left"></i></a>
        @endif

        {{-- Halaman pertama + ellipsis --}}
        @if($from > 1)
            <a href="{{ $kompensasis->withQueryString()->url(1) }}" style="{{ $pgStyle }}">1</a>
            @if($from > 2)
                <span style="{{ $pgDisabledStyle }}">…</span>
            @endif
        @endif

        {{-- Halaman window --}}
        @for($p = $from; $p <= $to; $p++)
            @if($p == $cur)
                <span style="{{ $pgActiveStyle }}">{{ $p }}</span>
            @else
                <a href="{{ $kompensasis->withQueryString()->url($p) }}" style="{{ $pgStyle }}">{{ $p }}</a>
            @endif
        @endfor

        {{-- Halaman terakhir + ellipsis --}}
        @if($to < $last)
            @if($to < $last - 1)
                <span style="{{ $pgDisabledStyle }}">…</span>
            @endif
            <a href="{{ $kompensasis->withQueryString()->url($last) }}" style="{{ $pgStyle }}">{{ $last }}</a>
        @endif

        {{-- Next --}}
        @if($kompensasis->hasMorePages())
            <a href="{{ $kompensasis->withQueryString()->nextPageUrl() }}" style="{{ $pgStyle }}"><i class="bi bi-chevron-right"></i></a>
        @else
            <span style="{{ $pgDisabledStyle }}"><i class="bi bi-chevron-right"></i></span>
        @endif
    </div>
</div>
@endif

</div>
@endsection