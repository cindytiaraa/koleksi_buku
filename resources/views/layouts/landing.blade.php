<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Digital Library Management System')</title>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --lp-primary: #6C63FF;
            --lp-secondary: #8B5CF6;
            --lp-bg: #F8F9FC;
            --lp-text: #2D3748;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--lp-bg);
            color: var(--lp-text);
        }

        a {
            text-decoration: none;
        }

        /* ===== Navbar ===== */
        .lp-navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.06);
            padding-top: 0.85rem;
            padding-bottom: 0.85rem;
        }

        .lp-navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--lp-primary);
        }

        .lp-navbar .navbar-brand i {
            margin-right: 0.4rem;
        }

        .lp-navbar .nav-link {
            font-weight: 500;
            color: var(--lp-text);
            margin: 0 0.4rem;
            padding: 0.5rem 0.75rem !important;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .lp-navbar .nav-link:hover,
        .lp-navbar .nav-link.active {
            color: var(--lp-primary);
            background-color: rgba(108, 99, 255, 0.08);
        }

        .lp-navbar .btn-lp-login {
            background: linear-gradient(135deg, var(--lp-primary), var(--lp-secondary));
            color: #fff;
            font-weight: 600;
            padding: 0.5rem 1.4rem;
            border-radius: 50px;
            border: none;
            transition: all 0.25s ease;
        }

        .lp-navbar .btn-lp-login:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            color: #fff;
        }

        .lp-navbar .btn-lp-register {
            font-weight: 500;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            color: #a0aec0;
            pointer-events: none;
            border: 1px solid #e2e8f0;
        }

        /* ===== Footer ===== */
        .lp-footer {
            background-color: #1A1D2E;
            color: #cbd5e0;
            padding-top: 3rem;
            padding-bottom: 1.5rem;
        }

        .lp-footer h5 {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .lp-footer .footer-brand {
            font-weight: 700;
            font-size: 1.3rem;
            color: #ffffff;
        }

        .lp-footer .footer-brand i {
            color: var(--lp-primary);
            margin-right: 0.4rem;
        }

        .lp-footer a {
            color: #cbd5e0;
            transition: color 0.2s ease;
        }

        .lp-footer a:hover {
            color: var(--lp-primary);
        }

        .lp-footer .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 2rem;
            padding-top: 1.5rem;
            font-size: 0.9rem;
            color: #a0aec0;
        }

        /* ===== Utilities ===== */
        .text-lp-primary { color: var(--lp-primary) !important; }
        .bg-lp-primary { background-color: var(--lp-primary) !important; }
        .btn-lp-gradient {
            background: linear-gradient(135deg, var(--lp-primary), var(--lp-secondary));
            color: #fff;
            border: none;
            font-weight: 600;
            padding: 0.75rem 1.8rem;
            border-radius: 50px;
            transition: all 0.25s ease;
        }
        .btn-lp-gradient:hover {
            opacity: 0.92;
            transform: translateY(-2px);
            color: #fff;
            box-shadow: 0 10px 25px rgba(108, 99, 255, 0.35);
        }
        .btn-lp-outline {
            border: 2px solid var(--lp-primary);
            color: var(--lp-primary);
            font-weight: 600;
            padding: 0.75rem 1.8rem;
            border-radius: 50px;
            background: transparent;
            transition: all 0.25s ease;
        }
        .btn-lp-outline:hover {
            background-color: var(--lp-primary);
            color: #fff;
            transform: translateY(-2px);
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- ===== Navbar ===== -->
    <nav class="navbar navbar-expand-lg lp-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-book-half"></i>Koleksi Buku
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#lpNavbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="lpNavbarContent">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link @yield('nav-home')" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('nav-catalog')" href="{{ url('/catalog') }}">Catalog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('nav-about')" href="{{ url('/about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('nav-faq')" href="{{ url('/faq') }}">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('nav-contact')" href="{{ url('/contact') }}">Contact</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-lp-register btn-sm" href="#" tabindex="-1" aria-disabled="true">Register</a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn btn-lp-login btn-sm" href="{{ url('/login') }}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ===== Page Content ===== -->
    <main>
        @yield('content')
    </main>

    <!-- ===== Footer ===== -->
    <footer class="lp-footer">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand mb-2">
                        <i class="bi bi-book-half"></i>Library Management System
                    </div>
                    <p class="small mb-0">
                        Kelola buku, anggota, transaksi, barcode, pembayaran, dan berbagai layanan
                        perpustakaan dalam satu sistem terpadu.
                    </p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ url('/') }}">Home</a></li>
                        <li class="mb-2"><a href="{{ url('/catalog') }}">Catalog</a></li>
                        <li class="mb-2"><a href="{{ url('/about') }}">About</a></li>
                        <li class="mb-2"><a href="{{ url('/faq') }}">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Contact</h5>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i>info@koleksibuku.id</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i>(0341) 000-000</li>
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>Malang, Jawa Timur, Indonesia</li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Ikuti Kami</h5>
                    <div class="d-flex gap-3 fs-5">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom text-center">
                &copy; {{ date('Y') }} Library Management System. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
