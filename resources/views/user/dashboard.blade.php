@extends('layouts.user')

@section('title', 'Katalog Buku')

@section('content')

<div class="row mb-3">
    <div class="col-md-8 offset-md-2">
        <form method="GET" class="input-group">
            <input type="text" name="search" class="form-control"
                   placeholder="Cari judul atau pengarang..." value="{{ request('search') }}">
            <select name="kategori" class="form-control" style="max-width:180px">
                <option value="">Semua Kategori</option>
                @foreach($kategori as $k)
                    <option value="{{ $k->idkategori }}" {{ request('kategori')==$k->idkategori ? 'selected':'' }}>
                        {{ $k->nama_kategori }}
                    </option>
                @endforeach
            </select>
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="mdi mdi-book-multiple-outline text-primary"></i> Katalog Buku ({{ $buku->total() }} buku)</h5>
    <a href="{{ route('user.order') }}" class="btn btn-primary btn-pill">
        <i class="mdi mdi-cart"></i> Beli Buku Online
    </a>
</div>

<div class="row">
    @forelse($buku as $b)
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card card-book h-100">
            <div class="card-img-top">
                <i class="mdi mdi-book-open-page-variant-outline"></i>
            </div>
            <div class="card-body">
                <h6 class="card-title mb-1">{{ $b->judul }}</h6>
                <p class="text-muted small mb-1">{{ $b->pengarang }}</p>
                <p class="mb-1">
                    <span class="badge badge-light">{{ $b->kategori->nama_kategori ?? '-' }}</span>
                </p>
                @if($b->harga)
                    <p class="text-primary font-weight-bold mb-3">
                        Rp {{ number_format($b->harga, 0, ',', '.') }}
                    </p>
                @else
                    <p class="text-success font-weight-bold mb-3">Gratis Pinjam</p>
                @endif
                
                <a href="{{ route('user.peminjaman.create', $b->kode) }}" 
                   class="btn btn-sm btn-outline-primary btn-block font-weight-bold btn-pill" 
                   style="font-size: 0.8rem; padding: 6px;">
                    <i class="mdi mdi-bookmark-plus-outline"></i> Booking Pinjam
                </a>
            </div>
            <div class="card-footer bg-transparent">
                <small class="text-muted">Kode: {{ $b->kode }}</small>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state">
            <i class="mdi mdi-book-search-outline"></i>
            <p class="title">Buku tidak ditemukan.</p>
            <p class="desc mb-3">Coba kata kunci atau kategori lain.</p>
            <a href="{{ route('user.dashboard') }}" class="btn btn-outline-primary btn-pill">Lihat Semua</a>
        </div>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center">
    {{ $buku->links() }}
</div>

@endsection
