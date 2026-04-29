@extends('layouts.admin')
@section('title','Tambah Kelas')
@section('page-title','Tambah Kelas')
@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="section-card">
    <div class="section-title mb-4">Form Tambah Kelas</div>
    <form action="{{ route('admin.kelas.store') }}" method="POST">
        @csrf
        @php $s="width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;";$l="font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;"; @endphp
        <div class="row g-3">
            <div class="col-md-6"><label style="{{ $l }}">Nama Kelas *</label><input type="text" name="nama" value="{{ old('nama') }}" placeholder="contoh: TI3C" required style="{{ $s }}"></div>
            <div class="col-md-6"><label style="{{ $l }}">Semester *</label>
                <select name="semester" required style="{{ $s }}">@for($i=1;$i<=8;$i++)<option value="{{ $i }}">Semester {{ $i }}</option>@endfor</select>
            </div>
            <div class="col-12"><label style="{{ $l }}">Program Studi</label><input type="text" name="prodi" value="{{ old('prodi','Teknologi Informasi') }}" style="{{ $s }}"></div>
            <div class="col-12"><label style="{{ $l }}">Dosen PA *</label>
                <select name="dosen_pa_id" required style="{{ $s }}">
                    <option value="">-- Pilih Dosen PA --</option>
                    @foreach($dosenList as $d)<option value="{{ $d->id }}">{{ $d->nama }}</option>@endforeach
                </select>
            </div>
            <div class="col-12"><label style="{{ $l }}">Tahun Akademik *</label><input type="text" name="tahun_akademik" value="{{ old('tahun_akademik','2024/2025') }}" placeholder="2024/2025" required style="{{ $s }}"></div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="primary-btn"><i class="bi bi-check-lg"></i> Simpan</button>
            <a href="{{ route('admin.kelas.index') }}" style="background:#f0f4fc;color:var(--navy);border:none;border-radius:10px;padding:8px 18px;font-size:13px;font-weight:600;text-decoration:none;">Batal</a>
        </div>
    </form>
</div></div></div>
@endsection