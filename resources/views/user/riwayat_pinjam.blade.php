@extends('layouts.user')

@section('title', 'Riwayat Peminjaman')

@section('content')

<h4 class="mb-4"><i class="mdi mdi-history text-primary"></i> Riwayat Peminjaman Saya</h4>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Tgl Kembali</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th>Petugas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $p)
                    <tr class="{{ $p->status==2 ? 'table-danger' : ($p->status==0 && $p->tgl_kembali_rencana < today() ? 'table-warning' : '') }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $p->buku->judul ?? $p->kode_buku }}</strong><br>
                            <small class="text-muted">{{ $p->kode_buku }}</small>
                        </td>
                        <td>{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                        <td>{{ $p->tgl_kembali_rencana->format('d/m/Y') }}</td>
                        <td>{{ $p->tgl_kembali_aktual ? $p->tgl_kembali_aktual->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($p->denda > 0)
                                <span class="text-danger font-weight-bold">
                                    Rp {{ number_format($p->denda, 0, ',', '.') }}
                                </span>
                            @else -
                            @endif
                        </td>
                        <td>
                            @if($p->status == 0)
                                @if($p->tgl_kembali_rencana < today())
                                    <span class="badge badge-danger">Terlambat!</span>
                                @else
                                    <span class="badge badge-warning text-dark">Dipinjam</span>
                                @endif
                            @elseif($p->status == 1)
                                <span class="badge badge-success">Dikembalikan</span>
                            @else
                                <span class="badge badge-danger">Terlambat</span>
                            @endif
                        </td>
                        <td>{{ $p->petugas->name ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('user.peminjaman.show', $p->idpeminjaman) }}" 
                               class="btn btn-sm btn-primary text-white font-weight-bold btn-pill shadow-lp">
                                <i class="mdi mdi-qrcode"></i> Lihat QR
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-0">
                            <div class="empty-state">
                                <i class="mdi mdi-book-search-outline"></i>
                                <p class="title">Belum ada riwayat peminjaman.</p>
                                <p class="desc">Kunjungi perpustakaan untuk meminjam buku.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $peminjaman->links() }}
    </div>
</div>

@endsection
