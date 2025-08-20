<div class="modal fade" id="modalDetailPengumuman{{ $data->id }}"
    tabindex="-1" aria-labelledby="modalDetailLabel{{ $data->id }}"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title mb-0"
                    id="modalDetailLabel{{ $data->id }}">
                    Detail Pengumuman
                </h5>
                <button type="button" class="btn-close btn-close-white"
                    data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body px-4 pt-4 pb-3">
                <h4 class="fw-bold text-success mb-3">{{ $data->judul }}</h4>

                <ul class="list-unstyled mb-3 small">
                    <li>
                        <strong>Kategori:</strong>
                        <span class="ms-1">{{ $data->kategori ?? '-' }}</span>
                    </li>

                    <li>
                        <strong>Tanggal:</strong>
                        <span class="ms-1">{{ \Carbon\Carbon::parse($data->tanggal)->isoFormat('dddd, D MMMM Y') }}</span>
                    </li>
                    @if ($data->id_rt)
                        <li>
                            <strong>RT:</strong>
                            {{ $data->rukunTetangga->rt ?? '-' }}
                        </li>
                    @else
                        <li>
                            <strong>RW:</strong>
                            {{ $data->rw->nomor_rw ?? '-' }}
                        </li>
                    @endif
                </ul>

                <hr class="my-2">

                <div class="mb-2">
                    <strong class="d-block mb-1">Isi Pengumuman:</strong>
                    <div class="border rounded bg-light p-3"
                        style="line-height: 1.6;">
                        {{ $data->isi }}
                    </div>
                </div>

                {{-- Tambahkan bagian ini untuk menampilkan dokumen --}}
                @if ($data->dokumen_path)
                <div class="mb-3">
                    <strong class="d-block mb-1">Dokumen Terlampir:</strong>
                    <p class="mb-2">
                        <a href="{{ Storage::url($data->dokumen_path) }}" target="_blank" class="btn btn-sm btn-info text-white">
                            <i class="fas fa-file-download me-1"></i> Unduh Dokumen ({{ $data->dokumen_name ?? 'Dokumen' }})
                        </a>
                    </p>
                    <small class="text-muted">
                        Dokumen akan dibuka di tab baru.
                    </small>
                </div>
                @endif
                {{-- Akhir bagian dokumen --}}

            </div>
            <div
                class="modal-footer bg-light border-0 justify-content-end py-2">
                <button type="button" class="btn btn-outline-success"
                    data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>