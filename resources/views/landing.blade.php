<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koleksi Buku - Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .landing-container {
            text-align: center;
            color: white;
        }
        .landing-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .landing-subtitle {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        .btn-login {
            background: white;
            color: #667eea;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 50px;
            text-decoration: none;
            margin-top: 2rem;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            color: #764ba2;
        }
        .icon {
            font-size: 5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="landing-container">
        <div class="icon">📚</div>
        <h1 class="landing-title">Koleksi Buku</h1>
        <p class="landing-subtitle">Sistem Manajemen Koleksi Buku Terpadu</p>
        <p class="landing-subtitle" style="font-size: 1rem;">Kelola, pinjam, dan jual buku dengan mudah</p>
        <a href="{{ route('login') }}" class="btn btn-login">Login Sekarang</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
