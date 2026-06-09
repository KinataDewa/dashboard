@extends('layouts.mahasiswa')

@section('title', 'Kompensasi Saya')
@section('page-title', 'Kompensasi Saya')
@section('page-sub', $mahasiswa->nama . ' · ' . $mahasiswa->nim)

@push('styles')
<style>
.stat-card-v2 {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    height: 100%;
}
.stat-card-accent { height: 4px; }
.stat-card-body   { padding: 16px 18px; display: flex; gap: 12px; align-items: flex-start; }
.stat-icon-box    { width: 40px; height: 40px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
.stat-card-info   { flex: 1; min-width: 0; }
.stat-card-label  { font-size: 11.5px; font-weight: 600; color: var(--text-2); margin-bottom: 3px; }
.stat-card-value  { font-size: 28px; font-weight: 800; line-height: 1; letter-spacing: -1px; }

.tbl-card-v2 { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); padding: 20px 22px; }
.tbl-head-v2 { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; gap: 10px; flex-wrap: wrap; }
.tbl-title-v2 { font-size: 15px; font-weight: 700; color: var(--text-1); }
.tbl-sub-v2   { font-size: 11.5px; color: var(--text-2); margin-top: 1px; }

.ac-table-v2 { width: 100%; border-collapse: collapse; }
.ac-table-v2 thead th {
    font-size: 11.5px; font-weight: 600; color: var(--text-2);
    padding: 0 14px 10px; text-align: left;
    border-bottom: 1.5px solid var(--border); white-space: nowrap;
}
.ac-table-v2 tbody tr { border-bottom: 1px solid #F8FAFC; transition: background .12s; }
.ac-table-v2 tbody tr:last-child { border-bottom: none; }
.ac-table-v2 tbody tr:hover { background: #F8FAFF; }
.ac-table-v2 tbody td { padding: 12px 14px; font-size: 13.5px; }

.section-label {
    font-size: 11px; font-weight: 700; color: var(--text-3);
    text-transform: uppercase; letter-spacing: 1px;
    margin-bottom: 12px; margin-top: 4px;
    display: flex; align-items: center; gap: 8px;
}
.section-label::after { content: ''; flex: 1; height: 1px; background: var(--border); }

@media (max-width: 576px) {
    .stat-card-value { font-size: 22px; }
}
</style>
@endpush

@section('content')

@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg, #1E3A8A 0%, #7C3AED 55%, #6D28D9 100%)',
    'icon'         => 'bi-clipboard2-check-fill',
    'title'        => 'Kompensasi Saya',
    'sub'          => $mahasiswa->nama . ' · ' . $mahasiswa->nim,
    'chips'        => [
        ['icon' => 'bi-clock-fill',       'label' => $totalJamAlpha . ' Jam Alpha Total'],
        ['icon' => 'bi-clipboard2-fill',  'label' => $totalWajibKompen . ' Jam Wajib'],
        ['icon' => 'bi-hourglass-split',  'label' => $totalSisaKompen . ' Jam Sisa'],
    ],
    'badge_num'    => $totalWajibKompen,
    'badge_label'  => "Jam Wajib\nKompen",
    'badge2_num'   => $totalSisaKompen,
    'badge2_label' => "Jam Sisa\nKompen",
])

{{-- ══ STAT CARDS ══ --}}
<div class="section-label">Ringkasan</div>
<div class="row g-3 mb-4">

    {{-- Total Jam Alpha --}}
    <div class="col-sm-4 col-12">
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background:linear-gradient(90deg,#EF4444,#FCA5A5);"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:#FEF2F2;">
                    <i class="bi bi-clock-fill" style="color:#EF4444;"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Total Jam Alpha</div>
                    <div class="stat-card-value" style="color:#EF4444;">
                        {{ $totalJamAlpha }}<span style="font-size:13px;font-weight:500;color:var(--text-2);"> jam</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Wajib Kompen --}}
    <div class="col-sm-4 col-12">
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background:linear-gradient(90deg,#F59E0B,#FCD34D);"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:#FFFBEB;">
                    <i class="bi bi-clipboard2-fill" style="color:#F59E0B;"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Total Wajib Kompen</div>
                    <div class="stat-card-value" style="color:#F59E0B;">
                        {{ $totalWajibKompen }}<span style="font-size:13px;font-weight:500;color:var(--text-2);"> jam</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Sisa Kompen --}}
    <div class="col-sm-4 col-12">
        <div class="stat-card-v2">
            <div class="stat-card-accent" style="background:{{ $totalSisaKompen > 0 ? 'linear-gradient(90deg,#EF4444,#FCA5A5)' : 'linear-gradient(90deg,#22C55E,#86EFAC)' }};"></div>
            <div class="stat-card-body">
                <div class="stat-icon-box" style="background:{{ $totalSisaKompen > 0 ? '#FEF2F2' : '#F0FDF4' }};">
                    <i class="bi bi-{{ $totalSisaKompen > 0 ? 'hourglass-split' : 'check-circle-fill' }}"
                       style="color:{{ $totalSisaKompen > 0 ? '#EF4444' : '#22C55E' }};"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Total Sisa Kompen</div>
                    <div class="stat-card-value" style="color:{{ $totalSisaKompen > 0 ? '#EF4444' : '#22C55E' }};">
                        {{ $totalSisaKompen }}<span style="font-size:13px;font-weight:500;color:var(--text-2);"> jam</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ TABEL ══ --}}
