<!-- Modal Tambah Mata Kuliah -->
<div class="modal fade" id="modalTambahMatkul" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-750px">
        <div class="modal-content">

            {{-- HEADER — di luar form agar Bootstrap flex chain tidak putus --}}
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

            {{-- FORM — hanya wrap body+footer agar scroll bekerja --}}
            <form id="form_tambah_matkul" method="POST" action="{{ route('matakuliah.store') }}"
                style="display:flex;flex-direction:column;flex:1 1 auto;overflow:hidden;min-height:0">
                @csrf

                <div class="modal-body pt-5 pb-4">
                    <div class="row g-4">

                        {{-- Kode MK --}}
                        <div class="col-md-5">
                            <label class="form-label required fw-semibold fs-7">Kode Mata Kuliah</label>
                            <input type="text" name="kode_mk" class="form-control form-control-solid"
                                placeholder="Contoh: TIF101" required value="{{ old('kode_mk') }}">
                        </div>

                        {{-- Bobot SKS --}}
                        <div class="col-md-3">
                            <label class="form-label required fw-semibold fs-7">Bobot SKS</label>
                            <input type="number" name="bobot" class="form-control form-control-solid"
                                placeholder="1–9" min="1" max="9" required value="{{ old('bobot') }}">
                        </div>

                        {{-- Jenis MK --}}
                        <div class="col-md-4">
                            <label class="form-label required fw-semibold fs-7">Jenis</label>
                            <select name="jenis" class="form-select form-select-solid" data-control="select2"
                                data-hide-search="true" data-placeholder="Jenis" required>
                                <option></option>
                                <option value="wajib" {{ old('jenis') == 'wajib' ? 'selected' : '' }}>Wajib</option>
                                <option value="pilihan" {{ old('jenis') == 'pilihan' ? 'selected' : '' }}>Pilihan
                                </option>
                                <option value="umum" {{ old('jenis') == 'umum' ? 'selected' : '' }}>Umum</option>
                            </select>
                        </div>

                        {{-- Nama MK --}}
                        <div class="col-12">
                            <label class="form-label required fw-semibold fs-7">Nama Mata Kuliah</label>
                            <input type="text" name="nama_mk" class="form-control form-control-solid"
                                placeholder="Nama lengkap mata kuliah" required value="{{ old('nama_mk') }}">
                        </div>

                        {{-- Dosen Pengampu --}}
                        <div class="col-12">
                            <label class="form-label required fw-semibold fs-7">Dosen Pengampu</label>
                            <select name="id_dosen" class="form-select form-select-solid" data-control="select2"
                                data-placeholder="Pilih Dosen" required>
                                <option></option>
                                @foreach ($dosen as $d)
                                    <option value="{{ $d->id }}"
                                        {{ old('id_dosen') == $d->id ? 'selected' : '' }}>
                                        {{ $d->user->nama ?? '-' }}
                                        @if ($d->nip)
                                            — NIP {{ $d->nip }}
                                        @endif
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
                        <div class="col-md-5">
                            <span class="text-muted fs-8 fw-semibold text-uppercase">Program Studi</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted fs-8 fw-semibold text-uppercase">Semester</span>
                        </div>
                        <div class="col">
                            <span class="text-muted fs-8 fw-semibold text-uppercase">
                                Rombel
                                <span class="badge badge-light-info fs-9 ms-1 text-lowercase">opsional</span>
                            </span>
                        </div>
                    </div>

                    {{-- Mapping rows container --}}
                    <div id="mapping-rows-tambah">
                        {{-- Baris pertama tidak bisa dihapus --}}
                        <div class="mapping-row border rounded-2 p-3 mb-2 bg-light position-relative">
                            <button type="button"
                                class="btn btn-icon btn-sm btn-light remove-mapping-tambah position-absolute top-0 end-0 m-1"
                                title="Hapus baris" style="width:22px;height:22px" disabled>
                                <i class="bi bi-x fs-7 text-danger"></i>
                            </button>
                            <div class="row g-2 mb-2">
                                <div class="col-md-5">
                                    <select name="mappings[0][prodi_id]"
                                        class="form-select form-select-solid form-select-sm select-prodi-tambah"
                                        required>
                                        <option value="">-- Pilih Prodi --</option>
                                        @foreach ($prodi as $p)
                                            <option value="{{ $p->id }}">{{ $p->nama_prodi }}
                                                ({{ $p->kode_prodi }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="mappings[0][semester]"
                                        class="form-select form-select-solid form-select-sm" required>
                                        <option value="">-- Semester --</option>
                                        @for ($s = 1; $s <= 14; $s++)
                                            <option value="{{ $s }}">Semester {{ $s }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            {{-- Area rombel: diisi AJAX setelah prodi dipilih --}}
                            <div class="rombel-section">
                                <div class="rombel-placeholder text-muted fs-9">
                                    <i class="bi bi-arrow-up me-1"></i>Pilih Prodi untuk memuat pilihan Rombel
                                </div>
                                <div class="rombel-loading d-none text-muted fs-9">
                                    <span class="spinner-border spinner-border-sm me-1"></span>Memuat rombel...
                                </div>
                                <div class="rombel-checkboxes d-none">
                                    <div class="d-flex flex-wrap gap-2 rombel-list"></div>
                                    <div class="text-muted fs-9 mt-1">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Kosongkan = berlaku untuk <strong>semua</strong> rombel di prodi ini
                                    </div>
                                </div>
                                <div class="rombel-empty d-none">
                                    <span class="text-warning fs-9">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Prodi ini belum memiliki Rombel.
                                        <a href="{{ route('rombel.index') }}" target="_blank" class="fw-bold">Buat
                                            Rombel →</a>
                                    </span>
                                </div>
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

                    {{--
                        Data rombel lokal — dikelompokkan per id_prodi.
                        JS membaca ini untuk lookup instant tanpa AJAX.
                    --}}
                    <script id="rombelDataCreate" type="application/json">
                        {!! json_encode(
                            ($allRombel ?? collect())->groupBy('id_prodi')->map(fn($group) =>
                                $group->map(fn($r) => [
                                    'id'         => $r->id,
                                    'nama'       => $r->nama_rombel,
                                    'kode'       => $r->kode_rombel,
                                    'tahun_awal' => $r->tahunMasuk?->tahun_awal,
                                ])->values()
                            )
                        ) !!}
                    </script>

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
            // ── URL base untuk AJAX rombel — TIDAK DIPAKAI (diganti lookup lokal) ──
            // var ROMBEL_API = '{{ url('matakuliah/rombel-by-prodi') }}/';

            // ── Data rombel lokal (di-embed oleh Blade, lookup tanpa AJAX) ────
            // Struktur: { "prodiId": [{ id, nama, kode, tahun_awal }, ...] }
            var rombelByProdi = {};
            try {
                var rawRombel = document.getElementById('rombelDataCreate');
                if (rawRombel) {
                    rombelByProdi = JSON.parse(rawRombel.textContent);
                }
            } catch (e) {
                console.error('[CreateMatkul] Gagal parse rombelData:', e);
            }

            var rombelCache = {}; // cache (dummy, untuk kompatibilitas)

            // ── Template string untuk options Prodi & Semester ────────────────
            const prodiOptions = `
        @foreach ($prodi as $p)
        <option value="{{ $p->id }}">{{ $p->nama_prodi }} ({{ $p->kode_prodi }})</option>
        @endforeach
    `;

            const semesterOptions = `
        @for ($s = 1; $s <= 14; $s++)
        <option value="{{ $s }}">Semester {{ $s }}</option>
        @endfor
    `;

            let tambahIndex = 1; // baris ke-0 sudah ada di HTML

            // ── Tambah baris mapping baru ─────────────────────────────────────
            document.getElementById('btn-tambah-mapping')?.addEventListener('click', function() {
                const container = document.getElementById('mapping-rows-tambah');
                const idx = tambahIndex++;

                const row = document.createElement('div');
                row.className = 'mapping-row border rounded-2 p-3 mb-2 bg-light position-relative';
                row.innerHTML = `
                    <button type="button"
                        class="btn btn-icon btn-sm btn-light remove-mapping-tambah position-absolute top-0 end-0 m-1"
                        title="Hapus baris" style="width:22px;height:22px">
                        <i class="bi bi-x fs-7 text-danger"></i>
                    </button>
                    <div class="row g-2 mb-2">
                        <div class="col-md-5">
                            <select name="mappings[${idx}][prodi_id]"
                                class="form-select form-select-solid form-select-sm select-prodi-tambah" required>
                                <option value="">-- Pilih Prodi --</option>
                                ${prodiOptions}
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="mappings[${idx}][semester]"
                                class="form-select form-select-solid form-select-sm" required>
                                <option value="">-- Semester --</option>
                                ${semesterOptions}
                            </select>
                        </div>
                    </div>
                    <div class="rombel-section">
                        <div class="rombel-placeholder text-muted fs-9">
                            <i class="bi bi-arrow-up me-1"></i>Pilih Prodi untuk memuat pilihan Rombel
                        </div>
                        <div class="rombel-loading d-none text-muted fs-9">
                            <span class="spinner-border spinner-border-sm me-1"></span>Memuat rombel...
                        </div>
                        <div class="rombel-checkboxes d-none">
                            <div class="d-flex flex-wrap gap-2 rombel-list"></div>
                            <div class="text-muted fs-9 mt-1">
                                <i class="bi bi-info-circle me-1"></i>
                                Kosongkan = berlaku untuk <strong>semua</strong> rombel di prodi ini
                            </div>
                        </div>
                        <div class="rombel-empty d-none">
                            <span class="text-warning fs-9">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Prodi ini belum memiliki Rombel.
                                <a href="{{ route('rombel.index') }}" target="_blank" class="fw-bold">Buat Rombel →</a>
                            </span>
                        </div>
                    </div>`;

                container.appendChild(row);
                updateRemoveButtons('mapping-rows-tambah', 'remove-mapping-tambah');
            });

            // ── Hapus baris (delegasi) ────────────────────────────────────────
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-mapping-tambah')) {
                    const row = e.target.closest('.mapping-row');
                    const container = document.getElementById('mapping-rows-tambah');
                    if (container && container.querySelectorAll('.mapping-row').length > 1) {
                        row.remove();
                        updateRemoveButtons('mapping-rows-tambah', 'remove-mapping-tambah');
                    }
                }
            });

            // ── Perubahan Prodi → load rombel via AJAX ────────────────────────
            document.getElementById('mapping-rows-tambah').addEventListener('change', function(e) {
                if (!e.target.classList.contains('select-prodi-tambah')) return;

                const row = e.target.closest('.mapping-row');
                const prodiId = e.target.value;

                if (!prodiId) {
                    resetRombelArea(row);
                    return;
                }

                // Ambil index mapping dari name="mappings[N][prodi_id]"
                const match = e.target.name.match(/mappings\[(\d+)\]/);
                const idx = match ? match[1] : null;

                loadRombelForRow(row, prodiId, [], idx);
            });

            // ── Load rombel — LOOKUP LOKAL (tidak ada AJAX) ──────────────────
            function loadRombelForRow(row, prodiId, selectedIds, mappingIdx) {
                const $placeholder = row.querySelector('.rombel-placeholder');
                const $loading = row.querySelector('.rombel-loading');
                const $checkboxes = row.querySelector('.rombel-checkboxes');
                const $list = row.querySelector('.rombel-list');
                const $empty = row.querySelector('.rombel-empty');

                $placeholder.classList.add('d-none');
                $empty.classList.add('d-none');
                $checkboxes.classList.add('d-none');
                $list.innerHTML = '';

                // Lookup langsung dari data lokal — instant, tanpa network
                const data = rombelByProdi[prodiId] || rombelByProdi[String(prodiId)] || [];
                renderRombel(row, data, selectedIds, mappingIdx);
            }

            // ── Render checkbox rombel ────────────────────────────────────────
            function renderRombel(row, data, selectedIds, mappingIdx) {
                const $loading = row.querySelector('.rombel-loading');
                const $checkboxes = row.querySelector('.rombel-checkboxes');
                const $list = row.querySelector('.rombel-list');
                const $empty = row.querySelector('.rombel-empty');

                $loading.classList.add('d-none');

                if (!data || data.length === 0) {
                    $empty.classList.remove('d-none');
                    return;
                }

                const prefix = 'rbl_' + Date.now() + '_';
                data.forEach(r => {
                    const isChecked = selectedIds && selectedIds.includes(r.id);
                    const nameAttr = mappingIdx !== null ?
                        `mappings[${mappingIdx}][rombel_ids][]` :
                        'mappings[?][rombel_ids][]';

                    const item = document.createElement('div');
                    item.className = 'form-check form-check-inline me-0';
                    item.innerHTML = `
                        <input class="form-check-input rombel-checkbox"
                               type="checkbox"
                               id="${prefix}${r.id}"
                               name="${nameAttr}"
                               value="${r.id}"
                               ${isChecked ? 'checked' : ''}>
                        <label class="form-check-label cursor-pointer" for="${prefix}${r.id}">
                            <span class="badge badge-light-primary fs-9">${escHtml(r.nama)}</span>
                            ${r.tahun_awal ? `<span class="text-muted fs-9 ms-1">${r.tahun_awal}</span>` : ''}
                        </label>`;
                    $list.appendChild(item);
                });

                $checkboxes.classList.remove('d-none');
            }

            // ── Reset area rombel ─────────────────────────────────────────────
            function resetRombelArea(row) {
                row.querySelector('.rombel-loading').classList.add('d-none');
                row.querySelector('.rombel-empty').classList.add('d-none');
                row.querySelector('.rombel-checkboxes').classList.add('d-none');
                row.querySelector('.rombel-list').innerHTML = '';
                row.querySelector('.rombel-placeholder').classList.remove('d-none');
            }

            // ── Disable tombol hapus jika hanya 1 baris ───────────────────────
            function updateRemoveButtons(containerId, btnClass) {
                const container = document.getElementById(containerId);
                if (!container) return;
                const rows = container.querySelectorAll('.mapping-row');
                container.querySelectorAll('.' + btnClass).forEach(btn => {
                    btn.disabled = rows.length <= 1;
                });
            }

            // ── Reset form saat modal ditutup ─────────────────────────────────
            document.getElementById('modalTambahMatkul')?.addEventListener('hidden.bs.modal', function() {
                rombelCache = {};
            });

            function escHtml(str) {
                return (str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            }
        })();
    </script>
@endpush
