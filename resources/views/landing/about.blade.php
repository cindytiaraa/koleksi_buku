@extends('layouts.landing')

@section('title', 'Tentang Kami - Library Management System')
@section('nav-about', 'active')

@section('content')

    <section class="py-5" style="background-color: var(--lp-bg);">
        <div class="container py-4">
            <div class="row align-items-center g-5 mb-5">
                <div class="col-lg-6">
                    <span class="badge rounded-pill px-3 py-2 mb-3" style="background: rgba(108,99,255,0.12); color: var(--lp-primary); font-weight: 600;">
                        Tentang Project
                    </span>
                    <h1 class="fw-bold mb-3">Library Management System berbasis Laravel</h1>
                    <p class="fs-5 text-muted">
                        Sistem manajemen perpustakaan modern yang dibangun menggunakan framework Laravel,
                        dirancang untuk mempermudah pengelolaan koleksi buku, anggota, dan seluruh proses
                        transaksi perpustakaan dalam satu platform terintegrasi.
                    </p>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="bi bi-mortarboard-fill" style="font-size: 10rem; color: var(--lp-primary); opacity: 0.85;"></i>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Teknologi yang Didukung</h2>
                <p class="text-muted">Sistem ini dibangun dengan berbagai teknologi modern</p>
            </div>
            <div class="row g-4">
                @php
                    $tech = [
                        ['icon' => 'bi-upc-scan', 'title' => 'Barcode', 'desc' => 'Pemindaian cepat untuk katalogisasi dan sirkulasi buku.'],
                        ['icon' => 'bi-qr-code', 'title' => 'QR Code', 'desc' => 'Akses informasi buku dan anggota secara instan.'],
                        ['icon' => 'bi-broadcast', 'title' => 'NFC', 'desc' => 'Identifikasi kartu anggota tanpa kontak.'],
                        ['icon' => 'bi-credit-card-2-front', 'title' => 'Payment Gateway', 'desc' => 'Pembayaran denda dan transaksi secara digital.'],
                        ['icon' => 'bi-geo-alt', 'title' => 'Geolocation', 'desc' => 'Pelacakan lokasi cabang dan layanan perpustakaan.'],
                        ['icon' => 'bi-broadcast-pin', 'title' => 'SSE (Server-Sent Events)', 'desc' => 'Notifikasi dan pembaruan data secara real-time.'],
                        ['icon' => 'bi-person-check', 'title' => 'Role Management', 'desc' => 'Pengaturan hak akses Admin, Petugas, dan Anggota.'],
                        ['icon' => 'bi-journal-richtext', 'title' => 'Manajemen Koleksi', 'desc' => 'Pengelolaan data buku, kategori, dan stok secara menyeluruh.'],
                    ];
                @endphp
                @foreach ($tech as $t)
                    <div class="col-sm-6 col-lg-3">
                        <div class="p-4 rounded-4 h-100 text-center" style="background: var(--lp-bg);">
                            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
                                 style="width: 60px; height: 60px; background: rgba(108,99,255,0.1);">
                                <i class="bi {{ $t['icon'] }} fs-4 text-lp-primary"></i>
                            </div>
                            <h6 class="fw-600 mb-2">{{ $t['title'] }}</h6>
                            <p class="small text-muted mb-0">{{ $t['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-5" style="background: linear-gradient(135deg, var(--lp-primary), var(--lp-secondary));">
        <div class="container py-4 text-center">
            <h2 class="fw-bold text-white mb-3">Ingin Menjelajahi Sistem Lebih Lanjut?</h2>
            <a href="{{ url('/catalog') }}" class="btn btn-light btn-lg rounded-pill px-5 fw-600">
                Lihat Katalog Buku
            </a>
        </div>
    </section>

@endsection
