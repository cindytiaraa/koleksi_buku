@extends('layouts.user')

@section('title', 'Status Pesanan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card" style="border-radius:16px;overflow:hidden;">
            <div class="card-header text-white"
                 style="background: {{ $pesanan->status_bayar == 1 ? '#10b981' : '#f59e0b' }}">
                <h5 class="mb-0">
                    <i class="mdi {{ $pesanan->status_bayar == 1 ? 'mdi-check-circle' : 'mdi-clock-outline' }}"></i>
                    {{ $pesanan->status_bayar == 1 ? 'Pembayaran Berhasil' : 'Menunggu Pembayaran' }}
                </h5>
            </div>
            <div class="card-body">
                <p><strong>Order ID:</strong> {{ $pesanan->midtrans_order_id }}</p>
                <p><strong>Nama:</strong> {{ $pesanan->nama }}</p>
                <p><strong>Metode:</strong> {{ $pesanan->metode_bayar_label }}</p>
                <p><strong>Status:</strong>
                    @if($pesanan->status_bayar == 1)
                        <span class="badge badge-success">Lunas</span>
                    @else
                        <span class="badge badge-warning text-dark">Pending</span>
                    @endif
                </p>
                <hr>
                
                <!-- Tampilan QR Code Transaksi (Praktikum 2 - Konsep Kantin) -->
                <div class="text-center my-4 p-3 border rounded bg-white shadow-sm" style="max-width: 260px; margin: 20px auto; border-radius: 16px;">
                    <span class="text-muted small d-block mb-2 font-weight-bold">QR CODE PEMBELIAN (TICKET)</span>
                    <div class="d-inline-block p-2 bg-white rounded border">
                        {!! QrCode::size(180)->margin(1)->generate('PESANAN-ID-' . $pesanan->idpesanan) !!}
                    </div>
                    <span class="badge bg-light text-dark border font-weight-normal mt-2 px-2 py-1 btn-pill" style="font-size: 0.75rem;">
                        PESANAN-ID-{{ $pesanan->idpesanan }}
                    </span>
                    <small class="text-muted d-block mt-2" style="font-size: 0.75rem;">Tunjukkan QR Code ini kepada vendor/admin untuk verifikasi pesanan & status bayar.</small>
                </div>

                <hr>
                <h6 class="font-weight-bold text-dark mb-3">Detail Pesanan:</h6>
                <table class="table table-sm">
                    <thead>
                        <tr><th>Buku</th><th>Jumlah</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        @foreach($pesanan->detail as $d)
                        <tr>
                            <td>{{ $d->kode_buku }}</td>
                            <td>{{ $d->jumlah }}</td>
                            <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-right"><strong>Total</strong></td>
                            <td><strong>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tfoot>
                </table>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('user.riwayat_beli') }}" class="btn btn-outline-secondary btn-pill">
                        <i class="mdi mdi-arrow-left"></i> Riwayat Pembelian
                    </a>
                    <a href="{{ route('user.order') }}" class="btn btn-primary btn-pill">
                        Beli Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
