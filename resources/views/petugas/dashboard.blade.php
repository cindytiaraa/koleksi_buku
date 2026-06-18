@extends('layouts.petugas')
@section('page_title','Dashboard')

@section('content')
<div class="row mb-3">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6)">
            <h2>{{ $totalPinjamAktif }}</h2>
            <p>📖 Sedang Dipinjam</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#065f46,#10b981)">
            <h2>{{ $totalKembaliHari }}</h2>
            <p>✅ Kembali Hari Ini</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#92400e,#f59e0b)">
            <h2>{{ $totalPinjamHari }}</h2>
            <p>📋 Pinjam Hari Ini</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1e3a8a,#2563eb)">
            <h2>Rp {{ number_format($totalPenjualanHari/1000,0,',','.')}}K</h2>
            <p>💰 Penjualan Hari Ini</p>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header" style="background:#fffbeb;color:#92400e;border-bottom:1px solid #fef3c7;font-weight:600;">
                ⚠️ Hampir Jatuh Tempo (3 Hari)
            </div>
            <div class="card-body p-0">
                @if($hampirJatuhTempo->isEmpty())
                    <p class="text-muted text-center py-3 mb-0 small">Tidak ada</p>
                @else
                <table class="table mb-0">
                    <thead><tr><th>Anggota</th><th>Buku</th><th>Jatuh Tempo</th></tr></thead>
                    <tbody>
                        @foreach($hampirJatuhTempo as $p)
                        <tr>
                            <td>{{ $p->user->name ?? '-' }}</td>
                            <td>{{ $p->buku->judul ?? $p->kode_buku }}</td>
                            <td><span class="badge badge-yellow">{{ $p->tgl_kembali_rencana->format('d/m/Y') }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header" style="background:#fef2f2;color:#991b1b;border-bottom:1px solid #fee2e2;font-weight:600;">
                🚨 Terlambat Kembali
            </div>
            <div class="card-body p-0">
                @if($terlambat->isEmpty())
                    <p class="text-muted text-center py-3 mb-0 small">Tidak ada</p>
                @else
                <table class="table mb-0">
                    <thead><tr><th>Anggota</th><th>Buku</th><th>Sejak</th></tr></thead>
                    <tbody>
                        @foreach($terlambat as $p)
                        <tr>
                            <td>{{ $p->user->name ?? '-' }}</td>
                            <td>{{ $p->buku->judul ?? $p->kode_buku }}</td>
                            <td><span class="badge badge-red">{{ $p->tgl_kembali_rencana->format('d/m/Y') }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <a href="{{ route('petugas.peminjaman.create') }}" class="btn btn-primary btn-block py-3" style="border-radius:10px;">
            <i class="mdi mdi-plus-circle mdi-18px d-block mb-1"></i> Catat Peminjaman
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-outline-primary btn-block py-3" style="border-radius:10px;">
            <i class="mdi mdi-format-list-bulleted mdi-18px d-block mb-1"></i> Daftar Peminjaman
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{ route('petugas.penjualan.pos') }}" class="btn btn-block py-3" style="background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;border-radius:10px;">
            <i class="mdi mdi-cash-register mdi-18px d-block mb-1"></i> Buka Kasir
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{ route('petugas.penjualan.riwayat') }}" class="btn btn-block py-3" style="background:#f0f5ff;color:#1e40af;border:1px solid #bfdbfe;border-radius:10px;">
            <i class="mdi mdi-history mdi-18px d-block mb-1"></i> Riwayat Penjualan
        </a>
    </div>
</div>
@endsection
