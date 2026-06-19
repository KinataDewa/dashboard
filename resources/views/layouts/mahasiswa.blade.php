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
        :root {
            --blue:#2563EB; --blue-hover:#1D4ED8; --blue-light:#EFF6FF; --blue-mid:#BFDBFE;
            --bg:#F1F5F9; --white:#FFFFFF;
            --text-1:#0F172A; --text-2:#64748B; --text-3:#94A3B8; --border:#E2E8F0;
            --radius:12px; --radius-sm:8px;
            --shadow-sm:0 1px 2px rgba(0,0,0,.05);
            --shadow:0 1px 3px rgba(0,0,0,.07),0 1px 2px rgba(0,0,0,.05);
            --shadow-md:0 4px 12px rgba(0,0,0,.08),0 2px 4px rgba(0,0,0,.04);
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text-1);min-height:100vh;font-size:14px;line-height:1.5;-webkit-font-smoothing:antialiased;}

        /* ── Flat Top Navbar — Mahasiswa ──────────────── */
        .mhs-navbar{
            background:var(--white);
            border-bottom:1px solid var(--border);
            height:60px;
            display:flex;align-items:center;
            padding:0 24px;
            position:sticky;top:0;z-index:1000;
            gap:4px;
        }
        .mhs-nav-brand{font-size:14px;font-weight:600;letter-spacing:-0.02em;color:var(--text-1);text-decoration:none;margin-right:12px;flex-shrink:0;}
        .mhs-nav-items{display:flex;align-items:center;gap:2px;flex:1;}
        .mhs-nav-link{
            display:flex;align-items:center;gap:6px;
            padding:8px 13px;border-radius:var(--radius-sm);
            font-size:13.5px;font-weight:500;
            color:var(--text-2);text-decoration:none;white-space:nowrap;
            transition:background .15s,color .15s;position:relative;
        }
        .mhs-nav-link:hover{background:var(--bg);color:var(--text-1);}
        .mhs-nav-link.active{background:var(--blue-light);color:var(--blue);font-weight:600;}
        .mhs-nav-dot{position:absolute;top:6px;right:6px;width:6px;height:6px;background:#EF4444;border-radius:50%;border:1.5px solid var(--white);}

        /* Right section */
        .mhs-nav-right{display:flex;align-items:center;gap:8px;margin-left:auto;flex-shrink:0;}

        /* Notif icon button */
        .nav-icon-btn{width:34px;height:34px;border-radius:50%;background:transparent;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--text-3);font-size:15px;transition:background .15s,color .15s;position:relative;}
        .nav-icon-btn:hover{background:var(--bg);color:var(--text-1);}
        .nav-notif-badge{position:absolute;top:2px;right:2px;min-width:15px;height:15px;padding:0 3px;background:#EF4444;color:#fff;border-radius:20px;font-size:9px;font-weight:700;display:flex;align-items:center;justify-content:center;}

        /* Notif panel */
        .notif-wrap{position:relative;}
        .notif-panel{position:absolute;top:calc(100% + 10px);right:0;width:320px;background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-md);z-index:500;display:none;overflow:hidden;}
        .notif-panel.open{display:block;animation:scaleIn .15s ease;}
        .notif-panel-head{padding:14px 16px 12px;border-bottom:1px solid var(--border);font-size:14px;font-weight:700;color:var(--text-1);}
        .notif-panel-body{padding:8px;max-height:300px;overflow-y:auto;}
        .notif-entry{display:flex;gap:10px;padding:10px;border-radius:var(--radius-sm);margin-bottom:4px;background:#FEF2F2;}
        .notif-entry:last-child{margin-bottom:0;}
        .notif-icon-wrap{width:34px;height:34px;border-radius:50%;border:1.5px solid #FECACA;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:#EF4444;font-size:14px;}
        .notif-entry-text{font-size:12.5px;color:var(--text-1);line-height:1.55;}
        .notif-entry-text strong{font-weight:600;}
        .notif-panel-foot{padding:10px 16px;border-top:1px solid var(--border);text-align:center;font-size:12.5px;color:var(--blue);cursor:pointer;font-weight:600;transition:background .15s;}
        .notif-panel-foot:hover{background:var(--blue-light);}

        /* Profile button — pill style (matches admin) */
        .prof-wrap{position:relative;}
        .prof-btn{
            display:flex;align-items:center;gap:8px;
            padding:5px 10px 5px 5px;border:1px solid var(--border);border-radius:40px;
            background:var(--white);cursor:pointer;transition:border-color .15s;
            font-family:'Plus Jakarta Sans',sans-serif;
        }
        .prof-btn:hover{border-color:var(--blue-mid);}
        .prof-ava{width:30px;height:30px;border-radius:50%;background:var(--blue);color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;}
        .prof-name{font-size:12.5px;font-weight:600;color:var(--text-1);}
        .prof-role-lbl{font-size:11px;color:var(--text-2);}
        .prof-dd{display:none;position:absolute;top:calc(100% + 8px);right:0;width:210px;background:var(--white);border:0.5px solid #D1D5DB;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.08);padding:6px;z-index:300;}
        .prof-dd.open{display:block;animation:scaleIn .13s ease;}
        .prof-dd-info{padding:8px 10px 10px;border-bottom:1px solid var(--border);margin-bottom:4px;}
        .prof-dd-name{font-size:13px;font-weight:600;color:var(--text-1);}
        .prof-dd-role{font-size:11px;color:var(--text-3);margin-top:1px;}
        .prof-dd-logout{display:flex;align-items:center;gap:9px;width:100%;padding:8px 10px;border-radius:var(--radius-sm);font-size:13px;font-weight:500;color:#DC2626;background:transparent;border:none;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:background .12s;text-align:left;margin-top:4px;border-top:1px solid var(--border);}
        .prof-dd-logout:hover{background:#FEF2F2;}

        /* Mobile hamburger */
        .mhs-hamburger{display:none;width:36px;height:36px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--white);cursor:pointer;align-items:center;justify-content:center;color:var(--text-2);font-size:16px;flex-shrink:0;margin-left:auto;}

        /* Mobile drawer overlay */
        .mobile-drawer-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.35);z-index:1100;}
        .mobile-drawer-overlay.open{display:block;}
        .mobile-drawer{
            position:fixed;top:0;left:0;bottom:0;width:260px;
            background:var(--white);z-index:1200;
            overflow-y:auto;padding:20px 12px;
            transform:translateX(-100%);
            transition:transform .25s cubic-bezier(.16,1,.3,1);
        }
        .mobile-drawer.open{transform:translateX(0);}
        .mobile-drawer-brand{font-size:16px;font-weight:700;color:var(--text-1);letter-spacing:-.3px;padding:4px 8px 16px;border-bottom:1px solid var(--border);margin-bottom:8px;display:block;}
        .mobile-drawer-link{display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:var(--radius-sm);font-size:13.5px;font-weight:500;color:var(--text-2);text-decoration:none;margin-bottom:2px;transition:all .15s;}
        .mobile-drawer-link.active{background:var(--blue-light);color:var(--blue);font-weight:600;}
        .mobile-drawer-link:hover{background:var(--bg);color:var(--text-1);}
        .mobile-drawer-link i{font-size:16px;width:20px;text-align:center;flex-shrink:0;}
        .mobile-drawer-sep{height:1px;background:var(--border);margin:8px 0;}
        .mobile-drawer-logout{display:flex;align-items:center;gap:10px;width:100%;padding:9px 10px;border-radius:var(--radius-sm);font-size:13.5px;font-weight:500;color:#EF4444;background:transparent;border:none;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:background .15s;}
        .mobile-drawer-logout:hover{background:#FEF2F2;}

        /* ── Page content ─────────────────────────── */
        .page-wrap{max-width:1440px;margin:0 auto;padding:24px;}
        .flash{display:flex;align-items:center;gap:8px;padding:10px 14px;border-radius:var(--radius-sm);font-size:13px;margin-bottom:18px;}
        .flash-ok{background:#F0FDF4;border:1px solid #BBF7D0;color:#15803D;}
        .flash-err{background:#FEF2F2;border:1px solid #FECACA;color:#DC2626;}

        /* ── Shared utility classes ───────────────── */
        .card-white{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);}
        .section-label{font-size:11px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;margin-top:4px;display:flex;align-items:center;gap:8px;}
        .section-label::after{content:'';flex:1;height:1px;background:var(--border);}

        .stat-card{padding:20px;position:relative;overflow:hidden;}
        .stat-deco{position:absolute;right:-8px;top:-8px;width:72px;height:72px;opacity:.07;pointer-events:none;}
        .stat-tag{font-size:12px;font-weight:600;color:var(--text-2);margin-bottom:10px;}
        .stat-num{font-size:34px;font-weight:800;line-height:1;letter-spacing:-1.5px;color:var(--text-1);}
        .stat-num.c-blue{color:var(--blue);}
        .stat-num.c-red{color:#EF4444;}
        .stat-unit{font-size:15px;font-weight:700;color:var(--text-2);margin-left:3px;}
        .stat-note{font-size:12px;color:var(--text-2);margin-top:8px;display:flex;align-items:center;gap:4px;}

        .stat-card-v2{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;transition:transform .18s,box-shadow .18s;height:100%;}
        .stat-card-v2:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(0,0,0,.1);}
        .stat-card-accent{height:4px;}
        .stat-card-body{padding:18px 20px;display:flex;gap:14px;align-items:flex-start;}
        .stat-icon-box{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
        .stat-card-info{flex:1;min-width:0;}
        .stat-card-label{font-size:12px;font-weight:600;color:var(--text-2);margin-bottom:4px;}
        .stat-card-value{font-size:32px;font-weight:800;line-height:1;letter-spacing:-1.5px;margin-bottom:6px;}
        .stat-card-note{font-size:11.5px;color:var(--text-2);}
        .stat-card-badge{display:inline-flex;align-items:center;gap:3px;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700;}
        .badge-up{background:#DCFCE7;color:#15803D;}
        .badge-down{background:#FEE2E2;color:#991B1B;}
        .badge-warn{background:#FEF9C3;color:#854D0E;}
        .badge-blue{background:#DBEAFE;color:#1D4ED8;}

        .chart-card{padding:20px;}
        .chart-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;}
        .chart-head-title{font-size:15px;font-weight:700;color:var(--text-1);}
        .tbl-card-v2{padding:20px 22px;}
        .tbl-head-v2{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;gap:10px;flex-wrap:wrap;}
        .tbl-title-v2{font-size:15px;font-weight:700;color:var(--text-1);}
        .tbl-sub-v2{font-size:11.5px;color:var(--text-2);margin-top:1px;}
        .tbl-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
        .ac-table-v2{width:100%;border-collapse:collapse;}
        .ac-table-v2 thead th{font-size:11.5px;font-weight:600;color:var(--text-2);padding:0 12px 10px;text-align:left;border-bottom:1.5px solid var(--border);white-space:nowrap;}
        .ac-table-v2 tbody tr{border-bottom:1px solid #F8FAFC;transition:background .12s;}
        .ac-table-v2 tbody tr:last-child{border-bottom:none;}
        .ac-table-v2 tbody tr:hover{background:#F8FAFF;}
        .ac-table-v2 tbody td{padding:11px 12px;font-size:13.5px;}

        .tbl-card{padding:20px;}
        .tbl-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;gap:10px;flex-wrap:wrap;}
        .tbl-title{font-size:15px;font-weight:700;color:var(--text-1);}
        .ac-table{width:100%;border-collapse:collapse;}
        .ac-table thead th{font-size:11.5px;font-weight:600;color:var(--text-2);padding:8px 12px 10px;text-align:left;border-bottom:1px solid var(--border);white-space:nowrap;}
        .ac-table tbody tr{border-bottom:1px solid var(--border);transition:background .12s;}
        .ac-table tbody tr:last-child{border-bottom:none;}
        .ac-table tbody tr:hover{background:#FAFBFC;}
        .ac-table tbody td{padding:12px;font-size:13.5px;color:var(--text-1);}
        .ac-table tbody td.muted{color:var(--text-2);}

        .badge{display:inline-flex;align-items:center;justify-content:center;padding:3px 11px;border-radius:20px;font-size:12px;font-weight:600;white-space:nowrap;}
        .badge-green{background:#DCFCE7;color:#166534;}
        .badge-red{background:#FEE2E2;color:#991B1B;}
        .badge-yellow{background:#FEF9C3;color:#854D0E;}
        .badge-blue{background:#DBEAFE;color:#1E40AF;}
        .badge-gray{background:#F1F5F9;color:#475569;}

        .search-wrap{display:flex;align-items:center;gap:7px;border:1px solid var(--border);border-radius:var(--radius-sm);padding:6px 11px;background:var(--white);transition:border-color .15s;}
        .search-wrap:focus-within{border-color:var(--blue);}
        .search-wrap i{color:var(--text-3);font-size:13px;flex-shrink:0;}
        .search-wrap input{border:none;outline:none;font-size:13px;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-1);background:transparent;width:130px;}
        .search-wrap input::placeholder{color:var(--text-3);}

        .filter-wrap{position:relative;}
        .btn-filter{display:flex;align-items:center;gap:6px;border:1px solid var(--border);border-radius:var(--radius-sm);padding:6px 11px;background:var(--white);font-size:13px;font-weight:500;color:var(--text-2);cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:all .15s;white-space:nowrap;}
        .btn-filter:hover{border-color:var(--blue);color:var(--blue);}
        .btn-filter.active{border-color:var(--blue);color:var(--blue);background:var(--blue-light);}
        .filter-menu{position:absolute;top:calc(100% + 6px);right:0;background:var(--white);border:1px solid var(--border);border-radius:var(--radius-sm);box-shadow:var(--shadow-md);min-width:170px;z-index:350;display:none;overflow:hidden;}
        .filter-menu.open{display:block;animation:scaleIn .13s ease;}
        .filter-menu-label{padding:7px 12px 5px;font-size:10.5px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.9px;border-bottom:1px solid var(--border);}
        .filter-opt{padding:9px 14px;font-size:13px;color:var(--text-1);cursor:pointer;transition:background .12s;}
        .filter-opt:hover{background:var(--blue-light);color:var(--blue);}
        .filter-opt.active{color:var(--blue);font-weight:600;background:var(--blue-light);}

        .btn-primary{background:var(--blue);color:#fff;border:none;border-radius:var(--radius-sm);padding:7px 18px;font-size:13.5px;font-weight:600;font-family:'Plus Jakarta Sans',sans-serif;cursor:pointer;transition:background .15s;white-space:nowrap;text-decoration:none;display:inline-flex;align-items:center;gap:6px;}
        .btn-primary:hover{background:var(--blue-hover);color:#fff;}
        .btn-outline{border:1px solid var(--border);border-radius:var(--radius-sm);padding:6px 14px;background:var(--white);font-size:13px;font-weight:600;color:var(--text-1);cursor:pointer;text-decoration:none;white-space:nowrap;font-family:'Plus Jakarta Sans',sans-serif;transition:all .15s;display:inline-block;}
        .btn-outline:hover{background:var(--blue);color:#fff;border-color:var(--blue);}

        .semester-bar{display:flex;align-items:center;gap:10px;margin-bottom:20px;}
        .select-semester{border:1px solid var(--border);border-radius:var(--radius-sm);padding:8px 32px 8px 12px;font-size:13.5px;font-weight:500;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-1);background:var(--white) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 10px center;-webkit-appearance:none;outline:none;cursor:pointer;box-shadow:var(--shadow-sm);}
        .select-semester:focus{border-color:var(--blue);}

        .legend{display:flex;flex-direction:column;gap:8px;}
        .legend-row{display:flex;align-items:center;gap:8px;}
        .legend-dot{width:9px;height:9px;border-radius:50%;flex-shrink:0;}
        .legend-text{font-size:12.5px;color:var(--text-2);}

        .tbl-footer{display:flex;align-items:center;gap:10px;margin-top:14px;padding-top:12px;border-top:1px solid var(--border);flex-wrap:wrap;}
        .info-chip{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:11.5px;font-weight:600;background:#F1F5F9;color:var(--text-2);}
        .tbl-number{font-size:11.5px;font-weight:700;color:var(--text-3);background:var(--bg);border-radius:20px;padding:2px 8px;font-family:monospace;}
        .empty-state{text-align:center;padding:48px 20px;color:var(--text-3);}
        .empty-state i{font-size:36px;display:block;margin-bottom:10px;}
        .empty-state p{font-size:14px;margin:0;}

        .pagination{display:flex;gap:4px;align-items:center;flex-wrap:wrap;}
        .pagination .page-item .page-link{border:1.5px solid var(--border);border-radius:var(--radius-sm);padding:6px 12px;font-size:13px;font-weight:500;color:var(--text-2);background:var(--white);text-decoration:none;transition:all .15s;font-family:'Plus Jakarta Sans',sans-serif;}
        .pagination .page-item .page-link:hover{background:var(--blue-light);color:var(--blue);border-color:var(--blue);}
        .pagination .page-item.active .page-link{background:var(--blue);color:#fff;border-color:var(--blue);font-weight:700;}
        .pagination .page-item.disabled .page-link{opacity:.4;cursor:not-allowed;}

        .status-active{background:#DCFCE7;color:#166534;border-radius:20px;padding:3px 11px;font-size:12px;font-weight:600;}
        .status-inactive{background:#FEE2E2;color:#991B1B;border-radius:20px;padding:3px 11px;font-size:12px;font-weight:600;}

        /* Donut */
        .donut-wrap-v2{display:flex;align-items:center;gap:20px;flex-wrap:wrap;}
        .donut-canvas-box{position:relative;width:140px;height:140px;flex-shrink:0;}
        .donut-center{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;}
        .donut-center-num{font-size:22px;font-weight:800;color:var(--text-1);letter-spacing:-1px;line-height:1;}
        .donut-center-lbl{font-size:10px;color:var(--text-3);margin-top:2px;}
        .legend-v2{flex:1;min-width:120px;}
        .legend-v2-row{display:flex;align-items:center;justify-content:space-between;gap:8px;padding:5px 0;border-bottom:1px solid #F1F5F9;}
        .legend-v2-row:last-child{border-bottom:none;}
        .legend-v2-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
        .legend-v2-label{font-size:12.5px;color:var(--text-2);flex:1;}
        .legend-v2-val{font-size:12.5px;font-weight:700;color:var(--text-1);}

        @keyframes scaleIn{from{opacity:0;transform:scale(.96) translateY(-4px);}to{opacity:1;transform:scale(1) translateY(0);}}

        /* ── Responsive ───────────────────────────── */
        @media(max-width:768px){
            .mhs-nav-items,.mhs-nav-right{display:none;}
            .mhs-hamburger{display:flex;}
            .page-wrap{padding:16px;}
            .ac-table-v2,.ac-table{display:block;overflow-x:auto;-webkit-overflow-scrolling:touch;}
            .tbl-card-v2{padding:16px;}
            .stat-card-value{font-size:24px;letter-spacing:-.8px;}
            .stat-card-body{padding:14px 16px;gap:10px;}
            .stat-icon-box{width:38px;height:38px;font-size:17px;}
            .chart-card{padding:16px;}
            .donut-wrap-v2{gap:12px;}
            .search-wrap input{width:100px;}
            .tbl-head-v2{gap:8px;}
            .notif-panel{width:290px;right:-40px;}
        }
        @media(max-width:576px){
            .stat-num{font-size:28px;}
            .stat-card-value{font-size:20px;}
            .tbl-actions{gap:6px;}
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

@php
$_sisaKompen = 0;
if (auth()->check()) {
    $_mhsSidebar = \App\Models\Mahasiswa::where('user_id', auth()->id())
        ->with(['absensis','kompensasis'])->first();
    if ($_mhsSidebar) {
        $_absByS = $_mhsSidebar->absensis->groupBy('semester');
        $_komByS = $_mhsSidebar->kompensasis->groupBy('semester');
        foreach ($_absByS as $_sem => $_abs) {
            $_alpha = (int) $_abs->sum('jam_alpha');
            if ($_alpha < 18) continue;
            $_selesai = (int) ($_komByS->get($_sem, collect())->where('status','lunas')->sum('jam_kompen_wajib'));
            $_sisaKompen += max(0, $_alpha * 2 - $_selesai);
        }
    }
}
@endphp

{{-- ── MOBILE DRAWER ── --}}
<div class="mobile-drawer-overlay" id="drawerOverlay"></div>
<nav class="mobile-drawer" id="mobileDrawer">
    <span class="mobile-drawer-brand">Academia</span>
    <a href="{{ route('mahasiswa.dashboard') }}"
       class="mobile-drawer-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>
    <a href="{{ route('mahasiswa.nilai') }}"
       class="mobile-drawer-link {{ request()->routeIs('mahasiswa.nilai*') ? 'active' : '' }}">
        <i class="bi bi-journal-bookmark-fill"></i> Nilai Akademik
    </a>
    <a href="{{ route('mahasiswa.absensi') }}"
       class="mobile-drawer-link {{ request()->routeIs('mahasiswa.absensi') ? 'active' : '' }}">
        <i class="bi bi-calendar2-check-fill"></i> Riwayat Absensi
    </a>
    <a href="{{ route('mahasiswa.kompensasi') }}"
       class="mobile-drawer-link {{ request()->routeIs('mahasiswa.kompensasi') ? 'active' : '' }}">
        <i class="bi bi-clipboard2-check-fill"></i> Kompensasi
        @if($_sisaKompen > 0)
        <span style="margin-left:auto;background:#EF4444;color:#fff;border-radius:99px;padding:1px 6px;font-size:10px;font-weight:700;">!</span>
        @endif
    </a>
    <div class="mobile-drawer-sep"></div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="mobile-drawer-logout">
            <i class="bi bi-box-arrow-left"></i> Logout
        </button>
    </form>
</nav>

{{-- ── FLAT NAVBAR ── --}}
<header class="mhs-navbar">
    <a href="{{ route('mahasiswa.dashboard') }}" class="mhs-nav-brand">Academia</a>

    <div class="mhs-nav-items">
        <a href="{{ route('mahasiswa.dashboard') }}"
           class="mhs-nav-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
            Dashboard
        </a>
        <a href="{{ route('mahasiswa.nilai') }}"
           class="mhs-nav-link {{ request()->routeIs('mahasiswa.nilai*') ? 'active' : '' }}">
            Nilai
        </a>
        <a href="{{ route('mahasiswa.absensi') }}"
           class="mhs-nav-link {{ request()->routeIs('mahasiswa.absensi') ? 'active' : '' }}">
            Absensi
        </a>
        <a href="{{ route('mahasiswa.kompensasi') }}"
           class="mhs-nav-link {{ request()->routeIs('mahasiswa.kompensasi') ? 'active' : '' }}">
            Kompensasi
            @if($_sisaKompen > 0)
            <span class="mhs-nav-dot"></span>
            @endif
        </a>
    </div>

    <div class="mhs-nav-right">
        @php
        $notifCount = 0;
        if (isset($nilaiDE)) {
            if (is_object($nilaiDE) && method_exists($nilaiDE,'count')) $notifCount += $nilaiDE->count();
            elseif (is_numeric($nilaiDE)) $notifCount += (int)$nilaiDE;
        }
        if (isset($absensiKritis)) {
            if (is_object($absensiKritis) && method_exists($absensiKritis,'count')) $notifCount += $absensiKritis->count();
            elseif (is_numeric($absensiKritis)) $notifCount += (int)$absensiKritis;
        }
        @endphp
        <div class="notif-wrap">
            <button class="nav-icon-btn" id="notifToggle" aria-label="Notifikasi">
                <i class="bi bi-bell-fill"></i>
                @if($notifCount > 0)
                <span class="nav-notif-badge">{{ $notifCount }}</span>
                @endif
            </button>
            <div class="notif-panel" id="notifPanel">
                <div class="notif-panel-head">
                    Notifikasi
                    @if($notifCount > 0)
                    <span style="background:var(--blue-light);color:var(--blue);border-radius:20px;padding:1px 8px;font-size:11px;font-weight:700;margin-left:6px;">{{ $notifCount }}</span>
                    @endif
                </div>
                <div class="notif-panel-body">
                    @if(isset($nilaiDE) && is_object($nilaiDE) && $nilaiDE->count() > 0)
                        @foreach($nilaiDE as $n)
                        <div class="notif-entry">
                            <div class="notif-icon-wrap"><i class="bi bi-exclamation-circle-fill"></i></div>
                            <div class="notif-entry-text">Nilai di bawah standar semester <strong>{{ $n->semester }}</strong> ({{ $n->grade }}).</div>
                        </div>
                        @endforeach
                    @endif
                    @if(isset($absensiKritis) && is_object($absensiKritis) && $absensiKritis->count() > 0)
                        @foreach($absensiKritis as $a)
                        <div class="notif-entry">
                            <div class="notif-icon-wrap"><i class="bi bi-clock-fill"></i></div>
                            <div class="notif-entry-text">Alpha semester <strong>{{ $a->semester }}</strong>: <strong>{{ $a->jam_alpha }} jam</strong>.</div>
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
                <div class="notif-panel-foot">Lihat semua</div>
            </div>
        </div>

        <div class="prof-wrap">
            <button class="prof-btn" id="profBtn" aria-label="Profile">
                <div class="prof-ava">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <div class="prof-name">{{ Str::limit(auth()->user()->name, 14) }}</div>
                    <div class="prof-role-lbl">Mahasiswa</div>
                </div>
                <i class="bi bi-chevron-down" style="font-size:11px;color:var(--text-3);"></i>
            </button>
            <div class="prof-dd" id="profMenu">
                <div class="prof-dd-info">
                    <div class="prof-dd-name">{{ auth()->user()->name }}</div>
                    <div class="prof-dd-role">Mahasiswa</div>
                </div>
                <form id="mhs-logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
                <button class="prof-dd-logout" onclick="event.preventDefault();document.getElementById('mhs-logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </div>
        </div>
    </div>

    <button class="mhs-hamburger" id="mhsHamburger" aria-label="Menu">
        <i class="bi bi-list"></i>
    </button>
</header>

{{-- ── MAIN CONTENT ── --}}
<main class="page-wrap">
    @if(session('success'))
    <div class="flash flash-ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="flash flash-err"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
    @endif
    @yield('content')
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    // Mobile drawer
    var overlay   = document.getElementById('drawerOverlay');
    var drawer    = document.getElementById('mobileDrawer');
    var hamburger = document.getElementById('mhsHamburger');
    hamburger && hamburger.addEventListener('click', function (e) {
        e.stopPropagation();
        overlay.classList.add('open');
        drawer.classList.add('open');
    });
    overlay && overlay.addEventListener('click', function () {
        overlay.classList.remove('open');
        drawer.classList.remove('open');
    });

    // Notif
    var notifToggle = document.getElementById('notifToggle');
    var notifPanel  = document.getElementById('notifPanel');
    notifToggle && notifToggle.addEventListener('click', function (e) {
        e.stopPropagation();
        var isOpen = notifPanel.classList.contains('open');
        closeAll(notifPanel);
        if (!isOpen) notifPanel.classList.add('open');
    });

    // Profile
    var profBtn  = document.getElementById('profBtn');
    var profMenu = document.getElementById('profMenu');
    profBtn && profBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        var isOpen = profMenu.classList.contains('open');
        closeAll(profMenu);
        if (!isOpen) profMenu.classList.add('open');
    });

    function closeAll(except) {
        document.querySelectorAll('.notif-panel.open, .prof-dd.open').forEach(function (el) {
            if (el !== except) el.classList.remove('open');
        });
    }
    document.addEventListener('click', closeAll);

    // Filter menus (dipakai di halaman-halaman content)
    function initFilterMenus() {
        document.querySelectorAll('.filter-wrap').forEach(function (wrap) {
            var btn  = wrap.querySelector('.btn-filter');
            var menu = wrap.querySelector('.filter-menu');
            if (!btn || !menu) return;
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var isOpen = menu.classList.contains('open');
                document.querySelectorAll('.filter-menu.open').forEach(function (m) { m.classList.remove('open'); });
                if (!isOpen) menu.classList.add('open');
            });
            menu.querySelectorAll('.filter-opt').forEach(function (opt) {
                opt.addEventListener('click', function (e) {
                    e.stopPropagation();
                    menu.querySelectorAll('.filter-opt').forEach(function (o) { o.classList.remove('active'); });
                    opt.classList.add('active');
                    menu.classList.remove('open');
                    btn.dispatchEvent(new CustomEvent('filterChange', { detail: { value: opt.dataset.val, label: opt.textContent.trim() } }));
                });
            });
        });
    }
    initFilterMenus();
    window.initFilterMenus = initFilterMenus;
})();
</script>
@stack('scripts')
</body>
</html>
