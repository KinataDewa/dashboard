@extends('layouts.admin')
@section('title','Tambah Matkul')
@section('page-title','Tambah Mata Kuliah')
@section('content')
<div class="mb-3"><a href="{{ route('admin.matkul.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--blue);font-weight:600;text-decoration:none;"><i class="bi bi-arrow-left"></i> Kembali</a></div>
<div class="row justify-content-center"><div class="col-lg-8">
<div class="section-label">Data Mata Kuliah Baru</div>
<div class="card-white tbl-card-v2">
    @if($errors->any())<div style="background:#FEF2F2;border:1px solid #FECACA;border-left:3px solid #EF4444;border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;"><ul style="margin:0;padding-left:18px;color:#B91C1C;font-size:13px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <form action="{{ route('admin.matkul.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-md-3"><label class="form-label-ac">Kode *</label><input type="text" name="kode" value="{{ old('kode') }}" required class="form-input-ac" placeholder="TI601"></div>
            <div class="col-md-9"><label class="form-label-ac">Nama Mata Kuliah *</label><input type="text" name="nama" value="{{ old('nama') }}" required class="form-input-ac" placeholder="Nama mata kuliah"></div>
            <div class="col-md-3"><label class="form-label-ac">SKS *</label><input type="number" name="sks" value="{{ old('sks',3) }}" min="1" max="6" required class="form-input-ac"></div>
            <div class="col-md-3"><label class="form-label-ac">Semester *</label>
                <select name="semester" required class="form-select-ac">@for($i=1;$i<=8;$i++)<option value="{{ $i }}" {{ old('semester')==$i ? 'selected':'' }}>Semester {{ $i }}</option>@endfor</select>
            </div>
            <div class="col-md-6"><label class="form-label-ac">Kelas *</label>
                <select name="kelas_id" required class="form-select-ac"><option value="">-- Pilih Kelas --</option>@foreach($kelasList as $k)<option value="{{ $k->id }}" {{ old('kelas_id')==$k->id ? 'selected':'' }}>{{ $k->nama }}</option>@endforeach</select>
            </div>
            <div class="col-12"><label class="form-label-ac">Dosen Pengampu *</label>
                <select name="dosen_id" required class="form-select-ac"><option value="">-- Pilih Dosen --</option>@foreach($dosenList as $d)<option value="{{ $d->id }}" {{ old('dosen_id')==$d->id ? 'selected':'' }}>{{ $d->nama }}</option>@endforeach</select>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4 pt-4" style="border-top:1px solid var(--border);">
            <button type="submit" class="btn-primary"><i class="bi bi-check-lg"></i> Simpan Matkul</button>
            <a href="{{ route('admin.matkul.index') }}" style="background:#F1F5F9;color:var(--text-1);border:none;border-radius:var(--radius-sm);padding:7px 18px;font-size:13.5px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;">Batal</a>
        </div>
    </form>
</div></div></div>
@endsection