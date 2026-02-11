@extends('master.app')

@section('toolbar')
  <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Data Mata Kuliah</h1>
                <span class="h-20px border-gray-200 border-start mx-4"></span>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-fluid">
        @include('master.notification')
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="d-flex flex-wrap justify-content-between align-items-center w-100">
                    <div id="custom-search-container" class="mb-2 mb-md-0"></div>
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <div id="custom-button-container" class="d-flex gap-2 flex-wrap align-items-center"></div>
                        <div class="ms-auto">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahMatkul">Tambah</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table id="tabel-custom" class="table table-bordered table-striped table-sm align-middle fs-6 gy-2 w-100">
                        <thead class="bg-gray-100 text-gray-800 border-bottom border-gray-300">
                            <tr class="fw-bold fs-6 text-uppercase">
                                <th class="text-center py-4 px-2 min-w-30px">No</th>
                                <th class="py-4 px-3 min-w-40px">Kode MK</th>
                                <th class="py-4 px-3 min-w-150px">Nama Mata Kuliah</th>
                                <th class="py-4 px-3 min-w-50px">Bobot</th>
                                <th class="py-4 px-3 min-w-70px">Jenis</th>
                                <th class="py-4 px-3 min-w-150px">Prodi</th>
                                <th class="py-4 px-3 min-w-200px">Nama Dosen</th>
                                <th class="text-center py-4 px-2 min-w-130px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 fw-semibold">
                            @foreach ($matakuliah as $m)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $m->kode_mk }}</td>
                                <td>{{ $m->nama_mk }}</td>
                                <td class="text-center">{{ $m->bobot }}</td>
                                <td class="text-center text-capitalize">{{ $m->jenis }}</td>
                                <td>{{ $m->prodi->nama_prodi ?? '-' }}</td>
                                <td>{{ $m->dosen->user->nama ?? '-' }}</td>
                                <td class="text-center">
                                    <!-- DETAIL -->
                                    <button type="button" class="btn btn-icon btn-sm btn-light-primary me-2" title="Detail"
                                        data-bs-toggle="modal" data-bs-target="#modalDetailMatkul{{ $m->id }}">
                                        <i class="bi bi-eye-fill fs-5"></i>
                                    </button>
                                    <!-- EDIT -->
                                    <button type="button" class="btn btn-icon btn-sm btn-light-success me-2" title="Ubah"
                                        data-bs-toggle="modal" data-bs-target="#modalEditMatkul{{ $m->id }}">
                                        <i class="bi bi-pencil-fill fs-5"></i>
                                    </button>
                                    <!-- DELETE -->
                                    <button type="button" class="btn btn-icon btn-sm btn-light-danger" title="Hapus"
                                        data-bs-toggle="modal" data-bs-target="#modalDeleteMatkul{{ $m->id }}">
                                        <i class="bi bi-trash-fill fs-5"></i>
                                    </button>
                                </td>
                            </tr>

                            @include('matakuliah.detail-matkul', ['m' => $m])
                            @include('matakuliah.edit-matkul', ['m' => $m, 'prodi' => $prodi, 'dosen' => $dosen])
                            @include('matakuliah.delete-matkul', ['m' => $m])
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('matakuliah.create-matkul', ['prodi' => $prodi, 'dosen' => $dosen])
@endsection