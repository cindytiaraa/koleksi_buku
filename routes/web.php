<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OtpController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Auth::routes();

/*
|=======================
| PUBLIC
|=======================
*/
Route::get('/', function () {
    return view('landing');
});

/*
|=======================
| LOGIN PAGE
|=======================
*/
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

/*
|=======================
| OTP
|=======================
*/
Route::middleware('auth')->group(function () {
    Route::get('/otp', [OtpController::class, 'index'])->name('otp.form');
    Route::post('/otp-verify', [OtpController::class, 'verify'])->name('otp.verify');
});

/*
|=======================
| ADMIN (role = 1)
| Akses: CRUD semua data + semua transaksi
|=======================
*/
Route::middleware(['auth', 'otp', 'isAdmin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard',
            [\App\Http\Controllers\Admin\DashboardController::class, 'index']
        )->name('dashboard');

        // ======= Data User =======
        Route::resource('users',
            \App\Http\Controllers\Admin\DataUserController::class
        );
        Route::patch('users/{id}/toggle-status',
            [\App\Http\Controllers\Admin\DataUserController::class, 'toggleStatus']
        )->name('users.toggle-status');

        // ======= Kategori =======
        Route::resource('kategori',
            \App\Http\Controllers\Admin\KategoriController::class
        );

        // ======= DataTables =======
        Route::get('datatables',
            [\App\Http\Controllers\Admin\BukuController::class, 'datatables']
        )->name('datatables.index');
        Route::get('datatables/manual',
            [\App\Http\Controllers\Admin\BukuController::class, 'manual']
        )->name('datatables_manual');

        // ===== DEMO PAGES =====
        Route::get('demo/table',
            [\App\Http\Controllers\Admin\BukuController::class, 'demoTable']
        )->name('demo.table');
        Route::get('demo/select',
            [\App\Http\Controllers\Admin\BukuController::class, 'demoSelect']
        )->name('demo.select');
        Route::get('demo/wilayah',
            [\App\Http\Controllers\Admin\BukuController::class, 'demoWilayah']
        )->name('demo.wilayah');

        // ======= Buku =======
        Route::get('buku/menu',
            [\App\Http\Controllers\Admin\BukuController::class, 'menu']
        )->name('buku.menu');
        Route::get('buku/select',
            [\App\Http\Controllers\Admin\BukuController::class, 'selectView']
        )->name('buku.select');
        Route::resource('buku',
            \App\Http\Controllers\Admin\BukuController::class
        );
        Route::get('buku/{id}/cetak-qr',
            [\App\Http\Controllers\Admin\BukuController::class, 'cetakQr']
        )->name('buku.cetak-qr');
        Route::patch('buku/{id}/toggle',
            [\App\Http\Controllers\Admin\BukuController::class, 'toggleStatus']
        )->name('buku.toggle');

        // ======= Tag Harga =======
        Route::get('tag-harga',
            [\App\Http\Controllers\Admin\TagController::class, 'index']
        )->name('tag.index');
        Route::post('tag/cetak',
            [\App\Http\Controllers\Admin\TagController::class, 'cetak']
        )->name('tag.cetak');

        // ======= PDF =======
        Route::get('pdf',
            [\App\Http\Controllers\Admin\PdfController::class, 'index']
        )->name('pdf.index');
        Route::get('pdf/sertifikat',
            [\App\Http\Controllers\Admin\PdfController::class, 'sertifikat']
        )->name('pdf.sertifikat');
        Route::get('pdf/undangan',
            [\App\Http\Controllers\Admin\PdfController::class, 'undangan']
        )->name('pdf.undangan');

        // ======= POS (Admin) =======
        Route::get('pos',
            [\App\Http\Controllers\Admin\PosController::class, 'index']
        )->name('pos.index');
        Route::get('pos/cari-buku',
            [\App\Http\Controllers\Admin\PosController::class, 'cariBuku']
        )->name('pos.cariBuku');
        Route::post('pos/bayar',
            [\App\Http\Controllers\Admin\PosController::class, 'bayar']
        )->name('pos.bayar');

        // ======= Wilayah =======
        Route::get('wilayah',
            [\App\Http\Controllers\Admin\WilayahController::class, 'index']
        )->name('wilayah.index');


        // ======= Customer =======
        Route::get('customer',
            [\App\Http\Controllers\CustomerController::class, 'index']
        )->name('customer.index');
        Route::get('customer/create1',
            [\App\Http\Controllers\CustomerController::class, 'create1']
        )->name('customer.create1');
        Route::get('customer/create2',
            [\App\Http\Controllers\CustomerController::class, 'create2']
        )->name('customer.create2');
        Route::post('customer/store',
            [\App\Http\Controllers\CustomerController::class, 'store']
        )->name('customer.store');
        Route::delete('customer/{id}',
            [\App\Http\Controllers\CustomerController::class, 'destroy']
        )->name('customer.destroy');

        // ======= Order / Transaksi Online =======
        Route::get('order',
            [\App\Http\Controllers\Admin\OrderController::class, 'index']
        )->name('order.index');
        Route::get('order/status',
            [\App\Http\Controllers\Admin\OrderController::class, 'status']
        )->name('order.status');
        Route::get('order/cari-buku',
            [\App\Http\Controllers\Admin\OrderController::class, 'cariBuku']
        )->name('order.cariBuku');
        Route::post('order/checkout',
            [\App\Http\Controllers\Admin\OrderController::class, 'checkout']
        )->name('order.checkout');

        // ======= Global Search =======
        Route::get('search',
            [\App\Http\Controllers\Admin\SearchController::class, 'index']
        )->name('search.index');
        Route::get('search/results',
            [\App\Http\Controllers\Admin\SearchController::class, 'results']
        )->name('search.results');

        // ======= Scanner =======
        Route::get('scanner',
            [\App\Http\Controllers\Admin\ScannerController::class, 'index']
        )->name('scanner.index');
        Route::get('scanner/cek/{kode}',
            [\App\Http\Controllers\Admin\ScannerController::class, 'cekData']
        )->name('scanner.cek');
        
        // ======= QR Code Scanner  =======
        Route::get('scanner-qr',
            [\App\Http\Controllers\Admin\ScannerController::class, 'indexQr']
        )->name('scanner.qr');
        Route::get('scanner-qr/cek/{id}',
            [\App\Http\Controllers\Admin\ScannerController::class, 'cekQr']
        )->name('scanner.cek_qr');
        Route::post('scanner-qr/bayar/{id}',
            [\App\Http\Controllers\Admin\ScannerController::class, 'prosesBayarQr']
        )->name('scanner.proses_bayar_qr');
        Route::post('scanner-qr/kembalikan/{id}',
            [\App\Http\Controllers\Admin\ScannerController::class, 'prosesKembaliQr']
        )->name('scanner.proses_kembali_qr');

        // ======= Kunjungan Toko =======
        Route::get('toko', [\App\Http\Controllers\Admin\KunjunganTokoController::class, 'indexToko'])->name('toko.index');
        Route::get('toko/create', [\App\Http\Controllers\Admin\KunjunganTokoController::class, 'createToko'])->name('toko.create');
        Route::post('toko/store', [\App\Http\Controllers\Admin\KunjunganTokoController::class, 'storeToko'])->name('toko.store');
        Route::get('toko/print/{barcode}', [\App\Http\Controllers\Admin\KunjunganTokoController::class, 'printBarcode'])->name('toko.print');
        Route::get('toko/api/{barcode}', [\App\Http\Controllers\Admin\KunjunganTokoController::class, 'apiGetToko'])->name('toko.api');

        Route::get('kunjungan', [\App\Http\Controllers\Admin\KunjunganTokoController::class, 'indexKunjungan'])->name('kunjungan.index');
        Route::get('stok', [\App\Http\Controllers\Admin\KunjunganTokoController::class, 'indexStok'])->name('stok.index');
        Route::get('stok/{barcode}', [\App\Http\Controllers\Admin\KunjunganTokoController::class, 'showStok'])->name('stok.show');
    });

