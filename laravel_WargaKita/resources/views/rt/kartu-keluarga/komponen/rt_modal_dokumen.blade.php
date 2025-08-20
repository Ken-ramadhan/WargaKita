<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center d-flex flex-column align-items-center justify-content-center">
                        {{-- Gunakan flexbox untuk memusatkan konten --}}
                        <img id="modalImage" class="img-fluid" style="display: none; max-height: 80vh; margin: auto;"
                            alt="Dokumen KK">
                        {{-- Iframe untuk PDF, sesuaikan tinggi dan lebar --}}
                        <iframe id="modalPdfViewer" style="width: 100%; height: 100vh; border: none; display: none;"
                            frameborder="0"></iframe>
                        {{-- <div id="caption" class="mt-2 text-white"></div> --}}
                    </div>
                    <div class="modal-footer justify-content-between border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-outline-light" onclick="printDocument()"><i
                                class="fas fa-print"></i> Cetak Dokumen</button>
                    </div>
                </div>
            </div>
</div>