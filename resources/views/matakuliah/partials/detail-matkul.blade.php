{{--
    detail-matkul.blade.php — Redesigned Modal
    Digunakan di: index.blade.php dan all-data.blade.php
    Di-include dalam loop dengan variable $m (Matkul model)
--}}

<div class="modal fade" id="modalDetailMatkul{{ $m->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header bg-light-primary border-0 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="symbol symbol-50px symbol-circle bg-primary">
                        <span class="symbol-label fw-bolder text-white fs-5">
                            {{ strtoupper(substr($m->kode_mk, 0, 2)) }}
                        </span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge badge-light-dark fw-bold fs-8">{{ $m->kode_mk }}</span>
                            @php
                                $jenisMap = [
                                    'wajib'   => ['label' => 'Wajib',   'class' => 'badge-primary'],
                                    'pilihan' => ['label' => 'Pilihan', 'class' => 'badge-warning'],
                                    'umum'    => ['label' => 'Umum',    'class' => 'badge-info'],
                                ];
                                $jenisInfo = $jenisMap[$m->jenis] ?? ['label' => ucfirst($m->jenis), 'class' => 'badge-secondary'];
                            @endphp
                            <span class="badge {{ $jenisInfo['class'] }} fs-9">{{ $jenisInfo['label'] }}</span>
                            <span class="badge badge-dark fs-9">{{ $m->bobot }} SKS</span>
                        </div>
                        <h5 class="modal-title fw-bolder text-dark mb-0 fs-5">{{ $m->nama_mk }}</h5>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body pt-6 pb-4">

                {{-- Dosen Pengampu --}}
                <div class="mb-6">
                    <div class="text-muted fs-8 fw-semibold text-uppercase ls-1 mb-3">
                        <i class="bi bi-person-badge me-1"></i>Dosen Pengampu
                    </div>
                    <div class="d-flex align-items-center gap-4 p-4 bg-light rounded-2">
                        <div class="symbol symbol-50px symbol-circle bg-primary">
                            <span class="symbol-label fw-bold text-white fs-5">
                                {{ strtoupper(substr($m->dosen->user->nama ?? '-', 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bolder text-dark fs-6">{{ $m->dosen->user->nama ?? '-' }}</div>
                            @if ($m->dosen && $m->dosen->nip)
                                <div class="text-muted fs-8">NIP {{ $m->dosen->nip }}</div>
                            @endif
                            @if ($m->dosen && $m->dosen->user && $m->dosen->user->email)
                                <div class="text-muted fs-8">
                                    <i class="bi bi-envelope me-1"></i>{{ $m->dosen->user->email }}
                                </div>
                            @endif
                        </div>
                        @if ($m->dosen)
                            <div class="text-end">
                                <div class="badge badge-light-primary fs-8">Dosen Tetap</div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="separator separator-dashed mb-6"></div>

                {{-- Mapping Prodi & Semester --}}
                <div class="mb-2">
                    <div class="text-muted fs-8 fw-semibold text-uppercase ls-1 mb-3">
                        <i class="bi bi-diagram-3 me-1"></i>Mapping Prodi &amp; Semester
                        <span class="badge badge-circle badge-light-success ms-2 fs-9">
                            {{ $m->prodiMappings->count() }}
                        </span>
                    </div>

                    @if ($m->prodiMappings->isEmpty())
                        <div class="text-center py-6 bg-light rounded-2">
                            <i class="bi bi-exclamation-circle text-warning fs-2 d-block mb-2"></i>
                            <span class="text-muted fw-semibold fs-7">MK ini belum memiliki mapping prodi</span>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm align-middle fs-7 mb-0">
                                <thead class="bg-gray-50">
                                    <tr class="fw-bold text-muted text-uppercase fs-9">
                                        <th class="ps-4 py-3">#</th>
                                        <th class="py-3">Program Studi</th>
                                        <th class="py-3 text-center w-120px">Kode</th>
                                        <th class="py-3 text-center w-110px">Semester</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($m->prodiMappings as $idx => $mp)
                                        <tr>
                                            <td class="ps-4 text-muted">{{ $idx + 1 }}</td>
                                            <td class="fw-semibold text-dark">{{ $mp->prodi->nama_prodi ?? '-' }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-light-info fs-9">
                                                    {{ $mp->prodi->kode_prodi ?? '?' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-light-success fw-bold fs-9">
                                                    Semester {{ $mp->semester }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- Meta info --}}
                <div class="d-flex align-items-center gap-4 mt-5 pt-4 border-top border-dashed">
                    <span class="text-muted fs-8">
                        <i class="bi bi-calendar3 me-1"></i>
                        Dibuat: {{ $m->created_at ? $m->created_at->format('d M Y') : '-' }}
                    </span>
                    <span class="text-muted fs-8">
                        <i class="bi bi-pencil-square me-1"></i>
                        Diupdate: {{ $m->updated_at ? $m->updated_at->format('d M Y') : '-' }}
                    </span>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer border-0 pt-0 justify-content-between">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="bi bi-x me-1"></i>Tutup
                </button>
                {{-- Transisi mulus: tutup detail → buka edit --}}
                <button type="button" class="btn btn-success btn-detail-to-edit"
                    data-dismiss-modal="#modalDetailMatkul{{ $m->id }}"
                    data-id="{{ $m->id }}"
                    data-url="{{ route('matakuliah.update', $m->id) }}"
                    data-kode="{{ $m->kode_mk }}"
                    data-nama="{{ $m->nama_mk }}"
                    data-bobot="{{ $m->bobot }}"
                    data-jenis="{{ $m->jenis }}"
                    data-id-dosen="{{ $m->id_dosen }}"
                    data-mappings="{{ json_encode($m->prodiMappings->map(fn($mp) => ['prodi_id' => $mp->id_prodi, 'semester' => $mp->semester])) }}">
                    <i class="bi bi-pencil-fill me-2"></i>Edit
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Script transisi Detail → Edit (guard agar hanya diregister sekali) --}}
@once
<script>
(function waitForjQuery() {
    if (typeof jQuery === 'undefined') { setTimeout(waitForjQuery, 50); return; }
    jQuery(function($) {
        // Tombol "Edit" di dalam modal detail
        $(document).on('click', '.btn-detail-to-edit', function() {
            var $btn = $(this);
            var detailModalId = $btn.data('dismiss-modal');

            // Salin semua data-* ke tombol edit global (reuse handler yang sudah ada)
            var $fakeBtn = $('<button>')
                .attr('data-id',       $btn.data('id'))
                .attr('data-url',      $btn.data('url'))
                .attr('data-kode',     $btn.data('kode'))
                .attr('data-nama',     $btn.data('nama'))
                .attr('data-bobot',    $btn.data('bobot'))
                .attr('data-jenis',    $btn.data('jenis'))
                .attr('data-id-dosen', $btn.data('id-dosen'))
                .attr('data-mappings', JSON.stringify($btn.data('mappings')));

            var $detailModal = $(detailModalId);

            // Tutup detail dulu, lalu buka edit setelah backdrop hilang
            $detailModal.one('hidden.bs.modal', function() {
                // Bersihkan backdrop sisa (mencegah scroll freeze)
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('overflow', '').css('padding-right', '');

                // Trigger klik pada tombol edit global via event
                setTimeout(function() {
                    $('#modalEditMatkulGlobal').find('#editMatkulUrl').val($fakeBtn.attr('data-url'));
                    $fakeBtn.addClass('btn-open-edit-matkul').appendTo('body');
                    $fakeBtn.trigger('click');
                    // Hapus setelah dipakai
                    setTimeout(function() { $fakeBtn.remove(); }, 500);
                }, 50);
            });

            $detailModal.modal('hide');
        });
    });
})();
</script>
@endonce
