@extends('layouts.dosen')
 
@section('title', 'Detail ' . $mahasiswa->nama)
@section('page-title', $mahasiswa->nama)
@section('page-sub', $mahasiswa->nim . ' · ' . ($mahasiswa->kelas->nama ?? '-') . ' · Semester ' . $semesterAktif)
 
@section('content')
 
<div class="mb-3">
    <a href="{{ route('dosen.dashboard') }}" style="font-size:13px;color:var(--accent);text-decoration:none;font-weight:600;">
        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>
 
{{-- STATUS BERISIKO --}}
@if($mahasiswa->isBerisiko())
<div style="background:linear-gradient(135deg,#e8334a,#c0192d);border-radius:13px;padding:14px 18px;color:#fff;display:flex;align-items:center;gap:14px;margin-bottom:20px;">
    <div style="width:40px;height:40px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;">⚠️</div>
    <div>
        <div style="font-weight:700;font-size:13.5px;">Mahasiswa ini terdeteksi BERISIKO</div>
        <div style="font-size:11.5px;opacity:.88;">Terdapat nilai D/E atau absensi ≥18 jam. Segera lakukan bimbingan akademik.</div>
    </div>
</div>
@endif
 
<div class="row g-4">
    {{-- KIRI --}}
    <div class="col-lg-8">
 
        {{-- TABEL NILAI --}}
        <div class="section-card mb-4">
            <div class="section-header">
                <div>
                    <div class="section-title">Nilai Semester {{ $semesterAktif }}</div>
                    <div class="section-subtitle">Bobot: Tugas 30% · UTS 30% · UAS 40%</div>
                </div>
                <span style="background:var(--navy);color:var(--accent);padding:5px 14px;border-radius:20px;font-size:11px;font-weight:700;font-family:'Space Mono',monospace;">
                    IP: {{ number_format($ip, 2) }}
                </span>
            </div>
            <table style="width:100%;border-collapse:separate;border-spacing:0 5px;">
                <thead>
                    <tr>
                        <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;">Mata Kuliah</th>
                        <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">Tugas</th>
                        <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">UTS</th>
                        <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">UAS</th>
                        <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">Nilai</th>
                        <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilais as $nilai)
                    <tr style="background:{{ in_array($nilai->grade,['D','E']) ? 'rgba(232,51,74,0.05)' : '#f8faff' }};">
                        <td style="padding:10px 12px;border-radius:10px 0 0 10px;{{ in_array($nilai->grade,['D','E']) ? 'border-left:3px solid var(--danger-red)' : '' }}">
                            <div style="font-weight:600;color:var(--navy);font-size:13px;">{{ $nilai->mataKuliah->nama }}</div>
                        </td>
                        <td style="padding:10px 12px;text-align:center;"><span class="score-pill">{{ $nilai->nilai_tugas }}</span></td>
                        <td style="padding:10px 12px;text-align:center;"><span class="score-pill">{{ $nilai->nilai_uts }}</span></td>
                        <td style="padding:10px 12px;text-align:center;"><span class="score-pill">{{ $nilai->nilai_uas }}</span></td>
                        <td style="padding:10px 12px;text-align:center;">
                            <span class="score-pill fw-bold" style="{{ in_array($nilai->grade,['D','E']) ? 'color:var(--danger-red)' : '' }}">{{ number_format($nilai->nilai_akhir,1) }}</span>
                        </td>
                        <td style="padding:10px 12px;text-align:center;border-radius:0 10px 10px 0;">
                            <span class="grade-badge grade-{{ $nilai->grade }}">{{ $nilai->grade }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:20px;color:#8da3c0;">Belum ada nilai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
 
        {{-- TABEL ABSENSI --}}
        <div class="section-card">
            <div class="section-title mb-4">Absensi Semester {{ $semesterAktif }}</div>
            <table style="width:100%;border-collapse:separate;border-spacing:0 5px;">
                <thead>
                    <tr>
                        <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;">Mata Kuliah</th>
                        <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">Hadir</th>
                        <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">Izin</th>
                        <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">Sakit</th>
                        <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">Alpha</th>
                        <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">% Hadir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $absen)
                    @php
                        $total = $absen->jam_hadir + $absen->jam_izin + $absen->jam_sakit + $absen->jam_alpha;
                        $pct   = $total > 0 ? round($absen->jam_hadir / $total * 100) : 0;
                        $kritis = $absen->jam_alpha >= 18;
                    @endphp
                    <tr style="background:{{ $kritis ? 'rgba(232,51,74,0.05)' : '#f8faff' }};">
                        <td style="padding:10px 12px;border-radius:10px 0 0 10px;{{ $kritis ? 'border-left:3px solid var(--danger-red)' : '' }}">
                            <div style="font-weight:600;color:var(--navy);font-size:13px;">{{ $absen->mataKuliah->nama }}</div>
                        </td>
                        <td style="padding:10px 12px;text-align:center;color:var(--success-green);font-weight:700;">{{ $absen->jam_hadir }}</td>
                        <td style="padding:10px 12px;text-align:center;color:var(--teal);font-weight:600;">{{ $absen->jam_izin }}</td>
                        <td style="padding:10px 12px;text-align:center;color:var(--warning-orange);font-weight:600;">{{ $absen->jam_sakit }}</td>
                        <td style="padding:10px 12px;text-align:center;font-weight:800;color:{{ $kritis ? 'var(--danger-red)' : '#5a6e8c' }};">
                            {{ $absen->jam_alpha }} {{ $kritis ? '⛔' : '' }}
                        </td>
                        <td style="padding:10px 12px;border-radius:0 10px 10px 0;min-width:100px;">
                            <div class="progress progress-thin mb-1">
                                <div class="progress-bar {{ $pct >= 75 ? 'bg-success' : 'bg-danger' }}" style="width:{{ $pct }}%"></div>
                            </div>
                            <small style="font-family:'Space Mono';font-size:11px;font-weight:700;color:{{ $pct>=75 ? 'var(--success-green)' : 'var(--danger-red)' }}">{{ $pct }}%</small>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:20px;color:#8da3c0;">Belum ada absensi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
 
    {{-- KANAN --}}
    <div class="col-lg-4">
        <div class="section-card mb-4">
            <div class="section-title mb-3">Profil Mahasiswa</div>
            <div style="display:flex;flex-direction:column;gap:10px;">
                @foreach(['NIM'=>$mahasiswa->nim,'Nama'=>$mahasiswa->nama,'Kelas'=>$mahasiswa->kelas->nama??'-','Angkatan'=>$mahasiswa->angkatan,'Status'=>ucfirst($mahasiswa->status),'Dosen PA'=>$mahasiswa->dosenPa->nama??'-'] as $label => $val)
                <div style="background:#f8faff;border-radius:10px;padding:11px 14px;">
                    <div style="font-size:10.5px;color:#8da3c0;font-weight:700;text-transform:uppercase;letter-spacing:.8px;margin-bottom:3px;">{{ $label }}</div>
                    <div style="font-size:13px;font-weight:600;color:var(--navy);font-family:{{ $label=='NIM' ? '\'Space Mono\'' : 'inherit' }};">{{ $val }}</div>
                </div>
                @endforeach
            </div>
        </div>
 
        <div class="section-card">
            <div class="section-title mb-3">Indeks Prestasi</div>
            <div class="text-center mb-3">
                <div style="font-size:44px;font-weight:800;color:var(--navy);font-family:'Space Mono',monospace;">{{ number_format($ipk, 2) }}</div>
                <div style="font-size:12px;color:#8da3c0;">IPK Kumulatif</div>
            </div>
            <div class="d-flex justify-content-between py-2" style="border-top:1px dashed #e8eef8;">
                <span style="font-size:13px;color:#5a6e8c;">IP Semester {{ $semesterAktif }}</span>
                <strong style="font-family:'Space Mono';">{{ number_format($ip, 2) }}</strong>
            </div>
        </div>
    </div>
</div>
@endsection