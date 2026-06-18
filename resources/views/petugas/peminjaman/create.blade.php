@extends('layouts.petugas')

@section('page_title', 'Catat Peminjaman Baru')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                📖 Form Peminjaman Buku
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('petugas.peminjaman.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Anggota / User <span class="text-danger">*</span></label>
                        <select name="iduser" class="form-control" required>
                            <option value="">-- Pilih Anggota --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('iduser')==$user->id ? 'selected':'' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kode Buku <span class="text-danger">*</span></label>
                        <select name="kode_buku" class="form-control" required>
                            <option value="">-- Pilih Buku --</option>
                            @foreach($buku as $b)
                                <option value="{{ $b->kode }}" {{ old('kode_buku')==$b->kode ? 'selected':'' }}>
                                    [{{ $b->kode }}] {{ $b->judul }} — {{ $b->pengarang }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hanya buku aktif yang ditampilkan</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Pinjam <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_pinjam" class="form-control"
                                       value="{{ old('tgl_pinjam', date('Y-m-d')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Kembali Rencana <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_kembali_rencana" class="form-control"
                                       value="{{ old('tgl_kembali_rencana', date('Y-m-d', strtotime('+7 days'))) }}"
                                       required>
                                <small class="text-muted">Denda Rp 1.000/hari jika terlambat</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3"
                            placeholder="Opsional...">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary">
                            ← Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Simpan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
