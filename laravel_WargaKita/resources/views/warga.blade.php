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
                <form action="{{ route('warga.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Data Warga...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 d-flex gap-2">
                        <select name="jenis_kelamin" class="form-select form-select-sm" id="">
                            <option value="">Jenis Kelamin</option>
                            <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                Laki-laki</option>
                            <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                Perempuan</option>
                        </select>
                        <button class="btn btn-sm btn-primary">Terapkan</button>
                        <a href="{{ route('warga.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                    </div>
                </form>


                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
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
                            <div class="table-responsive table-container">
                                <table class="table table-hover table-sm scroll-table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">NO</th>
                                            <th scope="col">NIK</th>
                                            <th scope="col">NO KK</th>
                                            <th scope="col">NAMA</th>
                                            <th scope="col">JENIS KELAMIN</th>
                                            <th scope="col">TANGGAL LAHIR</th>
                                            <th scope="col">HUBUNGAN</th>
                                            <th scope="col" class="text-center">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($warga as $item)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $item->nik }}</td>
                                                <td>{{ $item->kartuKeluarga->no_kk }}</td>
                                                <td>{{ $item->nama }}</td>
                                                <td>{{ $item->jenis_kelamin }}</td>
                                                <td>{{ $item->tanggal_lahir }}</td>
                                                <td>{{ $item->status_hubungan_dalam_keluarga }}</td>
                                                <td class="text-center align-middle">
                                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                                        <form action="{{ route('warga.destroy', $item->nik) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm">Hapus</button>
                                                        </form>

                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditwarga{{ $item->nik }}">
                                                            Edit
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tr>

                                            <!-- Modal Edit Warga -->
                                            <div class="modal fade" id="modalEditwarga{{ $item->nik }}" tabindex="-1"
                                                aria-labelledby="modalEditwargaLabel{{ $item->nik }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content shadow-lg">
                                                        <div class="modal-header bg-warning text-white">
                                                            <h5 class="modal-title"
                                                                id="modalEditwargaLabel{{ $item->nik }}">Edit Data Warga
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                        </div>

                                                        <form action="{{ route('warga.update', $item->nik) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body p-4">
                                                                <!-- NIK -->
                                                                <div class="mb-3">
                                                                    <label class="form-label">NIK</label>
                                                                    <input type="text" name="nik"
                                                                        class="form-control" value="{{ $item->nik }}"
                                                                        readonly>
                                                                </div>

                                                                <!-- No KK -->
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nomor KK</label>
                                                                    <input type="text" name="no_kk"
                                                                        class="form-control" value="{{ $item->no_kk }}"
                                                                        readonly>
                                                                </div>

                                                                <!-- Nama -->
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nama Lengkap</label>
                                                                    <input type="text" name="nama"
                                                                        class="form-control @error('nama') is-invalid @enderror"
                                                                        value="{{ old('nama', $item->nama) }}" required>
                                                                    @error('nama')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <!-- Jenis Kelamin -->
                                                                <div class="mb-3">
                                                                    <label class="form-label">Jenis Kelamin</label>
                                                                    <select name="jenis_kelamin"
                                                                        class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                                                        required>
                                                                        <option value="">-- Pilih --</option>
                                                                        <option value="Laki-laki"
                                                                            {{ old('jenis_kelamin', $item->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                                                                            Laki-laki</option>
                                                                        <option value="Perempuan"
                                                                            {{ old('jenis_kelamin', $item->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                                                                            Perempuan</option>
                                                                    </select>
                                                                    @error('jenis_kelamin')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>


                                                                <!-- Tempat Lahir -->
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tempat Lahir</label>
                                                                    <input type="text" name="tempat_lahir"
                                                                        class="form-control @error('tempat_lahir') is-invalid @enderror"
                                                                        value="{{ old('tempat_lahir', $item->tempat_lahir) }}"
                                                                        required>
                                                                    @error('tempat_lahir')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <!-- Tanggal Lahir -->
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tanggal Lahir</label>
                                                                    <input type="date" name="tanggal_lahir"
                                                                        class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                                                        value="{{ old('tanggal_lahir', $item->tanggal_lahir) }}"
                                                                        required>
                                                                    @error('tanggal_lahir')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <!-- Agama -->
                                                                <div class="mb-3">
                                                                    <label class="form-label">Agama</label>
                                                                    <input type="text" name="agama"
                                                                        class="form-control @error('agama') is-invalid @enderror"
                                                                        value="{{ old('agama', $item->agama) }}" required>
                                                                    @error('agama')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <!-- Pendidikan -->
                                                                <div class="mb-3">
                                                                    <label class="form-label">Pendidikan</label>
                                                                    <input type="text" name="pendidikan"
                                                                        class="form-control @error('pendidikan') is-invalid @enderror"
                                                                        value="{{ old('pendidikan', $item->pendidikan) }}"
                                                                        required>
                                                                    @error('pendidikan')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <!-- Pekerjaan -->
                                                                <div class="mb-3">
                                                                    <label class="form-label">Pekerjaan</label>
                                                                    <input type="text" name="pekerjaan"
                                                                        class="form-control @error('pekerjaan') is-invalid @enderror"
                                                                        value="{{ old('pekerjaan', $item->pekerjaan) }}"
                                                                        required>
                                                                    @error('pekerjaan')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <!-- Status Kawin -->
                                                                <div class="mb-3">
                                                                    <label class="form-label">Status Perkawinan</label>
                                                                    <input type="text" name="status_perkawinan"
                                                                        class="form-control @error('status_perkawinan') is-invalid @enderror"
                                                                        value="{{ old('status_perkawinan', $item->status_perkawinan) }}"
                                                                        required>
                                                                    @error('status_perkawinan')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                {{-- hubungan --}}
                                                                <div class="mb-4">
                                                                    <label class="form-label">Hubungan dengan KK</label>
                                                                    <select name="status_hubungan_dalam_keluarga"
                                                                        class="form-select @error('status_hubungan_dalam_keluarga') is-invalid @enderror"
                                                                        required>
                                                                        <option value="">-- Pilih --</option>
                                                                        <option value="kepala keluarga"
                                                                            {{ old('status_hubungan_dalam_keluarga', $item->status_hubungan_dalam_keluarga) == 'kepala keluarga' ? 'selected' : '' }}>
                                                                            Kepala Keluarga</option>
                                                                        <option value="istri"
                                                                            {{ old('status_hubungan_dalam_keluarga', $item->status_hubungan_dalam_keluarga) == 'istri' ? 'selected' : '' }}>
                                                                            Istri</option>
                                                                        <option value="anak"
                                                                            {{ old('status_hubungan_dalam_keluarga', $item->status_hubungan_dalam_keluarga) == 'anak' ? 'selected' : '' }}>
                                                                            Anak</option>
                                                                    </select>
                                                                    @error('status_hubungan_dalam_keluarga')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                {{-- Golongan Darah --}}
                                                                <div class="mb-4">
                                                                    <label class="form-label">Golongan Darah</label>
                                                                    <select name="golongan_darah"
                                                                        class="form-select @error('golongan_darah') is-invalid @enderror"
                                                                        required>
                                                                        <option value="">-- Pilih --</option>
                                                                        @foreach (['A', 'B', 'AB', 'O'] as $gd)
                                                                            <option value="{{ $gd }}"
                                                                                {{ old('golongan_darah', $item->golongan_darah) == $gd ? 'selected' : '' }}>
                                                                                {{ $gd }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('golongan_darah')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <!-- Nama Ayah -->
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nama Ayah</label>
                                                                    <input type="text" name="nama_ayah"
                                                                        class="form-control @error('nama_ayah') is-invalid @enderror"
                                                                        value="{{ old('nama_ayah', $item->nama_ayah) }}"
                                                                        required>
                                                                    @error('nama_ayah')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <!-- Nama Ibu -->
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nama Ibu</label>
                                                                    <input type="text" name="nama_ibu"
                                                                        class="form-control @error('nama_ibu') is-invalid @enderror"
                                                                        value="{{ old('nama_ibu', $item->nama_ibu) }}"
                                                                        required>
                                                                    @error('nama_ibu')
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
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 px-4">
                            <!-- Info Kustom -->
                            <div class="text-muted mb-2">
                                Menampilkan {{ $warga->firstItem() ?? '0' }}-{{ $warga->lastItem() }} dari total
                                {{ $warga->total() }} data
                            </div>

                            <!-- Tombol Pagination -->
                            <div>
                                {{ $warga->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                        <!-- Modal Tambah Warga -->
                        <div class="modal fade {{ session('showModal') === 'tambah' ? 'show d-block' : '' }}"
                            id="modalTambahWarga" tabindex="-1" aria-labelledby="modalTambahWargaLabel"
                            aria-hidden="{{ session('showModal') === 'tambah' ? 'false' : 'true' }}"
                            style="{{ session('showModal') === 'tambah' ? 'background-color: rgba(0,0,0,0.5);' : '' }}">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content shadow-lg">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="modalTambahWargaLabel">Tambah Data Warga</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body ">
                                        {{-- Form Tambah Warga --}}
                                        <form action="{{ route('warga.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="form_type" value="tambah">

                                            <div class="row mb-3">
                                                <label class="form-label">NIK</label>
                                                <input type="text" name="nik" maxlength="16" pattern="\d{16}"
                                                    required value="{{ old('form_type') === 'tambah' ? old('nik') : '' }}"
                                                    class="form-control {{ $errors->has('nik') && old('form_type') === 'tambah' ? 'is-invalid' : '' }}">
                                                @error('nik')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                            <div class="row mb-3">
                                                <label class="form-label">Nomor KK</label>
                                                <input type="text" name="no_kk" maxlength="16" pattern="\d{16}"
                                                    required
                                                    value="{{ old('form_type') === 'tambah' ? old('no_kk') : '' }}"
                                                    class="form-control {{ $errors->has('no_kk') && old('form_type') === 'tambah' ? 'is-invalid' : '' }}">
                                                @error('no_kk')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                            <div class="row mb-3">
                                                <label class="form-label">Nama Lengkap</label>
                                                <input type="text" name="nama" maxlength="255" required
                                                    value="{{ old('form_type') === 'tambah' ? old('nama') : '' }}"
                                                    class="form-control {{ $errors->has('nama') && old('form_type') === 'tambah' ? 'is-invalid' : '' }}">
                                                @error('nama')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                            <div class="row mb-3">
                                                <label class="form-label">Jenis Kelamin</label>
                                                <select name="jenis_kelamin"
                                                    class="form-select {{ $errors->has('jenis_kelamin') && old('form_type') === 'tambah' ? 'is-invalid' : '' }}"
                                                    required>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="Laki-laki"
                                                        {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                                        Laki-laki</option>
                                                    <option value="Perempuan"
                                                        {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                                        Perempuan</option>
                                                </select>
                                                @error('jenis_kelamin')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                            <div class="row mb-3">
                                                <label class="form-label">Tempat Lahir</label>
                                                <input type="text" name="tempat_lahir" maxlength="255" required
                                                    value="{{ old('form_type') === 'tambah' ? old('tempat_lahir') : '' }}"
                                                    class="form-control {{ $errors->has('tempat_lahir') && old('form_type') === 'tambah' ? 'is-invalid' : '' }}">
                                                @error('tempat_lahir')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                            <div class="row mb-3">
                                                <label class="form-label">Tanggal Lahir</label>
                                                <input type="date" name="tanggal_lahir" required
                                                    value="{{ old('form_type') === 'tambah' ? old('tanggal_lahir') : '' }}"
                                                    class="form-control {{ $errors->has('tanggal_lahir') && old('form_type') === 'tambah' ? 'is-invalid' : '' }}">
                                                @error('tanggal_lahir')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                            <div class="row mb-3">
                                                <label class="form-label">Agama</label>
                                                <input type="text" name="agama" maxlength="100" required
                                                    value="{{ old('form_type') === 'tambah' ? old('agama') : '' }}"
                                                    class="form-control {{ $errors->has('agama') && old('form_type') === 'tambah' ? 'is-invalid' : '' }}">
                                                @error('agama')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                            <div class="row mb-3">
                                                <label class="form-label">Pendidikan</label>
                                                <input type="text" name="pendidikan" maxlength="100" required
                                                    value="{{ old('form_type') === 'tambah' ? old('pendidikan') : '' }}"
                                                    class="form-control {{ $errors->has('pendidikan') && old('form_type') === 'tambah' ? 'is-invalid' : '' }}">
                                                @error('pendidikan')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                            <div class="row mb-3">
                                                <label class="form-label">Pekerjaan</label>
                                                <input type="text" name="pekerjaan" maxlength="100" required
                                                    value="{{ old('form_type') === 'tambah' ? old('pekerjaan') : '' }}"
                                                    class="form-control {{ $errors->has('pekerjaan') && old('form_type') === 'tambah' ? 'is-invalid' : '' }}">
                                                @error('pekerjaan')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                            <div class="row mb-3">
                                                <label class="form-label">Status Perkawinan</label>
                                                <input type="text" name="status_perkawinan" maxlength="100" required
                                                    value="{{ old('form_type') === 'tambah' ? old('status_perkawinan') : '' }}"
                                                    class="form-control {{ $errors->has('status_perkawinan') && old('form_type') === 'tambah' ? 'is-invalid' : '' }}">
                                                @error('status_perkawinan')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                            <div class="row mb-4">
                                                <label class="form-label">Hubungan Dalam Keluarga</label>
                                                <select name="status_hubungan_dalam_keluarga"
                                                    class="form-select {{ $errors->has('status_hubungan_dalam_keluarga') && old('form_type') === 'tambah' ? 'is-invalid' : '' }}"
                                                    required>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="kepala keluarga"
                                                        {{ old('status_hubungan_dalam_keluarga') == 'Kepala Keluarga' ? 'selected' : '' }}>
                                                        Kepala Keluarga</option>
                                                    <option value="istri"
                                                        {{ old('status_hubungan_dalam_keluarga') == 'Istri' ? 'selected' : '' }}>
                                                        Istri
                                                    </option>
                                                    <option value="anak"
                                                        {{ old('status_hubungan_dalam_keluarga') == 'Anak' ? 'selected' : '' }}>
                                                        Anak
                                                    </option>
                                                </select>
                                                @error('status_hubungan_dalam_keluarga')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                            <div class="row mb-4">
                                                <label class="form-label">Golongan Darah</label>
                                                <select name="golongan_darah"
                                                    class="form-select {{ $errors->has('golongan_darah') && old('form_type') === 'tambah' ? 'is-invalid' : '' }}"
                                                    required>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="A"
                                                        {{ old('golongan_darah') == 'A' ? 'selected' : '' }}>A</option>
                                                    <option value="B"
                                                        {{ old('golongan_darah') == 'B' ? 'selected' : '' }}>B</option>
                                                    <option value="AB"
                                                        {{ old('golongan_darah') == 'AB' ? 'selected' : '' }}>AB</option>
                                                    <option value="O"
                                                        {{ old('golongan_darah') == 'O' ? 'selected' : '' }}>O</option>

                                                </select>
                                                @error('golongan_darah')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                            <div class="row mb-3">
                                                <label class="form-label">Nama Ayah</label>
                                                <input type="text" name="nama_ayah" maxlength="100" required
                                                    value="{{ old('form_type') === 'tambah' ? old('nama_ayah') : '' }}"
                                                    class="form-control {{ $errors->has('nama_ayah') && old('form_type') === 'tambah' ? 'is-invalid' : '' }}">
                                                @error('nama_ayah')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                            <div class="row mb-3">
                                                <label class="form-label">Nama Ibu</label>
                                                <input type="text" name="nama_ibu" maxlength="100" required
                                                    value="{{ old('form_type') === 'tambah' ? old('nama_ibu') : '' }}"
                                                    class="form-control {{ $errors->has('nama_ibu') && old('form_type') === 'tambah' ? 'is-invalid' : '' }}">
                                                @error('nama_ibu')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
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

    @if (session('open_edit_modal'))
        <script>
            var modalId = 'modalEditwarga{{ session('open_edit_modal') }}';
            var modal = new bootstrap.Modal(document.getElementById(modalId));
            modal.show();
        </script>
    @endif


@endsection
