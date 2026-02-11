@extends('mahasiswa.detail-mahasiswa')

@section('pembayaran')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3 class="fw-bolder m-0">Histori Pembayaran</h3>
            </div>

            @php
                $userRole = strtolower(auth()->user()->role->nama_role ?? '');
                $isAdminOrKeuangan = in_array($userRole, ['super admin', 'bagian keuangan']);
            @endphp

            @if ($isAdminOrKeuangan)
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPembayaran">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Pembayaran
                </button>
            @else
                <span class="badge badge-light-info">Mode Read-Only</span>
            @endif
        </div>

        <div class="card-body p-9">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Summary Card -->
            @php
                $totalTagihan = $mahasiswa->pembayaran->sum('jumlah_tagihan');
                $totalDibayar = $mahasiswa->pembayaran->sum('jumlah_dibayar');
                $sisaTagihan = $totalTagihan - $totalDibayar;
                $belumBayar = $mahasiswa->pembayaran->where('status', 'Belum Bayar')->count();
                $lunas = $mahasiswa->pembayaran->where('status', 'Lunas')->count();
            @endphp

            <div class="row g-5 mb-8">
                <div class="col-md-3">
                    <div class="border border-gray-300 border-dashed rounded p-5 text-center">
                        <div class="fs-2 fw-bolder text-gray-800">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</div>
                        <div class="fw-semibold text-gray-400 mt-2">Total Tagihan</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border border-gray-300 border-dashed rounded p-5 text-center">
                        <div class="fs-2 fw-bolder text-success">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</div>
                        <div class="fw-semibold text-gray-400 mt-2">Total Dibayar</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border border-gray-300 border-dashed rounded p-5 text-center">
                        <div class="fs-2 fw-bolder text-danger">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</div>
                        <div class="fw-semibold text-gray-400 mt-2">Sisa Tagihan</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border border-gray-300 border-dashed rounded p-5 text-center">
                        <div class="fs-2 fw-bolder text-warning">{{ $belumBayar }}</div>
                        <div class="fw-semibold text-gray-400 mt-2">Belum Bayar</div>
                    </div>
                </div>
            </div>

            @if ($mahasiswa->pembayaran && $mahasiswa->pembayaran->count() > 0)
                <!-- Filter Tahun Akademik -->
                <div class="mb-5">
                    <label class="form-label fw-bold">Filter Tahun Akademik:</label>
                    <select class="form-select form-select-sm w-250px" id="filterTahunAkademik">
                        <option value="">Semua Tahun</option>
                        @foreach ($tahunAkademik as $ta)
                            <option value="{{ $ta->id }}">{{ $ta->tahun_awal }}/{{ $ta->tahun_akhir }} -
                                {{ $ta->semester }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle" id="tabelPembayaran">
                        <thead class="table-light">
                            <tr class="fw-bold text-uppercase">
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 15%;">Tahun Akademik</th>
                                <th style="width: 12%;">Jenis Pembayaran</th>
                                <th class="text-end" style="width: 12%;">Tagihan</th>
                                <th class="text-end" style="width: 12%;">Dibayar</th>
                                <th class="text-end" style="width: 12%;">Sisa</th>
                                <th class="text-center" style="width: 10%;">Jatuh Tempo</th>
                                <th class="text-center" style="width: 10%;">Status</th>
                                <th class="text-center" style="width: 12%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mahasiswa->pembayaran->sortByDesc('created_at') as $bayar)
                                <tr data-tahun="{{ $bayar->id_tahun_akademik }}">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold">
                                            {{ $bayar->tahunAkademik->tahun_awal }}/{{ $bayar->tahunAkademik->tahun_akhir }}
                                        </div>
                                        <div class="text-muted small">{{ $bayar->semester }}</div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-primary">{{ $bayar->jenis_pembayaran }}</span>
                                    </td>
                                    <td class="text-end fw-bold">Rp
                                        {{ number_format($bayar->jumlah_tagihan, 0, ',', '.') }}</td>
                                    <td class="text-end text-success fw-bold">Rp
                                        {{ number_format($bayar->jumlah_dibayar, 0, ',', '.') }}</td>
                                    <td class="text-end text-danger fw-bold">
                                        Rp
                                        {{ number_format($bayar->jumlah_tagihan - $bayar->jumlah_dibayar, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="small">
                                            {{ \Carbon\Carbon::parse($bayar->tanggal_jatuh_tempo)->format('d M Y') }}</div>
                                        @if (\Carbon\Carbon::parse($bayar->tanggal_jatuh_tempo)->isPast() && $bayar->status != 'Lunas')
                                            <span class="badge badge-light-danger mt-1">Terlambat</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($bayar->status == 'Lunas')
                                            <span class="badge badge-success">
                                                <i class="bi bi-check-circle me-1"></i>{{ $bayar->status }}
                                            </span>
                                            @if ($bayar->tanggal_bayar)
                                                <div class="small text-muted mt-1">
                                                    {{ \Carbon\Carbon::parse($bayar->tanggal_bayar)->format('d M Y') }}
                                                </div>
                                            @endif
                                        @elseif($bayar->status == 'Cicilan')
                                            <span class="badge badge-warning">
                                                <i class="bi bi-hourglass-split me-1"></i>{{ $bayar->status }}
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                <i class="bi bi-x-circle me-1"></i>{{ $bayar->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($bayar->bukti_bayar)
                                            <a href="{{ asset('storage/' . $bayar->bukti_bayar) }}" target="_blank"
                                                class="btn btn-icon btn-sm btn-light-success" title="Lihat Bukti Bayar">
                                                <i class="bi bi-file-earmark-check"></i>
                                            </a>
                                        @endif

                                        @if ($isAdminOrKeuangan)
                                            <button type="button" class="btn btn-icon btn-sm btn-light-warning"
                                                onclick="editPembayaran({{ $bayar->id }})" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form
                                                action="{{ route('mahasiswa.pembayaran.destroy', [$mahasiswa->nim, $bayar->id]) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-sm btn-light-danger"
                                                    onclick="return confirm('Yakin hapus data pembayaran ini?')"
                                                    title="Hapus">
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
            @else
                <div class="text-center py-10">
                    <div class="symbol symbol-60px mb-3">
                        <span class="symbol-label bg-light rounded-4">
                            <i class="bi bi-credit-card fs-1"></i>
                        </span>
                    </div>
                    <div class="fw-semibold mb-1">Belum Ada Data Pembayaran</div>
                    <div class="text-muted mb-3">Data pembayaran mahasiswa akan muncul di sini</div>
                    @if ($isAdminOrKeuangan)
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalTambahPembayaran">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Pembayaran Pertama
                        </button>
                    @endif
                </div>
            @endif

            <!-- Info Box -->
            @if ($isAdminOrKeuangan)
                <div class="alert alert-light-success d-flex align-items-center mt-8">
                    <i class="bi bi-shield-check fs-2 me-3"></i>
                    <div>
                        <strong>Akses Admin/Keuangan:</strong> Anda memiliki hak penuh untuk mengelola data pembayaran
                        mahasiswa (CRUD).
                    </div>
                </div>
            @else
                <div class="alert alert-light-info d-flex align-items-center mt-8">
                    <i class="bi bi-info-circle fs-2 me-3"></i>
                    <div>
                        <strong>Catatan:</strong> Untuk pertanyaan terkait pembayaran, silakan hubungi bagian administrasi
                        keuangan kampus.
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Tambah Pembayaran (Hanya untuk Admin/Keuangan) -->
    @if ($isAdminOrKeuangan)
        <div class="modal fade" id="modalTambahPembayaran" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('mahasiswa.pembayaran.store', $mahasiswa->nim) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h3 class="modal-title">Tambah Data Pembayaran</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-4">
                                <label class="form-label required">Tahun Akademik</label>
                                <select name="id_tahun_akademik" class="form-select" required>
                                    <option value="">Pilih Tahun Akademik</option>
                                    @foreach ($tahunAkademik as $ta)
                                        <option value="{{ $ta->id }}">{{ $ta->tahun_awal }}/{{ $ta->tahun_akhir }}
                                            - {{ $ta->semester }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label required">Jenis Pembayaran</label>
                                <select name="jenis_pembayaran" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="SPP">SPP</option>
                                    <option value="UTS">UTS</option>
                                    <option value="UAS">UAS</option>
                                    <option value="Praktikum">Praktikum</option>
                                    <option value="Wisuda">Wisuda</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label required">Jumlah Tagihan</label>
                                <input type="number" name="jumlah_tagihan" class="form-control" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Jumlah Dibayar</label>
                                <input type="number" name="jumlah_dibayar" class="form-control" value="0">
                            </div>
                            <div class="mb-4">
                                <label class="form-label required">Tanggal Jatuh Tempo</label>
                                <input type="date" name="tanggal_jatuh_tempo" class="form-control" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Bukti Pembayaran (PDF/JPG/PNG)</label>
                                <input type="file" name="bukti_bayar" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterTA = document.getElementById('filterTahunAkademik');
            const rows = document.querySelectorAll('#tabelPembayaran tbody tr');

            if (filterTA) {
                filterTA.addEventListener('change', function() {
                    const selectedTA = this.value;
                    rows.forEach(row => {
                        if (selectedTA === '' || row.getAttribute('data-tahun') === selectedTA) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
