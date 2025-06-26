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

                <div class="row">
                    <form action="{{ route('pengeluaran.index') }}" method="GET" class="row g-3 mb-4 align-items-end">
                        <div class="col-12 col-md-4">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Data Pengeluaran...">
                        </div>
                        <div class="col-6 col-md-2">
                            <select name="tahun" class="form-select">
                                <option value="">Semua Tahun</option>
                                @foreach ($daftar_tahun as $th)
                                    <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>
                                        {{ $th }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <select name="bulan" class="form-select">
                                <option value="">Semua Bulan</option>
                                @foreach ($daftar_bulan as $bln)
                                    <option value="{{ $bln }}" {{ request('bulan') == $bln ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($bln)->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <select name="rt" class="form-select">
                                <option value="">Semua RT</option>
                                @foreach ($rukun_tetangga as $rt)
                                    <option value="{{ $rt->nomor_rt }}"
                                        {{ request('rt') == $rt->nomor_rt ? 'selected' : '' }}>
                                        RT {{ $rt->nomor_rt }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-2 d-grid">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                        <div class="col-12 col-md-auto d-grid">
                            <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>



                    <!-- Area Chart -->
                    <div class="col-xl-12 col-lg-7">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Tabel Daftar pengeluaran</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Tambah Data Pengeluaran</div>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#modalTambahPengeluaran">Tambah</a>
                                        <a href="{{ route('pengeluaran_bulanan', ['bulan' => strtolower(now()->translatedFormat('F')), 'tahun' => now()->year]) }}"
                                            class="dropdown-item">
                                            Laporan Bulan Ini
                                        </a>

                                    </div>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>NO</th>
                                                <th>RT</th>
                                                <th>Nama</th>
                                                <th>Jumlah</th>
                                                <th>Tanggal</th>
                                                <th>Keterangan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengeluaran as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>RT {{ $data->rukunTetangga->nomor_rt }}</td>
                                                    <td>{{ $data->nama_pengeluaran }}</td>
                                                    <td>Rp {{ number_format($data->jumlah, 2, ',', '.') }}</td>
                                                    <td>{{ $data->tanggal }}</td>
                                                    <td>{{ $data->keterangan }}</td>
                                                    <td class="d-flex gap-1 flex-wrap">
                                                        <form action="{{ route('pengeluaran.destroy', $data->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm">Hapus</button>
                                                        </form>
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditPengeluaran{{ $data->id }}">
                                                            Edit
                                                        </button>

                                                    </td>
                                                </tr>
                                                <!-- Modal Edit kartu keluarga -->
                                                <div class="modal fade" id="modalEditPengeluaran{{ $data->id }}"
                                                    tabindex="-1"
                                                    aria-labelledby="modalEditPengeluaranLabel{{ $data->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content shadow-lg">
                                                            <div class="modal-header bg-warning text-white">
                                                                <h5 class="modal-title"
                                                                    id="modalEditRTLabel{{ $data->id }}">
                                                                    Edit Data Pengeluaran
                                                                </h5>
                                                                <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                            </div>
                                                            <form action="{{ route('pengeluaran.update', $data->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">

                                                                        <label for="nomor_rt{{ $data->nomor_rt }}"
                                                                            class="form-label">Nomor RT</label>
                                                                        <select name="id_rt" class="form-control">
                                                                            @foreach ($rukun_tetangga as $rt)
                                                                                <option value="{{ $rt->id }}"
                                                                                    {{ $data->id_rt == $rt->id ? 'selected' : '' }}>
                                                                                    RT {{ $rt->nomor_rt }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>

                                                                        <div class="row mb-3">
                                                                            <label for="nama_pengeluaran"
                                                                                class="form-label">Nama
                                                                                Pengeluaran</label>
                                                                            <input type="text" name="nama_pengeluaran"
                                                                                id="nama_pengeluaran" class="form-control"
                                                                                value="{{ $data->nama_pengeluaran }}"
                                                                                required>
                                                                        </div>

                                                                        <div class="row mb-3">
                                                                            <label for="jumlah"
                                                                                class="form-label">Jumlah
                                                                                Pengeluaran</label>
                                                                            <input type="text" name="jumlah"
                                                                                id="jumlah" class="form-control"
                                                                                value="{{ $data->jumlah }}" required>
                                                                        </div>

                                                                        <div class="row mb-3">
                                                                            <label for="tanggal"
                                                                                class="form-label">Tanggal
                                                                                Pengeluaran</label>
                                                                            <input type="date" name="tanggal"
                                                                                id="tanggal" class="form-control"
                                                                                value="{{ $data->tanggal }}" required>
                                                                        </div>

                                                                        <div class="row mb-3">
                                                                            <label for="keterangan"
                                                                                class="form-label">Keterangan</label>
                                                                            <input type="text" name="keterangan"
                                                                                id="keterangan" class="form-control"
                                                                                value="{{ $data->keterangan }}">
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
                                </div>

                                <!-- Info dan Tombol Pagination Sejajar -->
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                    <!-- Info Kustom -->
                                    <div class="text-muted mb-2">
                                        Menampilkan {{ $pengeluaran->firstItem() ?? '0' }}-{{ $pengeluaran->lastItem() }}
                                        dari total
                                        {{ $pengeluaran->total() }} data
                                    </div>

                                    <!-- Tombol Pagination -->
                                    <div>
                                        {{ $pengeluaran->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Tambah Pengeluaran -->
                            <div class="modal fade" id="modalTambahPengeluaran" tabindex="-1"
                                aria-labelledby="modalTambahPengeluaranLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content shadow-lg">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="modalTambahPengeluaranLabel">Tambah Data
                                                Pengeluaran
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{-- Form Tambah Pengeluaran --}}
                                            <form action="{{ route('pengeluaran.store') }}" method="POST"
                                                class="p-4">
                                                @csrf

                                                <!-- Dropdown No RT -->
                                                <div class="row mb-3">
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


                                                <div class="row mb-3">
                                                    <label for="nama_pengeluaran" class="form-label">Nama
                                                        Pengeluaran</label>
                                                    <input type="text" name="nama_pengeluaran" id="nama_pengeluaran"
                                                        class="form-control @error('nama_pengeluaran') is-invalid @enderror"
                                                        value="{{ old('nama_pengeluaran') }}" required>
                                                    @error('nama_pengeluaran')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="jumlah" class="form-label">Jumlah Pengeluaran</label>
                                                    <input type="text" name="jumlah" id="jumlah"
                                                        class="form-control @error('jumlah') is-invalid @enderror"
                                                        value="{{ old('jumlah') }}" required>
                                                    @error('jumlah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="tanggal" class="form-label">Tanggal Pengeluaran</label>
                                                    <input type="date" name="tanggal" id="tanggal"
                                                        class="form-control @error('tanggal') is-invalid @enderror"
                                                        value="{{ old('tanggal') }}" required>
                                                    @error('tanggal')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="tanggal" class="form-label">Keterangan</label>
                                                    <input type="text" name="keterangan" id="keterangan"
                                                        class="form-control @error('keterangan') is-invalid @enderror"
                                                        value="{{ old('keterangan') }}">
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

        @if ($errors->any() && old('_token'))
            {{-- gunakan old token untuk memastikan dari form POST --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const modal = new bootstrap.Modal(document.getElementById('modalTambahPengeluaran'));
                    modal.show();
                });
            </script>
        @endif


    @endsection
