<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cetak Label Tom & Jerry 108</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #fff;
            -webkit-font-smoothing: antialiased;
        }
        .page-container {
            width: 210mm;
            height: 297mm;
            padding-top: 15mm;
            padding-left: 10mm;
            overflow: hidden;
            background: white;
            position: relative;
        }
        table {
            border-collapse: collapse;
            table-layout: fixed; 
            width: 190mm;
            border: 0;
            margin: 0;
            padding: 0;
        }
        td {
            width: 38mm;
            height: 18mm;
            padding: 0; 
            vertical-align: middle;
            text-align: center;
            overflow: hidden;
            border: 0;
        }
        .label-content {
            width: 38mm;
            height: 18mm;
            padding: 0.5mm 1mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            line-height: 1.1;
        }
        .barcode-container {
            width: 100%;
            height: 5.5mm;
            margin-bottom: 0.2mm;
        }
        .barcode-container img {
            max-width: 95%;
            height: 100%;
            display: block;
            margin: 0 auto;
        }
        .id-barang {
            font-size: 5pt;
            font-weight: bold;
            color: #000;
        }
        .judul {
            font-size: 5.5pt;
            font-weight: 800;
            max-height: 7.5pt;
            overflow: hidden;
            text-transform: uppercase;
            margin: 0.1mm 0;
        }
        .pengarang {
            font-size: 4.5pt;
            font-style: italic;
            color: #444;
            max-height: 5.5pt;
            overflow: hidden;
        }
        .harga {
            font-size: 6pt;
            font-weight: 900;
            margin-top: 0.2mm;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
@foreach ($pages as $pageIndex => $page)<div class="page-container {{ $pageIndex < count($pages) - 1 ? 'page-break' : '' }}"><table cellspacing="0" cellpadding="0">@for ($row = 0; $row < 8; $row++)<tr>@for ($col = 0; $col < 5; $col++)@php $index = ($row * 5) + $col; $item = $page[$index] ?? null; if ($item && is_array($item)) { $item = (object) $item; } @endphp<td>@if ($item)<div class="label-content"><div class="barcode-container"><img src="data:image/png;base64,{{ $item->barcode }}"></div><div class="id-barang">{{ $item->kode }}</div><div class="judul">{{ $item->judul }}</div><div class="pengarang">{{ $item->pengarang }}</div><div class="harga">Rp {{ number_format($item->harga, 0, ',', '.') }}</div></div>@endif</td>@endfor</tr>@endfor</table></div>@endforeach</body></html>