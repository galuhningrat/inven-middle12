@extends('master.app')

@section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Detail Mahasiswa</h1>
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
                        <a href="{{ route('mahasiswa.index') }}" class="text-muted text-hover-primary">Data Mahasiswa</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">Detail</li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_content_container" class="container-fluid">
        <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                    <div class="me-7 mb-4">
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <div style="border-radius: 12px; overflow: hidden; max-width: 150px;">
                                @php
                                    $avatarPath = $mahasiswa->user->img ?? 'foto_users/default.png';
                                    $avatarUrl = file_exists(public_path($avatarPath))
                                        ? asset($avatarPath)
                                        : asset('foto_users/default.png');
                                @endphp
                                <img src="{{ $avatarUrl }}" alt="{{ $mahasiswa->user->nama ?? 'Mahasiswa' }}"
                                    class="img-fluid shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-gray-900 fs-2 fw-bolder me-1">{{ $mahasiswa->user->nama }}</span>
                                    <span
                                        class="badge badge-success fw-bolder ms-2 fs-8 py-1 px-3">{{ $mahasiswa->status ?? 'Aktif' }}</span>
                                </div>
                                <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                                    <div class="d-flex align-items-center text-gray-600 me-5 mb-2">
                                        <i
                                            class="bi bi-journal-bookmark me-2"></i>{{ $mahasiswa->prodi->nama_prodi ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center text-gray-600 me-5 mb-2">
                                        <i class="bi bi-person-badge me-2"></i>{{ $mahasiswa->nim }}
                                    </div>
                                    <div class="d-flex align-items-center text-gray-600 me-5 mb-2">
                                        <i class="bi bi-whatsapp me-2"></i>{{ $mahasiswa->hp ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center text-gray-600 mb-2">
                                        <i class="bi bi-envelope me-2"></i>{{ $mahasiswa->user->email }}
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('mahasiswa.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                        </div>

                        <!-- Stats -->
                        <div class="d-flex flex-wrap flex-stack">
                            <div class="d-flex flex-column flex-grow-1 pe-8">
                                <div class="d-flex flex-wrap">
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bolder">{{ number_format($mahasiswa->ips_1 ?? 0, 2) }}
                                            </div>
                                        </div>
                                        <div class="fw-bold fs-6 text-gray-400">IPS 1</div>
                                    </div>
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bolder">{{ number_format($mahasiswa->ips_2 ?? 0, 2) }}
                                            </div>
                                        </div>
                                        <div class="fw-bold fs-6 text-gray-400">IPS 2</div>
                                    </div>
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bolder">{{ $mahasiswa->kelengkapan_hardfile }}%</div>
                                        </div>
                                        <div class="fw-bold fs-6 text-gray-400">Hardfile</div>
                                    </div>
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bolder">{{ $mahasiswa->kelengkapan_softfile }}%</div>
                                        </div>
                                        <div class="fw-bold fs-6 text-gray-400">Softfile</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <div class="d-flex overflow-auto h-55px">
                    <ul
                        class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder flex-nowrap">
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6 {{ request()->routeIs('mahasiswa.biodata') ? 'active' : '' }}"
                                href="{{ route('mahasiswa.biodata', $mahasiswa->nim) }}">Biodata</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6 {{ request()->routeIs('mahasiswa.pendidikan') ? 'active' : '' }}"
                                href="{{ route('mahasiswa.pendidikan', $mahasiswa->nim) }}">Data Pendidikan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6 {{ request()->routeIs('mahasiswa.dataortu') ? 'active' : '' }}"
                                href="{{ route('mahasiswa.dataortu', $mahasiswa->nim) }}">Data Orang Tua</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6 {{ request()->routeIs('mahasiswa.akademik') ? 'active' : '' }}"
                                href="{{ route('mahasiswa.akademik', $mahasiswa->nim) }}">Informasi Akademik</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6 {{ request()->routeIs('mahasiswa.riwayat-kuliah') ? 'active' : '' }}"
                                href="{{ route('mahasiswa.riwayat-kuliah', $mahasiswa->nim) }}">Riwayat Kuliah</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6 {{ request()->routeIs('mahasiswa.pembayaran') ? 'active' : '' }}"
                                href="{{ route('mahasiswa.pembayaran', $mahasiswa->nim) }}">Pembayaran</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6 {{ request()->routeIs('mahasiswa.dokumen') ? 'active' : '' }}"
                                href="{{ route('mahasiswa.dokumen', $mahasiswa->nim) }}">Dokumen</a>
                        </li>
                        <!-- NEW TAB -->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6 {{ request()->routeIs('mahasiswa.kelengkapan-dokumen') ? 'active' : '' }}"
                                href="{{ route('mahasiswa.kelengkapan-dokumen', $mahasiswa->nim) }}">Kelengkapan Berkas</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            @if (request()->routeIs('mahasiswa.biodata'))
                @yield('biodata')
            @elseif (request()->routeIs('mahasiswa.pendidikan'))
                @yield('pendidikan')
            @elseif (request()->routeIs('mahasiswa.dataortu'))
                @yield('dataortu')
            @elseif (request()->routeIs('mahasiswa.akademik'))
                @yield('akademik')
            @elseif (request()->routeIs('mahasiswa.riwayat-kuliah'))
                @yield('riwayat-kuliah')
            @elseif (request()->routeIs('mahasiswa.pembayaran'))
                @yield('pembayaran')
            @elseif (request()->routeIs('mahasiswa.dokumen'))
                @yield('dokumen')
            @elseif (request()->routeIs('mahasiswa.kelengkapan-dokumen'))
                @yield('kelengkapan-dokumen')
            @else
                <div class="card-body">
                    <p class="text-center text-muted">Halaman tidak ditemukan</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Ini untuk memastikan script dari file anak bisa "mendarat" di sini --}}
@endpush

@stack('scripts')
