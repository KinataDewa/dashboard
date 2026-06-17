@extends('layouts.mahasiswa')

@section('title', 'Riwayat Absensi')
@section('page-title', 'Riwayat Absensi')
@section('page-sub', 'Data kehadiran Anda per semester · Semester ' . $semesterAktif)

@push('styles')
<style>
/* ===== Design tokens (scoped, tambahan — tidak menimpa var global) ===== */
.absen-page {
    --absen-ease: cubic-bezier(.16, 1, .3, 1);
    --absen-ease-soft: cubic-bezier(.4, 0, .2, 1);
    --absen-danger: #EF4444;
    --absen-danger-deep: #B91C1C;
    --absen-warning: #F59E0B;
    --absen-info: #3B82F6;
    --absen-success: #22C55E;
}

/* ===== Stat cards ===== */
.absen-mini {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 16px 20px;
    animation: absenFadeUp .45s var(--absen-ease-soft) both;
    transition: transform .22s var(--absen-ease-soft), box-shadow .22s var(--absen-ease-soft);
}
.absen-mini:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 24px -10px rgba(15, 23, 42, .16);
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
    position: relative;
    height: 6px; background: #F1F5F9;
    border-radius: 3px; margin-top: 10px;
}
.absen-bar-fill {
    position: absolute; top: 0; left: 0; height: 100%;
    border-radius: 3px;
    transition: width .6s var(--absen-ease);
}
.absen-bar-overflow {
    position: absolute; top: 0; height: 100%;
    background-color: var(--absen-danger-deep);
    background-image: repeating-linear-gradient(45deg, rgba(255,255,255,.35) 0 4px, transparent 4px 8px);
    border-radius: 0 3px 3px 0;
}
.absen-bar-threshold {
    position: absolute; top: -3px; bottom: -3px; width: 2px;
    background: #0F172A; opacity: .3;
}
.absen-warn {
    font-size: 11px; font-weight: 600;
    margin-top: 6px;
}

