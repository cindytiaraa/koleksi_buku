@extends('layouts.admin')

@section('style_page')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .lp-profile-card{font-family:'Poppins',sans-serif;border:none;border-radius:16px;box-shadow:0 5px 20px rgba(93,98,180,.10);}
    .lp-avatar{width:88px;height:88px;border-radius:50%;background:linear-gradient(135deg,#6C63FF,#8B5CF6);display:flex;align-items:center;justify-content:center;}
    .lp-badge{display:inline-block;padding:.35rem .9rem;border-radius:50px;background:rgba(108,99,255,.12);color:#6C63FF;font-weight:600;font-size:.8rem;}
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card lp-profile-card">
            <div class="card-body text-center py-5">
                <div class="mx-auto mb-3 lp-avatar">
                    <i class="mdi mdi-shield-crown text-white" style="font-size:2.5rem;"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                <span class="lp-badge">👑 Administrator</span>
            </div>
        </div>
    </div>
    <div class="col-lg-8 mb-4">
        <div class="card lp-profile-card">
            <div class="card-body">
                <h6 class="fw-bold mb-4">Informasi Akun</h6>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Nama</div>
                    <div class="col-sm-8 fw-600">{{ Auth::user()->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Email</div>
                    <div class="col-sm-8 fw-600">{{ Auth::user()->email }}</div>
                </div>
                <div class="row">
                    <div class="col-sm-4 text-muted small">Role</div>
                    <div class="col-sm-8"><span class="lp-badge">Administrator</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
