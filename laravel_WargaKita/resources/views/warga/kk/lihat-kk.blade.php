@extends('warga.layouts.app')

{{-- Menggunakan title yang dinamis dari controller --}}
@section('title', $title ?? 'Detail Kartu Keluarga')

@section('konten')

    <div id="content">

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

                        <div class="kk-container p-4 border rounded shadow-sm">
                            <div class="kk-header text-center mb-4">
                                <div class="kk-header-top-line d-flex justify-content-between align-items-center mb-2">
                                    <div class="kk-header-left-space" style="width: 100px;">
                                        {{-- Placeholder for Garuda Logo if needed --}}
                                    </div>
                                    <div class="kk-header-right-reg text-end flex-grow-1">
                                        @if ($kartuKeluarga->no_registrasi)
                                            <p class="mb-0 small">No. Registrasi:
                                                <strong>{{ $kartuKeluarga->no_registrasi }}</strong>
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="kk-header-main-title">
                                    <h4 class="fw-bold text-success mb-1">KARTU KELUARGA</h4>
                                    <p class="no-kk-big lead fw-bold text-primary">No. KK:
                                        <span>{{ $kartuKeluarga->no_kk }}</span>
                                    </p>
                                </div>
                            </div>

                            @php
                                $kepala = optional($kartuKeluarga->warga)->firstWhere(
                                    'status_hubungan_dalam_keluarga',
                                    'kepala keluarga',
                                );
                            @endphp

                            <div class="kk-info-grid row row-cols-1 row-cols-md-2 g-3 mb-4 small">
                                <div class="col">
                                    <p class="mb-1"><strong>Nama Kepala Keluarga</strong> : {{ $kepala->nama ?? '-' }}</p>
                                    <p class="mb-1"><strong>Alamat</strong> : {{ $kartuKeluarga->alamat ?? '-' }}</p>
                                    <p class="mb-1"><strong>RT/RW</strong> :
                                        {{ $kartuKeluarga->rukunTetangga->rt ?? '-' }}/{{ $kartuKeluarga->rw->nomor_rw ?? '-' }}
                                    </p>
                                    <p class="mb-0"><strong>Desa/Kelurahan</strong> :
                                        {{ $kartuKeluarga->kelurahan ?? '-' }}</p>
                                </div>
                                <div class="col">
                                    <p class="mb-1"><strong>Kecamatan</strong> : {{ $kartuKeluarga->kecamatan ?? '-' }}
                                    </p>
                                    <p class="mb-1"><strong>Kabupaten/Kota</strong> :
                                        {{ $kartuKeluarga->kabupaten ?? '-' }}</p>
                                    <p class="mb-1"><strong>Kode Pos</strong> : {{ $kartuKeluarga->kode_pos ?? '-' }}</p>
                                    <p class="mb-0"><strong>Provinsi</strong> : {{ $kartuKeluarga->provinsi ?? '-' }}</p>
                                    <p class="mb-0"><strong>Kategori Iuran</strong> : {{ $kartuKeluarga->kategori_iuran ?? '-' }}</p>
                                </div>
                            </div>

                            <hr class="my-4" style="border-top: 2px solid #e0e0e0;">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-dark fw-bold mb-0">DAFTAR ANGGOTA KELUARGA</h6>
                            </div>

                            <div class="table-scroll-container mb-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle kk-table table-striped">
                                        <thead class="text-center table-success sticky-top">
                                            <tr>
                                                <th rowspan="2">No.</th>
                                                <th rowspan="2">Nama Lengkap</th>
                                                <th rowspan="2">NIK</th>
                                                <th rowspan="2">Jenis Kelamin</th>
                                                <th colspan="2">Tempat, Tanggal Lahir</th>
                                                <th rowspan="2">Agama</th>
                                                <th rowspan="2">Pendidikan</th>
                                                <th rowspan="2">Jenis Pekerjaan</th>
                                                <th rowspan="2">Golongan Darah</th>
                                                <th rowspan="2">Status Perkawinan</th>
                                                <th rowspan="2">Status Hubungan Dlm Keluarga</th>
                                                <th rowspan="2">Kewarganegaraan</th>
                                                <th colspan="2">Dokumen Imigrasi</th>
                                                <th colspan="2">Nama Orang Tua</th>
                                                <th rowspan="2">Status Warga</th>
                                            </tr>
                                            <tr>
                                                <th>Tempat Lahir</th>
                                                <th>Tanggal Lahir</th>
                                                <th>No. Paspor</th>
                                                <th>No. KITAS/KITAP</th>
                                                <th>Nama Ayah</th>
                                                <th>Nama Ibu</th>
                                            </tr>
                                        </thead>
                                        <tbody class="small">
                                            @forelse($kartuKeluarga->warga->sortByDesc(function($item) {
                                                    return $item->status_hubungan_dalam_keluarga === 'Kepala Keluarga' ? 2 : ($item->status_hubungan_dalam_keluarga === 'Istri' ? 1 : 0);
                                                }) as $data)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>{{ $data->nama ?? '-' }}</td>
                                                    <td>{{ $data->nik ?? '-' }}</td>
                                                    <td class="text-center">{{ $data->jenis_kelamin ?? '-' }}</td>
                                                    <td>{{ $data->tempat_lahir ?? '-' }}</td>
                                                    <td class="text-center">
                                                        {{ $data->tanggal_lahir ? \Carbon\Carbon::parse($data->tanggal_lahir)->format('d-m-Y') : '-' }}
                                                    </td>
                                                    <td>{{ $data->agama ?? '-' }}</td>
                                                    <td>{{ $data->pendidikan ?? '-' }}</td>
                                                    <td>{{ $data->pekerjaan ?? '-' }}</td>
                                                    <td class="text-center">{{ $data->golongan_darah ?? '-' }}</td>
                                                    <td class="text-center">{{ $data->status_perkawinan ?? '-' }}</td>
                                                    <td>{{ $data->status_hubungan_dalam_keluarga ?? '-' }}</td>
                                                    <td class="text-center">{{ $data->kewarganegaraan ?? 'WNI' }}</td>
                                                    <td class="text-center">{{ $data->no_paspor ?? '-' }}</td>
                                                    <td class="text-center">
                                                        {{ ($data->no_kitas ?? '-') . '/' . ($data->no_kitap ?? '-') }}
                                                    </td>
                                                    <td>{{ $data->nama_ayah ?? '-' }}</td>
                                                    <td>{{ $data->nama_ibu ?? '-' }}</td>
                                                    <td class="text-center">{{ $data->status_warga ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="19" class="text-center text-muted p-4">
                                                        Tidak ada anggota keluarga yang terdaftar untuk Kartu Keluarga ini.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <hr class="my-4" style="border-top: 2px dashed #a0a0a0;">

                            <div class="kk-footer row g-3">
                                <div class="col-12 col-md-6 d-flex flex-column align-items-center text-center">
                                    <p class="mb-5">
                                        Mengetahui, <br>
                                        Kepala Keluarga
                                    </p>
                                    @php
                                        $kepala = $kartuKeluarga->warga->firstWhere(
                                            'status_hubungan_dalam_keluarga',
                                            'kepala keluarga',
                                        );
                                    @endphp
                                    <p class="mb-0">
                                        <strong>{{ $kepala->nama ?? '_____________________' }}</strong>
                                    </p>
                                    <p style="font-size: 0.8rem; color: #6c757d;">(Tanda Tangan)</p>
                                </div>

                                <div class="col-12 col-md-6 d-flex flex-column align-items-center text-center">
                                    <p class="mb-0">
                                        {{ $kartuKeluarga->kabupaten ?? '___________' }},
                                        {{ \Carbon\Carbon::parse($kartuKeluarga->tgl_terbit)->isoFormat('D MMMM Y') }}
                                    </p>
                                    <p class="mb-5">
                                        {{ $kartuKeluarga->instansi_penerbit ?? 'KEPALA DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL' }}
                                    </p>
                                    <p class="mb-0">
                                        <strong>{{ $kartuKeluarga->nama_kepala_dukcapil ?? '_____________________' }}</strong>
                                    </p>
                                    <p>NIP.
                                        {{ $kartuKeluarga->nip_kepala_dukcapil ?? '_____________________' }}
                                    </p>
                                </div>
                            </div> {{-- End kk-footer --}}

                            <hr class="my-4" style="border-top: 2px solid #e0e0e0;">

                            {{-- Bagian untuk Dokumen KK (Unggah dan Tampil) --}}
                            <div class="kk-document-section mt-4 border rounded p-3 bg-light">
                                <div class="kk-document-display text-center">
                                    @if ($kartuKeluarga->foto_kk)
                                        @php
                                            $fileExtension = pathinfo($kartuKeluarga->foto_kk, PATHINFO_EXTENSION);
                                            $isPdf = in_array(strtolower($fileExtension), ['pdf']);
                                            $filePath = asset('storage/' . $kartuKeluarga->foto_kk);
                                        @endphp

                                        <h6 class="fw-bold mb-3 small text-muted">Dokumen KK Terunggah:</h6>
                                        <div class="document-preview-wrapper mx-auto">
                                            @if ($isPdf)
                                                <div class="pdf-thumbnail-container"
                                                    onclick="openDocumentModal('{{ $filePath }}', true)">
                                                    <i class="far fa-file-pdf pdf-icon-lg"></i>
                                                    <p class="pdf-filename mt-2">Lihat Dokumen PDF</p>
                                                </div>
                                            @else
                                                <img src="{{ $filePath }}"
                                                    alt="Dokumen Kartu Keluarga {{ $kartuKeluarga->no_kk }}"
                                                    class="img-fluid rounded shadow-sm"
                                                    style="max-height: 500px; object-fit: contain;">
                                            @endif
                                            <div class="view-document-overlay"
                                                onclick="openDocumentModal('{{ $filePath }}', {{ $isPdf ? 'true' : 'false' }})">
                                                <i class="fas fa-eye"></i> Lihat Dokumen
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-muted p-3 border rounded bg-white">
                                            Tidak ada dokumen Kartu Keluarga yang terunggah.
                                        </div>
                                    @endif
                                </div>
                            </div> {{-- End kk-document-section --}}

                        </div> {{-- End kk-container --}}

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

    {{-- MODAL UNTUK PREVIEW DOKUMEN KK --}}
    <div class="modal fade" id="documentViewerModal" tabindex="-1" aria-labelledby="documentViewerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="documentViewerModalLabel">Preview Dokumen Kartu Keluarga</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img id="documentPreviewImage" src="" alt="Document Preview" class="img-fluid d-none">
                    <iframe id="documentPreviewPdf" src="" frameborder="0" width="100%"
                        style="min-height: calc(100vh - 120px);" class="d-none"></iframe>
                </div>
                <div class="modal-footer justify-content-end bg-light">
                    <a id="downloadDocumentBtn" href="#" class="btn btn-primary" download>Unduh Dokumen</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .modal-dialog.modal-fullscreen {
            margin: 0;
            max-width: 100vw;
            height: 100vh;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-dialog.modal-fullscreen .modal-content {
            height: 100%;
            width: 100%;
            border-radius: 0;
            /* Tambahkan atau pastikan latar belakang modal-content adalah hitam/gelap */
            background-color: #333;
            /* Warna latar belakang gelap untuk modal secara keseluruhan */
        }

        /* --- Penyesuaian untuk modal-body --- */
        .modal-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
            overflow-y: auto;
            overflow-x: auto;
            padding: 1rem !important;
            /* Ini kunci untuk latar belakang modal-body */
            background-color: none;
            /* Warna gelap yang sama dengan modal-content */
            /* Atau bisa juga rgba(0,0,0,0.8) jika ingin sedikit transparan */
        }

        /* --- Penyesuaian Spesifik untuk Gambar di dalam Modal Body --- */
        #documentPreviewImage {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            min-height: auto;
            height: auto;
            /* Jika gambar memiliki area transparan, ini akan terlihat di atas background modal-body */
            background-color: transparent;
            /* Pastikan tidak ada latar belakang putih dari gambar itu sendiri */
        }

        /* --- Penyesuaian Spesifik untuk Iframe PDF di dalam Modal Body --- */
        #documentPreviewPdf {
            width: 100%;
            height: calc(100vh - 120px);
            overflow: hidden;
            /* Sembunyikan scrollbar bawaan iframe */
            min-height: 400px;
            background-color: transparent;
            /* Coba atur background iframe ke transparan. */
            /* Efeknya mungkin terbatas karena PDF itu sendiri memiliki latar. */
        }

        body {
            /* Penting: Mencegah scroll pada seluruh elemen body */
            overflow: hidden;
        }

        /* Ini adalah elemen yang akan di-scroll */
        #content {
            /* Mengisi sisa tinggi viewport yang tersedia */
            height: calc(100vh - 56px);
            /* Contoh: 100vh dikurangi tinggi topbar (misal 56px) */
            overflow-y: auto;
            /* Aktifkan scroll vertikal otomatis */
            overflow-x: hidden;
            /* Sembunyikan scroll horizontal jika tidak dibutuhkan */
            padding-top: 20px;
            /* Sesuaikan padding agar tidak terlalu mepet topbar */
            padding-bottom: 20px;
            /* Sesuaikan padding bawah */
        }

        /* Jika Anda ingin hanya card-body yang scrollable */
        .scrollable-card-body {
            max-height: calc(100vh - 200px);
            /* Contoh: Atur tinggi maksimal agar card-body bisa di-scroll */
            overflow-y: auto;
            /* Aktifkan scroll vertikal */
        }

        /* General Styling for KK Look-Alike */
        .kk-container {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            border: 2px solid #0056b3;
            /* Darker blue border for KK look */
            padding: 2rem;
            background-color: #f8faff;
            /* Light blueish background */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .kk-header {
            border-bottom: 2px solid #0056b3;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }

        .kk-header-top-line p {
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        .kk-header-main-title h4 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: #0056b3;
            /* Darker blue for main title */
            text-transform: uppercase;
        }

        .no-kk-big {
            font-size: 1.5rem;
            margin-top: 0;
            color: #007bff;
            /* Primary blue */
        }

        .no-kk-big span {
            letter-spacing: 1px;
            /* Spacing for KK number */
        }

        .kk-info-grid p {
            margin-bottom: 0.5rem;
        }

        .kk-info-grid strong {
            color: #555;
        }

        /* Table Styling for Anggota Keluarga */
        .table-scroll-container {
            max-height: 50vh;
            /* Ini sudah benar untuk scroll tabel internal */
            overflow-y: auto;
            overflow-x: auto;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }

        .table-scroll-container thead.sticky-top {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #eaf7f7;
            /* Light greenish-blue for sticky header */
            box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
        }

        .table-scroll-container .table-sm th,
        .table-scroll-container .table-sm td {
            padding: 0.4rem 0.6rem;
            /* Slightly more padding for readability */
            white-space: nowrap;
            /* Prevent text wrapping in table cells */
        }

        .kk-table th,
        .kk-table td {
            vertical-align: middle;
        }

        /* KK Footer Styling */
        .kk-footer p {
            margin-bottom: 0.2rem;
            font-size: 0.9rem;
        }

        .kk-footer strong {
            font-size: 1rem;
            color: #000;
        }

        /* Document Upload/Display Section */
        .kk-document-section {
            background-color: #eaf0f6;
            /* Light gray-blue background */
            padding: 1.5rem;
            border-radius: 0.5rem;
        }

        .document-preview-wrapper {
            position: relative;
            display: inline-block;
            /* To center the image/pdf thumbnail */
            cursor: pointer;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            overflow: hidden;
            max-width: 100%;
            /* Ensure it doesn't overflow */
        }

        .document-preview-wrapper img {
            display: block;
            max-width: 100%;
            height: auto;
            transition: transform 0.3s ease;
        }

        .document-preview-wrapper:hover img {
            transform: scale(1.02);
        }

        .view-document-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            font-size: 1.2rem;
            text-align: center;
        }

        .document-preview-wrapper:hover .view-document-overlay {
            opacity: 1;
        }

        .view-document-overlay i {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .pdf-thumbnail-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-color: #fff;
            border: 1px dashed #ccc;
            border-radius: 0.5rem;
            min-height: 150px;
            /* Adjust as needed */
        }

        .pdf-icon-lg {
            font-size: 4rem;
            /* Larger PDF icon */
            color: #dc3545;
            /* Red color for PDF */
            margin-bottom: 0.5rem;
        }

        .pdf-filename {
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 0;
        }

        .no-document-text {
            color: #6c757d;
            font-style: italic;
            padding: 1.5rem;
            border: 1px dashed #ccc;
            border-radius: 0.5rem;
            background-color: #f0f0f0;
        }

        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .kk-container {
                padding: 1rem;
            }

            .kk-header-main-title h4 {
                font-size: 1.5rem;
            }

            .no-kk-big {
                font-size: 1.2rem;
            }

            .kk-info-grid.row.row-cols-md-2 .col {
                padding-bottom: 0.5rem;
                /* Add some space between rows in single column layout */
            }

            .table-scroll-container .table-sm th,
            .table-scroll-container .table-sm td {
                font-size: 0.7rem !important;
                padding: 0.3rem !important;
            }

            .kk-footer .col-12 {
                text-align: center;
                margin-bottom: 1.5rem;
                /* Space between signature blocks on small screens */
            }

            .kk-document-upload .input-group {
                flex-direction: column;
            }

            .kk-document-upload .input-group input,
            .kk-document-upload .input-group button {
                width: 100%;
            }

            .kk-document-upload .input-group button {
                margin-top: 0.5rem;
            }

            .document-preview-wrapper {
                max-width: 90%;
                /* Smaller on small screens */
            }
        }

        @media (max-width: 575.98px) {
            .kk-header-top-line {
                flex-direction: column;
                align-items: center;
            }

            .kk-header-right-reg {
                text-align: center;
                margin-top: 0.5rem;
            }

            .kk-info-grid.row.row-cols-md-2 {
                display: block;
                /* Force single column on very small screens */
            }

            .kk-info-grid .col {
                width: 100%;
            }
        }
    </style>

    <script>
        // Function to open document in a modal
        function openDocumentModal(filePath, isPdf) {
            const modal = new bootstrap.Modal(document.getElementById('documentViewerModal'));
            const previewImage = document.getElementById('documentPreviewImage');
            const previewPdf = document.getElementById('documentPreviewPdf');
            const downloadBtn = document.getElementById('downloadDocumentBtn'); // Mendapatkan elemen tombol

            // Reset previous previews
            previewImage.classList.add('d-none');
            previewPdf.classList.add('d-none');
            previewImage.src = '';
            previewPdf.src = '';

            // Tampilkan atau sembunyikan tombol unduh berdasarkan jenis file
            if (isPdf) {
                previewPdf.src = filePath;
                previewPdf.classList.remove('d-none');
                previewPdf.style.display = 'block';
                downloadBtn.classList.add('d-none'); // Sembunyikan tombol unduh untuk PDF
            } else {
                previewImage.src = filePath;
                previewImage.classList.remove('d-none');
                previewImage.style.display = 'block';
                downloadBtn.classList.remove('d-none'); // Tampilkan tombol unduh untuk foto
            }

            downloadBtn.href = filePath;
            modal.show();
        }
    </script>
@endsection
