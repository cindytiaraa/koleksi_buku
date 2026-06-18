<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Antrian</title>

    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        body{
            background:#f5f7ff;
        }

        .antrian-card{
            max-width:550px;
            margin:auto;
            border:none;
            border-radius:20px;
        }

        .icon-ticket{
            font-size:90px;
            color:#9a55ff;
        }

        .title-gradient{
            background:linear-gradient(90deg,#9a55ff,#da8cff);
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
        }

        .btn-gradient{
            background:linear-gradient(90deg,#9a55ff,#da8cff);
            border:none;
            color:white;
        }

        .btn-gradient:hover{
            color:white;
            opacity:.95;
        }
    </style>
</head>
<body>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">

    <div class="card shadow-lg antrian-card w-100">

        <div class="card-body p-5">

            <div class="text-center mb-4">

                <i class="mdi mdi-ticket-confirmation icon-ticket"></i>

                <h2 class="font-weight-bold mt-3 title-gradient">
                    Ambil Nomor Antrian
                </h2>

                <p class="text-muted">
                    Silakan isi nama Anda untuk mendapatkan nomor antrian.
                </p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('antrian.ambil') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="font-weight-bold">
                        Nama Pengunjung
                    </label>

                    <input
                        type="text"
                        name="nama_pengunjung"
                        class="form-control form-control-lg"
                        placeholder="Masukkan nama Anda"
                        value="{{ old('nama_pengunjung') }}"
                        required>
                </div>

                <button type="submit"
                        class="btn btn-gradient btn-lg btn-block mt-4">

                    <i class="mdi mdi-printer me-2"></i>
                    Ambil Antrian
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('antrian.landing') }}"
                   class="text-muted">

                    <i class="mdi mdi-arrow-left"></i>
                    Kembali ke Beranda
                </a>
            </div>

        </div>

    </div>

</div>

</body>
</html>