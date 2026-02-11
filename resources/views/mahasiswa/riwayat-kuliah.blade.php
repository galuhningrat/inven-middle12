@extends('mahasiswa.detail-mahasiswa')

@section('riwayat-kuliah')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3 class="fw-bolder m-0">Riwayat Kuliah</h3>
            </div>
            <button class="btn btn-primary align-self-center" data-bs-toggle="modal" data-bs-target="#modalTambahRiwayat">
                <i class="bi bi-plus-circle me-2"></i>Tambah Riwayat
            </button>
        </div>

        <div class="card-body p-9">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($mahasiswa->riwayatKuliah && $mahasiswa->riwayatKuliah->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr class="fw-bold text-uppercase">
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 20%;">Kampus Asal</th>
                                <th style="width: 20%;">Program Studi</th>
                                <th class="text-center" style="width: 10%;">Tahun Masuk</th>
                                <th class="text-center" style="width: 10%;">Tahun Keluar</th>
                                <th class="text-center" style="width: 10%;">Jenis</th>
                                <th style="width: 15%;">Alasan</th>
                                <th class="text-center" style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mahasiswa->riwayatKuliah as $riwayat)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $riwayat->kampus_asal }}</td>
                                    <td>{{ $riwayat->prodi_asal }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-light-info">{{ $riwayat->tahun_masuk }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-light-warning">{{ $riwayat->tahun_keluar }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-primary">{{ $riwayat->jenis }}</span>
                                    </td>
                                    <td>{{ Str::limit($riwayat->alasan ?? '-', 50) }}</td>
                                    <td class="text-center">
                                        @if ($riwayat->file_transkrip)
                                            <a href="{{ asset('storage/' . $riwayat->file_transkrip) }}" target="_blank"
                                                class="btn btn-icon btn-sm btn-light-success me-1" title="Lihat Transkrip">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                        @endif
                                        <form
                                            action="{{ route('mahasiswa.riwayat-kuliah.destroy', [$mahasiswa->nim, $riwayat->id]) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-sm btn-light-danger"
                                                onclick="return confirm('Yakin ingin menghapus riwayat ini?')"
                                                title="Hapus">
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
                    <div class="symbol symbol-60px mb-3">
                        <span class="symbol-label bg-light rounded-4">
                            <i class="bi bi-journal-bookmark fs-1"></i>
                        </span>
                    </div>
                    <div class="fw-semibold mb-1">Belum Ada Riwayat Kuliah</div>
                    <div class="text-muted mb-3">Mahasiswa ini tidak memiliki riwayat kuliah sebelumnya (bukan mahasiswa
                        transfer)</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Tambah Riwayat -->
    <div class="modal fade" id="modalTambahRiwayat" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('mahasiswa.riwayat-kuliah.store', $mahasiswa->nim) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header">
                        <h3 class="modal-title">Tambah Riwayat Kuliah</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label required">Kampus Asal</label>
                                <input type="text" name="kampus_asal" class="form-control"
                                    placeholder="Contoh: Universitas Negeri Semarang" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Program Studi Asal</label>
                                <input type="text" name="prodi_asal" class="form-control"
                                    placeholder="Contoh: Teknik Informatika" required>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-4">
                                <label class="form-label required">Tahun Masuk</label>
                                <input type="number" name="tahun_masuk" class="form-control" min="1990"
                                    max="{{ date('Y') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Tahun Keluar</label>
                                <input type="number" name="tahun_keluar" class="form-control" min="1990"
                                    max="{{ date('Y') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Jenis</label>
                                <select name="jenis" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Transfer">Transfer</option>
                                    <option value="Pindahan">Pindahan</option>
                                    <option value="Lanjutan">Lanjutan</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Alasan Pindah/Transfer</label>
                            <textarea name="alasan" class="form-control" rows="3" placeholder="Jelaskan alasan pindah/transfer..."></textarea>
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Upload Transkrip Nilai</label>
                            <input type="file" name="file_transkrip" class="form-control" accept=".pdf">
                            <div class="form-text">Format: PDF (Maksimal 2MB)</div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
