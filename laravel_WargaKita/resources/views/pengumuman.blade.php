@extends('layouts.app')
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

                <form action="{{ route('pengumuman.index') }}" method="GET"
                    class="row row-cols-lg-auto g-2 mb-3 align-items-center mx-2">
                    <div class="input-group">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Pengumuman...">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    <div class="col">
                        <select name="tahun" class="form-select">
                            <option value="">Semua Tahun</option>
                            @foreach ($daftar_tahun as $th)
                                <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>
                                    {{ $th }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select name="bulan" class="form-select">
                            <option value="">Semua Bulan</option>
                            @foreach ($daftar_bulan as $bln)
                                <option value="{{ $bln }}" {{ request('bulan') == $bln ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($bln)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select name="subjek" class="form-select me-2">
                            <option value="">Semua Subjek</option>
                            @foreach ($daftar_subjek as $subjek)
                                <option value="{{ $subjek }}" {{ request('subjek') == $subjek ? 'selected' : '' }}>
                                    {{ $subjek }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary ml-3">Filter</button>
                        <a href="{{ route('pengumuman.index') }}" class="btn btn-secondary mx-3">Reset</a>
                    </div>
                </form>



                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Pengumuman</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="dropdownMenuLink">
                                    <div class="dropdown-header">Pengumuman</div>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#modalTambahPengumuman">Tambah</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <table class="table align-middle table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-3">No</th>
                                        <th scope="col" class="px-3">ID</th>
                                        <th scope="col" class="px-3">JUDUL</th>
                                        <th scope="col" class="px-3">Subjek</th>
                                        <th scope="col" class="px-3">Ringkasan Isi</th>
                                        <th scope="col" class="px-3">TANGGAL</th>
                                        <th scope="col" class="text-center px-3">AKSI</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengumuman as $data)
                                        <tr>
                                            <th scope="row" class="px-3">{{ $loop->iteration }}</th>
                                            <th scope="row" class="px-3">{{ $data->id }}</th>
                                            <td class="px-3">{{ $data->judul }}</td>
                                            <td class="px-3">{{ $data->subjek }}</td>

                                            {{-- Menggunakan Str::limit untuk membatasi panjang teks --}}
                                            <td class="px-3">{{ \Illuminate\Support\Str::limit($data->isi, 50, '...') }}
                                            </td>

                                            {{-- Menggunakan Carbon untuk format hari tanggal bulan dan tahun --}}
                                            <td class="px-3">
                                                {{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('l, d F Y') }}
                                            </td>



                                            <td class="text-center px-3">
                                                {{-- Tombol Aksi: Hapus, Edit, Detail --}}
                                                <form action="{{ route('pengumuman.destroy', $data->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm me-1">Hapus</button>
                                                </form>

                                                <button type="button" class="btn btn-warning btn-sm me-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditPengumuman{{ $data->id }}">
                                                    Edit
                                                </button>

                                                <button type="button" class="btn btn-success btn-sm mt-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalDetailPengumuman{{ $data->id }}">
                                                    Detail
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


                                        <!-- Modal Edit Pengumuman -->
                                        <div class="modal fade" id="modalEditPengumuman{{ $data->id }}"
                                            tabindex="-1" aria-labelledby="modalEditPengumumanLabel{{ $data->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg"> <!-- Ukuran modal disamakan -->
                                                <div class="modal-content shadow-lg">
                                                    <div class="modal-header bg-warning text-white">
                                                        <h5 class="modal-title"
                                                            id="modalEditPengumumanLabel{{ $data->id }}">Edit
                                                            Pengumuman
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <form action="{{ route('pengumuman.update', $data->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body modal-body-scroll px-4">
                                                            <div class="mb-3">
                                                                <label for="judul{{ $data->id }}"
                                                                    class="form-label">Judul</label>
                                                                <input type="text" name="judul"
                                                                    id="judul{{ $data->id }}" required
                                                                    value="{{ $data->judul }}"
                                                                    class="form-control @error('judul') is-invalid @enderror"
                                                                    placeholder="Masukkan Judul Pengumuman">
                                                                @error('judul')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="subjek{{ $data->id }}"
                                                                    class="form-label">Subjek</label>
                                                                <input type="text" name="subjek"
                                                                    id="subjek{{ $data->id }}" required
                                                                    value="{{ $data->subjek }}"
                                                                    class="form-control @error('subjek') is-invalid @enderror"
                                                                    placeholder="Masukkan Subjek">
                                                                @error('subjek')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="isi{{ $data->id }}"
                                                                    class="form-label">Isi</label>
                                                                <textarea name="isi" id="isi{{ $data->id }}" rows="5" required
                                                                    class="form-control @error('isi') is-invalid @enderror" placeholder="Masukkan Isi Pengumuman">{{ $data->isi }}</textarea>
                                                                @error('isi')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="tanggal{{ $data->id }}"
                                                                    class="form-label">Tanggal</label>
                                                                <input type="date" name="tanggal"
                                                                    id="tanggal{{ $data->id }}" required
                                                                    value="{{ \Carbon\Carbon::parse($data->tanggal)->format('Y-m-d') }}"
                                                                    class="form-control @error('tanggal') is-invalid @enderror">

                                                                @error('tanggal')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="id_rt{{ $data->id }}"
                                                                    class="form-label">No RT</label>
                                                                <select name="id_rt" id="id_rt{{ $data->id }}"
                                                                    class="form-control @error('id_rt') is-invalid @enderror"
                                                                    required>
                                                                    @foreach ($rukun_tetangga as $rt)
                                                                        <option value="{{ $rt->id }}"
                                                                            {{ $data->id_rt == $rt->id ? 'selected' : '' }}>
                                                                            RT {{ $rt->nomor_rt }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('id_rt')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
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

                                        <!-- Modal Detail Pengumuman -->
                                        <div class="modal fade" id="modalDetailPengumuman{{ $data->id }}"
                                            tabindex="-1" aria-labelledby="modalDetailLabel{{ $data->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                <div class="modal-content shadow-lg border-0">
                                                    <div class="modal-header bg-info text-white">
                                                        <h5 class="modal-title" id="modalDetailLabel{{ $data->id }}">
                                                            Detail Pengumuman
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <h4 class="mb-3 text-primary">{{ $data->judul }}</h4>

                                                        <p><strong>Subjek:</strong> {{ $data->subjek ?? '-' }}</p>

                                                        <p><strong>RT Tujuan:</strong> RT
                                                            {{ $data->rukuntetangga->nomor_rt ?? '-' }}</p>

                                                        <p><strong>Tanggal:</strong>
                                                            {{ \Carbon\Carbon::parse($data->tanggal)->isoFormat('dddd, D MMMM Y') }}
                                                        </p>

                                                        <hr>

                                                        <div class="mb-3">
                                                            <strong>Isi Pengumuman:</strong>
                                                            <div class="border rounded p-3 bg-light mt-2" style="">
                                                                {{ $data->isi }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-end">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                    </div>
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
                                    Menampilkan {{ $pengumuman->firstItem() ?? '0' }}-{{ $pengumuman->lastItem() }} dari total
                                    {{ $pengumuman->total() }} data
                                </div>

                                <!-- Tombol Pagination -->
                                <div>
                                    {{ $pengumuman->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>

                        <!-- Modal Tambah Pengumuman -->
                        <div class="modal fade" id="modalTambahPengumuman" tabindex="-1"
                            aria-labelledby="modalTambahPengumumanLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content shadow-lg">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="modalTambahPengumumanLabel">Tambah Pengumuman</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{-- Form Tambah Pengumuman --}}
                                        <form action="{{ route('pengumuman.store') }}" method="POST" class="p-4">
                                            @csrf

                                            <div class="row mb-3">
                                                <label for="judul" class="form-label">Judul</label>
                                                <input type="text" name="judul" id="judul" required
                                                    value="{{ old('judul') }}"
                                                    class="form-control @error('judul') is-invalid @enderror"
                                                    placeholder="Masukkan Judul Pengumuman">
                                                <small class="form-text text-muted">Masukkan Judul Pengumuman</small>
                                                @error('judul')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror

                                                <label for="subjek" class="form-label">Subjek</label>
                                                <input type="text" name="subjek" id="subjek"
                                                    value="{{ old('subjek') }}"
                                                    class="form-control @error('subjek') is-invalid @enderror"
                                                    placeholder="Masukkan Subjek">
                                                <small class="form-text text-muted">Masukkan Subjek</small>
                                                @error('subjek')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror


                                                <label for="isi" class="form-label">Isi</label>
                                                <textarea name="isi" id="isi" required
                                                    class="form-control @error('isi') is-invalid @enderror
                                                "
                                                    cols="30" rows="10">{{ old('isi') }}</textarea>
                                                <small class="form-text text-muted">Masukkan Isi</small>
                                                @error('isi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror

                                                <label for="tanggal" class="form-label">Tanggal</label>
                                                <input type="date" name="tanggal" id="tanggal" required
                                                    value="{{ old('tanggal') }}"
                                                    class="form-control @error('tanggal') is-invalid @enderror">
                                                <small class="form-text text-muted">Masukkan Tanggal</small>
                                                @error('tanggal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror

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
