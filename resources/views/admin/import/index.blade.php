@extends('layouts.admin')
 
@section('title', 'Import Data')
@section('page-title', 'Import Data')
@section('page-sub', 'Upload file Excel untuk memperbarui data sistem')
 
@section('content')
 
<div class="section-card mb-4">
    <div class="section-header">
        <div>
            <div class="section-title">Import Data — Semester Aktif</div>
            <div class="section-subtitle">Format file: .xlsx — Gunakan template yang disediakan</div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @foreach(['nilai','absensi','mahasiswa','dosen','matkul','kelas'] as $t)
            <a href="{{ route('admin.import.template', $t) }}"
               style="font-size:12px;color:var(--blue);font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border:1px solid var(--border);border-radius:6px;background:var(--white);transition:all .15s;"
               onmouseover="this.style.background='var(--blue-light)'"
               onmouseout="this.style.background='var(--white)'">
                <i class="bi bi-download"></i> {{ ucfirst($t) }}
            </a>
            @endforeach
        </div>
    </div>
 
    <div class="row g-3">
        @php
        $imports = [
            ['route'=>'admin.import.nilai',    'icon'=>'📊', 'title'=>'Import Nilai',     'desc'=>'Nilai tugas, UTS, UAS per mata kuliah',    'color'=>'rgba(40,199,111,0.12)', 'btn'=>'#28c76f'],
            ['route'=>'admin.import.absensi',  'icon'=>'📅', 'title'=>'Import Absensi',   'desc'=>'Hadir, izin, sakit, alpha per jam',          'color'=>'rgba(232,160,32,0.12)', 'btn'=>'#e8a020'],
            ['route'=>'admin.import.jadwal',   'icon'=>'🗓️', 'title'=>'Import Jadwal',    'desc'=>'Jadwal kuliah semua kelas & ruangan',        'color'=>'rgba(0,180,200,0.12)',  'btn'=>'#00b4c8'],
            ['route'=>'admin.import.mahasiswa','icon'=>'👨‍🎓','title'=>'Import Mahasiswa', 'desc'=>'Data biodata mahasiswa aktif',               'color'=>'rgba(124,77,255,0.12)', 'btn'=>'#7c4dff'],
            ['route'=>'admin.import.dosen',    'icon'=>'👨‍🏫','title'=>'Import Dosen',     'desc'=>'Data dosen pengampu & DPA per kelas',        'color'=>'rgba(232,51,74,0.12)',  'btn'=>'#e8334a'],
            ['route'=>'admin.import.matkul',   'icon'=>'📖', 'title'=>'Import Matkul',    'desc'=>'Kurikulum & mata kuliah per semester',       'color'=>'rgba(124,77,255,0.12)', 'btn'=>'#7c4dff'],
            ['route'=>'admin.import.kelas',    'icon'=>'🏫', 'title'=>'Import Kelas',     'desc'=>'Data kelas & penugasan DPA',                 'color'=>'rgba(0,180,200,0.12)',  'btn'=>'#00b4c8'],
        ];
        @endphp
 
        @foreach($imports as $imp)
        <div class="col-md-4">
            <div class="import-card">
                <div class="import-icon" style="background:{{ $imp['color'] }};">{{ $imp['icon'] }}</div>
                <div class="import-title">{{ $imp['title'] }}</div>
                <div class="import-desc">{{ $imp['desc'] }}</div>
 
                <form action="{{ route($imp['route']) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-2">
                        <input type="file" name="file" accept=".xlsx,.xls,.csv"
                               style="display:block;width:100%;font-size:12px;padding:6px;border:1.5px solid #e4eaf5;border-radius:8px;cursor:pointer;"
                               required>
                    </div>
                    <button type="submit" class="import-btn w-100" style="background:{{ $imp['btn'] }};color:#fff;">
                        <i class="bi bi-upload me-1"></i> Upload & Import
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
 
{{-- FORMAT TEMPLATE --}}
<div class="section-card">
    <div class="section-title mb-3">📋 Format Kolom Excel per Jenis Import</div>
    <div class="row g-3">
        <div class="col-md-6">
            <div style="background:#f8faff;border-radius:12px;padding:14px;">
                <div style="font-size:12px;font-weight:700;color:var(--navy);margin-bottom:8px;">📊 Import Nilai</div>
                <code style="font-size:11px;color:#5a6e8c;display:block;line-height:1.8;">
                    nim | kode_matkul | semester | tahun_akademik | nilai_tugas | nilai_uts | nilai_uas
                </code>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background:#f8faff;border-radius:12px;padding:14px;">
                <div style="font-size:12px;font-weight:700;color:var(--navy);margin-bottom:8px;">📅 Import Absensi</div>
                <code style="font-size:11px;color:#5a6e8c;display:block;line-height:1.8;">
                    nim | kode_matkul | semester | tahun_akademik | jam_hadir | jam_izin | jam_sakit | jam_alpha
                </code>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background:#f8faff;border-radius:12px;padding:14px;">
                <div style="font-size:12px;font-weight:700;color:var(--navy);margin-bottom:8px;">👨‍🎓 Import Mahasiswa</div>
                <code style="font-size:11px;color:#5a6e8c;display:block;line-height:1.8;">
                    nim | nama | email | kelas | angkatan | nip_dosen_pa
                </code>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background:#f8faff;border-radius:12px;padding:14px;">
                <div style="font-size:12px;font-weight:700;color:var(--navy);margin-bottom:8px;">👨‍🏫 Import Dosen</div>
                <code style="font-size:11px;color:#5a6e8c;display:block;line-height:1.8;">
                    nip | nama | email | no_hp
                </code>
            </div>
        </div>
    </div>
</div>
@endsection
 