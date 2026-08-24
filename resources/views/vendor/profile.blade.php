@extends('layouts.vendor')
@section('page_title','Profile')

@section('content')
<div class="row">
    <div class="col-lg-4 mb-3">
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
                     style="width:88px;height:88px;background:linear-gradient(135deg,#6C63FF,#8B5CF6);">
                    <i class="mdi mdi-store text-white" style="font-size:2.5rem;"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                <span class="role-pill">🏪 Vendor</span>
            </div>
        </div>
    </div>
    <div class="col-lg-8 mb-3">
        <div class="card">
            <div class="card-header light">Informasi Akun</div>
            <div class="card-body">
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
                    <div class="col-sm-8"><span class="badge badge-blue">Vendor / Penyedia Buku</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
