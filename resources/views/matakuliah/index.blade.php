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
                    <li class="breadcrumb-item text-dark">Kurikulum Mata Kuliah</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    {{--
        ╔═══════════════════════════════════════════════════════════════════════╗
        ║  $allRenderedMks — Collection kosong yang diisi oleh partial          ║
        ║  semester-accordion.blade.php saat merender setiap baris MK.         ║
        ║                                                                       ║
        ║  BUG FIX: partial WAJIB memanggil $allRenderedMks->push($mk)         ║
        ║  agar loop @foreach di bawah bisa merender modal Detail & Hapus.     ║
        ║  Karena Collection adalah object PHP, ia diteruskan by-reference      ║
        ║  ke partial sehingga perubahan di dalam partial terlihat di sini.     ║
        ╚═══════════════════════════════════════════════════════════════════════╝
    --}}
    @php $allRenderedMks = collect(); @endphp

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-fluid">

            @include('master.notification')

            {{-- ================================================================ --}}
            {{-- STATS CARDS — berubah sesuai filter aktif                        --}}
            {{-- ================================================================ --}}
            <div class="row g-3 mb-3">
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-3">
                            <div
                                class="d-flex flex-center w-35px h-35px rounded-circle bg-light-primary me-3 flex-shrink-0">
                                <i class="bi bi-book-fill fs-5 text-primary"></i>
                            </div>
                            <div>
                                <div class="fs-2x fw-bold text-gray-800 lh-1">{{ $statTotalMatkul }}</div>
                                <div class="fs-9 fw-semibold text-gray-500">
                                    Total Mata Kuliah
                                    @if ($isFiltered)
                                        <span class="text-primary ms-1">(hasil filter)</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-3">
                            <div
                                class="d-flex flex-center w-35px h-35px rounded-circle bg-light-success me-3 flex-shrink-0">
                                <i class="bi bi-building fs-5 text-success"></i>
                            </div>
                            <div>
                                <div class="fs-2x fw-bold text-gray-800 lh-1">{{ $statFakultasCount }}</div>
                                <div class="fs-9 fw-semibold text-gray-500">Fakultas</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="d-flex flex-center w-35px h-35px rounded-circle bg-light-info me-3 flex-shrink-0">
                                <i class="bi bi-mortarboard-fill fs-5 text-info"></i>
                            </div>
                            <div>
                                <div class="fs-2x fw-bold text-gray-800 lh-1">{{ $statProdiCount }}</div>
                                <div class="fs-9 fw-semibold text-gray-500">Program Studi</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-3">
                            <div
                                class="d-flex flex-center w-35px h-35px rounded-circle bg-light-warning me-3 flex-shrink-0">
                                <i class="bi bi-people-fill fs-5 text-warning"></i>
                            </div>
                            <div>
                                <div class="fs-2x fw-bold text-gray-800 lh-1">{{ $statRombelCount }}</div>
                                <div class="fs-9 fw-semibold text-gray-500">Rombel / Angkatan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================================================================ --}}
            {{-- FILTER & ACTION BAR — kompak                                     --}}
            {{-- ================================================================ --}}
            <div class="card mb-3">
                <div class="card-body py-3 px-4">
                    <form method="GET" action="{{ route('matakuliah.index') }}"
                        class="d-flex flex-wrap gap-2 align-items-end" id="formFilter">

                        <div style="min-width:180px;max-width:260px;flex-grow:1">
                            <label class="form-label fw-semibold fs-8 mb-1">Cari Mata Kuliah</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white px-2">
                                    <i class="bi bi-search text-gray-500 fs-8"></i>
                                </span>
                                <input type="text" name="search" id="inputSearch"
                                    class="form-control form-control-sm {{ $search ? 'border-primary' : '' }}"
                                    placeholder="Kode / Nama MK..." value="{{ $search ?? '' }}">
                            </div>
                        </div>

                        <div style="min-width:150px">
                            <label class="form-label fw-semibold fs-8 mb-1">Program Studi</label>
                            <select name="filter_prodi" id="selectProdi"
                                class="form-select form-select-sm {{ $filterProdi ? 'border-primary' : '' }}">
                                <option value="">Semua Prodi</option>
                                @foreach ($allProdi as $p)
                                    <option value="{{ $p->id }}" {{ $filterProdi == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ─────────────────────────────────────────────────────────────
                             FIX BUG 6: Rombel dropdown dengan data-prodi-id agar JS bisa
                             menyembunyikan option yang bukan milik Prodi terpilih.
                             data-prodi-id diisi dari relasi $r->prodi->id (eager-loaded).
                             ───────────────────────────────────────────────────────────── --}}
                        <div style="min-width:160px">
                            <label class="form-label fw-semibold fs-8 mb-1">Rombel / Angkatan</label>
                            <select name="filter_rombel" id="selectRombel"
                                class="form-select form-select-sm {{ $filterRombel ? 'border-primary' : '' }}">
                                <option value="">Semua Rombel</option>
                                @foreach ($allRombel as $r)
                                    <option value="{{ $r->id }}" data-prodi-id="{{ $r->prodi->id ?? '' }}"
                                        {{ $filterRombel == $r->id ? 'selected' : '' }}>
                                        {{ $r->nama_rombel }}
                                        @if ($r->tahunMasuk)
                                            ({{ $r->tahunMasuk->tahun_awal }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="min-width:130px">
                            <label class="form-label fw-semibold fs-8 mb-1">Semester</label>
                            <select name="filter_semester" id="selectSemester"
                                class="form-select form-select-sm {{ $filterSemester ? 'border-primary' : '' }}">
                                <option value="">Semua Semester</option>
                                @for ($s = 1; $s <= 14; $s++)
                                    <option value="{{ $s }}" {{ $filterSemester == $s ? 'selected' : '' }}>
                                        Semester {{ $s }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="d-flex gap-1 align-self-end">
                            <button type="submit" class="btn btn-sm btn-primary px-3">
                                <i class="bi bi-funnel me-1"></i>Filter
                            </button>
                            <a href="{{ route('matakuliah.index') }}"
                                class="btn btn-sm {{ $isFiltered ? 'btn-danger' : 'btn-light' }} px-3">
                                <i class="bi bi-x-lg me-1"></i>Reset
                                @if ($isFiltered)
                                    <span class="badge badge-white text-danger ms-1 fs-9" id="filterCount"></span>
                                @endif
                            </a>
                        </div>

                        <div class="ms-auto d-flex gap-2 align-items-end">
                            @can('kurikulum-create')
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTambahMatkul">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah MK
                                </button>
                            @endcan
                            <a href="{{ route('matakuliah.all-data') }}" class="btn btn-sm btn-light-primary">
                                <i class="bi bi-table me-1"></i>Master Data
                            </a>
                        </div>

                    </form>

                    {{-- Banner filter aktif --}}
                    @if ($isFiltered)
                        <div class="d-flex align-items-center gap-2 mt-2 pt-2 border-top border-dashed">
                            <i class="bi bi-funnel-fill text-primary fs-8"></i>
                            <span class="fs-9 text-gray-600 fw-semibold">Filter aktif:</span>
                            @if ($search)
                                <span class="badge badge-light-primary fs-9">
                                    <i class="bi bi-search me-1"></i>"{{ $search }}"
                                </span>
                            @endif
                            @if ($filterProdi)
                                <span class="badge badge-light-success fs-9">
                                    <i class="bi bi-mortarboard me-1"></i>
                                    {{ $allProdi->firstWhere('id', $filterProdi)?->nama_prodi ?? 'Prodi #' . $filterProdi }}
                                </span>
                            @endif
                            @if ($filterRombel)
                                <span class="badge badge-light-warning fs-9">
                                    <i class="bi bi-people me-1"></i>
                                    {{ $allRombel->firstWhere('id', $filterRombel)?->nama_rombel ?? 'Rombel #' . $filterRombel }}
                                </span>
                            @endif
                            @if ($filterSemester)
                                <span class="badge badge-light-info fs-9">
                                    <i class="bi bi-calendar3 me-1"></i>Semester {{ $filterSemester }}
                                </span>
                            @endif
                            <span class="text-muted fs-9 ms-1">
                                — menampilkan <strong>{{ $statTotalMatkul }}</strong> MK di
                                <strong>{{ $statProdiCount }}</strong> Prodi
                            </span>
                        </div>
                    @endif

                </div>
            </div>

            {{-- ================================================================ --}}
            {{-- HIERARKI 5 LEVEL                                                  --}}
            {{-- ================================================================ --}}
            @if ($fakultas->isEmpty())
                <div class="card">
                    <div class="card-body text-center py-12">
                        <i class="bi bi-folder2-open fs-5tx text-gray-300 d-block mb-3"></i>
                        <div class="fs-5 fw-bold text-gray-500 mb-1">Belum ada data fakultas</div>
                        <p class="text-muted fs-8">Tambahkan Fakultas terlebih dahulu melalui menu Data Master.</p>
                    </div>
                </div>
            @else
                {{-- ── LEVEL 1 — FAKULTAS : Nav-Tabs ─────────────────────────── --}}
                <div class="card">
                    <div class="card-header border-0 pt-3 pb-0" style="min-height:auto">
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-7 fw-bold" id="tabFakultas"
                            role="tablist">
                            @foreach ($fakultas as $fi => $fak)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link text-active-primary pb-3 {{ $fi === 0 ? 'active' : '' }}"
                                        id="tab-fak-{{ $fak->id }}" data-bs-toggle="tab"
                                        data-bs-target="#pane-fak-{{ $fak->id }}" type="button" role="tab"
                                        aria-controls="pane-fak-{{ $fak->id }}"
                                        aria-selected="{{ $fi === 0 ? 'true' : 'false' }}">
                                        <i class="bi bi-building me-1 fs-8"></i>
                                        {{ $fak->nama_fakultas }}
                                        <span class="badge badge-light-primary ms-1 fs-9">
                                            {{ $fak->prodi->count() }} Prodi
                                        </span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="card-body pt-4 pb-3 px-4">
                        <div class="tab-content" id="tabFakultasContent">

                            @foreach ($fakultas as $fi => $fak)
                                <div class="tab-pane fade {{ $fi === 0 ? 'show active' : '' }}"
                                    id="pane-fak-{{ $fak->id }}" role="tabpanel"
                                    aria-labelledby="tab-fak-{{ $fak->id }}">

                                    @if ($fak->prodi->isEmpty())
                                        <div class="text-center py-8 text-muted">
                                            <i class="bi bi-info-circle fs-2x text-gray-300 d-block mb-2"></i>
                                            <p class="fs-8 mb-0">Belum ada program studi di fakultas ini.</p>
                                        </div>
                                    @else
                                        {{-- ── LEVEL 2 — PRODI : Nav-Pills vertikal ─────── --}}
                                        <div class="d-flex gap-4 align-items-start">

                                            {{-- Sidebar Nav-Pills Prodi --}}
                                            <div class="flex-shrink-0" style="width:200px;min-width:160px">
                                                <div class="nav flex-column nav-pills gap-1"
                                                    id="pills-prodi-{{ $fak->id }}" role="tablist"
                                                    aria-orientation="vertical">

                                                    @foreach ($fak->prodi as $pi => $prodi)
                                                        <button
                                                            class="nav-link btn btn-flex btn-active-light-primary
                                                                   text-start px-3 py-2 {{ $pi === 0 ? 'active' : '' }}"
                                                            id="pill-prodi-{{ $prodi->id }}" data-bs-toggle="pill"
                                                            data-bs-target="#pane-prodi-{{ $prodi->id }}"
                                                            type="button" role="tab"
                                                            aria-controls="pane-prodi-{{ $prodi->id }}"
                                                            aria-selected="{{ $pi === 0 ? 'true' : 'false' }}">
                                                            <span
                                                                class="d-flex flex-column align-items-start w-100 overflow-hidden">
                                                                <span
                                                                    class="fw-semibold fs-8 text-gray-800 text-truncate w-100">
                                                                    {{ $prodi->nama_prodi }}
                                                                </span>
                                                                <span class="fs-9 text-gray-500 mt-0">
                                                                    <span
                                                                        class="text-primary fw-bold">{{ $statsByProdi[$prodi->id]['total'] ?? 0 }}</span>
                                                                    MK &bull;
                                                                    <span
                                                                        class="text-success fw-bold">{{ $statsByProdi[$prodi->id]['total_sks'] ?? 0 }}</span>
                                                                    SKS &bull;
                                                                    <span
                                                                        class="text-warning fw-bold">{{ $prodi->rombel->count() }}</span>
                                                                    Rombel
                                                                </span>
                                                            </span>
                                                            <i
                                                                class="bi bi-chevron-right ms-auto text-gray-400 flex-shrink-0 fs-9"></i>
                                                        </button>
                                                    @endforeach

                                                </div>
                                            </div>

                                            {{-- Konten Prodi --}}
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="tab-content" id="pills-prodi-content-{{ $fak->id }}">

                                                    @foreach ($fak->prodi as $pi => $prodi)
                                                        <div class="tab-pane fade {{ $pi === 0 ? 'show active' : '' }}"
                                                            id="pane-prodi-{{ $prodi->id }}" role="tabpanel"
                                                            aria-labelledby="pill-prodi-{{ $prodi->id }}">

                                                            {{-- Header Info Prodi --}}
                                                            <div
                                                                class="d-flex align-items-center mb-3 pb-3 border-bottom border-dashed">
                                                                <div class="flex-grow-1">
                                                                    <h5 class="fw-bold text-gray-800 mb-1 fs-6">
                                                                        {{ $prodi->nama_prodi }}
                                                                    </h5>
                                                                    <div class="d-flex gap-1 flex-wrap">
                                                                        @if ($prodi->kode_prodi)
                                                                            <span class="badge badge-light-dark fs-9">
                                                                                <i
                                                                                    class="bi bi-hash me-1"></i>{{ $prodi->kode_prodi }}
                                                                            </span>
                                                                        @endif
                                                                        @if ($prodi->jenjang)
                                                                            <span class="badge badge-light-success fs-9">
                                                                                {{ strtoupper($prodi->jenjang) }}
                                                                            </span>
                                                                        @endif
                                                                        @if ($prodi->status_akre)
                                                                            <span class="badge badge-light-info fs-9">
                                                                                Akreditasi: {{ $prodi->status_akre }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex gap-3 text-center ms-3 flex-shrink-0">
                                                                    <div>
                                                                        <div class="fs-3 fw-bolder text-primary lh-1">
                                                                            {{ $statsByProdi[$prodi->id]['total'] ?? 0 }}
                                                                        </div>
                                                                        <div class="fs-9 text-muted">MK</div>
                                                                    </div>
                                                                    <div class="border-start border-dashed"></div>
                                                                    <div>
                                                                        <div class="fs-3 fw-bolder text-success lh-1">
                                                                            {{ $statsByProdi[$prodi->id]['total_sks'] ?? 0 }}
                                                                        </div>
                                                                        <div class="fs-9 text-muted">SKS</div>
                                                                    </div>
                                                                    <div class="border-start border-dashed"></div>
                                                                    <div>
                                                                        <div class="fs-3 fw-bolder text-warning lh-1">
                                                                            {{ $prodi->rombel->count() }}
                                                                        </div>
                                                                        <div class="fs-9 text-muted">Rombel</div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            @if ($prodi->rombel->isEmpty())
                                                                @php
                                                                    $bySemesterDirect = collect(
                                                                        $mkByProdi[$prodi->id] ?? [],
                                                                    )->sortKeys();
                                                                @endphp

                                                                @if ($bySemesterDirect->isEmpty())
                                                                    <div class="text-center py-8 text-muted">
                                                                        <i
                                                                            class="bi bi-file-earmark-x fs-2x text-gray-300 d-block mb-2"></i>
                                                                        <p class="fs-8 mb-1 fw-semibold">Belum ada
                                                                            kurikulum</p>
                                                                        <p class="fs-9 mb-0">
                                                                            Tambahkan mata kuliah dan petakan ke prodi ini,
                                                                            atau buat Rombel terlebih dahulu.
                                                                        </p>
                                                                    </div>
                                                                @else
                                                                    <div
                                                                        class="alert alert-light-warning border border-warning border-dashed d-flex align-items-center mb-3 py-2">
                                                                        <i
                                                                            class="bi bi-exclamation-triangle-fill text-warning me-2 fs-6 flex-shrink-0"></i>
                                                                        <span class="fs-8">
                                                                            Prodi ini belum memiliki Rombel/Angkatan.
                                                                            Mata kuliah ditampilkan langsung per semester.
                                                                            <a href="{{ route('rombel.index') }}"
                                                                                class="fw-bold text-warning ms-1">
                                                                                Buat Rombel &rarr;
                                                                            </a>
                                                                        </span>
                                                                    </div>

                                                                    @include(
                                                                        'matakuliah.partials.semester-accordion',
                                                                        [
                                                                            'bySemester' => $bySemesterDirect,
                                                                            'accordionId' =>
                                                                                'acc-direct-' . $prodi->id,
                                                                            'allRenderedMks' => $allRenderedMks,
                                                                            'rombel' => (object) [
                                                                                'id_prodi' => $prodi->id,
                                                                                'id' => 'direct-' . $prodi->id,
                                                                            ],
                                                                        ]
                                                                    )
                                                                @endif
                                                            @else
                                                                {{-- ── LEVEL 3 — ROMBEL : Accordion ──────── --}}
                                                                <div class="accordion accordion-icon-collapse"
                                                                    id="acc-rombel-prodi-{{ $prodi->id }}">

                                                                    @foreach ($prodi->rombel as $ri => $rombel)
                                                                        @php
                                                                            // ── FILTER PER ROMBEL ─────────────────────
                                                                            // Tampilkan MK yang:
                                                                            //   (a) id_rombel = NULL → berlaku semua rombel
                                                                            //   (b) id_rombel = $rombel->id → khusus rombel ini
                                                                            // MK dengan id_rombel = NULL adalah mapping lama
                                                                            // (sebelum migration) yang backward-compatible.
                                                                            $bySemester = collect(
                                                                                $mkByProdi[$prodi->id] ?? [],
                                                                            )
                                                                                ->map(function ($mappings) use (
                                                                                    $rombel,
                                                                                ) {
                                                                                    return array_values(
                                                                                        array_filter(
                                                                                            $mappings,
                                                                                            fn($mp) => is_null(
                                                                                                $mp->id_rombel,
                                                                                            ) ||
                                                                                                $mp->id_rombel ==
                                                                                                    $rombel->id,
                                                                                        ),
                                                                                    );
                                                                                })
                                                                                ->filter(fn($arr) => !empty($arr))
                                                                                ->sortKeys();

                                                                            $rombelPaneId =
                                                                                'pane-rombel-' . $rombel->id;

                                                                            $rombelTotalMk = $bySemester->sum(
                                                                                fn($arr) => count($arr),
                                                                            );
                                                                            $rombelTotalSem = $bySemester->count();
                                                                            $rombelTotalSks = $bySemester->reduce(
                                                                                function ($carry, $arr) {
                                                                                    foreach ($arr as $mp) {
                                                                                        $carry +=
                                                                                            $mp->matkul?->bobot ?? 0;
                                                                                    }
                                                                                    return $carry;
                                                                                },
                                                                                0,
                                                                            );

                                                                            $tahunAwal =
                                                                                $rombel->tahunMasuk?->tahun_awal;
                                                                            $angkatanLabel = $tahunAwal
                                                                                ? 'Angkatan ' . $tahunAwal
                                                                                : 'Angkatan —';
                                                                        @endphp

                                                                        <div
                                                                            class="accordion-item mb-2 border rounded-2 shadow-sm">

                                                                            <h2 class="accordion-header"
                                                                                id="hd-{{ $rombelPaneId }}">
                                                                                <button
                                                                                    class="accordion-button {{ $ri !== 0 ? 'collapsed' : '' }}
                                                                                           fw-semibold py-2 px-4 rounded-2 bg-light-primary"
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#{{ $rombelPaneId }}"
                                                                                    aria-expanded="{{ $ri === 0 ? 'true' : 'false' }}"
                                                                                    aria-controls="{{ $rombelPaneId }}">

                                                                                    <div
                                                                                        class="d-flex align-items-center w-100 me-3 gap-2">

                                                                                        <div
                                                                                            class="d-flex flex-center w-30px h-30px rounded-circle bg-primary flex-shrink-0">
                                                                                            <i
                                                                                                class="bi bi-people-fill text-white fs-8"></i>
                                                                                        </div>

                                                                                        <div class="flex-grow-1 min-w-0">
                                                                                            <div
                                                                                                class="fw-bold text-gray-800 fs-7 d-flex align-items-center gap-2">
                                                                                                {{ $rombel->nama_rombel }}
                                                                                                <span
                                                                                                    class="badge badge-light-dark fw-normal fs-9 font-monospace">
                                                                                                    {{ $rombel->kode_rombel }}
                                                                                                </span>
                                                                                            </div>
                                                                                            <div class="d-flex gap-1">
                                                                                                <span
                                                                                                    class="badge badge-light-warning fs-9">
                                                                                                    <i
                                                                                                        class="bi bi-calendar3 me-1"></i>
                                                                                                    {{ $angkatanLabel }}
                                                                                                </span>
                                                                                                @if ($rombel->dosen?->user?->nama)
                                                                                                    <span
                                                                                                        class="badge badge-light-info fs-9">
                                                                                                        <i
                                                                                                            class="bi bi-person me-1"></i>
                                                                                                        DPA:
                                                                                                        {{ $rombel->dosen->user->nama }}
                                                                                                    </span>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>

                                                                                        <div
                                                                                            class="d-flex gap-1 ms-auto flex-shrink-0">
                                                                                            <span
                                                                                                class="badge badge-light-primary fs-9">
                                                                                                <i
                                                                                                    class="bi bi-layers me-1"></i>
                                                                                                {{ $rombelTotalSem }} Sem
                                                                                            </span>
                                                                                            <span
                                                                                                class="badge badge-light-success fs-9">
                                                                                                <i
                                                                                                    class="bi bi-book me-1"></i>
                                                                                                {{ $rombelTotalMk }} MK
                                                                                            </span>
                                                                                            <span
                                                                                                class="badge badge-light-info fs-9">
                                                                                                {{ $rombelTotalSks }} SKS
                                                                                            </span>
                                                                                        </div>

                                                                                    </div>
                                                                                </button>
                                                                            </h2>

                                                                            <div id="{{ $rombelPaneId }}"
                                                                                class="accordion-collapse collapse {{ $ri === 0 ? 'show' : '' }}"
                                                                                aria-labelledby="hd-{{ $rombelPaneId }}"
                                                                                data-bs-parent="#acc-rombel-prodi-{{ $prodi->id }}">

                                                                                <div class="accordion-body px-3 py-3">
                                                                                    @if ($bySemester->isEmpty())
                                                                                        <div
                                                                                            class="text-center py-6 text-muted">
                                                                                            <i
                                                                                                class="bi bi-inbox fs-2x text-gray-300 d-block mb-2"></i>
                                                                                            <p class="fs-8 mb-0">
                                                                                                Belum ada mata kuliah yang
                                                                                                dipetakan ke Prodi
                                                                                                <strong>{{ $prodi->nama_prodi }}</strong>.
                                                                                            </p>
                                                                                        </div>
                                                                                    @else
                                                                                        {{--
                                                                                            LEVEL 4 — SEMESTER & LEVEL 5 — MK
                                                                                            Partial ini yang mengisi $allRenderedMks
                                                                                            via $allRenderedMks->push($mk)
                                                                                        --}}
                                                                                        @include(
                                                                                            'matakuliah.partials.semester-accordion',
                                                                                            [
                                                                                                'bySemester' => $bySemester,
                                                                                                'accordionId' =>
                                                                                                    'acc-sem-rombel-' .
                                                                                                    $rombel->id,
                                                                                                'allRenderedMks' => $allRenderedMks,
                                                                                            ]
                                                                                        )
                                                                                    @endif
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    @endforeach

                                                                </div>
                                                            @endif

                                                        </div>
                                                        {{-- end tab-pane Prodi --}}
                                                    @endforeach

                                                </div>
                                            </div>

                                        </div>
                                    @endif

                                </div>
                                {{-- end tab-pane Fakultas --}}
                            @endforeach

                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- MODALS — di luar semua struktur tabel/accordion                  --}}
    {{--                                                                  --}}
    {{-- $allRenderedMks sudah diisi oleh semester-accordion.blade.php    --}}
    {{-- via $allRenderedMks->push($mk). Loop ini merender modal          --}}
    {{-- Detail dan Hapus untuk setiap MK unik yang tampil di halaman.   --}}
    {{-- ================================================================ --}}
    @foreach ($allRenderedMks as $mk)
        @include('matakuliah.partials.detail-matkul', ['m' => $mk])
        @include('matakuliah.partials.delete-matkul', ['m' => $mk])
    @endforeach

    {{--
        ╔══════════════════════════════════════════════════════════════════════╗
        ║  KENAPA TIDAK PAKAI @can DI SINI?                                   ║
        ║                                                                      ║
        ║  Aplikasi ini memakai custom middleware 'permission:kurikulum,X'    ║
        ║  yang mengecek tabel `permissions` sendiri — bukan Laravel Gate.    ║
        ║  Directive @can('kurikulum-update') memanggil Gate::check() yang    ║
        ║  tidak pernah terdaftar → selalu false → modal TIDAK ter-render     ║
        ║  → tombol Edit diklik → tidak ada modal → diam saja.                ║
        ║                                                                      ║
        ║  Security TETAP aman: route update dilindungi controller middleware. ║
        ║  Modal ada di DOM ≠ bypass security. Request tetap ditolak server   ║
        ║  jika user tidak punya permission.                                   ║
        ║                                                                      ║
        ║  Referensi: all-data.blade.php include edit modal tanpa @can         ║
        ║  dan berfungsi normal.                                               ║
        ╚══════════════════════════════════════════════════════════════════════╝
    --}}
    @include('matakuliah.partials.create-matkul', [
        'dosen' => $dosen,
        'prodi' => $allProdi,
        'allRombel' => $allRombel, // untuk rombelDataCreate JSON
    ])

    @include('matakuliah.partials.edit-matkul', [
        'dosen' => $dosen,
        'prodi' => $allProdi,
        'allRombel' => $allRombel, // untuk rombelData JSON
    ])
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // ── Tooltips Bootstrap ────────────────────────────────────────────
            $('[title]').tooltip({
                trigger: 'hover'
            });

            // ================================================================
            // FIX BUG 6: Dynamic Rombel dropdown
            //
            // Saat user memilih Prodi, dropdown Rombel otomatis menyembunyikan
            // option yang bukan milik Prodi tersebut. Setiap <option> rombel
            // memiliki data-prodi-id="{{ $r->prodi->id }}" yang di-render
            // oleh Blade dari relasi eager-loaded.
            //
            // Logika:
            //   - Prodi dipilih  → sembunyikan rombel prodi lain, reset jika
            //                      rombel saat ini bukan milik prodi terpilih
            //   - Prodi direset  → tampilkan semua rombel kembali
            // ================================================================
            var $selectProdi = $('#selectProdi');
            var $selectRombel = $('#selectRombel');

            // Simpan semua option rombel (termasuk "Semua Rombel") ke memori
            // agar bisa di-restore saat prodi direset ke "Semua"
            var $allRombelOptions = $selectRombel.find('option').clone();

            function filterRombelByProdi(prodiId) {
                var currentRombel = $selectRombel.val();
                $selectRombel.find('option').each(function() {
                    var $opt = $(this);
                    var optProdi = $opt.data('prodi-id');

                    if (!prodiId || !optProdi || optProdi == prodiId) {
                        // Tampilkan: option "Semua Rombel" (no data-prodi-id),
                        // atau rombel milik prodi terpilih
                        $opt.show().prop('disabled', false);
                    } else {
                        $opt.hide().prop('disabled', true);
                    }
                });

                // Reset pilihan rombel jika rombel aktif bukan milik prodi baru
                if (currentRombel) {
                    var $selectedOpt = $selectRombel.find('option[value="' + currentRombel + '"]');
                    if ($selectedOpt.prop('disabled')) {
                        $selectRombel.val('');
                    }
                }
            }

            // Jalankan saat halaman load (ada filter prodi dari server)
            filterRombelByProdi($selectProdi.val());

            // Jalankan saat user mengubah pilihan prodi
            $selectProdi.on('change', function() {
                filterRombelByProdi($(this).val());
            });

            // ── Hitung jumlah filter aktif untuk badge di tombol Reset ───────
            (function countActiveFilters() {
                var count = 0;
                if ($('#inputSearch').val().trim()) count++;
                if ($('#selectProdi').val()) count++;
                if ($('#selectRombel').val()) count++;
                if ($('#selectSemester').val()) count++;
                if (count > 0) {
                    $('#filterCount').text(count + ' aktif');
                }
            })();

            {{--
                CATATAN PENTING:
                Handler untuk tombol Edit (.btn-open-edit-matkul) TIDAK didefinisikan
                di sini. Handler tersebut sudah ada secara lengkap di dalam
                matakuliah/partials/edit-matkul.blade.php dan membuka modal
                #modalEditMatkulGlobal dengan benar.

                Mendefinisikan handler kedua di sini sebelumnya menyebabkan dua bug:
                  1. Mencari $('#modalEditMatkul') — ID yang salah (seharusnya #modalEditMatkulGlobal)
                  2. Selalu return lebih awal karena modal tidak ditemukan
                  3. Konflik event listener sehingga handler asli tidak berjalan
            --}}
        });
    </script>
@endpush
