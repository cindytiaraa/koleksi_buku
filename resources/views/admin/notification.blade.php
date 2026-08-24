@extends('layouts.admin')

@section('style_page')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .lp-notif-card{font-family:'Poppins',sans-serif;border:none;border-radius:16px;box-shadow:0 5px 20px rgba(93,98,180,.10);}
    .lp-notif-badge{background:rgba(108,99,255,.12);color:#6C63FF;font-weight:600;border-radius:50px;padding:.3rem .8rem;font-size:.8rem;}
</style>
@endsection

@section('content')
@php
    $notifikasi = [
        ['icon'=>'mdi-account-plus','color'=>'#6C63FF','title'=>'Member Baru Terdaftar','desc'=>'5 member baru mendaftar hari ini.','time'=>'15 menit lalu'],
        ['icon'=>'mdi-alert-circle','color'=>'#f59e0b','title'=>'Stok Buku Menipis','desc'=>'3 judul buku memiliki stok di bawah 5 eksemplar.','time'=>'1 jam lalu'],
        ['icon'=>'mdi-truck-check','color'=>'#8B5CF6','title'=>'Supply Baru Masuk','desc'=>'Vendor mengirimkan 120 unit buku baru.','time'=>'3 jam lalu'],
    ];
@endphp

<div class="card lp-notif-card">
    <div class="card-body p-0">
        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
            <h6 class="fw-bold mb-0">🔔 Notifikasi</h6>
            @if(count($notifikasi))
                <span class="lp-notif-badge">{{ count($notifikasi) }} baru</span>
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
