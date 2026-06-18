<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi Berhasil — Koleksi Buku</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #4B49AC 0%, #7978E9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .welcome-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            padding: 50px 40px;
            max-width: 480px;
            width: 100%;
            text-align: center;
        }
        .icon-circle {
            width: 90px; height: 90px;
            background: linear-gradient(135deg, #4B49AC, #7978E9);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px; font-size: 2.5rem;
        }
        .welcome-title { font-size: 1.6rem; font-weight: 700; color: #1e293b; margin-bottom: 8px; }
        .welcome-sub { color: #64748b; font-size: 1rem; margin-bottom: 20px; }
        .role-badge {
            display: inline-block; padding: 8px 24px; border-radius: 30px;
            font-weight: 600; font-size: 0.95rem; margin-bottom: 28px;
        }
        .role-1 { background: #fef3c7; color: #92400e; }
        .role-2 { background: #dbeafe; color: #1e40af; }
        .role-3 { background: #d1fae5; color: #065f46; }
        .role-4 { background: #ede9fe; color: #4c1d95; }
        .info-box {
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 12px; padding: 16px 20px; margin-bottom: 28px; text-align: left;
        }
        .info-box p { margin: 0; font-size: 0.88rem; color: #475569; }
        .info-box p + p { margin-top: 6px; }
        .info-box strong { color: #1e293b; }
        .btn-login {
            background: linear-gradient(135deg, #4B49AC, #7978E9);
            color: #fff; border: none; border-radius: 12px;
            padding: 14px 40px; font-size: 1rem; font-weight: 600;
            width: 100%; transition: opacity .2s;
            text-decoration: none; display: block; cursor: pointer;
        }
        .btn-login:hover { opacity: .88; color: #fff; text-decoration: none; }
        .note-text { font-size: .8rem; color: #94a3b8; margin-top: 16px; }
    </style>
</head>
<body>
<div class="welcome-card">
    <div class="icon-circle">✅</div>

    <h2 class="welcome-title">Selamat Datang, {{ Auth::user()->name }}!</h2>
    <p class="welcome-sub">Akun kamu berhasil dibuat.</p>

    @php
        $role    = Auth::user()->role;
        $roleMap = [
            1 => ['label' => 'Administrator', 'icon' => '🛡️'],
            2 => ['label' => 'Petugas',        'icon' => '👷'],
            3 => ['label' => 'Anggota / User', 'icon' => '👤'],
            4 => ['label' => 'Vendor',          'icon' => '🏪'],
        ];
        $info = $roleMap[$role] ?? ['label' => 'Pengguna', 'icon' => '👤'];
    @endphp

    <div class="role-badge role-{{ $role }}">
        {{ $info['icon'] }} Kamu terdaftar sebagai <strong>{{ $info['label'] }}</strong>
    </div>

    <div class="info-box">
        <p><strong>Nama &nbsp;:</strong> {{ Auth::user()->name }}</p>
        <p><strong>Email &nbsp;:</strong> {{ Auth::user()->email }}</p>
        <p><strong>Role &nbsp;&nbsp;:</strong> {{ $info['icon'] }} {{ $info['label'] }}</p>
        @if(Auth::user()->role == 2 && !Auth::user()->is_approved)
            <p class="mt-2" style="color:#b45309">
                ⚠️ Akun petugas memerlukan persetujuan admin sebelum bisa login.
            </p>
        @endif
    </div>

    {{-- Logout user yang baru registrasi, lalu arahkan ke halaman login --}}
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn-login">🔑 Login Sekarang</button>
    </form>

    <p class="note-text">Kamu akan diarahkan ke halaman login setelah menekan tombol di atas.</p>
</div>
</body>
</html>