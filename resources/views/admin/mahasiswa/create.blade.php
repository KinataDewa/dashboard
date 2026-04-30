@extends('layouts.admin')
@section('title','Tambah Mahasiswa')
@section('page-title','Tambah Mahasiswa')
@section('page-sub','Buat akun dan data mahasiswa baru')
@section('content')
<div class="mb-3">
    <a href="{{ route('admin.mahasiswa.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--blue);font-weight:600;text-decoration:none;">
        <i class="bi bi-arrow-left"></i> Kembali ke Data Mahasiswa
    </a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="section-label">Informasi Mahasiswa Baru</div>
        <div class="card-white tbl-card-v2">
            @if($errors->any())
            <div style="background:#FEF2F2;border:1px solid #FECACA;border-left:3px solid #EF4444;border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;">
                <div style="font-size:13px;font-weight:700;color:#991B1B;margin-bottom:6px;"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat kesalahan input:</div>
                <ul style="margin:0;padding-left:18px;color:#B91C1C;font-size:13px;">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif
            <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-12"><div style="font-size:13px;font-weight:700;color:var(--text-1);padding-bottom:8px;border-bottom:1px solid var(--border);">Data Akun</div></div>
                    <div class="col-md-6">
                        <label class="form-label-ac">Email Akun *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="form-input-ac" placeholder="email@student.polinema.ac.id">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-ac">Password *</label>
                        <input type="password" name="password" required class="form-input-ac" placeholder="Minimal 6 karakter">
                    </div>
                    <div class="col-12"><div style="font-size:13px;font-weight:700;color:var(--text-1);padding-bottom:8px;border-bottom:1px solid var(--border);">Data Mahasiswa</div></div>
                    <div class="col-md-4">
                        <label class="form-label-ac">NIM *</label>
                        <input type="text" name="nim" value="{{ old('nim') }}" required class="form-input-ac" placeholder="2341720xxx">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label-ac">Nama Lengkap *</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required class="form-input-ac" placeholder="Nama sesuai KTP">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-ac">Kelas *</label>
                        <select name="kelas_id" required class="form-select-ac">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ old('kelas_id')==$kelas->id ? 'selected':'' }}>{{ $kelas->nama }} (Sem {{ $kelas->semester }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-ac">Dosen PA *</label>
                        <select name="dosen_pa_id" required class="form-select-ac">
                            <option value="">-- Pilih Dosen PA --</option>
                            @foreach($dosenList as $dosen)
                            <option value="{{ $dosen->id }}" {{ old('dosen_pa_id')==$dosen->id ? 'selected':'' }}>{{ $dosen->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-ac">Angkatan *</label>
                        <input type="number" name="angkatan" value="{{ old('angkatan', date('Y')) }}" min="2000" max="{{ date('Y') }}" required class="form-input-ac">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-ac">Status</label>
                        <select name="status" class="form-select-ac">
                            @foreach(['aktif','cuti','lulus','keluar'] as $st)
                            <option value="{{ $st }}" {{ old('status','aktif')==$st ? 'selected':'' }}>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4 pt-4" style="border-top:1px solid var(--border);">
                    <button type="submit" class="btn-primary"><i class="bi bi-check-lg"></i> Simpan Mahasiswa</button>
                    <a href="{{ route('admin.mahasiswa.index') }}" style="background:#F1F5F9;color:var(--text-1);border:none;border-radius:var(--radius-sm);padding:7px 18px;font-size:13.5px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection