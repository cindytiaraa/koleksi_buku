@extends('layouts.petugas')

@section('page_title', 'Daftar Peminjaman')

@section('content')

<div class="card mb-3">
    <div class="card-body py-2">
        <form class="form-inline" method="GET">
            <input type="text" name="search" class="form-control form-control-sm mr-2"
                   placeholder="Cari nama / kode buku..." value="{{ request('search') }}">
            <select name="status" class="form-control form-control-sm mr-2">
                <option value="">Semua Status</option>
                <option value="0" {{ request('status')==='0' ? 'selected':'' }}>Dipinjam</option>
                <option value="1" {{ request('status')==='1' ? 'selected':'' }}>Dikembalikan</option>
                <option value="2" {{ request('status')==='2' ? 'selected':'' }}>Terlambat</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm mr-2">Filter</button>
            <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary btn-sm mr-2">Reset</a>
            <a href="{{ route('petugas.peminjaman.create') }}" class="btn btn-success btn-sm ml-auto">
                + Catat Pinjam Baru
            </a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">📖 Data Peminjaman</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Tgl Kembali</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $p)
                    <tr class="{{ $p->status==2 ? 'table-danger' : ($p->status==0 && $p->tgl_kembali_rencana < today() ? 'table-warning' : '') }}">
                        <td>{{ $loop->iteration + ($peminjaman->currentPage()-1)*$peminjaman->perPage() }}</td>
                        <td>{{ $p->user->name ?? '-' }}</td>
                        <td>
                            <strong>{{ $p->kode_buku }}</strong><br>
                            <small class="text-muted">{{ $p->buku->judul ?? '-' }}</small>
                        </td>
                        <td>{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                        <td>{{ $p->tgl_kembali_rencana->format('d/m/Y') }}</td>
                        <td>{{ $p->tgl_kembali_aktual ? $p->tgl_kembali_aktual->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($p->denda > 0)
                                <span class="text-danger">Rp {{ number_format($p->denda,0,',','.') }}</span>
                            @else -
                            @endif
                        </td>
                        <td>
                            @if($p->status == 0)
                                <span class="badge badge-warning text-dark">Dipinjam</span>
                            @elseif($p->status == 1)
                                <span class="badge badge-success">Dikembalikan</span>
                            @else
                                <span class="badge badge-danger">Terlambat</span>
                            @endif
                        </td>
                        <td>
                            @if($p->status == 0)
                            <button class="btn btn-sm btn-outline-success btn-kembalikan"
                                data-id="{{ $p->idpeminjaman }}"
                                data-nama="{{ $p->user->name ?? '' }}"
                                data-buku="{{ $p->buku->judul ?? $p->kode_buku }}">
                                Kembalikan
                            </button>
                            @else
                                <span class="text-muted small">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">Tidak ada data peminjaman</td>
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

{{-- Modal Kembalikan --}}
<div class="modal fade" id="modalKembalikan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proses Pengembalian</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form method="POST" id="formKembalikan">
                @csrf @method('PUT')
                <div class="modal-body">
                    <p>Anggota: <strong id="modalNama"></strong></p>
                    <p>Buku: <strong id="modalBuku"></strong></p>
                    <div class="form-group">
                        <label>Tanggal Kembali Aktual</label>
                        <input type="date" name="tgl_kembali_aktual" class="form-control"
                               value="{{ date('Y-m-d') }}" required>
                        <small class="text-muted">Denda: Rp 1.000/hari keterlambatan</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Konfirmasi Kembali</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js_page')
<script>
document.querySelectorAll('.btn-kembalikan').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const id   = this.dataset.id;
        const nama = this.dataset.nama;
        const buku = this.dataset.buku;
        document.getElementById('modalNama').textContent = nama;
        document.getElementById('modalBuku').textContent = buku;
        document.getElementById('formKembalikan').action = '/petugas/peminjaman/' + id + '/kembalikan';
        $('#modalKembalikan').modal('show');
    });
});
</script>
@endsection
