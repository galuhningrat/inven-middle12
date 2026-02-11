@extends('dosen.detail-dosen')

@section('biodata')
    <!--begin::Profile Header with Avatar-->
    <div class="card mb-5">
        <div class="card-body p-9">
            <div class="d-flex flex-wrap flex-sm-nowrap">
                <!--begin::Avatar-->
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                        @php
                            $avatarPath = $dosen->user->img ?? 'foto_users/default.png';
                            $avatarUrl = file_exists(public_path($avatarPath))
                                ? asset($avatarPath)
                                : asset('foto_users/default.png');
                        @endphp
                        <img src="{{ $avatarUrl }}" alt="{{ $dosen->user->nama ?? 'Dosen' }}"
                            style="object-fit: cover; border-radius: 50%;" />
                    </div>
                </div>
                <!--end::Avatar-->

                <!--begin::Info-->
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-900 fs-2 fw-bold me-1">{{ $dosen->user->nama ?? '-' }}</span>
                            </div>
                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                <span class="d-flex align-items-center text-gray-400 me-5 mb-2">
                                    <i class="bi bi-person-badge fs-4 me-1"></i>
                                    NIP: {{ $dosen->nip }}
                                </span>
                                <span class="d-flex align-items-center text-gray-400 me-5 mb-2">
                                    <i class="bi bi-envelope fs-4 me-1"></i>
                                    {{ $dosen->user->email ?? '-' }}
                                </span>
                                <span class="d-flex align-items-center text-gray-400 me-5 mb-2">
                                    <i class="bi bi-telephone fs-4 me-1"></i>
                                    {{ $dosen->no_hp ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex my-4">
                            <a href="{{ route('dosen.edit', $dosen->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil-fill"></i> Edit Profil
                            </a>
                        </div>
                    </div>
                </div>
                <!--end::Info-->
            </div>
        </div>
    </div>
    <!--end::Profile Header-->

    <!--begin::Card header-->
    <div class="card-header cursor-pointer">
        <!--begin::Card title-->
        <div class="card-title m-0">
            <h3 class="fw-bolder m-0">Biodata</h3>
        </div>
        <!--end::Card title-->
    </div>
    <!--begin::Card header-->

    <!--begin::Card body-->
    <div class="card-body p-9">
        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">Nomor Kartu Keluarga</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $dosen->no_kk ?? '-' }}</span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">NIK</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $dosen->nik ?? '-' }}</span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">NIP</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $dosen->nip ?? '-' }}</span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">NIDN</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $dosen->nidn ?? '-' }}</span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">Tempat, Tanggal Lahir</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">
                    @if (empty($dosen->tempat_lahir) && empty($dosen->tanggal_lahir))
                        -
                    @else
                        {{ $dosen->tempat_lahir ?? '' }}{{ $dosen->tempat_lahir && $dosen->tanggal_lahir ? ', ' : '' }}{{ $dosen->tanggal_lahir ? \Carbon\Carbon::parse($dosen->tanggal_lahir)->format('d-m-Y') : '' }}
                    @endif
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">Jenis Kelamin</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $dosen->jenis_kelamin ?? '-' }}</span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">Alamat Lengkap</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">
                    @if (
                        $dosen->dusun ||
                            $dosen->rt ||
                            $dosen->rw ||
                            $dosen->ds_kel ||
                            $dosen->kec ||
                            $dosen->kab ||
                            $dosen->prov ||
                            $dosen->kode_pos)
                        {{ $dosen->dusun }} RT/RW {{ $dosen->rt }}/{{ $dosen->rw }}, Desa/Kelurahan
                        {{ $dosen->ds_kel }}, Kec. {{ $dosen->kec }}, Kab. {{ $dosen->kab }}, Prov.
                        {{ $dosen->prov ?? '-' }} - {{ $dosen->kode_pos }}
                    @else
                        -
                    @endif
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">Agama</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $dosen->agama ?? '-' }}</span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">Status Perkawinan</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $dosen->marital_status ?? '-' }}</span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">Kewarganegaraan</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $dosen->kewarganegaraan ?? '-' }}</span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">Golongan Darah</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $dosen->gol_darah ?? '-' }}</span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-bold text-muted">No HP</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800">{{ $dosen->no_hp ?? '-' }}</span>
            </div>
        </div>
    </div>
    <!--end::Card body-->
@endsection
