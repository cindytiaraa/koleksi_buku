@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-book"></i>
        </span>
        Manajemen Buku
    </h3>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Daftar Buku</h4>
                    <a href="{{ route('admin.buku.create') }}" class="btn btn-gradient-primary btn-sm px-4">
                        + Tambah Buku
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-alert-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($data as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $item->kode }}</strong></td>
                                    <td>{{ $item->judul }}</td>
                                    <td>{{ $item->pengarang }}</td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $item->kategori->nama_kategori ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.buku.toggle', $item->idbuku) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            @if ($item->status == 1)
                                                <button class="btn btn-success btn-sm">
                                                    <i class="mdi mdi-check-circle"></i> Tersedia
                                                </button>
                                            @else
                                                <button class="btn btn-danger btn-sm">
                                                    <i class="mdi mdi-close-circle"></i> Tidak Tersedia
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.buku.show', $item->idbuku) }}"
                                           class="btn btn-info btn-sm">
                                            <i class="mdi mdi-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('admin.buku.edit', $item->idbuku) }}"
                                           class="btn btn-warning btn-sm">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.buku.destroy', $item->idbuku) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin hapus buku {{ addslashes($item->judul) }}?')">
                                                <i class="mdi mdi-delete"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="mdi mdi-book-off-outline" style="font-size: 3rem;"></i>
                                        <br>Belum ada data buku.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
