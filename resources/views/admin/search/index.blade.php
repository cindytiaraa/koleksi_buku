@extends('layouts.admin')

@section('title', 'Global Search - Admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="mdi mdi-magnify"></i> Global Search
                </h4>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <form method="GET" action="{{ route('admin.search.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text"
                                       name="q"
                                       class="form-control"
                                       placeholder="Cari buku, user, kategori, vendor..."
                                       value="{{ $query }}"
                                       required>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="mdi mdi-magnify"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if($query)
                                <a href="{{ route('admin.search.index') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-refresh"></i> Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                @if($query)
                    <div class="mb-3">
                        <h5>Hasil pencarian untuk: "<strong>{{ $query }}</strong>"</h5>
                        <p class="text-muted">Ditemukan {{ $total }} hasil</p>
                    </div>

                    @if($total > 0)
                        <!-- Users Results -->
                        @if($results['users']->count() > 0)
                            <div class="mb-4">
                                <h6 class="text-primary">
                                    <i class="mdi mdi-account-multiple"></i> Users ({{ $results['users']->count() }})
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($results['users'] as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <span class="badge badge-primary">{{ $user->role }}</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info">
                                                            <i class="mdi mdi-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Buku Results -->
                        @if($results['buku']->count() > 0)
                            <div class="mb-4">
                                <h6 class="text-success">
                                    <i class="mdi mdi-book"></i> Buku ({{ $results['buku']->count() }})
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Judul</th>
                                                <th>Penulis</th>
                                                <th>Kategori</th>
                                                <th>Harga</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($results['buku'] as $buku)
                                                <tr>
                                                    <td>{{ $buku->judul }}</td>
                                                    <td>{{ $buku->penulis }}</td>
                                                    <td>
                                                        <span class="badge badge-secondary">{{ $buku->kategori->nama_kategori ?? 'N/A' }}</span>
                                                    </td>
                                                    <td>Rp {{ number_format($buku->harga, 0, ',', '.') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.buku.show', $buku->id) }}" class="btn btn-sm btn-success">
                                                            <i class="mdi mdi-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Kategori Results -->
                        @if($results['kategori']->count() > 0)
                            <div class="mb-4">
                                <h6 class="text-warning">
                                    <i class="mdi mdi-tag"></i> Kategori ({{ $results['kategori']->count() }})
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Kode</th>
                                                <th>Nama Kategori</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($results['kategori'] as $kategori)
                                                <tr>
                                                    <td>{{ $kategori->kode_kategori }}</td>
                                                    <td>{{ $kategori->nama_kategori }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.kategori.show', $kategori->id) }}" class="btn btn-sm btn-warning">
                                                            <i class="mdi mdi-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Vendor Results -->
                        @if($results['vendors']->count() > 0)
                            <div class="mb-4">
                                <h6 class="text-info">
                                    <i class="mdi mdi-storefront"></i> Vendor ({{ $results['vendors']->count() }})
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nama Vendor</th>
                                                <th>Alamat</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($results['vendors'] as $vendor)
                                                <tr>
                                                    <td>{{ $vendor->nama_vendor }}</td>
                                                    <td>{{ $vendor->alamat }}</td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-info">
                                                            <i class="mdi mdi-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-magnify-remove-outline" style="font-size: 4rem; color: #6c757d;"></i>
                            <h5 class="mt-3 text-muted">Tidak ada hasil ditemukan</h5>
                            <p class="text-muted">Coba kata kunci yang berbeda</p>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="mdi mdi-magnify" style="font-size: 4rem; color: #6c757d;"></i>
                        <h5 class="mt-3 text-muted">Pencarian Global</h5>
                        <p class="text-muted">Masukkan kata kunci untuk mencari data di seluruh sistem</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_page')
<script>
$(document).ready(function() {
    // Auto-focus pada input search jika kosong
    if (!$('input[name="q"]').val()) {
        $('input[name="q"]').focus();
    }
});
</script>
@endsection