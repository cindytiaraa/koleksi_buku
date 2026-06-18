@extends('layouts.user')

@section('title', 'Riwayat Peminjaman')

@section('content')

<h4 class="mb-4">📖 Riwayat Peminjaman Saya</h4>

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
                               class="btn btn-sm btn-primary text-white font-weight-bold" 
                               style="border-radius: 6px; box-shadow: 0 2px 5px rgba(145, 118, 251, 0.2);">
                                <i class="mdi mdi-qrcode"></i> Lihat QR
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            Belum ada riwayat peminjaman.<br>
                            <small>Kunjungi perpustakaan untuk meminjam buku.</small>
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
