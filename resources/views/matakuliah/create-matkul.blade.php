<!-- Modal Tambah Mata Kuliah -->
<div class="modal fade" id="modalTambahMatkul" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-700px">
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
                                placeholder="1–9"
                                min="1" max="9"
                                required
                                value="{{ old('bobot') }}">
                        </div>

                        {{-- Semester --}}
                        <div class="col-md-4">
                            <label class="form-label required fw-semibold fs-7">Semester</label>
                            <select name="semester"
                                class="form-select form-select-solid"
                                data-control="select2"
                                data-hide-search="true"
                                data-placeholder="Pilih Semester"
                                required>
                                <option></option>
                                @for($s = 1; $s <= 14; $s++)
                                <option value="{{ $s }}" {{ old('semester') == $s ? 'selected' : '' }}>
                                    Semester {{ $s }}
                                </option>
                                @endfor
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

                        {{-- Jenis MK --}}
                        <div class="col-md-4">
                            <label class="form-label required fw-semibold fs-7">Jenis</label>
                            <select name="jenis" id="jenis_mk"
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

                        {{-- Program Studi (MULTIPLE SELECT) --}}
                        <div class="col-md-8">
                            <label class="form-label fw-semibold fs-7">
                                <span id="label_prodi_required" class="required">Program Studi</span>
                                <i class="bi bi-info-circle text-muted ms-1"
                                   data-bs-toggle="tooltip"
                                   title="MK Umum: Kosongkan (berlaku semua prodi). MK Wajib/Pilihan: Pilih minimal 1 prodi."></i>
                            </label>
                            <select name="id_prodi[]" id="select_prodi"
                                class="form-select form-select-solid"
                                data-control="select2"
                                data-placeholder="Pilih Program Studi"
                                multiple>
                                @foreach($prodi as $p)
                                <option value="{{ $p->id }}"
                                    {{ is_array(old('id_prodi')) && in_array($p->id, old('id_prodi')) ? 'selected' : '' }}>
                                    {{ $p->nama_prodi }} ({{ $p->kode_prodi }})
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted fs-8">
                                <span id="hint_prodi">Pilih satu atau lebih program studi</span>
                            </div>
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
$(document).ready(function() {
    // 1. Fungsi Pintar: Mengatur Wajib/Tidak Wajib
    function toggleProdiRequirement(selectJenis) {
        // Deteksi apakah ini Modal Create atau Edit berdasarkan ID
        // Jika ID = "jenis_mk", maka idSuffix kosong (Create)
        // Jika ID = "jenis_mk_edit_12", maka idSuffix = "12" (Edit)
        const idSuffix = selectJenis.id.replace('jenis_mk_edit_', '').replace('jenis_mk', '');

        // Tentukan ID elemen target
        const prodiSelectId = idSuffix === '' ? '#select_prodi' : '#select_prodi_edit_' + idSuffix;
        const labelId       = idSuffix === '' ? '#label_prodi_required' : '#label_prodi_required_edit_' + idSuffix;
        const hintId        = idSuffix === '' ? '#hint_prodi' : '#hint_prodi_edit_' + idSuffix;

        const prodiSelect = $(prodiSelectId);
        const label = $(labelId);
        const hint = $(hintId);

        if (selectJenis.value === 'umum') {
            // Logika untuk MK UMUM
            label.removeClass('required');
            prodiSelect.removeAttr('required');
            hint.html('<i class="bi bi-info-circle me-1"></i>Berlaku untuk <strong>semua prodi</strong> (Otomatis)').addClass('text-primary');
        } else {
            // Logika untuk MK WAJIB/PILIHAN
            label.addClass('required');
            prodiSelect.attr('required', 'required');
            hint.html('Pilih <strong>minimal 1 prodi</strong>').removeClass('text-primary');
        }
    }

    // 2. Event Listener: Jika Dropdown Jenis diganti (oleh user)
    $(document).on('change', '[id^="jenis_mk"]', function() {
        toggleProdiRequirement(this);
    });

    // 3. Event Listener: Saat Modal Dibuka (Penting!)
    $('.modal').on('shown.bs.modal', function () {
        // A. Jalankan fungsi cek requirement supaya tampilan sesuai data yang ada
        const jenisSelect = $(this).find('[id^="jenis_mk"]')[0];
        if (jenisSelect) toggleProdiRequirement(jenisSelect);

        // B. Perbaiki bug Select2 di dalam modal (Re-inisialisasi)
        // Ini memastikan dropdown bisa dicari dan diklik
        $(this).find('[data-control="select2"]').select2({
            dropdownParent: $(this),
            width: '100%' // Memastikan lebar dropdown pas
        });
    });
});
</script>
@endpush
