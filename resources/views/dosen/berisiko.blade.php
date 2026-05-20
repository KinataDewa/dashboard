@extends('layouts.dosen')
@section('title', 'Mahasiswa Berisiko')
@section('page-title', 'Mahasiswa Berisiko')
@section('page-sub', 'Rekap mahasiswa berisiko pada kelas bimbingan Anda')

@push('styles')
<style>
/* ── Summary Cards ───────────────────────────── */
.sum-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 24px; }
.sum-card  { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
.sum-card-bar { height: 3px; }
.sum-card-body { padding: 14px 16px; }
.sum-card-val  { font-size: 30px; font-weight: 800; line-height: 1; letter-spacing: -1px; }
.sum-card-lbl  { font-size: 12px; color: var(--text-2); margin-top: 4px; font-weight: 500; }
.sum-card-pct  { font-size: 11px; font-weight: 700; margin-top: 6px; display: inline-flex; align-items: center; gap: 3px; padding: 2px 8px; border-radius: 99px; }

/* ── Filter Bar ──────────────────────────────── */
.filter-bar {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.filter-label { font-size: 12px; font-weight: 700; color: var(--text-2); white-space: nowrap; }

.jenis-pills { display: flex; gap: 6px; flex-wrap: wrap; }
.jenis-pill {
    padding: 7px 14px;
    border-radius: 99px;
    font-size: 12px;
    font-weight: 600;
    border: 1.5px solid var(--border);
    color: var(--text-2);
    background: var(--white);
    cursor: pointer;
    text-decoration: none;
    transition: all .15s;
    white-space: nowrap;
}
.jenis-pill:hover { border-color: var(--blue); color: var(--blue); }
.jenis-pill.active-semua   { background: #EF4444; color: #fff; border-color: #EF4444; }
.jenis-pill.active-nilai   { background: #F59E0B; color: #fff; border-color: #F59E0B; }
.jenis-pill.active-absensi { background: #8B5CF6; color: #fff; border-color: #8B5CF6; }

.btn-print {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--blue);
    color: #fff;
    border: none;
    cursor: pointer;
    transition: all .15s;
    text-decoration: none;
}
.btn-print:hover { background: #1D4ED8; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,.25); }

/* ── Tabel ───────────────────────────────────── */
.risk-table { width: 100%; border-collapse: collapse; }
.risk-table thead th {
    font-size: 11px; font-weight: 700; color: var(--text-3);
    text-transform: uppercase; letter-spacing: .6px;
    padding: 10px 14px; border-bottom: 1.5px solid var(--border);
    background: #FAFBFF; white-space: nowrap;
}
.risk-table tbody tr { border-bottom: 1px solid #F8FAFC; transition: background .1s; }
.risk-table tbody tr:last-child { border-bottom: none; }
.risk-table tbody tr:hover { background: #FAFBFF; }
.risk-table tbody td { padding: 12px 14px; vertical-align: middle; font-size: 13px; }

/* ── Badge kategori ──────────────────────────── */
.badge-de    { display: inline-flex; align-items: center; gap: 3px; background: #FEF9C3; color: #854D0E; border-radius: 99px; padding: 3px 9px; font-size: 11px; font-weight: 700; white-space: nowrap; }
.badge-alpha { display: inline-flex; align-items: center; gap: 3px; background: #EDE9FE; color: #5B21B6; border-radius: 99px; padding: 3px 9px; font-size: 11px; font-weight: 700; white-space: nowrap; }
.badge-both  { display: inline-flex; align-items: center; gap: 3px; background: #FEE2E2; color: #991B1B; border-radius: 99px; padding: 3px 9px; font-size: 11px; font-weight: 700; white-space: nowrap; }

/* ── Avatar ──────────────────────────────────── */
.avatar { width: 34px; height: 34px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #fff; flex-shrink: 0; }

/* ── Alpha bar ───────────────────────────────── */
.alpha-bar-wrap { width: 60px; height: 4px; background: #F1F5F9; border-radius: 2px; overflow: hidden; margin-top: 4px; }
.alpha-bar-fill { height: 100%; border-radius: 2px; }

/* ── No circle ───────────────────────────────── */
.no-circle { width: 28px; height: 28px; border-radius: 50%; background: #F1F5F9; color: var(--text-2); display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; }

/* ── Empty state ─────────────────────────────── */
.empty-state { text-align: center; padding: 60px 20px; color: var(--text-3); }
.empty-state i { font-size: 48px; display: block; margin-bottom: 12px; opacity: .25; }

/* ── PRINT ───────────────────────────────────── */
@media print {
    body * { visibility: hidden; }
    #printArea, #printArea * { visibility: visible; }
    #printArea { position: absolute; top: 0; left: 0; width: 100%; padding: 20px; }
    .no-print { display: none !important; }
    .print-header { display: block !important; }
    .risk-table tbody tr { break-inside: avoid; }
}
.print-header { display: none; margin-bottom: 20px; }
.print-header h2 { font-size: 18px; font-weight: 800; color: #0F172A; }
.print-header p  { font-size: 12px; color: #64748B; margin-top: 4px; }

@media (max-width: 768px) {
    .sum-cards { grid-template-columns: repeat(2, 1fr); }
    .hide-mobile { display: none !important; }
}
</style>
@endpush

@section('content')

{{-- Banner --}}
@include('components.page-banner', [
    'gradient'    => 'linear-gradient(135deg, #7F1D1D 0%, #991B1B 45%, #EF4444 100%)',
    'icon'        => 'bi-exclamation-triangle-fill',
    'title'       => 'Mahasiswa Berisiko — ' . ($dosen->nama ?? auth()->user()->name),
    'sub'         => 'Rekap mahasiswa berisiko pada kelas yang Anda ampu · ' . now()->format('d F Y'),
    'chips'       => [
        ['icon' => 'bi-people-fill',               'label' => $summary['total_mahasiswa'] . ' Total Mahasiswa'],
        ['icon' => 'bi-exclamation-triangle-fill', 'label' => $summary['total_berisiko'] . ' Berisiko'],
        ['icon' => 'bi-x-circle-fill',             'label' => $summary['berisiko_nilai'] . ' Nilai D/E'],
        ['icon' => 'bi-clock-history',             'label' => $summary['berisiko_absensi'] . ' Alpha ≥18 Jam'],
    ],
    'badge_num'   => $summary['total_berisiko'],
    'badge_label' => "Total\nBerisiko",
])

{{-- ══ SUMMARY CARDS ══ --}}
<div class="sum-cards">
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#EF4444,#FCA5A5);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#EF4444;">{{ $summary['total_berisiko'] }}</div>
            <div class="sum-card-lbl">Total Berisiko</div>
            <div class="sum-card-pct" style="background:#FEE2E2;color:#991B1B;">
                dari {{ $summary['total_mahasiswa'] }} mahasiswa
            </div>
        </div>
    </div>
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#F59E0B,#FCD34D);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#F59E0B;">{{ $summary['berisiko_nilai'] }}</div>
            <div class="sum-card-lbl">Berisiko Nilai D/E</div>
            <div class="sum-card-pct" style="background:#FEF9C3;color:#854D0E;">
                <i class="bi bi-x-circle-fill" style="font-size:10px;"></i> Nilai buruk
            </div>
        </div>
    </div>
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#8B5CF6,#A78BFA);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#8B5CF6;">{{ $summary['berisiko_absensi'] }}</div>
            <div class="sum-card-lbl">Alpha ≥18 Jam</div>
            <div class="sum-card-pct" style="background:#EDE9FE;color:#5B21B6;">
                <i class="bi bi-clock-history" style="font-size:10px;"></i> Absensi tinggi
            </div>
        </div>
    </div>
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#DC2626,#991B1B);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#DC2626;">{{ $summary['berisiko_keduanya'] }}</div>
            <div class="sum-card-lbl">Berisiko Keduanya</div>
            <div class="sum-card-pct" style="background:#FEE2E2;color:#991B1B;">
                <i class="bi bi-exclamation-triangle-fill" style="font-size:10px;"></i> Prioritas utama
            </div>
        </div>
    </div>
</div>

{{-- ══ FILTER BAR ══ --}}
<div class="filter-bar no-print">
    <span class="filter-label">Kategori:</span>
    <div class="jenis-pills">
        <a href="{{ request()->fullUrlWithQuery(['jenis' => 'semua']) }}"
           class="jenis-pill {{ $filterJenis === 'semua' ? 'active-semua' : '' }}">
            Semua ({{ $summary['total_berisiko'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['jenis' => 'nilai']) }}"
           class="jenis-pill {{ $filterJenis === 'nilai' ? 'active-nilai' : '' }}">
            Nilai D/E ({{ $summary['berisiko_nilai'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['jenis' => 'absensi']) }}"
           class="jenis-pill {{ $filterJenis === 'absensi' ? 'active-absensi' : '' }}">
            Alpha ≥18 Jam ({{ $summary['berisiko_absensi'] }})
        </a>
    </div>
    <button type="button" class="btn-print" onclick="window.print()">
        <i class="bi bi-printer-fill"></i>
        Cetak / Export
    </button>
</div>

{{-- ══ TABEL ══ --}}
<div id="printArea">

    <div class="print-header">
        <h2>Rekap Mahasiswa Berisiko — Kelas Bimbingan</h2>
        <p>{{ $dosen->nama ?? '' }} · Jurusan Teknologi Informasi · Politeknik Negeri Malang</p>
        <p>Dicetak: {{ now()->format('d F Y, H:i') }} · Total berisiko: {{ $summary['total_berisiko'] }} dari {{ $summary['total_mahasiswa'] }} mahasiswa</p>
    </div>

    <div class="card-white tbl-card-v2">
        <div class="tbl-head-v2 no-print">
            <div>
                <div class="tbl-title-v2">Daftar Mahasiswa Berisiko</div>
                <div class="tbl-sub-v2">
                    {{ $mahasiswaBerisiko->count() }} mahasiswa berisiko pada kelas yang Anda ampu
                </div>
            </div>
            <div class="search-wrap no-print">
                <i class="bi bi-search"></i>
                <input type="text" id="cariMhs" placeholder="Cari nama / NIM...">
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table class="risk-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Mahasiswa</th>
                        <th class="hide-mobile">Kelas</th>
                        <th style="text-align:center;">IPK</th>
                        <th style="text-align:center;">Nilai D/E</th>
                        <th style="text-align:center;">Alpha</th>
                        <th style="text-align:center;">Kategori Risiko</th>
                        <th class="no-print"></th>
                    </tr>
                </thead>
                <tbody id="riskBody">
                    @forelse($mahasiswaBerisiko as $i => $mhs)
                    @php
                        $colors     = ['#2563EB','#EF4444','#8B5CF6','#F59E0B','#0891B2','#DB2777','#16A34A'];
                        $aColor     = $colors[$i % count($colors)];
                        $isKeduanya = count($mhs['kategori']) >= 2;
                        $isNilai    = in_array('nilai', $mhs['kategori']);
                        $isAbsensi  = in_array('absensi', $mhs['kategori']);
                        $alphaWidth = min(($mhs['total_alpha'] / 36) * 100, 100);
                    @endphp
                    <tr data-nama="{{ strtolower($mhs['nama']) }}" data-nim="{{ $mhs['nim'] }}"
                        style="{{ $isKeduanya ? 'background:rgba(239,68,68,.025);' : '' }}">

                        <td>
                            <div class="no-circle" style="{{ $isKeduanya ? 'background:#FEE2E2;color:#991B1B;' : '' }}">
                                {{ $i + 1 }}
                            </div>
                        </td>

                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="avatar" style="background:{{ $aColor }};">
                                    {{ strtoupper(substr($mhs['nama'], 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:700;font-size:13.5px;color:var(--text-1);">
                                        {{ $mhs['nama'] }}
                                    </div>
                                    <div style="font-size:11px;color:var(--text-3);font-family:monospace;">
                                        {{ $mhs['nim'] }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="hide-mobile" style="font-size:12.5px;color:var(--text-2);">
                            {{ $mhs['kelas'] }}
                        </td>

                        <td style="text-align:center;">
                            <div style="font-weight:700;font-size:14px;color:{{ $mhs['ipk'] < 2.5 ? '#EF4444' : 'var(--text-1)' }};">
                                {{ $mhs['ipk'] }}
                            </div>
                            <div style="width:44px;height:4px;background:#F1F5F9;border-radius:2px;overflow:hidden;margin:4px auto 0;">
                                <div style="height:100%;width:{{ min(($mhs['ipk']/4)*100,100) }}%;background:{{ $mhs['ipk'] < 2.5 ? '#EF4444' : '#2563EB' }};border-radius:2px;"></div>
                            </div>
                        </td>

                        <td style="text-align:center;">
                            @if($mhs['jumlah_de'] > 0)
                            <span style="font-weight:800;font-size:16px;color:#F59E0B;">
                                {{ $mhs['jumlah_de'] }}
                            </span>
                            <div style="font-size:10.5px;color:var(--text-3);">mata kuliah</div>
                            @else
                            <span style="color:var(--text-3);font-size:12px;">—</span>
                            @endif
                        </td>

                        <td style="text-align:center;">
                            <span style="font-weight:800;font-size:16px;color:{{ $mhs['total_alpha'] >= 18 ? '#8B5CF6' : 'var(--text-2)' }};">
                                {{ $mhs['total_alpha'] }}j
                            </span>
                            <div class="alpha-bar-wrap" style="margin:4px auto 0;">
                                <div class="alpha-bar-fill"
                                     style="width:{{ $alphaWidth }}%;background:{{ $mhs['total_alpha'] >= 18 ? '#8B5CF6' : '#94A3B8' }};"></div>
                            </div>
                        </td>

                        <td style="text-align:center;">
                            @if($isKeduanya)
                            <span class="badge-both">
                                <i class="bi bi-exclamation-triangle-fill" style="font-size:10px;"></i>
                                Nilai + Alpha
                            </span>
                            @elseif($isNilai)
                            <span class="badge-de">
                                <i class="bi bi-x-circle-fill" style="font-size:10px;"></i>
                                Nilai D/E
                            </span>
                            @elseif($isAbsensi)
                            <span class="badge-alpha">
                                <i class="bi bi-clock-history" style="font-size:10px;"></i>
                                Alpha ≥18j
                            </span>
                            @endif
                        </td>

                        <td class="no-print">
                            <a href="{{ route('dosen.mahasiswa.detail', $mhs['id']) }}"
                               style="display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:7px;font-size:12px;font-weight:600;background:var(--blue);color:#fff;text-decoration:none;">
                                <i class="bi bi-eye-fill" style="font-size:11px;"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="bi bi-shield-check-fill" style="color:#22C55E;"></i>
                                <p style="font-size:16px;font-weight:700;color:#166534;margin-bottom:4px;">
                                    Tidak ada mahasiswa berisiko!
                                </p>
                                <p>Semua mahasiswa pada kelas Anda memiliki performa akademik yang baik.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div style="display:flex;align-items:center;gap:8px;margin-top:12px;padding-top:10px;border-top:1px solid var(--border);flex-wrap:wrap;" class="no-print">
            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:600;background:#FEE2E2;color:#991B1B;">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ $mahasiswaBerisiko->count() }} berisiko
            </span>
            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:600;background:#FEF9C3;color:#854D0E;">
                <i class="bi bi-x-circle-fill"></i> {{ $summary['berisiko_nilai'] }} nilai D/E
            </span>
            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:600;background:#EDE9FE;color:#5B21B6;">
                <i class="bi bi-clock-history"></i> {{ $summary['berisiko_absensi'] }} alpha ≥18j
            </span>
            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:600;background:#FEE2E2;color:#991B1B;">
                <i class="bi bi-exclamation-circle-fill"></i> {{ $summary['berisiko_keduanya'] }} keduanya
            </span>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('cariMhs').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#riskBody tr[data-nama]').forEach(function(r) {
        var nama = (r.dataset.nama || '').toLowerCase();
        var nim  = (r.dataset.nim  || '').toLowerCase();
        r.style.display = (nama.includes(q) || nim.includes(q)) ? '' : 'none';
    });
});
</script>
@endpush