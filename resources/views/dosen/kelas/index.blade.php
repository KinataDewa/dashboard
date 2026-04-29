@extends('layouts.dosen')
 
@section('title', 'Data Mahasiswa')
@section('page-title', 'Data Mahasiswa Bimbingan')
@section('page-sub', $dosen->nama . ' · ' . $mahasiswas->count() . ' mahasiswa')
 
@section('content')
<div class="section-card">
    <div class="section-header">
        <div>
            <div class="section-title">Daftar Mahasiswa Bimbingan</div>
            <div class="section-subtitle">Klik Detail untuk melihat nilai & absensi lengkap</div>
        </div>
    </div>
    <table style="width:100%;border-collapse:separate;border-spacing:0 5px;">
        <thead>
            <tr>
                <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;">#</th>
                <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;">Mahasiswa</th>
                <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">IPK</th>
                <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;text-align:center;">Status</th>
                <th style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8da3c0;padding:5px 12px;"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswas as $i => $mhs)
            @php $berisiko = $mhs->is_berisiko ?? $mhs->isBerisiko(); @endphp
            <tr style="background:{{ $berisiko ? 'rgba(232,51,74,0.04)' : '#f8faff' }};">
                <td style="padding:10px 12px;color:#b0c0d8;font-size:12px;border-radius:10px 0 0 10px;">{{ $i+1 }}</td>
                <td style="padding:10px 12px;">
                    <div style="font-weight:600;color:var(--navy);">{{ $mhs->nama }}</div>
                    <div style="font-size:11px;color:#8da3c0;font-family:'Space Mono',monospace;">{{ $mhs->nim }}</div>
                </td>
                <td style="padding:10px 12px;text-align:center;">
                    <span style="background:#f0f4fc;border-radius:6px;padding:3px 10px;font-size:12px;font-weight:700;font-family:'Space Mono',monospace;">
                        {{ number_format($mhs->ipk_val ?? $mhs->ipk, 2) }}
                    </span>
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
                       style="background:#f0f4fc;color:var(--navy);border-radius:8px;padding:5px 14px;font-size:11px;font-weight:600;text-decoration:none;">
                        Detail
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection