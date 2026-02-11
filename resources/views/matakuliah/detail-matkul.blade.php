<!-- Modal Detail Mata Kuliah -->
<div class="modal fade" id="modalDetailMatkul{{ $m->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">Kode MK</dt>
                    <dd class="col-sm-8">{{ $m->kode_mk }}</dd>
                    <dt class="col-sm-4">Nama Mata Kuliah</dt>
                    <dd class="col-sm-8">{{ $m->nama_mk }}</dd>
                    <dt class="col-sm-4">Bobot</dt>
                    <dd class="col-sm-8">{{ $m->bobot }}</dd>
                    <dt class="col-sm-4">Jenis</dt>
                    <dd class="col-sm-8">{{ ucfirst($m->jenis) }}</dd>
                    <dt class="col-sm-4">Prodi</dt>
                    <dd class="col-sm-8">{{ $m->prodi->nama_prodi ?? '-' }}</dd>
                    <dt class="col-sm-4">Dosen</dt>
                    <dd class="col-sm-8">{{ $m->dosen->user->nama ?? '-' }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>