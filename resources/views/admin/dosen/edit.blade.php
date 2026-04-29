@extends('layouts.admin')
@section('title','Edit Dosen')
@section('page-title','Edit Dosen')
@section('page-sub', $dosen->nama)
@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="section-card">
    <div class="section-title mb-4">Edit Data Dosen</div>
    @if($errors->any())<div class="alert alert-danger" style="border-radius:10px;"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <form action="{{ route('admin.dosen.update', $dosen->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            @php $inputStyle = "width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;"; $labelStyle = "font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;"; @endphp
            <div class="col-md-6"><label style="{{ $labelStyle }}">NIP</label><input type="text" name="nip" value="{{ old('nip', $dosen->nip) }}" required style="{{ $inputStyle }}"></div>
            <div class="col-md-6"><label style="{{ $labelStyle }}">Nama Lengkap</label><input type="text" name="nama" value="{{ old('nama', $dosen->nama) }}" required style="{{ $inputStyle }}"></div>
            <div class="col-md-6"><label style="{{ $labelStyle }}">No. HP</label><input type="text" name="no_hp" value="{{ old('no_hp', $dosen->no_hp) }}" style="{{ $inputStyle }}"></div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="primary-btn"><i class="bi bi-check-lg"></i> Update</button>
            <a href="{{ route('admin.dosen.index') }}" style="background:#f0f4fc;color:var(--navy);border:none;border-radius:10px;padding:8px 18px;font-size:13px;font-weight:600;text-decoration:none;">Batal</a>
        </div>
    </form>
</div>
</div></div>
@endsection