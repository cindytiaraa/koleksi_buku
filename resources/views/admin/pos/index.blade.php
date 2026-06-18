@extends('layouts.admin')

@section('style_page')
<style>
    .input-readonly { background-color: #fff0f0 !important; }
    #btnTambahkan   { opacity: 0.5; pointer-events: none; }
    #btnTambahkan.aktif { opacity: 1; pointer-events: auto; }
    tbody tr:hover  { cursor: pointer; }
    .qty-input {
        width: 70px;
        text-align: center;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 2px 6px;
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <h3 class="page-title">POS / Kasir</h3>
</div>

<div class="row">

    {{-- ── Form Input ── --}}
    <div class="col-md-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Input Barang</h4>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label>Kode Barang</label>
                    <input type="text" id="inputKode" class="form-control"
                        placeholder="Ketik kode lalu Enter...">
                    <small id="pesanKode" class="text-danger d-none"></small>
                </div>

                <div class="form-group mt-3">
                    <label>Nama Barang</label>
                    <input type="text" id="inputNama" class="form-control input-readonly" readonly>
                </div>

                <div class="form-group mt-3">
                    <label>Harga Barang</label>
                    <input type="text" id="inputHarga" class="form-control input-readonly" readonly>
                </div>

                <div class="form-group mt-3">
                    <label>Jumlah</label>
                    <input type="number" id="inputJumlah" class="form-control"
                        value="1" min="1">
                </div>

                <div class="mt-4 text-right">
                    <button id="btnTambahkan" type="button"
                        class="btn btn-success px-4">
                        Tambahkan
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ── Tabel Transaksi ── --}}
    <div class="col-md-7 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Keranjang Transaksi</h4>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped" id="tabelPos">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tabelBody">
                            <tr id="emptyRow">
                                <td colspan="6" class="text-center text-muted">
                                    Belum ada item
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-right mt-2">
                    <h5>Total: <strong id="totalLabel">Rp 0</strong></h5>
                </div>

                <hr>

                {{-- Versi AJAX --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="text-muted small">jQuery AJAX</span>
                    <button id="btnBayarAjax" type="button"
                        class="btn btn-primary px-4">
                        Bayar (AJAX)
                    </button>
                </div>

                {{-- Versi Axios --}}
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="text-muted small">Axios</span>
                    <button id="btnBayarAxios" type="button"
                        class="btn btn-warning px-4">
                        Bayar (Axios)
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@section('js_page')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
// ═══════════════════════════════════════════════════════════════
// STATE
// ═══════════════════════════════════════════════════════════════
let bukuDitemukan = null; // data buku hasil pencarian
let totalTransaksi = 0;

// ═══════════════════════════════════════════════════════════════
// HELPER
// ═══════════════════════════════════════════════════════════════

function formatRp(angka) {
    return 'Rp ' + Number(angka).toLocaleString('id-ID');
}

function updateTotal() {
    let total = 0;
    document.querySelectorAll('#tabelBody tr[data-kode]').forEach(function(row) {
        total += parseInt(row.dataset.subtotal);
    });
    totalTransaksi = total;
    document.getElementById('totalLabel').textContent = formatRp(total);
}

function aktifkanTambah(aktif) {
    const btn = document.getElementById('btnTambahkan');
    if (aktif) btn.classList.add('aktif');
    else        btn.classList.remove('aktif');
}

function resetForm() {
    document.getElementById('inputKode').value  = '';
    document.getElementById('inputNama').value  = '';
    document.getElementById('inputHarga').value = '';
    document.getElementById('inputJumlah').value = 1;
    document.getElementById('pesanKode').classList.add('d-none');
    bukuDitemukan = null;
    aktifkanTambah(false);
}

function resetHalaman() {
    resetForm();
    document.getElementById('tabelBody').innerHTML = `
        <tr id="emptyRow">
            <td colspan="6" class="text-center text-muted">Belum ada item</td>
        </tr>`;
    totalTransaksi = 0;
    document.getElementById('totalLabel').textContent = 'Rp 0';
}

function getItems() {
    const items = [];
    document.querySelectorAll('#tabelBody tr[data-kode]').forEach(function(row) {
        items.push({
            kode:     row.dataset.kode,
            nama:     row.dataset.nama,
            harga:    parseInt(row.dataset.harga),
            jumlah:   parseInt(row.dataset.jumlah),
            subtotal: parseInt(row.dataset.subtotal),
        });
    });
    return items;
}

// ═══════════════════════════════════════════════════════════════
// CARI BUKU — tekan Enter di input kode
// ═══════════════════════════════════════════════════════════════

document.getElementById('inputKode').addEventListener('keydown', function(e) {
    if (e.key !== 'Enter') return;
    e.preventDefault();

    const kode  = this.value.trim();
    const pesan = document.getElementById('pesanKode');

    if (!kode) return;

    // Reset state sebelumnya
    bukuDitemukan = null;
    aktifkanTambah(false);
    document.getElementById('inputNama').value  = '';
    document.getElementById('inputHarga').value = '';
    pesan.classList.add('d-none');

    // Cari via jQuery AJAX
    $.ajax({
        url:    "{{ route('admin.pos.cariBuku') }}",
        method: 'GET',
        data:   { kode: kode },
        success: function(res) {
            if (res.status === 'success') {
                bukuDitemukan = res.data;
                document.getElementById('inputNama').value  = res.data.nama;
                document.getElementById('inputHarga').value = formatRp(res.data.harga);
                document.getElementById('inputJumlah').value = 1;
                aktifkanTambah(true);
                pesan.classList.add('d-none');
            }
        },
        error: function(xhr) {
            const msg = xhr.responseJSON?.message || 'Buku tidak ditemukan';
            pesan.textContent = msg;
            pesan.classList.remove('d-none');
        }
    });
});

// ═══════════════════════════════════════════════════════════════
// TAMBAHKAN KE TABEL
// ═══════════════════════════════════════════════════════════════

document.getElementById('btnTambahkan').addEventListener('click', function() {
    if (!bukuDitemukan) return;

    const jumlah   = parseInt(document.getElementById('inputJumlah').value);
    if (jumlah < 1) return;

    const kode     = bukuDitemukan.kode;
    const nama     = bukuDitemukan.nama;
    const harga    = parseInt(bukuDitemukan.harga);
    const subtotal = harga * jumlah;

    const tbody = document.getElementById('tabelBody');

    // Cek apakah kode sudah ada di tabel
    const existingRow = tbody.querySelector(`tr[data-kode="${kode}"]`);
    if (existingRow) {
        // Update jumlah & subtotal saja
        const jumlahBaru   = parseInt(existingRow.dataset.jumlah) + jumlah;
        const subtotalBaru = harga * jumlahBaru;

        existingRow.dataset.jumlah   = jumlahBaru;
        existingRow.dataset.subtotal = subtotalBaru;
        existingRow.querySelector('.qty-input').value = jumlahBaru;
        existingRow.querySelector('.subtotal-cell').textContent = formatRp(subtotalBaru);
    } else {
        // Hapus empty row kalau masih ada
        const emptyRow = document.getElementById('emptyRow');
        if (emptyRow) emptyRow.remove();

        // Tambah row baru
        const row = document.createElement('tr');
        row.dataset.kode     = kode;
        row.dataset.nama     = nama;
        row.dataset.harga    = harga;
        row.dataset.jumlah   = jumlah;
        row.dataset.subtotal = subtotal;

        row.innerHTML = `
            <td>${kode}</td>
            <td>${nama}</td>
            <td>${formatRp(harga)}</td>
            <td>
                <input type="number" class="qty-input" value="${jumlah}" min="1">
            </td>
            <td class="subtotal-cell">${formatRp(subtotal)}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm btn-hapus">
                    Hapus
                </button>
            </td>
        `;
        tbody.appendChild(row);
    }

    updateTotal();
    resetForm();
});

// ═══════════════════════════════════════════════════════════════
// UPDATE JUMLAH & HAPUS ROW (event delegation)
// ═══════════════════════════════════════════════════════════════

document.getElementById('tabelBody').addEventListener('input', function(e) {
    if (!e.target.classList.contains('qty-input')) return;

    const row      = e.target.closest('tr');
    const jumlah   = parseInt(e.target.value);
    if (isNaN(jumlah) || jumlah < 1) return;

    const harga    = parseInt(row.dataset.harga);
    const subtotal = harga * jumlah;

    row.dataset.jumlah   = jumlah;
    row.dataset.subtotal = subtotal;
    row.querySelector('.subtotal-cell').textContent = formatRp(subtotal);
    updateTotal();
});

document.getElementById('tabelBody').addEventListener('click', function(e) {
    if (!e.target.classList.contains('btn-hapus')) return;

    const row = e.target.closest('tr');
    row.remove();
    updateTotal();

    // Kalau tbody kosong tampilkan empty row
    const tbody = document.getElementById('tabelBody');
    if (tbody.children.length === 0) {
        tbody.innerHTML = `
            <tr id="emptyRow">
                <td colspan="6" class="text-center text-muted">Belum ada item</td>
            </tr>`;
    }
});

// ═══════════════════════════════════════════════════════════════
// BAYAR — jQuery AJAX
// ═══════════════════════════════════════════════════════════════

document.getElementById('btnBayarAjax').addEventListener('click', function() {
    const items = getItems();
    if (items.length === 0) {
        Swal.fire('Perhatian', 'Keranjang masih kosong!', 'warning');
        return;
    }

    const btn = this;
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Memproses...`;

    $.ajax({
        url:    "{{ route('admin.pos.bayar') }}",
        method: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            items:  items,
            total:  totalTransaksi,
        },
        success: function(res) {
            btn.disabled = false;
            btn.innerHTML = 'Bayar (AJAX)';

            if (res.status === 'success') {
                Swal.fire({
                    icon:  'success',
                    title: 'Berhasil!',
                    text:  'Transaksi #' + res.data.id_penjualan + ' berhasil disimpan.',
                }).then(function() { resetHalaman(); });
            } else {
                Swal.fire('Gagal', res.message, 'error');
            }
        },
        error: function() {
            btn.disabled = false;
            btn.innerHTML = 'Bayar (AJAX)';
            Swal.fire('Error', 'Terjadi kesalahan saat menyimpan transaksi.', 'error');
        }
    });
});

// ═══════════════════════════════════════════════════════════════
// BAYAR — Axios
// ═══════════════════════════════════════════════════════════════

document.getElementById('btnBayarAxios').addEventListener('click', function() {
    const items = getItems();
    if (items.length === 0) {
        Swal.fire('Perhatian', 'Keranjang masih kosong!', 'warning');
        return;
    }

    const btn = this;
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Memproses...`;

    axios.post("{{ route('admin.pos.bayar') }}", {
        _token: "{{ csrf_token() }}",
        items:  items,
        total:  totalTransaksi,
    })
    .then(function(res) {
        btn.disabled = false;
        btn.innerHTML = 'Bayar (Axios)';

        if (res.data.status === 'success') {
            Swal.fire({
                icon:  'success',
                title: 'Berhasil!',
                text:  'Transaksi #' + res.data.data.id_penjualan + ' berhasil disimpan.',
            }).then(function() { resetHalaman(); });
        } else {
            Swal.fire('Gagal', res.data.message, 'error');
        }
    })
    .catch(function(err) {
        btn.disabled = false;
        btn.innerHTML = 'Bayar (Axios)';
        Swal.fire('Error', 'Terjadi kesalahan saat menyimpan transaksi.', 'error');
        console.log(err);
    });
});
</script>
@endsection