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

            <div class="row ">
                <form action="{{ route('kartu_keluarga.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Data Warga...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-7 col-sm-6 d-flex gap-2">
                        <!-- Filter RT -->
                        <div class="col-md-3 col-sm-6">
                            <select name="rt" class="form-select form-select-sm">
                                <option value="">Semua RT</option>
                                @foreach ($rukun_tetangga as $rt)
                                    <option value="{{ $rt->nomor_rt }}"
                                        {{ request('rt') == $rt->nomor_rt ? 'selected' : '' }}>
                                        RT {{ $rt->nomor_rt }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-sm btn-primary">Filter</button>
                        <a href="{{ route('warga.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                    </div>
                </form>



                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">

                        <div class="card-header d-flex align-items-center justify-content-between p-3">
                            {{-- Judul: Hanya judul di header --}}
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Daftar Kartu Keluarga</h6>
                        </div>



                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-1">
                                {{-- Total KK (akan berada di kiri) --}}
                                <div class="d-flex align-items-center gap-1 mb-1 mb-sm-0">
                                    <i class="fas fa-id-card text-primary"></i>
                                    <span class="fw-semibold text-dark">Total: {{ $total_kk }} KK</span>
                                </div>

                                {{-- Tombol tambah (akan berada di kanan) --}}
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTambahKK">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </div>
                            <div class="table-responsive table-container">
                                <table class="table table-hover table-sm scroll-table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">NO</th>
                                            <th scope="col">NO KK</th>
                                            <th scope="col">KEPALA KELUARGA</th>
                                            <th scope="col">ALAMAT</th>
                                            <th scope="col">RT</th>
                                            <th scope="col">RW</th>
                                            <th scope="col">KATEGORI GOLONGAN</th>
                                            <th scope="col" class="text-center">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kartu_keluarga as $kk)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $kk->no_kk }}</td>
                                                <td>
                                                    @php
                                                        $kepala = optional($kk->warga)->firstWhere(
                                                            'status_hubungan_dalam_keluarga',
                                                            'kepala keluarga',
                                                        );
                                                    @endphp
                                                    {{ $kepala->nama ?? '-' }}
                                                </td>
                                                <td>{{ $kk->alamat }}</td>
                                                <td>{{ $kk->rukunTetangga->nomor_rt ?? '-' }}</td>
                                                <td>{{ $kk->rw->nomor_rw }}</td>
                                                <td>{{ $kk->golongan }}</td>
                                                <td class="text-center align-middle">
                                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                                        <form action="{{ route('kartu_keluarga.destroy', $kk->no_kk) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                            @csrf


                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"><i
                                                                    class="fas fa-trash-alt"></i>
                                                                <!-- Ikon hapus --></button>
                                                        </form>

                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditkk{{ $kk->no_kk }}">
                                                            <i class="fas fa-edit"></i> <!-- Ikon edit -->
                                                        </button>

                                                        <button type="button" class="btn btn-success btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalDetailkk{{ $kk->no_kk }}">
                                                            <i class="fas fa-eye"></i>

                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif


                                @foreach ($kartu_keluarga as $kk)
                                    <!-- Modal Edit kartu keluarga -->
                                    <div class="modal fade" id="modalEditkk{{ $kk->no_kk }}" tabindex="-1"
                                        aria-labelledby="modalEditkkLabel{{ $kk->no_kk }}" aria-hidden="true">
                                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                            <div class="modal-content shadow border-0">
                                                <form action="{{ route('kartu_keluarga.update', $kk->no_kk) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header bg-warning text-white">
                                                        <h5 class="modal-title" id="modalEditkkLabel{{ $kk->no_kk }}">
                                                            Edit
                                                            Data Kartu Keluarga</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>

                                                    <input type="hidden" name="redirect_to"
                                                        value="{{ url()->previous() }}">

                                                    <div class="modal-body px-4 py-3"
                                                        style="max-height: 85vh; overflow-y: auto;">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label">Nomor KK</label>
                                                                <input type="text" name="no_kk" maxlength="16"
                                                                    pattern="\d{16}" required
                                                                    value="{{ old('no_kk', $kk->no_kk) }}"
                                                                    class="form-control @error('no_kk') is-invalid @enderror">
                                                                @error('no_kk')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">No RT</label>
                                                                <select name="id_rt"
                                                                    class="form-select @error('id_rt') is-invalid @enderror"
                                                                    required>
                                                                    <option value="">-- Pilih RT --</option>
                                                                    @foreach ($rukun_tetangga as $rt)
                                                                        <option value="{{ $rt->id }}"
                                                                            {{ $kk->id_rt == $rt->id ? 'selected' : '' }}>
                                                                            RT {{ $rt->nomor_rt }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('id_rt')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Kategori Golongan</label>
                                                                <select name="golongan"
                                                                    class="form-select @error('id_golongan') is-invalid @enderror"
                                                                    required>
                                                                    <option value="">-- Pilih Kategori --</option>
                                                                    @foreach ($kategori_golongan as $golongan)
                                                                        <option value="{{ $golongan }}"
                                                                            {{ old('golongan', $kk->golongan) == $golongan ? 'selected' : '' }}>
                                                                            {{ ucfirst($golongan) }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('id_golongan')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-12">
                                                                <label class="form-label">Alamat</label>
                                                                <textarea name="alamat" rows="2" required class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $kk->alamat) }}</textarea>
                                                                @error('alamat')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Kelurahan</label>
                                                                <input type="text" name="kelurahan" maxlength="100"
                                                                    required
                                                                    value="{{ old('kelurahan', $kk->kelurahan) }}"
                                                                    class="form-control @error('kelurahan') is-invalid @enderror">
                                                                @error('kelurahan')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Kecamatan</label>
                                                                <input type="text" name="kecamatan" maxlength="100"
                                                                    required
                                                                    value="{{ old('kecamatan', $kk->kecamatan) }}"
                                                                    class="form-control @error('kecamatan') is-invalid @enderror">
                                                                @error('kecamatan')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Kabupaten/Kota</label>
                                                                <input type="text" name="kabupaten" maxlength="100"
                                                                    required
                                                                    value="{{ old('kabupaten', $kk->kabupaten) }}"
                                                                    class="form-control @error('kabupaten') is-invalid @enderror">
                                                                @error('kabupaten')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Provinsi</label>
                                                                <input type="text" name="provinsi" maxlength="100"
                                                                    required value="{{ old('provinsi', $kk->provinsi) }}"
                                                                    class="form-control @error('provinsi') is-invalid @enderror">
                                                                @error('provinsi')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Kode Pos</label>
                                                                <input type="text" name="kode_pos" maxlength="10"
                                                                    required value="{{ old('kode_pos', $kk->kode_pos) }}"
                                                                    class="form-control @error('kode_pos') is-invalid @enderror">
                                                                @error('kode_pos')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Tanggal Terbit</label>
                                                                <input type="date" name="tgl_terbit" required
                                                                    value="{{ old('tgl_terbit', $kk->tgl_terbit) }}"
                                                                    class="form-control @error('tgl_terbit') is-invalid @enderror">
                                                                @error('tgl_terbit')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer bg-light border-0">
                                                            <button type="submit" class="btn btn-warning text-white">
                                                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                                                            </button>
                                                        </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                            </div>



                            <!-- Modal Detail KK -->
                            <div class="modal fade" id="modalDetailkk{{ $kk->no_kk }}" tabindex="-1"
                                aria-labelledby="modalDetailkkLabel{{ $kk->no_kk }}" aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                    <div class="modal-content shadow border-0">
                                        <!-- Header -->
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title" id="modalDetailkkLabel{{ $kk->no_kk }}">
                                                Detail Kartu Keluarga
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>

                                        <!-- Body -->
                                        <div class="modal-body p-4">
                                            <!-- Informasi KK -->
                                            <div class="mb-4">
                                                <h6 class="text-success mb-3 fw-bold">Informasi Kartu Keluarga</h6>
                                                <div class="row g-4">
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>No. KK:</strong> {{ $kk->no_kk }}</p>
                                                        <p class="mb-1">
                                                            <strong>Kepala Keluarga:</strong>
                                                            @php
                                                                $kepala = $kk->warga->firstWhere(
                                                                    'status_hubungan_dalam_keluarga',
                                                                    'kepala keluarga',
                                                                );
                                                            @endphp
                                                            {{ $kepala->nama ?? '-' }}
                                                        </p>
                                                        <p class="mb-1"><strong>Alamat:</strong> {{ $kk->alamat }}</p>
                                                        <p class="mb-1"><strong>RT/RW:</strong>
                                                            {{ $kk->rukunTetangga->nomor_rt }}/{{ $kk->rw->nomor_rw }}</p>
                                                        <p class="mb-1"><strong>Golongan:</strong> {{ $kk->golongan }}
                                                        </p>
                                                        <p class="mb-1"><strong>Kode Pos:</strong> {{ $kk->kode_pos }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>Kelurahan:</strong> {{ $kk->kelurahan }}
                                                        </p>
                                                        <p class="mb-1"><strong>Kecamatan:</strong> {{ $kk->kecamatan }}
                                                        </p>
                                                        <p class="mb-1"><strong>Kabupaten:</strong> {{ $kk->kabupaten }}
                                                        </p>
                                                        <p class="mb-1"><strong>Provinsi:</strong> {{ $kk->provinsi }}
                                                        </p>
                                                        <p class="mb-1"><strong>Tanggal Terbit:</strong>
                                                            {{ \Carbon\Carbon::parse($kk->tgl_terbit)->isoFormat('D MMM Y') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Anggota Keluarga -->
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="text-success fw-bold mb-0">Anggota Keluarga</h6>
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#modalTambahWarga"
                                                        data-no_kk="{{ $kk->no_kk }}"
                                                        data-nama_ayah="{{ $kk->warga->firstWhere('status_hubungan_dalam_keluarga', 'kepala keluarga')->nama ?? '' }}"
                                                        data-nama_ibu="{{ $kk->warga->firstWhere('status_hubungan_dalam_keluarga', 'istri')->nama ?? '' }}">
                                                        <i class="fas fa-plus"></i> Tambah Warga
                                                    </button>

                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm align-middle text-nowrap">
                                                        <thead class="table-success text-center small">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>NIK</th>
                                                                <th>Nama</th>
                                                                <th>Jenis Kelamin</th>
                                                                <th>TTL</th>
                                                                <th>Agama</th>
                                                                <th>Pendidikan</th>
                                                                <th>Pekerjaan</th>
                                                                <th>Status Kawin</th>
                                                                <th>Hubungan</th>
                                                                <th>Gol Darah</th>
                                                                <th>Jenis Warga</th>
                                                                <th>Nama Ayah</th>
                                                                <th>Nama Ibu</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="small">
                                                            @forelse ($kk->warga->sortByDesc(function($item) {
                                                                                return $item->status_hubungan_dalam_keluarga === 'kepala keluarga';
                                                                                    }) as $data)
                                                                <tr>
                                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                                    <td>{{ $data->nik }}</td>
                                                                    <td>{{ $data->nama }}</td>
                                                                    <td>{{ $data->jenis_kelamin }}</td>
                                                                    <td>{{ $data->tempat_lahir }},
                                                                        {{ \Carbon\Carbon::parse($data->tanggal_lahir)->format('d-m-Y') }}
                                                                    </td>
                                                                    <td>{{ $data->agama }}</td>
                                                                    <td>{{ $data->pendidikan }}</td>
                                                                    <td>{{ $data->pekerjaan }}</td>
                                                                    <td>{{ $data->status_perkawinan }}</td>
                                                                    <td>{{ $data->status_hubungan_dalam_keluarga }}</td>
                                                                    <td>{{ $data->golongan_darah }}</td>
                                                                    <td>{{ $data->jenis }}</td>
                                                                    <td>{{ $data->nama_ayah }}</td>
                                                                    <td>{{ $data->nama_ibu }}</td>
                                                                    <td>
                                                                        <div
                                                                            class="d-flex justify-content-center align-items-center gap-1 flex-nowrap">
                                                                            <form
                                                                                action="{{ route('warga.destroy', $data->nik) }}"
                                                                                method="POST"
                                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                                                @csrf
                                                                                <input type="hidden" name="redirect_to"
                                                                                    value="{{ route('kartu_keluarga.index') }}">

                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="btn btn-sm btn-danger d-flex align-items-center">
                                                                                    <i class="fas fa-trash-alt me-1"></i>
                                                                                </button>
                                                                            </form>

                                                                            <button type="button"
                                                                                class="btn btn-sm btn-warning d-flex align-items-center"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#modalEditwarga{{ $data->nik }}">
                                                                                <i class="fas fa-edit me-1"></i>
                                                                            </button>
                                                                        </div>
                                                                    </td>

                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="14" class="text-center text-muted">
                                                                        Belum ada anggota keluarga.
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Footer -->
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-outline-success"
                                                data-bs-dismiss="modal">
                                                <i class="bi bi-check2-circle"></i> Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @foreach ($warga as $item)
                                <!-- Modal Edit Warga -->
                                <div class="modal fade" id="modalEditwarga{{ $item->nik }}" tabindex="-1"
                                    aria-labelledby="modalEditwargaLabel{{ $item->nik }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                        <div class="modal-content shadow-lg">
                                            <div class="modal-header bg-warning text-white">
                                                <h5 class="modal-title" id="modalEditwargaLabel{{ $item->nik }}">Edit
                                                    Data Warga</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>

                                            <form action="{{ route('warga.update', $item->nik) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="redirect_to"
                                                    value="{{ route('kartu_keluarga.index') }}">



                                                <!-- MODAL BODY YANG BISA SCROLL -->
                                                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                                    <div class="row g-3">

                                                        <!-- Nik -->
                                                        <div class="col-md-6">
                                                            <label class="form-label">NIK</label>
                                                            <input type="text" name="nik" class="form-control"
                                                                value="{{ $item->nik }}" readonly>
                                                        </div>

                                                        <!-- No KK -->
                                                        <div class="col-md-6">
                                                            <label class="form-label">Nomor KK</label>
                                                            <input type="text" name="no_kk" class="form-control"
                                                                value="{{ $item->no_kk }}" readonly>
                                                        </div>

                                                        <!-- Nama Lengkap -->
                                                        <div class="col-md-6">
                                                            <label class="form-label">Nama Lengkap</label>
                                                            <input type="text" name="nama"
                                                                class="form-control @error('nama') is-invalid @enderror"
                                                                value="{{ old('nama', $item->nama) }}" required>
                                                            @error('nama')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Jenis Kelamin -->
                                                        <div class="col-md-6">
                                                            <label class="form-label">Jenis Kelamin</label>
                                                            <select name="jenis_kelamin"
                                                                class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                                                required
                                                                value="{{ old('jenis_kelamin') ?? $item->jenis_kelamin }}">
                                                                <option value="laki-laki"
                                                                    {{ (old('jenis_kelamin') ?? $item->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>
                                                                    Laki-laki
                                                                </option>
                                                                <option value="perempuan"
                                                                    {{ (old('jenis_kelamin') ?? $item->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>
                                                                    Perempuan
                                                                </option>
                                                            </select>
                                                            @error('jenis_kelamin')
                                                                <div class="invalid-feedback">{{ $message }}</div>
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
                                                                <div class="invalid-feedback">{{ $message }}</div>
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
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Agama -->
                                                        <div class="col-md-6">
                                                            <label class="form-label">Agama</label>
                                                            <input type="text" name="agama"
                                                                class="form-control @error('agama') is-invalid @enderror"
                                                                value="{{ old('agama', $item->agama) }}" required>
                                                            @error('agama')
                                                                <div class="invalid-feedback">{{ $message }}</div>
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
                                                                <div class="invalid-feedback">{{ $message }}</div>
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
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Status Perkawinan -->
                                                        <div class="col-md-6">
                                                            <label class="form-label">Status Perkawinan</label>
                                                            <select name="status_perkawinan"
                                                                class="form-select @error('status_perkawinan') is-invalid @enderror"
                                                                required>
                                                                <option value="menikah"
                                                                    {{ old('status_perkawinan', $item->status_perkawinan) == 'menikah' ? 'selected' : '' }}>
                                                                    Menikah</option>
                                                                <option value="belum menikah"
                                                                    {{ old('status_perkawinan', $item->status_perkawinan) == 'belum menikah' ? 'selected' : '' }}>
                                                                    Belum Menikah</option>
                                                                <option value="cerai hidup"
                                                                    {{ old('status_perkawinan', $item->status_perkawinan) == 'cerai hidup' ? 'selected' : '' }}>
                                                                    Cerai Hidup</option>
                                                                <option value="cerai mati"
                                                                    {{ old('status_perkawinan', $item->status_perkawinan) == 'cerai mati' ? 'selected' : '' }}>
                                                                    Cerai Mati</option>
                                                            </select>
                                                            @error('status_perkawinan')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Hubungan dengan KK -->
                                                        <div class="col-md-6">
                                                            <label class="form-label">Hubungan dengan KK</label>
                                                            <select name="status_hubungan_dalam_keluarga"
                                                                class="form-select @error('status_hubungan_dalam_keluarga') is-invalid @enderror"
                                                                required>
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
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Golongan Darah -->
                                                        <div class="col-md-6">
                                                            <label class="form-label">Golongan Darah</label>
                                                            <select name="golongan_darah"
                                                                class="form-select @error('golongan_darah') is-invalid @enderror"
                                                                required>
                                                                @foreach (['A', 'B', 'AB', 'O'] as $gd)
                                                                    <option value="{{ $gd }}"
                                                                        {{ old('golongan_darah', $item->golongan_darah) == $gd ? 'selected' : '' }}>
                                                                        {{ $gd }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('golongan_darah')
                                                                <div class="invalid-feedback">{{ $message }}</div>
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
                                                                <div class="invalid-feedback">{{ $message }}</div>
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
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Nama Ibu -->
                                                        <div class="col-md-6">
                                                            <label class="form-label">Nama Ibu</label>
                                                            <input type="text" name="nama_ibu"
                                                                class="form-control @error('nama_ibu') is-invalid @enderror"
                                                                value="{{ old('nama_ibu', $item->nama_ibu) }}" required>
                                                            @error('nama_ibu')
                                                                <div class="invalid-feedback">{{ $message }}</div>
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
                                                                <div class="invalid-feedback">{{ $message }}</div>
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



                            <!-- Info dan Tombol Pagination Sejajar -->
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                <!-- Info Kustom -->
                                <div class="text-muted mb-2">
                                    Menampilkan
                                    {{ $kartu_keluarga->firstItem() ?? '0' }}-{{ $kartu_keluarga->lastItem() }}
                                    dari total
                                    {{ $kartu_keluarga->total() }} data
                                </div>

                                <!-- Tombol Pagination -->
                                <div>
                                    {{ $kartu_keluarga->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>

                        <!-- Modal Tambah Kartu Keluarga -->
                        <div class="modal fade {{ session('showModal') === 'kk_tambah' ? 'show d-block' : '' }}"
                            id="modalTambahKK" tabindex="-1" aria-labelledby="modalTambahKKLabel"
                            aria-hidden="{{ session('showModal') === 'kk_tambah' ? 'false' : 'true' }}"
                            style="{{ session('showModal') === 'kk_tambah' ? 'background-color: rgba(0,0,0,0.5);' : '' }}">

                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content shadow border-0">
                                    <form action="{{ route('kartu_keluarga.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="form_type" value="kk_tambah">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="modalTambahKKLabel">Tambah Data Kartu Keluarga
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>

                                        <div class="modal-body px-4 py-3" style="max-height: 85vh; overflow-y: auto;">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Nomor KK</label>
                                                    <input type="text" name="no_kk" maxlength="16" pattern="\d{16}"
                                                        required
                                                        value="{{ old('form_type') === 'kk_tambah' ? old('no_kk') : '' }}"
                                                        class="form-control {{ $errors->has('no_kk') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                                                    @if ($errors->has('no_kk') && old('form_type') === 'kk_tambah')
                                                        <div class="invalid-feedback">{{ $errors->first('no_kk') }}</div>
                                                    @endif
                                                </div>


                                                <div class="col-md-6">
                                                    <label class="form-label">No RT</label>
                                                    <select name="id_rt"
                                                        class="form-select @error('id_rt') is-invalid @enderror" required>
                                                        <option value="">-- Pilih RT --</option>
                                                        @foreach ($rukun_tetangga as $rt)
                                                            <option value="{{ $rt->id }}"
                                                                {{ old('id_rt') == $rt->id ? 'selected' : '' }}>
                                                                RT {{ $rt->nomor_rt }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Kategori Golongan</label>
                                                    <select name="golongan"
                                                        class="form-select {{ $errors->has('kategori_golongan') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }} {{ old('kategori_golongan') }}"
                                                        required>
                                                        <option value="">-- Pilih Golongan --</option>
                                                        @foreach ($kategori_golongan as $golongan)
                                                            <option value="{{ $golongan }}"
                                                                {{ old('golongan') == $golongan ? 'selected' : '' }}>
                                                                {{ ucfirst($golongan) }}
                                                            </option>
                                                        @endforeach



                                                    </select>
                                                    @if ($errors->has('kategori_golongan') && old('form_type') === 'kk_tambah')
                                                        <div class="invalid-feedback">
                                                            {{ $errors->first('kategori_golongan') }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="form-label">Alamat</label>
                                                    <textarea name="alamat" rows="2" required
                                                        class="form-control {{ $errors->has('alamat') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">{{ old('form_type') === 'kk_tambah' ? old('alamat') : '' }}</textarea>
                                                    @if ($errors->has('alamat') && old('form_type') === 'kk_tambah')
                                                        <div class="invalid-feedback">{{ $errors->first('alamat') }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Kelurahan</label>
                                                    <input type="text" name="kelurahan" maxlength="100" required
                                                        value="{{ old('form_type') === 'kk_tambah' ? old('kelurahan') : '' }}"
                                                        class="form-control {{ $errors->has('kelurahan') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                                                    @if ($errors->has('kelurahan') && old('form_type') === 'kk_tambah')
                                                        <div class="invalid-feedback">{{ $errors->first('kelurahan') }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Kecamatan</label>
                                                    <input type="text" name="kecamatan" maxlength="100" required
                                                        value="{{ old('form_type') === 'kk_tambah' ? old('kecamatan') : '' }}"
                                                        class="form-control {{ $errors->has('kecamatan') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                                                    @if ($errors->has('kecamatan') && old('form_type') === 'kk_tambah')
                                                        <div class="invalid-feedback">{{ $errors->first('kecamatan') }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Kabupaten/Kota</label>
                                                    <input type="text" name="kabupaten" maxlength="100" required
                                                        value="{{ old('form_type') === 'kk_tambah' ? old('kabupaten') : '' }}"
                                                        class="form-control {{ $errors->has('kabupaten') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                                                    @if ($errors->has('kabupaten') && old('form_type') === 'kk_tambah')
                                                        <div class="invalid-feedback">{{ $errors->first('kabupaten') }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Provinsi</label>
                                                    <input type="text" name="provinsi" maxlength="100" required
                                                        value="{{ old('form_type') === 'kk_tambah' ? old('provinsi') : '' }}"
                                                        class="form-control {{ $errors->has('provinsi') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                                                    @if ($errors->has('provinsi') && old('form_type') === 'kk_tambah')
                                                        <div class="invalid-feedback">{{ $errors->first('provinsi') }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Kode Pos</label>
                                                    <input type="text" name="kode_pos" maxlength="10" required
                                                        value="{{ old('form_type') === 'kk_tambah' ? old('kode_pos') : '' }}"
                                                        class="form-control {{ $errors->has('kode_pos') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                                                    @if ($errors->has('kode_pos') && old('form_type') === 'kk_tambah')
                                                        <div class="invalid-feedback">{{ $errors->first('kode_pos') }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Tanggal Terbit</label>
                                                    <input type="date" name="tgl_terbit" required
                                                        value="{{ old('form_type') === 'kk_tambah' ? old('tgl_terbit') : '' }}"
                                                        class="form-control {{ $errors->has('tgl_terbit') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                                                    @if ($errors->has('tgl_terbit') && old('form_type') === 'kk_tambah')
                                                        <div class="invalid-feedback">{{ $errors->first('tgl_terbit') }}
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>

                                            <div class="modal-footer bg-light border-0 ">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-save me-1"></i> Simpan Kartu Keluarga
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Tambah Warga -->
                        @php
                            $oldIfTambah = fn($field) => old('form_type') === 'tambah' ? old($field) : '';
                            $errorIfTambah = fn($field) => $errors->has($field) && old('form_type') === 'tambah'
                                ? 'is-invalid'
                                : '';
                        @endphp

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
                                        <input type="hidden" name="redirect_to"
                                            value="{{ route('kartu_keluarga.index') }}">
                                        <input type="hidden" name="form_type" value="tambah">

                                        <!-- Nomor KK -->
                                        <input type="hidden" name="no_kk" id="modal_no_kk">

                                        <div class="modal-body px-4" style="max-height: 70vh; overflow-y: auto;">
                                            <!-- No KK -->
                                            <div class="mb-3">
                                                <label class="form-label">Nomor KK</label>
                                                <input type="text" class="form-control" id="modal_no_kk_show"
                                                    value="{{ old('no_kk') }}" readonly>
                                            </div>

                                            <!-- NIK -->
                                            <div class="mb-3">
                                                <label class="form-label">NIK</label>
                                                <input type="text" name="nik" maxlength="16"
                                                    class="form-control {{ $errorIfTambah('nik') }}"
                                                    value="{{ $oldIfTambah('nik') }}" required>
                                                @error('nik')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Nama Lengkap -->
                                            <div class="mb-3">
                                                <label class="form-label">Nama Lengkap</label>
                                                <input type="text" name="nama"
                                                    class="form-control {{ $errorIfTambah('nama') }}"
                                                    value="{{ $oldIfTambah('nama') }}" required>
                                                @error('nama')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Hubungan Dalam Keluarga -->
                                            <div class="mb-3">
                                                <label class="form-label">Hubungan Dalam Keluarga</label>
                                                <select name="status_hubungan_dalam_keluarga" id="hubungan_keluarga"
                                                    class="form-select {{ $errorIfTambah('status_hubungan_dalam_keluarga') }}"
                                                    required>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="kepala keluarga"
                                                        {{ $oldIfTambah('status_hubungan_dalam_keluarga') == 'kepala keluarga' ? 'selected' : '' }}>
                                                        Kepala Keluarga</option>
                                                    <option value="istri"
                                                        {{ $oldIfTambah('status_hubungan_dalam_keluarga') == 'istri' ? 'selected' : '' }}>
                                                        Istri</option>
                                                    <option value="anak"
                                                        {{ $oldIfTambah('status_hubungan_dalam_keluarga') == 'anak' ? 'selected' : '' }}>
                                                        Anak</option>
                                                </select>
                                                @error('status_hubungan_dalam_keluarga')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Nama Ayah -->
                                            <div class="mb-3">
                                                <label class="form-label">Nama Ayah</label>
                                                <input type="text" name="nama_ayah" id="input_nama_ayah"
                                                    class="form-control {{ $errorIfTambah('nama_ayah') }}"
                                                    value="{{ $oldIfTambah('nama_ayah') }}" required>
                                                @error('nama_ayah')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Nama Ibu -->
                                            <div class="mb-3">
                                                <label class="form-label">Nama Ibu</label>
                                                <input type="text" name="nama_ibu" id="input_nama_ibu"
                                                    class="form-control {{ $errorIfTambah('nama_ibu') }}"
                                                    value="{{ $oldIfTambah('nama_ibu') }}" required>
                                                @error('nama_ibu')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Jenis Kelamin -->
                                            <div class="mb-3">
                                                <label class="form-label">Jenis Kelamin</label>
                                                <select name="jenis_kelamin"
                                                    class="form-select {{ $errorIfTambah('jenis_kelamin') }}" required>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="laki-laki"
                                                        {{ $oldIfTambah('jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>
                                                        Laki-laki</option>
                                                    <option value="perempuan"
                                                        {{ $oldIfTambah('jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>
                                                        Perempuan</option>
                                                </select>
                                                @error('jenis_kelamin')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- TTL -->
                                            <div class="mb-3">
                                                <label class="form-label">Tempat Lahir</label>
                                                <input type="text" name="tempat_lahir"
                                                    class="form-control {{ $errorIfTambah('tempat_lahir') }}"
                                                    value="{{ $oldIfTambah('tempat_lahir') }}" required>
                                                @error('tempat_lahir')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Tanggal Lahir</label>
                                                <input type="date" name="tanggal_lahir"
                                                    class="form-control {{ $errorIfTambah('tanggal_lahir') }}"
                                                    value="{{ $oldIfTambah('tanggal_lahir') }}" required>
                                                @error('tanggal_lahir')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Agama -->
                                            <div class="mb-3">
                                                <label class="form-label">Agama</label>
                                                <input type="text" name="agama"
                                                    class="form-control {{ $errorIfTambah('agama') }}"
                                                    value="{{ $oldIfTambah('agama') }}" required>
                                                @error('agama')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Pendidikan -->
                                            <div class="mb-3">
                                                <label class="form-label">Pendidikan</label>
                                                <input type="text" name="pendidikan"
                                                    class="form-control {{ $errorIfTambah('pendidikan') }}"
                                                    value="{{ $oldIfTambah('pendidikan') }}" required>
                                                @error('pendidikan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Pekerjaan -->
                                            <div class="mb-3">
                                                <label class="form-label">Pekerjaan</label>
                                                <input type="text" name="pekerjaan"
                                                    class="form-control {{ $errorIfTambah('pekerjaan') }}"
                                                    value="{{ $oldIfTambah('pekerjaan') }}" required>
                                                @error('pekerjaan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
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
                                                        {{ $oldIfTambah('status_perkawinan') == 'belum menikah' ? 'selected' : '' }}>
                                                        Belum Menikah</option>
                                                    <option value="menikah"
                                                        {{ $oldIfTambah('status_perkawinan') == 'menikah' ? 'selected' : '' }}>
                                                        Menikah</option>
                                                    <option value="cerai hidup"
                                                        {{ $oldIfTambah('status_perkawinan') == 'cerai hidup' ? 'selected' : '' }}>
                                                        Cerai Hidup</option>
                                                    <option value="cerai mati"
                                                        {{ $oldIfTambah('status_perkawinan') == 'cerai mati' ? 'selected' : '' }}>
                                                        Cerai Mati</option>
                                                </select>
                                                @error('status_perkawinan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
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
                                                            {{ $oldIfTambah('golongan_darah') == $gol ? 'selected' : '' }}>
                                                            {{ $gol }}</option>
                                                    @endforeach
                                                </select>
                                                @error('golongan_darah')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Jenis Warga -->
                                            <div class="mb-3">
                                                <label class="form-label">Jenis Warga</label>
                                                <select name="jenis" class="form-select {{ $errorIfTambah('jenis') }}"
                                                    required>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="penduduk"
                                                        {{ $oldIfTambah('jenis') == 'penduduk' ? 'selected' : '' }}>
                                                        Penduduk</option>
                                                    <option value="pendatang"
                                                        {{ $oldIfTambah('jenis') == 'pendatang' ? 'selected' : '' }}>
                                                        Pendatang</option>
                                                </select>
                                                @error('jenis')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Kewarganegaraan -->
                                            <div class="mb-3">
                                                <label class="form-label">Kewarganegaraan</label>
                                                <select name="kewarganegaraan"
                                                    class="form-select {{ $errorIfTambah('kewarganegaraan') }}" required>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="WNI"
                                                        {{ $oldIfTambah('kewarganegaraan') == 'WNI' ? 'selected' : '' }}>
                                                        WNI</option>
                                                    <option value="WNA"
                                                        {{ $oldIfTambah('kewarganegaraan') == 'WNA' ? 'selected' : '' }}>
                                                        WNA</option>
                                                </select>
                                                @error('kewarganegaraan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Hidden Auto Ayah/Ibu -->
                                            <input type="hidden" id="kk_nama_ayah_auto" value="">
                                            <input type="hidden" id="kk_nama_ibu_auto" value="">
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
    <script>
        const modalTambah = document.getElementById('modalTambahWarga');

        modalTambah.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            const noKK = button.getAttribute('data-no_kk');
            const namaAyah = button.getAttribute('data-nama_ayah');
            const namaIbu = button.getAttribute('data-nama_ibu');

            modalTambah.querySelector('#modal_no_kk').value = noKK;
            modalTambah.querySelector('#modal_no_kk_show').value = noKK;
            modalTambah.querySelector('#kk_nama_ayah_auto').value = namaAyah;
            modalTambah.querySelector('#kk_nama_ibu_auto').value = namaIbu;

            const hubungan = modalTambah.querySelector('#hubungan_keluarga');
            const inputAyah = modalTambah.querySelector('#input_nama_ayah');
            const inputIbu = modalTambah.querySelector('#input_nama_ibu');

            hubungan.addEventListener('change', function() {
                if (this.value === 'anak') {
                    inputAyah.value = namaAyah;
                    inputIbu.value = namaIbu;
                } else {
                    inputAyah.value = '';
                    inputIbu.value = '';
                }
            });
        });
        var modalTambahWarga = document.getElementById('modalTambahWarga');
        modalTambahWarga.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget; // Tombol yang memicu modal
            var noKK = button.getAttribute('data-no_kk');

            // Input hidden & input readonly
            var inputHidden = modalTambahWarga.querySelector('#modal_no_kk');
            var inputShow = modalTambahWarga.querySelector('#modal_no_kk_show');

            inputHidden.value = noKK;
            inputShow.value = noKK;
        });
    </script>


@endsection
