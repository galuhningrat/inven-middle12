@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Master Data Mata Kuliah</h1>
                <span class="h-20px border-gray-200 border-start mx-4"></span>
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">Dashboard</li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-200 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('matakuliah.index') }}" class="text-muted text-hover-primary">Mata Kuliah</a>
                    </li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-200 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-dark">Master Data</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-fluid">

            @include('master.notification')

            {{-- ===== HEADER STATS ===== --}}
            <div class="row g-5 mb-6">
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-flush h-lg-100">
                        <div class="card-body d-flex align-items-center gap-4 py-4">
                            <div class="symbol symbol-50px symbol-circle bg-light-primary flex-shrink-0">
                                <span class="symbol-label"><i class="bi bi-book-fill text-primary fs-3"></i></span>
                            </div>
                            <div>
                                <div class="text-muted fs-8 fw-semibold text-uppercase ls-1 mb-1">Total MK</div>
                                <div class="fs-2 fw-bolder text-dark">{{ $matakuliah->total() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-flush h-lg-100">
                        <div class="card-body d-flex align-items-center gap-4 py-4">
                            <div class="symbol symbol-50px symbol-circle bg-light-danger flex-shrink-0">
                                <span class="symbol-label"><i
                                        class="bi bi-exclamation-triangle-fill text-danger fs-3"></i></span>
                            </div>
                            <div>
                                <div class="text-muted fs-8 fw-semibold text-uppercase ls-1 mb-1">Tanpa Mapping</div>
                                <div class="fs-2 fw-bolder text-dark">{{ $totalOrphan }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-flush h-lg-100">
                        <div class="card-body d-flex align-items-center gap-4 py-4">
                            <div class="symbol symbol-50px symbol-circle bg-light-success flex-shrink-0">
                                <span class="symbol-label"><i class="bi bi-diagram-3-fill text-success fs-3"></i></span>
                            </div>
                            <div>
                                <div class="text-muted fs-8 fw-semibold text-uppercase ls-1 mb-1">Total Prodi</div>
                                <div class="fs-2 fw-bolder text-dark">{{ $prodi->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-flush h-lg-100">
                        <div class="card-body d-flex align-items-center justify-content-between py-4">
                            <div class="d-flex align-items-center gap-4">
                                <div class="symbol symbol-50px symbol-circle bg-light-info flex-shrink-0">
                                    <span class="symbol-label"><i class="bi bi-grid-3x3-gap-fill text-info fs-3"></i></span>
                                </div>
                                <div>
                                    <div class="text-muted fs-8 fw-semibold text-uppercase ls-1 mb-1">Kurikulum</div>
                                    <div class="fs-7 fw-bold text-dark">Per Prodi</div>
                                </div>
                            </div>
                            <a href="{{ route('matakuliah.index') }}" class="btn btn-sm btn-light-info">
                                <i class="bi bi-arrow-right fs-7"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== MAIN CARD ===== --}}
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title flex-column gap-2 w-100">
                        <div class="d-flex align-items-center gap-3 w-100">

                            {{-- Live Search --}}
                            <div class="input-group input-group-solid w-300px">
                                <span class="input-group-text border-0 bg-light ps-4">
                                    <i class="bi bi-search text-muted fs-6" id="searchIcon"></i>
                                    <span class="spinner-border spinner-border-sm text-muted d-none" id="searchSpinner"
                                        style="width:14px;height:14px;border-width:2px;"></span>
                                </span>
                                <input type="text" id="liveSearch" class="form-control form-control-solid border-0 ps-2"
                                    placeholder="Cari kode atau nama MK..." value="{{ request('search') }}"
                                    autocomplete="off">
                                <button type="button" class="btn btn-icon btn-sm btn-light border-0 d-none"
                                    id="btnClearSearch" title="Hapus">
                                    <i class="bi bi-x fs-6 text-muted"></i>
                                </button>
                            </div>

                            {{-- Filter Jenis --}}
                            <select id="filterJenis" class="form-select form-select-solid w-150px">
                                <option value="">Semua Jenis</option>
                                <option value="wajib" {{ request('filter_jenis') === 'wajib' ? 'selected' : '' }}>Wajib
                                </option>
                                <option value="pilihan" {{ request('filter_jenis') === 'pilihan' ? 'selected' : '' }}>
                                    Pilihan</option>
                                <option value="umum" {{ request('filter_jenis') === 'umum' ? 'selected' : '' }}>Umum
                                </option>
                            </select>

                            {{-- Filter Orphan --}}
                            <label class="d-flex align-items-center gap-2 cursor-pointer ms-2">
                                <input type="checkbox" class="form-check-input form-check-input-sm" id="filterOrphan"
                                    {{ request('filter_orphan') === '1' ? 'checked' : '' }}>
                                <span class="text-muted fs-7 fw-semibold">Tampilkan yang belum mapping</span>
                                @if ($totalOrphan > 0)
                                    <span class="badge badge-circle badge-danger fs-9"
                                        style="width:18px;height:18px;">{{ $totalOrphan }}</span>
                                @endif
                            </label>

                            <div class="ms-auto">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTambahMatkul">
                                    <i class="bi bi-plus-circle me-2"></i>Tambah MK
                                </button>
                            </div>
                        </div>

                        {{-- Filter aktif badge --}}
                        <div id="activeFilters"
                            class="d-flex gap-2 flex-wrap {{ request('search') || request('filter_jenis') || request('filter_orphan') ? '' : 'd-none' }}">
                            @if (request('search'))
                                <span class="badge badge-light-primary fs-8">
                                    <i class="bi bi-search me-1"></i>"{{ request('search') }}"
                                </span>
                            @endif
                            @if (request('filter_jenis'))
                                <span class="badge badge-light-info fs-8">
                                    Jenis: {{ ucfirst(request('filter_jenis')) }}
                                </span>
                            @endif
                            @if (request('filter_orphan'))
                                <span class="badge badge-light-danger fs-8">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Belum Mapping
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body pt-3">
                    <div id="tableWrapper">
                        @include('matakuliah.partials.all-data-table', [
                            'matakuliah' => $matakuliah,
                            'prodi' => $prodi,
                            'dosen' => $dosen,
                        ])
                    </div>
                </div>
            </div>

            {{-- Modal Tambah --}}
            @include('matakuliah.create-matkul', ['prodi' => $prodi, 'dosen' => $dosen])

            {{-- Modal Detail & Delete per MK --}}
            @foreach ($matakuliah as $m)
                @include('matakuliah.detail-matkul', ['m' => $m])
                @include('matakuliah.delete-matkul', ['m' => $m])
            @endforeach

            {{-- Modal Edit Global --}}
            @include('matakuliah.edit-matkul', ['prodi' => $prodi, 'dosen' => $dosen])

        </div>
    </div>
@endsection

@push('scripts')
<script>
(function waitForjQuery() {
    if (typeof jQuery === 'undefined') { setTimeout(waitForjQuery, 50); return; }

    jQuery(function($) {
        'use strict';

        var searchTimer = null;
        var $search   = $('#liveSearch');
        var $jenis    = $('#filterJenis');
        var $orphan   = $('#filterOrphan');
        var $wrapper  = $('#tableWrapper');
        var $icon     = $('#searchIcon');
        var $spinner  = $('#searchSpinner');
        var $clearBtn = $('#btnClearSearch');

        if ($search.val()) $clearBtn.removeClass('d-none');

        $search.on('keyup input', function() {
            $clearBtn.toggleClass('d-none', $(this).val().length === 0);
            clearTimeout(searchTimer);
            searchTimer = setTimeout(doSearch, 350);
        });

        $clearBtn.on('click', function() {
            $search.val('').focus();
            $clearBtn.addClass('d-none');
            doSearch();
        });

        $jenis.on('change', function() { doSearch(); });
        $orphan.on('change', function() { doSearch(); });

        function doSearch() {
            var params = {
                search:        $search.val().trim(),
                filter_jenis:  $jenis.val(),
                filter_orphan: $orphan.is(':checked') ? '1' : '',
                ajax:          '1',   // ← flag tambahan agar controller deteksi AJAX
            };

            // Update URL tanpa reload
            var queryStr = $.param(params);
            window.history.replaceState(null, '', '{{ route('matakuliah.all-data') }}?' + queryStr);

            // Loading
            $icon.addClass('d-none');
            $spinner.removeClass('d-none');
            $wrapper.css({ 'opacity': '0.5', 'pointer-events': 'none' });

            $.ajax({
                url: '{{ route('matakuliah.all-data') }}',
                method: 'GET',
                data: params,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                success: function(res) {
                    if (res.html) {
                        $wrapper.html(res.html);
                    }
                },
                error: function(xhr) {
                    console.error('[LiveSearch] Error:', xhr.status, xhr.responseText);
                    // Fallback: reload halaman dengan filter
                    window.location.href = '{{ route('matakuliah.all-data') }}?' + queryStr;
                },
                complete: function() {
                    $icon.removeClass('d-none');
                    $spinner.addClass('d-none');
                    $wrapper.css({ 'opacity': '1', 'pointer-events': '' });
                }
            });
        }
    });
})();
</script>
@endpush