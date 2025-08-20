<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ $title ?? 'WargaKita' }}</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>
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
            transform: translateX(-100%);
            /* Awalnya di luar layar */
            transition: transform .3s ease-out;
            /* Animasi geser */
            margin-left: 0;
            margin-right: auto;
            /* Mendorong modal ke kiri */
            pointer-events: none;
            /* Memastikan klik tidak melewati saat modal belum muncul sepenuhnya */
        }

        .modal.show .modal-dialog.modal-dialog-slideout-left {
            transform: translateX(0);
            /* Meluncur masuk ke layar */
            pointer-events: auto;
            /* Aktifkan interaksi saat modal muncul */
        }

        .modal-backdrop.fade {
            opacity: 0;
            transition: opacity .15s linear;
        }

        .modal-backdrop.show {
            opacity: .5;
            /* Sesuaikan opasitas backdrop jika perlu */
        }

        /* Penyesuaian untuk tampilan profesional di mobile (layar kecil) */
        @media (max-width: 575.98px) {

            /* Ini menargetkan perangkat ekstra kecil (misalnya, ponsel) */
            .modal-dialog.modal-dialog-slideout-left.modal-sm {
                max-width: 35%;
                /* Batasi lebar modal, misalnya 65% dari lebar layar */
                height: auto;
                /* Penting: Biarkan tinggi otomatis sesuai konten */
            }

            .modal-content {
                border-radius: .3rem;
                /* Pertahankan sedikit border-radius agar tidak kaku */
                height: auto;
                /* Tinggi konten yang menyesuaikan */
                max-height: 100vh;
                /* Batasi tinggi maksimum modal: tinggi viewport dikurangi margin atas/bawah */
                display: flex;
                /* Untuk flexbox layout */
                flex-direction: column;
                /* Konten diatur secara kolom */
            }

            .modal-body {
                overflow-y: auto;
                /* Aktifkan scroll vertikal jika konten melebihi max-height */
                -webkit-overflow-scrolling: touch;
                /* Untuk scrolling yang lebih mulus di iOS */
                flex-grow: 1;
                /* Biarkan body mengambil ruang yang tersedia */
            }

            /* --- Perbaikan Tombol Close --- */
            .modal-header {
                /* Mengatur perataan item di header ke atas */
                align-items: flex-start;
                padding-top: 1rem;
                /* Sesuaikan padding atas agar judul/logo tidak terlalu mepet */
                padding-bottom: 0.5rem;
                /* Sesuaikan padding bawah */
                flex-shrink: 0;
                /* Pastikan header tidak mengecil */
                border-bottom: 2px solid #e3e6f0;
            }

            .modal-header .close {
                /* Menyesuaikan margin negatif pada tombol close jika masih kurang pas */
                margin-top: -1.5rem;
                /* Angka negatif akan menarik tombol ke atas */
                margin-bottom: 0;
                padding: 0.5rem;
                /* Tambahkan padding di sekitar tombol agar lebih mudah di klik */
            }

            .modal-header .modal-title,
            .modal-header .sidebar-brand-icon-logo {
                margin-top: 0;
                /* Pastikan tidak ada margin-top berlebihan */
                margin-bottom: 0;
            }

            .sidebar-brand-icon-logo {
                width: 35px;
                /* Sesuaikan ukuran logo di header modal */
                height: 35px;
                margin-left: 10px;
                /* Beri sedikit jarak antara logo dan judul */
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

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <!-- Sidebar Desktop -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion d-none d-md-block"
            id="accordionSidebar">
            @include('rw.layouts.sidebar')
        </ul>
        <div class="modal fade" id="mobileSidebarModal" tabindex="-1" role="dialog"
            aria-labelledby="mobileSidebarLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-slideout-left modal-sm" role="document">
                <div class="modal-content bg-primary text-white">
                    <div class="modal-header border-0">
                        <img src="{{ asset('img/logo.png') }}" class="sidebar-brand-icon-logo" alt="Logo">
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <ul class="navbar-nav sidebar sidebar-dark accordion">
                            <hr class="sidebar-divider my-0">

                            <li class="nav-item{{ Request::is('rw') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboard-rw') }}">
                                    <i class="fas fa-fw fa-tachometer-alt"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            {{-- ... semua item lainnya tetap seperti sebelumnya ... --}}

                            <li class="nav-item {{ Request::is('rw/rukun_tetangga*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('rukun_tetangga.index') }}">
                                    <i class="fas fa-house-user"></i>
                                    <span>Rukun Tetangga</span>
                                </a>
                            </li>

                            <li class="nav-item {{ Request::is('rw/warga*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('warga.index') }}">
                                    <i class="fas fa-users"></i>
                                    <span>Manajemen Warga</span>
                                </a>
                            </li>

                            <li class="nav-item {{ Request::is('rw/kartu_keluarga*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('kartu_keluarga.index') }}">
                                    <i class="fas fa-users "></i>
                                    <span>Kartu Keluarga</span>
                                </a>
                            </li>
                            <li
                                class="nav-item {{ Request::is('rw/pengumuman') || (Request::is('rw/pengumuman/*') && !Request::is('rw/pengumuman-rt*')) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('pengumuman.index') }}">
                                    <i class="fas fa-bullhorn"></i>
                                    <span>Pengumuman RW</span>
                                </a>
                            </li>

                            <li class="nav-item {{ Request::is('rw/pengumuman-rt*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('pengumuman-rt.index') }}">
                                    <i class="fas fa-bullhorn"></i>
                                    <span>Pengumuman RT</span>
                                </a>
                            </li>

                            <li class="nav-item {{ Request::is('rw/tagihan*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('tagihan.index') }}">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    <span>Manajemen Keuangan</span>
                                </a>
                            </li>

                            <hr class="sidebar-divider d-none d-md-block">

                            <hr class="sidebar-divider d-none d-md-block">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            @yield('content')
            <!-- End of Main Content -->




            <!-- End of Content Wrapper -->
            {{-- footer --}}
            @include('rw.layouts.footer')
            <!-- End of Footer -->
        </div>

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Fungsi togglePassword
        function togglePassword(inputId, buttonElement) {
            const passwordInput = document.getElementById(inputId);
            const icon = buttonElement.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        // // Kode JavaScript untuk menampilkan modal jika ada error atau success
        document.addEventListener('DOMContentLoaded', function() {
            // Pastikan elemen modal sudah ada di DOM sebelum mencoba menginisialisasinya
            const modalElement = document.getElementById('modalUbahPassword');
            if (modalElement) {
                const modalUbahPassword = new bootstrap.Modal(modalElement);

                // Cek apakah ada error dari Laravel
                @if ($errors->hasAny(['current_password', 'password', 'password_confirmation']))
                    modalUbahPassword.show(); // Tampilkan modal jika ada error
                @endif

                // Opsional: Jika Anda ingin modal otomatis tertutup setelah pesan sukses muncul dan dilihat
                @if (session('success'))
                    // Tampilkan pesan sukses, lalu tutup modal setelah beberapa detik
                    setTimeout(function() {
                        modalUbahPassword.hide();
                    }, 3000); // Tutup setelah 3 detik
                @endif
            }

        });
    </script>



</body>

</html>
