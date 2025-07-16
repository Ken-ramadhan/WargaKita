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
                <form action="{{ route('warga.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Data Warga...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 d-flex gap-2">
                        <select name="jenis_kelamin" class="form-select form-select-sm" id="">
                            <option value="">Jenis Kelamin</option>
                            <option value="laki-laki" {{ request('jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>
                                Laki-laki</option>
                            <option value="perempuan" {{ request('jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>
                                Perempuan</option>
                        </select>
                        <button class="btn btn-sm btn-primary">Filter</button>
                        <a href="{{ route('warga.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                    </div>
                </form>


                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Daftar Warga</h6>

                            <div class="d-flex align-items-center">
                                <i class="fas fa-users me-2 text-primary"></i>
                                <span class="fw-semibold text-dark me-4">Total Warga: {{ $total_warga }}</span>
                            </div>
                        </div>


                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="table-responsive table-container">
                                <table class="table table-hover table-sm scroll-table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">NO</th>
                                            <th scope="col">NO KK</th>
                                            <th scope="col">NIK</th>
                                            <th scope="col">NAMA</th>
                                            <th scope="col">JENIS KELAMIN</th>
                                            <th scope="col">TANGGAL LAHIR</th>
                                            <th scope="col">JENIS WARGA</th>
                                            <th scope="col">HUBUNGAN</th>
                                            <th scope="col">RT</th>
                                            <th scope="col">AKSI</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($warga as $item)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $item->kartuKeluarga->no_kk }}</td>
                                                <td>{{ $item->nik }}</td>
                                                <td>{{ strtoupper($item->nama) }}</td>
                                                <td class="text-center">{{ $item->jenis_kelamin }}</td>
                                                <td>{{ $item->tanggal_lahir }}</td>
                                                <td>{{ $item->jenis }}</td>
                                                <td>{{ $item->status_hubungan_dalam_keluarga }}</td>
                                                <td>{{ $item->kartuKeluarga->rukunTetangga->nomor_rt }}</td>
                                                <td class="text-center align-middle">
                                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                                        <form action="{{ route('warga.destroy', $item->nik) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="redirect_to"
                                                                value="{{ route('warga.index') }}">
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="fas fa-trash-alt"></i> <!-- Ikon hapus -->
                                                            </button>
                                                        </form>

                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditwarga{{ $item->nik }}">
                                                            <i class="fas fa-edit"></i> <!-- Ikon edit -->
                                                        </button>
                                                    </div>

                                                </td>
                                            </tr>


                                            <!-- Modal Edit Warga -->
                                            <div class="modal fade" id="modalEditwarga{{ $item->nik }}" tabindex="-1"
                                                aria-labelledby="modalEditwargaLabel{{ $item->nik }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                    <div class="modal-content shadow-lg">
                                                        <div class="modal-header bg-warning text-white">
                                                            <h5 class="modal-title"
                                                                id="modalEditwargaLabel{{ $item->nik }}">Edit
                                                                Data Warga</h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                        </div>

                                                        <form action="{{ route('warga.update', $item->nik) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="redirect_to"
                                                                value="{{ route('warga.index') }}">



                                                            <!-- MODAL BODY YANG BISA SCROLL -->
                                                            <div class="modal-body"
                                                                style="max-height: 70vh; overflow-y: auto;">
                                                                <div class="row g-3">

                                                                    <!-- Nik -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">NIK</label>
                                                                        <input type="text" name="nik"
                                                                            class="form-control"
                                                                            value="{{ $item->nik }}" readonly>
                                                                    </div>

                                                                    <!-- No KK -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Nomor KK</label>
                                                                        <input type="text" name="no_kk"
                                                                            class="form-control"
                                                                            value="{{ $item->no_kk }}" readonly>
                                                                    </div>

                                                                    <!-- Nama Lengkap -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Nama Lengkap</label>
                                                                        <input type="text" name="nama"
                                                                            class="form-control @error('nama') is-invalid @enderror"
                                                                            value="{{ old('nama', $item->nama) }}"
                                                                            required>
                                                                        @error('nama')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Jenis Kelamin -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Jenis Kelamin</label>
                                                                        <select name="jenis_kelamin"
                                                                            class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                                                            required
                                                                            value="{{ old('jenis_kelamin') ?? $item->jenis_kelamin }}">
                                                                            <option value="laki-laki"
                                                                                {{ (old('jenis_kelamin') ?? $item->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>
                                                                                Laki-laki

                                                                            </option>
                                                                            <option value="perempuan"
                                                                                {{ (old('jenis_kelamin') ?? $item->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>
                                                                                Perempuan
                                                                            </option>
                                                                        </select>
                                                                        @error('jenis_kelamin')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>



                                                                    <!-- Tempat Lahir -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Tempat Lahir</label>
                                                                        <input type="text" name="tempat_lahir"
                                                                            class="form-control @error('tempat_lahir') is-invalid @enderror"
                                                                            value="{{ old('tempat_lahir', $item->tempat_lahir) }}"
                                                                            required>
                                                                        @error('tempat_lahir')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Tanggal Lahir -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Tanggal Lahir</label>
                                                                        <input type="date" name="tanggal_lahir"
                                                                            class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                                                            value="{{ old('tanggal_lahir', $item->tanggal_lahir) }}"
                                                                            required>
                                                                        @error('tanggal_lahir')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Agama -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Agama</label>
                                                                        <input type="text" name="agama"
                                                                            class="form-control @error('agama') is-invalid @enderror"
                                                                            value="{{ old('agama', $item->agama) }}"
                                                                            required>
                                                                        @error('agama')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Pendidikan -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Pendidikan</label>
                                                                        <input type="text" name="pendidikan"
                                                                            class="form-control @error('pendidikan') is-invalid @enderror"
                                                                            value="{{ old('pendidikan', $item->pendidikan) }}"
                                                                            required>
                                                                        @error('pendidikan')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Pekerjaan -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Pekerjaan</label>
                                                                        <input type="text" name="pekerjaan"
                                                                            class="form-control @error('pekerjaan') is-invalid @enderror"
                                                                            value="{{ old('pekerjaan', $item->pekerjaan) }}"
                                                                            required>
                                                                        @error('pekerjaan')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Status Perkawinan -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Status Perkawinan</label>
                                                                        <select name="status_perkawinan"
                                                                            class="form-select @error('status_perkawinan') is-invalid @enderror"
                                                                            required>
                                                                            <option value="menikah"
                                                                                {{ old('status_perkawinan', $item->status_perkawinan) == 'menikah' ? 'selected' : '' }}>
                                                                                Menikah</option>
                                                                            <option value="belum menikah"
                                                                                {{ old('status_perkawinan', $item->status_perkawinan) == 'belum menikah' ? 'selected' : '' }}>
                                                                                Belum Menikah</option>
                                                                            <option value="cerai hidup"
                                                                                {{ old('status_perkawinan', $item->status_perkawinan) == 'cerai hidup' ? 'selected' : '' }}>
                                                                                Cerai Hidup</option>
                                                                            <option value="cerai mati"
                                                                                {{ old('status_perkawinan', $item->status_perkawinan) == 'cerai mati' ? 'selected' : '' }}>
                                                                                Cerai Mati</option>
                                                                        </select>
                                                                        @error('status_perkawinan')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Hubungan dengan KK -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Hubungan dengan
                                                                            KK</label>
                                                                        <select name="status_hubungan_dalam_keluarga"
                                                                            class="form-select @error('status_hubungan_dalam_keluarga') is-invalid @enderror"
                                                                            required>
                                                                            <option value="kepala keluarga"
                                                                                {{ old('status_hubungan_dalam_keluarga', $item->status_hubungan_dalam_keluarga) == 'kepala keluarga' ? 'selected' : '' }}>
                                                                                Kepala Keluarga</option>
                                                                            <option value="istri"
                                                                                {{ old('status_hubungan_dalam_keluarga', $item->status_hubungan_dalam_keluarga) == 'istri' ? 'selected' : '' }}>
                                                                                Istri</option>
                                                                            <option value="anak"
                                                                                {{ old('status_hubungan_dalam_keluarga', $item->status_hubungan_dalam_keluarga) == 'anak' ? 'selected' : '' }}>
                                                                                Anak</option>
                                                                        </select>
                                                                        @error('status_hubungan_dalam_keluarga')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Golongan Darah -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Golongan Darah</label>
                                                                        <select name="golongan_darah"
                                                                            class="form-select @error('golongan_darah') is-invalid @enderror"
                                                                            required>
                                                                            @foreach (['A', 'B', 'AB', 'O'] as $gd)
                                                                                <option value="{{ $gd }}"
                                                                                    {{ old('golongan_darah', $item->golongan_darah) == $gd ? 'selected' : '' }}>
                                                                                    {{ $gd }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('golongan_darah')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Kewarganegaraan -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Kewarganegaraan</label>
                                                                        <select name="kewarganegaraan"
                                                                            class="form-select @error('kewarganegaraan') is-invalid @enderror"
                                                                            required>
                                                                            <option value="WNI"
                                                                                {{ old('kewarganegaraan', $item->kewarganegaraan) == 'WNI' ? 'selected' : '' }}>
                                                                                WNI</option>
                                                                            <option value="WNA"
                                                                                {{ old('kewarganegaraan', $item->kewarganegaraan) == 'WNA' ? 'selected' : '' }}>
                                                                                WNA</option>
                                                                        </select>
                                                                        @error('kewarganegaraan')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Nama Ayah -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Nama Ayah</label>
                                                                        <input type="text" name="nama_ayah"
                                                                            class="form-control @error('nama_ayah') is-invalid @enderror"
                                                                            value="{{ old('nama_ayah', $item->nama_ayah) }}"
                                                                            required>
                                                                        @error('nama_ayah')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Nama Ibu -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Nama Ibu</label>
                                                                        <input type="text" name="nama_ibu"
                                                                            class="form-control @error('nama_ibu') is-invalid @enderror"
                                                                            value="{{ old('nama_ibu', $item->nama_ibu) }}"
                                                                            required>
                                                                        @error('nama_ibu')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Jenis Warga -->
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Jenis Warga</label>
                                                                        <select name="jenis"
                                                                            class="form-select @error('jenis') is-invalid @enderror"
                                                                            required>
                                                                            <option value="penduduk"
                                                                                {{ old('jenis', $item->jenis) == 'penduduk' ? 'selected' : '' }}>
                                                                                Penduduk</option>
                                                                            <option value="pendatang"
                                                                                {{ old('jenis', $item->jenis) == 'pendatang' ? 'selected' : '' }}>
                                                                                Pendatang</option>
                                                                        </select>
                                                                        @error('jenis')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="modal-footer bg-light border-top-0">
                                                                <button type="submit" class="btn btn-warning">Simpan
                                                                    Perubahan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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