/*
|=======================
| PETUGAS (role = 2)
| Akses: Peminjaman + Penjualan tunai
|=======================
*/
Route::middleware(['auth', 'otp', 'isPetugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard',
            [\App\Http\Controllers\Petugas\DashboardController::class, 'index']
        )->name('dashboard');

        // ======= Peminjaman =======
        Route::get('peminjaman',
            [\App\Http\Controllers\Petugas\PeminjamanController::class, 'index']
        )->name('peminjaman.index');

        Route::get('peminjaman/create',
            [\App\Http\Controllers\Petugas\PeminjamanController::class, 'create']
        )->name('peminjaman.create');

        Route::post('peminjaman',
            [\App\Http\Controllers\Petugas\PeminjamanController::class, 'store']
        )->name('peminjaman.store');

        Route::put('peminjaman/{id}/kembalikan',
            [\App\Http\Controllers\Petugas\PeminjamanController::class, 'kembalikan']
        )->name('peminjaman.kembalikan');

        Route::get('peminjaman/cari-buku',
            [\App\Http\Controllers\Petugas\PeminjamanController::class, 'cariBuku']
        )->name('peminjaman.cariBuku');

        // ======= Penjualan (Kasir manual) =======
        Route::get('penjualan/pos',
            [\App\Http\Controllers\Petugas\PenjualanController::class, 'index']
        )->name('penjualan.pos');

        Route::get('penjualan/riwayat',
            [\App\Http\Controllers\Petugas\PenjualanController::class, 'riwayat']
        )->name('penjualan.riwayat');

        Route::get('penjualan/cari-buku',
            [\App\Http\Controllers\Petugas\PenjualanController::class, 'cariBuku']
        )->name('penjualan.cariBuku');

        Route::post('penjualan/bayar',
            [\App\Http\Controllers\Petugas\PenjualanController::class, 'bayar']
        )->name('penjualan.bayar');
    });


