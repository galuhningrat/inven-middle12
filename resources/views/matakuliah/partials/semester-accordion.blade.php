{{--
    ┌─────────────────────────────────────────────────────────────────────┐
    │  PARTIAL: matakuliah/partials/semester-accordion.blade.php          │
    │                                                                     │
    │  Render accordion Semester (Level 4) beserta tabel MK (Level 5).   │
    │                                                                     │
    │  Variabel yang diterima dari parent (via @include):                 │
    │    $bySemester   : collect() — key=semester, value=[...mappings]   │
    │    $accordionId  : string   — unique ID untuk accordion ini        │
    │    $allRenderedMks : Collection — referensi untuk dedup modal      │
    └─────────────────────────────────────────────────────────────────────┘
--}}

<div class="accordion accordion-icon-collapse" id="{{ $accordionId }}">
    @foreach ($bySemester as $semester => $mappings)
        @php
            $semPaneId = $accordionId . '-sem-' . $semester;
            $totalSks = collect($mappings)->sum(fn($mp) => $mp->matkul?->bobot ?? 0);
            $totalMk = count($mappings);

            // Categorize MK by jenis untuk tampilan badge summary
            $jumlahWajib = collect($mappings)->filter(fn($mp) => $mp->matkul?->jenis === 'wajib')->count();
            $jumlahUmum = collect($mappings)->filter(fn($mp) => $mp->matkul?->jenis === 'umum')->count();
            $jumlahPilihan = collect($mappings)->filter(fn($mp) => $mp->matkul?->jenis === 'pilihan')->count();
        @endphp

        <div class="accordion-item mb-2 border rounded-2">

            {{-- ── HEADER SEMESTER ────────────────────────────────────────────── --}}
            <h2 class="accordion-header" id="hd-{{ $semPaneId }}">
                <button
                    class="accordion-button {{ !$loop->first ? 'collapsed' : '' }} fw-semibold fs-7 py-3 px-4 bg-light rounded-2"
                    type="button" data-bs-toggle="collapse" data-bs-target="#{{ $semPaneId }}"
                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}">

                    <div class="d-flex align-items-center w-100 me-3 gap-3">
                        {{-- Icon Semester --}}
                        <div class="d-flex flex-center w-32px h-32px rounded bg-info flex-shrink-0">
                            <span class="text-white fw-bolder fs-8">{{ $semester }}</span>
                        </div>

                        {{-- Label --}}
                        <div class="flex-grow-1">
                            <span class="fw-bold text-gray-700">Semester {{ $semester }}</span>
                        </div>

                        {{-- Badge Summary --}}
                        <div class="d-flex gap-2 ms-auto flex-shrink-0">
                            <span class="badge badge-light-dark fs-9">
                                <i class="bi bi-book me-1"></i>{{ $totalMk }} MK
                            </span>
                            <span class="badge badge-light-info fs-9">
                                {{ $totalSks }} SKS
                            </span>
                            @if ($jumlahWajib > 0)
                                <span class="badge badge-light-primary fs-9">{{ $jumlahWajib }} Wajib</span>
                            @endif
                            @if ($jumlahUmum > 0)
                                <span class="badge badge-light-success fs-9">{{ $jumlahUmum }} MKU</span>
                            @endif
                            @if ($jumlahPilihan > 0)
                                <span class="badge badge-light-warning fs-9">{{ $jumlahPilihan }} Pilihan</span>
                            @endif
                        </div>
                    </div>
                </button>
            </h2>

            {{-- ── BODY SEMESTER — LEVEL 5: Tabel MK ────────────────────────── --}}
            <div id="{{ $semPaneId }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                data-bs-parent="#{{ $accordionId }}">

                <div class="accordion-body p-0">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-200 align-middle gs-4 gy-2 mb-0">
                            <thead>
                                <tr class="fw-bold text-muted bg-light fs-8 text-uppercase">
                                    <th class="ps-4" style="width:45px">#</th>
                                    <th style="width:110px">Kode MK</th>
                                    <th>Nama Mata Kuliah</th>
                                    <th style="width:60px" class="text-center">SKS</th>
                                    <th style="width:80px" class="text-center">Jenis</th>
                                    <th>Dosen Pengampu</th>
                                    <th style="width:110px" class="text-center pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($mappings as $i => $mp)
                                    @php
                                        $mk = $mp->matkul;

                                        // Dedup modal — pastikan modal hanya dirender sekali per MK unik
                                        if ($mk && !$allRenderedMks->contains('id', $mk->id)) {
                                            $allRenderedMks->push($mk);
                                        }

                                        $jenisBadge = match ($mk?->jenis) {
                                            'wajib' => 'badge-light-primary',
                                            'pilihan' => 'badge-light-warning',
                                            'umum' => 'badge-light-success',
                                            default => 'badge-light-dark',
                                        };
                                        $jenisLabel = match ($mk?->jenis) {
                                            'wajib' => 'Wajib',
                                            'pilihan' => 'Pilihan',
                                            'umum' => 'MKU',
                                            default => '-',
                                        };
                                    @endphp
                                    <tr>
                                        {{-- No --}}
                                        <td class="ps-4 text-muted fs-8">{{ $i + 1 }}</td>

                                        {{-- Kode MK --}}
                                        <td>
                                            <span class="badge badge-light-dark fw-bold font-monospace fs-8">
                                                {{ $mk?->kode_mk ?? '—' }}
                                            </span>
                                        </td>

                                        {{-- Nama MK --}}
                                        <td class="fw-semibold text-gray-800 fs-7">
                                            {{ $mk?->nama_mk ?? '(MK tidak ditemukan)' }}
                                        </td>

                                        {{-- SKS --}}
                                        <td class="text-center">
                                            <span class="badge badge-light-info fw-bold">
                                                {{ $mk?->bobot ?? 0 }} SKS
                                            </span>
                                        </td>

                                        {{-- Jenis --}}
                                        <td class="text-center">
                                            <span class="badge {{ $jenisBadge }} fs-8">
                                                {{ $jenisLabel }}
                                            </span>
                                        </td>

                                        {{-- Dosen --}}
                                        <td class="fs-7 text-gray-600">
                                            {{ $mk?->dosen?->user?->nama ?? '—' }}
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="text-center pe-4">
                                            @if ($mk)
                                                <div class="d-flex justify-content-center gap-1">
                                                    @can('kurikulum-read')
                                                        <button type="button" class="btn btn-icon btn-sm btn-light-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalDetailMatkul{{ $mk->id }}"
                                                            title="Detail">
                                                            <i class="bi bi-eye-fill fs-5"></i>
                                                        </button>
                                                    @endcan
                                                    @can('kurikulum-update')
                                                        <button type="button"
                                                            class="btn btn-icon btn-sm btn-light-success btn-open-edit-matkul"
                                                            data-id="{{ $mk->id }}" title="Edit">
                                                            <i class="bi bi-pencil-fill fs-5"></i>
                                                        </button>
                                                    @endcan
                                                    @can('kurikulum-delete')
                                                        <button type="button" class="btn btn-icon btn-sm btn-light-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalHapusMatkul{{ $mk->id }}"
                                                            title="Hapus">
                                                            <i class="bi bi-trash-fill fs-5"></i>
                                                        </button>
                                                    @endcan
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-2x text-gray-300 d-block mb-2"></i>
                                            <span class="fs-7">Belum ada mata kuliah di semester ini.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if (count($mappings) > 0)
                                <tfoot>
                                    <tr class="border-top-2 bg-light">
                                        <td colspan="3" class="ps-4 fw-bold text-gray-600 fs-8 py-3">
                                            Total Semester {{ $semester }}
                                        </td>
                                        <td class="text-center fw-bold text-info">
                                            {{ $totalSks }} SKS
                                        </td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- end accordion-item Semester --}}
    @endforeach
</div>
{{-- end accordion Semester --}}
