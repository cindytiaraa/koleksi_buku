@extends(auth()->user()->role == 1 ? 'layouts.admin' : 'layouts.petugas')

@section('page_title', 'Kelola Antrian')

@section('style_page')
    <style>
        .dot-live {
            width: 9px; height: 9px;
            background: #2ecc71;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
            animation: blink 1.5s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.2; }
        }
        .nomor-display {
            color: #9a55ff;
            text-shadow: 0 0 15px rgba(154, 85, 255, 0.15);
        }
        .card-outline-primary {
            border-top: 4px solid #9a55ff;
        }
        .toast-wrapper {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        .btn-icon-text i {
            font-size: 1rem;
        }
    </style>
@endsection

@section('content')
    {{-- HEADER SECTION --}}
    @if(auth()->user()->role == 1)
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-ticket-confirmation"></i>
            </span>
            Kelola Antrian Real-Time
        </h3>
        <div class="d-flex gap-2 align-items-center">
            <a href="{{ route('antrian.papan') }}" class="btn btn-gradient-info btn-sm btn-icon-text" target="_blank">
                <i class="mdi mdi-monitor me-1"></i> Papan
            </a>
            <a href="{{ route('antrian.riwayat') }}" class="btn btn-gradient-secondary btn-sm btn-icon-text">
                <i class="mdi mdi-history me-1"></i> Riwayat
            </a>
            <button class="btn btn-gradient-warning text-dark btn-sm btn-icon-text" onclick="resetAntrian()">
                <i class="mdi mdi-refresh me-1"></i> Reset
            </button>
        </div>
    </div>
    @else
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="text-primary font-weight-bold"><span class="dot-live"></span> Kelola Antrian Real-Time</h4>
            <small class="text-muted">{{ \Carbon\Carbon::today()->isoFormat('dddd, D MMMM Y') }}</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('antrian.papan') }}" class="btn btn-outline-primary btn-sm" target="_blank">
                <i class="mdi mdi-monitor me-1"></i> Papan
            </a>
            <a href="{{ route('antrian.riwayat') }}" class="btn btn-outline-secondary btn-sm">
                <i class="mdi mdi-history me-1"></i> Riwayat
            </a>
            <button class="btn btn-warning btn-sm text-dark" onclick="resetAntrian()">
                <i class="mdi mdi-refresh me-1"></i> Reset
            </button>
        </div>
    </div>
    @endif

    {{-- STAT CARDS --}}
    <div class="row mb-4">
        <!-- MENUNGGU -->
        <div class="col-md-3 col-sm-6 stretch-card grid-margin mb-3">
            <div class="card bg-gradient-warning card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Menunggu <i class="mdi mdi-account-multiple mdi-24px float-end"></i></h4>
                    <h2 class="mb-3 font-weight-bold" id="countMenunggu">{{ $daftarMenunggu->count() }}</h2>
                    <h6 class="card-text">Orang menunggu giliran</h6>
                </div>
            </div>
        </div>
        <!-- DIPANGGIL -->
        <div class="col-md-3 col-sm-6 stretch-card grid-margin mb-3">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Sedang Aktif <i class="mdi mdi-megaphone mdi-24px float-end"></i></h4>
                    <h2 class="mb-3 font-weight-bold" id="countDipanggil">{{ $sedangDipanggil ? 1 : 0 }}</h2>
                    <h6 class="card-text">Nomor sedang dipanggil</h6>
                </div>
            </div>
        </div>
        <!-- SELESAI -->
        <div class="col-md-3 col-sm-6 stretch-card grid-margin mb-3">
            <div class="card bg-gradient-primary card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Selesai <i class="mdi mdi-check-circle mdi-24px float-end"></i></h4>
                    <h2 class="mb-3 font-weight-bold" id="countSelesai">{{ $totalSelesai }}</h2>
                    <h6 class="card-text">Antrian selesai dilayani</h6>
                </div>
            </div>
        </div>
        <!-- TERLAMBAT -->
        <div class="col-md-3 col-sm-6 stretch-card grid-margin mb-3">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Terlambat <i class="mdi mdi-clock-alert mdi-24px float-end"></i></h4>
                    <h2 class="mb-3 font-weight-bold" id="countTerlambat">{{ $daftarTerlambat->count() }}</h2>
                    <h6 class="card-text">Antrian terlewat / terlambat</h6>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT GRID --}}
    <div class="row">
        {{-- KOLOM KIRI: Panggilan Aktif --}}
        <div class="col-md-4 grid-margin stretch-card mb-4">
            <div class="card card-outline-primary">
                <div class="card-body text-center py-4">
                    <h4 class="card-title text-start mb-4"><i class="mdi mdi-volume-high me-2 text-primary"></i>Panggilan Aktif</h4>
                    
                    <div class="nomor-display py-3" id="displayNomor" style="font-size: 4.5rem; font-weight: 900; letter-spacing: 2px;">
                        {{ $sedangDipanggil ? $sedangDipanggil->kode_antrian : '—' }}
                    </div>
                    
                    <h3 class="font-weight-bold mt-2 text-dark" id="displayNama">
                        {{ $sedangDipanggil ? $sedangDipanggil->nama_pengunjung : 'Belum ada panggilan' }}
                    </h3>
                    
                    <p class="text-muted small mb-4" id="displayJam">
                        @if($sedangDipanggil)
                            Dipanggil: {{ $sedangDipanggil->dipanggil_pada?->format('H:i:s') }}
                        @endif
                    </p>

                    {{-- Actions for current --}}
                    <div id="actionsDipanggil" class="{{ $sedangDipanggil ? '' : 'd-none' }} mb-4">
                        <div class="d-flex gap-2 justify-content-center">
                            <button class="btn btn-gradient-success btn-sm btn-icon-text text-white" onclick="skipCurrent()">
                                <i class="mdi mdi-check btn-icon-prepend"></i> Selesai
                            </button>
                            <button class="btn btn-gradient-warning btn-sm btn-icon-text text-dark" onclick="terlambatCurrent()">
                                <i class="mdi mdi-clock-alert btn-icon-prepend"></i> Terlambat
                            </button>
                        </div>
                    </div>

                    <button class="btn btn-gradient-danger btn-lg w-100 font-weight-bold py-3 text-white shadow-sm" onclick="panggilBerikutnya()" id="btnPanggil" style="border-radius: 8px;">
                        <i class="mdi mdi-bullhorn me-2"></i> PANGGIL BERIKUTNYA
                    </button>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Tabel Antrian Menunggu & Terlambat --}}
        <div class="col-md-8">
            <!-- DAFTAR MENUNGGU -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0"><i class="mdi mdi-format-list-bulleted text-primary me-2"></i>Antrian Menunggu</h4>
                        <span class="badge badge-gradient-warning text-white" id="badgeMenunggu">{{ $daftarMenunggu->count() }} orang</span>
                    </div>
                    
                    <div class="table-responsive" id="tableMenunggu">
                        @if($daftarMenunggu->count() > 0)
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode</th>
                                    <th>Nama Pengunjung</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($daftarMenunggu as $i => $a)
                                <tr id="row-{{ $a->id }}">
                                    <td>{{ $i + 1 }}</td>
                                    <td><label class="badge badge-gradient-danger font-weight-bold" style="font-size:0.9rem;">{{ $a->kode_antrian }}</label></td>
                                    <td class="font-weight-bold text-dark">{{ $a->nama_pengunjung }}</td>
                                    <td class="text-end">
                                        <button class="btn btn-gradient-info btn-rounded btn-icon text-white" onclick="panggilLangsung({{ $a->id }}, '{{ $a->kode_antrian }}', '{{ $a->nama_pengunjung }}')" title="Panggil langsung">
                                            <i class="mdi mdi-bullhorn"></i>
                                        </button>
                                        <button class="btn btn-gradient-light btn-rounded btn-icon ms-1" onclick="skipAntrian({{ $a->id }})" title="Skip/Selesai">
                                            <i class="mdi mdi-close text-danger"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="text-center py-4 text-muted">
                            <i class="mdi mdi-check-circle-outline display-4 text-muted mb-2"></i>
                            <p class="mb-0">Tidak ada antrian menunggu</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- DAFTAR TERLAMBAT -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0"><i class="mdi mdi-clock-alert text-danger me-2"></i>Antrian Terlambat / Terlewat</h4>
                        <span class="badge badge-gradient-danger text-white" id="badgeTerlambat">{{ $daftarTerlambat->count() }} orang</span>
                    </div>
                    
                    <div class="table-responsive" id="tableTerlambat">
                        @if($daftarTerlambat->count() > 0)
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Pengunjung</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($daftarTerlambat as $a)
                                <tr id="row-terlambat-{{ $a->id }}">
                                    <td><label class="badge badge-gradient-warning text-dark font-weight-bold" style="font-size:0.9rem;">{{ $a->kode_antrian }}</label></td>
                                    <td class="font-weight-bold text-dark">{{ $a->nama_pengunjung }}</td>
                                    <td class="text-end">
                                        <button class="btn btn-gradient-success btn-sm btn-icon-text text-white" onclick="panggilUlang({{ $a->id }}, '{{ $a->kode_antrian }}', '{{ $a->nama_pengunjung }}')">
                                            <i class="mdi mdi-refresh me-1"></i> Panggil Ulang
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="text-center py-4 text-muted">
                            <i class="mdi mdi-check-circle-outline display-4 text-muted mb-2"></i>
                            <p class="mb-0">Tidak ada antrian terlambat</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TOAST NOTIFICATION --}}
    <div class="toast-wrapper">
        <div id="toastMsg" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastText">Berhasil!</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto close text-white" data-bs-dismiss="toast" data-dismiss="toast" aria-label="Close" style="background:none; border:none; font-size:1.2rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
