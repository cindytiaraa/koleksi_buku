<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            background-color: #f8f9fa;
            width: 20%;
            height: 90px;
            border: 1px solid #000;
            padding: 5px;
            vertical-align: middle;
            text-align: center;
            
        }

        .nama {
            font-weight: bold;
        }

        .harga {
            font-size: 12px;
            margin-top: 5px;
        }

        .kode {
            font-size: 9px;
            color: gray;
        }

    </style>
</head>
<body>

<table>

@for($row = 0; $row < 8; $row++)
<tr>
    @for($col = 0; $col < 5; $col++)
        @php
            $index = $row * 5 + $col;
        @endphp

        <td>
            @if($slots[$index])
                <div class="nama">
                    {{ $slots[$index]->buku->judul }}
                </div>

                <div class="harga">
                    Rp {{ number_format($slots[$index]->harga, 0, ',', '.') }}
                </div>

                <div class="kode">
                    {{ $slots[$index]->id_buku }}
                </div>
            @endif
        </td>

    @endfor
</tr>
@endfor

</table>

</body>
</html>