@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Data Role</h1>
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
                    <li class="breadcrumb-item text-dark">Data Role</li>
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
    <!--begin::Tampil Role-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
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
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah-role">Tambah</a>
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
                        <table id="tabel-custom" class="table table-bordered table-striped table-sm align-middle fs-6 gy-2 w-100">
                            <thead class="bg-gray-100 text-gray-800 border-bottom border-gray-300">
                            <tr class="fw-bold fs-5 text-uppercase">
                                <th class="text-center py-4 px-2 min-w-50px">No</th>
                                <th class="py-4 px-3 min-w-150px">Nama Role</th>
                                <th class="py-4 px-3 min-w-250px">Deskripsi</th>
                                <th class="text-center py-4 px-2 min-w-100px">Aksi</th>
                            </tr>
                            </thead>

                            <tbody class="fw-bold text-gray-600">
                            @foreach ($role as $index => $row)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $row->nama_role }}</td>
                                    <td>{{ $row->deskripsi_role }}</td>
                                    <td class="text-center">
                                        <!-- Edit -->
                                        <button type="button" class="btn btn-icon btn-sm btn-light-success me-2 btn-edit-role"
                                                data-id="{{ $row->id }}" data-url="{{ route('master-role.edit', $row->id) }}">
                                            <i class="bi bi-pencil-fill fs-5"></i>
                                        </button>

                                        <!-- Hapus -->
                                        <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-hapus" title="Hapus" data-id="{{ $row->id }}">
                                            <i class="bi bi-trash-fill fs-5"></i>
                                        </button>

                                        <!-- Hidden Form -->
                                        <form id="form-hapus-{{ $row->id }}" action="{{ route('role.destroy', $row->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
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
    <!--end::Tampil Role-->

    <!--begin::Modal - Tambah Role-->
    <div class="modal fade" id="tambah-role" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <h2>Tambah Role</h2>
                    <!-- Tombol tutup -->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <!-- SVG “X” bawaan Metronic -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.314" width="16" height="2" rx="1" transform="rotate(-45 6 17.314)" fill="black"/>
                                <rect x="7.414" y="6" width="16" height="2" rx="1" transform="rotate(45 7.414 6)" fill="black"/>
                            </svg>
                        </span>
                    </div>
                </div>
                <!--end::Modal header-->

                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <!--begin::Form-->
                    <form id="form_tambah_akses" class="form" action="/master-role" method="POST">
                        @csrf
                        <!-- Nama Role -->
                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="required fs-6 fw-bold form-label mb-2">Nama Role</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Masukkan nama role" name="nama_role" id="nama_role" required oninvalid="this.setCustomValidity('Nama role wajib diisi!')" oninput="this.setCustomValidity('')"/>
                        </div>

                        <!-- Deskripsi Role -->
                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="required fs-6 fw-bold form-label mb-2">Deskripsi Role</label>
                            <textarea class="form-control form-control-solid" name="deskripsi_role" id="deskripsi_role" rows="3" placeholder="Contoh: Hak akses untuk mengelola data user" required oninvalid="this.setCustomValidity('Deskripsi role wajib diisi!')"  oninput="this.setCustomValidity('')"></textarea>
                        </div>

                        <!--begin::Actions-->
                        <div class="text-center pt-15 d-flex justify-content-between">
                            <button type="button" id="tambah-role-batal" class="btn btn-light-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" id="tambah-role-simpan" class="btn btn-primary">Simpan</button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - Tambah Role-->

    <!--begin::Modal - Ubah Role-->
    <div class="modal fade" id="ubah-role" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <h2>Ubah Role</h2>
                    <!-- Tombol tutup -->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <!-- SVG “X” bawaan Metronic -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.314" width="16" height="2" rx="1" transform="rotate(-45 6 17.314)" fill="black"/>
                                <rect x="7.414" y="6" width="16" height="2" rx="1" transform="rotate(45 7.414 6)" fill="black"/>
                            </svg>
                        </span>
                    </div>
                </div>
                <!--end::Modal header-->

                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <!--begin::Form-->
                    <form id="form-edit-role" class="form" action="/master-role" method="POST">
                        @csrf
                        @method('PUT')
                        <!-- Nama Role -->
                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="required fs-6 fw-bold form-label mb-2">Nama Role</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Masukkan nama role" name="nama_role" id="nama_role" required oninvalid="this.setCustomValidity('Nama role wajib diisi!')" oninput="this.setCustomValidity('')"/>
                        </div>

                        <!-- Deskripsi Role -->
                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="required fs-6 fw-bold form-label mb-2">Deskripsi Role</label>
                            <textarea class="form-control form-control-solid" name="deskripsi_role" id="deskripsi_role" rows="3" placeholder="Contoh: Hak akses untuk mengelola data user" required oninvalid="this.setCustomValidity('Deskripsi role wajib diisi!')"  oninput="this.setCustomValidity('')"></textarea>
                        </div>

                        <!--begin::Actions-->
                        <div class="text-center pt-15 d-flex justify-content-between">
                            <button type="button" id="tambah-role-batal" class="btn btn-light-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" id="tambah-role-simpan" class="btn btn-primary">Simpan</button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - Ubah Role-->

@endsection
