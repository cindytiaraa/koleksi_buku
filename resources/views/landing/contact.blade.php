@extends('layouts.landing')

@section('title', 'Contact - Library Management System')
@section('nav-contact', 'active')

@section('content')

    <section class="py-5" style="background-color: var(--lp-bg);">
        <div class="container py-4">

            <div class="text-center mb-5">
                <h1 class="fw-bold">Hubungi Kami</h1>
                <p class="text-muted">Ada pertanyaan? Kirimkan pesan Anda kepada kami</p>
            </div>

            <div class="row g-4">
                <!-- Form -->
                <div class="col-lg-6">
                    <div class="bg-white p-4 p-md-5 rounded-4 h-100" style="box-shadow: 0 5px 25px rgba(0,0,0,0.06);">
                        <form>
                            <div class="mb-3">
                                <label class="form-label fw-500">Nama</label>
                                <input type="text" class="form-control" placeholder="Masukkan nama Anda">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-500">Email</label>
                                <input type="email" class="form-control" placeholder="nama@email.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-500">Pesan</label>
                                <textarea class="form-control" rows="5" placeholder="Tulis pesan Anda di sini..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-lp-gradient w-100">
                                <i class="bi bi-send me-1"></i> Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Info + Map -->
                <div class="col-lg-6">
                    <div class="bg-white p-4 p-md-5 rounded-4 mb-4" style="box-shadow: 0 5px 25px rgba(0,0,0,0.06);">
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3 d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                 style="width: 46px; height: 46px; background: rgba(108,99,255,0.1);">
                                <i class="bi bi-geo-alt text-lp-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-600 mb-1">Alamat</h6>
                                <p class="text-muted small mb-0">Malang, Jawa Timur, Indonesia</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3 d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                 style="width: 46px; height: 46px; background: rgba(108,99,255,0.1);">
                                <i class="bi bi-envelope text-lp-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-600 mb-1">Email</h6>
                                <p class="text-muted small mb-0">info@koleksibuku.id</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <div class="me-3 d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                 style="width: 46px; height: 46px; background: rgba(108,99,255,0.1);">
                                <i class="bi bi-telephone text-lp-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-600 mb-1">Telepon</h6>
                                <p class="text-muted small mb-0">(0341) 000-000</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dummy Map -->
                    <div class="rounded-4 overflow-hidden" style="height: 220px; box-shadow: 0 5px 25px rgba(0,0,0,0.06);">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126926.5!2d112.6!3d-7.98!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwNTgnNDguMCJTIDExMsKwMzcnMTIuMCJF!5e0!3m2!1sen!2sid!4v1234567890"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
