<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Academia — @yield('title', 'Dashboard DPA')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue:#2563EB; --blue-hover:#1D4ED8; --blue-light:#EFF6FF; --blue-mid:#BFDBFE;
            --green:#16A34A; --green-light:rgba(22,163,74,0.08); --green-mid:rgba(22,163,74,0.2);
            --bg:#F1F5F9; --white:#FFFFFF;
            --text-1:#0F172A; --text-2:#64748B; --text-3:#94A3B8; --border:#E2E8F0;
            --radius:12px; --radius-sm:8px;
            --shadow-sm:0 1px 2px rgba(0,0,0,.05);
            --shadow:0 1px 3px rgba(0,0,0,.07),0 1px 2px rgba(0,0,0,.05);
            --shadow-md:0 4px 12px rgba(0,0,0,.08),0 2px 4px rgba(0,0,0,.04);
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text-1);min-height:100vh;font-size:14px;line-height:1.5;-webkit-font-smoothing:antialiased;}

        /* ── Flat Top Navbar — Dosen ──────────────────── */
        .dosen-navbar{
            background:var(--white);
            border-bottom:1px solid var(--border);
            height:60px;
            display:flex;align-items:center;
            padding:0 24px;
            position:sticky;top:0;z-index:1000;
            gap:4px;
        }
        .dosen-nav-brand{font-size:14px;font-weight:600;letter-spacing:-0.02em;color:var(--text-1);text-decoration:none;margin-right:12px;flex-shrink:0;}
        .dosen-nav-items{display:flex;align-items:center;gap:2px;flex:1;}
        .dosen-nav-link{
            display:flex;align-items:center;gap:6px;
            padding:8px 13px;border-radius:var(--radius-sm);
            font-size:13.5px;font-weight:500;
            color:var(--text-2);text-decoration:none;white-space:nowrap;
            transition:background .15s,color .15s;
        }
        .dosen-nav-link:hover{background:var(--bg);color:var(--text-1);}
        .dosen-nav-link.active{background:var(--green-light);color:var(--green);font-weight:600;}

        /* Right section */
        .dosen-nav-right{display:flex;align-items:center;gap:8px;margin-left:auto;flex-shrink:0;}

        /* Notif icon button */
        .nav-icon-btn{width:34px;height:34px;border-radius:50%;background:transparent;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--text-3);font-size:15px;transition:background .15s,color .15s;position:relative;}
        .nav-icon-btn:hover{background:var(--bg);color:var(--text-1);}
        .nav-notif-badge{position:absolute;top:2px;right:2px;min-width:15px;height:15px;padding:0 3px;background:#EF4444;color:#fff;border-radius:20px;font-size:9px;font-weight:700;display:flex;align-items:center;justify-content:center;}

        /* Notif panel */
        .notif-wrap{position:relative;}
        .notif-panel{position:absolute;top:calc(100% + 10px);right:0;width:320px;background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-md);z-index:500;display:none;overflow:hidden;}
        .notif-panel.open{display:block;animation:scaleIn .15s ease;}
        .notif-panel-head{padding:14px 16px 12px;border-bottom:1px solid var(--border);font-size:14px;font-weight:700;color:var(--text-1);}
        .notif-panel-body{padding:8px;max-height:280px;overflow-y:auto;}
        .notif-entry{display:flex;gap:10px;padding:10px;border-radius:var(--radius-sm);margin-bottom:4px;}
        .notif-entry.warn-red{background:#FEF2F2;}
        .notif-entry.warn-blue{background:#EFF6FF;}
        .notif-entry:last-child{margin-bottom:0;}
        .notif-icon-wrap{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;margin-top:1px;}
        .notif-entry-text{font-size:12.5px;color:var(--text-1);line-height:1.55;}
        .notif-entry-text strong{font-weight:600;}
        .notif-panel-foot{padding:10px 16px;border-top:1px solid var(--border);text-align:center;font-size:12.5px;color:var(--blue);cursor:pointer;font-weight:600;transition:background .15s;}
        .notif-panel-foot:hover{background:var(--blue-light);}

        /* Profile button — pill style (matches admin, green accent) */
        .prof-wrap{position:relative;}
        .prof-btn{
            display:flex;align-items:center;gap:8px;
            padding:5px 10px 5px 5px;border:1px solid var(--border);border-radius:40px;
            background:var(--white);cursor:pointer;transition:border-color .15s;
            font-family:'Plus Jakarta Sans',sans-serif;
        }
        .prof-btn:hover{border-color:var(--green-mid);}
        .prof-ava{width:30px;height:30px;border-radius:50%;background:var(--green);color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;}
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
        .dosen-hamburger{display:none;width:36px;height:36px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--white);cursor:pointer;align-items:center;justify-content:center;color:var(--text-2);font-size:16px;flex-shrink:0;margin-left:auto;}

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
        .mobile-drawer-link.active{background:var(--green-light);color:var(--green);font-weight:600;}
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

        .status-active{background:#DCFCE7;color:#166534;border-radius:20px;padding:3px 11px;font-size:12px;font-weight:600;}
        .status-inactive{background:#FEE2E2;color:#991B1B;border-radius:20px;padding:3px 11px;font-size:12px;font-weight:600;}

        @keyframes scaleIn{from{opacity:0;transform:scale(.96) translateY(-4px);}to{opacity:1;transform:scale(1) translateY(0);}}

        /* ── Risk Alert Banner ───────────────────────── */
        .risk-alert-wrap{position:relative;background:linear-gradient(135deg,#1A0A0A 0%,#7F1D1D 40%,#991B1B 100%);border-radius:14px;padding:20px 24px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;gap:16px;overflow:hidden;box-shadow:0 8px 32px rgba(239,68,68,.25),0 2px 8px rgba(239,68,68,.15);animation:alertSlideIn .4s cubic-bezier(.16,1,.3,1) both;flex-wrap:wrap;}
        @keyframes alertSlideIn{from{opacity:0;transform:translateY(-12px) scale(.98);}to{opacity:1;transform:translateY(0) scale(1);}}
        @keyframes alertSlideOut{from{opacity:1;transform:translateY(0) scale(1);max-height:200px;margin-bottom:24px;padding:20px 24px;}to{opacity:0;transform:translateY(-8px) scale(.97);max-height:0;margin-bottom:0;padding:0 24px;}}
        .risk-alert-wrap::before{content:'';position:absolute;inset:0;background-image:radial-gradient(circle,rgba(255,255,255,.06) 1px,transparent 1px);background-size:24px 24px;pointer-events:none;}
        .risk-alert-wrap::after{content:'';position:absolute;top:0;left:-100%;width:60%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.04),transparent);animation:glowSweep 4s ease infinite;pointer-events:none;}
        @keyframes glowSweep{0%{left:-60%;}100%{left:140%;}}
        .risk-pulse-ring{position:absolute;left:28px;top:50%;transform:translateY(-50%);width:52px;height:52px;border-radius:50%;background:rgba(239,68,68,.2);animation:ringPulse 2s ease-out infinite;pointer-events:none;}
        @keyframes ringPulse{0%{transform:translateY(-50%) scale(1);opacity:.8;}70%{transform:translateY(-50%) scale(1.8);opacity:0;}100%{transform:translateY(-50%) scale(1);opacity:0;}}
        .risk-alert-left{display:flex;align-items:flex-start;gap:16px;flex:1;min-width:0;position:relative;z-index:1;}
        .risk-alert-icon{width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:20px;color:#FCA5A5;flex-shrink:0;animation:iconShake 3s ease infinite;}
        @keyframes iconShake{0%,90%,100%{transform:rotate(0deg);}92%{transform:rotate(-8deg);}94%{transform:rotate(8deg);}96%{transform:rotate(-4deg);}98%{transform:rotate(4deg);}}
        .risk-alert-content{min-width:0;}
        .risk-alert-tag{display:inline-flex;align-items:center;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);border-radius:20px;padding:2px 10px;font-size:11px;font-weight:700;color:#FCA5A5;letter-spacing:.5px;margin-bottom:6px;}
        .risk-alert-title{font-size:15px;font-weight:800;color:#fff;line-height:1.3;margin-bottom:5px;letter-spacing:-.2px;}
        .risk-alert-desc{font-size:12.5px;color:rgba(255,255,255,.7);line-height:1.5;}
        .risk-alert-desc strong{color:#FCA5A5;font-weight:700;}
        .risk-alert-right{display:flex;align-items:center;gap:10px;flex-shrink:0;position:relative;z-index:1;}
        .risk-alert-btn{background:#fff;color:#991B1B;border:none;border-radius:9px;padding:10px 18px;font-size:13px;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:7px;transition:all .2s;white-space:nowrap;box-shadow:0 2px 8px rgba(0,0,0,.2);}
        .risk-alert-btn:hover{background:#FEF2F2;color:#7F1D1D;transform:translateY(-2px);box-shadow:0 6px 16px rgba(0,0,0,.25);}
        .risk-alert-close{width:34px;height:34px;border-radius:8px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.7);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:13px;transition:all .2s;flex-shrink:0;}
        .risk-alert-close:hover{background:rgba(255,255,255,.2);color:#fff;border-color:rgba(255,255,255,.3);transform:scale(1.05);}

        /* ── Responsive ───────────────────────────── */
        @media(max-width:768px){
            .dosen-nav-items,.dosen-nav-right{display:none;}
            .dosen-hamburger{display:flex;}
            .page-wrap{padding:16px;}
            .ac-table-v2{display:block;overflow-x:auto;-webkit-overflow-scrolling:touch;}
            .tbl-card-v2{padding:16px;}
            .stat-card-value{font-size:24px;letter-spacing:-.8px;}
            .stat-card-body{padding:14px 16px;gap:10px;}
            .stat-icon-box{width:38px;height:38px;font-size:17px;}
            .donut-wrap-v2{gap:12px;}
            .search-wrap input{width:100px;}
            .tbl-head-v2{gap:8px;}
            .notif-panel{width:280px;right:-40px;}
            .risk-alert-wrap{padding:16px 18px;gap:14px;}
            .risk-alert-right{width:100%;justify-content:space-between;}
            .risk-alert-btn{flex:1;justify-content:center;}
            .risk-pulse-ring{display:none;}
        }
        @media(max-width:576px){
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

{{-- ── MOBILE DRAWER ── --}}
<div class="mobile-drawer-overlay" id="drawerOverlay"></div>
<nav class="mobile-drawer" id="mobileDrawer">
    <span class="mobile-drawer-brand">Academia</span>
    <a href="{{ route('dosen.dashboard') }}"
       class="mobile-drawer-link {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>
    <a href="{{ route('dosen.kelas') }}"
       class="mobile-drawer-link {{ request()->routeIs('dosen.kelas') ? 'active' : '' }}">
        <i class="bi bi-grid-3x3-gap-fill"></i> Kelas
    </a>
    <a href="{{ route('dosen.berisiko.index') }}"
       class="mobile-drawer-link {{ request()->routeIs('dosen.berisiko*') ? 'active' : '' }}">
        <i class="bi bi-exclamation-triangle-fill"></i> Mahasiswa Berisiko
    </a>
    <a href="{{ route('dosen.kompensasi.index') }}"
       class="mobile-drawer-link {{ request()->routeIs('dosen.kompensasi*') ? 'active' : '' }}">
        <i class="bi bi-clipboard2-check-fill"></i> Kompensasi
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
<header class="dosen-navbar">
    <a href="{{ route('dosen.dashboard') }}" class="dosen-nav-brand">Academia</a>

    <div class="dosen-nav-items">
        <a href="{{ route('dosen.dashboard') }}"
           class="dosen-nav-link {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
            Dashboard
        </a>
        <a href="{{ route('dosen.kelas') }}"
           class="dosen-nav-link {{ request()->routeIs('dosen.kelas') ? 'active' : '' }}">
            Kelas
        </a>
        <a href="{{ route('dosen.berisiko.index') }}"
           class="dosen-nav-link {{ request()->routeIs('dosen.berisiko*') ? 'active' : '' }}">
            Berisiko
        </a>
        <a href="{{ route('dosen.kompensasi.index') }}"
           class="dosen-nav-link {{ request()->routeIs('dosen.kompensasi*') ? 'active' : '' }}">
            Kompensasi
        </a>
    </div>

    <div class="dosen-nav-right">
        <div class="notif-wrap">
            <button class="nav-icon-btn" id="notifToggle" aria-label="Notifikasi">
                <i class="bi bi-bell-fill"></i>
                @if(isset($totalBerisiko) && $totalBerisiko > 0)
                <span class="nav-notif-badge">{{ $totalBerisiko }}</span>
                @endif
            </button>
            <div class="notif-panel" id="notifPanel">
                <div class="notif-panel-head">Notifikasi DPA</div>
                <div class="notif-panel-body">
                    @if(isset($mahasiswaBerisiko) && $mahasiswaBerisiko->count() > 0)
                        @foreach($mahasiswaBerisiko->take(5) as $mhs)
                        <div class="notif-entry warn-red">
                            <div class="notif-icon-wrap" style="background:#FEE2E2;color:#EF4444;">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                            <div class="notif-entry-text">
                                <strong>{{ is_array($mhs) ? $mhs['nama'] : $mhs->nama }}</strong> — Segera lakukan bimbingan.
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div style="text-align:center;padding:20px;color:var(--text-3);font-size:13px;">
                        <i class="bi bi-check-circle" style="font-size:22px;color:var(--blue-mid);display:block;margin-bottom:4px;"></i>
                        Tidak ada mahasiswa berisiko
                    </div>
                    @endif
                </div>
                <div class="notif-panel-foot">Lihat semua mahasiswa</div>
            </div>
        </div>

        <div class="prof-wrap">
            <button class="prof-btn" id="profBtn" aria-label="Profile">
                <div class="prof-ava">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <div class="prof-name">{{ Str::limit(auth()->user()->name, 14) }}</div>
                    <div class="prof-role-lbl">Dosen PA</div>
                </div>
                <i class="bi bi-chevron-down" style="font-size:11px;color:var(--text-3);"></i>
            </button>
            <div class="prof-dd" id="profMenu">
                <div class="prof-dd-info">
                    <div class="prof-dd-name">{{ auth()->user()->name }}</div>
                    <div class="prof-dd-role">Dosen PA</div>
                </div>
                <form id="dosen-logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
                <button class="prof-dd-logout" onclick="event.preventDefault();document.getElementById('dosen-logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </div>
        </div>
    </div>

    <button class="dosen-hamburger" id="dosenHamburger" aria-label="Menu">
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
    var hamburger = document.getElementById('dosenHamburger');
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

    // Filter menus
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
