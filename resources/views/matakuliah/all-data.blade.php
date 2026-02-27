@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
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

            {{-- ============================================================ --}}
            {{-- STATS CARDS                                                   --}}
            {{-- ============================================================ --}}
            <div class="row g-3 mb-3">
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-3">
                            <div
                                class="d-flex flex-center w-35px h-35px rounded-circle bg-light-primary me-3 flex-shrink-0">
                                <i class="bi bi-book-fill fs-5 text-primary"></i>
                            </div>
                            <div>
                                <div class="fs-2x fw-bold text-gray-800 lh-1" id="stat-total">{{ $matakuliah->count() }}
                                </div>
                                <div class="fs-8 fw-semibold text-gray-500">Total Mata Kuliah</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="d-flex flex-center w-35px h-35px rounded-circle bg-light-danger me-3 flex-shrink-0">
                                <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                            </div>
                            <div>
                                <div class="fs-2x fw-bold text-gray-800 lh-1">{{ $totalOrphan }}</div>
                                <div class="fs-8 fw-semibold text-gray-500">MK Belum Dipetakan</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-3">
                            <div
                                class="d-flex flex-center w-35px h-35px rounded-circle bg-light-success me-3 flex-shrink-0">
                                <i class="bi bi-mortarboard-fill fs-5 text-success"></i>
                            </div>
                            <div>
                                <div class="fs-2x fw-bold text-gray-800 lh-1">{{ $prodi->count() }}</div>
                                <div class="fs-8 fw-semibold text-gray-500">Program Studi</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-3">
                            <div
                                class="d-flex flex-center w-35px h-35px rounded-circle bg-light-warning me-3 flex-shrink-0">
                                <i class="bi bi-person-badge-fill fs-5 text-warning"></i>
                            </div>
                            <div>
                                <div class="fs-2x fw-bold text-gray-800 lh-1">{{ $dosen->count() }}</div>
                                <div class="fs-8 fw-semibold text-gray-500">Dosen Pengampu</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- TABEL UTAMA — menggunakan id="tabel-custom" seperti           --}}
            {{-- halaman Data Pengguna agar search Metronic auto-aktif         --}}
            {{-- ============================================================ --}}
            <div class="card">
                <div class="card-header border-0 pt-4 pb-2">
                    <div class="d-flex flex-wrap justify-content-between align-items-center w-100 gap-2">

                        {{-- Placeholder search Metronic (sama persis dengan user.blade.php) --}}
                        <div id="custom-search-container" class="mb-2 mb-md-0"></div>

                        {{-- Filter tambahan: Prodi, Semester, Jenis --}}
                        <div class="d-flex flex-wrap align-items-center gap-2">

                            {{-- <select id="filterProdi" class="form-select form-select-sm" style="min-width:170px">
                                <option value="">Semua Prodi</option>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->nama_prodi }}">{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>

                            <select id="filterSemester" class="form-select form-select-sm" style="min-width:150px">
                                <option value="">Semua Semester</option>
                                @for ($s = 1; $s <= 14; $s++)
                                    <option value="Sem.{{ $s }}">Semester {{ $s }}</option>
                                @endfor
                            </select>

                            <select id="filterJenis" class="form-select form-select-sm" style="min-width:140px">
                                <option value="">Semua Jenis</option>
                                <option value="Wajib">Wajib</option>
                                <option value="Pilihan">Pilihan</option>
                                <option value="MKU">MKU</option>
                            </select> --}}

                            {{-- <button type="button" id="btnResetFilter" class="btn btn-sm btn-light">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                            </button> --}}

                            <div id="custom-button-container" class="d-flex gap-2 flex-wrap align-items-center"></div>

                            <a href="{{ route('matakuliah.index') }}" class="btn btn-sm btn-light-info">
                                <i class="bi bi-diagram-3 me-1 fs-7"></i><span class="fs-8">Lihat Kurikulum</span>
                            </a>

                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalTambahMatkul">
                                <i class="bi bi-plus-lg me-1 fs-7"></i><span class="fs-8">Tambah MK</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-2 pb-3">
                    <div class="table-responsive">
                        {{--
                            KUNCI: id="tabel-custom"
                            Metronic akan auto-init DataTables pada tabel ini
                            dan menempatkan search input ke #custom-search-container
                        --}}
                        <table id="tabel-custom"
                            class="table table-bordered table-striped table-sm align-middle fs-7 gy-1 w-100">
                            <thead class="bg-gray-100 text-gray-800 border-bottom border-gray-300">
                                <tr class="fw-bold fs-8 text-uppercase">
                                    <th class="text-center py-2 px-2 min-w-30px">No</th>
                                    <th class="py-2 px-2 min-w-100px">Kode MK</th>
                                    <th class="py-2 px-2 min-w-200px">Nama Mata Kuliah</th>
                                    <th class="text-center py-2 px-2 min-w-60px">SKS</th>
                                    <th class="text-center py-2 px-2 min-w-80px">Jenis</th>
                                    <th class="py-2 px-2 min-w-150px">Dosen Pengampu</th>
                                    <th class="py-2 px-2 min-w-180px">Mapping Prodi / Semester</th>
                                    <th class="text-center py-2 px-2 min-w-100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                                @foreach ($matakuliah as $i => $mk)
                                    <tr>
                                        <td class="text-center text-muted">{{ $i + 1 }}</td>

                                        <td>
                                            <span class="badge badge-light fw-bold text-dark font-monospace px-2 py-1 fs-8">
                                                {{ $mk->kode_mk }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="fw-semibold text-gray-800">{{ $mk->nama_mk }}</span>
                                            @if ($mk->prodiMappings->isEmpty())
                                                <br>
                                                <span class="badge badge-light-danger fs-9 mt-1">Belum dipetakan</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <span class="fw-bolder text-primary">{{ $mk->bobot }}</span>
                                            <span class="text-muted fs-9"> sks</span>
                                        </td>

                                        <td class="text-center">
                                            @php
                                                $jenisColor = match ($mk->jenis) {
                                                    'wajib' => 'primary',
                                                    'pilihan' => 'warning',
                                                    'umum' => 'info',
                                                    default => 'secondary',
                                                };
                                                $jenisLabel = $mk->jenis === 'umum' ? 'MKU' : ucfirst($mk->jenis);
                                            @endphp
                                            <span class="badge badge-light-{{ $jenisColor }} fw-semibold">
                                                {{ $jenisLabel }}
                                            </span>
                                        </td>

                                        <td>
                                            @if ($mk->dosen && $mk->dosen->user)
                                                <div class="d-flex align-items-center gap-1">
                                                    <div class="symbol symbol-25px flex-shrink-0">
                                                        <span
                                                            class="symbol-label bg-light-success fw-bold text-success fs-9">
                                                            {{ strtoupper(substr($mk->dosen->user->nama, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <span class="text-truncate fw-semibold fs-8" style="max-width:135px">
                                                        {{ $mk->dosen->user->nama }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-muted fs-8">—</span>
                                            @endif
                                        </td>

                                        {{-- Mapping — teks ini yang akan di-search oleh DataTables --}}
                                        <td>
                                            @if ($mk->prodiMappings->isNotEmpty())
                                                @foreach ($mk->prodiMappings->sortBy('semester') as $mp)
                                                    <div class="d-flex align-items-center gap-1">
                                                        <i class="bi bi-dot text-primary fs-6"></i>
                                                        <span class="text-truncate fs-8" style="max-width:120px"
                                                            title="{{ $mp->prodi->nama_prodi ?? '-' }}">
                                                            {{ Str::limit($mp->prodi->nama_prodi ?? '-', 20) }}
                                                        </span>
                                                        <span class="badge badge-light-primary fs-9 flex-shrink-0">
                                                            Sem.{{ $mp->semester }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            @else
                                                <span class="text-muted fs-8">—</span>
                                            @endif
                                        </td>

                                        {{-- AKSI —tanpa @can agar selalu tampil --}}
                                        <td class="text-center">
                                            <button type="button" class="btn btn-icon btn-sm btn-light-primary me-1"
                                                style="width:26px;height:26px" title="Detail" data-bs-toggle="modal"
                                                data-bs-target="#modalDetailMatkul{{ $mk->id }}">
                                                <i class="bi bi-eye-fill fs-7"></i>
                                            </button>

                                            <button type="button"
                                                class="btn btn-icon btn-sm btn-light-success me-1 btn-open-edit-matkul"
                                                style="width:26px;height:26px" title="Edit"
                                                data-id="{{ $mk->id }}"
                                                data-url="{{ route('matakuliah.update', $mk->id) }}"
                                                data-kode="{{ $mk->kode_mk }}" data-nama="{{ $mk->nama_mk }}"
                                                data-bobot="{{ $mk->bobot }}" data-jenis="{{ $mk->jenis }}"
                                                data-id-dosen="{{ $mk->id_dosen }}"
                                                data-mappings="{{ json_encode($mk->prodiMappings->map(fn($mp) => ['prodi_id' => $mp->id_prodi, 'semester' => $mp->semester])) }}">
                                                <i class="bi bi-pencil-fill fs-7"></i>
                                            </button>

                                            <button type="button" class="btn btn-icon btn-sm btn-light-danger"
                                                style="width:26px;height:26px" title="Hapus" data-bs-toggle="modal"
                                                data-bs-target="#modalDeleteMatkul{{ $mk->id }}">
                                                <i class="bi bi-trash-fill fs-7"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- MODALS — di luar tabel agar tidak melanggar HTML spec            --}}
    {{-- ================================================================ --}}
    @foreach ($matakuliah as $mk)
        @include('matakuliah.partials.detail-matkul', ['m' => $mk])
        @include('matakuliah.partials.delete-matkul', ['m' => $mk])
    @endforeach

    @include('matakuliah.partials.create-matkul', ['dosen' => $dosen, 'prodi' => $prodi])

    @include('matakuliah.partials.edit-matkul', ['dosen' => $dosen, 'prodi' => $prodi])
@endsection

@push('scripts')
    <script>
        /**
         * Filter tambahan: Prodi, Semester, Jenis
         * Bekerja bersama DataTables (id=tabel-custom) yang sudah di-init Metronic.
         * Mekanisme: $.fn.dataTable.ext.search (custom filter function)
         */
        $(document).ready(function() {

            // ── Tunggu DataTables siap (Metronic init mungkin async) ────────────
            function waitForDT(cb) {
                var check = setInterval(function() {
                    if ($.fn.DataTable.isDataTable('#tabel-custom')) {
                        clearInterval(check);
                        cb($('#tabel-custom').DataTable());
                    }
                }, 100);
            }

            waitForDT(function(dt) {

                // ── Daftarkan custom filter function ──────────────────────────────
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    if (settings.nTable.id !== 'tabel-custom') return true;

                    var valProdi = $('#filterProdi').val().toLowerCase().trim();
                    var valSemester = $('#filterSemester').val().toLowerCase().trim();
                    var valJenis = $('#filterJenis').val().toLowerCase().trim();

                    // data[6] = kolom "Mapping Prodi / Semester" (index ke-6, 0-based)
                    // data[4] = kolom "Jenis"
                    var colMapping = (data[6] || '').toLowerCase();
                    var colJenis = (data[4] || '').toLowerCase();

                    if (valProdi && colMapping.indexOf(valProdi) === -1) return false;
                    if (valSemester && colMapping.indexOf(valSemester) === -1) return false;
                    if (valJenis && colJenis.indexOf(valJenis) === -1) return false;

                    return true;
                });

                // ── Event: filter berubah → re-draw DataTables (langsung, no debounce) ──
                $('#filterProdi, #filterSemester, #filterJenis').on('change', function() {
                    dt.draw();
                });

                // ── Event: tombol Reset ───────────────────────────────────────────
                $('#btnResetFilter').on('click', function() {
                    $('#filterProdi, #filterSemester, #filterJenis').val('');
                    dt.search('').draw();
                });
            });
        });
    </script>
@endpush
