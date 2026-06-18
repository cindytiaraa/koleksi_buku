@extends('layouts.app')

@section('content')

<div class="container">
    <div class="card p-4" style="max-width: 500px; margin: 50px auto;">
    <div class="card-body">

    <div class="text-center mb-3">
        <h2 style="color:#6A1B9A; font-weight:700;">
            KOLEKSI BUKU
        </h2>
    </div>

        <h4 class="card-title text-center mb-4">Login</h4>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label>Email address</label>
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email"
                       value="{{ old('email') }}"
                       required autofocus>

                @error('email')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label>Password</label>
                <input type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password"
                       required>

                @error('password')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            {{-- Remember --}}
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" name="remember">
                <label class="form-check-label">Remember me</label>
            </div>

            {{-- Button --}}
            <div class="text-center">
                <button type="submit" class="btn btn-gradient-primary btn-block">
                    Login
                </button>
            </div>

            {{-- Forgot --}}
            <div class="text-center mt-3">
                <a href="{{ route('password.request') }}">
                    Forgot Your Password?
                </a>
            </div>

             {{-- Register --}}
            <div class="text-center mt-3">
                <a href="{{ route('register') }}">
                    Don't have an account? Register here
                </a>
            </div>

        </form>
    </div>
    </div>
</div>

@endsection