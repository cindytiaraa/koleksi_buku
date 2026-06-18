@extends('layouts.admin')

@section('page_title', 'Stok Toko')

@section('content')
<div class="card">
    <div class="card-header blue">Stok Toko</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Barcode Toko</th>
                        <th>Nama Toko</th>
                        <th>Total Buku</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stores as $store)
                    <tr>
                        <td>{{ $store->barcode }}</td>
                        <td>{{ $store->nama_toko }}</td>
                        <td>{{ $store->stok_count }}</td>
                        <td>
                            <a href="{{ route('admin.stok.show', $store->barcode) }}" class="btn btn-sm btn-primary">Lihat Stok</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada toko yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
