@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-nfc-variant"></i>
        </span>
        Scanner Absensi Mahasiswa
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Scanner Absensi</li>
        </ol>
    </nav>
</div>

<div class="row justify-content-center">
    <div class="col-md-7 grid-margin stretch-card">
        <div class="card bg-white shadow-sm border-0" style="border-radius:15px;">
            <div class="card-body p-5">

                {{-- Judul wajib modul --}}
                <h2>Scanner Absensi Mahasiswa</h2>

                {{-- Tombol wajib modul --}}
                <div class="mt-4 mb-3">
                    <button id="btnActivate"
                            class="btn btn-gradient-primary btn-lg px-5 py-3 text-white border-0 font-weight-bold"
                            style="border-radius:12px;box-shadow:0 4px 15px rgba(145,118,251,.3);">
                        <i class="mdi mdi-nfc me-2"></i> Aktifkan NFC
                    </button>
                </div>

                {{-- Status wajib modul --}}
                <p id="scannerStatus" class="text-muted font-weight-bold" style="font-size:1rem;">
                    Status: NFC Belum Aktif. Klik tombol "Aktifkan NFC" untuk memulai.
                </p>

                <hr style="border-top:1px solid #eee;">

                {{-- Hasil scan wajib modul --}}
                <div id="scanResult" class="mt-3">
                    <div class="text-center py-5 text-muted border rounded bg-light"
                         style="border-style:dashed!important;border-radius:12px;">
                        <i class="mdi mdi-nfc text-muted mb-2"
                           style="font-size:4rem;opacity:.25;display:block;"></i>
                        <p class="mb-0 font-weight-bold">Hasil Scan</p>
                        <p class="mb-0 small">Tempelkan KTM mahasiswa ke smartphone untuk mencatat absensi.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js_page')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const btnActivate   = document.getElementById('btnActivate');
const scannerStatus = document.getElementById('scannerStatus');
const scanResult    = document.getElementById('scanResult');

