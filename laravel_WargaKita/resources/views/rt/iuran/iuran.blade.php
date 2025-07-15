@extends('rt.layouts.app') {{-- Pastikan layout ini benar untuk RT --}}

@section('title', $title) {{-- Pastikan $title dikirim dari controller --}}

@section('content')

    <div id="content">

        {{-- top bar --}}
        @include('rt.layouts.topbar') {{-- Asumsi topbar ada di rt.layouts.topbar --}}

        {{-- top bar end --}}

        <div class="container-fluid ">
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

                {{-- FORM FILTER/SEARCH --}}
                <form action="{{ route('rt_iuran.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2"> {{-- PERBAIKAN ROUTE --}}
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
                        {{-- <select name="rt" class="form-select form-select-sm">
                            <option value="">Semua RT</option>
                            @foreach ($rt as $item)
                                <option value="{{ $item->nomor_rt }}" {{ request('rt') == $item->nomor_rt ? 'selected' : '' }}>
                                    RT {{ $item->nomor_rt }}
                                </option>
                            @endforeach
                        </select> --}}
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        <a href="{{ route('rt_iuran.index') }}" class="btn btn-secondary btn-sm">Reset</a> {{-- PERBAIKAN ROUTE --}}
                    </div>
                </form>

                <!--tabel iuran manual-->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Daftar Iuran RT</h6>
                            <div class="dropdown no-arrow">
                                <!--tombol titik tiga di kanan-->
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="dropdownMenuLink">
                                    <div class="dropdown-header">Data Iuran</div>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#modalTambahIuran">Tambah</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-container">
                                <table class="table table-hover table-sm scroll-table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">NOMINAL</th>
                                            <th scope="col">NAMA IURAN</th>
                                            <th scope="col">TGL TAGIH</th>
                                            <th scope="col">TGL TEMPO</th>
                                            <th scope="col">JENIS</th>
                                            <th scope="col">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($iuran as $item)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>Rp{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                                <td>{{ $item->nama }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->tgl_tagih)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($item->tgl_tempo)->translatedFormat('d F Y') }}
                                                </td>
                                                <td><span
                                                        class="badge bg-secondary">
                                                        Manual
                                                    </span></td>
                                                <td>
                                                    {{-- FORM HAPUS --}}
                                                    <form action="{{ route('rt_iuran.destroy', $item->id) }}" method="POST" {{-- PERBAIKAN ROUTE --}}
                                                        class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                    </form>
                                                    {{-- TOMBOL EDIT --}}
                                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#modalEditIuran{{ $item->id }}">
                                                        Edit
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada data iuran manual.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                <div class="text-muted mb-2">
                                    Menampilkan {{ $iuran->firstItem() ?? '0' }}-{{ $iuran->lastItem() }}
                                    dari total
                                    {{ $iuran->total() }} data
                                </div>

                                <div>
                                    {{ $iuran->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Iuran --}}
    <div class="modal fade" id="modalTambahIuran" tabindex="-1" aria-labelledby="modalTambahIuranLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTambahIuranLabel">Tambah Data Iuran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    {{-- Form Tambah Iuran --}}
                    <form action="{{ route('rt_iuran.store') }}" method="POST" class="p-4"> {{-- PERBAIKAN ROUTE --}}
                        @csrf

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Iuran</label>
                            <input type="text" name="nama" placeholder="Nama Iuran"
                                class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tgl_tagih" class="form-label">Tanggal Tagih</label>
                            <input type="date" name="tgl_tagih"
                                class="form-control @error('tgl_tagih') is-invalid @enderror"
                                value="{{ old('tgl_tagih') }}" required>
                            @error('tgl_tagih')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tgl_tempo" class="form-label">Tanggal Tempo</label>
                            <input type="date" name="tgl_tempo" id="tgl_tempo"
                                class="form-control @error('tgl_tempo') is-invalid @enderror"
                                value="{{ old('tgl_tempo') }}" required>
                            @error('tgl_tempo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jenis" class="form-label">Jenis Iuran</label>
                            <input type="text" class="form-control" value="Manual" disabled>
                            <input type="hidden" name="jenis" value="manual">
                        </div>

                        <div class="mb-3" id="manual-field">
                            <label class="form-label">Nominal</label>
                            <input type="number" name="nominal_manual" class="form-control"
                                placeholder="Masukkan nominal manual" value="{{ old('nominal_manual') }}" required>
                        </div>

                        <hr>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </div>
                    </form>
                    @if ($errors->any() && !request()->has('search') && !request()->has('rt'))
                        <div class="alert alert-danger mt-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modals for Edit Iuran --}}
    @foreach ($iuran as $item)
        <div class="modal fade" id="modalEditIuran{{ $item->id }}" tabindex="-1"
            aria-labelledby="modalEditIuranLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content shadow-lg">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="modalEditIuranLabel{{ $item->id }}">Edit Data Iuran</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Form Edit Iuran --}}
                        <form action="{{ route('rt_iuran.update', $item->id) }}" method="POST" class="p-4"> {{-- PERBAIKAN ROUTE --}}
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Iuran</label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama', $item->nama) }}" placeholder="Nama Iuran">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tgl_tagih" class="form-label">Tanggal Tagih</label>
                                <input type="date" name="tgl_tagih"
                                    class="form-control @error('tgl_tagih') is-invalid @enderror"
                                    value="{{ old('tgl_tagih', $item->tgl_tagih) }}">
                                @error('tgl_tagih')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tgl_tempo" class="form-label">Tanggal Tempo</label>
                                <input type="date" name="tgl_tempo"
                                    class="form-control @error('tgl_tempo') is-invalid @enderror"
                                    value="{{ old('tgl_tempo', $item->tgl_tempo) }}">
                                @error('tgl_tempo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jenis Iuran</label>
                                <input type="text" class="form-control" value="Manual" disabled>
                                <input type="hidden" name="jenis" value="manual">
                            </div>

                            <div id="manualFieldEdit{{ $item->id }}">
                                <label class="form-label">Nominal</label>
                                <input type="number" name="nominal_manual" class="form-control"
                                    placeholder="Masukkan nominal manual"
                                    value="{{ old('nominal_manual', $item->nominal) }}" required>
                            </div>

                            <hr>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning">Update Data</button>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
   <!-- <style>
        /* Pastikan html dan body bisa di-scroll */
        html, body {
            height: 100%; /* Memastikan tinggi minimal 100% dari viewport */
            overflow-x: hidden; /* Mencegah horizontal scrollbar jika tidak diinginkan */
            overflow-y: auto; /* Memungkinkan vertical scrollbar jika konten melebihi tinggi viewport */
            scroll-behavior: smooth; /* Opsional: membuat scrolling lebih halus */
        }

        /* Jika ada elemen utama yang membungkus seluruh konten, pastikan juga bisa di-scroll */
        /* Contoh: jika Anda punya div dengan id="wrapper" atau id="content-wrapper" */
        #wrapper, #content-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Memastikan wrapper minimal setinggi viewport */
            overflow: hidden; /* Biasanya wrapper tidak perlu scroll, biarkan body yang handle */
        }

        /* Pastikan main content area bisa berkembang dan di-scroll */
        #content {
            flex-grow: 1; /* Memungkinkan konten untuk mengisi ruang yang tersedia */
            overflow-y: auto; /* Jika konten di dalam #content melebihi tingginya */
            -webkit-overflow-scrolling: touch; /* Untuk scrolling yang lebih baik di iOS */
        }

        /* Jika Anda menggunakan SB Admin 2, periksa juga CSS bawaannya */
        /* Beberapa class SB Admin 2 mungkin mengatur overflow: hidden */
        /* Anda mungkin perlu menimpanya jika itu yang menyebabkan masalah */
        body.sidebar-toggled #content-wrapper {
            overflow: auto; /* Pastikan konten bisa di-scroll saat sidebar ditoggle */
        }

        /* Tambahkan CSS yang sudah ada sebelumnya di sini */
        @media (min-width: 768px) {
            .sidebar {
                transition: all 0.3s ease;
            }
            .sidebar.toggled {
                width: 100px !important; /* ukuran kecil saat ditutup */
            }
            .sidebar .nav-item .nav-link span {
                transition: opacity 0.3s ease;
            }
        }
    </style>-->
@endsection

@push('scripts')
<script>
    // Tidak ada script JavaScript yang diperlukan untuk toggling field karena hanya ada jenis manual.
    document.addEventListener('DOMContentLoaded', function() {
        // Jika ada logika lain yang perlu dijalankan saat DOM dimuat, tambahkan di sini.
        // Misalnya, inisialisasi Bootstrap modals secara manual jika diperlukan.
    });
</script>
@endpush
