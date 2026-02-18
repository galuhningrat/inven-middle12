<!-- Modal Edit Mata Kuliah -->
<div class="modal fade" id="modalEditMatkul{{ $m->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-750px">
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

                        {{-- Jenis MK --}}
                        <div class="col-md-4">
                            <label class="form-label required fw-semibold fs-7">Jenis</label>
                            <select name="jenis"
                                class="form-select form-select-solid"
                                data-control="select2"
                                data-hide-search="true"
                                required>
                                <option value="wajib"   {{ $m->jenis == 'wajib'   ? 'selected' : '' }}>Wajib</option>
                                <option value="pilihan" {{ $m->jenis == 'pilihan' ? 'selected' : '' }}>Pilihan</option>
                                <option value="umum"    {{ $m->jenis == 'umum'    ? 'selected' : '' }}>Umum</option>
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

                    {{-- ===== MAPPING PRODI & SEMESTER ===== --}}
                    <div class="separator separator-dashed my-5"></div>

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="fw-bolder text-dark mb-0">
                                <i class="bi bi-diagram-3 me-1 text-success"></i>
                                Mapping Prodi & Semester
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

                    {{-- Mapping rows — pre-populate dengan data yang sudah ada --}}
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
                                    <option value="{{ $s }}" {{ $existingMap->semester == $s ? 'selected' : '' }}>
                                        Semester {{ $s }}
                                    </option>
                                    @endfor
                                </select>
                            </div>
                            <div style="width:36px; flex-shrink:0">
                                <button type="button"
                                    class="btn btn-icon btn-sm btn-light remove-mapping-edit-{{ $m->id }}"
                                    title="Hapus baris"
                                    {{ $existingMappings->count() <= 1 ? 'disabled' : '' }}>
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </div>
                        </div>
                        @empty
                        {{-- Fallback: baris kosong jika belum ada mapping (seharusnya tidak terjadi) --}}
                        <div class="mapping-row d-flex align-items-center gap-2 mb-2">
                            <div class="flex-grow-1">
                                <select name="mappings[0][prodi_id]"
                                    class="form-select form-select-solid form-select-sm"
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
                                    class="btn btn-icon btn-sm btn-light remove-mapping-edit-{{ $m->id }}"
                                    disabled title="Hapus baris">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <button type="button"
                        class="btn btn-sm btn-light-success mt-1 btn-tambah-mapping-edit"
                        data-target="mapping-rows-edit-{{ $m->id }}"
                        data-remove-class="remove-mapping-edit-{{ $m->id }}"
                        data-matkul-id="{{ $m->id }}">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Prodi & Semester
                    </button>

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
