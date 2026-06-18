@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-account-plus"></i>
        </span>
        Tambah User
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Data User</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ul>
    </nav>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Form Tambah User Baru</h4>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0 pl-3">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name') }}" required placeholder="Nama lengkap">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Email <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email') }}" required placeholder="email@domain.com">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Password <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="password" name="password" id="pwCreate"
                                       class="form-control" minlength="6" required placeholder="Min. 6 karakter">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick="togglePw('pwCreate',this)">
                                        <i class="mdi mdi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Role <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select name="role" class="form-control" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="1" {{ old('role')==1?'selected':'' }}>🛡️ Admin</option>
                                <option value="2" {{ old('role')==2?'selected':'' }}>👷 Petugas</option>
                                <option value="3" {{ old('role')==3?'selected':'' }}>👤 User / Anggota</option>
                                <option value="4" {{ old('role')==4?'selected':'' }}>🏪 Vendor</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">
                            <select name="is_approved" class="form-control">
                                <option value="1" selected>✅ Aktif</option>
                                <option value="0">⏳ Pending</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-right mt-4">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light mr-2">Batal</a>
                        <button type="submit" class="btn btn-gradient-primary px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_page')
<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    btn.innerHTML = isHidden ? '<i class="mdi mdi-eye-off"></i>' : '<i class="mdi mdi-eye"></i>';
}
</script>
@endsection
