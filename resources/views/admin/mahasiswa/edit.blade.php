@extends('layouts.admin')
@section('title','Edit Mahasiswa')
@section('page-title','Edit Mahasiswa')
@section('page-sub', $mahasiswa->nama . ' · ' . $mahasiswa->nim)
@section('content')

@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg, #1E3A8A 0%, #4338CA 55%, #6366F1 100%)',
    'icon'         => 'bi-pencil-square',
    'title'        => 'Edit Mahasiswa — ' . $mahasiswa->nama,
    'sub'          => 'NIM: ' . $mahasiswa->nim . ' · ' . ($mahasiswa->kelas->nama ?? '') . ' · ' . ucfirst($mahasiswa->status),
    'chips'        => [
        ['icon' => 'bi-person-fill',          'label' => $mahasiswa->nama],
        ['icon' => 'bi-hash',                 'label' => $mahasiswa->nim],
        ['icon' => 'bi-grid-3x3-gap-fill',    'label' => $mahasiswa->kelas->nama ?? '-'],
    ],
    'badge_num'    => $mahasiswa->angkatan,
    'badge_label'  => "Angkatan",
])

<div class="mb-3">
    <a href="{{ route('admin.mahasiswa.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--blue);font-weight:600;text-decoration:none;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="section-label">Edit Data Mahasiswa</div>
        <div class="card-white tbl-card-v2">
            @if($errors->any())
            <div style="background:#FEF2F2;border:1px solid #FECACA;border-left:3px solid #EF4444;border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;">
                <div style="font-size:13px;font-weight:700;color:#991B1B;margin-bottom:6px;"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat kesalahan input:</div>
                <ul style="margin:0;padding-left:18px;color:#B91C1C;font-size:13px;">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif
            <form action="{{ route('admin.mahasiswa.update', $mahasiswa->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label-ac">NIM</label>
                        <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" required class="form-input-ac">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label-ac">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" required class="form-input-ac">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-ac">Kelas</label>
                        <select name="kelas_id" required class="form-select-ac">
                            @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ $mahasiswa->kelas_id==$kelas->id ? 'selected':'' }}>{{ $kelas->nama }} (Sem {{ $kelas->semester }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-ac">Dosen PA</label>
                        <select name="dosen_pa_id" required class="form-select-ac">
                            @foreach($dosenList as $dosen)
                            <option value="{{ $dosen->id }}" {{ $mahasiswa->dosen_pa_id==$dosen->id ? 'selected':'' }}>{{ $dosen->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-ac">Angkatan</label>
                        <input type="number" name="angkatan" value="{{ old('angkatan', $mahasiswa->angkatan) }}" class="form-input-ac">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-ac">Status</label>
                        <select name="status" class="form-select-ac">
                            @foreach(['aktif','cuti','lulus','keluar'] as $st)
                            <option value="{{ $st }}" {{ $mahasiswa->status==$st ? 'selected':'' }}>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4 pt-4" style="border-top:1px solid var(--border);">
                    <button type="submit" class="btn-primary"><i class="bi bi-check-lg"></i> Update Data</button>
                    <a href="{{ route('admin.mahasiswa.index') }}" style="background:#F1F5F9;color:var(--text-1);border:none;border-radius:var(--radius-sm);padding:7px 18px;font-size:13.5px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection