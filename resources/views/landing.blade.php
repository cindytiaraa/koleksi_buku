@extends('layouts.landing')

@section('title', 'Digital Library Management System')
@section('nav-home', 'active')

@section('content')

    <!-- ===== HERO ===== -->
    <section class="py-5" style="background: linear-gradient(135deg, #F8F9FC 0%, #EEEBFF 100%); overflow: hidden;">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="badge rounded-pill px-3 py-2 mb-3" style="background: rgba(108,99,255,0.12); color: var(--lp-primary); font-weight: 600;">
                        <i class="bi bi-stars me-1"></i> Sistem Perpustakaan Digital
                    </span>
                    <h1 class="fw-bold mb-3" style="font-size: 2.9rem; line-height: 1.2;">
                        Digital <span class="text-lp-primary">Library</span> Management System
                    </h1>
                    <p class="fs-5 mb-4" style="color: #5a6478;">
                        Kelola buku, anggota, transaksi, barcode, pembayaran, dan berbagai layanan
                        perpustakaan dalam satu sistem.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ url('/login') }}" class="btn btn-lp-gradient">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Sistem
                        </a>
                        <a href="{{ url('/catalog') }}" class="btn btn-lp-outline">
                            <i class="bi bi-journal-bookmark me-1"></i> Lihat Katalog
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="p-5 rounded-4" style="background: #fff; box-shadow: 0 25px 60px rgba(108,99,255,0.18);">
                        <i class="bi bi-building-fill-check" style="font-size: 9rem; color: var(--lp-primary); opacity: 0.9;"></i>
                        <div class="row mt-3 g-3">
                            <div class="col-4">
                                <i class="bi bi-upc-scan fs-2 text-lp-primary"></i>
                                <div class="small mt-1 fw-500">Barcode</div>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-qr-code fs-2 text-lp-primary"></i>
                                <div class="small mt-1">QR Code</div>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-credit-card-2-front fs-2 text-lp-primary"></i>
                                <div class="small mt-1">Payment</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== STATISTIK ===== -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-6 col-lg-3">
                    <h2 class="fw-bold text-lp-primary mb-0">10.000+</h2>
                    <p class="text-muted mb-0">Koleksi Buku</p>
                </div>
                <div class="col-6 col-lg-3">
                    <h2 class="fw-bold text-lp-primary mb-0">3.500+</h2>
                    <p class="text-muted mb-0">Member</p>
                </div>
                <div class="col-6 col-lg-3">
                    <h2 class="fw-bold text-lp-primary mb-0">1.250+</h2>
                    <p class="text-muted mb-0">Peminjaman</p>
                </div>
                <div class="col-6 col-lg-3">
                    <h2 class="fw-bold text-lp-primary mb-0">99%</h2>
                    <p class="text-muted mb-0">Uptime</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FITUR ===== -->
    <section class="py-5" style="background-color: var(--lp-bg);">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Fitur Unggulan</h2>
                <p class="text-muted">Semua yang dibutuhkan untuk mengelola perpustakaan modern</p>
            </div>
            <div class="row g-4">
                @php
                    $features = [
                        ['icon' => 'bi-journal-richtext', 'title' => 'Manajemen Buku'],
                        ['icon' => 'bi-upc-scan', 'title' => 'Barcode'],
                        ['icon' => 'bi-qr-code', 'title' => 'QR Code'],
                        ['icon' => 'bi-broadcast', 'title' => 'NFC'],
                        ['icon' => 'bi-geo-alt', 'title' => 'Geolocation'],
                        ['icon' => 'bi-credit-card-2-front', 'title' => 'Payment Gateway'],
                        ['icon' => 'bi-speedometer2', 'title' => 'Dashboard Admin'],
                        ['icon' => 'bi-person-badge', 'title' => 'Dashboard Petugas'],
                    ];
                @endphp
                @foreach ($features as $f)
                    <div class="col-sm-6 col-lg-3">
                        <div class="p-4 rounded-4 h-100 bg-white text-center" style="box-shadow: 0 5px 20px rgba(0,0,0,0.05); transition: all .25s;">
                            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
                                 style="width: 64px; height: 64px; background: rgba(108,99,255,0.1);">
                                <i class="bi {{ $f['icon'] }} fs-3 text-lp-primary"></i>
                            </div>
                            <h6 class="fw-600 mb-0">{{ $f['title'] }}</h6>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== PREVIEW DASHBOARD ===== -->
    <section class="py-5 bg-white">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="badge rounded-pill px-3 py-2 mb-3" style="background: rgba(139,92,246,0.12); color: var(--lp-secondary); font-weight: 600;">
                        Preview
                    </span>
                    <h2 class="fw-bold mb-3">Dashboard yang Intuitif</h2>
                    <p class="text-muted fs-5">
                        Pantau statistik peminjaman, stok buku, transaksi, dan aktivitas member
                        secara real-time dari satu dashboard terpadu.
                    </p>
                    <a href="{{ url('/login') }}" class="btn btn-lp-gradient mt-2">
                        Masuk ke Sistem <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="col-lg-6">
                    <div class="rounded-4 overflow-hidden" style="box-shadow: 0 25px 60px rgba(0,0,0,0.12);">
                        <div class="p-2 d-flex gap-2" style="background: #2D3748;">
                            <span class="rounded-circle" style="width:10px;height:10px;background:#ff5f56;display:inline-block;"></span>
                            <span class="rounded-circle" style="width:10px;height:10px;background:#ffbd2e;display:inline-block;"></span>
                            <span class="rounded-circle" style="width:10px;height:10px;background:#27c93f;display:inline-block;"></span>
                        </div>
                        <div class="p-4" style="background: linear-gradient(135deg,#6C63FF,#8B5CF6);">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.15);">
                                        <i class="bi bi-graph-up-arrow text-white fs-4"></i>
                                        <div class="text-white small mt-1">Statistik Peminjaman</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.15);">
                                        <i class="bi bi-box-seam text-white fs-4"></i>
                                        <div class="text-white small mt-1">Stok Buku</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.15);">
                                        <i class="bi bi-bar-chart-line text-white fs-4"></i>
                                        <div class="text-white small mt-1">Ringkasan Transaksi Bulanan</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CTA ===== -->
    <section class="py-5" style="background: linear-gradient(135deg, var(--lp-primary), var(--lp-secondary));">
        <div class="container py-4 text-center">
            <h2 class="fw-bold text-white mb-3">Siap Mengelola Perpustakaan Anda?</h2>
            <p class="text-white-50 fs-5 mb-4">Mulai kelola koleksi buku Anda dengan sistem yang modern dan efisien.</p>
            <a href="{{ url('/login') }}" class="btn btn-light btn-lg rounded-pill px-5 fw-600">
                <i class="bi bi-box-arrow-in-right me-1"></i> Login
            </a>
        </div>
    </section>

@endsection
