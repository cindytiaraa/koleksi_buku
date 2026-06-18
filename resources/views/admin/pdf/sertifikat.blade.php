<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            text-align: center;
        }

        .certificate {
            border: 10px solid #6A1B9A;
            padding: 60px;
            margin: 30px;
        }

        .title {
            font-size: 42px;
            font-weight: bold;
            color: #6A1B9A;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .name {
            font-size: 32px;
            font-weight: bold;
            margin: 20px 0;
            color: #333;
        }

        .event {
            font-size: 20px;
            margin-top: 10px;
        }

        .footer {
            margin-top: 60px;
            font-size: 14px;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

    </style>
</head>
<body>

<div class="certificate">

    <div class="title">SERTIFIKAT</div>
    <div class="subtitle">Diberikan kepada</div>

    <div class="name">{{ $nama }}</div>

    <div class="subtitle">
        Atas partisipasinya dalam kegiatan
    </div>

    <div class="event"><strong>{{ $kegiatan }}</strong></div>

    <div class="footer">
        Diberikan pada tanggal {{ date('d F Y') }}
    </div>

    <div class="signature">
        <p><strong>Perpustakaan Koleksi Buku</strong></p>
    </div>

</div>

</body>
</html>