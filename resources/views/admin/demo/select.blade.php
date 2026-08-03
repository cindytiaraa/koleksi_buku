@extends('layouts.admin')

@section('style_page')
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
    .select2-container .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
    .select2-container--default
        .select2-selection--single
        .select2-selection__arrow {
        height: calc(1.5em + 0.75rem + 2px);
    }
    .select2-container--default
        .select2-selection--single
        .select2-selection__rendered {
        line-height: 1.5;
        padding-left: 0;
        color: #495057;
    }
    .buku-terpilih {
        min-height: 38px;
        background: #f8f9fa;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 0.95rem;
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <h3 class="page-title">Chained Select — Kategori & Buku</h3>
</div>

{{-- Data buku dari DB, di-encode ke JS --}}
@php
    $bukuPerKategori = [];
    foreach($kategori as $kat) {
        $bukuPerKategori[$kat->idkategori] = [
            'nama' => $kat->nama_kategori,
            'buku' => $kat->buku->map(fn($b) => [
                'id'    => $b->idbuku,
                'judul' => $b->judul,
                'kode'  => $b->kode,
            ])->values()
        ];
    }
@endphp

{{-- Demo Dynamic Select --}}
<div class="row mb-4">

    <div class="col-12">

        <div class="card">

            <div class="card-header">
                <h4 class="card-title mb-0">
                    Demo Dynamic Select
                </h4>
            </div>

            <div class="card-body">

                <div class="row align-items-end">

                    <div class="col-md-9">

                        <div class="form-group mb-0">

                            <label>
                                Kategori Baru
                            </label>

                            <input
                                type="text"
                                id="kategoriBaru"
                                class="form-control"
                                placeholder="Masukkan kategori baru">

                        </div>

                    </div>

                    <div class="col-md-3">

                        <button
                            type="button"
                            id="btnTambahKategori"
                            class="btn btn-gradient-primary btn-block">

                            Tambah

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="row">

    {{-- Card 1: Select Biasa --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Select</h4>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label>Pilih Kategori</label>
                    <select id="selectKategori1" class="form-control">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->idkategori }}">
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Pilih Buku</label>
                    <select id="selectBuku1" class="form-control" disabled>
                        <option value="">-- Pilih Kategori dulu --</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Buku Terpilih</label>
                    <div id="bukuTerpilih1" class="buku-terpilih text-muted">—</div>
                </div>

            </div>
        </div>
    </div>

    {{-- Card 2: Select2 --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Select 2</h4>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label>Pilih Kategori</label>
                    <select id="selectKategori2" class="form-control" style="width:100%">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->idkategori }}">
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Pilih Buku</label>
                    <select id="selectBuku2" class="form-control" style="width:100%" disabled>
                        <option value="">-- Pilih Kategori dulu --</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Buku Terpilih</label>
                    <div id="bukuTerpilih2" class="buku-terpilih text-muted">—</div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@section('js_page')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Data buku dari Laravel ke JS
    const bukuData = @json($bukuPerKategori);

    // Init Select2
    $('#selectKategori2').select2({ placeholder: '-- Pilih Kategori --', allowClear: true });
    $('#selectBuku2').select2({ placeholder: '-- Pilih Kategori dulu --', allowClear: true });

    // ── Helper: populate select buku ──────────────────────
    function populateBuku(idKategori, selectEl, displayEl) {
        selectEl.innerHTML = '';
        displayEl.textContent = '—';
        displayEl.classList.add('text-muted');

        if (!idKategori) {
            selectEl.innerHTML =
            '<option value="">-- Pilih Kategori dulu --</option>';

            selectEl.disabled = true;
            return;
        }

        if (!bukuData[idKategori]) {
            selectEl.disabled = false;
            selectEl.innerHTML =
                '<option value="">Belum ada buku</option>';
            return;
        }

        const buku = bukuData[idKategori].buku;
        selectEl.disabled = false;
        selectEl.innerHTML = '<option value="">-- Pilih Buku --</option>';

        buku.forEach(function(b) {
            const opt = document.createElement('option');
            opt.value       = b.id;
            opt.textContent = `[${b.kode}] ${b.judul}`;
            selectEl.appendChild(opt);
        });
    }

    // ════════════════════════════════════════════════════
    // CARD 1 — Select Biasa
    // ════════════════════════════════════════════════════

    document.getElementById('selectKategori1').addEventListener('change', function () {
        const selectBuku = document.getElementById('selectBuku1');
        const display    = document.getElementById('bukuTerpilih1');
        populateBuku(this.value, selectBuku, display);
    });

    document.getElementById('selectBuku1').addEventListener('change', function () {
        const display = document.getElementById('bukuTerpilih1');
        const text    = this.options[this.selectedIndex]?.text;
        if (this.value && text) {
            display.textContent = text;
            display.classList.remove('text-muted');
        } else {
            display.textContent = '—';
            display.classList.add('text-muted');
        }
    });

    // ════════════════════════════════════════════════════
    // CARD 2 — Select2
    // ════════════════════════════════════════════════════

    $('#selectKategori2').on('change', function () {
        const selectBuku = document.getElementById('selectBuku2');
        const display    = document.getElementById('bukuTerpilih2');

        populateBuku(this.value, selectBuku, display);

        // Select2 perlu di-destroy dan di-init ulang setelah opsi berubah
            $('#selectBuku2').select2('destroy');
            $('#selectBuku2').select2({
                placeholder: this.value ? '-- Pilih Buku --' : '-- Pilih Kategori dulu --',
                allowClear: true
            });
        });

        $('#selectBuku2').on('change', function () {
            const display = document.getElementById('bukuTerpilih2');
            const text    = this.options[this.selectedIndex]?.text;
            if (this.value && text) {
                display.textContent = text;
                display.classList.remove('text-muted');
            } else {
                display.textContent = '—';
                display.classList.add('text-muted');
            }
        });

        // Tambah kategori (Dummy)
        $('#btnTambahKategori').on('click', function () {

            const nama = $('#kategoriBaru').val().trim();

            if (nama === '') {
                alert('Kategori tidak boleh kosong!');
                return;
            }

            const id = 'dummy_' + Date.now();

            // Tambah ke Select biasa
            $('#selectKategori1').append(
                $('<option>', {
                    value: id,
                    text: nama
                })
            );

            // Tambah ke Select2
            $('#selectKategori2').append(
                $('<option>', {
                    value: id,
                    text: nama
                })
            );

            // Refresh Select2
            $('#selectKategori2').trigger('change.select2');

            // Bersihkan input
            $('#kategoriBaru').val('');
        });

</script>
@endsection