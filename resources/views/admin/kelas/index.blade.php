@extends('layouts.admin')
@section('title','Kelas')
@section('page-title','Kelola Kelas')
@section('page-sub','Daftar kelas aktif seluruh angkatan')
 
@section('topbar-actions')
<a href="{{ route('admin.kelas.create') }}" class="btn-primary"><i class="bi bi-plus-lg"></i> Tambah Kelas</a>
@endsection
 
@section('content')
<div class="section-label">Daftar Kelas</div>
<div class="card-white tbl-card-v2">
    <div class="tbl-head-v2">
        <div>
            <div class="tbl-title-v2">Data Kelas</div>
            <div class="tbl-sub-v2">Total: <strong>{{ $kelas->total() }}</strong> kelas aktif</div>
        </div>
    </div>
 
    <div style="overflow-x:auto;">
        <table class="ac-table-v2">
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th style="text-align:center;">Semester</th>
                    <th>Program Studi</th>
                    <th>Dosen PA</th>
                    <th style="text-align:center;">Mahasiswa</th>
                    <th style="text-align:center;">Tahun Akademik</th>
                    <th style="text-align:center;width:100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelas as $k)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:38px;height:38px;border-radius:10px;background:#EFF6FF;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-grid-3x3-gap-fill" style="color:#2563EB;font-size:16px;"></i>
                            </div>
                            <div>
                                <div style="font-size:15px;font-weight:800;color:var(--blue);">{{ $k->nama }}</div>
                                <div style="font-size:11px;color:var(--text-3);">{{ $k->prodi }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <span style="background:#F5F3FF;color:#7C3AED;border-radius:20px;padding:3px 12px;font-size:12px;font-weight:700;">Sem {{ $k->semester }}</span>
                    </td>
                    <td style="font-size:13px;color:var(--text-2);">{{ $k->prodi }}</td>
                    <td style="font-size:13px;color:var(--text-2);">{{ $k->dosenPa->nama ?? '-' }}</td>
                    <td style="text-align:center;">
                        <span style="font-size:18px;font-weight:800;color:{{ $k->mahasiswas->count() > 0 ? 'var(--blue)' : 'var(--text-3)' }};">
                            {{ $k->mahasiswas->count() }}
                        </span>
                        <div style="font-size:10px;color:var(--text-3);">mahasiswa</div>
                    </td>
                    <td style="text-align:center;">
                        <span style="background:#F0FDF4;color:#166534;border-radius:20px;padding:3px 12px;font-size:12px;font-weight:600;">{{ $k->tahun_akademik }}</span>
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:5px;justify-content:center;">
                            <a href="{{ route('admin.kelas.edit', $k->id) }}" class="btn-edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus kelas {{ $k->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7"><div class="empty-state"><i class="bi bi-grid-3x3-gap"></i><p>Tidak ada data kelas.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $kelas->links('vendor.pagination.custom') }}
</div>
@endsection