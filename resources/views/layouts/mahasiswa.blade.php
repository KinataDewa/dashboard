<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Academia — @yield('title', 'Dashboard')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <style>
        /* ══════════════════════════════════════════
           ROOT TOKENS
        ══════════════════════════════════════════ */
        :root {
            --blue:       #2563EB;
            --blue-hover: #1D4ED8;
            --blue-light: #EFF6FF;
            --blue-mid:   #BFDBFE;
            --bg:         #F1F5F9;
            --white:      #FFFFFF;
            --sidebar-w:  240px;
            --text-1:     #0F172A;
            --text-2:     #64748B;
            --text-3:     #94A3B8;
            --border:     #E2E8F0;
            --radius:     12px;
            --radius-sm:  8px;
            --shadow-sm:  0 1px 2px rgba(0,0,0,.05);
            --shadow:     0 1px 3px rgba(0,0,0,.07), 0 1px 2px rgba(0,0,0,.05);
            --shadow-md:  0 4px 12px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.04);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text-1);
            min-height: 100vh;
            display: flex;
            font-size: 14px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* ══════════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--white);
            border-right: 1px solid var(--border);
            position: fixed; top: 0; left: 0; height: 100vh;
            display: flex; flex-direction: column;
            z-index: 300;
            transition: transform .25s ease;
        }

        .sidebar-header {
            padding: 24px 20px 20px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-brand {
            font-size: 20px; font-weight: 800;
            color: var(--blue); letter-spacing: -.4px;
            text-decoration: none; display: block;
        }

        .sidebar-nav { padding: 16px 12px; flex: 1; overflow-y: auto; }
        .sidebar-nav::-webkit-scrollbar { width: 0; }

        .nav-label {
            font-size: 10px; font-weight: 700; color: var(--text-3);
            text-transform: uppercase; letter-spacing: 1px;
            padding: 8px 8px 4px; display: block;
        }

        .nav-link-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 10px; border-radius: var(--radius-sm);
            color: var(--text-2); font-size: 13.5px; font-weight: 500;
            text-decoration: none; margin-bottom: 2px;
            transition: all .15s ease; position: relative;
        }
        .nav-link-item i {
            font-size: 16px; width: 20px; text-align: center;
            flex-shrink: 0;
        }
        .nav-link-item:hover {
            background: var(--blue-light);
            color: var(--blue);
        }
        .nav-link-item.active {
            background: var(--blue-light);
            color: var(--blue);
            font-weight: 600;
        }
        .nav-link-item.active::before {
            content: '';
            position: absolute; left: -12px; top: 7px; bottom: 7px;
            width: 3px; background: var(--blue);
            border-radius: 0 3px 3px 0;
        }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid var(--border);
        }

        /* Overlay mobile */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.3); z-index: 299;
        }

        /* ══════════════════════════════════════════
           MAIN AREA
        ══════════════════════════════════════════ */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1; display: flex; flex-direction: column;
            min-height: 100vh; min-width: 0;
        }

        /* ══════════════════════════════════════════
           TOPBAR
        ══════════════════════════════════════════ */
        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0; z-index: 200;
            gap: 16px;
        }


        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            flex: 1;
        }

        .hamburger {
            display: none; width: 36px; height: 36px;
            border: 1px solid var(--border); border-radius: var(--radius-sm);
            background: var(--white); cursor: pointer;
            align-items: center; justify-content: center;
            color: var(--text-2); font-size: 16px;
            flex-shrink: 0;
        }

        .topbar-title {
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .topbar-title h2 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-1);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0;
            line-height: 1.3;
        }
        .topbar-title p {
            font-size: 12px;
            color: var(--text-2);
            margin: 2px 0 0 0;
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .topbar-right {
            display: flex; align-items: center; gap: 10px;
            flex-shrink: 0;
        }

        /* Notif */
        .notif-wrap { position: relative; }
        .notif-btn {
            width: 38px; height: 38px; border-radius: 50%;
            background: var(--bg); border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--text-2); font-size: 15px;
            transition: all .15s; flex-shrink: 0;
        }
        .notif-btn:hover { background: var(--blue-light); color: var(--blue); border-color: var(--blue-mid); }
        .notif-badge {
            position: absolute; top: 4px; right: 4px;
            min-width: 16px; height: 16px; padding: 0 4px;
            background: #EF4444; color: #fff;
            border-radius: 20px; font-size: 10px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--white); line-height: 1;
        }

        /* Notif dropdown */
        .notif-panel {
            position: absolute; top: calc(100% + 10px); right: 0;
            width: 340px; background: var(--white);
            border: 1px solid var(--border); border-radius: var(--radius);
            box-shadow: var(--shadow-md); z-index: 400;
            display: none; overflow: hidden;
        }
        .notif-panel.open { display: block; animation: scaleIn .15s ease; }
        .notif-panel-head {
            padding: 14px 16px 12px;
            border-bottom: 1px solid var(--border);
            font-size: 14px; font-weight: 700; color: var(--text-1);
        }
        .notif-panel-body { padding: 8px; max-height: 300px; overflow-y: auto; }
        .notif-entry {
            display: flex; gap: 10px; padding: 10px;
            border-radius: var(--radius-sm); margin-bottom: 4px;
            background: #FEF2F2;
        }
        .notif-entry:last-child { margin-bottom: 0; }
        .notif-icon-wrap {
            width: 34px; height: 34px; border-radius: 50%;
            border: 1.5px solid #FECACA; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            color: #EF4444; font-size: 14px; margin-top: 1px;
        }
        .notif-entry-text { font-size: 12.5px; color: var(--text-1); line-height: 1.55; }
        .notif-entry-text strong { font-weight: 600; }
        .notif-panel-foot {
            padding: 10px 16px;
            border-top: 1px solid var(--border);
            text-align: center;
            font-size: 12.5px; color: var(--blue);
            cursor: pointer; font-weight: 600;
            transition: background .15s;
        }
        .notif-panel-foot:hover { background: var(--blue-light); }

        /* User pill */
        .user-pill {
            display: flex; align-items: center; gap: 8px;
            padding: 5px 10px 5px 5px;
            border: 1px solid var(--border); border-radius: 40px;
            background: var(--white); cursor: pointer;
            transition: border-color .15s; flex-shrink: 0;
        }
        .user-pill:hover { border-color: var(--blue-mid); }
        .user-ava {
            width: 30px; height: 30px; border-radius: 50%;
            background: var(--blue); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; flex-shrink: 0;
        }
        .user-info-wrap { line-height: 1.2; }
        .user-info-name { font-size: 12.5px; font-weight: 600; color: var(--text-1); }
        .user-info-role { font-size: 11px; color: var(--text-2); }
        .user-pill i { color: var(--text-3); font-size: 11px; }

        /* ══════════════════════════════════════════
           PAGE CONTENT
        ══════════════════════════════════════════ */
        .page-content { padding: 24px; flex: 1; }

        /* Flash messages */
        .flash {
            display: flex; align-items: center; gap: 8px;
            padding: 10px 14px; border-radius: var(--radius-sm);
            font-size: 13px; margin-bottom: 18px;
        }
        .flash-ok  { background: #F0FDF4; border: 1px solid #BBF7D0; color: #15803D; }
        .flash-err { background: #FEF2F2; border: 1px solid #FECACA; color: #DC2626; }

        /* ══════════════════════════════════════════
           CARDS
        ══════════════════════════════════════════ */
        .card-white {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        /* Stat cards */
        .stat-card { padding: 20px; position: relative; overflow: hidden; }
        .stat-deco {
            position: absolute; right: -8px; top: -8px;
            width: 72px; height: 72px; opacity: .07;
            pointer-events: none;
        }
        .stat-tag {
            font-size: 12px; font-weight: 600; color: var(--text-2);
            margin-bottom: 10px;
        }
        .stat-num {
            font-size: 34px; font-weight: 800; line-height: 1;
            letter-spacing: -1.5px; color: var(--text-1);
        }
        .stat-num.c-blue { color: var(--blue); }
        .stat-num.c-red  { color: #EF4444; }
        .stat-unit {
            font-size: 15px; font-weight: 700; color: var(--text-2);
            margin-left: 3px;
        }
        .stat-note {
            font-size: 12px; color: var(--text-2); margin-top: 8px;
            display: flex; align-items: center; gap: 4px;
        }

        /* Chart card */
        .chart-card { padding: 20px; }
        .chart-head {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 18px;
        }
        .chart-head-title { font-size: 15px; font-weight: 700; color: var(--text-1); }

        /* Table card */
        .tbl-card { padding: 20px; }
        .tbl-head {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 14px; gap: 10px; flex-wrap: wrap;
        }
        .tbl-title { font-size: 15px; font-weight: 700; color: var(--text-1); }
        .tbl-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

        /* Table itself */
        .ac-table { width: 100%; border-collapse: collapse; }
        .ac-table thead th {
            font-size: 11.5px; font-weight: 600; color: var(--text-2);
            padding: 8px 12px 10px; text-align: left;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        .ac-table tbody tr { border-bottom: 1px solid var(--border); transition: background .12s; }
        .ac-table tbody tr:last-child { border-bottom: none; }
        .ac-table tbody tr:hover { background: #FAFBFC; }
        .ac-table tbody td { padding: 12px 12px; font-size: 13.5px; color: var(--text-1); }
        .ac-table tbody td.muted { color: var(--text-2); }

        /* Badges */
        .badge {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 3px 11px; border-radius: 20px;
            font-size: 12px; font-weight: 600; white-space: nowrap;
        }
        .badge-green  { background: #DCFCE7; color: #166534; }
        .badge-red    { background: #FEE2E2; color: #991B1B; }
        .badge-yellow { background: #FEF9C3; color: #854D0E; }
        .badge-blue   { background: #DBEAFE; color: #1E40AF; }
        .badge-gray   { background: #F1F5F9; color: #475569; }

        /* ══════════════════════════════════════════
           SEARCH BOX
        ══════════════════════════════════════════ */
        .search-wrap {
            display: flex; align-items: center; gap: 7px;
            border: 1px solid var(--border); border-radius: var(--radius-sm);
            padding: 6px 11px; background: var(--white);
            transition: border-color .15s;
        }
        .search-wrap:focus-within { border-color: var(--blue); }
        .search-wrap i { color: var(--text-3); font-size: 13px; flex-shrink: 0; }
        .search-wrap input {
            border: none; outline: none; font-size: 13px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-1); background: transparent; width: 130px;
        }
        .search-wrap input::placeholder { color: var(--text-3); }

        /* ══════════════════════════════════════════
           FILTER BUTTON + DROPDOWN
        ══════════════════════════════════════════ */
        .filter-wrap { position: relative; }
        .btn-filter {
            display: flex; align-items: center; gap: 6px;
            border: 1px solid var(--border); border-radius: var(--radius-sm);
            padding: 6px 11px; background: var(--white);
            font-size: 13px; font-weight: 500; color: var(--text-2);
            cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all .15s; white-space: nowrap;
        }
        .btn-filter:hover { border-color: var(--blue); color: var(--blue); }
        .btn-filter.active { border-color: var(--blue); color: var(--blue); background: var(--blue-light); }

        .filter-menu {
            position: absolute; top: calc(100% + 6px); right: 0;
            background: var(--white); border: 1px solid var(--border);
            border-radius: var(--radius-sm); box-shadow: var(--shadow-md);
            min-width: 170px; z-index: 350;
            display: none; overflow: hidden;
        }
        .filter-menu.open { display: block; animation: scaleIn .13s ease; }
        .filter-menu-label {
            padding: 7px 12px 5px;
            font-size: 10.5px; font-weight: 700; color: var(--text-3);
            text-transform: uppercase; letter-spacing: .9px;
            border-bottom: 1px solid var(--border);
        }
        .filter-opt {
            padding: 9px 14px; font-size: 13px; color: var(--text-1);
            cursor: pointer; transition: background .12s;
        }
        .filter-opt:hover { background: var(--blue-light); color: var(--blue); }
        .filter-opt.active { color: var(--blue); font-weight: 600; background: var(--blue-light); }

        /* ══════════════════════════════════════════
           BUTTONS
        ══════════════════════════════════════════ */
        .btn-primary {
            background: var(--blue); color: #fff;
            border: none; border-radius: var(--radius-sm);
            padding: 7px 18px; font-size: 13.5px; font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer; transition: background .15s; white-space: nowrap;
        }
        .btn-primary:hover { background: var(--blue-hover); }

        .btn-outline {
            border: 1px solid var(--border); border-radius: var(--radius-sm);
            padding: 6px 14px; background: var(--white);
            font-size: 13px; font-weight: 600; color: var(--text-1);
            cursor: pointer; text-decoration: none; white-space: nowrap;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all .15s; display: inline-block;
        }
        .btn-outline:hover { background: var(--blue); color: #fff; border-color: var(--blue); }

        /* Semester select */
        .semester-bar { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
        .select-semester {
            border: 1px solid var(--border); border-radius: var(--radius-sm);
            padding: 8px 32px 8px 12px; font-size: 13.5px; font-weight: 500;
            font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-1);
            background: var(--white) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 10px center;
            -webkit-appearance: none; outline: none; cursor: pointer;
            transition: border-color .15s; box-shadow: var(--shadow-sm);
        }
        .select-semester:focus { border-color: var(--blue); }

        /* ══════════════════════════════════════════
           DONUT LEGEND
        ══════════════════════════════════════════ */
        .legend { display: flex; flex-direction: column; gap: 8px; }
        .legend-row { display: flex; align-items: center; gap: 8px; }
        .legend-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
        .legend-text { font-size: 12.5px; color: var(--text-2); }

        /* ══════════════════════════════════════════
           ANIMATIONS
        ══════════════════════════════════════════ */
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(.96) translateY(-4px); }
            to   { opacity: 1; transform: scale(1)   translateY(0); }
        }

        /* ══════════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════════ */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .sidebar-overlay.open { display: block; }
            .main-wrap { margin-left: 0; }
            .hamburger { display: flex; }
            .page-content { padding: 16px; }
            .topbar {
                padding: 0 16px;
                height: 60px;
            }
            .topbar-title h2 { font-size: 14px; }
            .topbar-title p  { font-size: 11px; }
            .user-info-wrap  { display: none; }
            .notif-panel     { width: 290px; right: -50px; }
            .ac-table-v2{display:block;overflow-x:auto;-webkit-overflow-scrolling:touch;}
            .tbl-card-v2{padding:16px;}
            .stat-card-value{font-size:24px;letter-spacing:-.8px;}
            .stat-card-body{padding:14px 16px;gap:10px;}
            .stat-icon-box{width:38px;height:38px;font-size:17px;}
            .chart-card-v2,.chart-card{padding:16px;}
            .donut-wrap-v2{gap:12px;}
            .search-wrap input{width:110px;}
            .tbl-head-v2{gap:8px;}
        }

        @media (max-width: 400px) {
            .topbar-title p { display: none; }
        }


        @media (max-width: 576px) {
            .stat-num { font-size: 28px; }
            .stat-card-value{font-size:20px;}
            .tbl-actions { gap: 6px; }
            .search-wrap input { width: 100px; }
            .donut-canvas-box{width:120px;height:120px;}
            .donut-canvas-box canvas{width:120px!important;height:120px!important;}
            .donut-center-num{font-size:16px;}
        }
        @media(max-width:480px){
            .donut-wrap-v2{flex-direction:column;align-items:center;}
            .legend-v2{width:100%;}
            .tbl-head-v2{flex-direction:column;align-items:stretch;}
            .tbl-actions{justify-content:flex-start;flex-wrap:wrap;}
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- Overlay mobile --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- ══ SIDEBAR ══ --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('mahasiswa.dashboard') }}" class="sidebar-brand">Academia</a>
    </div>

    <nav class="sidebar-nav">
        <span class="nav-label">Menu</span>
        <a href="{{ route('mahasiswa.dashboard') }}"
           class="nav-link-item {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('mahasiswa.nilai') }}"
           class="nav-link-item {{ request()->routeIs('mahasiswa.nilai*') ? 'active' : '' }}">
            <i class="bi bi-journal-bookmark-fill"></i>
            <span>Nilai Akademik</span>
        </a>
        <a href="{{ route('mahasiswa.absensi') }}"
           class="nav-link-item {{ request()->routeIs('mahasiswa.absensi') ? 'active' : '' }}">
            <i class="bi bi-calendar2-check-fill"></i>
            <span>Riwayat Absensi</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="nav-link-item w-100 border-0 bg-transparent text-start"
                    style="color:#EF4444; cursor:pointer;">
                <i class="bi bi-box-arrow-left"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>

{{-- ══ MAIN ══ --}}
<div class="main-wrap">

    {{-- TOPBAR --}}
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" id="hamburgerBtn">
                <i class="bi bi-list"></i>
            </button>
            <div class="topbar-title">
                <h2>@yield('page-title', 'Halo! ' . auth()->user()->name . ' 👋')</h2>
                <p>@yield('page-sub', 'Selamat datang di Dashboard Akademik')</p>
            </div>
        </div>

        <div class="topbar-right">
            {{-- Notifikasi --}}
            <div class="notif-wrap">
                @php
                $notifCount = 0;
 
                // Cek $nilaiDE — bisa Collection atau int atau null
                if (isset($nilaiDE)) {
                    if (is_object($nilaiDE) && method_exists($nilaiDE, 'count')) {
                        $notifCount += $nilaiDE->count();
                    } elseif (is_int($nilaiDE) || is_numeric($nilaiDE)) {
                        $notifCount += (int) $nilaiDE;
                    }
                }
 
                // Cek $absensiKritis — bisa Collection atau int atau null
                if (isset($absensiKritis)) {
                    if (is_object($absensiKritis) && method_exists($absensiKritis, 'count')) {
                        $notifCount += $absensiKritis->count();
                    } elseif (is_int($absensiKritis) || is_numeric($absensiKritis)) {
                        $notifCount += (int) $absensiKritis;
                    }
                }
            @endphp
                <button class="notif-btn" id="notifToggle" aria-label="Notifikasi">
                    <i class="bi bi-bell-fill"></i>
                    @if($notifCount > 0)
                        <span class="notif-badge">{{ $notifCount }}</span>
                    @endif
                </button>

                <div class="notif-panel" id="notifPanel">
                    <div class="notif-panel-head">
                        Notification Alert
                        @if($notifCount > 0)
                        <span style="background:var(--blue-light);color:var(--blue);border-radius:20px;padding:1px 8px;font-size:11px;font-weight:700;margin-left:6px;">{{ $notifCount }}</span>
                        @endif
                    </div>
                    <div class="notif-panel-body">
                        @if(isset($nilaiDE) && is_object($nilaiDE) && $nilaiDE->count() > 0)
                            @foreach($nilaiDE as $n)
                            <div class="notif-entry">
                                <div class="notif-icon-wrap"><i class="bi bi-exclamation-circle-fill"></i></div>
                                <div class="notif-entry-text">
                                    Performa akademik pada mata kuliah
                                    <strong>{{ $n->mataKuliah->nama }}</strong>
                                    berada di bawah standar! (Nilai {{ $n->grade }}).
                                </div>
                            </div>
                            @endforeach
                        @endif
 
                        @if(isset($absensiKritis) && is_object($absensiKritis) && $absensiKritis->count() > 0)
                            @foreach($absensiKritis as $a)
                            <div class="notif-entry">
                                <div class="notif-icon-wrap"><i class="bi bi-clock-fill"></i></div>
                                <div class="notif-entry-text">
                                    Alpha <strong>{{ $a->jam_alpha }} jam</strong> pada
                                    <strong>{{ $a->mataKuliah->nama }}</strong>.
                                    Mendekati batas 18 jam!
                                </div>
                            </div>
                            @endforeach
                        @endif
 
                        @if($notifCount === 0)
                        <div style="text-align:center;padding:20px;color:var(--text-3);font-size:13px;">
                            <i class="bi bi-check-circle d-block mb-1" style="font-size:22px;color:var(--blue-mid);"></i>
                            Tidak ada notifikasi baru
                        </div>
                        @endif
                        @if(isset($absensiKritis) && $absensiKritis->count() > 0)
                            @foreach($absensiKritis as $a)
                            <div class="notif-entry">
                                <div class="notif-icon-wrap"><i class="bi bi-clock-fill"></i></div>
                                <div class="notif-entry-text">
                                    Alpha <strong>{{ $a->jam_alpha }} jam</strong> pada
                                    <strong>{{ $a->mataKuliah->nama }}</strong>.
                                    Mendekati batas 18 jam!
                                </div>
                            </div>
                            @endforeach
                        @endif
                        @if($notifCount === 0)
                        <div style="text-align:center;padding:20px;color:var(--text-3);font-size:13px;">
                            <i class="bi bi-check-circle d-block mb-1" style="font-size:22px;color:var(--blue-mid);"></i>
                            Tidak ada notifikasi baru
                        </div>
                        @endif
                    </div>
                    <div class="notif-panel-foot">View all notifications</div>
                </div>
            </div>

            {{-- User --}}
            <div class="user-pill">
                <div class="user-ava">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="user-info-wrap">
                    <div class="user-info-name">{{ auth()->user()->name }}</div>
                    <div class="user-info-role">Student</div>
                </div>
                <i class="bi bi-chevron-down"></i>
            </div>
        </div>
    </header>

    {{-- CONTENT --}}
    <main class="page-content">
        @if(session('success'))
        <div class="flash flash-ok">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="flash flash-err">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
(function() {
    // Hamburger
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebarOverlay');
    const hamburger = document.getElementById('hamburgerBtn');

    hamburger?.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
    });
    overlay?.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
    });

    // Notif panel
    const notifToggle = document.getElementById('notifToggle');
    const notifPanel  = document.getElementById('notifPanel');
    notifToggle?.addEventListener('click', (e) => {
        e.stopPropagation();
        closeAllMenus(notifPanel);
        notifPanel?.classList.toggle('open');
    });

    // Filter menus
    function initFilterMenus() {
        document.querySelectorAll('.filter-wrap').forEach(wrap => {
            const btn  = wrap.querySelector('.btn-filter');
            const menu = wrap.querySelector('.filter-menu');
            if (!btn || !menu) return;
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = menu.classList.contains('open');
                closeAllMenus();
                if (!isOpen) menu.classList.add('open');
            });
            menu.querySelectorAll('.filter-opt').forEach(opt => {
                opt.addEventListener('click', (e) => {
                    e.stopPropagation();
                    menu.querySelectorAll('.filter-opt').forEach(o => o.classList.remove('active'));
                    opt.classList.add('active');
                    menu.classList.remove('open');
                    // fire custom event
                    btn.dispatchEvent(new CustomEvent('filterChange', {
                        detail: { value: opt.dataset.val, label: opt.textContent.trim() }
                    }));
                });
            });
        });
    }

    function closeAllMenus(except) {
        document.querySelectorAll('.filter-menu.open, .notif-panel.open').forEach(el => {
            if (el !== except) el.classList.remove('open');
        });
    }

    document.addEventListener('click', () => closeAllMenus());
    initFilterMenus();
    window.initFilterMenus = initFilterMenus;
})();
</script>
@stack('scripts')
</body>
</html>