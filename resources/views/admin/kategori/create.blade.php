@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-tag-plus"></i>
        </span>
        Tambah Kategori
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.kategori.index') }}">Kategori</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ul>
    </nav>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Form Tambah Kategori</h4>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif

                <form id="formKategoriCreate" action="{{ route('admin.kategori.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" required placeholder="Masukkan nama kategori">
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Kode Kategori</label>
                        <input type="text" name="kode_kategori" class="form-control" required placeholder="Contoh: NV">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.kategori.index') }}" class="btn btn-light">Batal</a>
                        <button id="btnSimpan" type="button" class="btn btn-primary px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_page')
<script>
    document.getElementById('btnSimpan').addEventListener('click', function () {
        const form = document.getElementById('formKategoriCreate');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status"></span> Menyimpan...`;

        form.submit();
    });
</script>
@endsection