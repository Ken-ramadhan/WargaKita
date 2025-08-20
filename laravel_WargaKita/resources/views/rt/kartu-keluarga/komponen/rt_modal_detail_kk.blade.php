{{-- PENTING: Untuk produksi, pindahkan CSS ini ke file .css terpisah --}}
<style>
    /* --- CSS Kartu Keluarga & Tabel (yang sudah ada) --- */
    .kk-container {
        border: 1px solid #ccc;
        padding: 25px;
        font-family: 'Times New Roman', serif;
        font-size: 0.95rem;
        line-height: 1.6;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .kk-header {
        text-align: center;
        margin-bottom: 30px;
        position: relative;
    }

    .kk-header-top-line {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        width: 100%;
        margin-bottom: 5px;
    }

    .kk-header-left-space {
        width: 60px;
        height: 1px;
    }

    .kk-header-right-reg p {
        text-align: right;
        margin-bottom: 0;
        font-size: 0.9rem;
        font-weight: bold;
        color: #333;
    }

    .kk-header-main-title {
        text-align: center;
        margin-top: -20px;
    }

    .kk-header h4 {
        font-weight: bold;
        margin-bottom: 5px;
        font-size: 1.8rem;
        color: #000000;
    }

    .kk-header p.no-kk-big {
        font-size: 1.1rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 0;
    }

    .kk-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px 30px;
        margin-bottom: 25px;
    }

    .kk-info-item p {
        margin-bottom: 5px;
        display: flex;
    }

    .kk-info-item strong {
        display: inline-block;
        min-width: 170px;
        flex-shrink: 0;
        margin-right: 10px;
        color: #495057;
    }

    .kk-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-family: 'Times New Roman', serif;
    }

    .kk-table th,
    .kk-table td {
        font-size: 0.8rem;
        padding: 6px 8px;
        border: 1px solid #000;
        vertical-align: top;
        line-height: 1.2;
        color: #000;
    }

    .kk-table tbody td:nth-child(1),
    .kk-table tbody td:nth-child(4),
    .kk-table tbody td:nth-child(6),
    .kk-table tbody td:nth-child(10),
    .kk-table tbody td:nth-child(11),
    .kk-table tbody td:nth-child(12),
    .kk-table tbody td:nth-child(14),
    .kk-table tbody td:nth-child(15),
    .kk-table tbody td:nth-child(16),
    .kk-table tbody td:nth-child(19) {
        text-align: center;
    }

    /* Ini bisa diterapkan pada modalDetailkk juga jika Anda ingin lebih besar */
    .modal.modal-fullscreen .modal-dialog {
        width: 100vw;
        height: 100vh;
        max-width: 100vw;
        margin: 0;
    }

    .modal.modal-fullscreen .modal-content {
        height: 100%;
        border-radius: 0;
        overflow: hidden;
        /* Penting untuk iframe */
    }

    .modal.modal-fullscreen .modal-body {
        flex: 1;
        /* Agar mengambil sisa ruang */
        display: flex;
        /* Untuk memposisikan iframe */
        justify-content: center;
        align-items: center;
        overflow: hidden;
        /* Mencegah scroll ganda */
    }

    .modal.modal-fullscreen iframe {
        width: 100%;
        height: 100%;
    }

    /* --- Gaya Tampilan Dokumen KK (Thumbnail) --- */
    .kk-document-section {
        margin-top: 2rem;
        /* Added margin for separation */
    }

    .kk-document-display {
        position: relative;
        width: 200px;
        height: 150px;
        border: 1px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #f8f9fa;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 20px;
    }

    .kk-document-display img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* Gaya khusus untuk thumbnail PDF */
    .pdf-thumbnail-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        background-color: #e9ecef;
        /* Warna latar belakang untuk thumbnail PDF */
    }

    .pdf-icon {
        font-size: 3rem;
        /* Ukuran ikon PDF */
        color: #dc3545;
        /* Warna ikon PDF */
        margin-bottom: 5px;
    }

    .pdf-filename {
        font-size: 0.8rem;
        color: #6c757d;
        text-align: center;
        padding: 0 5px;
        word-break: break-all;
    }

    /* Overlay dan teks tanpa dokumen */
    .kk-document-display .view-document-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
    }

    .kk-document-display:hover .view-document-overlay {
        opacity: 1;
    }

    .kk-document-display .view-document-overlay i {
        color: white;
        font-size: 2rem;
    }

    .no-document-text {
        color: #6c757d;
        font-style: italic;
    }
</style>

