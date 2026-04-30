@extends('layouts.admin')

@section('title', 'Import Data')
@section('page-title', 'Import Data')
@section('page-sub', 'Upload file Excel untuk memperbarui data sistem')

@push('styles')
<style>
/* ══ IMPORT PAGE STYLES ══════════════════════════════ */

/* Page intro */
.import-intro {
    background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 50%, #3B82F6 100%);
    border-radius: var(--radius);
    padding: 28px 32px;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}
.import-intro::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
    pointer-events: none;
}
.import-intro::after {
    content: '';
    position: absolute;
    bottom: -80px; right: 80px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,.04);
    pointer-events: none;
}
.import-intro-title {
    font-size: 20px; font-weight: 800;
    color: #fff; margin-bottom: 4px; letter-spacing: -.3px;
}
.import-intro-sub {
    font-size: 13px; color: rgba(255,255,255,.75);
}
.import-intro-chips {
    display: flex; gap: 8px; margin-top: 14px; flex-wrap: wrap;
}
.import-chip {
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 20px; padding: 4px 12px;
    font-size: 11.5px; font-weight: 600; color: #fff;
    display: inline-flex; align-items: center; gap: 5px;
    backdrop-filter: blur(4px);
}
.import-intro-badge {
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 12px; padding: 16px 24px;
    text-align: center; flex-shrink: 0;
    backdrop-filter: blur(4px);
}
.import-intro-badge-num {
    font-size: 32px; font-weight: 800; color: #fff; line-height: 1;
}
.import-intro-badge-label {
    font-size: 11px; color: rgba(255,255,255,.7); margin-top: 3px;
}

/* Grid cards */
.import-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}

/* Import card modern */
.import-card-modern {
    background: var(--white);
    border: 1.5px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    transition: all .25s cubic-bezier(.34,1.56,.64,1);
    position: relative;
    animation: cardIn .4s ease both;
}
.import-card-modern:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,.1);
    border-color: transparent;
}
.import-card-modern:nth-child(1) { animation-delay: .05s; }
.import-card-modern:nth-child(2) { animation-delay: .10s; }
.import-card-modern:nth-child(3) { animation-delay: .15s; }
.import-card-modern:nth-child(4) { animation-delay: .20s; }
.import-card-modern:nth-child(5) { animation-delay: .25s; }
.import-card-modern:nth-child(6) { animation-delay: .30s; }
.import-card-modern:nth-child(7) { animation-delay: .35s; }

@keyframes cardIn {
    from { opacity:0; transform: translateY(16px); }
    to   { opacity:1; transform: translateY(0); }
}

.import-card-top {
    height: 5px;
    border-radius: 0;
}
.import-card-inner { padding: 20px; }
.import-card-header {
    display: flex; align-items: center;
    gap: 14px; margin-bottom: 16px;
}
.import-card-icon-box {
    width: 48px; height: 48px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
    transition: transform .2s;
}
.import-card-modern:hover .import-card-icon-box {
    transform: scale(1.1) rotate(-3deg);
}
.import-card-title { font-size: 15px; font-weight: 700; color: var(--text-1); }
.import-card-desc  { font-size: 12px; color: var(--text-2); margin-top: 2px; line-height: 1.5; }

/* Dropzone */
.dropzone {
    border: 2px dashed var(--border);
    border-radius: 10px;
    padding: 18px 14px;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
    background: var(--bg);
    position: relative;
    margin-bottom: 10px;
}
.dropzone:hover, .dropzone.dragover {
    border-color: var(--blue);
    background: var(--blue-light);
}
.dropzone.has-file {
    border-style: solid;
    border-color: #22C55E;
    background: #F0FDF4;
}
.dropzone input[type="file"] {
    position: absolute; inset: 0;
    opacity: 0; cursor: pointer;
    width: 100%; height: 100%;
}
.dropzone-icon {
    font-size: 24px; margin-bottom: 6px;
    transition: transform .2s;
}
.dropzone:hover .dropzone-icon { transform: translateY(-2px); }
.dropzone-label {
    font-size: 12.5px; color: var(--text-2);
    line-height: 1.5;
}
.dropzone-label strong { color: var(--blue); font-weight: 600; }
.dropzone-filename {
    font-size: 12px; font-weight: 600;
    color: #166534; margin-top: 4px;
    display: none;
}
.dropzone-filename.show { display: block; }
.dropzone-hint {
    font-size: 11px; color: var(--text-3);
    margin-top: 3px;
}

