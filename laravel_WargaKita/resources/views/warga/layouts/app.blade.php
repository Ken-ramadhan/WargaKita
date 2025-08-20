<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ $title ?? 'WargaKita' }}</title>

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Umum untuk sidebar di desktop, tidak berkaitan langsung dengan modal mobile */
        @media (min-width: 768px) {
            .sidebar {
                transition: all 0.3s ease;
            }

            .sidebar.toggled {
                width: 100px !important;
                /* ukuran kecil saat ditutup */
            }

            .sidebar .nav-item .nav-link span {
                transition: opacity 0.3s ease;
            }

            body.sidebar-toggled .sidebar {
                width: 80px;
            }
        }

        /* Gaya untuk tabel yang bisa di-scroll */
        .scroll-table thead th {
            position: sticky;
            top: 0;
            background: rgb(255, 255, 255);
            /* atau sesuaikan dengan warna thead */
            z-index: 10;
        }

        .table-container {
            max-height: 380px;
            overflow-y: auto;
        }

        /* --- CSS Untuk Modal Sidebar Mobile --- */

        /* CSS dasar untuk modal slide dari kiri */
        .modal.fade .modal-dialog.modal-dialog-slideout-left {
            transform: translateX(-100%); /* Awalnya di luar layar */
            transition: transform .3s ease-out; /* Animasi geser */
            margin-left: 0;
            margin-right: auto; /* Mendorong modal ke kiri */
            pointer-events: none; /* Memastikan klik tidak melewati saat modal belum muncul sepenuhnya */
        }

        .modal.show .modal-dialog.modal-dialog-slideout-left {
            transform: translateX(0); /* Meluncur masuk ke layar */
            pointer-events: auto; /* Aktifkan interaksi saat modal muncul */
        }

        .modal-backdrop.fade {
            opacity: 0;
            transition: opacity .15s linear;
        }

        .modal-backdrop.show {
            opacity: .5; /* Sesuaikan opasitas backdrop jika perlu */
        }

        /* Penyesuaian untuk tampilan profesional di mobile (layar kecil) */
        @media (max-width: 575.98px) { /* Ini menargetkan perangkat ekstra kecil (misalnya, ponsel) */
            .modal-dialog.modal-dialog-slideout-left.modal-sm {
                max-width: 35%; /* Batasi lebar modal, misalnya 65% dari lebar layar */
                height: auto; /* Penting: Biarkan tinggi otomatis sesuai konten */
            }

            .modal-content {
                border-radius: .3rem; /* Pertahankan sedikit border-radius agar tidak kaku */
                height: auto; /* Tinggi konten yang menyesuaikan */
                max-height: 100vh; /* Batasi tinggi maksimum modal: tinggi viewport dikurangi margin atas/bawah */
                display: flex; /* Untuk flexbox layout */
                flex-direction: column; /* Konten diatur secara kolom */
            }

            .modal-body {
                overflow-y: auto; /* Aktifkan scroll vertikal jika konten melebihi max-height */
                -webkit-overflow-scrolling: touch; /* Untuk scrolling yang lebih mulus di iOS */
                flex-grow: 1; /* Biarkan body mengambil ruang yang tersedia */
            }

            /* --- Perbaikan Tombol Close --- */
            .modal-header {
                /* Mengatur perataan item di header ke atas */
                align-items: flex-start;
                padding-top: 1rem; /* Sesuaikan padding atas agar judul/logo tidak terlalu mepet */
                padding-bottom: 0.5rem; /* Sesuaikan padding bawah */
                flex-shrink: 0; /* Pastikan header tidak mengecil */
                border-bottom: 2px solid #e3e6f0;
            }

            .modal-header .close {
                /* Menyesuaikan margin negatif pada tombol close jika masih kurang pas */
                margin-top: -1.5rem; /* Angka negatif akan menarik tombol ke atas */
                margin-bottom: 0;
                padding: 0.5rem; /* Tambahkan padding di sekitar tombol agar lebih mudah di klik */
            }

            .modal-header .modal-title,
            .modal-header .sidebar-brand-icon-logo {
                margin-top: 0; /* Pastikan tidak ada margin-top berlebihan */
                margin-bottom: 0;
            }

            .sidebar-brand-icon-logo {
                width: 35px; /* Sesuaikan ukuran logo di header modal */
                height: 35px;
                margin-left: 10px; /* Beri sedikit jarak antara logo dan judul */
            }

        }
        /* --- Akhir CSS Untuk Modal Sidebar Mobile --- */
    </style>


</head>

@php
    function isActive($pattern, $output = 'active')
    {
        return Request::is($pattern) ? $output : '';
    }

@endphp

<body id="page-top">

    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion d-none d-md-block"
            id="accordionSidebar">
            @include('warga.layouts.sidebar')
        </ul>
        <div class="modal fade" id="mobileSidebarModal" tabindex="-1" role="dialog"
            aria-labelledby="mobileSidebarLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-slideout-left modal-sm" role="document">
                <div class="modal-content bg-primary text-white">
                    <div class="modal-header border-0">
                        <img src="{{ asset('img/logo.png')}}" class="sidebar-brand-icon-logo" alt="Logo">
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <ul class="navbar-nav sidebar sidebar-dark accordion">
                            <hr class="sidebar-divider my-0">

                            <li class="nav-item {{ Route::is('dashboard-main') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboard-main') }}">
                                    <i class="fas fa-fw fa-tachometer-alt"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            {{-- ... semua item lainnya tetap seperti sebelumnya ... --}}

                            <li class="nav-item {{ Request::is('warga/warga_pengumuman*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('pengumuman-main') }}">
                                    <i class="fas fa-bullhorn"></i>
                                    <span>Pengumuman</span>
                                </a>
                            </li>

                            <li class="nav-item {{ Request::is('warga/lihat_kk*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('lihat_kk') }}">
                                    <i class="fas fa-id-card"></i>
                                    <span>Lihat KK</span>
                                </a>
                            </li>
                            <li class="nav-item {{ Request::is('warga/tagihan*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('tagihan') }}">
                                    <i class="fas fa-id-card"></i>
                                    <span>Lihat Tagihan</span>
                                </a>
                            </li>

                            <hr class="sidebar-divider d-none d-md-block">

                            <hr class="sidebar-divider d-none d-md-block">
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div id="content-wrapper" class="d-flex flex-column">

            @yield('konten')
            {{-- footer --}}
            @include('warga.layouts.footer')
            </div>

    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>




</body>

</html>