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

        /* Kategori pill di header */
        .kategori-pills {
            display: flex; flex-wrap: wrap; gap: 6px;
            margin-top: 12px; position: relative;
        }
        .kategori-pill {
            display: inline-block;
            background: rgba(255,255,255,.18);
            border: 1px solid rgba(255,255,255,.3);
            border-radius: 20px; padding: 3px 12px;
            font-size: 12px; font-weight: 700; color: #fff;
        }
        .kategori-pill.pill-sp {
            background: rgba(239,68,68,.35);
            border-color: rgba(239,68,68,.5);
            color: #FCA5A5;
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

        /* Kategori risiko strip */
        .risiko-strip {
            background: #FEF2F2;
            border: 1px solid #FEE2E2;
            border-left: 4px solid #EF4444;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 24px;
        }
        .risiko-strip-title {
            font-size: 12px; font-weight: 700;
            color: #991B1B; text-transform: uppercase;
            letter-spacing: .7px; margin-bottom: 10px;
        }
        .risiko-pills {
            display: flex; flex-wrap: wrap; gap: 7px;
        }
        .risiko-pill {
            display: inline-block;
            border-radius: 20px; padding: 4px 13px;
            font-size: 12.5px; font-weight: 700;
        }
        .risiko-pill-sp    { background: #FEE2E2; color: #991B1B; }
        .risiko-pill-nilai { background: #FEF3C7; color: #92400E; }
        .risiko-pill-ips   { background: #EDE9FE; color: #6D28D9; }

        /* Alpha info */
        .alpha-info {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 8px;
            display: flex; justify-content: space-between;
            align-items: center; flex-wrap: wrap; gap: 10px;
        }
        .alpha-info-label { font-size: 13px; color: #64748B; font-weight: 600; }
        .alpha-info-val   { font-size: 18px; font-weight: 800; }
        .alpha-progress { margin-top: 8px; }
        .progress-bar {
            height: 6px; background: #F1F5F9;
            border-radius: 3px; overflow: hidden;
        }
        .progress-fill { height: 100%; border-radius: 3px; }
        .progress-markers {
            display: flex; justify-content: space-between;
            font-size: 10px; color: #94A3B8; margin-top: 3px;
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

        /* Grade badge */
        .grade-badge {
            display: inline-flex; align-items: center;
            justify-content: center;
            min-width: 30px; height: 28px; border-radius: 14px;
            font-size: 12px; font-weight: 800; padding: 0 6px;
        }
        .grade-D { background: #FEE2E2; color: #991B1B; }
        .grade-E { background: #FEE2E2; color: #7F1D1D; }

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
        @if(!empty($kategoriRisiko))
        <div class="kategori-pills">
            @foreach($kategoriRisiko as $k)
            @php
                $isSpOrPs = in_array($k, ['ps','sp3','sp2','sp1']);
                $pillLabel = match($k) {
                    'ps'         => '⛔ Putus Studi',
                    'sp3'        => '⛔ SP III',
                    'sp2'        => '⚠ SP II',
                    'sp1'        => '⚠ SP I',
                    'nilai_e'    => '📊 Nilai E',
                    'nilai_d'    => '📊 D > 3 MK',
                    'ips_rendah' => '📉 IPS < 2.00',
                    default      => $k,
                };
            @endphp
            <span class="kategori-pill {{ $isSpOrPs ? 'pill-sp' : '' }}">{{ $pillLabel }}</span>
            @endforeach
        </div>
        @endif
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
                <div class="info-val" style="color:{{ $ipk < 2.00 ? '#EF4444' : ($ipk < 2.50 ? '#F59E0B' : '#0F172A') }};">{{ $ipk }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Dosen PA</div>
                <div class="info-val">{{ $mahasiswa->dosenPa->nama ?? '-' }}</div>
            </div>
        </div>

        {{-- Kategori risiko --}}
        @if(!empty($kategoriRisiko))
        <div class="risiko-strip">
            <div class="risiko-strip-title">⚠ Kategori Risiko Terdeteksi</div>
            <div class="risiko-pills">
                @foreach($kategoriRisiko as $k)
                @php
                    $isSpOrPs = in_array($k, ['ps','sp3','sp2','sp1']);
                    $isNilai  = in_array($k, ['nilai_e','nilai_d']);
                    $pillClass = $isSpOrPs ? 'risiko-pill-sp' : ($isNilai ? 'risiko-pill-nilai' : 'risiko-pill-ips');
                    $pillLabel = match($k) {
                        'ps'         => '⛔ Putus Studi (≥ 56j alpha)',
                        'sp3'        => '⛔ Surat Peringatan III (≥ 47j alpha)',
                        'sp2'        => '⚠ Surat Peringatan II (≥ 36j alpha)',
                        'sp1'        => '⚠ Surat Peringatan I (≥ 18j alpha)',
                        'nilai_e'    => '📊 Ada Nilai E',
                        'nilai_d'    => '📊 Nilai D Lebih dari 3 MK',
                        'ips_rendah' => '📉 IPS < 2.00',
                        default      => $k,
                    };
                @endphp
                <span class="risiko-pill {{ $pillClass }}">{{ $pillLabel }}</span>
                @endforeach
            </div>
        </div>
        @endif

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

        {{-- Absensi alpha --}}
        @if(count($absensiAlpha) > 0)
        <div class="section-title">📅 Rekap Ketidakhadiran ({{ count($absensiAlpha) }} mata kuliah)</div>

        {{-- Total alpha + SP level --}}
        <div class="alpha-info">
            <div>
                <div class="alpha-info-label">Total Alpha Semester Ini</div>
                <div class="alpha-info-val" style="color:{{ $totalAlpha >= 47 ? '#7F1D1D' : ($totalAlpha >= 36 ? '#DC2626' : ($totalAlpha >= 18 ? '#EF4444' : '#F59E0B')) }};">
                    {{ $totalAlpha }} jam
                </div>
            </div>
            <div style="text-align:right;">
                <div class="alpha-info-label">Status</div>
                @php
                    $spStatus = match(true) {
                        $alphaEfektif >= 56 => ['label' => '⛔ Putus Studi', 'color' => '#7F1D1D'],
                        $alphaEfektif >= 47 => ['label' => '⛔ SP III', 'color' => '#991B1B'],
                        $alphaEfektif >= 36 => ['label' => '⚠ SP II', 'color' => '#DC2626'],
                        $alphaEfektif >= 18 => ['label' => '⚠ SP I', 'color' => '#EF4444'],
                        default             => ['label' => '✅ Aman', 'color' => '#15803D'],
                    };
                @endphp
                <div class="alpha-info-val" style="color:{{ $spStatus['color'] }};">{{ $spStatus['label'] }}</div>
            </div>
        </div>
        {{-- Progress bar: 56j = batas PS --}}
        <div class="alpha-progress" style="margin-bottom: 16px;">
            <div class="progress-bar">
                <div class="progress-fill" style="width:{{ min(($totalAlpha/56)*100,100) }}%;background:{{ $totalAlpha >= 47 ? '#7F1D1D' : ($totalAlpha >= 36 ? '#DC2626' : ($totalAlpha >= 18 ? '#EF4444' : '#F59E0B')) }};"></div>
            </div>
            <div class="progress-markers">
                <span>0j</span>
                <span>SP I (18j)</span>
                <span>SP II (36j)</span>
                <span>SP III (47j)</span>
                <span>PS (56j)</span>
            </div>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Mata Kuliah</th>
                    <th style="text-align:center;">Jam Alpha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensiAlpha as $a)
                <tr>
                    <td>{{ $a['nama'] }}</td>
                    <td style="text-align:center;font-weight:800;color:{{ $a['jam_alpha'] >= 18 ? '#EF4444' : ($a['jam_alpha'] >= 10 ? '#F59E0B' : '#64748B') }};">
                        {{ $a['jam_alpha'] }}j
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
                @foreach($kategoriRisiko as $k)
                @if($k === 'ps')
                • Segera datangi Jurusan untuk konsultasi status akademik.<br>
                @elseif(in_array($k, ['sp3','sp2','sp1']))
                • Perbaiki kehadiran segera dan konsultasi dengan Dosen PA terkait {{ match($k) { 'sp3' => 'Surat Peringatan III', 'sp2' => 'Surat Peringatan II', default => 'Surat Peringatan I' } }}.<br>
                @elseif($k === 'nilai_e')
                • Konsultasikan rencana perbaikan nilai E dengan Dosen PA.<br>
                @elseif($k === 'nilai_d')
                • Diskusikan strategi peningkatan nilai D dengan Dosen terkait dan Dosen PA.<br>
                @elseif($k === 'ips_rendah')
                • Rencanakan perbaikan IPS bersama Dosen PA untuk semester berikutnya.<br>
                @endif
                @endforeach
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
