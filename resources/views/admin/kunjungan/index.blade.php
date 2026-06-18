@extends('layouts.admin')

@section('page_title', 'Riwayat Kunjungan Vendor')

@section('content')
<div class="card">
    <div class="card-header blue">Riwayat Kunjungan Vendor</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Toko</th>
                        <th>Vendor</th>
                        <th>Latitude Vendor</th>
                        <th>Longitude Vendor</th>
                        <th>Accuracy Toko</th>
                        <th>Accuracy Vendor</th>
                        <th>Jarak (m)</th>
                        <th>Threshold (m)</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kunjungans as $visit)
                    <tr>
                        <td>{{ $visit->toko->nama_toko ?? $visit->barcode_toko }}</td>
                        <td>{{ $visit->vendor->nama_vendor ?? '-' }}</td>
                        <td>{{ number_format($visit->latitude_vendor, 6) }}</td>
                        <td>{{ number_format($visit->longitude_vendor, 6) }}</td>
                        <td>{{ number_format($visit->toko->accuracy ?? 0, 2) }}</td>
                        <td>{{ number_format($visit->accuracy_vendor ?? 0, 2) }}</td>
                        <td>{{ number_format($visit->jarak ?? 0, 2) }}</td>
                        <td>{{ number_format($visit->threshold_efektif ?? 0, 2) }}</td>
                        <td>
                            <span class="badge {{ $visit->status_kunjungan === 'DITERIMA' ? 'badge-green' : 'badge-red' }}">
                                {{ $visit->status_kunjungan }}
                            </span>
                        </td>
                        <td>{{ $visit->waktu_kunjungan }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">Belum ada catatan kunjungan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
