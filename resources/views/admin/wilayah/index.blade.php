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
    .select2-container { width: 100% !important; }
</style>
@endsection

@section('content')

<div class="page-header">
    <h3 class="page-title">Wilayah Administrasi Indonesia</h3>
</div>

<div class="row">

    {{-- ── Card 1: jQuery AJAX ── --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">jQuery AJAX</h4>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label>Provinsi</label>
                    <select id="provinsi1" class="form-control">
                        <option value="">-- Pilih Provinsi --</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label>Kota / Kabupaten</label>
                    <select id="kota1" class="form-control" disabled>
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label>Kecamatan</label>
                    <select id="kecamatan1" class="form-control" disabled>
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label>Kelurahan</label>
                    <select id="kelurahan1" class="form-control" disabled>
                        <option value="">-- Pilih Kelurahan --</option>
                    </select>
                </div>

                <div id="hasilAjax" class="mt-3 p-2 border rounded text-muted"
                    style="min-height:38px; font-size:0.9rem;">
                    —
                </div>

            </div>
        </div>
    </div>

    {{-- ── Card 2: Axios ── --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Axios</h4>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label>Provinsi</label>
                    <select id="provinsi2" class="form-control" style="width:100%">
                        <option value="">-- Pilih Provinsi --</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label>Kota / Kabupaten</label>
                    <select id="kota2" class="form-control" style="width:100%" disabled>
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label>Kecamatan</label>
                    <select id="kecamatan2" class="form-control" style="width:100%" disabled>
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label>Kelurahan</label>
                    <select id="kelurahan2" class="form-control" style="width:100%" disabled>
                        <option value="">-- Pilih Kelurahan --</option>
                    </select>
                </div>

                <div id="hasilAxios" class="mt-3 p-2 border rounded text-muted"
                    style="min-height:38px; font-size:0.9rem;">
                    —
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@section('js_page')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
// ═══════════════════════════════════════════════════════════════
// HELPER
// ═══════════════════════════════════════════════════════════════

// Reset & disable select mulai dari level tertentu
function resetSelect(selects) {
    selects.forEach(function(sel) {
        sel.innerHTML = '<option value="">' + sel.dataset.placeholder + '</option>';
        sel.disabled = true;
    });
}

// Populate select dari array data wilayah
function populateSelect(sel, data) {
    sel.innerHTML = '<option value="">' + sel.dataset.placeholder + '</option>';
    data.forEach(function(item) {
        const opt = document.createElement('option');
        opt.value       = item.code;
        opt.textContent = item.name;
        sel.appendChild(opt);
    });
    sel.disabled = false;
}

// Update tampilan hasil terpilih
function updateHasil(elId, provinsi, kota, kecamatan, kelurahan) {
    const parts = [provinsi, kota, kecamatan, kelurahan].filter(Boolean);
    const el = document.getElementById(elId);
    if (parts.length) {
        el.textContent = parts.join(' → ');
        el.classList.remove('text-muted');
    } else {
        el.textContent = '—';
        el.classList.add('text-muted');
    }
}

const API = 'https://wilayah.id/api/';

// ═══════════════════════════════════════════════════════════════
// CARD 1 — jQuery AJAX
// ═══════════════════════════════════════════════════════════════

const p1  = document.getElementById('provinsi1');
const k1  = document.getElementById('kota1');
const kc1 = document.getElementById('kecamatan1');
const kel1= document.getElementById('kelurahan1');

p1.dataset.placeholder  = '-- Pilih Provinsi --';
k1.dataset.placeholder  = '-- Pilih Kota --';
kc1.dataset.placeholder = '-- Pilih Kecamatan --';
kel1.dataset.placeholder= '-- Pilih Kelurahan --';

// Load provinsi saat halaman load
$.ajax({
    url: API + 'provinces.json',
    method: 'GET',
    success: function(res) {
        populateSelect(p1, res.data);
    },
    error: function() {
        p1.innerHTML = '<option value="">Gagal memuat data</option>';
    }
});

// Provinsi → Kota
$(p1).on('change', function() {
    resetSelect([k1, kc1, kel1]);
    updateHasil('hasilAjax');
    if (!this.value) return;

    $.ajax({
        url: API + 'regencies/' + this.value + '.json',
        method: 'GET',
        success: function(res) { populateSelect(k1, res.data); },
        error: function() { k1.innerHTML = '<option value="">Gagal memuat</option>'; }
    });
});

// Kota → Kecamatan
$(k1).on('change', function() {
    resetSelect([kc1, kel1]);
    updateHasil('hasilAjax',
        p1.options[p1.selectedIndex]?.text,
        this.options[this.selectedIndex]?.text
    );
    if (!this.value) return;

    $.ajax({
        url: API + 'districts/' + this.value + '.json',
        method: 'GET',
        success: function(res) { populateSelect(kc1, res.data); },
        error: function() { kc1.innerHTML = '<option value="">Gagal memuat</option>'; }
    });
});

// Kecamatan → Kelurahan
$(kc1).on('change', function() {
    resetSelect([kel1]);
    updateHasil('hasilAjax',
        p1.options[p1.selectedIndex]?.text,
        k1.options[k1.selectedIndex]?.text,
        this.options[this.selectedIndex]?.text
    );
    if (!this.value) return;

    $.ajax({
        url: API + 'villages/' + this.value + '.json',
        method: 'GET',
        success: function(res) { populateSelect(kel1, res.data); },
        error: function() { kel1.innerHTML = '<option value="">Gagal memuat</option>'; }
    });
});

// Kelurahan dipilih
$(kel1).on('change', function() {
    updateHasil('hasilAjax',
        p1.options[p1.selectedIndex]?.text,
        k1.options[k1.selectedIndex]?.text,
        kc1.options[kc1.selectedIndex]?.text,
        this.options[this.selectedIndex]?.text
    );
});

// ═══════════════════════════════════════════════════════════════
// CARD 2 — Axios
// ═══════════════════════════════════════════════════════════════

const p2  = document.getElementById('provinsi2');
const k2  = document.getElementById('kota2');
const kc2 = document.getElementById('kecamatan2');
const kel2= document.getElementById('kelurahan2');

p2.dataset.placeholder  = '-- Pilih Provinsi --';
k2.dataset.placeholder  = '-- Pilih Kota --';
kc2.dataset.placeholder = '-- Pilih Kecamatan --';
kel2.dataset.placeholder= '-- Pilih Kelurahan --';

// Load provinsi saat halaman load
axios.get(API + 'provinces.json')
    .then(function(res) { populateSelect(p2, res.data.data); })
    .catch(function()   { p2.innerHTML = '<option value="">Gagal memuat data</option>'; });

// Provinsi → Kota
p2.addEventListener('change', function() {
    resetSelect([k2, kc2, kel2]);
    updateHasil('hasilAxios');
    if (!this.value) return;

    axios.get(API + 'regencies/' + this.value + '.json')
        .then(function(res) { populateSelect(k2, res.data.data); })
        .catch(function()   { k2.innerHTML = '<option value="">Gagal memuat</option>'; });
});

// Kota → Kecamatan
k2.addEventListener('change', function() {
    resetSelect([kc2, kel2]);
    updateHasil('hasilAxios',
        p2.options[p2.selectedIndex]?.text,
        this.options[this.selectedIndex]?.text
    );
    if (!this.value) return;

    axios.get(API + 'districts/' + this.value + '.json')
        .then(function(res) { populateSelect(kc2, res.data.data); })
        .catch(function()   { kc2.innerHTML = '<option value="">Gagal memuat</option>'; });
});

// Kecamatan → Kelurahan
kc2.addEventListener('change', function() {
    resetSelect([kel2]);
    updateHasil('hasilAxios',
        p2.options[p2.selectedIndex]?.text,
        k2.options[k2.selectedIndex]?.text,
        this.options[this.selectedIndex]?.text
    );
    if (!this.value) return;

    axios.get(API + 'villages/' + this.value + '.json')
        .then(function(res) { populateSelect(kel2, res.data.data); })
        .catch(function()   { kel2.innerHTML = '<option value="">Gagal memuat</option>'; });
});

// Kelurahan dipilih
kel2.addEventListener('change', function() {
    updateHasil('hasilAxios',
        p2.options[p2.selectedIndex]?.text,
        k2.options[k2.selectedIndex]?.text,
        kc2.options[kc2.selectedIndex]?.text,
        this.options[this.selectedIndex]?.text
    );
});
</script>
@endsection