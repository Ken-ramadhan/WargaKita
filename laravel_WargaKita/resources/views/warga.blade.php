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
                <h1 class="h3 mb-0 text-gray-800">Mananjeman Warga</h1>
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
                                    <div class="dropdown-header">Tambah Data Warga</div>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#modalTambahWarga">Tambah</a>
                                    <a class="dropdown-item" href="{{ route('kartu_keluarga.index') }}">Kartu Keluarga</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">id</th>
                                        <th scope="col">NIK</th>
                                        <th scope="col">NO KK</th>
                                        <th scope="col">NAMA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($warga as $warga)
                                        <tr>
                                            <th scope="row">{{ $warga->id }}</th>
                                            <td>{{ $warga->nik }}</td>
                                            <td>{{ $warga->no_kk }}</td>
                                            <td>{{ $warga->nama }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        <!-- Modal Tambah Warga -->
                        <div class="modal fade" id="modalTambahWarga" tabindex="-1" aria-labelledby="modalTambahWargaLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content shadow-lg">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="modalTambahWargaLabel">Tambah Data Warga</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{-- Form Tambah Warga --}}
                                        <form action="{{ route('warga.store') }}" method="POST" class="p-4">
                                            @csrf

                                            <div class="row mb-3">
                                                <label for="nik" class="form-label">NIK</label>
                                                <input type="text" name="nik" id="nik" maxlength="16"
                                                    pattern="\d{16}" required value="{{ old('nik') }}"
                                                    class="form-control @error('nik') is-invalid @enderror"
                                                    placeholder="Contoh: 3275121404990002">
                                                <small class="form-text text-muted">Masukkan NIK yang terdiri dari 16
                                                    angka.</small>
                                                @error('nik')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row mb-3">
                                                <label for="no_kk" class="form-label">Nomor Kartu Keluarga (No
                                                    KK)</label>
                                                <input type="text" name="no_kk" id="no_kk" maxlength="16"
                                                    pattern="\d{16}" required value="{{ old('no_kk') }}"
                                                    class="form-control @error('no_kk') is-invalid @enderror"
                                                    placeholder="Contoh: 3275121404990001">
                                                <small class="form-text text-muted">Masukkan No KK yang terdiri dari 16
                                                    angka.</small>
                                                @error('no_kk')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row mb-3">
                                                <label for="nama" class="form-label">Nama Lengkap</label>
                                                <input type="text" name="nama" id="nama" maxlength="255" required
                                                    value="{{ old('nama') }}"
                                                    class="form-control @error('nama') is-invalid @enderror"
                                                    placeholder="Contoh: Rudi Hartono">
                                                <small class="form-text text-muted">Masukkan nama lengkap sesuai
                                                    identitas.</small>
                                                @error('nama')
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
