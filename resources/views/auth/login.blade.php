<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academia — Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Sora:wght@700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:           #030712;
            --blue:         #3b82f6;
            --purple:       #8b5cf6;
            --cyan:         #06b6d4;
            --green:        #10b981;
            --accent:       #60a5fa;
            --accent2:      #a78bfa;
            --accent3:      #34d399;
            --glass:        rgba(255,255,255,0.05);
            --glass-border: rgba(255,255,255,0.1);
            --text:         #f8fafc;
            --text-muted:   rgba(248,250,252,0.5);
            --text-dim:     rgba(248,250,252,0.28);
        }

        html, body {
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            -webkit-font-smoothing: antialiased;
            overflow: hidden;
        }

        /* ─── Aurora Background ─────────────────────────── */
        .aurora {
            position: fixed; inset: 0; z-index: 0; overflow: hidden;
        }
        .aurora-orb {
            position: absolute; border-radius: 50%;
            filter: blur(100px); pointer-events: none;
            animation: orbDrift ease-in-out infinite;
        }
        .orb-1 { width:700px;height:700px;top:-200px;left:-150px;background:radial-gradient(circle,rgba(59,130,246,.5),transparent 70%);animation-duration:15s; }
        .orb-2 { width:600px;height:600px;top:40%;right:-180px;background:radial-gradient(circle,rgba(139,92,246,.45),transparent 70%);animation-duration:11s;animation-delay:-4s; }
        .orb-3 { width:500px;height:500px;bottom:-120px;left:30%;background:radial-gradient(circle,rgba(6,182,212,.3),transparent 70%);animation-duration:17s;animation-delay:-8s; }
        .orb-4 { width:350px;height:350px;top:35%;left:42%;background:radial-gradient(circle,rgba(16,185,129,.18),transparent 70%);animation-duration:19s;animation-delay:-13s; }
        @keyframes orbDrift {
            0%,100% { transform:translate(0,0) scale(1); }
            25%     { transform:translate(30px,-40px) scale(1.06); }
            50%     { transform:translate(-20px,25px) scale(.95); }
            75%     { transform:translate(18px,40px) scale(1.03); }
        }

        .grid-overlay {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image: radial-gradient(circle, rgba(255,255,255,.055) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* ─── Layout ─────────────────────────────────────── */
        .login-wrap {
            position: relative; z-index: 1;
            display: flex; min-height: 100vh;
        }

        /* ─── Left Panel ─────────────────────────────────── */
        .left-panel {
            width: 50%; flex-shrink: 0;
            display: flex; flex-direction: column;
            justify-content: space-between;
            padding: 52px 60px;
            position: relative; overflow: hidden;
        }

        /* Concentric decorative rings */
        .deco-ring {
            position: absolute; border-radius: 50%;
            pointer-events: none; border: 1px solid rgba(255,255,255,.055);
        }
        .ring-1 { width:520px;height:520px;top:50%;left:50%;transform:translate(-50%,-50%);animation:ringPulse 7s ease-in-out infinite; }
        .ring-2 { width:390px;height:390px;top:50%;left:50%;transform:translate(-50%,-50%);border-color:rgba(59,130,246,.09);animation:ringPulse 7s ease-in-out infinite .9s; }
        .ring-3 { width:260px;height:260px;top:50%;left:50%;transform:translate(-50%,-50%);border-color:rgba(139,92,246,.12);animation:ringPulse 7s ease-in-out infinite 1.8s; }
        @keyframes ringPulse {
            0%,100% { opacity:.55; transform:translate(-50%,-50%) scale(1); }
            50%     { opacity:1;   transform:translate(-50%,-50%) scale(1.04); }
        }

        /* Floating particles */
        .particles { position:absolute;inset:0;pointer-events:none;overflow:hidden; }
        .particle {
            position:absolute; border-radius:50%;
            background:rgba(255,255,255,.45);
            animation:particleFloat linear infinite;
        }
        @keyframes particleFloat {
            0%   { transform:translateY(110%) translateX(0); opacity:0; }
            8%   { opacity:1; }
            88%  { opacity:.5; }
            100% { transform:translateY(-80px) translateX(24px); opacity:0; }
        }

        /* Brand */
        .left-top { position:relative;z-index:10;animation:fadeUp .8s cubic-bezier(.16,1,.3,1) both; }
        .brand { display:flex;align-items:center;gap:14px; }
        .brand-icon {
            width:46px;height:46px;border-radius:14px;flex-shrink:0;
            background:linear-gradient(135deg,var(--blue),var(--purple));
            display:flex;align-items:center;justify-content:center;
            font-size:21px;color:#fff;
            box-shadow:0 8px 32px rgba(59,130,246,.4);
            animation:iconGlow 3s ease-in-out infinite;
        }
        @keyframes iconGlow {
            0%,100% { box-shadow:0 8px 32px rgba(59,130,246,.4); }
            50%     { box-shadow:0 8px 52px rgba(59,130,246,.7),0 0 80px rgba(139,92,246,.3); }
        }
        .brand-name  { font-size:24px;font-weight:900;color:var(--text);letter-spacing:-.5px; }
        .brand-badge {
            display:inline-block;margin-top:2px;font-size:9px;font-weight:700;
            padding:2px 8px;border-radius:999px;letter-spacing:1px;text-transform:uppercase;
            background:rgba(59,130,246,.18);border:1px solid rgba(59,130,246,.3);color:var(--accent);
        }

        /* Tagline */
        .left-mid { position:relative;z-index:10;animation:fadeUp .8s cubic-bezier(.16,1,.3,1) .1s both; }
        .tagline {
            font-family:'Sora',sans-serif;
            font-size:54px;font-weight:900;
            color:var(--text);line-height:1.08;
            letter-spacing:-2.5px;margin-bottom:20px;
        }
        .tagline .grad {
            background:linear-gradient(135deg,#60a5fa 0%,#a78bfa 50%,#34d399 100%);
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;
            background-clip:text;background-size:200% 200%;
            animation:gradShift 5s ease infinite;
        }
        @keyframes gradShift { 0%,100%{background-position:0% 50%;} 50%{background-position:100% 50%;} }

        .tagline-desc { font-size:14px;color:var(--text-muted);line-height:1.75;max-width:320px; }

        .pills { display:flex;gap:8px;flex-wrap:wrap;margin-top:22px; }
        .pill {
            display:flex;align-items:center;gap:6px;
            padding:6px 14px;border-radius:999px;
            background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);
            font-size:11px;font-weight:600;color:var(--text-muted);
            transition:all .25s;cursor:default;
        }
        .pill:hover { background:rgba(59,130,246,.12);border-color:rgba(59,130,246,.35);color:var(--accent); }
        .pill i { font-size:12px; }

        /* Stats */
        .stats-row { position:relative;z-index:10;display:flex;animation:fadeUp .8s cubic-bezier(.16,1,.3,1) .2s both; }
        .stat-card {
            flex:1;padding:20px 22px;
            background:rgba(255,255,255,.04);
            border:1px solid rgba(255,255,255,.08);border-right:none;
            position:relative;overflow:hidden;transition:all .3s;
        }
        .stat-card:first-child { border-radius:16px 0 0 16px; }
        .stat-card:last-child  { border-radius:0 16px 16px 0;border-right:1px solid rgba(255,255,255,.08); }
        .stat-card::before {
            content:'';position:absolute;top:0;left:0;right:0;height:2px;
            background:linear-gradient(90deg,transparent,rgba(59,130,246,.6),transparent);
            opacity:0;transition:opacity .3s;
        }
        .stat-card:hover::before { opacity:1; }
        .stat-card:hover { background:rgba(255,255,255,.07); }
        .stat-num  { font-size:32px;font-weight:900;color:var(--text);letter-spacing:-1.5px;line-height:1; }
        .stat-plus { font-size:18px;color:var(--accent);font-weight:700; }
        .stat-label{ font-size:10px;color:var(--text-dim);margin-top:5px;font-weight:600;text-transform:uppercase;letter-spacing:.9px; }

        /* ─── Right Panel ─────────────────────────────────── */
        .right-panel {
            flex:1;display:flex;align-items:center;justify-content:center;
            padding:40px 32px;position:relative;
        }
        .right-panel::before {
            content:'';position:absolute;
            width:480px;height:480px;top:50%;left:50%;transform:translate(-50%,-50%);
            background:radial-gradient(circle,rgba(59,130,246,.1) 0%,transparent 70%);
            pointer-events:none;
        }

        /* Glassmorphism card */
        .form-card {
            width:100%;max-width:420px;
            background:rgba(255,255,255,.05);
            backdrop-filter:blur(40px);-webkit-backdrop-filter:blur(40px);
            border:1px solid rgba(255,255,255,.1);
            border-radius:24px;padding:40px 36px;
            box-shadow:
                0 0 0 1px rgba(255,255,255,.04) inset,
                0 32px 80px rgba(0,0,0,.55),
                0 0 120px rgba(59,130,246,.07);
            position:relative;overflow:hidden;
            animation:fadeUp .8s cubic-bezier(.16,1,.3,1) .15s both;
        }
        /* Top shimmer line */
        .form-card::before {
            content:'';position:absolute;
            top:0;left:14px;right:14px;height:1px;
            background:linear-gradient(90deg,transparent,rgba(96,165,250,.65),rgba(167,139,250,.65),transparent);
        }

        /* Form header */
        .form-greeting {
            display:flex;align-items:center;gap:8px;
            font-size:11px;font-weight:700;color:var(--accent);
            letter-spacing:1.2px;text-transform:uppercase;margin-bottom:10px;
        }
        .greeting-dot {
            width:6px;height:6px;border-radius:50%;
            background:var(--accent);box-shadow:0 0 8px var(--accent);
            animation:dotBlink 2.2s ease-in-out infinite;
        }
        @keyframes dotBlink { 0%,100%{opacity:1;} 50%{opacity:.3;} }

        .form-title { font-size:27px;font-weight:800;color:var(--text);letter-spacing:-.7px;line-height:1.15;margin-bottom:6px; }
        .form-sub   { font-size:13px;color:var(--text-muted);line-height:1.55;margin-bottom:26px; }

        /* Inputs */
        .input-group  { margin-bottom:15px; }
        .input-label  { display:block;font-size:10px;font-weight:700;color:rgba(255,255,255,.45);margin-bottom:7px;text-transform:uppercase;letter-spacing:.9px; }
        .input-wrap   { position:relative; }
        .input-icon   { position:absolute;left:15px;top:50%;transform:translateY(-50%);font-size:14px;color:rgba(255,255,255,.22);transition:color .25s;pointer-events:none; }
        .form-input {
            width:100%;
            background:rgba(255,255,255,.06);
            border:1px solid rgba(255,255,255,.1);
            border-radius:12px;
            padding:13px 14px 13px 44px;
            font-size:14px;font-family:'Plus Jakarta Sans',sans-serif;
            color:var(--text);outline:none;transition:all .25s;
        }
        .form-input::placeholder { color:rgba(255,255,255,.18); }
        .form-input:focus {
            background:rgba(59,130,246,.08);
            border-color:rgba(59,130,246,.55);
            box-shadow:0 0 0 4px rgba(59,130,246,.1),0 0 24px rgba(59,130,246,.12);
        }
        .input-wrap:focus-within .input-icon { color:var(--accent); }
        .pw-toggle {
            position:absolute;right:14px;top:50%;transform:translateY(-50%);
            cursor:pointer;color:rgba(255,255,255,.22);font-size:14px;
            transition:color .2s;background:none;border:none;padding:4px;
        }
        .pw-toggle:hover { color:var(--accent); }
        .input-error { font-size:12px;color:#fca5a5;margin-top:5px;display:flex;align-items:center;gap:4px; }

        /* Alert */
        .alert-err {
            background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);
            border-left:3px solid #ef4444;border-radius:10px;
            padding:11px 14px;font-size:13px;color:#fca5a5;
            display:flex;align-items:center;gap:8px;
            margin-bottom:18px;animation:fadeDown .3s ease both;
        }

        /* Button */
        .btn-login {
            width:100%;
            background:linear-gradient(135deg,#3b82f6,#6366f1);
            color:#fff;border:none;border-radius:12px;
            padding:14px;font-size:15px;font-weight:700;
            font-family:'Plus Jakarta Sans',sans-serif;
            cursor:pointer;position:relative;overflow:hidden;
            transition:all .25s;
            display:flex;align-items:center;justify-content:center;gap:8px;
            margin-top:8px;
            box-shadow:0 8px 32px rgba(59,130,246,.3);
        }
        .btn-login .btn-bg {
            position:absolute;inset:0;
            background:linear-gradient(135deg,#6366f1,#8b5cf6);
            opacity:0;transition:opacity .3s;
        }
        .btn-login:hover { transform:translateY(-2px);box-shadow:0 16px 48px rgba(59,130,246,.42); }
        .btn-login:hover .btn-bg { opacity:1; }
        .btn-login:active { transform:translateY(0); }
        .btn-login i,.btn-login span { position:relative;z-index:1; }
        /* Shimmer sweep */
        .btn-login::after {
            content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;
            background:linear-gradient(90deg,transparent,rgba(255,255,255,.22),transparent);
            transition:left .55s;
        }
        .btn-login:hover::after { left:100%; }

        /* Demo section */
        .demo-section { margin-top:22px;padding-top:20px;border-top:1px solid rgba(255,255,255,.08); }
        .demo-label {
            font-size:10px;font-weight:700;color:var(--text-dim);
            text-transform:uppercase;letter-spacing:1px;
            text-align:center;margin-bottom:12px;
        }
        .demo-cards { display:flex;gap:6px;flex-wrap:wrap; }
        .demo-card {
            flex:1;min-width:calc(33% - 4px);
            border:1px solid rgba(255,255,255,.08);
            border-radius:10px;padding:10px 10px 8px;
            cursor:pointer;transition:all .22s;
            background:rgba(255,255,255,.03);
        }
        .demo-card:hover {
            border-color:rgba(59,130,246,.4);
            background:rgba(59,130,246,.08);
            transform:translateY(-2px);
            box-shadow:0 4px 16px rgba(59,130,246,.12);
        }
        .demo-card-role  { font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;margin-bottom:2px; }
        .demo-card-email { font-size:9px;color:var(--text-dim);white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
        .demo-card-tag   { display:inline-block;padding:1px 6px;border-radius:4px;font-size:8px;font-weight:700;margin-top:4px; }

        /* Footer */
        .form-footer { margin-top:20px;text-align:center;font-size:11px;color:var(--text-dim); }

        /* ─── Animations ──────────────────────────────────── */
        @keyframes fadeUp   { from{opacity:0;transform:translateY(24px);}to{opacity:1;transform:translateY(0);} }
        @keyframes fadeDown { from{opacity:0;transform:translateY(-12px);}to{opacity:1;transform:translateY(0);} }
        @keyframes spin     { to{transform:rotate(360deg);} }

        /* ─── Mobile Header (hidden on desktop) ──────────── */
        .mobile-header { display: none; }

        /* ─── Mobile ──────────────────────────────────────── */
        @media (max-width: 900px) {
            html, body { overflow: auto; }
            .left-panel { display: none; }

            .right-panel {
                min-height: 100vh;
                padding: 52px 20px 48px;
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
                gap: 28px;
            }

            /* ── Mobile Brand Header ── */
            .mobile-header {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 14px;
                width: 100%;
                max-width: 480px;
                position: relative;
                animation: fadeUp .7s cubic-bezier(.16,1,.3,1) both;
            }

            /* Glow behind brand icon */
            .m-glow-orb {
                position: absolute;
                width: 240px; height: 240px;
                top: -70px; left: 50%; transform: translateX(-50%);
                background: radial-gradient(circle, rgba(59,130,246,.22) 0%, rgba(139,92,246,.12) 40%, transparent 70%);
                border-radius: 50%;
                pointer-events: none;
                filter: blur(32px);
            }

            .m-brand {
                display: flex; align-items: center; gap: 14px;
                position: relative; z-index: 1;
            }

            .m-brand-icon {
                width: 56px; height: 56px; border-radius: 17px; flex-shrink: 0;
                background: linear-gradient(135deg, var(--blue), var(--purple));
                display: flex; align-items: center; justify-content: center;
                font-size: 26px; color: #fff;
                box-shadow: 0 10px 36px rgba(59,130,246,.5), 0 0 0 1px rgba(255,255,255,.12) inset;
                animation: iconGlow 3s ease-in-out infinite;
            }

            .m-brand-info { display: flex; flex-direction: column; }

            .m-brand-name {
                font-size: 26px; font-weight: 900; color: var(--text);
                letter-spacing: -.6px; line-height: 1;
            }

            .m-brand-badge {
                display: inline-block; margin-top: 5px;
                font-size: 9px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
                padding: 2px 9px; border-radius: 999px; width: fit-content;
                background: rgba(59,130,246,.15); border: 1px solid rgba(59,130,246,.3);
                color: var(--accent);
            }

            .m-tagline {
                font-size: 13px; color: var(--text-muted); text-align: center;
                max-width: 265px; line-height: 1.7;
            }

            /* Feature pills */
            .m-pills {
                display: flex; gap: 6px; flex-wrap: wrap; justify-content: center;
            }

            .m-pill {
                display: flex; align-items: center; gap: 5px;
                padding: 5px 12px; border-radius: 999px;
                background: rgba(255,255,255,.055); border: 1px solid rgba(255,255,255,.1);
                font-size: 10px; font-weight: 600; color: var(--text-muted);
                cursor: default;
            }

            .m-pill i { font-size: 11px; }

            /* Gradient divider */
            .m-divider {
                width: 44px; height: 2px; border-radius: 999px;
                background: linear-gradient(90deg, var(--blue), var(--purple), var(--cyan));
                margin-top: 2px;
            }

            /* Stats strip */
            .m-stats {
                display: flex; gap: 8px; width: 100%;
            }

            .m-stat {
                flex: 1; padding: 11px 14px; border-radius: 14px;
                background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
                text-align: center; position: relative; overflow: hidden;
            }

            .m-stat::before {
                content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
                background: linear-gradient(90deg, transparent, rgba(96,165,250,.5), transparent);
            }

            .m-stat-num  { font-size: 22px; font-weight: 900; color: var(--text); letter-spacing: -1px; line-height: 1; }
            .m-stat-plus { font-size: 14px; color: var(--accent); font-weight: 700; }
            .m-stat-label{ font-size: 9px; color: var(--text-dim); margin-top: 4px; font-weight: 600; text-transform: uppercase; letter-spacing: .8px; }

            /* Form card */
            .form-card { max-width: 480px; width: 100%; }
        }

        @media (max-width: 480px) {
            .right-panel { padding: 40px 16px 40px; gap: 22px; }
            .form-card { padding: 28px 20px; border-radius: 20px; }
            .form-title { font-size: 22px; }
            .demo-cards { flex-direction: column; }
            .demo-card { min-width: 100%; }
            .m-brand-icon { width: 50px; height: 50px; font-size: 22px; border-radius: 14px; }
            .m-brand-name { font-size: 22px; }
        }
    </style>
</head>
<body>

<!-- Aurora -->
<div class="aurora">
    <div class="aurora-orb orb-1"></div>
    <div class="aurora-orb orb-2"></div>
    <div class="aurora-orb orb-3"></div>
    <div class="aurora-orb orb-4"></div>
</div>
<div class="grid-overlay"></div>

<div class="login-wrap">

    {{-- ── LEFT PANEL ─────────────────────────────── --}}
    <div class="left-panel">
        <div class="deco-ring ring-1"></div>
        <div class="deco-ring ring-2"></div>
        <div class="deco-ring ring-3"></div>
        <div class="particles" id="particles"></div>

        {{-- Brand --}}
        <div class="left-top">
            <div class="brand">
                <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <div>
                    <div class="brand-name">Academia</div>
                    <div class="brand-badge">SIAKAD Polinema</div>
                </div>
            </div>
        </div>

        {{-- Tagline --}}
        <div class="left-mid">
            <div class="tagline">
                Dashboard<br>
                <span class="grad">Akademik</span><br>
                Terpadu
            </div>
            <p class="tagline-desc">Platform digital untuk memantau performa akademik mahasiswa, absensi, dan nilai secara real-time.</p>
            <div class="pills">
                <div class="pill"><i class="bi bi-graph-up-arrow"></i> Analitik Real-time</div>
                <div class="pill"><i class="bi bi-shield-check"></i> Aman &amp; Terenkripsi</div>
                <div class="pill"><i class="bi bi-phone"></i> Responsif</div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-num"><span class="count" data-target="{{ $mahasiswa_aktif }}">0</span><span class="stat-plus">+</span></div>
                <div class="stat-label">Mahasiswa Aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-num"><span class="count" data-target="{{ $dosen_pa }}">0</span></div>
                <div class="stat-label">DPA</div>
            </div>
            {{-- <div class="stat-card">
                <div class="stat-num"><span class="count" data-target="{{ $kelas_aktif }}">0</span></div>
                <div class="stat-label">Kelas Aktif</div>
            </div> --}}
        </div>
    </div>

    {{-- ── RIGHT PANEL ─────────────────────────────── --}}
    <div class="right-panel">

        {{-- Mobile-only brand header --}}
        <div class="mobile-header">
            <div class="m-glow-orb"></div>
            <div class="m-brand">
                <div class="m-brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <div class="m-brand-info">
                    <div class="m-brand-name">Academia</div>
                    <span class="m-brand-badge">SIAKAD Polinema</span>
                </div>
            </div>
            <p class="m-tagline">Platform digital untuk memantau performa akademik mahasiswa secara real-time.</p>
            <div class="m-pills">
                <div class="m-pill"><i class="bi bi-graph-up-arrow"></i> Analitik Real-time</div>
                <div class="m-pill"><i class="bi bi-shield-check"></i> Aman &amp; Terenkripsi</div>
                <div class="m-pill"><i class="bi bi-phone"></i> Responsif</div>
            </div>
            <div class="m-stats">
                <div class="m-stat">
                    <div class="m-stat-num"><span class="count-m" data-target="{{ $mahasiswa_aktif }}">0</span><span class="m-stat-plus">+</span></div>
                    <div class="m-stat-label">Mahasiswa Aktif</div>
                </div>
                <div class="m-stat">
                    <div class="m-stat-num"><span class="count-m" data-target="{{ $dosen_pa }}">0</span></div>
                    <div class="m-stat-label">DPA</div>
                </div>
            </div>
            <div class="m-divider"></div>
        </div>

        <div class="form-card">

            <div class="form-greeting">
                <div class="greeting-dot"></div>
                Selamat Datang
            </div>
            <div class="form-title">Masuk ke Akun Anda</div>
            <div class="form-sub">Gunakan email dan password yang terdaftar di sistem SIAKAD.</div>

            @if ($errors->any())
            <div class="alert-err">
                <i class="bi bi-exclamation-triangle-fill" style="flex-shrink:0;"></i>
                {{ $errors->first() }}
            </div>
            @endif
            @if (session('error'))
            <div class="alert-err">
                <i class="bi bi-exclamation-triangle-fill" style="flex-shrink:0;"></i>
                {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <div class="input-group">
                    <label class="input-label" for="email">Email</label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope-fill input-icon"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="form-input" placeholder="email@polinema.ac.id" autocomplete="email" required>
                    </div>
                    @error('email')<div class="input-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
                <div class="input-group">
                    <label class="input-label" for="password">Password</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input type="password" id="password" name="password"
                               class="form-input" placeholder="••••••••" autocomplete="current-password" required>
                        <button type="button" class="pw-toggle" id="pwToggle" tabindex="-1">
                            <i class="bi bi-eye-fill" id="pwIcon"></i>
                        </button>
                    </div>
                    @error('password')<div class="input-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn-login" id="loginBtn">
                    <div class="btn-bg"></div>
                    <i class="bi bi-box-arrow-in-right" id="loginIcon"></i>
                    <span id="loginText">Masuk Sekarang</span>
                </button>
            </form>

            <div class="demo-section">
                <div class="demo-label">Quick Login — Akun Demo</div>
                <div class="demo-cards">
                    <div class="demo-card" onclick="fillDemo('admin@polinema.ac.id', event)">
                        <div class="demo-card-role" style="color:#a78bfa;">Admin</div>
                        <div class="demo-card-email">admin@polinema...</div>
                        <span class="demo-card-tag" style="background:rgba(167,139,250,.15);color:#a78bfa;">Admin</span>
                    </div>
                    <div class="demo-card" onclick="fillDemo('triana.fatmawati@dosen.polinema.ac.id', event)">
                        <div class="demo-card-role" style="color:#34d399;">Dosen</div>
                        <div class="demo-card-email">triana...</div>
                        <span class="demo-card-tag" style="background:rgba(52,211,153,.15);color:#34d399;">DPA</span>
                    </div>
                    <div class="demo-card" onclick="fillDemo('elok.nur.hamdana@dosen.polinema.ac.id', event)">
                        <div class="demo-card-role" style="color:#34d399;">Dosen</div>
                        <div class="demo-card-email">elok...</div>
                        <span class="demo-card-tag" style="background:rgba(52,211,153,.15);color:#34d399;">DPA</span>
                    </div>
                    <div class="demo-card" onclick="fillDemo('dewadafug766@gmail.com', event)">
                        <div class="demo-card-role" style="color:#60a5fa;">Mahasiswa</div>
                        <div class="demo-card-email">latifbima...</div>
                        <span class="demo-card-tag" style="background:rgba(96,165,250,.15);color:#60a5fa;">Student</span>
                    </div>
                    <div class="demo-card" onclick="fillDemo('2241760122@student.polinema.ac.id', event)">
                        <div class="demo-card-role" style="color:#60a5fa;">Mahasiswa 2</div>
                        <div class="demo-card-email">chikal...</div>
                        <span class="demo-card-tag" style="background:rgba(96,165,250,.15);color:#60a5fa;">Student</span>
                    </div>
                </div>
            </div>

            <div class="form-footer">Politeknik Negeri Malang · Jurusan Teknologi Informasi</div>
        </div>
    </div>

</div>

<script>
// ── Password toggle
document.getElementById('pwToggle').addEventListener('click', function() {
    var pw = document.getElementById('password');
    var icon = document.getElementById('pwIcon');
    pw.type = pw.type === 'password' ? 'text' : 'password';
    icon.className = pw.type === 'password' ? 'bi bi-eye-fill' : 'bi bi-eye-slash-fill';
});

// ── Fill demo credentials
function fillDemo(email, e) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = 'password';
    if (e && e.currentTarget) {
        e.currentTarget.style.transform = 'scale(.95)';
        setTimeout(function() { e.currentTarget.style.transform = ''; }, 160);
    }
    document.getElementById('loginBtn').focus();
}

// ── Form submit state
document.getElementById('loginForm').addEventListener('submit', function() {
    var btn  = document.getElementById('loginBtn');
    var icon = document.getElementById('loginIcon');
    var text = document.getElementById('loginText');
    btn.style.opacity = '.7'; btn.disabled = true;
    icon.className = 'bi bi-arrow-repeat';
    icon.style.animation = 'spin .7s linear infinite';
    text.textContent = 'Memverifikasi...';
});

// ── Count-up animation
function countUp(el, target, duration) {
    var start = 0, step = target / (duration / 16);
    var iv = setInterval(function() {
        start += step;
        if (start >= target) { start = target; clearInterval(iv); }
        el.textContent = Math.round(start);
    }, 16);
}
var counters = document.querySelectorAll('.count');
var triggered = false;
var obs = new IntersectionObserver(function(entries) {
    if (entries[0].isIntersecting && !triggered) {
        triggered = true;
        counters.forEach(function(el) {
            countUp(el, parseInt(el.dataset.target), 1600);
        });
    }
}, { threshold: 0.4 });
if (counters.length) obs.observe(counters[0]);

// ── Mobile stat count-up (triggers after short delay on page load)
var mobileCounters = document.querySelectorAll('.count-m');
if (mobileCounters.length) {
    setTimeout(function() {
        mobileCounters.forEach(function(el) {
            countUp(el, parseInt(el.dataset.target), 1400);
        });
    }, 400);
}

// ── Generate floating particles
(function() {
    var c = document.getElementById('particles');
    if (!c) return;
    for (var i = 0; i < 22; i++) {
        var p = document.createElement('div');
        p.className = 'particle';
        var sz = Math.random() * 2.5 + 1;
        p.style.cssText = [
            'width:'  + sz + 'px',
            'height:' + sz + 'px',
            'left:'   + (Math.random() * 100) + '%',
            'bottom:0',
            'animation-duration:' + (Math.random() * 10 + 9)  + 's',
            'animation-delay:'    + (Math.random() * 12)       + 's'
        ].join(';');
        c.appendChild(p);
    }
})();
</script>
</body>
</html>
