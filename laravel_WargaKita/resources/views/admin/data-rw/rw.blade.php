@extends('admin.layouts.app')

@section('title', $title)

@section('content')

    <style>
        .modal-body {
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-body-scroll {
            max-height: 65vh;
            /* maksimal 65% tinggi layar */
            overflow-y: auto;
            /* scroll jika konten melebihi tinggi */
        }
    </style>

    <!-- Main Content -->
    <div id="content">

        {{-- top bar --}}
        @include('admin.layouts.topbar')

        {{-- top bar end --}}

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Content Row -->

            <div class="row">

                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel RW</h6>
                            {{-- <!-- Tombol Tambah Warga tanpa dropdown --}}
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalTambahRW">
                                <i class="fas fa-plus"></i>
                            </button>

                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="table-responsive table-container">
                                <table class="table table-hover table-sm scroll-table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">NIK</th>
                                            <th scope="col">NOMOR RW</th>
                                            <th scope="col">NAMA KETUA RW</th>
                                            <th scope="col">MULAI MENJABAT</th>
                                            <th scope="col">AKHIR JABATAN</th>
                                            <th scope="col">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rw as $data)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <th scope="row">{{ $data->nik }}</th>
                                                <td>{{ $data->nomor_rw }}</td>
                                                <td>{{ $data->nama_ketua_rw }}</td>
                                                <td>{{ $data->mulai_menjabat }}</td>
                                                <td>{{ $data->akhir_jabatan }}</td>
                                                <td>
                                                    <form action="{{ route('data_rw.destroy', $data->id) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus RW ini?')">
                                                        {{-- Alert Konfirmasi Hapus --}}
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"><i
                                                                class="fas fa-trash-alt"></i> <!-- Ikon hapus --></button>
                                                        {{-- Alert Error --}}
                                                    </form>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditRW{{ $data->id }}">
                                                        <i class="fas fa-edit"></i> <!-- Ikon edit -->
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
                                            <div class="modal fade" id="modalEditRW{{ $data->id }}" tabindex="-1"
                                                aria-labelledby="modalEditRWLabel{{ $data->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content shadow-lg">
                                                        <div class="modal-header bg-warning text-white">
                                                            <h5 class="modal-title"
                                                                id="modalEditRWLabel{{ $data->id }}">Edit Data RW
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                        </div>
                                                        <form action="{{ route('data_rw.update', $data->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="mb-3">

                                                                    <label for="nik{{ $data->id }}"
                                                                        class="form-label">Nik</label>
                                                                    <input type="text" class="form-control"
                                                                        name="nik" id="nik{{ $data->id }}"
                                                                        value="{{ $data->nik }}" required readonly>
                                                                    @error('nik')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>


                                                                <div class="mb-3">
                                                                    <label for="nomor_rw{{ $data->id }}"
                                                                        class="form-label">Nomor RW</label>
                                                                    <input type="text" class="form-control"
                                                                        name="nomor_rw" id="nomor_rw{{ $data->id }}"
                                                                        value="{{ $data->nomor_rw }}" required>
                                                                    @error('nomor_rw')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="nama_ketua_rw{{ $data->id }}"
                                                                        class="form-label">Nama Ketua RW</label>
                                                                    <input type="text" class="form-control"
                                                                        name="nama_ketua_rw"
                                                                        id="nama_ketua_rw{{ $data->id }}"
                                                                        value="{{ $data->nama_ketua_rw }}" required>
                                                                    @error('nama_ketua_rw')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>


                                                                <div class="mb-3">
                                                                    <label for="mulai_menjabat{{ $data->id }}" class="form-label">Mulai
                                                                        Masa Jabatan</label>
                                                                    <input type="date" name="mulai_menjabat"
                                                                        id="mulai_menjabat{{ $data->id }}" maxlength="16" required
                                                                        value="{{ $data->mulai_menjabat }}"
                                                                        class="form-control @error('mulai_menjabat') is-invalid @enderror"
                                                                        placeholder="Contoh: 2023-2025">
                                                                    <small class="form-text text-muted">Masukkan Masa Mulai
                                                                        Jabatan</small>
                                                                    @error('mulai_menjabat')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="akhir_jabatan{{ $data->id }}" class="form-label">Akhir
                                                                        Masa Jabatan</label>
                                                                    <input type="date" name="akhir_jabatan"
                                                                        id="akhir_jabatan{{ $data->id }}" maxlength="16" required
                                                                        value="{{ $data->akhir_jabatan }}"
                                                                        class="form-control @error('akhir_jabatan') is-invalid @enderror"
                                                                        placeholder="Contoh: 2023-2025">
                                                                    <small class="form-text text-muted">Masukkan Masa Akhir
                                                                        Jabatan</small>
                                                                    @error('akhir_jabatan')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
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
                        </div>
                        <!-- Info dan Tombol Pagination Sejajar -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 px-3">
                            <!-- Info Kustom -->
                            <div class="text-muted mb-2">
                                Menampilkan {{ $rw->firstItem() }}-{{ $rw->lastItem() }} dari
                                total
                                {{ $rw->total() }} data
                            </div>

                            <!-- Tombol Pagination -->
                            <div>
                                {{ $rw->links('pagination::bootstrap-5') }}
                            </div>
                        </div>

                        <!-- Modal Tambah RT -->
                        <div class="modal fade" id="modalTambahRW" tabindex="-1" aria-labelledby="modalTambahRWLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content shadow-lg">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="modalTambahRtLabel">Tambah Data RW</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{-- Form Tambah Warga --}}
                                        <form action="{{ route('data_rw.store') }}" method="POST" class="p-4">
                                            @csrf

                                            <div class="mb-3">
                                                <label for="nik" class="form-label">Nik</label>
                                                <input type="text" name="nik" id="nik" maxlength="16"
                                                    required value="{{ old('nik') }}"
                                                    class="form-control @error('nik') is-invalid @enderror"
                                                    placeholder="Contoh: 1234567890987654">
                                                <small class="form-text text-muted">Masukkan Nik</small>
                                                @error('nik')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="nomor_rw" class="form-label">Nomor Rw</label>
                                                <input type="text" name="nomor_rw" id="nomor_rw" maxlength="16"
                                                    required value="{{ old('nomor_rw') }}"
                                                    class="form-control @error('nomor_rw') is-invalid @enderror"
                                                    placeholder="Contoh: 01">
                                                <small class="form-text text-muted">Masukkan Nomor rw</small>
                                                @error('nomor_rw')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="nama_ketua_rw" class="form-label">Nama Ketua RW</label>
                                                <input type="text" name="nama_ketua_rw" id="nama_ketua_rw"
                                                    maxlength="16" required value="{{ old('nama_ketua_rw') }}"
                                                    class="form-control @error('nama_ketua_rw') is-invalid @enderror"
                                                    placeholder="Contoh: Budi">
                                                <small class="form-text text-muted">Masukkan Nama Ketua RT</small>
                                                @error('nama_ketua_rw')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="mulai_menjabat" class="form-label">Mulai Masa Jabatan</label>
                                                <input type="date" name="mulai_menjabat" id="mulai_menjabat"
                                                    maxlength="16" required value="{{ old('mulai_menjabat') }}"
                                                    class="form-control @error('mulai_menjabat') is-invalid @enderror"
                                                    placeholder="Contoh: 2023-2025">
                                                <small class="form-text text-muted">Masukkan Masa Mulai Jabatan</small>
                                                @error('mulai_menjabat')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="akhir_jabatan" class="form-label">Akhir Masa Jabatan</label>
                                                <input type="date" name="akhir_jabatan" id="akhir_jabatan"
                                                    maxlength="16" required value="{{ old('akhir_jabatan') }}"
                                                    class="form-control @error('akhir_jabatan') is-invalid @enderror"
                                                    placeholder="Contoh: 2023-2025">
                                                <small class="form-text text-muted">Masukkan Masa Akhir Jabatan</small>
                                                @error('akhir_jabatan')
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
