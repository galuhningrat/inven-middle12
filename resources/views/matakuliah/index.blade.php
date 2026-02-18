@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Kurikulum Mata Kuliah</h1>
                <span class="h-20px border-gray-200 border-start mx-4"></span>
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">Dashboard</li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-200 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-dark">Mata Kuliah</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-fluid">

            @include('master.notification')

            {{-- ===== SEARCH & FILTER BAR ===== --}}
            <div class="card mb-5 shadow-sm">
                <div class="card-body py-4">
                    <form method="GET" action="{{ route('matakuliah.index') }}" id="filterForm">
                        <div class="row g-3 align-items-end">
                            {{-- Search Input --}}
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold fs-7 mb-2">
                                    <i class="bi bi-search me-1"></i>Cari Mata Kuliah
                                </label>
                                <div class="input-group input-group-solid">
                                    <span class="input-group-text border-0 bg-light">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search"
                                        class="form-control form-control-solid border-0 ps-0"
                                        placeholder="Kode atau Nama Mata Kuliah..."
                                        value="{{ request('search') }}">
                                    @if(request('search'))
                                        <button type="button" class="btn btn-sm btn-icon btn-light-danger"
                                            onclick="clearSearch()" title="Hapus pencarian">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- Filter Prodi --}}
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label fw-semibold fs-7 mb-2">
                                    <i class="bi bi-building me-1"></i>Program Studi
                                </label>
                                <select name="filter_prodi" class="form-select form-select-solid"
                                    data-control="select2" data-placeholder="Semua Prodi" data-allow-clear="true">
                                    <option></option>
                                    @foreach($prodi as $p)
                                        <option value="{{ $p->id }}"
                                            {{ request('filter_prodi') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter Semester --}}
                            <div class="col-lg-2 col-md-6">
                                <label class="form-label fw-semibold fs-7 mb-2">
                                    <i class="bi bi-calendar3 me-1"></i>Semester
                                </label>
                                <select name="filter_semester" class="form-select form-select-solid"
                                    data-control="select2" data-placeholder="Semua Semester" data-allow-clear="true">
                                    <option></option>
                                    @for($s = 1; $s <= 14; $s++)
                                        <option value="{{ $s }}"
                                            {{ request('filter_semester') == $s ? 'selected' : '' }}>
                                            Semester {{ $s }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="col-lg-3 col-md-6">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="bi bi-funnel me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('matakuliah.index') }}" class="btn btn-light-secondary"
                                        title="Reset Filter">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </a>
                                    <a href="{{ route('matakuliah.all-data') }}" class="btn btn-light-info"
                                        title="Lihat Master Data MK">
                                        <i class="bi bi-list-ul"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Active Filters --}}
                        @if(request('search') || request('filter_prodi') || request('filter_semester'))
                            <div class="mt-4 pt-3 border-top border-gray-300">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="text-muted fs-8 fw-semibold">Filter Aktif:</span>
                                    @if(request('search'))
                                        <span class="badge badge-light-primary fs-8">
                                            <i class="bi bi-search me-1"></i>"{{ request('search') }}"
                                        </span>
                                    @endif
                                    @if(request('filter_prodi'))
                                        @php $sp = $prodi->firstWhere('id', request('filter_prodi')); @endphp
                                        <span class="badge badge-light-info fs-8">
                                            <i class="bi bi-building me-1"></i>{{ $sp->nama_prodi ?? 'Prodi' }}
                                        </span>
                                    @endif
                                    @if(request('filter_semester'))
                                        <span class="badge badge-light-success fs-8">
                                            <i class="bi bi-calendar3 me-1"></i>Semester {{ request('filter_semester') }}
                                        </span>
                                    @endif
                                    <span class="text-muted fs-9">
                                        ({{ $allMappings->count() }} mapping, {{ $totalUniqueMatkul }} MK unik)
                                    </span>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            {{-- ===== RINGKASAN STATISTIK ===== --}}
            <div class="row g-5 mb-7">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body py-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
                                {{-- Total MK Unik --}}
                                <div class="d-flex align-items-center gap-3">
                                    <div class="symbol symbol-45px symbol-circle bg-light-primary">
                                        <span class="symbol-label">
                                            <i class="bi bi-book-fill text-primary fs-4"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-gray-500 fs-8 fw-semibold text-uppercase ls-1">MK Unik</div>
                                        <div class="text-dark fw-bold fs-4">{{ $totalUniqueMatkul }}</div>
                                    </div>
                                </div>
                                <div class="separator separator-dashed d-none d-md-block" style="width:1px;height:40px;"></div>

                                {{-- Per Prodi Stats --}}
                                @foreach($prodi as $loop_prodi)
                                    @php
                                        $colors = ['primary', 'info', 'success', 'warning', 'danger'];
                                        $c = $colors[$loop->index % count($colors)];
                                    @endphp
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="symbol symbol-45px symbol-circle bg-light-{{ $c }}">
                                            <span class="symbol-label fw-bold text-{{ $c }} fs-7">
                                                {{ strtoupper(substr($loop_prodi->kode_prodi, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-gray-500 fs-8 fw-semibold text-uppercase ls-1">
                                                {{ $loop_prodi->kode_prodi }}
                                            </div>
                                            <div class="text-dark fw-bold fs-5">
                                                {{ $statsByProdi[$loop_prodi->id]['total'] ?? 0 }} MK
                                                <span class="text-muted fs-8 fw-normal">
                                                    / {{ $statsByProdi[$loop_prodi->id]['total_sks'] ?? 0 }} SKS
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Tombol Tambah --}}
                                <div class="ms-auto">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalTambahMatkul">
                                        <i class="bi bi-plus-circle me-2"></i>Tambah Mata Kuliah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== TAB NAVIGASI PER PRODI ===== --}}
            <div class="card">
                <div class="card-header border-0 pt-6 pb-0">
                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold"
                        id="prodiTabs" role="tablist">
                        @foreach($prodi as $loop_prodi)
                            @php
                                $colors    = ['primary', 'info', 'success', 'warning', 'danger'];
                                $tabColor  = $colors[$loop->index % count($colors)];
                                $isFirst   = $loop->first;
                            @endphp
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-active-{{ $tabColor }} pb-4 {{ $isFirst ? 'active' : '' }}"
                                    data-bs-toggle="tab"
                                    href="#tab_prodi_{{ $loop_prodi->id }}"
                                    role="tab">
                                    <span class="badge badge-circle badge-light-{{ $tabColor }} me-2 fs-8">
                                        {{ $statsByProdi[$loop_prodi->id]['total'] ?? 0 }}
                                    </span>
                                    {{ $loop_prodi->nama_prodi }}
                                    <span class="text-muted fs-8 ms-1">({{ $loop_prodi->kode_prodi }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="card-body pt-5">
                    <div class="tab-content" id="prodiTabsContent">

                        @foreach($prodi as $loop_prodi)
                            @php $isFirst = $loop->first; @endphp
                            <div class="tab-pane fade {{ $isFirst ? 'show active' : '' }}"
                                id="tab_prodi_{{ $loop_prodi->id }}" role="tabpanel">

                                @if(empty($matkulByProdiSemester[$loop_prodi->id]) || $matkulByProdiSemester[$loop_prodi->id]->isEmpty())
                                    <div class="text-center py-10">
                                        <i class="bi bi-journal-x fs-2x text-gray-300 mb-4 d-block"></i>
                                        <p class="text-muted fw-semibold">
                                            Belum ada mata kuliah untuk <strong>{{ $loop_prodi->nama_prodi }}</strong>.
                                        </p>
                                        <button type="button" class="btn btn-sm btn-light-primary mt-2"
                                            data-bs-toggle="modal" data-bs-target="#modalTambahMatkul">
                                            <i class="bi bi-plus me-1"></i> Tambah Sekarang
                                        </button>
                                    </div>
                                @else
                                    {{-- Ringkasan SKS prodi --}}
                                    <div class="d-flex align-items-center gap-4 mb-6 p-4 bg-light rounded-2">
                                        <div class="text-center px-4 border-end">
                                            <div class="fs-2 fw-bold text-dark">{{ $statsByProdi[$loop_prodi->id]['total'] ?? 0 }}</div>
                                            <div class="text-muted fs-8 text-uppercase fw-semibold">Total MK</div>
                                        </div>
                                        <div class="text-center px-4 border-end">
                                            <div class="fs-2 fw-bold text-primary">{{ $statsByProdi[$loop_prodi->id]['total_sks'] ?? 0 }}</div>
                                            <div class="text-muted fs-8 text-uppercase fw-semibold">Total SKS</div>
                                        </div>
                                        <div class="text-center px-4">
                                            <div class="fs-2 fw-bold text-success">{{ $matkulByProdiSemester[$loop_prodi->id]->count() }}</div>
                                            <div class="text-muted fs-8 text-uppercase fw-semibold">Semester</div>
                                        </div>
                                    </div>

                                    {{-- ===== ACCORDION PER SEMESTER ===== --}}
                                    <div class="accordion accordion-icon-toggle"
                                        id="accordion_prodi_{{ $loop_prodi->id }}">

                                        @foreach($matkulByProdiSemester[$loop_prodi->id] as $semester => $matkulList)
                                            @php
                                                $accordionId = 'smt_' . $loop_prodi->id . '_' . $semester;
                                                $collapseId  = 'collapse_' . $loop_prodi->id . '_' . $semester;

                                                // Hitung SKS & jenis dari mapping list
                                                $totalSksSmt = $matkulList->sum(fn($mp) => $mp->matkul?->bobot ?? 0);
                                                $wajib   = $matkulList->filter(fn($mp) => $mp->matkul?->jenis === 'wajib')->count();
                                                $pilihan = $matkulList->filter(fn($mp) => $mp->matkul?->jenis === 'pilihan')->count();
                                                $umum    = $matkulList->filter(fn($mp) => $mp->matkul?->jenis === 'umum')->count();

                                                $smtColors = ['primary','success','info','warning','danger','primary','success','info'];
                                                $smtColor  = $smtColors[($semester - 1) % count($smtColors)];
                                            @endphp

                                            <div class="accordion-item border border-dashed border-gray-300 mb-4 rounded-2">

                                                {{-- Header Semester --}}
                                                <div class="accordion-header py-3 px-5 d-flex align-items-center"
                                                    id="{{ $accordionId }}">
                                                    <button
                                                        class="accordion-button fw-semibold fs-6 collapsed text-dark bg-transparent border-0 p-0 w-100 text-start"
                                                        type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#{{ $collapseId }}"
                                                        aria-expanded="false"
                                                        aria-controls="{{ $collapseId }}">

                                                        <div class="d-flex align-items-center gap-3 flex-grow-1">
                                                            <span class="badge badge-circle badge-{{ $smtColor }} fs-7 w-35px h-35px d-flex align-items-center justify-content-center">
                                                                {{ $semester }}
                                                            </span>
                                                            <div>
                                                                <span class="fw-bolder text-dark">Semester {{ $semester }}</span>
                                                                <span class="d-block text-muted fs-8">
                                                                    {{ $matkulList->count() }} Mata Kuliah &bull; {{ $totalSksSmt }} SKS
                                                                </span>
                                                            </div>
                                                        </div>

                                                        {{-- Komposisi jenis --}}
                                                        <div class="d-flex gap-2 me-4">
                                                            @if($wajib)
                                                                <span class="badge badge-light-primary fs-9">{{ $wajib }} Wajib</span>
                                                            @endif
                                                            @if($pilihan)
                                                                <span class="badge badge-light-warning fs-9">{{ $pilihan }} Pilihan</span>
                                                            @endif
                                                            @if($umum)
                                                                <span class="badge badge-light-info fs-9">{{ $umum }} Umum</span>
                                                            @endif
                                                        </div>
                                                    </button>
                                                </div>

                                                {{-- Body: Tabel Mata Kuliah --}}
                                                <div id="{{ $collapseId }}"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="{{ $accordionId }}"
                                                    data-bs-parent="#accordion_prodi_{{ $loop_prodi->id }}">
                                                    <div class="accordion-body p-0">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover table-row-bordered align-middle fs-7 mb-0">
                                                                <thead class="bg-gray-50">
                                                                    <tr class="fw-bold text-muted text-uppercase fs-8">
                                                                        <th class="ps-5 py-3 w-40px">#</th>
                                                                        <th class="py-3 min-w-80px">Kode MK</th>
                                                                        <th class="py-3 min-w-200px">Nama Mata Kuliah</th>
                                                                        <th class="py-3 text-center min-w-60px">SKS</th>
                                                                        <th class="py-3 text-center min-w-80px">Jenis</th>
                                                                        <th class="py-3 min-w-180px">Dosen Pengampu</th>
                                                                        <th class="py-3 text-center min-w-120px pe-5">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    {{--
                                                                        $matkulList adalah Collection<MatkulProdiSemester>.
                                                                        Kita set $m = $mapping->matkul agar semua include
                                                                        modal yang memakai variabel $m tetap kompatibel.
                                                                    --}}
                                                                    @foreach($matkulList as $mapping)
                                                                        @php $m = $mapping->matkul; @endphp
                                                                        @if(!$m) @continue @endif

                                                                        <tr>
                                                                            <td class="ps-5 text-muted">{{ $loop->iteration }}</td>
                                                                            <td>
                                                                                <span class="badge badge-light-dark fw-bold fs-8">
                                                                                    {{ $m->kode_mk }}
                                                                                </span>
                                                                            </td>
                                                                            <td class="fw-semibold text-dark">{{ $m->nama_mk }}</td>
                                                                            <td class="text-center">
                                                                                <span class="badge badge-circle badge-light-primary">
                                                                                    {{ $m->bobot }}
                                                                                </span>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                @php
                                                                                    $jenisMap = [
                                                                                        'wajib'   => ['label' => 'Wajib',   'class' => 'badge-light-primary'],
                                                                                        'pilihan' => ['label' => 'Pilihan', 'class' => 'badge-light-warning'],
                                                                                        'umum'    => ['label' => 'Umum',    'class' => 'badge-light-info'],
                                                                                    ];
                                                                                    $jenis = $jenisMap[$m->jenis] ?? ['label' => ucfirst($m->jenis), 'class' => 'badge-light-secondary'];
                                                                                @endphp
                                                                                <span class="badge {{ $jenis['class'] }} fs-9">
                                                                                    {{ $jenis['label'] }}
                                                                                </span>
                                                                            </td>
                                                                            <td>
                                                                                <div class="d-flex align-items-center gap-2">
                                                                                    <div class="symbol symbol-30px symbol-circle bg-light-primary">
                                                                                        <span class="symbol-label fw-bold text-primary fs-8">
                                                                                            {{ strtoupper(substr($m->dosen->user->nama ?? '-', 0, 1)) }}
                                                                                        </span>
                                                                                    </div>
                                                                                    <span class="text-gray-700 fw-semibold">
                                                                                        {{ $m->dosen->user->nama ?? '-' }}
                                                                                    </span>
                                                                                </div>
                                                                            </td>
                                                                            <td class="text-center pe-5">
                                                                                <button type="button"
                                                                                    class="btn btn-icon btn-sm btn-light-primary me-1"
                                                                                    title="Detail"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#modalDetailMatkul{{ $m->id }}">
                                                                                    <i class="bi bi-eye-fill fs-6"></i>
                                                                                </button>
                                                                                <button type="button"
                                                                                    class="btn btn-icon btn-sm btn-light-success me-1"
                                                                                    title="Edit"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#modalEditMatkul{{ $m->id }}">
                                                                                    <i class="bi bi-pencil-fill fs-6"></i>
                                                                                </button>
                                                                                <button type="button"
                                                                                    class="btn btn-icon btn-sm btn-light-danger"
                                                                                    title="Hapus"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#modalDeleteMatkul{{ $m->id }}">
                                                                                    <i class="bi bi-trash-fill fs-6"></i>
                                                                                </button>
                                                                            </td>
                                                                        </tr>

                                                                        {{-- Include modals per baris --}}
                                                                        @include('matakuliah.detail-matkul', ['m' => $m])
                                                                        @include('matakuliah.edit-matkul', ['m' => $m, 'prodi' => $prodi, 'dosen' => $dosen])
                                                                        @include('matakuliah.delete-matkul', ['m' => $m])
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot class="bg-gray-50 border-top border-dashed">
                                                                    <tr>
                                                                        <td colspan="3" class="ps-5 py-2 text-muted fs-8 fw-semibold">
                                                                            Total Semester {{ $semester }}
                                                                        </td>
                                                                        <td class="text-center py-2">
                                                                            <span class="fw-bolder text-primary">{{ $totalSksSmt }}</span>
                                                                            <span class="text-muted fs-9"> SKS</span>
                                                                        </td>
                                                                        <td colspan="3"></td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Modal Tambah (satu, di luar loop agar tidak duplikat) --}}
            @include('matakuliah.create-matkul', ['prodi' => $prodi, 'dosen' => $dosen])

        </div>
    </div>
