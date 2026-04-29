@extends('layouts.admin')
@section('title','Edit Kelas')
@section('page-title','Edit Kelas')
@section('page-sub', $kela->nama)
@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="section-card">
    <div class="section-title mb-4">Edit Kelas</div>
    <form action="{{ route('admin.kelas.update', $kela->id) }}" method="POST">
        @csrf @method('PUT')
        @php $s="width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;";$l="font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;"; @endphp
        <div class="row g-3">
            <div class="col-md-6"><label style="{{ $l }}">Nama Kelas</label><input type="text" name="nama" value="{{ $kela->nama }}" required style="{{ $s }}"></div>
            <div class="col-md-6"><label style="{{ $l }}">Semester</label>
                <select name="semester" required style="{{ $s }}">@for($i=1;$i<=8;$i++)<option value="{{ $i }}" {{ $kela->semester==$i?'selected':'' }}>Semester {{ $i }}</option>@endfor</select>
            </div>
            <div class="col-12"><label style="{{ $l }}">Program Studi</label><input type="text" name="prodi" value="{{ $kela->prodi }}" style="{{ $s }}"></div>
            <div class="col-12"><label style="{{ $l }}">Dosen PA</label>
                <select name="dosen_pa_id" required style="{{ $s }}">
                    @foreach($dosenList as $d)<option value="{{ $d->id }}" {{ $kela->dosen_pa_id==$d->id?'selected':'' }}>{{ $d->nama }}</option>@endforeach
                </select>
            </div>
            <div class="col-12"><label style="{{ $l }}">Tahun Akademik</label><input type="text" name="tahun_akademik" value="{{ $kela->tahun_akademik }}" style="{{ $s }}"></div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="primary-btn"><i class="bi bi-check-lg"></i> Update</button>
            <a href="{{ route('admin.kelas.index') }}" style="background:#f0f4fc;color:var(--navy);border:none;border-radius:10px;padding:8px 18px;font-size:13px;font-weight:600;text-decoration:none;">Batal</a>
        </div>
    </form>
</div></div></div>
@endsection