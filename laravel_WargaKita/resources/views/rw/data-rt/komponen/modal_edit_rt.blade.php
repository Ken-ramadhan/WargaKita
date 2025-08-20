{{-- Ini adalah loop untuk menampilkan beberapa modal edit jika Anda memiliki daftar RT.
     Sesuaikan variabel '$rukunTetanggaList' dengan nama variabel yang Anda gunakan
     untuk melewatkan daftar RT dari controller ke view. --}}
<div class="modal fade" id="modalEditRT{{ $rt->id }}" tabindex="-1"
    aria-labelledby="modalEditRTLabel{{ $rt->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalEditRTLabel{{ $rt->id }}">Edit Data RT</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            {{-- Tambahkan id_for_modal untuk menandai modal mana yang aktif saat ada error --}}
            <form action="{{ route('rukun_tetangga.update', $rt->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_for_modal" value="{{ $rt->id }}"> {{-- Penting untuk validasi --}}

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="no_kk{{ $rt->id }}" class="form-label">NO KK </label>
                        {{-- Gunakan old() untuk mempertahankan nilai input jika validasi gagal --}}
                        <input type="text" class="form-control @error('no_kk') is-invalid @enderror"
                            name="no_kk" id="no_kk{{ $rt->id }}"
                            value="{{ old('no_kk', $rt->no_kk) }}"
                            maxlength="16" required>
                        <small class="form-text text-muted">Nomor KK (16 digit). Pastikan terdaftar di database Kartu Keluarga.</small>
                        @error('no_kk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nik{{ $rt->id }}" class="form-label">NIK Pengurus RT</label>
                        <input type="text" class="form-control @error('nik') is-invalid @enderror"
                            name="nik" id="nik{{ $rt->id }}"
                            value="{{ old('nik', $rt->nik) }}"
                            maxlength="16" required>
                        <small class="form-text text-muted">NIK pengurus RT (16 digit), juga sebagai username login.</small>
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="rt_num{{ $rt->id }}" class="form-label">Nomor RT</label>
                        <input type="text" class="form-control @error('rt') is-invalid @enderror"
                            name="rt" id="rt_num{{ $rt->id }}"
                            value="{{ old('rt', $rt->rt) }}"
                            maxlength="10" required>
                        <small class="form-text text-muted">Masukkan Nomor RT (misal: 01, 002).</small>
                        @error('rt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nama{{ $rt->id }}" class="form-label">Nama Pengurus RT</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                            name="nama" id="nama{{ $rt->id }}"
                            value="{{ old('nama', $rt->nama) }}"
                            required>
                        <small class="form-text text-muted">Masukkan nama lengkap pengurus RT.</small>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="mulai_menjabat{{ $rt->id }}" class="form-label">Mulai Masa Jabatan</label>
                        <input type="date" name="mulai_menjabat" id="mulai_menjabat{{ $rt->id }}"
                            value="{{ old('mulai_menjabat', $rt->mulai_menjabat) }}"
                            required class="form-control @error('mulai_menjabat') is-invalid @enderror">
                        <small class="form-text text-muted">Pilih tanggal mulai masa jabatan pengurus RT.</small>
                        @error('mulai_menjabat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="akhir_jabatan{{ $rt->id }}" class="form-label">Akhir Masa Jabatan</label>
                        <input type="date" name="akhir_jabatan" id="akhir_jabatan{{ $rt->id }}"
                            value="{{ old('akhir_jabatan', $rt->akhir_jabatan) }}"
                            required class="form-control @error('akhir_jabatan') is-invalid @enderror">
                        <small class="form-text text-muted">Pilih tanggal akhir masa jabatan pengurus RT.</small>
                        @error('akhir_jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jabatan{{ $rt->id }}" class="form-label">Jabatan</label>
                        <select name="jabatan" id="jabatan{{ $rt->id }}"
                            class="form-select @error('jabatan') is-invalid @enderror">
                            <option value="">-- Pilih Jabatan --</option>
                            <option value="ketua" {{ old('jabatan', $rt->jabatan) === 'ketua' ? 'selected' : '' }}>Ketua</option>
                            <option value="sekretaris" {{ old('jabatan', $rt->jabatan) === 'sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                            <option value="bendahara" {{ old('jabatan', $rt->jabatan) === 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                        </select>
                        <small class="form-text text-muted">Pilih jabatan pengurus RT.</small>
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning w-100">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>


