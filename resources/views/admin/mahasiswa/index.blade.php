@extends('layouts.admin')
@section('title','Data Mahasiswa')
@section('page-title','Data Mahasiswa')
@section('page-sub','Kelola seluruh data mahasiswa aktif')
 
@section('topbar-actions')
<a href="{{ route('admin.mahasiswa.create') }}" class="btn-primary">
    <i class="bi bi-plus-lg"></i> Tambah Mahasiswa
</a>
@endsection
 
@section('content')

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

<div class="section-label">Daftar Mahasiswa</div>
<div class="card-white tbl-card-v2">
    <div class="tbl-head-v2">
        <div>
            <div class="tbl-title-v2">Data Mahasiswa</div>
            <div class="tbl-sub-v2">Total: <strong>{{ $mahasiswas->total() }}</strong> mahasiswa terdaftar</div>
        </div>
        <form method="GET" class="tbl-actions">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIM / nama...">
            </div>
            <select name="kelas" class="form-select-ac" style="width:auto;padding:6px 32px 6px 11px;">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $kelas)
                <option value="{{ $kelas->id }}" {{ request('kelas')==$kelas->id ? 'selected':'' }}>{{ $kelas->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary" style="padding:6px 16px;">Filter</button>
            @if(request('search') || request('kelas'))
            <a href="{{ route('admin.mahasiswa.index') }}" style="display:inline-flex;align-items:center;gap:5px;font-size:13px;color:var(--text-2);font-weight:500;text-decoration:none;padding:6px 10px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--white);">
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
                    <th>Mahasiswa</th>
                    <th style="text-align:center;">Kelas</th>
                    <th>Dosen PA</th>
                    <th style="text-align:center;">Angkatan</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;width:120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswas as $i => $mhs)
                <tr>
                    <td><span class="tbl-number">{{ $mahasiswas->firstItem() + $i }}</span></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:50%;background:var(--blue);color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">
                                {{ strtoupper(substr($mhs->nama, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;color:var(--text-1);font-size:13.5px;">{{ $mhs->nama }}</div>
                                <div style="font-size:11px;color:var(--text-3);">{{ $mhs->nim }} · {{ $mhs->user->email ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <span class="badge" style="background:#EFF6FF;color:#1D4ED8;font-weight:700;">{{ $mhs->kelas->nama ?? '-' }}</span>
                    </td>
                    <td style="font-size:13px;color:var(--text-2);">{{ $mhs->dosenPa->nama ?? '-' }}</td>
                    <td style="text-align:center;font-size:13px;color:var(--text-2);">{{ $mhs->angkatan }}</td>
                    <td style="text-align:center;">
                        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:700;background:{{ $mhs->status==='aktif' ? '#DCFCE7' : '#FEE2E2' }};color:{{ $mhs->status==='aktif' ? '#166534' : '#991B1B' }};">
                            <span style="width:6px;height:6px;border-radius:50%;background:currentColor;"></span>
                            {{ ucfirst($mhs->status) }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:5px;justify-content:center;">
                            <a href="{{ route('admin.mahasiswa.edit', $mhs->id) }}" class="btn-edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.mahasiswa.destroy', $mhs->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus mahasiswa {{ addslashes($mhs->nama) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <p>Tidak ada data mahasiswa{{ request('search') ? ' untuk pencarian "'.request('search').'"' : '' }}.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
 
    {{-- Pagination --}}
    {{ $mahasiswas->withQueryString()->links('vendor.pagination.custom') }}
</div>
@endsection