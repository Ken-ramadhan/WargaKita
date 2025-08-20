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
                <form action="{{ route('tagihan.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Data Tagihan...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 d-flex gap-2">
                        <a href="{{ route('tagihan.index') }}" class="btn btn-secondary btn-sm">Reset Pencarian</a>
                    </div>
                </form>

                <!--tabel tagihan-->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Tagihan Saya</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-container">
                                <table class="table table-hover table-sm scroll-table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">NAMA IURAN</th>
                                            <th scope="col">NOMINAL</th>
                                            <th scope="col">TGL TAGIH</th>
                                            <th scope="col">TGL TEMPO</th>
                                            <th scope="col">JENIS</th>
                                            <th scope="col">STATUS</th>
                                            <th scope="col">TGL BAYAR</th>
                                            <th scope="col">Aksi</th> {{-- Kolom Aksi baru --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tagihan as $item)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $item->nama }}</td>
                                                <td>Rp{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->tgl_tagih)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($item->tgl_tempo)->translatedFormat('d F Y') }}
                                                </td>
                                                <td><span class="badge bg-secondary">{{ ucfirst($item->jenis) }}</span>
                                                </td>
                                                <td>
                                                    @if ($item->status_bayar === 'sudah_bayar')
                                                        <span class="badge bg-success">Sudah Bayar</span>
                                                    @else
                                                        <span class="badge bg-warning">Belum Bayar</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->tgl_bayar ? \Carbon\Carbon::parse($item->tgl_bayar)->translatedFormat('d F Y H:i') : '-' }}</td>
                                                <td>
                                                    {{-- Tombol Detail --}}
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalDetailTagihan{{ $item->id }}">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">Tidak ada data tagihan untuk Anda.</td> {{-- colspan disesuaikan --}}
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                <div class="text-muted mb-2">
                                    Menampilkan {{ $tagihan->firstItem() ?? '0' }}-{{ $tagihan->lastItem() }}
                                    dari total
                                    {{ $tagihan->total() }} data
                                </div>

                                <div>
                                    {{ $tagihan->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Detail Tagihan --}}
    @foreach ($tagihan as $item)
    <div class="modal fade" id="modalDetailTagihan{{ $item->id }}" tabindex="-1"
        aria-labelledby="modalDetailTagihanLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content shadow border-0">
                <!-- Header -->
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalDetailTagihanLabel{{ $item->id }}">
                        Detail Tagihan: {{ $item->nama }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>

                <!-- Body -->
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nama Iuran:</strong> {{ $item->nama }}</p>
                            <p class="mb-1"><strong>Nominal:</strong> Rp{{ number_format($item->nominal, 0, ',', '.') }}</p>
                            <p class="mb-1"><strong>Tanggal Tagih:</strong> {{ \Carbon\Carbon::parse($item->tgl_tagih)->translatedFormat('d F Y') }}</p>
                            <p class="mb-1"><strong>Tanggal Tempo:</strong> {{ \Carbon\Carbon::parse($item->tgl_tempo)->translatedFormat('d F Y') }}</p>
                            <p class="mb-1"><strong>Jenis:</strong> <span class="badge bg-secondary">{{ ucfirst($item->jenis) }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Status Pembayaran:</strong>
                                @if ($item->status_bayar === 'sudah_bayar')
                                    <span class="badge bg-success">Sudah Bayar</span>
                                @else
                                    <span class="badge bg-warning">Belum Bayar</span>
                                @endif
                            </p>
                            <p class="mb-1"><strong>Tanggal Bayar:</strong> {{ $item->tgl_bayar ? \Carbon\Carbon::parse($item->tgl_bayar)->translatedFormat('d F Y H:i') : '-' }}</p>
                            <p class="mb-1"><strong>Kategori Pembayaran:</strong> {{ $item->kategori_pembayaran ?? '-' }}</p>
                            <p class="mb-1"><strong>Bukti Transfer:</strong>
                                @if ($item->bukti_transfer)
                                    <a href="{{ asset('storage/' . $item->bukti_transfer) }}" target="_blank" class="btn btn-sm btn-info">Lihat Bukti</a>
                                @else
                                    -
                                @endif
                            </p>
                            <p class="mb-1"><strong>Nomor KK:</strong> {{ $item->no_kk ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- Opsional: Informasi Detail KK jika diperlukan di modal ini --}}
                    @if ($item->kartuKeluarga)
                    <hr class="my-4">
                    <h6 class="text-primary mb-3 fw-bold">Informasi Kartu Keluarga</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Alamat:</strong> {{ $item->kartuKeluarga->alamat }}</p>
                            <p class="mb-1"><strong>RT/RW:</strong> 
                                {{ $item->kartuKeluarga->rukunTetangga->nomor_rt ?? '-' }}/{{ $item->kartuKeluarga->rw->nomor_rw ?? '-' }}
                            </p>
                            <p class="mb-1"><strong>Kepala Keluarga:</strong>
                                @php
                                    $kepala = $item->kartuKeluarga->warga->firstWhere('status_hubungan_dalam_keluarga', 'kepala keluarga');
                                @endphp
                                {{ $kepala->nama ?? '-' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Kelurahan:</strong> {{ $item->kartuKeluarga->kelurahan ?? '-' }}</p>
                            <p class="mb-1"><strong>Kecamatan:</strong> {{ $item->kartuKeluarga->kecamatan ?? '-' }}</p>
                            <p class="mb-1"><strong>Kabupaten:</strong> {{ $item->kartuKeluarga->kabupaten ?? '-' }}</p>
                            <p class="mb-1"><strong>Provinsi:</strong> {{ $item->kartuKeluarga->provinsi ?? '-' }}</p>
                        </div>
                    </div>
                    @endif
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
    {{-- Tidak ada script khusus untuk modal tambah/edit/hapus karena warga hanya melihat --}}
@endpush
