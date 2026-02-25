{{-- Partial: matakuliah/partials/all-data-table.blade.php --}}
{{-- Variabel: $matakuliah (paginated), $prodi, $dosen --}}

<div class="table-responsive">
    <table class="table table-bordered table-striped table-sm align-middle fs-6 gy-2 w-100">
        <thead class="bg-gray-100 text-gray-800 border-bottom border-gray-300">
            <tr>
                <th class="text-center fw-bold" style="width:50px">No</th>
                <th class="fw-bold" style="width:110px">Kode MK</th>
                <th class="fw-bold">Nama Mata Kuliah</th>
                <th class="text-center fw-bold" style="width:65px">SKS</th>
                <th class="text-center fw-bold" style="width:90px">Jenis</th>
                <th class="fw-bold" style="width:200px">Dosen Pengampu</th>
                <th class="fw-bold" style="width:230px">Mapping Prodi / Semester</th>
                <th class="text-center fw-bold" style="width:110px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($matakuliah as $i => $mk)
                <tr>
                    <td class="text-center text-muted">{{ $matakuliah->firstItem() + $i }}</td>

                    <td>
                        <span class="badge badge-light fw-bold text-dark font-monospace px-2 py-1 fs-8">
                            {{ $mk->kode_mk }}
                        </span>
                    </td>

                    <td>
                        <span class="fw-semibold text-gray-800">{{ $mk->nama_mk }}</span>
                        @if ($mk->prodiMappings->isEmpty())
                            <br><span class="badge badge-light-danger fs-9 mt-1">Belum dipetakan</span>
                        @endif
                    </td>

                    <td class="text-center">
                        <span class="fw-bolder text-primary">{{ $mk->bobot }}</span>
                        <span class="text-muted fs-9"> sks</span>
                    </td>

                    <td class="text-center">
                        @php
                            $jenisColor = match ($mk->jenis) {
                                'wajib' => 'danger',
                                'pilihan' => 'warning',
                                'umum' => 'info',
                                default => 'secondary',
                            };
                        @endphp
                        <span class="badge badge-light-{{ $jenisColor }} fw-semibold text-capitalize">
                            {{ $mk->jenis === 'umum' ? 'MKU' : ucfirst($mk->jenis) }}
                        </span>
                    </td>

                    <td>
                        @if ($mk->dosen && $mk->dosen->user)
                            <div class="d-flex align-items-center gap-2">
                                <div class="symbol symbol-30px flex-shrink-0">
                                    <span class="symbol-label bg-light-primary fw-bold text-primary fs-8">
                                        {{ strtoupper(substr($mk->dosen->user->nama, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <div class="fw-semibold text-gray-800 fs-7 text-truncate" style="max-width:145px">
                                        {{ $mk->dosen->user->nama }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <span class="text-muted fs-8">—</span>
                        @endif
                    </td>

                    <td>
                        @if ($mk->prodiMappings->isNotEmpty())
                            @foreach ($mk->prodiMappings->sortBy('semester')->take(2) as $mp)
                                <div class="d-flex align-items-center gap-1 mb-1">
                                    <i class="bi bi-dot text-primary"></i>
                                    <span class="text-gray-700 fs-8 text-truncate" style="max-width:135px"
                                        title="{{ $mp->prodi->nama_prodi ?? '-' }}">
                                        {{ Str::limit($mp->prodi->nama_prodi ?? '-', 22) }}
                                    </span>
                                    <span class="badge badge-light-primary fs-9 py-1 px-2 flex-shrink-0">
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

                    <td class="text-center">
                        <button type="button" class="btn btn-icon btn-sm btn-light-primary me-1 btn-detail-mk"
                            title="Detail" data-id="{{ $mk->id }}">
                            <i class="bi bi-eye-fill fs-5"></i>
                        </button>
                        @can('kurikulum-update')
                            <button type="button" class="btn btn-icon btn-sm btn-light-success me-1 btn-edit-mk"
                                title="Ubah" data-id="{{ $mk->id }}">
                                <i class="bi bi-pencil-fill fs-5"></i>
                            </button>
                        @endcan
                        @can('kurikulum-delete')
                            <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-delete-mk" title="Hapus"
                                data-id="{{ $mk->id }}" data-nama="{{ $mk->nama_mk }}">
                                <i class="bi bi-trash-fill fs-5"></i>
                            </button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-12">
                        <i class="bi bi-search fs-2tx text-gray-300 d-block mb-3"></i>
                        <div class="fw-bold text-gray-500 fs-5 mb-1">Tidak ada mata kuliah ditemukan</div>
                        <div class="text-muted fs-7">Coba ubah filter atau kata kunci pencarian</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if ($matakuliah->hasPages())
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 px-1 py-3 border-top">
        <div class="fs-7 text-muted">
            Menampilkan
            <span class="fw-bold text-gray-800">{{ $matakuliah->firstItem() }}</span>
            –
            <span class="fw-bold text-gray-800">{{ $matakuliah->lastItem() }}</span>
            dari <span class="fw-bold text-gray-800">{{ $matakuliah->total() }}</span> mata kuliah
        </div>
        <div>
            {{ $matakuliah->links('pagination::bootstrap-5') }}
        </div>
    </div>
@else
    <div class="px-1 py-3 border-top">
        <span class="fs-7 text-muted">
            Total <span class="fw-bold text-gray-800">{{ $matakuliah->total() }}</span> mata kuliah
        </span>
    </div>
@endif
