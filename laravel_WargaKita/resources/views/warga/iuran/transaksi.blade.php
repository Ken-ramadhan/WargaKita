@extends('warga.layouts.app') {{-- Asumsi ada layout untuk warga --}}

@section('title', $title)

@section('konten')

    <div id="content">

        {{-- top bar --}}
        @include('warga.layouts.topbar') {{-- Asumsi ada topbar untuk warga --}}

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

                {{-- Form Pencarian --}}
                <form action="{{ route('transaksi.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Nama Transaksi atau Keterangan...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 d-flex gap-2">
                        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary btn-sm">Reset Pencarian</a>
                    </div>
                </form>

                <!--tabel transaksi-->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi Keuangan RT Saya</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-container">
                                <table class="table table-hover table-sm scroll-table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">TANGGAL</th>
                                            <th scope="col">NAMA TRANSAKSI</th>
                                        
                                            <th scope="col">PENGELUARAN</th>
                                            
                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($transaksi as $item)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y H:i') }}</td>
                                                <td>{{ $item->nama_transaksi }}</td>
                                                
                                                <td>Rp{{ number_format($item->pengeluaran, 0, ',', '.') }}</td>
                                                
                                                <td>
                                                    {{-- Tombol Detail --}}
                                                    {{-- <button type="button" class="btn btn-info btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalDetailTransaksi{{ $item->id }}">
                                                        <i class="fas fa-info-circle"></i> Detail
                                                    </button> --}}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada data transaksi untuk RT Anda.</td> {{-- colspan disesuaikan --}}
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                <div class="text-muted mb-2">
                                    Menampilkan {{ $transaksi->firstItem() ?? '0' }}-{{ $transaksi->lastItem() }}
                                    dari total
                                    {{ $transaksi->total() }} data
                                </div>

                                <div>
                                    {{ $transaksi->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Detail Transaksi --}}
    @foreach ($transaksi as $item)
    <div class="modal fade" id="modalDetailTransaksi{{ $item->id }}" tabindex="-1"
        aria-labelledby="modalDetailTransaksiLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content shadow border-0">
                <!-- Header -->
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalDetailTransaksiLabel{{ $item->id }}">
                        Detail Transaksi: {{ $item->nama_transaksi }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>

                <!-- Body -->
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y H:i') }}</p>
                            <p class="mb-1"><strong>Nama Transaksi:</strong> {{ $item->nama_transaksi }}</p>
                            
                            <p class="mb-1"><strong>Pengeluaran:</strong> Rp{{ number_format($item->pengeluaran, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-md-6">
                        
                            <p class="mb-1"><strong>Keterangan:</strong> {{ $item->keterangan ?? '-' }}</p>
                            <p class="mb-1"><strong>RT:</strong> {{ $item->rt ?? '-' }}</p> {{-- Menampilkan nomor RT --}}
                            <p class="mb-1"><strong>RW:</strong> {{ $item->rw ?? '-' }}</p> {{-- Menampilkan nomor RW --}}
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">
                        <i class="bi bi-check2-circle"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

@endsection

@push('scripts')
    {{-- Tidak ada script khusus --}}
@endpush