/*
|=======================
| USER 
| Akses: Katalog, pembelian online, riwayat pinjam & beli
|=======================
*/
Route::middleware(['auth', 'otp', 'isUser'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        // Dashboard / Katalog
        Route::get('dashboard',
            [\App\Http\Controllers\User\DashboardController::class, 'index']
        )->name('dashboard');

        // Riwayat peminjaman
        Route::get('riwayat-pinjam',
            [\App\Http\Controllers\User\DashboardController::class, 'riwayatPinjam']
        )->name('riwayat_pinjam');

        // Peminjaman / Booking Buku (Praktikum 2)
        Route::get('peminjaman/create/{kode_buku}',
            [\App\Http\Controllers\User\DashboardController::class, 'createPeminjaman']
        )->name('peminjaman.create');
        Route::post('peminjaman/store',
            [\App\Http\Controllers\User\DashboardController::class, 'storePeminjaman']
        )->name('peminjaman.store');
        Route::get('peminjaman/{id}',
            [\App\Http\Controllers\User\DashboardController::class, 'showPeminjaman']
        )->name('peminjaman.show');

        // Riwayat pembelian
        Route::get('riwayat-beli',
            [\App\Http\Controllers\User\DashboardController::class, 'riwayatBeli']
        )->name('riwayat_beli');

        // Order / Beli buku
        Route::get('order',
            [\App\Http\Controllers\User\DashboardController::class, 'order']
        )->name('order');

        Route::post('checkout',
            [\App\Http\Controllers\User\DashboardController::class, 'checkout']
        )->name('checkout');

        Route::get('status/{midtrans_order_id}',
            [\App\Http\Controllers\User\DashboardController::class, 'statusOrder']
        )->name('status_order');

        Route::get('cari-buku',
            [\App\Http\Controllers\User\DashboardController::class, 'cariBuku']
        )->name('cariBuku');
    });


/*
|=======================
| VENDOR 
| Akses: Kelola buku sendiri + lihat pesanan masuk
|=======================
*/
Route::middleware(['auth', 'otp', 'isVendor'])
    ->prefix('vendor')
    ->name('vendor.')
    ->group(function () {

        Route::get('/dashboard',
            [\App\Http\Controllers\VendorController::class, 'dashboard']
        )->name('dashboard');

        Route::get('/buku',
            [\App\Http\Controllers\VendorController::class, 'buku']
        )->name('buku');

        Route::post('/buku/store',
            [\App\Http\Controllers\VendorController::class, 'bukuStore']
        )->name('buku.store');

        Route::delete('/buku/{id}',
            [\App\Http\Controllers\VendorController::class, 'bukuDestroy']
        )->name('buku.destroy');

        Route::get('/pesanan',
            [\App\Http\Controllers\VendorController::class, 'pesanan']
        )->name('pesanan');

        Route::get('/kunjungan',
            [\App\Http\Controllers\Vendor\KunjunganController::class, 'form']
        )->name('kunjungan.form');
        Route::post('/kunjungan/cek',
            [\App\Http\Controllers\Vendor\KunjunganController::class, 'cekToko']
        )->name('kunjungan.cek');
        Route::post('/kunjungan/process',
            [\App\Http\Controllers\Vendor\KunjunganController::class, 'process']
        )->name('kunjungan.process');
        Route::get('/kunjungan/store/{barcode}',
            [\App\Http\Controllers\Vendor\KunjunganController::class, 'showStore']
        )->name('kunjungan.store');
        Route::post('/kunjungan/tambah-stok',
            [\App\Http\Controllers\Vendor\KunjunganController::class, 'tambahStok']
        )->name('kunjungan.tambah_stok');
    });


