<div class="modal fade" id="modalEditPengumuman{{ $data->id }}"
    tabindex="-1"
    aria-labelledby="modalEditPengumumanLabel{{ $data->id }}"
    aria-hidden="true">
    <div class="modal-dialog modal-lg"> <div class="modal-content shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"
                    id="modalEditPengumumanLabel{{ $data->id }}">Edit
                    Pengumuman
                </h5>
                <button type="button" class="btn-close btn-close-white"
                    data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            {{-- Tambahkan enctype="multipart/form-data" pada form --}}
            <form action="{{ route('rt_pengumuman.update', $data->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body modal-body-scroll px-4">
                    <div class="mb-3">
                        <label for="judul{{ $data->id }}"
                            class="form-label">Judul</label>
                        <input type="text" name="judul"
                            id="judul{{ $data->id }}" required
                            value="{{ old('judul', $data->judul) }}" {{-- Gunakan old() untuk mempertahankan input jika ada error --}}
                            class="form-control @error('judul') is-invalid @enderror"
                            placeholder="Masukkan Judul Pengumuman">
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kategori{{ $data->id }}"
                            class="form-label">Kategori</label>
                        <input type="text" name="kategori"
                            id="kategori{{ $data->id }}" required
                            value="{{ old('kategori', $data->kategori) }}" {{-- Gunakan old() --}}
                            class="form-control @error('kategori') is-invalid @enderror"
                            placeholder="Masukkan kategori">
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="isi{{ $data->id }}"
                            class="form-label">Isi</label>
                        <textarea name="isi" id="isi{{ $data->id }}" rows="5" required
                            class="form-control @error('isi') is-invalid @enderror" placeholder="Masukkan Isi Pengumuman">{{ old('isi', $data->isi) }}</textarea> {{-- Gunakan old() --}}
                        @error('isi')
                            <div class="invalid-feedback">{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal{{ $data->id }}"
                            class="form-label">Tanggal</label>
                        <input type="date" name="tanggal"
                            id="tanggal{{ $data->id }}" required
                            value="{{ old('tanggal', \Carbon\Carbon::parse($data->tanggal)->format('Y-m-d')) }}" {{-- Gunakan old() --}}
                            class="form-control @error('tanggal') is-invalid @enderror">

                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Bagian untuk edit dokumen --}}
                    <div class="mb-3">
                        <label for="dokumen{{ $data->id }}" class="form-label">Dokumen (Opsional, Max 2MB: .doc, .docx, .pdf)</label>
                        @if ($data->dokumen_path)
                            <p class="mb-1">
                                Dokumen saat ini:
                                <a href="{{ Storage::url($data->dokumen_path) }}" target="_blank" class="text-decoration-none">
                                    <i class="fas fa-file-alt me-1"></i> {{ $data->dokumen_name ?? 'Dokumen' }}
                                </a>
                            </p>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="hapus_dokumen_lama" id="hapusDokumenLama{{ $data->id }}" value="1">
                                <label class="form-check-label" for="hapusDokumenLama{{ $data->id }}">
                                    Hapus dokumen ini
                                </label>
                            </div>
                        @else
                            <p class="text-muted">Tidak ada dokumen terlampir saat ini.</p>
                        @endif

                        <input type="file" name="dokumen" id="dokumen{{ $data->id }}"
                            class="form-control @error('dokumen') is-invalid @enderror">
                        <small class="form-text text-muted">Unggah dokumen baru untuk mengganti yang lama, atau biarkan kosong jika tidak ingin mengubah.</small>
                        @error('dokumen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Akhir bagian edit dokumen --}}

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>