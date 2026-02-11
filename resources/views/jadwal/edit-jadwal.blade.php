<!-- Modal Edit Jadwal -->
<div class="modal fade" id="modalEditJadwal{{ $j->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <form method="POST" action="{{ route('jadwal.update', $j->id) }}" id="formEditJadwal{{ $j->id }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jadwal Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Mata Kuliah</label>
                                <div class="col-lg-8 fv-row">
                                    <select id="edit_id_matkul_{{ $j->id }}" name="id_matkul" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Mata Kuliah" required>
                                        @foreach ($matkul as $mk)
                                            <option value="{{ $mk->id }}" {{ $j->id_matkul == $mk->id ? 'selected' : '' }}>
                                                {{ $mk->kode_mk }} - {{ $mk->nama_mk }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Dosen</label>
                                <div class="col-lg-8 fv-row">
                                    <select id="edit_id_dosen_{{ $j->id }}" name="id_dosen" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Dosen" required>
                                        @foreach ($dosen as $d)
                                            <option value="{{ $d->id }}" {{ $j->id_dosen == $d->id ? 'selected' : '' }}>
                                                {{ $d->user->nama ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Rombel</label>
                                <div class="col-lg-8 fv-row">
                                    <select id="edit_id_rombel_{{ $j->id }}" name="id_rombel" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Rombel" required>
                                        @foreach ($rombel as $r)
                                            <option value="{{ $r->id }}" {{ $j->id_rombel == $r->id ? 'selected' : '' }}>{{ $r->nama_rombel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Tahun Akademik</label>
                                <div class="col-lg-8 fv-row">
                                    <select id="edit_tahun_akademik_{{ $j->id }}" name="tahun_akademik" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Tahun Akademik" required>
                                        @foreach ($tahun_akademik as $ta)
                                            <option value="{{ $ta->id }}" {{ $j->tahun_akademik == $ta->id ? 'selected' : '' }}>{{ $ta->tahun_akademik }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Ruangan</label>
                                <div class="col-lg-8 fv-row">
                                    <select id="edit_id_ruangan_{{ $j->id }}" name="id_ruangan" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Ruangan" required>
                                        @foreach ($ruangan as $r)
                                            <option value="{{ $r->id }}" {{ $j->id_ruangan == $r->id ? 'selected' : '' }}>{{ $r->nama_ruang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Hari</label>
                                <div class="col-lg-8 fv-row">
                                    <select id="edit_hari_{{ $j->id }}" name="hari" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Hari" required>
                                        <option value="Senin" {{ $j->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                                        <option value="Selasa" {{ $j->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                        <option value="Rabu" {{ $j->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                        <option value="Kamis" {{ $j->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                        <option value="Jumat" {{ $j->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                        <option value="Sabtu" {{ $j->hari == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mb-6">
                                <div class="col-md-6">
                                    <label class="col-form-label required fw-bold fs-6">Jam Mulai</label>
                                    <input type="time" id="edit_jam_mulai_{{ $j->id }}" name="jam_mulai" class="form-control form-control-lg form-control-solid" required value="{{ \Carbon\Carbon::createFromFormat('H:i:s', $j->jam_mulai)->format('H:i') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label required fw-bold fs-6">Jam Selesai</label>
                                    <input type="time" id="edit_jam_selesai_{{ $j->id }}" name="jam_selesai" class="form-control form-control-lg form-control-solid" required value="{{ \Carbon\Carbon::createFromFormat('H:i:s', $j->jam_selesai)->format('H:i') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Debug info (remove in production) -->
                    <input type="hidden" name="debug_id" value="{{ $j->id }}">
                </div>
                
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <span class="indicator-label">Update</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>