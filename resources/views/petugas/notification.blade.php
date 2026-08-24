@extends('layouts.petugas')
@section('page_title','Notification')

@section('content')
@php
    $notifikasi = [
        ['icon'=>'mdi-book-check','color'=>'#6C63FF','title'=>'Peminjaman Baru','desc'=>'Buku "Laskar Pelangi" berhasil dipinjam oleh member.','time'=>'5 menit lalu'],
        ['icon'=>'mdi-book-arrow-left','color'=>'#8B5CF6','title'=>'Pengembalian Buku','desc'=>'Buku "Sapiens" telah dikembalikan tepat waktu.','time'=>'32 menit lalu'],
        ['icon'=>'mdi-clock-alert','color'=>'#f59e0b','title'=>'Antrian Menunggu','desc'=>'3 pengunjung sedang menunggu di antrian layanan.','time'=>'1 jam lalu'],
    ];
@endphp

<div class="card">
    <div class="card-header light d-flex justify-content-between align-items-center">
        <span>🔔 Notifikasi</span>
        @if(count($notifikasi))
            <span class="badge badge-blue">{{ count($notifikasi) }} baru</span>
        @endif
    </div>
    <div class="card-body p-0">
        @forelse ($notifikasi as $n)
            <div class="d-flex align-items-start p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="me-3 d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                     style="width:42px;height:42px;background:{{ $n['color'] }}1A;">
                    <i class="mdi {{ $n['icon'] }}" style="color:{{ $n['color'] }};font-size:1.2rem;"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="fw-600 mb-1">{{ $n['title'] }}</h6>
                    <p class="text-muted small mb-1">{{ $n['desc'] }}</p>
                    <span class="text-muted" style="font-size:.75rem;">{{ $n['time'] }}</span>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="mdi mdi-bell-off-outline" style="font-size:3rem;color:#c7c2ff;"></i>
                <p class="fw-600 mt-3 mb-1">Belum ada notifikasi.</p>
                <p class="text-muted small">Silakan mulai menggunakan fitur ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
