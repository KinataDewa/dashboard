@extends('layouts.admin')
 
@section('title', 'Data Mahasiswa')
@section('page-title', 'Data Mahasiswa')
@section('page-sub', 'Kelola seluruh data mahasiswa aktif')
 
@section('topbar-actions')
<a href="{{ route('admin.mahasiswa.create') }}" class="primary-btn">
    <i class="bi bi-plus-lg"></i> Tambah Mahasiswa
</a>
@endsection
 
@section('content')
<div class="section-card">
    <div class="section-header">
        <div>
            <div class="section-title">Daftar Mahasiswa</div>
            <div class="section-subtitle">Total: {{ $mahasiswas->total() }} mahasiswa</div>
        </div>
        <form method="GET" action="{{ route('admin.mahasiswa.index') }}" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIM / nama..."
                   style="border:1.5px solid #e4eaf5;border-radius:10px;padding:7px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;width:220px;">
            <select name="kelas" style="border:1.5px solid #e4eaf5;border-radius:10px;padding:7px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $kelas)
                <option value="{{ $kelas->id }}" {{ request('kelas') == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="primary-btn">Filter</button>
        </form>
    </div>
 
    <table style="width:100%;border-collapse:separate;border-spacing:0 5px;">
        <thead>
            <tr>
                <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;">NIM</th>
                <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;">Nama</th>
                <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">Kelas</th>
                <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;">Dosen PA</th>
                <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">Status</th>
                <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswas as $mhs)
            <tr style="background:#f8faff;">
                <td style="padding:10px 12px;border-radius:10px 0 0 10px;font-family:'Space Mono',monospace;font-size:11px;color:#8da3c0;">{{ $mhs->nim }}</td>
                <td style="padding:10px 12px;">
                    <div style="font-weight:600;color:var(--navy);font-size:13px;">{{ $mhs->nama }}</div>
                    <div style="font-size:11px;color:#8da3c0;">{{ $mhs->user->email ?? '-' }}</div>
                </td>
                <td style="padding:10px 12px;text-align:center;">
                    <span style="background:rgba(0,180,200,0.1);color:var(--teal);border-radius:6px;padding:2px 10px;font-size:11px;font-weight:700;">
                        {{ $mhs->kelas->nama ?? '-' }}
                    </span>
                </td>
                <td style="padding:10px 12px;font-size:12px;color:#5a6e8c;">{{ $mhs->dosenPa->nama ?? '-' }}</td>
                <td style="padding:10px 12px;text-align:center;">
                    <span class="status-{{ $mhs->status === 'aktif' ? 'active' : 'inactive' }}">
                        ● {{ ucfirst($mhs->status) }}
                    </span>
                </td>
                <td style="padding:10px 12px;text-align:center;border-radius:0 10px 10px 0;">
                    <a href="{{ route('admin.mahasiswa.edit', $mhs->id) }}"
                       style="background:rgba(0,180,200,0.1);color:var(--teal);border-radius:7px;padding:4px 10px;font-size:11px;font-weight:600;text-decoration:none;margin-right:4px;">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('admin.mahasiswa.destroy', $mhs->id) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Hapus mahasiswa {{ $mhs->nama }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:rgba(232,51,74,0.1);color:var(--danger-red);border:none;border-radius:7px;padding:4px 10px;font-size:11px;font-weight:600;cursor:pointer;">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:30px;color:#8da3c0;">
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                    Tidak ada data mahasiswa.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
 
    <div class="mt-3">
        {{ $mahasiswas->withQueryString()->links() }}
    </div>
</div>
@endsection