@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Input Nilai Massal</h1>
                <span class="h-20px border-gray-200 border-start mx-4"></span>
            </div>
            <div class="d-flex align-items-center py-1">
                <a href="{{ route('nilai.index', ['id_jadwal' => $jadwal->id]) }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-fluid">

            @include('master.notification')

            <!-- Info Card -->
            <div class="card mb-5 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="mb-3">{{ $jadwal->matkul->nama_mk }}</h3>
                            <div class="d-flex flex-wrap gap-4">
                                <div>
                                    <span class="text-muted d-block">Kode Mata Kuliah</span>
                                    <span class="fw-bold">{{ $jadwal->matkul->kode_mk }}</span>
                                </div>
                                <div>
                                    <span class="text-muted d-block">SKS</span>
                                    <span class="fw-bold">{{ $jadwal->matkul->bobot }} SKS</span>
                                </div>
                                <div>
                                    <span class="text-muted d-block">Kelas</span>
                                    <span class="fw-bold">{{ $jadwal->rombel->nama_rombel }}</span>
                                </div>
                                <div>
                                    <span class="text-muted d-block">Hari / Waktu</span>
                                    <span class="fw-bold">{{ $jadwal->hari }} / {{ substr($jadwal->jam_mulai, 0, 5) }} -
                                        {{ substr($jadwal->jam_selesai, 0, 5) }}</span>
                                </div>
                                <div>
                                    <span class="text-muted d-block">Dosen Pengampu</span>
                                    <span class="fw-bold">{{ $jadwal->dosen->user->nama }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-center justify-content-end">
                            <div class="text-end">
                                <span class="text-muted d-block">Total Mahasiswa</span>
                                <span class="fs-2x fw-bold text-primary">{{ $mahasiswaList->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Input Nilai -->
            <form action="{{ route('nilai.store-massal') }}" method="POST" id="formInputNilai">
                @csrf
                <input type="hidden" name="id_jadwal" value="{{ $jadwal->id }}">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Mahasiswa & Input Nilai</h3>
                        <div class="card-toolbar">
                            <span class="text-muted">*Nilai dalam rentang 0-100</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="fw-bold text-uppercase text-center">
                                        <th style="width: 50px;">No</th>
                                        <th style="width: 120px;">NIM</th>
                                        <th class="text-start">Nama Mahasiswa</th>
                                        <th style="width: 100px;">Tugas<br><small class="text-muted">(20%)</small></th>
                                        <th style="width: 100px;">UTS<br><small class="text-muted">(30%)</small></th>
                                        <th style="width: 100px;">UAS<br><small class="text-muted">(35%)</small></th>
                                        <th style="width: 100px;">Praktikum<br><small class="text-muted">(10%)</small></th>
                                        <th style="width: 100px;">Kehadiran<br><small class="text-muted">(5%)</small></th>
                                        <th style="width: 100px;">Absensi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mahasiswaList as $mhs)
                                        @php
                                            $existingNilai = \App\Models\Nilai::where('id_mahasiswa', $mhs->id)
                                                ->where('id_jadwal', $jadwal->id)
                                                ->first();
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center fw-bold">{{ $mhs->nim }}</td>
                                            <td>
                                                {{ $mhs->user->nama }}
                                                <input type="hidden" name="nilai[{{ $loop->index }}][id_mahasiswa]"
                                                    value="{{ $mhs->id }}">
                                            </td>
                                            <td class="p-2">
                                                <input type="number" name="nilai[{{ $loop->index }}][nilai_tugas]"
                                                    class="form-control form-control-sm text-center" min="0"
                                                    max="100" step="0.01"
                                                    value="{{ $existingNilai->nilai_tugas ?? '' }}" placeholder="0">
                                            </td>
                                            <td class="p-2">
                                                <input type="number" name="nilai[{{ $loop->index }}][nilai_uts]"
                                                    class="form-control form-control-sm text-center" min="0"
                                                    max="100" step="0.01"
                                                    value="{{ $existingNilai->nilai_uts ?? '' }}" placeholder="0">
                                            </td>
                                            <td class="p-2">
                                                <input type="number" name="nilai[{{ $loop->index }}][nilai_uas]"
                                                    class="form-control form-control-sm text-center" min="0"
                                                    max="100" step="0.01"
                                                    value="{{ $existingNilai->nilai_uas ?? '' }}" placeholder="0">
                                            </td>
                                            <td class="p-2">
                                                <input type="number" name="nilai[{{ $loop->index }}][nilai_praktikum]"
                                                    class="form-control form-control-sm text-center" min="0"
                                                    max="100" step="0.01"
                                                    value="{{ $existingNilai->nilai_praktikum ?? '' }}" placeholder="0">
                                            </td>
                                            <td class="p-2">
                                                <input type="number" name="nilai[{{ $loop->index }}][nilai_kehadiran]"
                                                    class="form-control form-control-sm text-center" min="0"
                                                    max="100" step="0.01"
                                                    value="{{ $existingNilai->nilai_kehadiran ?? '' }}" placeholder="0">
                                            </td>
                                            <td class="p-2">
                                                <input type="text" name="nilai[{{ $loop->index }}][jumlah_kehadiran]"
                                                    class="form-control form-control-sm text-center"
                                                    value="{{ $existingNilai->jumlah_kehadiran ?? '' }}"
                                                    placeholder="Ex: 12/14">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div>
                            <label class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmInput" required>
                                <span class="form-check-label fw-bold">
                                    Saya memastikan semua nilai sudah benar
                                </span>
                            </label>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('nilai.index', ['id_jadwal' => $jadwal->id]) }}" class="btn btn-light">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save me-2"></i>Simpan Nilai
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-calculate final grade (optional visual feedback)
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('change', function() {
                // Visual validation: turn red if > 100
                if (parseFloat(this.value) > 100) {
                    this.classList.add('is-invalid');
                    this.value = 100;
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        });

        // Form validation
        document.getElementById('formInputNilai').addEventListener('submit', function(e) {
            if (!document.getElementById('confirmInput').checked) {
                e.preventDefault();
                alert('Mohon centang konfirmasi bahwa semua nilai sudah benar');
            }
        });
    </script>
@endsection
