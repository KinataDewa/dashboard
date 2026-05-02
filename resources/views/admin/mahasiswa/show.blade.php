@extends('layouts.admin')
@section('title', 'Detail ' . $mahasiswa->nama)
@section('page-title', $mahasiswa->nama)
@section('page-sub', $mahasiswa->nim . ' · ' . ($mahasiswa->kelas->nama ?? '-') . ' · Semester ' . $semesterAktif)

@push('styles')
<style>
.grade-pill{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:50%;font-size:13px;font-weight:800;}
.grade-A{background:#DCFCE7;color:#15803D;}
.grade-B{background:#DBEAFE;color:#1D4ED8;}
.grade-C{background:#FEF9C3;color:#854D0E;}
.grade-D{background:#FEE2E2;color:#991B1B;}
.grade-E{background:#FEE2E2;color:#7F1D1D;}
.score-bar{width:60px;height:4px;background:#F1F5F9;border-radius:2px;overflow:hidden;display:inline-block;vertical-align:middle;margin-left:6px;}
.score-bar-fill{height:100%;border-radius:2px;}
.info-row{display:flex;justify-content:space-between;align-items:flex-start;padding:10px 0;border-bottom:1px solid #F1F5F9;gap:8px;}
.info-row:last-child{border-bottom:none;}
.info-label{font-size:12px;color:var(--text-2);font-weight:500;flex-shrink:0;min-width:72px;}
.info-val{font-size:13px;font-weight:600;color:var(--text-1);text-align:right;word-break:break-word;overflow-wrap:anywhere;}
.ipk-bar{width:100%;height:6px;background:#EFF6FF;border-radius:3px;margin-top:8px;overflow:hidden;}
.ipk-bar-fill{height:100%;border-radius:3px;background:linear-gradient(90deg,#2563EB,#60A5FA);}
.sem-pills{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:20px;}
.sem-pill{padding:5px 14px;border-radius:20px;font-size:12.5px;font-weight:600;cursor:pointer;border:1.5px solid var(--border);color:var(--text-2);background:var(--white);text-decoration:none;transition:all .15s;}
.sem-pill:hover{border-color:var(--blue);color:var(--blue);}
.sem-pill.active{background:var(--blue);color:#fff;border-color:var(--blue);}
.detail-action-bar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:20px;}
.detail-action-right{display:flex;gap:8px;flex-wrap:wrap;}
.btn-back{display:inline-flex;align-items:center;gap:5px;padding:7px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;font-weight:500;color:var(--text-2);background:var(--white);text-decoration:none;transition:all .15s;white-space:nowrap;}
.btn-back:hover{border-color:var(--blue);color:var(--blue);}
.chart-card-v2{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;}
.chart-head-v2{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;}
.chart-title-v2{font-size:14px;font-weight:700;color:var(--text-1);}
.chart-sub-v2{font-size:12px;color:var(--text-2);margin-top:2px;}
.hide-mobile{display:table-cell;}
.hide-sm{display:table-cell;}

@media(max-width:992px){
    .tbl-card-v2{padding:16px;}
    .chart-card-v2{padding:16px;}
}
@media(max-width:768px){
    .hide-mobile{display:none;}
    .detail-action-right{flex-direction:row;gap:6px;width:100%;}
    .detail-action-right .btn-back,
    .detail-action-right a.btn-edit,
    .detail-action-right form{flex:1;}
    .detail-action-right a.btn-edit{display:flex;justify-content:center;}
    .detail-action-right form button{width:100%;justify-content:center;}
    .btn-back{justify-content:center;font-size:12px;padding:8px 6px;}
    .detail-action-bar{flex-direction:column;align-items:stretch;}
    .info-label{min-width:64px;font-size:11.5px;}
    .info-val{font-size:12px;}
    .sem-pill{font-size:11.5px;padding:4px 11px;}
    .tbl-card-v2{padding:14px;}
    .chart-card-v2{padding:14px;}
}
@media(max-width:576px){
    .hide-sm{display:none;}
    .info-label{min-width:56px;font-size:11px;}
    .info-val{font-size:11.5px;}
    .sem-pill{font-size:11px;padding:3px 9px;}
    #chartIp,#chartAlpha{max-height:140px;}
}
</style>
@endpush

@section('content')

@php
    $isRisky      = $mahasiswa->isBerisiko();
    $nilaiDECount = $nilais->whereIn('grade', ['D', 'E'])->count();
    $totAlpha     = $absensis->sum('jam_alpha');
@endphp

{{-- BANNER --}}
@include('components.page-banner', [
    'gradient'     => $isRisky
        ? 'linear-gradient(135deg, #7F1D1D 0%, #DC2626 55%, #EF4444 100%)'
        : 'linear-gradient(135deg, #1E3A8A 0%, #2563EB 55%, #60A5FA 100%)',
    'icon'         => $isRisky ? 'bi-exclamation-triangle-fill' : 'bi-person-check-fill',
    'title'        => $mahasiswa->nama,
    'sub'          => $mahasiswa->nim . ' · ' . ($mahasiswa->kelas->nama ?? '') . ' · Semester ' . $semesterAktif,
    'chips'        => [
        ['icon' => 'bi-mortarboard-fill',    'label' => 'IPK ' . number_format($ipk, 2)],
        ['icon' => 'bi-calendar2-check',     'label' => 'IP Sem ' . number_format($ip, 2)],
        ['icon' => 'bi-x-circle-fill',       'label' => $totAlpha . ' Jam Alpha'],
        ['icon' => $isRisky ? 'bi-exclamation-circle-fill' : 'bi-shield-check-fill',
                                             'label' => $isRisky ? 'Status: Berisiko' : 'Status: Aman'],
    ],
    'badge_num'    => number_format($ipk, 2),
    'badge_label'  => "IPK\nKumulatif",
    'badge2_num'   => $nilaiDECount,
    'badge2_label' => "Nilai\nD/E",
])

{{-- ACTION BAR --}}
<div class="detail-action-bar">
    <div><span class="section-label" style="margin-bottom:0;">Rincian Akademik</span></div>
    <div class="detail-action-right">
        <a href="{{ route('admin.mahasiswa.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('admin.mahasiswa.edit', $mahasiswa->id) }}" class="btn-edit"
           style="padding:7px 14px;display:inline-flex;align-items:center;gap:5px;font-size:13px;">
            <i class="bi bi-pencil-fill"></i> Edit
        </a>
        <form action="{{ route('admin.mahasiswa.destroy', $mahasiswa->id) }}" method="POST"
              onsubmit="return confirm('Hapus {{ addslashes($mahasiswa->nama) }}?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-del"
                    style="padding:7px 14px;display:inline-flex;align-items:center;gap:5px;font-size:13px;">
                <i class="bi bi-trash-fill"></i> Hapus
            </button>
        </form>
    </div>
</div>

{{-- SEMESTER PILLS --}}
@if($semesterList->count() > 0)
<div class="sem-pills">
    @foreach($semesterList as $sem)
    <a href="{{ route('admin.mahasiswa.show', $mahasiswa->id) }}?semester={{ $sem }}"
       class="sem-pill {{ $semesterAktif == $sem ? 'active' : '' }}">
        Semester {{ $sem }}
    </a>
    @endforeach
</div>
@endif

{{-- MAIN LAYOUT — sama seperti dosen --}}
<div class="row g-4">

    {{-- KIRI: Nilai & Absensi + Chart --}}
    <div class="col-lg-8">

        <div class="section-label">Nilai Akademik</div>
        <div class="card-white tbl-card-v2 mb-4">
            <div class="tbl-head-v2">
                <div>
                    <div class="tbl-title-v2">Nilai Semester {{ $semesterAktif }}</div>
                    <div class="tbl-sub-v2">Bobot: Tugas 30% · UTS 30% · UAS 40%</div>
                </div>
                <span style="background:#EFF6FF;color:var(--blue);padding:5px 14px;border-radius:20px;font-size:12px;font-weight:700;font-family:monospace;white-space:nowrap;">
                    IP: {{ number_format($ip, 2) }}
                </span>
            </div>
            <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
                <table class="ac-table-v2" style="min-width:360px;">
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th class="hide-sm" style="text-align:center;">Tugas</th>
                            <th class="hide-sm" style="text-align:center;">UTS</th>
                            <th class="hide-sm" style="text-align:center;">UAS</th>
                            <th style="text-align:center;">Nilai</th>
                            <th style="text-align:center;">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nilais as $nilai)
                        @php $isDE = in_array($nilai->grade, ['D','E']); @endphp
                        <tr style="{{ $isDE ? 'background:rgba(239,68,68,.03);' : '' }}">
                            <td style="{{ $isDE ? 'border-left:3px solid #EF4444;' : '' }}">
                                <div style="font-weight:500;font-size:13px;">{{ $nilai->mataKuliah->nama }}</div>
                                <div style="font-size:11px;color:var(--text-3);font-family:monospace;">{{ $nilai->mataKuliah->kode }}</div>
                            </td>
                            <td class="hide-sm" style="text-align:center;color:var(--text-2);">{{ $nilai->nilai_tugas }}</td>
                            <td class="hide-sm" style="text-align:center;color:var(--text-2);">{{ $nilai->nilai_uts }}</td>
                            <td class="hide-sm" style="text-align:center;color:var(--text-2);">{{ $nilai->nilai_uas }}</td>
                            <td style="text-align:center;">
                                <span style="font-weight:700;color:{{ $isDE ? '#EF4444' : 'var(--text-1)' }};">
                                    {{ number_format($nilai->nilai_akhir, 1) }}
                                </span>
                                <div class="score-bar">
                                    <div class="score-bar-fill" style="width:{{ $nilai->nilai_akhir }}%;background:{{ $isDE ? '#EF4444' : ($nilai->grade==='A' ? '#22C55E' : '#3B82F6') }};"></div>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <span class="grade-pill grade-{{ $nilai->grade }}">{{ $nilai->grade }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" style="text-align:center;padding:28px;color:var(--text-3);">
                            <i class="bi bi-journal-x" style="font-size:28px;display:block;margin-bottom:8px;opacity:.4;"></i>
                            Belum ada data nilai untuk semester {{ $semesterAktif }}.
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="section-label">Riwayat Absensi</div>
        <div class="card-white tbl-card-v2 mb-4">
            <div class="tbl-head-v2">
                <div>
                    <div class="tbl-title-v2">Absensi Semester {{ $semesterAktif }}</div>
                    <div class="tbl-sub-v2">Batas alpha: 18 jam per mata kuliah</div>
                </div>
            </div>
            <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
                <table class="ac-table-v2" style="min-width:360px;">
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th style="text-align:center;">Hadir</th>
                            <th class="hide-sm" style="text-align:center;">Izin</th>
                            <th class="hide-sm" style="text-align:center;">Sakit</th>
                            <th style="text-align:center;">Alpha</th>
                            <th class="hide-mobile" style="text-align:center;">% Hadir</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensis as $absen)
                        @php
                            $total   = $absen->jam_hadir + $absen->jam_izin + $absen->jam_sakit + $absen->jam_alpha;
                            $pct     = $total > 0 ? round($absen->jam_hadir / $total * 100) : 0;
                            $kritis  = $absen->jam_alpha >= 18;
                            $waspada = $absen->jam_alpha >= 14 && !$kritis;
                        @endphp
                        <tr style="{{ $kritis ? 'background:rgba(239,68,68,.03);' : '' }}">
                            <td style="{{ $kritis ? 'border-left:3px solid #EF4444;' : '' }}">
                                <div style="font-weight:500;font-size:13px;">{{ $absen->mataKuliah->nama }}</div>
                                <div style="font-size:11px;color:var(--text-3);font-family:monospace;">{{ $absen->mataKuliah->kode }}</div>
                            </td>
                            <td style="text-align:center;font-weight:600;color:#22C55E;">{{ $absen->jam_hadir }}</td>
                            <td class="hide-sm" style="text-align:center;font-weight:600;color:#FBBF24;">{{ $absen->jam_izin }}</td>
                            <td class="hide-sm" style="text-align:center;font-weight:600;color:#3B82F6;">{{ $absen->jam_sakit }}</td>
                            <td style="text-align:center;font-weight:700;color:{{ $kritis ? '#EF4444' : ($waspada ? '#F59E0B' : 'var(--text-2)') }};">
                                {{ $absen->jam_alpha }}j
                                @if($kritis) ⛔ @elseif($waspada) ⚠️ @endif
                            </td>
                            <td class="hide-mobile" style="text-align:center;">
                                <div style="display:flex;align-items:center;gap:6px;justify-content:center;">
                                    <div style="width:44px;height:4px;background:#F1F5F9;border-radius:2px;overflow:hidden;">
                                        <div style="height:100%;width:{{ $pct }}%;background:{{ $pct>=75 ? '#22C55E' : '#EF4444' }};border-radius:2px;"></div>
                                    </div>
                                    <span style="font-size:12px;font-weight:700;color:{{ $pct>=75 ? '#22C55E' : '#EF4444' }};">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td>
                                @if($kritis)
                                    <span class="badge badge-red" style="white-space:nowrap;font-size:11px;">Kritis</span>
                                @elseif($waspada)
                                    <span class="badge" style="background:#FEF3C7;color:#92400E;white-space:nowrap;font-size:11px;">Waspada</span>
                                @else
                                    <span class="badge badge-green" style="white-space:nowrap;font-size:11px;">Aman</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" style="text-align:center;padding:28px;color:var(--text-3);">
                            <i class="bi bi-calendar-x" style="font-size:28px;display:block;margin-bottom:8px;opacity:.4;"></i>
                            Belum ada data absensi untuk semester {{ $semesterAktif }}.
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Chart Trend (khusus admin) --}}
        @if($allSemesters->count() > 1)
        <div class="section-label">Trend per Semester</div>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="chart-card-v2">
                    <div class="chart-head-v2">
                        <div>
                            <div class="chart-title-v2">Trend IP</div>
                            <div class="chart-sub-v2">Perkembangan per semester</div>
                        </div>
                    </div>
                    <canvas id="chartIp" height="160"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-card-v2">
                    <div class="chart-head-v2">
                        <div>
                            <div class="chart-title-v2">Alpha per Semester</div>
                            <div class="chart-sub-v2">Total jam ketidakhadiran</div>
                        </div>
                    </div>
                    <canvas id="chartAlpha" height="160"></canvas>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- KANAN: Profil & IPK --}}
    <div class="col-lg-4">

        <div class="section-label">Profil Mahasiswa</div>
        <div class="card-white tbl-card-v2 mb-4">
            <div style="text-align:center;padding:20px 0 16px;">
                <div style="width:64px;height:64px;border-radius:50%;background:var(--blue);color:#fff;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:800;margin:0 auto 12px;">
                    {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
                </div>
                <div style="font-size:16px;font-weight:700;color:var(--text-1);word-break:break-word;">{{ $mahasiswa->nama }}</div>
                <div style="font-size:12px;color:var(--text-2);margin-top:2px;font-family:monospace;">{{ $mahasiswa->nim }}</div>
                <div style="margin-top:8px;">
                    @if($isRisky)
                    <span style="display:inline-flex;align-items:center;gap:4px;background:#FEE2E2;color:#991B1B;border-radius:20px;padding:3px 10px;font-size:11px;font-weight:700;">
                        <i class="bi bi-exclamation-triangle-fill"></i> Berisiko
                    </span>
                    @else
                    <span style="display:inline-flex;align-items:center;gap:4px;background:#DCFCE7;color:#166534;border-radius:20px;padding:3px 10px;font-size:11px;font-weight:700;">
                        <i class="bi bi-check-circle-fill"></i> Aman
                    </span>
                    @endif
                </div>
            </div>
            <div style="border-top:1px solid var(--border);padding-top:14px;">
                <div class="info-row">
                    <span class="info-label">NIM</span>
                    <span class="info-val" style="font-family:monospace;">{{ $mahasiswa->nim }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kelas</span>
                    <span class="info-val">{{ $mahasiswa->kelas->nama ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Angkatan</span>
                    <span class="info-val">{{ $mahasiswa->angkatan }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-val">
                        <span class="badge {{ $mahasiswa->status==='aktif' ? 'badge-green' : 'badge-red' }}">
                            {{ ucfirst($mahasiswa->status) }}
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Dosen PA</span>
                    <span class="info-val" style="font-size:12px;">{{ $mahasiswa->dosenPa->nama ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-val" style="font-size:11px;word-break:break-all;">{{ $mahasiswa->user->email ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="section-label">Indeks Prestasi</div>
        <div class="card-white tbl-card-v2">
            <div style="text-align:center;padding:8px 0 20px;">
                <div style="font-size:48px;font-weight:800;color:{{ $ipk >= 3.0 ? 'var(--blue)' : ($ipk >= 2.0 ? '#F59E0B' : '#DC2626') }};letter-spacing:-2px;line-height:1;">
                    {{ number_format($ipk, 2) }}
                </div>
                <div style="font-size:12px;color:var(--text-2);margin-top:4px;">IPK Kumulatif</div>
                <div class="ipk-bar" style="max-width:160px;margin:12px auto 8px;">
                    <div class="ipk-bar-fill" style="width:{{ min(($ipk/4)*100,100) }}%;"></div>
                </div>
                @if($ipk >= 3.5)
                    <span class="badge badge-green">Dengan Pujian</span>
                @elseif($ipk >= 3.0)
                    <span class="badge" style="background:#DBEAFE;color:#1D4ED8;">Sangat Memuaskan</span>
                @elseif($ipk >= 2.5)
                    <span class="badge badge-yellow">Memuaskan</span>
                @else
                    <span class="badge badge-red">Perlu Ditingkatkan</span>
                @endif
            </div>
            <div style="border-top:1px solid var(--border);padding-top:14px;">
                <div class="info-row">
                    <span class="info-label">IP Sem {{ $semesterAktif }}</span>
                    <span class="info-val" style="font-family:monospace;color:var(--blue);">{{ number_format($ip, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total MK</span>
                    <span class="info-val">{{ $nilais->count() }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nilai D/E</span>
                    <span class="info-val" style="color:{{ $nilaiDECount > 0 ? '#EF4444' : '#22C55E' }};">{{ $nilaiDECount }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Alpha</span>
                    <span class="info-val" style="color:{{ $totAlpha >= 18 ? '#EF4444' : 'var(--text-1)' }};">{{ $totAlpha }} jam</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kehadiran</span>
                    <span class="info-val" style="color:{{ $pctHadir >= 75 ? '#22C55E' : '#EF4444' }};">{{ $pctHadir }}%</span>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
@if($allSemesters->count() > 1)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const labels    = {!! json_encode($allSemesters->map(fn($s) => 'Sem '.$s)->values()) !!};
    const ipData    = {!! json_encode($trendIp->map(fn($v) => round($v,2))->values()) !!};
    const alphaData = {!! json_encode($trendAlpha->values()) !!};
    const baseOpts  = {
        responsive: true, maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid:{display:false}, ticks:{font:{size:11},color:'#64748B'} },
            y: { grid:{color:'#F8FAFC'}, ticks:{font:{size:11},color:'#64748B'}, border:{display:false} },
        },
    };
    new Chart(document.getElementById('chartIp'), {
        type: 'line',
        data: { labels, datasets: [{ data: ipData, borderColor:'#2563EB', backgroundColor:'rgba(37,99,235,.08)', fill:true, tension:.35, pointRadius:5, pointBackgroundColor:'#2563EB', borderWidth:2 }] },
        options: { ...baseOpts, scales: { ...baseOpts.scales, y: { ...baseOpts.scales.y, min:0, max:4 } } },
    });
    new Chart(document.getElementById('chartAlpha'), {
        type: 'bar',
        data: { labels, datasets: [{ data: alphaData, backgroundColor: alphaData.map(v => v>=18 ? 'rgba(239,68,68,.7)' : v>=14 ? 'rgba(245,158,11,.6)' : 'rgba(59,130,246,.6)'), borderRadius:5, borderSkipped:false }] },
        options: { ...baseOpts, scales: { ...baseOpts.scales, y: { ...baseOpts.scales.y, min:0 } } },
    });
})();
</script>
@endif
@endpush