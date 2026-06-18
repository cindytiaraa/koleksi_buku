@extends('layouts.admin')

@section('style_page')
<link rel="stylesheet"
    href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')

<div class="page-header">
    <h3 class="page-title">JS Modul — DataTables</h3>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Input Buku</h4>

                <form id="formBukuDT">
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
                    <table class="table table-striped" id="tabelDT">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
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
                        <label>ID</label>
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
                <button id="btnHapus" type="button" class="btn btn-danger">Hapus</button>
                <button id="btnUbah" type="button" class="btn btn-primary">Ubah</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js_page')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    const table = $('#tabelDT').DataTable({
        language: {
            emptyTable:  "Belum ada data",
            zeroRecords: "Data tidak ditemukan",
            info:        "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty:   "Menampilkan 0 data",
            search:      "Cari:",
            paginate: { next: "Selanjutnya", previous: "Sebelumnya" }
        }
    });

    let counter = 1;
    let selectedRow = null;

    // Tambah
    document.getElementById('btnTambah').addEventListener('click', function () {
        const form  = document.getElementById('formBukuDT');
        const nama  = document.getElementById('inputNama');
        const harga = document.getElementById('inputHarga');

        if (!form.checkValidity()) { form.reportValidity(); return; }

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span>`;

        setTimeout(function () {
            const id       = `BRG-${String(counter).padStart(3,'0')}`;
            const namaVal  = nama.value;
            const hargaVal = Number(harga.value);

            const rowNode = table.row.add([
                id, namaVal,
                `Rp ${hargaVal.toLocaleString('id-ID')}`
            ]).draw().node();

            rowNode.dataset.id    = id;
            rowNode.dataset.nama  = namaVal;
            rowNode.dataset.harga = hargaVal;
            rowNode.style.cursor  = 'pointer';

            counter++;
            nama.value = ''; harga.value = '';
            btn.disabled = false;
            btn.innerHTML = 'Tambah';
        }, 600);
    });

    // Klik row
    $('#tabelDT tbody').on('click', 'tr', function () {
        if (!table.row(this).data()) return;
        selectedRow = this;
        document.getElementById('modalId').value    = this.dataset.id;
        document.getElementById('modalNama').value  = this.dataset.nama;
        document.getElementById('modalHarga').value = this.dataset.harga;
        $('#modalEditHapus').modal('show');
    });

    // Ubah
    document.getElementById('btnUbah').addEventListener('click', function () {
        const form = document.getElementById('formModal');
        if (!form.checkValidity()) { form.reportValidity(); return; }

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Menyimpan...`;

        setTimeout(function () {
            const nama  = document.getElementById('modalNama').value;
            const harga = Number(document.getElementById('modalHarga').value);

            selectedRow.dataset.nama  = nama;
            selectedRow.dataset.harga = harga;

            table.row(selectedRow).data([
                selectedRow.dataset.id, nama,
                `Rp ${harga.toLocaleString('id-ID')}`
            ]).draw();

            const newRow = document.querySelector(`tr[data-id="${selectedRow.dataset.id}"]`);
            if (newRow) {
                newRow.dataset.nama  = nama;
                newRow.dataset.harga = harga;
                newRow.style.cursor  = 'pointer';
                selectedRow = newRow;
            }

            btn.disabled = false;
            btn.innerHTML = 'Ubah';
            $('#modalEditHapus').modal('hide');
        }, 600);
    });

    // Hapus
    document.getElementById('btnHapus').addEventListener('click', function () {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Menghapus...`;

        setTimeout(function () {
            table.row(selectedRow).remove().draw();
            btn.disabled = false;
            btn.innerHTML = 'Hapus';
            $('#modalEditHapus').modal('hide');
        }, 600);
    });
</script>
@endsection