{{-- Modal Detail Kartu Keluarga --}}
<div class="modal fade" id="modalDetailkk{{ $kk->no_kk }}" tabindex="-1"
    aria-labelledby="modalDetailkkLabel{{ $kk->no_kk }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow border-0">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalDetailkkLabel{{ $kk->no_kk }}">
                    Detail Kartu Keluarga
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>

            <div class="modal-body p-4">
                <div class="kk-container">
                    <div class="kk-header">
                        <div class="kk-header-top-line">
                            <div class="kk-header-left-space">
                                {{-- Ini adalah tempat untuk logo Garuda jika Anda ingin meletakkannya tanpa mengganggu alignment teks --}}
                            </div>
                            <div class="kk-header-right-reg">
                                @if ($kk->no_registrasi)
                                    <p>No. Registrasi:
                                        <strong>{{ $kk->no_registrasi }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="kk-header-main-title">
                            <h4>KARTU KELUARGA</h4>
                            <p class="no-kk-big">No. KK: <strong>{{ $kk->no_kk }}</strong></p>
                        </div>
                    </div>

                    @php
                        $kepala = optional($kk->warga)->firstWhere('status_hubungan_dalam_keluarga', 'kepala keluarga');
                        $ibu_keluarga = optional($kk->warga)->firstWhere('status_hubungan_dalam_keluarga', 'istri');
                    @endphp

                    <div class="kk-info-grid mb-4">
                        <div class="kk-info-item">
                            <p><strong>Nama Kepala Keluarga</strong> : {{ $kepala->nama ?? '-' }}</p>
                            <p><strong>Alamat</strong> : {{ $kk->alamat ?? '-' }}</p>
                            <p><strong>RT/RW</strong> :
                                {{ $kk->rukunTetangga->rt ?? '-' }}/{{ $kk->rw->nomor_rw ?? '-' }}
                            </p>
                            <p><strong>Desa/Kelurahan</strong> : {{ $kk->kelurahan ?? '-' }}</p>
                        </div>
                        <div class="kk-info-item">
                            <p><strong>Kecamatan</strong> : {{ $kk->kecamatan ?? '-' }}</p>
                            <p><strong>Kabupaten/Kota</strong> : {{ $kk->kabupaten ?? '-' }}</p>
                            <p><strong>Kode Pos</strong> : {{ $kk->kode_pos ?? '-' }}</p>
                            <p><strong>Provinsi</strong> : {{ $kk->provinsi ?? '-' }}</p>
                        </div>
                    </div>

                    <hr class="my-4" style="border-top: 2px solid #e0e0e0;">

                    <div class="mx-auto text-end mb-3">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalTambahWarga" data-no_kk="{{ $kk->no_kk }}" {{-- Mengambil nama kepala keluarga dari relasi warga yang sudah di-eager load --}}
                            data-nama_ayah="{{ $kk->warga->firstWhere('status_hubungan_dalam_keluarga', 'kepala keluarga')->nama ?? '' }}"
                            {{-- Mengambil nama istri dari relasi warga yang sudah di-eager load (jika ada) --}}
                            data-nama_ibu="{{ $kk->warga->firstWhere('status_hubungan_dalam_keluarga', 'istri')->nama ?? '' }}">
                            <i class="fas fa-plus"></i> Tambah Anggota Keluarga
                        </button>
                    </div>

                    <h6 class="text-dark mb-3 fw-bold text-center">DAFTAR ANGGOTA KELUARGA</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle kk-table table-striped">
                            <thead class="text-center table-success">
                                <tr>
                                    <th rowspan="2">No.</th>
                                    <th rowspan="2">Nama Lengkap</th>
                                    <th rowspan="2">NIK</th>
                                    <th rowspan="2">Jenis Kelamin</th>
                                    <th colspan="2">Tempat, Tanggal Lahir</th>
                                    <th rowspan="2">Agama</th>
                                    <th rowspan="2">Pendidikan</th>
                                    <th rowspan="2">Jenis Pekerjaan</th>
                                    <th rowspan="2">Golongan Darah</th>
                                    <th rowspan="2">Status Perkawinan</th>
                                    <th rowspan="2">Status Hubungan Dlm Keluarga</th>
                                    <th rowspan="2">Kewarganegaraan</th>
                                    <th colspan="2">Dokumen Imigrasi</th>
                                    <th colspan="2">Nama Orang Tua</th>
                                    <th rowspan="2">Status Warga</th>
                                    <th rowspan="2">Aksi</th>
                                </tr>
                                <tr>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>No. Paspor</th>
                                    <th>No. KITAS/KITAP</th>
                                    <th>Nama Ayah</th>
                                    <th>Nama Ibu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kk->warga->sortByDesc(function($item) {
                                        return $item->status_hubungan_dalam_keluarga === 'kepala keluarga' ? 1 : ($item->status_hubungan_dalam_keluarga === 'Istri' ? 0.5 : 0);
                                    }) as $data)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $data->nama ?? '-' }}</td>
                                        <td>{{ $data->nik ?? '-' }}</td>
                                        <td class="text-center">{{ $data->jenis_kelamin ?? '-' }}</td>
                                        <td>{{ $data->tempat_lahir ?? '-' }}</td>
                                        <td class="text-center">
                                            {{ $data->tanggal_lahir ? \Carbon\Carbon::parse($data->tanggal_lahir)->format('d-m-Y') : '-' }}
                                        </td>
                                        <td>{{ $data->agama ?? '-' }}</td>
                                        <td>{{ $data->pendidikan ?? '-' }}</td>
                                        <td>{{ $data->pekerjaan ?? '-' }}</td>
                                        <td class="text-center">{{ $data->golongan_darah ?? '-' }}</td>
                                        <td class="text-center">{{ $data->status_perkawinan ?? '-' }}</td>
                                        <td>{{ $data->status_hubungan_dalam_keluarga ?? '-' }}</td>
                                        <td class="text-center">{{ $data->kewarganegaraan ?? 'WNI' }}</td>
                                        <td class="text-center">{{ $data->no_paspor ?? '-' }}</td>
                                        <td class="text-center">
                                            {{ $data->no_kitas ?? '-' }}/{{ $data->no_kitap ?? '-' }}</td>
                                        <td>{{ $data->nama_ayah ?? '-' }}</td>
                                        <td>{{ $data->nama_ibu ?? '-' }}</td>
                                        <td class="text-center">{{ $data->status_warga ?? '-' }}</td>
                                        <td class="text-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center gap-1 flex-nowrap">
                                                <form action="{{ route('rt_warga.destroy', $data->nik) }}" method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    <input type="hidden" name="redirect_to"
                                                        value="{{ route('rt_kartu_keluarga.index') }}">
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-danger d-flex align-items-center"
                                                        title="Hapus Warga">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                                <button type="button"
                                                    class="btn btn-sm btn-warning d-flex align-items-center"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditwarga{{ $data->nik }}"
                                                    title="Edit Warga">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="19" class="text-center text-muted p-4">
                                            Belum ada anggota keluarga yang terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <hr class="my-4" style="border-top: 2px solid #e0e0e0;">

                    <div class="kk-footer">
                        <hr class="my-4" style="border-top: 2px dashed #a0a0a0;">
                        <p class="mb-3"><strong>Dikeluarkan Tanggal</strong> :
                            {{ \Carbon\Carbon::parse($kk->tgl_terbit)->isoFormat('D MMMM Y') ?? '-' }}
                        </p>

                        <div class="d-flex justify-content-between align-items-start text-center ps-3">
                            <div class="footer-left" style="width: 48%;">
                                <p class="mb-5">
                                    Mengetahui, <br>
                                    Kepala Keluarga
                                </p>
                                <p class="mb-0">
                                    <strong>{{ $kepala->nama ?? '_____________________' }}</strong>
                                </p>
                                <p style="font-size: 0.8rem; color: #6c757d;">(Tanda Tangan)</p>
                            </div>

                            <div class="footer-right" style="width: 48%;">
                                <p class="mb-0">
                                    {{ $kk->kabupaten_kota_penerbit ?? '___________' }},
                                    {{ \Carbon\Carbon::parse($kk->tgl_terbit)->isoFormat('D MMMM Y') }}
                                </p>
                                <p class="mb-5">
                                    {{ $kk->instansi_penerbit ?? 'KEPALA DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL' }}
                                </p>
                                <p class="mb-0">
                                    <strong>{{ $kk->nama_kepala_dukcapil ?? '_____________________' }}</strong>
                                </p>
                                <p>NIP.
                                    {{ $kk->nip_kepala_dukcapil ?? '_____________________' }}
                                </p>
                            </div>
                        </div> {{-- End kk-footer --}}
                    </div> {{-- End kk-container --}}


                    <hr class="my-4" style="border-top: 2px solid #e0e0e0;">

                    {{-- Bagian untuk Dokumen KK (Unggah dan Tampil) --}}
                    <div class="kk-document-section">
                        <div class="kk-document-upload">
                            <h6 class="fw-bold mb-3">Unggah/Perbarui Dokumen Kartu Keluarga</h6>
                            <form action="{{ route('rt_kartu_keluarga.upload_foto', $kk->no_kk) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="input-group">
                                    <label for="kkFile" class="form-label visually-hidden">Pilih Dokumen KK</label>
                                    <input type="file" class="form-control" id="kkFile" name="kk_file"
                                        accept=".pdf, .jpg, .jpeg, ;.png">
                                    <button class="btn btn-success" type="submit" id="button-upload-foto">
                                        <i class="fas fa-upload me-1"></i> Unggah
                                    </button>
                                </div>
                                <small class="form-text text-muted mt-1">Format yang didukung: PDF, JPG, JPEG, PNG
                                    (Maks. 5MB).</small>
                                @error('kk_file')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </form>
                            @if ($kk->foto_kk)
                                <form action="{{ route('rt_kartu_keluarga.delete_foto', $kk->no_kk) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger mt-2">
                                        <i class="fas fa-times-circle me-1"></i> Hapus Dokumen
                                    </button>
                                </form>
                            @endif
                        </div>

                        {{-- Bagian untuk Dokumen KK (Tampil) --}}
                        <div class="kk-document-display">
                            @if ($kk->foto_kk)
                                @php
                                    $fileExtension = pathinfo($kk->foto_kk, PATHINFO_EXTENSION);
                                    $isPdf = in_array(strtolower($fileExtension), ['pdf']);
                                    $filePath = asset('storage/' . $kk->foto_kk);
                                @endphp

                                @if ($isPdf)
                                    <div class="pdf-thumbnail-container"
                                        onclick="openDocumentModal('{{ $filePath }}', true)">
                                        <i class="far fa-file-pdf pdf-icon"></i>
                                        <p class="pdf-filename">Lihat PDF</p>
                                    </div>
                                @else
                                    <img src="{{ $filePath }}" alt="Dokumen Kartu Keluarga {{ $kk->no_kk }}"
                                        onclick="openDocumentModal('{{ $filePath }}', false)">
                                @endif
                                <div class="view-document-overlay"
                                    onclick="openDocumentModal('{{ $filePath }}', {{ $isPdf ? 'true' : 'false' }})">
                                    <i class="fas fa-eye"></i>
                                </div>
                            @else
                                <p class="no-document-text">Tidak ada dokumen KK</p>
                            @endif
                        </div>
                    </div>
                    {{-- End kk-document-section --}}

                </div> {{-- End modal-body --}}

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        <i class="bi bi-check2-circle"></i> Tutup
                    </button>
                </div>
            </div> {{-- End modal-body --}}
        </div> {{-- End modal-content --}}
    </div> {{-- End modal-dialog --}}
