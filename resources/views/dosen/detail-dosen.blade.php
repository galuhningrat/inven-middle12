@extends('master.app')

@section('content')
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Detail Dosen</h1>
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
                        <a href="{{ route('dosen.index') }}" class="text-muted text-hover-primary">Data Dosen</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
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
                                    $avatarPath = $dosen->user->img ?? 'foto_users/default.png';
                                    $avatarUrl = file_exists(public_path($avatarPath))
                                        ? asset($avatarPath)
                                        : asset('foto_users/default.png');
                                @endphp
                                <img src="{{ $avatarUrl }}" alt="{{ $dosen->user->nama ?? 'Dosen' }}"
                                    class="img-fluid shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-gray-900 fs-2 fw-bolder me-1">{{ $dosen->user->nama }}</span>
                                    <span
                                        class="badge badge-success fw-bolder ms-2 fs-8 py-1 px-3">{{ $dosen->status }}</span>
                                </div>
                                <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                                    <div class="d-flex align-items-center text-gray-600 me-5 mb-2">
                                        <i class="bi bi-credit-card-2-front me-2"></i>NIP: {{ $dosen->nip }}
                                    </div>
                                    <div class="d-flex align-items-center text-gray-600 me-5 mb-2">
                                        <i class="bi bi-person-badge me-2"></i>NIDN: {{ $dosen->nidn ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center text-gray-600 me-5 mb-2">
                                        <i class="bi bi-whatsapp me-2"></i>{{ $dosen->no_hp ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center text-gray-600 mb-2">
                                        <i class="bi bi-envelope me-2"></i>{{ $dosen->user->email }}
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('dosen.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                        </div>
                        <!--begin::Stats-->
                        <div class="d-flex flex-wrap flex-stack">
                            <div class="d-flex flex-column flex-grow-1 pe-8">
                                <div class="d-flex flex-wrap">
                                    <!--begin::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bolder">{{ $dosen->agama }}</div>
                                        </div>
                                        <div class="fw-bold fs-6 text-gray-400">Agama</div>
                                    </div>
                                    <!--end::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bolder">{{ $dosen->jenis_kelamin }}</div>
                                        </div>
                                        <div class="fw-bold fs-6 text-gray-400">Jenis Kelamin</div>
                                    </div>
                                    <!--end::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bolder">{{ $dosen->kewarganegaraan }}</div>
                                        </div>
                                        <div class="fw-bold fs-6 text-gray-400">Kewarganegaraan</div>
                                    </div>
                                    <!--end::Stat-->
                                </div>
                            </div>
                        </div>
                        <!--end::Stats-->
                    </div>
                </div>

                <!--begin::Navs-->
                <div class="d-flex overflow-auto h-55px">
                    <ul
                        class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder flex-nowrap">
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6 {{ request()->routeIs('dosen.biodata') ? 'active' : '' }}"
                                href="{{ route('dosen.biodata', $dosen->id) }}">
                                Biodata
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6 {{ request()->routeIs('dosen.pendidikan') ? 'active' : '' }}"
                                href="{{ route('dosen.pendidikan', $dosen->id) }}">
                                Riwayat Pendidikan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6 {{ request()->routeIs('dosen.dokumen') ? 'active' : '' }}"
                                href="{{ route('dosen.dokumen', $dosen->id) }}">
                                Dokumen
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6 {{ request()->routeIs('dosen.jadwal') ? 'active' : '' }}"
                                href="{{ route('dosen.jadwal', $dosen->id) }}"> Jadwal Mengajar </a>
                        </li>
                    </ul>
                    <!--end::Navs-->
                </div>
            </div>

            <!--begin::details View-->
            <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
                @if (request()->routeIs('dosen.biodata'))
                    @yield('biodata')
                @elseif (request()->routeIs('dosen.pendidikan'))
                    @yield('pendidikan')
                @elseif (request()->routeIs('dosen.dokumen'))
                    @yield('dokumen')
                @elseif (request()->routeIs('dosen.jadwal'))
                    @yield('jadwal')
                @else
                    <div class="card-body">
                        <p class="text-center text-muted">Halaman tidak ditemukan</p>
                    </div>
                @endif
            </div>
            <!--end::details View-->
        </div>
    @endsection
