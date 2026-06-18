@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-qrcode-scan"></i>
        </span>
        Praktikum 2: QR Code Reader (Admin / Vendor)
    </h3>
</div>

<div class="row">
    <!-- Kolom Scanner -->
    <div class="col-lg-5 grid-margin stretch-card">
        <div class="card bg-white shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-light-primary p-2 rounded-circle me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; background-color: rgba(145, 118, 251, 0.1);">
                        <i class="mdi mdi-qrcode text-primary" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h4 class="card-title mb-0 font-weight-bold text-dark">Scan Transaksi</h4>
                        <p class="text-muted small mb-0">Dekatkan QR Code HP Customer ke kamera</p>
                    </div>
                </div>
                
                <!-- Frame Kamera Scanner -->
                <div class="position-relative overflow-hidden bg-dark mb-4 shadow-inner" style="border-radius: 12px; min-height: 280px; border: 3px solid #eee; display: flex; align-items: center; justify-content: center;">
                    <div id="reader" style="width: 100%; border: none;"></div>
                    <div id="scannerOverlay" class="position-absolute w-100 h-100" style="top: 0; left: 0; background: rgba(0,0,0,0.5); display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 10; pointer-events: none;">
                        <i class="mdi mdi-video-off text-white mb-2" style="font-size: 3rem; opacity: 0.6;"></i>
                        <span class="text-white small font-weight-bold" style="opacity: 0.8;">Kamera Dinonaktifkan</span>
                    </div>
                </div>
                
                <div class="text-center">
                    <button id="btnStart" class="btn btn-gradient-primary btn-lg px-4 py-2 text-white border-0 font-weight-bold" style="border-radius: 30px; box-shadow: 0 4px 15px rgba(145, 118, 251, 0.4);">
                        <i class="mdi mdi-play me-2"></i> Aktifkan Kamera Scanner
                    </button>
                    <button id="btnStop" class="btn btn-gradient-danger btn-lg px-4 py-2 text-white border-0 font-weight-bold" style="border-radius: 30px; display: none; box-shadow: 0 4px 15px rgba(254, 86, 117, 0.4);">
                        <i class="mdi mdi-stop me-2"></i> Matikan Kamera
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Hasil Scan Transaksi -->
    <div class="col-lg-7 grid-margin stretch-card">
        <!-- 1. KARTU HASIL SCAN PEMBELIAN / PESANAN (KONSEP KANTIN) -->
        <div class="card bg-white shadow-sm border-0" id="cardOrderResult" style="display: none; border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title text-primary mb-0 font-weight-bold">
                        <i class="mdi mdi-cart-check me-2"></i> Detail Pembelian Buku
                    </h4>
                    <span id="orderStatusBadge" class="badge px-3 py-1 font-weight-bold" style="border-radius: 10px;">Lunas</span>
                </div>
                <hr style="border-top: 1px solid #eee;">

                <!-- Informasi Pembelian -->
                <div class="bg-light p-3 rounded mb-4" style="background-color: #f8f9fa;">
                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            <span class="text-muted small d-block">ID Pesanan (Midtrans)</span>
                            <strong id="orderIdText" class="text-dark">ORDER-XXXXX</strong>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <span class="text-muted small d-block">Nama Pembeli</span>
                            <strong id="orderCustomerText" class="text-dark">Cindy Tiara</strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted small d-block">Metode Pembayaran</span>
                            <strong id="orderPaymentText" class="text-dark">QRIS / GoPay</strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted small d-block">Waktu Transaksi</span>
                            <strong id="orderTimeText" class="text-dark">20 May 2026 22:30</strong>
                        </div>
                    </div>
                </div>

                <!-- Daftar Buku (Menu yang Dipesan) -->
                <h5 class="font-weight-bold text-dark mb-2"><i class="mdi mdi-book-open-page-variant me-2"></i> Daftar Buku yang Dipesan:</h5>
                <div class="table-responsive mb-3">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Judul Buku</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="orderItemsTable">
                            <!-- Baris item akan dimasukkan dinamis lewat JS -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end font-weight-bold" style="font-size: 1.1rem;">Total Belanja:</th>
                                <th id="orderTotalText" class="text-end text-primary font-weight-bold" style="font-size: 1.1rem;">Rp 0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Tombol Konfirmasi Pembayaran jika belum lunas -->
                <div id="divConfirmPayment" class="mt-4" style="display: none;">
                    <button id="btnConfirmPayment" class="btn btn-success btn-lg w-100 py-3 text-white border-0 font-weight-bold" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);">
                        <i class="mdi mdi-cash-multiple me-2"></i> Konfirmasi Pembayaran Lunas (Tunai)
                    </button>
                </div>

                <div class="mt-3">
                    <button class="btn btn-gradient-primary w-100 py-3 text-white border-0 font-weight-bold" onclick="resetScanner()" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(145, 118, 251, 0.2);">
                        <i class="mdi mdi-refresh me-2"></i> Scan Ulang Transaksi Lain
                    </button>
                </div>
            </div>
        </div>

        <!-- 2. KARTU HASIL SCAN PEMINJAMAN BUKU -->
        <div class="card bg-white shadow-sm border-0" id="cardBorrowResult" style="display: none; border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title text-info mb-0 font-weight-bold">
                        <i class="mdi mdi-book-reader me-2"></i> Detail Peminjaman Buku
                    </h4>
                    <span id="borrowStatusBadge" class="badge px-3 py-1 font-weight-bold" style="border-radius: 10px;">Dipinjam</span>
                </div>
                <hr style="border-top: 1px solid #eee;">

                <!-- Informasi Anggota & Buku -->
                <div class="row mb-4">
                    <div class="col-sm-6 mb-3">
                        <span class="text-muted small d-block">Nama Peminjam</span>
                        <strong id="borrowerNameText" class="text-dark" style="font-size: 1.1rem;">-</strong>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <span class="text-muted small d-block">Buku yang Dipinjam</span>
                        <strong id="borrowBookTitleText" class="text-primary" style="font-size: 1.1rem;">-</strong>
                        <span id="borrowBookAuthorText" class="text-muted d-block small">-</span>
                        <span id="borrowBookCodeText" class="badge bg-light text-dark font-weight-normal mt-1 border">-</span>
                    </div>
                    <div class="col-sm-4 mb-2">
                        <span class="text-muted small d-block">Tanggal Pinjam</span>
                        <strong id="borrowPinjamDateText" class="text-dark">-</strong>
                    </div>
                    <div class="col-sm-4 mb-2">
                        <span class="text-muted small d-block">Batas Pengembalian</span>
                        <strong id="borrowReturnDateText" class="text-dark">-</strong>
                    </div>
                    <div class="col-sm-4 mb-2">
                        <span class="text-muted small d-block">Denda Akumulasi</span>
                        <strong id="borrowDendaText" class="text-danger font-weight-bold">-</strong>
                    </div>
                </div>

                <!-- Tombol Proses Pengembalian Buku jika masih aktif dipinjam -->
                <div id="divConfirmBorrowReturn" class="mt-4" style="display: none;">
                    <button id="btnConfirmBorrowReturn" class="btn btn-info btn-lg w-100 py-3 text-white border-0 font-weight-bold" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(13, 202, 240, 0.3);">
                        <i class="mdi mdi-keyboard-return me-2"></i> Proses Pengembalian Buku (Kembalikan Sekarang)
                    </button>
                </div>

                <div class="mt-3">
                    <button class="btn btn-gradient-primary w-100 py-3 text-white border-0 font-weight-bold" onclick="resetScanner()" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(145, 118, 251, 0.2);">
                        <i class="mdi mdi-refresh me-2"></i> Scan Ulang Transaksi Lain
                    </button>
                </div>
            </div>
        </div>

        <!-- Placeholder Sebelum Scan -->
        <div class="card bg-white shadow-sm border-0" id="cardPlaceholder" style="border-radius: 15px;">
            <div class="card-body text-center py-5 d-flex flex-column align-items-center justify-content-center" style="min-height: 420px;">
                <div class="bg-light p-4 rounded-circle mb-3" style="background-color: #f8f9fa;">
                    <i class="mdi mdi-qrcode-scan text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
                </div>
                <h5 class="text-muted font-weight-bold mt-2">Menunggu QR Code Transaksi...</h5>
                <p class="text-muted small px-4">Kamera ini dapat mendeteksi **QR Code Pembelian (Alur Kantin)** maupun **QR Code Peminjaman** milik anggota secara instan dan realtime.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_page')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let html5QrCode;
    let isScanning = false;
    let currentScannedId = null; // Menyimpan ID transaksi aktif

    $(document).ready(function() {
        html5QrCode = new Html5Qrcode("reader");

        $('#btnStart').click(function() {
            startScanner();
        });

        $('#btnStop').click(function() {
            stopScanner();
        });

        // Handler konfirmasi pembayaran pesanan (Alur Kantin)
        $('#btnConfirmPayment').click(function() {
            if (!currentScannedId) return;
            
            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: "Apakah Anda yakin ingin menandai transaksi pembelian ini telah dibayar Lunas?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Lunas!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/scanner-qr/bayar/${currentScannedId}`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pembayaran Sukses',
                                    text: response.message
                                });
                                
                                // Update tampilan status menjadi lunas
                                $('#orderStatusBadge')
                                    .removeClass('bg-warning')
                                    .addClass('bg-success')
                                    .text('Lunas');
                                $('#divConfirmPayment').fadeOut();
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON.message || 'Terjadi kesalahan sistem.'
                            });
                        }
                    });
                }
            });
        });

        // Handler proses pengembalian buku (Peminjaman)
        $('#btnConfirmBorrowReturn').click(function() {
            if (!currentScannedId) return;

            Swal.fire({
                title: 'Proses Pengembalian',
                text: "Apakah Anda yakin ingin memproses pengembalian buku fisik ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0dcaf0',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Kembalikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/scanner-qr/kembalikan/${currentScannedId}`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Buku Dikembalikan',
                                    text: response.message
                                });

                                // Update status tampilan
                                $('#borrowStatusBadge')
                                    .removeClass('bg-warning bg-danger')
                                    .addClass('bg-success')
                                    .text('Dikembalikan');
                                $('#borrowDendaText').removeClass('text-danger').addClass('text-success').text('Rp 0 (Selesai)');
                                $('#divConfirmBorrowReturn').fadeOut();
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON.message || 'Terjadi kesalahan sistem.'
                            });
                        }
                    });
                }
            });
        });
    });

    // Pembangkitan bunyi beep dinamis dengan Web Audio API
    function playBeep() {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);
            
            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(1200, audioCtx.currentTime); // Beep nyaring 1200 Hz
            gainNode.gain.setValueAtTime(0.2, audioCtx.currentTime);
            
            oscillator.start();
            oscillator.stop(audioCtx.currentTime + 0.15); // bunyi selama 0.15 detik
        } catch (error) {
            console.warn("Browser memblokir audio atau Web Audio tidak disupport:", error);
        }
    }

    function startScanner() {
        $('#cardOrderResult').hide();
        $('#cardBorrowResult').hide();
        $('#cardPlaceholder').show();
        $('#scannerOverlay').hide();
        currentScannedId = null;
        
        // Konfigurasi area scan terfokus persegi (ideal untuk QR Code)
        const config = { 
            fps: 25, 
            qrbox: { width: 220, height: 220 }, // Area scan QR presisi persegi
            aspectRatio: 1.0,
            showTorchButtonIfSupported: true
        };

        html5QrCode.start(
            { facingMode: "environment" }, 
            config, 
            onScanSuccess
        ).then(() => {
            isScanning = true;
            $('#btnStart').hide();
            $('#btnStop').show();
        }).catch((err) => {
            Swal.fire({
                icon: 'error',
                title: 'Kamera Gagal',
                text: "Gagal mengakses kamera perangkat: " + err
            });
        });
    }

    function stopScanner() {
        return new Promise((resolve) => {
            if (isScanning) {
                html5QrCode.stop().then(() => {
                    isScanning = false;
                    $('#btnStart').show();
                    $('#btnStop').hide();
                    $('#scannerOverlay').show();
                    resolve(true);
                }).catch((err) => {
                    console.error("Gagal menghentikan kamera: ", err);
                    resolve(false);
                });
            } else {
                resolve(true);
            }
        });
    }

    function onScanSuccess(decodedText, decodedResult) {
        if (!isScanning) return; // Proteksi pembacaan ganda
        
        isScanning = false;
        
        // 1. Dikeluarkan bunyi beep pendek nyaring
        playBeep();
        
        // 2. Scanner otomatis berhenti
        stopScanner().then(() => {
            Swal.fire({
                title: 'Mendekode QR Code...',
                text: 'Memproses data transaksi',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            // 3. Ambil data transaksi dari database berdasarkan hasil scan
            $.ajax({
                url: `/admin/scanner-qr/cek/${decodedText}`,
                method: 'GET',
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        if (response.type === 'pesanan') {
                            // TAMPILKAN HASIL SCAN PEMBELIAN BUKU (ALUR KANTIN)
                            const order = response.data;
                            currentScannedId = order.idpesanan;
                            
                            $('#orderIdText').text(order.midtrans_order_id);
                            $('#orderCustomerText').text(order.nama_customer);
                            $('#orderPaymentText').text(order.metode_bayar);
                            $('#orderTimeText').text(order.tanggal);
                            $('#orderTotalText').text(order.total);
                            
                            // Set Badge Status
                            $('#orderStatusBadge').removeClass('bg-success bg-warning text-white');
                            if (order.status_bayar == 1) {
                                $('#orderStatusBadge').addClass('bg-success text-white').text('Lunas');
                                $('#divConfirmPayment').hide();
                            } else {
                                $('#orderStatusBadge').addClass('bg-warning text-dark').text('Belum Lunas');
                                $('#divConfirmPayment').fadeIn();
                            }
                            
                            // Isi tabel daftar buku yang dipesan (menu yang dipesan)
                            let rows = '';
                            order.items.forEach(item => {
                                rows += `
                                    <tr>
                                        <td><strong>${item.judul}</strong></td>
                                        <td class="text-center font-weight-bold">${item.jumlah}</td>
                                        <td class="text-end">${item.harga}</td>
                                        <td class="text-end text-dark font-weight-bold">${item.subtotal}</td>
                                    </tr>
                                `;
                            });
                            $('#orderItemsTable').html(rows);

                            $('#cardPlaceholder').hide();
                            $('#cardBorrowResult').hide();
                            $('#cardOrderResult').fadeIn();
                            
                        } else if (response.type === 'peminjaman') {
                            // TAMPILKAN HASIL SCAN PEMINJAMAN BUKU
                            const borrow = response.data;
                            currentScannedId = borrow.idpeminjaman;
                            
                            $('#borrowerNameText').text(borrow.nama_anggota);
                            $('#borrowBookTitleText').text(borrow.buku_judul);
                            $('#borrowBookAuthorText').text(borrow.buku_pengarang);
                            $('#borrowBookCodeText').text('Kode Buku: ' + borrow.buku_kode);
                            $('#borrowPinjamDateText').text(borrow.tgl_pinjam);
                            $('#borrowReturnDateText').text(borrow.tgl_kembali_rencana);
                            $('#borrowDendaText').text(borrow.denda);
                            
                            // Set Badge Status
                            $('#borrowStatusBadge').removeClass('bg-success bg-warning bg-danger text-white text-dark');
                            if (borrow.status == 1) {
                                $('#borrowStatusBadge').addClass('bg-success text-white').text('Dikembalikan');
                                $('#borrowDendaText').removeClass('text-danger').addClass('text-success');
                                $('#divConfirmBorrowReturn').hide();
                            } else if (borrow.status == 0) {
                                $('#borrowStatusBadge').addClass('bg-warning text-dark').text('Dipinjam');
                                $('#borrowDendaText').addClass('text-danger');
                                $('#divConfirmBorrowReturn').fadeIn();
                            } else if (borrow.status == 2) {
                                $('#borrowStatusBadge').addClass('bg-danger text-white').text('Terlambat');
                                $('#borrowDendaText').addClass('text-danger');
                                $('#divConfirmBorrowReturn').fadeIn();
                            }

                            $('#cardPlaceholder').hide();
                            $('#cardOrderResult').hide();
                            $('#cardBorrowResult').fadeIn();
                        }
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    let msg = 'Terjadi kesalahan sistem';
                    if (xhr.status === 404) {
                        msg = `Kode QR "${decodedText}" tidak terdaftar atau transaksi tidak ditemukan.`;
                    }
                    
                    Swal.fire({
                        icon: 'warning',
                        title: 'Transaksi Tidak Ditemukan',
                        text: msg
                    }).then(() => {
                        resetScanner();
                    });
                }
            });
        });
    }

    function resetScanner() {
        $('#cardOrderResult').hide();
        $('#cardBorrowResult').hide();
        $('#cardPlaceholder').show();
        startScanner();
    }
</script>
@endsection
