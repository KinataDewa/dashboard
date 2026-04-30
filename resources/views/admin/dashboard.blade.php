@extends('layouts.admin')
@section('title','Admin Dashboard')
@section('page-title','Overview Sistem')
@section('page-sub','Jurusan Teknologi Informasi — Politeknik Negeri Malang')

@section('topbar-actions')
<a href="{{ route('admin.import.index') }}" class="btn-primary">
    <i class="bi bi-file-earmark-arrow-up"></i> Import Data
</a>
@endsection

@push('styles')
<style>
/* ── Risk Alert ─────────────────────────────────── */
.risk-alert-wrap {
    position: relative;
    background: linear-gradient(135deg, #1A0A0A 0%, #7F1D1D 40%, #991B1B 100%);
    border-radius: 14px;
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(239,68,68,.25), 0 2px 8px rgba(239,68,68,.15);
    animation: alertSlideIn .4s cubic-bezier(.16,1,.3,1) both;
    flex-wrap: wrap;
}
@keyframes alertSlideIn {
    from { opacity:0; transform: translateY(-12px) scale(.98); }
    to   { opacity:1; transform: translateY(0) scale(1); }
}
@keyframes alertSlideOut {
    from { opacity:1; transform: translateY(0) scale(1); max-height:200px; margin-bottom:24px; padding:20px 24px; }
    to   { opacity:0; transform: translateY(-8px) scale(.97); max-height:0; margin-bottom:0; padding:0 24px; }
}
.risk-alert-wrap::before {
    content: '';
    position: absolute; inset: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,.06) 1px, transparent 1px);
    background-size: 24px 24px;
    pointer-events: none;
}
.risk-alert-wrap::after {
    content: '';
    position: absolute;
    top: 0; left: -100%;
    width: 60%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.04), transparent);
    animation: glowSweep 4s ease infinite;
    pointer-events: none;
}
@keyframes glowSweep {
    0%   { left: -60%; }
    100% { left: 140%; }
}
.risk-pulse-ring {
    position: absolute;
    left: 28px; top: 50%;
    transform: translateY(-50%);
    width: 52px; height: 52px;
    border-radius: 50%;
    background: rgba(239,68,68,.2);
    animation: ringPulse 2s ease-out infinite;
    pointer-events: none;
}
@keyframes ringPulse {
    0%   { transform: translateY(-50%) scale(1);   opacity:.8; }
    70%  { transform: translateY(-50%) scale(1.8); opacity:0; }
    100% { transform: translateY(-50%) scale(1);   opacity:0; }
}
.risk-alert-left {
    display: flex; align-items: flex-start;
    gap: 16px; flex: 1; min-width: 0;
    position: relative; z-index: 1;
}
.risk-alert-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #FCA5A5; flex-shrink: 0;
    animation: iconShake 3s ease infinite;
}
@keyframes iconShake {
    0%,90%,100% { transform: rotate(0deg); }
    92%  { transform: rotate(-8deg); }
    94%  { transform: rotate(8deg); }
    96%  { transform: rotate(-4deg); }
    98%  { transform: rotate(4deg); }
}
.risk-alert-content { min-width: 0; }
.risk-alert-tag {
    display: inline-flex; align-items: center;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 20px; padding: 2px 10px;
    font-size: 11px; font-weight: 700;
    color: #FCA5A5; letter-spacing: .5px; margin-bottom: 6px;
}
.risk-alert-title {
    font-size: 15px; font-weight: 800;
    color: #fff; line-height: 1.3;
    margin-bottom: 5px; letter-spacing: -.2px;
}
.risk-alert-desc {
    font-size: 12.5px; color: rgba(255,255,255,.7); line-height: 1.5;
}
.risk-alert-desc strong { color: #FCA5A5; font-weight: 700; }
.risk-alert-right {
    display: flex; align-items: center; gap: 10px;
    flex-shrink: 0; position: relative; z-index: 1;
}
.risk-alert-btn {
    background: #fff; color: #991B1B;
    border: none; border-radius: 9px;
    padding: 10px 18px; font-size: 13px; font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer; text-decoration: none;
    display: inline-flex; align-items: center; gap: 7px;
    transition: all .2s; white-space: nowrap;
    box-shadow: 0 2px 8px rgba(0,0,0,.2);
}
.risk-alert-btn:hover {
    background: #FEF2F2; color: #7F1D1D;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,.25);
}
.risk-alert-btn i { font-size: 15px; }
.risk-alert-close {
    width: 34px; height: 34px; border-radius: 8px;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.15);
    color: rgba(255,255,255,.7); cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; transition: all .2s; flex-shrink: 0;
}
.risk-alert-close:hover {
    background: rgba(255,255,255,.2); color: #fff;
    border-color: rgba(255,255,255,.3); transform: scale(1.05);
}
@media (max-width: 768px) {
    .risk-alert-wrap  { padding: 16px 18px; gap: 14px; }
    .risk-alert-right { width: 100%; justify-content: space-between; }
    .risk-alert-btn   { flex: 1; justify-content: center; }
    .risk-pulse-ring  { display: none; }
    .risk-alert-title { font-size: 14px; }
}
</style>
@endpush

