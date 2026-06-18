@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-account-group"></i>
        </span>
        Manajemen User
    </h3>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Daftar User</h4>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-gradient-primary btn-sm px-4">
                        + Tambah User
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
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $u)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td>
                                    @php
                                        $roles = [
                                            1=>['Admin',  'badge-warning'],
                                            2=>['Petugas','badge-info'],
                                            3=>['User',   'badge-success'],
                                            4=>['Vendor', 'badge-primary'],
                                        ];
                                        $r = $roles[$u->role] ?? ['?','badge-secondary'];
                                    @endphp
                                    <span class="badge {{ $r[1] }}">{{ $r[0] }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $u->is_approved ? 'badge-success' : 'badge-danger' }} status-badge" id="status-{{ $u->id }}">
                                        {{ $u->is_approved ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    @if(auth()->id() !== $u->id)
                                    <button class="btn btn-outline-primary btn-sm toggle-status"
                                            data-id="{{ $u->id }}"
                                            data-status="{{ $u->is_approved }}"
                                            title="Toggle Status">
                                        <i class="mdi mdi-swap-horizontal"></i>
                                    </button>
                                    @endif
                                    <a href="{{ route('admin.users.edit', $u->id) }}"
                                       class="btn btn-warning btn-sm">Edit</a>
                                    @if(auth()->id() !== $u->id)
                                    <form action="{{ route('admin.users.destroy', $u->id) }}"
                                          method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus user {{ addslashes($u->name) }}?')">
                                            Hapus
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada user.</td>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Toggle status functionality
    $('.toggle-status').on('click', function() {
        const button = $(this);
        const userId = button.data('id');
        const currentStatus = button.data('status');
        const statusBadge = $('#status-' + userId);

        // Disable button during request
        button.prop('disabled', true).addClass('loading');

        // Show loading state
        statusBadge.html('<i class="mdi mdi-loading mdi-spin"></i>');

        // Send AJAX request
        $.ajax({
            url: '{{ route("admin.users.index") }}/' + userId + '/toggle-status',
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update badge
                    statusBadge.removeClass('badge-success badge-danger')
                              .addClass(response.badge_class)
                              .text(response.status_text);

                    // Update button data
                    button.data('status', response.status ? 1 : 0);

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message
                    });

                    // Revert badge
                    statusBadge.removeClass('badge-success badge-danger')
                              .addClass(currentStatus ? 'badge-success' : 'badge-danger')
                              .text(currentStatus ? 'Aktif' : 'Nonaktif');
                }
            },
            error: function(xhr) {
                // Handle error
                let message = 'Terjadi kesalahan saat mengubah status.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: message
                });

                // Revert badge
                statusBadge.removeClass('badge-success badge-danger')
                          .addClass(currentStatus ? 'badge-success' : 'badge-danger')
                          .text(currentStatus ? 'Aktif' : 'Nonaktif');
            },
            complete: function() {
                // Re-enable button
                button.prop('disabled', false).removeClass('loading');
            }
        });
    });
});
</script>
@endsection
