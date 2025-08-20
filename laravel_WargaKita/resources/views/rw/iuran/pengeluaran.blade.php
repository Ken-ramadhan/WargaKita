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

                <form action="{{ route('pengeluaran.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Data Transaksi...">
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
                        <input type="text" name="rt" value="{{ request('rt') }}" class="form-control form-control-sm" placeholder="Filter RT">

                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                    </div>
                </form>

                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Daftar Transaksi Keuangan</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="dropdownMenuLink">
                                    <div class="dropdown-header">Aksi</div>
                                    {{-- Tombol untuk memicu modal tambah --}}
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#transactionModal" data-mode="add">Tambah Transaksi</a>
                                    <a href="{{ route('pengeluaranBulanan', ['bulan' => strtolower(now()->translatedFormat('F')), 'tahun' => now()->year]) }}"
                                        class="dropdown-item">
                                        Laporan Bulan Ini
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">

                            <div class="table-responsive table-container">
                                <table class="table table-hover table-sm scroll-table text-nowrap">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>NO</th>
                                            <th>RT</th>
                                            <th>Tanggal</th>
                                            <th>Nama Transaksi</th>
                                            <th>Pemasukan</th>
                                            <th>Pengeluaran</th>
                                            <th>Jumlah (Net)</th>
                                            <th>Sisa Uang</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($paginatedTransaksi as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->rt }}</td>
                                                <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y') }}</td>
                                                <td>{{ $data->nama_transaksi }}</td>
                                                <td>Rp {{ number_format($data->pemasukan_display, 2, ',', '.') }}</td>
                                                <td>Rp {{ number_format($data->pengeluaran_display, 2, ',', '.') }}</td>
                                                <td>Rp {{ number_format($data->jumlah, 2, ',', '.') }}</td>
                                                <td>Rp {{ number_format($data->sisa_uang, 2, ',', '.') }}</td>
                                                <td>{{ $data->keterangan ?? '-' }}</td>
                                                <td class="d-flex gap-1 flex-wrap">
                                                    <form action="{{ route('pengeluaran.destroy', $data->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                    </form>
                                                    {{-- Tombol Edit yang memicu modal dengan data transaksi --}}
                                                    <button type="button" class="btn btn-warning btn-sm edit-btn"
                                                        data-bs-toggle="modal" data-bs-target="#transactionModal"
                                                        data-mode="edit"
                                                        data-id="{{ $data->id }}"
                                                        data-rt="{{ $data->rt }}"
                                                        data-tanggal="{{ \Carbon\Carbon::parse($data->tanggal)->format('Y-m-d') }}"
                                                        data-nama_transaksi="{{ $data->nama_transaksi }}"
                                                        data-pemasukan="{{ $data->pemasukan_display }}"
                                                        data-pengeluaran="{{ $data->pengeluaran_display }}"
                                                        data-jumlah="{{ $data->jumlah }}"
                                                        data-keterangan="{{ $data->keterangan }}">
                                                        Edit
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">Tidak ada data transaksi.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Info dan Tombol Pagination Sejajar -->
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                <!-- Info Kustom -->
                                <div class="text-muted mb-2">
                                    Menampilkan {{ $paginatedTransaksi->firstItem() ?? '0' }}-{{ $paginatedTransaksi->lastItem() }}
                                    dari total
                                    {{ $paginatedTransaksi->total() }} data
                                </div>

                                <!-- Tombol Pagination -->
                                <div>
                                    {{ $paginatedTransaksi->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->

        {{-- Modal Tunggal untuk Tambah dan Edit Transaksi --}}
        <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content shadow-lg">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="transactionModalLabel"></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <form id="transactionForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod"> {{-- Untuk PUT method pada edit --}}
                        <input type="hidden" name="id" id="transactionId"> {{-- Untuk ID transaksi yang diedit --}}

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="rt" class="form-label">RT</label>
                                <input type="text" class="form-control" id="modalRt" name="rt" required>
                                <div class="invalid-feedback" id="rt-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="modalTanggal" name="tanggal" required>
                                <div class="invalid-feedback" id="tanggal-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="nama_transaksi" class="form-label">Nama/Deskripsi Transaksi</label>
                                <input type="text" class="form-control" id="modalNamaTransaksi" name="nama_transaksi" required>
                                <div class="invalid-feedback" id="nama_transaksi-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="pemasukan" class="form-label">Pemasukan (Isi jika ini pemasukan)</label>
                                <input type="number" step="0.01" class="form-control" id="modalPemasukan" name="pemasukan" min="0">
                                <div class="invalid-feedback" id="pemasukan-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="pengeluaran" class="form-label">Pengeluaran (Isi jika ini pengeluaran)</label>
                                <input type="number" step="0.01" class="form-cont