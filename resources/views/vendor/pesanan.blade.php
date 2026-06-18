@extends('layouts.vendor')
@section('page_title','Pesanan Lunas')

@section('content')
<div class="card">
    <div class="card-header blue">🧾 Pesanan Lunas — {{ $vendor->nama_vendor }}</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr><th>Order ID</th><th>Nama Pembeli</th><th>Waktu</th><th>Total</th><th>Detail</th></tr>
                </thead>
                <tbody>
                    @forelse($pesanan as $p)
                    <tr>
                        <td><small class="text-muted">{{ $p->midtrans_order_id }}</small></td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td><strong>Rp {{ number_format($p->total,0,',','.') }}</strong></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" data-toggle="collapse"
                                data-target="#det-{{ $p->idpesanan }}">
                                Detail
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="p-0">
                            <div class="collapse" id="det-{{ $p->idpesanan }}">
                                <table class="table table-sm mb-0" style="background:var(--blue-50,#eff6ff);">
                                    <thead><tr><th>Kode Buku</th><th>Jumlah</th><th>Harga</th><th>Subtotal</th><th>Catatan</th></tr></thead>
                                    <tbody>
                                        @foreach($p->detail as $d)
                                        <tr>
                                            <td>{{ $d->kode_buku }}</td>
                                            <td>{{ $d->jumlah }}</td>
                                            <td>Rp {{ number_format($d->harga,0,',','.') }}</td>
                                            <td>Rp {{ number_format($d->subtotal,0,',','.') }}</td>
                                            <td>{{ $d->catatan ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pesanan lunas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
