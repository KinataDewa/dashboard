@extends('layouts.admin')
@section('title','Tambah Kelas')
@section('page-title','Tambah Kelas')
@section('content')
<div class="mb-3"><a href="{{ route('admin.kelas.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--blue);font-weight:600;text-decoration:none;"><i class="bi bi-arrow-left"></i> Kembali</a></div>
<div class="row justify-content-center"><div class="col-lg-7">
<div class="section-label">Data Kelas Baru</div>
<div class="card-white tbl-card-v2">
    @if($errors->any())<div style="background:#FEF2F2;border:1px solid #FECACA;border-left:3px solid #EF4444;border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;"><ul style="margin:0;padding-left:18px;color:#B91C1C;font-size:13px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <form action="{{ route('admin.kelas.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-md-6"><label class="form-label-ac">Nama Kelas *</label><input type="text" name="nama" value="{{ old('nama') }}" placeholder="contoh: TI3C" required class="form-input-ac"></div>
            <div class="col-md-6"><label class="form-label-ac">Semester *</label>
                <select name="semester" required class="form-select-ac">@for($i=1;$i<=8;$i++)<option value="{{ $i }}" {{ old('semester')==$i ? 'selected':'' }}>Semester {{ $i }}</option>@endfor</select>
            </div>
            <div class="col-12"><label class="form-label-ac">Program Studi</label><input type="text" name="prodi" value="{{ old('prodi','Teknologi Informasi') }}" class="form-input-ac"></div>
            <div class="col-12"><label class="form-label-ac">Dosen PA *</label>
                <select name="dosen_pa_id" required class="form-select-ac"><option value="">-- Pilih Dosen PA --</option>@foreach($dosenList as $d)<option value="{{ $d->id }}" {{ old('dosen_pa_id')==$d->id ? 'selected':'' }}>{{ $d->nama }}</option>@endforeach</select>
            </div>
            <div class="col-md-6"><label class="form-label-ac">Tahun Akademik *</label><input type="text" name="tahun_akademik" value="{{ old('tahun_akademik','2024/2025') }}" placeholder="2024/2025" required class="form-input-ac"></div>
        </div>
        <div class="d-flex gap-2 mt-4 pt-4" style="border-top:1px solid var(--border);">
            <button type="submit" class="btn-primary"><i class="bi bi-check-lg"></i> Simpan Kelas</button>
            <a href="{{ route('admin.kelas.index') }}" style="background:#F1F5F9;color:var(--text-1);border:none;border-radius:var(--radius-sm);padding:7px 18px;font-size:13.5px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;">Batal</a>
        </div>
    </form>
</div></div></div>
@endsection