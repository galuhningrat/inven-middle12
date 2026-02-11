@extends('mahasiswa.detail-mahasiswa')

@section('kelengkapan-dokumen')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3 class="fw-bolder m-0">Kelengkapan Berkas Mahasiswa</h3>
            </div>
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

            <!-- Progress Kelengkapan -->
            <div class="row g-5 mb-8">
                <div class="col-md-6">
                    <div class="border border-primary border-dashed rounded p-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-folder-check fs-1 text-primary me-3"></i>
                            <div>
                                <h4 class="mb-0">Hardfile (Berkas Fisik)</h4>
                                <p class="text-muted mb-0">Kelengkapan dokumen fisik yang diserahkan</p>
                            </div>
                        </div>
                        <div class="progress h-20px mb-3">
                            <div class="progress-bar bg-primary" role="progressbar"
                                style="width: {{ $mahasiswa->kelengkapan_hardfile }}%"
                                aria-valuenow="{{ $mahasiswa->kelengkapan_hardfile }}" aria-valuemin="0"
                                aria-valuemax="100">
                                {{ $mahasiswa->kelengkapan_hardfile }}%
                            </div>
                        </div>
                        <p class="text-muted mb-0">
                            {{ collect([
                                $mahasiswa->hardfile_surat_pernyataan,
                                $mahasiswa->hardfile_pas_foto,
                                $mahasiswa->hardfile_ktp_mhs,
                                $mahasiswa->hardfile_kk,
                                $mahasiswa->hardfile_akte,
                                $mahasiswa->hardfile_ktp_ayah,
                                $mahasiswa->hardfile_ktp_ibu,
                                $mahasiswa->hardfile_skl,
                                $mahasiswa->hardfile_transkrip,
                                $mahasiswa->hardfile_ijazah,
                            ])->filter()->count() }}
                            dari 10 dokumen terpenuhi
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border border-success border-dashed rounded p-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-file-earmark-arrow-up fs-1 text-success me-3"></i>
                            <div>
                                <h4 class="mb-0">Softfile (Berkas Digital)</h4>
                                <p class="text-muted mb-0">Dokumen yang sudah diupload ke sistem</p>
                            </div>
                        </div>
                        <div class="progress h-20px mb-3">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $mahasiswa->kelengkapan_softfile }}%"
                                aria-valuenow="{{ $mahasiswa->kelengkapan_softfile }}" aria-valuemin="0"
                                aria-valuemax="100">
                                {{ $mahasiswa->kelengkapan_softfile }}%
                            </div>
                        </div>
                        <p class="text-muted mb-0">
                            {{ collect([
                                $mahasiswa->softfile_surat_pernyataan,
                                $mahasiswa->softfile_pas_foto,
                                $mahasiswa->softfile_ktp_mhs,
                                $mahasiswa->softfile_kk,
                                $mahasiswa->softfile_akte,
                                $mahasiswa->softfile_ktp_ayah,
                                $mahasiswa->softfile_ktp_ibu,
                                $mahasiswa->softfile_skl,
                                $mahasiswa->softfile_transkrip,
                                $mahasiswa->softfile_ijazah,
                            ])->filter()->count() }}
                            dari 10 dokumen terupload
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tabel Kelengkapan -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr class="fw-bold text-uppercase">
                            <th style="width: 5%;">No</th>
                            <th style="width: 25%;">Jenis Dokumen</th>
                            <th class="text-center" style="width: 15%;">Hardfile<br><small
                                    class="text-muted">(Fisik)</small></th>
                            <th class="text-center" style="width: 15%;">Softfile<br><small
                                    class="text-muted">(Digital)</small></th>
                            <th class="text-center" style="width: 20%;">File Terupload</th>
                            <th class="text-center" style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jenisDokumen as $key => $label)
                            @php
                                $hardfileField = "hardfile_$key";
                                $softfileField = "softfile_$key";
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-text fs-3 text-info me-3"></i>
                                        <span class="fw-semibold">{{ $label }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch form-check-custom form-check-solid d-inline-block">
                                        <input class="form-check-input" type="checkbox" id="hardfile_{{ $key }}"
                                            {{ $mahasiswa->$hardfileField ? 'checked' : '' }}
                                            onchange="toggleHardfile('{{ $key }}', this.checked)">
                                        <label class="form-check-label" for="hardfile_{{ $key }}">
                                            @if ($mahasiswa->$hardfileField)
                                                <span class="badge badge-success">Sudah</span>
                                            @else
                                                <span class="badge badge-secondary">Belum</span>
                                            @endif
                                        </label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if ($mahasiswa->$softfileField)
                                        <span class="badge badge-success">
                                            <i class="bi bi-check-circle me-1"></i>Terupload
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            <i class="bi bi-exclamation-circle me-1"></i>Belum
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($mahasiswa->$softfileField)
                                        <a href="{{ asset('storage/' . $mahasiswa->$softfileField) }}" target="_blank"
                                            class="btn btn-icon btn-sm btn-light-success me-1" title="Lihat File">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-icon btn-sm btn-light-primary"
                                            onclick="reuploadFile('{{ $key }}')" title="Ganti File">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-primary"
                                            onclick="uploadFile('{{ $key }}')">
                                            <i class="bi bi-cloud-upload me-1"></i>Upload
                                        </button>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($mahasiswa->$softfileField)
                                        <form
                                            action="{{ route('mahasiswa.kelengkapan-dokumen.delete', [$mahasiswa->nim, $key]) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-sm btn-light-danger"
                                                onclick="return confirm('Yakin hapus file ini?')" title="Hapus File">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Info -->
            <div class="alert alert-light-info d-flex align-items-center mt-8">
                <i class="bi bi-info-circle fs-2 me-3"></i>
                <div>
                    <strong>Catatan:</strong>
                    <ul class="mb-0 mt-2">
                        <li><strong>Hardfile</strong>: Centang jika dokumen fisik sudah diserahkan ke admin</li>
                        <li><strong>Softfile</strong>: Upload dokumen dalam format PDF/JPG/PNG (Max 2MB)</li>
                        <li>Pastikan semua dokumen telah lengkap sebelum proses administrasi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload -->
    <div class="modal fade" id="modalUploadDokumen" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formUploadDokumen"
                    action="{{ route('mahasiswa.kelengkapan-dokumen.upload', $mahasiswa->nim) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="jenis_dokumen" id="jenis_dokumen">

                    <div class="modal-header">
                        <h3 class="modal-title">Upload Dokumen</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-5">
                            <label class="form-label fw-bold">Jenis Dokumen</label>
                            <input type="text" id="label_dokumen" class="form-control-plaintext fw-bold" readonly>
                        </div>
                        <div class="mb-5">
                            <label class="form-label required">Pilih File</label>
                            <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png"
                                required>
                            <div class="form-text">Format: PDF, JPG, PNG (Max 2MB)</div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="hardfile_status" value="1"
                                id="hardfile_status">
                            <label class="form-check-label" for="hardfile_status">
                                Tandai bahwa hardfile (berkas fisik) juga sudah diserahkan
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cloud-upload me-2"></i>Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function uploadFile(jenis) {
            const labels = {
                'surat_pernyataan': 'Surat Pernyataan',
                'pas_foto': 'Pas Foto',
                'ktp_mhs': 'KTP Mahasiswa',
                'kk': 'Kartu Keluarga',
                'akte': 'Akte Kelahiran',
                'ktp_ayah': 'KTP Ayah',
                'ktp_ibu': 'KTP Ibu',
                'skl': 'Surat Keterangan Lulus',
                'transkrip': 'Transkrip Nilai',
                'ijazah': 'Ijazah'
            };

            document.getElementById('jenis_dokumen').value = jenis;
            document.getElementById('label_dokumen').value = labels[jenis];
            document.getElementById('hardfile_status').checked = false;

            const modal = new bootstrap.Modal(document.getElementById('modalUploadDokumen'));
            modal.show();
        }

        function reuploadFile(jenis) {
            if (confirm('File lama akan diganti. Lanjutkan?')) {
                uploadFile(jenis);
            }
        }

        async function toggleHardfile(jenis, status) {
            const checkbox = document.getElementById(`hardfile_${jenis}`);
            const badge = checkbox.nextElementSibling.querySelector('.badge');

            try {
                const response = await fetch("{{ route('mahasiswa.kelengkapan-dokumen.toggle', $mahasiswa->nim) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        jenis_dokumen: jenis,
                        status: status ? 1 : 0
                    })
                });

                if (!response.ok) throw new Error(`HTTP ${response.status}`);

                const data = await response.json();

                if (data.success) {
                    // ✅ Update badge
                    if (status) {
                        badge.className = 'badge badge-success';
                        badge.innerHTML = '<i class="bi bi-check-circle me-1"></i>Sudah';
                    } else {
                        badge.className = 'badge badge-secondary';
                        badge.textContent = 'Belum';
                    }

                    // ✅ Update progress bar
                    const progressBar = document.querySelector('.progress-bar.bg-primary');
                    progressBar.style.width = data.kelengkapan_hardfile + '%';
                    progressBar.textContent = data.kelengkapan_hardfile + '%';
                    progressBar.setAttribute('aria-valuenow', data.kelengkapan_hardfile);

                    // ✅ Update counter text
                    const counterText = progressBar.closest('.border').querySelector('.text-muted');
                    const count = Math.round(data.kelengkapan_hardfile / 10);
                    counterText.textContent = `${count} dari 10 dokumen terpenuhi`;

                    // Success notification
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Status hardfile berhasil diupdate',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                checkbox.checked = !status; // Revert checkbox

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan: ' + error.message
                });
            }
        }
        const checkbox = document.getElementById(`hardfile_${jenis}`);

        try {
            const response = await fetch("{{ route('mahasiswa.kelengkapan-dokumen.toggle', $mahasiswa->nim) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    jenis_dokumen: jenis,
                    status: status ? 1 : 0
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                // ✅ Update badge
                const label = checkbox.nextElementSibling.querySelector('.badge');
                if (status) {
                    label.className = 'badge badge-success';
                    label.innerHTML = '<i class="bi bi-check-circle me-1"></i>Sudah';
                } else {
                    label.className = 'badge badge-secondary';
                    label.textContent = 'Belum';
                }

                // ✅ Update progress bar
                const progressBar = document.querySelector('.progress-bar.bg-primary');
                if (progressBar) {
                    progressBar.style.width = data.kelengkapan_hardfile + '%';
                    progressBar.textContent = data.kelengkapan_hardfile + '%';
                    progressBar.setAttribute('aria-valuenow', data.kelengkapan_hardfile);
                }

                // ✅ Show success notification
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Status hardfile berhasil diupdate',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        } catch (error) {
            console.error('Error:', error);

            // ✅ Revert checkbox on error
            checkbox.checked = !status;

            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat update status: ' + error.message
            });
        }
        }
    </script>
@endpush