@endsection

@push('scripts')
<script>
    function clearSearch() {
        document.querySelector('input[name="search"]').value = '';
        document.getElementById('filterForm').submit();
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Auto-buka accordion semester pertama pada tab aktif
        const activeTab = document.querySelector('.nav-link.active');
        if (activeTab) {
            const targetPane = document.querySelector(activeTab.getAttribute('href'));
            if (targetPane) {
                const firstCollapse = targetPane.querySelector('.accordion-collapse');
                if (firstCollapse) {
                    new bootstrap.Collapse(firstCollapse, { toggle: true });
                }
            }
        }

        // Buka accordion pertama saat tab baru aktif
        document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function (tabEl) {
            tabEl.addEventListener('shown.bs.tab', function (e) {
                const targetPane = document.querySelector(e.target.getAttribute('href'));
                if (targetPane) {
                    const firstCollapse = targetPane.querySelector('.accordion-collapse');
                    if (firstCollapse && !firstCollapse.classList.contains('show')) {
                        new bootstrap.Collapse(firstCollapse, { toggle: true });
                    }
                }
            });
        });

        // Auto-submit filter saat select berubah
        document.querySelectorAll('select[name="filter_prodi"], select[name="filter_semester"]')
            .forEach(function (select) {
                select.addEventListener('change', function () {
                    document.getElementById('filterForm').submit();
                });
            });

        // ===== HANDLER MAPPING EDIT (dinamis, berlaku untuk semua modal edit) =====
        // Menggunakan event delegation agar bekerja untuk semua modal yang di-generate via loop
        const prodiOptionsHtml = `
            @foreach($prodi as $p)
            <option value="{{ $p->id }}">{{ $p->nama_prodi }} ({{ $p->kode_prodi }})</option>
            @endforeach
        `;
        const semesterOptionsHtml = `
            @for($s = 1; $s <= 14; $s++)
            <option value="{{ $s }}">Semester {{ $s }}</option>
            @endfor
        `;

        // Tambah baris mapping pada modal edit
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-tambah-mapping-edit');
            if (!btn) return;

            const containerId  = btn.dataset.target;
            const removeClass  = btn.dataset.removeClass;
            const container    = document.getElementById(containerId);
            if (!container) return;

            const currentRows  = container.querySelectorAll('.mapping-row');
            const newIdx       = currentRows.length;

            const row = document.createElement('div');
            row.className = 'mapping-row d-flex align-items-center gap-2 mb-2';
            row.innerHTML = `
                <div class="flex-grow-1">
                    <select name="mappings[${newIdx}][prodi_id]"
                        class="form-select form-select-solid form-select-sm" required>
                        <option value="">-- Pilih Prodi --</option>
                        ${prodiOptionsHtml}
                    </select>
                </div>
                <div style="width:170px; flex-shrink:0">
                    <select name="mappings[${newIdx}][semester]"
                        class="form-select form-select-solid form-select-sm" required>
                        <option value="">-- Semester --</option>
                        ${semesterOptionsHtml}
                    </select>
                </div>
                <div style="width:36px; flex-shrink:0">
                    <button type="button"
                        class="btn btn-icon btn-sm btn-light ${removeClass}"
                        title="Hapus baris">
                        <i class="bi bi-trash text-danger"></i>
                    </button>
                </div>
            `;
            container.appendChild(row);
            refreshRemoveButtons(containerId, removeClass);
        });

        // Hapus baris mapping pada modal edit (delegasi dari document)
        document.addEventListener('click', function (e) {
            // Cek apakah tombol yang diklik adalah tombol hapus mapping edit
            const btn = e.target.closest('[class*="remove-mapping-edit-"]');
            if (!btn) return;

            const row = btn.closest('.mapping-row');
            if (!row) return;

            const container = row.closest('[id^="mapping-rows-edit-"]');
            if (!container) return;

            if (container.querySelectorAll('.mapping-row').length > 1) {
                row.remove();
                // Re-index nama input agar berurutan
                reindexMappingRows(container);
                // Tentukan class dari container id
                const matkulId = container.id.replace('mapping-rows-edit-', '');
                refreshRemoveButtons(container.id, 'remove-mapping-edit-' + matkulId);
            }
        });

        function reindexMappingRows(container) {
            container.querySelectorAll('.mapping-row').forEach(function (row, idx) {
                row.querySelectorAll('[name]').forEach(function (input) {
                    input.name = input.name.replace(/mappings\[\d+\]/, `mappings[${idx}]`);
                });
            });
        }

        function refreshRemoveButtons(containerId, btnClass) {
            const container = document.getElementById(containerId);
            if (!container) return;
            const rows = container.querySelectorAll('.mapping-row');
            container.querySelectorAll('.' + btnClass).forEach(btn => {
                btn.disabled = rows.length <= 1;
            });
        }
    });
</script>
@endpush
