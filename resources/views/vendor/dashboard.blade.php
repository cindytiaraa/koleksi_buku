@extends('layouts.vendor')
@section('page_title','Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6C63FF,#8B5CF6)">
            <h2>{{ $totalBuku }}</h2>
            <p>📚 Total Buku Terdaftar</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#065f46,#10b981)">
            <h2>{{ $totalPesanan }}</h2>
            <p>🧾 Pesanan Lunas</p>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header light">🏪 Profil Vendor</div>
    <div class="card-body">
        <p class="mb-1"><strong>Nama Vendor:</strong> {{ $vendor->nama_vendor }}</p>
        <p class="mb-0"><strong>Akun:</strong> {{ Auth::user()->email }}</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <a href="{{ route('vendor.buku') }}" class="btn btn-primary btn-block py-3" style="border-radius:16px;">
            <i class="mdi mdi-bookshelf mdi-18px d-block mb-1"></i> Kelola Buku Saya
        </a>
    </div>
    <div class="col-md-6 mb-3">
        <a href="{{ route('vendor.pesanan') }}" class="btn btn-block py-3" style="background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;border-radius:16px;">
            <i class="mdi mdi-receipt-outline mdi-18px d-block mb-1"></i> Lihat Pesanan Lunas
        </a>
    </div>
</div>
@endsection
