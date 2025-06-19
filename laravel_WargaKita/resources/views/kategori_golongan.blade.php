@extends('layouts.app')

@section('title', $title)

@section('content')

    <!-- Main Content -->
    <div id="content">

        {{-- top bar --}}
        @include('layouts.topbar')

        {{-- top bar end --}}

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Content Row -->

            <div class="row">

                <form method="GET" action="{{ route('kategori_golongan.index') }}" class="mb-3 d-flex gap-2">
                    <div class="input-group">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Cari Data Kategori Golongan...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <button class="btn btn-primary">Terapkan</button>
                    <a href="{{ route('kategori_golongan.index') }}" class="btn btn-secondary">Reset</a>
                </form>

                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Daftar Kategori Golongan</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="dropdownMenuLink">
                                    <div class="dropdown-header">Data Iuran</div>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#modalTambahGolongan">Tambah</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">NAMA</th>
                                        <th scope="col">KETERANGAN</th>
                                        <th scope="col">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kategori_golongan as $golongan)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $golongan->nama }}</td>
                                            <td>{{ $golongan->keterangan }}</td>

                                            <td>
                                                <form action="{{ route('kategori_golongan.destroy', $golongan->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditGolongan{{ $golongan->id }}">
                                                    Edit
                                                </button>
                                            </td>

                                        </tr>

                                        <tr>
                                            @if (session('error'))
                                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    {{ session('error') }}
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                </div>
                                            @endif
                                        </tr>

                                        <!-- Modal Edit kategori Golongan -->
                                        <div class="modal fade" id="modalEditGolongan{{ $golongan->id }}" tabindex="-1"
                                            aria-labelledby="modalEditGolonganLabel{{ $golongan->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content shadow-lg">
                                                    <div class="modal-header bg-warning text-white">
                                                        <h5 class="modal-title" id="modalEditGolongan{{ $golongan->id }}">
                                                            Edit Kategori Golongan
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <form action="{{ route('kategori_golongan.update', $golongan->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="no_kk{{ $golongan->id }}"
                                                                    class="form-label">Nama</label>
                                                                <input type="text" name="nama" id="nama"
                                                                    class="form-control @error('nama') is-invalid @enderror"
                                                                    value="{{ old('nama', $golongan->nama) }}" required>
                                                                @error('nama')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror

                                                                <div class="row mb-3">
                                                                    <label for="keterangan"
                                                                        class="form-label">Keterangan</label>
                                                                    <textarea name="keterangan" id="keterangan" cols="30" rows="10"
                                                                        class="form-control @error('keterangan') is-invalid @enderror" required>
                                                                        {{ old('keterangan', $golongan->keterangan) }}
                                                                    </textarea>
                                                                    @error('keterangan')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
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
                            <!-- Info dan Tombol Pagination Sejajar -->
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                <!-- Info Kustom -->
                                <div class="text-muted mb-2">
                                    Menampilkan
                                    {{ $kategori_golongan->firstItem() ?? '0' }}-{{ $kategori_golongan->lastItem() }}
                                    dari total
                                    {{ $kategori_golongan->total() }} data
                                </div>

                                <!-- Tombol Pagination -->
                                <div>
                                    {{ $kategori_golongan->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>

                        <!-- Modal Tambah Kategori Golongan -->
                        <div class="modal fade" id="modalTambahGolongan" tabindex="-1"
                            aria-labelledby="modalTambahGolonganLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content shadow-lg">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="modalTambahGolonganLabel">Tambah Data Kategori
                                            Golongan</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{-- Form Tambah Kategori Golongan --}}
                                        <form action="{{ route('kategori_golongan.store') }}" method="POST"
                                            class="p-4">
                                            @csrf

                                            <!-- Input Nama Kategori Golongan -->
                                            <div class="row mb-3">
                                                <label for="nama" class="form-label">Nama</label>
                                                <input type="text" name="nama" id="nama"
                                                    class="form-control @error('nama') is-invalid @enderror"
                                                    value="{{ old('nama') }}" required>
                                                @error('nama')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Input Keterangan -->
                                            <div class="row mb-3">
                                                <label for="keterangan" class="form-label">Keterangan</label>
                                                <textarea name="keterangan" id="keterangan" cols="30" rows="10" class="form-control @error('keterangan') is-invalid @enderror" required>
                                                    {{ old('keterangan') }}
                                                </textarea>
                                                @error('keterangan')
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
