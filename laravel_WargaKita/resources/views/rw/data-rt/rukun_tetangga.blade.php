@extends('rw.layouts.app')

@section('title', $title)

@section('content')

    <style>
        .modal-body {
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-body-scroll {
            max-height: 65vh;
            /* maksimal 65% tinggi layar */
            overflow-y: auto;
            /* scroll jika konten melebihi tinggi */
        }
    </style>

    <!-- Main Content -->
    <div id="content">

        {{-- top bar --}}
        @include('rw.layouts.topbar')

        {{-- top bar end --}}

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Content Row -->

            <div class="row">
                <form action="{{ route('rukun_tetangga.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari no kk/nama..."> {{-- Ganti "alamat" dengan "nama" jika alamat tidak ada di pencarian --}}
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-7 col-sm-6 d-flex gap-2">
                        <div class="col-md-3 col-sm-6">
                            <select name="rt" class="form-select form-select-sm">
                                <option value="">Semua RT</option>
                                {{-- Pastikan variabel di sini adalah yang sudah difilter dari controller --}}
                                @foreach ($rukun_tetangga_filter as $rt_option)
                                    {{-- Ubah $rt menjadi $rt_option untuk kejelasan --}}
                                    <option value="{{ $rt_option->rt }}"
                                        {{ request('rt') == $rt_option->rt ? 'selected' : '' }}>
                                        RT {{ $rt_option->rt }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <select name="jabatan" class="form-select form-select-sm">
                                <option value="">Semua Jabatan</option>
                                {{-- Pastikan variabel di sini adalah yang sudah difilter dari controller --}}
                                @foreach ($jabatan_filter as $key => $value)
                                    <option value="{{ $key }}" {{ request('jabatan') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-sm btn-primary" type="submit">Filter</button> {{-- Tambahkan type="submit" --}}
                        <a href="{{ route('rukun_tetangga.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                        {{-- Perbarui ke route rukun_tetangga.index --}}
                    </div>
                </form>

                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            {{-- Judul: Hanya judul di header --}}
                            <h6 class="m-0 font-weight-bold text-primary">Rukun Tetangga</h6>
                            {{-- Tombol tambah dan info jumlah RT akan dipindahkan ke card-body --}}
                        </div>

                        <div class="card-body">
                            {{-- Kontainer Flexbox untuk Jumlah RT (kiri) dan Tombol Tambah (kanan) di dalam card-body --}}
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-1">
                                {{-- Informasi Jumlah RT --}}
                                <div class="d-flex align-items-center gap-1 mb-1 mb-sm-0">
                                    <i class="fas fa-home text-primary"></i>
                                    <span class="fw-semibold text-dark">Jumlah : {{ $total_rt_di_rw ?? 0 }} RT</span>
                                </div>

                                {{-- Tombol tambah --}}
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTambahRt">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </div>

                            <div class="table-responsive table-container">
                                <table class="table table-hover table-sm scroll-table text-nowrap">
                                    <thead>
                                        <tr class="text-center">
                                            <th scope="col">No</th>
                                            <th scope="col">NO KK</th>
                                            <th scope="col">NIK</th>
                                            <th scope="col">RT</th>
                                            <th scope="col">NAMA</th>
                                            <th scope="col">JABATAN</th>
                                            <th scope="col">MULAI MENJABAT</th>
                                            <th scope="col">AKHIR JABATAN</th>
                                            <th scope="col">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rukun_tetangga as $rt)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <th scope="row">{{ $rt->no_kk }}</th>
                                                <th scope="row">{{ $rt->nik }}</th>
                                                <td>{{ $rt->rt }}</td>
                                                <td class="text-center">{{ ucwords(strtolower($rt->nama)) }}</td>
                                                <td class="text-center">{{ ucwords(strtolower($rt->jabatan)) }}</td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($rt->mulai_menjabat)->format('d-m-Y') }}</td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($rt->akhir_jabatan)->format('d-m-Y') }}</td>

                                                <td>
                                                    <form action="{{ route('rukun_tetangga.destroy', $rt->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus RT ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"><i
                                                                class="fas fa-trash-alt"></i></button>
                                                    </form>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditRT{{ $rt->id }}">
                                                        <i class="fas fa-edit"></i></button>
                                                </td>
                                            </tr>

                                            {{-- Modal Edit RT --}}
                                            @include('rw.data-rt.komponen.modal_edit_rt')
                                            {{-- End Modal Edit RT --}}
                                        @endforeach

                                        {{-- Alert Error (ditempatkan di luar loop foreach, atau di dalam <tr> dengan colspan) --}}
                                        @if (session('error'))
                                            <tr>
                                                <td colspan="7"> {{-- Sesuaikan colspan dengan jumlah kolom di tabel Anda (7 kolom) --}}
                                                    <div class="alert alert-danger alert-dismissible fade show mt-2"
                                                        role="alert">
                                                        {{ session('error') }}
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                            aria-label="Close"></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 px-3">
                            <div class="text-muted mb-2">
                                Menampilkan {{ $rukun_tetangga->firstItem() }}-{{ $rukun_tetangga->lastItem() }} dari
                                total
                                {{ $rukun_tetangga->total() }} data
                            </div>

                            <div>
                                {{ $rukun_tetangga->links('pagination::bootstrap-5') }}
                            </div>
                        </div>

                        {{-- Modal Tambah RT --}}
                        @include('rw.data-rt.komponen.modal_tambah_rt')
                        {{-- End Modal Tambah RT --}}


                    </div>
                </div>
            </div>



        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah ada error validasi DAN apakah ini adalah formulir yang kita inginkan
            // (menggunakan old('form_type') karena itu yang kita kirimkan saat redirect back)
            @if ($errors->any() && (old('form_type') === 'rt_tambah' || old('form_type') === 'kk_tambah'))
                var modalId;
                @if (old('form_type') === 'rt_tambah')
                    modalId = 'modalTambahRt';
                @elseif (old('form_type') === 'kk_tambah')
                    modalId = 'modalTambahKK';
                @endif

                if (modalId) {
                    // Dapatkan elemen modal berdasarkan ID
                    var myModalElement = document.getElementById(modalId);
                    if (myModalElement) {
                        // Buat instance modal Bootstrap
                        var myModal = new bootstrap.Modal(myModalElement);
                        // Tampilkan modal
                        myModal.show();
                    }
                }
            @endif
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Logika untuk modal Tambah RT (tetap sama)
            @if (session('showModal') === 'rt_tambah')
                var myTambahModal = new bootstrap.Modal(document.getElementById('modalTambahRt'));
                myTambahModal.show();
            @endif

            // Logika untuk modal Edit RT
            // Akan muncul jika ada error validasi DAN old input menyertakan id_for_modal yang cocok
            // ATAU jika session 'showModalEditId' disetel oleh controller.
            @if ($errors->any() && old('id_for_modal'))
                var rtIdWithErrors = "{{ old('id_for_modal') }}";
                var myEditModalWithError = new bootstrap.Modal(document.getElementById('modalEditRT' +
                    rtIdWithErrors));
                myEditModalWithError.show();
            @elseif (session('showModalEditId'))
                var rtIdFromSession = "{{ session('showModalEditId') }}";
                var myEditModalFromSession = new bootstrap.Modal(document.getElementById('modalEditRT' +
                    rtIdFromSession));
                myEditModalFromSession.show();
            @endif

            // Tampilkan pesan sukses jika ada (menggunakan alert standar)
            @if (session('success'))
                alert('Berhasil! {{ session('success') }}');
            @endif

            // Tampilkan pesan error umum atau error otorisasi jika ada (menggunakan alert standar)
            @if (session('error'))
                alert('Oops... {{ session('error') }}');
            @elseif ($errors->any() && !old('id_for_modal'))
                // Ini akan menangani error validasi yang tidak terkait langsung dengan form modal edit
                // (misalnya, jika ada error validasi di tempat lain atau jika id_for_modal tidak terkirim)
                var errorMessages = '';
                @foreach ($errors->all() as $error)
                    errorMessages += '- {{ $error }}\n';
                @endforeach
                alert('Validasi Gagal! Terdapat kesalahan input:\n' + errorMessages);
            @endif
        });
    </script>

@endsection
