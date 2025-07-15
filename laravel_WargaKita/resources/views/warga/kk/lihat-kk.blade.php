@extends('warga.layouts.app')

{{-- Menggunakan title yang dinamis dari controller --}}
@section('title', $title ?? 'Detail Kartu Keluarga')

@section('konten')

<div id="content" style="overflow: hidden;">

    {{-- Top Bar --}}
    @include('warga.layouts.topbar')
    {{-- Top Bar End --}}

    <div class="container-fluid">

        {{-- Menampilkan pesan error atau success dari controller --}}
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Cek apakah data kartuKeluarga ada sebelum menampilkannya --}}
        @if ($kartuKeluarga)
            <div class="card shadow border-0 mb-3">
                <div class="card-header bg-success text-white py-2">
                    <h6 class="m-0 font-weight-bold text-white small">Informasi Kartu Keluarga & Anggota</h6>
                </div>
                <div class="card-body p-3">

                    {{-- Bagian Informasi Kartu Keluarga - Menggunakan Grid Layout yang Kompak --}}
                    <h5 class="text-success fw-bold mb-2">Data Kartu Keluarga</h5>
                    {{-- PERUBAHAN UTAMA DI SINI: row-cols-md-2, row-cols-lg-4 --}}
                    {{-- Defaultnya 1 kolom (untuk extra small devices), 2 kolom untuk medium (md) dan di atasnya, 4 kolom untuk large (lg) dan di atasnya --}}
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-2 mb-3">
                        <div class="col">
                            <p class="mb-0 small"><strong>No. KK:</strong> <span
                                    class="text-muted">{{ $kartuKeluarga->no_kk }}</span></p>
                        </div>
                        <div class="col">
                            <p class="mb-0 small"><strong>Kepala Keluarga:</strong> <span
                                    class="text-muted">{{ $kartuKeluarga->kepala_keluarga }}</span></p>
                            {{-- Jika Anda perlu mengambil dari relasi warga, gunakan kode ini: --}}
                            {{--
                            <p class="mb-0 small">
                                <strong>Kepala Keluarga:</strong>
                                <span class="text-muted">
                                    @php
                                        $kepalaKeluargaWarga = $kartuKeluarga->warga->firstWhere('hubungan_keluarga', 'Kepala Keluarga');
                                    @endphp
                                    {{ $kepalaKeluargaWarga->nama ?? 'Tidak Ditemukan' }}
                                </span>
                            </p>
                            --}}
                        </div>
                        <div class="col">
                            <p class="mb-0 small"><strong>Alamat:</strong> <span
                                    class="text-muted">{{ $kartuKeluarga->alamat }}</span></p>
                        </div>
                        <div class="col">
                            <p class="mb-0 small"><strong>RT/RW:</strong> <span
                                    class="text-muted">
                                    {{ $kartuKeluarga->rukunTetangga->nomor_rt ?? '-' }}
                                    /
                                    {{ $kartuKeluarga->rw->nomor_rw ?? '-' }}
                                </span>
                            </p>
                        </div>
                        <div class="col">
                            <p class="mb-0 small"><strong>Golongan:</strong> <span
                                    class="text-muted">{{ $kartuKeluarga->golongan ?? '-' }}</span></p>
                        </div>
                        <div class="col">
                            <p class="mb-0 small"><strong>Kode Pos:</strong> <span
                                    class="text-muted">{{ $kartuKeluarga->kode_pos ?? '-' }}</span></p>
                        </div>
                        <div class="col">
                            <p class="mb-0 small"><strong>Kelurahan:</strong> <span
                                    class="text-muted">{{ $kartuKeluarga->kelurahan }}</span></p>
                        </div>
                        <div class="col">
                            <p class="mb-0 small"><strong>Kecamatan:</strong> <span
                                    class="text-muted">{{ $kartuKeluarga->kecamatan }}</span></p>
                        </div>
                        <div class="col">
                            <p class="mb-0 small"><strong>Kabupaten:</strong> <span
                                    class="text-muted">{{ $kartuKeluarga->kabupaten }}</span></p>
                        </div>
                        <div class="col">
                            <p class="mb-0 small"><strong>Provinsi:</strong> <span
                                    class="text-muted">{{ $kartuKeluarga->provinsi }}</span></p>
                        </div>
                        <div class="col">
                            <p class="mb-0 small"><strong>Tanggal Terbit:</strong> <span
                                    class="text-muted">{{ \Carbon\Carbon::parse($kartuKeluarga->tgl_terbit)->isoFormat('DD MMMM YYYY') }}</span>
                            </p>
                        </div>
                    </div>

                    <hr class="my-3">

                    {{-- Bagian Anggota Keluarga - Kolom Lengkap dengan Scroll Horizontal & Vertikal --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="text-success fw-bold mb-0">Daftar Anggota Keluarga</h5>
                    </div>

                    <div class="table-scroll-container">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm align-middle mb-0 text-nowrap">
                                <thead class="table-success text-center small sticky-top">
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
                                        <th>TTL</th>
                                        <th>Agama</th>
                                        <th>Pendidikan</th>
                                        <th>Pekerjaan</th>
                                        <th>Status Kawin</th>
                                        <th>Hubungan</th>
                                        <th>Gol Darah</th>
                                        <th>Jenis Warga</th>
                                        <th>Nama Ayah</th>
                                        <th>Nama Ibu</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    {{-- Loop melalui relasi 'warga' di model Kartu_keluarga --}}
                                    @forelse($kartuKeluarga->warga->sortByDesc(function($item) {
                                                                                return $item->status_hubungan_dalam_keluarga === 'kepala keluarga';
                                                                                    }) as $index => $anggota)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $anggota->nik }}</td>
                                            <td>{{ $anggota->nama }}</td>
                                            <td>{{ $anggota->jenis_kelamin }}</td>
                                            <td>{{ $anggota->tempat_lahir }},
                                                {{ \Carbon\Carbon::parse($anggota->tanggal_lahir)->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                            <td>{{ $anggota->agama }}</td>
                                            <td>{{ $anggota->pendidikan }}</td>
                                            <td>{{ $anggota->pekerjaan }}</td>
                                            <td>{{ $anggota->status_perkawinan }}</td>
                                            <td>{{ $anggota->status_hubungan_dalam_keluarga }}</td>
                                            <td>{{ $anggota->golongan_darah ?? '-' }}</td>
                                            <td>{{ $anggota->jenis}}</td>
                                            <td>{{ $anggota->nama_ayah }}</td>
                                            <td>{{ $anggota->nama_ibu }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="14" class="text-center">Tidak ada anggota keluarga yang
                                                terdaftar untuk Kartu Keluarga ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Pesan jika data kartuKeluarga tidak ditemukan --}}
            <div class="alert alert-info text-center" role="alert">
                Data Kartu Keluarga Anda belum tersedia. Silakan hubungi RT/RW Anda.
            </div>
        @endif
    </div>
</div>

<style>
    /* CSS Kustom untuk Responsivitas Tabel Anggota */
    .table-scroll-container {
        max-height: 50vh;
        overflow-y: auto;
        overflow-x: auto;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }

    /* Mengatasi header sticky yang tertutup background-color default */
    .table-scroll-container thead.sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #f8f9fa !important;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
    }

    /* Mengurangi padding cell untuk kepadatan lebih baik di layar kecil */
    .table-scroll-container .table-sm th,
    .table-scroll-container .table-sm td {
        padding: 0.3rem;
    }

    /* Menjaga lebar minimum kolom untuk data penting di layar kecil */
    .table-scroll-container table th:nth-child(2),
    .table-scroll-container table td:nth-child(2),
    .table-scroll-container table th:nth-child(3),
    .table-scroll-container table td:nth-child(3) {
        min-width: 120px;
    }

    /* Memastikan teks tidak pecah di beberapa kolom jika terlalu panjang, tapi tetap responsif */
    .table-scroll-container table th,
    .table-scroll-container table td {
        white-space: nowrap;
    }

    /* Override background-color for sticky header */
    .table-scroll-container thead.sticky-top {
        background-color: #e9ecef;
    }

    /* --- Penyesuaian CSS untuk Layar Seluler (di bawah 768px) --- */
    @media (max-width: 767.98px) {
        h1.h3 {
            font-size: 1.25rem !important; /* Judul halaman lebih kecil */
        }

        .card-header h6 {
            font-size: 0.75rem !important; /* Header card lebih kecil */
        }

        .card-body h5 {
            font-size: 1rem !important; /* Sub-judul di dalam card lebih kecil */
        }

        /* Ini tidak lagi diperlukan secara eksplisit karena kita akan mengatur ulang grid */
        /* .card-body p strong,
        .card-body p .text-muted {
            font-size: 0.7rem !important;
        } */

        .table-scroll-container .table-sm th,
        .table-scroll-container .table-sm td {
            font-size: 0.65rem !important; /* Ukuran font untuk tabel anggota keluarga */
            padding: 0.2rem !important; /* Padding cell tabel lebih kecil */
        }

        .table-scroll-container table th:nth-child(2),
        .table-scroll-container table td:nth-child(2),
        .table-scroll-container table th:nth-child(3),
        .table-scroll-container table td:nth-child(3) {
            min-width: 90px; /* NIK dan Nama bisa sedikit lebih kecil di layar sangat sempit */
        }

        /* Mengurangi tinggi maksimum tabel untuk tampilan vertikal lebih baik */
        .table-scroll-container {
            max-height: 40vh; /* Sedikit lebih pendek di HP */
        }
    }

    @media (max-width: 575.98px) {
        /* Penyesuaian utama untuk tata letak 2 kolom di layar HP */
        .row.row-cols-1.row-cols-md-2.row-cols-lg-4 {
            --bs-gutter-x: 0.5rem; /* Mengurangi gutter horizontal */
            --bs-gutter-y: 0.5rem; /* Mengurangi gutter vertikal */
            /* Mengatur 2 kolom untuk ukuran extra small (<576px) */
            grid-template-columns: repeat(2, 1fr);
            gap: var(--bs-gutter-x);
            display: grid;
        }

        /* Pastikan setiap kolom mengisi setengah lebar */
        .row.row-cols-1.row-cols-md-2.row-cols-lg-4 > .col {
            width: 100%; /* Untuk memastikan setiap col mengambil seluruh width dari grid cell-nya */
            flex: unset; /* Override flex-grow */
        }

        /* Menyesuaikan ukuran font untuk detail KK di layar sangat kecil */
        .card-body p strong,
        .card-body p .text-muted {
            font-size: 0.65rem !important; /* Ukuran font lebih kecil lagi untuk informasi KK */
        }

        .table-scroll-container .table-sm th,
        .table-scroll-container .table-sm td {
            font-size: 0.6rem !important; /* Lebih kecil lagi untuk layar sangat sempit */
            padding: 0.15rem !important;
        }

        .table-scroll-container table th:nth-child(2),
        .table-scroll-container table td:nth-child(2),
        .table-scroll-container table th:nth-child(3),
        .table-scroll-container table td:nth-child(3) {
            min-width: 80px;
        }
    }
</style>
@endsection