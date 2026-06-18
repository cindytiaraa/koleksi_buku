@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-qrcode-scan"></i>
        </span>
        Kunjungan Toko (Sales)
    </h3>
</div>

<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card bg-white shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-light-primary p-2 rounded-circle me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; background-color: rgba(145, 118, 251, 0.1);">
                        <i class="mdi mdi-qrcode text-primary" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h4 class="card-title mb-0 font-weight-bold text-dark">Scanner QR Code</h4>
                        <p class="text-muted small mb-0">Scan QR Code toko atau masukkan barcode secara manual.</p>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Barcode / QR Code</label>
                    <input type="text" id="barcode" class="form-control" placeholder="Masukkan barcode toko atau scan QR" autocomplete="off">
                </div>
                <div class="mb-3 d-flex gap-2">
                    <button id="btn-fetch" class="btn btn-secondary flex-fill">Ambil Data Toko</button>
                    <button id="btn-scan" class="btn btn-primary flex-fill">Scan QR Code</button>
                </div>

                <div class="position-relative overflow-hidden bg-dark mb-4 shadow-inner" style="border-radius: 12px; min-height: 320px; border: 3px solid #eee; display: flex; align-items: center; justify-content: center;">
                    <div id="reader" style="width: 100%;"></div>
                    <div id="scannerOverlay" class="position-absolute w-100 h-100" style="top: 0; left: 0; background: rgba(0,0,0,0.6); display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 10; pointer-events: none;">
                        <i class="mdi mdi-video-off text-white mb-2" style="font-size: 3rem; opacity: 0.6;"></i>
                        <span class="text-white small font-weight-bold" style="opacity: 0.8;">Kamera belum aktif</span>
                    </div>
                </div>

                <div class="text-center">
                    <button id="btn-start-scanner" class="btn btn-gradient-primary btn-lg px-4 py-2 text-white border-0 font-weight-bold" style="border-radius: 30px;">
                        <i class="mdi mdi-play me-2"></i> Mulai Scanner
                    </button>
                    <button id="btn-stop-scanner" class="btn btn-gradient-danger btn-lg px-4 py-2 text-white border-0 font-weight-bold" style="border-radius: 30px; display:none;">
                        <i class="mdi mdi-stop me-2"></i> Berhenti Scanner
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card bg-white shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body p-4">
                <h4 class="card-title font-weight-bold text-dark">Informasi Kunjungan</h4>
                <div id="toko-data" style="display:none;">
                    <div class="mb-4" id="toko-info"></div>

                    <div class="mb-3">
                        <label class="form-label">Latitude Sales</label>
                        <input type="text" id="latitude_sales" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Longitude Sales</label>
                        <input type="text" id="longitude_sales" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Accuracy Sales (m)</label>
                        <input type="text" id="accuracy_sales" class="form-control" readonly>
                    </div>

                    <div class="mb-3 d-flex gap-2">
                        <button id="btn-get-location-sales" class="btn btn-info flex-fill">Ambil Lokasi</button>
                        <button id="btn-submit-kunjungan" class="btn btn-primary flex-fill">Kirim Kunjungan</button>
                    </div>
                </div>
                <div id="no-data" class="alert alert-secondary" role="alert">
                    Silakan scan QR Code atau masukkan barcode toko, lalu tekan Ambil Data Toko.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_page')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
let html5QrCode;
let isScanning = false;
let currentToko = null;
const csrfToken = '{{ csrf_token() }}';

function showTokoData(toko) {
    currentToko = toko;
    document.getElementById('toko-info').innerHTML = `
        <div class="mb-3">
            <strong>${toko.nama_toko}</strong><br>
            <small class="text-muted">Barcode: ${toko.barcode}</small><br>
            <small class="text-muted">Lat: ${toko.latitude} | Lng: ${toko.longitude} | Accuracy: ${toko.accuracy} m</small>
        </div>
    `;
    document.getElementById('toko-data').style.display = 'block';
    document.getElementById('no-data').style.display = 'none';
}

function resetTokoData() {
    currentToko = null;
    document.getElementById('toko-data').style.display = 'none';
    document.getElementById('no-data').style.display = 'block';
}

