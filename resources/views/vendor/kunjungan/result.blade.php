@extends('layouts.vendor')

@section('title','Hasil Kunjungan')
@section('page_title','Hasil Kunjungan')

@section('content')
<div class="card">
    <div class="card-header blue">Hasil Validasi Kunjungan</div>
    <div class="card-body">
        <div class="mb-4">
            <h5>{{ $toko->nama_toko }}</h5>
            <p class="text-muted mb-1">Barcode: {{ $toko->barcode }}</p>
            <p class="text-muted mb-1">Latitude Toko: {{ $toko->latitude }} | Longitude Toko: {{ $toko->longitude }}</p>
            <p class="text-muted mb-1">Accuracy Toko: {{ $accuracy_toko }} m</p>
        </div>

        <div class="mb-4">
            <p><strong>Lokasi Vendor:</strong></p>
            <p>Latitude: {{ $kunjungan->latitude_vendor }}</p>
            <p>Longitude: {{ $kunjungan->longitude_vendor }}</p>
            <p>Accuracy Vendor: {{ $accuracy_vendor }} m</p>
            <p>Jarak: {{ number_format($kunjungan->jarak, 2) }} m</p>
            <p>Threshold Efektif: {{ number_format($kunjungan->threshold_efektif, 2) }} m</p>
            <p>Status: <strong>{{ $status }}</strong></p>
            <p>Waktu Kunjungan: {{ $kunjungan->waktu_kunjungan }}</p>
        </div>

        @if($status === 'DITERIMA')
            <div class="alert alert-success">Kunjungan diterima. Vendor dapat menambahkan stok buku toko.</div>
            <a href="{{ route('vendor.kunjungan.store', ['barcode' => $toko->barcode]) }}" class="btn btn-primary">Lihat Stok Toko</a>
        @else
            <div class="alert alert-danger">Kunjungan ditolak. Jarak melebihi threshold efektif.</div>
            <a href="{{ route('vendor.kunjungan.form') }}" class="btn btn-secondary">Kembali ke Form Kunjungan</a>
        @endif
    </div>
</div>
@endsection