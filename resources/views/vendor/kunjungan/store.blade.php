@extends('layouts.vendor')

@section('title','Stok Toko')
@section('page_title','Stok Toko')

@section('content')
<div class="card">
    <div class="card-header blue">Stok Toko: {{ $toko->nama_toko }}</div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-4">
            <p><strong>Barcode Toko:</strong> {{ $toko->barcode }}</p>
            <p><strong>Latitude:</strong> {{ $toko->latitude }} | <strong>Longitude:</strong> {{ $toko->longitude }}</p>
        </div>

        <div class="table-responsive mb-4">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode Buku</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Stok Saat Ini</th>
                        <th>Tambah Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stocks as $stock)
                    <tr>
                        <td>{{ $stock->kode }}</td>
                        <td>{{ $stock->judul }}</td>
                        <td>{{ $stock->pengarang }}</td>
                        <td>{{ $stock->jumlah_stok }}</td>
                        <td>
                            <form action="{{ route('vendor.kunjungan.tambah_stok') }}" method="POST" class="d-flex gap-2 align-items-center">
                                @csrf
                                <input type="hidden" name="barcode_toko" value="{{ $toko->barcode }}">
                                <input type="hidden" name="buku_id" value="{{ $stock->idbuku }}">
                                <input type="number" name="stok_tambah" class="form-control form-control-sm" min="1" value="1" style="width:100px;">
                                <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <a href="{{ route('vendor.kunjungan.form') }}" class="btn btn-secondary">Kembali ke Kunjungan</a>
    </div>
</div>
@endsection