@extends('layouts.admin')

@section('page_title', 'Daftar Toko')

@section('content')
<div class="card">
    <div class="card-header blue">Daftar Lokasi Toko</div>
    <div class="card-body">
        <a href="{{ route('admin.toko.create') }}" class="btn btn-primary mb-3">Tambah Toko</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Barcode</th>
                        <th>Nama Toko</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Accuracy (m)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tokos as $t)
                    <tr>
                        <td>{{ $t->barcode }}</td>
                        <td>{{ $t->nama_toko }}</td>
                        <td>{{ $t->latitude }}</td>
                        <td>{{ $t->longitude }}</td>
                        <td>{{ $t->accuracy }}</td>
                        <td>
                            <a href="{{ route('admin.toko.print', $t->barcode) }}" class="btn btn-sm btn-secondary" target="_blank">Cetak Barcode</a>
                            <a href="{{ route('admin.stok.show', $t->barcode) }}" class="btn btn-sm btn-outline-primary">Stok</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data toko.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
