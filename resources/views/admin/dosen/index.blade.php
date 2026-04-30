@extends('layouts.admin')
@section('title','Data Dosen')
@section('page-title','Data Dosen')
@section('page-sub','Kelola data dosen Jurusan TI')

@section('topbar-actions')
<a href="{{ route('admin.dosen.create') }}" class="btn-primary">
    <i class="bi bi-plus-lg"></i> Tambah Dosen
</a>
@endsection
 
@section('content')

@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg, #14532D 0%, #15803D 55%, #22C55E 100%)',
    'icon'         => 'bi-person-badge-fill',
    'title'        => 'Data Dosen',
    'sub'          => 'Kelola data dosen pengampu dan Dosen Pembimbing Akademik',
    'chips'        => [
        ['icon' => 'bi-person-badge-fill', 'label' => $dosens->total() . ' Dosen Aktif'],
        ['icon' => 'bi-mortarboard-fill',  'label' => 'Pengampu & DPA'],
    ],
    'badge_num'    => $dosens->total(),
    'badge_label'  => "Total\nDosen",
])

<div class="section-label">Daftar Dosen</div>
<div class="card-white tbl-card-v2">
    <div class="tbl-head-v2">
        <div>
            <div class="tbl-title-v2">Data Dosen</div>
            <div class="tbl-sub-v2">Total: <strong>{{ $dosens->total() }}</strong> dosen</div>
        </div>
        <form method="GET" class="tbl-actions">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIP / nama...">
            </div>
            <button type="submit" class="btn-primary" style="padding:6px 16px;">Cari</button>
            @if(request('search'))
            <a href="{{ route('admin.dosen.index') }}" style="display:inline-flex;align-items:center;gap:5px;font-size:13px;color:var(--text-2);text-decoration:none;padding:6px 10px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--white);">
                <i class="bi bi-x"></i> Reset
            </a>
            @endif
        </form>
    </div>
 
    <div style="overflow-x:auto;">
        <table class="ac-table-v2">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Dosen</th>
                    <th>Email</th>
                    <th>NIP</th>
                    <th style="text-align:center;">No. HP</th>
                    <th style="text-align:center;width:120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dosens as $i => $dosen)
                <tr>
                    <td><span class="tbl-number">{{ $dosens->firstItem() + $i }}</span></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:50%;background:#16A34A;color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">
                                {{ strtoupper(substr($dosen->nama, 0, 1)) }}
                            </div>
                            <div style="font-weight:600;color:var(--text-1);">{{ $dosen->nama }}</div>
                        </div>
                    </td>
                    <td style="font-size:13px;color:var(--text-2);">{{ $dosen->user->email ?? '-' }}</td>
                    <td style="font-size:12px;font-family:monospace;color:var(--text-3);">{{ $dosen->nip }}</td>
                    <td style="text-align:center;font-size:13px;color:var(--text-2);">{{ $dosen->no_hp ?? '—' }}</td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:5px;justify-content:center;">
                            <a href="{{ route('admin.dosen.edit', $dosen->id) }}" class="btn-edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.dosen.destroy', $dosen->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus dosen {{ addslashes($dosen->nama) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><i class="bi bi-person-badge"></i><p>Tidak ada data dosen.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $dosens->withQueryString()->links('vendor.pagination.custom') }}
</div>
@endsection