/* Upload button */
.upload-btn {
    width: 100%;
    border: none; border-radius: 9px;
    padding: 10px;
    font-size: 13.5px; font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 7px;
    transition: all .2s;
    color: #fff;
    position: relative; overflow: hidden;
}
.upload-btn::after {
    content: '';
    position: absolute; inset: 0;
    background: rgba(255,255,255,0);
    transition: background .2s;
}
.upload-btn:hover::after { background: rgba(255,255,255,.1); }
.upload-btn:active { transform: scale(.98); }

/* Template btn */
.tpl-btn {
    width: 100%;
    border: 1.5px solid var(--border);
    border-radius: 9px; padding: 8px;
    font-size: 12.5px; font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer; background: var(--white);
    display: flex; align-items: center; justify-content: center; gap: 6px;
    color: var(--text-2); transition: all .2s;
    text-decoration: none; margin-top: 6px;
}
.tpl-btn:hover { background: var(--blue-light); color: var(--blue); border-color: var(--blue); }

/* Progress bar upload */
.upload-progress {
    height: 3px; border-radius: 2px;
    background: var(--border); overflow: hidden;
    margin-top: 8px; display: none;
}
.upload-progress.show { display: block; }
.upload-progress-fill {
    height: 100%; border-radius: 2px;
    background: linear-gradient(90deg, #2563EB, #60A5FA);
    width: 0; transition: width .3s ease;
    animation: progressPulse 1.5s ease infinite;
}
@keyframes progressPulse {
    0%,100% { opacity:1; }
    50% { opacity:.6; }
}

/* Success state */
.card-success .import-card-modern {
    border-color: #22C55E;
}
.success-tag {
    position: absolute; top: 12px; right: 12px;
    background: #22C55E; color: #fff;
    border-radius: 20px; padding: 2px 10px;
    font-size: 10.5px; font-weight: 700;
    display: flex; align-items: center; gap: 4px;
    animation: popIn .3s cubic-bezier(.34,1.56,.64,1);
}
@keyframes popIn {
    from { opacity:0; transform: scale(.7); }
    to   { opacity:1; transform: scale(1); }
}

/* Format guide */
.format-guide {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 14px; padding: 24px;
    animation: cardIn .4s ease .4s both;
}
.format-title {
    font-size: 15px; font-weight: 700;
    color: var(--text-1); margin-bottom: 16px;
    display: flex; align-items: center; gap: 8px;
}
.format-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 12px;
}
.format-item {
    background: #F8FAFC;
    border: 1px solid var(--border);
    border-radius: 10px; padding: 14px;
    transition: all .2s;
}
.format-item:hover { background: var(--blue-light); border-color: var(--blue-mid); }
.format-item-title {
    font-size: 12.5px; font-weight: 700;
    color: var(--text-1); margin-bottom: 8px;
    display: flex; align-items: center; gap: 6px;
}
.format-cols {
    display: flex; gap: 4px; flex-wrap: wrap;
}
.format-col {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 5px; padding: 2px 7px;
    font-size: 11px; font-weight: 600;
    color: var(--blue); font-family: monospace;
    transition: all .15s;
}
.format-item:hover .format-col {
    background: #EFF6FF; border-color: var(--blue-mid);
}

