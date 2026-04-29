@extends('layouts.admin')
@section('title', 'Edit Mahasiswa')
@section('page-title', 'Edit Mahasiswa')
@section('page-sub', $mahasiswa->nama . ' — ' . $mahasiswa->nim)
 
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="section-card">
            <div class="section-title mb-4">Edit Data Mahasiswa</div>
 
            @if($errors->any())
            <div class="alert alert-danger" style="border-radius:10px;">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif
 
            <form action="{{ route('admin.mahasiswa.update', $mahasiswa->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label style="font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;">NIM</label>
                        <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" required
                               style="width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;">
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" required
                               style="width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;">
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;">Kelas</label>
                        <select name="kelas_id" style="width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;">
                            @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ $mahasiswa->kelas_id==$kelas->id ? 'selected':'' }}>{{ $kelas->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;">Dosen PA</label>
                        <select name="dosen_pa_id" style="width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;">
                            @foreach($dosenList as $dosen)
                            <option value="{{ $dosen->id }}" {{ $mahasiswa->dosen_pa_id==$dosen->id ? 'selected':'' }}>{{ $dosen->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;">Angkatan</label>
                        <input type="number" name="angkatan" value="{{ old('angkatan', $mahasiswa->angkatan) }}"
                               style="width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;">
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;">Status</label>
                        <select name="status" style="width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;">
                            @foreach(['aktif','cuti','lulus','keluar'] as $s)
                            <option value="{{ $s }}" {{ $mahasiswa->status==$s ? 'selected':'' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="primary-btn"><i class="bi bi-check-lg"></i> Update</button>
                    <a href="{{ route('admin.mahasiswa.index') }}" style="background:#f0f4fc;color:var(--navy);border:none;border-radius:10px;padding:8px 18px;font-size:13px;font-weight:600;text-decoration:none;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection