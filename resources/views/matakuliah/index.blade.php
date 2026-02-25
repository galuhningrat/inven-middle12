@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Kurikulum Mata Kuliah</h1>
                <span class="h-20px border-gray-200 border-start mx-4"></span>
            </div>
            <div class="d-flex align-items-center py-1">
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="/dashboard" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">Mata Kuliah</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @php
        /**
         * Kumpulkan semua objek Matkul unik yang muncul di seluruh kurikulum.
         * Modal detail & delete akan dirender sekali per MK, di luar struktur tabel.
         */
        $allRenderedMks = collect();
    @endphp

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-fluid">

            @include('master.notification')

            {{-- ================================================================ --}}
            {{-- STATS CARDS                                                       --}}
            {{-- ================================================================ --}}
            <div class="row g-4 mb-6">
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-5">
                            <div
                                class="d-flex flex-center w-50px h-50px rounded-circle bg-light-primary me-4 flex-shrink-0">
                                <i class="bi bi-book-fill fs-2 text-primary"></i>
                            </div>
                            <div>
                                <div class="fs-2hx fw-bold text-gray-800 lh-1">{{ $totalUniqueMatkul }}</div>
                                <div class="fs-7 fw-semibold text-gray-500">Total Mata Kuliah</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-5">
                            <div
                                class="d-flex flex-center w-50px h-50px rounded-circle bg-light-success me-4 flex-shrink-0">
                                <i class="bi bi-building fs-2 text-success"></i>
                            </div>
                            <div>
                                <div class="fs-2hx fw-bold text-gray-800 lh-1">{{ $fakultas->count() }}</div>
                                <div class="fs-7 fw-semibold text-gray-500">Fakultas</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-5">
                            <div class="d-flex flex-center w-50px h-50px rounded-circle bg-light-info me-4 flex-shrink-0">
                                <i class="bi bi-mortarboard-fill fs-2 text-info"></i>
                            </div>
                            <div>
                                <div class="fs-2hx fw-bold text-gray-800 lh-1">{{ $allProdi->count() }}</div>
                                <div class="fs-7 fw-semibold text-gray-500">Program Studi</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-5">
                            <div
                                class="d-flex flex-center w-50px h-50px rounded-circle bg-light-warning me-4 flex-shrink-0">
                                <i class="bi bi-person-badge-fill fs-2 text-warning"></i>
                            </div>
                            <div>
                                <div class="fs-2hx fw-bold text-gray-800 lh-1">{{ $dosen->count() }}</div>
                                <div class="fs-7 fw-semibold text-gray-500">Dosen Pengampu</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================================================================ --}}
            {{-- FILTER CARD                                                       --}}
            {{-- ================================================================ --}}
            <div class="card mb-5">
                <div class="card-body py-4">
                    <form method="GET" action="{{ route('matakuliah.index') }}"
                        class="d-flex flex-wrap gap-3 align-items-end">
                        <div style="min-width:220px; max-width:320px; flex-grow:1">
                            <label class="form-label fw-semibold fs-7 mb-1">Cari Mata Kuliah</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search text-gray-500"></i>
                                </span>
                                <input type="text" name="search" class="form-control" placeholder="Kode / Nama MK..."
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div style="min-width:180px">
                            <label class="form-label fw-semibold fs-7 mb-1">Filter Prodi</label>
                            <select name="filter_prodi" class="form-select form-select-sm">
                                <option value="">Semua Prodi</option>
                                @foreach ($allProdi as $p)
                                    <option value="{{ $p->id }}"
                                        {{ request('filter_prodi') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div style="min-width:160px">
                            <label class="form-label fw-semibold fs-7 mb-1">Filter Semester</label>
                            <select name="filter_semester" class="form-select form-select-sm">
                                <option value="">Semua Semester</option>
                                @for ($s = 1; $s <= 14; $s++)
                                    <option value="{{ $s }}"
                                        {{ request('filter_semester') == $s ? 'selected' : '' }}>
                                        Semester {{ $s }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-funnel me-1"></i>Filter
                            </button>
                            <a href="{{ route('matakuliah.index') }}" class="btn btn-sm btn-light">Reset</a>
                        </div>
                        <div class="ms-auto">
                            <a href="{{ route('matakuliah.all-data') }}" class="btn btn-sm btn-light-primary">
                                <i class="bi bi-table me-1"></i>Lihat Master Data
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ================================================================ --}}
            {{-- HIERARCHY: Fakultas > Prodi > Semester > MK                     --}}
            {{-- ================================================================ --}}
            @if ($fakultas->isEmpty())
                <div class="card">
                    <div class="card-body text-center py-20">
                        <i class="bi bi-folder2-open fs-5tx text-gray-300 d-block mb-4"></i>
                        <div class="fs-4 fw-bold text-gray-500">Belum ada data fakultas</div>
                    </div>
                </div>
            @else
                {{-- LEVEL 1: FAKULTAS — Nav-Tabs --}}
                <div class="card">
                    <div class="card-header border-0 pt-5 pb-0">
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-6 fw-bold" id="fakultasTabs"
                            role="tablist">
                            @foreach ($fakultas as $fi => $fak)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link text-active-primary pb-4 {{ $fi === 0 ? 'active' : '' }}"
                                        data-bs-toggle="tab" data-bs-target="#fak-pane-{{ $fak->id }}"
                                        type="button" role="tab">
                                        <i class="bi bi-building me-2"></i>
                                        {{ $fak->nama_fakultas }}
                                        <span class="badge badge-light-primary ms-2">{{ $fak->prodi->count() }}
                                            Prodi</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="card-body pt-5">
                        <div class="tab-content">
                            @foreach ($fakultas as $fi => $fak)
                                <div class="tab-pane fade {{ $fi === 0 ? 'show active' : '' }}"
                                    id="fak-pane-{{ $fak->id }}" role="tabpanel">

                                    @if ($fak->prodi->isEmpty())
                                        <div class="text-center py-10 text-muted">
                                            <i class="bi bi-info-circle fs-3x text-gray-300 d-block mb-3"></i>
                                            Belum ada program studi di fakultas ini.
                                        </div>
                                    @else
                                        {{-- LEVEL 2: PRODI — Nav Pills vertikal --}}
                                        <div class="d-flex gap-5">
                                            {{-- Sidebar Prodi --}}
                                            <div class="flex-shrink-0" style="width:230px">
                                                <div class="nav flex-column nav-pills gap-2"
                                                    id="prodi-pills-{{ $fak->id }}" role="tablist">
                                                    @foreach ($fak->prodi as $pi => $prodi)
                                                        <button
                                                            class="nav-link btn btn-flex btn-active-light-primary text-start px-4 py-3
                                        {{ $pi === 0 ? 'active' : '' }}"
                                                            data-bs-toggle="pill"
                                                            data-bs-target="#prodi-pane-{{ $prodi->id }}"
                                                            type="button" role="tab">
                                                            <span class="d-flex flex-column align-items-start w-100">
                                                                <span
                                                                    class="fw-bold fs-7 text-gray-800">{{ $prodi->nama_prodi }}</span>
                                                                <span class="fs-8 text-gray-500 mt-1">
                                                                    <span class="text-primary fw-bold">
                                                                        {{ $statsByProdi[$prodi->id]['total'] ?? 0 }}
                                                                    </span> MK &bull;
                                                                    <span class="text-success fw-bold">
                                                                        {{ $statsByProdi[$prodi->id]['total_sks'] ?? 0 }}
                                                                    </span> SKS
                                                                </span>
                                                            </span>
                                                            <i class="bi bi-chevron-right ms-auto text-gray-400 fs-8"></i>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>

                                            {{-- Konten Prodi --}}
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="tab-content">
                                                    @foreach ($fak->prodi as $pi => $prodi)
                                                        <div class="tab-pane fade {{ $pi === 0 ? 'show active' : '' }}"
                                                            id="prodi-pane-{{ $prodi->id }}" role="tabpanel">

                                                            {{-- Header Prodi --}}
                                                            <div class="d-flex align-items-center mb-5 pb-4 border-bottom">
                                                                <div class="flex-grow-1">
                                                                    <h4 class="fw-bold text-gray-800 mb-1">
                                                                        {{ $prodi->nama_prodi }}</h4>
                                                                    <div class="d-flex gap-2">
                                                                        @if ($prodi->kode_prodi)
                                                                            <span
                                                                                class="badge badge-light-info">{{ $prodi->kode_prodi }}</span>
                                                                        @endif
                                                                        @if ($prodi->jenjang)
                                                                            <span
                                                                                class="badge badge-light-success">{{ strtoupper($prodi->jenjang) }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex gap-5 text-center flex-shrink-0">
                                                                    <div>
                                                                        <div class="fs-2 fw-bolder text-primary">
                                                                            {{ $statsByProdi[$prodi->id]['total'] ?? 0 }}
                                                                        </div>
                                                                        <div class="fs-8 text-muted">Mata Kuliah</div>
                                                                    </div>
                                                                    <div class="border-start border-dashed mx-1"></div>
                                                                    <div>
                                                                        <div class="fs-2 fw-bolder text-success">
                                                                            {{ $statsByProdi[$prodi->id]['total_sks'] ?? 0 }}
                                                                        </div>
                                                                        <div class="fs-8 text-muted">Total SKS</div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            @php
                                                                $bySemester = $prodi->matkulMappings
                                                                    ->sortBy('semester')
                                                                    ->groupBy('semester');
                                                            @endphp

                                                            @if ($bySemester->isEmpty())
                                                                <div class="text-center py-10 text-muted">
                                                                    <i
                                                                        class="bi bi-file-earmark-x fs-3x text-gray-300 d-block mb-3"></i>
                                                                    Belum ada mata kuliah yang dipetakan ke prodi ini.
                                                                </div>
                                                            @else
                                                                {{-- LEVEL 3: SEMESTER — Accordion --}}
                                                                <div class="accordion accordion-icon-collapse"
                                                                    id="sem-acc-{{ $prodi->id }}">
                                                                    @foreach ($bySemester as $semester => $mappings)
                                                                        @php $semId = 'sem-' . $prodi->id . '-' . $semester; @endphp
                                                                        <div class="accordion-item mb-3 border rounded">
                                                                            <h2 class="accordion-header"
                                                                                id="hd-{{ $semId }}">
                                                                                <button
                                                                                    class="accordion-button collapsed fw-semibold fs-6 py-4 px-5 bg-light-primary rounded"
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#col-{{ $semId }}"
                                                                                    aria-expanded="false">
                                                                                    <div
                                                                                        class="d-flex align-items-center w-100 me-3">
                                                                                        <span
                                                                                            class="badge badge-primary me-3 px-3 flex-shrink-0">
                                                                                            Sem. {{ $semester }}
                                                                                        </span>
                                                                                        <span
                                                                                            class="fw-bold text-gray-800">
                                                                                            Semester {{ $semester }}
                                                                                        </span>
                                                                                        <span class="ms-auto d-flex gap-2">
                                                                                            <span
                                                                                                class="badge badge-light-primary">
                                                                                                {{ $mappings->count() }}
                                                                                                Mata Kuliah
                                                                                            </span>
                                                                                            <span
                                                                                                class="badge badge-light-success">
                                                                                                {{ $mappings->sum(fn($mp) => $mp->matkul?->bobot ?? 0) }}
                                                                                                SKS
                                                                                            </span>
                                                                                        </span>
                                                                                    </div>
                                                                                </button>
                                                                            </h2>
                                                                            <div id="col-{{ $semId }}"
                                                                                class="accordion-collapse collapse"
                                                                                data-bs-parent="#sem-acc-{{ $prodi->id }}">
                                                                                <div class="accordion-body p-0">

                                                                                    {{-- LEVEL 4: TABEL MK --}}
                                                                                    <div class="table-responsive">
                                                                                        <table
                                                                                            class="table table-bordered table-striped table-sm align-middle fs-6 gy-2 w-100 mb-0">
                                                                                            <thead
                                                                                                class="bg-gray-100 text-gray-800 border-bottom border-gray-300">
                                                                                                <tr
                                                                                                    class="fw-bold text-uppercase fs-8">
                                                                                                    <th class="ps-4 py-3"
                                                                                                        style="width:110px">
                                                                                                        Kode MK</th>
                                                                                                    <th class="py-3">Nama
                                                                                                        Mata Kuliah</th>
                                                                                                    <th class="text-center py-3"
                                                                                                        style="width:65px">
                                                                                                        SKS</th>
                                                                                                    <th class="text-center py-3"
                                                                                                        style="width:90px">
                                                                                                        Jenis</th>
                                                                                                    <th class="py-3"
                                                                                                        style="width:175px">
                                                                                                        Dosen Pengampu</th>
                                                                                                    <th class="py-3"
                                                                                                        style="width:110px">
                                                                                                        Berlaku</th>
                                                                                                    <th class="text-center py-3"
                                                                                                        style="width:100px">
                                                                                                        Aksi</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                @foreach ($mappings->sortBy(fn($mp) => $mp->matkul?->kode_mk) as $mapping)
                                                                                                    @php $mk = $mapping->matkul; @endphp
                                                                                                    @if (!$mk)
                                                                                                        @continue
                                                                                                    @endif

                                                                                                    {{-- Kumpulkan MK unik untuk dirender modalnya di luar tabel --}}
                                                                                                    @php $allRenderedMks->put($mk->id, $mk); @endphp

                                                                                                    <tr>
                                                                                                        <td class="ps-4">
                                                                                                            <span
                                                                                                                class="badge badge-light fw-bold text-dark font-monospace">
                                                                                                                {{ $mk->kode_mk }}
                                                                                                            </span>
                                                                                                        </td>
                                                                                                        <td
                                                                                                            class="fw-semibold text-gray-800">
                                                                                                            {{ $mk->nama_mk }}
                                                                                                        </td>
                                                                                                        <td
                                                                                                            class="text-center">
                                                                                                            <span
                                                                                                                class="fw-bolder text-primary">{{ $mk->bobot }}</span>
                                                                                                            <span
                                                                                                                class="text-muted fs-9">
                                                                                                                sks</span>
                                                                                                        </td>
                                                                                                        <td
                                                                                                            class="text-center">
                                                                                                            @php
                                                                                                                $jc = match (
                                                                                                                    $mk->jenis
                                                                                                                ) {
                                                                                                                    'wajib'
                                                                                                                        => 'primary',
                                                                                                                    'pilihan'
                                                                                                                        => 'warning',
                                                                                                                    'umum'
                                                                                                                        => 'info',
                                                                                                                    default
                                                                                                                        => 'secondary',
                                                                                                                };
                                                                                                            @endphp
                                                                                                            <span
                                                                                                                class="badge badge-light-{{ $jc }} fw-semibold">
                                                                                                                {{ $mk->jenis === 'umum' ? 'MKU' : ucfirst($mk->jenis) }}
                                                                                                            </span>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            @if ($mk->dosen && $mk->dosen->user)
                                                                                                                <div
                                                                                                                    class="d-flex align-items-center gap-2">
                                                                                                                    <div
                                                                                                                        class="symbol symbol-25px flex-shrink-0">
                                                                                                                        <span
                                                                                                                            class="symbol-label bg-light-success fs-9 fw-bold text-success">
                                                                                                                            {{ strtoupper(substr($mk->dosen->user->nama, 0, 1)) }}
                                                                                                                        </span>
                                                                                                                    </div>
                                                                                                                    <span
                                                                                                                        class="text-truncate fs-8"
                                                                                                                        style="max-width:125px">
                                                                                                                        {{ $mk->dosen->user->nama }}
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                            @else
                                                                                                                <span
                                                                                                                    class="text-muted">—</span>
                                                                                                            @endif
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            @if ($mk->prodiMappings->count() > 1)
                                                                                                                <span
                                                                                                                    class="badge badge-light-info fs-9"
                                                                                                                    title="{{ $mk->prodiMappings->pluck('prodi.nama_prodi')->filter()->implode(', ') }}">
                                                                                                                    {{ $mk->prodiMappings->count() }}
                                                                                                                    Prodi
                                                                                                                </span>
                                                                                                            @else
                                                                                                                <span
                                                                                                                    class="badge badge-light fs-9">Prodi
                                                                                                                    ini</span>
                                                                                                            @endif
                                                                                                        </td>
                                                                                                        <td
                                                                                                            class="text-center">
                                                                                                            {{-- DETAIL — ID pakai partial (modalDetailMatkul) --}}
                                                                                                            <button
                                                                                                                type="button"
                                                                                                                class="btn btn-icon btn-sm btn-light-primary me-1"
                                                                                                                title="Detail"
                                                                                                                data-bs-toggle="modal"
                                                                                                                data-bs-target="#modalDetailMatkul{{ $mk->id }}">
                                                                                                                <i
                                                                                                                    class="bi bi-eye-fill fs-5"></i>
                                                                                                            </button>

                                                                                                            {{-- EDIT --}}
                                                                                                            @can('kurikulum-update')
                                                                                                                <button
                                                                                                                    type="button"
                                                                                                                    class="btn btn-icon btn-sm btn-light-success me-1 btn-open-edit-matkul"
                                                                                                                    title="Ubah"
                                                                                                                    data-id="{{ $mk->id }}"
                                                                                                                    data-url="{{ route('matakuliah.update', $mk->id) }}"
                                                                                                                    data-kode="{{ $mk->kode_mk }}"
                                                                                                                    data-nama="{{ $mk->nama_mk }}"
                                                                                                                    data-bobot="{{ $mk->bobot }}"
                                                                                                                    data-jenis="{{ $mk->jenis }}"
                                                                                                                    data-id-dosen="{{ $mk->id_dosen }}"
                                                                                                                    data-mappings="{{ json_encode($mk->prodiMappings->map(fn($mp) => ['prodi_id' => $mp->id_prodi, 'semester' => $mp->semester])) }}">
                                                                                                                    <i
                                                                                                                        class="bi bi-pencil-fill fs-5"></i>
                                                                                                                </button>
                                                                                                            @endcan

                                                                                                            {{-- HAPUS — ID pakai partial (modalDeleteMatkul) --}}
                                                                                                            @can('kurikulum-delete')
                                                                                                                <button
                                                                                                                    type="button"
                                                                                                                    class="btn btn-icon btn-sm btn-light-danger"
                                                                                                                    title="Hapus"
                                                                                                                    data-bs-toggle="modal"
                                                                                                                    data-bs-target="#modalDeleteMatkul{{ $mk->id }}">
                                                                                                                    <i
                                                                                                                        class="bi bi-trash-fill fs-5"></i>
                                                                                                                </button>
                                                                                                            @endcan
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    {{-- ⚠️ JANGAN taruh modal di sini (di dalam tbody) --}}
                                                                                                @endforeach
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif {{-- end bySemester isEmpty --}}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif {{-- end prodi isEmpty --}}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif {{-- end fakultas isEmpty --}}

        </div>
    </div>

    {{--
    ╔══════════════════════════════════════════════════════════════════╗
    ║  MODALS — Dirender di luar seluruh struktur card/table/accordion ║
    ║  Satu modal per MK unik, tidak peduli di berapa prodi/semester   ║
    ║  MK itu muncul (dedup via $allRenderedMks collect).              ║
    ╚══════════════════════════════════════════════════════════════════╝
--}}
    @foreach ($allRenderedMks as $mk)
        @include('matakuliah.partials.detail-matkul', ['m' => $mk])
        @include('matakuliah.partials.delete-matkul', ['m' => $mk])
    @endforeach

    {{-- Modal Tambah --}}
    @can('kurikulum-create')
        @include('matakuliah.partials.create-matkul', ['dosen' => $dosen, 'prodi' => $allProdi])
    @endcan

    {{-- Modal Edit Global --}}
    @can('kurikulum-update')
        @include('matakuliah.partials.edit-matkul', ['dosen' => $dosen, 'prodi' => $allProdi])
    @endcan
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('[title]').tooltip({
                trigger: 'hover'
            });
        });
    </script>
@endpush
