@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
            </span>
            Dashboard
        </h3>

        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active">
                    Halo, {{ auth()->user()->name }}
                </li>
            </ul>
        </nav>
    </div>


    <div class="row">

        <!-- TOTAL BUKU -->
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">

                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute">

                    <h4 class="font-weight-normal mb-3">
                        Total Buku
                        <i class="mdi mdi-book mdi-24px float-end"></i>
                    </h4>

                    <h2 class="mb-5">{{ $totalBuku }}</h2>

                    <h6 class="card-text">Total koleksi buku</h6>

                </div>
            </div>
        </div>


        <!-- TOTAL KATEGORI -->
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">

                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute">

                    <h4 class="font-weight-normal mb-3">
                        Kategori
                        <i class="mdi mdi-tag mdi-24px float-end"></i>
                    </h4>

                    <h2 class="mb-5">{{ $totalKategori }}</h2>

                    <h6 class="card-text">Kategori buku</h6>

                </div>
            </div>
        </div>


        <!-- BUKU TERSEDIA -->
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">

                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute">

                    <h4 class="font-weight-normal mb-3">
                        Buku Tersedia
                        <i class="mdi mdi-library mdi-24px float-end"></i>
                    </h4>

                    <h2 class="mb-5">{{ $tersedia }}</h2>

                    <h6 class="card-text">Status tersedia</h6>

                </div>
            </div>
        </div>


        <!-- TOTAL USER -->
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-dark card-img-holder text-white">
                <div class="card-body">

                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute">

                    <h4 class="font-weight-normal mb-3">
                        Total User
                        <i class="mdi mdi-account mdi-24px float-end"></i>
                    </h4>

                    <h2 class="mb-5">{{ $totalUser }}</h2>

                    <h6 class="card-text">Pengguna sistem</h6>

                </div>
            </div>
        </div>

    </div>


    <!-- CHART SECTION -->

    <div class="row">

        <div class="col-md-7 grid-margin stretch-card">

            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Statistik Buku per Kategori</h4>

                    <canvas id="kategoriChart"></canvas>

                </div>
            </div>

        </div>


        <div class="col-md-5 grid-margin stretch-card">

            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Distribusi Status Buku</h4>

                    <canvas id="statusChart"></canvas>

                </div>
            </div>

        </div>

    </div>


    <!-- TABLE BUKU TERBARU -->

    <div class="row">

        <div class="col-12 grid-margin">

            <div class="card">

                <div class="card-body">

                    <h4 class="card-title">Buku Terbaru</h4>

                    <div class="table-responsive">

                        <table class="table">

                            <thead>

                                <tr>
                                    <th>Kode</th>
                                    <th>Judul</th>
                                    <th>Pengarang</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($bukuTerbaru as $buku)
                                    <tr>

                                        <td>{{ $buku->kode }}</td>

                                        <td>{{ $buku->judul }}</td>

                                        <td>{{ $buku->pengarang }}</td>

                                        <td>{{ $buku->kategori->nama_kategori ?? '-' }}</td>

                                        <td>

                                            @if ($buku->status == 1)
                                                <span class="badge badge-success">Tersedia</span>
                                            @else
                                                <span class="badge badge-danger">Tidak tersedia</span>
                                            @endif

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection


@section('js_page')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // CHART KATEGORI
            const kategoriChart = new Chart(
                document.getElementById('kategoriChart'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($kategoriLabels) !!},
                        datasets: [{
                            label: 'Jumlah Buku',
                            data: {!! json_encode($kategoriData) !!},
                            backgroundColor: '#4B49AC'
                        }]
                    }
                });

            // CHART STATUS
            const statusChart = new Chart(
                document.getElementById('statusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Tersedia', 'Tidak tersedia'],
                        datasets: [{
                            data: [{{ $tersedia }}, {{ $tidakTersedia }}],
                            backgroundColor: ['#1bcfb4', '#fe7c96']
                        }]
                    }
                });

        });
    </script>
@endsection