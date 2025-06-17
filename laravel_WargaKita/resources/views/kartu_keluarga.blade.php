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
                <h1 class="h3 mb-0 text-gray-800">Mananjeman Kartu Keluarga</h1>
            </div>

            <!-- Content Row -->

            <div class="row">

                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Daftar Kartu Keluarga</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="dropdownMenuLink">
                                    <div class="dropdown-header">Tambah Data Kartu Keluarga</div>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#modalTambahWarga">Tambah</a>
                                    {{-- <a class="dropdown-item" href="{{ route('kartu_keluarga') }}">Kartu Keluarga</a> --}}
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">id</th>
                                        <th scope="col">No KK</th>
                                        <th scope="col">Nomor Rt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kartu_keluarga as $kk)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration  }}</th>
                                            <td>{{ $kk->no_kk }}</td>
                                            <td>{{ $kk->rukunTetangga->nomor_rt ?? '-' }}</td>

                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        <!-- Modal Tambah kartu keluarga -->
                        <div class="modal fade" id="modalTambahWarga" tabindex="-1" aria-labelledby="modalTambahWargaLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content shadow-lg">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="modalTambahWargaLabel">Tambah Data Kartu keluarga</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{-- Form Tambah Warga --}}
                                        <form action="{{ route('kartu_keluarga.store') }}" method="POST" class="p-4">
                                            @csrf

                                            <!-- Input No KK -->
                                            <div class="row mb-3">
                                                <label for="no_kk" class="form-label">No KK</label>
                                                <input type="text" name="no_kk" id="no_kk"
                                                    class="form-control @error('no_kk') is-invalid @enderror"
                                                    value="{{ old('no_kk') }}" required>
                                                @error('no_kk')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Dropdown No RT -->
                                            <div class="row mb-3">
                                                <label for="id_rt" class="form-label">No RT</label>
                                                <select name="id_rt" id="id_rt"
                                                    class="form-control @error('id_rt') is-invalid @enderror" required>
                                                    <option value="">-- Pilih No RT --</option>
                                                    @foreach ($rukun_tetangga as $rt)
                                                        <option value="{{ $rt->id }}"
                                                            {{ old('id_rt') == $rt->id ? 'selected' : '' }}>
                                                            RT {{ $rt->nomor_rt }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_rt')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>


                                            <div class="row mb-3">
                                                <label for="kepala_kk" class="form-label">Kepala KK</label>
                                                <input type="text" name="kepala_kk" id="kepala_kk"
                                                    class="form-control @error('kepala_kk') is-invalid @enderror"
                                                    value="{{ old('kepala_kk') }}" required>
                                                @error('kepala_kk')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Tombol Submit -->
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
