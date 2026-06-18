@extends(auth()->user()->role == 1 ? 'layouts.admin' : 'layouts.petugas')

@section('page_title','Kelola Antrian')

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <i class="mdi mdi-ticket-confirmation text-primary"></i>
        Sistem Antrian
    </h3>

    <div>

        <a href="{{ route('antrian.papan') }}"
           target="_blank"
           class="btn btn-info">

            <i class="mdi mdi-monitor"></i>
            Papan
        </a>

        <button onclick="resetAntrian()"
                class="btn btn-danger">

            <i class="mdi mdi-refresh"></i>
            Reset
        </button>

    </div>
</div>

<div class="row">

    {{-- Sedang Dipanggil --}}
    <div class="col-md-4">

        <div class="card">

            <div class="card-body text-center">

                <h5 class="mb-4">
                    Sedang Dipanggil
                </h5>

                <h1 id="nomorAktif"
                    style="font-size:70px;
                    font-weight:900;
                    color:#9a55ff">

                    {{ $sedangDipanggil ? $sedangDipanggil->kode_antrian : '-' }}

                </h1>

                <h4 id="namaAktif">

                    {{ $sedangDipanggil ? $sedangDipanggil->nama_pengunjung : 'Belum Ada Panggilan' }}

                </h4>

                <hr>

                <button
                    onclick="panggilBerikutnya()"
                    class="btn btn-success btn-lg btn-block">

                    <i class="mdi mdi-bullhorn"></i>
                    Panggil Berikutnya

                </button>

                <div class="mt-3">

                    <button
                        onclick="selesaiAktif()"
                        class="btn btn-primary">

                        Selesai
                    </button>

                    <button
                        onclick="terlambatAktif()"
                        class="btn btn-warning">

                        Terlambat
                    </button>

                </div>

            </div>

        </div>

    </div>

    {{-- Menunggu --}}
    <div class="col-md-4">

        <div class="card">

            <div class="card-body">

                <h5>
                    Menunggu
                    <span class="badge badge-primary"
                          id="jumlahMenunggu">

                        {{ $daftarMenunggu->count() }}

                    </span>
                </h5>

                <hr>

                <div id="listMenunggu">

                    @forelse($daftarMenunggu as $item)

                        <div class="d-flex
                                    justify-content-between
                                    align-items-center
                                    mb-2">

                            <div>

                                <strong>
                                    {{ $item->kode_antrian }}
                                </strong>

                                <br>

                                <small>
                                    {{ $item->nama_pengunjung }}
                                </small>

                            </div>

                            <button
                                onclick="panggilUlang({{ $item->id }})"
                                class="btn btn-sm btn-info">

                                Panggil

                            </button>

                        </div>

                    @empty

                        <p class="text-muted">
                            Tidak ada antrian
                        </p>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

    {{-- Terlambat --}}
    <div class="col-md-4">

        <div class="card">

            <div class="card-body">

                <h5>
                    Terlambat
                    <span class="badge badge-danger"
                          id="jumlahTerlambat">

                        {{ $daftarTerlambat->count() }}

                    </span>
                </h5>

                <hr>

                <div id="listTerlambat">

                    @forelse($daftarTerlambat as $item)

                        <div class="d-flex
                                    justify-content-between
                                    align-items-center
                                    mb-2">

                            <div>

                                <strong>
                                    {{ $item->kode_antrian }}
                                </strong>

                                <br>

                                <small>
                                    {{ $item->nama_pengunjung }}
                                </small>

                            </div>

                            <button
                                onclick="panggilUlang({{ $item->id }})"
                                class="btn btn-sm btn-success">

                                Panggil Ulang

                            </button>

                        </div>

                    @empty

                        <p class="text-muted">
                            Tidak ada antrian terlambat
                        </p>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('js_page')

<script>

const CSRF =
"{{ csrf_token() }}";

let currentId = null;

const evtSource =
setInterval(function(){

    fetch("{{ route('antrian.sse') }}")
    .then(res => res.json())
    .then(data => {

        document.getElementById('jumlahMenunggu')
            .innerHTML = data.menunggu.length;

        document.getElementById('jumlahTerlambat')
            .innerHTML = data.terlambat.length;

    });

},3000);

evtSource.onmessage = function(event){

    const data =
    JSON.parse(event.data);

    document.getElementById('jumlahMenunggu')
        .innerHTML =
        data.menunggu.length;

    document.getElementById('jumlahTerlambat')
        .innerHTML =
        data.terlambat.length;

    if(data.dipanggil){

        currentId =
            data.dipanggil.id;

        document.getElementById('nomorAktif')
            .innerHTML =
            data.dipanggil.kode_antrian;

        document.getElementById('namaAktif')
            .innerHTML =
            data.dipanggil.nama_pengunjung;
    }
};

function post(url){

    return fetch(url,{
        method:'POST',
        headers:{
            'X-CSRF-TOKEN':CSRF,
            'Accept':'application/json'
        }
    }).then(r=>r.json());
}

function panggilBerikutnya(){

    post("{{ route('antrian.panggil') }}")
        .then(()=>location.reload());
}

function selesaiAktif(){

    if(!currentId) return;

    post(`/antrian/${currentId}/skip`)
        .then(()=>location.reload());
}

function terlambatAktif(){

    if(!currentId) return;

    post(`/antrian/${currentId}/terlambat`)
        .then(()=>location.reload());
}

function panggilUlang(id){

    post(`/antrian/${id}/panggil-ulang`)
        .then(()=>location.reload());
}

function resetAntrian(){

    if(!confirm('Reset antrian hari ini?'))
        return;

    post("{{ route('antrian.reset') }}")
        .then(()=>location.reload());
}

</script>

@endsection