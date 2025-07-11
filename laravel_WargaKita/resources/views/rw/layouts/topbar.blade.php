<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Tombol buka sidebar modal untuk mobile -->
    <button class="btn btn-link d-md-none rounded-circle mr-3" data-toggle="modal" data-target="#mobileSidebarModal">
        <i class="fa fa-bars"></i>
    </button>

    @php
        $segment = request()->segment(1);

        $judulHalaman = match ($segment) {
            'warga' => 'Manajemen Warga',
            'pengumuman' => 'Pengumuman',
            'kartu_keluarga' => 'Kartu Keluarga',
            'rukun_tetangga' => 'Rukun Tetangga',
            'tagihan' => 'Tagihan',
            'iuran' => 'Iuran',
            'kategori_golongan' => 'Kategori Golongan',
            'pengeluaran' => 'Pengeluaran',
            'laporan_pengeluaran_bulanan' => 'Laporan Pengeluaran Bulanan',
            default => ucwords(str_replace('-', ' ', $segment)),
        };
    @endphp

    <h1 class="h3 mb-0 text-gray-800 mx-2">{{ $judulHalaman }}</h1>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                            aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                {{-- <span class="badge badge-danger badge-counter">3+</span> --}}
            </a>
        </li>

        <!-- Nav Item - Messages -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                {{-- <span class="badge badge-danger badge-counter">7</span> --}}
            </a>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                {{-- Perbaikan di sini: Tambahkan pengecekan Auth::user() --}}
                <span class="mr-3 d-none d-lg-inline text-gray-600 small">
                    @if (Auth::check())
                        {{ Auth::user()->nama }}
                    @else
                        Guest
                    @endif
                </span>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i>
                    Ubah Password
                </a>
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
<!-- End of Topbar -->