</div> {{-- End modalDetailkk --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modalTambahWarga');

        if (modal) {
            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                if (!button) return;

                const noKK = button.getAttribute('data-no_kk') || '';
                const namaAyah = button.getAttribute('data-nama_ayah') || '';
                const namaIbu = button.getAttribute('data-nama_ibu') || '';

                // Validasi: jika KK kosong, batalkan modal
                if (noKK === '') {
                    event.preventDefault(); // Batalkan tampil modal
                    alert(
                        'Nomor KK belum tersedia. Simpan data KK terlebih dahulu sebelum menambahkan anggota.');
                    return;
                }

                // Isi field input KK
                document.getElementById('modal_no_kk').value = noKK;
                document.getElementById('modal_no_kk_show').value = noKK;

                // Simpan ke hidden untuk auto-nama ayah/ibu
                document.getElementById('kk_nama_ayah_auto').value = namaAyah;
                document.getElementById('kk_nama_ibu_auto').value = namaIbu;
            });
        }

        // Logika isi nama ayah/ibu jika status anak
        const hubunganSelect = document.getElementById('hubungan_keluarga');
        if (hubunganSelect) {
            hubunganSelect.addEventListener('change', function() {
                const status = this.value;
                const inputAyah = document.getElementById('input_nama_ayah');
                const inputIbu = document.getElementById('input_nama_ibu');

                if (status === 'anak') {
                    const autoAyah = document.getElementById('kk_nama_ayah_auto').value;
                    const autoIbu = document.getElementById('kk_nama_ibu_auto').value;

                    if (inputAyah.value.trim() === '') inputAyah.value = autoAyah;
                    if (inputIbu.value.trim() === '') inputIbu.value = autoIbu;
                } else {
                    inputAyah.value = '';
                    inputIbu.value = '';
                }
            });
        }
    });
</script>
