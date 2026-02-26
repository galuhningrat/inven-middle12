@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Master Data Mata Kuliah</h1>
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
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('matakuliah.index') }}" class="text-muted text-hover-primary">Mata Kuliah</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
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

            {{-- ================================================================ --}}
            {{-- STATS RINGKAS                                                     --}}
            {{-- ================================================================ --}}
            <div class="row g-4 mb-5">
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-4">
                            <div
                                class="d-flex flex-center w-45px h-45px rounded-circle bg-light-primary me-3 flex-shrink-0">
                                <i class="bi bi-book-fill fs-3 text-primary"></i>
                            </div>
                            <div>
                                <div class="fs-2hx fw-bold text-gray-800 lh-1" id="stat-total">{{ $matakuliah->count() }}
                                </div>
                                <div class="fs-8 fw-semibold text-gray-500">Total MK (ditampilkan)</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-4">
                            <div class="d-flex flex-center w-45px h-45px rounded-circle bg-light-danger me-3 flex-shrink-0">
                                <i class="bi bi-exclamation-triangle-fill fs-3 text-danger"></i>
                            </div>
                            <div>
                                <div class="fs-2hx fw-bold text-gray-800 lh-1">{{ $totalOrphan }}</div>
                                <div class="fs-8 fw-semibold text-gray-500">MK Belum Dipetakan</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-4">
                            <div
                                class="d-flex flex-center w-45px h-45px rounded-circle bg-light-success me-3 flex-shrink-0">
                                <i class="bi bi-mortarboard-fill fs-3 text-success"></i>
                            </div>
                            <div>
                                <div class="fs-2hx fw-bold text-gray-800 lh-1">{{ $prodi->count() }}</div>
                                <div class="fs-8 fw-semibold text-gray-500">Program Studi</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-4">
                            <div
                                class="d-flex flex-center w-45px h-45px rounded-circle bg-light-warning me-3 flex-shrink-0">
                                <i class="bi bi-person-badge-fill fs-3 text-warning"></i>
                            </div>
                            <div>
                                <div class="fs-2hx fw-bold text-gray-800 lh-1">{{ $dosen->count() }}</div>
                                <div class="fs-8 fw-semibold text-gray-500">Dosen Pengampu</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================================================================ --}}
            {{-- FILTER & SEARCH CARD                                             --}}
            {{-- ================================================================ --}}
            <div class="card mb-5">
                <div class="card-body py-4">
                    <div class="d-flex flex-wrap gap-3 align-items-end">

                        {{-- Live Search Input --}}
                        <div style="min-width:240px; max-width:340px; flex-grow:1">
                            <label class="form-label fw-semibold fs-7 mb-1">
                                <i class="bi bi-search me-1 text-primary"></i>Cari Mata Kuliah
                            </label>
                            <input type="text" id="liveSearch" class="form-control form-control-sm"
                                placeholder="Ketik kode atau nama MK..." autocomplete="off">
                        </div>

                        {{-- Filter Prodi --}}
                        <div style="min-width:180px">
                            <label class="form-label fw-semibold fs-7 mb-1">Program Studi</label>
                            <select id="filterProdi" class="form-select form-select-sm">
                                <option value="">Semua Prodi</option>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Semester --}}
                        <div style="min-width:150px">
                            <label class="form-label fw-semibold fs-7 mb-1">Semester</label>
                            <select id="filterSemester" class="form-select form-select-sm">
                                <option value="">Semua Semester</option>
                                @for ($s = 1; $s <= 14; $s++)
                                    <option value="{{ $s }}">Semester {{ $s }}</option>
                                @endfor
                            </select>
                        </div>

                        {{-- Filter Jenis --}}
                        <div style="min-width:140px">
                            <label class="form-label fw-semibold fs-7 mb-1">Jenis MK</label>
                            <select id="filterJenis" class="form-select form-select-sm">
                                <option value="">Semua Jenis</option>
                                <option value="wajib">Wajib</option>
                                <option value="pilihan">Pilihan</option>
                                <option value="umum">Umum (MKU)</option>
                            </select>
                        </div>

                        {{-- Tombol Reset --}}
                        <div>
                            <label class="form-label fw-semibold fs-7 mb-1 d-block">&nbsp;</label>
                            <button type="button" id="btnResetFilter" class="btn btn-sm btn-light">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                            </button>
                        </div>

                        {{-- Spacer + Aksi Kanan --}}
                        <div class="ms-auto d-flex align-items-end gap-2">
                            <a href="{{ route('matakuliah.index') }}" class="btn btn-sm btn-light-info">
                                <i class="bi bi-diagram-3 me-1"></i>Lihat Kurikulum
                            </a>
                            @can('kurikulum-create')
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTambahMatkul">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah MK
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================================================================ --}}
            {{-- TABEL UTAMA                                                      --}}
            {{-- ================================================================ --}}
            <div class="card">
                <div class="card-header border-0 pt-5 pb-3 d-flex align-items-center justify-content-between">
                    <h4 class="card-title fw-bold text-gray-800 mb-0">
                        <i class="bi bi-table me-2 text-primary"></i>Daftar Mata Kuliah
                    </h4>
                    {{-- Indikator hasil pencarian --}}
                    <div id="searchInfo" class="text-muted fs-7 d-none">
                        Menampilkan <span id="searchCount" class="fw-bold text-primary">0</span> hasil
                        <span id="searchKeyword" class="text-gray-600"></span>
                    </div>
                </div>

                <div class="card-body pt-0">

                    {{-- Loading overlay --}}
                    <div id="tableLoading" class="d-none text-center py-10">
                        <div class="spinner-border text-primary" role="status" style="width:2.5rem;height:2.5rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="text-muted fs-7 mt-3">Memuat data...</div>
                    </div>

                    {{-- ============================================================ --}}
                    {{-- TABLE CONTAINER — Target update AJAX                         --}}
                    {{-- ============================================================ --}}
                    <div class="table-responsive" id="table-container">
                        <table class="table table-bordered table-striped table-sm align-middle fs-6 gy-2 w-100"
                            id="tabel-matkul">
                            <thead class="bg-gray-100 text-gray-800 border-bottom border-gray-300">
                                <tr class="fw-bold fs-6 text-uppercase">
                                    <th class="text-center py-4 px-2 min-w-30px">No</th>
                                    <th class="py-4 px-3 min-w-100px">Kode MK</th>
                                    <th class="py-4 px-3 min-w-200px">Nama Mata Kuliah</th>
                                    <th class="text-center py-4 px-2 min-w-60px">SKS</th>
                                    <th class="text-center py-4 px-2 min-w-80px">Jenis</th>
                                    <th class="py-4 px-3 min-w-150px">Dosen Pengampu</th>
                                    <th class="py-4 px-3 min-w-180px">Mapping Prodi / Semester</th>
                                    <th class="text-center py-4 px-2 min-w-110px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tabel-matkul-body">
                                @include('matakuliah.partials.all-data-table', [
                                    'matakuliah' => $matakuliah,
                                ])
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- MODALS — Di luar tabel agar tidak merusak struktur HTML           --}}
    {{-- ================================================================ --}}
    @foreach ($matakuliah as $mk)
        @include('matakuliah.partials.detail-matkul', ['m' => $mk])
        @include('matakuliah.partials.delete-matkul', ['m' => $mk])
    @endforeach

    {{-- Modal Tambah --}}
    @include('matakuliah.partials.create-matkul', ['dosen' => $dosen, 'prodi' => $prodi])

    {{-- Modal Edit Global --}}
    @include('matakuliah.partials.edit-matkul', ['dosen' => $dosen, 'prodi' => $prodi])
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            'use strict';

            // ============================================================
            // AJAX LIVE SEARCH & FILTER — dengan Debounce 350ms
            // ============================================================

            var ajaxUrl = '{{ route('matakuliah.all-data') }}';
            var debounceTimer = null;
            var currentRequest = null; // Batalkan request sebelumnya jika ada

            /**
             * Kumpulkan semua nilai filter yang aktif saat ini
             */
            function getFilters() {
                return {
                    search: $('#liveSearch').val().trim(),
                    id_prodi: $('#filterProdi').val(),
                    semester: $('#filterSemester').val(),
                    jenis: $('#filterJenis').val(),
                };
            }

            /**
             * Tampilkan indikator info pencarian
             */
            function updateSearchInfo(filters, total) {
                var hasFilter = filters.search || filters.id_prodi || filters.semester || filters.jenis;
                if (hasFilter) {
                    var keyword = filters.search ? ' untuk "<strong>' + filters.search + '</strong>"' : '';
                    $('#searchCount').text(total);
                    $('#searchKeyword').html(keyword);
                    $('#searchInfo').removeClass('d-none');
                    $('#stat-total').text(total);
                } else {
                    $('#searchInfo').addClass('d-none');
                    $('#stat-total').text(total);
                }
            }

            /**
             * Fungsi utama: kirim AJAX request ke server
             */
            function fetchMatkul() {
                var filters = getFilters();

                // Batalkan request sebelumnya yang masih berjalan
                if (currentRequest) {
                    currentRequest.abort();
                }

                // Tampilkan loading
                $('#tableLoading').removeClass('d-none');
                $('#table-container').addClass('opacity-50');

                currentRequest = $.ajax({
                    url: ajaxUrl,
                    method: 'GET',
                    data: filters,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    success: function(res) {
                        if (res.success) {
                            // Update isi tbody
                            $('#tabel-matkul-body').html(res.html);
                            updateSearchInfo(filters, res.total);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.statusText !== 'abort') {
                            console.error('[LiveSearch] Error:', xhr.status, xhr.statusText);
                            $('#tabel-matkul-body').html(
                                '<tr><td colspan="8" class="text-center py-8 text-danger">' +
                                '<i class="bi bi-exclamation-triangle-fill fs-2 d-block mb-2"></i>' +
                                'Gagal memuat data. Silakan coba lagi.</td></tr>'
                            );
                        }
                    },
                    complete: function() {
                        $('#tableLoading').addClass('d-none');
                        $('#table-container').removeClass('opacity-50');
                        currentRequest = null;
                    },
                });
            }

            // ── Event: Live Search (ketik di input) dengan Debounce 350ms ──
            $('#liveSearch').on('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(fetchMatkul, 350);
            });

            // ── Event: Filter Prodi — langsung trigger tanpa debounce ──
            $('#filterProdi').on('change', function() {
                clearTimeout(debounceTimer);
                fetchMatkul();
            });

            // ── Event: Filter Semester — langsung trigger tanpa debounce ──
            $('#filterSemester').on('change', function() {
                clearTimeout(debounceTimer);
                fetchMatkul();
            });

            // ── Event: Filter Jenis — langsung trigger tanpa debounce ──
            $('#filterJenis').on('change', function() {
                clearTimeout(debounceTimer);
                fetchMatkul();
            });

            // ── Event: Reset Filter ──
            $('#btnResetFilter').on('click', function() {
                $('#liveSearch').val('');
                $('#filterProdi').val('');
                $('#filterSemester').val('');
                $('#filterJenis').val('');
                clearTimeout(debounceTimer);
                fetchMatkul();
            });

            // ============================================================
            // Reload tabel setelah modal tambah berhasil disimpan
            // (intercept form submit modal tambah)
            // ============================================================
            $('#form_tambah_matkul').on('submit', function() {
                // Setelah redirect back, halaman akan reload otomatis
                // Tidak perlu AJAX khusus di sini karena form pakai POST biasa
            });

            // ============================================================
            // Tooltip Bootstrap
            // ============================================================
            $('[title]').tooltip({
                trigger: 'hover'
            });

        });
    </script>
@endpush
