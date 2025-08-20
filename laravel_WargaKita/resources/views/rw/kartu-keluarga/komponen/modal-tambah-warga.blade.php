@php
    $oldIfTambah = fn($field) => old('form_type') === 'tambah' ? old($field) : '';
    $errorIfTambah = fn($field) => $errors->has($field) && old('form_type') === 'tambah' ? 'is-invalid' : '';
@endphp

<div class="modal fade {{ session('showModal') === 'tambah' ? 'show d-block' : '' }}"
    id="modalTambahWarga" tabindex="-1" aria-labelledby="modalTambahWargaLabel"
    aria-hidden="{{ session('showModal') === 'tambah' ? 'false' : 'true' }}"
    style="{{ session('showModal') === 'tambah' ? 'background-color: rgba(0,0,0,0.5);' : '' }}">

    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTambahWargaLabel">Tambah Data Warga</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>

            <form action="{{ route('warga.store') }}" method="POST">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ route('kartu_keluarga.index') }}">
                <input type="hidden" name="form_type" value="tambah">
                <input type="hidden" name="no_kk" id="modal_no_kk" value="{{ $oldIfTambah('no_kk') }}">

                <div class="modal-body px-4" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row">
                        {{-- Kolom Form (dibagi dua) --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor KK</label>
                            <input type="text" name="no_kk_display" class="form-control" id="modal_no_kk_show"
                                value="{{ $oldIfTambah('no_kk') }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik" maxlength="16" class="form-control {{ $errorIfTambah('nik') }}"
                                value="{{ $oldIfTambah('nik') }}" required>
                            @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control {{ $errorIfTambah('nama') }}"
                                value="{{ $oldIfTambah('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hubungan Dalam Keluarga</label>
                            <select name="status_hubungan_dalam_keluarga"
                                class="form-select {{ $errorIfTambah('status_hubungan_dalam_keluarga') }}" id="status_hubungan_dalam_keluarga" required>
                                <option value="">-- Pilih --</option>
                                <option value="kepala keluarga" {{ $oldIfTambah('status_hubungan_dalam_keluarga') == 'kepala keluarga' ? 'selected' : '' }}>Kepala Keluarga</option>
                                <option value="istri" {{ $oldIfTambah('status_hubungan_dalam_keluarga') == 'istri' ? 'selected' : '' }}>Istri</option>
                                <option value="anak" {{ $oldIfTambah('status_hubungan_dalam_keluarga') == 'anak' ? 'selected' : '' }}>Anak</option>
                            </select>
                            @error('status_hubungan_dalam_keluarga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Ayah</label>
                            <input type="text" name="nama_ayah" id="nama_ayah" class="form-control {{ $errorIfTambah('nama_ayah') }}"
                                value="{{ $oldIfTambah('nama_ayah') }}" required>
                            @error('nama_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Ibu</label>
                            <input type="text" name="nama_ibu" id="nama_ibu" class="form-control {{ $errorIfTambah('nama_ibu') }}"
                                value="{{ $oldIfTambah('nama_ibu') }}" required>
                            @error('nama_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select {{ $errorIfTambah('jenis_kelamin') }}" required>
                                <option value="">-- Pilih --</option>
                                <option value="laki-laki" {{ $oldIfTambah('jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="perempuan" {{ $oldIfTambah('jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control {{ $errorIfTambah('tempat_lahir') }}"
                                value="{{ $oldIfTambah('tempat_lahir') }}" required>
                            @error('tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control {{ $errorIfTambah('tanggal_lahir') }}"
                                value="{{ $oldIfTambah('tanggal_lahir') }}" required>
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Agama</label>
                            <input type="text" name="agama" class="form-control {{ $errorIfTambah('agama') }}"
                                value="{{ $oldIfTambah('agama') }}" required>
                            @error('agama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pendidikan</label>
                            <input type="text" name="pendidikan" class="form-control {{ $errorIfTambah('pendidikan') }}"
                                value="{{ $oldIfTambah('pendidikan') }}" required>
                            @error('pendidikan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pekerjaan</label>
                            <input type="text" name="pekerjaan" class="form-control {{ $errorIfTambah('pekerjaan') }}"
                                value="{{ $oldIfTambah('pekerjaan') }}" required>
                            @error('pekerjaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Paspor</label>
                            <input type="text" name="no_paspor" class="form-control {{ $errorIfTambah('no_paspor') }}"
                                value="{{ $oldIfTambah('no_paspor') }}">
                            @error('no_paspor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Kitap</label>
                            <input type="text" name="no_kitap" class="form-control {{ $errorIfTambah('no_kitap') }}"
                                value="{{ $oldIfTambah('no_kitap') }}">
                            @error('no_kitap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">No Kitas</label>
                            <input type="text" name="no_kitas" class="form-control {{ $errorIfTambah('no_kitas') }}"
                                value="{{ $oldIfTambah('no_kitas') }}">
                            @error('no_kitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Perkawinan</label>
                            <select name="status_perkawinan" class="form-select {{ $errorIfTambah('status_perkawinan') }}" required>
                                <option value="">-- Pilih --</option>
                                @foreach (['belum menikah', 'menikah', 'cerai hidup', 'cerai mati'] as $status)
                                    <option value="{{ $status }}" {{ $oldIfTambah('status_perkawinan') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                            @error('status_perkawinan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Golongan Darah</label>
                            <select name="golongan_darah" class="form-select {{ $errorIfTambah('golongan_darah') }}" required>
                                <option value="">-- Pilih --</option>
                                @foreach (['A', 'B', 'AB', 'O'] as $gol)
                                    <option value="{{ $gol }}" {{ $oldIfTambah('golongan_darah') == $gol ? 'selected' : '' }}>{{ $gol }}</option>
                                @endforeach
                            </select>
                            @error('golongan_darah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Warga</label>
                            <select name="status_warga" class="form-select {{ $errorIfTambah('status_warga') }}" required>
                                <option value="">-- Pilih --</option>
                                <option value="penduduk" {{ $oldIfTambah('status_warga') == 'penduduk' ? 'selected' : '' }}>Penduduk</option>
                                <option value="pendatang" {{ $oldIfTambah('status_warga') == 'pendatang' ? 'selected' : '' }}>Pendatang</option>
                            </select>
                            @error('status_warga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kewarganegaraan</label>
                            <select name="kewarganegaraan" class="form-select {{ $errorIfTambah('kewarganegaraan') }}" required>
                                <option value="">-- Pilih --</option>
                                <option value="WNI" {{ $oldIfTambah('kewarganegaraan') == 'WNI' ? 'selected' : '' }}>WNI</option>
                                <option value="WNA" {{ $oldIfTambah('kewarganegaraan') == 'WNA' ? 'selected' : '' }}>WNA</option>
                            </select>
                            @error('kewarganegaraan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Hidden ayah/ibu otomatis --}}
                    <input type="hidden" id="kk_nama_ayah_auto" value="">
                    <input type="hidden" id="kk_nama_ibu_auto" value="">
                </div>

                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalTambahWarga = document.getElementById('modalTambahWarga');
        const statusHubunganSelect = modalTambahWarga.querySelector('#status_hubungan_dalam_keluarga');
        const namaAyahInput = modalTambahWarga.querySelector('#nama_ayah');
        const namaIbuInput = modalTambahWarga.querySelector('#nama_ibu');

        modalTambahWarga.addEventListener('show.bs.modal', function (event) {
            // Dapatkan data dari tombol yang memicu modal
            const button = event.relatedTarget;
            const namaAyahAuto = button.getAttribute('data-nama_ayah');
            const namaIbuAuto = button.getAttribute('data-nama_ibu');

            // Simpan nilai di bidang tersembunyi (atau langsung gunakan)
            modalTambahWarga.querySelector('#kk_nama_ayah_auto').value = namaAyahAuto;
            modalTambahWarga.querySelector('#kk_nama_ibu_auto').value = namaIbuAuto;

            // Reset bidang saat modal dibuka untuk mencegah nilai lama tetap ada
            statusHubunganSelect.value = '';
            namaAyahInput.value = '';
            namaIbuInput.value = '';
        });

        statusHubunganSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            const namaAyahAuto = modalTambahWarga.querySelector('#kk_nama_ayah_auto').value;
            const namaIbuAuto = modalTambahWarga.querySelector('#kk_nama_ibu_auto').value;

            if (selectedValue === 'anak') {
                namaAyahInput.value = namaAyahAuto;
                namaIbuInput.value = namaIbuAuto;
            } else {
                namaAyahInput.value = '';
                namaIbuInput.value = '';
            }
        });
    });
</script>