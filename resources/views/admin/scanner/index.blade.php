@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-barcode-scan"></i>
        </span>
        Praktikum 1: Barcode Reader Buku
    </h3>
</div>

<div class="row">
    <!-- Kolom Scanner -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card bg-white shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-light-primary p-2 rounded-circle me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; background-color: rgba(145, 118, 251, 0.1);">
                        <i class="mdi mdi-camera text-primary" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h4 class="card-title mb-0 font-weight-bold text-dark">Kamera Scanner</h4>
                        <p class="text-muted small mb-0">Arahkan kamera perangkat Anda ke barcode label buku fisik</p>
                    </div>
                </div>
                
                <!-- Frame Scanner Modern -->
                <div class="position-relative overflow-hidden bg-dark mb-4" style="border-radius: 12px; min-height: 280px; border: 3px solid #eee; display: flex; align-items: center; justify-content: center;">
                    <div id="reader" style="width: 100%; border: none;"></div>
                    <div id="scannerOverlay" class="position-absolute w-100 h-100" style="top: 0; left: 0; background: rgba(0,0,0,0.4); display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 10; pointer-events: none;">
                        <i class="mdi mdi-video-off text-white mb-2" style="font-size: 3rem; opacity: 0.7;"></i>
                        <span class="text-white small font-weight-bold" style="opacity: 0.8;">Kamera Dinonaktifkan</span>
                    </div>
                </div>
                
                <div class="text-center">
                    <button id="btnStart" class="btn btn-gradient-primary btn-lg px-4 py-2 text-white border-0 font-weight-bold" style="border-radius: 30px; box-shadow: 0 4px 15px rgba(145, 118, 251, 0.4);">
                        <i class="mdi mdi-play me-2"></i> Aktifkan Kamera
                    </button>
                    <button id="btnStop" class="btn btn-gradient-danger btn-lg px-4 py-2 text-white border-0 font-weight-bold" style="border-radius: 30px; display: none; box-shadow: 0 4px 15px rgba(254, 86, 117, 0.4);">
                        <i class="mdi mdi-stop me-2"></i> Matikan Kamera
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Hasil Scan -->
    <div class="col-md-6 grid-margin stretch-card">
        <!-- Kartu Hasil Scan -->
        <div class="card bg-white shadow-sm border-0" id="cardResult" style="display: none; border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title text-success mb-0 font-weight-bold">
                        <i class="mdi mdi-checkbox-marked-circle-outline me-2"></i> Hasil Scan Berhasil
                    </h4>
                    <span class="badge bg-success text-white px-3 py-1 font-weight-bold" style="border-radius: 10px;">Ditemukan</span>
                </div>
                <hr style="border-top: 1px solid #eee;">
                
                <div class="text-center mb-4 p-3 bg-light rounded" style="border: 1px dashed #ddd; background-color: #fbfbfb;">
                    <img id="resGambar" src="" class="img-fluid rounded shadow-sm" style="max-height: 160px; border: 3px solid #white; border-radius: 8px;">
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr style="border-bottom: 1px solid #f8f9fa;">
                                <td class="text-muted font-weight-bold" width="160" style="padding: 10px 0;">ID Barang (Buku)</td>
                                <td id="resIdbuku" class="font-weight-bold text-dark text-end" style="padding: 10px 0;"></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f8f9fa;">
                                <td class="text-muted font-weight-bold" style="padding: 10px 0;">Kode Barcode</td>
                                <td id="resKode" class="font-weight-bold text-secondary text-end" style="padding: 10px 0;"></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f8f9fa;">
                                <td class="text-muted font-weight-bold" style="padding: 10px 0;">Nama Barang (Judul)</td>
                                <td id="resJudul" class="font-weight-bold text-primary text-end" style="padding: 10px 0; font-size: 1.05rem;"></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f8f9fa;">
                                <td class="text-muted font-weight-bold" style="padding: 10px 0;">Pengarang</td>
                                <td id="resPengarang" class="text-dark text-end" style="padding: 10px 0;"></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f8f9fa;">
                                <td class="text-muted font-weight-bold" style="padding: 10px 0;">Kategori</td>
                                <td id="resKategori" class="text-dark text-end" style="padding: 10px 0;"></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f8f9fa;">
                                <td class="text-muted font-weight-bold" style="padding: 10px 0;">Harga Barang</td>
                                <td id="resHarga" class="text-success font-weight-bold text-end" style="padding: 10px 0; font-size: 1.2rem;"></td>
                            </tr>
                            <tr>
                                <td class="text-muted font-weight-bold" style="padding: 10px 0;">Stok Tersedia</td>
                                <td id="resStok" class="text-dark text-end" style="padding: 10px 0;"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <button class="btn btn-gradient-primary w-100 py-3 text-white border-0 font-weight-bold" onclick="resetScanner()" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(145, 118, 251, 0.3);">
                        <i class="mdi mdi-refresh me-2"></i> Scan Ulang (Barcode Baru)
                    </button>
                </div>
            </div>
        </div>

        <!-- Placeholder Sebelum Scan -->
        <div class="card bg-white shadow-sm border-0" id="cardPlaceholder" style="border-radius: 15px;">
            <div class="card-body text-center py-5 d-flex flex-column align-items-center justify-content-center" style="min-height: 400px;">
                <div class="bg-light p-4 rounded-circle mb-3" style="background-color: #f8f9fa;">
                    <i class="mdi mdi-barcode-scan text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
                </div>
                <h5 class="text-muted font-weight-bold mt-2">Menunggu Data Barcode...</h5>
                <p class="text-muted small px-4">Setelah kamera menyala, dekatkan barcode label barang ke area tengah kotak kamera agar terdeteksi otomatis.</p>
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

    $(document).ready(function() {
        html5QrCode = new Html5Qrcode("reader");

        $('#btnStart').click(function() {
            startScanner();
        });

        $('#btnStop').click(function() {
            stopScanner();
        });
    });

    // Pembangkitan bunyi beep dinamis dengan Web Audio API (Stabil & Offline-Ready)
    function playBeep() {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);
            
            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(1000, audioCtx.currentTime); // Frekuensi 1000 Hz (nyaring)
            gainNode.gain.setValueAtTime(0.2, audioCtx.currentTime); // Volume sedang
            
            oscillator.start();
            oscillator.stop(audioCtx.currentTime + 0.12); // Bunyi selama 0.12 detik (beep pendek)
        } catch (error) {
            console.warn("Browser memblokir pemutaran audio sebelum ada interaksi pengguna atau Web Audio API tidak disupport:", error);
        }
    }

    function startScanner() {
        $('#cardResult').hide();
        $('#cardPlaceholder').show();
        $('#scannerOverlay').hide(); // Hilangkan gelap
        
        // Konfigurasi area scan barcode terfokus
        const config = { 
            fps: 25, 
            qrbox: function(width, height) {
                // Dimensi lebar untuk barcode (persegi panjang)
                const minEdge = Math.min(width, height);
                const qrboxWidth = Math.floor(width * 0.8);
                const qrboxHeight = Math.floor(height * 0.45);
                return {
                    width: qrboxWidth > 320 ? 320 : qrboxWidth,
                    height: qrboxHeight > 180 ? 180 : qrboxHeight
                };
            },
            aspectRatio: 1.333334,
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
                    $('#scannerOverlay').show(); // Tampilkan gelap
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
        if (!isScanning) return; // Proteksi scan ganda: abaikan jika sedang tidak aktif scanning
        
        // Kunci status agar tidak men-scan berulang kali
        isScanning = false;
        
        // 1. Dikeluarkan bunyi beep pendek nyaring secara lokal
        playBeep();
        
        // 2. Scanner otomatis berhenti
        stopScanner().then(() => {
            // Tampilkan loading swal
            Swal.fire({
                title: 'Mencari data barang...',
                text: 'Harap tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            // 3. Ambil data dari database Laravel (Query)
            $.ajax({
                url: `/admin/scanner/cek/${decodedText}`,
                method: 'GET',
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        const data = response.data;
                        
                        // 4. Tampilkan data barang yang berhasil didapatkan
                        $('#resIdbuku').text(data.idbuku);
                        $('#resKode').text(data.kode);
                        $('#resJudul').text(data.judul);
                        $('#resPengarang').text(data.pengarang);
                        $('#resKategori').text(data.kategori);
                        $('#resHarga').text(data.harga);
                        $('#resStok').text(data.stok);
                        $('#resGambar').attr('src', data.gambar);
                        
                        $('#cardPlaceholder').hide();
                        $('#cardResult').fadeIn();
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    let msg = 'Terjadi kesalahan sistem';
                    if (xhr.status === 404) {
                        msg = `Buku dengan kode "${decodedText}" tidak terdaftar di database.`;
                    }
                    
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Tidak Ditemukan',
                        text: msg
                    }).then(() => {
                        // Reset scanner agar siap digunakan lagi
                        resetScanner();
                    });
                }
            });
        });
    }

    function resetScanner() {
        $('#cardResult').hide();
        $('#cardPlaceholder').show();
        startScanner();
    }
</script>
@endsection
