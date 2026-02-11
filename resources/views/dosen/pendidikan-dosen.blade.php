@extends('dosen.detail-dosen')

@section('pendidikan')
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder m-0">Riwayat Pendidikan</h3>
        </div>
        <button class="btn btn-primary align-self-center" data-bs-toggle="modal" data-bs-target="#modalPendidikan"
            id="btn-tambah-pendidikan"><i class="bi bi-plus-circle me-2"></i>Tambah
        </button>
    </div>

    <div class="card-body p-9">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr class="fw-bold text-uppercase">
                        <th class="text-center" style="width: 5%;">No</th>
                        <th style="width: 10%;">Jenjang</th>
                        <th style="width: 10%;">Gelar</th>
                        <th style="width: 25%;">Nama Perguruan Tinggi</th>
                        <th style="width: 20%;">Jurusan</th>
                        <th class="text-center" style="width: 10%;">Tahun Lulus</th>
                        <th class="text-center" style="width: 10%;">Ijazah</th>
                        <th class="text-center" style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendidikan as $p)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge badge-primary">{{ $p->jenjang }}</span>
                            </td>
                            <td>{{ $p->gelar ?? '-' }}</td>
                            <td>{{ $p->nama_pt }}</td>
                            <td>{{ $p->jurusan }}</td>
                            <td class="text-center">
                                <span class="badge badge-light-info">{{ $p->tahun_lulus }}</span>
                            </td>
                            <td class="text-center">
                                @if($p->ijazah)
                                    <a href="{{ asset('storage/' . $p->ijazah) }}" target="_blank"
                                        class="btn btn-sm btn-light-success" data-bs-toggle="tooltip" title="Lihat Ijazah">
                                        <i class="bi bi-file-earmark-pdf"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-icon btn-sm btn-light-warning me-1 btn-edit-pendidikan"
                                    data-id="{{ $p->id }}" data-jenjang="{{ $p->jenjang }}" data-gelar="{{ $p->gelar }}"
                                    data-nama_pt="{{ $p->nama_pt }}" data-jurusan="{{ $p->jurusan }}"
                                    data-tahun_lulus="{{ $p->tahun_lulus }}" data-ijazah="{{ $p->ijazah }}"
                                    data-bs-toggle="modal" data-bs-target="#modalPendidikan" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <form action="{{ route('dosen.pendidikan.destroy', [$dosen->id, $p->id]) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-sm btn-light-danger"
                                        onclick="return confirm('Yakin ingin menghapus riwayat pendidikan ini?\n\nData yang dihapus tidak dapat dikembalikan.')"
                                        title="Hapus">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-inbox fs-3x text-muted mb-3"></i>
                                    <h5 class="text-muted mb-2">Belum Ada Riwayat Pendidikan</h5>
                                    <p class="text-muted mb-3">Klik tombol "Tambah" untuk menambahkan riwayat pendidikan</p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalPendidikan">
                                        <i class="bi bi-plus-circle me-2"></i>Tambah Riwayat Pendidikan
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal tambah/edit pendidikan -->
    <div class="modal fade" id="modalPendidikan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="formPendidikan" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="pendidikan-method" value="POST">
                    <input type="hidden" name="pendidikan_id" id="pendidikan_id" value="">

                    <div class="modal-header">
                        <h3 class="modal-title" id="modalPendidikanTitle">Tambah Riwayat Pendidikan</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <label class="form-label required">Jenjang Pendidikan</label>
                                <select name="jenjang" id="jenjang" class="form-select form-select-solid" required>
                                    <option value="">-- Pilih Jenjang --</option>
                                    <option value="D3">D3 (Diploma 3)</option>
                                    <option value="D4">D4 (Diploma 4)</option>
                                    <option value="S1">S1 (Sarjana)</option>
                                    <option value="S2">S2 (Magister)</option>
                                    <option value="S3">S3 (Doktor)</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-5">
                                <label class="form-label">Gelar Akademik</label>
                                <input type="text" name="gelar" id="gelar" class="form-control form-control-solid"
                                    placeholder="Contoh: S.Kom, M.Kom, Dr.">
                                <small class="text-muted">Opsional - isi jika ada</small>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label required">Nama Perguruan Tinggi</label>
                            <input type="text" name="nama_pt" id="nama_pt" class="form-control form-control-solid"
                                placeholder="Contoh: Universitas Indonesia" required>
                        </div>

                        <div class="mb-5">
                            <label class="form-label required">Program Studi / Jurusan</label>
                            <input type="text" name="jurusan" id="jurusan" class="form-control form-control-solid"
                                placeholder="Contoh: Teknik Informatika" required>
                        </div>

                        <div class="mb-5">
                            <label class="form-label required">Tahun Lulus</label>
                            <input type="text" name="tahun_lulus" id="tahun_lulus" class="form-control form-control-solid"
                                placeholder="Contoh: 2020" maxlength="4" pattern="\d{4}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4)" required>
                            <small class="text-muted">Format: 4 digit angka (contoh: 2020)</small>
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Upload Ijazah</label>
                            <input type="file" name="ijazah" id="ijazah" class="form-control form-control-solid"
                                accept=".pdf,.jpg,.jpeg,.png">
                            <div id="ijazahLama" class="mt-2"></div>
                            <small class="text-muted">Format: PDF, JPG, JPEG, PNG (Maksimal 2MB)</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSimpanPendidikan">
                            <i class="bi bi-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('modalPendidikan');
            const form = document.getElementById('formPendidikan');
            const methodInput = document.getElementById('pendidikan-method');
            const pendidikanIdInput = document.getElementById('pendidikan_id');
            const title = document.getElementById('modalPendidikanTitle');
            const btnSimpan = document.getElementById('btnSimpanPendidikan');
            const ijazahLama = document.getElementById('ijazahLama');

            // Tambah
            document.getElementById('btn-tambah-pendidikan').addEventListener('click', function () {
                form.action = "{{ route('dosen.pendidikan.store', $dosen->id) }}";
                methodInput.value = 'POST';
                pendidikanIdInput.value = '';
                title.textContent = 'Tambah Riwayat Pendidikan';
                btnSimpan.innerHTML = '<i class="bi bi-save me-2"></i>Simpan';
                ijazahLama.innerHTML = '';
                form.reset();
            });

            // Edit
            document.querySelectorAll('.btn-edit-pendidikan').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const id = btn.getAttribute('data-id');
                    const jenjang = btn.getAttribute('data-jenjang');
                    const gelar = btn.getAttribute('data-gelar');
                    const nama_pt = btn.getAttribute('data-nama_pt');
                    const jurusan = btn.getAttribute('data-jurusan');
                    const tahun_lulus = btn.getAttribute('data-tahun_lulus');
                    const ijazah = btn.getAttribute('data-ijazah');

                    form.action = "{{ url('/dosen/' . $dosen->id . '/pendidikan') }}/" + id;
                    methodInput.value = 'PUT';
                    pendidikanIdInput.value = id;
                    title.textContent = 'Edit Riwayat Pendidikan';
                    btnSimpan.innerHTML = '<i class="bi bi-save me-2"></i>Update';

                    document.getElementById('jenjang').value = jenjang;
                    document.getElementById('gelar').value = gelar;
                    document.getElementById('nama_pt').value = nama_pt;
                    document.getElementById('jurusan').value = jurusan;
                    document.getElementById('tahun_lulus').value = tahun_lulus;

                    if (ijazah && ijazah !== 'null' && ijazah !== '') {
                        ijazahLama.innerHTML = `
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="bi bi-file-earmark-pdf fs-2 me-3"></i>
                                <div>
                                    <strong>File Ijazah Saat Ini:</strong><br>
                                    <a href="{{ asset('storage') }}/${ijazah}" target="_blank" class="text-primary">
                                        <i class="bi bi-eye me-1"></i>Lihat File
                                    </a>
                                </div>
                            </div>
                        `;
                    } else {
                        ijazahLama.innerHTML = '';
                    }
                });
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush