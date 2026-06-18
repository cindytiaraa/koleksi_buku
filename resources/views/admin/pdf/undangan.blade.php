<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 40px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #6A1B9A;
            padding-bottom: 15px;
            margin-bottom: 40px;
        }

        .header h2 {
            margin: 0;
            color: #6A1B9A;
        }

        .content {
            font-size: 16px;
            line-height: 1.8;
            text-align: justify;
        }

        .footer {
            margin-top: 60px;
            text-align: right;
        }

    </style>
</head>
<body>

<div class="header">
    <h2>FAKULTAS VOKASI</h2>
    <p>Universitas Airlangga</p>
</div>

<div class="content">
    <h3>{{ $judul }}</h3>

    <p>{{ $isi }}</p>

    <p>
        Demikian undangan ini disampaikan. Atas perhatian dan kehadirannya,
        kami ucapkan terima kasih.
    </p>
</div>

<div class="footer">
    <p>Surabaya, {{ date('d F Y') }}</p>
    <p>Hormat Kami,</p>
    <br><br>
    <p><strong>Perpustakaan Koleksi Buku</strong></p>
</div>

</body>
</html>