/* Warning box */
.warning-box {
    background: #FFFBEB;
    border: 1px solid #FDE68A;
    border-left: 3px solid #F59E0B;
    border-radius: 10px; padding: 14px 16px;
    margin-top: 14px;
}
.warning-box ul {
    margin: 6px 0 0 16px; padding: 0;
}
.warning-box li { font-size: 12.5px; color: #92400E; margin-bottom: 3px; }

/* Toast notif */
.toast-wrap {
    position: fixed; bottom: 24px; right: 24px;
    z-index: 9999; display: flex; flex-direction: column; gap: 8px;
}
.toast {
    background: #0F172A; color: #fff;
    border-radius: 10px; padding: 12px 16px;
    font-size: 13px; font-weight: 500;
    display: flex; align-items: center; gap: 10px;
    box-shadow: 0 8px 24px rgba(0,0,0,.2);
    animation: toastIn .3s cubic-bezier(.34,1.56,.64,1);
    max-width: 320px;
}
.toast.success { background: #166534; }
.toast.error   { background: #991B1B; }
@keyframes toastIn {
    from { opacity:0; transform: translateX(20px) scale(.95); }
    to   { opacity:1; transform: translateX(0) scale(1); }
}
@keyframes toastOut {
    to { opacity:0; transform: translateX(20px) scale(.95); }
}

@media (max-width: 768px) {
    .import-grid { grid-template-columns: 1fr; }
    .import-intro { flex-direction: column; }
    .import-intro-badge { width: 100%; }
    .format-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

{{-- ══ INTRO BANNER ══ --}}
<div class="import-intro" style="animation: cardIn .4s ease both;">
    <div>
        <div class="import-intro-title">📤 Import Data Akademik</div>
        <div class="import-intro-sub">Upload file Excel (.xlsx) untuk memperbarui data sistem secara massal</div>
        <div class="import-intro-chips">
            <span class="import-chip"><i class="bi bi-file-earmark-excel-fill"></i> Format .xlsx</span>
            <span class="import-chip"><i class="bi bi-shield-check-fill"></i> Validasi otomatis</span>
            <span class="import-chip"><i class="bi bi-arrow-repeat"></i> Update & Insert</span>
            <span class="import-chip"><i class="bi bi-cloud-upload-fill"></i> Max 5MB</span>
        </div>
    </div>
    <div class="import-intro-badge">
        <div class="import-intro-badge-num">7</div>
        <div class="import-intro-badge-label">Jenis Import<br>Tersedia</div>
    </div>
</div>

{{-- ══ FLASH ══ --}}
@if(session('success'))
<div style="background:#F0FDF4;border:1px solid #BBF7D0;border-left:3px solid #22C55E;border-radius:10px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:10px;animation:cardIn .3s ease;">
    <i class="bi bi-check-circle-fill" style="color:#22C55E;font-size:18px;flex-shrink:0;"></i>
    <span style="font-size:13.5px;font-weight:600;color:#166534;">{{ session('success') }}</span>
</div>
@endif
@if(session('error'))
<div style="background:#FEF2F2;border:1px solid #FECACA;border-left:3px solid #EF4444;border-radius:10px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:10px;animation:cardIn .3s ease;">
    <i class="bi bi-exclamation-triangle-fill" style="color:#EF4444;font-size:18px;flex-shrink:0;"></i>
    <span style="font-size:13.5px;font-weight:600;color:#991B1B;">{{ session('error') }}</span>
</div>
@endif

{{-- ══ IMPORT CARDS ══ --}}
<div class="section-label">Upload File</div>
<div class="import-grid">

@php
$imports = [
    [
        'route'  => 'admin.import.nilai',
        'tpl'    => 'nilai',
        'icon'   => 'bi-bar-chart-line-fill',
        'title'  => 'Nilai Akademik',
        'desc'   => 'Nilai tugas, UTS, UAS per mata kuliah dan kelas',
        'accent' => 'linear-gradient(135deg,#22C55E,#16A34A)',
        'ibg'    => '#F0FDF4',
        'ic'     => '#16A34A',
        'top'    => '#22C55E',
    ],
    [
        'route'  => 'admin.import.absensi',
        'tpl'    => 'absensi',
        'icon'   => 'bi-calendar2-check-fill',
        'title'  => 'Absensi',
        'desc'   => 'Hadir, izin, sakit, alpha + tanggal pertemuan',
        'accent' => 'linear-gradient(135deg,#F59E0B,#D97706)',
        'ibg'    => '#FFFBEB',
        'ic'     => '#D97706',
        'top'    => '#F59E0B',
    ],
    [
        'route'  => 'admin.import.mahasiswa',
        'tpl'    => 'mahasiswa',
        'icon'   => 'bi-people-fill',
        'title'  => 'Mahasiswa',
        'desc'   => 'Data biodata dan akun mahasiswa aktif',
        'accent' => 'linear-gradient(135deg,#2563EB,#1D4ED8)',
        'ibg'    => '#EFF6FF',
        'ic'     => '#1D4ED8',
        'top'    => '#2563EB',
    ],
    [
        'route'  => 'admin.import.dosen',
        'tpl'    => 'dosen',
        'icon'   => 'bi-person-badge-fill',
        'title'  => 'Dosen',
        'desc'   => 'Data dosen pengampu dan DPA per kelas',
        'accent' => 'linear-gradient(135deg,#7C3AED,#6D28D9)',
        'ibg'    => '#F5F3FF',
        'ic'     => '#6D28D9',
        'top'    => '#7C3AED',
    ],
    [
        'route'  => 'admin.import.matkul',
        'tpl'    => 'matkul',
        'icon'   => 'bi-book-fill',
        'title'  => 'Mata Kuliah',
        'desc'   => 'Kurikulum dan mata kuliah per semester',
        'accent' => 'linear-gradient(135deg,#0891B2,#0E7490)',
        'ibg'    => '#ECFEFF',
        'ic'     => '#0E7490',
        'top'    => '#0891B2',
    ],
    [
        'route'  => 'admin.import.jadwal',
        'tpl'    => 'jadwal',
        'icon'   => 'bi-clock-fill',
        'title'  => 'Jadwal Kuliah',
        'desc'   => 'Jadwal kuliah semua kelas dan ruangan',
        'accent' => 'linear-gradient(135deg,#DB2777,#BE185D)',
        'ibg'    => '#FDF2F8',
        'ic'     => '#BE185D',
        'top'    => '#DB2777',
    ],
    [
        'route'  => 'admin.import.kelas',
        'tpl'    => 'kelas',
        'icon'   => 'bi-grid-3x3-gap-fill',
        'title'  => 'Kelas',
        'desc'   => 'Data kelas dan penugasan dosen PA',
        'accent' => 'linear-gradient(135deg,#EA580C,#C2410C)',
        'ibg'    => '#FFF7ED',
        'ic'     => '#C2410C',
        'top'    => '#EA580C',
    ],
];
@endphp

@foreach($imports as $idx => $imp)
<div class="import-card-modern" id="card-{{ $idx }}">
    <div class="import-card-top" style="background:{{ $imp['top'] }};"></div>
    <div class="import-card-inner">
        <div class="import-card-header">
            <div class="import-card-icon-box" style="background:{{ $imp['ibg'] }};">
                <i class="bi {{ $imp['icon'] }}" style="color:{{ $imp['ic'] }};font-size:20px;"></i>
            </div>
            <div>
                <div class="import-card-title">{{ $imp['title'] }}</div>
                <div class="import-card-desc">{{ $imp['desc'] }}</div>
            </div>
        </div>

        <form action="{{ route($imp['route']) }}" method="POST" enctype="multipart/form-data"
              class="import-form" data-idx="{{ $idx }}">
            @csrf

            {{-- Dropzone --}}
            <div class="dropzone" id="dropzone-{{ $idx }}"
                 ondragover="handleDragOver(event, {{ $idx }})"
                 ondragleave="handleDragLeave(event, {{ $idx }})"
                 ondrop="handleDrop(event, {{ $idx }})">
                <input type="file" name="file" accept=".xlsx,.xls,.csv"
                       id="file-{{ $idx }}"
                       onchange="handleFileSelect(this, {{ $idx }})">
                <div class="dropzone-icon" id="dz-icon-{{ $idx }}">📂</div>
                <div class="dropzone-label" id="dz-label-{{ $idx }}">
                    <strong>Klik atau drag & drop</strong> file di sini<br>
                </div>
                <div class="dropzone-hint">.xlsx, .xls, .csv · Max 5MB</div>
                <div class="dropzone-filename" id="dz-filename-{{ $idx }}"></div>
            </div>

            {{-- Progress --}}
            <div class="upload-progress" id="progress-{{ $idx }}">
                <div class="upload-progress-fill" id="progress-fill-{{ $idx }}"></div>
            </div>

            {{-- Buttons --}}
            <button type="submit" class="upload-btn"
                    style="background:{{ $imp['accent'] }};"
                    id="upload-btn-{{ $idx }}">
                <i class="bi bi-upload"></i> Upload & Import
            </button>
        </form>

        <a href="{{ route('admin.import.template', $imp['tpl']) }}" class="tpl-btn">
            <i class="bi bi-download"></i> Download Template
        </a>
    </div>
</div>
@endforeach

</div>

{{-- ══ FORMAT GUIDE ══ --}}
<div class="format-guide">
    <div class="format-title">
        <div style="width:32px;height:32px;border-radius:8px;background:#EFF6FF;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-info-circle-fill" style="color:#2563EB;font-size:15px;"></i>
        </div>
        Panduan Format Kolom Excel
    </div>

    <div class="format-grid">
        @php
        $formats = [
            ['icon'=>'bi-bar-chart-line-fill','color'=>'#16A34A','title'=>'Nilai','cols'=>['nim','kode_matkul','semester','tahun_akademik','nilai_tugas','nilai_uts','nilai_uas']],
            ['icon'=>'bi-calendar2-check-fill','color'=>'#D97706','title'=>'Absensi','cols'=>['nim','kode_matkul','semester','tahun_akademik','tanggal','pertemuan_ke','jam_hadir','jam_izin','jam_sakit','jam_alpha']],
            ['icon'=>'bi-people-fill','color'=>'#1D4ED8','title'=>'Mahasiswa','cols'=>['nim','nama','email','kelas','angkatan','nip_dosen_pa']],
            ['icon'=>'bi-person-badge-fill','color'=>'#6D28D9','title'=>'Dosen','cols'=>['nip','nama','email','no_hp']],
            ['icon'=>'bi-book-fill','color'=>'#0E7490','title'=>'Mata Kuliah','cols'=>['kode','nama','sks','semester','kelas','nip_dosen']],
            ['icon'=>'bi-clock-fill','color'=>'#BE185D','title'=>'Jadwal','cols'=>['kode_matkul','kelas','hari','jam_mulai','jam_selesai','ruangan']],
        ];
        @endphp
        @foreach($formats as $fmt)
        <div class="format-item">
            <div class="format-item-title">
                <i class="bi {{ $fmt['icon'] }}" style="color:{{ $fmt['color'] }};"></i>
                {{ $fmt['title'] }}
            </div>
            <div class="format-cols">
                @foreach($fmt['cols'] as $col)
                <span class="format-col">{{ $col }}</span>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <div class="warning-box">
        <div style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:700;color:#92400E;margin-bottom:4px;">
            <i class="bi bi-exclamation-triangle-fill"></i> Perhatian
        </div>
        <ul>
            <li>Header baris pertama harus <strong>persis sama</strong> dengan nama kolom di atas (huruf kecil, underscore)</li>
            <li>Format <code>tahun_akademik</code>: <strong>2024/2025</strong></li>
            <li>Format <code>tanggal</code>: <strong>YYYY-MM-DD</strong> (contoh: 2025-05-20)</li>
            <li>Kelas, NIM, dan NIP harus sudah terdaftar di database sebelum import nilai/absensi</li>
            <li>Import menggunakan <strong>updateOrCreate</strong> — data lama akan diperbarui jika sudah ada</li>
        </ul>
    </div>
</div>

{{-- Toast container --}}
<div class="toast-wrap" id="toastWrap"></div>

@endsection

@push('scripts')
<script>
// ── File Select Handler ──────────────────────────────
function handleFileSelect(input, idx) {
    var file = input.files[0];
    if (!file) return;

    var dz       = document.getElementById('dropzone-' + idx);
    var icon     = document.getElementById('dz-icon-' + idx);
    var label    = document.getElementById('dz-label-' + idx);
    var filename = document.getElementById('dz-filename-' + idx);

    dz.classList.add('has-file');
    icon.textContent = '✅';
    label.innerHTML  = '<strong>File dipilih:</strong>';
    filename.textContent = file.name + ' (' + formatBytes(file.size) + ')';
    filename.classList.add('show');
}

function formatBytes(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
}

// ── Drag & Drop ──────────────────────────────────────
function handleDragOver(e, idx) {
    e.preventDefault();
    document.getElementById('dropzone-' + idx).classList.add('dragover');
    document.getElementById('dz-icon-' + idx).textContent = '📥';
}

function handleDragLeave(e, idx) {
    document.getElementById('dropzone-' + idx).classList.remove('dragover');
    var file = document.getElementById('file-' + idx).files[0];
    if (!file) document.getElementById('dz-icon-' + idx).textContent = '📂';
}

function handleDrop(e, idx) {
    e.preventDefault();
    var dz    = document.getElementById('dropzone-' + idx);
    var input = document.getElementById('file-' + idx);
    dz.classList.remove('dragover');

    var file = e.dataTransfer.files[0];
    if (!file) return;

    // Inject file into input
    var dt = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
    handleFileSelect(input, idx);
}

// ── Form Submit dengan Progress ──────────────────────
document.querySelectorAll('.import-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        var idx      = this.dataset.idx;
        var fileInput = document.getElementById('file-' + idx);

        if (!fileInput.files.length) {
            e.preventDefault();
            showToast('Pilih file terlebih dahulu!', 'error');
            return;
        }

        // Show progress animation
        var progress = document.getElementById('progress-' + idx);
        var fill     = document.getElementById('progress-fill-' + idx);
        var btn      = document.getElementById('upload-btn-' + idx);

        progress.classList.add('show');
        btn.innerHTML = '<i class="bi bi-arrow-repeat" style="animation:spin .8s linear infinite;"></i> Mengupload...';
        btn.style.opacity = '.7';
        btn.disabled = true;

        // Animate progress
        var w = 0;
        var timer = setInterval(function() {
            w = Math.min(w + Math.random() * 15, 85);
            fill.style.width = w + '%';
        }, 200);

        // Let form submit naturally
    });
});

// ── Toast ────────────────────────────────────────────
function showToast(msg, type) {
    var wrap  = document.getElementById('toastWrap');
    var toast = document.createElement('div');
    toast.className = 'toast ' + (type || '');
    toast.innerHTML = '<i class="bi bi-' + (type==='error' ? 'x-circle-fill' : type==='success' ? 'check-circle-fill' : 'info-circle-fill') + '"></i> ' + msg;
    wrap.appendChild(toast);
    setTimeout(function() {
        toast.style.animation = 'toastOut .3s ease forwards';
        setTimeout(function() { toast.remove(); }, 300);
    }, 3500);
}

// ── Spin animation ───────────────────────────────────
var style = document.createElement('style');
style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
document.head.appendChild(style);

// ── Show success toast if session success ────────────
@if(session('success'))
document.addEventListener('DOMContentLoaded', function() {
    showToast('{{ session('success') }}', 'success');
});
@endif
</script>
@endpush