/* ===== Filter semester ===== */
.sem-filter-row {
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px; margin-bottom: 20px; flex-wrap: wrap;
}
.sem-filter-title { font-size: 15px; font-weight: 700; color: var(--text-1); }
.sem-filter-sub { font-size: 12px; color: var(--text-2); margin-top: 2px; }
.badge-aktif {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
    background: #DCFCE7; color: #15803D; margin-left: 6px;
}
.sem-pill-group { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
.sem-pill-label { font-size: 11.5px; color: var(--text-3); font-weight: 600; margin-right: 4px; }
.sem-pill {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 36px; height: 32px; padding: 0 12px;
    border-radius: 20px; font-size: 12.5px; font-weight: 700;
    text-decoration: none; background: #F1F5F9; color: var(--text-2);
    border: 1px solid var(--border);
    transition: background .2s var(--absen-ease-soft), transform .2s var(--absen-ease-soft), box-shadow .2s var(--absen-ease-soft);
}
.sem-pill:hover { background: #E2E8F0; transform: translateY(-1px); }
.sem-pill:focus-visible { outline: 2px solid var(--blue); outline-offset: 2px; }
.sem-pill--active {
    background: var(--blue); color: #fff; border-color: transparent;
    box-shadow: 0 2px 8px rgba(37, 99, 235, .3);
}
.sem-pill--active:hover { background: var(--blue); transform: none; }

/* ===== Breakdown panel (pengganti tabel 1-baris) ===== */
.absen-detail { padding: 20px 22px; }
.absen-detail-head {
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px; flex-wrap: wrap; margin-bottom: 18px;
}
.absen-detail-title { font-size: 15px; font-weight: 700; color: var(--text-1); }
.absen-detail-sub { font-size: 12px; color: var(--text-2); margin-top: 2px; }

.absen-breakdown {
    display: flex; flex-wrap: wrap; align-items: stretch;
    border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden;
}
.absen-breakdown-item {
    flex: 1; min-width: 140px; display: flex; align-items: center; gap: 12px;
    padding: 18px 20px; border-right: 1px solid var(--border);
    transition: background .2s var(--absen-ease-soft);
}
.absen-breakdown-item:last-of-type { border-right: none; }
.absen-breakdown-item:hover { background: #F8FAFC; }
.absen-breakdown-item i { font-size: 18px; }
.absen-breakdown-item--danger i { color: var(--absen-danger); }
.absen-breakdown-item--warning i { color: var(--absen-warning); }
.absen-breakdown-item--info i { color: var(--absen-info); }
.absen-breakdown-text { display: flex; flex-direction: column; }
.absen-breakdown-num { font-size: 22px; font-weight: 800; color: var(--text-1); line-height: 1; }
.absen-breakdown-num small { font-size: 12px; font-weight: 600; color: var(--text-3); margin-left: 2px; }
.absen-breakdown-label {
    font-size: 11px; font-weight: 600; color: var(--text-3);
    text-transform: uppercase; letter-spacing: .5px; margin-top: 3px;
}
.absen-breakdown-status { padding: 18px 20px; display: flex; align-items: center; }

.status-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 700;
}
.status-pill--danger { background: #FEE2E2; color: #991B1B; }
.status-pill--warning { background: #FEF3C7; color: #92400E; }
.status-pill--success { background: #DCFCE7; color: #166534; }

.absen-empty { text-align: center; padding: 40px; color: var(--text-3); }
.absen-empty i { font-size: 32px; display: block; margin-bottom: 8px; }

/* ===== Motion ===== */
@keyframes absenFadeUp {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}
@media (prefers-reduced-motion: reduce) {
    .absen-mini, .sem-pill, .absen-bar-fill, .absen-breakdown-item {
        animation: none !important;
        transition: none !important;
    }
}
</style>
@endpush

@section('content')

@php
    $sumHadir = $absensis->sum('jam_hadir');
    $sumIzin  = $absensis->sum('jam_izin');
    $sumSakit = $absensis->sum('jam_sakit');
    $sumAlpha = $absensis->sum('jam_alpha');
    $sumAll   = $sumHadir + $sumIzin + $sumSakit + $sumAlpha;
    $pctHadir = $sumAll > 0 ? round($sumHadir / $sumAll * 100) : 0;

    // Skala bar Alpha dengan threshold marker di 18 jam
    $alphaLimit     = 18;
    $alphaScaleMax  = $sumAlpha > $alphaLimit ? ceil($sumAlpha * 1.15) : $alphaLimit;
    $alphaBasePct   = round(min($sumAlpha, $alphaLimit) / $alphaScaleMax * 100);
    $alphaOverflow  = $sumAlpha > $alphaLimit ? round(($sumAlpha - $alphaLimit) / $alphaScaleMax * 100) : 0;
    $alphaThreshold = round($alphaLimit / $alphaScaleMax * 100);
    $alphaBarColor  = $sumAlpha >= 18 ? '#EF4444' : ($sumAlpha >= 14 ? '#F59E0B' : '#22C55E');
@endphp

<div class="absen-page">

{{-- ══ BANNER — chip dipangkas jadi 1 (status saja), sisanya sudah ada di card bawah ══ --}}
@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg, #1E3A5F 0%, #0891B2 55%, #22D3EE 100%)',
    'icon'         => 'bi-calendar2-check-fill',
    'title'        => 'Riwayat Absensi',
    'sub'          => 'Data kehadiran per semester · Semester ' . $semesterAktif,
    'chips'        => [
        ['icon' => $sumAlpha >= 18 ? 'bi-exclamation-triangle-fill' : 'bi-shield-check-fill',
         'label' => $sumAlpha >= 18 ? 'Status: Kritis!' : ($sumAlpha >= 14 ? 'Status: Waspada' : 'Status: Aman')],
    ],
    'badge_num'    => $sumAlpha . 'j',
    'badge_label'  => "Total\nAlpha",
])

{{-- ══ FILTER SEMESTER ══ --}}
<div class="sem-filter-row">
    <div>
        <div class="sem-filter-title">
            Absensi Semester {{ $semester }}
            @if($semester == $semesterAktif)
                <span class="badge-aktif"><i class="bi bi-circle-fill" style="font-size:7px;"></i> Aktif</span>
            @endif
        </div>
        <div class="sem-filter-sub">
            Semester {{ $semester }} ·
            Alpha: <strong style="color:{{ $sumAlpha >= 18 ? '#EF4444' : ($sumAlpha >= 14 ? '#F59E0B' : 'var(--text-1)') }};">{{ $sumAlpha }} jam</strong>
        </div>
    </div>

    <div class="sem-pill-group">
        <span class="sem-pill-label">Semester:</span>
        @foreach($semesterList as $sem)
        <a href="{{ route('mahasiswa.absensi', ['semester' => $sem]) }}"
           class="sem-pill {{ $sem == $semester ? 'sem-pill--active' : '' }}"
           @if($sem == $semester) aria-current="true" @endif>
            {{ $sem }}
        </a>
        @endforeach
    </div>
</div>

{{-- ══ STAT CARDS ══ --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="absen-mini" style="animation-delay:.02s;{{ $sumAlpha >= 14 ? 'border-left:3px solid #EF4444;' : '' }}">
            <div class="absen-mini-label" style="color:#EF4444;"><i class="bi bi-x-circle-fill"></i> Alpha</div>
            <div class="absen-mini-val" style="color:{{ $sumAlpha >= 14 ? '#EF4444' : 'var(--text-1)' }};">
                {{ $sumAlpha }}<span class="absen-mini-unit">/ {{ $alphaLimit }} jam</span>
            </div>
            <div class="absen-bar">
                <div class="absen-bar-fill" style="width:{{ $alphaBasePct }}%;background:{{ $alphaBarColor }};"></div>
                @if($alphaOverflow > 0)
                <div class="absen-bar-overflow" style="left:{{ $alphaBasePct }}%;width:{{ $alphaOverflow }}%;"></div>
                @endif
                @if($alphaThreshold < 100)
                <div class="absen-bar-threshold" style="left:{{ $alphaThreshold }}%;"></div>
                @endif
            </div>
            @if($sumAlpha >= 14)
            <div class="absen-warn" style="color:{{ $sumAlpha>=18 ? '#EF4444' : '#F59E0B' }};">
                @if($sumAlpha >= 18)
                    ⛔ {{ $sumAlpha - $alphaLimit }} jam melebihi batas — Mahasiswa Beresiko!
                @else
                    ⚠ {{ $alphaLimit - $sumAlpha }} jam lagi menuju batas
                @endif
            </div>
            @endif
        </div>
    </div>

    <div class="col-6 col-md-4">
        <div class="absen-mini" style="animation-delay:.08s;">
            <div class="absen-mini-label" style="color:#F59E0B;"><i class="bi bi-journal-x"></i> Izin</div>
            <div class="absen-mini-val">{{ $sumIzin }}<span class="absen-mini-unit">jam</span></div>
            <div class="absen-bar">
                <div class="absen-bar-fill" style="width:{{ ($sumIzin+$sumSakit+$sumAlpha)>0 ? round($sumIzin/($sumIzin+$sumSakit+$sumAlpha)*100) : 0 }}%;background:#F59E0B;"></div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4">
        <div class="absen-mini" style="animation-delay:.14s;">
            <div class="absen-mini-label" style="color:#3B82F6;"><i class="bi bi-thermometer"></i> Sakit</div>
            <div class="absen-mini-val">{{ $sumSakit }}<span class="absen-mini-unit">jam</span></div>
            <div class="absen-bar">
                <div class="absen-bar-fill" style="width:{{ ($sumIzin+$sumSakit+$sumAlpha)>0 ? round($sumSakit/($sumIzin+$sumSakit+$sumAlpha)*100) : 0 }}%;background:#3B82F6;"></div>
            </div>
        </div>
    </div>
</div>

{{-- ══ BREAKDOWN PANEL (pengganti tabel 1-baris) ══ --}}
<div class="card-white absen-detail">
    <div class="absen-detail-head">
        <div>
            <div class="absen-detail-title">Riwayat Absensi Semester {{ $semester }}</div>
            <div class="absen-detail-sub">
                Total kehadiran: <strong style="color:{{ $pctHadir>=75 ? '#22C55E' : '#EF4444' }};">{{ $pctHadir }}%</strong>
            </div>
        </div>
    </div>

    @php
        $absenSem  = $absensis->first();
        $alphaSem  = $absenSem?->jam_alpha ?? 0;
        $statusSem = $alphaSem >= 18 ? 'kritis' : ($alphaSem >= 14 ? 'waspada' : 'aman');
    @endphp

    @if($absenSem)
    <div class="absen-breakdown">
        <div class="absen-breakdown-item absen-breakdown-item--danger">
            <i class="bi bi-x-circle-fill"></i>
            <div class="absen-breakdown-text">
                <span class="absen-breakdown-num">{{ $alphaSem }}<small>j</small></span>
                <span class="absen-breakdown-label">Alpha</span>
            </div>
        </div>
        <div class="absen-breakdown-item absen-breakdown-item--warning">
            <i class="bi bi-journal-x"></i>
            <div class="absen-breakdown-text">
                <span class="absen-breakdown-num">{{ $absenSem->jam_izin }}<small>j</small></span>
                <span class="absen-breakdown-label">Izin</span>
            </div>
        </div>
        <div class="absen-breakdown-item absen-breakdown-item--info">
            <i class="bi bi-thermometer"></i>
            <div class="absen-breakdown-text">
                <span class="absen-breakdown-num">{{ $absenSem->jam_sakit }}<small>j</small></span>
                <span class="absen-breakdown-label">Sakit</span>
            </div>
        </div>
        <div class="absen-breakdown-status">
            @if($statusSem === 'kritis')
                <span class="status-pill status-pill--danger"><i class="bi bi-slash-circle-fill"></i> Kritis — SP I</span>
            @elseif($statusSem === 'waspada')
                <span class="status-pill status-pill--warning"><i class="bi bi-exclamation-triangle-fill"></i> Waspada</span>
            @else
                <span class="status-pill status-pill--success"><i class="bi bi-check-circle-fill"></i> Aman</span>
            @endif
        </div>
    </div>
    @else
    <div class="absen-empty">
        <i class="bi bi-calendar-x"></i>
        <p>Belum ada data absensi untuk semester ini.</p>
    </div>
    @endif
</div>

</div>{{-- /.absen-page --}}

@endsection

@push('scripts')
@endpush