<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>QR Code {{ $data->kode }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .container {
            margin-top: 30px;
        }
        .qr-code svg {
            width: 150pt; /* Pas dengan ukuran kertas 200pt */
            height: 150pt;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="qr-code">
            {!! $qrcode !!}
        </div>
    </div>

</body>
</html>
