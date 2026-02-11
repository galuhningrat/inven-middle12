<!-- Modal Tambah Mata Kuliah -->
<div class="modal fade" id="modalTambahMatkul" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form id="form_tambah_matkul" method="POST" action="{{ route('matakuliah.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahMatkulLabel">Tambah Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Kode Mata Kuliah</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="kode_mk" name="kode_mk" class="form-control form-control-lg form-control-solid" placeholder="Kode MK (misal: TIF101)" required value="{{ old('kode_mk') }}">
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Nama Mata Kuliah</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="nama_mk" name="nama_mk" class="form-control form-control-lg form-control-solid" placeholder="Nama Mata Kuliah" required value="{{ old('nama_mk') }}">
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Bobot</label>
                        <div class="col-lg-8 fv-row">
                            <input type="number" id="bobot" name="bobot" min="1" max="9" class="form-control form-control-lg form-control-solid" placeholder="Jumlah SKS" required value="{{ old('bobot') }}">
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Jenis</label>
                        <div class="col-lg-8 fv-row">
                            <select id="jenis" name="jenis" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Jenis" required>
                                <option value="wajib" {{ old('jenis') == 'wajib' ? 'selected' : '' }}>Wajib</option>
                                <option value="pilihan" {{ old('jenis') == 'pilihan' ? 'selected' : '' }}>Pilihan</option>
                                <option value="umum" {{ old('jenis') == 'umum' ? 'selected' : '' }}>Umum</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Program Studi</label>
                        <div class="col-lg-8 fv-row">
                            <select id="id_prodi" name="id_prodi" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Prodi" required>
                                <option></option>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->id }}" {{ old('id_prodi') == $p->id ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Dosen Pengampu</label>
                        <div class="col-lg-8 fv-row">
                            <select id="id_dosen" name="id_dosen" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Dosen" required>
                                <option></option>
                                @foreach ($dosen as $d)
                                    <option value="{{ $d->id }}" {{ old('id_dosen') == $d->id ? 'selected' : '' }}>
                                        {{ $d->user->nama ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>