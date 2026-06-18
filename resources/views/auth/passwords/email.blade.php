@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-center align-items-center"
     style="min-height: 80vh;">

    <div class="card p-5 shadow"
         style="width: 420px; border-radius: 20px;">

        <div class="text-center mb-4">
            <h3 style="color:#6A1B9A; font-weight:700;">
                Reset Password
            </h3>
            <p class="text-muted small">
                Masukkan email untuk menerima link reset
            </p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email"
                       value="{{ old('email') }}"
                       required autofocus>

                @error('email')
                    <span class="text-danger small">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="text-center mt-4">
                <button type="submit"
                        class="btn btn-gradient-primary px-5 py-2"
                        style="border-radius:60px;">
                    Send Reset Link
                </button>
            </div>

            <div class="d-flex justify-content-center align-items-center mt-3">
                <a href="{{ route('login') }}">
                    Login
                </a>
            </div>

        </form>

    </div>
</div>

@endsection