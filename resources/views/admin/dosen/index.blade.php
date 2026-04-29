@extends('layouts.admin')
@section('title', 'Data Dosen')
@section('page-title', 'Data Dosen')
@section('page-sub', 'Kelola data dosen Jurusan TI')
@section('topbar-actions')
<a href="{{ route('admin.dosen.create') }}" class="primary-btn"><i class="bi bi-plus-lg"></i> Tambah Dosen</a>
@endsection
@section('content')
<div class="section-card">
    <div class="section-header">
        <div>
            <div class="section-title">Daftar Dosen</div>
            <div class="section-subtitle">Total: {{ $dosens->total() }} dosen</div>
        </div>
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIP / nama..."
                   style="border:1.5px solid #e4eaf5;border-radius:10px;padding:7px 14px;font-size:13px;outline:none;width:220px;font-family:'Plus Jakarta Sans',sans-serif;">
            <button type="submit" class="primary-btn">Cari</button>
        </form>
    </div>
    <table style="width:100%;border-collapse:separate;border-spacing:0 5px;">
        <thead><tr>
            <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;">NIP</th>
            <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;">Nama</th>
            <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;">Email</th>
            <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;">No. HP</th>
            <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">Aksi</th>
        </tr></thead>
        <tbody>
            @forelse($dosens as $dosen)
            <tr style="background:#f8faff;">
                <td style="padding:10px 12px;border-radius:10px 0 0 10px;font-family:'Space Mono',monospace;font-size:11px;color:#8da3c0;">{{ $dosen->nip }}</td>
                <td style="padding:10px 12px;font-weight:600;color:var(--navy);">{{ $dosen->nama }}</td>
                <td style="padding:10px 12px;font-size:12px;color:#5a6e8c;">{{ $dosen->user->email ?? '-' }}</td>
                <td style="padding:10px 12px;font-size:12px;color:#5a6e8c;">{{ $dosen->no_hp ?? '-' }}</td>
                <td style="padding:10px 12px;text-align:center;border-radius:0 10px 10px 0;">
                    <a href="{{ route('admin.dosen.edit', $dosen->id) }}" style="background:rgba(0,180,200,0.1);color:var(--teal);border-radius:7px;padding:4px 10px;font-size:11px;font-weight:600;text-decoration:none;margin-right:4px;"><i class="bi bi-pencil"></i> Edit</a>
                    <form action="{{ route('admin.dosen.destroy', $dosen->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus dosen {{ $dosen->nama }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:rgba(232,51,74,0.1);color:var(--danger-red);border:none;border-radius:7px;padding:4px 10px;font-size:11px;font-weight:600;cursor:pointer;"><i class="bi bi-trash"></i> Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;padding:30px;color:#8da3c0;"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Tidak ada data dosen.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-3">{{ $dosens->withQueryString()->links() }}</div>
</div>
@endsection