@extends('layouts.dosen')
 
@section('title', 'Dashboard DPA')
@section('page-title', 'Dashboard DPA — ' . ($kelas->first()->nama ?? 'Kelas Saya'))
@section('page-sub', 'Semester Aktif • ' . $totalMahasiswa . ' Mahasiswa Bimbingan')
 
@section('content')
 
{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
            <div class="stat-value">{{ $totalMahasiswa }}</div>
            <div class="stat-label">Total Mahasiswa</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="stat-value">{{ $totalBerisiko }}</div>
            <div class="stat-label">Mahasiswa Berisiko</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-award-fill"></i></div>
            <div class="stat-value">{{ number_format($rataRataIpk, 2) }}</div>
            <div class="stat-label">Rata-rata IPK Kelas</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="stat-value">{{ $totalNilaiDE }}</div>
            <div class="stat-label">Total Nilai D/E</div>
        </div>
    </div>
</div>
 
@if($totalBerisiko > 0)
<div style="background:linear-gradient(135deg,#e8334a,#c0192d);border-radius:12px;padding:14px 18px;color:#fff;display:flex;align-items:center;gap:12px;margin-bottom:22px;">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    <span style="font-size:13px;font-weight:600;">{{ $totalBerisiko }} mahasiswa terdeteksi berisiko akademik (nilai D/E atau absensi ≥18 jam). Silakan lakukan bimbingan segera.</span>
</div>
@endif
 
<div class="row g-4">
    {{-- TABEL MAHASISWA --}}
    <div class="col-lg-8">
        <div class="section-card">
            <div class="section-header">
                <div>
                    <div class="section-title">Performa Seluruh Mahasiswa</div>
                    <div class="section-subtitle">Klik "Detail" untuk melihat nilai & absensi lengkap</div>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:separate;border-spacing:0 5px;">
                    <thead>
                        <tr>
                            <th style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#8da3c0;padding:5px 12px;">#</th>
                            <th style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#8da3c0;padding:5px 12px;">Mahasiswa</th>
                            <th style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#8da3c0;padding:5px 12px;text-align:center;">IPK</th>
                            <th style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#8da3c0;padding:5px 12px;text-align:center;">Alpha</th>
                            <th style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#8da3c0;padding:5px 12px;text-align:center;">Grade Min</th>
                            <th style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#8da3c0;padding:5px 12px;text-align:center;">Status</th>
                            <th style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#8da3c0;padding:5px 12px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mahasiswas as $i => $mhs)
                        @php
                            $ipkMhs     = $mhs->ipk_val ?? $mhs->ipk;
                            $berisiko   = $mhs->is_berisiko ?? $mhs->isBerisiko();
                            $totalAlpha = $mhs->absensis->sum('jam_alpha');
                            $gradeList  = $mhs->nilais->pluck('grade');
                            $gradeMin   = $gradeList->contains('E') ? 'E' : ($gradeList->contains('D') ? 'D' : ($gradeList->contains('C') ? 'C' : ($gradeList->contains('B') ? 'B' : 'A')));
                        @endphp
                        <tr style="background:{{ $berisiko ? 'rgba(232,51,74,0.04)' : '#f8faff' }};">
                            <td style="padding:10px 12px;font-size:12px;color:#b0c0d8;border-radius:10px 0 0 10px;{{ $berisiko ? 'border-left:3px solid var(--danger-red)' : '' }}">{{ $i+1 }}</td>
                            <td style="padding:10px 12px;">
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:50%;background:{{ $berisiko ? 'linear-gradient(135deg,#e8334a,#c0192d)' : 'linear-gradient(135deg,#00b4c8,#4dd6e5)' }};display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:11px;flex-shrink:0;">
                                        {{ strtoupper(substr($mhs->nama, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600;color:var(--navy);font-size:13px;">{{ $mhs->nama }}</div>
                                        <div style="font-size:11px;color:#8da3c0;font-family:'Space Mono',monospace;">{{ $mhs->nim }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding:10px 12px;text-align:center;">
                                <span style="background:#f0f4fc;border-radius:6px;padding:3px 10px;font-size:12px;font-weight:600;font-family:'Space Mono',monospace;color:{{ $ipkMhs < 3.0 ? 'var(--danger-red)' : 'var(--navy)' }};">
                                    {{ number_format($ipkMhs, 2) }}
                                </span>
                            </td>
                            <td style="padding:10px 12px;text-align:center;font-weight:700;font-size:13px;color:{{ $totalAlpha >= 18 ? 'var(--danger-red)' : ($totalAlpha >= 14 ? 'var(--warning-orange)' : 'var(--success-green)') }};">
                                {{ $totalAlpha }} jam
                            </td>
                            <td style="padding:10px 12px;text-align:center;">
                                <span class="grade-badge grade-{{ $gradeMin }}">{{ $gradeMin }}</span>
                            </td>
                            <td style="padding:10px 12px;text-align:center;">
                                @if($berisiko)
                                    <span class="risk-badge risk-high"><i class="bi bi-exclamation-circle-fill"></i> Berisiko</span>
                                @else
                                    <span class="risk-badge risk-low"><i class="bi bi-check-circle-fill"></i> Aman</span>
                                @endif
                            </td>
                            <td style="padding:10px 12px;border-radius:0 10px 10px 0;">
                                <a href="{{ route('dosen.mahasiswa.detail', $mhs->id) }}"
                                   style="background:#f0f4fc;color:var(--navy);border:none;border-radius:8px;padding:5px 12px;font-size:11px;font-weight:600;text-decoration:none;">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
 
    {{-- KANAN: Mahasiswa Berisiko --}}
    <div class="col-lg-4">
        <div class="section-card">
            <div class="section-title mb-1">🔴 Mahasiswa Berisiko</div>
            <div class="section-subtitle mb-3">Perlu bimbingan segera</div>
            @forelse($mahasiswaBerisiko as $mhs)
            @php
                $nilaiDE    = $mhs->nilais->whereIn('grade',['D','E']);
                $alphaKritis = $mhs->absensis->where('jam_alpha','>=',18);
            @endphp
            <div style="background:rgba(232,51,74,0.04);border:1px solid rgba(232,51,74,0.15);border-radius:10px;padding:12px;margin-bottom:10px;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;">
                    <div>
                        <div style="font-size:13px;font-weight:700;color:var(--navy);">{{ $mhs->nama }}</div>
                        <div style="font-size:11px;color:#8da3c0;font-family:'Space Mono',monospace;">{{ $mhs->nim }}</div>
                    </div>
                    <span class="risk-badge risk-high">Berisiko</span>
                </div>
                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                    @foreach($nilaiDE as $n)
                    <span style="background:rgba(232,51,74,0.1);color:var(--danger-red);border-radius:6px;padding:2px 8px;font-size:10px;font-weight:700;">
                        Nilai {{ $n->grade }} — {{ $n->mataKuliah->nama }}
                    </span>
                    @endforeach
                    @foreach($alphaKritis as $a)
                    <span style="background:rgba(255,159,67,0.1);color:var(--warning-orange);border-radius:6px;padding:2px 8px;font-size:10px;font-weight:700;">
                        Alpha {{ $a->jam_alpha }}j — {{ $a->mataKuliah->nama }}
                    </span>
                    @endforeach
                </div>
                <a href="{{ route('dosen.mahasiswa.detail', $mhs->id) }}"
                   style="display:block;margin-top:10px;text-align:center;background:var(--danger-red);color:#fff;border-radius:8px;padding:6px;font-size:11px;font-weight:700;text-decoration:none;">
                    Lihat Detail
                </a>
            </div>
            @empty
            <div style="text-align:center;padding:20px;color:#8da3c0;">
                <i class="bi bi-shield-check fs-3 d-block mb-2 text-success"></i>
                Tidak ada mahasiswa berisiko saat ini.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
 