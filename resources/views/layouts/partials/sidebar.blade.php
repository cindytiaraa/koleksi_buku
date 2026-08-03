@php
    use Illuminate\Support\Facades\Request;
@endphp

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ 'https://i.pinimg.com/1200x/49/26/2a/49262aa6b3a85b90542c5e5dd2256974.jpg' }}" alt="profile" />
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <p class="mb-1 text-black">{{ Auth::user()->name }}</p>
                    <span class="text-secondary text-small">Administrator</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        {{-- User Management --}}
        <li class="nav-item">
            <a class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <span class="menu-title">User Management</span>
                <i class="mdi mdi-account-multiple menu-icon"></i>
            </a>
        </li>

        {{-- Buku --}}
        <li class="nav-item">
            <a class="nav-link {{ Request::is('admin/buku*') && !Request::is('admin/datatables', 'admin/buku/select') ? 'active' : '' }}" href="{{ route('admin.buku.index') }}">
                <span class="menu-title">Buku</span>
                <i class="mdi mdi-book menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Request::is('admin/kategori*') ? 'active' : '' }}" href="{{ route('admin.kategori.index') }}">
                <span class="menu-title">Kategori</span>
                <i class="mdi mdi-tag menu-icon"></i>
            </a>
        </li>

        {{-- Customer --}}
        <li class="nav-item {{ Request::is('admin/customer*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#customerMenu" aria-expanded="{{ Request::is('admin/customer*') ? 'true' : 'false' }}" aria-controls="customerMenu">
                <span class="menu-title">Customer</span>
                <i class="mdi mdi-account menu-icon"></i>
                <i class="menu-arrow mdi mdi-chevron-down"></i>
            </a>
            <div class="collapse {{ Request::is('admin/customer*') ? 'show' : '' }}" id="customerMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/customer') && !Request::is('admin/customer/create*') ? 'active' : '' }}" href="{{ route('admin.customer.index') }}">
                            <span class="menu-title">Daftar Customer</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/customer/create1') ? 'active' : '' }}" href="{{ route('admin.customer.create1') }}">
                            <span class="menu-title">Tambah Form 1</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/customer/create2') ? 'active' : '' }}" href="{{ route('admin.customer.create2') }}">
                            <span class="menu-title">Tambah Form 2</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Scanner --}}
        <li class="nav-item {{ Request::is('admin/scanner*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#scannerMenu" aria-expanded="{{ Request::is('admin/scanner*') ? 'true' : 'false' }}" aria-controls="scannerMenu">
                <span class="menu-title">Scanner</span>
                <i class="mdi mdi-barcode-scan menu-icon"></i>
                <i class="menu-arrow mdi mdi-chevron-down"></i>
            </a>
            <div class="collapse {{ Request::is('admin/scanner*') ? 'show' : '' }}" id="scannerMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/scanner') ? 'active' : '' }}" href="{{ route('admin.scanner.index') }}">
                            <span class="menu-title">Barcode Reader</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/scanner-qr') ? 'active' : '' }}" href="{{ route('admin.scanner.qr') }}">
                            <span class="menu-title">QR Code Reader</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Cetak PDF --}}
        <li class="nav-item {{ Request::is('admin/pdf*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#pdfMenu" aria-expanded="{{ Request::is('admin/pdf*') ? 'true' : 'false' }}" aria-controls="pdfMenu">
                <span class="menu-title">Cetak PDF</span>
                <i class="mdi mdi-file-pdf menu-icon"></i>
                <i class="menu-arrow mdi mdi-chevron-down"></i>
            </a>
            <div class="collapse {{ Request::is('admin/pdf*') ? 'show' : '' }}" id="pdfMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/pdf/sertifikat') ? 'active' : '' }}" href="{{ route('admin.pdf.sertifikat') }}">
                            <span class="menu-title">Sertifikat</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/pdf/undangan') ? 'active' : '' }}" href="{{ route('admin.pdf.undangan') }}">
                            <span class="menu-title">Undangan</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Tag Harga --}}
        <li class="nav-item {{ Request::is('admin/tag*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#tagMenu" aria-expanded="{{ Request::is('admin/tag*') ? 'true' : 'false' }}" aria-controls="tagMenu">
                <span class="menu-title">Tag Harga</span>
                <i class="mdi mdi-tag-multiple menu-icon"></i>
                <i class="menu-arrow mdi mdi-chevron-down"></i>
            </a>
            <div class="collapse {{ Request::is('admin/tag*') ? 'show' : '' }}" id="tagMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/tag') ? 'active' : '' }}" href="{{ route('admin.tag.index') }}">
                            <span class="menu-title">Daftar Tag</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Kunjungan Toko --}}
        <li class="nav-item {{ Request::is('admin/toko*') || Request::is('admin/kunjungan*') || Request::is('admin/stok*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#tokoMenu" aria-expanded="{{ Request::is('admin/toko*') || Request::is('admin/kunjungan*') || Request::is('admin/stok*') ? 'true' : 'false' }}" aria-controls="tokoMenu">
                <span class="menu-title">Kunjungan Toko</span>
                <i class="mdi mdi-map-marker-radius menu-icon"></i>
                <i class="menu-arrow mdi mdi-chevron-down"></i>
            </a>
            <div class="collapse {{ Request::is('admin/toko*') || Request::is('admin/kunjungan*') || Request::is('admin/stok*') ? 'show' : '' }}" id="tokoMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/toko') ? 'active' : '' }}" href="{{ route('admin.toko.index') }}">
                            <span class="menu-title">Daftar Toko</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/toko/create') ? 'active' : '' }}" href="{{ route('admin.toko.create') }}">
                            <span class="menu-title">Tambah Toko</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/kunjungan') ? 'active' : '' }}" href="{{ route('admin.kunjungan.index') }}">
                            <span class="menu-title">Riwayat Kunjungan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/stok*') ? 'active' : '' }}" href="{{ route('admin.stok.index') }}">
                            <span class="menu-title">Stok Toko</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- ===== SISTEM ANTRIAN ===== --}}
        <li class="nav-item {{ Request::is('admin/antrian*') || Request::is('papan-antrian') ? 'active' : '' }}">

            <a class="nav-link"
            data-bs-toggle="collapse"
            href="#antrianMenu"
            aria-expanded="{{ Request::is('admin/antrian*') || Request::is('papan-antrian') ? 'true' : 'false' }}"
            aria-controls="antrianMenu">

                <span class="menu-title">
                    Sistem Antrian
                </span>

                <i class="mdi mdi-ticket-confirmation menu-icon"></i>
                <i class="menu-arrow"></i>
            </a>

            <div class="collapse {{ Request::is('admin/antrian*') || Request::is('papan-antrian') ? 'show' : '' }}"
                id="antrianMenu">

                <ul class="nav flex-column sub-menu">

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/antrian') ? 'active' : '' }}"
                        href="{{ route('antrian.admin') }}">

                            Kelola Antrian

                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/antrian/riwayat') ? 'active' : '' }}"
                        href="{{ route('antrian.riwayat') }}">

                            Riwayat Antrian

                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                        target="_blank"
                        href="{{ route('antrian.papan') }}">

                            Papan Antrian

                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                        target="_blank"
                        href="{{ route('antrian.landing') }}">

                            Ambil Antrian

                        </a>
                    </li>

                </ul>

            </div>

        </li>

        {{-- NFC Absensi Mahasiswa --}}
        <li class="nav-item {{ Request::is('nfc/*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#nfcMenu"
               aria-expanded="{{ Request::is('nfc/*') ? 'true' : 'false' }}" aria-controls="nfcMenu">
                <span class="menu-title">Absensi NFC</span>
                <i class="mdi mdi-nfc menu-icon"></i>
                <i class="menu-arrow mdi mdi-chevron-down"></i>
            </a>
            <div class="collapse {{ Request::is('nfc/*') ? 'show' : '' }}" id="nfcMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('nfc/register') ? 'active' : '' }}"
                           href="{{ route('nfc.register.form') }}">
                            <span class="menu-title">Registrasi KTM</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('nfc/scanner') ? 'active' : '' }}"
                           href="{{ route('nfc.scanner.form') }}">
                            <span class="menu-title">Scanner Absensi</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Else --}}
        <li class="nav-item {{ Request::is('admin/buku/select', 'admin/order*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#elseMenu" aria-expanded="{{ Request::is('admin/buku/select', 'admin/order*') ? 'true' : 'false' }}" aria-controls="elseMenu">
                <span class="menu-title">Else</span>
                <i class="mdi mdi-dots-horizontal menu-icon"></i>
                <i class="menu-arrow mdi mdi-chevron-down"></i>
            </a>
            <div class="collapse {{ Request::is('admin/buku/select', 'admin/order*') ? 'show' : '' }}" id="elseMenu">
                <ul class="nav flex-column sub-menu">

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/order*') ? 'active' : '' }}" href="{{ route('admin.order.index') }}">
                            <span class="menu-title">Order</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/demo/table') ? 'active' : '' }}" href="{{ route('admin.demo.table') }}">
                            <span class="menu-title">HTML Table</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/datatables') && !Request::is('admin/datatables/manual') ? 'active' : '' }}" href="{{ route('admin.datatables.index') }}">
                            <span class="menu-title">DataTables</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/wilayah') ? 'active' : '' }}" href="{{ route('admin.wilayah.index') }}">
                            <span class="menu-title">Wilayah</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>

    </ul>
</nav>