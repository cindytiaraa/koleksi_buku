@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card p-4" style="max-width: 500px; margin: 50px auto;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Masukkan Kode OTP</h3>
            
            {{-- Display email where OTP was sent --}}
            <div class="alert alert-info mb-4">
                <small>
                    <strong>Kode OTP telah dikirim ke:</strong><br>
                    {{ Auth::user()->email }}
                </small>
            </div>

            {{-- OTP Form --}}
            <form method="POST" action="{{ route('otp.verify') }}">
                @csrf
                <div class="form-group mb-3">
                    <label>Masukkan Kode OTP (6 digit)</label>
                    <input type="text" 
                           name="otp" 
                           maxlength="6" 
                           required 
                           class="form-control form-control-lg text-center"
                           placeholder="000000"
                           autocomplete="off">
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    Verifikasi
                </button>
            </form>

            {{-- Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif

            {{-- If wrong email, logout --}}
            <hr class="my-4">
            <div class="text-center">
                <small>Email salah?</small><br>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link btn-sm">
                        Logout dan coba dengan email lain
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection