@extends('layouts.vendor')
@section('page_title','Kelola Buku')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header blue">➕ Tambah Buku</div>
            <div class="card-body">
                <form id="formTambahBuku">
                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" id="inputJudul" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Pengarang</label>
                        <input type="text" id="inputPengarang" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select id="inputKategori" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->idkategori }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" id="inputHarga" class="form-control" min="0" required>
                    </div>
                    <button id="btnSimpan" type="button" class="btn btn-primary btn-block">Simpan Buku</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header blue">📚 Daftar Buku Saya — {{ $vendor->nama_vendor }}</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0" id="tabelBuku">
                        <thead>
                            <tr><th>Kode</th><th>Judul</th><th>Pengarang</th><th>Kategori</th><th>Harga</th><th>Aksi</th></tr>
                        </thead>
                        <tbody id="tabelBody">
                            @forelse($buku as $b)
                            <tr>
                                <td><span class="badge badge-blue">{{ $b->kode }}</span></td>
                                <td>{{ $b->judul }}</td>
                                <td>{{ $b->pengarang }}</td>
                                <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                                <td>Rp {{ number_format($b->harga ?? 0,0,',','.') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger btn-hapus" data-id="{{ $b->idbuku }}">
                                        <i class="mdi mdi-delete"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr id="emptyRow">
                                <td colspan="6" class="text-center text-muted py-4">Belum ada buku</td>
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

@section('js_page')
<script>
document.getElementById('btnSimpan').addEventListener('click', function() {
    const form = document.getElementById('formTambahBuku');
    if (!form.checkValidity()) { form.reportValidity(); return; }
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
    axios.post("{{ route('vendor.buku.store') }}", {
        _token: "{{ csrf_token() }}",
        judul: document.getElementById('inputJudul').value,
        pengarang: document.getElementById('inputPengarang').value,
        idkategori: document.getElementById('inputKategori').value,
        harga: document.getElementById('inputHarga').value,
    }).then(res => {
        btn.disabled = false; btn.innerHTML = 'Simpan Buku';
        if (res.data.status === 'success') {
            Swal.fire({icon:'success',title:'Berhasil',text:res.data.message,timer:1500,showConfirmButton:false})
                .then(() => location.reload());
        }
    }).catch(() => {
        btn.disabled = false; btn.innerHTML = 'Simpan Buku';
        Swal.fire('Error','Gagal menyimpan buku.','error');
    });
});

document.getElementById('tabelBody').addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-hapus');
    if (!btn) return;
    const id = btn.dataset.id;
    const row = btn.closest('tr');
    Swal.fire({
        title:'Hapus buku ini?', icon:'warning',
        showCancelButton:true, confirmButtonColor:'#ef4444',
        confirmButtonText:'Hapus', cancelButtonText:'Batal',
    }).then(result => {
        if (!result.isConfirmed) return;
        axios.delete("{{ url('vendor/buku') }}/"+id, {data:{_token:"{{ csrf_token() }}"}})
        .then(res => {
            if (res.data.status === 'success') {
                row.remove();
                Swal.fire({icon:'success',title:'Dihapus',timer:1200,showConfirmButton:false});
                if (!document.getElementById('tabelBody').querySelector('tr')) {
                    document.getElementById('tabelBody').innerHTML =
                        '<tr id="emptyRow"><td colspan="6" class="text-center text-muted py-4">Belum ada buku</td></tr>';
                }
            }
        }).catch(() => Swal.fire('Error','Gagal menghapus.','error'));
    });
});
</script>
@endsection
