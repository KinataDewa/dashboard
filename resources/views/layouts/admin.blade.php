<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Academia — @yield('title', 'Admin Panel')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue:#2563EB;--blue-hover:#1D4ED8;--blue-light:#EFF6FF;--blue-mid:#BFDBFE;
            --bg:#F1F5F9;--white:#FFFFFF;--sidebar-w:240px;
            --text-1:#0F172A;--text-2:#64748B;--text-3:#94A3B8;--border:#E2E8F0;
            --radius:12px;--radius-sm:8px;
            --shadow-sm:0 1px 2px rgba(0,0,0,.05);
            --shadow:0 1px 3px rgba(0,0,0,.07),0 1px 2px rgba(0,0,0,.05);
            --shadow-md:0 4px 12px rgba(0,0,0,.08),0 2px 4px rgba(0,0,0,.04);
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text-1);min-height:100vh;display:flex;font-size:14px;line-height:1.5;-webkit-font-smoothing:antialiased;}
 
        .sidebar{width:var(--sidebar-w);background:var(--white);border-right:1px solid var(--border);position:fixed;top:0;left:0;height:100vh;display:flex;flex-direction:column;z-index:300;transition:transform .25s ease;}
        .sidebar-header{padding:24px 20px 20px;border-bottom:1px solid var(--border);}
        .sidebar-brand{font-size:20px;font-weight:800;color:var(--blue);letter-spacing:-.4px;text-decoration:none;display:block;}
        .sidebar-badge{display:inline-block;background:var(--blue-light);color:var(--blue);border-radius:6px;padding:1px 8px;font-size:10px;font-weight:700;margin-top:4px;}
        .sidebar-nav{padding:16px 12px;flex:1;overflow-y:auto;}
        .sidebar-nav::-webkit-scrollbar{width:0;}
        .nav-label{font-size:10px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:1px;padding:8px 8px 4px;display:block;}
        .nav-link-item{display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:var(--radius-sm);color:var(--text-2);font-size:13.5px;font-weight:500;text-decoration:none;margin-bottom:2px;transition:all .15s ease;position:relative;}
        .nav-link-item i{font-size:16px;width:20px;text-align:center;flex-shrink:0;}
        .nav-link-item:hover{background:var(--blue-light);color:var(--blue);}
        .nav-link-item.active{background:var(--blue-light);color:var(--blue);font-weight:600;}
        .nav-link-item.active::before{content:'';position:absolute;left:-12px;top:7px;bottom:7px;width:3px;background:var(--blue);border-radius:0 3px 3px 0;}
        .sidebar-footer{padding:12px;border-top:1px solid var(--border);}
        .sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.3);z-index:299;}
 
        .main-wrap{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh;min-width:0;}
 
        .topbar{background:var(--white);border-bottom:1px solid var(--border);padding:0 24px;height:68px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:200;gap:16px;}
        .topbar-left{display:flex;align-items:center;gap:12px;min-width:0;flex:1;}
        .hamburger{display:none;width:36px;height:36px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--white);cursor:pointer;align-items:center;justify-content:center;color:var(--text-2);font-size:16px;flex-shrink:0;}
        .topbar-title{min-width:0;display:flex;flex-direction:column;justify-content:center;}
        .topbar-title h2{font-size:16px;font-weight:700;color:var(--text-1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin:0;line-height:1.3;}
        .topbar-title p{font-size:12px;color:var(--text-2);margin:2px 0 0 0;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
        .topbar-right{display:flex;align-items:center;gap:10px;flex-shrink:0;}
        .user-pill{display:flex;align-items:center;gap:8px;padding:5px 10px 5px 5px;border:1px solid var(--border);border-radius:40px;background:var(--white);cursor:pointer;transition:border-color .15s;flex-shrink:0;}
        .user-pill:hover{border-color:var(--blue-mid);}
        .user-ava{width:30px;height:30px;border-radius:50%;background:#7C3AED;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;}
        .user-info-name{font-size:12.5px;font-weight:600;color:var(--text-1);}
        .user-info-role{font-size:11px;color:var(--text-2);}
        .user-pill i{color:var(--text-3);font-size:11px;}
 
        .page-content{padding:24px;flex:1;}
        .flash{display:flex;align-items:center;gap:8px;padding:10px 14px;border-radius:var(--radius-sm);font-size:13px;margin-bottom:18px;}
        .flash-ok{background:#F0FDF4;border:1px solid #BBF7D0;color:#15803D;}
        .flash-err{background:#FEF2F2;border:1px solid #FECACA;color:#DC2626;}
 
        /* SHARED */
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
        .ac-table-v2 tbody td.muted{color:var(--text-2);}
        .badge{display:inline-flex;align-items:center;justify-content:center;padding:3px 11px;border-radius:20px;font-size:12px;font-weight:600;white-space:nowrap;}
        .badge-green{background:#DCFCE7;color:#166534;}
        .badge-red{background:#FEE2E2;color:#991B1B;}
        .badge-yellow{background:#FEF9C3;color:#854D0E;}
        .badge-purple{background:#F5F3FF;color:#6D28D9;}
        .badge-gray{background:#F1F5F9;color:#475569;}
        .search-wrap{display:flex;align-items:center;gap:7px;border:1px solid var(--border);border-radius:var(--radius-sm);padding:6px 11px;background:var(--white);transition:border-color .15s;}
        .search-wrap:focus-within{border-color:var(--blue);}
        .search-wrap i{color:var(--text-3);font-size:13px;flex-shrink:0;}
        .search-wrap input{border:none;outline:none;font-size:13px;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-1);background:transparent;width:150px;}
        .search-wrap input::placeholder{color:var(--text-3);}
        .filter-wrap{position:relative;}
        .btn-filter{display:flex;align-items:center;gap:6px;border:1px solid var(--border);border-radius:var(--radius-sm);padding:6px 11px;background:var(--white);font-size:13px;font-weight:500;color:var(--text-2);cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:all .15s;white-space:nowrap;}
        .btn-filter:hover{border-color:var(--blue);color:var(--blue);}
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
        .btn-edit{background:#DBEAFE;color:#1D4ED8;border:none;border-radius:var(--radius-sm);padding:5px 12px;font-size:12px;font-weight:600;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:all .15s;text-decoration:none;display:inline-flex;align-items:center;gap:4px;}
        .btn-edit:hover{background:#2563EB;color:#fff;}
        .btn-del{background:#FEE2E2;color:#991B1B;border:none;border-radius:var(--radius-sm);padding:5px 12px;font-size:12px;font-weight:600;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:all .15s;display:inline-flex;align-items:center;gap:4px;}
        .btn-del:hover{background:#EF4444;color:#fff;}
        .tbl-footer{display:flex;align-items:center;gap:10px;margin-top:14px;padding-top:12px;border-top:1px solid var(--border);flex-wrap:wrap;}
        .info-chip{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:11.5px;font-weight:600;background:#F1F5F9;color:var(--text-2);}
        .import-card{background:#F8FAFC;border:2px dashed var(--border);border-radius:var(--radius);padding:20px;text-align:center;cursor:pointer;transition:all .2s;}
        .import-card:hover{border-color:var(--blue);background:var(--blue-light);transform:translateY(-2px);}
        .import-card.done{border-color:#22C55E;background:#F0FDF4;border-style:solid;}
        .import-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;margin:0 auto 12px;}
        .import-title{font-size:13.5px;font-weight:700;color:var(--text-1);margin-bottom:4px;}
        .import-desc{font-size:11.5px;color:var(--text-2);margin-bottom:14px;line-height:1.5;}
        .select-semester{border:1px solid var(--border);border-radius:var(--radius-sm);padding:8px 32px 8px 12px;font-size:13.5px;font-weight:500;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-1);background:var(--white) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 10px center;-webkit-appearance:none;outline:none;cursor:pointer;box-shadow:var(--shadow-sm);}
        .select-semester:focus{border-color:var(--blue);}
        .semester-bar{display:flex;align-items:center;gap:10px;margin-bottom:20px;}
        .status-active{background:#DCFCE7;color:#166534;border-radius:20px;padding:3px 11px;font-size:12px;font-weight:600;}
        .status-inactive{background:#FEE2E2;color:#991B1B;border-radius:20px;padding:3px 11px;font-size:12px;font-weight:600;}
        @keyframes scaleIn{from{opacity:0;transform:scale(.96) translateY(-4px);}to{opacity:1;transform:scale(1) translateY(0);}}
        @media(max-width:768px){.sidebar{transform:translateX(-100%);}.sidebar.open{transform:translateX(0);}.sidebar-overlay.open{display:block;}.main-wrap{margin-left:0;}.hamburger{display:flex;}.page-content{padding:16px;}.topbar{padding:0 16px;height:60px;}.topbar-title h2{font-size:14px;}.topbar-title p{font-size:11px;}.user-info-wrap{display:none;}}
        @media(max-width:400px){.topbar-title p{display:none;}}
    </style>
    @stack('styles')
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">Academia</a>
        <span class="sidebar-badge">Admin Panel</span>
    </div>
    <nav class="sidebar-nav">
        <span class="nav-label">Dashboard</span>
        <a href="{{ route('admin.dashboard') }}" class="nav-link-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> <span>Overview</span>
        </a>
        <span class="nav-label">Import Data</span>
        <a href="{{ route('admin.import.index') }}" class="nav-link-item {{ request()->routeIs('admin.import*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-arrow-up-fill"></i> <span>Import Data</span>
        </a>
        <span class="nav-label">Kelola Data</span>
        <a href="{{ route('admin.mahasiswa.index') }}" class="nav-link-item {{ request()->routeIs('admin.mahasiswa*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> <span>Mahasiswa</span>
        </a>
        <a href="{{ route('admin.dosen.index') }}" class="nav-link-item {{ request()->routeIs('admin.dosen*') ? 'active' : '' }}">
            <i class="bi bi-person-badge-fill"></i> <span>Dosen</span>
        </a>
        <a href="{{ route('admin.matkul.index') }}" class="nav-link-item {{ request()->routeIs('admin.matkul*') ? 'active' : '' }}">
            <i class="bi bi-book-fill"></i> <span>Mata Kuliah</span>
        </a>
        <a href="{{ route('admin.kelas.index') }}" class="nav-link-item {{ request()->routeIs('admin.kelas*') ? 'active' : '' }}">
            <i class="bi bi-grid-3x3-gap-fill"></i> <span>Kelas</span>
        </a>
    </nav>
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link-item w-100 border-0 bg-transparent text-start" style="color:#EF4444;cursor:pointer;">
                <i class="bi bi-box-arrow-left"></i> <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
<div class="main-wrap">
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2>@yield('page-title', 'Admin Panel')</h2>
                <p>@yield('page-sub', 'Panel Admin Jurusan Teknologi Informasi')</p>
            </div>
        </div>
        <div class="topbar-right">
            @yield('topbar-actions')
            <div class="user-pill">
                <div class="user-ava">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="user-info-wrap">
                    <div class="user-info-name">{{ auth()->user()->name }}</div>
                    <div class="user-info-role">Admin Jurusan</div>
                </div>
                <i class="bi bi-chevron-down"></i>
            </div>
        </div>
    </header>
    <main class="page-content">
        @if(session('success'))
        <div class="flash flash-ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="flash flash-err"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
        @endif
        @yield('content')
    </main>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
    var sidebar=document.getElementById('sidebar'),overlay=document.getElementById('sidebarOverlay'),hamburger=document.getElementById('hamburgerBtn');
    hamburger?.addEventListener('click',()=>{sidebar.classList.toggle('open');overlay.classList.toggle('open');});
    overlay?.addEventListener('click',()=>{sidebar.classList.remove('open');overlay.classList.remove('open');});
    function initFilterMenus(){
        document.querySelectorAll('.filter-wrap').forEach(wrap=>{
            var btn=wrap.querySelector('.btn-filter'),menu=wrap.querySelector('.filter-menu');
            if(!btn||!menu)return;
            btn.addEventListener('click',(e)=>{e.stopPropagation();var isOpen=menu.classList.contains('open');closeAll();if(!isOpen)menu.classList.add('open');});
            menu.querySelectorAll('.filter-opt').forEach(opt=>{
                opt.addEventListener('click',(e)=>{e.stopPropagation();menu.querySelectorAll('.filter-opt').forEach(o=>o.classList.remove('active'));opt.classList.add('active');menu.classList.remove('open');btn.dispatchEvent(new CustomEvent('filterChange',{detail:{value:opt.dataset.val,label:opt.textContent.trim()}}));});
            });
        });
    }
    function closeAll(except){document.querySelectorAll('.filter-menu.open,.notif-panel.open').forEach(el=>{if(el!==except)el.classList.remove('open');});}
    document.addEventListener('click',()=>closeAll());
    initFilterMenus();
    window.initFilterMenus=initFilterMenus;
})();
</script>
@stack('scripts')
</body>
</html>