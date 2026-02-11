<!-- Modal Delete Jadwal -->
    <div class="modal fade" id="modalDeleteJadwal{{ $j->id }}" tabindex="-1" aria-labelledby="modalDeleteJadwalLabel{{ $j->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('jadwal.destroy', $j->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Jadwal Kuliah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p>Yakin ingin menghapus jadwal kuliah <b>{{ $j->matkul->nama_mk ?? '-' }}</b>?</p>
                        <div class="alert alert-light-warning border-warning">
                            <strong>{{ $j->matkul->nama_mk ?? '-' }}</strong><br>
                            <small class="text-muted">
                                {{ $j->hari }}, {{ \Carbon\Carbon::createFromFormat('H:i:s', $j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $j->jam_selesai)->format('H:i') }}<br>
                                Ruangan: {{ $j->ruangan->nama_ruangan ?? '-' }}<br>
                                Rombel: {{ $j->rombel->nama_rombel ?? '-' }}
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>