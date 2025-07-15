@extends('rw.layouts.app')

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
        @include('rw.layouts.topbar')

        {{-- top bar end --}}

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Content Row -->

            <div class="row">

                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
    <div class="card shadow mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
    {{-- Judul: Hanya judul di header --}}
    <h6 class="m-0 font-weight-bold text-primary">Tabel Rukun Tetangga</h6>
    {{-- Tombol tambah dan info jumlah RT akan dipindahkan ke card-body --}}
</div>

<div class="card-body">
    {{-- Kontainer Flexbox untuk Jumlah RT (kiri) dan Tombol Tambah (kanan) di dalam card-body --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-1">
        {{-- Informasi Jumlah RT --}}
        <div class="d-flex align-items-center gap-1 mb-1 mb-sm-0">
            <i class="fas fa-home text-primary"></i>
            <span class="fw-semibold text-dark">Jumlah : {{ $total_rt ?? 0 }} RT</span>
        </div>

        {{-- Tombol tambah --}}
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
            data-bs-target="#modalTambahRt">
            <i class="fas fa-plus"></i> Tambah
        </button>
    </div>

    <div class="table-responsive table-container">
        <table class="table table-hover table-sm scroll-table text-nowrap">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">NIK</th>
                    <th scope="col">NOMOR RT</th>
                    <th scope="col">NAMA KETUA RT</th>
                    <th scope="col">MULAI MENJABAT</th>
                    <th scope="col">AKHIR JABATAN</th>
                    <th scope="col">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rukun_tetangga as $rt)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <th scope="row">{{ $rt->nik }}</th>
                        <td>{{ $rt->nomor_rt }}</td>
                        <td>{{ $rt->nama_ketua_rt }}</td>
                        <td>{{ $rt->mulai_menjabat }}</td>
                        <td>{{ $rt->akhir_jabatan }}</td>
                        <td>
                            <form action="{{ route('rukun_tetangga.destroy', $rt->id) }}"
                                method="POST" class="d-inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus RT ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i
                                        class="fas fa-trash-alt"></i></button>
                            </form>
                            <button type="button" class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditRT{{ $rt->id }}">
                                <i class="fas fa-edit"></i></button>
                        </td>
                    </tr>

                    {{-- Modal Edit RT --}}
                    <div class="modal fade" id="modalEditRT{{ $rt->id }}" tabindex="-1"
                        aria-labelledby="modalEditRTLabel{{ $rt->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content shadow-lg">
                                <div class="modal-header bg-warning text-white">
                                    <h5 class="modal-title"
                                        id="modalEditRTLabel{{ $rt->id }}">Edit Data RT
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <form action="{{ route('rukun_tetangga.update', $rt->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="nik{{ $rt->id }}"
                                                class="form-label">Nik</label>
                                            <input type="text" class="form-control"
                                                name="nik" id="nik{{ $rt->id }}"
                                                value="{{ $rt->nik }}" required readonly>
                                            @error('nik')
                                                <div class="invalid-feedback">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="nomor_rt{{ $rt->id }}"
                                                class="form-label">Nomor RT</label>
                                            <input type="text" class="form-control"
                                                name="nomor_rt" id="nomor_rt{{ $rt->id }}"
                                                value="{{ $rt->nomor_rt }}" required>
                                            @error('nomor_rt')
                                                <div class="invalid-feedback">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="nama_ketua_rt{{ $rt->id }}"
                                                class="form-label">Nama Ketua RT</label>
                                            <input type="text" class="form-control"
                                                name="nama_ketua_rt"
                                                id="nama_ketua_rt{{ $rt->id }}"
                                                value="{{ $rt->nama_ketua_rt }}" required>
                                            @error('nama_ketua_rt')
                                                <div class="invalid-feedback">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="mulai_menjabat{{ $rt->id }}"
                                                class="form-label">Mulai
                                                Masa Jabatan</label>
                                            <input type="date" name="mulai_menjabat"
                                                id="mulai_menjabat{{ $rt->id }}"
                                                maxlength="16" required
                                                value="{{ $rt->mulai_menjabat }}"
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
                                            <label for="akhir_jabatan{{ $rt->id }}"
                                                class="form-label">Akhir
                                                Masa Jabatan</label>
                                            <input type="date" name="akhir_jabatan"
                                                id="akhir_jabatan{{ $rt->id }}"
                                                maxlength="16" required
                                                value="{{ $rt->akhir_jabatan }}"
                                                class="form-control @error('akhir_jabatan') is-invalid @enderror"
                                                placeholder="Contoh: 2023-2025">
                                            <small class="form-text text-muted">Masukkan Masa Akhir Jabatan</small>
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

                {{-- Alert Error (ditempatkan di luar loop foreach, atau di dalam <tr> dengan colspan) --}}
                @if (session('error'))
                    <tr>
                        <td colspan="7"> {{-- Sesuaikan colspan dengan jumlah kolom di tabel Anda (7 kolom) --}}
                            <div class="alert alert-danger alert-dismissible fade show mt-2"
                                role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 px-3">
            <div class="text-muted mb-2">
                Menampilkan {{ $rukun_tetangga->firstItem() }}-{{ $rukun_tetangga->lastItem() }} dari
                total
                {{ $rukun_tetangga->total() }} data
            </div>

            <div>
                {{ $rukun_tetangga->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <div class="modal fade" id="modalTambahRt" tabindex="-1" aria-labelledby="modalTambahRtLabel" aria-hidden="true">
    {{-- Hapus modal-lg agar modal tidak terlalu besar di HP --}}
    <div class="modal-dialog modal-dialog-scrollable"> 
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTambahRtLabel">Tambah Data RT</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('rukun_tetangga.store') }}" method="POST" class="p-4">
                    @csrf

                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" name="nik" id="nik" maxlength="16" required
                            value="{{ old('nik') }}"
                            class="form-control @error('nik') is-invalid @enderror"
                            placeholder="Contoh: 1234567890987654">
                        <small class="form-text text-muted">Masukkan NIK</small>
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nomor_rt" class="form-label">Nomor RT</label>
                        <input type="text" name="nomor_rt" id="nomor_rt" maxlength="16" required
                            value="{{ old('nomor_rt') }}"
                            class="form-control @error('nomor_rt') is-invalid @enderror"
                            placeholder="Contoh: 01">
                        <small class="form-text text-muted">Masukkan Nomor RT</small>
                        @error('nomor_rt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nama_ketua_rt" class="form-label">Nama Ketua RT</label>
                        <input type="text" name="nama_ketua_rt" id="nama_ketua_rt"
                            required value="{{ old('nama_ketua_rt') }}"
                            class="form-control @error('nama_ketua_rt') is-invalid @enderror"
                            placeholder="Contoh: Budi">
                        <small class="form-text text-muted">Masukkan Nama Ketua RT</small>
                        @error('nama_ketua_rt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="mulai_menjabat" class="form-label">Mulai Masa Jabatan</label>
                        <input type="date" name="mulai_menjabat" id="mulai_menjabat" required
                            value="{{ old('mulai_menjabat') }}"
                            class="form-control @error('mulai_menjabat') is-invalid @enderror">
                        <small class="form-text text-muted">Masukkan Masa Mulai Jabatan</small>
                        @error('mulai_menjabat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="akhir_jabatan" class="form-label">Akhir Masa Jabatan</label>
                        <input type="date" name="akhir_jabatan" id="akhir_jabatan" required
                            value="{{ old('akhir_jabatan') }}"
                            class="form-control @error('akhir_jabatan') is-invalid @enderror">
                        <small class="form-text text-muted">Masukkan Masa Akhir Jabatan</small>
                        @error('akhir_jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-none">
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer sticky-bottom bg-light shadow-sm"> {{-- Tambahkan kelas sticky-bottom dan shadow --}}
                <button type="submit" form="modalTambahRt form" class="btn btn-primary w-100">Simpan Data</button>
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
