@extends('master.app')

@section('content')
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Tambah Mahasiswa ke Rombel</h1>
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
                        <a href="{{ route('rombel.index') }}" class="text-muted text-hover-primary">Data Rombel</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">Tambah</li>
                </ul>
            </div>
        </div>
    </div>

    <!--begin::Content-->
    <div id="kt_content_container" class="container-fluid">
        <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-gray-900 fs-2 fw-bolder me-1">{{ $rombel->nama_rombel }}</span>
                                    <span class="fs-6 text-gray-400 fw-bold ms-1">({{ $rombel->mahasiswa_count ?? '-' }}
                                        Mahasiswa)</span>
                                </div>
                            </div>
                            <a href="{{ route('rombel.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">Daftar Mahasiswa Prodi {{ $rombel->prodi->nama_prodi }} Tahun
                        {{ $rombel->tahunMasuk->tahun_awal }}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Form-->
                <form action="{{ route('rombel.storeMahasiswa', $rombel->id) }}" method="POST" id="formTambahMahasiswa">
                    @csrf
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table id="tabel-custom-2"
                            class="table table-bordered table-striped table-sm align-middle fs-6 gy-2 w-100">
                            <thead class="bg-gray-100 text-gray-800 border-bottom border-gray-300">
                                <tr class="fw-bold fs-5 text-uppercase text-center">
                                    <th class="w-10px py-2 px-2 text-center">
                                        <!-- Checkbox master -->
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                data-kt-check-target="#tabel-custom-2 tbody .form-check-input" />
                                        </div>
                                    </th>
                                    <th class="w-10px py-2 px-2 text-center min-w-50px">No</th>
                                    <th class="py-4 px-3 min-w-80px">NIM</th>
                                    <th class="py-4 px-3 min-w-250px">Nama</th>
                                    <th class="py-4 px-3 min-w-150px">Status Mhs</th>
                                    <th class="py-4 px-3 min-w-100px">No HP/WA</th>
                                    <th class="py-4 px-3 min-w-75px">JK</th>
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
                                                    <input class="form-check-input" type="checkbox" name="mahasiswa_ids[]"
                                                        value="{{ $row->id }}" />
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
                                    </tr>
                                @endforeach
                            </tbody>
                            <!--end::Table body-->
                        </table>
                    </div>
                    <!--end::Table-->

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-between pt-10">
                        <a href="{{ route('rombel.index') }}" class="btn btn-light ms-2">Kembali</a>
                        <button type="submit" class="btn btn-primary">Tambahkan ke Rombel
                        </button>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card body-->
        </div>
    </div>
@endsection
