<!-- Modal Detail Mata Kuliah -->
<div class="modal fade" id="modalDetailMatkul{{ $m->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
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
                    <span class="badge badge-light-primary fs-8 px-4 py-2">
                        <i class="bi bi-calendar3 me-1"></i>Semester {{ $m->semester ?? '-' }}
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
                </div>

                {{-- Detail rows --}}
                <div class="separator separator-dashed mb-5"></div>

                <div class="row g-4">
                    <div class="col-sm-6">
                        <div class="d-flex flex-column gap-1">
                            <span class="text-muted fs-8 fw-semibold text-uppercase">Program Studi</span>
                            <span class="text-dark fw-semibold">{{ $m->prodi->nama_prodi ?? '-' }}</span>
                            <span class="text-muted fs-9">{{ $m->prodi->kode_prodi ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex flex-column gap-1">
                            <span class="text-muted fs-8 fw-semibold text-uppercase">Dosen Pengampu</span>
                            <div class="d-flex align-items-center gap-2">
                                <div class="symbol symbol-30px symbol-circle bg-light-primary">
                                    <span class="symbol-label fw-bold text-primary fs-8">
                                        {{ strtoupper(substr($m->dosen->user->nama ?? '-', 0, 1)) }}
                                    </span>
                                </div>
                                <span class="text-dark fw-semibold">{{ $m->dosen->user->nama ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex flex-column gap-1">
                            <span class="text-muted fs-8 fw-semibold text-uppercase">Semester</span>
                            <span class="text-dark fw-semibold">Semester {{ $m->semester ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex flex-column gap-1">
                            <span class="text-muted fs-8 fw-semibold text-uppercase">Dibuat</span>
                            <span class="text-dark fw-semibold">
                                {{ $m->created_at ? $m->created_at->format('d M Y') : '-' }}
                            </span>
                        </div>
                    </div>
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
