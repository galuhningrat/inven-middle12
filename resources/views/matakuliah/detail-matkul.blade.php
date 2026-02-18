<!-- Modal Detail Mata Kuliah -->
<div class="modal fade" id="modalDetailMatkul{{ $m->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header bg-light-primary border-0 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="symbol symbol-45px symbol-circle bg-primary">
                        <span class="symbol-label fw-bolder text-white fs-6">
                            {{ strtoupper(substr($m->kode_mk, 0, 2)) }}
                        </span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bolder text-dark mb-0">{{ $m->nama_mk }}</h5>
                        <span class="text-muted fs-8">Detail Informasi Mata Kuliah</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body py-6">

                {{-- Badge ringkasan --}}
                <div class="d-flex flex-wrap gap-3 mb-6">
                    <span class="badge badge-light-dark fs-8 px-4 py-2">
                        <i class="bi bi-hash me-1"></i>{{ $m->kode_mk }}
                    </span>
                    <span class="badge badge-light-success fs-8 px-4 py-2">
                        <i class="bi bi-stack me-1"></i>{{ $m->bobot }} SKS
                    </span>
                    @php
                        $jenisClass = ['wajib' => 'warning', 'pilihan' => 'info', 'umum' => 'secondary'];
                        $jClass = $jenisClass[$m->jenis] ?? 'secondary';
                    @endphp
                    <span class="badge badge-light-{{ $jClass }} fs-8 px-4 py-2 text-capitalize">
                        <i class="bi bi-tag me-1"></i>{{ ucfirst($m->jenis) }}
                    </span>
                    <span class="badge badge-light-primary fs-8 px-4 py-2">
                        <i class="bi bi-diagram-3 me-1"></i>
                        {{ $m->prodiMappings->count() }} Mapping
                    </span>
                </div>

                <div class="separator separator-dashed mb-5"></div>

                {{-- Dosen Pengampu --}}
                <div class="mb-5">
                    <span class="text-muted fs-8 fw-semibold text-uppercase d-block mb-2">Dosen Pengampu</span>
                    <div class="d-flex align-items-center gap-2">
                        <div class="symbol symbol-35px symbol-circle bg-light-primary">
                            <span class="symbol-label fw-bold text-primary fs-8">
                                {{ strtoupper(substr($m->dosen->user->nama ?? '-', 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-dark fw-semibold d-block">{{ $m->dosen->user->nama ?? '-' }}</span>
                            @if($m->dosen?->nip)
                            <span class="text-muted fs-8">NIP {{ $m->dosen->nip }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Tabel Mapping Prodi & Semester --}}
                <div>
                    <span class="text-muted fs-8 fw-semibold text-uppercase d-block mb-3">
                        <i class="bi bi-diagram-3 me-1"></i>Mapping Prodi & Semester
                    </span>

                    @if($m->prodiMappings->isEmpty())
                        <div class="text-center py-4 text-muted fs-8">
                            <i class="bi bi-exclamation-circle me-1"></i>Belum ada mapping prodi & semester.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle fs-8 mb-0">
                                <thead>
                                    <tr class="fw-semibold text-muted text-uppercase">
                                        <th class="ps-0">Program Studi</th>
                                        <th class="text-center">Semester</th>
                                        @if($m->prodiMappings->whereNotNull('angkatan')->isNotEmpty())
                                        <th class="text-center">Angkatan</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($m->prodiMappings->sortBy(['id_prodi', 'semester']) as $mp)
                                    <tr>
                                        <td class="ps-0">
                                            <span class="fw-semibold text-dark">
                                                {{ $mp->prodi->nama_prodi ?? '-' }}
                                            </span>
                                            @if($mp->prodi)
                                            <span class="text-muted ms-1">({{ $mp->prodi->kode_prodi }})</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-light-primary">
                                                Semester {{ $mp->semester }}
                                            </span>
                                        </td>
                                        @if($m->prodiMappings->whereNotNull('angkatan')->isNotEmpty())
                                        <td class="text-center">
                                            {{ $mp->angkatan ?? '<span class="text-muted">Semua</span>' }}
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- Info created --}}
                <div class="mt-5 text-muted fs-9">
                    <i class="bi bi-clock me-1"></i>
                    Dibuat: {{ $m->created_at ? $m->created_at->format('d M Y') : '-' }}
                    @if($m->updated_at && $m->updated_at != $m->created_at)
                        &bull; Diupdate: {{ $m->updated_at->format('d M Y') }}
                    @endif
                </div>

            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button"
                    class="btn btn-light-success"
                    data-bs-dismiss="modal"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditMatkul{{ $m->id }}">
                    <i class="bi bi-pencil me-1"></i>Edit
                </button>
            </div>
        </div>
    </div>
</div>
