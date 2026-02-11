@extends('master.app')

@section('content')
<!--begin::Toolbar-->
<div class="toolbar" id="kt_toolbar">
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
             data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
             class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Edit Data Dosen</h1>
            <span class="h-20px border-gray-200 border-start mx-4"></span>
        </div>
        <div class="d-flex align-items-center py-1">
            <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                <li class="breadcrumb-item text-muted">
                    <a href="/dashboard" class="text-muted text-hover-primary">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-200 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('dosen.index') }}" class="text-muted text-hover-primary">Data Dosen</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-200 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-dark">Edit</li>
            </ul>
        </div>
    </div>
</div>

<div id="kt_content_container" class="container-fluid">
    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">
            <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                        <div style="border-radius: 12px; overflow: hidden; max-width: 150px;">
                            <img src="{{ asset('storage/' . ($dosen->user->img ?? 'foto_users/blank.jpg')) }}" alt="Foto Profil"
                                 class="img-fluid shadow-sm"
                                 style="width: 150px; height: auto; object-fit: cover;">
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1">{{ $dosen->user->nama }}</a>
                            </div>
                            <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                    <span class="svg-icon svg-icon-4 me-1">
                                        <!-- icon ... -->
                                    </span>
                                    Dosen
                                </a>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                    <span class="svg-icon svg-icon-4 me-1">
                                        <!-- icon ... -->
                                    </span>
                                    {{ $dosen->user->email }}
                                </a>
                            </div>
                        </div>
                        <!-- Tombol Hapus -->
                        <div>
                            <form action="{{ route('dosen.destroy', $dosen->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="kt_account_profile_details">
            <form method="POST" action="{{ route('dosen.update', $dosen->id) }}" id="edit_dosen">
                @csrf
                @method('PUT')
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card-body border-top p-6 p-lg-9">
                    <input type="hidden" name="id_users" id="id_users" value="{{ $dosen->id_users }}">

                    <!-- NAMA LENGKAP (EDITABLE) -->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Nama Lengkap</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="nama" name="nama"
                                class="form-control form-control-lg form-control-solid"
                                placeholder="Nama Lengkap"
                                required
                                value="{{ old('nama', $dosen->user->nama ?? '') }}">
                        </div>
                    </div>
                    <!-- EMAIL (EDITABLE) -->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Email</label>
                        <div class="col-lg-8 fv-row">
                            <input type="email" id="email" name="email"
                                class="form-control form-control-lg form-control-solid"
                                placeholder="Email"
                                required
                                value="{{ old('email', $dosen->user->email ?? '') }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">NIP</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="nip" name="nip" class="form-control form-control-lg form-control-solid"
                                inputmode="numeric" pattern="[0-9]*"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                placeholder="Nomor Induk Pegawai" required
                                value="{{ old('nip', $dosen->nip) }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">No KK & KTP</label>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-6 fv-row">
                                    <input type="text" id="no_kk" name="no_kk" maxlength="16"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        inputmode="numeric" pattern="[0-9]*"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)"
                                        placeholder="No Kartu Keluarga" required
                                        value="{{ old('no_kk', $dosen->no_kk) }}">
                                </div>
                                <div class="col-lg-6 fv-row">
                                    <input type="text" id="nik" name="nik" maxlength="16"
                                        class="form-control form-control-lg form-control-solid"
                                        inputmode="numeric" pattern="[0-9]*"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)"
                                        placeholder="No KTP" required
                                        value="{{ old('nik', $dosen->nik) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">NIDN</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="nidn" name="nidn"
                                class="form-control form-control-lg form-control-solid"
                                placeholder="NIDN"
                                value="{{ old('nidn', $dosen->nidn) }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">NUPTK</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="nuptk" name="nuptk"
                                class="form-control form-control-lg form-control-solid"
                                placeholder="NUPTK"
                                value="{{ old('nuptk', $dosen->nuptk) }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">NPWP</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="npwp" name="npwp"
                                class="form-control form-control-lg form-control-solid"
                                placeholder="Nomor Pokok Wajib Pajak"
                                value="{{ old('npwp', $dosen->npwp) }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Tempat Lahir</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="tempat_lahir" name="tempat_lahir"
                                class="form-control form-control-lg form-control-solid"
                                placeholder="Kota/Kabupaten tempat lahir" required
                                value="{{ old('tempat_lahir', $dosen->tempat_lahir) }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Tanggal Lahir</label>
                        <div class="col-lg-8 fv-row">
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                class="form-control form-control-lg form-control-solid" required
                                value="{{ old('tanggal_lahir', $dosen->tanggal_lahir) }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Jenis Kelamin</label>
                        <div class="col-lg-8 fv-row">
                            <select id="jenis_kelamin" name="jenis_kelamin"
                                class="form-select form-select-solid" data-control="select2"
                                data-hide-search="true" data-placeholder="Pilih Jenis Kelamin" required>
                                <option></option>
                                <option value="Laki-laki" {{ old('jenis_kelamin', $dosen->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $dosen->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Agama</label>
                        <div class="col-lg-8 fv-row">
                            <select id="agama" name="agama"
                                class="form-select form-select-solid" data-control="select2"
                                data-hide-search="true" data-placeholder="Pilih Agama" required>
                                <option></option>
                                <option value="Islam" {{ old('agama', $dosen->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Katolik" {{ old('agama', $dosen->agama) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Protestan" {{ old('agama', $dosen->agama) == 'Protestan' ? 'selected' : '' }}>Protestan</option>
                                <option value="Hindu" {{ old('agama', $dosen->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Budha" {{ old('agama', $dosen->agama) == 'Budha' ? 'selected' : '' }}>Budha</option>
                                <option value="Konghucu" {{ old('agama', $dosen->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                        </div>
                    </div>

                    <div class="separator separator-dashed my-6"></div>

                    <div class="mb-7">
                        <h4 class="fw-bold text-dark mb-3">Alamat Tempat Tinggal</h4>
                        <div class="text-muted fw-semibold fs-6">Isi dengan alamat lengkap sesuai KTP</div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Provinsi</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="prov" name="prov"
                                class="form-control form-control-lg form-control-solid"
                                placeholder="Nama provinsi" required
                                value="{{ old('prov', $dosen->prov) }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Kabupaten/Kota</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="kab" name="kab" maxlength="20"
                                class="form-control form-control-lg form-control-solid"
                                placeholder="Nama Kabupaten/Kota" required
                                value="{{ old('kab', $dosen->kab) }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Kecamatan</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="kec" name="kec"
                                class="form-control form-control-lg form-control-solid"
                                placeholder="Nama kecamatan" required
                                value="{{ old('kec', $dosen->kec) }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Desa/Kelurahan</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="ds_kel" name="ds_kel"
                                class="form-control form-control-lg form-control-solid"
                                placeholder="Nama desa/kelurahan" required
                                value="{{ old('ds_kel', $dosen->ds_kel) }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Dusun/Jalan</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="dusun" name="dusun"
                                class="form-control form-control-lg form-control-solid"
                                placeholder="Nama Dusun/Jalan" required
                                value="{{ old('dusun', $dosen->dusun) }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">RT / RW</label>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-6 fv-row">
                                    <input type="text" id="rt" name="rt" maxlength="3"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        inputmode="numeric" pattern="[0-9]*"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3)"
                                        placeholder="RT" required
                                        value="{{ old('rt', $dosen->rt) }}">
                                </div>
                                <div class="col-lg-6 fv-row">
                                    <input type="text" id="rw" name="rw" maxlength="3"
                                        class="form-control form-control-lg form-control-solid"
                                        inputmode="numeric" pattern="[0-9]*"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3)"
                                        placeholder="RW" required
                                        value="{{ old('rw', $dosen->rw) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Kode Pos</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="kode_pos" name="kode_pos" maxlength="5"
                                class="form-control form-control-lg form-control-solid"
                                inputmode="numeric" pattern="[0-9]*"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5)"
                                placeholder="Kode pos (5 digit)" required
                                value="{{ old('kode_pos', $dosen->kode_pos) }}">
                        </div>
                    </div>

                    <div class="separator separator-dashed my-6"></div>

                    <div class="mb-7">
                        <h4 class="fw-bold text-dark mb-3">Kontak & Informasi Tambahan</h4>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">No HP/WhatsApp</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="no_hp" name="no_hp"
                                class="form-control form-control-lg form-control-solid"
                                inputmode="numeric" pattern="[0-9]*"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                placeholder="Nomor Handphone/WhatsApp" required
                                value="{{ old('no_hp', $dosen->no_hp) }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Status Perkawinan</label>
                        <div class="col-lg-8 fv-row">
                            <select id="marital_status" name="marital_status"
                                class="form-select form-select-solid" data-control="select2"
                                data-hide-search="true" data-placeholder="Pilih Status Perkawinan" required>
                                <option></option>
                                <option value="Lajang" {{ old('marital_status', $dosen->marital_status) == 'Lajang' ? 'selected' : '' }}>Lajang</option>
                                <option value="Menikah" {{ old('marital_status', $dosen->marital_status) == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                <option value="Cerai Hidup" {{ old('marital_status', $dosen->marital_status) == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                <option value="Cerai Mati" {{ old('marital_status', $dosen->marital_status) == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Status Dosen</label>
                        <div class="col-lg-8 fv-row">
                            <select id="status" name="status"
                                class="form-select form-select-solid" data-control="select2"
                                data-hide-search="true" data-placeholder="Pilih Status Dosen" required>
                                <option></option>
                                <option value="Dosen Tetap" {{ old('status', $dosen->status) == 'Dosen Tetap' ? 'selected' : '' }}>Dosen Tetap</option>
                                <option value="Dosen Tidak Tetap" {{ old('status', $dosen->status) == 'Dosen Tidak Tetap' ? 'selected' : '' }}>Dosen Tidak Tetap</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Kewarganegaraan</label>
                        <div class="col-lg-8 fv-row">
                            <select id="kewarganegaraan" name="kewarganegaraan"
                                class="form-select form-select-solid" data-control="select2"
                                data-hide-search="true" data-placeholder="Pilih Kewarganegaraan" required>
                                <option></option>
                                <option value="WNI" {{ old('kewarganegaraan', $dosen->kewarganegaraan) == 'WNI' ? 'selected' : '' }}>WNI</option>
                                <option value="WNA" {{ old('kewarganegaraan', $dosen->kewarganegaraan) == 'WNA' ? 'selected' : '' }}>WNA</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">Golongan Darah</label>
                        <div class="col-lg-8 fv-row">
                            <select id="gol_darah" name="gol_darah"
                                class="form-select form-select-solid" data-control="select2"
                                data-hide-search="true" data-placeholder="Pilih Golongan Darah">
                                <option></option>
                                <option value="A" {{ old('gol_darah', $dosen->gol_darah) == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('gol_darah', $dosen->gol_darah) == 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ old('gol_darah', $dosen->gol_darah) == 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ old('gol_darah', $dosen->gol_darah) == 'O' ? 'selected' : '' }}>O</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex flex-column flex-sm-row justify-content-between py-6 px-6 px-lg-9">
                    <a href="{{ route('dosen.index') }}"
                        class="btn btn-light mb-3 mb-sm-0 w-100 w-sm-auto">
                        Batal
                    </a>
                    <div class="d-flex">
                        <button type="reset" class="btn btn-light-danger me-2">Reset</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection