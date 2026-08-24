@extends('layouts.user')

@section('title', 'Booking Peminjaman Buku')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-header card-header-gradient py-3">
                <h5 class="mb-0 font-weight-bold"><i class="mdi mdi-bookmark-plus me-2"></i> Form Booking Peminjaman</h5>
            </div>
            
            <div class="card-body p-4">
                @if($sedangDipinjam)
                    <div class="alert alert-danger" role="alert">
                        <i class="mdi mdi-alert-circle me-2"></i> 
                        <strong>Maaf!</strong> Buku ini sedang dipinjam oleh anggota lain dan saat ini tidak tersedia.
                    </div>
                @endif

                <!-- Ringkasan Info Buku -->
                <div class="d-flex align-items-start p-3 bg-light rounded mb-4" style="background-color: #f8f9fa; border: 1px solid #eee;">
                    <div class="rounded p-3 me-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; background:linear-gradient(135deg,var(--primary-700),var(--primary-500)); color:#fff;">
                        <i class="mdi mdi-book-open-page-variant-outline"></i>
                    </div>
                    <div>
                        <h5 class="font-weight-bold text-dark mb-1">{{ $buku->judul }}</h5>
                        <p class="text-muted small mb-1">Oleh: {{ $buku->pengarang }}</p>
                        <span class="badge text-white" style="background:var(--primary-600);">{{ $buku->kategori->nama_kategori ?? '-' }}</span>
                        <span class="badge bg-light text-dark border">Kode: {{ $buku->kode }}</span>
                    </div>
                </div>

                <form action="{{ route('user.peminjaman.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kode_buku" value="{{ $buku->kode }}">

                    <div class="form-group mb-3">
                        <label for="tgl_pinjam" class="font-weight-bold text-dark mb-2">Tanggal Pinjam (Hari Ini)</label>
                        <input type="text" class="form-control bg-light" id="tgl_pinjam" value="{{ date('d/m/Y') }}" disabled>
                    </div>

                    <div class="form-group mb-3">
                        <label for="tgl_kembali_rencana" class="font-weight-bold text-dark mb-2">Rencana Tanggal Pengembalian <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_kembali_rencana" id="tgl_kembali_rencana" 
                               class="form-control @error('tgl_kembali_rencana') is-invalid @enderror" 
                               min="{{ date('Y-m-d') }}" 
                               value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                        @error('tgl_kembali_rencana')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <small class="text-muted d-block mt-1">Sesuai aturan perpustakaan, masa peminjaman maksimal adalah 7 hari.</small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="catatan" class="font-weight-bold text-dark mb-2">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan" id="catatan" class="form-control" rows="3" placeholder="Tulis catatan jika ada..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary px-4 py-2 btn-pill">
                            <i class="mdi mdi-arrow-left me-2"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-5 py-2 font-weight-bold text-white border-0 btn-pill shadow-lp" 
                                {{ $sedangDipinjam ? 'disabled' : '' }}>
                            <i class="mdi mdi-check-circle me-2"></i> Konfirmasi Pinjam
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
