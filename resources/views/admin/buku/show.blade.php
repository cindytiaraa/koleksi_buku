@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-book-open-page-variant"></i>
        </span>
        Detail Buku
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.buku.index') }}">Data Buku</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="card-title">QR Code Buku</h4>
                <div class="mt-4 p-3 bg-white d-inline-block border rounded">
                    {!! $qrcode !!}
                </div>
                <div class="mt-3">
                    <p class="text-muted small">Scan QR Code ini untuk melihat detail atau memproses buku di sistem scanner.</p>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.buku.cetak-qr', $data->idbuku) }}" class="btn btn-gradient-info btn-sm">
                        <i class="mdi mdi-printer"></i> Cetak QR
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Informasi Lengkap</h4>
                    <span class="badge {{ $data->status == 1 ? 'badge-success' : 'badge-danger' }}">
                        {{ $data->status == 1 ? 'Tersedia' : 'Kosong' }}
                    </span>
                </div>
                
                <table class="table table-bordered">
                    <tr>
                        <th width="200" class="bg-light">Kode Buku</th>
                        <td><strong>{{ $data->kode }}</strong></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Judul Buku</th>
                        <td>{{ $data->judul }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Pengarang</th>
                        <td>{{ $data->pengarang }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Kategori</th>
                        <td>{{ $data->kategori->nama_kategori ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Harga</th>
                        <td>Rp {{ number_format($data->harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Ditambahkan Pada</th>
                        <td>{{ $data->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                </table>

                <div class="mt-5">
                    <a href="{{ route('admin.buku.edit', $data->idbuku) }}" class="btn btn-gradient-warning">
                        <i class="mdi mdi-pencil"></i> Edit Data
                    </a>
                    <a href="{{ route('admin.buku.index') }}" class="btn btn-light">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
