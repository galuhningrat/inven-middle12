{{--
    Partial: matakuliah/partials/semester-accordion.blade.php

    Variabel WAJIB dari parent:
      - $rombel  (model Rombel, harus punya kolom id_prodi)

    Self-contained — $semester TIDAK perlu dikirim dari luar.
    Data semester di-query dari pivot matkul_prodi_semester.
--}}

@php
    use App\Models\MatkulProdiSemester;

    $mappingsBySemester = MatkulProdiSemester::with(['matkul.dosen.user', 'matkul.prodiMappings'])
        ->where('id_prodi', $rombel->id_prodi)
        ->orderBy('semester')
        ->orderBy('id_matkul')
        ->get()
        ->groupBy('semester');
@endphp

@if ($mappingsBySemester->isEmpty())
    <div class="text-center text-muted py-8">
        <i class="bi bi-inbox fs-2x d-block mb-2 text-gray-300"></i>
        <div class="fs-7">Belum ada mata kuliah yang dipetakan ke prodi ini.</div>
    </div>
@else
    @foreach ($mappingsBySemester as $semester => $mappings)
        <div class="mb-3">
            {{-- Header Semester --}}
            <div class="d-flex align-items-center justify-content-between py-3 px-4
                        bg-light-primary rounded cursor-pointer"
                data-bs-toggle="collapse" data-bs-target="#semCollapse-{{ $rombel->id }}-{{ $semester }}"
                aria-expanded="true">

                <div class="d-flex align-items-center gap-3">
                    <span class="w-8px h-8px rounded-circle bg-primary d-inline-block"></span>
                    <h6 class="fw-bold text-gray-800 mb-0">Semester {{ $semester }}</h6>
                </div>

                @php
                    $totalSks = $mappings->sum(fn($m) => $m->matkul?->bobot ?? 0);
                    $totalWajib = $mappings->filter(fn($m) => $m->matkul?->jenis === 'wajib')->count();
                @endphp

                <div class="d-flex align-items-center gap-3 text-muted fs-8">
                    <span>
                        <i class="bi bi-book me-1 text-primary"></i>
                        <strong class="text-gray-800">{{ $mappings->count() }}</strong> MK
                    </span>
                    <span>
                        <i class="bi bi-award me-1 text-success"></i>
                        <strong class="text-success">{{ $totalSks }}</strong> SKS
                    </span>
                    <span class="badge badge-light-warning fs-9">{{ $totalWajib }} Wajib</span>
                    <i class="bi bi-chevron-up text-primary fs-7 ms-2"></i>
                </div>
            </div>

            {{-- Tabel MK --}}
            <div class="collapse show" id="semCollapse-{{ $rombel->id }}-{{ $semester }}">
                <div class="table-responsive border border-top-0 rounded-bottom">
                    <table class="table table-sm align-middle fs-7 mb-0 gy-1">
                        <thead class="bg-gray-100 border-bottom">
                            <tr class="fw-semibold text-uppercase text-muted fs-8">
                                <th class="ps-5 py-3 min-w-30px text-center">#</th>
                                <th class="py-3 min-w-100px">Kode MK</th>
                                <th class="py-3 min-w-220px">Nama Mata Kuliah</th>
                                <th class="text-center py-3 min-w-60px">SKS</th>
                                <th class="text-center py-3 min-w-80px">Jenis</th>
                                <th class="py-3 min-w-160px">Dosen Pengampu</th>
                                <th class="text-center py-3 min-w-115px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mappings as $i => $mapping)
                                @php $mk = $mapping->matkul; @endphp
                                @if (!$mk)
                                    @continue
                                @endif

                                <tr class="border-bottom border-gray-100">
                                    <td class="ps-5 text-center text-muted">{{ $i + 1 }}</td>

                                    <td>
                                        <span class="badge badge-light fw-bold text-dark font-monospace px-2">
                                            {{ $mk->kode_mk }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="fw-semibold text-gray-800">{{ $mk->nama_mk }}</span>
                                    </td>

                                    <td class="text-center">
                                        <span class="fw-bolder text-primary">{{ $mk->bobot }}</span>
                                        <span class="text-muted fs-9"> SKS</span>
                                    </td>

                                    <td class="text-center">
                                        @php
                                            $color = match ($mk->jenis) {
                                                'wajib' => 'primary',
                                                'pilihan' => 'warning',
                                                'umum' => 'info',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge badge-light-{{ $color }} fw-semibold">
                                            {{ $mk->jenis === 'umum' ? 'MKU' : ucfirst($mk->jenis) }}
                                        </span>
                                    </td>

                                    <td>
                                        @if ($mk->dosen && $mk->dosen->user)
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="symbol symbol-25px flex-shrink-0">
                                                    <span
                                                        class="symbol-label bg-light-success fw-bold text-success fs-9">
                                                        {{ strtoupper(substr($mk->dosen->user->nama, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <span class="text-truncate fs-8 fw-semibold" style="max-width:140px">
                                                    {{ $mk->dosen->user->nama }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-muted fs-8">—</span>
                                        @endif
                                    </td>

                                    {{-- AKSI — tanpa @can agar selalu tampil --}}
                                    <td class="text-center">
                                        <button type="button" class="btn btn-icon btn-sm btn-light-primary me-1"
                                            title="Detail" data-bs-toggle="modal"
                                            data-bs-target="#modalDetailMatkul{{ $mk->id }}">
                                            <i class="bi bi-eye-fill fs-6"></i>
                                        </button>

                                        <button type="button"
                                            class="btn btn-icon btn-sm btn-light-success me-1 btn-open-edit-matkul"
                                            title="Edit" data-id="{{ $mk->id }}"
                                            data-url="{{ route('matakuliah.update', $mk->id) }}"
                                            data-kode="{{ $mk->kode_mk }}" data-nama="{{ $mk->nama_mk }}"
                                            data-bobot="{{ $mk->bobot }}" data-jenis="{{ $mk->jenis }}"
                                            data-id-dosen="{{ $mk->id_dosen }}"
                                            data-mappings="{{ json_encode($mk->prodiMappings->map(fn($mp) => ['prodi_id' => $mp->id_prodi, 'semester' => $mp->semester])) }}">
                                            <i class="bi bi-pencil-fill fs-6"></i>
                                        </button>

                                        <button type="button" class="btn btn-icon btn-sm btn-light-danger"
                                            title="Hapus" data-bs-toggle="modal"
                                            data-bs-target="#modalDeleteMatkul{{ $mk->id }}">
                                            <i class="bi bi-trash-fill fs-6"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
@endif
