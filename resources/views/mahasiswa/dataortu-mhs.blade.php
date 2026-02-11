@extends('mahasiswa.detail-mahasiswa')

@section('dataortu')
    <div class="card-header cursor-pointer">
        <div class="card-title m-0">
            <h3 class="fw-bolder m-0">Data Orang Tua / Wali</h3>
        </div>
        <div class="card-toolbar d-flex align-items-center">
            <form action="{{ route('mahasiswa.reset-ortu', $mahasiswa->nim) }}" method="POST"
                onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan data orang tua mahasiswa ini?')">
                @csrf
                <button type="submit" class="btn btn-light-danger btn-sm me-3">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Data
                </button>
            </form>

            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditOrtu">
                <i class="bi bi-pencil me-2"></i>Edit
            </button>
        </div>
    </div>

    <div class="card-body pt-3 pb-9 px-9">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- DATA AYAH -->
        <div class="m-0 mb-8">
            <div class="d-flex align-items-center mb-5">
                <i class="bi bi-person fs-1 text-primary me-3"></i>
                <h4 class="text-gray-700 fw-bolder mb-0">Data Ayah Kandung</h4>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="row mb-4">
                        <label class="col-lg-5 fw-bold text-muted">Nama Ayah</label>
                        <div class="col-lg-7">
                            <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->nama_ayah ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-lg-5 fw-bold text-muted">Tempat, Tanggal Lahir</label>
                        <div class="col-lg-7">
                            <span class="fw-bolder fs-6 text-gray-800">
                                @if ($mahasiswa->tempat_lahir_ayah && $mahasiswa->tanggal_lahir_ayah)
                                    {{ $mahasiswa->tempat_lahir_ayah }},
                                    {{ \Carbon\Carbon::parse($mahasiswa->tanggal_lahir_ayah)->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-lg-5 fw-bold text-muted">Pendidikan Terakhir</label>
                        <div class="col-lg-7">
                            <span class="badge badge-light-info">{{ $mahasiswa->pendidikan_ayah ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row mb-4">
                        <label class="col-lg-5 fw-bold text-muted">Pekerjaan</label>
                        <div class="col-lg-7">
                            <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->pekerjaan_ayah ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-lg-5 fw-bold text-muted">Penghasilan</label>
                        <div class="col-lg-7">
                            <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->penghasilan_ayah ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-lg-5 fw-bold text-muted">No HP/WhatsApp</label>
                        <div class="col-lg-7">
                            <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->hp_ayah ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if ($mahasiswa->alamat_ayah)
                <div class="row mt-5">
                    <label class="col-lg-2 fw-bold text-muted">Alamat Ayah</label>
                    <div class="col-lg-10">
                        <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->alamat_ayah }}</span>
                    </div>
                </div>
            @endif
        </div>

        <div class="separator separator-dashed my-8"></div>

        <!-- DATA IBU -->
        <div class="m-0 mb-5">
            <div class="d-flex align-items-center mb-5">
                <i class="bi bi-person-heart fs-1 text-danger me-3"></i>
                <h4 class="text-gray-700 fw-bolder mb-0">Data Ibu Kandung</h4>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="row mb-4">
                        <label class="col-lg-5 fw-bold text-muted">Nama Ibu</label>
                        <div class="col-lg-7">
                            <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->nama_ibu ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-lg-5 fw-bold text-muted">Tempat, Tanggal Lahir</label>
                        <div class="col-lg-7">
                            <span class="fw-bolder fs-6 text-gray-800">
                                @if ($mahasiswa->tempat_lahir_ibu && $mahasiswa->tanggal_lahir_ibu)
                                    {{ $mahasiswa->tempat_lahir_ibu }},
                                    {{ \Carbon\Carbon::parse($mahasiswa->tanggal_lahir_ibu)->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-lg-5 fw-bold text-muted">Pendidikan Terakhir</label>
                        <div class="col-lg-7">
                            <span class="badge badge-light-info">{{ $mahasiswa->pendidikan_ibu ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row mb-4">
                        <label class="col-lg-5 fw-bold text-muted">Pekerjaan</label>
                        <div class="col-lg-7">
                            <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->pekerjaan_ibu ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-lg-5 fw-bold text-muted">Penghasilan</label>
                        <div class="col-lg-7">
                            <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->penghasilan_ibu ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-lg-5 fw-bold text-muted">No HP/WhatsApp</label>
                        <div class="col-lg-7">
                            <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->hp_ibu ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if ($mahasiswa->alamat_ibu)
                <div class="row mt-5">
                    <label class="col-lg-2 fw-bold text-muted">Alamat Ibu</label>
                    <div class="col-lg-10">
                        <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->alamat_ibu }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Edit Orang Tua (FIXED LAYOUT) -->
    <div class="modal fade" id="modalEditOrtu" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form action="{{ route('mahasiswa.orangtua.update', $mahasiswa->nim) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h3 class="modal-title">Edit Data Orang Tua</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <!-- DATA AYAH -->
                        <div class="mb-8">
                            <h4 class="text-primary mb-5"><i class="bi bi-person me-2"></i>Data Ayah Kandung</h4>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label required">Nama Ayah</label>
                                    <input type="text" name="nama_ayah" class="form-control"
                                        value="{{ old('nama_ayah', $mahasiswa->nama_ayah) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Pekerjaan</label>
                                    <input type="text" name="pekerjaan_ayah" class="form-control"
                                        value="{{ old('pekerjaan_ayah', $mahasiswa->pekerjaan_ayah) }}">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir_ayah" class="form-control"
                                        value="{{ old('tempat_lahir_ayah', $mahasiswa->tempat_lahir_ayah) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir_ayah" class="form-control"
                                        value="{{ old('tanggal_lahir_ayah', $mahasiswa->tanggal_lahir_ayah) }}">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label">Pendidikan Terakhir</label>
                                    <select name="pendidikan_ayah" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="SD"
                                            {{ $mahasiswa->pendidikan_ayah == 'SD' ? 'selected' : '' }}>SD</option>
                                        <option value="SMP"
                                            {{ $mahasiswa->pendidikan_ayah == 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="SMA"
                                            {{ $mahasiswa->pendidikan_ayah == 'SMA' ? 'selected' : '' }}>SMA</option>
                                        <option value="D1"
                                            {{ $mahasiswa->pendidikan_ayah == 'D1' ? 'selected' : '' }}>D1</option>
                                        <option value="D2"
                                            {{ $mahasiswa->pendidikan_ayah == 'D2' ? 'selected' : '' }}>D2</option>
                                        <option value="D3"
                                            {{ $mahasiswa->pendidikan_ayah == 'D3' ? 'selected' : '' }}>D3</option>
                                        <option value="D4"
                                            {{ $mahasiswa->pendidikan_ayah == 'D4' ? 'selected' : '' }}>D4</option>
                                        <option value="S1"
                                            {{ $mahasiswa->pendidikan_ayah == 'S1' ? 'selected' : '' }}>S1</option>
                                        <option value="S2"
                                            {{ $mahasiswa->pendidikan_ayah == 'S2' ? 'selected' : '' }}>S2</option>
                                        <option value="S3"
                                            {{ $mahasiswa->pendidikan_ayah == 'S3' ? 'selected' : '' }}>S3</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Penghasilan</label>
                                    <select name="penghasilan_ayah" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="< 1 Juta"
                                            {{ $mahasiswa->penghasilan_ayah == '< 1 Juta' ? 'selected' : '' }}>
                                            < 1 Juta</option>
                                        <option value="1-3 Juta"
                                            {{ $mahasiswa->penghasilan_ayah == '1-3 Juta' ? 'selected' : '' }}>1-3 Juta
                                        </option>
                                        <option value="3-5 Juta"
                                            {{ $mahasiswa->penghasilan_ayah == '3-5 Juta' ? 'selected' : '' }}>3-5 Juta
                                        </option>
                                        <option value="> 5 Juta"
                                            {{ $mahasiswa->penghasilan_ayah == '> 5 Juta' ? 'selected' : '' }}>> 5 Juta
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">No HP/WhatsApp</label>
                                    <input type="text" name="hp_ayah" class="form-control"
                                        value="{{ old('hp_ayah', $mahasiswa->hp_ayah) }}">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Alamat Lengkap Ayah</label>
                                <textarea name="alamat_ayah" class="form-control" rows="2">{{ old('alamat_ayah', $mahasiswa->alamat_ayah) }}</textarea>
                            </div>
                        </div>

                        <div class="separator separator-dashed my-8"></div>

                        <!-- DATA IBU -->
                        <div class="mb-5">
                            <h4 class="text-danger mb-5"><i class="bi bi-person-heart me-2"></i>Data Ibu Kandung</h4>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label required">Nama Ibu</label>
                                    <input type="text" name="nama_ibu" class="form-control"
                                        value="{{ old('nama_ibu', $mahasiswa->nama_ibu) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Pekerjaan</label>
                                    <input type="text" name="pekerjaan_ibu" class="form-control"
                                        value="{{ old('pekerjaan_ibu', $mahasiswa->pekerjaan_ibu) }}">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir_ibu" class="form-control"
                                        value="{{ old('tempat_lahir_ibu', $mahasiswa->tempat_lahir_ibu) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir_ibu" class="form-control"
                                        value="{{ old('tanggal_lahir_ibu', $mahasiswa->tanggal_lahir_ibu) }}">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label">Pendidikan Terakhir</label>
                                    <select name="pendidikan_ibu" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="SD" {{ $mahasiswa->pendidikan_ibu == 'SD' ? 'selected' : '' }}>
                                            SD</option>
                                        <option value="SMP"
                                            {{ $mahasiswa->pendidikan_ibu == 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="SMA"
                                            {{ $mahasiswa->pendidikan_ibu == 'SMA' ? 'selected' : '' }}>SMA</option>
                                        <option value="D1"
                                            {{ $mahasiswa->pendidikan_ayah == 'D1' ? 'selected' : '' }}>D1</option>
                                        <option value="D2"
                                            {{ $mahasiswa->pendidikan_ayah == 'D2' ? 'selected' : '' }}>D2</option>
                                        <option value="D3"
                                            {{ $mahasiswa->pendidikan_ayah == 'D3' ? 'selected' : '' }}>D3</option>
                                        <option value="D4"
                                            {{ $mahasiswa->pendidikan_ayah == 'D4' ? 'selected' : '' }}>D4</option>
                                        <option value="S1" {{ $mahasiswa->pendidikan_ibu == 'S1' ? 'selected' : '' }}>
                                            S1</option>
                                        <option value="S2" {{ $mahasiswa->pendidikan_ibu == 'S2' ? 'selected' : '' }}>
                                            S2</option>
                                        <option value="S3" {{ $mahasiswa->pendidikan_ibu == 'S3' ? 'selected' : '' }}>
                                            S3</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Penghasilan</label>
                                    <select name="penghasilan_ibu" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="< 1 Juta"
                                            {{ $mahasiswa->penghasilan_ibu == '< 1 Juta' ? 'selected' : '' }}>
                                            < 1 Juta</option>
                                        <option value="1-3 Juta"
                                            {{ $mahasiswa->penghasilan_ibu == '1-3 Juta' ? 'selected' : '' }}>1-3 Juta
                                        </option>
                                        <option value="3-5 Juta"
                                            {{ $mahasiswa->penghasilan_ibu == '3-5 Juta' ? 'selected' : '' }}>3-5 Juta
                                        </option>
                                        <option value="> 5 Juta"
                                            {{ $mahasiswa->penghasilan_ibu == '> 5 Juta' ? 'selected' : '' }}>> 5 Juta
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">No HP/WhatsApp</label>
                                    <input type="text" name="hp_ibu" class="form-control"
                                        value="{{ old('hp_ibu', $mahasiswa->hp_ibu) }}">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Alamat Lengkap Ibu</label>
                                <textarea name="alamat_ibu" class="form-control" rows="2">{{ old('alamat_ibu', $mahasiswa->alamat_ibu) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
