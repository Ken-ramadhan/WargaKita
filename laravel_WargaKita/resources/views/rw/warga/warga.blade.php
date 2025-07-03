@extends('rw.layouts.app')
@section('title', $title)

@section('content')


    <!-- Main Content -->
    <div id="content">

        {{-- top bar --}}
        @include('rw.layouts.topbar')

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
                                            <th scope="col">JENIS WARGA</th>
                                            <th scope="col">AKSI</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($warga as $item)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $item->nik }}</td>
                                                <td>{{ $item->kartuKeluarga->no_kk }}</td>
                                                <td>{{ strtoupper($item->nama) }}</td>
                                                <td class="text-center">{{ $item->jenis_kelamin }}</td>
                                                <td>{{ $item->tanggal_lahir }}</td>
                                                <td>{{ $item->jenis }}</td>
                                                <td>{{ $item->status_hubungan_dalam_keluarga }}</td>
                                                <td class="text-center align-middle">
                                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                                        <form action="{{ route('warga.destroy', $item->nik) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="fas fa-trash-alt"></i> <!-- Ikon hapus -->
                                                            </button>
                                                        </form>

                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditwarga{{ $item->nik }}">
                                                            <i class="fas fa-edit"></i> <!-- Ikon edit -->
                                                        </button>
                                                    </div>

                                                </td>
                                            </tr>


                                            <!-- Modal Edit Warga -->
                                            <div class="modal fade" id="modalEditwarga{{ $item->nik }}" tabindex="-1"
                                                aria-labelledby="modalEditwargaLabel{{ $item->nik }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
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
                                                            <div class="modal-body px-4 py-4">
                                                                <div class="row g-3">

                                                                    <!-- NIK -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">NIK</label>
                                                                        <input type="text" name="nik"
                                                                            class="form-control"
                                                                            value="{{ $item->nik }}" readonly>
                                                                    </div>

                                                                    <!-- No KK -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Nomor KK</label>
                                                                        <input type="text" name="no_kk"
                                                                            class="form-control"
                                                                            value="{{ $item->no_kk }}" readonly>
                                                                    </div>

                                                                    <!-- Nama -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Nama Lengkap</label>
                                                                        <input type="text" name="nama"
                                                                            class="form-control @error('nama') is-invalid @enderror"
                                                                            value="{{ old('nama', $item->nama) }}"
                                                                            required>
                                                                        @error('nama')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Jenis Kelamin -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Jenis Kelamin</label>
                                                                        <select name="jenis_kelamin"
                                                                            class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                                                            required>
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
                                                                    <div class="col-md-6">
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
                                                                    <div class="col-md-6">
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
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Agama</label>
                                                                        <input type="text" name="agama"
                                                                            class="form-control @error('agama') is-invalid @enderror"
                                                                            value="{{ old('agama', $item->agama) }}"
                                                                            required>
                                                                        @error('agama')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Pendidikan -->
                                                                    <div class="col-md-6">
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
                                                                    <div class="col-md-6">
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

                                                                    <!-- Status Perkawinan -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Status Perkawinan</label>
                                                                        <select name="status_perkawinan"
                                                                            class="form-select @error('status_perkawinan') is-invalid @enderror"
                                                                            required>
                                                                            <option value="status_perkawinan">
                                                                                {{ old('status_perkawinan', $item->status_perkawinan) }}
                                                                            </option>
                                                                            <option value="menikah"
                                                                                {{ old('status_perkawinan', $item->status_perkawinan) == 'menikah' ? 'selected' : '' }}>
                                                                                Menikah</option>
                                                                            <option value="belum menikah"
                                                                                {{ old('status_perkawinan', $item->status_perkawinan) == 'belum_menikah' ? 'selected' : '' }}>
                                                                                Belum Menikah</option>
                                                                            <option value="cerai_hidup"
                                                                                {{ old('status_perkawinan', $item->status_perkawinan) == 'cerai_hidup' ? 'selected' : '' }}>
                                                                                Cerai Hidup</option>
                                                                            <option value="cerai_mati"
                                                                                {{ old('status_perkawinan', $item->status_perkawinan) == 'cerai_mati' ? 'selected' : '' }}>
                                                                                Cerai Mati</option>
                                                                        </select>
                                                                        @error('status_perkawinan')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Hubungan dengan KK -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Hubungan dengan
                                                                            KK</label>
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
                                                                        @error('status_perkawinan')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Golongan Darah -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Golongan Darah</label>
                                                                        <select name="golongan_darah"
                                                                            class="form-select @error('golongan_darah') is-invalid @enderror"
                                                                            required>
                                                                            <option value="">-- Pilih --</option>
                                                                            @foreach (['A', 'B', 'AB', 'O'] as $gd)
                                                                                <option value="{{ $gd }}"
                                                                                    {{ old('golongan_darah', $item->golongan_darah) == $gd ? 'selected' : '' }}>
                                                                                    {{ $gd }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('golongan_darah')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Kewarganegaraan -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Kewarganegaraan</label>
                                                                        <select name="kewarganegaraan"
                                                                            class="form-select @error('kewarganegaraan') is-invalid @enderror"
                                                                            required>
                                                                            <option value="WNI"
                                                                                {{ old('kewarganegaraan', $item->kewarganegaraan) == 'WNI' ? 'selected' : '' }}>
                                                                                WNI</option>
                                                                            <option value="WNA"
                                                                                {{ old('kewarganegaraan', $item->kewarganegaraan) == 'WNA' ? 'selected' : '' }}>
                                                                                WNA</option>
                                                                        </select>
                                                                        @error('kewarganegaraan')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Nama Ayah -->
                                                                    <div class="col-md-6">
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
                                                                    <div class="col-md-6">
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

                                                                    <!-- Jenis Warga -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Jenis Warga</label>
                                                                        <select name="jenis"
                                                                            class="form-select @error('jenis') is-invalid @enderror"
                                                                            required>
                                                                            <option value="penduduk"
                                                                                {{ old('jenis', $item->jenis) == 'penduduk' ? 'selected' : '' }}>
                                                                                Penduduk</option>
                                                                            <option value="pendatang"
                                                                                {{ old('jenis', $item->jenis) == 'pendatang' ? 'selected' : '' }}>
                                                                                Pendatang</option>
                                                                        </select>
                                                                        @error('jenis')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>



                                                                </div>
                                                            </div>

                                                            <div class="modal-footer bg-light border-top-0">
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

                                    <form action="{{ route('warga.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="form_type" value="tambah">
                                        <div class="modal-body px-4" style="max-height: 70vh; overflow-y: auto;">

                                            @php
                                                $oldIfTambah = fn($field) => old('form_type') === 'tambah'
                                                    ? old($field)
                                                    : '';
                                                $errorIfTambah = fn($field) => $errors->has($field) &&
                                                old('form_type') === 'tambah'
                                                    ? 'is-invalid'
                                                    : '';
                                            @endphp

                                            @foreach ([
            'nik' => 'NIK',
            'no_kk' => 'Nomor KK',
            'nama' => 'Nama Lengkap',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'agama' => 'Agama',
            'pendidikan' => 'Pendidikan',
            'pekerjaan' => 'Pekerjaan',
            'nama_ayah' => 'Nama Ayah',
            'nama_ibu' => 'Nama Ibu',
        ] as $field => $label)
                                                <div class="mb-3">
                                                    <label class="form-label">{{ $label }}</label>
                                                    <input type="{{ $field === 'tanggal_lahir' ? 'date' : 'text' }}"
                                                        name="{{ $field }}"
                                                        class="form-control {{ $errorIfTambah($field) }}"
                                                        value="{{ $oldIfTambah($field) }}"
                                                        {{ $field !== 'tanggal_lahir' ? 'maxlength=255' : '' }} required>
                                                    @error($field)
                                                        @if (old('form_type') === 'tambah')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @endif
                                                    @enderror
                                                </div>
                                            @endforeach

                                            <!-- Jenis Kelamin -->
                                            <div class="mb-3">
                                                <label class="form-label">Jenis Kelamin</label>
                                                <select name="jenis_kelamin"
                                                    class="form-select {{ $errorIfTambah('jenis_kelamin') }}" required>
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

                                            <!-- Hubungan Dalam Keluarga -->
                                            <div class="mb-3">
                                                <label class="form-label">Hubungan Dalam Keluarga</label>
                                                <select name="status_hubungan_dalam_keluarga"
                                                    class="form-select {{ $errorIfTambah('status_hubungan_dalam_keluarga') }}"
                                                    required>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="kepala keluarga"
                                                        {{ old('status_hubungan_dalam_keluarga') == 'kepala keluarga' ? 'selected' : '' }}>
                                                        Kepala Keluarga</option>
                                                    <option value="istri"
                                                        {{ old('status_hubungan_dalam_keluarga') == 'istri' ? 'selected' : '' }}>
                                                        Istri</option>
                                                    <option value="anak"
                                                        {{ old('status_hubungan_dalam_keluarga') == 'anak' ? 'selected' : '' }}>
                                                        Anak</option>
                                                </select>
                                                @error('status_hubungan_dalam_keluarga')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                            <!-- Status Perkawinan -->
                                            <div class="mb-3">
                                                <label class="form-label">Status Perkawinan</label>
                                                <select name="status_perkawinan"
                                                    class="form-select {{ $errorIfTambah('status_perkawinan') }}"
                                                    required>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="belum menikah"
                                                        {{ old('status_perkawinan') == 'belum menikah' ? 'selected' : '' }}>
                                                        Belum Menikah</option>
                                                    <option value="menikah"
                                                        {{ old('status_perkawinan') == 'menikah' ? 'selected' : '' }}>
                                                        menikah</option>
                                                    <option value="cerai hidup"
                                                        {{ old('status_perkawinan') == 'cerai hidup' ? 'selected' : '' }}>
                                                        cerai hidup</option>
                                                    <option value="cerai mati"
                                                        {{ old('status_perkawinan') == 'cerai mati' ? 'selected' : '' }}>
                                                        cerai mati</option>
                                                </select>
                                                @error('status_perkawinan')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                            <!-- Golongan Darah -->
                                            <div class="mb-3">
                                                <label class="form-label">Golongan Darah</label>
                                                <select name="golongan_darah"
                                                    class="form-select {{ $errorIfTambah('golongan_darah') }}" required>
                                                    <option value="">-- Pilih --</option>
                                                    @foreach (['A', 'B', 'AB', 'O'] as $gol)
                                                        <option value="{{ $gol }}"
                                                            {{ old('golongan_darah') == $gol ? 'selected' : '' }}>
                                                            {{ $gol }}</option>
                                                    @endforeach
                                                </select>
                                                @error('golongan_darah')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                            <!-- Kewarganegaraan -->
                                            <div class="mb-3">
                                                <label class="form-label">Kewarganegaraan</label>
                                                <select name="kewarganegaraan"
                                                    class="form-select {{ $errorIfTambah('kewarganegaraan') }}" required>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="WNI"
                                                        {{ old('kewarganegaraan') == 'WNI' ? 'selected' : '' }}>
                                                        WNI</option>
                                                    <option value="WNA"
                                                        {{ old('kewarganegaraan') == 'WNA' ? 'selected' : '' }}>
                                                        WNA</option>
                                                </select>
                                                @error('kewarganegaraan')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                            <!-- Jenis Warga -->
                                            <div class="mb-3">
                                                <label class="form-label">Jenis Warga</label>
                                                <select name="jenis" class="form-select {{ $errorIfTambah('jenis') }}"
                                                    required>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="penduduk"
                                                        {{ old('jenis') == 'penduduk' ? 'selected' : '' }}>
                                                        Penduduk</option>
                                                    <option value="pendatang"
                                                        {{ old('jenis') == 'pendatang' ? 'selected' : '' }}>
                                                        Pendatang</option>
                                                </select>
                                                @error('jenis')
                                                    @if (old('form_type') === 'tambah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endif
                                                @enderror
                                            </div>

                                        </div>

                                        <div class="modal-footer bg-light">
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
