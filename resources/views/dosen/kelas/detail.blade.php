@extends('layouts.dosen')
 
@section('title', 'Detail ' . $mahasiswa->nama)
@section('page-title', $mahasiswa->nama)
@section('page-sub', $mahasiswa->nim . ' · ' . ($mahasiswa->kelas->nama ?? '-') . ' · Semester ' . $semesterAktif)
 
@push('styles')
<style>
.grade-pill{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:50%;font-size:13px;font-weight:800;}
.grade-A{background:#DCFCE7;color:#15803D;}
.grade-B{background:#DBEAFE;color:#1D4ED8;}
.grade-C{background:#FEF9C3;color:#854D0E;}
.grade-D{background:#FEE2E2;color:#991B1B;}
.grade-E{background:#FEE2E2;color:#7F1D1D;}
.score-bar{width:60px;height:4px;background:#F1F5F9;border-radius:2px;overflow:hidden;display:inline-block;vertical-align:middle;margin-left:6px;}
.score-bar-fill{height:100%;border-radius:2px;}
.info-row{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #F1F5F9;}
.info-row:last-child{border-bottom:none;}
.info-label{font-size:12px;color:var(--text-2);font-weight:500;}
.info-val{font-size:13.5px;font-weight:600;color:var(--text-1);}
.ipk-bar{width:100%;height:6px;background:#EFF6FF;border-radius:3px;margin-top:8px;overflow:hidden;}
.ipk-bar-fill{height:100%;border-radius:3px;background:linear-gradient(90deg,#2563EB,#60A5FA);}
</style>
@endpush
 
@section('content')
 
{{-- ══ BANNER ══ --}}
@php
    $isRiskyMhs = $mahasiswa->isBerisiko();
    $alphaTotal = $absensis->sum('jam_alpha');
    $nilaiDECount = $nilais->whereIn('grade',['D','E'])->count();
@endphp
@include('components.page-banner', [
    'gradient'     => $isRiskyMhs
        ? 'linear-gradient(135deg, #7F1D1D 0%, #DC2626 55%, #EF4444 100%)'
        : 'linear-gradient(135deg, #14532D 0%, #15803D 55%, #22C55E 100%)',
    'icon'         => $isRiskyMhs ? 'bi-exclamation-triangle-fill' : 'bi-person-check-fill',
    'title'        => $mahasiswa->nama,
    'sub'          => $mahasiswa->nim . ' · ' . ($mahasiswa->kelas->nama ?? '') . ' · Semester ' . $semesterAktif,
    'chips'        => [
        ['icon' => 'bi-mortarboard-fill',          'label' => 'IPK ' . number_format($ipk, 2)],
        ['icon' => 'bi-calendar2-check',           'label' => 'IP Sem ' . number_format($ip, 2)],
        ['icon' => 'bi-x-circle-fill',             'label' => $alphaTotal . ' Jam Alpha'],
        ['icon' => $isRiskyMhs ? 'bi-exclamation-circle-fill' : 'bi-shield-check-fill',
                                                   'label' => $isRiskyMhs ? 'Status: Berisiko' : 'Status: Aman'],
    ],
    'badge_num'    => number_format($ipk, 2),
    'badge_label'  => "IPK\nKumulatif",
    'badge2_num'   => $nilaiDECount,
    'badge2_label' => "Nilai\nD/E",
])

 
<div class="row g-4">
    {{-- KIRI: Nilai & Absensi --}}
    <div class="col-lg-8">
 
        {{-- Tabel Nilai --}}
        <div class="section-label">Nilai Akademik</div>
        <div class="card-white tbl-card-v2 mb-4">
            <div class="tbl-head-v2">
                <div>
                    <div class="tbl-title-v2">Nilai Semester {{ $semesterAktif }}</div>
                    <div class="tbl-sub-v2">Bobot: Tugas 30% · UTS 30% · UAS 40%</div>
                </div>
                <span style="background:var(--blue-light);color:var(--blue);padding:5px 14px;border-radius:20px;font-size:12px;font-weight:700;font-family:monospace;">
                    IP: {{ number_format($ip, 2) }}
                </span>
            </div>
            <div style="overflow-x:auto;">
                <table class="ac-table-v2">
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th style="text-align:center;">Tugas</th>
                            <th style="text-align:center;">UTS</th>
                            <th style="text-align:center;">UAS</th>
                            <th style="text-align:center;">Nilai</th>
                            <th style="text-align:center;">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nilais as $nilai)
                        @php $isDE = in_array($nilai->grade, ['D','E']); @endphp
                        <tr style="{{ $isDE ? 'background:rgba(239,68,68,.03);' : '' }}">
                            <td style="{{ $isDE ? 'border-left:3px solid #EF4444;' : '' }}">
                                <div style="font-weight:500;">{{ $nilai->mataKuliah->nama }}</div>
                                <div style="font-size:11px;color:var(--text-3);font-family:monospace;">{{ $nilai->mataKuliah->kode }}</div>
                            </td>
                            <td style="text-align:center;" class="muted">{{ $nilai->nilai_tugas }}</td>
                            <td style="text-align:center;" class="muted">{{ $nilai->nilai_uts }}</td>
                            <td style="text-align:center;" class="muted">{{ $nilai->nilai_uas }}</td>
                            <td style="text-align:center;">
                                <span style="font-weight:700;color:{{ $isDE ? '#EF4444' : 'var(--text-1)' }};">
                                    {{ number_format($nilai->nilai_akhir, 1) }}
                                </span>
                                <div class="score-bar">
                                    <div class="score-bar-fill" style="width:{{ $nilai->nilai_akhir }}%;background:{{ $isDE ? '#EF4444' : ($nilai->grade==='A' ? '#22C55E' : '#3B82F6') }};"></div>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <span class="grade-pill grade-{{ $nilai->grade }}">{{ $nilai->grade }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-3);">Belum ada data nilai.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
 
        {{-- Tabel Absensi --}}
        <div class="section-label">Riwayat Absensi</div>
        <div class="card-white tbl-card-v2">
            <div class="tbl-head-v2">
                <div>
                    <div class="tbl-title-v2">Absensi Semester {{ $semesterAktif }}</div>
                    <div class="tbl-sub-v2">Batas alpha: 18 jam per mata kuliah</div>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table class="ac-table-v2">
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th style="text-align:center;">Hadir</th>
                            <th style="text-align:center;">Izin</th>
                            <th style="text-align:center;">Sakit</th>
                            <th style="text-align:center;">Alpha</th>
                            <th style="text-align:center;">% Hadir</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensis as $absen)
                        @php
                            $total  = $absen->jam_hadir + $absen->jam_izin + $absen->jam_sakit + $absen->jam_alpha;
                            $pct    = $total > 0 ? round($absen->jam_hadir / $total * 100) : 0;
                            $kritis = $absen->jam_alpha >= 18;
                            $waspada = $absen->jam_alpha >= 14 && !$kritis;
                        @endphp
                        <tr style="{{ $kritis ? 'background:rgba(239,68,68,.03);' : '' }}">
                            <td style="{{ $kritis ? 'border-left:3px solid #EF4444;' : '' }}">
                                <div style="font-weight:500;">{{ $absen->mataKuliah->nama }}</div>
                                <div style="font-size:11px;color:var(--text-3);font-family:monospace;">{{ $absen->mataKuliah->kode }}</div>
                            </td>
                            <td style="text-align:center;font-weight:600;color:#22C55E;">{{ $absen->jam_hadir }}</td>
                            <td style="text-align:center;font-weight:600;color:#FBBF24;">{{ $absen->jam_izin }}</td>
                            <td style="text-align:center;font-weight:600;color:#3B82F6;">{{ $absen->jam_sakit }}</td>
                            <td style="text-align:center;font-weight:700;color:{{ $kritis ? '#EF4444' : ($waspada ? '#F59E0B' : 'var(--text-2)') }};">
                                {{ $absen->jam_alpha }}j
                                @if($kritis) ⛔ @elseif($waspada) ⚠️ @endif
                            </td>
                            <td style="text-align:center;">
                                <div style="display:flex;align-items:center;gap:6px;justify-content:center;">
                                    <div style="width:44px;height:4px;background:#F1F5F9;border-radius:2px;overflow:hidden;">
                                        <div style="height:100%;width:{{ $pct }}%;background:{{ $pct>=75 ? '#22C55E' : '#EF4444' }};border-radius:2px;"></div>
                                    </div>
                                    <span style="font-size:12px;font-weight:700;color:{{ $pct>=75 ? '#22C55E' : '#EF4444' }};">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td>
                                @if($kritis)
                                    <span class="badge badge-red">⛔ Melewati Batas</span>
                                @elseif($waspada)
                                    <span class="badge" style="background:#FEF3C7;color:#92400E;">⚠ Waspada</span>
                                @else
                                    <span class="badge badge-green">✓ Aman</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" style="text-align:center;padding:24px;color:var(--text-3);">Belum ada data absensi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
 
    {{-- KANAN: Profil & IP --}}
    <div class="col-lg-4">
 
        {{-- Profil --}}
        <div class="section-label">Profil Mahasiswa</div>
        <div class="card-white tbl-card-v2 mb-4">
            {{-- Avatar besar --}}
            <div style="text-align:center;padding:20px 0 16px;">
                <div style="width:64px;height:64px;border-radius:50%;background:var(--blue);color:#fff;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:800;margin:0 auto 12px;">
                    {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
                </div>
                <div style="font-size:16px;font-weight:700;color:var(--text-1);">{{ $mahasiswa->nama }}</div>
                <div style="font-size:12px;color:var(--text-2);margin-top:2px;font-family:monospace;">{{ $mahasiswa->nim }}</div>
                @if($mahasiswa->isBerisiko())
                <span style="display:inline-flex;align-items:center;gap:4px;background:#FEE2E2;color:#991B1B;border-radius:20px;padding:3px 10px;font-size:11px;font-weight:700;margin-top:8px;">
                    <i class="bi bi-exclamation-triangle-fill"></i> Berisiko
                </span>
                @else
                <span style="display:inline-flex;align-items:center;gap:4px;background:#DCFCE7;color:#166534;border-radius:20px;padding:3px 10px;font-size:11px;font-weight:700;margin-top:8px;">
                    <i class="bi bi-check-circle-fill"></i> Aman
                </span>
                @endif
            </div>
 
            <div style="border-top:1px solid var(--border);padding-top:14px;">
                <div class="info-row">
                    <span class="info-label">Kelas</span>
                    <span class="info-val">{{ $mahasiswa->kelas->nama ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Angkatan</span>
                    <span class="info-val">{{ $mahasiswa->angkatan }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="badge badge-green">{{ ucfirst($mahasiswa->status) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Dosen PA</span>
                    <span class="info-val" style="font-size:12px;">{{ $mahasiswa->dosenPa->nama ?? '-' }}</span>
                </div>
            </div>
        </div>
 
        {{-- IPK --}}
        <div class="section-label">Indeks Prestasi</div>
        <div class="card-white tbl-card-v2">
            <div style="text-align:center;padding:8px 0 20px;">
                <div style="font-size:48px;font-weight:800;color:var(--blue);letter-spacing:-2px;line-height:1;">
                    {{ number_format($ipk, 2) }}
                </div>
                <div style="font-size:12px;color:var(--text-2);margin-top:4px;">IPK Kumulatif</div>
                <div class="ipk-bar" style="max-width:160px;margin:12px auto 0;">
                    <div class="ipk-bar-fill" style="width:{{ ($ipk/4)*100 }}%;"></div>
                </div>
                <div style="margin-top:8px;">
                    @if($ipk >= 3.5)
                        <span class="badge badge-green">Dengan Pujian</span>
                    @elseif($ipk >= 3.0)
                        <span class="badge" style="background:#DBEAFE;color:#1D4ED8;">Sangat Memuaskan</span>
                    @elseif($ipk >= 2.5)
                        <span class="badge badge-yellow">Memuaskan</span>
                    @else
                        <span class="badge badge-red">Perlu Ditingkatkan</span>
                    @endif
                </div>
            </div>
            <div style="border-top:1px solid var(--border);padding-top:14px;">
                <div class="info-row">
                    <span class="info-label">IP Semester {{ $semesterAktif }}</span>
                    <span class="info-val" style="font-family:monospace;color:var(--blue);">{{ number_format($ip, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Mata Kuliah</span>
                    <span class="info-val">{{ $nilais->count() }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nilai D/E</span>
                    <span class="info-val" style="color:{{ $nilais->whereIn('grade',['D','E'])->count() > 0 ? '#EF4444' : '#22C55E' }};">
                        {{ $nilais->whereIn('grade',['D','E'])->count() }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Alpha</span>
                    @php $totAlpha = $absensis->sum('jam_alpha'); @endphp
                    <span class="info-val" style="color:{{ $totAlpha >= 18 ? '#EF4444' : 'var(--text-1)' }};">
                        {{ $totAlpha }} jam
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
 
@endsection