<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD Polinema — Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0b1a35;
            --navy-mid: #132244;
            --navy-light: #1c3260;
            --accent: #e8a020;
            --teal: #00b4c8;
            --danger: #e8334a;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            background: var(--navy);
            overflow: hidden;
        }

        /* ── KIRI: Branding Panel ── */
        .brand-panel {
            width: 55%;
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        /* Decorative circles */
        .brand-panel::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.04);
            top: -100px; right: -150px;
        }
        .brand-panel::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0,180,200,0.08) 0%, transparent 70%);
            bottom: 50px; left: -50px;
        }

        .brand-logo-wrap {
            display: flex; align-items: center; gap: 16px;
            margin-bottom: 60px;
        }
        .brand-logo {
            width: 52px; height: 52px;
            background: var(--accent);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Space Mono', monospace;
            font-weight: 700; font-size: 16px; color: var(--navy);
        }
        .brand-name { color: #fff; font-size: 18px; font-weight: 700; line-height: 1.3; }
        .brand-name span { color: rgba(255,255,255,0.5); font-size: 13px; font-weight: 400; display: block; }

        .brand-headline {
            font-size: 42px; font-weight: 800; color: #fff;
            line-height: 1.2; margin-bottom: 20px;
        }
        .brand-headline em {
            font-style: normal;
            background: linear-gradient(135deg, var(--accent), var(--teal));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .brand-sub {
            font-size: 15px; color: rgba(255,255,255,0.55);
            line-height: 1.7; max-width: 400px; margin-bottom: 50px;
        }

        /* Feature pills */
        .feature-list { display: flex; flex-direction: column; gap: 14px; }
        .feature-item {
            display: flex; align-items: center; gap: 14px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 12px; padding: 14px 18px;
            transition: background .2s;
        }
        .feature-item:hover { background: rgba(255,255,255,0.07); }
        .feature-icon {
            width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .feature-text strong { color: #fff; font-size: 13px; display: block; }
        .feature-text span { color: rgba(255,255,255,0.45); font-size: 11.5px; }

        /* ── KANAN: Login Form ── */
        .login-panel {
            width: 45%;
            background: #f0f4fc;
            display: flex; align-items: center; justify-content: center;
            padding: 40px;
        }

        .login-box {
            width: 100%; max-width: 400px;
        }

        .login-header { margin-bottom: 36px; }
        .login-header h2 { font-size: 26px; font-weight: 800; color: var(--navy); margin-bottom: 6px; }
        .login-header p { font-size: 13.5px; color: #8da3c0; }

        .form-group { margin-bottom: 18px; }
        .form-label {
            font-size: 12px; font-weight: 700; color: #5a6e8c;
            text-transform: uppercase; letter-spacing: .8px;
            display: block; margin-bottom: 8px;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #8da3c0; font-size: 16px; pointer-events: none;
        }
        .form-input {
            width: 100%;
            border: 1.5px solid #e4eaf5;
            border-radius: 12px;
            padding: 12px 14px 12px 42px;
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--navy);
            background: #fff;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-input:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 4px rgba(0,180,200,0.1);
        }
        .form-input.is-invalid { border-color: var(--danger); }

        .password-toggle {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            color: #8da3c0; cursor: pointer; font-size: 16px;
            transition: color .2s;
        }
        .password-toggle:hover { color: var(--navy); }

        .remember-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 24px;
        }
        .remember-label {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: #5a6e8c; cursor: pointer;
        }
        .remember-label input[type="checkbox"] {
            width: 16px; height: 16px; accent-color: var(--teal);
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, var(--navy), var(--navy-light));
            color: #fff; border: none; border-radius: 12px;
            padding: 14px; font-size: 15px; font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer; transition: all .3s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, var(--navy-light), var(--teal));
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(11,26,53,0.3);
        }
        .btn-login:active { transform: translateY(0); }

        /* Role selector */
        .role-pills {
            display: flex; gap: 8px; margin-bottom: 28px;
        }
        .role-pill {
            flex: 1; padding: 8px; border-radius: 10px;
            border: 1.5px solid #e4eaf5; background: #fff;
            text-align: center; cursor: pointer; transition: all .2s;
        }
        .role-pill:hover { border-color: var(--teal); }
        .role-pill.active { border-color: var(--navy); background: var(--navy); }
        .role-pill .role-icon { font-size: 20px; display: block; margin-bottom: 3px; }
        .role-pill .role-name {
            font-size: 11px; font-weight: 700;
            color: #8da3c0; text-transform: uppercase; letter-spacing: .5px;
        }
        .role-pill.active .role-name { color: var(--accent); }

        /* Demo accounts */
        .demo-accounts {
            margin-top: 28px;
            background: #fff;
            border: 1px solid #e4eaf5;
            border-radius: 12px;
            padding: 16px;
        }
        .demo-title {
            font-size: 11px; font-weight: 700; color: #8da3c0;
            text-transform: uppercase; letter-spacing: .8px;
            margin-bottom: 12px;
        }
        .demo-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 8px 0; border-bottom: 1px dashed #e8eef8;
            cursor: pointer; transition: all .2s;
        }
        .demo-item:last-child { border-bottom: none; padding-bottom: 0; }
        .demo-item:hover .demo-email { color: var(--teal); }
        .demo-role {
            font-size: 11px; font-weight: 700; padding: 2px 10px;
            border-radius: 20px;
        }
        .demo-role.admin    { background: rgba(124,77,255,0.1); color: #7c4dff; }
        .demo-role.dosen    { background: rgba(0,180,200,0.1);  color: var(--teal); }
        .demo-role.mahasiswa{ background: rgba(232,160,32,0.1); color: var(--accent); }
        .demo-email { font-size: 12px; color: #5a6e8c; font-family: 'Space Mono', monospace; }
        .demo-pass  { font-size: 11px; color: #b0c0d8; }

        .error-alert {
            background: rgba(232,51,74,0.08);
            border: 1px solid rgba(232,51,74,0.2);
            border-radius: 10px; padding: 12px 14px;
            color: var(--danger); font-size: 13px;
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 18px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .brand-panel { display: none; }
            .login-panel { width: 100%; }
        }
    </style>
</head>
<body>

{{-- KIRI: Branding --}}
<div class="brand-panel">
    <div class="brand-logo-wrap">
        <div class="brand-logo">JTI</div>
        <div class="brand-name">
            SIAKAD Polinema
            <span>Jurusan Teknologi Informasi</span>
        </div>
    </div>

    <h1 class="brand-headline">
        Sistem Informasi<br>
        Akademik <em>Terpadu</em>
    </h1>
    <p class="brand-sub">
        Platform monitoring akademik mahasiswa Jurusan Teknologi Informasi
        Politeknik Negeri Malang. Pantau nilai, absensi, dan performa secara real-time.
    </p>

    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon" style="background:rgba(232,160,32,0.15);">📊</div>
            <div class="feature-text">
                <strong>Monitoring Nilai Real-time</strong>
                <span>Tugas, UTS, UAS — IP & IPK otomatis terhitung</span>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon" style="background:rgba(0,180,200,0.15);">📅</div>
            <div class="feature-text">
                <strong>Tracking Absensi Lengkap</strong>
                <span>Hadir, izin, sakit, alpha — peringatan otomatis ≥18 jam</span>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon" style="background:rgba(232,51,74,0.15);">🚨</div>
            <div class="feature-text">
                <strong>Deteksi Dini Mahasiswa Berisiko</strong>
                <span>Notifikasi otomatis nilai D/E untuk DPA</span>
            </div>
        </div>
    </div>
</div>

{{-- KANAN: Form Login --}}
<div class="login-panel">
    <div class="login-box">

        <div class="login-header">
            <h2>Selamat Datang 👋</h2>
            <p>Masuk ke akun Anda untuk mengakses SIAKAD</p>
        </div>

        {{-- Error --}}
        @if($errors->any())
        <div class="error-alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $errors->first() }}
        </div>
        @endif

        @if(session('status'))
        <div style="background:rgba(40,199,111,0.08);border:1px solid rgba(40,199,111,0.2);border-radius:10px;padding:12px 14px;color:#28c76f;font-size:13px;margin-bottom:18px;">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label">Email</label>
                <div class="input-wrap">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           placeholder="email@polinema.ac.id"
                           required autofocus autocomplete="email">
                </div>
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrap">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" id="passwordInput"
                           class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                           placeholder="••••••••"
                           required autocomplete="current-password">
                    <i class="bi bi-eye password-toggle" id="togglePassword"></i>
                </div>
            </div>

            {{-- Remember + Forgot --}}
            <div class="remember-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Ingat saya
                </label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   style="font-size:13px;color:var(--teal);text-decoration:none;font-weight:600;">
                    Lupa password?
                </a>
                @endif
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i>
                Masuk ke SIAKAD
            </button>
        </form>

        {{-- Demo Accounts --}}
        <div class="demo-accounts">
            <div class="demo-title">🔑 Akun Demo — Klik untuk isi otomatis</div>
            <div class="demo-item" onclick="fillLogin('admin@polinema.ac.id','password')">
                <span class="demo-role admin">Admin</span>
                <span class="demo-email">admin@polinema.ac.id</span>
                <span class="demo-pass">password</span>
            </div>
            <div class="demo-item" onclick="fillLogin('budi.santoso@polinema.ac.id','password')">
                <span class="demo-role dosen">Dosen</span>
                <span class="demo-email">budi.santoso@polinema.ac.id</span>
                <span class="demo-pass">password</span>
            </div>
            <div class="demo-item" onclick="fillLogin('kinata@student.polinema.ac.id','password')">
                <span class="demo-role mahasiswa">Mahasiswa</span>
                <span class="demo-email">kinata@student.polinema.ac.id</span>
                <span class="demo-pass">password</span>
            </div>
        </div>

    </div>
</div>

<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('passwordInput');
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        this.className = isPassword ? 'bi bi-eye-slash password-toggle' : 'bi bi-eye password-toggle';
    });

    // Auto-fill demo accounts
    function fillLogin(email, password) {
        document.querySelector('input[name="email"]').value = email;
        document.querySelector('input[name="password"]').value = password;

        // Highlight effect
        document.querySelectorAll('.demo-item').forEach(el => el.style.background = '');
        event.currentTarget.style.background = 'rgba(0,180,200,0.05)';
        event.currentTarget.style.borderRadius = '8px';

        // Auto submit setelah 300ms
        setTimeout(() => {
            document.querySelector('form').submit();
        }, 400);
    }
</script>

</body>
</html>