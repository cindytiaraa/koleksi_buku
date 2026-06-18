<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Anggota') — Koleksi Buku</title>
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
        .topnav .container-inner{max-width:1200px;margin:0 auto;padding:0 20px;display:flex;align-items:center;height:64px;gap:8px;}
        .topnav .brand{font-size:1rem;font-weight:700;color:var(--primary-900);text-decoration:none;display:flex;align-items:center;gap:6px;margin-right:20px;white-space:nowrap;}
        .topnav .brand:hover{color:var(--primary-900);text-decoration:none;}
        .nav-links{display:flex;align-items:center;gap:2px;flex:1;}
        .nav-links a{color:rgba(30,64,175,.8);text-decoration:none;padding:6px 12px;border-radius:6px;font-size:.85rem;transition:.15s;display:flex;align-items:center;gap:5px;}
        .nav-links a:hover{background:rgba(59,130,246,.12);color:var(--primary-900);}
        .nav-links a.active{background:rgba(59,130,246,.18);color:var(--primary-900);font-weight:600;}
        .nav-right{display:flex;align-items:center;gap:8px;margin-left:auto;}
        .nav-user{color:rgba(30,64,175,.8);font-size:.83rem;display:flex;align-items:center;gap:5px;}
        .btn-nav-logout{border:1px solid rgba(30,64,175,.4);color:var(--primary-900);background:transparent;padding:5px 14px;border-radius:6px;font-size:.8rem;cursor:pointer;transition:.15s;}
        .btn-nav-logout:hover{background:rgba(37,99,235,.12);color:#fff;}

        .alert{border:none;border-radius:var(--radius);font-size:.88rem;border-left:4px solid;}
        .alert-success{background:#ecfdf5;color:#065f46;border-color:#10b981;}
        .alert-danger{background:#fef2f2;color:#991b1b;border-color:#ef4444;}
        .alert-warning{background:#fffbeb;color:#92400e;border-color:#f59e0b;}
        .alert-info{background:var(--primary-50);color:var(--primary-800);border-color:var(--primary-500);}

        .card{border:none;border-radius:var(--radius);box-shadow:var(--shadow);}
        .card-book{transition:transform .2s;}
        .card-book:hover{transform:translateY(-4px);}
        .card-book .card-img-top{height:180px;object-fit:cover;border-radius:12px 12px 0 0;background:linear-gradient(135deg,var(--primary-700),var(--primary-500));display:flex;align-items:center;justify-content:center;font-size:3rem;color:#fff;}

        .badge-user{background:var(--primary-600);color:#fff;font-size:.75rem;padding:3px 10px;border-radius:20px;}

        .page-footer{background:var(--primary-900);color:rgba(255,255,255,.7);text-align:center;padding:16px;margin-top:40px;font-size:.85rem;}

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

<nav class="topnav">
    <div class="container-inner">
        <a href="{{ route('user.dashboard') }}" class="brand">📚 Koleksi Buku</a>
        <div class="nav-links">
            <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active':'' }}">
                <i class="mdi mdi-home-outline"></i> Katalog
            </a>
            <a href="{{ route('user.order') }}" class="{{ request()->routeIs('user.order') ? 'active':'' }}">
                <i class="mdi mdi-cart-outline"></i> Beli Buku
            </a>
            <a href="{{ route('user.riwayat_pinjam') }}" class="{{ request()->routeIs('user.riwayat_pinjam') ? 'active':'' }}">
                <i class="mdi mdi-book-open-variant"></i> Riwayat Pinjam
            </a>
            <a href="{{ route('user.riwayat_beli') }}" class="{{ request()->routeIs('user.riwayat_beli') ? 'active':'' }}">
                <i class="mdi mdi-receipt-outline"></i> Riwayat Beli
            </a>
        </div>
        <div class="nav-right">
            <span class="nav-user"><i class="mdi mdi-account-circle"></i> {{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                @csrf
                <button type="submit" class="btn-nav-logout">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container" style="max-width:1200px;padding:0 20px;">

    @foreach(['success','error','warning','info'] as $type)
        @if(session($type))
        <div class="alert alert-{{ $type }} alert-dismissible fade show mt-3" role="alert">
            {{ session($type) }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
        @endif
    @endforeach

    @yield('content')
</div>

<div class="page-footer">&copy; {{ date('Y') }} Koleksi Buku — Semua hak dilindungi</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@yield('js_page')
</body>
</html>
