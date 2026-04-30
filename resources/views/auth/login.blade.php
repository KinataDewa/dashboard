<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academia — Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Sora:wght@700;800&display=swap" rel="stylesheet">
    <style>
        /* ══ RESET & BASE ══════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
 
        :root {
            --blue:     #2563EB;
            --blue-d:   #1D4ED8;
            --blue-l:   #EFF6FF;
            --dark:     #0A0F1E;
            --dark-2:   #111827;
            --dark-3:   #1F2937;
            --white:    #FFFFFF;
            --gray-1:   #F9FAFB;
            --gray-2:   #F3F4F6;
            --gray-3:   #E5E7EB;
            --text-1:   #111827;
            --text-2:   #6B7280;
            --text-3:   #9CA3AF;
        }
 
        html, body {
            height: 100%; overflow: hidden;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
 
        /* ══ LAYOUT SPLIT ══════════════════════════════════════ */
        .login-wrap {
            display: flex;
            height: 100vh;
        }
 
        /* ══ LEFT PANEL — Dark geometric ══════════════════════ */
        .left-panel {
            width: 48%;
            background: var(--dark);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 52px;
            flex-shrink: 0;
        }
 
        /* Grid dots background */
        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle, rgba(37,99,235,.15) 1px, transparent 1px);
            background-size: 32px 32px;
            animation: gridFloat 20s ease infinite;
        }
 
        @keyframes gridFloat {
            0%, 100% { background-position: 0 0; }
            50% { background-position: 16px 16px; }
        }
 
        /* Glow blob animasi */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            animation: blobPulse 8s ease-in-out infinite;
        }
        .blob-1 {
            width: 380px; height: 380px;
            top: -100px; left: -100px;
            background: radial-gradient(circle, rgba(37,99,235,.35), transparent 70%);
            animation-delay: 0s;
        }
        .blob-2 {
            width: 280px; height: 280px;
            bottom: 80px; right: -60px;
            background: radial-gradient(circle, rgba(99,102,241,.3), transparent 70%);
            animation-delay: -3s;
        }
        .blob-3 {
            width: 200px; height: 200px;
            bottom: -60px; left: 120px;
            background: radial-gradient(circle, rgba(6,182,212,.2), transparent 70%);
            animation-delay: -6s;
        }
        @keyframes blobPulse {
            0%, 100% { transform: scale(1) translate(0, 0); opacity: .8; }
            33%  { transform: scale(1.1) translate(10px, -15px); opacity: 1; }
            66%  { transform: scale(.95) translate(-8px, 10px); opacity: .7; }
        }
 
        /* Geometric lines */
        .geo-lines {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .geo-line {
            position: absolute;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.04), transparent);
            height: 1px;
            width: 100%;
            animation: lineScan 6s ease-in-out infinite;
        }
        .geo-line:nth-child(1) { top: 20%; animation-delay: 0s; }
        .geo-line:nth-child(2) { top: 45%; animation-delay: -2s; }
        .geo-line:nth-child(3) { top: 70%; animation-delay: -4s; }
        @keyframes lineScan {
            0%, 100% { opacity: 0; transform: translateX(-100%); }
            50% { opacity: 1; transform: translateX(0); }
        }
 
        /* Floating cards — dihapus */
 
        /* Left content */
        .left-top {
            position: relative; z-index: 10;
            animation: slideDown .7s cubic-bezier(.16,1,.3,1) both;
        }
        .brand {
            font-size: 26px; font-weight: 900;
            color: #fff; letter-spacing: -.5px;
            display: flex; align-items: center; gap: 10px;
        }
        .brand-dot {
            width: 10px; height: 10px; border-radius: 50%;
            background: var(--blue);
            box-shadow: 0 0 12px var(--blue);
            animation: dotPulse 2s ease infinite;
        }
        @keyframes dotPulse {
            0%, 100% { box-shadow: 0 0 8px var(--blue); transform: scale(1); }
            50% { box-shadow: 0 0 20px var(--blue), 0 0 40px rgba(37,99,235,.3); transform: scale(1.15); }
        }
 
        .left-mid {
            position: relative; z-index: 10;
            animation: slideUp .8s cubic-bezier(.16,1,.3,1) .15s both;
        }
        .left-tagline {
            font-family: 'Sora', sans-serif;
            font-size: 42px;
            font-weight: 800;
            font-style: normal;
            color: #fff;
            line-height: 1.15;
            letter-spacing: -1.5px;
            margin-bottom: 16px;
        }
        .left-tagline span {
            background: linear-gradient(135deg, #60A5FA, #818CF8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .left-desc {
            font-size: 14px;
            color: rgba(255,255,255,.55);
            line-height: 1.7;
            max-width: 320px;
        }
 
        /* Stats strip */
        .stats-strip {
            position: relative; z-index: 10;
            display: flex; gap: 28px;
            animation: slideUp .8s cubic-bezier(.16,1,.3,1) .3s both;
        }
        .stat-item {}
        .stat-num {
            font-size: 28px; font-weight: 800;
            color: #fff; letter-spacing: -1px;
            line-height: 1;
        }
        .stat-num span {
            font-size: 16px; font-weight: 600;
            color: #60A5FA;
        }
        .stat-label {
            font-size: 11px; color: rgba(255,255,255,.45);
            margin-top: 3px; font-weight: 500;
        }
        .stat-divider {
            width: 1px;
            background: rgba(255,255,255,.1);
        }
 
        /* ══ RIGHT PANEL — Login form ══════════════════════════ */
        .right-panel {
            flex: 1;
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }
 
        /* Subtle background texture */
        .right-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 20%, rgba(37,99,235,.04) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 80%, rgba(99,102,241,.03) 0%, transparent 60%);
            pointer-events: none;
        }
 
        .form-container {
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 1;
            animation: slideUp .7s cubic-bezier(.16,1,.3,1) .2s both;
        }
 
        .form-header { margin-bottom: 36px; }
        .form-greeting {
            font-size: 13px; font-weight: 600;
            color: var(--blue); letter-spacing: .5px;
            text-transform: uppercase;
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 10px;
        }
        .form-greeting::before {
            content: '';
            width: 24px; height: 2px;
            background: var(--blue); border-radius: 2px;
        }
        .form-title {
            font-size: 32px; font-weight: 800;
            color: var(--text-1); letter-spacing: -.8px;
            line-height: 1.15; margin-bottom: 8px;
        }
        .form-sub {
            font-size: 14px; color: var(--text-2);
            line-height: 1.5;
        }
 
        /* Input group */
        .input-group {
            margin-bottom: 18px;
        }
        .input-label {
            display: block;
            font-size: 12px; font-weight: 700;
            color: var(--text-1);
            margin-bottom: 7px;
            text-transform: uppercase; letter-spacing: .6px;
        }
        .input-wrap {
            position: relative;
        }
        .input-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            font-size: 15px; color: var(--text-3);
            transition: color .2s; pointer-events: none;
        }
        .form-input {
            width: 100%;
            border: 1.5px solid var(--gray-3);
            border-radius: 10px;
            padding: 12px 14px 12px 42px;
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-1);
            background: var(--gray-1);
            outline: none;
            transition: all .2s;
        }
        .form-input:focus {
            border-color: var(--blue);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(37,99,235,.08);
        }
        .form-input:focus + .input-focus-line { width: 100%; }
        .form-input::placeholder { color: var(--text-3); }
 
        /* Password toggle */
        .pw-toggle {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            cursor: pointer; color: var(--text-3);
            font-size: 15px;
            transition: color .2s;
            background: none; border: none; padding: 0;
        }
        .pw-toggle:hover { color: var(--blue); }
 
        /* Focus effect on icon */
        .input-wrap:focus-within .input-icon { color: var(--blue); }
 
        /* Error */
        .input-error {
            font-size: 12px; color: #EF4444;
            margin-top: 5px; display: flex; align-items: center; gap: 4px;
        }
 
        /* Submit button */
        .btn-login {
            width: 100%;
            background: var(--blue);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-size: 15px; font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all .2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-top: 8px;
        }
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.15), transparent);
            transition: left .4s;
        }
        .btn-login:hover {
            background: var(--blue-d);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(37,99,235,.3);
        }
        .btn-login:hover::before { left: 100%; }
        .btn-login:active { transform: translateY(0); }
 
        /* Demo accounts */
        .demo-section {
            margin-top: 28px;
            padding-top: 24px;
            border-top: 1px solid var(--gray-3);
        }
        .demo-label {
            font-size: 11.5px; font-weight: 700;
            color: var(--text-3); text-transform: uppercase;
            letter-spacing: .8px; text-align: center;
            margin-bottom: 12px;
        }
        .demo-cards {
            display: flex; gap: 8px;
        }
        .demo-card {
            flex: 1;
            border: 1.5px solid var(--gray-3);
            border-radius: 10px;
            padding: 10px 12px;
            cursor: pointer;
            transition: all .2s;
            background: var(--white);
        }
        .demo-card:hover {
            border-color: var(--blue);
            background: var(--blue-l);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37,99,235,.1);
        }
        .demo-card-role {
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .8px;
            margin-bottom: 3px;
        }
        .demo-card-email {
            font-size: 11px; color: var(--text-2);
            white-space: nowrap; overflow: hidden;
            text-overflow: ellipsis;
        }
        .demo-card-tag {
            display: inline-block;
            padding: 1px 6px; border-radius: 4px;
            font-size: 9px; font-weight: 700;
            margin-top: 4px;
        }
 
        /* Footer */
        .form-footer {
            margin-top: 28px;
            text-align: center;
            font-size: 12px; color: var(--text-3);
        }
        .form-footer a {
            color: var(--blue); font-weight: 600; text-decoration: none;
        }
 
        /* ══ ANIMASI ══ */
        @keyframes slideDown {
            from { opacity:0; transform: translateY(-20px); }
            to   { opacity:1; transform: translateY(0); }
        }
        @keyframes slideUp {
            from { opacity:0; transform: translateY(20px); }
            to   { opacity:1; transform: translateY(0); }
        }
 
        /* ══ ALERT ══ */
        .alert-err {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-left: 3px solid #EF4444;
            border-radius: 9px;
            padding: 11px 14px;
            font-size: 13px; color: #991B1B;
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 20px;
            animation: slideDown .3s ease both;
        }
 
        /* ══ RESPONSIVE ══ */
        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel {
                background: linear-gradient(135deg, #0A0F1E, #1E3A8A);
                align-items: flex-start;
                padding-top: 60px;
            }
            .right-panel::before { display: none; }
            .form-container {
                max-width: 420px;
            }
            .form-title { color: #fff; }
            .form-sub   { color: rgba(255,255,255,.6); }
            .form-greeting { color: #60A5FA; }
            .form-greeting::before { background: #60A5FA; }
            .input-label { color: rgba(255,255,255,.8); }
            .form-input {
                background: rgba(255,255,255,.08);
                border-color: rgba(255,255,255,.15);
                color: #fff;
            }
            .form-input::placeholder { color: rgba(255,255,255,.3); }
            .form-input:focus {
                background: rgba(255,255,255,.12);
                border-color: #60A5FA;
                box-shadow: 0 0 0 4px rgba(96,165,250,.15);
            }
            .demo-section { border-color: rgba(255,255,255,.1); }
            .demo-label { color: rgba(255,255,255,.4); }
            .demo-card {
                background: rgba(255,255,255,.06);
                border-color: rgba(255,255,255,.12);
            }
            .demo-card:hover {
                background: rgba(255,255,255,.12);
                border-color: #60A5FA;
            }
            .demo-card-role { color: rgba(255,255,255,.7); }
            .demo-card-email { color: rgba(255,255,255,.5); }
            .form-footer { color: rgba(255,255,255,.4); }
            .form-footer a { color: #60A5FA; }
            .pw-toggle { color: rgba(255,255,255,.4); }
            .input-icon { color: rgba(255,255,255,.3); }
 
            /* Tambah brand di mobile */
            .mobile-brand {
                display: flex !important;
            }
        }
 
        .mobile-brand {
            display: none;
            align-items: center; gap: 8px;
            font-size: 22px; font-weight: 900;
            color: #fff; margin-bottom: 32px;
        }
        .mobile-brand-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: #60A5FA;
            box-shadow: 0 0 10px #60A5FA;
        }
 
        @media (max-width: 480px) {
            .right-panel { padding: 40px 24px; }
            .demo-cards { flex-direction: column; }
            .left-tagline { font-size: 36px; }
        }
    </style>
</head>
<body>
 
<div class="login-wrap">
 
    {{-- ══ LEFT PANEL ══ --}}
    <div class="left-panel">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
        <div class="geo-lines">
            <div class="geo-line"></div>
            <div class="geo-line"></div>
            <div class="geo-line"></div>
        </div>
 
        
 
        <div class="left-top">
            <div class="brand">
                <div class="brand-dot"></div>
                Academia
            </div>
        </div>
 
        <div class="left-mid">
            <div class="left-tagline">
                Sistem<br>
                Informasi<br>
                <span>Akademik</span><br>
                Terpadu
            </div>
            <p class="left-desc">
                Platform digital untuk memantau performa akademik mahasiswa, absensi, dan nilai secara real-time.
            </p>
        </div>
 
        <div class="stats-strip">
            <div class="stat-item">
                <div class="stat-num">21<span>+</span></div>
                <div class="stat-label">Mahasiswa Aktif</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <div class="stat-num">7</div>
                <div class="stat-label">Mata Kuliah</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <div class="stat-num">6</div>
                <div class="stat-label">Dosen PA</div>
            </div>
        </div>
    </div>
 
    {{-- ══ RIGHT PANEL ══ --}}
    <div class="right-panel">
        <div class="form-container">
 
            {{-- Mobile brand --}}
            <div class="mobile-brand">
                <div class="mobile-brand-dot"></div>
                Academia
            </div>
 
            <div class="form-header">
                <div class="form-greeting">Selamat Datang</div>
                <div class="form-title">Masuk ke Akun Anda</div>
                <div class="form-sub">Gunakan email dan password yang terdaftar di sistem SIAKAD.</div>
            </div>
 
            {{-- Error --}}
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
 
            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
 
                <div class="input-group">
                    <label class="input-label" for="email">Email</label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope-fill input-icon"></i>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               class="form-input"
                               placeholder="email@polinema.ac.id"
                               autocomplete="email"
                               required>
                    </div>
                    @error('email')
                    <div class="input-error">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                    </div>
                    @enderror
                </div>
 
                <div class="input-group">
                    <label class="input-label" for="password">Password</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input type="password" id="password" name="password"
                               class="form-input"
                               placeholder="••••••••"
                               autocomplete="current-password"
                               required>
                        <button type="button" class="pw-toggle" id="pwToggle" tabindex="-1">
                            <i class="bi bi-eye-fill" id="pwIcon"></i>
                        </button>
                    </div>
                    @error('password')
                    <div class="input-error">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                    </div>
                    @enderror
                </div>
 
                <button type="submit" class="btn-login" id="loginBtn">
                    <i class="bi bi-box-arrow-in-right" id="loginIcon"></i>
                    <span id="loginText">Masuk Sekarang</span>
                </button>
            </form>
 
            {{-- Demo accounts --}}
            <div class="demo-section">
                <div class="demo-label">Quick Login — Akun Demo</div>
                <div class="demo-cards">
                    <div class="demo-card" onclick="fillDemo('admin@polinema.ac.id')">
                        <div class="demo-card-role" style="color:#7C3AED;">Admin</div>
                        <div class="demo-card-email">admin@polinema.ac.id</div>
                        <span class="demo-card-tag" style="background:#F5F3FF;color:#7C3AED;">Admin Jurusan</span>
                    </div>
                    <div class="demo-card" onclick="fillDemo('budi.santoso@polinema.ac.id')">
                        <div class="demo-card-role" style="color:#16A34A;">Dosen</div>
                        <div class="demo-card-email">budi.santoso@...</div>
                        <span class="demo-card-tag" style="background:#F0FDF4;color:#16A34A;">Dosen PA</span>
                    </div>
                    <div class="demo-card" onclick="fillDemo('kinata@student.polinema.ac.id')">
                        <div class="demo-card-role" style="color:#2563EB;">Mahasiswa</div>
                        <div class="demo-card-email">kinatadas@student...</div>
                        <span class="demo-card-tag" style="background:#EFF6FF;color:#2563EB;">Student</span>
                    </div>
                </div>
            </div>
 
            <div class="form-footer">
                Politeknik Negeri Malang · Jurusan Teknologi Informasi
            </div>
        </div>
    </div>
 
</div>
 
<script>
// ── Password toggle ──────────────────────────────────
document.getElementById('pwToggle').addEventListener('click', function() {
    var pw   = document.getElementById('password');
    var icon = document.getElementById('pwIcon');
    if (pw.type === 'password') {
        pw.type = 'text';
        icon.className = 'bi bi-eye-slash-fill';
    } else {
        pw.type = 'password';
        icon.className = 'bi bi-eye-fill';
    }
});
 
// ── Demo autofill ────────────────────────────────────
function fillDemo(email) {
    document.getElementById('email').value    = email;
    document.getElementById('password').value = 'password';
 
    // Animasi card yang diklik
    event.currentTarget.style.transform = 'scale(.97)';
    setTimeout(function() {
        event.currentTarget.style.transform = '';
    }, 150);
 
    // Focus tombol login
    document.getElementById('loginBtn').focus();
}
 
// ── Submit animation ─────────────────────────────────
document.getElementById('loginForm').addEventListener('submit', function() {
    var btn  = document.getElementById('loginBtn');
    var icon = document.getElementById('loginIcon');
    var text = document.getElementById('loginText');
 
    btn.style.opacity  = '.8';
    btn.disabled       = true;
    icon.className     = 'bi bi-arrow-repeat';
    icon.style.animation = 'spin .7s linear infinite';
    text.textContent   = 'Memverifikasi...';
});
 
// ── Spin keyframe ─────────────────────────────────────
var s = document.createElement('style');
s.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
document.head.appendChild(s);
</script>
</body>
</html>