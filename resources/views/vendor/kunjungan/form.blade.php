@extends('layouts.vendor')

@section('title','Kunjungan Toko')
@section('page_title','Kunjungan Toko')

@section('content')
<div class="card">
    <div class="card-header blue">Kunjungan Vendor ke Toko Cabang</div>
    <div class="card-body">
        <div class="form-group mb-3">
            <label class="form-label">Kode Toko / Barcode</label>
            <input type="text" id="barcode" class="form-control" placeholder="Masukkan barcode toko" autocomplete="off">
        </div>
        <div class="d-flex gap-2 mb-4">
            <button id="btn-fetch" class="btn btn-primary">Ambil Data Toko</button>
            <button id="btn-scan" class="btn btn-outline-primary">Scan QR Code</button>
        </div>

        <div id="store-info" class="mb-4"></div>

        <div id="location-panel" style="display:none;">
            <div class="mb-3">
                <label class="form-label">Latitude Vendor</label>
                <input type="text" id="latitude_vendor" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Longitude Vendor</label>
                <input type="text" id="longitude_vendor" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Accuracy Vendor (m)</label>
                <input type="text" id="accuracy_vendor" class="form-control" readonly>
            </div>
            <button id="btn-get-location" class="btn btn-info">Ambil Lokasi</button>
            <button id="btn-validate" class="btn btn-success">Validasi Kunjungan</button>
        </div>

        <div id="scan-area" class="mt-4 position-relative" style="min-height:300px; display:none;">
            <div id="reader" style="width:100%; height:100%;"></div>
            <div id="scannerOverlay" class="position-absolute w-100 h-100" style="top:0;left:0;background:rgba(0,0,0,.45);display:flex;align-items:center;justify-content:center;color:#fff;">
                <div>Menunggu QR Code ...</div>
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

function renderStoreInfo(toko) {
    currentToko = toko;
    document.getElementById('store-info').innerHTML = `
        <div class="alert alert-info">
            <strong>${toko.nama_toko}</strong><br>
            Barcode: ${toko.barcode}<br>
            Lat: ${toko.latitude}, Lng: ${toko.longitude}<br>
            Accuracy Toko: ${toko.accuracy} m
        </div>
    `;
    document.getElementById('location-panel').style.display = 'block';
}

function clearStore() {
    currentToko = null;
    document.getElementById('store-info').innerHTML = '';
    document.getElementById('location-panel').style.display = 'none';
}

function fetchStore() {
    const barcode = document.getElementById('barcode').value.trim();
    if (!barcode) {
        alert('Masukkan barcode toko terlebih dahulu.');
        return;
    }

    axios.post('{{ route('vendor.kunjungan.cek') }}', { barcode })
        .then(function(response) {
            renderStoreInfo(response.data.toko);
        })
        .catch(function(error) {
            clearStore();
            alert(error.response?.data?.message || 'Toko tidak ditemukan.');
        });
}

function startScanner() {
    document.getElementById('scan-area').style.display = 'block';
    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode('reader');
    }

    html5QrCode.start({ facingMode: 'environment' }, {
        fps: 20,
        qrbox: { width: 260, height: 260 },
        aspectRatio: 1.0,
        showTorchButtonIfSupported: true
    }, decodedText => {
        if (!isScanning) return;
        isScanning = false;
        stopScanner();
        document.getElementById('barcode').value = decodedText;
        fetchStore();
    }, errorMessage => {
        // ignore scanning errors
    }).then(() => {
        isScanning = true;
        document.getElementById('scannerOverlay').textContent = 'Arahkan kamera ke QR Code toko.';
    }).catch(err => {
        alert('Gagal mulai scanner: '+err);
    });
}

function stopScanner() {
    if (!html5QrCode || !isScanning) return;
    html5QrCode.stop().then(() => {
        isScanning = false;
        document.getElementById('scannerOverlay').textContent = 'Scanner berhenti.';
    }).catch(console.error);
}

function getLocation() {
    const latEl = document.getElementById('latitude_vendor');
    const lngEl = document.getElementById('longitude_vendor');
    const accEl = document.getElementById('accuracy_vendor');

    if (!navigator.geolocation) {
        alert('Geolocation tidak didukung browser ini.');
        return;
    }

    let best = null;
    const timeout = 20000;
    const start = Date.now();
    const watchId = navigator.geolocation.watchPosition(position => {
        if (!position.coords) return;
        if (best === null || position.coords.accuracy < best.coords.accuracy) {
            best = position;
            latEl.value = position.coords.latitude;
            lngEl.value = position.coords.longitude;
            accEl.value = position.coords.accuracy;
        }
        if (position.coords.accuracy <= 50 || Date.now() - start > timeout) {
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

function validateVisit() {
    if (!currentToko) {
        alert('Ambil data toko terlebih dahulu.');
        return;
    }
    const latitude_vendor = document.getElementById('latitude_vendor').value;
    const longitude_vendor = document.getElementById('longitude_vendor').value;
    const accuracy_vendor = document.getElementById('accuracy_vendor').value;

    if (!latitude_vendor || !longitude_vendor) {
        alert('Ambil lokasi vendor terlebih dahulu.');
        return;
    }

    axios.post('{{ route('vendor.kunjungan.process') }}', {
        barcode: currentToko.barcode,
        latitude_vendor,
        longitude_vendor,
        accuracy_vendor
    }).then(function(response) {
        const win = window.open('', '_blank');
        win.document.write(response.data);
        win.document.close();
    }).catch(function(error) {
        alert(error.response?.data?.message || 'Gagal memproses kunjungan.');
    });
}

window.addEventListener('DOMContentLoaded', function() {
    document.getElementById('btn-fetch').addEventListener('click', fetchStore);
    document.getElementById('btn-scan').addEventListener('click', startScanner);
    document.getElementById('btn-get-location').addEventListener('click', getLocation);
    document.getElementById('btn-validate').addEventListener('click', validateVisit);
});
</script>
@endsection