<div class="modal fade {{ session('showModal') === 'kk_tambah' ? 'show d-block' : '' }}" id="modalTambahKK" tabindex="-1"
    aria-labelledby="modalTambahKKLabel" aria-hidden="{{ session('showModal') === 'kk_tambah' ? 'false' : 'true' }}"
    style="{{ session('showModal') === 'kk_tambah' ? 'background-color: rgba(0,0,0,0.5);' : '' }}">
    
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow border-0">
            <form action="{{ route('rt_kartu_keluarga.store') }}" method="POST">
                @csrf
                {{-- Input tersembunyi untuk mengidentifikasi formulir ini secara spesifik untuk old() dan errors --}}
                <input type="hidden" name="form_type" value="kk_tambah"> 

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTambahKKLabel">Tambah Data Kartu Keluarga</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>

                <div class="modal-body px-4 py-3" style="max-height: 85vh; overflow-y: auto;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nomor KK</label>
                            <input type="text" name="no_kk" maxlength="16" pattern="\d{16}" required
                                value="{{ old('form_type') === 'kk_tambah' ? old('no_kk') : '' }}"
                                class="form-control {{ $errors->has('no_kk') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                            {{-- Tampilkan kesalahan validasi untuk no_kk --}}
                            @if ($errors->has('no_kk') && old('form_type') === 'kk_tambah')
                                <div class="invalid-feedback">{{ $errors->first('no_kk') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nomor Registrasi</label>
                            <input type="text" name="no_registrasi"
                                value="{{ old('form_type') === 'kk_tambah' ? old('no_registrasi') : '' }}"
                                class="form-control {{ $errors->has('no_registrasi') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                            @if ($errors->has('no_registrasi') && old('form_type') === 'kk_tambah')
                                <div class="invalid-feedback">{{ $errors->first('no_registrasi') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kategori Iuran</label>
                            <select name="kategori_iuran"
                                class="form-select {{ $errors->has('kategori_iuran') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}"
                                required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategori_iuran as $kategori)
                                    <option value="{{ $kategori }}"
                                        {{ old('form_type') === 'kk_tambah' && old('kategori_iuran') == $kategori ? 'selected' : '' }}>
                                        {{ ucfirst($kategori) }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('kategori_iuran') && old('form_type') === 'kk_tambah')
                                <div class="invalid-feedback">
                                    {{ $errors->first('kategori_iuran') }}
                                </div>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" rows="2" required
                                class="form-control {{ $errors->has('alamat') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">{{ old('form_type') === 'kk_tambah' ? old('alamat') : '' }}</textarea>
                            @if ($errors->has('alamat') && old('form_type') === 'kk_tambah')
                                <div class="invalid-feedback">{{ $errors->first('alamat') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kelurahan</label>
                            <input type="text" name="kelurahan" required
                                value="{{ old('form_type') === 'kk_tambah' ? old('kelurahan') : '' }}"
                                class="form-control {{ $errors->has('kelurahan') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                            @if ($errors->has('kelurahan') && old('form_type') === 'kk_tambah')
                                <div class="invalid-feedback">{{ $errors->first('kelurahan') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" required
                                value="{{ old('form_type') === 'kk_tambah' ? old('kecamatan') : '' }}"
                                class="form-control {{ $errors->has('kecamatan') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                            @if ($errors->has('kecamatan') && old('form_type') === 'kk_tambah')
                                <div class="invalid-feedback">{{ $errors->first('kecamatan') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kabupaten/Kota</label>
                            <input type="text" name="kabupaten" required
                                value="{{ old('form_type') === 'kk_tambah' ? old('kabupaten') : '' }}"
                                class="form-control {{ $errors->has('kabupaten') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                            @if ($errors->has('kabupaten') && old('form_type') === 'kk_tambah')
                                <div class="invalid-feedback">{{ $errors->first('kabupaten') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="provinsi" required
                                value="{{ old('form_type') === 'kk_tambah' ? old('provinsi') : '' }}"
                                class="form-control {{ $errors->has('provinsi') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                            @if ($errors->has('provinsi') && old('form_type') === 'kk_tambah')
                                <div class="invalid-feedback">{{ $errors->first('provinsi') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kode Pos</label>
                            <input type="text" name="kode_pos" required
                                value="{{ old('form_type') === 'kk_tambah' ? old('kode_pos') : '' }}"
                                class="form-control {{ $errors->has('kode_pos') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                            @if ($errors->has('kode_pos') && old('form_type') === 'kk_tambah')
                                <div class="invalid-feedback">{{ $errors->first('kode_pos') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Instansi Penerbit</label>
                            <input type="text" name="instansi_penerbit"
                                value="{{ old('form_type') === 'kk_tambah' ? old('instansi_penerbit') : '' }}"
                                class="form-control {{ $errors->has('instansi_penerbit') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                            @if ($errors->has('instansi_penerbit') && old('form_type') === 'kk_tambah')
                                <div class="invalid-feedback">{{ $errors->first('instansi_penerbit') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"> Kabupaten/Kota Penerbit</label>
                            <input type="text" name="kabupaten_kota_penerbit"
                                value="{{ old('form_type') === 'kk_tambah' ? old('kabupaten_kota_penerbit') : '' }}"
                                class="form-control {{ $errors->has('kabupaten_kota_penerbit') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                            @if ($errors->has('kabupaten_kota_penerbit') && old('form_type') === 'kk_tambah')
                                <div class="invalid-feedback">{{ $errors->first('kabupaten_kota_penerbit') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"> Nama Kepala Dukcapil</label>
                            <input type="text" name="nama_kepala_dukcapil"
                                value="{{ old('form_type') === 'kk_tambah' ? old('nama_kepala_dukcapil') : '' }}"
                                class="form-control {{ $errors->has('nama_kepala_dukcapil') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                            @if ($errors->has('nama_kepala_dukcapil') && old('form_type') === 'kk_tambah')
                                <div class="invalid-feedback">{{ $errors->first('nama_kepala_dukcapil') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"> NIP Kepala Dukcapil</label>
                            <input type="text" name="nip_kepala_dukcapil"
                                value="{{ old('form_type') === 'kk_tambah' ? old('nip_kepala_dukcapil') : '' }}"
                                class="form-control {{ $errors->has('nip_kepala_dukcapil') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                            @if ($errors->has('nip_kepala_dukcapil') && old('form_type') === 'kk_tambah')
                                <div class="invalid-feedback">{{ $errors->first('nip_kepala_dukcapil') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Terbit</label>
                            <input type="date" name="tgl_terbit" required
                                value="{{ old('form_type') === 'kk_tambah' ? old('tgl_terbit') : '' }}"
                                class="form-control {{ $errors->has('tgl_terbit') && old('form_type') === 'kk_tambah' ? 'is-invalid' : '' }}">
                            @if ($errors->has('tgl_terbit') && old('form_type') === 'kk_tambah')
                                <div class="invalid-feedback">{{ $errors->first('tgl_terbit') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-0 ">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Kartu Keluarga
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>