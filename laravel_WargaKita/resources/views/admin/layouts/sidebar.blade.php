<style>
  /* Slide-in dari kiri */
.modal-dialog-slideout-left {
    position: fixed;
    left: 0;
    margin: 0;
    height: 100%;
    transform: translateX(-100%);
    transition: transform 0.3s ease-out;
}

.modal.fade .modal-dialog-slideout-left {
    transform: translateX(-100%);
}

.modal.fade.show .modal-dialog-slideout-left {
    transform: translateX(0);
}

</style>



<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion d-none d-md-block" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Siwar</div>
    </a>

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


    <li class="nav-item {{ isActive('admin/data_rt*') }}">
        <a class="nav-link" href="{{ route('data_rt.index') }}">
            <i class="fas fa-house-user"></i>
            <span>Data RT</span>
        </a>
    </li>

    <li class="nav-item {{ isActive('admin/data_rw*') }}">
        <a class="nav-link" href="{{ route('data_rw.index') }}">
            <i class="fas fa-solid fa-users"></i>
            <span>Data RW</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>




<!-- End of Sidebar -->
