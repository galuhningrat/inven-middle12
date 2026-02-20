@extends('master.app')

@section('toolbar')
  <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Data Jadwal Kuliah</h1>
                <span class="h-20px border-gray-200 border-start mx-4"></span>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-fluid">
        @include('master.notification')

    <!--begin::Simple Filter-->
<form method="GET" action="{{ route('jadwal.index') }}" class="d-flex align-items-center gap-2 mb-3" id="formFilterJadwal" style="flex-wrap:wrap;">
    <select name="hari" class="form-select form-select-sm" style="width:120px;" aria-label="Filter Hari">
        <option value="">pilih hari</option>
        <option value="Senin" {{ request('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
        <option value="Selasa" {{ request('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
        <option value="Rabu" {{ request('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
        <option value="Kamis" {{ request('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
        <option value="Jumat" {{ request('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
        <option value="Sabtu" {{ request('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
    </select>
    <select name="rombel" class="form-select form-select-sm" style="width:130px;" aria-label="Filter Rombel">
        <option value="">pilih rombel</option>
        @foreach ($rombel as $r)
            <option value="{{ $r->id }}" {{ request('rombel') == $r->id ? 'selected' : '' }}>{{ $r->nama_rombel }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-sm btn-primary px-3">Filter</button>
    <a href="{{ route('jadwal.index') }}" class="btn btn-sm btn-light px-3">Reset</a>
</form>
<!--end::Simple Filter-->

        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="d-flex flex-wrap justify-content-between align-items-center w-100">
                    <div id="custom-search-container" class="mb-2 mb-md-0"></div>
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <div id="custom-button-container" class="d-flex gap-2 flex-wrap align-items-center"></div>
                        <div class="ms-auto">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">Tambah</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table id="tabel-custom" class="table table-bordered table-striped table-sm align-middle fs-6 gy-2 w-100">
                        <thead class="bg-gray-100 text-gray-800 border-bottom border-gray-300">
                            <tr class="fw-bold fs-6 text-uppercase">
                                <th class="text-center py-4 px-2 min-w-10px">No</th>
                                <th class="py-4 px-3 min-w-175px">Mata Kuliah</th>
                                <th class="py-4 px-3 min-w-1750px">Dosen</th>
                                <th class="py-4 px-3 min-w-60px">Hari</th>
                                <th class="py-4 px-3 min-w-100px">Waktu</th>
                                <th class="py-4 px-3 min-w-75px">Ruangan</th>
                                <th class="py-4 px-3 min-w-150px">Rombel</th>
                                <th class="py-4 px-3 min-w-105px">Tahun Akademik</th>
                                <th class="text-center py-4 px-2 min-w-150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 fw-semibold">
                            @foreach ($jadwal as $j)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $j->matkul->nama_mk ?? '-' }}</td>
                                <td>{{ $j->dosen->user->nama ?? '-' }}</td>
                                <td>{{ $j->hari }}</td>
                                <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $j->jam_selesai)->format('H:i') }}</td>
                                <td>{{ $j->ruangan->nama_ruang ?? '-' }}</td>
                                <td>{{ $j->rombel->nama_rombel ?? '-' }}</td>
                                <td>
                                            {{ $r->tahunMasuk->tahun_awal ?? '-' }} - {{ $r->tahunMasuk->semester ?? '-' }}
                                </td>
                                <td class="text-center">
                                    <!-- DETAIL -->
                                    <button type="button" class="btn btn-icon btn-sm btn-light-primary me-2" title="Detail"
                                        data-bs-toggle="modal" data-bs-target="#modalDetailJadwal{{ $j->id }}">
                                        <i class="bi bi-eye-fill fs-5"></i>
                                    </button>
                                    <!-- EDIT -->
                                    <button type="button" class="btn btn-icon btn-sm btn-light-success me-2" title="Ubah"
                                        data-bs-toggle="modal" data-bs-target="#modalEditJadwal{{ $j->id }}">
                                        <i class="bi bi-pencil-fill fs-5"></i>
                                    </button>
                                    <!-- DELETE -->
                                    <button type="button" class="btn btn-icon btn-sm btn-light-danger" title="Hapus"
                                        data-bs-toggle="modal" data-bs-target="#modalDeleteJadwal{{ $j->id }}">
                                        <i class="bi bi-trash-fill fs-5"></i>
                                    </button>
                                </td>
                            </tr>

                            @include('jadwal.detail-jadwal', ['j' => $j])
                            @include('jadwal.edit-jadwal', ['j' => $j, 'matkul' => $matkul, 'dosen' => $dosen, 'rombel' => $rombel, 'tahun_akademik' => $tahun_akademik, 'ruangan' => $ruangan])
                            @include('jadwal.delete-jadwal', ['j' => $j])
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('jadwal.create-jadwal', ['matkul' => $matkul, 'prodi' => $prodi, 'dosen' => $dosen, 'rombel' => $rombel, 'tahun_akademik' => $tahun_akademik, 'ruangan' => $ruangan])

@endsection
