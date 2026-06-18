@extends('layouts.user')

@section('title', 'Riwayat Pembelian')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">🧾 Riwayat Pembelian Saya</h4>
    <a href="{{ route('user.order') }}" class="btn btn-primary btn-sm">+ Beli Buku</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Waktu</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesanan as $p)
                    <tr>
                        <td><small class="text-muted">{{ $p->midtrans_order_id }}</small></td>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td><strong>Rp {{ number_format($p->total, 0, ',', '.') }}</strong></td>
                        <td>{{ $p->metode_bayar_label }}</td>
                        <td>
                            @if($p->status_bayar == 1)
                                <span class="badge badge-success">Lunas</span>
                            @else
                                <span class="badge badge-warning text-dark">Pending</span>
                                @if($p->snap_token)
                                    <br><small>
                                        <a href="{{ route('user.status_order', $p->midtrans_order_id) }}">
                                            Cek / Bayar
                                        </a>
                                    </small>
                                @endif
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center" style="gap: 5px;">
                                <button class="btn btn-sm btn-info" data-toggle="collapse"
                                    data-target="#detail-{{ $p->idpesanan }}">
                                    <i class="mdi mdi-eye"></i> Detail
                                </button>
                                <a href="{{ route('user.status_order', $p->midtrans_order_id) }}" 
                                   class="btn btn-sm btn-primary text-white font-weight-bold" 
                                   style="border-radius: 4px; box-shadow: 0 2px 4px rgba(145, 118, 251, 0.15);">
                                    <i class="mdi mdi-qrcode"></i> Lihat QR
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="p-0">
                            <div class="collapse" id="detail-{{ $p->idpesanan }}">
                                <table class="table table-sm mb-0 bg-light">
                                    <thead>
                                        <tr>
                                            <th>Kode Buku</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                            <th>Subtotal</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($p->detail as $d)
                                        <tr>
                                            <td>{{ $d->kode_buku }}</td>
                                            <td>{{ $d->jumlah }}</td>
                                            <td>Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                            <td>{{ $d->catatan ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            Belum ada riwayat pembelian.<br>
                            <a href="{{ route('user.order') }}">Mulai beli buku sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $pesanan->links() }}
    </div>
</div>

@endsection
