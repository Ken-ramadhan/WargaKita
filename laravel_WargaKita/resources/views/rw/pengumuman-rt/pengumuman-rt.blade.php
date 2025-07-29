@extends('rw.layouts.app')
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

                <form action="{{ route('pengumuman-rt.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Cari judul/isi...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 d-flex gap-2">
                        <select name="tahun" class="form-select form-select-sm">
                            <option value="">Semua Tahun</option>
                            @foreach ($daftar_tahun as $th)
                                <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>
                                    {{ $th }}</option>
                            @endforeach
                        </select>
                        <select name="bulan" class="form-select form-select-sm">
                            <option value="">Semua Bulan</option>
                            @foreach ($daftar_bulan as $bln)
                                <option value="{{ $bln }}" {{ request('bulan') == $bln ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($bln)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                        <select name="kategori" class="form-select form-select-sm">
                            <option value="">Semua kategori</option>
                            @foreach ($daftar_kategori as $kategori)
                                <option value="{{ $kategori }}"
                                    {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                    {{ $kategori }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        <a href="{{ route('pengumuman-rt.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                    </div>
                </form>


                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">

                        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengumuman</h6>


                        </div>

                        <!-- Card Body -->
                        <!-- Card Body -->
                        <div class="card-body">
                            {{-- Kontainer Flexbox untuk Total Pengumuman dan Tombol Tambah --}}
                            {{-- flex-column di layar kecil, flex-row di layar sm ke atas --}}
                            {{-- justify-content-between untuk memisahkan ke kiri dan kanan --}}
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-1">
                                {{-- Total Pengumuman (kiri) --}}
                                <div class="d-flex align-items-center gap-1 mb-1 mb-sm-0">
                                    <i class="fas fa-bullhorn me-2 text-primary"></i>
                                    <span class="fw-semibold text-dark">{{ $total_pengumuman ?? 0 }} Pengumuman</span>
                                </div>
                            </div>

                            <div class="table-responsive table-container">
                                <table class="table table-sm scroll-table text-nowrap table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">NO</th>
                                            {{-- <th scope="col">ID</th> --}}
                                            <th scope="col">JUDUL</th>
                                            <th scope="col">kategori</th>
                                            <th scope="col">RINGKASAN ISI</th>
                                            <th scope="col">TANGGAL</th>
                                            <th scope="col" class="text-center">AKSI</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pengumuman as $data)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                {{-- <th scope="row">{{ $data->id }}</th> --}}
                                                <td>{{ $data->judul }}</td>
                                                <td>{{ $data->kategori }}</td>

                                                {{-- Menggunakan Str::limit untuk membatasi panjang teks --}}
                                                <td>
                                                    {{ \Illuminate\Support\Str::limit($data->isi, 50, '...') }}
                                                </td>

                                                {{-- Menggunakan Carbon untuk format hari tanggal bulan dan tahun --}}
                                                <td>
                                                    {{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('l, d F Y') }}
                                                </td>



                                                <td class="text-center align-item-center">
                                                    {{-- Tombol Aksi: Hapus, Edit, Detail --}}
                                                    <form action="{{ route('pengumuman.destroy', $data->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"><i
                                                                class="fas fa-trash-alt"></i> <!-- Ikon hapus --></button>
                                                    </form>

                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditPengumuman{{ $data->id }}">
                                                        <i class="fas fa-edit"></i> <!-- Ikon edit -->
                                                    </button>

                                                    <button type="button" class="btn btn-success btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalDetailPengumuman{{ $data->id }}">
                                                        <i class="fas fa-info"></i>
                                                    </button>
                                                </td>


                                            </tr>

                                            <tr>
                                                @if (session('error'))
                                                    <div class="alert alert-danger alert-dismissible fade show"
                                                        role="alert">
                                                        {{ session('error') }}
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                            aria-label="Close"></button>
                                                    </div>
                                                @endif
                                            </tr>
                                            <!-- Modal Detail Pengumuman -->
                                            @include('rw.pengumuman-rt.komponen.modal_detail_pengumuman_rt')
                                            <!-- Modal Detail Pengumuman End -->
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <!-- Info dan Tombol Pagination Sejajar -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 px-4">
                            <!-- Info Kustom -->
                            <div class="text-muted mb-2">
                                Menampilkan {{ $pengumuman->firstItem() ?? '0' }}-{{ $pengumuman->lastItem() }} dari total
                                {{ $pengumuman->total() }} data
                            </div>

                            <!-- Tombol Pagination -->
                            <div>
                                {{ $pengumuman->links('pagination::bootstrap-5') }}
                            </div>
                        </div>



                    </div>
                </div>
            </div>



        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

@endsection
