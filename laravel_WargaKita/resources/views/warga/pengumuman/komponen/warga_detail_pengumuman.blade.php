<div class="modal fade" id="modalDetailPengumuman{{ $data->id }}"
     tabindex="-1" aria-labelledby="modalDetailLabel{{ $data->id }}"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title mb-0" id="modalDetailLabel{{ $data->id }}">
                    Detail Pengumuman
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body px-4 pt-4 pb-3">
                <h4 class="fw-bold text-success mb-2">{{ $data->judul }}</h4>

                <div class="d-flex align-items-center mb-3">
                    <span class="text-muted me-3">
                        <i class="bi bi-calendar me-1"></i> {{ \Carbon\Carbon::parse($data->tanggal)->isoFormat('dddd, D MMMM Y') }}
                    </span>
                    @if ($data->id_rt)
                        <span class="text-dark fw-semibold">
                            <i class="me-1"></i> Dari RT: {{ $data->rukunTetangga->rt ?? '-' }}
                        </span>
                    @else
                        <span class="text-dark fw-semibold">
                            <i class="me-1"></i> Dari RW: {{ $data->rw->nomor_rw ?? '-' }}
                        </span>
                    @endif
                </div>

                <ul class="list-unstyled mb-3 small">
                    <li><strong>Kategori:</strong> <span class="ms-1">{{ $data->kategori ?? '-' }}</span></li>
                </ul>

                <hr class="my-3">

                <div class="mb-4">
                    <h5 class="fw-bold text-success mb-2">Isi Pengumuman:</h5>
                    <div class="border rounded bg-light p-3" style="line-height: 1.6;">
                        {{ $data->isi }}
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="fw-bold text-success mb-2">Dokumen Terlampir:</h5>
                    @if ($data->dokumen_path)
                        <div class="border rounded bg-light p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <i class="bi bi-file-earmark-text me-2"></i>
                                <span>{{ $data->dokumen_name ?: 'Dokumen Terlampir' }}</span>
                                <small class="text-muted d-block mt-1">Klik tombol di samping untuk melihat atau mengunduh.</small>
                            </div>
                            <a href="{{ Storage::url($data->dokumen_path) }}" target="_blank" class="btn btn-primary btn-sm">
                                <i class="bi bi-download"></i> Unduh
                            </a>
                        </div>
                    @else
                        <div class="text-muted p-3 border rounded bg-light">
                            Tidak ada dokumen yang terlampir.
                        </div>
                    @endif
                </div>

            </div>
            <div class="modal-footer bg-light border-0 justify-content-end py-2">
                <button type="button" class="btn btn-outline-success"
                        data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>