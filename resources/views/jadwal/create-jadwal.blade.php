<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="modalTambahJadwal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <form id="form_tambah_jadwal" method="POST" action="{{ route('jadwal.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahJadwalLabel">Tambah Jadwal Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Mata Kuliah</label>
                                <div class="col-lg-8 fv-row">
                                    <select id="id_matkul" name="id_matkul" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Mata Kuliah" required>
                                        <option></option>
                                        @foreach ($matkul as $mk)
                                            <option value="{{ $mk->id }}" {{ old('id_matkul') == $mk->id ? 'selected' : '' }}>
                                                {{ $mk->kode_mk }} - {{ $mk->nama_mk }}
                                            </option>
                                        @endforeach
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
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Dosen</label>
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

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Rombel</label>
                                <div class="col-lg-8 fv-row">
                                    <select id="id_rombel" name="id_rombel" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Rombel" required>
                                        <option></option>
                                        @foreach ($rombel as $r)
                                            <option value="{{ $r->id }}" {{ old('id_rombel') == $r->id ? 'selected' : '' }}>{{ $r->nama_rombel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Tahun Akademik</label>
                                <div class="col-lg-8 fv-row">
                                    <select id="tahun_akademik" name="tahun_akademik" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Tahun Akademik" required>
                                        <option></option>
                                        @foreach ($tahun_akademik as $ta)
                                            <option value="{{ $ta->id }}">{{ $ta->tahun_awal }} {{ $ta->semester }}</option>
                                        @endforeach
                                        
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Ruangan</label>
                                <div class="col-lg-8 fv-row">
                                    <select id="id_ruangan" name="id_ruangan" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Ruangan" required>
                                        <option></option>
                                        @foreach ($ruangan as $r)
                                            <option value="{{ $r->id }}" {{ old('id_ruangan') == $r->id ? 'selected' : '' }}>{{ $r->nama_ruang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Hari</label>
                                <div class="col-lg-8 fv-row">
                                    <select id="hari" name="hari" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Hari" required>
                                        <option></option>
                                        <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                                        <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                        <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                        <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                        <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                        <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Jam Mulai</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="time" id="jam_mulai" name="jam_mulai" class="form-control form-control-lg form-control-solid" required value="{{ old('jam_mulai') }}">
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Jam Selesai</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="time" id="jam_selesai" name="jam_selesai" class="form-control form-control-lg form-control-solid" required value="{{ old('jam_selesai') }}">
                                </div>
                            </div>
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