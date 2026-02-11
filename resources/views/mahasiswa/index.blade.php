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
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Data Mahasiswa</h1>
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
                    <li class="breadcrumb-item text-dark">Data Mahasiswa</li>
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
    <!--begin::Tampil Mahasiswa-->
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
                                    data-bs-target="#cari-akun-mahasiswa">Tambah</a>
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
                                    <th class="w-10px py-2 px-2 text-center min-w-50px">No</th>
                                    <th class="py-4 px-3 min-w-80px">NIM</th>
                                    <th class="py-4 px-3 min-w-250px">Nama</th>
                                    <th class="py-4 px-3 min-w-150px">Prodi</th>
                                    <th class="py-4 px-3 min-w-100px">No HP/WA</th>
                                    <th class="py-4 px-3 min-w-75px">JK</th>
                                    <th class="py-4 px-3 text-center min-w-100px">Aksi</th>
                                </tr>
                            </thead>
                            <!--end::Table head-->

                            <!--begin::Table body-->
                            <tbody class="text-gray-600 fw-bold">
                                @foreach ($mahasiswa as $row)
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
                                        <td class="text-center align-middle">{{ $row->nim }}</td>
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
                                                    <img src="{{ $avatarUrl }}"
                                                        alt="{{ $row->user->nama ?? 'Mahasiswa' }}" class="w-100"
                                                        style="object-fit: cover;" />
                                                </div>
                                                <!-- Nama -->
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-800 fw-bold">{{ $row->user->nama }}</span>
                                                    <span>{{ $row->user->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">{{ $row->prodi->nama_prodi ?? '-' }}</td>
                                        <td class="text-center align-middle">{{ $row->hp }}</td>
                                        <td class="text-center align-middle">{{ $row->jenis_kelamin }}</td>
                                        <!--begin::Action=-->
                                        <td class="text-center">
                                            <!-- Detail -->
                                            <a href="{{ route('mahasiswa.biodata', $row->nim) }}"
                                                class="btn btn-icon btn-sm btn-light-primary me-2" title="Detail">
                                                <i class="bi bi-eye-fill fs-5"></i>
                                            </a>
                                            <!-- Ubah -->
                                            <a href="{{ route('mahasiswa.edit', $row->id) }}"
                                                class="btn btn-icon btn-sm btn-light-success me-2" title="Ubah">
                                                <i class="bi bi-pencil-fill fs-5"></i>
                                            </a>
                                            <!-- Hapus -->
                                            <form id="delete-mahasiswa-{{ $row->id }}"
                                                action="{{ route('mahasiswa.destroy', $row->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-icon btn-sm btn-light-danger"
                                                    title="Hapus"
                                                    onclick="if(confirm('Yakin ingin menghapus data ini?')) document.getElementById('delete-mahasiswa-{{ $row->id }}').submit();">
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

    <!--begin::Modal - Tambah Mahasiswa-->
    <div class="modal fade" id="cari-akun-mahasiswa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <h2>Tambah Mahasiswa</h2>
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
                <!--end::Modal header-->

                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <form id="form-tambah-mahasiswa" class="form" action="{{ route('mahasiswa.store') }}"
                        method="POST">
                        @csrf

                        <!-- STEP 1: Pilih User -->
                        <div class="mb-7">
                            <label class="required fs-6 fw-bold form-label mb-2">Cari Akun Pengguna</label>
                            <select name="id_users" id="select-user" class="form-select form-select-solid"
                                data-control="select2" data-hide-search="false" data-placeholder="Pilih Nama Mahasiswa"
                                required>
                                <option></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->nama }} - {{ $user->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- STEP 2: Pilih Prodi dan NIM (disembunyikan dulu) -->
                        <div id="step-2" style="display: none;">
                            <div class="mb-7">
                                <label class="required fs-6 fw-bold form-label mb-2">Program Studi</label>
                                <select name="id_prodi" class="form-select form-select-solid" required>
                                    <option value="">Pilih Prodi</option>
                                    @foreach ($prodis as $prodi)
                                        <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-7">
                                <label class="required fs-6 fw-bold form-label mb-2">NIM</label>
                                <input type="text" name="nim" id="nim"
                                    class="form-control form-control-solid" placeholder="Masukkan NIM" required />
                            </div>
                            <div class="mb-7">
                                <label for="id_tahun_akademik" class="form-label">Tahun Akademik</label>
                                @if ($tahunAkademikAktif)
                                    <input type="text" class="form-control form-control-solid"
                                        value="{{ $tahunAkademikAktif->tahun_awal }} - Semester {{ $tahunAkademikAktif->semester }}"
                                        readonly>
                                    <input type="hidden" name="tahun_masuk" value="{{ $tahunAkademikAktif->id }}">
                                @else
                                    <div class="alert alert-danger">Peringatan: Tidak ada Tahun Akademik yang Aktif!</div>
                                    <input type="text" class="form-control is-invalid" value="Data tidak ditemukan"
                                        readonly>
                                @endif
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex justify-content-between pt-10">
                            <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">Batal</button>
                            <div>
                                <button type="button" id="btn-lanjutkan" class="btn btn-primary">Lanjutkan</button>
                                <button type="submit" id="btn-simpan" class="btn btn-success"
                                    style="display: none;">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!--end::Modal body-->
            </div>
        </div>
    </div>
    <!--end::Modal - Tambah Mahasiswa-->
@endsection
