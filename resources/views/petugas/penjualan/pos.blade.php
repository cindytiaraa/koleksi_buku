@extends('layouts.petugas')

@section('page_title', 'Kasir / POS Penjualan')

@section('style_page')
<style>
    .keranjang-item { border-bottom: 1px solid #eee; padding: 8px 0; }
    #totalDisplay { font-size: 1.5rem; font-weight: 700; color: #1e3a5f; }
    .btn-qty { width: 30px; height: 30px; padding: 0; line-height: 28px; text-align: center; }
    #inputKode { text-transform: uppercase; letter-spacing: 1px; }
</style>
@endsection

@section('content')
<div class="row">
    {{-- Cari Buku --}}
    <div class="col-md-5 mb-4">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">🔍 Cari Buku</div>
            <div class="card-body">
                <div class="input-group mb-2">
                    <input type="text" id="inputKode" class="form-control"
                           placeholder="Masukkan kode buku...">
                    <div class="input-group-append">
                        <button id="btnCari" class="btn btn-primary">Cari</button>
                    </div>
                </div>
                <div id="hasilCari" class="d-none">
                    <div class="alert alert-info mb-2" id="infoBuku"></div>
                    <div class="form-group mb-2">
                        <label>Jumlah</label>
                        <input type="number" id="inputJumlah" class="form-control" value="1" min="1">
                    </div>
                    <button id="btnTambah" class="btn btn-success btn-block">+ Tambah ke Keranjang</button>
                </div>
                <div id="alertCari"></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-success text-white">💰 Bayar</div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <p class="mb-1 text-muted">Total Pembayaran</p>
                    <div id="totalDisplay">Rp 0</div>
                </div>
                <button id="btnBayar" class="btn btn-success btn-block py-3" disabled>
                    Proses Pembayaran Tunai
                </button>
            </div>
        </div>
    </div>

    {{-- Keranjang --}}
    <div class="col-md-7 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                🛒 Keranjang
                <button id="btnKosongkan" class="btn btn-sm btn-light">Kosongkan</button>
            </div>
            <div class="card-body" id="keranjangContainer">
                <p class="text-muted text-center py-4" id="emptyMsg">Keranjang masih kosong</p>
                <div id="keranjangList"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_page')
<script>
let keranjang = [];
let bukuTerpilih = null;

// Cari buku
document.getElementById('btnCari').addEventListener('click', function() {
    const kode = document.getElementById('inputKode').value.trim();
    if (!kode) return;

    this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    this.disabled = true;

    axios.get("{{ route('petugas.penjualan.cariBuku') }}", { params: { kode } })
    .then(res => {
        if (res.data.status === 'success') {
            bukuTerpilih = res.data.data;
            document.getElementById('infoBuku').innerHTML =
                `<strong>${bukuTerpilih.nama}</strong><br>
                 Kode: ${bukuTerpilih.kode} | Harga: Rp ${formatRp(bukuTerpilih.harga)}`;
            document.getElementById('hasilCari').classList.remove('d-none');
            document.getElementById('alertCari').innerHTML = '';
        }
    })
    .catch(() => {
        document.getElementById('hasilCari').classList.add('d-none');
        document.getElementById('alertCari').innerHTML =
            '<div class="alert alert-danger mt-2">Buku tidak ditemukan.</div>';
    })
    .finally(() => {
        this.innerHTML = 'Cari';
        this.disabled = false;
    });
});

// Enter = cari
document.getElementById('inputKode').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') document.getElementById('btnCari').click();
});

// Tambah ke keranjang
document.getElementById('btnTambah').addEventListener('click', function() {
    if (!bukuTerpilih) return;
    const jumlah = parseInt(document.getElementById('inputJumlah').value) || 1;
    const idx = keranjang.findIndex(k => k.kode === bukuTerpilih.kode);

    if (idx >= 0) {
        keranjang[idx].jumlah += jumlah;
        keranjang[idx].subtotal = keranjang[idx].jumlah * keranjang[idx].harga;
    } else {
        keranjang.push({
            kode: bukuTerpilih.kode,
            nama: bukuTerpilih.nama,
            harga: bukuTerpilih.harga,
            jumlah: jumlah,
            subtotal: bukuTerpilih.harga * jumlah,
        });
    }

    renderKeranjang();
    document.getElementById('inputKode').value = '';
    document.getElementById('hasilCari').classList.add('d-none');
    bukuTerpilih = null;
});

// Render keranjang
function renderKeranjang() {
    const container = document.getElementById('keranjangList');
    const emptyMsg  = document.getElementById('emptyMsg');
    const total     = keranjang.reduce((a, b) => a + b.subtotal, 0);

    document.getElementById('totalDisplay').textContent = 'Rp ' + formatRp(total);
    document.getElementById('btnBayar').disabled = keranjang.length === 0;

    if (keranjang.length === 0) {
        emptyMsg.classList.remove('d-none');
        container.innerHTML = '';
        return;
    }

    emptyMsg.classList.add('d-none');
    container.innerHTML = keranjang.map((item, i) => `
        <div class="keranjang-item d-flex justify-content-between align-items-center">
            <div>
                <strong>${item.nama}</strong> <small class="text-muted">${item.kode}</small><br>
                <small>Rp ${formatRp(item.harga)} × ${item.jumlah} = <strong>Rp ${formatRp(item.subtotal)}</strong></small>
            </div>
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary btn-qty" onclick="ubahQty(${i}, -1)">−</button>
                <span class="mx-2">${item.jumlah}</span>
                <button class="btn btn-outline-secondary btn-qty" onclick="ubahQty(${i}, 1)">+</button>
                <button class="btn btn-danger btn-sm ml-2" onclick="hapus(${i})">✕</button>
            </div>
        </div>
    `).join('');
}

function ubahQty(i, delta) {
    keranjang[i].jumlah = Math.max(1, keranjang[i].jumlah + delta);
    keranjang[i].subtotal = keranjang[i].jumlah * keranjang[i].harga;
    renderKeranjang();
}

function hapus(i) {
    keranjang.splice(i, 1);
    renderKeranjang();
}

document.getElementById('btnKosongkan').addEventListener('click', function() {
    keranjang = [];
    renderKeranjang();
});

// Bayar
document.getElementById('btnBayar').addEventListener('click', function() {
    const total = keranjang.reduce((a, b) => a + b.subtotal, 0);

    Swal.fire({
        title: 'Konfirmasi Pembayaran',
        html: `Total: <strong>Rp ${formatRp(total)}</strong><br>Tunai / manual`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Proses',
        cancelButtonText: 'Batal',
    }).then(result => {
        if (!result.isConfirmed) return;

        axios.post("{{ route('petugas.penjualan.bayar') }}", {
            _token: "{{ csrf_token() }}",
            items: keranjang,
            total: total,
        })
        .then(res => {
            if (res.data.status === 'success') {
                Swal.fire('Berhasil!', 'Transaksi berhasil disimpan.', 'success');
                keranjang = [];
                renderKeranjang();
            }
        })
        .catch(() => {
            Swal.fire('Error', 'Gagal menyimpan transaksi.', 'error');
        });
    });
});

function formatRp(n) {
    return Number(n).toLocaleString('id-ID');
}
</script>
@endsection
