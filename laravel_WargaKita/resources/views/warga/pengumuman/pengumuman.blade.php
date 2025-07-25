@extends('warga.layouts.app')

<style>
    .modal-body {
        max-height: 80vh;
        overflow-y: auto;
    }

    .modal-body-scroll {
        max-height: 65vh;
        overflow-y: auto;
    }

    /* Hanya pelengkap: Overflow tabel */
    .table-container {
        overflow-x: auto;
    }
</style>

@section('title', $title)

@section('konten')

    <!-- Main Content -->
    <div id="content">

        {{-- top bar --}}
        @include('warga.layouts.topbar')
        {{-- top bar end --}}

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Content Row -->
            <div class="row">

                <form action="{{ route('pengumuman-main') }}" method="GET" class="row g-2 align-items-start px-3 pb-2">
                    <div class="col-md-5 col-12 mb-2">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Judul/Isi/hari...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-7 col-12 d-flex flex-wrap gap-2">
                        <select name="tahun" class="form-select form-select-sm w-auto flex-fill">
                            <option value="">Semua Tahun</option>
                            @foreach ($daftar_tahun as $th)
                                <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>
                                    {{ $th }}</option>
                            @endforeach
                        </select>

                        <select name="bulan" class="form-select form-select-sm w-auto flex-fill">
                            <option value="">Semua Bulan</option>
                            @foreach ($daftar_bulan as $bln)
                                <option value="{{ $bln }}" {{ request('bulan') == $bln ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($bln)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>

                        <select name="kategori" class="form-select form-select-sm w-auto flex-fill">
                            <option value="">Semua Kategori</option>
                            @foreach ($daftar_kategori as $kategori)
                                <option value="{{ $kategori }}"
                                    {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                    {{ $kategori }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-sm btn-primary flex-fill">Filter</button>
                        <a href="{{ route('pengumuman-main') }}" class="btn btn-secondary btn-sm flex-fill">Reset</a>
                    </div>
                </form>

                <!-- Tabel -->
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <!-- Card Header -->
                        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Pengumuman</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-1">
                                {{-- Total Pengumuman (kiri) --}}
                                <div class="d-flex align-items-center gap-1 mb-1 mb-sm-0">
                                    <i class="fas fa-bullhorn me-2 text-primary"></i>
                                    <span class="fw-semibold text-dark">{{ $total_pengumuman ?? 0 }} Pengumuman</span>
                                </div>

                                {{-- Tombol tambah (kanan) --}}
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTambahPengumuman">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </div>
                            <div class="table-responsive table-container">
                                <table class="table table-sm table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">NO</th>
                                            <th scope="col">JUDUL</th>
                                            <th scope="col">Kategori</th>
                                            <th scope="col">RINGKASAN ISI</th>
                                            <th scope="col">TANGGAL</th>
                                            <th scope="col" class="text-center">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pengumuman as $data)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $data->judul }}</td>
                                                <td>{{ $data->kategori }}</td>
                                                <td>
                                                    {{ \Illuminate\Support\Str::limit($data->isi, 50, '...') }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('l, d F Y') }}
                                                </td>
                                                <td class="text-center align-item-center">
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalDetailPengumuman{{ $data->id }}">
                                                        Baca
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Modal Detail Pengumuman -->
                                            <div class="modal fade" id="modalDetailPengumuman{{ $data->id }}"
                                                tabindex="-1" aria-labelledby="modalDetailLabel{{ $data->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                    <div class="modal-content shadow-lg border-0">
                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title mb-0"
                                                                id="modalDetailLabel{{ $data->id }}">
                                                                Detail Pengumuman
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                        </div>
                                                        <div class="modal-body px-4 pt-4 pb-3">
                                                            <h4 class="fw-bold text-success mb-3">{{ $data->judul }}</h4>

                                                            <ul class="list-unstyled mb-3 small">
                                                                <li><strong>Kategori:</strong> <span
                                                                        class="ms-1">{{ $data->kategori ?? '-' }}</span>
                                                                </li>
                                                                @if ($data->id_rt)
                                                                    <li>
                                                                        <strong>RT:</strong>
                                                                        {{ $data->rukunTetangga->nomor_rt ?? '-' }}
                                                                    </li>
                                                                @else
                                                                    <li>
                                                                        <strong>RW:</strong>
                                                                        {{ $data->rw->nomor_rw ?? '-' }}
                                                                    </li>
                                                                @endif


                                                                <li><strong>Tanggal:</strong> <span
                                                                        class="ms-1">{{ \Carbon\Carbon::parse($data->tanggal)->isoFormat('dddd, D MMMM Y') }}</span>
                                                                </li>
                                                            </ul>

                                                            <hr class="my-2">

                                                            <div class="mb-2">
                                                                <strong class="d-block mb-1">Isi Pengumuman:</strong>
                                                                <div class="border rounded bg-light p-3"
                                                                    style="line-height: 1.6;">
                                                                    {{ $data->isi }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="modal-footer bg-light border-0 justify-content-end py-2">
                                                            <button type="button" class="btn btn-outline-success"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Info & Pagination -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 px-4">
                            <div class="text-muted mb-2">
                                Menampilkan {{ $pengumuman->firstItem() ?? '0' }}-{{ $pengumuman->lastItem() }} dari total
                                {{ $pengumuman->total() }} data
                            </div>
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
