@extends('layouts.admin')
@section('title', 'Tambah Mahasiswa')
@section('page-title', 'Tambah Mahasiswa')
@section('page-sub', 'Buat akun dan data mahasiswa baru')
 
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="section-card">
            <div class="section-title mb-4">Form Tambah Mahasiswa</div>
 
            @if($errors->any())
            <div class="alert alert-danger" style="border-radius:10px;">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif
 
            <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label style="font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;">NIM *</label>
                        <input type="text" name="nim" value="{{ old('nim') }}" required
                               style="width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;">
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;">Nama Lengkap *</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required
                               style="width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;">
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               style="width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;">
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;">Password *</label>
                        <input type="password" name="password" required
                               style="width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;">
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;">Kelas *</label>
                        <select name="kelas_id" required style="width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ old('kelas_id')==$kelas->id ? 'selected':'' }}>{{ $kelas->nama }} (Sem {{ $kelas->semester }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;">Dosen PA *</label>
                        <select name="dosen_pa_id" required style="width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;">
                            <option value="">-- Pilih Dosen PA --</option>
                            @foreach($dosenList as $dosen)
                            <option value="{{ $dosen->id }}" {{ old('dosen_pa_id')==$dosen->id ? 'selected':'' }}>{{ $dosen->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;">Angkatan *</label>
                        <input type="number" name="angkatan" value="{{ old('angkatan', date('Y')) }}" required min="2000" max="{{ date('Y') }}"
                               style="width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;">
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;">Status</label>
                        <select name="status" style="width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;">
                            <option value="aktif">Aktif</option>
                            <option value="cuti">Cuti</option>
                            <option value="lulus">Lulus</option>
                            <option value="keluar">Keluar</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="primary-btn"><i class="bi bi-check-lg"></i> Simpan</button>
                    <a href="{{ route('admin.mahasiswa.index') }}" style="background:#f0f4fc;color:var(--navy);border:none;border-radius:10px;padding:8px 18px;font-size:13px;font-weight:600;text-decoration:none;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection