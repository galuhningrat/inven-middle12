@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Data KRS</h1>
                <span class="h-20px border-gray-200 border-start mx-4"></span>
            </div>
            <!--end::Page title-->
            <div class="d-flex align-items-center py-1">
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="/dashboard" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">Data KRS</li>
                </ul>
            </div>
        </div>
        <!--end::Container-->
    </div>
@endsection

@section('content')
    <!--begin::Tampil KRS-->
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
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pilih-rombel-krs">Tambah</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="tabel-custom" class="table table-bordered table-striped table-sm align-middle fs-6 gy-2 w-100">
                            <thead class="bg-gray-100 text-gray-800 border-bottom border-gray-300">
                                <tr class="fw-bold fs-5 text-uppercase text-center">
                                    <th class="w-10px py-2 px-2 text-center">
                                        
                                    </th>
                                    <th class="w-10px py-2 px-2 text-center">No</th>
                                    <th class="py-4 px-3 min-w-125px">Nama Mahasiswa</th>
                                    <th class="py-4 px-3 min-w-125px">Rombel</th>
                                    <th>Dosen Pembimbing Akademik</th>
                                    <th class="py-4 px-3 min-w-125px">Program Studi</th>
                                    <th class="py-4 px-3 min-w-100px">Tahun Akademik</th>
                                    <th class="py-4 px-3 min-w-60px">Semester</th>
                                    <th class="py-4 px-3 min-w-100px">Status</th>
                                    <th class="py-4 px-3 min-w-150px">Dibuat</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold">
                                @foreach ($krs as $row)
                                    <tr>
                                        <td class="text-center align-middle">
                                          
                                        </td>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="align-middle">
                                            {{ $row->mahasiswa->user->nama ?? '-' }}
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ $row->rombel->nama_rombel ?? '-' }}
                                        </td>
                                         <td class="align-middle">
                                            {{ $row->rombel->dosen->user->nama ?? '-' }}
                                        </td>
                                        <td class="align-middle">
                                            {{ $row->rombel->prodi->nama_prodi ?? '-' }}
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ $row->rombel->tahunMasuk->tahun_awal ?? '-' }}
                                            @if(isset($row->rombel->tahunMasuk->semester))
                                                - {{ $row->rombel->tahunMasuk->semester }}
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ $row->semester ?? '-' }}
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($row->status == 'draft')
                                                <span class="badge bg-secondary">Draft</span>
                                            @elseif($row->status == 'disetujui')
                                                <span class="badge bg-success">Disetujui</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ $row->created_at ? $row->created_at->format('d-m-Y H:i') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pilih Rombel -->
    <div class="modal fade" id="pilih-rombel-krs" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Pilih Rombel</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.314" width="16" height="2" rx="1" transform="rotate(-45 6 17.314)" fill="black"/>
                                <rect x="7.414" y="6" width="16" height="2" rx="1" transform="rotate(45 7.414 6)" fill="black"/>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <form id="form-pilih-rombel-krs" class="form" action="{{ route('krs.redirect') }}" method="POST">
                        @csrf
                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="required fs-6 fw-bold form-label mb-2">Pilih Rombel</label>
                            <select name="id_rombel" class="form-select form-select-solid" data-control="select2" data-hide-search="false" data-placeholder="Pilih Rombel" required>
                                <option></option>
                                @foreach ($rombels as $rombel)
                                    <option value="{{ $rombel->id }}">{{ $rombel->nama_rombel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-center pt-15 d-flex justify-content-between">
                            <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection