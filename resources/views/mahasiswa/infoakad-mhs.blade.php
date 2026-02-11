@extends('mahasiswa.detail-mahasiswa')

@section('akademik')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title fw-bolder m-0">
                <i class="bi bi-graph-up-arrow text-primary me-2"></i>
                Informasi Akademik & Dosen Pembimbing
            </h3>
        </div>

        {{-- SECURITY: Only Admin/Akademik can edit --}}
        @if (in_array(strtolower(auth()->user()->role->nama_role ?? ''), ['admin', 'administrator', 'akademik']))
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditAkademik">
                <i class="bi bi-pencil me-2"></i>Edit Data Akademik
            </button>
        @endif

        <div class="card-body p-9">
            <!-- ========== DPA INFO CARD ========== -->
            <div class="card bg-gradient-primary mb-8">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-70px me-5">
                            <span class="symbol-label bg-white">
                                <i class="bi bi-person-badge fs-1 text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="text-white mb-1">Dosen Pembimbing Akademik (DPA)</h4>
                            @if ($mahasiswa->rombel && $mahasiswa->rombel->dosen)
                                <div class="fw-bold fs-4 text-white">
                                    {{ $mahasiswa->rombel->dosen->user->nama }}
                                </div>
                                <div class="text-white opacity-75">
                                    NIP: {{ $mahasiswa->rombel->dosen->nip }} |
                                    Rombel: {{ $mahasiswa->rombel->nama_rombel }}
                                </div>
                            @else
                                <span class="badge badge-warning">Belum Ada DPA</span>
                                <p class="text-white opacity-75 mb-0 mt-2">Hubungi admin untuk penugasan DPA</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== STATS CARDS ========== -->
            <div class="row g-5 mb-10">
                <!-- IPK Card -->
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm hover-elevate-up">
                        <div class="card-body text-center p-6">
                            <i class="bi bi-trophy fs-3x text-warning mb-3"></i>
                            <h6 class="text-muted fw-bold mb-2">IPK Kumulatif</h6>
                            <h1 class="fw-bolder text-primary mb-2">{{ number_format($ipk, 2) }}</h1>
                            <span class="badge badge-light-{{ $predikatKelulusan['class'] }}">
                                {{ $predikatKelulusan['label'] }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Total SKS Card -->
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm hover-elevate-up">
                        <div class="card-body text-center p-6">
                            <i class="bi bi-bookmark-check fs-3x text-info mb-3"></i>
                            <h6 class="text-muted fw-bold mb-2">Total SKS</h6>
                            <h1 class="fw-bolder text-info mb-2">{{ $totalSks }}</h1>
                            <small class="text-muted">dari 144 SKS</small>
                        </div>
                    </div>
                </div>

                <!-- Beban SKS Card -->
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm hover-elevate-up">
                        <div class="card-body text-center p-6">
                            <i class="bi bi-calendar-check fs-3x text-success mb-3"></i>
                            <h6 class="text-muted fw-bold mb-2">Jatah SKS</h6>
                            <h1 class="fw-bolder text-success mb-2">{{ $bebanSKS }}</h1>
                            <small class="text-muted">Semester Depan</small>
                        </div>
                    </div>
                </div>

                <!-- Semester Card -->
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm hover-elevate-up">
                        <div class="card-body text-center p-6">
                            <i class="bi bi-mortarboard fs-3x text-danger mb-3"></i>
                            <h6 class="text-muted fw-bold mb-2">Semester</h6>
                            <h1 class="fw-bolder text-danger mb-2">{{ $semesterTerakhir }}</h1>
                            <small class="text-muted">Terakhir</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== PROGRESS KELULUSAN ========== -->
            <div class="card border border-primary border-dashed mb-10">
                <div class="card-body p-6">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Progress Kelulusan</h5>
                        <span class="badge badge-light-primary fs-6">{{ round(($totalSks / 144) * 100) }}%</span>
                    </div>
                    <div class="progress h-20px mb-3">
                        <div class="progress-bar bg-primary" role="progressbar"
                            style="width: {{ round(($totalSks / 144) * 100) }}%" aria-valuenow="{{ $totalSks }}"
                            aria-valuemin="0" aria-valuemax="144">
                            {{ $totalSks }} SKS
                        </div>
                    </div>
                    <p class="text-muted mb-0">
                        @if ($sksKurang > 0)
                            <i class="bi bi-exclamation-circle text-warning"></i>
                            Kekurangan {{ $sksKurang }} SKS lagi untuk lulus
                        @else
                            <i class="bi bi-check-circle text-success"></i>
                            Syarat SKS minimal telah terpenuhi
                        @endif
                    </p>
                </div>
            </div>

            <!-- ========== GRAFIK IPS ========== -->
            <div class="card card-bordered mb-10">
                <div class="card-header">
                    <h4 class="card-title fw-bolder">
                        <i class="bi bi-graph-up text-primary me-2"></i>
                        Grafik Perkembangan IPS
                    </h4>
                </div>
                <div class="card-body">
                    <div style="height: 350px;">
                        @if (count($semesterIPS) > 0)
                            <canvas id="chartIPS"></canvas>
                        @else
                            <div class="d-flex flex-column flex-center h-100">
                                <i class="bi bi-graph-up fs-3x text-muted mb-3"></i>
                                <span class="text-muted fw-bold fs-5">Belum ada data nilai</span>
                                <p class="text-muted">Grafik akan muncul setelah nilai diinput</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ========== TABEL TRANSKRIP ========== -->
            <div class="card card-bordered">
                <div class="card-header">
                    <h4 class="card-title fw-bolder">
                        <i class="bi bi-table text-success me-2"></i>
                        Transkrip Akademik
                    </h4>
                    <div class="card-toolbar">
                        <button class="btn btn-sm btn-light-primary" onclick="window.print()">
                            <i class="bi bi-printer me-1"></i> Cetak Transkrip
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-row-bordered table-striped align-middle gs-0 gy-3">
                            <thead class="bg-light-primary">
                                <tr class="fw-bold text-uppercase">
                                    <th class="text-center" style="width: 10%;">Semester</th>
                                    <th class="text-center" style="width: 15%;">IPS</th>
                                    <th class="text-center" style="width: 15%;">Status</th>
                                    <th style="width: 60%;">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($semesterIPS as $smtr => $nilai)
                                    @php
                                        $badgeClass =
                                            $nilai >= 3.0
                                                ? 'success'
                                                : ($nilai >= 2.5
                                                    ? 'primary'
                                                    : ($nilai >= 2.0
                                                        ? 'warning'
                                                        : 'danger'));
                                        $statusText = $nilai >= 2.0 ? 'Lulus' : 'Mengulang';
                                        $statusBadge = $nilai >= 2.0 ? 'success' : 'danger';
                                    @endphp
                                    <tr>
                                        <td class="text-center fw-bold">{{ $smtr }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-light-{{ $badgeClass }} fs-6 fw-bold px-4 py-2">
                                                {{ number_format($nilai, 2) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $statusBadge }}">{{ $statusText }}</span>
                                        </td>
                                        <td>
                                            @if ($nilai >= 3.0)
                                                <i class="bi bi-trophy-fill text-warning"></i> Sangat Baik
                                            @elseif ($nilai >= 2.5)
                                                <i class="bi bi-star-fill text-primary"></i> Baik
                                            @elseif ($nilai >= 2.0)
                                                <i class="bi bi-check-circle text-success"></i> Cukup
                                            @else
                                                <i class="bi bi-exclamation-triangle text-danger"></i> Perlu Perbaikan
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-10">
                                            <i class="bi bi-inbox fs-2x text-muted mb-3 d-block"></i>
                                            Belum ada data KRS. Silakan ambil KRS terlebih dahulu.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if (count($semesterIPS) > 0)
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="4" class="p-5">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <strong>Total SKS:</strong> {{ $totalSks }} SKS
                                                </div>
                                                <div>
                                                    <strong>IPK Kumulatif:</strong>
                                                    <span
                                                        class="badge badge-light-primary fs-5 ms-2">{{ number_format($ipk, 2) }}</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const canvas = document.getElementById('chartIPS');
                if (canvas) {
                    const ctx = canvas.getContext('2d');
                    const dataIPS = @json(array_values($semesterIPS));
                    const labels = @json(array_map(fn($s) => 'Semester ' . $s, array_keys($semesterIPS)));

                    if (dataIPS.length > 0) {
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'IPS',
                                    data: dataIPS,
                                    borderColor: '#009EF7',
                                    backgroundColor: 'rgba(0, 158, 247, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 6,
                                    pointHoverRadius: 8,
                                    pointBackgroundColor: '#009EF7',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 4,
                                        ticks: {
                                            stepSize: 0.5,
                                            callback: function(value) {
                                                return value.toFixed(1);
                                            }
                                        },
                                        grid: {
                                            color: 'rgba(0, 0, 0, 0.05)'
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                        labels: {
                                            font: {
                                                size: 14,
                                                weight: 'bold'
                                            }
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return 'IPS: ' + context.parsed.y.toFixed(2);
                                            },
                                            title: function(context) {
                                                return context[0].label;
                                            }
                                        },
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        padding: 12,
                                        titleFont: {
                                            size: 14
                                        },
                                        bodyFont: {
                                            size: 14
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            });
        </script>
    @endpush
@endsection
