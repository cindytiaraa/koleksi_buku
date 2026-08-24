@extends('layouts.landing')

@section('title', 'FAQ - Library Management System')
@section('nav-faq', 'active')

@section('content')

    <section class="py-5" style="background-color: var(--lp-bg);">
        <div class="container py-4">

            <div class="text-center mb-5">
                <h1 class="fw-bold">Frequently Asked Questions</h1>
                <p class="text-muted">Pertanyaan yang sering diajukan seputar sistem ini</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">

                        <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-600" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Apa itu Library Management System ini?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Sistem ini adalah platform manajemen perpustakaan berbasis web yang membantu
                                    pengelolaan koleksi buku, anggota, dan transaksi peminjaman secara digital.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-600" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Bagaimana cara masuk ke sistem?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Klik tombol "Login" pada navbar, kemudian masukkan kredensial akun Anda
                                    untuk mengakses dashboard sesuai peran (Admin, Petugas, atau Anggota).
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-600" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Apakah sistem ini mendukung barcode dan QR Code?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Ya. Sistem mendukung pemindaian barcode dan QR Code untuk mempercepat
                                    proses sirkulasi dan pencarian data buku maupun anggota.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-600" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    Bagaimana cara melihat koleksi buku tanpa login?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Anda dapat mengunjungi halaman "Catalog" untuk melihat pratinjau koleksi
                                    buku secara publik tanpa perlu login terlebih dahulu.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-600" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    Apakah pendaftaran anggota baru sudah tersedia?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Fitur pendaftaran mandiri (Register) saat ini masih dalam pengembangan.
                                    Untuk sementara, pendaftaran anggota dilakukan oleh Admin atau Petugas.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-600" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                    Bagaimana jika saya mengalami kendala teknis?
                                </button>
                            </h2>
                            <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Silakan hubungi kami melalui halaman "Contact" dan tim kami akan membantu
                                    menyelesaikan kendala yang Anda alami.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

@push('styles')
<style>
    .accordion-button:not(.collapsed) {
        background-color: rgba(108,99,255,0.08);
        color: var(--lp-primary);
        box-shadow: none;
    }
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(108,99,255,0.25);
    }
</style>
@endpush
