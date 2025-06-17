@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <!-- Main Content -->
    <div id="content">

        {{-- top bar --}}
        @include('layouts.topbar')

        {{-- top bar end --}}

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Rukun Tetangga</h1>
            </div>

            <!-- Content Row -->

            <div class="row">

                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Daftar Warga</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="dropdownMenuLink">
                                    <div class="dropdown-header">Tambah Data RT</div>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#modalTambahWarga">Tambah</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">id</th>
                                        <th scope="col">NOMOR RT</th>
                                        <th scope="col">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rukun_tetangga as $rukun_tetangga)
                                        <tr>
                                            <th scope="row">{{ $rukun_tetangga->id }}</th>
                                            <td>{{ $rukun_tetangga->nomor_rt }}</td>
                                            <td>
                                                <form action="{{ route('rukun_tetangga.destroy', $rukun_tetangga->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                    {{-- Alert Error --}}
                                                </form>
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditRT{{ $rukun_tetangga->id }}">
                                                    Edit
                                                </button>
                                            </td>
                                            
                                        </tr>

                                        <tr>
                                            @if (session('error'))
                                                   <div class="alert alert-danger alert-dismissible fade show"
                                                       role="alert">
                                                       {{ session('error') }}
                                                       <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                           aria-label="Close"></button>
                                                   </div>
                                               @endif
                                        </tr>
                                        
                                        
                                        <!-- Modal Edit RT -->
                                        <div class="modal fade" id="modalEditRT{{ $rukun_tetangga->id }}" tabindex="-1"
                                            aria-labelledby="modalEditRTLabel{{ $rukun_tetangga->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content shadow-lg">
                                                    <div class="modal-header bg-warning text-white">
                                                        <h5 class="modal-title"
                                                            id="modalEditRTLabel{{ $rukun_tetangga->id }}">Edit Nomor RT
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <form action="{{ route('rukun_tetangga.update', $rukun_tetangga->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="nomor_rt{{ $rukun_tetangga->id }}"
                                                                    class="form-label">Nomor RT</label>
                                                                <input type="text" class="form-control" name="nomor_rt"
                                                                    id="nomor_rt{{ $rukun_tetangga->id }}"
                                                                    value="{{ $rukun_tetangga->nomor_rt }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-warning">Simpan
                                                                Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        <!-- Modal Tambah RT -->
                        <div class="modal fade" id="modalTambahWarga" tabindex="-1" aria-labelledby="modalTambahWargaLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content shadow-lg">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="modalTambahWargaLabel">Tambah Nomor RT</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{-- Form Tambah Warga --}}
                                        <form action="{{ route('rukun_tetangga.store') }}" method="POST" class="p-4">
                                            @csrf

                                            <div class="row mb-3">
                                                <label for="nomor_rt" class="form-label">Nomor rt</label>
                                                <input type="text" name="nomor_rt" id="nomor_rt" maxlength="16" required
                                                    value="{{ old('nomor_rt') }}"
                                                    class="form-control @error('nomor_rt') is-invalid @enderror"
                                                    placeholder="Contoh: 01">
                                                <small class="form-text text-muted">Masukkan Nomor rt</small>
                                                @error('nomor_rt')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Simpan Data</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>



        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->


@endsection
