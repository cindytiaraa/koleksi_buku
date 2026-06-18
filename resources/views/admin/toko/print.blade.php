<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Cetak Barcode {{ $toko->barcode }}</title>
    <style>
        body { font-family: Arial, sans-serif; text-align:center; padding:40px }
        .barcode { font-family: monospace; font-size: 36px; letter-spacing: 6px; }
        .nama { margin-top: 20px; font-size: 18px }
    </style>
</head>
<body>
    <div>
        <div class="barcode">{{ $toko->barcode }}</div>
        <div class="nama">{{ $toko->nama_toko }}</div>
        <div style="margin-top:20px">Lat: {{ $toko->latitude }} | Lng: {{ $toko->longitude }}</div>
    </div>
    <script>window.print();</script>
</body>
</html>
