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

        {{-- ===== RINGKASAN STATISTIK ===== --}}
        <div class="row g-5 mb-7">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body py-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
                            {{-- Total Semua MK --}}
                            <div class="d-flex align-items-center gap-3">
                                <div class="symbol symbol-45px symbol-circle bg-light-primary">
                                    <span class="symbol-label">
                                        <i class="bi bi-book-fill text-primary fs-4"></i>
                                    </span>
                                </div>
                                <div>
                                    <div class="text-gray-500 fs-8 fw-semibold text-uppercase ls-1">Total Mata Kuliah</div>
                                    <div class="text-dark fw-bold fs-4">{{ $matakuliah->count() }}</div>
                                </div>
                            </div>
                            <div class="separator separator-dashed d-none d-md-block" style="width:1px;height:40px;"></div>
                            {{-- Per Prodi Stats --}}
                            @foreach($prodi as $p)
                            <div class="d-flex align-items-center gap-3">
                                <div class="symbol symbol-45px symbol-circle bg-light-{{ $loop->index == 0 ? 'info' : ($loop->index == 1 ? 'success' : 'warning') }}">
                                    <span class="symbol-label fw-bold text-{{ $loop->index == 0 ? 'info' : ($loop->index == 1 ? 'success' : 'warning') }} fs-7">
                                        {{ strtoupper(substr($p->kode_prodi, 0, 2)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-gray-500 fs-8 fw-semibold text-uppercase ls-1">{{ $p->kode_prodi }}</div>
                                    <div class="text-dark fw-bold fs-5">
                                        {{ $statsByProdi[$p->id]['total'] ?? 0 }} MK
                                        <span class="text-muted fs-8 fw-normal">/ {{ $statsByProdi[$p->id]['total_sks'] ?? 0 }} SKS</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            {{-- Tombol Tambah --}}
                            <div class="ms-auto">
                                <button type="button" class="btn btn-primary"
                                    data-bs-toggle="modal" data-bs-target="#modalTambahMatkul">
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
                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold" id="prodiTabs" role="tablist">
                    @foreach($prodi as $loop_prodi)
                    @php
                        $colors = ['primary', 'info', 'success', 'warning', 'danger'];
                        $tabColor = $colors[$loop->index % count($colors)];
                        $isFirst  = $loop->first;
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

                    {{-- ===== ISI SETIAP TAB PRODI ===== --}}
                    @foreach($prodi as $loop_prodi)
                    @php $isFirst = $loop->first; @endphp
                    <div class="tab-pane fade {{ $isFirst ? 'show active' : '' }}"
                         id="tab_prodi_{{ $loop_prodi->id }}"
                         role="tabpanel">

                        @if(empty($matkulByProdiSemester[$loop_prodi->id]))
                            {{-- State kosong --}}
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
                            {{-- Ringkasan SKS prodi ini --}}
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
                                    <div class="fs-2 fw-bold text-success">{{ count($matkulByProdiSemester[$loop_prodi->id]) }}</div>
                                    <div class="text-muted fs-8 text-uppercase fw-semibold">Semester</div>
                                </div>
                            </div>

                            {{-- ===== ACCORDION PER SEMESTER ===== --}}
                            <div class="accordion accordion-icon-toggle" id="accordion_prodi_{{ $loop_prodi->id }}">

                                @foreach($matkulByProdiSemester[$loop_prodi->id] as $semester => $matkulList)
                                @php
                                    $accordionId  = 'smt_' . $loop_prodi->id . '_' . $semester;
                                    $collapseId   = 'collapse_' . $loop_prodi->id . '_' . $semester;
                                    $totalSksSmt  = $matkulList->sum('bobot');
                                    $smtColors    = ['primary','success','info','warning','danger','primary','success','info'];
                                    $smtColor     = $smtColors[($semester - 1) % count($smtColors)];
                                @endphp

                                <div class="accordion-item border border-dashed border-gray-300 mb-4 rounded-2">

                                    {{-- Header Semester --}}
                                    <div class="accordion-header py-3 px-5 d-flex align-items-center"
                                         id="{{ $accordionId }}">
                                        <button class="accordion-button fw-semibold fs-6 collapsed text-dark bg-transparent border-0 p-0 w-100 text-start"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#{{ $collapseId }}"
                                            aria-expanded="false"
                                            aria-controls="{{ $collapseId }}">

                                            <div class="d-flex align-items-center gap-3 flex-grow-1">
                                                {{-- Badge Semester --}}
                                                <span class="badge badge-circle badge-{{ $smtColor }} fs-7 w-35px h-35px d-flex align-items-center justify-content-center">
                                                    {{ $semester }}
                                                </span>
                                                <div>
                                                    <span class="fw-bolder text-dark">Semester {{ $semester }}</span>
                                                    <span class="d-block text-muted fs-8">
                                                        {{ $matkulList->count() }} Mata Kuliah
                                                        &bull;
                                                        {{ $totalSksSmt }} SKS
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Badge komposisi jenis MK --}}
                                            <div class="d-flex gap-2 me-4">
                                                @php
                                                    $wajib   = $matkulList->where('jenis', 'wajib')->count();
                                                    $pilihan = $matkulList->where('jenis', 'pilihan')->count();
                                                    $umum    = $matkulList->where('jenis', 'umum')->count();
                                                @endphp
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
                                                        @foreach($matkulList as $m)
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
                                                                <span class="badge {{ $jenis['class'] }} fs-9">{{ $jenis['label'] }}</span>
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
                                                        @include('matakuliah.edit-matkul',   ['m' => $m, 'prodi' => $prodi, 'dosen' => $dosen])
                                                        @include('matakuliah.delete-matkul', ['m' => $m])

                                                        @endforeach
                                                    </tbody>
                                                    {{-- Footer: Total SKS semester ini --}}
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
                            {{-- End Accordion --}}
                        @endif

                    </div>
                    {{-- End Tab Pane --}}
                    @endforeach

                </div>
                {{-- End Tab Content --}}
            </div>
        </div>
        {{-- End Card --}}

    </div>
</div>

{{-- Modal Tambah (di luar tab agar tidak duplikat) --}}
@include('matakuliah.create-matkul', ['prodi' => $prodi, 'dosen' => $dosen])

@endsection

@push('scripts')
<script>
    // Buka accordion semester pertama secara otomatis saat tab aktif ditampilkan
    document.addEventListener('DOMContentLoaded', function () {
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

        // Buka accordion pertama pada tab yang baru diaktifkan
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
    });
</script>
@endpush
