<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peringatan Akademik — Academia</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #F1F5F9;
            color: #0F172A;
            -webkit-font-smoothing: antialiased;
        }
        .wrap {
            max-width: 600px;
            margin: 32px auto;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,.08);
        }
 
        /* Header */
        .header {
            background: linear-gradient(135deg, #1A0A0A 0%, #7F1D1D 50%, #991B1B 100%);
            padding: 36px 40px 32px;
            position: relative;
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute; inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,.06) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .header-brand {
            font-size: 13px; font-weight: 700;
            color: rgba(255,255,255,.6);
            letter-spacing: 1px; text-transform: uppercase;
            margin-bottom: 20px;
            position: relative;
        }
        .header-brand span {
            color: #FCA5A5; font-weight: 800;
        }
        .header-icon {
            width: 56px; height: 56px;
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; margin-bottom: 16px;
            position: relative;
        }
        .header-title {
            font-size: 22px; font-weight: 800;
            color: #fff; line-height: 1.3;
            letter-spacing: -.3px; position: relative;
        }
        .header-sub {
            font-size: 13.5px; color: rgba(255,255,255,.7);
            margin-top: 6px; position: relative;
        }
 
        /* Alert tag */
        .alert-tag {
            display: inline-block;
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 20px; padding: 3px 12px;
            font-size: 11px; font-weight: 700;
            color: #FCA5A5; letter-spacing: .5px;
            margin-bottom: 12px; position: relative;
        }
 
        /* Body */
        .body { padding: 32px 40px; }
 
        .greeting {
            font-size: 16px; font-weight: 600;
            color: #0F172A; margin-bottom: 12px;
        }
        .intro {
            font-size: 14px; color: #475569;
            line-height: 1.7; margin-bottom: 28px;
        }
 
        /* Info strip */
        .info-strip {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex; gap: 20px;
            flex-wrap: wrap;
        }
        .info-item { }
        .info-label {
            font-size: 11px; font-weight: 700;
            color: #94A3B8; text-transform: uppercase;
            letter-spacing: .7px; margin-bottom: 3px;
        }
        .info-val {
            font-size: 15px; font-weight: 700; color: #0F172A;
        }
 
        /* Section title */
        .section-title {
            font-size: 13px; font-weight: 700;
            color: #64748B; text-transform: uppercase;
            letter-spacing: .8px; margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #E2E8F0;
        }
 
        /* Table nilai */
        .data-table {
            width: 100%; border-collapse: collapse;
            margin-bottom: 24px; font-size: 13.5px;
        }
        .data-table thead th {
            background: #F8FAFC;
            padding: 9px 14px; text-align: left;
            font-size: 11.5px; font-weight: 600;
            color: #64748B; border-bottom: 1px solid #E2E8F0;
        }
        .data-table tbody td {
            padding: 11px 14px;
            border-bottom: 1px solid #F1F5F9;
            color: #0F172A;
        }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background: #F8FAFF; }
 
        /* Grade badge */
        .grade-badge {
            display: inline-flex; align-items: center;
            justify-content: center;
            width: 28px; height: 28px; border-radius: 50%;
            font-size: 12px; font-weight: 800;
        }
        .grade-D { background: #FEE2E2; color: #991B1B; }
        .grade-E { background: #FEE2E2; color: #7F1D1D; }
 
        /* Progress bar */
        .progress-wrap { margin-top: 4px; }
        .progress-bar {
            height: 5px; background: #F1F5F9;
            border-radius: 3px; overflow: hidden;
        }
        .progress-fill {
            height: 100%; border-radius: 3px;
        }
        .progress-label {
            font-size: 11px; color: #94A3B8; margin-top: 2px;
        }
 
        /* Alpha badge */
        .alpha-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 10px; border-radius: 20px;
            font-size: 12px; font-weight: 700;
        }
        .alpha-kritis  { background: #FEE2E2; color: #991B1B; }
        .alpha-waspada { background: #FEF3C7; color: #92400E; }
 
        /* Warning box */
        .warning-box {
            background: #FFF7ED;
            border: 1px solid #FED7AA;
            border-left: 4px solid #F59E0B;
            border-radius: 10px;
            padding: 16px 18px;
            margin-bottom: 24px;
        }
        .warning-box-title {
            font-size: 13.5px; font-weight: 700;
            color: #92400E; margin-bottom: 6px;
        }
        .warning-box-text {
            font-size: 13px; color: #78350F; line-height: 1.6;
        }
 
        /* CTA Button */
        .cta-wrap { text-align: center; margin: 28px 0; }
        .cta-btn {
            display: inline-block;
            background: linear-gradient(135deg, #1E3A8A, #2563EB);
            color: #fff; text-decoration: none;
            padding: 14px 32px; border-radius: 10px;
            font-size: 14px; font-weight: 700;
            letter-spacing: -.2px;
            box-shadow: 0 4px 16px rgba(37,99,235,.3);
        }
 
        /* Footer */
        .footer {
            background: #F8FAFC;
            border-top: 1px solid #E2E8F0;
            padding: 20px 40px;
            text-align: center;
        }
        .footer-brand {
            font-size: 15px; font-weight: 800;
            color: #2563EB; letter-spacing: -.3px;
            margin-bottom: 4px;
        }
        .footer-text {
            font-size: 12px; color: #94A3B8; line-height: 1.6;
        }
        .footer-text a { color: #2563EB; text-decoration: none; }
 
        /* Divider */
        .divider {
            height: 1px; background: #E2E8F0;
            margin: 24px 0;
        }
    </style>
</head>
<body>
<div class="wrap">
 
    {{-- ══ HEADER ══ --}}
    <div class="header">
        <div class="header-brand">⬛ <span>Academia</span> — SIAKAD Polinema</div>
        <div class="alert-tag">⚡ Peringatan Akademik</div>
        <div class="header-icon">⚠️</div>
        <div class="header-title">Perhatian Diperlukan!</div>
        <div class="header-sub">Sistem mendeteksi risiko akademik pada akun Anda.</div>
    </div>
 
    {{-- ══ BODY ══ --}}
    <div class="body">
 
        <div class="greeting">Yth. {{ $mahasiswa->nama }},</div>
        <p class="intro">
            Sistem SIAKAD Politeknik Negeri Malang mendeteksi bahwa terdapat kondisi akademik
            yang memerlukan perhatian segera. Mohon tinjau informasi di bawah ini dan segera
            hubungi <strong>Dosen Pembimbing Akademik</strong> Anda untuk konsultasi.
        </p>
 
        {{-- Info strip --}}
        <div class="info-strip">
            <div class="info-item">
                <div class="info-label">NIM</div>
                <div class="info-val">{{ $mahasiswa->nim }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Kelas</div>
                <div class="info-val">{{ $mahasiswa->kelas->nama ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">IPK Saat Ini</div>
                <div class="info-val" style="color:{{ $ipk < 2.5 ? '#EF4444' : '#0F172A' }};">{{ $ipk }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Dosen PA</div>
                <div class="info-val">{{ $mahasiswa->dosenPa->nama ?? '-' }}</div>
            </div>
        </div>
 
        {{-- Nilai D/E --}}
        @if(count($nilaiDE) > 0)
        <div class="section-title">📊 Nilai Kategori D/E ({{ count($nilaiDE) }} mata kuliah)</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mata Kuliah</th>
                    <th style="text-align:center;">Nilai Akhir</th>
                    <th style="text-align:center;">Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nilaiDE as $n)
                <tr>
                    <td>{{ $n['nama'] }}</td>
                    <td style="text-align:center;font-weight:700;color:#EF4444;">{{ $n['nilai'] }}</td>
                    <td style="text-align:center;">
                        <span class="grade-badge grade-{{ $n['grade'] }}">{{ $n['grade'] }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
 
        {{-- Absensi kritis --}}
        @if(count($absensiKritis) > 0)
        <div class="section-title">📅 Absensi Mendekati/Melewati Batas ({{ count($absensiKritis) }} mata kuliah)</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mata Kuliah</th>
                    <th style="text-align:center;">Jam Alpha</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensiKritis as $a)
                <tr>
                    <td>
                        {{ $a['nama'] }}
                        <div class="progress-wrap">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width:{{ min(($a['jam_alpha']/18)*100,100) }}%;background:{{ $a['kritis'] ? '#EF4444' : '#F59E0B' }};"></div>
                            </div>
                            <div class="progress-label">{{ $a['jam_alpha'] }} / 18 jam</div>
                        </div>
                    </td>
                    <td style="text-align:center;font-weight:800;color:{{ $a['kritis'] ? '#EF4444' : '#F59E0B' }};">
                        {{ $a['jam_alpha'] }}j
                    </td>
                    <td>
                        @if($a['kritis'])
                            <span class="alpha-badge alpha-kritis">⛔ Melewati Batas</span>
                        @else
                            <span class="alpha-badge alpha-waspada">⚠ Sisa {{ $a['sisa'] }}j</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
 
        {{-- Warning box --}}
        <div class="warning-box">
            <div class="warning-box-title">⚠ Yang perlu Anda lakukan sekarang:</div>
            <div class="warning-box-text">
                @if(count($nilaiDE) > 0)
                • Konsultasikan rencana perbaikan nilai dengan Dosen PA.<br>
                @endif
                @if(count($absensiKritis) > 0)
                • Pastikan kehadiran di semua kelas untuk mencegah pelanggaran batas absensi.<br>
                @endif
                • Hubungi <strong>{{ $mahasiswa->dosenPa->nama ?? 'Dosen PA' }}</strong> untuk jadwal bimbingan.<br>
                • Login ke SIAKAD untuk melihat detail lengkap nilai dan absensi Anda.
            </div>
        </div>
 
        {{-- CTA --}}
        <div class="cta-wrap">
            <a href="{{ url('/mahasiswa/dashboard') }}" class="cta-btn">
                🔍 Lihat Dashboard Akademik Saya
            </a>
        </div>
 
        <div class="divider"></div>
 
        <p style="font-size:12.5px;color:#94A3B8;line-height:1.6;text-align:center;">
            Email ini dikirim otomatis oleh sistem SIAKAD Politeknik Negeri Malang.<br>
            Jika Anda merasa ini adalah kesalahan, hubungi admin jurusan.
        </p>
    </div>
 
    {{-- ══ FOOTER ══ --}}
    <div class="footer">
        <div class="footer-brand">Academia</div>
        <div class="footer-text">
            Sistem Informasi Akademik — Jurusan Teknologi Informasi<br>
            Politeknik Negeri Malang · <a href="mailto:admin@polinema.ac.id">admin@polinema.ac.id</a>
        </div>
    </div>
 
</div>
</body>
</html>