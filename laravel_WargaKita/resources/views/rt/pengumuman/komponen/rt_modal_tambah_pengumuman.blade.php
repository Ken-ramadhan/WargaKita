<div class="modal fade" id="modalTambahPengumuman" tabindex="-1"
    aria-labelledby="modalTambahPengumumanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTambahPengumumanLabel">Tambah Pengumuman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>

            {{-- Tambahkan enctype="multipart/form-data" pada form --}}
            <form action="{{ route('rt_pengumuman.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4" style="max-height: 70vh; overflow-y: auto;">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" name="judul" id="judul" required
                            value="{{ old('judul') }}"
                            class="form-control @error('judul') is-invalid @enderror"
                            placeholder="Masukkan Judul Pengumuman">
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" name="kategori" id="kategori"
                            value="{{ old('kategori') }}"
                            class="form-control @error('kategori') is-invalid @enderror"
                            placeholder="Masukkan kategori">
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="isi" class="form-label">Isi</label>
                        <textarea name="isi" id="isi" rows="5" required
                            class="form-control @error('isi') is-invalid @enderror" placeholder="Masukkan Isi Pengumuman">{{ old('isi') }}</textarea>
                        @error('isi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" required
                            value="{{ old('tanggal') }}"
                            class="form-control @error('tanggal') is-invalid @enderror">
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tambahkan input untuk unggah dokumen --}}
                    <div class="mb-3">
                        <label for="dokumen" class="form-label">Dokumen (Opsional, Max 2MB: .doc, .docx, .pdf)</label>
                        <input type="file" name="dokumen" id="dokumen"
                            class="form-control @error('dokumen') is-invalid @enderror">
                        @error('dokumen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>