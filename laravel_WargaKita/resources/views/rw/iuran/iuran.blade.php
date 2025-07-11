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
                                        @forelse ($iuran->where('jenis', 'manual') as $item) {{-- Filter here for manual --}}
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
                                            @foreach ($kategori_golongan as $golongan)
                                                <th scope="col">Nominal {{ $golongan->nama }}</th>
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
                                        @forelse ($iuran->where('jenis', 'otomatis') as $item) {{-- Filter here for automatic --}}
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                {{-- Menampilkan nominal per golongan --}}
                                                @foreach ($kategori_golongan as $golongan)
                                                    <td>
                                                        {{-- Pastikan relasi iuran_golongan di-load di controller --}}
                                                        {{-- Gunakan firstWhere() untuk mencari langsung berdasarkan golongan_id --}}
                                                        Rp{{ number_format($item->iuran_golongan->firstWhere('id_golongan', $golongan->id)->nominal ?? 0, 0, ',', '.') }}
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
                                                {{-- Hitung colspan yang benar: 5 kolom tetap + jumlah kategori_golongan --}}
                                                <td colspan="{{ 5 + count($kategori_golongan) }}" class="text-center">Tidak ada data iuran otomatis.</td>
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
                                <select name="jenis" class="form-select" id="jenisEdit{{ $item->id }}"
                                    onchange="toggleEditFields('{{ addslashes($item->id) }}')"> {{-- Pastikan ID string --}}
                                    <option value="otomatis" {{ $item->jenis == 'otomatis' ? 'selected' : '' }}>Otomatis</option>
                                    <option value="manual" {{ $item->jenis == 'manual' ? 'selected' : '' }}>Manual</option>
                                </select>
                            </div>

                            <div id="otomatisFieldsEdit{{ $item->id }}"
                                style="{{ $item->jenis == 'otomatis' ? '' : 'display: none' }}">
                                @foreach ($kategori_golongan as $golongan)
                                    <label class="form-label">{{ $golongan->nama }}</label>
                                    <input type="number" name="nominal[{{ $golongan->id }}]" class="form-control mb-2"
                                        placeholder="Masukkan nominal {{ $golongan->nama }}"
                                        value="{{ old('nominal.' . $golongan->id, $item->iuran_golongan->firstWhere('id_golongan', $golongan->id)->nominal ?? '') }}">
                                @endforeach
                            </div>

                            <div id="manualFieldEdit{{ $item->id }}"
                                style="{{ $item->jenis == 'manual' ? '' : 'display: none' }}">
                                <label class="form-label">Nominal</label>
                                <input type="number" name="nominal_manual" class="form-control"
                                    placeholder="Masukkan nominal manual"
                                    value="{{ old('nominal_manual', $item->nominal) }}">
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
                            <select name="jenis" id="jenis" onchange="toggleNominalFields()" required
                                class="form-select">
                                <option value="otomatis" {{ old('jenis') === 'otomatis' ? 'selected' : '' }}>Otomatis
                                </option>
                                <option value="manual" {{ old('jenis') === 'manual' ? 'selected' : '' }}>Manual</option>
                            </select>
                        </div>

                        <div id="otomatis-fields" class="mb-3" style="{{ old('jenis') === 'manual' ? 'display: none;' : '' }}">
                            @foreach ($kategori_golongan as $golongan)
                                <label class="form-label">{{ $golongan->nama }}</label>
                                <input type="number" name="nominal[{{ $golongan->id }}]"
                                    class="form-control mb-2" placeholder="Masukkan nominal {{ $golongan->nama }}"
                                    value="{{ old('nominal.' . $golongan->id) }}"required>
                            @endforeach
                        </div>
                        <div class="mb-3" id="manual-field" style="{{ old('jenis') === 'otomatis' ? 'display: none;' : '' }}">
                            <label class="form-label">Nominal</label>
                            <input type="number" name="nominal_manual" class="form-control"
                                placeholder="Masukkan nominal manual" value="{{ old('nominal_manual') }}">
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

@endsection

