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
                                @for($s = 1; $s <= 8; $s++)
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

                        {{-- Program Studi --}}
                        <div class="col-md-8">
                            <label class="form-label required fw-semibold fs-7">Program Studi</label>
                            <select name="id_prodi"
                                class="form-select form-select-solid"
                                data-control="select2"
                                data-placeholder="Pilih Program Studi"
                                required>
                                <option></option>
                                @foreach($prodi as $p)
                                <option value="{{ $p->id }}" {{ old('id_prodi') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_prodi }} ({{ $p->kode_prodi }})
                                </option>
                                @endforeach
                            </select>
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
