@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-nfc"></i>
        </span>
        Registrasi KTM NFC Mahasiswa
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Registrasi KTM NFC</li>
        </ol>
    </nav>
</div>

<div class="row justify-content-center">
    <div class="col-md-7 grid-margin stretch-card">
        <div class="card bg-white shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body p-5">

                {{-- Header --}}
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 rounded-circle d-flex align-items-center justify-content-center"
                         style="width:50px;height:50px;background:rgba(145,118,251,0.12);">
                        <i class="mdi mdi-account-plus text-primary" style="font-size:1.8rem;"></i>
                    </div>
                    <div>
                        <h4 class="card-title mb-0 font-weight-bold text-dark">Daftarkan KTM Mahasiswa</h4>
                        <p class="text-muted small mb-0">Input nama & NIM, lalu tempelkan KTM ke smartphone</p>
                    </div>
                </div>

                {{-- Step 1 & 2: Nama + NIM --}}
                <div class="form-group mb-3">
                    <label for="nama" class="font-weight-bold text-dark mb-1">
                        Langkah 1 — Nama Mahasiswa
                    </label>
                    <input type="text" id="nama" class="form-control"
                           placeholder="Contoh: Budi Santoso"
                           style="border-radius:8px;padding:12px;">
                </div>

                <div class="form-group mb-4">
                    <label for="nim" class="font-weight-bold text-dark mb-1">
                        Langkah 2 — NIM Mahasiswa
                    </label>
                    <input type="text" id="nim" class="form-control"
                           placeholder="Contoh: 2023010001"
                           style="border-radius:8px;padding:12px;">
                </div>

                {{-- Step 3: NFC --}}
                <div class="mb-4">
                    <label class="font-weight-bold text-dark mb-2 d-block">
                        Langkah 3 — Tempelkan KTM ke Smartphone
                    </label>

                    {{-- Status area --}}
                    <div class="py-4 px-3 text-center rounded mb-3"
                         style="border:2px dashed #ddd;border-radius:12px!important;background:#fafafa;">
                        <div id="nfc-spinner" class="mb-2 d-none">
                            <div class="spinner-grow text-primary" role="status" style="width:2.5rem;height:2.5rem;"></div>
                        </div>
                        <p id="nfc-status" class="font-weight-bold mb-1 text-muted" style="font-size:1rem;">
                            Status: NFC belum aktif. Isi nama &amp; NIM lalu klik tombol di bawah.
                        </p>
                        <p id="nfc-serial-display" class="d-none mb-0" style="font-size:.95rem;">
                            Serial NFC: <strong id="nfc-serial-value" class="text-primary">—</strong>
                        </p>
                    </div>

                    <button id="btnActivate"
                            class="btn btn-gradient-primary btn-lg w-100 py-3 text-white border-0 font-weight-bold"
                            style="border-radius:12px;box-shadow:0 4px 15px rgba(145,118,251,.3);">
                        <i class="mdi mdi-nfc me-2"></i> Aktifkan NFC
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js_page')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const btnActivate        = document.getElementById('btnActivate');
const nfcStatus          = document.getElementById('nfc-status');
const nfcSpinner         = document.getElementById('nfc-spinner');
const nfcSerialDisplay   = document.getElementById('nfc-serial-display');
const nfcSerialValue     = document.getElementById('nfc-serial-value');

btnActivate.addEventListener('click', async () => {

    const nama = document.getElementById('nama').value.trim();
    const nim  = document.getElementById('nim').value.trim();

    // Validasi input
    if (!nama || !nim) {
        Swal.fire({
            icon: 'warning',
            title: 'Data belum lengkap',
            text: 'Harap isi Nama dan NIM mahasiswa terlebih dahulu.'
        });
        return;
    }

    // Cek browser support
    if (!('NDEFReader' in window)) {
        nfcStatus.textContent = 'Status: Browser tidak mendukung Web NFC API.';
        nfcStatus.className   = 'font-weight-bold mb-1 text-danger';
        Swal.fire({
            icon: 'error',
            title: 'Browser Tidak Mendukung NFC',
            text: 'Web NFC API hanya didukung oleh Google Chrome di perangkat Android.'
        });
        return;
    }

    try {
        // Update status
        nfcStatus.textContent = 'Status: Mengaktifkan NFC...';
        nfcStatus.className   = 'font-weight-bold mb-1 text-warning';
        nfcSpinner.classList.remove('d-none');
        nfcSerialDisplay.classList.add('d-none');
        btnActivate.disabled  = true;

        const ndef = new NDEFReader();
        await ndef.scan();

        nfcStatus.textContent = 'Status: NFC aktif. Silakan tempelkan KTM mahasiswa ke bagian belakang smartphone.';
        nfcStatus.className   = 'font-weight-bold mb-1 text-info';

        ndef.onreadingerror = () => {
            nfcStatus.textContent = 'Status: Gagal membaca NFC. Coba tempelkan kembali.';
            nfcStatus.className   = 'font-weight-bold mb-1 text-danger';
        };

        ndef.onreading = async (event) => {
            playBeep();

            const serialNumber = event.serialNumber;
            nfcSerialValue.textContent = serialNumber;
            nfcSerialDisplay.classList.remove('d-none');

            nfcStatus.textContent = 'Status: Serial terbaca. Menyimpan ke database...';
            nfcStatus.className   = 'font-weight-bold mb-1 text-success';

            // Kirim ke Laravel via fetch()
            try {
                const response = await fetch('{{ route("nfc.register.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type' : 'application/json',
                        'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        nama       : nama,
                        nim        : nim,
                        nfc_serial : serialNumber
                    })
                });

                const result = await response.json();
                nfcSpinner.classList.add('d-none');
                btnActivate.disabled = false;

                if (response.ok && result.success) {
                    Swal.fire({
                        icon             : 'success',
                        title            : 'Registrasi Berhasil!',
                        html             : `KTM mahasiswa <strong>${result.mahasiswa.nama}</strong> (${result.mahasiswa.nim}) berhasil didaftarkan.`,
                        timer            : 3000,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    nfcStatus.textContent = 'Status: Gagal — ' + (result.message || 'Terjadi kesalahan.');
                    nfcStatus.className   = 'font-weight-bold mb-1 text-danger';
                    Swal.fire({
                        icon : 'error',
                        title: 'Registrasi Gagal',
                        text : result.message || 'Terjadi kesalahan sistem.'
                    });
                }
            } catch (err) {
                nfcSpinner.classList.add('d-none');
                btnActivate.disabled = false;
                nfcStatus.textContent = 'Status: Koneksi error — ' + err.message;
                nfcStatus.className   = 'font-weight-bold mb-1 text-danger';
                Swal.fire({ icon: 'error', title: 'Error Koneksi', text: err.message });
            }
        };

    } catch (error) {
        nfcSpinner.classList.add('d-none');
        btnActivate.disabled  = false;
        nfcStatus.textContent = 'Status: Error — ' + error.message;
        nfcStatus.className   = 'font-weight-bold mb-1 text-danger';
        Swal.fire({
            icon : 'error',
            title: 'NFC Gagal Diaktifkan',
            text : error.message
        });
    }
});

function playBeep() {
    try {
        const ctx        = new (window.AudioContext || window.webkitAudioContext)();
        const osc        = ctx.createOscillator();
        const gain       = ctx.createGain();
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
