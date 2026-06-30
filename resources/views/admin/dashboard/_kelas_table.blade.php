@if(empty($ringkasanKelas))
<div style="text-align:center;padding:48px;color:#94A3B8;">
    <i class="bi bi-grid-3x3-gap" style="font-size:32px;display:block;margin-bottom:10px;opacity:.3;"></i>
    <div style="font-size:13.5px;font-weight:600;">Belum ada data kelas untuk filter ini.</div>
</div>
@else
<div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
    <table class="db-tbl">
        <thead>
            <tr>
                <th>#</th>
                <th>Kelas</th>
                <th style="text-align:center;" class="hide-xs">Angkatan</th>
                <th style="text-align:center;">Total</th>
                <th style="text-align:center;">Berisiko</th>
                <th style="text-align:center;">% Risiko</th>
                <th style="text-align:center;" class="hide-xs">Rata-rata IPK</th>
                <th style="text-align:center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ringkasanKelas as $i => $kls)
            @php $perlu = $kls['pct_risiko'] > 30; @endphp
            <tr class="{{ $perlu ? 'risk-row' : '' }}">
                <td style="font-size:11.5px;color:#CBD5E1;font-weight:600;">{{ $i + 1 }}</td>
                <td style="font-weight:700;color:#0F172A;">{{ $kls['kelas'] }}</td>
                <td style="text-align:center;font-size:12px;color:#64748B;font-weight:600;" class="hide-xs">{{ $kls['angkatan'] }}</td>
                <td style="text-align:center;color:#64748B;font-weight:600;">{{ $kls['total'] }}</td>
                <td style="text-align:center;">
                    <span style="font-weight:800;font-size:14px;color:{{ $kls['berisiko'] > 0 ? '#EF4444' : '#22C55E' }};">
                        {{ $kls['berisiko'] }}
                    </span>
                </td>
                <td style="text-align:center;">
                    <div class="db-pct-wrap">
                        <span style="font-size:12px;font-weight:700;color:{{ $perlu ? '#EF4444' : '#64748B' }};min-width:32px;text-align:right;">
                            {{ $kls['pct_risiko'] }}%
                        </span>
                        <div class="db-pct-bar">
                            <div class="db-pct-fill" style="width:{{ min($kls['pct_risiko'],100) }}%;background:{{ $perlu ? '#EF4444' : '#22C55E' }};"></div>
                        </div>
                    </div>
                </td>
                <td style="text-align:center;" class="hide-xs">
                    <div style="display:flex;align-items:center;justify-content:center;gap:5px;">
                        <span style="font-weight:800;font-size:13px;color:{{ $kls['ipk'] < 2.5 ? '#EF4444' : ($kls['ipk'] >= 3.5 ? '#22C55E' : '#0F172A') }};">
                            {{ number_format($kls['ipk'], 2) }}
                        </span>
                        <div class="db-ipk-bar">
                            <div class="db-ipk-fill" style="width:{{ min(($kls['ipk']/4)*100,100) }}%;background:{{ $kls['ipk'] < 2.5 ? '#EF4444' : '#2563EB' }};"></div>
                        </div>
                    </div>
                </td>
                <td style="text-align:center;">
                    @if($perlu)
                    <span class="db-pill db-pill-red">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:9px;"></i> Perlu Perhatian
                    </span>
                    @else
                    <span class="db-pill db-pill-grn">
                        <i class="bi bi-check-circle-fill" style="font-size:9px;"></i> Baik
                    </span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@php
    $kelasMaxRisk = collect($ringkasanKelas)->sortByDesc('pct_risiko')->first();
    $kelasAman    = collect($ringkasanKelas)->where('pct_risiko', 0)->pluck('kelas');
@endphp
<div class="db-tbl-foot">
    @if($kelasMaxRisk && $kelasMaxRisk['pct_risiko'] > 0)
    <span class="db-chip" style="background:#FEE2E2;color:#991B1B;">
        <i class="bi bi-exclamation-triangle-fill" style="font-size:10px;"></i>
        Risiko tertinggi: {{ $kelasMaxRisk['kelas'] }} ({{ $kelasMaxRisk['pct_risiko'] }}%)
    </span>
    @endif
    @if($kelasAman->isNotEmpty())
    <span class="db-chip" style="background:#DCFCE7;color:#166534;">
        <i class="bi bi-check-circle-fill" style="font-size:10px;"></i>
        Paling stabil: {{ $kelasAman->implode(', ') }}
    </span>
    @endif
</div>
@endif
