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
                                placeholder="Cari Data Iuran..."> {{-- Changed "Pengeluaran" to "Iuran" --}}
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 d-flex gap-2">
                        <select name="rt" class="form-select form-select-sm">
                            <option value="">Semua RT</option>
                            @foreach ($rt as $item)
                                <option value="{{ $item->nomor_rt }}"
                                    {{ request('rt') == $item->nomor_rt ? 'selected' : '' }}>
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
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahIuran">
                            <i class="fas fa-plus"></i> tambah
                        </button>
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
                                        @forelse ($iuran->where('jenis', 'manual') as $item)
                                            {{-- Filter here for manual --}}
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
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal"
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

                <!-- Tabel Iuran Otomatis -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Daftar Iuran Otomatis</h6>
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
                                            {{-- Kolom nominal per golongan --}}
                                            @foreach ($golongan_list as $golongan)
                                                <th scope="col">Nominal {{ ucfirst($golongan) }}</th>
                                            @endforeach
                                            {{-- Hapus kolom NOMINAL umum di sini --}}
                                            <th scope="col">NAMA IURAN</th>
                                            <th scope="col">TGL TAGIH</th>
                                            <th scope="col">TGL TEMPO</th>
                                            <th scope="col">JENIS</th>
                                            <th scope="col">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($iuran->where('jenis', 'otomatis') as $item)
                                            {{-- Filter here for automatic --}}
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                {{-- Menampilkan nominal per golongan --}}
                                                @foreach ($golongan_list as $golongan)
                                                    <td>
                                                        {{-- Pastikan relasi iuran_golongan di-load di controller --}}
                                                        {{-- Gunakan firstWhere() untuk mencari langsung berdasarkan golongan_id --}}
                                                        Rp{{ number_format($item->iuran_golongan->firstWhere('golongan', $golongan)->nominal ?? 0, 0, ',', '.') }}
                                                    </td>
                                                @endforeach
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
                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm">Hapus</button>
                                                    </form>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditIuran{{ $item->id }}">
                                                        Edit
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                {{-- Hitung colspan yang benar: 5 kolom tetap + jumlah kategori_golongan --}}
                                                <td colspan="{{ 5 + count($golongan_list) }}" class="text-center">Tidak
                                                    ada data iuran otomatis.</td>
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

    {{-- Modals for Edit --}}
   
    

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
                    <form action="{{ route('iuran.store') }}" method="POST" class="p-3">
                        @csrf

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Iuran</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="tgl_tagih" class="form-label">Tanggal Tagih</label>
                            <input type="date" name="tgl_tagih" class="form-control" value="{{ old('tgl_tagih') }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="tgl_tempo" class="form-label">Tanggal Tempo</label>
                            <input type="date" name="tgl_tempo" class="form-control" value="{{ old('tgl_tempo') }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="jenis" class="form-label">Jenis Iuran</label>
                            <select name="jenis" id="jenis" class="form-control" required>
                                <option value="manual">Manual</option>
                                <option value="otomatis">Otomatis</option>
                            </select>
                        </div>

                        {{-- Input untuk Manual --}}
                        <div class="mb-3" id="manualFields">
                            <label for="nominal_manual" class="form-label">Nominal Manual</label>
                            <input type="number" name="nominal" class="form-control"
                                placeholder="Masukkan nominal manual">
                        </div>

                        {{-- Input untuk Otomatis --}}
                        <div id="otomatisFields" style="display: none;">
                            <label class="form-label">Nominal per Golongan</label>
                            @foreach ($golongan_list as $golongan)
                                <div class="mb-2">
                                    <label for="nominal_{{ $golongan }}">Golongan {{ ucfirst($golongan) }}</label>
                                    <input type="number" name="nominal_{{ $golongan }}" class="form-control"
                                        placeholder="Masukkan nominal untuk golongan {{ $golongan }}">
                                </div>
                            @endforeach
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </div>
                    </form>

                    {{-- Tampilkan error validasi --}}
                    @if ($errors->any())
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
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ======================
        // === Tambah Iuran ====
        // ======================
        function toggleNominalFields() {
            const jenisSelect = document.getElementById('jenis');
            const otomatisFields = document.getElementById('otomatisFields');
            const manualField = document.getElementById('manualFields');

            if (!jenisSelect || !otomatisFields || !manualField) return;

            const isOtomatis = jenisSelect.value === 'otomatis';

            otomatisFields.style.display = isOtomatis ? 'block' : 'none';
            manualField.style.display = isOtomatis ? 'none' : 'block';

            // Handle field requirements
            otomatisFields.querySelectorAll('input[type="number"]').forEach(input => {
                input.required = isOtomatis;
                if (!isOtomatis) input.value = '';
            });

            const manualInput = manualField.querySelector('input[type="number"]');
            if (manualInput) {
                manualInput.required = !isOtomatis;
                if (isOtomatis) manualInput.value = '';
            }
        }

        // ======================
        // === Edit Iuran ======
        // ======================
        function toggleEditFields(itemId) {
            const jenisSelect = document.getElementById('jenisEdit' + itemId);
            const otomatisField = document.getElementById('otomatisFieldsEdit' + itemId);
            const manualField = document.getElementById('manualFieldEdit' + itemId);

            if (!jenisSelect || !otomatisField || !manualField) return;

            const isOtomatis = jenisSelect.value === 'otomatis';

            otomatisField.style.display = isOtomatis ? 'block' : 'none';
            manualField.style.display = isOtomatis ? 'none' : 'block';

            // Atur input otomatis
            otomatisField.querySelectorAll('input[type="number"]').forEach(input => {
                input.required = isOtomatis;
                if (!isOtomatis) input.value = '';
            });

            // Atur input manual
            const manualInput = manualField.querySelector('input[type="number"]');
            if (manualInput) {
                manualInput.required = !isOtomatis;
                if (isOtomatis) manualInput.value = '';
            }
        }

        // ======================
        // === Inisialisasi ====
        // ======================
        const jenisAddSelect = document.getElementById('jenis');
        if (jenisAddSelect) {
            toggleNominalFields();
            jenisAddSelect.addEventListener('change', toggleNominalFields);

            const tambahIuranModal = document.getElementById('modalTambahIuran');
            if (tambahIuranModal) {
                tambahIuranModal.addEventListener('shown.bs.modal', toggleNominalFields);
            }
        }

        // ================================
        // === Inisialisasi Modal Edit ===
        // ================================
        @if ($iuran->isNotEmpty())
            @foreach ($iuran as $item)
                (function() {
                    const itemId = '{{ $item->id }}';
                    toggleEditFields(itemId);

                    const jenisEditSelect = document.getElementById('jenisEdit' + itemId);
                    if (jenisEditSelect) {
                        jenisEditSelect.addEventListener('change', function() {
                            toggleEditFields(itemId);
                        });
                    }

                    const editModal = document.getElementById('modalEditIuran' + itemId);
                    if (editModal) {
                        editModal.addEventListener('shown.bs.modal', function() {
                            toggleEditFields(itemId);
                        });
                    }
                })();
            @endforeach
        @endif
    });
</script>
