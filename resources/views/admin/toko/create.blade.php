@extends('layouts.admin')

@section('page_title', 'Tambah Toko')

@section('content')
<div class="card">
    <div class="card-header blue">Tambah Lokasi Toko</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.toko.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Toko</label>
                <input type="text" name="nama_toko" class="form-control" required>
            </div>

        <div class="mb-3">
            <label class="form-label">Latitude</label>
            <input type="text" id="latitude" name="latitude" class="form-control" required readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Longitude</label>
            <input type="text" id="longitude" name="longitude" class="form-control" required readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Accuracy (m)</label>
            <input type="text" id="accuracy" name="accuracy" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <button type="button" class="btn btn-info" id="btn-get-location">Ambil Lokasi</button>
            <button type="submit" class="btn btn-primary">Simpan Toko</button>
        </div>
    </form>
    </div>
</div>
@endsection

@section('js_page')
<script>
// watchPosition logic: target accuracy <= 50m, timeout 20s
document.getElementById('btn-get-location').addEventListener('click', function(){
    const latitude = document.getElementById('latitude');
    const longitude = document.getElementById('longitude');
    const accuracy = document.getElementById('accuracy');

    if(!navigator.geolocation){
        alert('Geolocation tidak didukung browser ini');
        return;
    }

    let best = null;
    const timeout = 20000; // 20s
    const start = Date.now();
    const watchId = navigator.geolocation.watchPosition(function(pos){
        if(!pos.coords) return;
        if(best === null || pos.coords.accuracy < best.coords.accuracy){
            best = pos;
            latitude.value = pos.coords.latitude;
            longitude.value = pos.coords.longitude;
            accuracy.value = pos.coords.accuracy;
        }

        if(pos.coords.accuracy <= 50){
            navigator.geolocation.clearWatch(watchId);
        }
        if(Date.now() - start > timeout){
            navigator.geolocation.clearWatch(watchId);
        }
    }, function(err){
        alert('Gagal mendapatkan lokasi: '+err.message);
    }, { enableHighAccuracy: true, maximumAge: 0, timeout: timeout });
});
</script>
@endsection
