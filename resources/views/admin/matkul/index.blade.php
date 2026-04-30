@extends('layouts.admin')
@section('title','Mata Kuliah')
@section('page-title','Mata Kuliah')
@section('page-sub','Kelola mata kuliah seluruh kelas')
 
@section('topbar-actions')
<a href="{{ route('admin.matkul.create') }}" class="btn-primary"><i class="bi bi-plus-lg"></i> Tambah Matkul</a>
@endsection
 
@section('content')

@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg, #3B0764 0%, #7C3AED 55%, #A78BFA 100%)',
    'icon'         => 'bi-book-fill',
    'title'        => 'Mata Kuliah',
    'sub'          => 'Kelola mata kuliah seluruh kelas Jurusan Teknologi Informasi',
    'chips'        => [
        ['icon' => 'bi-book-fill',         'label' => $matkuls->total() . ' Mata Kuliah'],
        ['icon' => 'bi-layers-fill',       'label' => 'Multi Semester'],
        ['icon' => 'bi-person-badge-fill', 'label' => 'Terhubung Dosen'],
    ],
    'badge_num'    => $matkuls->total(),
    'badge_label'  => "Total\nMata Kuliah",
])

<div class="section-label">Daftar Mata Kuliah</div>
<div class="card-white tbl-card-v2">
    <div class="tbl-head-v2">
        <div>
            <div class="tbl-title-v2">Mata Kuliah</div>
            <div class="tbl-sub-v2">Total: <strong>{{ $matkuls->total() }}</strong> mata kuliah</div>
        </div>
        <form method="GET" class="tbl-actions">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode / nama...">
            </div>
            <select name="kelas" class="form-select-ac" style="width:auto;padding:6px 32px 6px 11px;">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $kelas)
                <option value="{{ $kelas->id }}" {{ request('kelas')==$kelas->id ? 'selected':'' }}>{{ $kelas->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary" style="padding:6px 16px;">Filter</button>
        </form>
    </div>
 
    <div style="overflow-x:auto;">
        <table class="ac-table-v2">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Kode</th>
                    <th>Nama Mata Kuliah</th>
                    <th style="text-align:center;">SKS</th>
                    <th style="text-align:center;">Semester</th>
                    <th style="text-align:center;">Kelas</th>
                    <th>Dosen Pengampu</th>
                    <th style="text-align:center;width:100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($matkuls as $i => $mk)
                <tr>
                    <td><span class="tbl-number">{{ $matkuls->firstItem() + $i }}</span></td>
                    <td>
                        <span style="font-family:monospace;font-size:12.5px;background:#F1F5F9;color:var(--text-1);padding:3px 8px;border-radius:6px;font-weight:600;">{{ $mk->kode }}</span>
                    </td>
                    <td style="font-weight:500;color:var(--text-1);">{{ $mk->nama }}</td>
                    <td style="text-align:center;">
                        <span style="background:#F5F3FF;color:#7C3AED;border-radius:20px;padding:2px 10px;font-size:12px;font-weight:700;">{{ $mk->sks }} SKS</span>
                    </td>
                    <td style="text-align:center;font-weight:700;color:var(--text-1);">{{ $mk->semester }}</td>
                    <td style="text-align:center;">
                        <span class="badge" style="background:#EFF6FF;color:#1D4ED8;font-weight:700;">{{ $mk->kelas->nama ?? '-' }}</span>
                    </td>
                    <td style="font-size:13px;color:var(--text-2);">{{ $mk->dosen->nama ?? '-' }}</td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:5px;justify-content:center;">
                            <a href="{{ route('admin.matkul.edit', $mk->id) }}" class="btn-edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.matkul.destroy', $mk->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus matkul {{ addslashes($mk->nama) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state"><i class="bi bi-book"></i><p>Tidak ada data mata kuliah.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $matkuls->withQueryString()->links('vendor.pagination.custom') }}
</div>
@endsection