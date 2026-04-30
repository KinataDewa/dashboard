@extends('layouts.admin')
@section('title', 'Data Mahasiswa')
@section('page-title', 'Data Mahasiswa')
@section('page-sub', 'Kelola seluruh data mahasiswa aktif')
 
@section('topbar-actions')
<a href="{{ route('admin.mahasiswa.create') }}" class="btn-primary">
    <i class="bi bi-plus-lg"></i> Tambah Mahasiswa
</a>
@endsection
 
@section('content')
<div class="section-label">Daftar Mahasiswa</div>
<div class="card-white tbl-card-v2">
    <div class="tbl-head-v2">
        <div>
            <div class="tbl-title-v2">Data Mahasiswa</div>
            <div class="tbl-sub-v2">Total: {{ $mahasiswas->total() }} mahasiswa terdaftar</div>
        </div>
        <form method="GET" class="tbl-actions">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIM / nama...">
            </div>
            <select name="kelas" class="select-semester" style="width:auto;">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $kelas)
                <option value="{{ $kelas->id }}" {{ request('kelas') == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary">Filter</button>
        </form>
    </div>
 
    <div style="overflow-x:auto;">
        <table class="ac-table-v2">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th style="text-align:center;">Kelas</th>
                    <th>Dosen PA</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswas as $mhs)
                <tr>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-2);">{{ $mhs->nim }}</td>
                    <td>
                        <div style="font-weight:600;color:var(--text-1);">{{ $mhs->nama }}</div>
                        <div style="font-size:11.5px;color:var(--text-3);">{{ $mhs->user->email ?? '-' }}</div>
                    </td>
                    <td style="text-align:center;">
                        <span class="badge" style="background:#EFF6FF;color:#1D4ED8;">{{ $mhs->kelas->nama ?? '-' }}</span>
                    </td>
                    <td style="font-size:13px;color:var(--text-2);">{{ $mhs->dosenPa->nama ?? '-' }}</td>
                    <td style="text-align:center;">
                        <span class="{{ $mhs->status === 'aktif' ? 'status-active' : 'status-inactive' }}">
                            ● {{ ucfirst($mhs->status) }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <a href="{{ route('admin.mahasiswa.edit', $mhs->id) }}" class="btn-edit">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('admin.mahasiswa.destroy', $mhs->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus mahasiswa {{ $mhs->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:36px;color:var(--text-3);">
                        <i class="bi bi-inbox" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                        Tidak ada data mahasiswa.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
 
    <div style="margin-top:16px;">
        {{ $mahasiswas->withQueryString()->links() }}
    </div>
</div>
@endsection