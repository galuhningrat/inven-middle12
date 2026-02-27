{{--
    Partial: matakuliah/partials/all-data-table.blade.php
    Variabel: $matakuliah (Collection)

    Dirender oleh:
      - all-data.blade.php (SSR awal)
      - MatkulController@allData  (AJAX response → res.html)
--}}

@if ($matakuliah->isEmpty())
    <tr>
        <td colspan="8" class="text-center py-8">
            <i class="bi bi-search fs-2x text-gray-300 d-block mb-2"></i>
            <div class="fw-bold text-gray-500 fs-7 mb-1">Tidak ada mata kuliah ditemukan</div>
            <div class="text-muted fs-8">Coba ubah filter atau kata kunci pencarian</div>
        </td>
    </tr>
@else
    @foreach ($matakuliah as $i => $mk)
        <tr>

            {{-- No --}}
            <td class="text-center text-muted">{{ $i + 1 }}</td>

            {{-- Kode MK --}}
            <td>
                <span class="badge badge-light fw-bold text-dark font-monospace px-2 py-1 fs-8"
                    data-cell="kode">{{ $mk->kode_mk }}</span>
            </td>

            {{-- Nama MK --}}
            <td>
                <span class="fw-semibold text-gray-800" data-cell="nama">{{ $mk->nama_mk }}</span>
                @if ($mk->prodiMappings->isEmpty())
                    <br>
                    <span class="badge badge-light-danger fs-9 mt-1">Belum dipetakan</span>
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
                    <div class="d-flex align-items-center gap-1">
                        <div class="symbol symbol-25px flex-shrink-0">
                            <span class="symbol-label bg-light-success fw-bold text-success fs-9">
                                {{ strtoupper(substr($mk->dosen->user->nama, 0, 1)) }}
                            </span>
                        </div>
                        <span class="text-truncate fw-semibold fs-8" style="max-width:135px"
                            data-cell="dosen">{{ $mk->dosen->user->nama }}</span>
                    </div>
                @else
                    <span class="text-muted fs-8">—</span>
                @endif
            </td>

            {{-- Mapping Prodi / Semester --}}
            <td>
                @if ($mk->prodiMappings->isNotEmpty())
                    @foreach ($mk->prodiMappings->sortBy('semester')->take(2) as $mp)
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
                    @if ($mk->prodiMappings->count() > 2)
                        <span class="text-muted fs-9">+{{ $mk->prodiMappings->count() - 2 }} lainnya</span>
                    @endif
                @else
                    <span class="text-muted fs-8">—</span>
                @endif
            </td>

            {{-- ================================================================ --}}
            {{-- AKSI — Detail | Edit | Hapus                                     --}}
            {{-- @can dihapus agar semua role bisa melihat tombol                 --}}
            {{-- Atur visibility via policy/gate di controller jika perlu         --}}
            {{-- ================================================================ --}}
            <td class="text-center">

                {{-- DETAIL --}}
                <button type="button" class="btn btn-icon btn-sm btn-light-primary me-1" style="width:26px;height:26px"
                    title="Detail" data-bs-toggle="modal" data-bs-target="#modalDetailMatkul{{ $mk->id }}">
                    <i class="bi bi-eye-fill fs-7"></i>
                </button>

                {{-- EDIT --}}
                <button type="button" class="btn btn-icon btn-sm btn-light-success me-1 btn-open-edit-matkul"
                    style="width:26px;height:26px" title="Edit" data-id="{{ $mk->id }}"
                    data-url="{{ route('matakuliah.update', $mk->id) }}" data-kode="{{ $mk->kode_mk }}"
                    data-nama="{{ $mk->nama_mk }}" data-bobot="{{ $mk->bobot }}" data-jenis="{{ $mk->jenis }}"
                    data-id-dosen="{{ $mk->id_dosen }}"
                    data-mappings="{{ json_encode($mk->prodiMappings->map(fn($mp) => ['prodi_id' => $mp->id_prodi, 'semester' => $mp->semester])) }}">
                    <i class="bi bi-pencil-fill fs-7"></i>
                </button>

                {{-- HAPUS --}}
                <button type="button" class="btn btn-icon btn-sm btn-light-danger" style="width:26px;height:26px"
                    title="Hapus" data-bs-toggle="modal" data-bs-target="#modalDeleteMatkul{{ $mk->id }}">
                    <i class="bi bi-trash-fill fs-7"></i>
                </button>

            </td>
        </tr>
    @endforeach
@endif
