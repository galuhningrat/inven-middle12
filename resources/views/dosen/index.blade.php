@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Data Dosen</h1>
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
                    <li class="breadcrumb-item text-dark">Data Dosen</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Container-->
    </div>
@endsection

@section('content')
    <!--begin::Tampil Dosen-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-fluid">
            @include('master.notification')
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="d-flex flex-wrap justify-content-between align-items-center w-100">
                        <!--begin::Search-->
                        <div id="custom-search-container" class="mb-2 mb-md-0"></div>
                        <!--end::Search-->
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <!-- Export Buttons -->
                            <div id="custom-button-container" class="d-flex gap-2 flex-wrap align-items-center"></div>
                            <!-- Tombol Tambah di paling kanan -->
                            <div class="ms-auto">
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#cari-akun-dosen">Tambah</a>
                            </div>
                        </div>
                    </div>
                    <!--begin::Card title-->
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table id="tabel-custom"
                            class="table table-bordered table-striped table-sm align-middle fs-6 gy-2 w-100">
                            <thead class="bg-gray-100 text-gray-800 border-bottom border-gray-300">
                                <tr class="fw-bold fs-5 text-uppercase text-center">
                                    <th class="w-10px py-2 px-2 text-center">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                data-kt-check-target="#tabel-custom .form-check-input" value="1" />
                                        </div>
                                    </th>
                                    <th class="w-10px py-2 px-2 text-center">No</th>
                                    <th class="py-4 px-3 min-w-125px">NIP</th>
                                    <th class="py-4 px-3 min-w-125px">Nama</th>
                                    <th class="py-4 px-3 min-w-125px">No HP/WA</th>
                                    <th class="py-4 px-3 min-w-125px">Jenis Kelamin</th>
                                    <th class="py-4 px-3 text-center min-w-100px">Aksi</th>
                                </tr>
                            </thead>
                            <!--end::Table head-->

                            <!--begin::Table body-->
                            <tbody class="text-gray-600 fw-bold">
                                <!--begin::Table row-->
                                @foreach ($dosen as $row)
                                    <tr>
                                        <!--begin::Checkbox-->
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox" />
                                                </div>
                                            </div>
                                        </td>
                                        <!--end::Checkbox-->
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">{{ $row->nip }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <!-- Foto Profil -->
                                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    @php
                                                        $avatarPath = $row->user->img ?? 'foto_users/default.png';
                                                        $avatarUrl = file_exists(public_path($avatarPath))
                                                            ? asset($avatarPath)
                                                            : asset('foto_users/default.png');
                                                    @endphp
                                                    <img src="{{ $avatarUrl }}" alt="{{ $row->user->nama ?? 'Dosen' }}"
                                                        class="w-100" style="object-fit: cover;" />
                                                </div>
                                                <!-- Nama -->
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-800 fw-bold">{{ $row->user->nama ?? '-' }}</span>
                                                    <span>{{ $row->user->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">{{ $row->no_hp }}</td>
                                        <td class="text-center align-middle">{{ $row->jenis_kelamin }}</td>
                                        <!--begin::Action=-->
                                        <td class="text-center">
                                            <!-- Detail -->
                                            <a href="{{ route('dosen.biodata', $row->id) }}"
                                                class="btn btn-icon btn-sm btn-light-primary me-2" title="Detail">
                                                <i class="bi bi-eye-fill fs-5"></i>
                                            </a>
                                            <!-- Ubah -->
                                            <a href="{{ route('dosen.edit', $row->id) }}"
                                                class="btn btn-icon btn-sm btn-light-success me-2" title="Ubah">
                                                <i class="bi bi-pencil-fill fs-5"></i>
                                            </a>
                                            <!-- Hapus -->
                                            <form action="{{ route('dosen.destroy', $row->id) }}" method="POST"
                                                style="display:inline;" id="form-delete-{{ $row->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-icon btn-sm btn-light-danger"
                                                    title="Hapus"
                                                    onclick="if(confirm('Yakin hapus data ini?')) document.getElementById('form-delete-{{ $row->id }}').submit();">
                                                    <i class="bi bi-trash-fill fs-5"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <!--end::Action=-->
                                    </tr>
                                @endforeach
                            </tbody>
                            <!--end::Table body-->
                        </table>

                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->

    <!--begin::Modal - Tambah Dosen-->
    <div class="modal fade" id="cari-akun-dosen" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Tambah Dosen</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.314" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.314)" fill="black" />
                                <rect x="7.414" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.414 6)" fill="black" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <form id="form-cari-akun-dosen" class="form" action="{{ route('dosen.store') }}" method="POST">
                        @csrf

                        <!-- Pilih User -->
                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="required fs-6 fw-bold form-label mb-2">Cari Akun Pengguna</label>
                            <select name="id_users" class="form-select form-select-solid" data-control="select2"
                                data-placeholder="Pilih Nama Dosen" required>
                                <option></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->nama }} - {{ $user->email }}
                                    </option>
                                @endforeach
                            </select>
                            @if (count($users) == 0)
                                <div class="text-muted fs-7 mt-2">
                                    Tidak ada user dengan role Dosen yang tersedia.
                                    <a href="{{ route('master-pengguna.index') }}" target="_blank">Tambah user baru</a>
                                </div>
                            @endif
                        </div>

                        <!-- NIP -->
                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="required fs-6 fw-bold form-label mb-2">NIP</label>
                            <input type="text" class="form-control form-control-solid"
                                placeholder="Nomor Induk Pegawai" name="nip" id="nip" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                            <div class="text-muted fs-7 mt-2">Hanya angka</div>
                        </div>

                        <div class="text-center pt-15 d-flex justify-content-between">
                            <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Lanjutkan</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Tambah Dosen-->
@endsection