@section('content')

{{-- Banner --}}
@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg, #0C1445 0%, #1E3A8A 40%, #2563EB 100%)',
    'icon'         => 'bi-speedometer2',
    'title'        => 'Overview Sistem — Jurusan TI',
    'sub'          => 'Politeknik Negeri Malang · Tahun Akademik 2024/2025',
    'chips'        => [
        ['icon' => 'bi-mortarboard-fill',         'label' => $totalMahasiswa . ' Mahasiswa'],
        ['icon' => 'bi-person-badge-fill',        'label' => $totalDosen . ' Dosen'],
        ['icon' => 'bi-book-fill',                'label' => $totalMatkul . ' Mata Kuliah'],
        ['icon' => 'bi-exclamation-triangle-fill','label' => $mahasiswaBerisiko . ' Berisiko'],
    ],
    'badge_num'    => $totalMahasiswa,
    'badge_label'  => "Total\nMahasiswa",
    'badge2_num'   => $mahasiswaBerisiko,
    'badge2_label' => "Perlu\nPerhatian",
])

{{-- ══ ALERT BERISIKO ══ --}}
@if($mahasiswaBerisiko > 0)
<div class="risk-alert-wrap" id="riskAlertAdmin">
    <div class="risk-pulse-ring"></div>
    <div class="risk-alert-left">
        <div class="risk-alert-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="risk-alert-content">
            <div class="risk-alert-tag">⚡ Tindakan Diperlukan</div>
            <div class="risk-alert-title">
                {{ $mahasiswaBerisiko }} Mahasiswa Terdeteksi Berisiko Akademik
            </div>
            <div class="risk-alert-desc">
                Terdapat mahasiswa dengan <strong>nilai D/E</strong> atau <strong>absensi ≥18 jam</strong>.
                DPA perlu segera melakukan bimbingan agar tidak mempengaruhi kelulusan.
            </div>
        </div>
    </div>
    <div class="risk-alert-right">
        <a href="{{ route('admin.mahasiswa.index') }}" class="risk-alert-btn">
            <i class="bi bi-arrow-right-circle-fill"></i>
            Lihat & Tangani Sekarang
        </a>
        <button class="risk-alert-close" id="riskCloseAdmin" title="Tutup">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
</div>
@endif

