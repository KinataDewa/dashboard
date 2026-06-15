@extends('layouts.mahasiswa')
 
@section('title', 'Riwayat Absensi')
@section('page-title', 'Riwayat Absensi')
@section('page-sub', 'Data kehadiran Anda per semester · Semester ' . $semesterAktif)
 
@push('styles')
<style>
.absen-mini {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 16px 20px;
}
.absen-mini-label {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .8px;
    margin-bottom: 6px;
}
.absen-mini-val {
    font-size: 26px; font-weight: 800;
    line-height: 1; color: var(--text-1);
}
.absen-mini-unit {
    font-size: 13px; font-weight: 500;
    color: var(--text-2); margin-left: 2px;
}
.absen-bar {
    height: 4px; background: #F1F5F9;
    border-radius: 2px; margin-top: 10px; overflow: hidden;
}
.absen-bar-fill { height: 100%; border-radius: 2px; }
.absen-warn {
    font-size: 11px; font-weight: 600;
    margin-top: 5px;
}
</style>
@endpush
 
@section('content')
 
{{-- Hitung dulu sebelum banner --}}
@php
    $sumHadir = $absensis->sum('jam_hadir');
    $sumIzin  = $absensis->sum('jam_izin');
    $sumSakit = $absensis->sum('jam_sakit');
    $sumAlpha = $absensis->sum('jam_alpha');
    $sumAll   = $sumHadir + $sumIzin + $sumSakit + $sumAlpha;
    $pctHadir = $sumAll > 0 ? round($sumHadir / $sumAll * 100) : 0;
@endphp
 
{{-- ══ BANNER ══ --}}
@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg, #1E3A5F 0%, #0891B2 55%, #22D3EE 100%)',
    'icon'         => 'bi-calendar2-check-fill',
    'title'        => 'Riwayat Absensi',
    'sub'          => 'Data kehadiran per semester · Semester ' . $semesterAktif,
    'chips'        => [
        ['icon' => 'bi-x-circle-fill',     'label' => $sumAlpha . ' Jam Alpha'],
        ['icon' => 'bi-journal-x',         'label' => $sumIzin . ' Jam Izin'],
        ['icon' => 'bi-thermometer',       'label' => $sumSakit . ' Jam Sakit'],
        ['icon' => $sumAlpha >= 14 ? 'bi-exclamation-triangle-fill' : 'bi-shield-check-fill',
                                           'label' => $sumAlpha >= 18 ? 'Status: Kritis!' : ($sumAlpha >= 14 ? 'Status: Waspada' : 'Status: Aman')],
    ],
    'badge_num'    => $sumAlpha . 'j',
    'badge_label'  => "Total\nAlpha",
])
 
{{-- ══ FILTER SEMESTER ══ --}}
<div style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
 
    {{-- Kiri: info semester --}}
    <div>
        <div style="font-size:15px;font-weight:700;color:var(--text-1);">
            Absensi Semester {{ $semester }}
            @if($semester == $semesterAktif)
                <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700;background:#DCFCE7;color:#15803D;margin-left:6px;">
                    <i class="bi bi-circle-fill" style="font-size:7px;"></i> Aktif
                </span>
            @endif
        </div>
        <div style="font-size:12px;color:var(--text-2);margin-top:2px;">
            Semester {{ $semester }} ·
            Alpha: <strong style="color:{{ $sumAlpha >= 18 ? '#EF4444' : ($sumAlpha >= 14 ? '#F59E0B' : 'var(--text-1)') }};">{{ $sumAlpha }} jam</strong>
        </div>
    </div>
 
    {{-- Kanan: pills semester --}}
    <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
        <span style="font-size:11.5px;color:var(--text-3);font-weight:600;margin-right:4px;">Semester:</span>
        @foreach($semesterList as $sem)
        <a href="{{ route('mahasiswa.absensi', ['semester' => $sem]) }}"
           style="
               display:inline-flex;align-items:center;justify-content:center;
               min-width:36px;height:32px;padding:0 12px;
               border-radius:20px;font-size:12.5px;font-weight:700;
               text-decoration:none;transition:all .15s;
               {{ $sem == $semester
                   ? 'background:var(--blue);color:#fff;box-shadow:0 2px 8px rgba(37,99,235,.3);'
                   : 'background:#F1F5F9;color:var(--text-2);border:1px solid var(--border);' }}
           "
           onmouseover="{{ $sem != $semester ? "this.style.background='#E2E8F0'" : '' }}"
           onmouseout="{{ $sem != $semester ? "this.style.background='#F1F5F9'" : '' }}">
            {{ $sem }}
        </a>
        @endforeach
    </div>
</div>
 
