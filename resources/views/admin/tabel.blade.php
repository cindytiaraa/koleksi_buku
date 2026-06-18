@extends('layouts.admin')

@section('content')

<div class="page-header">
    <h3 class="page-title">Tabel Buku — HTML Biasa</h3>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Input Buku</h4>

                <form id="formBuku">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Nama Buku</label>
                                <input type="text" id="inputNama" class="form-control"
                                    placeholder="Nama buku..." required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Harga</label>
                                <input type="number" id="inputHarga" class="form-control"
                                    placeholder="Harga..." required min="0">
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-group w-100">
                                <label class="d-block">&nbsp;</label>
                                <button id="btnTambah" type="button" class="btn btn-primary w-100">
                                    Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <hr>

                <div class="table-responsive">
                    <table class="table table-striped" id="tabelBuku">
                        <thead>
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody id="tabelBody">
                            <tr id="emptyRow">
                                <td colspan="3" class="text-center text-muted">
                                    Belum ada data
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Modal Edit/Hapus --}}
<div class="modal fade" id="modalEditHapus" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detail Buku</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="formModal">
                    <div class="form-group">
                        <label>ID Barang</label>
                        <input type="text" id="modalId" class="form-control" readonly>
                    </div>
                    <div class="form-group mt-2">
                        <label>Nama Buku</label>
                        <input type="text" id="modalNama" class="form-control" required>
                    </div>
                    <div class="form-group mt-2">
                        <label>Harga</label>
                        <input type="number" id="modalHarga" class="form-control" required min="0">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="btnHapus" type="button" class="btn btn-danger">
                    Hapus
                </button>
                <button id="btnUbah" type="button" class="btn btn-primary">
                    Ubah
                </button>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js_page')
<script>
    let counter = 1;
    let selectedRow = null; // menyimpan referensi row yang diklik

    // ── Tambah row ──────────────────────────────────────────
    document.getElementById('btnTambah').addEventListener('click', function () {
        const form  = document.getElementById('formBuku');
        const nama  = document.getElementById('inputNama');
        const harga = document.getElementById('inputHarga');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status"></span>`;

        setTimeout(function () {
            const emptyRow = document.getElementById('emptyRow');
            if (emptyRow) emptyRow.remove();

            const tbody = document.getElementById('tabelBody');
            const row   = document.createElement('tr');

            const idBarang = `BRG-${String(counter).padStart(3, '0')}`;
            const namaVal  = nama.value;
            const hargaVal = Number(harga.value);

            // Simpan data mentah di dataset supaya mudah dibaca saat modal dibuka
            row.dataset.id    = idBarang;
            row.dataset.nama  = namaVal;
            row.dataset.harga = hargaVal;

            row.innerHTML = `
                <td>${idBarang}</td>
                <td>${namaVal}</td>
                <td>Rp ${hargaVal.toLocaleString('id-ID')}</td>
            `;

            // Cursor pointer + klik buka modal
            row.style.cursor = 'pointer';
            row.addEventListener('click', function () {
                bukaModal(this);
            });

            tbody.appendChild(row);
            counter++;

            nama.value  = '';
            harga.value = '';
            btn.disabled = false;
            btn.innerHTML = 'Tambah';

        }, 600);
    });

    // ── Buka modal ──────────────────────────────────────────
    function bukaModal(row) {
        selectedRow = row;
        document.getElementById('modalId').value    = row.dataset.id;
        document.getElementById('modalNama').value  = row.dataset.nama;
        document.getElementById('modalHarga').value = row.dataset.harga;
        $('#modalEditHapus').modal('show');
    }

    // ── Ubah ────────────────────────────────────────────────
    document.getElementById('btnUbah').addEventListener('click', function () {
        const form  = document.getElementById('formModal');
        const nama  = document.getElementById('modalNama');
        const harga = document.getElementById('modalHarga');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status"></span> Menyimpan...`;

        setTimeout(function () {
            const hargaVal = Number(harga.value);

            // Update dataset row
            selectedRow.dataset.nama  = nama.value;
            selectedRow.dataset.harga = hargaVal;

            // Update tampilan cell
            selectedRow.cells[1].innerText = nama.value;
            selectedRow.cells[2].innerText = `Rp ${hargaVal.toLocaleString('id-ID')}`;

            btn.disabled = false;
            btn.innerHTML = 'Ubah';

            $('#modalEditHapus').modal('hide');
        }, 600);
    });

    // ── Hapus ───────────────────────────────────────────────
    document.getElementById('btnHapus').addEventListener('click', function () {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status"></span> Menghapus...`;

        setTimeout(function () {
            selectedRow.remove();

            // Kalau tbody kosong, tampilkan empty row lagi
            const tbody = document.getElementById('tabelBody');
            if (tbody.children.length === 0) {
                tbody.innerHTML = `
                    <tr id="emptyRow">
                        <td colspan="3" class="text-center text-muted">Belum ada data</td>
                    </tr>`;
            }

            btn.disabled = false;
            btn.innerHTML = 'Hapus';

            $('#modalEditHapus').modal('hide');
        }, 600);
    });
</script>
@endsection