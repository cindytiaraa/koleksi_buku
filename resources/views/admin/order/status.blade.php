<!DOCTYPE html>
<html lang="id">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status Pesanan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background: #f4f6f9; }
        .card { border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .badge-lunas   { background: #1bcfb4; color: white; }
        .badge-pending { background: #f9e264; color: #333; }
        .badge-gagal   { background: #fe7c96; color: white; }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center p-5">
                        @if($pesanan->status_bayar == 1)
                            <h1>✅</h1>
                            <h3 class="text-success">Pembayaran Lunas!</h3>
                        @elseif($pesanan->status_bayar == 2)
                            <h1>❌</h1>
                            <h3 class="text-danger">Pembayaran Gagal</h3>
                        @else
                            <h1>⏳</h1>
                            <h3 class="text-warning">Menunggu Pembayaran</h3>
                        @endif

                        <p class="text-muted mt-2">Order ID: {{ $pesanan->midtrans_order_id }}</p>
                        <p>Halo, <strong>{{ $pesanan->nama }}</strong>!</p>

                        <hr>

                        <table class="table table-sm text-left">
                            <thead>
                                <tr>
                                    <th>Buku</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanan->detail as $d)
                                <tr>
                                    <td>{{ $d->kode_buku }}
                                        @if($d->catatan)
                                            <br><small class="text-muted">{{ $d->catatan }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $d->jumlah }}</td>
                                    <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <h5 class="text-right">
                            Total: <strong>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</strong>
                        </h5>

                        <a href="{{ route('admin.order.index') }}" class="btn btn-primary mt-3">
                            Pesan Lagi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>