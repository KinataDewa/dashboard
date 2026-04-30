@extends('layouts.admin')
@section('title','Tambah Dosen')
@section('page-title','Tambah Dosen')
@section('page-sub','Buat akun dan data dosen baru')
@section('content')

@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg, #14532D 0%, #16A34A 55%, #22C55E 100%)',
    'icon'         => 'bi-person-plus-fill',
    'title'        => 'Tambah Dosen Baru',
    'sub'          => 'Buat akun dan data dosen baru di sistem SIAKAD',
    'chips'        => [
        ['icon' => 'bi-key-fill',         'label' => 'Akun otomatis dibuat'],
        ['icon' => 'bi-shield-check-fill','label' => 'Role Dosen'],
    ],
])

<div class="mb-3"><a href="{{ route('admin.dosen.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--blue);font-weight:600;text-decoration:none;"><i class="bi bi-arrow-left"></i> Kembali</a></div>
<div class="row justify-content-center"><div class="col-lg-7">
<div class="section-label">Data Dosen Baru</div>
<div class="card-white tbl-card-v2">
    @if($errors->any())<div style="background:#FEF2F2;border:1px solid #FECACA;border-left:3px solid #EF4444;border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;"><ul style="margin:0;padding-left:18px;color:#B91C1C;font-size:13px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <form action="{{ route('admin.dosen.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-md-6"><label class="form-label-ac">NIP *</label><input type="text" name="nip" value="{{ old('nip') }}" required class="form-input-ac" placeholder="NIP 18 digit"></div>
            <div class="col-md-6"><label class="form-label-ac">Nama Lengkap *</label><input type="text" name="nama" value="{{ old('nama') }}" required class="form-input-ac" placeholder="Nama + gelar"></div>
            <div class="col-md-6"><label class="form-label-ac">Email *</label><input type="email" name="email" value="{{ old('email') }}" required class="form-input-ac" placeholder="email@polinema.ac.id"></div>
            <div class="col-md-6"><label class="form-label-ac">Password *</label><input type="password" name="password" required class="form-input-ac" placeholder="Minimal 6 karakter"></div>
            <div class="col-md-6"><label class="form-label-ac">No. HP</label><input type="text" name="no_hp" value="{{ old('no_hp') }}" class="form-input-ac" placeholder="08xxxxxxxxxx"></div>
        </div>
        <div class="d-flex gap-2 mt-4 pt-4" style="border-top:1px solid var(--border);">
            <button type="submit" class="btn-primary"><i class="bi bi-check-lg"></i> Simpan Dosen</button>
            <a href="{{ route('admin.dosen.index') }}" style="background:#F1F5F9;color:var(--text-1);border:none;border-radius:var(--radius-sm);padding:7px 18px;font-size:13.5px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;">Batal</a>
        </div>
    </form>
</div>
</div></div>
@endsection