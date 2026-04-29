{{-- resources/views/mahasiswa/dashboard.blade.php --}}
@extends('layouts.mahasiswa')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Mahasiswa')
@section('page-sub', 'Semester ' . $semesterAktif . ' • Kelas ' . ($mahasiswa->kelas->nama ?? '-') . ' • TA ' . $tahunAkademik)

@section('content')

{{-- ── ALERT PERINGATAN ── --}}
@if($nilaiDE->count() > 0)
<div class="alert-banner danger mb-3">
    <div class="alert-banner-icon">⚠️</div>
    <div class="flex-1">
        <h6>Peringatan Nilai! Terdapat {{ $nilaiDE->count() }} mata kuliah dengan nilai D/E</h6>
        <p>{{ $nilaiDE->map(fn($n) => $n->mataKuliah->nama . ' (' . $n->grade . ')')->implode(', ') }} — Segera konsultasi dengan DPA Anda.</p>
    </div>
</div>
@endif

@if($absensiKritis->count() > 0)
<div class="alert-banner warning mb-4">
    <div class="alert-banner-icon">🕐</div>
    <div class="flex-1">
        <h6>Peringatan Absensi — {{ $absensiKritis->count() }} Mata Kuliah</h6>
        <p>{{ $absensiKritis->map(fn($a) => $a->mataKuliah->nama . ' (' . $a->jam_alpha . ' jam alpha)')->implode(', ') }} — Mendekati/melewati batas 18 jam.</p>
    </div>
</div>
@endif

