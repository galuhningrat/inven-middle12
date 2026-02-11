@extends('mahasiswa.detail-mahasiswa')

@section('dokumen')
    <div class="card">
        <div class="card-header flex-wrap gap-3 align-items-center justify-content-between">
            <div class="card-title m-0 d-flex align-items-center gap-3">
                <h3 class="fw-bolder m-0">Dokumen Mahasiswa</h3>
            </div>
            <div class="d-flex gap-2">
                <a href="#" class="btn btn-primary btn-sm px-4" data-bs-toggle="modal"
                    data-bs-target="#modalUploadDokumen">
                    <i class="ki-duotone ki-cloud-upload me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i> Upload Dokumen
                </a>
            </div>
        </div>

        <div class="card-body p-6 p-md-9">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filter Jenis Dokumen -->
            <div class="mb-5">
                <label class="form-label fw-bold">Filter Jenis Dokumen:</label>
                <select class="form-select form-select-sm w-250px" id="filterJenisDokumen">
                    <option value="">Semua Dokumen</option>
                    <option value="Ijazah">Ijazah</option>
                    <option value="Transkrip">Transkrip</option>
                    <option value="KTP">KTP</option>
                    <option value="KK">Kartu Keluarga</option>
                    <option value="Akta Lahir">Akta Lahir</option>
                    <option value="Foto">Foto</option>
                    <option value="Sertifikat">Sertifikat</option>
                    <option value="Surat Keterangan">Surat Keterangan</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table table-row-dashed align-middle gs-0 gy-4" id="tbl-dokumen">
                    <thead>
                        <tr class="fw-bold text-muted text-uppercase fs-7 border-bottom-2 border-gray-200">
                            <th class="min-w-250px ps-4">Nama Dokumen</th>
                            <th class="min-w-100px">Jenis</th>
                            <th class="min-w-100px text-end">Ukuran</th>
                            <th class="min-w-150px text-center">Tanggal Upload</th>
                            <th class="min-w-100px text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse($mahasiswa->dokumen as $file)
                            <tr class="row-file" data-jenis="{{ strtolower(trim($file->jenis_dokumen)) }}">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-3">
                                            <span class="symbol-label bg-light-primary">
                                                @if (in_array(strtolower($file->ekstensi), ['pdf']))
                                                    <i class="bi bi-file-earmark-pdf text-danger fs-2"></i>
                                                @elseif(in_array(strtolower($file->ekstensi), ['jpg', 'jpeg', 'png', 'gif']))
                                                    <i class="bi bi-file-earmark-image text-info fs-2"></i>
                                                @elseif(in_array(strtolower($file->ekstensi), ['doc', 'docx']))
                                                    <i class="bi bi-file-earmark-word text-primary fs-2"></i>
                                                @else
                                                    <i class="bi bi-file-earmark text-dark fs-2"></i>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                                class="text-gray-800 text-hover-primary mb-1 fs-6 fw-bold text-truncate"
                                                style="max-width: 350px;" title="{{ $file->nama_dokumen }}">
                                                {{ $file->nama_dokumen }}
                                            </a>
                                            <div class="text-muted fs-8">
                                                {{ strtoupper($file->ekstensi) }}
                                                @if ($file->keterangan)
                                                    <span class="mx-1">•</span> {{ Str::limit($file->keterangan, 30) }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary fs-7 fw-bold">{{ $file->jenis_dokumen }}</span>
                                </td>
                                <td class="text-end text-gray-700 fw-bold">
                                    {{ number_format($file->ukuran_file / 1024, 0) }} KB
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-light fw-bold text-gray-600">
                                        {{ $file->created_at->format('d M Y, H:i') }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end flex-shrink-0 gap-2">
                                        <a href="{{ asset('storage/' . $file->file_path) }}"
                                            class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm"
                                            data-bs-toggle="tooltip" title="Download" target="_blank">
                                            <i class="bi bi-download fs-5"></i>
                                        </a>

                                        <form
                                            action="{{ route('mahasiswa.dokumen.destroy', [$mahasiswa->nim, $file->id]) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm"
                                                data-bs-toggle="tooltip" title="Hapus"
                                                onclick="return confirm('Yakin hapus dokumen ini?\n\nFile: {{ $file->nama_dokumen }}')">
                                                <i class="bi bi-trash fs-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="db-empty-state">
                                <td colspan="5" class="text-center py-10">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="symbol symbol-60px mb-3">
                                            <span class="symbol-label bg-light rounded-circle">
                                                <i class="bi bi-folder-x fs-1 text-gray-400"></i>
                                            </span>
                                        </div>
                                        <div class="fw-bold fs-5 text-gray-600 mb-1">Belum Ada Dokumen</div>
                                        <div class="text-muted fs-7">Silakan upload dokumen melalui tombol di atas.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                        {{-- State kosong hasil Filter --}}
                        <tr id="filter-empty-state" style="display: none;">
                            <td colspan="5" class="text-center py-10 text-muted">
                                <i class="bi bi-search fs-2 mb-2 d-block text-gray-400"></i>
                                <span class="fs-7">Tidak ada dokumen dengan jenis tersebut.</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Info Summary -->
            @if ($mahasiswa->dokumen && $mahasiswa->dokumen->count() > 0)
                <div class="alert alert-light-info d-flex align-items-center mt-8">
                    <i class="bi bi-info-circle fs-2 me-3"></i>
                    <div>
                        <strong>Total: {{ $mahasiswa->dokumen->count() }} dokumen</strong>
                        <span class="mx-2">•</span>
                        <span>Total Ukuran: {{ number_format($mahasiswa->dokumen->sum('ukuran_file') / 1024 / 1024, 2) }}
                            MB</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Upload Dokumen -->
    <div class="modal fade" id="modalUploadDokumen" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formUploadDokumen" class="needs-validation"
                    action="{{ route('mahasiswa.dokumen.store', $mahasiswa->nim) }}" method="POST"
                    enctype="multipart/form-data" novalidate>
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Upload Dokumen</h5>
                        <button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x fs-2"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="namaDokumen" class="form-label required">Nama Dokumen</label>
                            <input type="text" id="namaDokumen" name="nama_dokumen" class="form-control"
                                placeholder="Mis. Ijazah SMA" required value="{{ old('nama_dokumen') }}">
                            @error('nama_dokumen')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback">Nama dokumen wajib diisi.</div>
                        </div>

                        <div class="mb-4">
                            <label for="jenisDokumen" class="form-label required">Jenis Dokumen</label>
                            <select id="jenisDokumen" name="jenis_dokumen" class="form-select" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Ijazah">Ijazah</option>
                                <option value="Transkrip">Transkrip Nilai</option>
                                <option value="KTP">KTP</option>
                                <option value="KK">Kartu Keluarga</option>
                                <option value="Akta Lahir">Akta Lahir</option>
                                <option value="Foto">Foto</option>
                                <option value="Sertifikat">Sertifikat</option>
                                <option value="Surat Keterangan">Surat Keterangan</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            @error('jenis_dokumen')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback">Pilih jenis dokumen.</div>
                        </div>

                        <div class="mb-4">
                            <label for="fileDokumen" class="form-label required">Upload File</label>
                            <input type="file" id="fileDokumen" name="file" class="form-control"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                            @error('file')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback">Silakan pilih file untuk diupload.</div>
                            <div class="form-text">Format: PDF, DOC, DOCX, JPG, PNG. Maksimal 10MB.</div>
                        </div>

                        <div class="mb-2">
                            <label for="keteranganDokumen" class="form-label">Keterangan (Opsional)</label>
                            <textarea id="keteranganDokumen" name="keterangan" class="form-control" rows="2"
                                placeholder="Tambahkan keterangan jika diperlukan..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cloud-upload me-2"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterJenis = document.getElementById('filterJenisDokumen');
            const rows = document.querySelectorAll('.row-file');

            // Buat elemen "Data Tidak Ditemukan" secara dinamis jika belum ada
            const tableBody = document.querySelector('#tbl-dokumen tbody');
            const emptyRow = document.createElement('tr');
            emptyRow.id = 'js-empty-state';
            emptyRow.style.display = 'none';
            emptyRow.innerHTML =
                `<td colspan="5" class="text-center py-10 text-muted">Dokumen tidak ditemukan</td>`;
            tableBody.appendChild(emptyRow);

            if (filterJenis) {
                filterJenis.addEventListener('change', function() {
                    const target = this.value.toLowerCase().trim();
                    let found = 0;

                    rows.forEach(row => {
                        // Ambil data-jenis dari atribut TR, lalu bersihkan spasi
                        const jenis = row.getAttribute('data-jenis').toLowerCase().trim();

                        if (target === "" || jenis === target) {
                            row.style.display = '';
                            found++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Tampilkan pesan jika hasil filter 0
                    emptyRow.style.display = (found === 0) ? '' : 'none';
                });
            }
        });
    </script>
@endpush
