@extends('layouts.mahasiswa')
 
@section('title', 'Nilai Akademik')
@section('page-title', 'Nilai Akademik')
@section('page-sub', 'Riwayat nilai mata kuliah Anda')
 
@section('content')
 
{{-- ══ BANNER ══ --}}
@php
    $ipSemVal = $nilais->isNotEmpty()
        ? collect($nilais)->reduce(function($carry, $n) {
            return $carry + ($n->nilai_akhir * $n->mataKuliah->sks);
        }, 0) / max($nilais->sum('mataKuliah.sks'), 1)
        : 0;
    $totalSks = $nilais->sum(fn($n) => $n->mataKuliah->sks);
    $nilaiDE  = $nilais->whereIn('grade', ['D','E'])->count();
@endphp
@include('components.page-banner', [
    'gradient'     => 'linear-gradient(135deg, #14532D 0%, #16A34A 55%, #22C55E 100%)',
    'icon'         => 'bi-journal-bookmark-fill',
    'title'        => 'Nilai Akademik',
    'sub'          => 'Riwayat nilai mata kuliah · Semester ' . $semester,
    'chips'        => [
        ['icon' => 'bi-collection-fill',      'label' => $nilais->count() . ' Mata Kuliah'],
        ['icon' => 'bi-layers-fill',          'label' => $totalSks . ' SKS Total'],
        ['icon' => 'bi-trophy-fill',          'label' => $nilais->where('grade','A')->count() . ' Nilai A'],
        ['icon' => 'bi-exclamation-triangle', 'label' => $nilaiDE . ' Nilai D/E'],
    ],
    'badge_num'    => $nilais->where('grade','A')->count() + $nilais->where('grade','B')->count(),
    'badge_label'  => "Nilai\nBaik",
    'badge2_num'   => $nilaiDE,
    'badge2_label' => "Perlu\nPerhatian",
])

{{-- Filter --}}
<form method="GET" action="{{ route('mahasiswa.nilai') }}" class="semester-bar">
    <select name="semester" class="select-semester">
        @foreach($semesterList as $sem)
        <option value="{{ $sem }}" {{ $sem == $semester ? 'selected' : '' }}>
            {{ $mahasiswa->kelas->tahun_akademik ?? '2024/2025' }}
            {{ $sem % 2 == 0 ? 'Genap' : 'Ganjil' }} — Semester {{ $sem }}
        </option>
        @endforeach
    </select>
    <button type="submit" class="btn-primary">Filter</button>
</form>
 
<div class="card-white tbl-card">
    <div class="tbl-head">
        <div class="tbl-title">Nilai Akademik</div>
        <div class="tbl-actions">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Search" id="searchNilai">
            </div>
            <div class="filter-wrap">
                <button class="btn-filter" id="filterNilaiBtn">
                    <i class="bi bi-sliders2" style="font-size:12px;"></i> Filter
                </button>
                <div class="filter-menu" id="filterNilaiMenu">
                    <div class="filter-menu-label">Select Filter</div>
                    <div class="filter-opt active" data-val="">Semua</div>
                    <div class="filter-opt" data-val="baik">Nilai Atas Rata-Rata</div>
                    <div class="filter-opt" data-val="perhatian">Nilai Bawah Rata-Rata</div>
                </div>
            </div>
        </div>
    </div>
 
    <div style="overflow-x:auto;">
        <table class="ac-table">
            <thead>
                <tr>
                    <th style="width:48px;">No</th>
                    <th>Kode Mata Kuliah</th>
                    <th>Mata Kuliah</th>
                    <th style="text-align:center;">SKS</th>
                    <th style="text-align:center;">Jam</th>
                    <th style="text-align:center;">Nilai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="nilaiBody">
                @forelse($nilais as $i => $nilai)
                @php
                    $rataKelas = $rataRataKelas[$nilai->mata_kuliah_id] ?? 0;
                    $isBawah   = $nilai->nilai_akhir < $rataKelas;
                    $isDE      = in_array($nilai->grade, ['D','E']);
                    $statusVal = ($isBawah || $isDE) ? 'perhatian' : 'baik';
                @endphp
                <tr data-matkul="{{ strtolower($nilai->mataKuliah->nama) }}"
                    data-status="{{ $statusVal }}">
                    <td class="muted">{{ $i + 1 }}</td>
                    <td class="muted" style="font-size:13px;font-family:monospace;">{{ $nilai->mataKuliah->kode }}</td>
                    <td style="font-weight:500;">{{ $nilai->mataKuliah->nama }}</td>
                    <td class="muted" style="text-align:center;">{{ $nilai->mataKuliah->sks }}</td>
                    <td class="muted" style="text-align:center;">{{ $nilai->mataKuliah->sks * 14 }}</td>
                    <td style="text-align:center;">
                        <span style="font-weight:800;font-size:15px;color:{{ $isDE ? '#EF4444' : ($nilai->grade==='A' ? '#22C55E' : ($nilai->grade==='B' ? '#2563EB' : 'var(--text-1)')) }};">
                            {{ $nilai->grade }}
                        </span>
                    </td>
                    <td>
                        @if($statusVal === 'baik')
                            <span class="badge badge-green">Nilai Baik</span>
                        @else
                            <span class="badge badge-red">Perlu Perhatian</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:36px;color:var(--text-3);">
                        <i class="bi bi-inbox" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                        Belum ada data nilai untuk semester ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
 
@endsection
 
@push('scripts')
<script>
document.getElementById('searchNilai').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#nilaiBody tr').forEach(function(row) {
        row.style.display = (row.dataset.matkul||'').includes(q) ? '' : 'none';
    });
});
 
document.getElementById('filterNilaiBtn').addEventListener('filterChange', function(e) {
    var val = e.detail.value;
    document.querySelectorAll('#nilaiBody tr').forEach(function(row) {
        if (!val) { row.style.display = ''; return; }
        row.style.display = row.dataset.status === val ? '' : 'none';
    });
});
</script>
@endpush