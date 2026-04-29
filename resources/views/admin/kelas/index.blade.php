@extends('layouts.admin')
@section('title','Kelas')
@section('page-title','Kelola Kelas')
@section('page-sub','Daftar kelas aktif seluruh angkatan')
@section('topbar-actions')
<a href="{{ route('admin.kelas.create') }}" class="primary-btn"><i class="bi bi-plus-lg"></i> Tambah Kelas</a>
@endsection
@section('content')
<div class="section-card">
    <div class="section-title mb-4">Daftar Kelas</div>
    <table style="width:100%;border-collapse:separate;border-spacing:0 5px;">
        <thead><tr>
            <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;">Nama Kelas</th>
            <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">Semester</th>
            <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;">Dosen PA</th>
            <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">Mahasiswa</th>
            <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;">Tahun Akademik</th>
            <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">Aksi</th>
        </tr></thead>
        <tbody>
            @forelse($kelas as $k)
            <tr style="background:#f8faff;">
                <td style="padding:10px 12px;border-radius:10px 0 0 10px;">
                    <span style="background:rgba(0,180,200,0.1);color:var(--teal);border-radius:8px;padding:4px 14px;font-size:13px;font-weight:700;">{{ $k->nama }}</span>
                </td>
                <td style="padding:10px 12px;text-align:center;font-weight:700;color:var(--navy);">{{ $k->semester }}</td>
                <td style="padding:10px 12px;font-size:12px;color:#5a6e8c;">{{ $k->dosenPa->nama ?? '-' }}</td>
                <td style="padding:10px 12px;text-align:center;">
                    <span style="background:#f0f4fc;border-radius:6px;padding:2px 10px;font-size:12px;font-weight:700;font-family:'Space Mono',monospace;">{{ $k->mahasiswas->count() }}</span>
                </td>
                <td style="padding:10px 12px;font-size:12px;color:#5a6e8c;">{{ $k->tahun_akademik }}</td>
                <td style="padding:10px 12px;text-align:center;border-radius:0 10px 10px 0;">
                    <a href="{{ route('admin.kelas.edit', $k->id) }}" style="background:rgba(0,180,200,0.1);color:var(--teal);border-radius:7px;padding:4px 10px;font-size:11px;font-weight:600;text-decoration:none;margin-right:4px;"><i class="bi bi-pencil"></i> Edit</a>
                    <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus kelas {{ $k->nama }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:rgba(232,51,74,0.1);color:var(--danger-red);border:none;border-radius:7px;padding:4px 10px;font-size:11px;font-weight:600;cursor:pointer;"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:30px;color:#8da3c0;">Tidak ada data kelas.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-3">{{ $kelas->links() }}</div>
</div>
@endsection