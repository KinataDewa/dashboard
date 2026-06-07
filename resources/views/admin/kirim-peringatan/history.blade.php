{{-- resources/views/admin/kirim-peringatan/history.blade.php --}}
@extends('layouts.admin')
@section('title', 'History Email Peringatan')
@section('page-title', 'History Email Peringatan')
@section('page-sub', 'Riwayat pengiriman email peringatan akademik')

@push('styles')
<style>
.sum-cards{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:24px;}
.sum-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;}
.sum-card-bar{height:3px;}
.sum-card-body{padding:14px 16px;}
.sum-card-val{font-size:28px;font-weight:800;line-height:1;letter-spacing:-1px;}
.sum-card-lbl{font-size:12px;color:var(--text-2);margin-top:4px;font-weight:500;}

.filter-bar{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:14px 20px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px;}
.filter-label{font-size:12px;font-weight:700;color:var(--text-2);white-space:nowrap;}
.filter-select{padding:8px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-1);background:var(--white);outline:none;cursor:pointer;}
.filter-select:focus{border-color:var(--blue);}
.filter-input{padding:8px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-1);background:var(--white);outline:none;min-width:200px;}
.filter-input:focus{border-color:var(--blue);}

.log-table{width:100%;border-collapse:collapse;}
.log-table thead th{font-size:11px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.6px;padding:10px 14px;border-bottom:1.5px solid var(--border);background:#FAFBFF;white-space:nowrap;}
.log-table tbody tr{border-bottom:1px solid #F8FAFC;transition:background .1s;}
.log-table tbody tr:last-child{border-bottom:none;}
.log-table tbody tr:hover{background:#FAFBFF;}
.log-table tbody td{padding:11px 14px;vertical-align:middle;font-size:13px;}

.badge-berhasil{display:inline-flex;align-items:center;gap:3px;background:#DCFCE7;color:#15803D;border-radius:99px;padding:3px 10px;font-size:11px;font-weight:700;}
.badge-gagal{display:inline-flex;align-items:center;gap:3px;background:#FEE2E2;color:#991B1B;border-radius:99px;padding:3px 10px;font-size:11px;font-weight:700;}
.badge-nilai{display:inline-flex;align-items:center;gap:3px;background:#FEF9C3;color:#854D0E;border-radius:99px;padding:3px 8px;font-size:10.5px;font-weight:700;white-space:nowrap;}
.badge-alpha{display:inline-flex;align-items:center;gap:3px;background:#EDE9FE;color:#5B21B6;border-radius:99px;padding:3px 8px;font-size:10.5px;font-weight:700;white-space:nowrap;}
.badge-both{display:inline-flex;align-items:center;gap:3px;background:#FEE2E2;color:#991B1B;border-radius:99px;padding:3px 8px;font-size:10.5px;font-weight:700;white-space:nowrap;}

.avatar{width:32px;height:32px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0;}

.btn-back{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;background:var(--white);color:var(--text-1);border:1.5px solid var(--border);text-decoration:none;transition:all .15s;}
.btn-back:hover{border-color:var(--blue);color:var(--blue);}

.empty-state{text-align:center;padding:60px 20px;color:var(--text-3);}
.empty-state i{font-size:40px;display:block;margin-bottom:10px;opacity:.25;}

/* Tooltip error */
.error-tooltip{position:relative;cursor:help;}
.error-tooltip:hover::after{content:attr(data-error);position:absolute;bottom:100%;left:50%;transform:translateX(-50%);background:#0F172A;color:#fff;padding:6px 10px;border-radius:8px;font-size:11px;white-space:nowrap;max-width:280px;white-space:normal;z-index:999;margin-bottom:4px;}

@media(max-width:768px){
    .sum-cards{grid-template-columns:repeat(2,1fr);}
    .hide-mobile{display:none!important;}
}
</style>
@endpush

@section('content')

{{-- Banner --}}
@include('components.page-banner', [
    'gradient'    => 'linear-gradient(135deg, #064E3B 0%, #065F46 55%, #10B981 100%)',
    'icon'        => 'bi-clock-history',
    'title'       => 'History Email Peringatan',
    'sub'         => 'Riwayat seluruh pengiriman email peringatan akademik',
    'chips'       => [
        ['icon' => 'bi-envelope-fill',       'label' => $totalLogs . ' Total Email'],
        ['icon' => 'bi-check-circle-fill',   'label' => $totalBerhasil . ' Berhasil'],
        ['icon' => 'bi-x-circle-fill',       'label' => $totalGagal . ' Gagal'],
        ['icon' => 'bi-calendar-check',      'label' => $terakhirKirim ? 'Terakhir: ' . $terakhirKirim->format('d M Y') : 'Belum ada'],
    ],
    'badge_num'   => $totalLogs,
    'badge_label' => "Total\nEmail",
])

{{-- Summary Cards --}}
<div class="sum-cards">
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#2563EB,#60A5FA);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#2563EB;">{{ $totalLogs }}</div>
            <div class="sum-card-lbl">Total Email Dikirim</div>
        </div>
    </div>
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#22C55E,#86EFAC);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#22C55E;">{{ $totalBerhasil }}</div>
            <div class="sum-card-lbl">Berhasil Terkirim</div>
        </div>
    </div>
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#EF4444,#FCA5A5);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#EF4444;">{{ $totalGagal }}</div>
            <div class="sum-card-lbl">Gagal Terkirim</div>
        </div>
    </div>
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#F59E0B,#FCD34D);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#F59E0B;font-size:18px;">
                {{ $terakhirKirim ? $terakhirKirim->format('d M Y') : '—' }}
            </div>
            <div class="sum-card-lbl">Terakhir Kirim</div>
        </div>
    </div>
</div>

{{-- Filter + Tombol Kembali --}}
<form method="GET" action="{{ route('admin.kirim-peringatan.history') }}">
<div class="filter-bar">
    <a href="{{ route('admin.kirim-peringatan.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Kirim Peringatan
    </a>

    <span class="filter-label" style="margin-left:8px;">Status:</span>
    <select name="status" class="filter-select" onchange="this.form.submit()">
        <option value="semua" {{ $filterStatus === 'semua' ? 'selected' : '' }}>Semua</option>
        <option value="berhasil" {{ $filterStatus === 'berhasil' ? 'selected' : '' }}>✅ Berhasil</option>
        <option value="gagal" {{ $filterStatus === 'gagal' ? 'selected' : '' }}>❌ Gagal</option>
    </select>

    <span class="filter-label">Kelas:</span>
    <select name="kelas" class="filter-select" onchange="this.form.submit()">
        <option value="">Semua Kelas</option>
        @foreach($kelasList as $k)
        <option value="{{ $k }}" {{ $filterKelas === $k ? 'selected' : '' }}>{{ $k }}</option>
        @endforeach
    </select>

    <input type="text" name="cari" class="filter-input" placeholder="Cari nama / email..."
           value="{{ $cari }}" onchange="this.form.submit()">
</div>
</form>

{{-- Tabel --}}
<div class="card-white tbl-card-v2">
    <div class="tbl-head-v2">
        <div>
            <div class="tbl-title-v2">Riwayat Pengiriman Email</div>
            <div class="tbl-sub-v2">
                {{ $logs->total() }} log pengiriman
                @if($filterStatus !== 'semua') · Filter: {{ ucfirst($filterStatus) }} @endif
                @if($filterKelas) · Kelas: {{ $filterKelas }} @endif
            </div>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="log-table">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Mahasiswa</th>
                    <th class="hide-mobile">Kelas</th>
                    <th style="text-align:center;">Kategori Risiko</th>
                    <th class="hide-mobile" style="text-align:center;">D/E</th>
                    <th class="hide-mobile" style="text-align:center;">Alpha</th>
                    <th style="text-align:center;">Status</th>
                    <th class="hide-mobile">Dikirim Oleh</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $i => $log)
                @php
                    $colors  = ['#2563EB','#EF4444','#8B5CF6','#F59E0B','#0891B2','#DB2777','#16A34A'];
                    $aColor  = $colors[$i % count($colors)];
                    $isKeduanya = count($log->kategori_risiko ?? []) >= 2;
                    $isNilai    = in_array('nilai', $log->kategori_risiko ?? []);
                    $isAbsensi  = in_array('absensi', $log->kategori_risiko ?? []);
                @endphp
                <tr>
                    <td style="font-size:12px;color:var(--text-3);">
                        {{ ($logs->currentPage() - 1) * $logs->perPage() + $i + 1 }}
                    </td>

                    <td>
                        <div style="display:flex;align-items:center;gap:9px;">
                            <div class="avatar" style="background:{{ $aColor }};">
                                {{ strtoupper(substr($log->nama_mahasiswa, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:13px;color:var(--text-1);">
                                    {{ $log->nama_mahasiswa }}
                                </div>
                                <div style="font-size:11px;color:var(--text-3);">
                                    {{ $log->email_tujuan }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <td class="hide-mobile" style="font-size:12.5px;color:var(--text-2);">
                        {{ $log->kelas ?? '—' }}
                    </td>

                    <td style="text-align:center;">
                        @if($isKeduanya)
                        <span class="badge-both">
                            <i class="bi bi-exclamation-triangle-fill" style="font-size:9px;"></i> Nilai + Alpha
                        </span>
                        @elseif($isNilai)
                        <span class="badge-nilai">
                            <i class="bi bi-x-circle-fill" style="font-size:9px;"></i> Nilai D/E
                        </span>
                        @elseif($isAbsensi)
                        <span class="badge-alpha">
                            <i class="bi bi-clock-history" style="font-size:9px;"></i> Alpha ≥18j
                        </span>
                        @else
                        <span style="color:var(--text-3);font-size:12px;">—</span>
                        @endif
                    </td>

                    <td class="hide-mobile" style="text-align:center;font-weight:700;color:{{ $log->jumlah_nilai_de > 0 ? '#F59E0B' : 'var(--text-3)' }};">
                        {{ $log->jumlah_nilai_de > 0 ? $log->jumlah_nilai_de : '—' }}
                    </td>

                    <td class="hide-mobile" style="text-align:center;font-weight:700;color:{{ $log->total_alpha >= 18 ? '#8B5CF6' : 'var(--text-3)' }};">
                        {{ $log->total_alpha > 0 ? $log->total_alpha . 'j' : '—' }}
                    </td>

                    <td style="text-align:center;">
                        @if($log->status === 'berhasil')
                        <span class="badge-berhasil">
                            <i class="bi bi-check-circle-fill" style="font-size:10px;"></i> Terkirim
                        </span>
                        @else
                        <span class="badge-gagal error-tooltip" data-error="{{ $log->pesan_error }}">
                            <i class="bi bi-x-circle-fill" style="font-size:10px;"></i> Gagal
                        </span>
                        @endif
                    </td>

                    <td class="hide-mobile" style="font-size:12px;color:var(--text-2);">
                        {{ $log->pengirim->name ?? '—' }}
                    </td>

                    <td style="font-size:12px;color:var(--text-2);white-space:nowrap;">
                        {{ $log->created_at->format('d M Y') }}<br>
                        <span style="font-size:11px;color:var(--text-3);">{{ $log->created_at->format('H:i') }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="bi bi-inbox-fill"></i>
                            <p style="font-size:15px;font-weight:700;color:var(--text-2);">Belum ada history pengiriman</p>
                            <p style="font-size:13px;">Email peringatan yang dikirim akan tercatat di sini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($logs->hasPages())
    <div style="margin-top:16px;padding-top:12px;border-top:1px solid var(--border);">
        {{ $logs->links() }}
    </div>
    @endif

    {{-- Footer info --}}
    <div style="display:flex;align-items:center;gap:8px;margin-top:10px;flex-wrap:wrap;">
        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:600;background:#EFF6FF;color:#1D4ED8;">
            <i class="bi bi-envelope-fill"></i> {{ $totalLogs }} total
        </span>
        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:600;background:#DCFCE7;color:#15803D;">
            <i class="bi bi-check-circle-fill"></i> {{ $totalBerhasil }} berhasil
        </span>
        @if($totalGagal > 0)
        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:600;background:#FEE2E2;color:#991B1B;">
            <i class="bi bi-x-circle-fill"></i> {{ $totalGagal }} gagal
        </span>
        @endif
    </div>
</div>

@endsection
