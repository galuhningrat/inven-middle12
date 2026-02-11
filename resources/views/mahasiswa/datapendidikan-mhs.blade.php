@extends('mahasiswa.detail-mahasiswa')

@section('pendidikan')
    <div class="card-header cursor-pointer">
        <div class="card-title m-0">
            <h3 class="fw-bolder m-0">Riwayat Pendidikan</h3>
        </div>
        <button type="button" class="btn btn-primary align-self-center" data-bs-toggle="modal"
            data-bs-target="#modalTambahPendidikan">
            <i class="bi bi-plus-circle me-2"></i>Tambah
        </button>
    </div>

    <div class="card-body p-9">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($mahasiswa->pendidikan && $mahasiswa->pendidikan->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr class="fw-bold text-uppercase">
                            <th class="text-center" style="width: 5%;">No</th>
                            <th style="width: 12%;">Jenjang</th>
                            <th style="width: 25%;">Nama Sekolah</th>
                            <th style="width: 15%;">Jurusan</th>
                            <th class="text-center" style="width: 8%;">Tahun Lulus</th>
                            <th class="text-center" style="width: 10%;">Ijazah</th>
                            <th class="text-center" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mahasiswa->pendidikan->sortBy('tahun_lulus') as $pend)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td><span class="badge badge-primary fs-7">{{ $pend->jenjang }}</span></td>
                                <td>{{ $pend->nama_sekolah }}</td>
                                <td>{{ $pend->jurusan ?? '-' }}</td>
                                <td class="text-center"><span class="badge badge-light-info">{{ $pend->tahun_lulus }}</span>
                                </td>
                                <td class="text-center">
                                    @if ($pend->file_ijazah)
                                        @php
                                            $fileUrl = asset('storage/' . $pend->file_ijazah);
                                            $fileExists = Storage::disk('public')->exists($pend->file_ijazah);
                                        @endphp
                                        @if ($fileExists)
                                            <button type="button" class="btn btn-icon btn-sm btn-success"
                                                onclick="window.previewFile('{{ $fileUrl }}', '{{ addslashes($pend->nama_sekolah) }}')"
                                                title="Lihat File">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </button>
                                        @else
                                            <span class="badge badge-light-danger">File Hilang</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-icon btn-sm btn-light-info me-1"
                                        onclick="window.showDetail({{ $pend->id }})" title="Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-icon btn-sm btn-light-success me-1"
                                        onclick="window.editData({{ $pend->id }})" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <form
                                        action="{{ route('mahasiswa.pendidikan.destroy', [$mahasiswa->nim, $pend->id]) }}"
                                        method="POST" style="display:inline;"
                                        onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-sm btn-light-danger" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-10">
                <div class="symbol symbol-100px mb-5">
                    <span class="symbol-label bg-light-primary">
                        <i class="bi bi-mortarboard fs-1 text-primary"></i>
                    </span>
                </div>
                <h4 class="fw-bold mb-2">Belum Ada Riwayat Pendidikan</h4>
                <p class="text-muted mb-5">Klik tombol "Tambah" untuk menambahkan riwayat pendidikan dari SD hingga SMA/SMK
                </p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPendidikan">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Riwayat Pertama
                </button>
            </div>
        @endif
    </div>

    <!-- MODAL TAMBAH - Enhanced Design -->
    <div class="modal fade" id="modalTambahPendidikan" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow">
                <form action="{{ route('mahasiswa.pendidikan.store', $mahasiswa->nim) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary">
                        <h3 class="modal-title text-white">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Riwayat Pendidikan
                        </h3>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body px-7 py-8">
                        <div class="row g-5 mb-7">
                            <div class="col-md-6">
                                <label class="form-label required fw-bold">Jenjang Pendidikan</label>
                                <select name="jenjang" class="form-select form-select-lg" required>
                                    <option value="">-- Pilih Jenjang --</option>
                                    <optgroup label="Pendidikan Tinggi">
                                        <option value="Universitas">Universitas</option>
                                        <option value="Institut">Institut</option>
                                        <option value="Politeknik">Politeknik</option>
                                        <option value="Sekolah Tinggi">Sekolah Tinggi</option>
                                        <option value="Akademi Komunitas">Akademi Komunitas</option>
                                    </optgroup>
                                    <optgroup label="Menengah Atas (Utama)">
                                        <option value="SMA">SMA (Sekolah Menengah Atas)</option>
                                        <option value="SMK">SMK (Sekolah Menengah Kejuruan)</option>
                                        <option value="MA">MA (Madrasah Aliyah)</option>
                                        <option value="MAK">MAK (Madrasah Aliyah Kejuruan)</option>
                                        <option value="Paket C">Paket C (Kesetaraan SMA)</option>
                                        <option value="SMAK">SMAK / SMAg.K</option>
                                        <option value="SMTK">SMTK (Teologi Kristen)</option>
                                        <option value="Utama Widyalaya">Utama Widyalaya (Hindu)</option>
                                        <option value="PDF Ulya">PDF Ulya / SPM Ulya</option>
                                    </optgroup>
                                    <optgroup label="Pendidikan Dasar">
                                        <option value="SD">SD / MI / SDTK</option>
                                        <option value="SMP">SMP / MTs / SMPTK</option>
                                        <option value="Paket A">Paket A (Setara SD)</option>
                                        <option value="Paket B">Paket B (Setara SMP)</option>
                                    </optgroup>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required fw-bold">Tahun Lulus</label>
                                <input type="number" name="tahun_lulus" class="form-control form-control-lg"
                                    min="1990" max="{{ date('Y') }}" placeholder="Contoh: 2020" required>
                            </div>
                        </div>
                        <div class="mb-7">
                            <label class="form-label required fw-bold">Nama Sekolah</label>
                            <input type="text" name="nama_sekolah" class="form-control form-control-lg"
                                placeholder="Contoh: SDN 1 Jakarta" required>
                        </div>
                        <div class="mb-7">
                            <label class="form-label fw-bold">Jurusan/Peminatan</label>
                            <input type="text" name="jurusan" class="form-control form-control-lg"
                                placeholder="Kosongkan jika SD/SMP">
                        </div>
                        <div class="mb-7">
                            <label class="form-label fw-bold">Alamat Sekolah</label>
                            <textarea name="alamat_sekolah" class="form-control" rows="3" placeholder="Alamat lengkap sekolah..."></textarea>
                        </div>
                        <div class="mb-7">
                            <label class="form-label fw-bold">No Ijazah</label>
                            <input type="text" name="no_ijazah" class="form-control form-control-lg"
                                placeholder="Nomor ijazah (opsional)">
                        </div>
                        <div class="mb-7">
                            <label class="form-label fw-bold d-flex align-items-center">
                                <i class="bi bi-paperclip me-2"></i>Upload Ijazah
                            </label>
                            <input type="file" name="file_ijazah" class="form-control form-control-lg"
                                accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text text-muted mt-2">
                                <i class="bi bi-info-circle me-1"></i>Format: PDF, JPG, PNG (Maksimal 2MB)
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT - Enhanced Design -->
    <div class="modal fade" id="modalEditPendidikan" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow">
                <form id="formEdit" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-success">
                        <h3 class="modal-title text-white">
                            <i class="bi bi-pencil-square me-2"></i>Edit Riwayat Pendidikan
                        </h3>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body px-7 py-8">
                        <div class="row g-5 mb-7">
                            <div class="col-md-6">
                                <label class="form-label required fw-bold">Jenjang Pendidikan</label>
                                <select name="jenjang" id="editJenjang" class="form-select form-select-lg" required>
                                    <option value="">-- Pilih Jenjang --</option>
                                    <optgroup label="Pendidikan Tinggi">
                                        <option value="Universitas">Universitas</option>
                                        <option value="Institut">Institut</option>
                                        <option value="Politeknik">Politeknik</option>
                                        <option value="Sekolah Tinggi">Sekolah Tinggi</option>
                                        <option value="Akademi Komunitas">Akademi Komunitas</option>
                                    </optgroup>
                                    <optgroup label="Menengah Atas (Utama)">
                                        <option value="SMA">SMA (Sekolah Menengah Atas)</option>
                                        <option value="SMK">SMK (Sekolah Menengah Kejuruan)</option>
                                        <option value="MA">MA (Madrasah Aliyah)</option>
                                        <option value="MAK">MAK (Madrasah Aliyah Kejuruan)</option>
                                        <option value="Paket C">Paket C (Kesetaraan SMA)</option>
                                        <option value="SMAK">SMAK / SMAg.K</option>
                                        <option value="SMTK">SMTK (Teologi Kristen)</option>
                                        <option value="Utama Widyalaya">Utama Widyalaya (Hindu)</option>
                                        <option value="PDF Ulya">PDF Ulya / SPM Ulya</option>
                                    </optgroup>
                                    <optgroup label="Pendidikan Dasar">
                                        <option value="SD">SD / MI / SDTK</option>
                                        <option value="SMP">SMP / MTs / SMPTK</option>
                                        <option value="Paket A">Paket A (Setara SD)</option>
                                        <option value="Paket B">Paket B (Setara SMP)</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required fw-bold">Tahun Lulus</label>
                                <input type="number" name="tahun_lulus" id="editTahun"
                                    class="form-control form-control-lg" required>
                            </div>
                        </div>
                        <div class="mb-7">
                            <label class="form-label required fw-bold">Nama Sekolah</label>
                            <input type="text" name="nama_sekolah" id="editNama"
                                class="form-control form-control-lg" required>
                        </div>
                        <div class="mb-7">
                            <label class="form-label fw-bold">Jurusan</label>
                            <input type="text" name="jurusan" id="editJurusan" class="form-control form-control-lg">
                        </div>
                        <div class="mb-7">
                            <label class="form-label fw-bold">Alamat Sekolah</label>
                            <textarea name="alamat_sekolah" id="editAlamat" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-7">
                            <label class="form-label fw-bold">No Ijazah</label>
                            <input type="text" name="no_ijazah" id="editNoIjazah"
                                class="form-control form-control-lg">
                        </div>
                        <div class="mb-7">
                            <div id="currentFile" class="mb-3"></div>
                            <label class="form-label fw-bold d-flex align-items-center">
                                <i class="bi bi-paperclip me-2"></i>Ganti File Ijazah (Opsional)
                            </label>
                            <input type="file" name="file_ijazah" class="form-control form-control-lg"
                                accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text text-muted mt-2">
                                <i class="bi bi-info-circle me-1"></i>Kosongkan jika tidak ingin mengubah file
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DETAIL - Enhanced Design -->
    <div class="modal fade" id="modalDetail" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h3 class="modal-title text-white">
                        <i class="bi bi-file-earmark-text me-2"></i>Detail Riwayat Pendidikan
                    </h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-8">
                    <div class="card border border-dashed border-primary mb-5">
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-5 text-muted fw-bold">Jenjang</div>
                                <div class="col-7" id="detailJenjang">-</div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-5 text-muted fw-bold">Nama Sekolah</div>
                                <div class="col-7 fw-bold" id="detailNama">-</div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-5 text-muted fw-bold">Jurusan/Peminatan</div>
                                <div class="col-7" id="detailJurusan">-</div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-5 text-muted fw-bold">Tahun Lulus</div>
                                <div class="col-7" id="detailTahun">-</div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-5 text-muted fw-bold">Alamat Sekolah</div>
                                <div class="col-7" id="detailAlamat">-</div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-5 text-muted fw-bold">No Ijazah</div>
                                <div class="col-7" id="detailNoIjazah">-</div>
                            </div>
                            <div class="row">
                                <div class="col-5 text-muted fw-bold">File Ijazah</div>
                                <div class="col-7" id="detailFile">-</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PREVIEW - Enhanced Design -->
    <div class="modal fade" id="modalPreview" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h3 id="previewTitle" class="text-white">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Preview Ijazah
                    </h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0" style="height: calc(100vh - 140px);">
                    <iframe id="previewFrame" style="width:100%;height:100%;border:none;"></iframe>
                </div>
                <div class="modal-footer bg-dark">
                    <a id="downloadBtn" class="btn btn-success" download>
                        <i class="bi bi-download me-2"></i>Download File
                    </a>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        (function() {
            const NIM = "{{ $mahasiswa->nim }}";

            window.editData = function(id) {
                fetch(`/mahasiswa/${NIM}/pendidikan/${id}/edit`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('editJenjang').value = data.jenjang || '';
                        document.getElementById('editNama').value = data.nama_sekolah || '';
                        document.getElementById('editJurusan').value = data.jurusan || '';
                        document.getElementById('editAlamat').value = data.alamat_sekolah || '';
                        document.getElementById('editTahun').value = data.tahun_lulus || '';
                        document.getElementById('editNoIjazah').value = data.no_ijazah || '';

                        if (data.file_ijazah) {
                            const fileUrl = `/storage/${data.file_ijazah}`;
                            const fileName = data.file_ijazah.split('/').pop();
                            const fileExt = fileName.split('.').pop().toLowerCase();

                            let iconClass = 'bi-file-earmark-pdf';
                            let badgeClass = 'badge-success';

                            if (['jpg', 'jpeg', 'png'].includes(fileExt)) {
                                iconClass = 'bi-file-earmark-image';
                                badgeClass = 'badge-info';
                            }

                            document.getElementById('currentFile').innerHTML = `
                                <div class="alert alert-info d-flex align-items-center">
                                    <i class="bi ${iconClass} fs-2x me-4"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">File Saat Ini</h5>
                                        <span class="badge ${badgeClass}">${fileName}</span>
                                    </div>
                                    <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-light-primary">
                                        <i class="bi bi-eye me-1"></i>Lihat
                                    </a>
                                </div>
                            `;
                        } else {
                            document.getElementById('currentFile').innerHTML = `
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Belum ada file yang diupload
                                </div>
                            `;
                        }

                        document.getElementById('formEdit').action = `/mahasiswa/${NIM}/pendidikan/${id}`;
                        new bootstrap.Modal(document.getElementById('modalEditPendidikan')).show();
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        alert('Gagal memuat data: ' + err.message);
                    });
            };

            window.showDetail = function(id) {
                fetch(`/mahasiswa/${NIM}/pendidikan/${id}/edit`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('detailJenjang').innerHTML =
                            `<span class="badge badge-lg badge-primary">${data.jenjang}</span>`;
                        document.getElementById('detailNama').textContent = data.nama_sekolah || '-';
                        document.getElementById('detailJurusan').textContent = data.jurusan || '-';
                        document.getElementById('detailTahun').innerHTML =
                            `<span class="badge badge-light-info">${data.tahun_lulus}</span>`;
                        document.getElementById('detailAlamat').textContent = data.alamat_sekolah || '-';
                        document.getElementById('detailNoIjazah').textContent = data.no_ijazah || '-';

                        if (data.file_ijazah) {
                            const fileUrl = `/storage/${data.file_ijazah}`;
                            const fileName = data.file_ijazah.split('/').pop();
                            document.getElementById('detailFile').innerHTML = `
                                <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-success">
                                    <i class="bi bi-file-earmark-pdf me-2"></i>${fileName}
                                </a>
                            `;
                        } else {
                            document.getElementById('detailFile').innerHTML =
                                '<span class="badge badge-light-secondary">Belum upload</span>';
                        }

                        new bootstrap.Modal(document.getElementById('modalDetail')).show();
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        alert('Gagal memuat detail: ' + err.message);
                    });
            };

            window.previewFile = function(url, nama) {
                console.log('Opening preview:', url);
                document.getElementById('previewTitle').innerHTML =
                    `<i class="bi bi-file-earmark-pdf me-2"></i>Ijazah - ${nama}`;
                document.getElementById('previewFrame').src = url;
                document.getElementById('downloadBtn').href = url;
                new bootstrap.Modal(document.getElementById('modalPreview')).show();
            };
        })();
    </script>
@endsection
