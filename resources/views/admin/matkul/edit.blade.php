@extends('layouts.admin')
@section('title','Edit Matkul')
@section('page-title','Edit Mata Kuliah')
@section('page-sub', $matkul->nama)
@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="section-card">
    <div class="section-title mb-4">Edit Mata Kuliah</div>
    <form action="{{ route('admin.matkul.update', $matkul->id) }}" method="POST">
        @csrf @method('PUT')
        @php $s = "width:100%;border:1.5px solid #e4eaf5;border-radius:10px;padding:9px 14px;font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;"; $l = "font-size:12px;font-weight:700;color:#5a6e8c;margin-bottom:6px;display:block;"; @endphp
        <div class="row g-3">
            <div class="col-md-4"><label style="{{ $l }}">Kode</label><input type="text" name="kode" value="{{ old('kode',$matkul->kode) }}" required style="{{ $s }}"></div>
            <div class="col-md-8"><label style="{{ $l }}">Nama</label><input type="text" name="nama" value="{{ old('nama',$matkul->nama) }}" required style="{{ $s }}"></div>
            <div class="col-md-4"><label style="{{ $l }}">SKS</label><input type="number" name="sks" value="{{ old('sks',$matkul->sks) }}" min="1" max="6" required style="{{ $s }}"></div>
            <div class="col-md-4"><label style="{{ $l }}">Semester</label>
                <select name="semester" required style="{{ $s }}">@for($i=1;$i<=8;$i++)<option value="{{ $i }}" {{ $matkul->semester==$i?'selected':'' }}>Semester {{ $i }}</option>@endfor</select>
            </div>
            <div class="col-md-4"><label style="{{ $l }}">Kelas</label>
                <select name="kelas_id" required style="{{ $s }}">
                    @foreach($kelasList as $kelas)<option value="{{ $kelas->id }}" {{ $matkul->kelas_id==$kelas->id?'selected':'' }}>{{ $kelas->nama }}</option>@endforeach
                </select>
            </div>
            <div class="col-12"><label style="{{ $l }}">Dosen Pengampu</label>
                <select name="dosen_id" required style="{{ $s }}">
                    @foreach($dosenList as $d)<option value="{{ $d->id }}" {{ $matkul->dosen_id==$d->id?'selected':'' }}>{{ $d->nama }}</option>@endforeach
                </select>
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="primary-btn"><i class="bi bi-check-lg"></i> Update</button>
            <a href="{{ route('admin.matkul.index') }}" style="background:#f0f4fc;color:var(--navy);border:none;border-radius:10px;padding:8px 18px;font-size:13px;font-weight:600;text-decoration:none;">Batal</a>
        </div>
    </form>
</div></div></div>
@endsection