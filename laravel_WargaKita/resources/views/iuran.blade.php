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

                    <form method="GET" action="{{ route('iuran.index') }}" class="mb-3 d-flex gap-2">
                        <div class="input-group">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Data Kartu Tagihan...">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Filter Nomor RT -->
                        <select name="rt" class="form-select w-25">
                            <option value="">Semua RT</option>
                            {{-- @foreach ($rukun_tetangga as $rt)
                                <option value="{{ $rt->nomor_rt }}" {{ request('rt') == $rt->nomor_rt ? 'selected' : '' }}>
                                    RT {{ $rt->nomor_rt }}
                                </option>
                            @endforeach --}}
                        </select>

                        <!-- Tombol -->
                        <button class="btn btn-primary">Terapkan</button>
                        <a href="{{ route('iuran.index') }}" class="btn btn-secondary">Reset</a>
                    </form>

                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Daftar Iuran</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="dropdownMenuLink">
                                    <div class="dropdown-header">Data Iuran</div>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#modalTambahTagihan">Tambah</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">NOMINAL</th>
                                        <th scope="col">NAMA IURAN</th>
                                        <th scope="col">TGL TAGIH</th>
                                        <th scope="col">TGL_TEMPO</th>
                                        <th scope="col">KATEGORI GOLONGAN</th>
                                        <th scope="col">JENIS</th>
                                        <th scope="col">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     {{-- @foreach ($kartu_keluarga as $kk) --}}
                                        {{-- <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $kk->no_kk }}</td>
                                            <td>{{ $kk->rukunTetangga->nomor_rt ?? '-' }}</td>
                                            <td>{{ $kk->kepala_kk }}</td>

                                            <td>
                                                <form action="{{ route('kartu_keluarga.destroy', $kk->no_kk) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditkk{{ $kk->no_kk }}">
                                                    Edit
                                                </button>
                                            </td>

                                        </tr> --}}

                                        {{-- <tr>
                                            @if (session('error'))
                                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    {{ session('error') }}
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                </div>
                                            @endif
                                        </tr> --}}

                                        <!-- Modal Edit kartu keluarga -->
                                        {{-- <div class="modal fade" id="modalEditkk{{ $kk->no_kk }}" tabindex="-1"
                                            aria-labelledby="modalEditKKLabel{{ $kk->no_kk }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content shadow-lg">
                                                    <div class="modal-header bg-warning text-white">
                                                        <h5 class="modal-title" id="modalEditRTLabel{{ $kk->no_kk }}">
                                                            Edit Nomor KK
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <form action="{{ route('kartu_keluarga.update', $kk->no_kk) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="no_kk{{ $kk->no_kk }}"
                                                                    class="form-label">Nomor KK</label>
                                                                <input type="text" name="no_kk"
                                                                    class="form-control @error('no_kk') is-invalid @enderror"
                                                                    value="{{ old('no_kk', $kk->no_kk) }}" required>
                                                                @error('no_kk')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror


                                                                <label for="nomor_rt{{ $kk->nomor_rt }}"
                                                                    class="form-label">Nomor RT</label>
                                                                <select name="id_rt" class="form-control">
                                                                    @foreach ($rukun_tetangga as $rt)
                                                                        <option value="{{ $rt->id }}"
                                                                            {{ $kk->id_rt == $rt->id ? 'selected' : '' }}>
                                                                            RT {{ $rt->nomor_rt }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                                <div class="row mb-3">
                                                                    <label for="kepala_kk" class="form-label">Kepala
                                                                        KK</label>
                                                                    <input type="text" name="kepala_kk" id="kepala_kk"
                                                                        class="form-control" value="{{ $kk->kepala_kk }}"
                                                                        required>
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
                                        </div> --}}
                                    {{-- @endforeach --}}

                                </tbody>
                            </table>
                            <!-- Info dan Tombol Pagination Sejajar -->
                            {{-- <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                <!-- Info Kustom -->
                                <div class="text-muted mb-2">
                                    Menampilkan {{ $kartu_keluarga->firstItem() ?? '0' }}-{{ $kartu_keluarga->lastItem() }}
                                    dari total
                                    {{ $kartu_keluarga->total() }} data
                                </div>

                                <!-- Tombol Pagination -->
                                <div>
                                    {{ $kartu_keluarga->links('pagination::bootstrap-5') }}
                                </div>
                            </div> --}}
                        </div>

                        <!-- Modal Tambah kartu keluarga -->
                         <div class="modal fade" id="modalTambahWarga" tabindex="-1"
                            aria-labelledby="modalTambahWargaLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content shadow-lg">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="modalTambahWargaLabel">Tambah Data Kartu keluarga</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{-- Form Tambah Warga --}}
                                        <form action="{{ route('tagihan.store') }}" method="POST"
                                            class="p-4">
                                            @csrf

                                            <!-- Input No KK -->
                                            {{-- <div class="row mb-3">
                                                <label for="no_kk" class="form-label">No KK</label>
                                                <input type="text" name="no_kk" id="no_kk"
                                                    class="form-control @error('no_kk') is-invalid @enderror"
                                                    value="{{ old('no_kk') }}" required>
                                                @error('no_kk')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div> --}}

                                            <!-- Dropdown No RT -->
                                            {{-- <div class="row mb-3">
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
                                            </div> --}}


                                            {{-- <div class="row mb-3">
                                                <label for="kepala_kk" class="form-label">Kepala KK</label>
                                                <input type="text" name="kepala_kk" id="kepala_kk"
                                                    class="form-control @error('kepala_kk') is-invalid @enderror"
                                                    value="{{ old('kepala_kk') }}" required>
                                                @error('kepala_kk')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div> --}}

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
