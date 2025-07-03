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

                <form action="{{ route('iuran.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Data Pengeluaran...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 d-flex gap-2">
                        <select name="rt" class="form-select form-select-sm">
                            <option value="">Semua RT</option>
                            {{-- @foreach ($rukun_tetangga as $rt)
                                <option value="{{ $rt->nomor_rt }}" {{ request('rt') == $rt->nomor_rt ? 'selected' : '' }}>
                                    RT {{ $rt->nomor_rt }}
                                </option>
                            @endforeach --}}
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        <a href="{{ route('iuran.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                    </div>
                </form>


                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
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
                                        data-bs-target="#modalTambahIuran">Tambah</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="table-responsive table-container">
                            <table class="table table-hover table-sm scroll-table text-nowrap">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">NOMINAL</th>
                                        <th scope="col">NAMA IURAN</th>
                                        <th scope="col">TGL TAGIH</th>
                                        <th scope="col">TGL_TEMPO</th>
                                        <th scope="col">JENIS</th>
                                        <th scope="col">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($iuran as $item)
                                    @if ($item->jenis == 'manual')
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>Rp{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tgl_tagih)->translatedFormat('d F Y') }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($item->tgl_tempo)->translatedFormat('d F Y') }}
                                            </td>
                                            <td><span
                                                    class="badge bg-{{ $item->jenis == 'otomatis' ? 'primary' : 'secondary' }}">
                                                    {{ ucfirst($item->jenis) }}
                                                </span></td>

                                            <td>
                                                <form action="{{ route('iuran.destroy', $item->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditIuran{{ $item->id }}">
                                                    Edit
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
                                        @else
                                        @endif
                                    @endforeach

                                </tbody>
                            </table>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <!-- Info dan Tombol Pagination Sejajar -->
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                <!-- Info Kustom -->
                                <div class="text-muted mb-2">
                                    Menampilkan {{ $iuran->firstItem() ?? '0' }}-{{ $iuran->lastItem() }}
                                    dari total
                                    {{ $iuran->total() }} data
                                </div>

                                <!-- Tombol Pagination -->
                                <div>
                                    {{ $iuran->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>

                        <!-- Modal Tambah Iuran -->
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
                                        <form action="{{ route('iuran.store') }}" method="POST" class="p-4">
                                            @csrf

                                            <!-- Input No KK -->
                                            <div class="row mb-3">
                                                <label for="nama" class="form-label">Nama Iuran</label>
                                                <input type="text" name="nama" placeholder="Nama Iuran"
                                                    class="form-control @error('nama') is-invalid @enderror"
                                                    value="{{ old('nama') }}">
                                                @error('nama')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>


                                            <div class="row mb-3">
                                                <label for="tgl_tagih" class="form-label">Tanggal Tagih</label>
                                                <input type="date" name="tgl_tagih"
                                                    class="form-control @error('tgl_tagih') is-invalid @enderror"
                                                    value="{{ old('tgl_tagih') }}" required>
                                                @error('tgl_tagih')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row mb-3">
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
                                                <select name="jenis" id="jenis" onchange="toggleNominalFields()"
                                                    required class="form-select">
                                                    <option value="otomatis"
                                                        {{ old('jenis') === 'otomatis' ? 'selected' : '' }}>Otomatis
                                                    </option>
                                                    <option value="manual"
                                                        {{ old('jenis') === 'manual' ? 'selected' : '' }}>Manual</option>

                                                </select>
                                            </div>

                                            <div id="otomatis-fields" class="mb-3">
                                                @foreach ($kategori_golongan as $golongan)
                                                    <label class="form-label">{{ $golongan->nama }}</label>
                                                    <input type="number" name="nominal[{{ $golongan->id }}]"
                                                        class="form-control">
                                                @endforeach

                                            </div>
                                            <div class="mb-3" id="manual-field" style="display: none;">
                                                <label class="form-label">Nominal</label>
                                                <input type="number" name="nominal_manual" class="form-control"
                                                    placeholder="Masukkan nominal">
                                            </div>

                                            <hr>

                                            <!-- Tombol Submit -->
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Simpan Data</button>
                                            </div>

                                        </form>
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
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

                    </div>
                </div>

                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Daftar Iuran otomatis</h6>
                            <div class="dropdown no-arrow">
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
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="table-responsive table-container">
                            <table class="table table-hover table-sm scroll-table-text-nowrap">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        @foreach ($kategori_golongan as $golongan)
                                            <th scope="col">{{ $golongan->nama }}</th>
                                        @endforeach
                                        <th scope="col">NAMA IURAN</th>
                                        <th scope="col">TGL TAGIH</th>
                                        <th scope="col">TGL_TEMPO</th>
                                        <th scope="col">JENIS</th>
                                        <th scope="col">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($iuran as $item)
                                    @if ($item->jenis == 'otomatis')
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $item->iuran_golongan->nominal }}</td>
                                            <td></td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tgl_tagih)->translatedFormat('d F Y') }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($item->tgl_tempo)->translatedFormat('d F Y') }}
                                            </td>
                                            <td><span
                                                    class="badge bg-{{ $item->jenis == 'otomatis' ? 'primary' : 'secondary' }}">
                                                    {{ ucfirst($item->jenis) }}
                                                </span></td>

                                            <td>
                                                <form action="{{ route('iuran.destroy', $item->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditIuran{{ $item->id }}">
                                                    Edit
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
                                        @else
                                        @endif
                                    @endforeach

                                </tbody>
                            </table>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <!-- Info dan Tombol Pagination Sejajar -->
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                <!-- Info Kustom -->
                                <div class="text-muted mb-2">
                                    Menampilkan {{ $iuran->firstItem() ?? '0' }}-{{ $iuran->lastItem() }}
                                    dari total
                                    {{ $iuran->total() }} data
                                </div>

                                <!-- Tombol Pagination -->
                                <div>
                                    {{ $iuran->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>

                        <!-- Modal Tambah Iuran -->
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
                                        <form action="{{ route('iuran.store') }}" method="POST" class="p-4">
                                            @csrf

                                            <!-- Input No KK -->
                                            <div class="row mb-3">
                                                <label for="nama" class="form-label">Nama Iuran</label>
                                                <input type="text" name="nama" placeholder="Nama Iuran"
                                                    class="form-control @error('nama') is-invalid @enderror"
                                                    value="{{ old('nama') }}">
                                                @error('nama')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>


                                            <div class="row mb-3">
                                                <label for="tgl_tagih" class="form-label">Tanggal Tagih</label>
                                                <input type="date" name="tgl_tagih"
                                                    class="form-control @error('tgl_tagih') is-invalid @enderror"
                                                    value="{{ old('tgl_tagih') }}" required>
                                                @error('tgl_tagih')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row mb-3">
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
                                                <select name="jenis" id="jenis" onchange="toggleNominalFields()"
                                                    required class="form-select">
                                                    <option value="otomatis"
                                                        {{ old('jenis') === 'otomatis' ? 'selected' : '' }}>Otomatis
                                                    </option>
                                                    <option value="manual"
                                                        {{ old('jenis') === 'manual' ? 'selected' : '' }}>Manual</option>

                                                </select>
                                            </div>

                                            <div id="otomatis-fields" class="mb-3">
                                                @foreach ($kategori_golongan as $golongan)
                                                    <label class="form-label">{{ $golongan->nama }}</label>
                                                    <input type="number" name="nominal[{{ $golongan->id }}]"
                                                        class="form-control">
                                                @endforeach

                                            </div>
                                            <div class="mb-3" id="manual-field" style="display: none;">
                                                <label class="form-label">Nominal</label>
                                                <input type="number" name="nominal_manual" class="form-control"
                                                    placeholder="Masukkan nominal">
                                            </div>

                                            <hr>

                                            <!-- Tombol Submit -->
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Simpan Data</button>
                                            </div>

                                        </form>
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
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

                    </div>
                </div>
            </div>



        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <script>
        function toggleNominalFields() {
        const jenis = document.getElementById('jenis').value;

        const otomatisFields = document.getElementById('otomatis-fields');
        const manualField = document.getElementById('manual-field');
        const manualInput = manualField.querySelector('input[name="nominal"]');

        if (jenis === 'otomatis') {
            otomatisFields.style.display = 'block';
            manualField.style.display = 'none';
            manualInput.removeAttribute('required'); // 🛠️ Hapus required saat disembunyi
        } else {
            otomatisFields.style.display = 'none';
            manualField.style.display = 'block';
            manualInput.setAttribute('required', true); // 🛠️ Tambahkan required saat tampil
        }
    }

    // Jalankan saat load awal (untuk isian old input)
    document.addEventListener("DOMContentLoaded", function () {
        toggleNominalFields();
    });
    </script>

@endsection