<div class="section-label">Detail Per Semester</div>
<div class="tbl-card-v2">
    <div class="tbl-head-v2">
        <div>
            <div class="tbl-title-v2">Riwayat Kompensasi</div>
            <div class="tbl-sub-v2">{{ $dataSemester->count() }} semester terdaftar</div>
        </div>
    </div>

    @if($dataSemester->isEmpty())
    <div style="text-align:center;padding:40px 24px;color:var(--text-3);">
        <i class="bi bi-clipboard2-x" style="font-size:32px;display:block;margin-bottom:8px;opacity:.45;"></i>
        <div style="font-size:14px;font-weight:600;color:var(--text-2);">Belum ada data absensi</div>
        <div style="font-size:12.5px;margin-top:4px;">Data akan muncul setelah ada riwayat kehadiran.</div>
    </div>
    @else
    <div style="overflow-x:auto;">
        <table class="ac-table-v2">
            <thead>
                <tr>
                    <th>Semester</th>
                    <th style="text-align:right;">Jam Alpha</th>
                    <th style="text-align:right;">Wajib Kompen</th>
                    <th style="text-align:right;">Sudah Diselesaikan</th>
                    <th style="text-align:right;">Sisa</th>
                    <th style="text-align:center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dataSemester as $row)
                <tr>
                    <td>
                        <div style="font-weight:600;color:var(--text-1);">Semester {{ $row->semester }}</div>
                    </td>
                    <td style="text-align:right;font-weight:700;color:{{ $row->jam_alpha >= 18 ? '#EF4444' : 'var(--text-1)' }};">
                        {{ $row->jam_alpha }} jam
                    </td>
                    <td style="text-align:right;font-weight:600;color:var(--text-2);">
                        @if($row->status === 'aman')
                            <span style="color:var(--text-3);">—</span>
                        @else
                            {{ $row->jam_kompen_wajib }} jam
                        @endif
                    </td>
                    <td style="text-align:right;font-weight:600;color:#22C55E;">
                        @if($row->jam_kompen_selesai > 0)
                            {{ $row->jam_kompen_selesai }} jam
                        @else
                            <span style="color:var(--text-3);">—</span>
                        @endif
                    </td>
                    <td style="text-align:right;font-weight:700;color:{{ $row->jam_kompen_sisa > 0 ? '#EF4444' : 'var(--text-3)' }};">
                        @if($row->jam_kompen_sisa > 0)
                            {{ $row->jam_kompen_sisa }} jam
                        @else
                            —
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if($row->status === 'aman')
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 11px;border-radius:20px;font-size:11.5px;font-weight:700;background:#DCFCE7;color:#15803D;">
                                <i class="bi bi-shield-check" style="font-size:10px;"></i> Aman
                            </span>
                        @elseif($row->status === 'lunas')
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 11px;border-radius:20px;font-size:11.5px;font-weight:700;background:#DBEAFE;color:#1E40AF;">
                                <i class="bi bi-check-circle-fill" style="font-size:10px;"></i> Lunas
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 11px;border-radius:20px;font-size:11.5px;font-weight:700;background:#FEE2E2;color:#991B1B;">
                                <i class="bi bi-exclamation-circle-fill" style="font-size:10px;"></i> Belum Lunas
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Footer summary --}}
    <div style="display:flex;align-items:center;gap:8px;margin-top:14px;padding-top:12px;border-top:1px solid var(--border);flex-wrap:wrap;">
        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:#FEF2F2;color:#991B1B;">
            <i class="bi bi-clock-fill"></i> {{ $totalJamAlpha }}j Alpha
        </span>
        @if($totalWajibKompen > 0)
        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:#FFFBEB;color:#92400E;">
            <i class="bi bi-clipboard2-fill"></i> {{ $totalWajibKompen }}j Wajib
        </span>
        @endif
        @if($totalSisaKompen > 0)
        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:#FEE2E2;color:#991B1B;">
            <i class="bi bi-hourglass-split"></i> {{ $totalSisaKompen }}j Sisa
        </span>
        @else
        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:#DCFCE7;color:#15803D;">
            <i class="bi bi-check-circle-fill"></i> Semua kompensasi selesai
        </span>
        @endif
    </div>
    @endif
</div>

@endsection
