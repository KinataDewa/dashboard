@extends('layouts.admin')
 
@section('title', 'Admin Dashboard')
@section('page-title', 'Panel Admin — Jurusan Teknologi Informasi')
@section('page-sub', 'Kelola seluruh data akademik Jurusan TI Polinema')
 
@section('topbar-actions')
<a href="{{ route('admin.import.index') }}" class="primary-btn">
    <i class="bi bi-file-earmark-arrow-up"></i> Import Data
</a>
@endsection
 
@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto mb-2" style="background:rgba(0,180,200,0.12);color:var(--teal);">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div class="stat-value">{{ $totalMahasiswa }}</div>
            <div class="stat-label">Mahasiswa Aktif</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto mb-2" style="background:rgba(232,160,32,0.12);color:var(--gold);">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div class="stat-value">{{ $totalDosen }}</div>
            <div class="stat-label">Total Dosen</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto mb-2" style="background:rgba(124,77,255,0.12);color:var(--accent);">
                <i class="bi bi-book-fill"></i>
            </div>
            <div class="stat-value">{{ $totalMatkul }}</div>
            <div class="stat-label">Mata Kuliah</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto mb-2" style="background:rgba(40,199,111,0.12);color:var(--success-green);">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </div>
            <div class="stat-value">{{ $totalKelas }}</div>
            <div class="stat-label">Kelas Aktif</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto mb-2" style="background:rgba(232,51,74,0.12);color:var(--danger-red);">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="stat-value">{{ $mahasiswaBerisiko }}</div>
            <div class="stat-label">Mahasiswa Berisiko</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto mb-2" style="background:rgba(0,180,200,0.12);color:var(--teal);">
                <i class="bi bi-database-fill"></i>
            </div>
            <div class="stat-value">5</div>
            <div class="stat-label">Tabel Data Aktif</div>
        </div>
    </div>
</div>
 
<div class="row g-4">
    <div class="col-md-8">
        <div class="section-card">
            <div class="section-header">
                <div>
                    <div class="section-title">Akses Cepat — Kelola Data</div>
                    <div class="section-subtitle">Navigasi ke halaman pengelolaan data</div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="{{ route('admin.mahasiswa.index') }}" style="display:block;background:#f8faff;border:1.5px solid #e8eef8;border-radius:13px;padding:18px;text-align:center;text-decoration:none;transition:all .2s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='#e8eef8'">
                        <div style="font-size:28px;margin-bottom:8px;">👨‍🎓</div>
                        <div style="font-size:13px;font-weight:700;color:var(--navy);">Data Mahasiswa</div>
                        <div style="font-size:11px;color:#8da3c0;margin-top:3px;">{{ $totalMahasiswa }} mahasiswa</div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('admin.dosen.index') }}" style="display:block;background:#f8faff;border:1.5px solid #e8eef8;border-radius:13px;padding:18px;text-align:center;text-decoration:none;transition:all .2s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='#e8eef8'">
                        <div style="font-size:28px;margin-bottom:8px;">👨‍🏫</div>
                        <div style="font-size:13px;font-weight:700;color:var(--navy);">Data Dosen</div>
                        <div style="font-size:11px;color:#8da3c0;margin-top:3px;">{{ $totalDosen }} dosen</div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('admin.import.index') }}" style="display:block;background:#f8faff;border:1.5px solid #e8eef8;border-radius:13px;padding:18px;text-align:center;text-decoration:none;transition:all .2s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='#e8eef8'">
                        <div style="font-size:28px;margin-bottom:8px;">📤</div>
                        <div style="font-size:13px;font-weight:700;color:var(--navy);">Import Data</div>
                        <div style="font-size:11px;color:#8da3c0;margin-top:3px;">Upload Excel</div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('admin.matkul.index') }}" style="display:block;background:#f8faff;border:1.5px solid #e8eef8;border-radius:13px;padding:18px;text-align:center;text-decoration:none;transition:all .2s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='#e8eef8'">
                        <div style="font-size:28px;margin-bottom:8px;">📚</div>
                        <div style="font-size:13px;font-weight:700;color:var(--navy);">Mata Kuliah</div>
                        <div style="font-size:11px;color:#8da3c0;margin-top:3px;">{{ $totalMatkul }} matkul</div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('admin.kelas.index') }}" style="display:block;background:#f8faff;border:1.5px solid #e8eef8;border-radius:13px;padding:18px;text-align:center;text-decoration:none;transition:all .2s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='#e8eef8'">
                        <div style="font-size:28px;margin-bottom:8px;">🏫</div>
                        <div style="font-size:13px;font-weight:700;color:var(--navy);">Kelola Kelas</div>
                        <div style="font-size:11px;color:#8da3c0;margin-top:3px;">{{ $totalKelas }} kelas</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="section-card">
            <div class="section-title mb-3">⚠️ Perhatian Admin</div>
            @if($mahasiswaBerisiko > 0)
            <div style="background:rgba(232,51,74,0.05);border:1px solid rgba(232,51,74,0.2);border-radius:10px;padding:14px;margin-bottom:10px;">
                <div style="font-weight:700;color:var(--danger-red);font-size:13px;margin-bottom:4px;">
                    {{ $mahasiswaBerisiko }} Mahasiswa Berisiko
                </div>
                <div style="font-size:11px;color:#8da3c0;">Terdapat mahasiswa dengan nilai D/E atau absensi ≥18 jam yang perlu penanganan DPA.</div>
            </div>
            @else
            <div style="background:rgba(40,199,111,0.05);border:1px solid rgba(40,199,111,0.2);border-radius:10px;padding:14px;">
                <div style="font-weight:700;color:var(--success-green);font-size:13px;margin-bottom:4px;">
                    ✅ Semua Aman
                </div>
                <div style="font-size:11px;color:#8da3c0;">Tidak ada mahasiswa dengan status berisiko saat ini.</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection