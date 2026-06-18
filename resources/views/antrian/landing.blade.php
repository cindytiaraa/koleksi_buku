<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Koleksi Buku & Antrian</title>

    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        body{
            background:#f5f7ff;
        }

        .hover-card{
            transition:.3s ease;
            cursor:pointer;
        }

        .hover-card:hover{
            transform:translateY(-10px);
            box-shadow:0 15px 30px rgba(0,0,0,.15)!important;
        }

        .icon-huge{
            font-size:80px;
            line-height:1;
        }

        .title-gradient{
            background:linear-gradient(90deg,#9a55ff,#da8cff);
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
        }
    </style>
</head>
<body>

<div class="container d-flex flex-column justify-content-center align-items-center min-vh-100 py-5">

    <div class="text-center mb-5">
        <h1 class="display-4 font-weight-bold title-gradient">
            <i class="mdi mdi-book-open-page-variant"></i>
            Sistem Koleksi Buku
        </h1>

        <p class="lead text-muted mt-3">
            Silakan pilih layanan yang ingin Anda akses
        </p>
    </div>

    <div class="row w-100 justify-content-center">

        {{-- Login Buku --}}
        <div class="col-md-4 mb-4 stretch-card">
            <div class="card hover-card border-0 shadow-sm w-100"
                 onclick="window.location.href='{{ route('login') }}'">

                <div class="card-body text-center p-5 d-flex flex-column justify-content-center align-items-center">

                    <i class="mdi mdi-book-multiple icon-huge text-success mb-3"></i>

                    <h3 class="font-weight-bold text-dark">
                        Pinjam & Beli Buku
                    </h3>

                    <p class="text-muted">
                        Login untuk melakukan peminjaman atau pembelian buku.
                    </p>

                    <span class="btn btn-outline-success rounded-pill mt-auto">
                        Masuk Sistem
                    </span>
                </div>
            </div>
        </div>

        {{-- Ambil Antrian --}}
        <div class="col-md-4 mb-4 stretch-card">
            <div class="card hover-card border-0 shadow-sm w-100"
                 onclick="window.location.href='{{ route('antrian.guest') }}'">

                <div class="card-body text-center p-5 d-flex flex-column justify-content-center align-items-center">

                    <i class="mdi mdi-ticket-account icon-huge text-info mb-3"></i>

                    <h3 class="font-weight-bold text-dark">
                        Ambil Antrian
                    </h3>

                    <p class="text-muted">
                        Ambil nomor antrian untuk layanan koleksi buku.
                    </p>

                    <span class="btn btn-outline-info rounded-pill mt-auto">
                        Ambil Nomor
                    </span>
                </div>
            </div>
        </div>

        {{-- Papan Antrian --}}
        <div class="col-md-4 mb-4 stretch-card">
            <div class="card hover-card border-0 shadow-sm w-100"
                 onclick="window.location.href='{{ route('antrian.papan') }}'">

                <div class="card-body text-center p-5 d-flex flex-column justify-content-center align-items-center">

                    <i class="mdi mdi-monitor-dashboard icon-huge text-primary mb-3"></i>

                    <h3 class="font-weight-bold text-dark">
                        Papan Antrian
                    </h3>

                    <p class="text-muted">
                        Lihat nomor antrian yang sedang dipanggil secara realtime.
                    </p>

                    <span class="btn btn-outline-primary rounded-pill mt-auto">
                        Lihat Papan
                    </span>
                </div>
            </div>
        </div>

    </div>

</div>

</body>
</html>