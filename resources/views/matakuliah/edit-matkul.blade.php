{{--
    edit-matkul.blade.php — SOLUSI FINAL (Single Global Modal)

    ARSITEKTUR:
    - Satu modal untuk semua baris (bukan per-baris)
    - Include SEKALI di luar loop di index.blade.php / all-data.blade.php
    - Tombol Edit tiap baris pakai class .btn-open-edit-matkul + data-* attributes
    - Tidak ada script tag di dalam loop → tidak ada konflik JS

    CARA PASANG TOMBOL EDIT di tiap baris:
    <button type="button" class="btn-open-edit-matkul"
        data-id="{{ $m->id }}"
        data-url="{{ route('matakuliah.update', $m->id) }}"
        data-kode="{{ $m->kode_mk }}"
        data-nama="{{ $m->nama_mk }}"
        data-bobot="{{ $m->bobot }}"
        data-jenis="{{ $m->jenis }}"
        data-id-dosen="{{ $m->id_dosen }}"
        data-mappings="{{ json_encode($m->prodiMappings->map(fn($mp) => ['prodi_id' => $mp->id_prodi, 'semester' => $mp->semester])) }}">
        <i class="bi bi-pencil"></i> Edit
    </button>
--}}

<div class="modal fade" id="modalEditMatkulGlobal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <form id="formEditMatkulGlobal">
                @csrf
                <input type="hidden" id="editMatkulUrl">
                <input type="hidden" id="editMatkulId">

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

                <div class="modal-body pt-5 pb-4">

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
                        <div class="col"><span class="text-muted fs-8 fw-semibold text-uppercase">Program
                                Studi</span></div>
                        <div class="col-auto" style="width:170px"><span
                                class="text-muted fs-8 fw-semibold text-uppercase">Semester</span></div>
                        <div class="col-auto" style="width:40px"></div>
                    </div>

                    <div id="editMappingRows"></div>

                    <button type="button" id="btnAddMappingRow" class="btn btn-sm btn-light-success mt-1">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Prodi &amp; Semester
                    </button>

                    {{-- Data prodi untuk JS (render mapping rows) --}}
                    <script id="prodiData" type="application/json">
                        {!! json_encode($prodi->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama_prodi, 'kode' => $p->kode_prodi])) !!}
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
    $(function() {
        'use strict';

        // Verifikasi jQuery tersedia
        if (typeof $ === 'undefined') {
            console.error('[EditMatkul] jQuery tidak tersedia!');
            return;
        }

        var $modal = $('#modalEditMatkulGlobal');
        var $form = $('#formEditMatkulGlobal');
        var $mapWrap = $('#editMappingRows');
        var $btnSimpan = $('#btnSimpanEdit');
        var $btnAdd = $('#btnAddMappingRow');
        var $errAlert = $('#editMatkulErrAlert');
        var $errList = $('#editMatkulErrList');

        // Baca data prodi dari JSON tag
        var prodiList = [];
        try {
            prodiList = JSON.parse(document.getElementById('prodiData').textContent);
        } catch (e) {
            console.error('[EditMatkul] Gagal parse prodiData:', e);
        }

        // Semester options
        var semOptions = '';
        for (var s = 1; s <= 14; s++) {
            semOptions += '<option value="' + s + '">Semester ' + s + '</option>';
        }

        // Prodi options
        var prodiOptions = '<option value="">-- Pilih Prodi --</option>';
        $.each(prodiList, function(i, p) {
            prodiOptions += '<option value="' + p.id + '">' + p.nama + ' (' + p.kode + ')</option>';
        });

        console.log('[EditMatkul] Handler terdaftar. Modal:', $modal.length, 'Form:', $form.length);

        // ── [1] Buka modal saat tombol Edit diklik ────────────────────────
        $(document).on('click', '.btn-open-edit-matkul', function() {
            var $btn = $(this);
            var id = $btn.data('id');
            var url = $btn.data('url');
            var kode = $btn.data('kode');
            var nama = $btn.data('nama');
            var bobot = $btn.data('bobot');
            var jenis = $btn.data('jenis');
            var idDosen = $btn.data('id-dosen');
            var mappings = $btn.data('mappings');

            console.log('[EditMatkul] Buka edit untuk ID:', id, '| Dosen:', idDosen, '| URL:', url);

            // Isi hidden fields
            $('#editMatkulUrl').val(url);
            $('#editMatkulId').val(id);
            $('#editMatkulSubtitle').text(kode + ' — ' + nama);

            // Isi input text
            $('#editKodeMk').val(kode);
            $('#editNamaMk').val(nama);
            $('#editBobot').val(bobot);

            // Set native select value SEBELUM Select2 di-init di shown.bs.modal
            // Ini yang paling krusial agar Select2 menampilkan nilai yang benar
            $('#editJenis').val(jenis);
            $('#editIdDosen').val(idDosen);

            // Render mapping rows
            $mapWrap.empty();
            if (mappings && mappings.length) {
                $.each(mappings, function(i, mp) {
                    appendMappingRow(mp.prodi_id, mp.semester);
                });
            } else {
                appendMappingRow(null, null);
            }
            updateRmState();

            // Reset error
            $errAlert.addClass('d-none');
            $errList.empty();

            // Tampilkan modal
            $modal.modal('show');
        });

        // ── [2] Init Select2 saat modal fully visible ─────────────────────
        $modal.on('shown.bs.modal', function() {
            // Destroy dulu instance lama
            ['#editJenis', '#editIdDosen'].forEach(function(sel) {
                var $el = $(sel);
                if ($el.data('select2')) {
                    try {
                        $el.select2('destroy');
                    } catch (e) {}
                }
            });

            // Re-init dengan dropdownParent untuk fix z-index
            $('#editJenis').select2({
                dropdownParent: $modal,
                width: '100%'
            });
            $('#editIdDosen').select2({
                dropdownParent: $modal,
                width: '100%'
            });

            console.log('[EditMatkul] Select2 init. editIdDosen value:', $('#editIdDosen').val());
        });

        // ── [3] Cleanup saat modal ditutup ────────────────────────────────
        $modal.on('hidden.bs.modal', function() {
            ['#editJenis', '#editIdDosen'].forEach(function(sel) {
                var $el = $(sel);
                if ($el.data('select2')) {
                    try {
                        $el.select2('destroy');
                    } catch (e) {}
                }
            });
        });

        // ── [4] SUBMIT ────────────────────────────────────────────────────
        $btnSimpan.on('click', function() {
            var url = $('#editMatkulUrl').val();
            if (!url) {
                console.error('[EditMatkul] URL kosong saat submit!');
                return;
            }

            // Log nilai sebelum submit — untuk verifikasi
            console.log('[EditMatkul] Submit ke:', url);
            console.log('[EditMatkul] id_dosen (native select):', document.getElementById('editIdDosen')
                .value);
            console.log('[EditMatkul] id_dosen (jQuery val):', $('#editIdDosen').val());

            // Loading
            $btnSimpan.find('.lbl-normal').addClass('d-none');
            $btnSimpan.find('.lbl-loading').removeClass('d-none');
            $btnSimpan.prop('disabled', true);

            $errAlert.addClass('d-none');
            $errList.empty();

            // serialize() membaca nilai dari DOM yang sudah di-update Select2
            var payload = $form.serialize() + '&_method=PUT';
            console.log('[EditMatkul] Payload:', payload);

            $.ajax({
                url: url,
                method: 'POST',
                data: payload,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                success: function(res) {
                    console.log('[EditMatkul] Success:', res);
                    $modal.modal('hide');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Mata kuliah berhasil diperbarui.',
                            timer: 1500,
                            showConfirmButton: false,
                        }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        window.location.reload();
                    }
                },

                error: function(xhr) {
                    console.error('[EditMatkul] Error:', xhr.status, xhr.responseJSON);
                    $btnSimpan.find('.lbl-normal').removeClass('d-none');
                    $btnSimpan.find('.lbl-loading').addClass('d-none');
                    $btnSimpan.prop('disabled', false);

                    var msgs = [];
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(f, arr) {
                            $.each(arr, function(i, m) {
                                msgs.push(m);
                            });
                        });
                    } else if (xhr.status === 403) {
                        msgs.push('Tidak memiliki izin (403 Forbidden).');
                    } else if (xhr.status === 419) {
                        msgs.push('Sesi habis. Refresh halaman lalu coba lagi.');
                    } else {
                        msgs.push('Error ' + xhr.status + ': ' + ((xhr.responseJSON && xhr
                            .responseJSON.message) || 'Coba lagi.'));
                    }

                    $.each(msgs, function(i, m) {
                        $errList.append('<li>' + m + '</li>');
                    });
                    $errAlert.removeClass('d-none');
                    $modal.find('.modal-body').scrollTop(0);
                }
            });
        });

        // ── [5] Tambah mapping row ────────────────────────────────────────
        $btnAdd.on('click', function() {
            appendMappingRow(null, null);
            updateRmState();
        });

        // ── [6] Hapus mapping row ─────────────────────────────────────────
        $mapWrap.on('click', '.btn-rm-map', function() {
            if ($mapWrap.find('.mapping-row').length <= 1) return;
            $(this).closest('.mapping-row').remove();
            reindexRows();
            updateRmState();
        });

        // ── Helpers ───────────────────────────────────────────────────────

        function appendMappingRow(prodiId, semester) {
            var html = [
                '<div class="mapping-row d-flex align-items-center gap-2 mb-2">',
                '<div class="flex-grow-1">',
                '<select name="mappings[0][prodi_id]" class="form-select form-select-solid form-select-sm" required>',
                prodiOptions,
                '</select>',
                '</div>',
                '<div style="width:170px;flex-shrink:0">',
                '<select name="mappings[0][semester]" class="form-select form-select-solid form-select-sm" required>',
                '<option value="">-- Semester --</option>',
                semOptions,
                '</select>',
                '</div>',
                '<div style="width:36px;flex-shrink:0">',
                '<button type="button" class="btn btn-icon btn-sm btn-light btn-rm-map">',
                '<i class="bi bi-trash text-danger"></i>',
                '</button>',
                '</div>',
                '</div>'
            ].join('');

            var $row = $(html);
            if (prodiId) $row.find('[name*="prodi_id"]').val(prodiId);
            if (semester) $row.find('[name*="semester"]').val(semester);

            $mapWrap.append($row);
            reindexRows();
        }

        function reindexRows() {
            $mapWrap.find('.mapping-row').each(function(idx) {
                $(this).find('[name]').each(function() {
                    this.name = this.name.replace(/mappings\[\d+\]/, 'mappings[' + idx + ']');
                });
            });
        }

        function updateRmState() {
            var only = $mapWrap.find('.mapping-row').length <= 1;
            $mapWrap.find('.btn-rm-map').prop('disabled', only);
        }

    });
</script>