{{-- ══ STAT CARDS ══ --}}
<div class="section-label">Statistik Sistem</div>
<div class="row g-3 mb-4">
    @php
    $stats = [
        ['label'=>'Mahasiswa Aktif',    'val'=>$totalMahasiswa,    'icon'=>'bi-mortarboard-fill',         'accent'=>'linear-gradient(90deg,#2563EB,#60A5FA)', 'ibg'=>'#EFF6FF', 'ic'=>'#2563EB', 'badge'=>'Terdaftar',      'bbg'=>'#DBEAFE', 'bc'=>'#1D4ED8'],
        ['label'=>'Total Dosen',        'val'=>$totalDosen,        'icon'=>'bi-person-badge-fill',        'accent'=>'linear-gradient(90deg,#16A34A,#86EFAC)', 'ibg'=>'#F0FDF4', 'ic'=>'#16A34A', 'badge'=>'Aktif mengajar', 'bbg'=>'#DCFCE7', 'bc'=>'#166534'],
        ['label'=>'Mata Kuliah',        'val'=>$totalMatkul,       'icon'=>'bi-book-fill',                'accent'=>'linear-gradient(90deg,#7C3AED,#A78BFA)', 'ibg'=>'#F5F3FF', 'ic'=>'#7C3AED', 'badge'=>'Semester aktif', 'bbg'=>'#EDE9FE', 'bc'=>'#5B21B6'],
        ['label'=>'Kelas Aktif',        'val'=>$totalKelas,        'icon'=>'bi-grid-3x3-gap-fill',        'accent'=>'linear-gradient(90deg,#0891B2,#67E8F9)', 'ibg'=>'#ECFEFF', 'ic'=>'#0891B2', 'badge'=>'Semua angkatan', 'bbg'=>'#CFFAFE', 'bc'=>'#0E7490'],
        ['label'=>'Mahasiswa Berisiko', 'val'=>$mahasiswaBerisiko, 'icon'=>'bi-exclamation-triangle-fill','accent'=>$mahasiswaBerisiko>0 ? 'linear-gradient(90deg,#EF4444,#FCA5A5)' : 'linear-gradient(90deg,#22C55E,#86EFAC)', 'ibg'=>$mahasiswaBerisiko>0 ? '#FEF2F2' : '#F0FDF4', 'ic'=>$mahasiswaBerisiko>0 ? '#EF4444' : '#22C55E', 'badge'=>$mahasiswaBerisiko>0 ? 'Perlu penanganan' : 'Semua aman', 'bbg'=>$mahasiswaBerisiko>0 ? '#FEE2E2' : '#DCFCE7', 'bc'=>$mahasiswaBerisiko>0 ? '#991B1B' : '#166534'],
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
        ['url'=>route('admin.mahasiswa.index'),'icon'=>'bi-people-fill',              'title'=>'Data Mahasiswa','count'=>$totalMahasiswa,'unit'=>'mahasiswa',  'bg'=>'#EFF6FF','c'=>'#2563EB'],
        ['url'=>route('admin.dosen.index'),    'icon'=>'bi-person-badge-fill',        'title'=>'Data Dosen',   'count'=>$totalDosen,    'unit'=>'dosen',      'bg'=>'#F0FDF4','c'=>'#16A34A'],
        ['url'=>route('admin.matkul.index'),   'icon'=>'bi-book-fill',                'title'=>'Mata Kuliah',  'count'=>$totalMatkul,   'unit'=>'matkul',     'bg'=>'#F5F3FF','c'=>'#7C3AED'],
        ['url'=>route('admin.kelas.index'),    'icon'=>'bi-grid-3x3-gap-fill',        'title'=>'Kelola Kelas', 'count'=>$totalKelas,    'unit'=>'kelas',      'bg'=>'#ECFEFF','c'=>'#0891B2'],
        ['url'=>route('admin.import.index'),   'icon'=>'bi-file-earmark-arrow-up-fill','title'=>'Import Data', 'count'=>null,           'unit'=>'Upload Excel','bg'=>'#FFF7ED','c'=>'#EA580C'],
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

@if($mahasiswaBerisiko > 0)
<div class="section-label">Peringatan</div>
@endif

@endsection

@push('scripts')
<script>
(function() {
    var el   = document.getElementById('riskAlertAdmin');
    var btnX = document.getElementById('riskCloseAdmin');
    if (!el || !btnX) return;
 
    // Hanya pakai variabel JS — hilang saat refresh, muncul lagi otomatis
    btnX.addEventListener('click', function() {
        el.style.animation = 'alertSlideOut .35s cubic-bezier(.4,0,1,1) forwards';
        setTimeout(function() {
            el.style.display = 'none';
        }, 340);
    });
})();
</script>
@endpush