btnActivate.addEventListener('click', async () => {

    // Cek browser support
    if (!('NDEFReader' in window)) {
        scannerStatus.innerHTML =
            'Status: <span class="text-danger font-weight-bold">Browser tidak mendukung Web NFC API.</span>';
        Swal.fire({
            icon : 'error',
            title: 'Browser Tidak Mendukung NFC',
            text : 'Web NFC API hanya didukung oleh Google Chrome di perangkat Android.'
        });
        return;
    }

    try {
        scannerStatus.innerHTML =
            'Status: <span class="text-warning font-weight-bold">Mengaktifkan NFC...</span>';
        btnActivate.disabled = true;

        const ndef = new NDEFReader();
        await ndef.scan();

        scannerStatus.innerHTML =
            'Status: <span class="text-success font-weight-bold">NFC Aktif. Silakan tempelkan KTM mahasiswa.</span>';
        btnActivate.disabled = false;

        ndef.onreadingerror = () => {
            scannerStatus.innerHTML =
                'Status: <span class="text-danger font-weight-bold">Gagal membaca NFC. Coba tempelkan kembali.</span>';
        };

        ndef.onreading = async (event) => {
            playBeep();

            const serialNumber = event.serialNumber;

            scannerStatus.innerHTML =
                'Status: <span class="text-info font-weight-bold">Tag terbaca: ' + serialNumber + '. Memproses...</span>';

            Swal.fire({
                title           : 'Mencatat Absensi...',
                text            : 'Harap tunggu sebentar.',
                allowOutsideClick: false,
                didOpen         : () => Swal.showLoading()
            });

            // Kirim serial ke Laravel via fetch()
            try {
                const response = await fetch('{{ route("api.nfc.scan") }}', {
                    method : 'POST',
                    headers: {
                        'Content-Type' : 'application/json',
                        'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ serial_number: serialNumber })
                });

                const result = await response.json();
                Swal.close();

                if (response.ok && result.success) {
                    scannerStatus.innerHTML =
                        'Status: <span class="text-success font-weight-bold">Absensi berhasil dicatat untuk ' +
                        result.mahasiswa.nama + '.</span>';

                    // Tampilkan kartu hasil scan
                    scanResult.innerHTML = `
                        <div class="card border border-success bg-white shadow-sm"
                             style="border-radius:12px;border-width:2px!important;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title text-success mb-0 font-weight-bold">
                                        <i class="mdi mdi-checkbox-marked-circle-outline me-2"></i>
                                        Absensi Berhasil Dicatat
                                    </h5>
                                    <span class="badge bg-success text-white px-3 py-1 font-weight-bold"
                                          style="border-radius:10px;font-size:.85rem;">Hadir</span>
                                </div>
                                <hr style="border-top:1px solid #eee;">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr style="border-bottom:1px solid #f8f9fa;">
                                            <td class="text-muted font-weight-bold" width="160"
                                                style="padding:10px 0;">Nama Mahasiswa</td>
                                            <td class="font-weight-bold text-dark text-end"
                                                style="padding:10px 0;font-size:1.05rem;">${result.mahasiswa.nama}</td>
                                        </tr>
                                        <tr style="border-bottom:1px solid #f8f9fa;">
                                            <td class="text-muted font-weight-bold" style="padding:10px 0;">NIM</td>
                                            <td class="font-weight-bold text-secondary text-end"
                                                style="padding:10px 0;">${result.mahasiswa.nim}</td>
                                        </tr>
                                        <tr style="border-bottom:1px solid #f8f9fa;">
                                            <td class="text-muted font-weight-bold" style="padding:10px 0;">Tanggal</td>
                                            <td class="text-dark font-weight-bold text-end"
                                                style="padding:10px 0;">${result.absensi.tanggal}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted font-weight-bold" style="padding:10px 0;">Jam Absensi</td>
                                            <td class="text-dark font-weight-bold text-end"
                                                style="padding:10px 0;">${result.absensi.waktu}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>`;

                    Swal.fire({
                        icon             : 'success',
                        title            : 'Absensi Berhasil!',
                        html             : `<strong>${result.mahasiswa.nama}</strong> (${result.mahasiswa.nim}) dicatat hadir pukul ${result.absensi.waktu}.`,
                        timer            : 3000,
                        showConfirmButton: false
                    });

                } else {
                    // KTM tidak dikenali / belum terdaftar
                    scannerStatus.innerHTML =
                        'Status: <span class="text-danger font-weight-bold">KTM belum terdaftar.</span>';

                    scanResult.innerHTML = `
                        <div class="alert border-0 p-4 mt-2"
                             style="border-radius:12px;background:rgba(254,86,117,.08);">
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-alert-circle-outline me-3 text-danger"
                                   style="font-size:2rem;"></i>
                                <div>
                                    <p class="font-weight-bold mb-1 text-danger">KTM Belum Terdaftar</p>
                                    <p class="mb-0 small text-muted">
                                        ${result.message || 'Silakan lakukan registrasi KTM terlebih dahulu.'}
                                    </p>
                                </div>
                            </div>
                        </div>`;

                    Swal.fire({
                        icon : 'warning',
                        title: 'KTM Belum Terdaftar',
                        text : result.message || 'Silakan lakukan registrasi KTM terlebih dahulu.'
                    });
                }

            } catch (err) {
                Swal.close();
                scannerStatus.innerHTML =
                    'Status: <span class="text-danger font-weight-bold">Koneksi error: ' + err.message + '</span>';
                Swal.fire({ icon: 'error', title: 'Error Koneksi', text: err.message });
            }
        };

    } catch (error) {
        btnActivate.disabled = false;
        scannerStatus.innerHTML =
            'Status: <span class="text-danger font-weight-bold">Gagal mengaktifkan NFC: ' + error.message + '</span>';
        Swal.fire({
            icon : 'error',
            title: 'NFC Gagal Diaktifkan',
            text : error.message
        });
    }
});

function playBeep() {
    try {
        const ctx  = new (window.AudioContext || window.webkitAudioContext)();
        const osc  = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.type = 'sine';
        osc.frequency.setValueAtTime(1000, ctx.currentTime);
        gain.gain.setValueAtTime(0.2, ctx.currentTime);
        osc.start();
        osc.stop(ctx.currentTime + 0.12);
    } catch (e) { /* ignore */ }
}
</script>
@endsection
