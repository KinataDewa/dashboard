@extends('layouts.admin')
@section('title','Admin Dashboard')
@section('page-title','Overview Sistem')
@section('page-sub','Jurusan Teknologi Informasi — Politeknik Negeri Malang')
 
@section('topbar-actions')
<a href="{{ route('admin.import.index') }}" class="btn-primary">
    <i class="bi bi-file-earmark-arrow-up"></i> Import Data
</a>
@endsection
 
@section('content')
 
<div class="section-label">Statistik Sistem</div>
<div class="row g-3 mb-4">
    <div class="col-sm-4 col-6">
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background:linear-gradient(90deg,#2563EB,#60A5FA);"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:#EFF6FF;"><i class="bi bi-mortarboard-fill" style="color:#2563EB;"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Mahasiswa Aktif</div>
                    <div class="stat-card-value" style="color:#2563EB;">{{ $totalMahasiswa }}</div>
                    <div class="stat-card-note"><span class="stat-card-badge badge-blue"><i class="bi bi-person-check"></i> Terdaftar</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-6">
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background:linear-gradient(90deg,#16A34A,#86EFAC);"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:#F0FDF4;"><i class="bi bi-person-badge-fill" style="color:#16A34A;"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Total Dosen</div>
                    <div class="stat-card-value" style="color:#16A34A;">{{ $totalDosen }}</div>
                    <div class="stat-card-note"><span class="stat-card-badge badge-up">Aktif mengajar</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-6">
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background:linear-gradient(90deg,#7C3AED,#A78BFA);"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:#F5F3FF;"><i class="bi bi-book-fill" style="color:#7C3AED;"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Mata Kuliah</div>
                    <div class="stat-card-value" style="color:#7C3AED;">{{ $totalMatkul }}</div>
                    <div class="stat-card-note"><span class="stat-card-badge" style="background:#F5F3FF;color:#7C3AED;">Semester aktif</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-6">
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background:linear-gradient(90deg,#0891B2,#67E8F9);"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:#ECFEFF;"><i class="bi bi-grid-3x3-gap-fill" style="color:#0891B2;"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Kelas Aktif</div>
                    <div class="stat-card-value" style="color:#0891B2;">{{ $totalKelas }}</div>
                    <div class="stat-card-note"><span class="stat-card-badge" style="background:#ECFEFF;color:#0891B2;">Semua angkatan</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-6">
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background:{{ $mahasiswaBerisiko > 0 ? 'linear-gradient(90deg,#EF4444,#FCA5A5)' : 'linear-gradient(90deg,#22C55E,#86EFAC)' }};"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:{{ $mahasiswaBerisiko > 0 ? '#FEF2F2' : '#F0FDF4' }};"><i class="bi bi-exclamation-triangle-fill" style="color:{{ $mahasiswaBerisiko > 0 ? '#EF4444' : '#22C55E' }};"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Mahasiswa Berisiko</div>
                    <div class="stat-card-value" style="color:{{ $mahasiswaBerisiko > 0 ? '#EF4444' : '#22C55E' }};">{{ $mahasiswaBerisiko }}</div>
                    <div class="stat-card-note">
                        @if($mahasiswaBerisiko > 0)
                            <span class="stat-card-badge badge-down">Perlu penanganan</span>
                        @else
                            <span class="stat-card-badge badge-up">Semua aman</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
<div class="section-label">Akses Cepat</div>
<div class="row g-3">
    @php
    $menus = [
        ['url'=>route('admin.mahasiswa.index'),'icon'=>'👨‍🎓','title'=>'Data Mahasiswa','desc'=>$totalMahasiswa.' mahasiswa','color'=>'#EFF6FF'],
        ['url'=>route('admin.dosen.index'),    'icon'=>'👨‍🏫','title'=>'Data Dosen',    'desc'=>$totalDosen.' dosen',    'color'=>'#F0FDF4'],
        ['url'=>route('admin.matkul.index'),   'icon'=>'📚','title'=>'Mata Kuliah',   'desc'=>$totalMatkul.' matkul',  'color'=>'#F5F3FF'],
        ['url'=>route('admin.kelas.index'),    'icon'=>'🏫','title'=>'Kelola Kelas',  'desc'=>$totalKelas.' kelas',    'color'=>'#ECFEFF'],
        ['url'=>route('admin.import.index'),   'icon'=>'📤','title'=>'Import Data',   'desc'=>'Upload Excel',          'color'=>'#FFF7ED'],
    ];
    @endphp
    @foreach($menus as $menu)
    <div class="col-md-4 col-6">
        <a href="{{ $menu['url'] }}" style="display:block;background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:20px;text-decoration:none;text-align:center;transition:all .2s;box-shadow:var(--shadow);"
           onmouseover="this.style.borderColor='#2563EB';this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)'"
           onmouseout="this.style.borderColor='#E2E8F0';this.style.transform='';this.style.boxShadow='var(--shadow)'">
            <div style="width:52px;height:52px;border-radius:12px;background:{{ $menu['color'] }};display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto 12px;">{{ $menu['icon'] }}</div>
            <div style="font-size:13.5px;font-weight:700;color:var(--text-1);margin-bottom:4px;">{{ $menu['title'] }}</div>
            <div style="font-size:12px;color:var(--text-2);">{{ $menu['desc'] }}</div>
        </a>
    </div>
    @endforeach
</div>
 
@if($mahasiswaBerisiko > 0)
<div class="mt-4">
    <div class="section-label">Peringatan Sistem</div>
    <div style="background:#FEF2F2;border:1px solid #FECACA;border-left:4px solid #EF4444;border-radius:var(--radius-sm);padding:16px 20px;display:flex;align-items:center;gap:14px;">
        <i class="bi bi-exclamation-triangle-fill" style="color:#EF4444;font-size:20px;flex-shrink:0;"></i>
        <div>
            <div style="font-size:14px;font-weight:700;color:#991B1B;">{{ $mahasiswaBerisiko }} Mahasiswa Terdeteksi Berisiko</div>
            <div style="font-size:12px;color:#B91C1C;margin-top:3px;">Terdapat mahasiswa dengan nilai D/E atau absensi ≥18 jam. DPA perlu segera melakukan bimbingan akademik.</div>
        </div>
    </div>
</div>
@endif
 
@endsection