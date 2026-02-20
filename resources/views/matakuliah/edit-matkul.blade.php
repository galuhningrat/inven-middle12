{{--
    edit-matkul.blade.php
    ─────────────────────────────────────────────────────────────────────
    Di-include dari index.blade.php di DALAM @section('content'),
    SEBELUM @endsection — JANGAN pernah di-include di luar </html>.

    Dipanggil dengan:
        @include('matakuliah.edit-matkul', ['m' => $m, 'prodi' => $prodi, 'dosen' => $dosen])
--}}

<div class="modal fade" id="modalEditMatkul{{ $m->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <form method="POST" action="{{ route('matakuliah.update', $m->id) }}">
                @csrf
                @method('PUT')

                {{-- ── HEADER ── --}}
                <div class="modal-header bg-light-success border-0 pb-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="symbol symbol-45px symbol-circle bg-success">
                            <span class="symbol-label">
                                <i class="bi bi-pencil-fill text-white fs-4"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bolder text-dark mb-0">Edit Mata Kuliah</h5>
                            <span class="text-muted fs-8">{{ $m->kode_mk }} — {{ $m->nama_mk }}</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                {{-- ── BODY ── --}}
                <div class="modal-body pt-5 pb-4">
                    <div class="row g-4">

                        {{-- Kode MK --}}
                        <div class="col-md-5">
                            <label class="form-label required fw-semibold fs-7">Kode Mata Kuliah</label>
                            <input type="text" name="kode_mk"
                                class="form-control form-control-solid"
                                value="{{ old('kode_mk', $m->kode_mk) }}"
                                required>
                        </div>

                        {{-- Bobot SKS --}}
                        <div class="col-md-3">
                            <label class="form-label required fw-semibold fs-7">Bobot SKS</label>
                            <input type="number" name="bobot"
                                class="form-control form-control-solid"
                                min="1" max="9"
                                value="{{ old('bobot', $m->bobot) }}"
                                required>
                        </div>

                        {{-- Jenis MK --}}
                        {{--
                            ✅ FIX Z-INDEX:
                            Hapus data-control="select2" dan data-hide-search="true".
                            Metronic's auto-init tidak support dropdownParent, sehingga
                            dropdown muncul di belakang modal. Ganti dengan class
                            "select2-in-modal" dan biarkan JS di bawah yang init manual.
                        --}}
                        <div class="col-md-4">
                            <label class="form-label required fw-semibold fs-7">Jenis</label>
                            <select name="jenis"
                                class="form-select form-select-solid select2-in-modal"
                                required>
                                <option value="wajib"   {{ old('jenis', $m->jenis) == 'wajib'   ? 'selected' : '' }}>Wajib</option>
                                <option value="pilihan" {{ old('jenis', $m->jenis) == 'pilihan' ? 'selected' : '' }}>Pilihan</option>
                                <option value="umum"    {{ old('jenis', $m->jenis) == 'umum'    ? 'selected' : '' }}>Umum</option>
                            </select>
                        </div>

                        {{-- Nama MK --}}
                        <div class="col-12">
                            <label class="form-label required fw-semibold fs-7">Nama Mata Kuliah</label>
                            <input type="text" name="nama_mk"
                                class="form-control form-control-solid"
                                value="{{ old('nama_mk', $m->nama_mk) }}"
                                required>
                        </div>

                        {{-- Dosen Pengampu --}}
                        {{-- ✅ FIX Z-INDEX: Ganti data-control="select2" → class select2-in-modal --}}
                        <div class="col-12">
                            <label class="form-label required fw-semibold fs-7">Dosen Pengampu</label>
                            <select name="id_dosen"
                                class="form-select form-select-solid select2-in-modal"
                                required>
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosen as $d)
                                    <option value="{{ $d->id }}"
                                        {{ old('id_dosen', $m->id_dosen) == $d->id ? 'selected' : '' }}>
                                        {{ $d->user->nama ?? '-' }}
                                        @if($d->nip) &mdash; NIP {{ $d->nip }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>{{-- /row --}}

                    {{-- ── MAPPING PRODI & SEMESTER ── --}}
                    <div class="separator separator-dashed my-5"></div>

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="fw-bolder text-dark mb-0">
                                <i class="bi bi-diagram-3 me-1 text-success"></i>
                                Mapping Prodi &amp; Semester
                            </h6>
                            <span class="text-muted fs-8">
                                Ubah daftar kombinasi Prodi + Semester tempat MK ini akan muncul.
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

                    @php
                        $existingMappings = $m->prodiMappings ?? collect();
                    @endphp

                    <div id="mapping-rows-edit-{{ $m->id }}">
                        @forelse($existingMappings as $mapIdx => $existingMap)
                        <div class="mapping-row d-flex align-items-center gap-2 mb-2">
                            <div class="flex-grow-1">
                                <select name="mappings[{{ $mapIdx }}][prodi_id]"
                                    class="form-select form-select-solid form-select-sm"
                                    required>
                                    <option value="">-- Pilih Prodi --</option>
                                    @foreach($prodi as $p)
                                        <option value="{{ $p->id }}"
                                            {{ $existingMap->id_prodi == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_prodi }} ({{ $p->kode_prodi }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="width:170px; flex-shrink:0">
                                <select name="mappings[{{ $mapIdx }}][semester]"
                                    class="form-select form-select-solid form-select-sm"
                                    required>
                                    <option value="">-- Semester --</option>
                                    @for($s = 1; $s <= 14; $s++)
                                        <option value="{{ $s }}"
                                            {{ $existingMap->semester == $s ? 'selected' : '' }}>
                                            Semester {{ $s }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div style="width:36px; flex-shrink:0">
                                <button type="button"
                                    class="btn btn-icon btn-sm btn-light remove-mapping-row"
                                    title="Hapus baris"
                                    {{ $existingMappings->count() <= 1 ? 'disabled' : '' }}>
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="mapping-row d-flex align-items-center gap-2 mb-2">
                            <div class="flex-grow-1">
                                <select name="mappings[0][prodi_id]"
                                    class="form-select form-select-solid form-select-sm"
                                    required>
                                    <option value="">-- Pilih Prodi --</option>
                                    @foreach($prodi as $p)
                                        <option value="{{ $p->id }}">
                                            {{ $p->nama_prodi }} ({{ $p->kode_prodi }})
                                        </option>
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
                                    class="btn btn-icon btn-sm btn-light remove-mapping-row"
                                    disabled title="Hapus baris">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </div>
                        </div>
                        @endforelse
                    </div>{{-- /#mapping-rows-edit-{id} --}}

                    <button type="button"
                        class="btn btn-sm btn-light-success mt-1 btn-tambah-mapping-edit"
                        data-target="mapping-rows-edit-{{ $m->id }}"
                        data-matkul-id="{{ $m->id }}">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Prodi &amp; Semester
                    </button>

                </div>{{-- /modal-body --}}

                {{-- ── FOOTER ── --}}
                <div class="modal-footer border-0 pt-0 justify-content-between">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check2-circle me-2"></i>Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>{{-- /modal-content --}}
    </div>{{-- /modal-dialog --}}
</div>{{-- /modal#modalEditMatkul --}}


{{-- ═══════════════════════════════════════════════════════════════════════════
    SCRIPT — satu blok per MK (aman karena modal juga render sekali).

    Fix yang ditangani:
      [1] Z-Index Select2   — gunakan dropdownParent agar dropdown tampil di ATAS
                              modal, bukan di belakang overlay Bootstrap.
      [2] Re-init on open   — init ulang setiap shown.bs.modal supaya state
                              tidak stale saat modal dibuka ulang.
      [3] Cleanup on close  — destroy Select2 saat modal hidden agar tidak ada
                              dropdown yang "tertinggal" di DOM.
      [4] Tombol hapus row  — remove baris mapping, selalu minimal 1 baris.
      [5] Tombol tambah row — clone baris terakhir, reset value, reindex name[].
      [6] Trigger dari Detail modal — tutup Detail dulu sebelum buka Edit
                              agar tidak terjadi backdrop ganda & z-index kacau.
══════════════════════════════════════════════════════════════════════════════ --}}
<script>
(function () {
    'use strict';

    var MODAL_EDIT_ID = '#modalEditMatkul{{ $m->id }}';
    var MAPPING_WRAP  = '#mapping-rows-edit-{{ $m->id }}';
    var MATKUL_ID     = {{ $m->id }};

    // ── [1] & [2] Init Select2 dengan dropdownParent ─────────────────────
    // Dijalankan setiap kali modal dibuka.
    $(MODAL_EDIT_ID).on('shown.bs.modal', function () {
        var $modal = $(this);

        $modal.find('.select2-in-modal').each(function () {
            var $el = $(this);

            // Hancurkan instance lama (bisa ada dari Metronic auto-init)
            if ($el.data('select2')) {
                $el.select2('destroy');
            }

            $el.select2({
                dropdownParent : $modal,   // ← FIX utama z-index
                width          : '100%',
                allowClear     : false,
            });
        });

        // Sinkronisasi state tombol hapus & index name[] saat buka modal
        reindexMappingRows();
        updateRemoveButtonState();
    });

    // ── [3] Cleanup: destroy Select2 saat modal ditutup ──────────────────
    // Mencegah dropdown "hantu" yang tertinggal di DOM setelah modal close.
    $(MODAL_EDIT_ID).on('hidden.bs.modal', function () {
        $(this).find('.select2-in-modal').each(function () {
            if ($(this).data('select2')) {
                $(this).select2('destroy');
            }
        });
    });

    // ── [4] Tombol HAPUS baris mapping ───────────────────────────────────
    $(document).on('click', MAPPING_WRAP + ' .remove-mapping-row', function () {
        var $wrap = $(MAPPING_WRAP);
        if ($wrap.find('.mapping-row').length <= 1) return; // guard minimal 1 baris

        $(this).closest('.mapping-row').remove();
        reindexMappingRows();
        updateRemoveButtonState();
    });

    // ── [5] Tombol TAMBAH baris mapping ──────────────────────────────────
    $(document).on('click',
        '.btn-tambah-mapping-edit[data-matkul-id="' + MATKUL_ID + '"]',
        function () {
            var $wrap    = $(MAPPING_WRAP);
            var $lastRow = $wrap.find('.mapping-row').last();
            var $newRow  = $lastRow.clone(false); // false = jangan clone event listener

            // Reset nilai semua <select> di baris baru
            $newRow.find('select').each(function () {
                $(this).val('');
            });

            // Pastikan tombol hapus aktif (karena baris sudah > 1)
            $newRow.find('.remove-mapping-row').prop('disabled', false);

            $wrap.append($newRow);
            reindexMappingRows();
            updateRemoveButtonState();
        }
    );

    // ── [6] Trigger dari dalam modal Detail ──────────────────────────────
    // Menutup modal Detail terlebih dahulu sebelum membuka modal Edit.
    // Membuka dua modal Bootstrap sekaligus menyebabkan:
    //   - Backdrop ganda (layar hitam dobel)
    //   - Z-index kacau (modal di bawah backdrop)
    $(document).on('click',
        '[data-bs-target="' + MODAL_EDIT_ID + '"][data-from-detail]',
        function (e) {
            e.preventDefault();
            e.stopPropagation();

            var $activeModal = $(this).closest('.modal');

            if ($activeModal.length && $activeModal.hasClass('show')) {
                // Ada modal yang sedang terbuka — tutup dulu, baru buka edit
                $activeModal.modal('hide');
                $activeModal.one('hidden.bs.modal', function () {
                    $(MODAL_EDIT_ID).modal('show');
                });
            } else {
                // Tidak ada modal aktif, langsung buka
                $(MODAL_EDIT_ID).modal('show');
            }
        }
    );

    // ── HELPERS ──────────────────────────────────────────────────────────

    /**
     * Re-index atribut name pada semua baris mapping.
     * Menjaga urutan array PHP ($request->mappings[0], [1], ...)
     * tetap konsisten setelah baris dihapus atau ditambah.
     */
    function reindexMappingRows() {
        $(MAPPING_WRAP + ' .mapping-row').each(function (idx) {
            $(this).find('[name]').each(function () {
                var oldName = $(this).attr('name');
                var newName = oldName.replace(/mappings\[\d+\]/, 'mappings[' + idx + ']');
                $(this).attr('name', newName);
            });
        });
    }

    /**
     * Disable tombol hapus jika hanya ada 1 baris (tidak boleh kosong).
     * Enable semua tombol hapus jika ada lebih dari 1 baris.
     */
    function updateRemoveButtonState() {
        var $rows  = $(MAPPING_WRAP + ' .mapping-row');
        var isOnly = $rows.length <= 1;
        $rows.find('.remove-mapping-row').prop('disabled', isOnly);
    }

})();
</script>
