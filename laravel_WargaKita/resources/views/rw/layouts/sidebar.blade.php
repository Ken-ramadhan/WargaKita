<style>
    /* CSS untuk logo di sidebar */
    .sidebar-brand-icon-logo {
        width: 110px;
        /* Ukuran normal logo */
        height: 110px;
        object-fit: contain;
        /* margin-right: 200px; Ini terlalu besar dan akan menyebabkan masalah saat dikecilkan. Kita akan menghapusnya atau menguranginya. */
        filter: brightness(0) invert(1);
        /* Ini membuat logo hitam menjadi putih */
    }

    /* Rotasi yang sebelumnya ada pada ikon Font Awesome tidak relevan lagi untuk gambar */
    .sidebar-brand-icon {
        transform: none !important;
        /* Batalkan rotasi jika ada dari kelas lain */
    }

    /* Aturan untuk Sidebar yang Dikecilkan */
    /* Targetkan saat #accordionSidebar memiliki kelas .toggled */
    #accordionSidebar.toggled .sidebar-brand-icon-logo {
        width: 50px;
        /* Ukuran logo lebih kecil saat sidebar dikecilkan */
        height: 50px;
        margin-left: 26px;
        /* Hapus margin kanan agar logo menjadi fokus utama */
    }


    /* Sesuaikan juga margin pada .sidebar-brand secara keseluruhan jika perlu */
    /* Saat sidebar dikecilkan, pastikan branding area juga pas */
    #accordionSidebar.toggled .sidebar-brand {
        width: 4.375rem !important;
        /* Lebar sidebar yang dikecilkan biasanya sekitar 4.375rem */
        padding-left: 0;
        padding-right: 0;
    }

    /* Jika Anda ingin logo berada di tengah secara horizontal di area icon saja saat dikecilkan */
    #accordionSidebar.toggled .sidebar-brand .sidebar-brand-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        /* Pastikan pembungkus icon mengisi lebar */
    }

    /* Hapus margin-right 200px dari .sidebar-brand-icon-logo */
    /* Ini akan konflik saat sidebar dikecilkan */
    .sidebar-brand-icon-logo {
        margin-right: 0;
        /* Setel ulang margin-right ke 0 */
        /* Jika Anda ingin margin kanan pada mode normal, tambahkan di sini */
        /* contoh: margin-right: 15px; */
    }

    /* Untuk kasus mobile atau breakpoint tertentu, kita juga bisa menyesuaikan */
    @media (max-width: 767.98px) {
        .sidebar-brand-icon-logo {
            width: 50px;
            /* Ukuran logo di mobile (jika sidebar mobile juga muncul) */
            height: 50px;
            margin-right: 10px;
            /* Sesuaikan margin untuk mobile */
        }
    }


    /* Asumsi CSS dasar dari template SB Admin 2 untuk sidebar sudah dimuat */
    /* .sidebar-dark.accordion { ... } */
    /* .sidebar-brand { ... } */
    /* .sidebar-brand-icon { ... } */
    /* .sidebar-brand-text { ... } */
    /* .nav-item { ... } */
    /* .nav-link { ... } */
    /* dll. */
</style>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion d-none d-md-block" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/logo.png') }}" alt="SiWar Logo" class="sidebar-brand-icon-logo">
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ Request::is('rw') ? 'active' : '' }}"> {{-- Menggunakan Request::is() untuk aktivasi --}}
        <a class="nav-link" href="{{ url('rw') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    {{-- Item Navigasi Lainnya --}}
    <li class="nav-item {{ Request::is('rw/rukun_tetangga*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('rukun_tetangga.index') }}">
            <i class="fas fa-house-user"></i>
            <span>Rukun Tetangga</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('rw/warga*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('warga.index') }}">
            <i class="fas fa-id-card"></i>
            <span>Manajemen Warga</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('rw/kartu_keluarga*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('kartu_keluarga.index') }}">
            <i class="fas fa-users "></i>
            <span>Kartu Keluarga</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('rw/pengumuman') || Request::is('rw/pengumuman/*') && !Request::is('rw/pengumuman-rt*') ? 'active' : '' }}">
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
            <i class="fas fa-dollar-sign"></i>
            <span>Tagihan</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('rw/transaksi*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('transaksi.index') }}">
            <i class="fas fa-money-bill-wave"></i>
            <span>Pengeluaran</span>
        </a>
    </li>
        <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
