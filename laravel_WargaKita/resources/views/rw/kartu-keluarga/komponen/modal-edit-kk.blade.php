<div class="modal fade" id="modalEditkk{{ $kk->no_kk }}" tabindex="-1"
    aria-labelledby="modalEditkkLabel{{ $kk->no_kk }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow border-0">
            <form action="{{ route('kartu_keluarga.update', $kk->no_kk) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="modalEditkkLabel{{ $kk->no_kk }}">
                        Edit Data Kartu Keluarga
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>

                {{-- Ini penting: Input hidden untuk menyimpan no_kk yang sedang diedit. --}}
                <input type="hidden" name="old_no_kk_for_modal" value="{{ $kk->no_kk }}">

                <div class="modal-body px-4 py-3" style="max-height: 85vh; overflow-y: auto;">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Nomor KK</label>
                            <input type="text" name="no_kk" maxlength="16" pattern="\d{16}" required
                                value="{{ old('no_kk', $kk->no_kk) }}"
                                class="form-control @error('no_kk') is-invalid @enderror">
                            @error('no_kk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nomor Registrasi</label>
                            <input type="text" name="no_registrasi" maxlength="16" pattern="\d{16}"
                                value="{{ old('no_registrasi', $kk->no_registrasi) }}" {{-- Hapus 'required' jika nullable --}}
                                class="form-control @error('no_registrasi') is-invalid @enderror">
                            @error('no_registrasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">No RT</label>
                            @php
                                // Pastikan old('id_rt') digunakan jika ada error validasi
                                $selectedRtId = old('id_rt') !== null ? old('id_rt') : ($kk->id_rt ?? '');
                            @endphp
                            <select name="id_rt" class="form-select @error('id_rt') is-invalid @enderror">
                                <option value="" {{ $selectedRtId === '' ? 'selected' : '' }}>
                                    Pilih No RT
                                </option>
                                {{-- Gunakan variabel yang di-compact dari controller untuk dropdown edit --}}
                                @foreach ($all_rukun_tetangga as $rt_data)
                                    <option value="{{ $rt_data->id }}"
                                        {{ $selectedRtId == $rt_data->id ? 'selected' : '' }}>
                                        RT {{ $rt_data->rt }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_rt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kategori Iuran</label>
                            @php
                                $selectedKategori = old('kategori_iuran') !== null ? old('kategori_iuran') : ($kk->kategori_iuran ?? '');
                            @endphp
                            <select name="kategori_iuran"
                                class="form-select @error('kategori_iuran') is-invalid @enderror" required>
                                <option value="" disabled {{ $selectedKategori === '' ? 'selected' : '' }}>
                                    Pilih Kategori Iuran
                                </option>
                                @foreach ($kategori_iuran as $kategori)
                                    <option value="{{ $kategori }}"
                                        {{ $selectedKategori == $kategori ? 'selected' : '' }}>
                                        {{ ucfirst($kategori) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_iuran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" rows="2" required
                                class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $kk->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kelurahan</label>
                            <input type="text" name="kelurahan" maxlength="100" required
                                value="{{ old('kelurahan', $kk->kelurahan) }}"
                                class="form-control @error('kelurahan') is-invalid @enderror">
                            @error('kelurahan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" maxlength="100" required
                                value="{{ old('kecamatan', $kk->kecamatan) }}"
                                class="form-control @error('kecamatan') is-invalid @enderror">
                            @error('kecamatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kabupaten/Kota</label>
                            <input type="text" name="kabupaten" maxlength="100" required
                                value="{{ old('kabupaten', $kk->kabupaten) }}"
                                class="form-control @error('kabupaten') is-invalid @enderror">
                            @error('kabupaten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="provinsi" maxlength="100" required
                                value="{{ old('provinsi', $kk->provinsi) }}"
                                class="form-control @error('provinsi') is-invalid @enderror">
                            @error('provinsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kode Pos</label>
                            <input type="text" name="kode_pos" maxlength="10" required
                                value="{{ old('kode_pos', $kk->kode_pos) }}"
                                class="form-control @error('kode_pos') is-invalid @enderror">
                            @error('kode_pos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Instansi Penerbit</label>
                            <input type="text" name="instansi_penerbit" maxlength="100" required
                                value="{{ old('instansi_penerbit', $kk->instansi_penerbit) }}"
                                class="form-control @error('instansi_penerbit') is-invalid @enderror">
                            @error('instansi_penerbit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kabupaten/Kota Penerbit</label>
                            <input type="text" name="kabupaten_kota_penerbit" maxlength="100" required
                                value="{{ old('kabupaten_kota_penerbit', $kk->kabupaten_kota_penerbit) }}"
                                class="form-control @error('kabupaten_kota_penerbit') is-invalid @enderror">
                            @error('kabupaten_kota_penerbit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Kepala Dukcapil</label>
                            <input type="text" name="nama_kepala_dukcapil" maxlength="100" required
                                value="{{ old('nama_kepala_dukcapil', $kk->nama_kepala_dukcapil) }}"
                                class="form-control @error('nama_kepala_dukcapil') is-invalid @enderror">
                            @error('nama_kepala_dukcapil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">NIP Kepala Dukcapil</label>
                            <input type="text" name="nip_kepala_dukcapil" maxlength="50" required
                                value="{{ old('nip_kepala_dukcapil', $kk->nip_kepala_dukcapil) }}"
                                class="form-control @error('nip_kepala_dukcapil') is-invalid @enderror">
                            @error('nip_kepala_dukcapil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Terbit</label>
                            <input type="date" name="tgl_terbit" required
                                value="{{ old('tgl_terbit', $kk->tgl_terbit) }}"
                                class="form-control @error('tgl_terbit') is-invalid @enderror">
                            @error('tgl_terbit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="submit" class="btn btn-warning text-white">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>