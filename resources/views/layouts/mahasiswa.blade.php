<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIAKAD Polinema — @yield('title', 'Dashboard')</title>
 
    {{-- Bootstrap & Icons --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
 
    <style>
        :root {
            --navy: #0b1a35;
            --navy-mid: #132244;
            --navy-light: #1c3260;
            --accent: #e8a020;
            --accent-soft: #f5c86a;
            --teal: #00b4c8;
            --danger-red: #e8334a;
            --success-green: #28c76f;
            --warning-orange: #ff9f43;
            --sidebar-w: 260px;
            --text-muted-custom: #8da3c0;
        }
 
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0f4fc; color: #1a2b4a; min-height: 100vh; display: flex; margin: 0; }
 
        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w); background: var(--navy); position: fixed;
            top: 0; left: 0; height: 100vh; display: flex; flex-direction: column;
            z-index: 100; overflow: hidden; transition: transform .3s;
        }
        .sidebar-brand {
            padding: 22px 20px 18px; border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex; align-items: center; gap: 12px; flex-shrink: 0;
        }
        .brand-logo {
            width: 40px; height: 40px; background: var(--accent); border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Space Mono', monospace; font-weight: 700; font-size: 13px; color: var(--navy); flex-shrink: 0;
        }
        .brand-text strong { color: #fff; font-size: 12.5px; display: block; line-height: 1.3; }
        .brand-text span { color: var(--text-muted-custom); font-size: 10.5px; }
 
        .user-card {
            margin: 14px 12px; background: var(--navy-mid); border-radius: 12px;
            padding: 13px; border: 1px solid rgba(255,255,255,0.06); flex-shrink: 0;
        }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, var(--teal), var(--accent));
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: #fff; font-size: 13px; flex-shrink: 0;
        }
        .user-info strong { color: #fff; font-size: 12.5px; display: block; line-height: 1.3; }
        .user-info span { color: var(--text-muted-custom); font-size: 10.5px; }
        .badge-nim {
            background: rgba(0,180,200,0.15); color: var(--teal);
            border-radius: 6px; padding: 2px 8px; font-size: 10px;
            font-family: 'Space Mono', monospace; display: inline-block; margin-top: 6px;
        }
 
        .nav-section { padding: 0 10px; flex: 1; overflow-y: auto; padding-bottom: 10px; }
        .nav-section::-webkit-scrollbar { width: 3px; }
        .nav-section::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }
 
        .nav-label {
            color: var(--text-muted-custom); font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.5px; padding: 12px 10px 5px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px; padding: 9px 12px;
            border-radius: 10px; color: #8da3c0; text-decoration: none;
            font-size: 13px; font-weight: 500; margin-bottom: 2px; transition: all .2s;
        }
        .nav-item:hover { background: rgba(255,255,255,0.06); color: #fff; }
        .nav-item.active {
            background: linear-gradient(90deg, rgba(232,160,32,0.18), rgba(232,160,32,0.04));
            color: var(--accent); border-left: 3px solid var(--accent);
        }
        .nav-item i { font-size: 16px; width: 20px; text-align: center; }
        .nav-badge {
            margin-left: auto; background: var(--danger-red); color: #fff;
            border-radius: 50%; width: 18px; height: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: 700;
        }
        .sidebar-footer { padding: 14px 10px; border-top: 1px solid rgba(255,255,255,0.07); flex-shrink: 0; }
 
        /* ── MAIN ── */
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
 
        .topbar {
            background: #fff; border-bottom: 1px solid #e4eaf5; padding: 13px 26px;
            display: flex; align-items: center; gap: 14px;
            position: sticky; top: 0; z-index: 50;
        }
        .page-title { font-size: 16px; font-weight: 700; color: var(--navy); }
        .page-sub { font-size: 11.5px; color: #8da3c0; margin-top: 1px; }
        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 10px; }
        .topbar-btn {
            width: 35px; height: 35px; border-radius: 50%; border: 1.5px solid #e4eaf5;
            background: #fff; display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #6b7fa3; position: relative; transition: all .2s; text-decoration: none;
        }
        .topbar-btn:hover { background: var(--navy); color: #fff; border-color: var(--navy); }
        .notif-dot {
            position: absolute; top: 5px; right: 5px; width: 7px; height: 7px;
            background: var(--danger-red); border-radius: 50%; border: 2px solid #fff;
        }
 
        .content { padding: 22px 26px; flex: 1; }
 
        /* ── KOMPONEN UMUM ── */
        .stat-card {
            background: #fff; border-radius: 16px; padding: 20px;
            border: 1px solid #e8eef8; transition: transform .2s, box-shadow .2s; height: 100%;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(11,26,53,0.09); }
        .stat-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 21px; margin-bottom: 13px; }
        .stat-icon.blue  { background: rgba(0,180,200,0.12); color: var(--teal); }
        .stat-icon.gold  { background: rgba(232,160,32,0.12); color: var(--accent); }
        .stat-icon.green { background: rgba(40,199,111,0.12); color: var(--success-green); }
        .stat-icon.red   { background: rgba(232,51,74,0.12);  color: var(--danger-red); }
        .stat-icon.purple{ background: rgba(124,77,255,0.12); color: #7c4dff; }
        .stat-value { font-size: 28px; font-weight: 800; color: var(--navy); font-family: 'Space Mono', monospace; line-height: 1; }
        .stat-label { font-size: 12px; color: #8da3c0; margin-top: 5px; font-weight: 500; }
        .stat-delta { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 600; margin-top: 8px; padding: 3px 8px; border-radius: 20px; }
        .stat-delta.up   { background: rgba(40,199,111,0.12); color: var(--success-green); }
        .stat-delta.down { background: rgba(232,51,74,0.12);  color: var(--danger-red); }
        .stat-delta.warn { background: rgba(255,159,67,0.12); color: var(--warning-orange); }
 
        .section-card { background: #fff; border-radius: 16px; padding: 22px; border: 1px solid #e8eef8; margin-bottom: 22px; }
        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .section-title { font-size: 14.5px; font-weight: 700; color: var(--navy); }
        .section-subtitle { font-size: 11px; color: #8da3c0; margin-top: 2px; }
 
        .grade-badge { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 8px; font-weight: 800; font-size: 13px; font-family: 'Space Mono', monospace; }
        .grade-A { background: rgba(40,199,111,0.15); color: var(--success-green); }
        .grade-B { background: rgba(0,180,200,0.15);  color: var(--teal); }
        .grade-C { background: rgba(255,159,67,0.15); color: var(--warning-orange); }
        .grade-D { background: rgba(232,51,74,0.15);  color: var(--danger-red); }
        .grade-E { background: rgba(232,51,74,0.25);  color: var(--danger-red); }
 
        .score-pill { background: #f0f4fc; border-radius: 6px; padding: 3px 10px; font-size: 12px; font-weight: 600; color: var(--navy); font-family: 'Space Mono', monospace; }
 
        .alert-banner {
            border-radius: 13px; padding: 14px 18px; color: #fff;
            display: flex; align-items: center; gap: 14px; margin-bottom: 14px;
        }
        .alert-banner.danger { background: linear-gradient(135deg, #e8334a, #c0192d); }
        .alert-banner.warning { background: linear-gradient(135deg, #ff9f43, #e07b10); }
        .alert-banner-icon { width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
        .alert-banner h6 { font-weight: 700; margin-bottom: 2px; font-size: 13.5px; }
        .alert-banner p { font-size: 11.5px; opacity: .88; margin: 0; }
 
        .progress-thin { height: 6px; border-radius: 3px; }
 
        /* Tabel nilai */
        .nilai-table { width: 100%; border-collapse: separate; border-spacing: 0 5px; }
        .nilai-table thead th { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: #8da3c0; padding: 5px 13px; }
        .nilai-table tbody tr { background: #f8faff; }
        .nilai-table tbody td { padding: 11px 13px; font-size: 13px; }
        .nilai-table tbody td:first-child { border-radius: 10px 0 0 10px; }
        .nilai-table tbody td:last-child  { border-radius: 0 10px 10px 0; }
        .nilai-table tbody tr.warn-row td { background: rgba(232,51,74,0.05); }
        .nilai-table tbody tr.warn-row td:first-child { border-left: 3px solid var(--danger-red); }
        .matkul-name { font-weight: 600; color: var(--navy); font-size: 13px; }
        .matkul-sub  { font-size: 11px; color: #8da3c0; margin-top: 1px; }
    </style>
 
    @stack('styles')
</head>
<body>
 
{{-- SIDEBAR --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">JTI</div>
        <div class="brand-text">
            <strong>SIAKAD Polinema</strong>
            <span>Jurusan Teknologi Informasi</span>
        </div>
    </div>
 
    {{-- User Card --}}
    @auth
    @php $mhs = auth()->user()->mahasiswa ?? null; @endphp
    <div class="user-card">
        <div class="d-flex align-items-center gap-2">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="user-info">
                <strong>{{ auth()->user()->name }}</strong>
                <span>Mahasiswa Aktif</span>
            </div>
        </div>
        @if($mhs)
        <div class="mt-2">
            <span class="badge-nim">{{ $mhs->nim }}</span>
            <span style="background:rgba(255,255,255,0.08);color:#8da3c0;border-radius:6px;padding:2px 8px;font-size:10px;margin-left:4px;">
                {{ $mhs->kelas->nama ?? '-' }} • Sem {{ $mhs->kelas->semester ?? '-' }}
            </span>
        </div>
        @endif
    </div>
    @endauth
 
    <div class="nav-section">
        <div class="nav-label">Menu Utama</div>
        <a href="{{ route('mahasiswa.dashboard') }}" class="nav-item {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="{{ route('mahasiswa.nilai') }}" class="nav-item {{ request()->routeIs('mahasiswa.nilai*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Nilai Akademik
        </a>
        <a href="{{ route('mahasiswa.absensi') }}" class="nav-item {{ request()->routeIs('mahasiswa.absensi') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> Riwayat Absensi
        </a>
 
        <div class="nav-label">Akun</div>
        <a href="#" class="nav-item">
            <i class="bi bi-person-circle"></i> Profil Saya
        </a>
    </div>
 
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item w-100 border-0 bg-transparent text-start" style="color:#e8334a;cursor:pointer;">
                <i class="bi bi-box-arrow-left"></i> Keluar
            </button>
        </form>
    </div>
</aside>
 
{{-- MAIN --}}
<main class="main">
    {{-- TOPBAR --}}
    <div class="topbar">
        <div>
            <div class="page-title">@yield('page-title', 'Dashboard')</div>
            <div class="page-sub">@yield('page-sub', 'Selamat datang di SIAKAD Polinema')</div>
        </div>
        <div class="topbar-right">
            <a href="#" class="topbar-btn">
                <i class="bi bi-bell"></i>
                <div class="notif-dot"></div>
            </a>
            <a href="#" class="topbar-btn"><i class="bi bi-gear"></i></a>
        </div>
    </div>
 
    {{-- CONTENT --}}
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert" style="border-radius:12px;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
 
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert" style="border-radius:12px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
 
        @yield('content')
    </div>
</main>
 
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>