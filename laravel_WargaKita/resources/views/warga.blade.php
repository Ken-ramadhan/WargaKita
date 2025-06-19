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
                    <form action="{{ route('warga.index') }}" method="GET" class="mb-3 d-flex gap-2 w-50">
                        <div class="input-group">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari nama atau NIK...">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <a href="{{ route('warga.index') }}" class="btn btn-secondary">Reset</a>
                    </form>


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
                                    <div class="dropdown-header">Manajemen Data Warga</div>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#modalTambahWarga">Tambah Warga</a>
                                    <a class="dropdown-item" href="{{ route('kartu_keluarga.index') }}">Kartu Keluarga</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">NO</th>
                                            <th scope="col">NIK</th>
                                            <th scope="col">NO KK</th>
                                            <th scope="col">NAMA</th>
                                            <th scope="col">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($warga as $item)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $item->nik }}</td>
                                                <td>{{ $item->no_kk }}</td>
                                                <td>{{ $item->nama }}</td>
                                                <td>
                                                    <form action="{{ route('warga.destroy', $item->nik) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                        {{-- Alert Error --}}
                                                    </form>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditwarga{{ $item->nik }}">
                                                        Edit
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit kartu keluarga -->
                                            <div class="modal fade" id="modalEditwarga{{ $item->nik }}" tabindex="-1"
                                                aria-labelledby="modalEditwargaLabel{{ $item->nik }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content shadow-lg">
                                                        <div class="modal-header bg-warning text-white">
                                                            <h5 class="modal-title"
                                                                id="modalEditwargaLabel{{ $item->nik }}">
                                                                Edit Data Warga
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                        </div>
                                                        <form action="{{ route('warga.update', $item->nik) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="nik" class="form-label">NIK</label>
                                                                    <input type="text" name="nik" id="nik"
                                                                        maxlength="16" pattern="\d{16}" required
                                                                        value="{{ $item->nik }}" class="form-control">

                                                                    <label for="no_kk" class="form-label">NO KK</label>
                                                                    <input type="text" name="no_kk"
                                                                        class="form-control @error('no_kk') is-invalid @enderror"
                                                                        value="{{ old('no_kk', $item->no_kk) }}" readonly
                                                                        disabled>

                                                                    <label for="nama" class="form-label">NAMA</label>
                                                                    <input type="text" name="nama" id="nama"
                                                                        required value="{{ $item->nama }}"
                                                                        class="form-control">
                                                                    <input type="hidden" name="form_type"
                                                                        value="edit">


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
                                        Menampilkan {{ $warga->firstItem() ?? "0" }}-{{ $warga->lastItem() }} dari total
                                        {{ $warga->total() }} data
                                    </div>

                                    <!-- Tombol Pagination -->
                                    <div>
                                        {{ $warga->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>


                            </div>
                        </div>

                        <!-- Modal Tambah Warga -->
                        <div class="modal fade" id="modalTambahWarga" tabindex="-1"
                            aria-labelledby="modalTambahWargaLabel" aria-hidden="true">
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
                                            <input type="hidden" name="form_type" value="tambah">

                                            <div class="row mb-3">
                                                <label for="nik" class="form-label">NIK</label>
                                                <input type="text" name="nik" id="nik" maxlength="16"
                                                    pattern="\d{16}" required
                                                    value="{{ old('form_type') === 'tambah' ? old('nik') : '' }}"
                                                    class="form-control {{ old('form_type') === 'tambah' && $errors->has('nik') ? 'is-invalid' : '' }}"
                                                    placeholder="Contoh: 3275121404990002">
                                                <small class="form-text text-muted">Masukkan NIK yang terdiri dari 16
                                                    angka.</small>
                                                @if (old('form_type') === 'tambah')
                                                    @error('nik')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>

                                            <div class="row mb-3">
                                                <label for="no_kk" class="form-label">Nomor Kartu Keluarga (No
                                                    KK)</label>
                                                <input type="text" name="no_kk" id="no_kk" maxlength="16"
                                                    pattern="\d{16}" required
                                                    value="{{ old('form_type') === 'tambah' ? old('no_kk') : '' }}"
                                                    class="form-control {{ old('form_type') === 'tambah' && $errors->has('no_kk') ? 'is-invalid' : '' }}"
                                                    placeholder="Contoh: 3275121404990001">
                                                <small class="form-text text-muted">Masukkan No KK yang terdiri dari 16
                                                    angka.</small>
                                                @if (old('form_type') === 'tambah')
                                                    @error('no_kk')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>

                                            <div class="row mb-3">
                                                <label for="nama" class="form-label">Nama Lengkap</label>
                                                <input type="text" name="nama" id="nama" maxlength="255"
                                                    required
                                                    value="{{ old('form_type') === 'tambah' ? old('nama') : '' }}"
                                                    class="form-control {{ old('form_type') === 'tambah' && $errors->has('nama') ? 'is-invalid' : '' }}"
                                                    placeholder="Contoh: Rudi Hartono">
                                                <small class="form-text text-muted">Masukkan nama lengkap sesuai
                                                    identitas.</small>
                                                @if (old('form_type') === 'tambah')
                                                    @error('nama')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
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
