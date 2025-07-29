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

            <div class="row">
                <form action="{{ route('rt_warga.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Nama/Nik/No KK...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 d-flex gap-2">
                        <select name="jenis_kelamin" class="form-select form-select-sm" id="">
                            <option value="">Jenis Kelamin</option>
                            <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                Laki-laki</option>
                            <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                Perempuan</option>
                        </select>
                        <button class="btn btn-sm btn-primary">Filter</button>
                        <a href="{{ route('rt_warga.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                    </div>
                </form>


                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Warga</h6>

                            <div class="d-flex align-items-center gap-2">
                                <!-- Total Warga RT -->
                                <i class="fas fa-users me-2 text-primary"></i>
                                <span class="fw-semibold text-dark">{{ $total_warga }} Warga</span>
                            </div>
                        </div>

                          <!-- Card Body -->
                        <div class="card-body">
                            <div class="table-responsive table-container">
                                <table class="table table-hover table-sm scroll-table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">NO</th>
                                            <th scope="col">NO KK</th> {{-- No. Kartu Keluarga dari warga tersebut --}}
                                            <th scope="col">NIK</th>
                                            <th scope="col">NAMA LENGKAP</th>
                                            <th scope="col">JENIS KELAMIN</th>
                                            <th scope="col">TEMPAT LAHIR</th>
                                            <th scope="col">TANGGAL LAHIR</th>
                                            <th scope="col">AGAMA</th>
                                            <th scope="col">PENDIDIKAN</th>
                                            <th scope="col">PEKERJAAN</th>
                                            <th scope="col">GOLONGAN DARAH</th>
                                            <th scope="col">STATUS PERKAWINAN</th>
                                            <th scope="col">HUBUNGAN DALAM KELUARGA</th>
                                            <th scope="col">KEWARGANEGARAAN</th>
                                            <th scope="col">NO. PASPOR</th>
                                            <th scope="col">NO. KITAS / KITAP</th>
                                            <th scope="col">NAMA AYAH</th>
                                            <th scope="col">NAMA IBU</th>
                                            <th scope="col">STATUS WARGA</th>
                                            <th scope="col">RT</th> {{-- RT dari KK warga tersebut --}}
                                            <th scope="col">AKSI</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($warga as $item)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $item->kartuKeluarga->no_kk ?? '-' }}</td> {{-- Pastikan relasi kartuKeluarga dimuat --}}
                                                <td>{{ $item->nik ?? '-' }}</td>
                                                <td>{{ strtoupper($item->nama ?? '-') }}</td>
                                                <td class="text-center">{{ ucwords($item->jenis_kelamin ?? '-') }}</td>
                                                <td>{{ $item->tempat_lahir ?? '-' }}</td>
                                                <td class="text-center">
                                                    {{ $item->tanggal_lahir ? \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') : '-' }}
                                                </td>
                                                <td>{{ ucwords($item->agama ?? '-') }}</td>
                                                <td>{{ ucwords($item->pendidikan ?? '-') }}</td>
                                                <td>{{ ucwords($item->pekerjaan ?? '-') }}</td>
                                                <td class="text-center">{{ $item->golongan_darah ?? '-' }}</td>
                                                <td class="text-center">{{ ucwords($item->status_perkawinan ?? '-') }}</td>
                                                <td>{{ ucwords($item->status_hubungan_dalam_keluarga ?? '-') }}</td>
                                                <td class="text-center">{{ strtoupper($item->kewarganegaraan ?? 'WNI') }}
                                                </td>
                                                <td class="text-center">{{ $item->no_paspor ?? '-' }}</td>
                                                <td class="text-center">
                                                    @if ($item->no_kitas && $item->no_kitap)
                                                        {{ $item->no_kitas }} / {{ $item->no_kitap }}
                                                    @elseif($item->no_kitas)
                                                        {{ $item->no_kitas }}
                                                    @elseif($item->no_kitap)
                                                        {{ $item->no_kitap }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $item->nama_ayah ?? '-' }}</td>
                                                <td>{{ $item->nama_ibu ?? '-' }}</td>
                                                <td class="text-center">{{ ucwords($item->status_warga ?? '-') }}</td>
                                                <td>{{ $item->kartuKeluarga->rukunTetangga->rt ?? '-' }}</td>
                                                {{-- Pastikan relasi kartuKeluarga dan rukunTetangga dimuat --}}
                                                <td class="text-center align-middle d-flex">
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <form action="{{ route('rt_warga.destroy', $item->nik) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="redirect_to"
                                                                value="{{ route('rt_warga.index') }}"> {{-- Kembali ke halaman index warga --}}
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                title="Hapus Warga">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditwarga{{ $item->nik }}"
                                                            title="Edit Warga">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>



                                            @include('rt.warga.komponen.edit_warga_modal')
                                        @endforeach

                                    </tbody>
                                </table>



                            </div>


                        </div>

                        <!-- Info dan Tombol Pagination Sejajar -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 px-4">
                            <!-- Info Kustom -->
                            <div class="text-muted mb-2">
                                Menampilkan {{ $warga->firstItem() ?? '0' }}-{{ $warga->lastItem() }} dari total
                                {{ $warga->total() }} data
                            </div>

                            <!-- Tombol Pagination -->
                            <div>
                                {{ $warga->links('pagination::bootstrap-5') }}
                            </div>
                        </div>




                    </div>
                </div>
            </div>



        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    @if (session('open_edit_modal'))
        <script>
            var modalId = 'modalEditwarga{{ session('open_edit_modal') }}';
            var modal = new bootstrap.Modal(document.getElementById(modalId));
            modal.show();
        </script>
    @endif


@endsection
