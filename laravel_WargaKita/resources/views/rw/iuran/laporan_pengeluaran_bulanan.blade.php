@extends('rw.layouts.app')

@section('title', $title)

@section('content')
    @include('rw.layouts.topbar')
    <div class="container-fluid">
        <a href="{{ route('pengeluaran.index') }}" class="btn btn-primary mb-3">Kembali</a>
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    Laporan Pengeluaran Bulan {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}
                    {{ $tahun }}
                </h4>
            </div>
            <div class="card-body">
                <h5>Total Pengeluaran: <strong>Rp {{ number_format($total , 2 , ',' , '.') }}</strong></h5>
                   
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-secondary text-center">
                            <tr>
                                <th>No</th>
                                <th>RT</th>
                                <th>Nama Pengeluaran</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengeluaran as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>RT {{ $item->rukunTetangga->nomor_rt }}</td>
                                    <td>{{ $item->nama_pengeluaran }}</td>
                                    <td>Rp {{ number_format($item->jumlah, 2, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data pengeluaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
