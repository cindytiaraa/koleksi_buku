@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-book-plus"></i>
        </span>
        Tambah Buku
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.buku.index') }}">Data Buku</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ul>
    </nav>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Form Tambah Buku</h4>

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

                <form id="formBukuCreate" action="{{ route('admin.buku.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label class="form-label">Judul Buku</label>
                        <input type="text" name="judul" class="form-control" required placeholder="Masukkan judul buku">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Pengarang</label>
                        <input type="text" name="pengarang" class="form-control" required placeholder="Nama pengarang">
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Kategori</label>
                        <select name="idkategori" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $item)
                                <option value="{{ $item->idkategori }}">{{ $item->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.buku.index') }}" class="btn btn-light">Batal</a>
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
        const form = document.getElementById('formBukuCreate');

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