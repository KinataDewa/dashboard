@extends('layouts.mahasiswa')

@section('title', 'Nilai Akademik')
@section('page-title', 'Nilai Akademik')
@section('page-sub', 'Riwayat nilai seluruh semester — ' . $mahasiswa->nim)

@section('content')

{{-- SEMESTER TABS --}}
<div class="d-flex gap-2 mb-4 flex-wrap">
    @foreach($semesterList as $sem)
    <a href="{{ route('mahasiswa.nilai.semester', $sem) }}"
       style="padding:6px 18px;border-radius:20px;border:1.5px solid {{ $sem == $semester ? 'var(--navy)' : '#e4eaf5' }};
              background:{{ $sem == $semester ? 'var(--navy)' : '#fff' }};
              color:{{ $sem == $semester ? 'var(--accent)' : '#8da3c0' }};
              font-size:12px;font-weight:600;text-decoration:none;transition:all .2s;">
        Semester {{ $sem }}
    </a>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="section-card">
            <div class="section-header">
                <div>
                    <div class="section-title">Nilai Semester {{ $semester }}</div>
                    <div class="section-subtitle">Bobot: Tugas 30% · UTS 30% · UAS 40%</div>
                </div>
                <span style="background:var(--navy);color:var(--accent);padding:5px 16px;border-radius:20px;font-size:12px;font-weight:700;font-family:'Space Mono',monospace;">
                    IP: {{ number_format($ip, 2) }}
                </span>
            </div>

            <table class="nilai-table">
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        <th class="text-center">SKS</th>
                        <th class="text-center">Tugas</th>
                        <th class="text-center">UTS</th>
                        <th class="text-center">UAS</th>
                        <th class="text-center">Nilai Akhir</th>
                        <th class="text-center">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilais as $nilai)
                    <tr class="{{ in_array($nilai->grade, ['D','E']) ? 'warn-row' : '' }}">
                        <td>
                            <div class="matkul-name">{{ $nilai->mataKuliah->nama }}</div>
                            <div class="matkul-sub">{{ $nilai->mataKuliah->kode }} · {{ $nilai->mataKuliah->dosen->nama ?? '-' }}</div>
                        </td>
                        <td class="text-center"><span class="score-pill">{{ $nilai->mataKuliah->sks }}</span></td>
                        <td class="text-center"><span class="score-pill">{{ $nilai->nilai_tugas }}</span></td>
                        <td class="text-center"><span class="score-pill">{{ $nilai->nilai_uts }}</span></td>
                        <td class="text-center"><span class="score-pill">{{ $nilai->nilai_uas }}</span></td>
                        <td class="text-center">
                            <span class="score-pill fw-bold" style="{{ in_array($nilai->grade,['D','E']) ? 'color:var(--danger-red)' : '' }}">
                                {{ number_format($nilai->nilai_akhir, 2) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="grade-badge grade-{{ $nilai->grade }}">{{ $nilai->grade }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4" style="color:#8da3c0;">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Belum ada data nilai untuk semester ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- RINGKASAN IP --}}
        <div class="section-card mb-4">
            <div class="section-title mb-3">Ringkasan Semester {{ $semester }}</div>
            <div class="text-center mb-3">
                <div style="font-size:48px;font-weight:800;color:var(--navy);font-family:'Space Mono',monospace;line-height:1;">
                    {{ number_format($ip, 2) }}
                </div>
                <div style="font-size:12px;color:#8da3c0;margin-top:4px;">Indeks Prestasi Semester</div>
            </div>
            <div style="border-top:1px dashed #e8eef8;padding-top:14px;display:flex;flex-direction:column;gap:8px;">
                <div class="d-flex justify-content-between">
                    <span style="font-size:13px;color:#5a6e8c;">Total SKS</span>
                    <strong style="font-family:'Space Mono';color:var(--navy);">{{ $nilais->sum('mataKuliah.sks') }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span style="font-size:13px;color:#5a6e8c;">Mata Kuliah</span>
                    <strong style="font-family:'Space Mono';color:var(--navy);">{{ $nilais->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span style="font-size:13px;color:#5a6e8c;">Nilai D/E</span>
                    <strong style="font-family:'Space Mono';color:{{ $nilais->whereIn('grade',['D','E'])->count() > 0 ? 'var(--danger-red)' : 'var(--success-green)' }};">
                        {{ $nilais->whereIn('grade',['D','E'])->count() }}
                    </strong>
                </div>
            </div>
        </div>

        {{-- DISTRIBUSI GRADE --}}
        <div class="section-card">
            <div class="section-title mb-3">Distribusi Grade</div>
            @foreach(['A'=>'success','B'=>'info','C'=>'warning','D'=>'danger','E'=>'danger'] as $grade => $color)
            @php $count = $nilais->where('grade', $grade)->count(); @endphp
            <div class="d-flex align-items-center gap-3 mb-2">
                <span style="font-family:'Space Mono';font-size:13px;font-weight:700;width:20px;color:var(--navy);">{{ $grade }}</span>
                <div class="progress flex-1 progress-thin">
                    <div class="progress-bar bg-{{ $color }}" style="width:{{ $nilais->count() > 0 ? ($count/$nilais->count()*100) : 0 }}%"></div>
                </div>
                <span style="font-size:12px;font-weight:700;font-family:'Space Mono';width:20px;color:var(--navy);">{{ $count }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection