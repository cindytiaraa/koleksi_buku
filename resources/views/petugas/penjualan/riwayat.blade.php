@extends('layouts.petugas')

@section('page_title', 'Riwayat Penjualan')

@section('content')

<div class="card mb-3">
    <div class="card-body py-2">
        <form class="form-inline" method="GET">
            <label class="mr-2">Tanggal:</label>
            <input type="date" name="tanggal" class="form-control form-control-sm mr-2"
                   value="{{ request('tanggal', date('Y-m-d')) }}">
            <button type="submit" class="btn btn-primary btn-sm mr-2">Tampilkan</button>
            <a href="{{ route('petugas.penjualan.riwayat') }}" class="btn btn-secondary btn-sm">Semua</a>
            <span class="ml-auto badge badge-success p-2">
                Penjualan Hari Ini: <strong>Rp {{ number_format($totalHari, 0, ',', '.') }}</strong>
            </span>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-success text-white">💰 Riwayat Penjualan Tunai</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Waktu Transaksi</th>
                        <th>Jumlah Item</th>
                        <th>Total</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualan as $p)
                    <tr>
                        <td>{{ $p->id_penjualan }}</td>
                        <td>{{ $p->timestamp }}</td>
                        <td>{{ $p->detail->sum('jumlah') }} item</td>
                        <td><strong>Rp {{ number_format($p->total, 0, ',', '.') }}</strong></td>
                        <td>
                            <button class="btn btn-sm btn-info" data-toggle="collapse"
                                data-target="#detail-{{ $p->id_penjualan }}">
                                Lihat
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="p-0">
                            <div class="collapse" id="detail-{{ $p->id_penjualan }}">
                                <table class="table table-sm mb-0 bg-light">
                                    <thead>
                                        <tr><th>Kode</th><th>Jumlah</th><th>Subtotal</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($p->detail as $d)
                                        <tr>
                                            <td>{{ $d->id_barang }}</td>
                                            <td>{{ $d->jumlah }}</td>
                                            <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Tidak ada data penjualan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $penjualan->links() }}
    </div>
</div>

@endsection
