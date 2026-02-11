@extends('master.app')

@section('content')
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Detail Karyawan</h1>
            </div>
            <div class="d-flex align-items-center py-1">
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="/dashboard" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-200 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('karyawan.index') }}" class="text-muted text-hover-primary">Data Karyawan</a>
                    </li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-200 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-dark">Detail</li>
                </ul>
            </div>
        </div>
    </div>

    <!--begin::Content-->
    <div id="kt_content_container" class="container-fluid">
        <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                    <div class="me-7 mb-4">
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <div style="border-radius: 12px; overflow: hidden; max-width: 150px;">
                                @php
                                    $avatarPath = $karyawan->user->img ?? 'foto_users/default.png';
                                    $avatarUrl = file_exists(public_path($avatarPath))
                                        ? asset($avatarPath)
                                        : asset('foto_users/default.png');
                                @endphp
                                <img src="{{ $avatarUrl }}" alt="{{ $karyawan->user->nama ?? 'Karyawan' }}"
                                    class="img-fluid shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-gray-900 fs-2 fw-bolder me-1">{{ $karyawan->user->nama }}</span>
                                    <span
                                        class="badge badge-light-success fw-bolder ms-2 fs-8 py-1 px-3">{{ $karyawan->status }}</span>
                                </div>
                                <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                                    <div class="d-flex align-items-center text-gray-600 me-5 mb-2">
                                        <i class="bi bi-credit-card-2-front me-2"></i>NIP: {{ $karyawan->nip }}
                                    </div>
                                    <div class="d-flex align-items-center text-gray-600 me-5 mb-2">
                                        <i class="bi bi-whatsapp me-2"></i>{{ $karyawan->hp ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center text-gray-600 mb-2">
                                        <i class="bi bi-envelope me-2"></i>{{ $karyawan->user->email }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('karyawan.edit', $karyawan->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </a>
                                <a href="{{ route('karyawan.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                            </div>
                        </div>
                        <!--begin::Stats-->
                        <div class="d-flex flex-wrap flex-stack">
                            <div class="d-flex flex-column flex-grow-1 pe-8">
                                <div class="d-flex flex-wrap">
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bolder">{{ $karyawan->agama }}</div>
                                        </div>
                                        <div class="fw-bold fs-6 text-gray-400">Agama</div>
                                    </div>
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bolder">{{ $karyawan->jenis_kelamin }}</div>
                                        </div>
                                        <div class="fw-bold fs-6 text-gray-400">Jenis Kelamin</div>
                                    </div>
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bolder">{{ $karyawan->pend_terakhir }}</div>
                                        </div>
                                        <div class="fw-bold fs-6 text-gray-400">Pendidikan Terakhir</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Stats-->
                    </div>
                </div>
            </div>
        </div>

        <!--begin::Details Card-->
        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <div class="card-header cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">Biodata</h3>
                </div>
            </div>
            <div class="card-body p-9">
                <div class="row mb-7">
                    <label class="col-lg-4 fw-bold text-muted">Nomor Kartu Keluarga</label>
                    <div class="col-lg-8">
                        <span class="fw-bolder fs-6 text-gray-800">{{ $karyawan->no_kk ?? '-' }}</span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-bold text-muted">NIK</label>
                    <div class="col-lg-8">
                        <span class="fw-bolder fs-6 text-gray-800">{{ $karyawan->nik ?? '-' }}</span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-bold text-muted">NPWP</label>
                    <div class="col-lg-8">
                        <span class="fw-bolder fs-6 text-gray-800">{{ $karyawan->npwp ?? '-' }}</span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-bold text-muted">Tempat, Tanggal Lahir</label>
                    <div class="col-lg-8">
                        <span class="fw-bolder fs-6 text-gray-800">
                            @if ($karyawan->tempat_lahir && $karyawan->tanggal_lahir)
                                {{ $karyawan->tempat_lahir }},
                                {{ \Carbon\Carbon::parse($karyawan->tanggal_lahir)->format('d-m-Y') }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-bold text-muted">Alamat Lengkap</label>
                    <div class="col-lg-8">
                        <span class="fw-bolder fs-6 text-gray-800">
                            @if ($karyawan->dusun || $karyawan->rt || $karyawan->rw)
                                {{ $karyawan->dusun }} RT/RW {{ $karyawan->rt }}/{{ $karyawan->rw }},
                                Desa/Kel {{ $karyawan->ds_kel }}, Kec. {{ $karyawan->kec }},
                                Kab. {{ $karyawan->kab }}, {{ $karyawan->prov }} - {{ $karyawan->kode_pos }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-bold text-muted">Status Perkawinan</label>
                    <div class="col-lg-8">
                        <span class="fw-bolder fs-6 text-gray-800">{{ $karyawan->marital_status ?? '-' }}</span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-bold text-muted">Golongan Darah</label>
                    <div class="col-lg-8">
                        <span class="fw-bolder fs-6 text-gray-800">{{ $karyawan->gol_darah ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Details Card-->
    </div>
@endsection
