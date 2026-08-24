@extends('layouts.user')
@section('title', 'Notifikasi')

@section('content')
@php
    $notifikasi = [
        ['icon'=>'mdi-book-clock','color'=>'#6C63FF','title'=>'Jatuh Tempo Pengembalian','desc'=>'Buku "Bumi Manusia" harus dikembalikan dalam 2 hari.','time'=>'1 jam lalu'],
        ['icon'=>'mdi-cash-check','color'=>'#8B5CF6','title'=>'Pembayaran Berhasil','desc'=>'Pesanan #ORD-1042 telah berhasil dibayar.','time'=>'Kemarin'],
    ];
@endphp

<div class="card">
    <div class="card-body p-0">
        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
            <h6 class="fw-bold mb-0">🔔 Notifikasi</h6>
            @if(count($notifikasi))
                <span class="badge-user">{{ count($notifikasi) }} baru</span>
            @endif
        </div>
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
