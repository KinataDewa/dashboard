<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peringatan Akademik — Academia</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
            background: #EAECEF;
            color: #0D1117;
            -webkit-font-smoothing: antialiased;
            padding: 28px 16px 48px;
        }

        /* ── Shell ── */
        .wrap {
            max-width: 580px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(0,0,0,.10), 0 0 0 1px rgba(0,0,0,.06);
        }

        /* ── Header ── */
        .header {
            background: #0D1117;
            padding: 0;
            position: relative;
            overflow: hidden;
        }

        /* Severity bar — the signature element */
        .severity-bar {
            height: 4px;
            background: linear-gradient(90deg, #FF4D4D 0%, #FF8C42 60%, #FFCA3A 100%);
        }

        .header-inner {
            padding: 28px 36px 30px;
        }

        /* Subtle dot-grid texture */
        .header-inner::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,.04) 1px, transparent 1px);
            background-size: 20px 20px;
            pointer-events: none;
        }

        .brand-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            position: relative;
        }
        .brand-logo {
            width: 24px; height: 24px;
            background: linear-gradient(135deg, #FF4D4D, #FF8C42);
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 900; color: #fff;
        }
        .brand-name {
            font-size: 13px; font-weight: 700;
            color: rgba(255,255,255,.5);
            letter-spacing: .5px;
        }
        .brand-name strong { color: rgba(255,255,255,.85); font-weight: 800; }

        .header-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255,77,77,.18);
            border: 1px solid rgba(255,77,77,.35);
            border-radius: 20px;
            padding: 3px 11px;
            font-size: 11px; font-weight: 700;
            color: #FF8080;
            letter-spacing: .4px;
            margin-bottom: 14px;
            position: relative;
        }
        .badge-dot {
            width: 6px; height: 6px;
            background: #FF4D4D;
            border-radius: 50%;
            animation: pulse-dot 2s infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: .35; }
        }

        .header-title {
            font-size: 24px; font-weight: 800;
            color: #fff;
            letter-spacing: -.5px;
            line-height: 1.25;
            position: relative;
        }
        .header-title span {
            background: linear-gradient(90deg, #FF8080, #FFCA3A);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .header-sub {
            font-size: 13.5px;
            color: rgba(255,255,255,.45);
            margin-top: 6px;
            line-height: 1.5;
            position: relative;
        }

        /* Risk pills in header */
        .header-pills {
            display: flex; flex-wrap: wrap; gap: 6px;
            margin-top: 18px; position: relative;
        }
        .hpill {
            display: inline-flex; align-items: center; gap: 4px;
            border-radius: 6px;
            padding: 4px 11px;
            font-size: 11.5px; font-weight: 700;
            letter-spacing: .2px;
        }
        .hpill-danger {
            background: rgba(255,77,77,.2);
            border: 1px solid rgba(255,77,77,.35);
            color: #FF9090;
        }
        .hpill-warn {
            background: rgba(255,202,58,.15);
            border: 1px solid rgba(255,202,58,.3);
            color: #FFCA3A;
        }
        .hpill-info {
            background: rgba(167,139,250,.15);
            border: 1px solid rgba(167,139,250,.3);
            color: #C4B5FD;
        }

        /* ── Body ── */
        .body { padding: 32px 36px; }

        .greeting {
            font-size: 15.5px; font-weight: 700;
            color: #0D1117; margin-bottom: 10px;
        }
        .intro {
            font-size: 13.5px; color: #5C6370;
            line-height: 1.75; margin-bottom: 28px;
        }
        .intro strong { color: #0D1117; font-weight: 700; }

        /* ── Info cards ── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 24px;
        }
        .info-card {
            background: #F6F8FA;
            border: 1px solid #E8ECF0;
            border-radius: 8px;
            padding: 12px 14px;
        }
        .info-card-label {
            font-size: 10.5px; font-weight: 700;
            color: #8B949E; text-transform: uppercase;
            letter-spacing: .8px; margin-bottom: 4px;
        }
        .info-card-val {
            font-size: 15px; font-weight: 800; color: #0D1117;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .val-danger { color: #CF3C3C; }
        .val-warn   { color: #B45309; }

        /* ── Section label ── */
        .section-label {
            font-size: 11px; font-weight: 700;
            color: #8B949E; text-transform: uppercase;
            letter-spacing: .9px;
            margin-bottom: 10px;
            display: flex; align-items: center; gap: 8px;
        }
        .section-label::after {
            content: '';
            flex: 1; height: 1px; background: #E8ECF0;
        }

        /* ── Risiko pills block ── */
        .risiko-block {
            background: #FFF8F8;
            border: 1px solid #FFD7D7;
            border-radius: 10px;
            padding: 16px 18px;
            margin-bottom: 22px;
        }
        .risiko-pills { display: flex; flex-wrap: wrap; gap: 7px; }
        .rpill {
            display: inline-flex; align-items: center; gap: 5px;
            border-radius: 7px; padding: 5px 12px;
            font-size: 12.5px; font-weight: 700;
        }
        .rpill-danger { background: #FFEBEB; color: #C0392B; border: 1px solid #FFD0D0; }
        .rpill-warn   { background: #FFF3E0; color: #8B4000; border: 1px solid #FFDDB5; }
        .rpill-purple { background: #F0EBFF; color: #6D28D9; border: 1px solid #DDD0FF; }

        /* ── Absensi ── */
        .absensi-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 22px;
        }
        .abs-card {
            text-align: center;
            background: #F6F8FA;
            border: 1px solid #E8ECF0;
            border-radius: 8px;
            padding: 12px 10px;
        }
        .abs-card.abs-danger {
            background: #FFF5F5;
            border-color: #FFD7D7;
        }
        .abs-num {
            font-size: 22px; font-weight: 900;
            line-height: 1; margin-bottom: 4px;
        }
        .abs-danger .abs-num { color: #CF3C3C; }
        .abs-safe   .abs-num { color: #0D1117; }
        .abs-label {
            font-size: 10.5px; font-weight: 600;
            color: #8B949E; text-transform: uppercase;
            letter-spacing: .6px;
        }

        /* ── Nilai table ── */
        .nilai-table {
            width: 100%; border-collapse: collapse;
            margin-bottom: 22px; font-size: 13px;
        }
        .nilai-table thead th {
            padding: 9px 12px;
            text-align: left;
            font-size: 10.5px; font-weight: 700;
            color: #8B949E; text-transform: uppercase;
            letter-spacing: .7px;
            background: #F6F8FA;
            border-top: 1px solid #E8ECF0;
            border-bottom: 1px solid #E8ECF0;
        }
        .nilai-table tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #F0F2F5;
            color: #0D1117; vertical-align: middle;
        }
        .nilai-table tbody tr:last-child td { border-bottom: none; }
        .nilai-table tbody tr:nth-child(even) td { background: #FAFBFC; }

        .mk-name { font-weight: 600; color: #0D1117; }
        .grade-badge {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 28px; height: 26px; border-radius: 6px;
            font-size: 12px; font-weight: 800; padding: 0 7px;
        }
        .grade-E { background: #FFE0E0; color: #8B0000; }
        .grade-D { background: #FFF3CD; color: #7B4200; }

        /* ── Action box ── */
        .action-box {
            background: #FFFBF0;
            border: 1px solid #FFE0A0;
            border-radius: 10px;
            padding: 18px 20px;
            margin-bottom: 26px;
        }
        .action-title {
            font-size: 13px; font-weight: 800;
            color: #7B4F00; margin-bottom: 12px;
            display: flex; align-items: center; gap: 7px;
        }
        .action-list { list-style: none; }
        .action-list li {
            font-size: 13px; color: #5C4300;
            line-height: 1.6;
            padding: 4px 0 4px 20px;
            position: relative;
        }
        .action-list li::before {
            content: '→';
            position: absolute; left: 0;
            color: #F59E0B; font-weight: 700;
        }
        .action-list li strong { color: #3D2C00; }
        .action-list li + li { border-top: 1px dashed #FFD86F; margin-top: 2px; padding-top: 6px; }

        /* ── CTA ── */
        .cta-wrap { text-align: center; margin: 26px 0 8px; }
        .cta-btn {
            display: inline-block;
            background: #0D1117;
            color: #fff;
            text-decoration: none;
            padding: 13px 30px;
            border-radius: 8px;
            font-size: 13.5px; font-weight: 700;
            letter-spacing: -.1px;
            transition: background .15s;
        }

        /* ── Divider ── */
        .divider { height: 1px; background: #E8ECF0; margin: 24px 0; }

        .footer-note {
            font-size: 12px; color: #8B949E;
            line-height: 1.7; text-align: center;
        }

        /* ── Footer ── */
        .footer {
            background: #F6F8FA;
            border-top: 1px solid #E8ECF0;
            padding: 18px 36px;
            text-align: center;
        }
        .footer-brand {
            font-size: 15px; font-weight: 900;
            color: #0D1117; letter-spacing: -.5px;
            margin-bottom: 4px;
        }
        .footer-brand span {
            background: linear-gradient(90deg, #FF4D4D, #FF8C42);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .footer-text {
            font-size: 11.5px; color: #8B949E; line-height: 1.65;
        }
        .footer-text a { color: #0550AE; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrap">

    {{-- ══ SEVERITY BAR ══ --}}
    <div class="severity-bar"></div>

    {{-- ══ HEADER ══ --}}
    <div class="header">
        <div class="header-inner">

            <div class="brand-row">
                <div class="brand-logo">A</div>
                <div class="brand-name"><strong>Academia</strong> · SIAKAD Polinema</div>
            </div>

            <div class="header-badge">
                <span class="badge-dot"></span>
                PERINGATAN AKADEMIK
            </div>

            <div class="header-title">
                Perhatian <span>Diperlukan</span>
            </div>
            <div class="header-sub">
                Sistem mendeteksi risiko akademik yang memerlukan tindakan segera.
            </div>

            @if(!empty($kategoriRisiko))
            <div class="header-pills">
                @foreach($kategoriRisiko as $k)
                @php
                    $isSpOrPs = in_array($k, ['ps','sp3','sp2','sp1']);
                    $isNilai  = in_array($k, ['nilai_e','nilai_d']);
                    $pillClass = $isSpOrPs ? 'hpill-danger' : ($isNilai ? 'hpill-warn' : 'hpill-info');
                    $pillIcon  = $isSpOrPs ? '⛔' : ($isNilai ? '📊' : '📉');
                    $pillLabel = match($k) {
                        'ps'         => 'Putus Studi',
                        'sp3'        => 'SP III',
                        'sp2'        => 'SP II',
                        'sp1'        => 'SP I',
                        'nilai_e'    => 'Nilai E',
                        'nilai_d'    => 'D > 3 MK',
                        'ips_rendah' => 'IPS < 2.00',
                        default      => $k,
                    };
                @endphp
                <span class="hpill {{ $pillClass }}">{{ $pillIcon }} {{ $pillLabel }}</span>
                @endforeach
            </div>
            @endif

        </div>
    </div>

    {{-- ══ BODY ══ --}}
    <div class="body">

        <div class="greeting">Yth. {{ $mahasiswa->nama }},</div>
        <p class="intro">
            SIAKAD Politeknik Negeri Malang mendeteksi kondisi akademik yang memerlukan
            perhatian segera. Tinjau rincian di bawah ini dan segera konsultasikan dengan
            <strong>Dosen Pembimbing Akademik</strong> Anda.
        </p>

        {{-- Info cards --}}
        <div class="info-grid">
            <div class="info-card">
                <div class="info-card-label">NIM</div>
                <div class="info-card-val">{{ $mahasiswa->nim }}</div>
            </div>
            <div class="info-card">
                <div class="info-card-label">Kelas</div>
                <div class="info-card-val">{{ $mahasiswa->kelas->nama ?? '—' }}</div>
            </div>
            <div class="info-card">
                <div class="info-card-label">IPK Saat Ini</div>
                <div class="info-card-val {{ $ipk < 2.00 ? 'val-danger' : ($ipk < 2.50 ? 'val-warn' : '') }}">
                    {{ $ipk }}
                </div>
            </div>
            <div class="info-card">
                <div class="info-card-label">Dosen PA</div>
                <div class="info-card-val" style="font-size:13px;">{{ $mahasiswa->dosenPa->nama ?? '—' }}</div>
            </div>
        </div>

        {{-- Kategori risiko --}}
        @if(!empty($kategoriRisiko))
        <div class="section-label">Kategori Risiko</div>
        <div class="risiko-block">
            <div class="risiko-pills">
                @foreach($kategoriRisiko as $k)
                @php
                    $isSpOrPs = in_array($k, ['ps','sp3','sp2','sp1']);
                    $isNilai  = in_array($k, ['nilai_e','nilai_d']);
                    $rClass   = $isSpOrPs ? 'rpill-danger' : ($isNilai ? 'rpill-warn' : 'rpill-purple');
                    $rLabel   = match($k) {
                        'ps'         => '⛔ Putus Studi — ≥56 jam alpha',
                        'sp3'        => '⛔ Surat Peringatan III — ≥47 jam',
                        'sp2'        => '⚠ Surat Peringatan II — ≥36 jam',
                        'sp1'        => '⚠ Surat Peringatan I — ≥18 jam',
                        'nilai_e'    => '📊 Terdapat Nilai E',
                        'nilai_d'    => '📊 Nilai D lebih dari 3 MK',
                        'ips_rendah' => '📉 IPS di bawah 2.00',
                        default      => $k,
                    };
                @endphp
                <span class="rpill {{ $rClass }}">{{ $rLabel }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Absensi alpha --}}
        @if(!empty($absensiAlpha))
        <div class="section-label">Rekap Kehadiran Semester Ini</div>
        <div class="absensi-row">
            <div class="abs-card abs-danger">
                <div class="abs-num">{{ $absensiAlpha['jam_alpha'] }}j</div>
                <div class="abs-label">Alpha</div>
            </div>
            <div class="abs-card abs-safe">
                <div class="abs-num" style="color:#0D1117;">{{ $absensiAlpha['jam_izin'] }}j</div>
                <div class="abs-label">Izin</div>
            </div>
            <div class="abs-card abs-safe">
                <div class="abs-num" style="color:#0D1117;">{{ $absensiAlpha['jam_sakit'] }}j</div>
                <div class="abs-label">Sakit</div>
            </div>
        </div>
        @endif

        {{-- Nilai D/E --}}
        @if(!empty($nilaiDE))
        <div class="section-label">Nilai D / E Semester Ini</div>
        <table class="nilai-table">
            <thead>
                <tr>
                    <th>Mata Kuliah</th>
                    <th style="text-align:center;">Grade</th>
                    <th style="text-align:right;">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nilaiDE as $n)
                <tr>
                    <td><span class="mk-name">{{ $n['nama'] }}</span></td>
                    <td style="text-align:center;">
                        <span class="grade-badge grade-{{ $n['grade'] }}">{{ $n['grade'] }}</span>
                    </td>
                    <td style="text-align:right; font-weight:700; color:#5C6370;">{{ $n['nilai'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        {{-- Action items --}}
        <div class="section-label">Langkah Yang Perlu Dilakukan</div>
        <div class="action-box">
            <div class="action-title">⚠ Segera lakukan hal berikut</div>
            <ul class="action-list">
                @foreach($kategoriRisiko as $k)
                @if($k === 'ps')
                <li>Datangi Jurusan secepatnya untuk konsultasi status akademik.</li>
                @elseif(in_array($k, ['sp3','sp2','sp1']))
                <li>Perbaiki kehadiran dan konsultasikan {{ match($k) { 'sp3' => 'Surat Peringatan III', 'sp2' => 'Surat Peringatan II', default => 'Surat Peringatan I' } }} dengan Dosen PA.</li>
                @elseif($k === 'nilai_e')
                <li>Rencanakan perbaikan nilai E bersama Dosen PA sesegera mungkin.</li>
                @elseif($k === 'nilai_d')
                <li>Diskusikan strategi peningkatan nilai D dengan Dosen pengampu dan Dosen PA.</li>
                @elseif($k === 'ips_rendah')
                <li>Susun rencana peningkatan IPS bersama Dosen PA untuk semester berikutnya.</li>
                @endif
                @endforeach
                <li>Hubungi <strong>{{ $mahasiswa->dosenPa->nama ?? 'Dosen PA' }}</strong> untuk menjadwalkan bimbingan.</li>
                <li>Masuk ke SIAKAD untuk melihat detail lengkap nilai dan absensi Anda.</li>
            </ul>
        </div>

        {{-- CTA --}}
        <div class="cta-wrap">
            <a href="{{ url('/mahasiswa/dashboard') }}" class="cta-btn">
                Lihat Dashboard Akademik →
            </a>
        </div>

        <div class="divider"></div>

        <p class="footer-note">
            Email ini dikirim otomatis oleh sistem SIAKAD Politeknik Negeri Malang.<br>
            Jika Anda merasa ini adalah kesalahan, hubungi admin jurusan.
        </p>

    </div>

    {{-- ══ FOOTER ══ --}}
    <div class="footer">
        <div class="footer-brand">Acade<span>mia</span></div>
        <div class="footer-text">
            Sistem Informasi Akademik — Jurusan Teknologi Informasi<br>
            Politeknik Negeri Malang ·
            <a href="mailto:admin@polinema.ac.id">admin@polinema.ac.id</a>
        </div>
    </div>

</div>
</body>
</html>