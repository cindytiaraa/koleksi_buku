@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-book-edit"></i>
        </span>
        Edit Buku
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.buku.index') }}">Data Buku</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ul>
    </nav>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Form Edit Buku</h4>

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

                <form id="formBukuEdit" action="{{ route('admin.buku.update', $data->idbuku) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label class="form-label">Kode Buku</label>
                        <input type="text" class="form-control" value="{{ $data->kode }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" name="judul" class="form-control" value="{{ $data->judul }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Pengarang</label>
                        <input type="text" name="pengarang" class="form-control" value="{{ $data->pengarang }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="idkategori" class="form-control" required>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->idkategori }}" {{ $item->idkategori == $data->idkategori ? 'selected' : '' }}>
                                    {{ $item->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="tersedia" {{ $data->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="tidak tersedia" {{ $data->status == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.buku.index') }}" class="btn btn-light">Batal</a>
                        <button id="btnUpdate" type="button" class="btn btn-success px-4">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_page')
<script>
    document.getElementById('btnUpdate').addEventListener('click', function () {
        const form = document.getElementById('formBukuEdit');

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