/**
 * matkul-mapping.js
 * ─────────────────────────────────────────────────────────────────────────────
 * Mengelola baris mapping Prodi+Semester+Rombel pada modal Tambah & Edit MK.
 *
 * CARA INTEGRASI:
 *   1. Taruh script ini di dalam @push('scripts') di view Anda, ATAU
 *   2. Simpan sebagai public/js/matkul-mapping.js dan load via <script src="...">
 *
 * DEPENDENSI:
 *   - jQuery (sudah ada di Metronic)
 *   - Bootstrap 5
 *   - Route: GET /matakuliah/rombel-by-prodi/{prodiId}
 *             (MatkulController@getRombelByProdi)
 *
 * CARA KERJA FORM SUBMISSION:
 *   Baris mapping tidak pakai name[index] biasa. Sebelum submit, fungsi
 *   serializeMappings() membaca semua .mapping-row dan inject hidden inputs
 *   ber-index ke dalam form. Ini menghindari masalah re-indexing saat
 *   baris dihapus di tengah.
 * ─────────────────────────────────────────────────────────────────────────────
 */

(function ($) {
    'use strict';

    // ── Konfigurasi ──────────────────────────────────────────────────────────
    var ROMBEL_API_URL = '/matakuliah/rombel-by-prodi/';   // trailing slash + prodiId
    var rombelCache    = {};   // cache AJAX per prodi agar tidak re-fetch

    // ── Utilitas: tambah baris mapping baru ──────────────────────────────────
    function addMappingRow($container, data) {
        var $tmpl     = $('#tmplMappingRow');
        if (!$tmpl.length) {
            console.error('[matkul-mapping] Template #tmplMappingRow tidak ditemukan.');
            return;
        }
        var $row = $($tmpl.html());

        // Pre-fill jika data diberikan (saat edit)
        if (data) {
            $row.find('.select-prodi').val(data.prodi_id);
            $row.find('.select-semester').val(data.semester);
        }

        $container.append($row);

        // Trigger load rombel jika prodi sudah di-preselect
        if (data && data.prodi_id) {
            var $select = $row.find('.select-prodi');
            loadRombelForRow($row, data.prodi_id, data.rombel_ids || []);
        }

        // Event: hapus baris
        $row.find('.btn-remove-mapping').on('click', function () {
            // Minimal harus ada 1 baris
            if ($container.find('.mapping-row').length <= 1) {
                Swal && Swal.fire({
                    icon: 'warning',
                    title: 'Tidak bisa dihapus',
                    text: 'Minimal harus ada satu mapping Prodi & Semester.',
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: { confirmButton: 'btn btn-sm btn-primary' },
                });
                return;
            }
            $row.remove();
        });

        // Event: prodi berubah → load rombel
        $row.find('.select-prodi').on('change', function () {
            var prodiId = $(this).val();
            if (prodiId) {
                loadRombelForRow($row, prodiId, []);
            } else {
                resetRombelArea($row);
            }
        });

        return $row;
    }

    // ── Load rombel via AJAX untuk satu baris ────────────────────────────────
    function loadRombelForRow($row, prodiId, selectedRombelIds) {
        var $loading    = $row.find('.rombel-loading');
        var $placeholder = $row.find('.rombel-placeholder');
        var $checkboxes = $row.find('.rombel-checkboxes');
        var $list       = $row.find('.rombel-list');
        var $empty      = $row.find('.rombel-empty');

        // Reset state
        $placeholder.addClass('d-none');
        $empty.addClass('d-none');
        $checkboxes.addClass('d-none');
        $list.empty();
        $loading.removeClass('d-none');

        // Gunakan cache jika ada
        if (rombelCache[prodiId]) {
            renderRombelCheckboxes($row, rombelCache[prodiId], selectedRombelIds);
            return;
        }

        $.getJSON(ROMBEL_API_URL + prodiId)
            .done(function (data) {
                rombelCache[prodiId] = data;
                renderRombelCheckboxes($row, data, selectedRombelIds);
            })
            .fail(function (xhr) {
                $loading.addClass('d-none');
                $checkboxes.removeClass('d-none');
                $list.html(
                    '<span class="text-danger fs-9">' +
                    '<i class="bi bi-exclamation-triangle me-1"></i>' +
                    'Gagal memuat rombel. Coba lagi.' +
                    '</span>'
                );
                console.error('[matkul-mapping] AJAX error:', xhr.responseText);
            });
    }

    // ── Render checkbox rombel ke dalam satu baris ───────────────────────────
    function renderRombelCheckboxes($row, rombelData, selectedIds) {
        var $loading    = $row.find('.rombel-loading');
        var $checkboxes = $row.find('.rombel-checkboxes');
        var $list       = $row.find('.rombel-list');
        var $empty      = $row.find('.rombel-empty');

        $loading.addClass('d-none');

        if (!rombelData || rombelData.length === 0) {
            $empty.removeClass('d-none');
            return;
        }

        // Buat unique ID prefix agar tidak konflik antar baris
        var prefix = 'rombel_' + Date.now() + '_';

        rombelData.forEach(function (r) {
            var isChecked = selectedIds && selectedIds.indexOf(r.id) !== -1;
            var $item     = $(
                '<div class="form-check form-check-inline me-0">' +
                    '<input class="form-check-input rombel-checkbox" ' +
                           'type="checkbox" ' +
                           'id="' + prefix + r.id + '" ' +
                           'value="' + r.id + '"' +
                           (isChecked ? ' checked' : '') + '>' +
                    '<label class="form-check-label fs-8 fw-semibold cursor-pointer" ' +
                            'for="' + prefix + r.id + '">' +
                        '<span class="badge badge-light-primary ms-1">' + escHtml(r.nama) + '</span>' +
                        (r.tahun_awal
                            ? '<span class="text-muted fs-9 ms-1">' + r.tahun_awal + '</span>'
                            : '') +
                    '</label>' +
                '</div>'
            );
            $list.append($item);
        });

        $checkboxes.removeClass('d-none');
    }

    // ── Reset area rombel ke kondisi awal (prodi belum dipilih) ──────────────
    function resetRombelArea($row) {
        $row.find('.rombel-loading').addClass('d-none');
        $row.find('.rombel-empty').addClass('d-none');
        $row.find('.rombel-checkboxes').addClass('d-none');
        $row.find('.rombel-list').empty();
        $row.find('.rombel-placeholder').removeClass('d-none');
    }

    // ── Serialize semua mapping rows → hidden inputs sebelum submit ──────────
    //
    // Menghasilkan:
    //   mappings[0][prodi_id]      = 1
    //   mappings[0][semester]      = 3
    //   mappings[0][rombel_ids][]  = 5
    //   mappings[0][rombel_ids][]  = 7
    //   mappings[1][prodi_id]      = 2
    //   ...
    // ─────────────────────────────────────────────────────────────────────────
    function serializeMappings($form) {
        // Hapus hidden inputs lama agar tidak duplikat
        $form.find('.mapping-hidden').remove();

        var $container = $form.find('#mappingRows');
        var valid      = true;

        $container.find('.mapping-row').each(function (idx) {
            var $row      = $(this);
            var prodiId   = $row.find('.select-prodi').val();
            var semester  = $row.find('.select-semester').val();

            if (!prodiId || !semester) {
                valid = false;
                $row.find('.select-prodi, .select-semester').each(function () {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                    }
                });
                return;
            }

            appendHidden($form, 'mappings[' + idx + '][prodi_id]',  prodiId);
            appendHidden($form, 'mappings[' + idx + '][semester]',   semester);

            // Kumpulkan rombel yang dicentang
            $row.find('.rombel-checkbox:checked').each(function () {
                appendHidden($form, 'mappings[' + idx + '][rombel_ids][]', $(this).val());
            });
        });

        return valid;
    }

    // ── Helper: append hidden input ke form ──────────────────────────────────
    function appendHidden($form, name, value) {
        $form.append(
            $('<input>').attr({ type: 'hidden', name: name, class: 'mapping-hidden' }).val(value)
        );
    }

    // ── Helper: escape HTML ──────────────────────────────────────────────────
    function escHtml(str) {
        return $('<div>').text(str || '').html();
    }

    // ── Inisialisasi modal TAMBAH ─────────────────────────────────────────────
    //
    // Panggil ini dari dalam modal create-matkul.blade.php.
    // Ganti inisialisasi mapping lama Anda dengan:
    //
    //   window.MkMapping.initCreate('#modalTambahMatkul', '#formTambahMatkul');
    //
    // ─────────────────────────────────────────────────────────────────────────
    function initCreate(modalSelector, formSelector) {
        var $modal = $(modalSelector);
        var $form  = $(formSelector);
        var $container = $form.find('#mappingRows');

        // Bersihkan & tambah 1 baris kosong saat modal dibuka
        $modal.on('show.bs.modal', function () {
            rombelCache = {};   // reset cache agar fresh
            $container.empty();
            addMappingRow($container, null);
        });

        // Tombol "+ Tambah Prodi"
        $form.find('#btnTambahMapping').on('click', function () {
            addMappingRow($container, null);
        });

        // Sebelum submit: serialize mappings → hidden inputs
        $form.on('submit', function (e) {
            var ok = serializeMappings($form);
            if (!ok) {
                e.preventDefault();
                // Tampilkan alert error
                var $alert = $form.find('.mapping-error-alert');
                if (!$alert.length) {
                    $('<div class="alert alert-danger fs-8 py-2 mapping-error-alert">' +
                      '<i class="bi bi-exclamation-triangle me-1"></i>' +
                      'Lengkapi semua field Prodi dan Semester yang wajib diisi.' +
                      '</div>').insertBefore($container);
                }
            }
        });
    }

    // ── Inisialisasi modal EDIT ───────────────────────────────────────────────
    //
    // Ganti logika populate mapping lama Anda dengan:
    //
    //   window.MkMapping.initEdit('#modalEditMatkulGlobal', '#formEditMatkul');
    //
    // Pastikan di handler .btn-open-edit-matkul, setelah data AJAX berhasil,
    // Anda memanggil:
    //
    //   window.MkMapping.populateEdit($form, data.mappings);
    //
    // ─────────────────────────────────────────────────────────────────────────
    function initEdit(modalSelector, formSelector) {
        var $modal = $(modalSelector);
        var $form  = $(formSelector);
        var $container = $form.find('#mappingRowsEdit');

        // Reset saat modal ditutup
        $modal.on('hidden.bs.modal', function () {
            $container.empty();
            $form.find('.mapping-hidden').remove();
        });

        // Tombol "+ Tambah Prodi"
        $form.find('#btnTambahMappingEdit').on('click', function () {
            addMappingRow($container, null);
        });

        // Sebelum submit
        $form.on('submit', function (e) {
            var ok = serializeMappings($form);
            if (!ok) {
                e.preventDefault();
            }
        });
    }

    // ── Public API: populate baris mapping dari data server (untuk edit) ─────
    function populateEdit($formOrSelector, mappings) {
        var $form      = typeof $formOrSelector === 'string'
            ? $($formOrSelector) : $formOrSelector;
        var $container = $form.find('#mappingRowsEdit');

        rombelCache = {};   // reset cache
        $container.empty();

        if (!mappings || mappings.length === 0) {
            addMappingRow($container, null);
            return;
        }

        // Group mappings by prodi_id + semester (karena 1 "baris UI" bisa punya
        // banyak rombel yang di-expand jadi beberapa baris DB)
        var grouped = {};
        mappings.forEach(function (mp) {
            var key = mp.prodi_id + '_' + mp.semester;
            if (!grouped[key]) {
                grouped[key] = {
                    prodi_id:   mp.prodi_id,
                    semester:   mp.semester,
                    rombel_ids: [],
                };
            }
            if (mp.id_rombel) {
                grouped[key].rombel_ids.push(mp.id_rombel);
            }
        });

        Object.values(grouped).forEach(function (data) {
            addMappingRow($container, data);
        });
    }

    // ── Expose ke global ─────────────────────────────────────────────────────
    window.MkMapping = {
        initCreate:    initCreate,
        initEdit:      initEdit,
        populateEdit:  populateEdit,
        addRow:        addMappingRow,
    };

})(jQuery);
