@extends('rw.layouts.app')
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
        @include('rw.layouts.topbar')

        {{-- top bar end --}}

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Content Row -->

            <div class="row">

                <form action="{{ route('pengumuman.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Data Warga...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 d-flex gap-2">
                        <select name="tahun" class="form-select form-select-sm">
                            <option value="">Semua Tahun</option>
                            @foreach ($daftar_tahun as $th)
                                <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>
                                    {{ $th }}</option>
                            @endforeach
                        </select>
                        <select name="bulan" class="form-select form-select-sm">
                            <option value="">Semua Bulan</option>
                            @foreach ($daftar_bulan as $bln)
                                <option value="{{ $bln }}" {{ request('bulan') == $bln ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($bln)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                        <select name="kategori" class="form-select form-select-sm">
                            <option value="">Semua kategori</option>
                            @foreach ($daftar_kategori as $kategori)
                                <option value="{{ $kategori }}" {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                    {{ $kategori }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        <a href="{{ route('pengumuman.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                    </div>
                </form>


                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
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
                            <div class="table-responsive table-container">
                                <table class="table table-sm scroll-table text-nowrap table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th scope="col">NO</th>
                                            {{-- <th scope="col">ID</th> --}}
                                            <th scope="col">JUDUL</th>
                                            <th scope="col">kategori</th>
                                            <th scope="col">RINGKASAN ISI</th>
                                            <th scope="col">TANGGAL</th>
                                            <th scope="col" class="text-center">AKSI</th>

                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @foreach ($pengumuman as $data)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                {{-- <th scope="row">{{ $data->id }}</th> --}}
                                                <td>{{ $data->judul }}</td>
                                                <td>{{ $data->kategori }}</td>

                                                {{-- Menggunakan Str::limit untuk membatasi panjang teks --}}
                                                <td class="px-3">
                                                    {{ \Illuminate\Support\Str::limit($data->isi, 50, '...') }}
                                                </td>

                                                {{-- Menggunakan Carbon untuk format hari tanggal bulan dan tahun --}}
                                                <td class="px-3">
                                                    {{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('l, d F Y') }}
                                                </td>



                                                <td class="text-center px-3 align-item-center">
                                                    {{-- Tombol Aksi: Hapus, Edit, Detail --}}
                                                    <form action="{{ route('pengumuman.destroy', $data->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                    </form>

                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditPengumuman{{ $data->id }}">
                                                        Edit
                                                    </button>

                                                    <button type="button" class="btn btn-success btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalDetailPengumuman{{ $data->id }}">
                                                        Detail
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


                                            <!-- Modal Edit Pengumuman -->
                                            <div class="modal fade" id="modalEditPengumuman{{ $data->id }}"
                                                tabindex="-1"
                                                aria-labelledby="modalEditPengumumanLabel{{ $data->id }}"
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
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="kategori{{ $data->id }}"
                                                                        class="form-label">kategori</label>
                                                                    <input type="text" name="kategori"
                                                                        id="kategori{{ $data->id }}" required
                                                                        value="{{ $data->kategori }}"
                                                                        class="form-control @error('kategori') is-invalid @enderror"
                                                                        placeholder="Masukkan kategori">
                                                                    @error('kategori')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="isi{{ $data->id }}"
                                                                        class="form-label">Isi</label>
                                                                    <textarea name="isi" id="isi{{ $data->id }}" rows="5" required
                                                                        class="form-control @error('isi') is-invalid @enderror" placeholder="Masukkan Isi Pengumuman">{{ $data->isi }}</textarea>
                                                                    @error('isi')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
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

                                            <!-- Modal Detail Pengumuman -->
                                            <div class="modal fade" id="modalDetailPengumuman{{ $data->id }}"
                                                tabindex="-1" aria-labelledby="modalDetailLabel{{ $data->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                    <div class="modal-content shadow-lg border-0">
                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title mb-0"
                                                                id="modalDetailLabel{{ $data->id }}">
                                                                Detail Pengumuman
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                        </div>
                                                        <div class="modal-body px-4 pt-4 pb-3">
                                                            <h4 class="fw-bold text-success mb-3">{{ $data->judul }}</h4>

                                                            <ul class="list-unstyled mb-3 small">
                                                                <li><strong>kategori:</strong> <span
                                                                        class="ms-1">{{ $data->kategori ?? '-' }}</span>
                                                                </li>
                                                                
                                                                <li><strong>Tanggal:</strong> <span
                                                                        class="ms-1">{{ \Carbon\Carbon::parse($data->tanggal)->isoFormat('dddd, D MMMM Y') }}</span>
                                                                </li>
                                                            </ul>

                                                            <hr class="my-2">

                                                            <div class="mb-2">
                                                                <strong class="d-block mb-1">Isi Pengumuman:</strong>
                                                                <div class="border rounded bg-light p-3"
                                                                    style=" line-height: 1.6;">
                                                                    {{ $data->isi }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="modal-footer bg-light border-0 justify-content-end py-2">
                                                            <button type="button" class="btn btn-outline-success"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
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
                                Menampilkan {{ $pengumuman->firstItem() ?? '0' }}-{{ $pengumuman->lastItem() }} dari total
                                {{ $pengumuman->total() }} data
                            </div>

                            <!-- Tombol Pagination -->
                            <div>
                                {{ $pengumuman->links('pagination::bootstrap-5') }}
                            </div>
                        </div>

                        <!-- Modal Tambah Pengumuman -->
                        <div class="modal fade" id="modalTambahPengumuman" tabindex="-1"
                            aria-labelledby="modalTambahPengumumanLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content shadow-lg">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="modalTambahPengumumanLabel">Tambah Pengumuman</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>

                                    <form action="{{ route('pengumuman.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-body px-4" style="max-height: 70vh; overflow-y: auto;">
                                            <div class="mb-3">
                                                <label for="judul" class="form-label">Judul</label>
                                                <input type="text" name="judul" id="judul" required
                                                    value="{{ old('judul') }}"
                                                    class="form-control @error('judul') is-invalid @enderror"
                                                    placeholder="Masukkan Judul Pengumuman">
                                                @error('judul')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="kategori" class="form-label">kategori</label>
                                                <input type="text" name="kategori" id="kategori"
                                                    value="{{ old('kategori') }}"
                                                    class="form-control @error('kategori') is-invalid @enderror"
                                                    placeholder="Masukkan kategori">
                                                @error('kategori')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="isi" class="form-label">Isi</label>
                                                <textarea name="isi" id="isi" rows="5" required
                                                    class="form-control @error('isi') is-invalid @enderror" placeholder="Masukkan Isi Pengumuman">{{ old('isi') }}</textarea>
                                                @error('isi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="tanggal" class="form-label">Tanggal</label>
                                                <input type="date" name="tanggal" id="tanggal" required
                                                    value="{{ old('tanggal') }}"
                                                    class="form-control @error('tanggal') is-invalid @enderror">
                                                @error('tanggal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="modal-footer">
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

@endsection
