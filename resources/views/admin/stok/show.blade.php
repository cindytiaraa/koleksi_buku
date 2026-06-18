@extends('layouts.admin')

@section('page_title', 'Stok Toko')

@section('content')
<div class="card">
    <div class="card-header blue">Stok Toko: {{ $toko->nama_toko }}</div>
    <div class="card-body">
        <div class="mb-4">
            <p><strong>Barcode:</strong> {{ $toko->barcode }}</p>
            <p><strong>Lokasi:</strong> {{ $toko->latitude }}, {{ $toko->longitude }}</p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Jumlah Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $s)
                    <tr>
                        <td>{{ $s->kode }}</td>
                        <td>{{ $s->judul }}</td>
                        <td>{{ $s->pengarang }}</td>
                        <td>{{ $s->jumlah_stok }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data buku.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <a href="{{ route('admin.stok.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection