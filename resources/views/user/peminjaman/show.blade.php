@extends('layouts.user')

@section('title', 'Detail Peminjaman & QR Code')

@section('style_page')
<style>
    @media print {
        .no-print { display: none !important; }
        body { background: #fff; }
        .card { box-shadow: none !important; }
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius: 16px;">
                <i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="border: none; background: transparent; float: right; font-size: 1.25rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card shadow border-0" style="border-radius: 16px; overflow: hidden; background-color: #ffffff;">
            <div class="card-header card-header-gradient text-center py-4 border-0">
                <i class="mdi mdi-book-reader" style="font-size: 2.5rem; display: block;"></i>
                <h4 class="font-weight-bold mb-1 mt-2">Kartu Transaksi Peminjaman</h4>
                <p class="text-white small mb-0 opacity-8">Tunjukkan QR Code ini kepada petugas perpustakaan</p>
            </div>

            <div class="card-body p-4 text-center">
                <div class="d-inline-block p-3 bg-white rounded shadow-sm mb-4 border" style="border-radius: 16px;">
                    {!! QrCode::size(220)->margin(1)->generate('PEMINJAMAN-ID-' . $peminjaman->idpeminjaman) !!}
                </div>
                <div class="mb-4">
                    <span class="badge bg-light text-dark font-weight-normal border px-3 py-2 btn-pill" style="font-size: 0.9rem;">
                        <i class="mdi mdi-barcode me-1 text-primary"></i> ID: PEMINJAMAN-ID-{{ $peminjaman->idpeminjaman }}
                    </span>
                </div>

                <div class="text-start bg-light p-3 rounded" style="border-radius: 16px; background-color: #f8f9fa;">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted font-weight-bold" width="140">Buku</td>
                                <td class="text-dark font-weight-bold text-end">
                                    {{ $peminjaman->buku->judul ?? $peminjaman->kode_buku }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Kode Buku</td>
                                <td class="text-secondary text-end small">{{ $peminjaman->kode_buku }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Peminjam</td>
                                <td class="text-dark text-end">{{ $peminjaman->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Pinjam</td>
                                <td class="text-dark text-end">{{ $peminjaman->tgl_pinjam->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jatuh Tempo</td>
                                <td class="text-danger font-weight-bold text-end">{{ $peminjaman->tgl_kembali_rencana->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status</td>
                                <td class="text-end">
                                    @if($peminjaman->status == 0)
                                        @if($peminjaman->tgl_kembali_rencana < today())
                                            <span class="badge bg-danger text-white">Terlambat!</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Dipinjam / Aktif</span>
                                        @endif
                                    @elseif($peminjaman->status == 1)
                                        <span class="badge bg-success text-white">Dikembalikan</span>
                                    @else
                                        <span class="badge bg-danger text-white">Terlambat</span>
                                    @endif
                                </td>
                            </tr>
                            @if($peminjaman->denda > 0)
                            <tr>
                                <td class="text-danger font-weight-bold">Denda</td>
                                <td class="text-danger font-weight-bold text-end">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="row g-2 mt-4 no-print">
                    <div class="col-4">
                        <button onclick="window.print()" class="btn btn-info w-100 py-2.5 font-weight-bold btn-pill">
                            <i class="mdi mdi-printer me-1"></i> Cetak Struk
                        </button>
                    </div>
                    <div class="col-4">
                        <a href="{{ route('user.riwayat_pinjam') }}" class="btn btn-outline-primary w-100 py-2.5 font-weight-bold btn-pill">
                            <i class="mdi mdi-history me-1"></i> Riwayat Saya
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-primary w-100 py-2.5 font-weight-bold text-white border-0 btn-pill shadow-lp">
                            <i class="mdi mdi-home me-1"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_page')
<script>
    // Cetak struk dilakukan langsung di browser dengan window.print().
</script>
@endsection
