@extends('layouts.app')

@section('content')

<div class="card p-4" style="max-width: 500px; margin: 50px auto; border-radius:20px; box-shadow:0 15px 35px rgba(0,0,0,0.05);">
    <div class="card-body">

        {{-- Branding --}}
        <div class="text-center mb-3">
            <h2 style="color:#6A1B9A; font-weight:700;">
                KOLEKSI BUKU
            </h2>
        </div>

        <h4 class="card-title text-center mb-4">Register</h4>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Name --}}
            <div class="form-group">
                <label>Full Name</label>
                <input type="text"
                       class="form-control @error('name') is-invalid @enderror"
                       name="name"
                       value="{{ old('name') }}"
                       required autofocus>

                @error('name')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label>Email address</label>
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email"
                       value="{{ old('email') }}"
                       required>

                @error('email')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            {{-- Role --}}
            <div class="form-group">
                <label>Role</label>
                <select class="form-control @error('role') is-invalid @enderror"
                        name="role"
                        required>
                    <option value="">-- Select Role --</option>
                    <option value="2" @selected(old('role') == 2)>Petugas</option>
                    <option value="3" @selected(old('role') == 3)>User</option>
                    <option value="4" @selected(old('role') == 4)>Vendor</option>
                </select>

                @error('role')
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

            {{-- Confirm Password --}}
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password"
                       class="form-control @error('password_confirmation') is-invalid @enderror"
                       name="password_confirmation"
                       required>

                @error('password_confirmation')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            {{-- Button --}}
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-gradient-primary btn-block">
                    Register
                </button>
            </div>

            {{-- Already have account --}}
            <div class="text-center mt-3">
                <small>
                    Already have an account?
                    <a href="{{ route('login') }}">Login here</a>
                </small>
            </div>

        </form>
    </div>
</div>

@endsection