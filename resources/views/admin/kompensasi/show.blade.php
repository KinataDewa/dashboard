@extends('layouts.admin')
@section('title', 'Detail Kompensasi')
@section('page-title', 'Detail Kompensasi')
@section('page-sub', $kompensasi->mahasiswa->nama . ' · Semester ' . $kompensasi->semester)
 
@push('styles')
<style>
.info-row{display:flex;justify-content:space-between;align-items:flex-start;padding:11px 0;border-bottom:1px solid #F1F5F9;gap:12px;}
.info-row:last-child{border-bottom:none;}
.info-label{font-size:12px;color:var(--text-2);font-weight:500;flex-shrink:0;min-width:130px;}
.info-val{font-size:13px;font-weight:600;color:var(--text-1);text-align:right;word-break:break-word;}
.ttd-card{border:1.5px solid var(--border);border-radius:10px;padding:16px;text-align:center;transition:all .2s;}
.ttd-card.done{background:#F0FDF4;border-color:#86EFAC;}
.ttd-card.pending{background:#F9FAFB;border-color:var(--border);}
.ttd-icon{font-size:28px;display:block;margin-bottom:6px;}
.ttd-label{font-size:12px;font-weight:700;color:var(--text-2);}
.ttd-status{font-size:11px;margin-top:3px;}
</style>
@endpush
 
@section('content')
 
<div class="mb-3">
    <a href="{{ route('admin.kompensasi.index') }}"
       style="display:inline-flex;align-items:center;gap:5px;font-size:13px;font-weight:500;color:var(--text-2);text-decoration:none;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
 
@include('components.page-banner', [
    'gradient'  => $kompensasi->status === 'lunas'
        ? 'linear-gradient(135deg, #14532D 0%, #16A34A 55%, #22C55E 100%)'
        : 'linear-gradient(135deg, #1A0A0A 0%, #7F1D1D 50%, #DC2626 100%)',
    'icon'      => $kompensasi->status === 'lunas' ? 'bi-check-circle-fill' : 'bi-clipboard2-check-fill',
    'title'     => $kompensasi->mahasiswa->nama,
    'sub'       => 'Kompensasi Semester ' . $kompensasi->semester . ' · ' . $kompensasi->tahun_akademik,
    'chips'     => [
        ['icon'=>'bi-x-circle-fill',   'label'=> $kompensasi->jam_alpha . ' Jam Alpha'],
        ['icon'=>'bi-clock-fill',      'label'=> $kompensasi->jam_kompen_wajib . ' Jam Kompen'],
        ['icon'=>'bi-exclamation-triangle-fill', 'label'=> $kompensasi->sp_label],
        ['icon'=>'bi-check-circle-fill','label'=> ucfirst($kompensasi->status)],
    ],
    'badge_num'   => $kompensasi->jam_kompen_wajib,
    'badge_label' => "Jam\nKompen",
])
 
{{-- Alerts --}}
@if(session('success'))
<div style="background:#F0FDF4;border:1px solid #86EFAC;border-left:3px solid #22C55E;border-radius:9px;padding:11px 14px;font-size:13px;color:#166534;display:flex;align-items:center;gap:8px;margin-bottom:16px;">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif
@if($errors->any())
<div style="background:#FEF2F2;border:1px solid #FECACA;border-left:3px solid #EF4444;border-radius:9px;padding:11px 14px;font-size:13px;color:#991B1B;display:flex;align-items:center;gap:8px;margin-bottom:16px;">
    <i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}
</div>
@endif
 
<div class="row g-4">
 
    {{-- Kiri: Detail + Aksi TTD --}}
    <div class="col-lg-8">
 
        {{-- Progress TTD --}}
        <div class="section-label">Progress Penandatanganan</div>
        <div class="card-white tbl-card-v2 mb-4">
            <div class="row g-3">
                {{-- Step 1: Mahasiswa ambil surat --}}
                <div class="col-4">
                    <div class="ttd-card done">
                        <span class="ttd-icon">📋</span>
                        <div class="ttd-label">Surat Dibuat</div>
                        <div class="ttd-status" style="color:#16A34A;font-weight:600;">✓ Selesai</div>
                    </div>
                </div>
 
                {{-- Step 2: TTD Admin --}}
                <div class="col-4">
                    <div class="ttd-card {{ $kompensasi->ttd_admin ? 'done' : 'pending' }}">
                        <span class="ttd-icon">{{ $kompensasi->ttd_admin ? '✅' : '⏳' }}</span>
                        <div class="ttd-label">TTD Admin</div>
                        <div class="ttd-status" style="color:{{ $kompensasi->ttd_admin ? '#16A34A' : '#9CA3AF' }};font-weight:600;">
                            {{ $kompensasi->ttd_admin ? '✓ Sudah TTD' : 'Menunggu' }}
                        </div>
                        @if(!$kompensasi->ttd_admin && $kompensasi->status === 'pending')
                        <form action="{{ route('admin.kompensasi.ttd-admin', $kompensasi->id) }}" method="POST" style="margin-top:10px;">
                            @csrf
                            <button type="submit" class="btn-primary"
                                    style="width:100%;padding:6px;font-size:12px;justify-content:center;display:flex;align-items:center;gap:4px;"
                                    onclick="return confirm('Konfirmasi tanda tangan admin?')">
                                <i class="bi bi-pen-fill"></i> TTD Sekarang
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
 
                {{-- Step 3: TTD Kajur --}}
                <div class="col-4">
                    <div class="ttd-card {{ $kompensasi->ttd_kajur ? 'done' : 'pending' }}">
                        <span class="ttd-icon">{{ $kompensasi->ttd_kajur ? '✅' : '⏳' }}</span>
                        <div class="ttd-label">TTD Kajur</div>
                        <div class="ttd-status" style="color:{{ $kompensasi->ttd_kajur ? '#16A34A' : '#9CA3AF' }};font-weight:600;">
                            {{ $kompensasi->ttd_kajur ? '✓ Lunas' : 'Menunggu' }}
                        </div>
                        @if($kompensasi->ttd_admin && !$kompensasi->ttd_kajur && $kompensasi->status === 'pending')
                        <form action="{{ route('admin.kompensasi.ttd-kajur', $kompensasi->id) }}" method="POST" style="margin-top:10px;">
                            @csrf
                            <button type="submit" class="btn-primary"
                                    style="width:100%;padding:6px;font-size:12px;justify-content:center;display:flex;align-items:center;gap:4px;background:#22C55E;"
                                    onclick="return confirm('Konfirmasi tanda tangan Kajur? Status akan menjadi LUNAS.')">
                                <i class="bi bi-check2-circle"></i> TTD & Lunas
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
 
            @if($kompensasi->status === 'lunas')
            <div style="background:#F0FDF4;border:1px solid #86EFAC;border-radius:10px;padding:12px 16px;text-align:center;margin-top:16px;">
                <i class="bi bi-check-circle-fill" style="color:#22C55E;font-size:18px;"></i>
                <strong style="color:#166534;margin-left:6px;">Kompensasi Lunas</strong>
                <div style="font-size:12px;color:#4D7C0F;margin-top:2px;">
                    {{ $kompensasi->tanggal_lunas?->format('d F Y, H:i') }}
                </div>
            </div>
            @endif
        </div>
 
        {{-- Detail Data --}}
        <div class="section-label">Detail Kompensasi</div>
        <div class="card-white tbl-card-v2 mb-4">
            <div class="info-row">
                <span class="info-label">Nama Mahasiswa</span>
                <span class="info-val">{{ $kompensasi->mahasiswa->nama }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">NIM</span>
                <span class="info-val" style="font-family:monospace;">{{ $kompensasi->mahasiswa->nim }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kelas</span>
                <span class="info-val">{{ $kompensasi->mahasiswa->kelas->nama ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Dosen PA</span>
                <span class="info-val">{{ $kompensasi->mahasiswa->dosenPa->nama ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Semester Alpha</span>
                <span class="info-val">Semester {{ $kompensasi->semester }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tahun Akademik</span>
                <span class="info-val">{{ $kompensasi->tahun_akademik }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Jam Alpha</span>
                <span class="info-val" style="color:#EF4444;">{{ $kompensasi->jam_alpha }} jam</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status SP</span>
                <span class="info-val">
                    <span style="background:{{ $kompensasi->sp_bg }};color:{{ $kompensasi->sp_color }};padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;">
                        {{ $kompensasi->sp_label }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Multiplier</span>
                <span class="info-val">×{{ $kompensasi->multiplier }}
                    @if($kompensasi->multiplier > 1)
                    <span style="font-size:11px;color:#EF4444;">(terlambat {{ log($kompensasi->multiplier, 2) }} semester)</span>
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Jam Kompen Wajib</span>
                <span class="info-val" style="font-size:18px;color:var(--blue);">
                    <strong>{{ $kompensasi->jam_kompen_wajib }} jam</strong>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Rumus</span>
                <span class="info-val" style="font-family:monospace;font-size:12px;color:var(--text-2);">
                    {{ $kompensasi->jam_alpha }} × 2 × {{ $kompensasi->multiplier }} = {{ $kompensasi->jam_kompen_wajib }} jam
                </span>
            </div>
            @if($kompensasi->catatan_tugas)
            <div class="info-row" style="align-items:flex-start;">
                <span class="info-label">Catatan Tugas</span>
                <span class="info-val" style="text-align:left;max-width:300px;line-height:1.5;">{{ $kompensasi->catatan_tugas }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Dibuat oleh</span>
                <span class="info-val">{{ $kompensasi->createdBy->name ?? 'System' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Buat</span>
                <span class="info-val">{{ $kompensasi->created_at->format('d F Y, H:i') }}</span>
            </div>
        </div>
 
        {{-- Hapus --}}
        @if($kompensasi->status === 'pending')
        <form action="{{ route('admin.kompensasi.destroy', $kompensasi->id) }}" method="POST"
              onsubmit="return confirm('Hapus data kompensasi ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-del"
                    style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;font-size:13px;">
                <i class="bi bi-trash-fill"></i> Hapus Kompensasi
            </button>
        </form>
        @endif
 
    </div>
 
    {{-- Kanan: Ringkasan --}}
    <div class="col-lg-4">
        <div class="section-label">Status Kompensasi</div>
        <div class="card-white tbl-card-v2 mb-4" style="text-align:center;padding:28px 20px;">
            @if($kompensasi->status === 'lunas')
            <div style="width:72px;height:72px;border-radius:50%;background:#DCFCE7;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:32px;">
                ✅
            </div>
            <div style="font-size:22px;font-weight:800;color:#166534;">LUNAS</div>
            <div style="font-size:12px;color:#4D7C0F;margin-top:4px;">Kompensasi selesai</div>
            @else
            <div style="width:72px;height:72px;border-radius:50%;background:#FEF3C7;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:32px;">
                ⏳
            </div>
            <div style="font-size:22px;font-weight:800;color:#92400E;">PENDING</div>
            <div style="font-size:12px;color:#78350F;margin-top:4px;">Menunggu penyelesaian</div>
            @endif
 
            <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
                <div style="font-size:44px;font-weight:800;letter-spacing:-2px;line-height:1;color:var(--blue);">
                    {{ $kompensasi->jam_kompen_wajib }}
                </div>
                <div style="font-size:12px;color:var(--text-2);margin-top:4px;">Jam Kompen Wajib</div>
 
                <div style="margin-top:14px;display:flex;flex-direction:column;gap:8px;">
                    <div style="display:flex;justify-content:space-between;font-size:12.5px;">
                        <span style="color:var(--text-2);">Jam Alpha</span>
                        <span style="font-weight:700;color:#EF4444;">{{ $kompensasi->jam_alpha }}j</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:12.5px;">
                        <span style="color:var(--text-2);">Multiplier</span>
                        <span style="font-weight:700;">×{{ $kompensasi->multiplier }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:12.5px;">
                        <span style="color:var(--text-2);">Status SP</span>
                        <span style="background:{{ $kompensasi->sp_bg }};color:{{ $kompensasi->sp_color }};padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700;">{{ $kompensasi->sp_label }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:12.5px;">
                        <span style="color:var(--text-2);">TTD Admin</span>
                        <span style="font-weight:700;color:{{ $kompensasi->ttd_admin ? '#22C55E' : '#9CA3AF' }};">
                            {{ $kompensasi->ttd_admin ? '✓ Sudah' : '✗ Belum' }}
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:12.5px;">
                        <span style="color:var(--text-2);">TTD Kajur</span>
                        <span style="font-weight:700;color:{{ $kompensasi->ttd_kajur ? '#22C55E' : '#9CA3AF' }};">
                            {{ $kompensasi->ttd_kajur ? '✓ Sudah' : '✗ Belum' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
 
        {{-- Link ke profil mahasiswa --}}
        <div class="card-white tbl-card-v2">
            <div style="font-size:13px;font-weight:700;color:var(--text-1);margin-bottom:10px;">Mahasiswa</div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                <div style="width:42px;height:42px;border-radius:50%;background:var(--blue);color:#fff;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:800;flex-shrink:0;">
                    {{ strtoupper(substr($kompensasi->mahasiswa->nama,0,1)) }}
                </div>
                <div>
                    <div style="font-weight:600;font-size:13.5px;">{{ $kompensasi->mahasiswa->nama }}</div>
                    <div style="font-size:11px;color:var(--text-3);font-family:monospace;">{{ $kompensasi->mahasiswa->nim }}</div>
                </div>
            </div>
            <a href="{{ route('admin.mahasiswa.show', $kompensasi->mahasiswa->id) }}"
               class="btn-primary"
               style="width:100%;justify-content:center;display:flex;align-items:center;gap:6px;padding:9px;font-size:13px;">
                <i class="bi bi-eye-fill"></i> Lihat Detail Mahasiswa
            </a>
        </div>
 
    </div>
</div>
 
@endsection