@endsection

@section('js_page')
<script>
    const CSRF = "{{ csrf_token() }}";
    let currentId = null; 

    // ============ SSE ============
    const evtSource = new EventSource("{{ route('antrian.sse') }}");

    evtSource.onmessage = function(e) {
        const data = JSON.parse(e.data);

        // Update stat
        const menungguCount = data.menunggu ? data.menunggu.length : 0;
        const terlambatCount = data.terlambat ? data.terlambat.length : 0;
        document.getElementById('countMenunggu').textContent = menungguCount;
        document.getElementById('countSelesai').textContent = data.total_selesai ?? 0;
        document.getElementById('countTerlambat').textContent = terlambatCount;
        document.getElementById('countDipanggil').textContent = data.dipanggil ? 1 : 0;
        document.getElementById('badgeMenunggu').textContent = menungguCount + ' orang';
        document.getElementById('badgeTerlambat').textContent = terlambatCount + ' orang';

        // Update display panggilan
        if (data.dipanggil) {
            document.getElementById('displayNomor').textContent = data.dipanggil.kode_antrian;
            document.getElementById('displayNama').textContent = data.dipanggil.nama_pengunjung;
            document.getElementById('displayJam').textContent = 'Dipanggil: ' + (data.dipanggil.dipanggil_pada ?? '');
            document.getElementById('actionsDipanggil').classList.remove('d-none');
            currentId = data.dipanggil.id;
        } else {
            document.getElementById('displayNomor').textContent = '—';
            document.getElementById('displayNama').textContent = 'Belum ada panggilan';
            document.getElementById('displayJam').textContent = '';
            document.getElementById('actionsDipanggil').classList.add('d-none');
            currentId = null;
        }

        // Update tabel menunggu
        renderTableMenunggu(data.menunggu ?? []);

        // Update tabel terlambat
        renderTableTerlambat(data.terlambat ?? []);
    };

    // ============ Render Tables ============
    function renderTableMenunggu(list) {
        const wrapper = document.getElementById('tableMenunggu');
        if (list.length === 0) {
            wrapper.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="mdi mdi-check-circle-outline display-4 text-muted mb-2"></i>
                    <p class="mb-0">Tidak ada antrian menunggu</p>
                </div>`;
            return;
        }
        let html = `
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode</th>
                        <th>Nama Pengunjung</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>`;
        list.forEach((a, i) => {
            html += `
                <tr>
                    <td>${i+1}</td>
                    <td><label class="badge badge-gradient-danger font-weight-bold" style="font-size:0.9rem;">${a.kode_antrian}</label></td>
                    <td class="font-weight-bold text-dark">${a.nama_pengunjung}</td>
                    <td class="text-end">
                        <button class="btn btn-gradient-info btn-rounded btn-icon text-white" onclick="panggilLangsung(${a.id},'${a.kode_antrian}','${a.nama_pengunjung}')" title="Panggil langsung">
                            <i class="mdi mdi-bullhorn"></i>
                        </button>
                        <button class="btn btn-gradient-light btn-rounded btn-icon ms-1" onclick="skipAntrian(${a.id})" title="Skip/Selesai">
                            <i class="mdi mdi-close text-danger"></i>
                        </button>
                    </td>
                </tr>`;
        });
        html += '</tbody></table>';
        wrapper.innerHTML = html;
    }

    function renderTableTerlambat(list) {
        const wrapper = document.getElementById('tableTerlambat');
        if (list.length === 0) {
            wrapper.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="mdi mdi-check-circle-outline display-4 text-muted mb-2"></i>
                    <p class="mb-0">Tidak ada antrian terlambat</p>
                </div>`;
            return;
        }
        let html = `
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Pengunjung</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>`;
        list.forEach(a => {
            html += `
                <tr>
                    <td><label class="badge badge-gradient-warning text-dark font-weight-bold" style="font-size:0.9rem;">${a.kode_antrian}</label></td>
                    <td class="font-weight-bold text-dark">${a.nama_pengunjung}</td>
                    <td class="text-end">
                        <button class="btn btn-gradient-success btn-sm btn-icon-text text-white" onclick="panggilUlang(${a.id},'${a.kode_antrian}','${a.nama_pengunjung}')">
                            <i class="mdi mdi-refresh me-1"></i> Panggil Ulang
                        </button>
                    </td>
                </tr>`;
        });
        html += '</tbody></table>';
        wrapper.innerHTML = html;
    }

    // ============ Aksi ============
    function panggilBerikutnya() {
        postAction("{{ route('antrian.panggil') }}", {}, function(data) {
            if (data.success) {
                showToast('🔊 Memanggil ' + data.antrian.kode_antrian + ' - ' + data.antrian.nama_pengunjung, 'success');
            } else {
                showToast(data.message, 'danger');
            }
        });
    }

    function panggilLangsung(id, kode, nama) {
        if (!confirm(`Panggil ${kode} - ${nama}?`)) return;
        postAction(`/antrian/${id}/panggil-ulang`, {}, function(data) {
            if (data.success) showToast('🔊 Memanggil ' + kode, 'success');
        });
    }

    function skipAntrian(id) {
        if (!confirm('Selesaikan antrian ini?')) return;
        postAction(`/antrian/${id}/skip`, {}, function(data) {
            if (data.success) showToast('Antrian diselesaikan.', 'info');
        });
    }

    function skipCurrent() {
        if (!currentId) return;
        skipAntrian(currentId);
    }

    function terlambatCurrent() {
        if (!currentId) return;
        if (!confirm('Tandai sebagai terlambat?')) return;
        postAction(`/antrian/${currentId}/terlambat`, {}, function(data) {
            if (data.success) showToast('⏰ Ditandai terlambat.', 'warning');
        });
    }

    function panggilUlang(id, kode, nama) {
        if (!confirm(`Panggil ulang ${kode} - ${nama}?`)) return;
        postAction(`/antrian/${id}/panggil-ulang`, {}, function(data) {
            if (data.success) showToast('🔄 Memanggil ulang ' + kode, 'success');
        });
    }

    function resetAntrian() {
        if (!confirm('Reset semua antrian hari ini? Data lama tetap tersimpan sebagai riwayat.')) return;
        postAction("{{ route('antrian.reset') }}", {}, function(data) {
            if (data.success) showToast('🔁 Antrian direset.', 'success');
        });
    }

    // ============ Helper ============
    function postAction(url, body, cb) {
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(body),
        })
        .then(r => r.json())
        .then(cb)
        .catch(err => showToast('Error: ' + err.message, 'danger'));
    }

    function showToast(msg, type = 'success') {
        const toast = document.getElementById('toastMsg');
        toast.className = `toast align-items-center text-white bg-${type === 'danger' ? 'danger' : (type === 'warning' ? 'warning' : (type === 'info' ? 'info' : 'success'))} border-0`;
        document.getElementById('toastText').textContent = msg;
        
        if (window.bootstrap && window.bootstrap.Toast) {
            const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
            bsToast.show();
        } else if (window.jQuery && jQuery.fn.toast) {
            $(toast).toast({ delay: 3000 });
            $(toast).toast('show');
        } else {
            // Fallback for custom showing/hiding
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }
    }
</script>
@endsection
