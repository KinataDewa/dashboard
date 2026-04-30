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

{{-- ══ STAT CARDS ══ --}}
<div class="section-label">Statistik Sistem</div>
<div class="row g-3 mb-4">

    @php
    $stats = [
        ['label'=>'Mahasiswa Aktif',    'val'=>$totalMahasiswa,    'icon'=>'bi-mortarboard-fill',    'accent'=>'linear-gradient(90deg,#2563EB,#60A5FA)', 'ibg'=>'#EFF6FF', 'ic'=>'#2563EB', 'badge'=>'Terdaftar',      'bbg'=>'#DBEAFE', 'bc'=>'#1D4ED8'],
        ['label'=>'Total Dosen',        'val'=>$totalDosen,        'icon'=>'bi-person-badge-fill',   'accent'=>'linear-gradient(90deg,#16A34A,#86EFAC)', 'ibg'=>'#F0FDF4', 'ic'=>'#16A34A', 'badge'=>'Aktif mengajar', 'bbg'=>'#DCFCE7', 'bc'=>'#166534'],
        ['label'=>'Mata Kuliah',        'val'=>$totalMatkul,       'icon'=>'bi-book-fill',           'accent'=>'linear-gradient(90deg,#7C3AED,#A78BFA)', 'ibg'=>'#F5F3FF', 'ic'=>'#7C3AED', 'badge'=>'Semester aktif', 'bbg'=>'#EDE9FE', 'bc'=>'#5B21B6'],
        ['label'=>'Kelas Aktif',        'val'=>$totalKelas,        'icon'=>'bi-grid-3x3-gap-fill',   'accent'=>'linear-gradient(90deg,#0891B2,#67E8F9)', 'ibg'=>'#ECFEFF', 'ic'=>'#0891B2', 'badge'=>'Semua angkatan', 'bbg'=>'#CFFAFE', 'bc'=>'#0E7490'],
        ['label'=>'Mahasiswa Berisiko', 'val'=>$mahasiswaBerisiko, 'icon'=>'bi-exclamation-triangle-fill', 'accent'=>$mahasiswaBerisiko>0 ? 'linear-gradient(90deg,#EF4444,#FCA5A5)' : 'linear-gradient(90deg,#22C55E,#86EFAC)', 'ibg'=>$mahasiswaBerisiko>0 ? '#FEF2F2' : '#F0FDF4', 'ic'=>$mahasiswaBerisiko>0 ? '#EF4444' : '#22C55E', 'badge'=>$mahasiswaBerisiko>0 ? 'Perlu penanganan' : 'Semua aman', 'bbg'=>$mahasiswaBerisiko>0 ? '#FEE2E2' : '#DCFCE7', 'bc'=>$mahasiswaBerisiko>0 ? '#991B1B' : '#166534'],
    ];
    @endphp

    @foreach($stats as $stat)
    <div class="col-md col-6">
        <div style="background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;transition:transform .18s,box-shadow .18s;height:100%;"
             onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)'"
             onmouseout="this.style.transform='';this.style.boxShadow='var(--shadow)'">
            <div style="height:3px;background:{{ $stat['accent'] }};"></div>
            <div style="padding:16px 18px;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:40px;height:40px;border-radius:10px;background:{{ $stat['ibg'] }};display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">
                        <i class="bi {{ $stat['icon'] }}" style="color:{{ $stat['ic'] }};"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:11.5px;font-weight:600;color:var(--text-2);margin-bottom:2px;">{{ $stat['label'] }}</div>
                        <div style="font-size:28px;font-weight:800;line-height:1;color:{{ $stat['ic'] }};letter-spacing:-1px;">{{ $stat['val'] }}</div>
                    </div>
                </div>
                <div style="margin-top:10px;">
                    <span style="display:inline-flex;align-items:center;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700;background:{{ $stat['bbg'] }};color:{{ $stat['bc'] }};">
                        {{ $stat['badge'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ══ AKSES CEPAT ══ --}}
<div class="section-label">Akses Cepat</div>
<div class="row g-3 mb-4">
    @php
    $menus = [
        ['url'=>route('admin.mahasiswa.index'),'icon'=>'bi-people-fill',         'title'=>'Data Mahasiswa', 'count'=>$totalMahasiswa,    'unit'=>'mahasiswa', 'bg'=>'#EFF6FF', 'c'=>'#2563EB'],
        ['url'=>route('admin.dosen.index'),    'icon'=>'bi-person-badge-fill',   'title'=>'Data Dosen',    'count'=>$totalDosen,        'unit'=>'dosen',     'bg'=>'#F0FDF4', 'c'=>'#16A34A'],
        ['url'=>route('admin.matkul.index'),   'icon'=>'bi-book-fill',           'title'=>'Mata Kuliah',   'count'=>$totalMatkul,       'unit'=>'matkul',    'bg'=>'#F5F3FF', 'c'=>'#7C3AED'],
        ['url'=>route('admin.kelas.index'),    'icon'=>'bi-grid-3x3-gap-fill',   'title'=>'Kelola Kelas',  'count'=>$totalKelas,        'unit'=>'kelas',     'bg'=>'#ECFEFF', 'c'=>'#0891B2'],
        ['url'=>route('admin.import.index'),   'icon'=>'bi-file-earmark-arrow-up-fill','title'=>'Import Data','count'=>null,            'unit'=>'Upload Excel','bg'=>'#FFF7ED','c'=>'#EA580C'],
    ];
    @endphp

    @foreach($menus as $menu)
    <div class="col-md col-6">
        <a href="{{ $menu['url'] }}" class="quick-card" style="text-decoration:none;">
            <div class="quick-card-icon" style="background:{{ $menu['bg'] }};">
                <i class="bi {{ $menu['icon'] }}" style="color:{{ $menu['c'] }};font-size:20px;"></i>
            </div>
            @if($menu['count'] !== null)
            <div class="quick-card-count" style="color:{{ $menu['c'] }};">{{ $menu['count'] }}</div>
            @endif
            <div class="quick-card-title">{{ $menu['title'] }}</div>
            <div class="quick-card-desc">{{ $menu['unit'] }}</div>
        </a>
    </div>
    @endforeach
</div>

{{-- ══ ALERT ══ --}}
@if($mahasiswaBerisiko > 0)
<div class="section-label">Peringatan</div>
<div style="background:#FEF2F2;border:1px solid #FECACA;border-left:4px solid #EF4444;border-radius:var(--radius-sm);padding:16px 20px;display:flex;align-items:center;gap:14px;">
    <div style="width:40px;height:40px;border-radius:50%;background:#FEE2E2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="bi bi-exclamation-triangle-fill" style="color:#EF4444;font-size:18px;"></i>
    </div>
    <div style="flex:1;">
        <div style="font-size:14px;font-weight:700;color:#991B1B;">{{ $mahasiswaBerisiko }} Mahasiswa Terdeteksi Berisiko</div>
        <div style="font-size:12.5px;color:#B91C1C;margin-top:3px;">Terdapat mahasiswa dengan nilai D/E atau absensi ≥18 jam. DPA perlu segera melakukan bimbingan akademik.</div>
    </div>
    <a href="{{ route('admin.mahasiswa.index') }}" class="btn-danger" style="white-space:nowrap;flex-shrink:0;">
        <i class="bi bi-arrow-right"></i> Lihat Data
    </a>
</div>
@endif

@endsection