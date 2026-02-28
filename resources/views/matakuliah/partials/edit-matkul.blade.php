{{--
    edit-matkul.blade.php — SOLUSI FINAL (Single Global Modal + DOM Update tanpa reload)
--}}

<div class="modal fade" id="modalEditMatkulGlobal" tabindex="-1" aria-hidden="true">
    {{--
        FIX SCROLL: Hapus modal-dialog-centered.
        Metronic overrides Bootstrap scroll chain saat centered+scrollable digabung.
        modal-dialog-scrollable saja sudah cukup untuk enable scroll body.
    --}}
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            {{--
                FIX SCROLL BUG:
                <form> TIDAK boleh membungkus modal-header karena memutus
                flex chain Bootstrap modal-dialog-scrollable.
                Bootstrap butuh: .modal-content (flex col) -> .modal-body (overflow-y:auto)
                Solusi: modal-header di LUAR form, form hanya wrap body+footer,
                form diberi style flex agar ikut flex chain.
            --}}

            {{-- HEADER — di luar form, sticky di atas --}}
            <div class="modal-header bg-light-success border-0 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="symbol symbol-45px symbol-circle bg-success">
                        <span class="symbol-label">
                            <i class="bi bi-pencil-fill text-white fs-4"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bolder text-dark mb-0">Edit Mata Kuliah</h5>
                        <span class="text-muted fs-8" id="editMatkulSubtitle">—</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            {{-- FORM — hanya wrap body+footer agar Bootstrap flex chain tidak putus --}}
            <form id="formEditMatkulGlobal"
                style="display:flex;flex-direction:column;flex:1 1 auto;overflow:hidden;min-height:0">
                @csrf
                <input type="hidden" id="editMatkulUrl">
                <input type="hidden" id="editMatkulId">

                {{--
                    FIX SCROLL: Tambah max-height + overflow-y explicit agar scroll bekerja
                    di Metronic yang terkadang override Bootstrap modal-body styling.
                --}}
                <div class="modal-body pt-5 pb-4" style="max-height:70vh;overflow-y:auto">

                    <div class="alert alert-danger d-none mb-4" id="editMatkulErrAlert">
                        <strong><i class="bi bi-exclamation-triangle me-2"></i>Gagal menyimpan:</strong>
                        <ul class="mb-0 mt-1" id="editMatkulErrList"></ul>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-5">
                            <label class="form-label required fw-semibold fs-7">Kode Mata Kuliah</label>
                            <input type="text" name="kode_mk" id="editKodeMk" class="form-control form-control-solid"
                                required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required fw-semibold fs-7">Bobot SKS</label>
                            <input type="number" name="bobot" id="editBobot" class="form-control form-control-solid"
                                min="1" max="9" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required fw-semibold fs-7">Jenis</label>
                            <select name="jenis" id="editJenis" class="form-select form-select-solid" required>
                                <option value="wajib">Wajib</option>
                                <option value="pilihan">Pilihan</option>
                                <option value="umum">Umum</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label required fw-semibold fs-7">Nama Mata Kuliah</label>
                            <input type="text" name="nama_mk" id="editNamaMk" class="form-control form-control-solid"
                                required>
                        </div>
                        <div class="col-12">
                            <label class="form-label required fw-semibold fs-7">Dosen Pengampu</label>
                            <select name="id_dosen" id="editIdDosen" class="form-select form-select-solid" required>
                                <option value="">-- Pilih Dosen --</option>
                                @foreach ($dosen as $d)
                                    <option value="{{ $d->id }}">
                                        {{ $d->user->nama ?? '-' }}
                                        @if ($d->nip)
                                            &mdash; NIP {{ $d->nip }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="separator separator-dashed my-5"></div>

                    <div class="mb-3">
                        <h6 class="fw-bolder text-dark mb-0">
                            <i class="bi bi-diagram-3 me-1 text-success"></i>Mapping Prodi &amp; Semester
                        </h6>
                        <span class="text-muted fs-8">Ubah daftar kombinasi Prodi + Semester tempat MK ini akan
                            muncul.</span>
                    </div>

                    <div class="row g-2 mb-2 px-1">
                        <div class="col-md-5"><span class="text-muted fs-8 fw-semibold text-uppercase">Program
                                Studi</span></div>
                        <div class="col-md-3"><span class="text-muted fs-8 fw-semibold text-uppercase">Semester</span>
                        </div>
                        <div class="col"><span class="text-muted fs-8 fw-semibold text-uppercase">
                                Rombel <span class="badge badge-light-info fs-9 ms-1 text-lowercase">opsional</span>
                            </span></div>
                    </div>

                    <div id="editMappingRows"></div>

                    <button type="button" id="btnAddMappingRow" class="btn btn-sm btn-light-success mt-1">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Prodi &amp; Semester
                    </button>

                    <script id="prodiData" type="application/json">
                        {!! json_encode($prodi->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama_prodi, 'kode' => $p->kode_prodi])) !!}
                    </script>

                    {{--
                        FIX BUG ROMBEL: Data rombel di-embed langsung di halaman (JSON),
                        dikelompokkan per id_prodi. JS melakukan lookup LOKAL — tidak perlu
                        AJAX ke route rombel-by-prodi yang sering gagal karena:
                          (a) Route belum terdaftar di web.php
                          (b) CSRF/auth redirect
                          (c) Network latency

                        Struktur: { "prodiId": [{ id, nama, kode, tahun_awal }, ...] }
                    --}}
                    <script id="rombelData" type="application/json">
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
                    <button type="button" id="btnSimpanEdit" class="btn btn-success">
                        <span class="lbl-normal"><i class="bi bi-check2-circle me-2"></i>Simpan Perubahan</span>
                        <span class="lbl-loading d-none">
                            <span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
    (function waitForjQuery() {
        if (typeof jQuery === 'undefined') {
            setTimeout(waitForjQuery, 50);
            return;
        }

        jQuery(function($) {
            'use strict';

            // ── Referensi elemen ──────────────────────────────────────────────
            var $modal = $('#modalEditMatkulGlobal');
            var $form = $('#formEditMatkulGlobal');
            var $mapWrap = $('#editMappingRows');
            var $btnSimpan = $('#btnSimpanEdit');
            var $btnAdd = $('#btnAddMappingRow');
            var $errAlert = $('#editMatkulErrAlert');
            var $errList = $('#editMatkulErrList');
            var $currentBtn = null;

            if (!$modal.length) {
                console.error('[EditMatkul] Modal tidak ditemukan!');
                return;
            }

            // ── URL base untuk AJAX rombel — TIDAK DIPAKAI LAGI (diganti lookup lokal)
            // var ROMBEL_API = '{{ url('matakuliah/rombel-by-prodi') }}/';
            var rombelCache = {}; // cache per prodiId (dipertahankan untuk reset di hidden.bs.modal)

            // ── Data Prodi dari JSON embedded ─────────────────────────────────
            var prodiList = [];
            try {
                prodiList = JSON.parse(document.getElementById('prodiData').textContent);
            } catch (e) {
                console.error('[EditMatkul] Gagal parse prodiData:', e);
            }

            var semOptions = '';
            for (var s = 1; s <= 14; s++) {
                semOptions += '<option value="' + s + '">Semester ' + s + '</option>';
            }

            var prodiOptions = '<option value="">-- Pilih Prodi --</option>';
            $.each(prodiList, function(i, p) {
                prodiOptions += '<option value="' + p.id + '">' + p.nama + ' (' + p.kode +
                    ')</option>';
            });

            console.log('[EditMatkul] Handler terdaftar. Modal:', $modal.length);

            // ================================================================
            // [1] Buka modal — populate semua field dari data-* tombol Edit
            //
            // PERUBAHAN dari versi lama:
            // - data-mappings sekarang bisa berisi id_rombel per baris
            // - Baris DB di-GROUP dulu per prodi_id+semester agar tampil
            //   sebagai 1 baris UI dengan beberapa checkbox rombel
            // ================================================================
            $(document).on('click', '.btn-open-edit-matkul', function() {
                $currentBtn = $(this);
                var $btn = $currentBtn;

                $('#editMatkulUrl').val($btn.data('url'));
                $('#editMatkulId').val($btn.data('id'));
                $('#editMatkulSubtitle').text($btn.data('kode') + ' — ' + $btn.data('nama'));
                $('#editKodeMk').val($btn.data('kode'));
                $('#editNamaMk').val($btn.data('nama'));
                $('#editBobot').val($btn.data('bobot'));
                $('#editJenis').val($btn.data('jenis'));
                $('#editIdDosen').val($btn.data('id-dosen'));

                var mappings = $btn.data('mappings');
                rombelCache = {}; // reset cache tiap buka modal
                $mapWrap.empty();

                if (mappings && mappings.length) {
                    // ── GROUP: baris DB → baris UI ────────────────────────────
                    // Contoh data-mappings (setelah controller update):
                    //   [{prodi_id:1, semester:3, id_rombel:5},
                    //    {prodi_id:1, semester:3, id_rombel:7},   ← digabung 1 baris UI
                    //    {prodi_id:2, semester:1, id_rombel:null}]
                    var grouped = {};
                    var groupOrder = [];
                    $.each(mappings, function(i, mp) {
                        var key = mp.prodi_id + '_' + mp.semester;
                        if (!grouped[key]) {
                            grouped[key] = {
                                prodi_id: mp.prodi_id,
                                semester: mp.semester,
                                rombel_ids: [],
                            };
                            groupOrder.push(key);
                        }
                        if (mp.id_rombel) {
                            grouped[key].rombel_ids.push(parseInt(mp.id_rombel));
                        }
                    });

                    $.each(groupOrder, function(i, key) {
                        appendMappingRow(
                            grouped[key].prodi_id,
                            grouped[key].semester,
                            grouped[key].rombel_ids
                        );
                    });
                } else {
                    appendMappingRow(null, null, []);
                }
                updateRmState();

                $errAlert.addClass('d-none');
                $errList.empty();
                $modal.modal('show');

                console.log('[EditMatkul] Klik edit ID:', $btn.data('id'));
            });

            // ================================================================
            // [2] Init Select2 untuk Jenis & Dosen saat modal tampil
            // ================================================================
            $modal.on('shown.bs.modal', function() {
                ['#editJenis', '#editIdDosen'].forEach(function(sel) {
                    var $el = $(sel);
                    if ($el.data('select2')) {
                        try {
                            $el.select2('destroy');
                        } catch (e) {}
                    }
                    $el.select2({
                        dropdownParent: $modal,
                        width: '100%'
                    });
                });
            });

            // ================================================================
            // [3] Destroy Select2 saat modal ditutup
            // ================================================================
            $modal.on('hidden.bs.modal', function() {
                ['#editJenis', '#editIdDosen'].forEach(function(sel) {
                    var $el = $(sel);
                    if ($el.data('select2')) {
                        try {
                            $el.select2('destroy');
                        } catch (e) {}
                    }
                });
                rombelCache = {};
            });

            // ================================================================
            // [4] Submit via AJAX — TIDAK BERUBAH dari versi lama
            // (reindexRows() sudah cukup karena name rombel_ids[]
            //  punya prefix mappings[N] yang dihandle regex yg sama)
            // ================================================================
            $btnSimpan.on('click', function() {
                var url = $('#editMatkulUrl').val();
                if (!url) return;

                console.log('[EditMatkul] Submit ke:', url);

                $btnSimpan.find('.lbl-normal').addClass('d-none');
                $btnSimpan.find('.lbl-loading').removeClass('d-none');
                $btnSimpan.prop('disabled', true);
                $errAlert.addClass('d-none');
                $errList.empty();

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: $form.serialize() + '&_method=PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    success: function(res) {
                        $modal.modal('hide');

                        // ── Update baris di tabel tanpa reload ──
                        if ($currentBtn && res.data) {
                            var d = res.data;
                            var $row = $currentBtn.closest('tr');

                            $currentBtn
                                .attr('data-kode', d.kode_mk)
                                .attr('data-nama', d.nama_mk)
                                .attr('data-bobot', d.bobot)
                                .attr('data-jenis', d.jenis)
                                .attr('data-id-dosen', d.id_dosen)
                                .attr('data-mappings', JSON.stringify(d.mappings));

                            $row.find('[data-cell="kode"]').text(d.kode_mk);
                            $row.find('[data-cell="nama"]').text(d.nama_mk);
                            $row.find('[data-cell="sks"]').text(d.bobot);
                            $row.find('[data-cell="dosen"]').text(d.dosen_nama);

                            var jenisMap = {
                                wajib: {
                                    cls: 'badge-light-primary',
                                    lbl: 'Wajib'
                                },
                                pilihan: {
                                    cls: 'badge-light-warning',
                                    lbl: 'Pilihan'
                                },
                                umum: {
                                    cls: 'badge-light-info',
                                    lbl: 'Umum'
                                },
                            };
                            var jInfo = jenisMap[d.jenis] || {
                                cls: 'badge-light-secondary',
                                lbl: d.jenis
                            };
                            $row.find('[data-cell="jenis"]')
                                .removeClass(
                                    'badge-light-primary badge-light-warning badge-light-info badge-light-secondary'
                                    )
                                .addClass(jInfo.cls).text(jInfo.lbl);

                            $row.addClass('table-success');
                            setTimeout(function() {
                                $row.removeClass('table-success');
                            }, 2500);
                        }

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end',
                            });
                        }
                    },

                    error: function(xhr) {
                        $btnSimpan.find('.lbl-normal').removeClass('d-none');
                        $btnSimpan.find('.lbl-loading').addClass('d-none');
                        $btnSimpan.prop('disabled', false);

                        var msgs = [];
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON
                            .errors) {
                            $.each(xhr.responseJSON.errors, function(f, arr) {
                                $.each(arr, function(i, m) {
                                    msgs.push(m);
                                });
                            });
                        } else if (xhr.status === 419) {
                            msgs.push('Sesi habis. Refresh halaman lalu coba lagi.');
                        } else {
                            var errMsg = (xhr.responseJSON && (xhr.responseJSON
                                    .message ||
                                    (xhr.responseJSON.errors && xhr.responseJSON
                                        .errors.server &&
                                        xhr.responseJSON.errors.server[0]))) ||
                                'Coba lagi.';
                            msgs.push('Error ' + xhr.status + ': ' + errMsg);
                        }
                        $.each(msgs, function(i, m) {
                            $errList.append('<li>' + m + '</li>');
                        });
                        $errAlert.removeClass('d-none');
                        $modal.find('.modal-body').scrollTop(0);
                    }
                });
            });

            // ================================================================
            // [5] Tambah baris mapping kosong
            // ================================================================
            $btnAdd.on('click', function() {
                appendMappingRow(null, null, []);
                updateRmState();
            });

            // ================================================================
            // [6] Hapus baris
            // ================================================================
            $mapWrap.on('click', '.btn-rm-map', function() {
                if ($mapWrap.find('.mapping-row').length <= 1) return;
                $(this).closest('.mapping-row').remove();
                reindexRows();
                updateRmState();
            });

            // ================================================================
            // [7] Prodi berubah → load rombel AJAX
            // ================================================================
            $mapWrap.on('change', '.select-prodi-edit', function() {
                var prodiId = $(this).val();
                var $row = $(this).closest('.mapping-row');
                var match = this.name.match(/mappings\[(\d+)\]/);
                var idx = match ? match[1] : null;

                if (prodiId) {
                    loadRombelForRow($row, prodiId, [], idx);
                } else {
                    resetRombelArea($row);
                }
            });

            // ================================================================
            // HELPER: appendMappingRow
            //
            // prodiId    — ID prodi untuk pre-select (null = kosong)
            // semester   — nilai semester untuk pre-select (null = kosong)
            // rombelIds  — array ID rombel yang harus dicentang ([] = kosong)
            // ================================================================
            function appendMappingRow(prodiId, semester, rombelIds) {
                var $row = $(`
                    <div class="mapping-row border rounded-2 p-3 mb-2 bg-light position-relative">
                        <button type="button"
                            class="btn btn-icon btn-sm btn-light btn-rm-map position-absolute top-0 end-0 m-1"
                            style="width:22px;height:22px">
                            <i class="bi bi-x fs-7 text-danger"></i>
                        </button>
                        <div class="row g-2 mb-2">
                            <div class="col-md-5">
                                <select name="mappings[0][prodi_id]"
                                    class="form-select form-select-solid form-select-sm select-prodi-edit" required>
                                    ${prodiOptions}
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="mappings[0][semester]"
                                    class="form-select form-select-solid form-select-sm" required>
                                    <option value="">-- Semester --</option>
                                    ${semOptions}
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
                                </span>
                            </div>
                        </div>
                    </div>`);

                if (prodiId) $row.find('.select-prodi-edit').val(prodiId);
                if (semester) $row.find('[name*="semester"]').val(semester);

                $mapWrap.append($row);
                reindexRows();

                // Load rombel jika prodi sudah dipilih
                if (prodiId) {
                    // Dapatkan index setelah reindex
                    var match = $row.find('.select-prodi-edit')[0].name.match(/mappings\[(\d+)\]/);
                    var idx = match ? match[1] : null;
                    loadRombelForRow($row, prodiId, rombelIds || [], idx);
                }
            }

            // ================================================================
            // HELPER: reindexRows — renumber semua field name mappings[N]
            // Bekerja untuk prodi_id, semester, DAN rombel_ids[] sekaligus
            // karena semuanya pakai prefix mappings[N]
            // ================================================================
            function reindexRows() {
                $mapWrap.find('.mapping-row').each(function(idx) {
                    $(this).find('[name]').each(function() {
                        this.name = this.name.replace(/mappings\[\d+\]/, 'mappings[' + idx +
                            ']');
                    });
                });
            }

            function updateRmState() {
                var only = $mapWrap.find('.mapping-row').length <= 1;
                $mapWrap.find('.btn-rm-map').prop('disabled', only);
            }

            // ================================================================
            // HELPER: Load rombel — LOOKUP LOKAL (bukan AJAX)
            //
            // Data rombel sudah di-embed di halaman sebagai JSON di #rombelData.
            // Tidak ada request ke server → tidak ada risiko 404 / auth redirect.
            // ================================================================
            var rombelByProdi = {};
            try {
                var rawRombel = document.getElementById('rombelData');
                if (rawRombel) {
                    rombelByProdi = JSON.parse(rawRombel.textContent);
                }
            } catch (e) {
                console.error('[EditMatkul] Gagal parse rombelData:', e);
            }

            function loadRombelForRow($row, prodiId, selectedIds, mappingIdx) {
                var $placeholder = $row.find('.rombel-placeholder');
                var $loading = $row.find('.rombel-loading');
                var $checkboxes = $row.find('.rombel-checkboxes');
                var $list = $row.find('.rombel-list');
                var $empty = $row.find('.rombel-empty');

                $placeholder.addClass('d-none');
                $empty.addClass('d-none');
                $checkboxes.addClass('d-none');
                $list.empty();

                // Lookup lokal — instant, tanpa AJAX
                var data = rombelByProdi[prodiId] || rombelByProdi[String(prodiId)] || [];
                renderRombel($row, data, selectedIds, mappingIdx);
            }

            function renderRombel($row, data, selectedIds, mappingIdx) {
                var $loading = $row.find('.rombel-loading');
                var $checkboxes = $row.find('.rombel-checkboxes');
                var $list = $row.find('.rombel-list');
                var $empty = $row.find('.rombel-empty');

                $loading.addClass('d-none');

                if (!data || data.length === 0) {
                    $empty.removeClass('d-none');
                    return;
                }

                var prefix = 'rbE_' + Date.now() + '_';
                $.each(data, function(i, r) {
                    var isChecked = selectedIds && selectedIds.indexOf(r.id) !== -1;
                    var nameAttr = mappingIdx !== null ?
                        'mappings[' + mappingIdx + '][rombel_ids][]' :
                        'mappings[?][rombel_ids][]';

                    var $item = $(`
                        <div class="form-check form-check-inline me-0">
                            <input class="form-check-input rombel-checkbox"
                                   type="checkbox"
                                   id="${prefix}${r.id}"
                                   name="${nameAttr}"
                                   value="${r.id}"
                                   ${isChecked ? 'checked' : ''}>
                            <label class="form-check-label cursor-pointer" for="${prefix}${r.id}">
                                <span class="badge badge-light-success fs-9">${$('<div>').text(r.nama).html()}</span>
                                ${r.tahun_awal ? '<span class="text-muted fs-9 ms-1">' + r.tahun_awal + '</span>' : ''}
                            </label>
                        </div>`);
                    $list.append($item);
                });

                $checkboxes.removeClass('d-none');
            }

            function resetRombelArea($row) {
                $row.find('.rombel-loading').addClass('d-none');
                $row.find('.rombel-empty').addClass('d-none');
                $row.find('.rombel-checkboxes').addClass('d-none');
                $row.find('.rombel-list').empty();
                $row.find('.rombel-placeholder').removeClass('d-none');
            }
        });
    })();
</script>