{{-- Summary cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="absen-mini" style="{{ $sumAlpha >= 14 ? 'border-left:3px solid #EF4444;' : '' }}">
            <div class="absen-mini-label" style="color:#EF4444;">Alpha</div>
            <div class="absen-mini-val" style="color:{{ $sumAlpha >= 14 ? '#EF4444' : 'var(--text-1)' }};">
                {{ $sumAlpha }}<span class="absen-mini-unit">/ 18 jam</span>
            </div>
            <div class="absen-bar">
                <div class="absen-bar-fill" style="width:{{ min(($sumAlpha/18*100),100) }}%;background:{{ $sumAlpha>=18 ? '#EF4444' : ($sumAlpha>=14 ? '#F59E0B' : '#22C55E') }};"></div>
            </div>
            @if($sumAlpha >= 14)
            <div class="absen-warn" style="color:{{ $sumAlpha>=18 ? '#EF4444' : '#F59E0B' }};">
                {{ $sumAlpha >= 18 ? '⛔ Termasuk Mahasiswa Beresiko!' : '⚠ ' . (18-$sumAlpha) . ' jam lagi batas' }}
            </div>
            @endif
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="absen-mini">
            <div class="absen-mini-label" style="color:#FBBF24;">Izin</div>
            <div class="absen-mini-val">{{ $sumIzin }}<span class="absen-mini-unit">jam</span></div>
            <div class="absen-bar">
                <div class="absen-bar-fill" style="width:{{ ($sumIzin+$sumSakit+$sumAlpha)>0 ? round($sumIzin/($sumIzin+$sumSakit+$sumAlpha)*100) : 0 }}%;background:#FBBF24;"></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="absen-mini">
            <div class="absen-mini-label" style="color:#3B82F6;">Sakit</div>
            <div class="absen-mini-val">{{ $sumSakit }}<span class="absen-mini-unit">jam</span></div>
            <div class="absen-bar">
                <div class="absen-bar-fill" style="width:{{ ($sumIzin+$sumSakit+$sumAlpha)>0 ? round($sumSakit/($sumIzin+$sumSakit+$sumAlpha)*100) : 0 }}%;background:#3B82F6;"></div>
            </div>
        </div>
    </div>
</div>
 
{{-- Tabel --}}
<div class="card-white" style="padding:20px 22px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;gap:12px;flex-wrap:wrap;">
        <div>
            <div style="font-size:15px;font-weight:700;color:var(--text-1);">Riwayat Absensi Semester {{ $semester }}</div>
            <div style="font-size:12px;color:var(--text-2);margin-top:2px;">
                Total kehadiran: <strong style="color:{{ $pctHadir>=75 ? '#22C55E' : '#EF4444' }};">{{ $pctHadir }}%</strong>
                · Alpha: <strong style="color:{{ $sumAlpha>=18 ? '#EF4444' : ($sumAlpha>=14 ? '#F59E0B' : 'inherit') }};">{{ $sumAlpha }}j</strong>
            </div>
        </div>
    </div>

    @php
        $absenSem  = $absensis->first();
        $alphaSem  = $absenSem?->jam_alpha ?? 0;
        $statusSem = $alphaSem >= 18 ? 'kritis' : ($alphaSem >= 14 ? 'waspada' : 'aman');
    @endphp

    @if($absenSem)
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="border-bottom:1.5px solid var(--border);">
                    <th style="font-size:11.5px;font-weight:600;color:#EF4444;padding:0 12px 10px;text-align:center;">Jam Alpha</th>
                    <th style="font-size:11.5px;font-weight:600;color:#FBBF24;padding:0 12px 10px;text-align:center;">Jam Izin</th>
                    <th style="font-size:11.5px;font-weight:600;color:#3B82F6;padding:0 12px 10px;text-align:center;">Jam Sakit</th>
                    <th style="font-size:11.5px;font-weight:600;color:var(--text-2);padding:0 12px 10px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background:{{ $alphaSem>=18 ? 'rgba(239,68,68,.03)' : ($alphaSem>=14 ? 'rgba(245,158,11,.03)' : 'transparent') }};">
                    <td style="padding:18px 12px;text-align:center;font-weight:700;font-size:20px;{{ $alphaSem>=18 ? 'border-left:3px solid #EF4444;' : ($alphaSem>=14 ? 'border-left:3px solid #F59E0B;' : '') }}color:{{ $alphaSem>=18 ? '#EF4444' : ($alphaSem>=14 ? '#F59E0B' : 'var(--text-2)') }};">
                        {{ $alphaSem }}{{ $alphaSem>=18 ? ' ⛔' : ($alphaSem>=14 ? ' ⚠️' : '') }}<span style="font-size:13px;font-weight:500;color:var(--text-2);">j</span>
                    </td>
                    <td style="padding:18px 12px;text-align:center;font-weight:600;color:#FBBF24;font-size:20px;">{{ $absenSem->jam_izin }}<span style="font-size:13px;font-weight:500;color:var(--text-2);">j</span></td>
                    <td style="padding:18px 12px;text-align:center;font-weight:600;color:#3B82F6;font-size:20px;">{{ $absenSem->jam_sakit }}<span style="font-size:13px;font-weight:500;color:var(--text-2);">j</span></td>
                    <td style="padding:18px 12px;">
                        @if($statusSem === 'kritis')
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;background:#FEE2E2;color:#991B1B;">⛔ Kritis — SP I</span>
                        @elseif($statusSem === 'waspada')
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;background:#FEF3C7;color:#92400E;">⚠ Waspada</span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;background:#DCFCE7;color:#166534;">✓ Aman</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align:center;padding:40px;color:var(--text-3);">
        <i class="bi bi-calendar-x" style="font-size:32px;display:block;margin-bottom:8px;"></i>
        Belum ada data absensi untuk semester ini.
    </div>
    @endif
 
    {{-- Footer summary --}}
    <div style="display:flex;align-items:center;gap:10px;margin-top:16px;padding-top:14px;border-top:1px solid var(--border);flex-wrap:wrap;">
        <div style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:20px;font-size:12px;font-weight:600;{{ $sumAlpha>=14 ? 'background:#FEE2E2;color:#991B1B;' : 'background:#F1F5F9;color:var(--text-2);' }}">
            <i class="bi bi-x-circle-fill"></i> Alpha: {{ $sumAlpha }}j
        </div>
        <div style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#FEF9C3;color:#854D0E;">
            <i class="bi bi-journal-x"></i> Izin: {{ $sumIzin }}j
        </div>
        <div style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#DBEAFE;color:#1E40AF;">
            <i class="bi bi-thermometer"></i> Sakit: {{ $sumSakit }}j
        </div>
    </div>
</div>
 
@endsection
 
@push('scripts')
@endpush
 