{{-- ── STAT CARDS ── --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon gold"><i class="bi bi-award"></i></div>
            <div class="stat-value">{{ number_format($ipk, 2) }}</div>
            <div class="stat-label">IPK Kumulatif</div>
            <span class="stat-delta {{ $ipk >= 3.0 ? 'up' : 'down' }}">
                <i class="bi bi-{{ $ipk >= 3.0 ? 'arrow-up' : 'arrow-down' }}"></i>
                {{ $ipk >= 3.5 ? 'Sangat Memuaskan' : ($ipk >= 3.0 ? 'Memuaskan' : 'Perlu Ditingkatkan') }}
            </span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-journal-bookmark"></i></div>
            <div class="stat-value">{{ $nilais->count() }}</div>
            <div class="stat-label">Mata Kuliah Semester Ini</div>
            <span class="stat-delta up"><i class="bi bi-check2-circle"></i> {{ $nilais->sum('mataKuliah.sks') }} SKS Total</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-calendar2-check"></i></div>
            @php
                $totalJam   = $absensis->sum(fn($a) => $a->jam_hadir + $a->jam_izin + $a->jam_sakit + $a->jam_alpha);
                $totalHadir = $absensis->sum('jam_hadir');
                $pctHadir   = $totalJam > 0 ? round($totalHadir / $totalJam * 100) : 0;
            @endphp
            <div class="stat-value">{{ $pctHadir }}%</div>
            <div class="stat-label">Rata-rata Kehadiran</div>
            <span class="stat-delta {{ $pctHadir >= 75 ? 'up' : 'down' }}">
                <i class="bi bi-{{ $pctHadir >= 75 ? 'check-circle' : 'exclamation-triangle' }}"></i>
                {{ $pctHadir >= 75 ? 'Aman' : 'Waspada' }}
            </span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon {{ ($nilaiDE->count() + $absensiKritis->count()) > 0 ? 'red' : 'green' }}">
                <i class="bi bi-{{ ($nilaiDE->count() + $absensiKritis->count()) > 0 ? 'exclamation-triangle' : 'shield-check' }}"></i>
            </div>
            <div class="stat-value">{{ $nilaiDE->count() + $absensiKritis->count() }}</div>
            <div class="stat-label">Peringatan Aktif</div>
            <span class="stat-delta {{ ($nilaiDE->count() + $absensiKritis->count()) > 0 ? 'down' : 'up' }}">
                <i class="bi bi-bell"></i>
                {{ ($nilaiDE->count() + $absensiKritis->count()) > 0 ? 'Butuh tindakan' : 'Semua aman' }}
            </span>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- KIRI: Tabel Nilai --}}
    <div class="col-lg-8">
        <div class="section-card">
            <div class="section-header">
                <div>
                    <div class="section-title">Nilai Mata Kuliah — Semester {{ $semesterAktif }}</div>
                    <div class="section-subtitle">Bobot: Tugas 30% • UTS 30% • UAS 40%</div>
                </div>
                <span style="background:var(--navy);color:var(--accent);padding:4px 14px;border-radius:20px;font-size:11px;font-weight:700;font-family:'Space Mono',monospace;">
                    IP: {{ number_format($ipSemester, 2) }}
                </span>
            </div>

            <table class="nilai-table">
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        <th class="text-center">SKS</th>
                        <th class="text-center">Tugas</th>
                        <th class="text-center">UTS</th>
                        <th class="text-center">UAS</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilais as $nilai)
                    <tr class="{{ in_array($nilai->grade, ['D','E']) ? 'warn-row' : '' }}">
                        <td>
                            <div class="matkul-name">
                                {{ $nilai->mataKuliah->nama }}
                                @if(in_array($nilai->grade, ['D','E']))
                                    <span style="background:rgba(232,51,74,0.1);color:var(--danger-red);border-radius:6px;padding:2px 8px;font-size:10px;font-weight:700;margin-left:6px;">
                                        ⚠ Perlu Perhatian
                                    </span>
                                @endif
                                @if(isset($rataRataKelas[$nilai->mata_kuliah_id]) && $nilai->nilai_akhir < $rataRataKelas[$nilai->mata_kuliah_id])
                                    <span style="background:rgba(255,159,67,0.1);color:var(--warning-orange);border-radius:6px;padding:2px 8px;font-size:10px;font-weight:700;margin-left:4px;">
                                        ↓ Bawah rata-rata kelas
                                    </span>
                                @endif
                            </div>
                            <div class="matkul-sub">{{ $nilai->mataKuliah->dosen->nama ?? 'Dosen TBA' }}</div>
                        </td>
                        <td class="text-center"><span class="score-pill">{{ $nilai->mataKuliah->sks }}</span></td>
                        <td class="text-center"><span class="score-pill">{{ $nilai->nilai_tugas ?? '—' }}</span></td>
                        <td class="text-center"><span class="score-pill">{{ $nilai->nilai_uts ?? '—' }}</span></td>
                        <td class="text-center"><span class="score-pill">{{ $nilai->nilai_uas ?? '—' }}</span></td>
                        <td class="text-center">
                            <span class="score-pill fw-bold" style="{{ in_array($nilai->grade, ['D','E']) ? 'color:var(--danger-red)' : '' }}">
                                {{ number_format($nilai->nilai_akhir, 1) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="grade-badge grade-{{ $nilai->grade }}">{{ $nilai->grade }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4" style="color:#8da3c0;">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            Belum ada data nilai untuk semester ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-end">
                <a href="{{ route('mahasiswa.nilai') }}" style="font-size:12px;color:var(--teal);font-weight:600;text-decoration:none;">
                    Lihat semua semester <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- ABSENSI RINGKAS --}}
        <div class="section-card">
            <div class="section-header">
                <div>
                    <div class="section-title">Rekap Absensi Semester {{ $semesterAktif }}</div>
                    <div class="section-subtitle">Batas alpha: 18 jam per mata kuliah</div>
                </div>
                <a href="{{ route('mahasiswa.absensi') }}" style="font-size:12px;color:var(--teal);font-weight:600;text-decoration:none;">Detail lengkap →</a>
            </div>
            <div class="row g-3">
                <div class="col-3">
                    <div style="background:rgba(40,199,111,0.08);border:1px solid rgba(40,199,111,0.2);border-radius:12px;padding:14px;text-align:center;">
                        <div style="font-size:26px;font-weight:800;color:var(--success-green);font-family:'Space Mono',monospace;">{{ $absensis->sum('jam_hadir') }}</div>
                        <div style="font-size:11px;color:#8da3c0;font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-top:3px;">Hadir (jam)</div>
                    </div>
                </div>
                <div class="col-3">
                    <div style="background:rgba(0,180,200,0.08);border:1px solid rgba(0,180,200,0.2);border-radius:12px;padding:14px;text-align:center;">
                        <div style="font-size:26px;font-weight:800;color:var(--teal);font-family:'Space Mono',monospace;">{{ $absensis->sum('jam_izin') }}</div>
                        <div style="font-size:11px;color:#8da3c0;font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-top:3px;">Izin (jam)</div>
                    </div>
                </div>
                <div class="col-3">
                    <div style="background:rgba(255,159,67,0.08);border:1px solid rgba(255,159,67,0.2);border-radius:12px;padding:14px;text-align:center;">
                        <div style="font-size:26px;font-weight:800;color:var(--warning-orange);font-family:'Space Mono',monospace;">{{ $absensis->sum('jam_sakit') }}</div>
                        <div style="font-size:11px;color:#8da3c0;font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-top:3px;">Sakit (jam)</div>
                    </div>
                </div>
                <div class="col-3">
                    <div style="background:rgba(232,51,74,0.08);border:1px solid rgba(232,51,74,0.2);border-radius:12px;padding:14px;text-align:center;">
                        <div style="font-size:26px;font-weight:800;color:var(--danger-red);font-family:'Space Mono',monospace;">{{ $absensis->sum('jam_alpha') }}</div>
                        <div style="font-size:11px;color:#8da3c0;font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-top:3px;">Alpha (jam)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KANAN: IPK + Info --}}
    <div class="col-lg-4">

        {{-- IPK CARD --}}
        <div class="section-card mb-4">
            <div class="section-title mb-3">Indeks Prestasi Kumulatif</div>
            <div class="text-center mb-3">
                <div style="font-size:52px;font-weight:800;color:var(--navy);font-family:'Space Mono',monospace;line-height:1;">
                    {{ number_format($ipk, 2) }}
                </div>
                <div style="font-size:12px;color:#8da3c0;margin-top:4px;">IPK Kumulatif</div>
                <div style="margin:12px auto;max-width:200px;">
                    <div class="progress" style="height:10px;border-radius:5px;">
                        <div class="progress-bar" style="width:{{ ($ipk/4)*100 }}%;background:linear-gradient(90deg,var(--accent),var(--accent-soft));border-radius:5px;"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <span style="font-size:10px;color:#8da3c0;">0.00</span>
                        <span style="font-size:10px;color:#8da3c0;">4.00</span>
                    </div>
                </div>
            </div>
            <div style="border-top:1px dashed #e8eef8;padding-top:14px;">
                <div class="d-flex justify-content-between align-items-center py-1">
                    <span style="font-size:13px;color:#5a6e8c;">IP Semester {{ $semesterAktif }}</span>
                    <strong style="font-family:'Space Mono';font-size:14px;color:var(--navy);">{{ number_format($ipSemester, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center py-1">
                    <span style="font-size:13px;color:#5a6e8c;">Total SKS Lulus</span>
                    <strong style="font-family:'Space Mono';font-size:14px;color:var(--navy);">{{ $nilais->whereNotIn('grade', ['E'])->sum('mataKuliah.sks') }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center py-1">
                    <span style="font-size:13px;color:#5a6e8c;">Predikat</span>
                    <strong style="font-size:13px;color:{{ $ipk >= 3.5 ? 'var(--success-green)' : ($ipk >= 3.0 ? 'var(--teal)' : 'var(--warning-orange)') }};">
                        {{ $ipk >= 3.5 ? 'Dengan Pujian' : ($ipk >= 3.0 ? 'Sangat Memuaskan' : ($ipk >= 2.5 ? 'Memuaskan' : 'Cukup')) }}
                    </strong>
                </div>
            </div>
        </div>

        {{-- INFO MAHASISWA --}}
        <div class="section-card mb-4">
            <div class="section-title mb-3">Informasi Akademik</div>
            <div class="d-flex flex-column gap-2">
                <div style="background:#f8faff;border-radius:10px;padding:12px;">
                    <div style="font-size:11px;color:#8da3c0;font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-bottom:4px;">NIM</div>
                    <div style="font-family:'Space Mono';font-size:14px;font-weight:700;color:var(--navy);">{{ $mahasiswa->nim }}</div>
                </div>
                <div style="background:#f8faff;border-radius:10px;padding:12px;">
                    <div style="font-size:11px;color:#8da3c0;font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-bottom:4px;">Kelas</div>
                    <div style="font-size:14px;font-weight:700;color:var(--navy);">{{ $mahasiswa->kelas->nama ?? '-' }}</div>
                </div>
                <div style="background:#f8faff;border-radius:10px;padding:12px;">
                    <div style="font-size:11px;color:#8da3c0;font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-bottom:4px;">Dosen PA</div>
                    <div style="font-size:13px;font-weight:600;color:var(--navy);">{{ $mahasiswa->dosenPa->nama ?? '-' }}</div>
                </div>
                <div style="background:#f8faff;border-radius:10px;padding:12px;">
                    <div style="font-size:11px;color:#8da3c0;font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-bottom:4px;">Status</div>
                    <span class="status-active">● {{ ucfirst($mahasiswa->status) }}</span>
                </div>
            </div>
        </div>

        {{-- PERINGATAN --}}
        @if($nilaiDE->count() > 0 || $absensiKritis->count() > 0)
        <div class="section-card">
            <div class="section-title mb-3">🔔 Peringatan Aktif</div>
            <div class="d-flex flex-column gap-2">
                @foreach($nilaiDE as $n)
                <div style="background:rgba(232,51,74,0.04);border:1px solid rgba(232,51,74,0.15);border-left:3px solid var(--danger-red);border-radius:10px;padding:12px;">
                    <div style="font-size:12px;font-weight:700;color:var(--navy);">Nilai {{ $n->grade }} — {{ $n->mataKuliah->nama }}</div>
                    <div style="font-size:11px;color:#8da3c0;margin-top:2px;">Nilai akhir: {{ number_format($n->nilai_akhir, 1) }} — Konsultasi dengan DPA</div>
                </div>
                @endforeach
                @foreach($absensiKritis as $a)
                <div style="background:rgba(255,159,67,0.04);border:1px solid rgba(255,159,67,0.2);border-left:3px solid var(--warning-orange);border-radius:10px;padding:12px;">
                    <div style="font-size:12px;font-weight:700;color:var(--navy);">Alpha {{ $a->jam_alpha }} jam — {{ $a->mataKuliah->nama }}</div>
                    <div style="font-size:11px;color:#8da3c0;margin-top:2px;">
                        {{ $a->jam_alpha >= 18 ? '⛔ Melewati batas! Tidak boleh mengikuti UAS.' : '⚠ Mendekati batas 18 jam.' }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

@endsection