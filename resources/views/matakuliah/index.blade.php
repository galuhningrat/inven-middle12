@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Kurikulum Mata Kuliah</h1>
                <span class="h-20px border-gray-200 border-start mx-4"></span>
            </div>
            <div class="d-flex align-items-center py-1">
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="/dashboard" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-200 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-dark">Kurikulum Mata Kuliah</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    {{--
        $allRenderedMks — mutable Collection yang diisi secara bertahap saat
        setiap baris tabel di-render. Dipakai untuk memastikan modal Detail/
        Hapus hanya di-render SEKALI per MK unik, di luar semua tag <table>
        agar tidak melanggar HTML spec (no <div> di dalam <tbody>).
    --}}
    @php $allRenderedMks = collect(); @endphp

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
                                <i class="bi bi-people-fill fs-2 text-warning"></i>
                            </div>
                            <div>
                                <div class="fs-2hx fw-bold text-gray-800 lh-1">{{ $allRombel->count() }}</div>
                                <div class="fs-7 fw-semibold text-gray-500">Rombel / Angkatan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================================================================ --}}
            {{-- FILTER & ACTION BAR                                               --}}
            {{-- ================================================================ --}}
            <div class="card mb-5">
                <div class="card-body py-4">
                    <form method="GET" action="{{ route('matakuliah.index') }}"
                        class="d-flex flex-wrap gap-3 align-items-end">

                        {{-- Cari MK --}}
                        <div style="min-width:200px;max-width:300px;flex-grow:1">
                            <label class="form-label fw-semibold fs-7 mb-1">Cari Mata Kuliah</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search text-gray-500"></i>
                                </span>
                                <input type="text" name="search" class="form-control" placeholder="Kode / Nama MK..."
                                    value="{{ request('search') }}">
                            </div>
                        </div>

                        {{-- Filter Prodi --}}
                        <div style="min-width:170px">
                            <label class="form-label fw-semibold fs-7 mb-1">Program Studi</label>
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

                        {{-- Filter Rombel --}}
                        <div style="min-width:180px">
                            <label class="form-label fw-semibold fs-7 mb-1">Rombel / Angkatan</label>
                            <select name="filter_rombel" class="form-select form-select-sm">
                                <option value="">Semua Rombel</option>
                                @foreach ($allRombel as $r)
                                    <option value="{{ $r->id }}"
                                        {{ request('filter_rombel') == $r->id ? 'selected' : '' }}>
                                        {{ $r->nama_rombel }}
                                        @if ($r->tahunMasuk)
                                            ({{ $r->tahunMasuk->tahun_awal }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Semester --}}
                        <div style="min-width:150px">
                            <label class="form-label fw-semibold fs-7 mb-1">Semester</label>
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

                        {{-- Tombol Filter --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-funnel me-1"></i>Filter
                            </button>
                            <a href="{{ route('matakuliah.index') }}" class="btn btn-sm btn-light">
                                <i class="bi bi-x-lg me-1"></i>Reset
                            </a>
                        </div>

                        {{-- Aksi kanan --}}
                        <div class="ms-auto d-flex gap-2 align-items-center">
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
                </div>
            </div>

            {{-- ================================================================ --}}
            {{-- HIERARKI 5 LEVEL                                                  --}}
            {{-- ================================================================ --}}
            @if ($fakultas->isEmpty())
                <div class="card">
                    <div class="card-body text-center py-20">
                        <i class="bi bi-folder2-open fs-5tx text-gray-300 d-block mb-4"></i>
                        <div class="fs-4 fw-bold text-gray-500 mb-2">Belum ada data fakultas</div>
                        <p class="text-muted fs-7">Tambahkan Fakultas terlebih dahulu melalui menu Data Master.</p>
                    </div>
                </div>
            @else
                {{-- ┌──────────────────────────────────────────────────────────┐ --}}
                {{-- │  LEVEL 1 — FAKULTAS : Nav-Tabs horizontal                │ --}}
                {{-- └──────────────────────────────────────────────────────────┘ --}}
                <div class="card">
                    <div class="card-header border-0 pt-5 pb-0">
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-6 fw-bold" id="tabFakultas"
                            role="tablist">
                            @foreach ($fakultas as $fi => $fak)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link text-active-primary pb-4 {{ $fi === 0 ? 'active' : '' }}"
                                        id="tab-fak-{{ $fak->id }}" data-bs-toggle="tab"
                                        data-bs-target="#pane-fak-{{ $fak->id }}" type="button" role="tab"
                                        aria-controls="pane-fak-{{ $fak->id }}"
                                        aria-selected="{{ $fi === 0 ? 'true' : 'false' }}">
                                        <i class="bi bi-building me-2"></i>
                                        {{ $fak->nama_fakultas }}
                                        <span class="badge badge-light-primary ms-2">
                                            {{ $fak->prodi->count() }} Prodi
                                        </span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>{{-- end card-header --}}

                    <div class="card-body pt-6">
                        <div class="tab-content" id="tabFakultasContent">

                            @foreach ($fakultas as $fi => $fak)
                                {{-- ── TAB PANE FAKULTAS ─────────────────────────────────── --}}
                                <div class="tab-pane fade {{ $fi === 0 ? 'show active' : '' }}"
                                    id="pane-fak-{{ $fak->id }}" role="tabpanel"
                                    aria-labelledby="tab-fak-{{ $fak->id }}">

                                    @if ($fak->prodi->isEmpty())
                                        <div class="text-center py-12 text-muted">
                                            <i class="bi bi-info-circle fs-3x text-gray-300 d-block mb-3"></i>
                                            <p class="fs-7 mb-0">Belum ada program studi di fakultas ini.</p>
                                        </div>
                                    @else
                                        {{-- ┌─────────────────────────────────────────────────┐ --}}
                                        {{-- │  LEVEL 2 — PRODI : Nav-Pills vertikal (sidebar) │ --}}
                                        {{-- └─────────────────────────────────────────────────┘ --}}
                                        <div class="d-flex gap-6 align-items-start">

                                            {{-- Sidebar Nav-Pills Prodi --}}
                                            <div class="flex-shrink-0" style="width:240px;min-width:200px">
                                                <div class="nav flex-column nav-pills gap-1"
                                                    id="pills-prodi-{{ $fak->id }}" role="tablist"
                                                    aria-orientation="vertical">

                                                    @foreach ($fak->prodi as $pi => $prodi)
                                                        <button
                                                            class="nav-link btn btn-flex btn-active-light-primary
                                                                   text-start px-4 py-3 {{ $pi === 0 ? 'active' : '' }}"
                                                            id="pill-prodi-{{ $prodi->id }}" data-bs-toggle="pill"
                                                            data-bs-target="#pane-prodi-{{ $prodi->id }}"
                                                            type="button" role="tab"
                                                            aria-controls="pane-prodi-{{ $prodi->id }}"
                                                            aria-selected="{{ $pi === 0 ? 'true' : 'false' }}">

                                                            <span
                                                                class="d-flex flex-column align-items-start w-100 overflow-hidden">
                                                                <span
                                                                    class="fw-bold fs-7 text-gray-800 text-truncate w-100">
                                                                    {{ $prodi->nama_prodi }}
                                                                </span>
                                                                <span class="fs-9 text-gray-500 mt-1">
                                                                    <span class="text-primary fw-bold">
                                                                        {{ $statsByProdi[$prodi->id]['total'] ?? 0 }}
                                                                    </span> MK &bull;
                                                                    <span class="text-success fw-bold">
                                                                        {{ $statsByProdi[$prodi->id]['total_sks'] ?? 0 }}
                                                                    </span> SKS &bull;
                                                                    <span class="text-warning fw-bold">
                                                                        {{ $prodi->rombel->count() }}
                                                                    </span> Rombel
                                                                </span>
                                                            </span>
                                                            <i
                                                                class="bi bi-chevron-right ms-auto text-gray-400 flex-shrink-0"></i>
                                                        </button>
                                                    @endforeach

                                                </div>
                                            </div>
                                            {{-- end sidebar Prodi --}}

                                            {{-- Konten Prodi --}}
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="tab-content" id="pills-prodi-content-{{ $fak->id }}">

                                                    @foreach ($fak->prodi as $pi => $prodi)
                                                        {{-- ── TAB PANE PRODI ──────────────────────────────── --}}
                                                        <div class="tab-pane fade {{ $pi === 0 ? 'show active' : '' }}"
                                                            id="pane-prodi-{{ $prodi->id }}" role="tabpanel"
                                                            aria-labelledby="pill-prodi-{{ $prodi->id }}">

                                                            {{-- Header Info Prodi --}}
                                                            <div
                                                                class="d-flex align-items-start mb-5 pb-4 border-bottom border-dashed">
                                                                <div class="flex-grow-1">
                                                                    <h4 class="fw-bold text-gray-800 mb-2">
                                                                        {{ $prodi->nama_prodi }}
                                                                    </h4>
                                                                    <div class="d-flex gap-2 flex-wrap">
                                                                        @if ($prodi->kode_prodi)
                                                                            <span class="badge badge-light-dark fs-8">
                                                                                <i
                                                                                    class="bi bi-hash me-1"></i>{{ $prodi->kode_prodi }}
                                                                            </span>
                                                                        @endif
                                                                        @if ($prodi->jenjang)
                                                                            <span class="badge badge-light-success fs-8">
                                                                                {{ strtoupper($prodi->jenjang) }}
                                                                            </span>
                                                                        @endif
                                                                        @if ($prodi->status_akre)
                                                                            <span class="badge badge-light-info fs-8">
                                                                                Akreditasi: {{ $prodi->status_akre }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                {{-- Stats ringkas --}}
                                                                <div class="d-flex gap-4 text-center ms-4 flex-shrink-0">
                                                                    <div>
                                                                        <div class="fs-1 fw-bolder text-primary lh-1">
                                                                            {{ $statsByProdi[$prodi->id]['total'] ?? 0 }}
                                                                        </div>
                                                                        <div class="fs-9 text-muted mt-1">MK</div>
                                                                    </div>
                                                                    <div class="border-start border-dashed"></div>
                                                                    <div>
                                                                        <div class="fs-1 fw-bolder text-success lh-1">
                                                                            {{ $statsByProdi[$prodi->id]['total_sks'] ?? 0 }}
                                                                        </div>
                                                                        <div class="fs-9 text-muted mt-1">SKS</div>
                                                                    </div>
                                                                    <div class="border-start border-dashed"></div>
                                                                    <div>
                                                                        <div class="fs-1 fw-bolder text-warning lh-1">
                                                                            {{ $prodi->rombel->count() }}
                                                                        </div>
                                                                        <div class="fs-9 text-muted mt-1">Rombel</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- end header Prodi --}}

                                                            {{-- ─────────────────────────────────────────────── --}}
                                                            {{-- Cek apakah Prodi memiliki Rombel               --}}
                                                            {{-- ─────────────────────────────────────────────── --}}
                                                            @if ($prodi->rombel->isEmpty())
                                                                {{--
                                                                    Prodi belum punya Rombel → tampilkan MK
                                                                    langsung per semester tanpa level Rombel.
                                                                    Ini adalah fallback agar kurikulum tetap
                                                                    bisa dilihat meski Rombel belum dibuat.
                                                                --}}
                                                                @php
                                                                    $bySemesterDirect = collect(
                                                                        $mkByProdi[$prodi->id] ?? [],
                                                                    )->sortKeys();
                                                                @endphp

                                                                @if ($bySemesterDirect->isEmpty())
                                                                    <div class="text-center py-10 text-muted">
                                                                        <i
                                                                            class="bi bi-file-earmark-x fs-3x text-gray-300 d-block mb-3"></i>
                                                                        <p class="fs-7 mb-1 fw-semibold">Belum ada
                                                                            kurikulum</p>
                                                                        <p class="fs-8 mb-0">
                                                                            Tambahkan mata kuliah dan petakan ke prodi ini,
                                                                            atau buat Rombel terlebih dahulu.
                                                                        </p>
                                                                    </div>
                                                                @else
                                                                    <div
                                                                        class="alert alert-light-warning border border-warning border-dashed d-flex align-items-center mb-5 py-3">
                                                                        <i
                                                                            class="bi bi-exclamation-triangle-fill text-warning me-3 fs-4 flex-shrink-0"></i>
                                                                        <span class="fs-7">
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
                                                                        ]
                                                                    )
                                                                @endif
                                                            @else
                                                                {{-- ┌────────────────────────────────────────────┐ --}}
                                                                {{-- │  LEVEL 3 — ROMBEL : Accordion luar         │ --}}
                                                                {{-- └────────────────────────────────────────────┘ --}}
                                                                <div class="accordion accordion-icon-collapse"
                                                                    id="acc-rombel-prodi-{{ $prodi->id }}">

                                                                    @foreach ($prodi->rombel as $ri => $rombel)
                                                                        @php
                                                                            /*
                                                                             * MK di kurikulum terikat ke Prodi+Semester,
                                                                             * BUKAN ke Rombel secara langsung.
                                                                             * Semua Rombel dalam Prodi yang sama
                                                                             * berbagi kurikulum MK yang sama.
                                                                             * Grouping per semester diambil dari $mkByProdi.
                                                                             */
                                                                            $bySemester = collect(
                                                                                $mkByProdi[$prodi->id] ?? [],
                                                                            )->sortKeys();

                                                                            $rombelPaneId =
                                                                                'pane-rombel-' . $rombel->id;

                                                                            // Badge summary untuk header Rombel
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

                                                                            // Label angkatan dari TahunAkademik
                                                                            $tahunAwal =
                                                                                $rombel->tahunMasuk?->tahun_awal;
                                                                            $angkatanLabel = $tahunAwal
                                                                                ? 'Angkatan ' . $tahunAwal
                                                                                : 'Angkatan —';
                                                                        @endphp

                                                                        {{-- ── ACCORDION ITEM ROMBEL ─────────────────────── --}}
                                                                        <div
                                                                            class="accordion-item mb-3 border rounded-2 shadow-sm">

                                                                            <h2 class="accordion-header"
                                                                                id="hd-{{ $rombelPaneId }}">
                                                                                <button
                                                                                    class="accordion-button {{ $ri !== 0 ? 'collapsed' : '' }}
                                                                                           fw-semibold py-4 px-5
                                                                                           rounded-2 bg-light-primary"
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#{{ $rombelPaneId }}"
                                                                                    aria-expanded="{{ $ri === 0 ? 'true' : 'false' }}"
                                                                                    aria-controls="{{ $rombelPaneId }}">

                                                                                    <div
                                                                                        class="d-flex align-items-center w-100 me-3 gap-3">

                                                                                        {{-- Avatar Rombel --}}
                                                                                        <div
                                                                                            class="d-flex flex-center w-40px h-40px
                                                                                                    rounded-circle bg-primary flex-shrink-0">
                                                                                            <i
                                                                                                class="bi bi-people-fill text-white fs-6"></i>
                                                                                        </div>

                                                                                        {{-- Info Rombel --}}
                                                                                        <div class="flex-grow-1 min-w-0">
                                                                                            <div
                                                                                                class="fw-bold text-gray-800 fs-6 d-flex align-items-center gap-2">
                                                                                                {{ $rombel->nama_rombel }}
                                                                                                <span
                                                                                                    class="badge badge-light-dark fw-normal fs-9 font-monospace">
                                                                                                    {{ $rombel->kode_rombel }}
                                                                                                </span>
                                                                                            </div>
                                                                                            <div class="d-flex gap-2 mt-1">
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

                                                                                        {{-- Badge Summary --}}
                                                                                        <div
                                                                                            class="d-flex gap-2 ms-auto flex-shrink-0">
                                                                                            <span
                                                                                                class="badge badge-light-primary fs-8">
                                                                                                <i
                                                                                                    class="bi bi-layers me-1"></i>
                                                                                                {{ $rombelTotalSem }}
                                                                                                Semester
                                                                                            </span>
                                                                                            <span
                                                                                                class="badge badge-light-success fs-8">
                                                                                                <i
                                                                                                    class="bi bi-book me-1"></i>
                                                                                                {{ $rombelTotalMk }} MK
                                                                                            </span>
                                                                                            <span
                                                                                                class="badge badge-light-info fs-8">
                                                                                                {{ $rombelTotalSks }} SKS
                                                                                            </span>
                                                                                        </div>

                                                                                    </div>
                                                                                </button>
                                                                            </h2>

                                                                            {{-- ── BODY ROMBEL ────────────────────────────── --}}
                                                                            <div id="{{ $rombelPaneId }}"
                                                                                class="accordion-collapse collapse
                                                                                        {{ $ri === 0 ? 'show' : '' }}"
                                                                                aria-labelledby="hd-{{ $rombelPaneId }}"
                                                                                data-bs-parent="#acc-rombel-prodi-{{ $prodi->id }}">

                                                                                <div class="accordion-body px-5 py-4">
                                                                                    @if ($bySemester->isEmpty())
                                                                                        <div
                                                                                            class="text-center py-8 text-muted">
                                                                                            <i
                                                                                                class="bi bi-inbox fs-2x text-gray-300 d-block mb-2"></i>
                                                                                            <p class="fs-7 mb-0">
                                                                                                Belum ada mata kuliah yang
                                                                                                dipetakan
                                                                                                ke Prodi
                                                                                                <strong>{{ $prodi->nama_prodi }}</strong>.
                                                                                            </p>
                                                                                        </div>
                                                                                    @else
                                                                                        {{-- ┌──────────────────────────────────────┐ --}}
                                                                                        {{-- │  LEVEL 4 — SEMESTER : Accordion dlm  │ --}}
                                                                                        {{-- │  LEVEL 5 — MK : Tabel di tiap sem.   │ --}}
                                                                                        {{-- └──────────────────────────────────────┘ --}}
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
                                                                            {{-- end accordion-collapse Rombel --}}

                                                                        </div>
                                                                        {{-- end accordion-item Rombel --}}
                                                                    @endforeach

                                                                </div>
                                                                {{-- end accordion Rombel --}}
                                                            @endif
                                                            {{-- end rombel isEmpty check --}}

                                                        </div>
                                                        {{-- end tab-pane Prodi --}}
                                                    @endforeach

                                                </div>
                                                {{-- end tab-content Prodi --}}
                                            </div>
                                            {{-- end flex-grow Prodi konten --}}

                                        </div>
                                        {{-- end d-flex Prodi --}}
                                    @endif
                                    {{-- end prodi isEmpty --}}

                                </div>
                                {{-- end tab-pane Fakultas --}}
                            @endforeach

                        </div>
                        {{-- end tab-content Fakultas --}}
                    </div>
                    {{-- end card-body --}}
                </div>
                {{-- end card --}}
            @endif
            {{-- end fakultas isEmpty --}}

        </div>
        {{-- end container --}}
    </div>
    {{-- end post --}}

    {{-- ================================================================ --}}
    {{-- MODALS                                                            --}}
    {{-- WAJIB di luar semua <div class="card"> dan struktur tabel!       --}}
    {{-- Di-render SEKALI per MK unik via $allRenderedMks (dedup).        --}}
    {{-- ================================================================ --}}
    @foreach ($allRenderedMks as $mk)
        @include('matakuliah.partials.detail-matkul', ['m' => $mk])
        @include('matakuliah.partials.delete-matkul', ['m' => $mk])
    @endforeach

    @can('kurikulum-create')
        @include('matakuliah.partials.create-matkul', [
            'dosen' => $dosen,
            'prodi' => $allProdi,
        ])
    @endcan

    @can('kurikulum-update')
        @include('matakuliah.partials.edit-matkul', [
            'dosen' => $dosen,
            'prodi' => $allProdi,
        ])
    @endcan
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Aktifkan tooltips Bootstrap
            $('[title]').tooltip({
                trigger: 'hover'
            });

            // ── Edit MK via AJAX ──────────────────────────────────────────
            $(document).on('click', '.btn-open-edit-matkul', function() {
                const mkId = $(this).data('id');
                const url = '{{ route('matakuliah.edit-data', ':id') }}'.replace(':id', mkId);
                const $modal = $('#modalEditMatkul');

                if (!$modal.length) return;

                $.get(url, function(data) {
                    $modal.find('#edit_mk_id').val(data.id);
                    $modal.find('#edit_kode_mk').val(data.kode_mk);
                    $modal.find('#edit_nama_mk').val(data.nama_mk);
                    $modal.find('#edit_bobot').val(data.bobot);
                    $modal.find('#edit_jenis').val(data.jenis).trigger('change');
                    $modal.find('#edit_id_dosen').val(data.id_dosen).trigger('change');

                    // Refresh mapping rows (jika ada dynamic mapping UI)
                    if (typeof populateEditMappings === 'function') {
                        populateEditMappings(data.mappings);
                    }

                    $modal.modal('show');
                }).fail(function() {
                    alert('Gagal memuat data mata kuliah. Silakan coba lagi.');
                });
            });
        });
    </script>
@endpush
