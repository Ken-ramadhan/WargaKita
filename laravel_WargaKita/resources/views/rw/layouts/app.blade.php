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
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css')}}" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!--scroll tambahan dari mika-->    <!-- Di dalam <head> tag di file layout utama Anda -->
   <!-- <style>
        /* Pastikan html dan body bisa di-scroll */
        html, body {
            height: 100%; /* Memastikan tinggi minimal 100% dari viewport */
            overflow-x: hidden; /* Mencegah horizontal scrollbar jika tidak diinginkan */
            overflow-y: auto; /* Memungkinkan vertical scrollbar jika konten melebihi tinggi viewport */
            scroll-behavior: smooth; /* Opsional: membuat scrolling lebih halus */
        }

        /* Jika ada elemen utama yang membungkus seluruh konten, pastikan juga bisa di-scroll */
        /* Contoh: jika Anda punya div dengan id="wrapper" atau id="content-wrapper" */
        #wrapper, #content-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Memastikan wrapper minimal setinggi viewport */
            overflow: hidden; /* Biasanya wrapper tidak perlu scroll, biarkan body yang handle */
        }

        /* Pastikan main content area bisa berkembang dan di-scroll */
        #content {
            flex-grow: 1; /* Memungkinkan konten untuk mengisi ruang yang tersedia */
            overflow-y: auto; /* Jika konten di dalam #content melebihi tingginya */
            -webkit-overflow-scrolling: touch; /* Untuk scrolling yang lebih baik di iOS */
        }

        /* Jika Anda menggunakan SB Admin 2, periksa juga CSS bawaannya */
        /* Beberapa class SB Admin 2 mungkin mengatur overflow: hidden */
        /* Anda mungkin perlu menimpanya jika itu yang menyebabkan masalah */
        body.sidebar-toggled #content-wrapper {
            overflow: auto; /* Pastikan konten bisa di-scroll saat sidebar ditoggle */
        }

        /* Tambahkan CSS yang sudah ada sebelumnya di sini */
        @media (min-width: 768px) {
            .sidebar {
                transition: all 0.3s ease;
            }
            .sidebar.toggled {
                width: 100px !important; /* ukuran kecil saat ditutup */
            }
            .sidebar .nav-item .nav-link span {
                transition: opacity 0.3s ease;
            }
        }
    </style>-->
    


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
        background: rgb(255, 255, 255); /* atau sesuaikan dengan warna thead */
        z-index: 10;
    }

    .table-container {
        max-height: 380px;
        overflow-y: auto;
    }
    </style>


</head>

@php
function isActive($pattern, $output = 'active') {
    return Request::is($pattern) ? $output : '';
}

@endphp

<body id="page-top" style="overflow: hidden;">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <!-- Sidebar Desktop -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion d-none d-md-block"
            id="accordionSidebar">
            @include('rw.layouts.sidebar')
        </ul>
       <!-- Sidebar Modal untuk Mobile -->
<div class="modal fade" id="mobileSidebarModal" tabindex="-1" role="dialog" aria-labelledby="mobileSidebarLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout-left modal-sm" role="document">
        <div class="modal-content bg-primary text-white">
            <div class="modal-header border-0">
                <h5 class="modal-title">Menu</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <!-- Salin isi <ul> sidebar ke sini -->
                <ul class="navbar-nav sidebar sidebar-dark accordion">
                    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="index.html">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Nav lainnya -->
    {{-- ... semua item lainnya tetap seperti sebelumnya ... --}}

    <li class="nav-item {{ Request::is('warga*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('warga.index') }}">
            <i class="fas fa-users"></i>
            <span>Manajemen Warga</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('rukun_tetangga*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('rukun_tetangga.index') }}">
            <i class="fas fa-house-user"></i>
            <span>Rukun Tetangga</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('pengumuman*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('pengumuman.index') }}">
            <i class="fas fa-bullhorn"></i>
            <span>Pengumuman</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('tagihan*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('tagihan.index') }}">
            <i class="fas fa-file-invoice-dollar"></i>
            <span>Manajemen Keuangan</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('kategori_golongan*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('kategori_golongan.index') }}">
            <i class="fas fa-layer-group"></i>
            <span>Kategori Golongan</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

                    @include('rw.layouts.sidebar')
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


            {{-- footer --}}
            @include('rw.layouts.footer')
            <!-- End of Footer -->


            <!-- End of Content Wrapper -->
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

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>




</body>

</html>
