@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-tag-multiple"></i>
        </span>
        Cetak Tag Harga Buku
    </h3>
</div>

    <div class="row">

    <!-- INFO LABEL -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    <i class="mdi mdi-information-outline text-info"></i> Informasi Label
                </h4>
                <p>Ukuran label menggunakan format <strong>Tom & Jerry 108</strong></p>
                <ul class="list-unstyled">
                    <li><i class="mdi mdi-check-circle text-success"></i> 5 Kolom</li>
                    <li><i class="mdi mdi-check-circle text-success"></i> 8 Baris</li>
                    <li><i class="mdi mdi-check-circle text-success"></i> 40 Label per halaman</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- FORM CETAK -->
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    <i class="mdi mdi-cog-outline text-primary"></i> Pengaturan Cetak
                </h4>

                <form action="{{ route('admin.tag.cetak') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kolom Mulai (X)</label>
                                <input type="number" class="form-control" name="x" min="1" max="5" value="1" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Baris Mulai (Y)</label>
                                <input type="number" class="form-control" name="y" min="1" max="8" value="1" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jumlah Label</label>
                                <input type="number" class="form-control" name="jumlah" value="10" min="1" max="40">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">
                        <i class="mdi mdi-book-multiple text-primary"></i> Pilih Buku
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th>Kode</th>
                                    <th>Judul</th>
                                    <th>Pengarang</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($buku as $item)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="buku[]" value="{{ $item->idbuku }}" class="form-check-input">
                                        </td>
                                        <td><strong>{{ $item->kode }}</strong></td>
                                        <td>{{ $item->judul }}</td>
                                        <td>{{ $item->pengarang }}</td>
                                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="mdi mdi-book-off-outline" style="font-size: 3rem;"></i>
                                            <br>Tidak ada buku tersedia
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-primary">
                            <i class="mdi mdi-printer"></i> Cetak Label
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_page')
<script>
$(document).ready(function() {
    // Select all functionality
    $('#selectAll').on('change', function() {
        $('input[name="buku[]"]').prop('checked', $(this).prop('checked'));
    });

    // Update select all when individual checkboxes change
    $('input[name="buku[]"]').on('change', function() {
        const totalCheckboxes = $('input[name="buku[]"]').length;
        const checkedCheckboxes = $('input[name="buku[]"]:checked').length;

        $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
        $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
    });
});
</script>
@endsection