function fetchToko(code) {
    if (!code) {
        alert('Masukkan atau scan barcode QR Code toko.');
        return;
    }

    fetch(`/admin/toko/api/${encodeURIComponent(code)}`)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error('Toko tidak ditemukan');
            }
            showTokoData(data.toko);
        })
        .catch(error => {
            resetTokoData();
            alert(error.message || 'Gagal mengambil data toko.');
        });
}

function startScanner() {
    if (isScanning) return;

    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode('reader');
    }

    const config = {
        fps: 25,
        qrbox: { width: 240, height: 240 },
        aspectRatio: 1.0,
        showTorchButtonIfSupported: true
    };

    html5QrCode.start(
        { facingMode: 'environment' },
        config,
        decodedText => {
            if (!isScanning) return;
            isScanning = false;
            stopScanner();
            document.getElementById('barcode').value = decodedText;
            fetchToko(decodedText);
        },
        errorMessage => {
            // ignore per-frame decode errors
        }
    ).then(() => {
        isScanning = true;
        document.getElementById('btn-start-scanner').style.display = 'none';
        document.getElementById('btn-stop-scanner').style.display = 'inline-block';
        document.getElementById('scannerOverlay').style.display = 'none';
    }).catch(err => {
        alert('Gagal memulai scanner QR Code: ' + err);
    });
}

function stopScanner() {
    if (!html5QrCode || !isScanning) return;
    html5QrCode.stop().then(() => {
        isScanning = false;
        document.getElementById('btn-start-scanner').style.display = 'inline-block';
        document.getElementById('btn-stop-scanner').style.display = 'none';
        document.getElementById('scannerOverlay').style.display = 'flex';
    }).catch(err => {
        console.error('Gagal menghentikan scanner:', err);
    });
}

function startLocationSearch() {
    const latEl = document.getElementById('latitude_sales');
    const lngEl = document.getElementById('longitude_sales');
    const accEl = document.getElementById('accuracy_sales');

    if (!navigator.geolocation) {
        alert('Geolocation tidak didukung browser ini.');
        return;
    }

    let best = null;
    const timeout = 20000;
    const startTime = Date.now();
    const watchId = navigator.geolocation.watchPosition(position => {
        const coords = position.coords;
        if (!coords) return;

        if (best === null || coords.accuracy < best.coords.accuracy) {
            best = position;
            latEl.value = coords.latitude;
            lngEl.value = coords.longitude;
            accEl.value = coords.accuracy;
        }

        if (coords.accuracy <= 50 || Date.now() - startTime > timeout) {
            navigator.geolocation.clearWatch(watchId);
        }
    }, error => {
        alert('Gagal mengambil lokasi: ' + error.message);
    }, {
        enableHighAccuracy: true,
        maximumAge: 0,
        timeout: timeout
    });
}

function submitKunjungan() {
    if (!currentToko) {
        alert('Ambil data toko terlebih dahulu.');
        return;
    }
    const latitudeSales = document.getElementById('latitude_sales').value;
    const longitudeSales = document.getElementById('longitude_sales').value;
    const accuracySales = document.getElementById('accuracy_sales').value;

    if (!latitudeSales || !longitudeSales) {
        alert('Ambil lokasi sales terlebih dahulu.');
        return;
    }

    fetch('/admin/kunjungan/process', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            barcode: currentToko.barcode,
            latitude_sales: latitudeSales,
            longitude_sales: longitudeSales,
            accuracy_sales: accuracySales
        })
    })
    .then(response => response.text())
    .then(html => {
        const resultWindow = window.open('', '_blank');
        resultWindow.document.write(html);
        resultWindow.document.close();
    })
    .catch(error => {
        alert('Gagal mengirim kunjungan: ' + error.message);
    });
}

window.addEventListener('DOMContentLoaded', function() {
    document.getElementById('btn-fetch').addEventListener('click', function () {
        fetchToko(document.getElementById('barcode').value.trim());
    });

    document.getElementById('btn-scan').addEventListener('click', startScanner);
    document.getElementById('btn-start-scanner').addEventListener('click', startScanner);
    document.getElementById('btn-stop-scanner').addEventListener('click', stopScanner);
    document.getElementById('btn-get-location-sales').addEventListener('click', startLocationSearch);
    document.getElementById('btn-submit-kunjungan').addEventListener('click', submitKunjungan);
});
</script>
@endsection
