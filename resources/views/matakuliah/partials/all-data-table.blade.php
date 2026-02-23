{{-- resources/views/matakuliah/partials/all-data-table.blade.php --}}
{{-- Partial ini di-render ulang saat live search AJAX --}}

<div class="table-responsive">
    <table class="table table-hover table-row-dashed table-row-gray-300 align-middle gs-0 gy-3 fs-7">
        <thead>
            <tr class="fw-bold text-muted bg-light text-uppercase fs-8">
                <th class="ps-4 w-40px rounded-start">#</th>
                <th class="min-w-90px">Kode MK</th>
                <th class="min-w-220px">Nama Mata Kuliah</th>
                <th class="text-center w-60px">SKS</th>
                <th class="text-center w-90px">Jenis</th>
                <th class="min-w-180px">Dosen Pengampu</th>
                <th class="min-w-160px">Mapping Prodi</th>
                <th class="text-center w-120px pe-4 rounded-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($matakuliah as $m)
                @php
                    $jenisMap = [
                        'wajib'   => ['label' => 'Wajib',   'class' => 'badge-light-primary'],
                        'pilihan' => ['label' => 'Pilihan', 'class' => 'badge-light-warning'],
                        'umum'    => ['label' => 'Umum',    'class' => 'badge-light-info'],
                    ];
                    $jenis = $jenisMap[$m->jenis] ?? ['label' => ucfirst($m->jenis), 'class' => 'badge-light-secondary'];
                @endphp
                <tr data-matkul-id="{{ $m->id }}">
                    <td class="ps-4 text-muted">{{ ($matakuliah->currentPage() - 1) * $matakuliah->perPage() + $loop->iteration }}</td>

                    <td>
                        <span class="badge badge-light-dark fw-bold fs-8" data-cell="kode">
                            {{ $m->kode_mk }}
                        </span>
                    </td>

                    <td>
                        <span class="fw-semibold text-dark" data-cell="nama">{{ $m->nama_mk }}</span>
                        @if ($m->prodiMappings->isEmpty())
                            <span class="badge badge-light-danger fs-9 ms-2">
                                <i class="bi bi-exclamation-circle me-1"></i>Belum Mapping
                            </span>
                        @endif
                    </td>

                    <td class="text-center">
                        <span class="badge badge-circle badge-light-primary fw-bold" data-cell="sks">
                            {{ $m->bobot }}
                        </span>
                    </td>

                    <td class="text-center">
                        <span class="badge {{ $jenis['class'] }} fs-9" data-cell="jenis" data-jenis="{{ $m->jenis }}">
                            {{ $jenis['label'] }}
                        </span>
                    </td>

                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="symbol symbol-28px symbol-circle bg-light-primary flex-shrink-0">
                                <span class="symbol-label fw-bold text-primary fs-9">
                                    {{ strtoupper(substr($m->dosen->user->nama ?? '-', 0, 1)) }}
                                </span>
                            </div>
                            <span class="text-gray-700 fw-semibold fs-7" data-cell="dosen">
                                {{ $m->dosen->user->nama ?? '-' }}
                            </span>
                        </div>
                    </td>

                    <td>
                        @forelse ($m->prodiMappings as $mp)
                            <span class="badge badge-light fs-9 mb-1 d-inline-flex align-items-center gap-1">
                                <span class="text-gray-700">{{ $mp->prodi->kode_prodi ?? '?' }}</span>
                                <span class="text-muted">·</span>
                                <span class="text-primary fw-bold">Smt {{ $mp->semester }}</span>
                            </span>
                        @empty
                            <span class="text-muted fs-8 fst-italic">—</span>
                        @endforelse
                    </td>

                    <td class="text-center pe-4">
                        {{-- Detail --}}
                        <button type="button"
                            class="btn btn-icon btn-sm btn-light-primary me-1"
                            title="Detail"
                            data-bs-toggle="modal"
                            data-bs-target="#modalDetailMatkul{{ $m->id }}">
                            <i class="bi bi-eye-fill fs-7"></i>
                        </button>

                        {{-- Edit (Global Modal) --}}
                        <button type="button"
                            class="btn btn-icon btn-sm btn-light-success me-1 btn-open-edit-matkul"
                            title="Edit"
                            data-id="{{ $m->id }}"
                            data-url="{{ route('matakuliah.update', $m->id) }}"
                            data-kode="{{ $m->kode_mk }}"
                            data-nama="{{ $m->nama_mk }}"
                            data-bobot="{{ $m->bobot }}"
                            data-jenis="{{ $m->jenis }}"
                            data-id-dosen="{{ $m->id_dosen }}"
                            data-mappings="{{ json_encode($m->prodiMappings->map(fn($mp) => ['prodi_id' => $mp->id_prodi, 'semester' => $mp->semester])) }}">
                            <i class="bi bi-pencil-fill fs-7"></i>
                        </button>

                        {{-- Hapus --}}
                        <button type="button"
                            class="btn btn-icon btn-sm btn-light-danger"
                            title="Hapus"
                            data-bs-toggle="modal"
                            data-bs-target="#modalDeleteMatkul{{ $m->id }}">
                            <i class="bi bi-trash-fill fs-7"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-12">
                        <div class="d-flex flex-column align-items-center gap-3">
                            <i class="bi bi-search fs-2x text-gray-300"></i>
                            <div>
                                <p class="fw-semibold text-muted mb-1">Tidak ada mata kuliah ditemukan</p>
                                <p class="text-muted fs-8">Coba ubah filter atau kata kunci pencarian</p>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if ($matakuliah->hasPages())
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mt-4">
        <div class="text-muted fs-8">
            Menampilkan <strong>{{ $matakuliah->firstItem() }}–{{ $matakuliah->lastItem() }}</strong>
            dari <strong>{{ $matakuliah->total() }}</strong> mata kuliah
        </div>
        <div>
            {{ $matakuliah->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
@else
    <div class="text-muted fs-8 mt-3">
        Menampilkan <strong>{{ $matakuliah->count() }}</strong> mata kuliah
    </div>
@endif
