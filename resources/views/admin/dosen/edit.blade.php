@extends('layouts.admin')
@section('title','Edit Dosen')
@section('page-title','Edit Dosen')
@section('page-sub', $dosen->nama)
@section('content')
<div class="mb-3"><a href="{{ route('admin.dosen.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--blue);font-weight:600;text-decoration:none;"><i class="bi bi-arrow-left"></i> Kembali</a></div>
<div class="row justify-content-center"><div class="col-lg-7">
<div class="section-label">Edit Data Dosen</div>
<div class="card-white tbl-card-v2">
    <form action="{{ route('admin.dosen.update', $dosen->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-4">
            <div class="col-md-6"><label class="form-label-ac">NIP</label><input type="text" name="nip" value="{{ old('nip',$dosen->nip) }}" required class="form-input-ac"></div>
            <div class="col-md-6"><label class="form-label-ac">Nama Lengkap</label><input type="text" name="nama" value="{{ old('nama',$dosen->nama) }}" required class="form-input-ac"></div>
            <div class="col-md-6"><label class="form-label-ac">No. HP</label><input type="text" name="no_hp" value="{{ old('no_hp',$dosen->no_hp) }}" class="form-input-ac"></div>
        </div>
        <div class="d-flex gap-2 mt-4 pt-4" style="border-top:1px solid var(--border);">
            <button type="submit" class="btn-primary"><i class="bi bi-check-lg"></i> Update Dosen</button>
            <a href="{{ route('admin.dosen.index') }}" style="background:#F1F5F9;color:var(--text-1);border:none;border-radius:var(--radius-sm);padding:7px 18px;font-size:13.5px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;">Batal</a>
        </div>
    </form>
</div>
</div></div>
@endsection