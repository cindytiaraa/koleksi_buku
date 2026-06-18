<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Petugas') — Koleksi Buku</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        :root {
            --primary-900:#1e3a8a;
            --primary-800:#1e40af;
            --primary-700:#1d4ed8;
            --primary-600:#2563eb;
            --primary-500:#3b82f6;
            --primary-100:#dbeafe;
            --primary-50:#eff6ff;
            --sidebar-w:240px;
            --radius:10px;
            --shadow:0 2px 8px rgba(30,64,175,.10);
        }
        *{box-sizing:border-box;}
        body{margin:0;font-family:'Segoe UI',sans-serif;background:#f0f5ff;display:flex;min-height:100vh;}

        .sidebar{width:var(--sidebar-w);min-height:100vh;background:linear-gradient(180deg,var(--primary-900) 0%,var(--primary-800) 100%);color:#fff;position:fixed;top:0;left:0;bottom:0;z-index:100;display:flex;flex-direction:column;}
        .sb-brand{padding:22px 20px 18px;font-size:1.05rem;font-weight:700;border-bottom:1px solid rgba(255,255,255,.1);letter-spacing:.3px;display:flex;align-items:center;gap:8px;}
        .sb-brand .logo{font-size:1.5rem;}
        .sb-nav{flex:1;padding:10px 0;overflow-y:auto;}
        .sb-section{font-size:.65rem;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:rgba(255,255,255,.4);padding:14px 20px 4px;}
        .sb-nav a{display:flex;align-items:center;gap:10px;padding:9px 20px;color:rgba(255,255,255,.75);text-decoration:none;font-size:.875rem;transition:background .15s;border-left:3px solid transparent;}
        .sb-nav a:hover{background:rgba(255,255,255,.08);color:#fff;}
        .sb-nav a.active{background:rgba(255,255,255,.12);color:#fff;border-left-color:var(--primary-500);font-weight:600;}
        .sb-nav a i{font-size:1rem;width:18px;text-align:center;}
        .sb-footer{padding:16px 20px;border-top:1px solid rgba(255,255,255,.1);}
        .sb-footer .uname{font-size:.85rem;font-weight:600;color:#fff;}
        .sb-footer .urole{font-size:.72rem;color:rgba(255,255,255,.5);margin-bottom:10px;}
        .btn-logout{display:block;width:100%;padding:6px;text-align:center;border:1px solid rgba(255,255,255,.3);color:rgba(255,255,255,.8);border-radius:6px;font-size:.8rem;background:transparent;cursor:pointer;transition:.15s;}
        .btn-logout:hover{background:rgba(255,255,255,.12);color:#fff;}

        .main{margin-left:var(--sidebar-w);flex:1;padding:24px;}
        .topbar{background:#fff;border-radius:var(--radius);padding:13px 20px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;box-shadow:var(--shadow);}
        .topbar .pg-title{font-size:1.05rem;font-weight:700;color:var(--primary-900);margin:0;}
        .role-pill{background:var(--primary-100);color:var(--primary-800);font-size:.72rem;font-weight:700;padding:4px 12px;border-radius:20px;}

        .alert{border:none;border-radius:var(--radius);font-size:.88rem;border-left:4px solid;}
        .alert-success{background:#ecfdf5;color:#065f46;border-color:#10b981;}
        .alert-danger{background:#fef2f2;color:#991b1b;border-color:#ef4444;}
        .alert-warning{background:#fffbeb;color:#92400e;border-color:#f59e0b;}
        .alert-info{background:var(--primary-50);color:var(--primary-800);border-color:var(--primary-500);}

        .card{border:none;border-radius:var(--radius);box-shadow:var(--shadow);}
        .card-header{border-radius:var(--radius) var(--radius) 0 0!important;font-weight:600;font-size:.9rem;}
        .card-header.blue{background:var(--primary-700);color:#fff;}
        .card-header.light{background:var(--primary-50);color:var(--primary-800);border-bottom:1px solid var(--primary-100);}

        .table thead th{background:var(--primary-50);color:var(--primary-800);font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;border:none;padding:10px 14px;}
        .table tbody td{font-size:.875rem;padding:10px 14px;vertical-align:middle;border-color:#f0f5ff;}
        .table tbody tr:hover{background:var(--primary-50);}
        .table-bordered{border:1px solid var(--primary-100)!important;}

        .badge{font-size:.72rem;font-weight:600;padding:4px 10px;border-radius:20px;}
        .badge-blue{background:var(--primary-100);color:var(--primary-800);}
        .badge-green{background:#dcfce7;color:#166534;}
        .badge-red{background:#fee2e2;color:#991b1b;}
        .badge-yellow{background:#fef9c3;color:#854d0e;}
        .badge-gray{background:#f1f5f9;color:#475569;}

        .btn-primary{background:var(--primary-600);border-color:var(--primary-600);}
        .btn-primary:hover{background:var(--primary-700);border-color:var(--primary-700);}
        .btn-outline-primary{border-color:var(--primary-600);color:var(--primary-600);}
        .btn-outline-primary:hover{background:var(--primary-600);color:#fff;}
        .btn-sm{font-size:.78rem;padding:4px 10px;}

        .form-control{border-color:#cbd5e1;border-radius:8px;font-size:.875rem;}
        .form-control:focus{border-color:var(--primary-500);box-shadow:0 0 0 3px rgba(59,130,246,.15);}
        label{font-size:.83rem;font-weight:600;color:#374151;margin-bottom:4px;}

        .stat-card{border-radius:12px;padding:20px;color:#fff;border:none;box-shadow:var(--shadow);}
        .stat-card h2{font-size:1.9rem;font-weight:700;margin:0;}
        .stat-card p{margin:4px 0 0;opacity:.85;font-size:.82rem;}

        .page-footer{background:var(--primary-900);color:rgba(255,255,255,.65);text-align:center;padding:16px;margin-top:40px;font-size:.85rem;}
    </style>
    @yield('style_page')
</head>
<body>

<div class="sidebar">
    <div class="sb-brand">
        <span class="logo">📚</span> Koleksi Buku
    </div>
    <nav class="sb-nav">
        <div class="sb-section">Utama</div>
        <a href="{{ route('petugas.dashboard') }}" class="{{ request()->routeIs('petugas.dashboard') ? 'active':'' }}">
            <i class="mdi mdi-view-dashboard-outline"></i> Dashboard
        </a>

        <div class="sb-section">Peminjaman</div>
        <a href="{{ route('petugas.peminjaman.index') }}" class="{{ request()->routeIs('petugas.peminjaman.index') ? 'active':'' }}">
            <i class="mdi mdi-format-list-bulleted"></i> Daftar Peminjaman
        </a>
        <a href="{{ route('petugas.peminjaman.create') }}" class="{{ request()->routeIs('petugas.peminjaman.create') ? 'active':'' }}">
            <i class="mdi mdi-plus-circle-outline"></i> Catat Pinjam Baru
        </a>

        <div class="sb-section">Penjualan</div>
        <a href="{{ route('petugas.penjualan.pos') }}" class="{{ request()->routeIs('petugas.penjualan.pos') ? 'active':'' }}">
            <i class="mdi mdi-cash-register"></i> Kasir / POS
        </a>
        <a href="{{ route('petugas.penjualan.riwayat') }}" class="{{ request()->routeIs('petugas.penjualan.riwayat') ? 'active':'' }}">
            <i class="mdi mdi-history"></i> Riwayat Penjualan
        </a>

        <div class="sb-section">Antrian</div>
        <a href="{{ route('antrian.admin') }}" class="{{ request()->is('admin/antrian') ? 'active':'' }}">
            <i class="mdi mdi-ticket-confirmation"></i> Kelola Antrian
        </a>
        <a href="{{ route('antrian.riwayat') }}" class="{{ request()->is('admin/antrian/riwayat') ? 'active':'' }}">
            <i class="mdi mdi-clock-outline"></i> Riwayat Antrian
        </a>
        <a href="{{ route('antrian.papan') }}" target="_blank">
            <i class="mdi mdi-television"></i> Papan Antrian <i class="mdi mdi-open-in-new" style="font-size:.7rem;"></i>
        </a>
        <a href="{{ route('antrian.landing') }}" target="_blank">
            <i class="mdi mdi-account-arrow-right"></i> Halaman Ambil Antrian <i class="mdi mdi-open-in-new" style="font-size:.7rem;"></i>
        </a>
    </nav>
    <div class="sb-footer">
        <div class="uname">{{ Auth::user()->name }}</div>
        <div class="urole">Petugas Perpustakaan</div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout"><i class="mdi mdi-logout"></i> Logout</button>
        </form>
    </div>
</div>

<div class="main">
    <div class="topbar">
        <h5 class="pg-title">@yield('page_title','Dashboard')</h5>
        <span class="role-pill">👷 Petugas</span>
    </div>

    @foreach(['success','error','warning','info'] as $type)
        @if(session($type))
        <div class="alert alert-{{ $type }} alert-dismissible fade show mb-3" role="alert">
            {{ session($type) }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
        @endif
    @endforeach

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3">
        <ul class="mb-0 pl-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    @endif

    @yield('content')
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@yield('js_page')
</body>
</html>
