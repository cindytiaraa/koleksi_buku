<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karcis Antrian</title>

    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        body{
            background:#f5f7ff;
        }

        .ticket-card{
            max-width:550px;
            margin:auto;
            border:none;
            border-radius:25px;
            overflow:hidden;
        }

        .ticket-header{
            background:linear-gradient(90deg,#9a55ff,#da8cff);
            color:white;
            padding:25px;
        }

        .ticket-number{
            font-size:80px;
            font-weight:900;
            color:#9a55ff;
            letter-spacing:5px;
            line-height:1;
        }

        .divider{
            border-top:2px dashed #ddd;
            margin:25px 0;
        }

        .info-label{
            color:#777;
            font-size:14px;
            text-transform:uppercase;
        }

        .btn-purple{
            background:linear-gradient(90deg,#9a55ff,#da8cff);
            color:white;
            border:none;
        }

        .btn-purple:hover{
            color:white;
            opacity:.95;
        }
    </style>
</head>
<body>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">

    <div class="card shadow-lg ticket-card w-100">

        <div class="ticket-header text-center">

            <h3 class="font-weight-bold mb-0">
                <i class="mdi mdi-ticket-confirmation"></i>
                KARCIS ANTRIAN
            </h3>

        </div>

        <div class="card-body p-5 text-center">

            <div class="ticket-number">
                {{ $antrian->kode_antrian }}
            </div>

            <h4 class="font-weight-bold mt-4">
                {{ $antrian->nama_pengunjung }}
            </h4>

            <div class="divider"></div>

            <div class="row">

                <div class="col-6">
                    <div class="info-label">
                        Tanggal
                    </div>

                    <div class="font-weight-bold">
                        {{ \Carbon\Carbon::parse($antrian->created_at)->format('d-m-Y') }}
                    </div>
                </div>

                <div class="col-6">
                    <div class="info-label">
                        Jam
                    </div>

                    <div class="font-weight-bold">
                        {{ \Carbon\Carbon::parse($antrian->created_at)->format('H:i') }}
                    </div>
                </div>

            </div>

            <div class="alert alert-info mt-4 mb-0">

                <i class="mdi mdi-information-outline"></i>

                Silakan tunggu hingga nomor Anda dipanggil oleh petugas.

            </div>

            <div class="mt-4">

                <a href="{{ route('antrian.papan') }}"
                   target="_blank"
                   class="btn btn-purple btn-lg">

                    <i class="mdi mdi-monitor"></i>
                    Lihat Papan Antrian

                </a>

            </div>

        </div>

    </div>

</div>

<script>
    window.onload = function(){
        window.print();
    }
</script>

</body>
</html>