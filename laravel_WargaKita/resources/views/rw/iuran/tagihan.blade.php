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
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Daftar Iuran Manual</h6>
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
                                        {{-- Menggunakan $iuran (yang sudah difilter manual dari controller) --}}
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
                                                    </span></td> {{-- Selalu Manual --}}
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
                                    {{-- Menggunakan pagination umum --}}
                                    {{ $iuran->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hapus bagian "Tabel Iuran Otomatis" ini --}}
                {{-- <div class="col-xl-12 col-lg-7">
                    ... (Konten tabel otomatis yang sudah dihapus) ...
                </div> --}}
            </div>
        </div>
        
    
        {{-- Modals for Edit --}}
        {{-- Kita hanya perlu modal edit untuk iuran manual --}}
        @foreach ($iuran as $item) {{-- Loop melalui $iuran yang sudah difilter manual --}}
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
                        <form action="{{ route('iuran.update', $item->id) }}" method="POST" class="p-4">
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
                                {{-- Karena hanya manual, tidak perlu select, cukup hidden input atau teks biasa --}}
                                <input type="text" class="form-control" value="Manual" disabled>
                                <input type="hidden" name="jenis" value="manual">
                            </div>

                            {{-- Hanya tampilkan field nominal manual --}}
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

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Iuran</label>
                            <input type="text" name="nama" placeholder="Nama Iuran"
                                class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}">
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
                            {{-- Karena hanya manual, tidak perlu select, cukup hidden input atau teks biasa --}}
                            <input type="text" class="form-control" value="Manual" disabled>
                            <input type="hidden" name="jenis" value="manual">
                        </div>

                        {{-- Hanya tampilkan field nominal manual --}}
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
                    @if ($errors->any() && !request()->has('search') && !request()->has('rt')) {{-- Only show errors for the add form if not filtering/searching --}}
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
    </div>

@endsection

@push('scripts')
{{-- Hapus semua skrip JavaScript terkait toggleNominalFields dan toggleEditFields --}}
<script>
    // Tidak ada script JavaScript yang diperlukan untuk toggling field karena hanya ada jenis manual.
    // Pastikan DOM sudah dimuat sebelum menjalankan script
    document.addEventListener('DOMContentLoaded', function() {
        // Jika ada logika lain yang perlu dijalankan saat DOM dimuat, tambahkan di sini.
        // Misalnya, inisialisasi Bootstrap modals secara manual jika diperlukan.
    });
</script>
@endpush
