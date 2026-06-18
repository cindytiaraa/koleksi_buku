<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Toko Buku Online')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css">
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
            --radius:10px;
            --shadow:0 2px 8px rgba(30,64,175,.10);
        }
        body{margin:0;background:#f0f5ff;font-family:'Segoe UI',sans-serif;}

        .topnav{background:#fff;padding:12px 0;box-shadow:0 2px 8px rgba(30,64,175,.12);}
        .topnav .navbar-brand{color:var(--primary-900);font-weight:700;font-size:1.2rem;}
        .topnav .nav-link{color:rgba(30,64,175,.85) !important;}
        .topnav .nav-link:hover,.topnav .nav-link.active{color:var(--primary-900) !important;}
        .topnav .btn-logout{border:1px solid rgba(30,64,175,.4);color:var(--primary-900);padding:4px 14px;border-radius:20px;font-size:.85rem;}
        .topnav .btn-logout:hover{background:rgba(37,99,235,.12);color:#fff;}

        .card{border:none;border-radius:var(--radius);box-shadow:var(--shadow);}
        .card-book{transition:transform .2s;}
        .card-book:hover{transform:translateY(-4px);}
        .card-book .card-img-top{height:180px;object-fit:cover;border-radius:12px 12px 0 0;background:linear-gradient(135deg,var(--primary-700),var(--primary-500));display:flex;align-items:center;justify-content:center;font-size:3rem;color:#fff;}

        .badge-user{background:var(--primary-500);color:#fff;font-size:.75rem;padding:3px 10px;border-radius:20px;}

        .page-footer{background:var(--primary-900);color:rgba(255,255,255,.7);text-align:center;padding:16px;margin-top:40px;font-size:.85rem;}

        .alert{border:none;border-radius:var(--radius);font-size:.88rem;border-left:4px solid;}
        .alert-success{background:#ecfdf5;color:#065f46;border-color:#10b981;}
        .alert-danger{background:#fef2f2;color:#991b1b;border-color:#ef4444;}
        .alert-warning{background:#fffbeb;color:#92400e;border-color:#f59e0b;}
        .alert-info{background:var(--primary-50);color:var(--primary-800);border-color:var(--primary-500);}

        .btn-primary{background:var(--primary-600);border-color:var(--primary-600);}
        .btn-primary:hover{background:var(--primary-700);border-color:var(--primary-700);}
        .btn-outline-primary{border-color:var(--primary-600);color:var(--primary-600);}
        .btn-outline-primary:hover{background:var(--primary-600);color:#fff;}

        .form-control{border-color:#cbd5e1;border-radius:8px;font-size:.875rem;}
        .form-control:focus{border-color:var(--primary-500);box-shadow:0 0 0 3px rgba(59,130,246,.15);}
        label{font-size:.83rem;font-weight:600;color:#374151;margin-bottom:4px;}

        .table thead th{background:var(--primary-50);color:var(--primary-800);font-size:.77rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;border:none;padding:10px 14px;}
        .table tbody td{font-size:.875rem;padding:10px 14px;vertical-align:middle;border-color:#f0f5ff;}
        .table tbody tr:hover{background:var(--primary-50);}
    </style>    @yield('style_page')
</head>
<body>

<nav class="topnav navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ route('user.dashboard') }}">📚 Koleksi Buku</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navUser">
            <span style="color:white;font-size:1.5rem">☰</span>
        </button>
        <div class="collapse navbar-collapse" id="navUser">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"
                       href="{{ route('user.dashboard') }}">
                        <i class="mdi mdi-home"></i> Katalog
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.order') ? 'active' : '' }}"
                       href="{{ route('user.order') }}">
                        <i class="mdi mdi-cart"></i> Beli Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.riwayat_pinjam') ? 'active' : '' }}"
                       href="{{ route('user.riwayat_pinjam') }}">
                        <i class="mdi mdi-book-open-variant"></i> Riwayat Pinjam
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.riwayat_beli') ? 'active' : '' }}"
                       href="{{ route('user.riwayat_beli') }}">
                        <i class="mdi mdi-receipt"></i> Riwayat Beli
                    </a>
                </li>
            </ul>
            <div class="navbar-nav align-items-center">
                <span class="nav-link text-white" style="opacity:.8">
                    <i class="mdi mdi-account-circle"></i> {{ Auth::user()->name }}
                </span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout ml-2">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @yield('content')
</div>

<div class="page-footer">
    &copy; {{ date('Y') }} Koleksi Buku &mdash; Semua hak dilindungi
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@yield('js_page')
</body>
</html>
