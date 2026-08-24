@extends('layouts.user')

@section('title', 'Beli Buku Online')

@section('style_page')
<style>
    #totalDisplay { font-size: 1.4rem; font-weight: 700; color: var(--primary-700, #6C63FF); }
    .item-row { border-bottom: 1px solid #eee; padding: 8px 0; }
</style>
@endsection

@section('content')

<h4 class="mb-4"><i class="mdi mdi-cart-outline text-primary"></i> Beli Buku Online</h4>

<div class="row">
    {{-- Form Pilih Buku --}}
    <div class="col-md-5 mb-4">
        <div class="card mb-3">
            <div class="card-header card-header-gradient">
                <i class="mdi mdi-package-variant"></i> Pilih Vendor & Buku
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Vendor</label>
                    <select id="selectVendor" class="form-control">
                        <option value="">-- Pilih Vendor --</option>
                        @foreach($vendor as $v)
                            <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="bukuSection" class="d-none">
                    <div class="form-group">
                        <label>Buku</label>
                        <select id="selectBuku" class="form-control">
                            <option value="">-- Pilih Buku --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" id="inputJumlah" class="form-control" value="1" min="1">
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <input type="text" id="inputCatatan" class="form-control" placeholder="Opsional...">
                    </div>
                    <button id="btnTambah" class="btn btn-success btn-block">
                        + Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header card-header-gradient">
                <i class="mdi mdi-credit-card-outline"></i> Metode Pembayaran
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="custom-control custom-radio mb-2">
                        <input type="radio" id="metodeVA" name="metode" value="1"
                               class="custom-control-input" checked>
                        <label class="custom-control-label" for="metodeVA">Virtual Account (Bank)</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="metodeQR" name="metode" value="2"
                               class="custom-control-input">
                        <label class="custom-control-label" for="metodeQR">QRIS / GoPay</label>
                    </div>
                </div>
                <div class="text-center mb-3">
                    <p class="mb-1 text-muted small">Total</p>
                    <div id="totalDisplay">Rp 0</div>
                </div>
                <button id="btnCheckout" class="btn btn-block py-2 btn-pill" disabled
                    style="background:linear-gradient(135deg,var(--primary-700),var(--primary-500));color:white;">
                    Checkout & Bayar
                </button>
            </div>
        </div>
    </div>

    {{-- Keranjang --}}
    <div class="col-md-7 mb-4">
        <div class="card">
            <div class="card-header card-header-gradient d-flex justify-content-between align-items-center">
                <span><i class="mdi mdi-cart-outline"></i> Keranjang Belanja</span>
                <button id="btnKosongkan" class="btn btn-sm btn-light btn-pill">Kosongkan</button>
            </div>
            <div class="card-body">
                <div class="empty-state py-3" id="emptyMsg">
                    <i class="mdi mdi-cart-outline"></i>
                    <p class="title">Keranjang masih kosong.</p>
                    <p class="desc">Pilih vendor & buku di sebelah kiri untuk mulai belanja.</p>
                </div>
                <div id="keranjangList"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js_page')
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
let keranjang = [];
let bukuData  = {};

// Pilih vendor → load buku
document.getElementById('selectVendor').addEventListener('change', function() {
    const id = this.value;
    if (!id) { document.getElementById('bukuSection').classList.add('d-none'); return; }

    axios.get("{{ route('user.cariBuku') }}", { params: { idvendor: id } })
    .then(res => {
        const sel = document.getElementById('selectBuku');
        sel.innerHTML = '<option value="">-- Pilih Buku --</option>';
        bukuData = {};
        res.data.data.forEach(b => {
            bukuData[b.kode] = b;
            sel.innerHTML += `<option value="${b.kode}">[${b.kode}] ${b.nama} — Rp ${formatRp(b.harga)}</option>`;
        });
        document.getElementById('bukuSection').classList.remove('d-none');
    });
});

// Tambah ke keranjang
document.getElementById('btnTambah').addEventListener('click', function() {
    const kode    = document.getElementById('selectBuku').value;
    const jumlah  = parseInt(document.getElementById('inputJumlah').value) || 1;
    const catatan = document.getElementById('inputCatatan').value;
    if (!kode) { Swal.fire('Pilih buku terlebih dahulu.'); return; }

    const b   = bukuData[kode];
    const idx = keranjang.findIndex(k => k.kode === kode);

    if (idx >= 0) {
        keranjang[idx].jumlah  += jumlah;
        keranjang[idx].subtotal = keranjang[idx].jumlah * keranjang[idx].harga;
        keranjang[idx].catatan  = catatan;
    } else {
        keranjang.push({ kode, nama: b.nama, harga: b.harga, jumlah, subtotal: b.harga * jumlah, catatan });
    }

    renderKeranjang();
});

function renderKeranjang() {
    const list  = document.getElementById('keranjangList');
    const empty = document.getElementById('emptyMsg');
    const total = keranjang.reduce((a, b) => a + b.subtotal, 0);

    document.getElementById('totalDisplay').textContent = 'Rp ' + formatRp(total);
    document.getElementById('btnCheckout').disabled = keranjang.length === 0;

    if (!keranjang.length) { empty.classList.remove('d-none'); list.innerHTML = ''; return; }

    empty.classList.add('d-none');
    list.innerHTML = keranjang.map((item, i) => `
        <div class="item-row d-flex justify-content-between align-items-start">
            <div>
                <strong>${item.nama}</strong> <small class="text-muted">(${item.kode})</small><br>
                <small>Rp ${formatRp(item.harga)} × ${item.jumlah} = <strong>Rp ${formatRp(item.subtotal)}</strong></small>
                ${item.catatan ? `<br><small class="text-muted"><i class="mdi mdi-note-text-outline"></i> ${item.catatan}</small>` : ''}
            </div>
            <button class="btn btn-sm btn-outline-danger ml-2" onclick="hapus(${i})">✕</button>
        </div>
    `).join('');
}

function hapus(i) { keranjang.splice(i, 1); renderKeranjang(); }
document.getElementById('btnKosongkan').addEventListener('click', () => { keranjang = []; renderKeranjang(); });

// Checkout
document.getElementById('btnCheckout').addEventListener('click', function() {
    const total   = keranjang.reduce((a, b) => a + b.subtotal, 0);
    const metode  = document.querySelector('input[name="metode"]:checked').value;

    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';

    axios.post("{{ route('user.checkout') }}", {
        _token: "{{ csrf_token() }}",
        items: keranjang,
        total: total,
        metode_bayar: metode,
    })
    .then(res => {
        if (res.data.status === 'success') {
            snap.pay(res.data.snap_token, {
                onSuccess: () => { window.location = "{{ url('user/status') }}/" + res.data.order_id; },
                onPending: () => { window.location = "{{ url('user/status') }}/" + res.data.order_id; },
                onError:   () => { Swal.fire('Pembayaran gagal. Coba lagi.'); },
                onClose:   () => { Swal.fire('Anda menutup jendela pembayaran.'); },
            });
        }
    })
    .catch(() => { Swal.fire('Error', 'Gagal membuat transaksi.', 'error'); })
    .finally(() => {
        this.disabled = false;
        this.innerHTML = 'Checkout & Bayar';
    });
});

function formatRp(n) { return Number(n).toLocaleString('id-ID'); }
</script>
@endsection
