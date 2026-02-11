@extends('master.app')

@section('content')
<!-- Toolbar -->
<div class="toolbar" id="kt_toolbar">
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Tambah Data KRS</h1>
        </div>
        <div class="d-flex align-items-center py-1">
            <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                <li class="breadcrumb-item text-muted">
                    <a href="/dashboard" class="text-muted text-hover-primary">Dashboard</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-200 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('krs.index') }}" class="text-muted text-hover-primary">Data KRS</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-200 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-dark">Tambah</li>
            </ul>
        </div>
    </div>
</div>

<div id="kt_content_container" class="container-fluid">
    <!--begin::Tabs-->
    <div class="card mb-7 shadow-sm border-0 rounded-3">
        <div class="card-header border-bottom-0 pt-6 pb-0">
            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder" id="tabMenu" role="tablist">
                <li class="nav-item">
                    <a class="nav-link text-active-primary active" id="rombel-jadwal-tab" data-bs-toggle="tab" href="#rombel-jadwal" role="tab" aria-controls="rombel-jadwal" aria-selected="true">
                        Jadwal
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary" id="form-krs-tab" data-bs-toggle="tab" href="#form-krs" role="tab" aria-controls="form-krs" aria-selected="false">
                        Formulir KRS
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body pt-9">
            <div class="tab-content" id="tabContent">
                <!--begin::Tab Rombel dan Jadwal-->
                <div class="tab-pane fade show active" id="rombel-jadwal" role="tabpanel" aria-labelledby="rombel-jadwal-tab">
                    <!-- Detail Rombel -->
                    <div class="card shadow-sm border-0 rounded-3 mb-7">
                        <div class="card-header bg-light">
                            <h3 class="card-title fw-bolder text-primary mb-0">Detail Rombel</h3>
                        </div>
                        <div class="card-body p-9">
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-bold text-muted">Nama Rombel</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-gray-800">{{ $rombel->nama_rombel }}</span>
                                </div>
                            </div>
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-bold text-muted">Tahun Akademik</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-gray-800">
                                        {{ $rombel->tahunMasuk->tahun_awal ?? '-' }}{{ isset($rombel->tahunMasuk->semester) ? ' - ' . $rombel->tahunMasuk->semester : '' }}
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-bold text-muted">Program Studi</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-gray-800">{{ $rombel->prodi->nama_prodi ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-bold text-muted">Dosen Pembimbing Akademik</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-gray-800">{{ $rombel->dosen->user->nama ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Detail Rombel -->

                    <!-- Jadwal Kuliah -->
                    <div class="card shadow-sm border-0 rounded-3 mb-7">
                        <div class="card-header bg-light">
                            <h3 class="card-title fw-bolder text-info mb-0">Jadwal Kuliah</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center py-4 px-2 min-w-10px">No</th>
                                            <th class="py-4 px-3 min-w-175px">Mata Kuliah</th>
                                            <th class="py-4 px-3 min-w-175px">Dosen</th>
                                            <th class="py-4 px-3 min-w-60px">Hari</th>
                                            <th class="py-4 px-3 min-w-100px">Waktu</th>
                                            <th class="py-4 px-3 min-w-75px">Ruangan</th>
                                            <th class="py-4 px-3 min-w-150px">Rombel</th>
                                            <th class="py-4 px-3 min-w-100px">Tahun Akademik</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-700 fw-semibold">
                                        @forelse ($jadwal as $j)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $j->matkul->nama_mk ?? '-' }}</td>
                                                <td>{{ $j->dosen->user->nama ?? '-' }}</td>
                                                <td>{{ $j->hari ?? '-' }}</td>
                                                <td>
                                                    @if($j->jam_mulai && $j->jam_selesai)
                                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $j->jam_mulai)->format('H:i') }} - 
                                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $j->jam_selesai)->format('H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $j->ruangan->nama_ruang ?? '-' }}</td>
                                                <td>{{ $j->rombel->nama_rombel ?? '-' }}</td>
                                                <td>{{ $j->rombel->tahunMasuk->tahun_awal ?? '-' }} </td> 
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Tidak ada data jadwal.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Jadwal Kuliah -->
                </div>
                <!--end::Tab Rombel dan Jadwal-->

                <!--begin::Tab Formulir KRS-->
                <div class="tab-pane fade" id="form-krs" role="tabpanel" aria-labelledby="form-krs-tab">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-light">
                            <h3 class="card-title fw-bolder text-success mb-0">Formulir KRS</h3>
                        </div>
                        <form method="POST" action="{{ route('krs.store') }}" id="tambah_krs">
                            @csrf
                            <div class="card-body p-9">
                                @if ($errors->any())
                                    <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6 mb-8">
                                        <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none">
                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"/>
                                                <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="black"/>
                                                <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="black"/>
                                            </svg>
                                        </span>
                                        <div class="d-flex flex-stack flex-grow-1">
                                            <div class="fw-bold">
                                                <h4 class="text-gray-900 fw-bolder">Mohon perhatikan!</h4>
                                                <div class="fs-6 text-gray-700">
                                                    @foreach ($errors->all() as $error)
                                                        <div>{{ $error }}</div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <input type="hidden" name="id_rombel" value="{{ $rombel->id }}">

                                <div class="row mb-7">
                                    <label class="col-lg-4 fw-bold text-muted required">Nama Mahasiswa</label>
                                    <div class="col-lg-8">
                                        <select name="id_mahasiswa" class="form-select form-select-solid" data-control="select2" data-hide-search="false" data-placeholder="Pilih Mahasiswa" required>
                                            <option></option>
                                            @foreach ($mahasiswa as $mhs)
                                                <option value="{{ $mhs->id }}">{{ $mhs->user->nama }} - {{ $mhs->nim ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-7">
                                    <label class="col-lg-4 fw-bold text-muted required">Semester</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="semester" class="form-control form-control-lg form-control-solid" maxlength="2" placeholder="Semester (contoh: 1, 2, dst)" required>
                                    </div>
                                </div>

                                <!-- Tambahan Pilihan Jadwal (WAJIB) -->
                                <div class="row mb-7">
                                    <label class="col-lg-4 fw-bold text-muted required">Pilih Jadwal</label>
                                    <div class="col-lg-8">
                                        <select name="id_jadwal" class="form-select form-select-solid" data-control="select2" data-hide-search="false" data-placeholder="Pilih Jadwal" required>
                                            <option></option>
                                            @foreach ($jadwal as $j)
                                                <option value="{{ $j->id }}">
                                                    {{ $j->matkul->nama_mk ?? '-' }} | {{ $j->hari ?? '-' }} | 
                                                    {{ $j->jam_mulai ? \Carbon\Carbon::createFromFormat('H:i:s', $j->jam_mulai)->format('H:i') : '-' }} - 
                                                    {{ $j->jam_selesai ? \Carbon\Carbon::createFromFormat('H:i:s', $j->jam_selesai)->format('H:i') : '-' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- End Pilihan Jadwal -->

                                <div class="row mb-7">
                                    <label class="col-lg-4 fw-bold text-muted required">Status</label>
                                    <div class="col-lg-8">
                                        <select name="status" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Status" required>
                                            <option></option>
                                            <option value="draft">Draft</option>
                                            <option value="disetujui">Disetujui</option>
                                            <option value="ditolak">Ditolak</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-10">
                                    <label class="col-lg-4 fw-bold text-muted required">Status Kunci</label>
                                    <div class="col-lg-8">
                                        <select name="status_kunci" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Status Kunci" required>
                                            <option></option>
                                            <option value="0">Tidak Terkunci</option>
                                            <option value="1">Terkunci</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex flex-column flex-sm-row justify-content-between py-6 px-6 px-lg-9">
                                <a href="{{ route('krs.index') }}" class="btn btn-light mb-3 mb-sm-0 w-100 w-sm-auto">Batal</a>
                                <div class="d-flex">
                                    <button type="reset" class="btn btn-light-danger me-2">Reset</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--end::Tab Formulir KRS-->
            </div>
        </div>
    </div>
    <!--end::Tabs-->
</div>
@endsection