<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Hasil Kunjungan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{padding:20px}</style>
</head>
<body>
    <div class="container">
        <h4>Hasil Kunjungan</h4>
        <table class="table">
            <tr><th>Barcode Toko</th><td>{{ $toko->barcode }}</td></tr>
            <tr><th>Nama Toko</th><td>{{ $toko->nama_toko }}</td></tr>
            <tr><th>Lokasi Toko</th><td>{{ $toko->latitude }}, {{ $toko->longitude }}</td></tr>
            <tr><th>Accuracy Toko (m)</th><td>{{ $accuracy_toko }}</td></tr>
            <tr><th>Lokasi Sales</th><td>{{ $kunjungan->latitude_sales }}, {{ $kunjungan->longitude_sales }}</td></tr>
            <tr><th>Accuracy Sales (m)</th><td>{{ $accuracy_sales }}</td></tr>
            <tr><th>Jarak (meter)</th><td>{{ number_format($jarak,2) }}</td></tr>
            <tr><th>Threshold Efektif (m)</th><td>{{ number_format($threshold,2) }}</td></tr>
            <tr><th>Status</th><td><strong>{{ $status }}</strong></td></tr>
        </table>
    </div>
</body>
</html>
