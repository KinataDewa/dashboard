@extends('layouts.admin')
@section('title', 'Kelas ' . $kela->nama . ' Sem ' . $kela->semester)
@section('page-title', 'Kelas ' . $kela->nama)
@section('page-sub', 'Angkatan ' . $kela->angkatan . ' · Semester ' . $kela->semester . ' · ' . $kela->tahun_akademik)

@push('styles')
<style>
.info-row{display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid #F1F5F9;}
.info-row:last-child{border-bottom:none;}
.info-label{font-size:12px;color:var(--text-2);font-weight:500;}
.info-val{font-size:13px;font-weight:700;color:var(--text-1);}
.risk-pill{display:inline-flex;gap:4px;flex-wrap:wrap;}
.risk-tag{font-size:10.5px;font-weight:700;padding:2px 8px;border-radius:20px;}
.risk-sp1{background:#FEF3C7;color:#92400E;}
.risk-sp2{background:#FDE68A;color:#78350F;}
.risk-sp3{background:#FECACA;color:#991B1B;}
.risk-ps {background:#7F1D1D;color:#fff;}
.risk-nilai_e{background:#FEE2E2;color:#7F1D1D;}
.risk-nilai_d{background:#FFF7ED;color:#C2410C;}
.risk-ips_rendah{background:#F5F3FF;color:#6D28D9;}
.stat-box{background:#F8FAFC;border:1px solid var(--border);border-radius:10px;padding:16px 20px;text-align:center;}
.stat-num{font-size:28px;font-weight:900;color:var(--blue);}
.stat-lbl{font-size:11px;color:var(--text-2);font-weight:500;margin-top:2px;}
</style>
@endpush

@section('content')

@include('components.page-banner', [
    'gradient'    => 'linear-gradient(135deg, #1E3A5F 0%, #2563EB 55%, #60A5FA 100%)',
    'icon'        => 'bi-people-fill',
    'title'       => 'Kelas ' . $kela->nama,
    'sub'         => 'Angkatan ' . $kela->angkatan . ' · Semester ' . $kela->semester . ' · ' . $kela->tahun_akademik,
    'chips'       => [
        ['icon' => 'bi-people-fill',    'label' => $mahasiswas->count() . ' Mahasiswa'],
        ['icon' => 'bi-exclamation-triangle-fill', 'label' => $totalBerisiko . ' Berisiko'],
        ['icon' => 'bi-mortarboard-fill','label' => 'IPK Rata-rata ' . $rataIpk],
    ],
    'badge_num'   => $mahasiswas->count(),
    'badge_label' => "Total\nMahasiswa",
])

{{-- Back + Info --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:16px;">
    <a href="{{ route('admin.kelas.index') }}" class="btn-sec" style="display:inline-flex;align-items:center;gap:6px;padding:7px 16px;font-size:13px;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <a href="{{ route('admin.kelas.edit', $kela->id) }}" class="btn-edit" style="padding:7px 16px;font-size:13px;">
        <i class="bi bi-pencil-fill"></i> Edit Kelas
    </a>
</div>

{{-- Info Kelas + Stat --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
    {{-- Info Card --}}
    <div class="card-white" style="padding:20px;">
        <div style="font-size:13px;font-weight:700;color:var(--text-1);margin-bottom:12px;display:flex;align-items:center;gap:8px;">
            <i class="bi bi-info-circle" style="color:var(--blue);"></i> Informasi Kelas
        </div>
        <div class="info-row">
            <span class="info-label">Nama Kelas</span>
            <span class="info-val">{{ $kela->nama }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Angkatan</span>
            <span class="info-val">{{ $kela->angkatan }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Semester</span>
            <span class="info-val">Semester {{ $kela->semester }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tahun Akademik</span>
            <span class="info-val">{{ $kela->tahun_akademik }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Program Studi</span>
            <span class="info-val">{{ $kela->prodi }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Dosen PA</span>
            <span class="info-val">{{ $kela->dosenPa->nama ?? '-' }}</span>
        </div>
    </div>

    {{-- Stat Card --}}
    <div class="card-white" style="padding:20px;">
        <div style="font-size:13px;font-weight:700;color:var(--text-1);margin-bottom:12px;display:flex;align-items:center;gap:8px;">
            <i class="bi bi-bar-chart-fill" style="color:var(--blue);"></i> Statistik Kelas
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <div class="stat-box">
                <div class="stat-num">{{ $mahasiswas->count() }}</div>
                <div class="stat-lbl">Total Mahasiswa</div>
            </div>
            <div class="stat-box" style="border-color:#FCA5A5;">
                <div class="stat-num" style="color:#DC2626;">{{ $totalBerisiko }}</div>
                <div class="stat-lbl">Berisiko</div>
            </div>
            <div class="stat-box" style="border-color:#A5B4FC;">
                <div class="stat-num" style="color:#4F46E5;">{{ $rataIpk }}</div>
                <div class="stat-lbl">IPK Rata-rata</div>
            </div>
            <div class="stat-box" style="border-color:#6EE7B7;">
                <div class="stat-num" style="color:#059669;">
                    {{ $mahasiswas->count() > 0 ? $mahasiswas->where('is_berisiko', false)->count() : 0 }}
                </div>
                <div class="stat-lbl">Aman</div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Mahasiswa --}}
<div class="section-label">Daftar Mahasiswa</div>
<div class="card-white tbl-card-v2">
    <div class="tbl-head-v2">
        <div>
            <div class="tbl-title-v2">Mahasiswa Kelas {{ $kela->nama }}</div>
            <div class="tbl-sub-v2">Semester {{ $kela->semester }} · {{ $kela->tahun_akademik }}</div>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="ac-table-v2">
            <thead>
                <tr>
                    <th style="width:32px;">#</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th style="text-align:center;">IPS</th>
                    <th style="text-align:center;">IPK</th>
                    <th style="text-align:center;">Alpha (jam)</th>
                    <th>Status Risiko</th>
                    <th style="text-align:center;width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswas as $i => $mhs)
                <tr>
                    <td style="color:var(--text-3);font-size:12px;">{{ $i + 1 }}</td>
                    <td>
                        <span style="font-size:12px;font-family:monospace;color:var(--text-2);">{{ $mhs->nim }}</span>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13.5px;color:var(--text-1);">{{ $mhs->nama }}</div>
                        <div style="font-size:11px;color:var(--text-3);">{{ $mhs->status }}</div>
                    </td>
                    <td style="text-align:center;">
                        @php $ips = $mhs->ips_val; @endphp
                        <span style="font-weight:700;font-size:14px;color:{{ $ips >= 3.0 ? '#059669' : ($ips >= 2.0 ? '#D97706' : '#DC2626') }};">
                            {{ number_format($ips, 2) }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        @php $ipk = $mhs->ipk_val; @endphp
                        <span style="font-weight:700;font-size:14px;color:{{ $ipk >= 3.0 ? '#059669' : ($ipk >= 2.0 ? '#D97706' : '#DC2626') }};">
                            {{ number_format($ipk, 2) }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        @php $alpha = $mhs->alpha_val; @endphp
                        <span style="font-weight:700;font-size:14px;color:{{ $alpha >= 18 ? '#DC2626' : ($alpha > 0 ? '#D97706' : '#059669') }};">
                            {{ $alpha }}
                        </span>
                    </td>
                    <td>
                        @if($mhs->is_berisiko)
                            <div class="risk-pill">
                                @foreach($mhs->kategori as $kat)
                                    <span class="risk-tag risk-{{ $kat }}">
                                        {{ match($kat) {
                                            'sp1'       => 'SP1',
                                            'sp2'       => 'SP2',
                                            'sp3'       => 'SP3',
                                            'ps'        => 'PS',
                                            'nilai_e'   => 'Nilai E',
                                            'nilai_d'   => 'Nilai D >3',
                                            'ips_rendah'=> 'IPS < 2',
                                            default     => $kat,
                                        } }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span style="background:#DCFCE7;color:#166534;border-radius:20px;padding:3px 10px;font-size:11px;font-weight:600;">Aman</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <a href="{{ route('admin.mahasiswa.show', $mhs->id) }}" class="btn-view" title="Detail mahasiswa">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <p>Belum ada mahasiswa di kelas ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
