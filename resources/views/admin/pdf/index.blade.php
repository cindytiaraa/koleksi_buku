@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h3 class="page-title"> Cetak Dokumen PDF </h3>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">

            <div class="card">
                <div class="card-body text-center">
                    <h4 class="card-title">Sertifikat</h4>

                    <p class="card-description">
                        Cetak dokumen sertifikat dalam format PDF.
                    </p>

                    <a href="{{ route('admin.pdf.sertifikat') }}" class="btn btn-gradient-primary btn-lg">
                        Cetak Sertifikat
                    </a>

                </div>
            </div>

        </div>


        <div class="col-md-6 grid-margin stretch-card">

            <div class="card">
                <div class="card-body text-center">
                    <h4 class="card-title">Undangan</h4>

                    <p class="card-description">
                        Cetak dokumen undangan dalam format PDF.
                    </p>

                    <a href="{{ route('admin.pdf.undangan') }}" class="btn btn-gradient-success btn-lg">
                        Cetak Undangan
                    </a>

                </div>
            </div>

        </div>

    </div>

@endsection