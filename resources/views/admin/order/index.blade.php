<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesan Buku Online</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <style>
        body { background: #f4f6f9; }
        .navbar-brand { font-weight: bold; color: #4B49AC !important; }
        .card { border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .card-header { background: #4B49AC; color: white; border-radius: 10px 10px 0 0 !important; }
        .btn-primary { background: #4B49AC; border-color: #4B49AC; }
        .btn-primary:hover { background: #3b3a8c; }
        .qty-input { width: 70px; text-align: center; border: 1px solid #ced4da; border-radius: 4px; padding: 2px 6px; }
        #btnTambahkan { opacity: 0.5; pointer-events: none; }
        #btnTambahkan.aktif { opacity: 1; pointer-events: auto; }
        .select2-container .select2-selection--single {
            height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 0.75rem + 2px);
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5; padding-left: 0; color: #495057;
        }
        .select2-container { width: 100% !important; }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white shadow-sm mb-4">
    <div class="container">
        <span class="navbar-brand">📚 Toko Buku Online</span>
    </div>
</nav>

<div class="container">
    <div class="row">

        {{-- Form Pemesanan --}}
        <div class="col-md-5 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pilih Buku</h5>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label>Vendor / Penyedia</label>
                        <select id="selectVendor" class="form-control" style="width:100%">
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendor as $v)
                                <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Pilih Buku</label>
                        <select id="selectBuku" class="form-control" style="width:100%" disabled>
                            <option value="">-- Pilih Vendor dulu --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Harga</label>
                        <input type="text" id="inputHarga" class="form-control" readonly
                            style="background:#fff0f0">
                    </div>

                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" id="inputJumlah" class="form-control"
                            value="1" min="1">
                    </div>

                    <div class="form-group">
                        <label>Catatan <small class="text-muted">(opsional)</small></label>
                        <input type="text" id="inputCatatan" class="form-control"
                            placeholder="Contoh: Edisi terbaru...">
                    </div>

                    <button id="btnTambahkan" type="button" class="btn btn-success btn-block mt-2">
                        + Tambahkan ke Keranjang
                    </button>

                </div>
            </div>
        </div>

        {{-- Keranjang --}}
        <div class="col-md-7 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Keranjang</h5>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Buku</th>
                                    <th>Harga</th>
                                    <th>Jml</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tabelBody">
                                <tr id="emptyRow">
                                    <td colspan="5" class="text-center text-muted">
                                        Keranjang kosong
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-right">
                        <h5>Total: <strong id="totalLabel">Rp 0</strong></h5>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <select id="metodeBayar" class="form-control">
                            <option value="1">Virtual Account (Bank Transfer)</option>
                            <option value="2">QRIS</option>
                        </select>
                    </div>

                    <button id="btnCheckout" type="button"
                        class="btn btn-primary btn-block">
                        Bayar Sekarang
                    </button>

                </div>
            </div>
        </div>

    </div>
</div>

{{-- Modal Pembayaran Midtrans --}}
<div class="modal fade" id="modalBayar" tabindex="-1" role="dialog"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pembayaran</h5>
            </div>
            <div class="modal-body text-center">
                <div id="snapContainer"></div>
                <div id="loadingBayar">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Memuat halaman pembayaran...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

{{-- Midtrans Snap JS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
// ═══════════════════════════════════════════════════════════════
// STATE
// ═══════════════════════════════════════════════════════════════
let bukuTerpilih  = null;
let totalTransaksi = 0;

// ═══════════════════════════════════════════════════════════════
// HELPER
// ═══════════════════════════════════════════════════════════════
function formatRp(n) {
    return 'Rp ' + Number(n).toLocaleString('id-ID');
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
    document.getElementById('selectBuku').value = '';
    document.getElementById('inputHarga').value = '';
    document.getElementById('inputJumlah').value = 1;
    document.getElementById('inputCatatan').value = '';
    bukuTerpilih = null;
    aktifkanTambah(false);
    $('#selectBuku').trigger('change.select2');
}

// ═══════════════════════════════════════════════════════════════
// INIT SELECT2
// ═══════════════════════════════════════════════════════════════
$('#selectVendor').select2({ placeholder: '-- Pilih Vendor --', allowClear: true });
$('#selectBuku').select2({ placeholder: '-- Pilih Vendor dulu --', allowClear: true });

// ═══════════════════════════════════════════════════════════════
// CHAINED SELECT: Vendor → Buku (Axios)
// ═══════════════════════════════════════════════════════════════
$('#selectVendor').on('change', function() {
    const idvendor = this.value;
    const selectBuku = document.getElementById('selectBuku');

    // Reset
    bukuTerpilih = null;
    aktifkanTambah(false);
    document.getElementById('inputHarga').value = '';
    selectBuku.innerHTML = '<option value="">-- Memuat buku... --</option>';
    selectBuku.disabled = true;
    $('#selectBuku').select2('destroy');

    if (!idvendor) {
        selectBuku.innerHTML = '<option value="">-- Pilih Vendor dulu --</option>';
        $('#selectBuku').select2({ placeholder: '-- Pilih Vendor dulu --', allowClear: true });
        return;
    }

    axios.get("{{ route('admin.order.cariBuku') }}", { params: { idvendor: idvendor } })
        .then(function(res) {
            selectBuku.innerHTML = '<option value="">-- Pilih Buku --</option>';
            res.data.data.forEach(function(b) {
                const opt = document.createElement('option');
                opt.value           = b.kode;
                opt.textContent     = `[${b.kode}] ${b.nama}`;
                opt.dataset.harga   = b.harga;
                opt.dataset.nama    = b.nama;
                selectBuku.appendChild(opt);
            });
            selectBuku.disabled = false;
            $('#selectBuku').select2({ placeholder: '-- Pilih Buku --', allowClear: true });
        })
        .catch(function() {
            selectBuku.innerHTML = '<option value="">Gagal memuat buku</option>';
            $('#selectBuku').select2({ placeholder: 'Gagal memuat buku' });
        });
});

// Buku dipilih → tampilkan harga
$('#selectBuku').on('change', function() {
    const opt = this.options[this.selectedIndex];
    if (this.value && opt.dataset.harga) {
        bukuTerpilih = {
            kode  : this.value,
            nama  : opt.dataset.nama,
            harga : parseInt(opt.dataset.harga),
        };
        document.getElementById('inputHarga').value = formatRp(bukuTerpilih.harga);
        aktifkanTambah(true);
    } else {
        bukuTerpilih = null;
        document.getElementById('inputHarga').value = '';
        aktifkanTambah(false);
    }
});

// ═══════════════════════════════════════════════════════════════
// TAMBAHKAN KE KERANJANG
// ═══════════════════════════════════════════════════════════════
document.getElementById('btnTambahkan').addEventListener('click', function() {
    if (!bukuTerpilih) return;

    const jumlah   = parseInt(document.getElementById('inputJumlah').value);
    const catatan  = document.getElementById('inputCatatan').value;
    if (jumlah < 1) return;

    const kode     = bukuTerpilih.kode;
    const nama     = bukuTerpilih.nama;
    const harga    = bukuTerpilih.harga;
    const subtotal = harga * jumlah;
    const tbody    = document.getElementById('tabelBody');

    // Cek duplikat
    const existing = tbody.querySelector(`tr[data-kode="${kode}"]`);
    if (existing) {
        const jumlahBaru   = parseInt(existing.dataset.jumlah) + jumlah;
        const subtotalBaru = harga * jumlahBaru;
        existing.dataset.jumlah   = jumlahBaru;
        existing.dataset.subtotal = subtotalBaru;
        existing.querySelector('.qty-input').value = jumlahBaru;
        existing.querySelector('.subtotal-cell').textContent = formatRp(subtotalBaru);
    } else {
        const emptyRow = document.getElementById('emptyRow');
        if (emptyRow) emptyRow.remove();

        const row = document.createElement('tr');
        row.dataset.kode     = kode;
        row.dataset.nama     = nama;
        row.dataset.harga    = harga;
        row.dataset.jumlah   = jumlah;
        row.dataset.subtotal = subtotal;
        row.dataset.catatan  = catatan;

        row.innerHTML = `
            <td>${nama}<br><small class="text-muted">${catatan || ''}</small></td>
            <td>${formatRp(harga)}</td>
            <td><input type="number" class="qty-input" value="${jumlah}" min="1"></td>
            <td class="subtotal-cell">${formatRp(subtotal)}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm btn-hapus">✕</button>
            </td>
        `;
        tbody.appendChild(row);
    }

    updateTotal();
    resetForm();
});

// Update jumlah & hapus
document.getElementById('tabelBody').addEventListener('input', function(e) {
    if (!e.target.classList.contains('qty-input')) return;
    const row      = e.target.closest('tr');
    const jumlah   = parseInt(e.target.value);
    if (isNaN(jumlah) || jumlah < 1) return;
    const subtotal = parseInt(row.dataset.harga) * jumlah;
    row.dataset.jumlah   = jumlah;
    row.dataset.subtotal = subtotal;
    row.querySelector('.subtotal-cell').textContent = formatRp(subtotal);
    updateTotal();
});

document.getElementById('tabelBody').addEventListener('click', function(e) {
    if (!e.target.classList.contains('btn-hapus')) return;
    e.target.closest('tr').remove();
    updateTotal();
    if (document.getElementById('tabelBody').children.length === 0) {
        document.getElementById('tabelBody').innerHTML =
            '<tr id="emptyRow"><td colspan="5" class="text-center text-muted">Keranjang kosong</td></tr>';
    }
});

// ═══════════════════════════════════════════════════════════════
// CHECKOUT — Axios → Midtrans Snap
// ═══════════════════════════════════════════════════════════════
document.getElementById('btnCheckout').addEventListener('click', function() {
    const items = [];
    document.querySelectorAll('#tabelBody tr[data-kode]').forEach(function(row) {
        items.push({
            kode    : row.dataset.kode,
            nama    : row.dataset.nama,
            harga   : parseInt(row.dataset.harga),
            jumlah  : parseInt(row.dataset.jumlah),
            subtotal: parseInt(row.dataset.subtotal),
            catatan : row.dataset.catatan || '',
        });
    });

    if (items.length === 0) {
        Swal.fire('Perhatian', 'Keranjang masih kosong!', 'warning');
        return;
    }

    const btn = this;
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Memproses...`;

    axios.post("{{ route('admin.order.checkout') }}", {
        _token      : "{{ csrf_token() }}",
        items       : items,
        total       : totalTransaksi,
        metode_bayar: document.getElementById('metodeBayar').value,
    })
    .then(function(res) {
        btn.disabled = false;
        btn.innerHTML = 'Bayar Sekarang';

        if (res.data.status === 'success') {
            // Buka Midtrans Snap
            snap.pay(res.data.snap_token, {
                onSuccess: function(result) {
                    Swal.fire({
                        icon : 'success',
                        title: 'Pembayaran Berhasil!',
                        text : 'Terima kasih ' + res.data.nama_guest + '! Pesanan kamu sudah lunas.',
                    }).then(function() {
                        window.location.href =
                            "{{ url('order/status') }}/" + res.data.order_id;
                    });
                },
                onPending: function(result) {
                    Swal.fire('Menunggu Pembayaran',
                        'Silahkan selesaikan pembayaran kamu.', 'info');
                },
                onError: function(result) {
                    Swal.fire('Pembayaran Gagal',
                        'Terjadi kesalahan saat pembayaran.', 'error');
                },
                onClose: function() {
                    Swal.fire('Info',
                        'Kamu menutup halaman pembayaran.', 'info');
                }
            });
        }
    })
    .catch(function(err) {
        btn.disabled = false;
        btn.innerHTML = 'Bayar Sekarang';
        Swal.fire('Error',
            err.response?.data?.message || 'Terjadi kesalahan.', 'error');
    });
});
</script>
</body>
</html>