@push('scripts')
<script>
    // Pastikan DOM sudah dimuat sebelum menjalankan script
    document.addEventListener('DOMContentLoaded', function() {

        // --- Fungsi untuk Modal Tambah Iuran ---
        function toggleNominalFields() {
            const jenisSelect = document.getElementById('jenis');
            const otomatisFields = document.getElementById('otomatis-fields');
            const manualField = document.getElementById('manual-field');

            // Peringatan jika elemen tidak ditemukan (membantu debugging)
            if (!jenisSelect || !otomatisFields || !manualField) {
                console.warn('Elemen form tambah iuran tidak ditemukan. Pastikan ID HTML sudah benar.');
                return;
            }

            const jenis = jenisSelect.value;

            if (jenis === 'otomatis') {
                otomatisFields.style.display = 'block';
                manualField.style.display = 'none';

                // Hapus atribut 'required' dari input manual saat disembunyikan
                const manualInput = manualField.querySelector('input');
                if (manualInput) {
                    manualInput.removeAttribute('required');
                    manualInput.value = ''; // Kosongkan nilai saat disembunyikan
                }

                // Tambahkan atribut 'required' ke input otomatis saat ditampilkan
                otomatisFields.querySelectorAll('input[type="number"]').forEach(input => {
                    input.setAttribute('required', 'required');
                });

            } else { // jenis === 'manual'
                otomatisFields.style.display = 'none';
                manualField.style.display = 'block';

                // Hapus atribut 'required' dari input otomatis saat disembunyikan
                otomatisFields.querySelectorAll('input[type="number"]').forEach(input => {
                    input.removeAttribute('required');
                    input.value = ''; // Kosongkan nilai saat disembunyikan
                });

                // Tambahkan atribut 'required' ke input manual saat ditampilkan
                const manualInput = manualField.querySelector('input');
                if (manualInput) {
                    manualInput.setAttribute('required', 'required');
                }
            }
        }

        // --- Fungsi untuk Modal Edit Iuran (dinamis berdasarkan itemId) ---
        function toggleEditFields(itemId) {
            const jenisEditSelect = document.getElementById('jenisEdit' + itemId);
            const otomatisFieldsEdit = document.getElementById('otomatisFieldsEdit' + itemId);
            const manualFieldEdit = document.getElementById('manualFieldEdit' + itemId);

            // Peringatan jika elemen tidak ditemukan (membantu debugging)
            if (!jenisEditSelect || !otomatisFieldsEdit || !manualFieldEdit) {
                console.warn(`Elemen form edit iuran ${itemId} tidak ditemukan. Pastikan ID HTML sudah benar.`);
                return;
            }

            const jenisEdit = jenisEditSelect.value;

            if (jenisEdit === 'otomatis') {
                otomatisFieldsEdit.style.display = 'block';
                manualFieldEdit.style.display = 'none';

                // Hapus atribut 'required' dari input manual edit saat disembunyikan
                const manualEditInput = manualFieldEdit.querySelector('input');
                if (manualEditInput) {
                    manualEditInput.removeAttribute('required');
                    // Tidak perlu mengosongkan nilai di sini, karena mungkin ada old input dari database
                }

                // Tambahkan atribut 'required' ke input otomatis edit saat ditampilkan
                otomatisFieldsEdit.querySelectorAll('input[type="number"]').forEach(input => {
                    input.setAttribute('required', 'required');
                });

            } else { // jenisEdit === 'manual'
                otomatisFieldsEdit.style.display = 'none';
                manualFieldEdit.style.display = 'block';

                // Hapus atribut 'required' dari input otomatis edit saat disembunyikan
                otomatisFieldsEdit.querySelectorAll('input[type="number"]').forEach(input => {
                    input.removeAttribute('required');
                    // Tidak perlu mengosongkan nilai di sini, karena mungkin ada old input dari database
                });

                // Tambahkan atribut 'required' ke input manual edit saat ditampilkan
                const manualEditInput = manualFieldEdit.querySelector('input');
                if (manualEditInput) {
                    manualEditInput.setAttribute('required', 'required');
                }
            }
        }

        // --- Inisialisasi Saat Halaman Dimuat ---

        // Inisialisasi untuk modal Tambah Iuran
        toggleNominalFields();
        // Tambahkan event listener untuk perubahan pada select jenis di modal tambah
        const jenisAddSelect = document.getElementById('jenis');
        if (jenisAddSelect) {
            jenisAddSelect.addEventListener('change', toggleNominalFields);
        }

        // Hanya inisialisasi untuk modal Edit Iuran jika ada data iuran
        // Ini mencegah error JavaScript jika tidak ada item iuran yang ditampilkan di halaman
        @if ($iuran->isNotEmpty())
            // Inisialisasi untuk setiap modal Edit Iuran
            @foreach ($iuran as $item)
                // Menggunakan addslashes dan mengapit dengan kutip tunggal untuk memastikan string JavaScript yang valid
                const currentItemId = '{{ addslashes($item->id) }}';
                toggleEditFields(currentItemId);
                // Tambahkan event listener untuk perubahan pada select jenis di setiap modal edit
                const jenisEditSelect = document.getElementById('jenisEdit' + currentItemId);
                if (jenisEditSelect) {
                    jenisEditSelect.addEventListener('change', function() {
                        toggleEditFields(currentItemId);
                    });
                }
            @endforeach

            // --- Re-inisialisasi Saat Modal Ditampilkan (untuk Bootstrap Modals) ---

            // Untuk modal Tambah Iuran (ini tetap di luar if, karena modal tambah selalu ada)
            const tambahIuranModal = document.getElementById('modalTambahIuran');
            if (tambahIuranModal) {
                tambahIuranModal.addEventListener('shown.bs.modal', function () {
                    toggleNominalFields();
                });
            }

            // Untuk setiap modal Edit Iuran
            @foreach ($iuran as $item)
                // Perhatikan: string ID HTML tidak memerlukan kutip
                const editIuranModal = document.getElementById('modalEditIuran{{ $item->id }}');
                if (editIuranModal) {
                    editIuranModal.addEventListener('shown.bs.modal', function () {
                        // Menggunakan addslashes dan mengapit dengan kutip tunggal untuk argumen fungsi JavaScript
                        toggleEditFields('{{ addslashes($item->id) }}');
                    });
                }
            @endforeach
        @endif

    }); // End DOMContentLoaded
</script>
@endpush
