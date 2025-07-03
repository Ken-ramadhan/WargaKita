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

                <!-- Form Filter - Sejajar dan Hemat Ruang -->
                <form method="GET" action="{{ route('kartu_keluarga.index') }}" class="row g-2 align-items-center px-3 pb-2">
                    <!-- Kolom Pencarian -->
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Data Kartu Keluarga...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Filter RT -->
                    <div class="col-md-3 col-sm-6">
                        <select name="rt" class="form-select form-select-sm">
                            <option value="">Semua RT</option>
                            @foreach ($rukun_tetangga as $rt)
                                <option value="{{ $rt->nomor_rt }}" {{ request('rt') == $rt->nomor_rt ? 'selected' : '' }}>
                                    RT {{ $rt->nomor_rt }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="col-md-4 col-sm-6 d-flex gap-2">
                        <button class="btn btn-sm btn-primary">Terapkan</button>
                        <a href="{{ route('kartu_keluarga.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                    </div>
                </form>



                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
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
                                        data-bs-target="#modalTambahKK">Tambah</a>

                                    <div class="dropdown-header">Halaman Warga</div>
                                    <a class="dropdown-item" href="{{ route('warga.index') }}">Manajemen Warga</a>
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
                                                <td>{{ $kk->rw }}</td>
                                                <td>{{ $kk->golongan->nama }}</td>
                                                <td class="text-center align-middle">
                                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                                        <form action="{{ route('kartu_keluarga.destroy', $kk->no_kk) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm">Hapus</button>
                                                        </form>

                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditkk{{ $kk->no_kk }}">
                                                            Edit
                                                        </button>

                                                        <button type="button" class="btn btn-success btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalDetailkk{{ $kk->no_kk }}">
                                                            Detail
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
                                                                <label class="form-label">RW</label>
                                                                <input type="text" name="rw" maxlength="5"
                                                                    required value="{{ old('rw', $kk->rw) }}"
                                                                    class="form-control @error('rw') is-invalid @enderror">
                                                                @error('rw')
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
                                                                <select name="id_golongan"
                                                                    class="form-select @error('id_golongan') is-invalid @enderror"
                                                                    required>
                                                                    <option value="">-- Pilih Kategori --</option>
                                                                    @foreach ($kategori_golongan as $golongan)
                                                                        <option value="{{ $golongan->id }}"
                                                                            {{ $kk->id_golongan == $golongan->id ? 'selected' : '' }}>
                                                                            {{ $golongan->nama }}
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

                                                            <div class="col-md-6">
                                                                <label class="form-label">Jenis KK</label>
                                                                <select name="jenis"
                                                                    class="form-select @error('jenis') is-invalid @enderror"
                                                                    required>
                                                                    <option
                                                                        value="{{ old('jenis', $item->jenis) }}">
                                                                        {{ old('jenis', $item->jenis) }}
                                                                    </option>
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
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title" id="modalDetailkkLabel{{ $kk->no_kk }}">
                                                Detail Kartu Keluarga
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>

                                        <div class="modal-body p-4">
                                            <!-- Informasi Kartu Keluarga -->
                                            <div class="mb-3">
                                                <h6 class="text-success mb-3 fw-bold">Informasi KK</h6>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>No. KK:</strong> {{ $kk->no_kk }}
                                                        </p>
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
                                                        <p class="mb-1"><strong>Alamat:</strong> {{ $kk->alamat }}
                                                        </p>
                                                        <p class="mb-1"><strong>RT/RW:</strong>
                                                            {{ $kk->rukunTetangga->nomor_rt }}/{{ $kk->rw }}</p>
                                                        <p class="mb-1"><strong>kode Pos:</strong>
                                                            {{ $kk->kode_pos }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>Kelurahan:</strong>
                                                            {{ $kk->kelurahan }}</p>
                                                        <p class="mb-1"><strong>Kecamatan:</strong>
                                                            {{ $kk->kecamatan }}</p>
                                                        <p class="mb-1"><strong>Kabupaten:</strong>
                                                            {{ $kk->kabupaten }}</p>
                                                        <p class="mb-1"><strong>Provinsi:</strong>
                                                            {{ $kk->provinsi }}</p>
                                                        <p class="mb-1"><strong>Tanggal Terbit:</strong>
                                                            {{ \Carbon\Carbon::parse($kk->tgl_terbit)->isoFormat('D MMM Y') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Anggota Keluarga -->
                                            <div class="mb-2">
                                                <h6 class="text-success mb-3 fw-bold">Anggota Keluarga</h6>
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
                                                                <th>Nama Ayah</th>
                                                                <th>Nama Ibu</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="small">
                                                            @forelse ($kk->warga as $data)
                                                                <tr>
                                                                    <td class="text-center">{{ $loop->iteration }}
                                                                    </td>
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
                                                                    <td>{{ $data->golongan_darah }}
                                                                    <td>{{ $data->nama_ayah }}
                                                                    <td>{{ $data->nama_ibu }}
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="10" class="text-center text-muted">
                                                                        Belum ada anggota keluarga.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

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
                                                    <label class="form-label">RW</label>
                                                    <input type="text" name="rw" maxlength="5" required
                                                        value="{{ old('form_type') === 'kk_tambah' ? old('rw') : '' }}"
                                                        class="form-control {{ $errors->has('rw') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                                                    @if ($errors->has('rw') && old('form_type') === 'kk_tambah')
                                                        <div class="invalid-feedback">{{ $errors->first('rw') }}</div>
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
                                                        <select name="kategori_golongan"
                                                            class="form-select {{ $errors->has('kategori_golongan') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }} {{ old('kategori_golongan') }}" required>
                                                            <option value="">-- Pilih --</option>
                                                            <option value="kampung"
                                                                {{ old('kategori_golongan') == 'kampung' ? 'selected' : '' }}>
                                                                Kampung</option>
                                                            <option value="kavling"
                                                                {{ old('kategori_golongan') == 'kavling' ? 'selected' : '' }}>
                                                                Kavling</option>

                                                            <option value="kost"
                                                                {{ old('kategori_golongan') == 'kost' ? 'selected' : '' }}>
                                                                Kost</option>

                                                            <option value="kantor"
                                                                {{ old('kategori_golongan') == 'kantor' ? 'selected' : '' }}>
                                                                Kantor</option>

                                                            <option value="kontrakan"
                                                                {{ old('kategori_golongan') == 'kontrakan' ? 'selected' : '' }}>
                                                                Kontrakan</option>

                                                            <option value="umkm"
                                                                {{ old('kategori_golongan') == 'umkm' ? 'selected' : '' }}>
                                                                Umkm</option>

                                                        </select>
                                                        @if ($errors->has('jenis') && old('form_type') === 'kk_tambah')
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('jenis') }}
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
                                                    <div class="col-md-6">
                                                        <label class="form-label">Jenis</label>
                                                        <select name="jenis"
                                                            class="form-select {{ $errors->has('jenis') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }} {{ old('jenis') }}" required>
                                                            <option value="">-- Pilih --</option>
                                                            <option value="penduduk"
                                                                {{ old('jenis') == 'penduduk' ? 'selected' : '' }}>
                                                                Penduduk</option>
                                                            <option value="Pendatang"
                                                                {{ old('jenis_kelamin') == 'Pendatang' ? 'selected' : '' }}>
                                                                Pendatang</option>

                                                        </select>
                                                        @if ($errors->has('jenis') && old('form_type') === 'kk_tambah')
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('jenis') }}
                                                            </div>
                                                        @endif
                                                    </div>
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

                    </div>
                </div>
            </div>



        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->


@endsection
