   <!-- Modal Detail Jadwal -->
    <div class="modal fade" id="modalDetailJadwal{{ $j->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
          <div class="modal-content">
              <div class="modal-header">
                 <h5 class="modal-title">Detail Jadwal Kuliah</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
              </div>
        <div class="modal-body">
          <dl class="row">
                    <dt class="col-sm-4">Mata Kuliah</dt>
                    <dd class="col-sm-8">{{ $j->matkul->nama_mk ?? '-' }}</dd>
                    <dt class="col-sm-4">Kode MK</dt>
                    <dd class="col-sm-8">{{ $j->matkul->kode_mk ?? '-' }}</dd>
                    <dt class="col-sm-4">SKS</dt>
                    <dd class="col-sm-8">{{ $j->matkul->bobot ?? '-' }}</dd>
                    <dt class="col-sm-4">Dosen</dt>
                    <dd class="col-sm-8">{{ $j->dosen->user->nama ?? '-' }}</dd>
                    <dt class="col-sm-4">Hari</dt>
                    <dd class="col-sm-8">{{ $j->hari }}</dd>
                    <dt class="col-sm-4">Waktu</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::createFromFormat('H:i:s', $j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $j->jam_selesai)->format('H:i') }}</dd>
                    <dt class="col-sm-4">Ruangan</dt>
                    <dd class="col-sm-8">{{ $j->ruangan->nama_ruang ?? '-' }}</dd>
                    <dt class="col-sm-4">Rombel</dt>
                    <dd class="col-sm-8">{{ $j->rombel->nama_rombel ?? '-' }}</dd>
                    <dt class="col-sm-4">Tahun Akademik</dt>
                    <dd class="col-sm-8">{{ $j->tahunAkademik->tahun_akademik ?? '-' }}</dd>
         </dl>
         </div>
    </div>
 </div>
</div>