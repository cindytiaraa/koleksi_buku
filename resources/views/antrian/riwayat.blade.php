\@extends('layouts.admin')

@section('page_title', 'Riwayat Antrian')

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <i class="mdi mdi-history text-primary"></i>
        Riwayat Antrian
    </h3>

    <a href="{{ route('antrian.admin') }}" class="btn btn-primary">
        <i class="mdi mdi-arrow-left"></i>
        Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">

        <form method="GET" class="row mb-4">
            <div class="col-md-4">
                <label>Filter Tanggal</label>
                <input
                    type="date"
                    name="tanggal"
                    value="{{ request('tanggal') }}"
                    class="form-control">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="mdi mdi-magnify"></i>
                    Cari
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">

                <thead class="table-light">
                    <tr>
                        <th width="60">No</th>
                        <th>Kode Antrian</th>
                        <th>Nama Pengunjung</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Dipanggil Pada</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($riwayat as $item)

                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            <strong>{{ $item->kode_antrian }}</strong>
                        </td>

                        <td>{{ $item->nama_pengunjung }}</td>

                        <td>

                            @switch($item->status)

                                @case('menunggu')
                                    <span class="badge bg-warning text-dark">
                                        Menunggu
                                    </span>
                                    @break

                                @case('dipanggil')
                                    <span class="badge bg-info">
                                        Dipanggil
                                    </span>
                                    @break

                                @case('selesai')
                                    <span class="badge bg-success">
                                        Selesai
                                    </span>
                                    @break

                                @case('terlambat')
                                    <span class="badge bg-danger">
                                        Terlambat
                                    </span>
                                    @break

                                @default
                                    <span class="badge bg-secondary">
                                        {{ $item->status }}
                                    </span>

                            @endswitch

                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($item->tanggal_antrian)->format('d-m-Y') }}
                        </td>

                        <td>
                            @if($item->dipanggil_pada)
                                {{ \Carbon\Carbon::parse($item->dipanggil_pada)->format('d-m-Y H:i:s') }}
                            @else
                                -
                            @endif
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Tidak ada data riwayat antrian
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>
        </div>

        <div class="mt-3">
            {{ $riwayat->links() }}
        </div>

    </div>
</div>

@endsection