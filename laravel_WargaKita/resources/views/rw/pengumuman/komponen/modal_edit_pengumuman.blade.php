<div class="modal fade" id="modalEditPengumuman{{ $data->id }}" tabindex="-1"
    aria-labelledby="modalEditPengumumanLabel{{ $data->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalEditPengumumanLabel{{ $data->id }}">Edit Pengumuman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <form action="{{ route('pengumuman.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body modal-body-scroll px-4">
                    <div class="mb-3">
                        <label for="judul{{ $data->id }}" class="form-label">Judul</label>
                        <input type="text" name="judul" id="judul{{ $data->id }}" required
                            value="{{ old('judul', $data->judul) }}"
                            class="form-control @error('judul') is-invalid @enderror"
                            placeholder="Masukkan Judul Pengumuman">
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kategori{{ $data->id }}" class="form-label">Kategori</label>
                        <input type="text" name="kategori" id="kategori{{ $data->id }}"
                            value="{{ old('kategori', $data->kategori) }}"
                            class="form-control @error('kategori') is-invalid @enderror"
                            placeholder="Masukkan kategori">
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="isi{{ $data->id }}" class="form-label">Isi</label>
                        <textarea name="isi" id="isi{{ $data->id }}" rows="5" required
                            class="form-control @error('isi') is-invalid @enderror" placeholder="Masukkan Isi Pengumuman">{{ old('isi', $data->isi) }}</textarea> @error('isi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal{{ $data->id }}" class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal{{ $data->id }}" required
                            value="{{ old('tanggal', \Carbon\Carbon::parse($data->tanggal)->format('Y-m-d')) }}"
                            class="form-control @error('tanggal') is-invalid @enderror">
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="mb-3">
                        <label for="dokumen{{ $data->id }}" class="form-label">Dokumen (Opsional)</label>
                        <input type="file" name="dokumen" id="dokumen{{ $data->id }}"
                            class="form-control @error('dokumen') is-invalid @enderror" accept=".doc,.docx,.pdf">
                        <div class="form-text">Unggah file Word (.doc, .docx) atau PDF (.pdf) untuk mengganti dokumen
                            yang sudah ada.</div>
                        @error('dokumen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        {{-- Tampilkan dokumen saat ini jika ada --}}
                        @if ($data->dokumen_path)
                            <div class="mt-2 p-3 border rounded bg-light">
                                <p class="mb-1">**Dokumen Saat Ini:**</p>
                                <a href="{{ Storage::url($data->dokumen_path) }}" target="_blank"
                                    class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-file-earmark-text"></i>
                                    {{ $data->dokumen_name ?: 'Lihat Dokumen' }}
                                </a>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="hapus_dokumen_lama"
                                        id="hapusDokumenLama{{ $data->id }}" value="1"
                                        {{ old('hapus_dokumen_lama') ? 'checked' : '' }}> <label
                                        class="form-check-label" for="hapusDokumenLama{{ $data->id }}">
                                        Centang untuk **Hapus** Dokumen Ini
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1">Jika Anda mengunggah file baru, dokumen lama akan
                                    otomatis dihapus.</small>
                            </div>
                        @else
                            <div class="mt-2 text-muted">Belum ada dokumen yang terlampir.</div>
                        @endif
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
