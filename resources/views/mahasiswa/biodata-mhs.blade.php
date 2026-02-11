@extends('mahasiswa.detail-mahasiswa')

@section('biodata')
    <!--begin::Profile Header with Avatar-->
    <div class="card mb-5">
        <div class="card-body p-9">
            <div class="d-flex flex-wrap flex-sm-nowrap">
                <!--begin::Avatar-->
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                        @php
                            $avatarPath = $mahasiswa->user->img ?? 'foto_users/default.png';
                            $avatarUrl = file_exists(public_path($avatarPath))
                                ? asset($avatarPath)
                                : asset('foto_users/default.png');
                        @endphp
                        <img src="{{ $avatarUrl }}" alt="{{ $mahasiswa->user->nama ?? 'Mahasiswa' }}"
                            style="object-fit: cover; border-radius: 50%; width: 160px; height: 160px;" />
                    </div>
                </div>
                <!--end::Avatar-->

                <!--begin::Info-->
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-900 fs-2 fw-bold me-1">{{ $mahasiswa->user->nama ?? '-' }}</span>
                            </div>
                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                <span class="d-flex align-items-center text-gray-400 me-5 mb-2">
                                    <i class="bi bi-person-badge fs-4 me-1"></i>
                                    NIM: {{ $mahasiswa->nim }}
                                </span>
                                <span class="d-flex align-items-center text-gray-400 me-5 mb-2">
                                    <i class="bi bi-envelope fs-4 me-1"></i>
                                    {{ $mahasiswa->user->email ?? '-' }}
                                </span>
                                <span class="d-flex align-items-center text-gray-400 me-5 mb-2">
                                    <i class="bi bi-telephone fs-4 me-1"></i>
                                    {{ $mahasiswa->hp ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex my-4">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalEditBiodata">
                                <i class="bi bi-pencil-fill"></i> Edit Profil
                            </button>
                        </div>
                    </div>
                </div>
                <!--end::Info-->
            </div>
        </div>
    </div>
    <!--end::Profile Header-->

    <!--begin::Card header-->
    <div class="card-header cursor-pointer">
        <div class="card-title m-0">
            <h3 class="fw-bolder m-0">Biodata</h3>
        </div>
    </div>

    <!--begin::Card body-->
    <div class="card-body p-9">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">Nomor Kartu Keluarga</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->no_kk ?? '-' }}</span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">NIK</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->nik ?? '-' }}</span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">NISN</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->nisn ?? '-' }}</span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">Tempat, Tanggal Lahir</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">
                    @if (empty($mahasiswa->tempat_lahir) && empty($mahasiswa->tanggal_lahir))
                        -
                    @else
                        {{ $mahasiswa->tempat_lahir ?? '' }}{{ $mahasiswa->tempat_lahir && $mahasiswa->tanggal_lahir ? ', ' : '' }}{{ $mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('d-m-Y') : '' }}
                    @endif
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">Jenis Kelamin</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->jenis_kelamin ?? '-' }}</span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">Alamat Lengkap</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">
                    @if (
                        $mahasiswa->dusun ||
                            $mahasiswa->rt ||
                            $mahasiswa->rw ||
                            $mahasiswa->ds_kel ||
                            $mahasiswa->kec ||
                            $mahasiswa->kab ||
                            $mahasiswa->prov ||
                            $mahasiswa->kode_pos)
                        {{ $mahasiswa->dusun }} RT/RW {{ $mahasiswa->rt }}/{{ $mahasiswa->rw }}, Desa/Kelurahan
                        {{ $mahasiswa->ds_kel }}, Kec. {{ $mahasiswa->kec }}, Kab. {{ $mahasiswa->kab }}, Prov.
                        {{ $mahasiswa->prov ?? '-' }} - {{ $mahasiswa->kode_pos }}
                    @else
                        -
                    @endif
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">Agama</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->agama ?? '-' }}</span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">Status Perkawinan</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->marital_status ?? '-' }}</span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">Kewarganegaraan</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->kewarganegaraan ?? '-' }}</span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">Golongan Darah</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $mahasiswa->gol_darah ?? '-' }}</span>
            </div>
        </div>
    </div>
    <!--end::Card body-->

    <!-- Modal Edit Biodata -->
    <div class="modal fade" id="modalEditBiodata" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form method="POST" action="{{ route('mahasiswa.biodata.update', $mahasiswa->nim) }}">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h3 class="modal-title">Edit Biodata Mahasiswa</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body scroll-y" style="max-height: 70vh;">
                        <!-- Nama & Email -->
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label required">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control"
                                    value="{{ old('nama', $mahasiswa->user->nama) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $mahasiswa->user->email) }}" required>
                            </div>
                        </div>

                        <!-- NIK, No KK, NISN -->
                        <div class="row mb-5">
                            <div class="col-md-4">
                                <label class="form-label required">NIK</label>
                                <input type="text" name="nik" class="form-control" maxlength="16"
                                    value="{{ old('nik', $mahasiswa->nik) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">No KK</label>
                                <input type="text" name="no_kk" class="form-control" maxlength="16"
                                    value="{{ old('no_kk', $mahasiswa->no_kk) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">NISN</label>
                                <input type="text" name="nisn" class="form-control" maxlength="10"
                                    value="{{ old('nisn', $mahasiswa->nisn) }}" required>
                            </div>
                        </div>

                        <!-- Tempat, Tanggal Lahir -->
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label required">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control"
                                    value="{{ old('tempat_lahir', $mahasiswa->tempat_lahir) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control"
                                    value="{{ old('tanggal_lahir', $mahasiswa->tanggal_lahir) }}" required>
                            </div>
                        </div>

                        <!-- Jenis Kelamin & Agama -->
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label required">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki"
                                        {{ $mahasiswa->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan"
                                        {{ $mahasiswa->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Agama</label>
                                <select name="agama" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Islam" {{ $mahasiswa->agama == 'Islam' ? 'selected' : '' }}>Islam
                                    </option>
                                    <option value="Katolik" {{ $mahasiswa->agama == 'Katolik' ? 'selected' : '' }}>Katolik
                                    </option>
                                    <option value="Protestan" {{ $mahasiswa->agama == 'Protestan' ? 'selected' : '' }}>
                                        Protestan</option>
                                    <option value="Hindu" {{ $mahasiswa->agama == 'Hindu' ? 'selected' : '' }}>Hindu
                                    </option>
                                    <option value="Budha" {{ $mahasiswa->agama == 'Budha' ? 'selected' : '' }}>Budha
                                    </option>
                                    <option value="Konghucu" {{ $mahasiswa->agama == 'Konghucu' ? 'selected' : '' }}>
                                        Konghucu</option>
                                </select>
                            </div>
                        </div>

                        <div class="separator my-5"></div>
                        <h4 class="mb-5">Alamat Lengkap</h4>

                        <!-- Alamat -->
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label required">Provinsi</label>
                                <input type="text" name="prov" class="form-control"
                                    value="{{ old('prov', $mahasiswa->prov) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Kabupaten/Kota</label>
                                <input type="text" name="kab" class="form-control"
                                    value="{{ old('kab', $mahasiswa->kab) }}" required>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label required">Kecamatan</label>
                                <input type="text" name="kec" class="form-control"
                                    value="{{ old('kec', $mahasiswa->kec) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Desa/Kelurahan</label>
                                <input type="text" name="ds_kel" class="form-control"
                                    value="{{ old('ds_kel', $mahasiswa->ds_kel) }}" required>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label required">Dusun/Jalan</label>
                                <input type="text" name="dusun" class="form-control"
                                    value="{{ old('dusun', $mahasiswa->dusun) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required">RT</label>
                                <input type="text" name="rt" class="form-control" maxlength="3"
                                    value="{{ old('rt', $mahasiswa->rt) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required">RW</label>
                                <input type="text" name="rw" class="form-control" maxlength="3"
                                    value="{{ old('rw', $mahasiswa->rw) }}" required>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-4">
                                <label class="form-label required">Kode Pos</label>
                                <input type="text" name="kode_pos" class="form-control" maxlength="5"
                                    value="{{ old('kode_pos', $mahasiswa->kode_pos) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">No HP/WA</label>
                                <input type="text" name="hp" class="form-control"
                                    value="{{ old('hp', $mahasiswa->hp) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Golongan Darah</label>
                                <select name="gol_darah" class="form-select">
                                    <option value="">Pilih</option>
                                    <option value="A" {{ $mahasiswa->gol_darah == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ $mahasiswa->gol_darah == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="AB" {{ $mahasiswa->gol_darah == 'AB' ? 'selected' : '' }}>AB
                                    </option>
                                    <option value="O" {{ $mahasiswa->gol_darah == 'O' ? 'selected' : '' }}>O</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label required">Status Perkawinan</label>
                                <select name="marital_status" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Lajang" {{ $mahasiswa->marital_status == 'Lajang' ? 'selected' : '' }}>
                                        Lajang</option>
                                    <option value="Menikah"
                                        {{ $mahasiswa->marital_status == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                    <option value="Cerai Hidup"
                                        {{ $mahasiswa->marital_status == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup
                                    </option>
                                    <option value="Cerai Mati"
                                        {{ $mahasiswa->marital_status == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Kewarganegaraan</label>
                                <select name="kewarganegaraan" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="WNI" {{ $mahasiswa->kewarganegaraan == 'WNI' ? 'selected' : '' }}>
                                        WNI</option>
                                    <option value="WNA" {{ $mahasiswa->kewarganegaraan == 'WNA' ? 'selected' : '' }}>
                                        WNA</option>
                                </select>
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
