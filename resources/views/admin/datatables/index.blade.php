@extends('layouts.admin')

@section('style_page')
<link rel="stylesheet"
    href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')

<div class="page-header">
    <h3 class="page-title">DataTables Buku</h3>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Buku</h4>

                {{-- Filter Kategori --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select id="filterKategori" class="form-control">
                            <option value="">-- Semua Kategori --</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->nama_kategori }}">
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped" id="tabelBukuDT">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($buku as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $item->kode }}</strong></td>
                                <td>{{ $item->judul }}</td>
                                <td>{{ $item->pengarang }}</td>
                                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                <td>Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    @if($item->status == 1)
                                        <span class="badge badge-success">Tersedia</span>
                                    @else
                                        <span class="badge badge-danger">Tidak Tersedia</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('js_page')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    // Init DataTables
    const table = $('#tabelBukuDT').DataTable({
        language: {
            emptyTable:  "Belum ada data",
            zeroRecords: "Data tidak ditemukan",
            info:        "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty:   "Menampilkan 0 data",
            search:      "Cari:",
            paginate: { next: "Selanjutnya", previous: "Sebelumnya" }
        }
    });

    // Filter by kategori
    $('#filterKategori').on('change', function () {
        table.column(4).search(this.value).draw();
    });
</script>
@endsection