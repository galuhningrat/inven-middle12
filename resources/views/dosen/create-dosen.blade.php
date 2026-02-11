@extends('master.app')

@section('content')
<!--begin::Toolbar-->
<div class="toolbar" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <!--begin::Title-->
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Tambah Data Dosen</h1>
            <!--end::Title-->
            <!--begin::Separator-->
            <span class="h-20px border-gray-200 border-start mx-4"></span>
            <!--end::Separator-->
        </div>
        <!--end::Page title-->
        <!--begin::Actions-->
        <div class="d-flex align-items-center py-1">
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">
                    <a href="/dashboard" class="text-muted text-hover-primary">Dashboard</a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-200 w-5px h-2px"></span>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">
                    <a href="/dosen" class="text-muted text-hover-primary">Data Dosen</a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-200 w-5px h-2px"></span>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item text-dark">Tambah</li>
                <!--end::Item-->
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Container-->
</div>

<!--begin::Content-->
<div id="kt_content_container" class="container-fluid">
    <!--begin::Navbar-->
    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">
            <!--begin::Details-->
            <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                <!--begin: Pic-->
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                        <div style="border-radius: 12px; overflow: hidden; max-width: 150px;">
                            <img src="{{ asset('storage/' . $user->img) }}" alt="Foto Profil" class="img-fluid shadow-sm"
                                 style="width: 150px; height: auto; object-fit: cover;">
                        </div>
                    </div>
                </div>
                <!--end::Pic-->
                <!--begin::Info-->
                <div class="flex-grow-1">
                    <!--begin::Title-->
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <!--begin::User-->
                        <div class="d-flex flex-column">
                            <!--begin::Name-->
                            <div class="d-flex align-items-center mb-2">
                                <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1">{{ $user->nama }}</a>
                            </div>
                            <!--end::Name-->
                            <!--begin::Info-->
                            <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                    <!--begin::Svg Icon | path: icons/duotune/communication/com006.svg-->
                                    <span class="svg-icon svg-icon-4 me-1">
																<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																	<path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z" fill="black" />
																	<path d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z" fill="black" />
																</svg>
															</span>
                                    <!--end::Svg Icon-->Dosen</a>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                    <!--begin::Svg Icon | path: icons/duotune/communication/com011.svg-->
                                    <span class="svg-icon svg-icon-4 me-1">
																<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																	<path opacity="0.3" d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z" fill="black" />
																	<path d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z" fill="black" />
																</svg>
															</span>
                                    <!--end::Svg Icon-->{{ $user->email }}</a>
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::User-->

                    </div>
                    <!--end::Title-->
                </div>
                <!--end::Info-->
            </div>
            <!--end::Details-->
        </div>
    </div>
    <!--end::Navbar-->
    <div class="card">
        <div id="kt_account_profile_details">
            <!--begin::Form-->
            <form method="POST" action="{{ route('dosen.store') }}" id="tambah_dosen">
                @csrf
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!--begin::Card body-->
                <div class="card-body border-top p-6 p-lg-9">
                    <input type="hidden" name="id_users" id="id_users" value="{{ $user->id }}">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">NIP</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="nip" name="nip" class="form-control form-control-lg form-control-solid" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Nomor Induk Pegawai" required>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">No KK & KTP</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" id="no_kk" name="no_kk" maxlength="16" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)" placeholder="No Kartu Keluarga" required>
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" id="nik" name="nik" maxlength="16" class="form-control form-control-lg form-control-solid" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)" placeholder="No KTP" required>
                                </div>
                                <!--end::Col-->
                            </div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6">NIDN / NUPTK</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" id="nidn" name="nidn" maxlength="10" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" placeholder="NIDN">
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" id="nuptk" name="nuptk" maxlength="16" class="form-control form-control-lg form-control-solid" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)" placeholder="NUPTK">
                                </div>
                                <!--end::Col-->
                            </div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">NPWP</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="npwp" name="npwp" class="form-control form-control-lg form-control-solid" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)" placeholder="Nomor Pokok Wajib Pajak">
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Tempat Lahir</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control form-control-lg form-control-solid" placeholder="Kota/Kabupaten tempat lahir" required>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Tanggal Lahir</label>
                        <div class="col-lg-8 fv-row">
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control form-control-lg form-control-solid" required>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Jenis Kelamin</label>
                        <div class="col-lg-8 fv-row">
                            <select id="jenis_kelamin" name="jenis_kelamin" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Jenis Kelamin" required>
                                <option></option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Agama</label>
                        <div class="col-lg-8 fv-row">
                            <select id="agama" name="agama" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Agama" required>
                                <option></option>
                                <option value="Islam">Islam</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Protestan">Protestan</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Budha">Budha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Separator-->
                    <div class="separator separator-dashed my-6"></div>
                    <!--end::Separator-->

                    <!--begin::Heading-->
                    <div class="mb-7">
                        <h4 class="fw-bold text-dark mb-3">Alamat Tempat Tinggal</h4>
                        <div class="text-muted fw-semibold fs-6">Isi dengan alamat lengkap sesuai KTP</div>
                    </div>
                    <!--end::Heading-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Provinsi</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="prov" name="prov" class="form-control form-control-lg form-control-solid" placeholder="Nama provinsi" required>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Kabupaten/Kota</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="kab" name="kab" maxlength="20" class="form-control form-control-lg form-control-solid" placeholder="Nama Kabupaten/Kota" required>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Kecamatan</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="kec" name="kec" class="form-control form-control-lg form-control-solid" placeholder="Nama kecamatan" required>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Desa/Kelurahan</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="ds_kel" name="ds_kel" class="form-control form-control-lg form-control-solid" placeholder="Nama desa/kelurahan" required>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Dusun/Jalan</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="dusun" name="dusun" class="form-control form-control-lg form-control-solid" placeholder="Nama Dusun/Jalan" required>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">RT / RW</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" id="rt" name="rt" maxlength="3" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3)" placeholder="RT" required>
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" id="rw" name="rw" maxlength="3" class="form-control form-control-lg form-control-solid" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3)" placeholder="RW" required>
                                </div>
                                <!--end::Col-->
                            </div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Kode Pos</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="kode_pos" name="kode_pos" maxlength="5" class="form-control form-control-lg form-control-solid" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5)" placeholder="Kode pos (5 digit)" required>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Separator-->
                    <div class="separator separator-dashed my-6"></div>
                    <!--end::Separator-->

                    <!--begin::Heading-->
                    <div class="mb-7">
                        <h4 class="fw-bold text-dark mb-3">Kontak & Informasi Tambahan</h4>
                    </div>
                    <!--end::Heading-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">No HP/WhatsApp</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="no_hp" name="no_hp" class="form-control form-control-lg form-control-solid" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Nomor Handphone/WhatsApp" required>
                        </div>
                    </div>
                    <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Status Perkawinan</label>
                <div class="col-lg-8 fv-row">
                    <select id="marital_status" name="marital_status" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Status Perkawinan" required>
                        <option></option>
                        <option value="Lajang">Lajang</option>
                        <option value="Menikah">Menikah</option>
                        <option value="Cerai Hidup">Cerai Hidup</option>
                        <option value="Cerai Mati">Cerai Mati</option>
                    </select>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Status Dosen</label>
                <div class="col-lg-8 fv-row">
                    <select id="status" name="status" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Status Dosen" required>
                        <option></option>
                        <option value="Dosen Tetap">Dosen Tetap</option>
                        <option value="Dosen Tidak Tetap">Dosen Tidak Tetap</option>
                    </select>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Kewarganegaraan</label>
                <div class="col-lg-8 fv-row">
                    <select id="kewarganegaraan" name="kewarganegaraan" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Kewarganegaraan" required>
                        <option></option>
                        <option value="WNI">WNI</option>
                        <option value="WNA">WNA</option>
                    </select>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label fw-bold fs-6">Golongan Darah</label>
                <div class="col-lg-8 fv-row">
                    <select id="gol_darah" name="gol_darah" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Golongan Darah">
                        <option></option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                    </select>
                </div>
            </div>
            <!--end::Input group-->

                </div>
                <!--end::Card body-->

                <!--begin::Actions-->
                <div class="card-footer d-flex flex-column flex-sm-row justify-content-between py-6 px-6 px-lg-9">
                    <!-- Tombol Batal (Kiri) -->
                    <a href="{{ route('dosen.index') }}" class="btn btn-light mb-3 mb-sm-0 w-100 w-sm-auto">
                        Batal
                    </a>

                    <div class="d-flex">
                        <!-- Tombol Reset (Tengah) -->
                        <button type="reset" class="btn btn-light-danger me-2">Reset</button>
                        <!-- Tombol Simpan (Kanan) -->
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
</div>
<!--end::Content-->

{{--@push('scripts')--}}
{{--<script>--}}
{{--// Submit button handler--}}
{{--const submitButton = document.getElementById('kt_account_profile_details_submit');--}}
{{--const form = document.getElementById('kt_account_profile_details_form');--}}

{{--submitButton.addEventListener('click', function (e) {--}}
{{--    e.preventDefault();--}}

{{--    // Show loading state--}}
{{--    submitButton.setAttribute('data-kt-indicator', 'on');--}}
{{--    submitButton.disabled = true;--}}

{{--    // Submit form after short delay--}}
{{--    setTimeout(function() {--}}
{{--        form.submit();--}}
{{--    }, 1000);--}}
{{--});--}}
{{--</script>--}}
{{--@endpush--}}
@endsection
