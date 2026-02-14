<!-- Modal Edit Mata Kuliah -->
<div class="modal fade" id="modalEditMatkul{{ $m->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-700px">
        <div class="modal-content">
            <form method="POST" action="{{ route('matakuliah.update', $m->id) }}">
                @csrf
                @method('PUT')

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

                <div class="modal-body pt-5 pb-4">
                    <div class="row g-4">

                        {{-- Kode MK --}}
                        <div class="col-md-5">
                            <label class="form-label required fw-semibold fs-7">Kode Mata Kuliah</label>
                            <input type="text" name="kode_mk"
                                class="form-control form-control-solid"
                                required
                                value="{{ $m->kode_mk }}">
                        </div>

                        {{-- Bobot SKS --}}
                        <div class="col-md-3">
                            <label class="form-label required fw-semibold fs-7">Bobot SKS</label>
                            <input type="number" name="bobot"
                                class="form-control form-control-solid"
                                min="1" max="9"
                                required
                                value="{{ $m->bobot }}">
                        </div>

                        {{-- Semester --}}
                        <div class="col-md-4">
                            <label class="form-label required fw-semibold fs-7">Semester</label>
                            <select name="semester"
                                class="form-select form-select-solid"
                                data-control="select2"
                                data-hide-search="true"
                                required>
                                @for($s = 1; $s <= 14; $s++)
                                <option value="{{ $s }}" {{ $m->semester == $s ? 'selected' : '' }}>
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
                                required
                                value="{{ $m->nama_mk }}">
                        </div>

                        {{-- Jenis MK --}}
                        <div class="col-md-4">
                            <label class="form-label required fw-semibold fs-7">Jenis</label>
                            <select name="jenis" id="jenis_mk_edit_{{ $m->id }}"
                                class="form-select form-select-solid"
                                data-control="select2"
                                data-hide-search="true"
                                required>
                                <option value="wajib"   {{ $m->jenis == 'wajib'   ? 'selected' : '' }}>Wajib</option>
                                <option value="pilihan" {{ $m->jenis == 'pilihan' ? 'selected' : '' }}>Pilihan</option>
                                <option value="umum"    {{ $m->jenis == 'umum'    ? 'selected' : '' }}>Umum</option>
                            </select>
                        </div>

                        {{-- Program Studi (MULTIPLE SELECT) --}}
                        <div class="col-md-8">
                            <label class="form-label fw-semibold fs-7">
                                <span id="label_prodi_required_edit_{{ $m->id }}" class="{{ $m->jenis !== 'umum' ? 'required' : '' }}">
                                    Program Studi
                                </span>
                                <i class="bi bi-info-circle text-muted ms-1"
                                   data-bs-toggle="tooltip"
                                   title="MK Umum: Kosongkan (berlaku semua prodi). MK Wajib/Pilihan: Pilih minimal 1 prodi."></i>
                            </label>
                            <select name="id_prodi[]" id="select_prodi_edit_{{ $m->id }}"
                                class="form-select form-select-solid"
                                data-control="select2"
                                data-placeholder="Pilih Program Studi"
                                {{ $m->jenis !== 'umum' ? 'required' : '' }}
                                multiple>
                                @php
                                    $selectedProdiIds = $m->prodis->pluck('id')->toArray();
                                @endphp
                                @foreach($prodi as $p)
                                <option value="{{ $p->id }}"
                                    {{ in_array($p->id, $selectedProdiIds) ? 'selected' : '' }}>
                                    {{ $p->nama_prodi }} ({{ $p->kode_prodi }})
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted fs-8">
                                <span id="hint_prodi_edit_{{ $m->id }}">
                                    @if($m->jenis === 'umum')
                                        <i class="bi bi-info-circle me-1"></i>Kosongkan jika berlaku untuk <strong>semua prodi</strong>
                                    @else
                                        Pilih <strong>minimal 1 prodi</strong> untuk MK {{ ucfirst($m->jenis) }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        {{-- Dosen Pengampu --}}
                        <div class="col-12">
                            <label class="form-label required fw-semibold fs-7">Dosen Pengampu</label>
                            <select name="id_dosen"
                                class="form-select form-select-solid"
                                data-control="select2"
                                required>
                                @foreach($dosen as $d)
                                <option value="{{ $d->id }}" {{ $m->id_dosen == $d->id ? 'selected' : '' }}>
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
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check2-circle me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalId = {{ $m->id }};
    const jenisSelect = document.getElementById('jenis_mk_edit_' + modalId);
    const prodiSelect = document.getElementById('select_prodi_edit_' + modalId);
    const labelRequired = document.getElementById('label_prodi_required_edit_' + modalId);
    const hintText = document.getElementById('hint_prodi_edit_' + modalId);

    // Fungsi untuk update UI berdasarkan jenis MK
    function updateProdiFieldEdit() {
        const jenis = jenisSelect.value;

        if (jenis === 'umum') {
            // MK Umum: Prodi tidak wajib
            labelRequired.classList.remove('required');
            prodiSelect.removeAttribute('required');
            hintText.innerHTML = '<i class="bi bi-info-circle me-1"></i>Kosongkan jika berlaku untuk <strong>semua prodi</strong>';
            hintText.classList.add('text-primary');
        } else {
            // MK Wajib/Pilihan: Prodi wajib
            labelRequired.classList.add('required');
            prodiSelect.setAttribute('required', 'required');
            hintText.innerHTML = 'Pilih <strong>minimal 1 prodi</strong> untuk MK ' + (jenis === 'wajib' ? 'Wajib' : 'Pilihan');
            hintText.classList.remove('text-primary');
        }
    }

    // Event listener untuk perubahan jenis MK
    if (jenisSelect) {
        jenisSelect.addEventListener('change', updateProdiFieldEdit);
    }

    // Initialize tooltip
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endpush
