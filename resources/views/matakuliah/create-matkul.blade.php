<!-- Modal Tambah Mata Kuliah -->
<div class="modal fade" id="modalTambahMatkul" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-750px">
        <div class="modal-content">
            <form id="form_tambah_matkul" method="POST" action="{{ route('matakuliah.store') }}">
                @csrf
                <div class="modal-header bg-light-primary border-0 pb-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="symbol symbol-45px symbol-circle bg-primary">
                            <span class="symbol-label">
                                <i class="bi bi-book-fill text-white fs-4"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bolder text-dark mb-0">Tambah Mata Kuliah</h5>
                            <span class="text-muted fs-8">Isi form berikut untuk menambah mata kuliah baru</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body pt-5 pb-4">
                    <div class="row g-4">

                        {{-- Kode MK --}}
                        <div class="col-md-5">
                            <label class="form-label required fw-semibold fs-7">Kode Mata Kuliah</label>
                            <input type="text" name="kode_mk"
                                class="form-control form-control-solid"
                                placeholder="Contoh: TIF101"
                                required
                                value="{{ old('kode_mk') }}">
                        </div>

                        {{-- Bobot SKS --}}
                        <div class="col-md-3">
                            <label class="form-label required fw-semibold fs-7">Bobot SKS</label>
                            <input type="number" name="bobot"
                                class="form-control form-control-solid"
                                placeholder="1–9" min="1" max="9"
                                required
                                value="{{ old('bobot') }}">
                        </div>

                        {{-- Jenis MK --}}
                        <div class="col-md-4">
                            <label class="form-label required fw-semibold fs-7">Jenis</label>
                            <select name="jenis"
                                class="form-select form-select-solid"
                                data-control="select2"
                                data-hide-search="true"
                                data-placeholder="Jenis"
                                required>
                                <option></option>
                                <option value="wajib"   {{ old('jenis') == 'wajib'   ? 'selected' : '' }}>Wajib</option>
                                <option value="pilihan" {{ old('jenis') == 'pilihan' ? 'selected' : '' }}>Pilihan</option>
                                <option value="umum"    {{ old('jenis') == 'umum'    ? 'selected' : '' }}>Umum</option>
                            </select>
                        </div>

                        {{-- Nama MK --}}
                        <div class="col-12">
                            <label class="form-label required fw-semibold fs-7">Nama Mata Kuliah</label>
                            <input type="text" name="nama_mk"
                                class="form-control form-control-solid"
                                placeholder="Nama lengkap mata kuliah"
                                required
                                value="{{ old('nama_mk') }}">
                        </div>

                        {{-- Dosen Pengampu --}}
                        <div class="col-12">
                            <label class="form-label required fw-semibold fs-7">Dosen Pengampu</label>
                            <select name="id_dosen"
                                class="form-select form-select-solid"
                                data-control="select2"
                                data-placeholder="Pilih Dosen"
                                required>
                                <option></option>
                                @foreach($dosen as $d)
                                <option value="{{ $d->id }}" {{ old('id_dosen') == $d->id ? 'selected' : '' }}>
                                    {{ $d->user->nama ?? '-' }}
                                    @if($d->nip) — NIP {{ $d->nip }} @endif
                                </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- ===== MAPPING PRODI & SEMESTER ===== --}}
                    <div class="separator separator-dashed my-5"></div>

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="fw-bolder text-dark mb-0">
                                <i class="bi bi-diagram-3 me-1 text-primary"></i>
                                Mapping Prodi & Semester
                            </h6>
                            <span class="text-muted fs-8">
                                MK ini akan muncul di kombinasi Prodi + Semester berikut.
                                MK Umum pun bisa dipetakan ke semester yang berbeda di tiap prodi.
                            </span>
                        </div>
                    </div>

                    {{-- Header kolom --}}
                    <div class="row g-2 mb-2 px-1">
                        <div class="col">
                            <span class="text-muted fs-8 fw-semibold text-uppercase">Program Studi</span>
                        </div>
                        <div class="col-auto" style="width:170px">
                            <span class="text-muted fs-8 fw-semibold text-uppercase">Semester</span>
                        </div>
                        <div class="col-auto" style="width:40px"></div>
                    </div>

                    {{-- Mapping rows container --}}
                    <div id="mapping-rows-tambah">
                        {{-- Baris pertama tidak bisa dihapus --}}
                        <div class="mapping-row d-flex align-items-center gap-2 mb-2">
                            <div class="flex-grow-1">
                                <select name="mappings[0][prodi_id]"
                                    class="form-select form-select-solid form-select-sm select2-prodi-tambah"
                                    required>
                                    <option value="">-- Pilih Prodi --</option>
                                    @foreach($prodi as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_prodi }} ({{ $p->kode_prodi }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="width:170px; flex-shrink:0">
                                <select name="mappings[0][semester]"
                                    class="form-select form-select-solid form-select-sm"
                                    required>
                                    <option value="">-- Semester --</option>
                                    @for($s = 1; $s <= 14; $s++)
                                    <option value="{{ $s }}">Semester {{ $s }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div style="width:36px; flex-shrink:0">
                                <button type="button"
                                    class="btn btn-icon btn-sm btn-light remove-mapping-tambah"
                                    title="Hapus baris" disabled>
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="btn-tambah-mapping" class="btn btn-sm btn-light-primary mt-1">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Prodi & Semester
                    </button>

                    {{-- Error mapping --}}
                    @error('mappings')
                        <div class="text-danger fs-8 mt-2">{{ $message }}</div>
                    @enderror

                </div>

                <div class="modal-footer border-0 pt-0 justify-content-between">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Simpan Mata Kuliah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    // Template untuk baris prodi baru
    const prodiOptions = `
        @foreach($prodi as $p)
        <option value="{{ $p->id }}">{{ $p->nama_prodi }} ({{ $p->kode_prodi }})</option>
        @endforeach
    `;

    const semesterOptions = `
        @for($s = 1; $s <= 14; $s++)
        <option value="{{ $s }}">Semester {{ $s }}</option>
        @endfor
    `;

    let tambahIndex = 1; // Baris ke-0 sudah ada di HTML

    // Tambah baris mapping baru
    document.getElementById('btn-tambah-mapping')?.addEventListener('click', function () {
        const container = document.getElementById('mapping-rows-tambah');
        const idx = tambahIndex++;

        const row = document.createElement('div');
        row.className = 'mapping-row d-flex align-items-center gap-2 mb-2';
        row.innerHTML = `
            <div class="flex-grow-1">
                <select name="mappings[${idx}][prodi_id]"
                    class="form-select form-select-solid form-select-sm"
                    required>
                    <option value="">-- Pilih Prodi --</option>
                    ${prodiOptions}
                </select>
            </div>
            <div style="width:170px; flex-shrink:0">
                <select name="mappings[${idx}][semester]"
                    class="form-select form-select-solid form-select-sm"
                    required>
                    <option value="">-- Semester --</option>
                    ${semesterOptions}
                </select>
            </div>
            <div style="width:36px; flex-shrink:0">
                <button type="button"
                    class="btn btn-icon btn-sm btn-light remove-mapping-tambah"
                    title="Hapus baris">
                    <i class="bi bi-trash text-danger"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
        updateRemoveButtons('mapping-rows-tambah', 'remove-mapping-tambah');
    });

    // Hapus baris (delegasi event)
    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-mapping-tambah')) {
            const row = e.target.closest('.mapping-row');
            const container = document.getElementById('mapping-rows-tambah');
            if (container && container.querySelectorAll('.mapping-row').length > 1) {
                row.remove();
                updateRemoveButtons('mapping-rows-tambah', 'remove-mapping-tambah');
            }
        }
    });

    // Disable tombol hapus jika hanya tersisa 1 baris
    function updateRemoveButtons(containerId, btnClass) {
        const container = document.getElementById(containerId);
        if (!container) return;
        const rows = container.querySelectorAll('.mapping-row');
        container.querySelectorAll('.' + btnClass).forEach(btn => {
            btn.disabled = rows.length <= 1;
        });
    }
})();
</script>
@endpush
