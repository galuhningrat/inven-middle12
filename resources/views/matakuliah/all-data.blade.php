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

            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="d-flex flex-wrap justify-content-between align-items-center w-100">
                        <div id="custom-search-container" class="mb-2 mb-md-0"></div>

                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <select id="filterJenis" class="form-select form-select-sm w-auto">
                                <option value="">Semua Jenis</option>
                                <option value="Wajib">Wajib</option>
                                <option value="Pilihan">Pilihan</option>
                                <option value="MKU">Umum (MKU)</option>
                            </select>

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

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="tabel-matkul"
                            class="table table-bordered table-striped table-sm align-middle fs-6 gy-2 w-100">
                            <thead class="bg-gray-100 text-gray-800 border-bottom border-gray-300">
                                <tr class="fw-bold fs-6 text-uppercase">
                                    <th class="text-center py-4 px-2 min-w-30px">No</th>
                                    <th class="py-4 px-3 min-w-100px">Kode MK</th>
                                    <th class="py-4 px-3 min-w-200px">Nama Mata Kuliah</th>
                                    <th class="text-center py-4 px-2 min-w-60px">SKS</th>
                                    <th class="text-center py-4 px-2 min-w-80px">Jenis</th>
                                    <th class="py-4 px-3 min-w-150px">Dosen Pengampu</th>
                                    <th class="py-4 px-3 min-w-180px">Mapping Prodi / Semester</th>
                                    <th class="text-center py-4 px-2 min-w-100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                                @foreach ($matakuliah as $i => $mk)
                                    <tr>
                                        {{-- No --}}
                                        <td class="text-center">{{ $i + 1 }}</td>

                                        {{-- Kode MK --}}
                                        <td>
                                            <span class="badge badge-light fw-bold text-dark font-monospace px-2"
                                                data-cell="kode">{{ $mk->kode_mk }}</span>
                                        </td>

                                        {{-- Nama MK --}}
                                        <td>
                                            <span class="fw-semibold text-gray-800"
                                                data-cell="nama">{{ $mk->nama_mk }}</span>
                                            @if ($mk->prodiMappings->isEmpty())
                                                <br><span class="badge badge-light-danger fs-9 mt-1">Belum dipetakan</span>
                                            @endif
                                        </td>

                                        {{-- SKS --}}
                                        <td class="text-center">
                                            <span class="fw-bolder text-primary" data-cell="sks">{{ $mk->bobot }}</span>
                                            <span class="text-muted fs-9"> sks</span>
                                        </td>

                                        {{-- Jenis --}}
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
                                            <span class="badge badge-light-{{ $jenisColor }} fw-semibold"
                                                data-cell="jenis">{{ $jenisLabel }}</span>
                                        </td>

                                        {{-- Dosen --}}
                                        <td>
                                            @if ($mk->dosen && $mk->dosen->user)
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="symbol symbol-30px flex-shrink-0">
                                                        <span
                                                            class="symbol-label bg-light-success fw-bold text-success fs-8">
                                                            {{ strtoupper(substr($mk->dosen->user->nama, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <span class="text-truncate fw-semibold" style="max-width:130px"
                                                        data-cell="dosen">{{ $mk->dosen->user->nama }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        {{-- Mapping --}}
                                        <td>
                                            @if ($mk->prodiMappings->isNotEmpty())
                                                @foreach ($mk->prodiMappings->sortBy('semester')->take(2) as $mp)
                                                    <div class="d-flex align-items-center gap-1 mb-1">
                                                        <i class="bi bi-dot text-primary fs-5"></i>
                                                        <span class="text-truncate fs-8" style="max-width:120px"
                                                            title="{{ $mp->prodi->nama_prodi ?? '-' }}">
                                                            {{ Str::limit($mp->prodi->nama_prodi ?? '-', 20) }}
                                                        </span>
                                                        <span class="badge badge-light-primary fs-9 flex-shrink-0">
                                                            Sem.{{ $mp->semester }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                                @if ($mk->prodiMappings->count() > 2)
                                                    <span class="text-muted fs-9">+{{ $mk->prodiMappings->count() - 2 }}
                                                        lainnya</span>
                                                @endif
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        {{-- AKSI --}}
                                        <td class="text-center">
                                            {{-- DETAIL --}}
                                            <button type="button" class="btn btn-icon btn-sm btn-light-primary me-1"
                                                title="Detail" data-bs-toggle="modal"
                                                data-bs-target="#modalDetailMatkul{{ $mk->id }}">
                                                <i class="bi bi-eye-fill fs-5"></i>
                                            </button>

                                            {{-- EDIT --}}
                                            @can('kurikulum-update')
                                                <button type="button"
                                                    class="btn btn-icon btn-sm btn-light-success me-1 btn-open-edit-matkul"
                                                    title="Ubah" data-id="{{ $mk->id }}"
                                                    data-url="{{ route('matakuliah.update', $mk->id) }}"
                                                    data-kode="{{ $mk->kode_mk }}" data-nama="{{ $mk->nama_mk }}"
                                                    data-bobot="{{ $mk->bobot }}" data-jenis="{{ $mk->jenis }}"
                                                    data-id-dosen="{{ $mk->id_dosen }}"
                                                    data-mappings="{{ json_encode($mk->prodiMappings->map(fn($mp) => ['prodi_id' => $mp->id_prodi, 'semester' => $mp->semester])) }}">
                                                    <i class="bi bi-pencil-fill fs-5"></i>
                                                </button>
                                            @endcan

                                            {{-- HAPUS --}}
                                            @can('kurikulum-delete')
                                                <button type="button" class="btn btn-icon btn-sm btn-light-danger"
                                                    title="Hapus" data-bs-toggle="modal"
                                                    data-bs-target="#modalDeleteMatkul{{ $mk->id }}">
                                                    <i class="bi bi-trash-fill fs-5"></i>
                                                </button>
                                            @endcan
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

    {{--
        ╔══════════════════════════════════════════════════════╗
        ║  MODALS — Harus di LUAR table agar tidak merusak    ║
        ║  struktur HTML tabel (modals di dalam tbody akan     ║
        ║  dirender browser sebagai tabel row ekstra)          ║
        ╚══════════════════════════════════════════════════════╝
    --}}
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
            var table = $('#tabel-matkul').DataTable({
                language: {
                    search: '',
                    searchPlaceholder: 'Cari kode / nama MK...',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data',
                    infoFiltered: '(difilter dari _MAX_ total data)',
                    zeroRecords: 'Data tidak ditemukan',
                    emptyTable: 'Belum ada mata kuliah',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Selanjutnya',
                        previous: 'Sebelumnya',
                    },
                },
                dom: 'lrtip',
                pageLength: 20,
                lengthMenu: [10, 20, 50, 100],
                order: [
                    [1, 'asc']
                ],
                columnDefs: [{
                        orderable: false,
                        targets: [6, 7]
                    },
                    {
                        searchable: false,
                        targets: [0, 3, 4, 6, 7]
                    },
                ],
                initComplete: function() {
                    var $filter = $(this.api().table().container()).find('.dataTables_filter');
                    $filter.find('input')
                        .addClass('form-control form-control-sm w-250px')
                        .attr('placeholder', 'Cari kode / nama MK...');
                    $filter.find('label').addClass('d-flex align-items-center gap-2 mb-0');
                    $filter.appendTo('#custom-search-container');
                }
            });

            $('#filterJenis').on('change', function() {
                table.column(4).search($(this).val()).draw();
            });
        });
    </script>
@endpush
