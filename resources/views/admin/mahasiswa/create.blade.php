@extends('layouts.admin')
@section('title', 'Edit Mahasiswa')
@section('page-title', 'Edit Mahasiswa')
@section('page-sub', $mahasiswa->nama . ' — ' . $mahasiswa->nim)
 
@section('content')
<div class="mb-3">
    <a href="{{ route('admin.mahasiswa.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--blue);font-weight:600;text-decoration:none;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
 
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="section-label">Form Edit Mahasiswa</div>
        <div class="card-white tbl-card-v2">
            @if($errors->any())
            <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:var(--radius-sm);padding:12px 14px;margin-bottom:18px;">
                <ul style="margin:0;padding-left:16px;color:#991B1B;font-size:13px;">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif
 
            <form action="{{ route('admin.mahasiswa.update', $mahasiswa->id) }}" method="POST">
                @csrf @method('PUT')
                @include('admin.mahasiswa._form', ['mahasiswa' => $mahasiswa])
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-primary"><i class="bi bi-check-lg"></i> Update</button>
                    <a href="{{ route('admin.mahasiswa.index') }}" style="background:#F1F5F9;color:var(--text-1);border:none;border-radius:var(--radius-sm);padding:7px 18px;font-size:13.5px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection