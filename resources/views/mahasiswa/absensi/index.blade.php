{{-- resources/views/mahasiswa/absensi/index.blade.php --}}
@extends('layouts.mahasiswa')

@section('title', 'Riwayat Absensi')
@section('page-title', 'Riwayat Absensi')
@section('page-sub', 'Semester ' . $semesterAktif . ' — ' . $mahasiswa->kelas->nama ?? '')

@section('content')

{{-- PERINGATAN ALPHA KRITIS --}}
@if($absensiKritis->count() > 0)
<div style="background:linear-gradient(135deg,#e8334a,#c0192d);border-radius:13px;padding:14px 18px;color:#fff;display:flex;align-items:center;gap:14px;margin-bottom:20px;">
    <div style="width:40px;height:40px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">⛔</div>
    <div>
        <div style="font-weight:700;font-size:13.5px;margin-bottom:2px;">
            {{ $absensiKritis->count() }} Mata Kuliah Melewati Batas Alpha 18 Jam!
        </div>
        <div style="font-size:11.5px;opacity:.88;">
            {{ $absensiKritis->map(fn($a) => $a->mataKuliah->nama . ' (' . $a->jam_alpha . ' jam)')->implode(', ') }}
            — Tidak diperkenankan mengikuti UAS.
        </div>
    </div>
</div>
@endif

{{-- REKAP TOTAL --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div style="background:rgba(40,199,111,0.08);border:1px solid rgba(40,199,111,0.2);border-radius:14px;padding:18px;text-align:center;">
            <div style="font-size:36px;font-weight:800;color:var(--success-green);font-family:'Space Mono',monospace;">{{ $totalHadir }}</div>
            <div style="font-size:11px;color:#8da3c0;font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-top:4px;">Total Hadir (jam)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div style="background:rgba(0,180,200,0.08);border:1px solid rgba(0,180,200,0.2);border-radius:14px;padding:18px;text-align:center;">
            <div style="font-size:36px;font-weight:800;color:var(--teal);font-family:'Space Mono',monospace;">{{ $totalIzin }}</div>
            <div style="font-size:11px;color:#8da3c0;font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-top:4px;">Total Izin (jam)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div style="background:rgba(255,159,67,0.08);border:1px solid rgba(255,159,67,0.2);border-radius:14px;padding:18px;text-align:center;">
            <div style="font-size:36px;font-weight:800;color:var(--warning-orange);font-family:'Space Mono',monospace;">{{ $totalSakit }}</div>
            <div style="font-size:11px;color:#8da3c0;font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-top:4px;">Total Sakit (jam)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div style="background:rgba(232,51,74,0.08);border:1px solid rgba(232,51,74,0.2);border-radius:14px;padding:18px;text-align:center;">
            <div style="font-size:36px;font-weight:800;color:var(--danger-red);font-family:'Space Mono',monospace;">{{ $totalAlpha }}</div>
            <div style="font-size:11px;color:#8da3c0;font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-top:4px;">Total Alpha (jam)</div>
        </div>
    </div>
</div>

{{-- TABEL DETAIL PER MATKUL --}}
<div class="section-card">
    <div class="section-header">
        <div>
            <div class="section-title">Detail Absensi per Mata Kuliah</div>
            <div class="section-subtitle">Batas maksimal alpha: 18 jam per mata kuliah</div>
        </div>
    </div>

    <table class="nilai-table">
        <thead>
            <tr>
                <th>Mata Kuliah</th>
                <th class="text-center">Total Jam</th>
                <th class="text-center">Hadir</th>
                <th class="text-center">Izin</th>
                <th class="text-center">Sakit</th>
                <th class="text-center">Alpha</th>
                <th class="text-center">% Hadir</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absensis as $absen)
            @php
                $totalJam  = $absen->jam_hadir + $absen->jam_izin + $absen->jam_sakit + $absen->jam_alpha;
                $pct       = $totalJam > 0 ? round($absen->jam_hadir / $totalJam * 100) : 0;
                $kritis    = $absen->jam_alpha >= 18;
                $waspada   = $absen->jam_alpha >= 14 && $absen->jam_alpha < 18;
            @endphp
            <tr class="{{ $kritis ? 'warn-row' : '' }}">
                <td>
                    <div class="matkul-name">{{ $absen->mataKuliah->nama }}</div>
                    <div class="matkul-sub">{{ $absen->mataKuliah->kode }}</div>
                </td>
                <td class="text-center"><span class="score-pill">{{ $totalJam }}</span></td>
                <td class="text-center" style="color:var(--success-green);font-weight:700;">{{ $absen->jam_hadir }}</td>
                <td class="text-center" style="color:var(--teal);font-weight:600;">{{ $absen->jam_izin }}</td>
                <td class="text-center" style="color:var(--warning-orange);font-weight:600;">{{ $absen->jam_sakit }}</td>
                <td class="text-center" style="color:{{ $kritis ? 'var(--danger-red)' : ($waspada ? 'var(--warning-orange)' : '#5a6e8c') }};font-weight:{{ $kritis || $waspada ? '800' : '600' }};">
                    {{ $absen->jam_alpha }}
                    @if($kritis) ⛔ @elseif($waspada) ⚠️ @endif
                </td>
                <td class="text-center" style="min-width:120px;">
                    <div class="progress progress-thin mb-1">
                        <div class="progress-bar {{ $pct >= 75 ? 'bg-success' : ($pct >= 60 ? 'bg-warning' : 'bg-danger') }}"
                             style="width:{{ $pct }}%"></div>
                    </div>
                    <small style="font-family:'Space Mono';font-size:11px;font-weight:700;color:{{ $pct >= 75 ? 'var(--success-green)' : 'var(--danger-red)' }};">{{ $pct }}%</small>
                </td>
                <td class="text-center">
                    @if($kritis)
                        <span style="background:rgba(232,51,74,0.1);color:var(--danger-red);border-radius:20px;padding:3px 10px;font-size:10px;font-weight:700;">⛔ Melewati Batas</span>
                    @elseif($waspada)
                        <span style="background:rgba(255,159,67,0.1);color:var(--warning-orange);border-radius:20px;padding:3px 10px;font-size:10px;font-weight:700;">⚠ Waspada</span>
                    @else
                        <span style="background:rgba(40,199,111,0.1);color:var(--success-green);border-radius:20px;padding:3px 10px;font-size:10px;font-weight:700;">✓ Aman</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-4" style="color:#8da3c0;">
                    <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
                    Belum ada data absensi untuk semester ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- KETERANGAN --}}
    <div class="mt-3 d-flex gap-3 flex-wrap" style="font-size:11px;color:#8da3c0;">
        <span><span style="color:var(--success-green);font-weight:700;">✓ Aman</span> = Alpha &lt; 14 jam</span>
        <span><span style="color:var(--warning-orange);font-weight:700;">⚠ Waspada</span> = Alpha 14–17 jam</span>
        <span><span style="color:var(--danger-red);font-weight:700;">⛔ Melewati Batas</span> = Alpha ≥ 18 jam (tidak boleh UAS)</span>
    </div>
</div>
@endsection
