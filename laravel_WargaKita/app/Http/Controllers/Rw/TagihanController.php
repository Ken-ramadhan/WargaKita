    @extends('rw.layouts.app')

    @section('title', $title)

    @section('content')

        <div id="content">

            {{-- top bar --}}
            @include('rw.layouts.topbar')

            {{-- top bar end --}}

            <div class="container-fluid">
                <div class="row">

                    {{-- Session messages --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('iuran.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                        <div class="col-md-5 col-sm-12">
                            <div class="input-group input-group-sm">
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                    placeholder="Cari Data Iuran...">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 d-flex gap-2">
                            <select name="rt" class="form-select form-select-sm">
                                <option value="">Semua RT</option>
                                @foreach ($rt as $item)
                                    <option value="{{ $item->nomor_rt }}" {{ request('rt') == $item->nomor_rt ? 'selected' : '' }}>
                                        RT {{ $item->nomor_rt }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                            <a href="{{ route('iuran.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                        </div>
                    </form>

                    <!--tabel iuran manual-->
                    <div class="col-xl-12 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Tabel Daftar Tagihan</h6> {{-- Ubah judul --}}
                                <div class="dropdown no-arrow">
                                    <!--tombol titik tiga di kanan-->
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Data Tagihan</div> {{-- Ubah teks --}}
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#modalTambahTagihan">Tambah</a> {{-- Ubah target modal --}}
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-container">
                                    <table class="table table-hover table-sm scroll-table text-nowrap">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Nama Tagihan</th> {{-- Sesuaikan header --}}
                                                <th scope="col">Nominal</th> {{-- Sesuaikan header --}}
                                                <th scope="col">Status</th> {{-- Tambahkan/sesuaikan header --}}
                                                <th scope="col">Tanggal Dibuat</th> {{-- Sesuaikan header --}}
                                                <th scope="col">AKSI</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- Menggunakan $tagihan, bukan $iuran --}}
                                            @forelse ($tagihan as $item)
                                                <tr>
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td>{{ $item->nama_tagihan ?? 'N/A' }}</td> {{-- Sesuaikan dengan kolom tagihan Anda --}}
                                                    <td>Rp{{ number_format($item->nominal ?? 0, 0, ',', '.') }}</td> {{-- Sesuaikan dengan kolom tagihan Anda --}}
                                                    <td><span class="badge bg-info">{{ $item->status ?? 'N/A' }}</span></td> {{-- Sesuaikan dengan kolom tagihan Anda --}}
                                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</td> {{-- Sesuaikan dengan kolom tagihan Anda --}}
                                                    <td>
                                                        <form action="{{ route('tagihan.destroy', $item->id) }}" method="POST" {{-- Ubah route --}}
                                                            class="d-inline"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                        </form>
                                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#modalEditTagihan{{ $item->id }}"> {{-- Ubah target modal --}}
                                                            Edit
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">Tidak ada data tagihan.</td> {{-- Sesuaikan colspan --}}
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                    <div class="text-muted mb-2">
                                        Menampilkan {{ $tagihan->firstItem() ?? '0' }}-{{ $tagihan->lastItem() }}
                                        dari total
                                        {{ $tagihan->total() }} data
                                    </div>

                                    <div>
                                        {{-- Menggunakan pagination umum --}}
                                        {{ $tagihan->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Hapus bagian "Tabel Iuran Otomatis" jika ada di file ini --}}
                    {{-- <div class="col-xl-12 col-lg-7">
                        ... (Konten tabel otomatis yang sudah dihapus) ...
                    </div> --}}
                </div>
            </div>
        </div>

        {{-- Modals for Edit Tagihan (sesuaikan dengan kolom Tagihan Anda) --}}
        @foreach ($tagihan as $item)
            <div class="modal fade" id="modalEditTagihan{{ $item->id }}" tabindex="-1" {{-- Ubah ID modal --}}
                aria-labelledby="modalEditTagihanLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content shadow-lg">
                        <div class="modal-header bg-warning text-white">
                            <h5 class="modal-title" id="modalEditTagihanLabel{{ $item->id }}">Edit Data Tagihan</h5> {{-- Ubah judul modal --}}
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            {{-- Form Edit Tagihan --}}
                            <form action="{{ route('tagihan.update', $item->