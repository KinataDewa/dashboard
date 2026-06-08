@props(['kategori'])

@php
$map = [
    'ps'         => ['label'=>'Putus Studi',       'color'=>'#7F1D1D', 'bg'=>'#FEE2E2', 'icon'=>'bi-x-octagon-fill'],
    'sp3'        => ['label'=>'SP III (≥47j)',      'color'=>'#DC2626', 'bg'=>'#FEE2E2', 'icon'=>'bi-alarm-fill'],
    'sp2'        => ['label'=>'SP II (≥36j)',       'color'=>'#EA580C', 'bg'=>'#FEF3C7', 'icon'=>'bi-clock-fill'],
    'sp1'        => ['label'=>'SP I (≥18j)',        'color'=>'#D97706', 'bg'=>'#FEF9C3', 'icon'=>'bi-clock-history'],
    'nilai_e'    => ['label'=>'Nilai E',            'color'=>'#991B1B', 'bg'=>'#FEE2E2', 'icon'=>'bi-x-circle-fill'],
    'nilai_d'    => ['label'=>'D >3 Matkul',        'color'=>'#B45309', 'bg'=>'#FEF9C3', 'icon'=>'bi-exclamation-circle-fill'],
    'ips_rendah' => ['label'=>'IPS < 2.00',         'color'=>'#5B21B6', 'bg'=>'#EDE9FE', 'icon'=>'bi-graph-down-arrow'],
];
$info = $map[$kategori] ?? ['label'=>$kategori, 'color'=>'#64748B', 'bg'=>'#F1F5F9', 'icon'=>'bi-exclamation'];
@endphp

<span style="display:inline-flex;align-items:center;gap:3px;background:{{ $info['bg'] }};color:{{ $info['color'] }};border-radius:99px;padding:3px 9px;font-size:11px;font-weight:700;white-space:nowrap;margin:1px;">
    <i class="bi {{ $info['icon'] }}" style="font-size:10px;"></i>
    {{ $info['label'] }}
</span>
