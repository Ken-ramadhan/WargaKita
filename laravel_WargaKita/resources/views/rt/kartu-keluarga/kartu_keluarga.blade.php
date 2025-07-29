@extends('rt.layouts.app')

@section('title', $title)

@section('content')

    <!-- Main Content -->
    <div id="content">

        {{-- top bar --}}
        @include('rt.layouts.topbar')

        {{-- top bar end --}}

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Content Row -->

            <div class="row ">
                <form action="{{ route('rt_kartu_keluarga.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari no kk/alamat...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('rt_kartu_keluarga.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                    </div>
                </form>


                <!-- Card Kartu Keluarga -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">

                        <div class="card-header d-flex align-items-center justify-content-between p-3">
                            {{-- Judul: Hanya judul di header --}}
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Kartu Keluarga</h6>
                        </div>



                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-1">
                                {{-- Total KK (akan berada di kiri) --}}
                                <div class="d-flex align-items-center gap-1 mb-1 mb-sm-0">
                                    <i class="fas fa-id-card text-primary"></i>
                                    <span class="fw-semibold text-dark">Total: {{ $total_kk }} KK</span>
                                </div>

                                {{-- Tombol tambah (akan berada di kanan) --}}
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTambahKK">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </div>
                            <div class="table-responsive table-container">
                                <table class="table table-hover table-sm scroll-table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">NO</th>
                                            <th scope="col">NO KK</th>
                                            <th scope="col">KEPALA KELUARGA</th>
                                            <th scope="col">ALAMAT</th>
                                            <th scope="col">RT</th>
                                            <th scope="col">RW</th>
                                            <th scope="col">KATEGORI IURAN</th>
                                            <th scope="col" class="text-center">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kartu_keluarga as $kk)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $kk->no_kk }}</td>
                                                <td>
                                                    @php
                                                        $kepala = optional($kk->warga)->firstWhere(
                                                            'status_hubungan_dalam_keluarga',
                                                            'kepala keluarga',
                                                        );
                                                    @endphp
                                                    {{ $kepala->nama ?? '-' }}
                                                </td>
                                                <td>{{ $kk->alamat }}</td>
                                                <td>{{ $kk->rukunTetangga->rt ?? '-' }}</td>
                                                <td>{{ $kk->rw->nomor_rw }}</td>
                                                <td>{{ $kk->kategori_iuran }}</td>
                                                <td class="text-center align-middle">
                                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                                        <form action="{{ route('rt_kartu_keluarga.destroy', $kk->no_kk) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                            @csrf


                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"><i
                                                                    class="fas fa-trash-alt"></i>
                                                                <!-- Ikon hapus --></button>
                                                        </form>

                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditkk{{ $kk->no_kk }}">
                                                            <i class="fas fa-edit"></i> <!-- Ikon edit -->
                                                        </button>

                                                        <button type="button" class="btn btn-success btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalDetailkk{{ $kk->no_kk }}">
                                                            <i class="fas fa-info"></i>

                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif






                                <!-- Info dan Tombol Pagination Sejajar -->
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 ml-2">
                                    <!-- Info Kustom -->
                                    <div class="text-muted mb-2">
                                        Menampilkan
                                        {{ $kartu_keluarga->firstItem() ?? '0' }}-{{ $kartu_keluarga->lastItem() }}
                                        dari total
                                        {{ $kartu_keluarga->total() }} data
                                    </div>

                                    <!-- Tombol Pagination -->
                                    <div>
                                        {{ $kartu_keluarga->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                {{-- End card --}}
            </div>

            {{-- Modal Document --}}
            @include('rt.kartu-keluarga.komponen.rt_modal_dokumen')
            {{-- End Modal Document --}}
        </div>
        <!-- /.container-fluid -->
        <!-- Modal Tambah Kartu Keluarga -->
        @include('rt.kartu-keluarga.komponen.rt_modal_tambah_kk')
        {{-- End Modal Tambah KK --}}

        <!-- Modal Tambah Warga -->
        @include('rt.kartu-keluarga.komponen.rt_modal_tambah_warga')
        {{-- End Modal Tambah Warga --}}

        @foreach ($kartu_keluarga as $kk)
            <!-- Modal Edit kartu keluarga -->
            @include('rt.kartu-keluarga.komponen.rt_modal_edit_kk')
            {{-- End Modal Edit KK  --}}
            <!-- Modal Detail kartu keluarga -->
            @include('rt.kartu-keluarga.komponen.rt_modal_detail_kk')
            {{-- End Modal Detail KK --}}
        @endforeach
        @foreach ($warga as $item)
            <!-- Modal Edit Warga -->
            @include('rt.kartu-keluarga.komponen.rt_modal_edit_warga')
            {{-- End Modal Edit Warga --}}
        @endforeach

    </div>
    <!-- End of Main Content -->



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logika untuk modal Edit KK (jika ada error validasi)
            @if ($errors->any() && old('old_no_kk_for_modal'))
                var kkNoWithErrors = "{{ old('old_no_kk_for_modal') }}";
                var myEditModalWithError = new bootstrap.Modal(document.getElementById('modalEditkk' +
                    kkNoWithErrors));
                myEditModalWithError.show();
            @endif

            // Tampilkan pesan sukses/error
            @if (session('success'))
                alert('Berhasil! {{ session('success') }}');
            @endif

            @if (session('error'))
                alert('Oops... {{ session('error') }}');
            @elseif ($errors->any() && !old('old_no_kk_for_modal'))
                var errorMessages = 'Terdapat kesalahan input:\n';
                @foreach ($errors->all() as $error)
                    errorMessages += '- {{ $error }}\n';
                @endforeach
                alert('Validasi Gagal!\n' + errorMessages);
            @endif

            // --- Logika untuk Modal Dokumen (Gambar & PDF) ---
            var documentModalElement = document.getElementById('documentModal');
            var modalImage = document.getElementById('modalImage');
            var modalPdfViewer = document.getElementById('modalPdfViewer');
            // var captionText = document.getElementById('caption');
            var printButton = documentModalElement.querySelector('.btn-outline-light'); // Referensi ke tombol cetak
            var modalFooter = documentModalElement.querySelector(
                '.modal-footer'); // <-- Dapatkan referensi ke modal-footer

            var documentModal = new bootstrap.Modal(documentModalElement);
            let previousDetailModalId = null;


            // Tambahkan event listener untuk event show.bs.modal
            documentModalElement.addEventListener('show.bs.modal', function(event) {
                // Sembunyikan semua konten modal terlebih dahulu
                modalImage.style.display = 'none';
                modalPdfViewer.style.display = 'none';
                // captionText.innerHTML = '';
                printButton.style.display = 'none'; // Tombol cetak disembunyikan secara default
                modalFooter.style.display = 'flex'; // <-- Pastikan footer terlihat (default Bootstrap)

                var invokerElement = event.relatedTarget;
                var documentUrl = invokerElement.getAttribute('data-document-url');
                var isPdf = invokerElement.getAttribute('data-is-pdf') === 'true';

                console.log("[DEBUG] Document URL from invoker:", documentUrl);
                console.log("[DEBUG] Is PDF from invoker:", isPdf);

                // Validasi URL
                if (documentUrl && (documentUrl.startsWith('/') || documentUrl.startsWith('http'))) {
                    // Pastikan URL storage di depannya jika belum ada
                    if (documentUrl.startsWith('foto_kk/')) {
                        documentUrl = '/storage/' + documentUrl;
                    }

                    console.log("[DEBUG] Final Document URL to load:", documentUrl);

                    // Set URL cetak pada tombol cetak modal
                    printButton.setAttribute('data-document-url', documentUrl);
                    printButton.setAttribute('data-is-pdf', isPdf);

                    if (isPdf) {
                        modalPdfViewer.src = documentUrl;
                        modalPdfViewer.style.display = 'block';
                        // captionText.innerHTML = "Dokumen Kartu Keluarga (PDF)";
                        modalFooter.style.display = 'none'; // <-- Sembunyikan modal-footer untuk PDF
                        modalPdfViewer.onerror = function() {
                            console.error("[ERROR] Gagal memuat PDF dari URL:", documentUrl);
                            captionText.innerHTML =
                                "Gagal memuat PDF. File mungkin rusak atau tidak ditemukan.";
                            modalPdfViewer.style.display = 'none';
                            modalFooter.style.display = 'flex'; // Tampilkan kembali jika ada error PDF
                        };
                    } else {
                        modalImage.src = documentUrl;
                        modalImage.style.display = 'block';
                        // captionText.innerHTML = "Dokumen Kartu Keluarga (Gambar)";
                        printButton.style.display = 'inline-block'; // Tampilkan tombol cetak untuk gambar
                        modalFooter.style.display =
                            'flex'; // <-- Pastikan modal-footer terlihat untuk gambar
                        modalImage.onerror = function() {
                            console.error("[ERROR] Gagal memuat gambar dari URL:", documentUrl);
                            captionText.innerHTML =
                                "Gagal memuat gambar. File mungkin rusak atau tidak ditemukan.";
                            modalImage.style.display = 'none';
                        };
                    }
                } else {
                    console.warn("[WARNING] Document URL tidak valid atau kosong:", documentUrl);
                    captionText.innerHTML = "Dokumen tidak ditemukan atau URL tidak valid.";
                    modalFooter.style.display = 'flex'; // Tampilkan kembali jika URL tidak valid
                }

                // Opsional: Sembunyikan modal detail KK saat modal dokumen terbuka
                const openBootstrapModal = document.querySelector('.modal.show');
                if (openBootstrapModal && openBootstrapModal.id.startsWith('modalDetailkk') &&
                    openBootstrapModal.id !== documentModalElement.id) {
                    previousDetailModalId = openBootstrapModal.id;
                    var detailModalInstance = bootstrap.Modal.getInstance(openBootstrapModal);
                    if (detailModalInstance) {
                        detailModalInstance.hide();
                    }
                }

            });

            // Event listener saat modal dokumen ditutup
            documentModalElement.addEventListener('hidden.bs.modal', function() {
                modalPdfViewer.src = '';
                modalImage.src = '';
                if (previousDetailModalId) {
                    const previousModalElement = document.getElementById(previousDetailModalId);
                    if (previousModalElement) {
                        const previousModalInstance = bootstrap.Modal.getOrCreateInstance(
                            previousModalElement);
                        previousModalInstance.show();
                    }
                    previousDetailModalId = null;
                }

                modalFooter.style.display = 'flex';
                console.log("[DEBUG] Modal dokumen ditutup, src di-reset.");
            });


        });

        // Fungsi untuk membuka modal dokumen (dipanggil dari HTML)
        function openDocumentModal(documentUrl, isPdf) {
            var documentModalElement = document.getElementById('documentModal');
            var documentModal = bootstrap.Modal.getInstance(documentModalElement) || new bootstrap.Modal(
                documentModalElement);

            var tempDiv = document.createElement('div');
            tempDiv.setAttribute('data-document-url', documentUrl);
            tempDiv.setAttribute('data-is-pdf', isPdf);

            documentModal.show(tempDiv);
            // Di fungsi openDocumentModal atau show.bs.modal listener
            if (isPdf) {
                // Tambahkan kelas untuk tampilan fullscreen
                documentModalElement.classList.add('modal-fullscreen');
                modalPdfViewer.src = filePath;
                modalPdfViewer.style.display = 'block';
                modalImage.style.display = 'none'; // Sembunyikan gambar
                captionText.innerHTML = "Dokumen Kartu Keluarga (PDF)";
            } else {
                documentModalElement.classList.remove('modal-fullscreen'); // Hapus kelas jika gambar
                modalImage.src = filePath;
                modalImage.style.display = 'block';
                modalPdfViewer.style.display = 'none'; // Sembunyikan PDF viewer
                captionText.innerHTML = "Dokumen Kartu Keluarga (Gambar)";
            }

        }

        // Fungsi untuk Mencetak Dokumen
        function printDocument() {
            var documentModalElement = document.getElementById('documentModal');
            var printButton = documentModalElement.querySelector('.btn-outline-light');
            var documentUrl = printButton.getAttribute('data-document-url');
            var isPdf = printButton.getAttribute('data-is-pdf') === 'true';

            if (!documentUrl) {
                alert("Dokumen tidak tersedia untuk dicetak.");
                return;
            }

            var printWindow = window.open(documentUrl, '_blank');
            if (!isPdf) { // Hanya untuk gambar
                printWindow.onload = function() {
                    printWindow.print();
                    printWindow.close();
                };
            }
            // Untuk PDF, browser biasanya memiliki kontrol cetak bawaan setelah membuka PDF.
        }
    </script>

@endsection