/*
|=======================
| MIDTRANS CALLBACK (public, no auth)
|=======================
*/
Route::post('/payment/notification',
    [\App\Http\Controllers\Admin\PaymentController::class, 'notification']
)->name('payment.notification');

// Callback dari Midtrans Snap (untuk update status bayar)
Route::post('/order/midtrans/callback',
    [\App\Http\Controllers\Admin\OrderController::class, 'callback']
)->name('order.callback');

// Endpoint baru sesuai permintaan user
Route::post('/midtrans/callback',
    [\App\Http\Controllers\Admin\PaymentController::class, 'notification']
)->name('midtrans.callback');


/*
|=======================
| SISTEM ANTRIAN (SSE)
|=======================
*/
use App\Http\Controllers\AntrianController;

// ---- Publik (tanpa login) ----
Route::get('/antrian', [AntrianController::class, 'landing'])->name('antrian.landing');
Route::get('/guest', [AntrianController::class, 'guest'])->name('antrian.guest');
Route::post('/guest/ambil', [AntrianController::class, 'ambilAntrian'])->name('antrian.ambil');
Route::get('/antrian/tiket/{id}', [AntrianController::class, 'tiket'])->name('antrian.tiket');
Route::get('/papan-antrian', [AntrianController::class, 'papanAntrian'])->name('antrian.papan');

// ---- SSE Endpoint (publik, tanpa login) ----
Route::get('/sse/antrian', [AntrianController::class, 'sseAntrian'])->name('antrian.sse');

// ---- Admin/Petugas (pakai middleware auth + isAdmin atau isPetugas) ----
Route::middleware(['auth', 'otp'])->group(function () {
    Route::get('/admin/antrian', [AntrianController::class, 'adminAntrian'])->name('antrian.admin');
    Route::get('/admin/antrian/riwayat', [AntrianController::class, 'riwayat'])->name('antrian.riwayat');

    // API endpoints
    Route::post('/antrian/panggil-berikutnya', [AntrianController::class, 'panggilBerikutnya'])->name('antrian.panggil');
    Route::post('/antrian/{id}/skip', [AntrianController::class, 'skipAntrian'])->name('antrian.skip');
    Route::post('/antrian/{id}/terlambat', [AntrianController::class, 'tandaiTerlambat'])->name('antrian.terlambat');
    Route::post('/antrian/{id}/panggil-ulang', [AntrianController::class, 'panggilUlang'])->name('antrian.panggil_ulang');
    Route::post('/antrian/reset', [AntrianController::class, 'resetAntrian'])->name('antrian.reset');
});

/*
|=======================
| SISTEM ABSENSI MAHASISWA BERBASIS NFC
|=======================
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/nfc/register', [\App\Http\Controllers\NfcController::class, 'registerForm'])->name('nfc.register.form');
    Route::post('/nfc/register', [\App\Http\Controllers\NfcController::class, 'registerStore'])->name('nfc.register.store');
    Route::get('/nfc/riwayat', [\App\Http\Controllers\NfcController::class, 'riwayatForm'])->name('nfc.riwayat.form');
    Route::get('/nfc/scanner', [\App\Http\Controllers\NfcController::class, 'scannerForm'])->name('nfc.scanner.form');
    Route::post('/api/nfc/scan', [\App\Http\Controllers\NfcController::class, 'scanApi'])->name('api.nfc.scan');
});

/*
|=======================
| ALIAS & WILAYAH PROXY ROUTES
|=======================
*/
Route::middleware(['auth'])->group(function () {
    // Wilayah proxy routes
    Route::get('wilayah/provinsi', [\App\Http\Controllers\Admin\WilayahController::class, 'provinsi'])->name('wilayah.provinsi');
    Route::get('wilayah/kota-by-name/{name}', [\App\Http\Controllers\Admin\WilayahController::class, 'kotaByName']);
});