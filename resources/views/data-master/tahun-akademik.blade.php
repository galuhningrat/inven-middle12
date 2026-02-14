@extends('master.app')

@section('title', 'Data Tahun Akademik')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Tahun Akademik</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTA">
            <i class="bi bi-plus-circle me-1"></i>Tambah Tahun Akademik
        </button>
    </div>

    <!-- Alert Messages -->
    @if(session('sukses'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('sukses') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error:</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- DataTable -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Tahun Akademik</th>
                            <th width="10%">Semester</th>
                            <th width="15%">Tanggal Mulai</th>
                            <th width="15%">Tanggal Selesai</th>
                            <th width="10%">Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tahunAkademik as $index => $t)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $t->tahun_awal }}/{{ $t->tahun_akhir }}</td>
                            <td>
                                <span class="badge bg-{{ $t->semester == 'Ganjil' ? 'primary' : 'success' }}">
                                    {{ $t->semester }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($t->tanggal_mulai)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($t->tanggal_selesai)->format('d M Y') }}</td>
                            <td class="text-center">
                                @if($t->status_aktif)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Aktif
                                </span>
                                @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditTA{{ $t->id }}"
                                        title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                @if(!$t->status_aktif)
                                <button type="button" class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalHapusTA{{ $t->id }}"
                                        title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @else
                                <button type="button" class="btn btn-sm btn-secondary" disabled
                                        title="Tidak dapat menghapus data yang sedang aktif">
                                    <i class="bi bi-lock"></i>
                                </button>
                                @endif
                            </td>
                        </tr>

                        <!-- ✅ FIXED: Modal Edit Tahun Akademik -->
                        <div class="modal fade" id="modalEditTA{{ $t->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('tahun-akademik.update', $t->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title">
                                                <i class="bi bi-pencil-square me-2"></i>Edit Tahun Akademik
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- ✅ FIX: Correct field names -->
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label required">Tahun Awal</label>
                                                    <input type="text"
                                                           name="tahun_awal"
                                                           class="form-control"
                                                           value="{{ old('tahun_awal', $t->tahun_awal) }}"
                                                           required
                                                           pattern="[0-9]{4}"
                                                           placeholder="YYYY"
                                                           maxlength="4">
                                                    <small class="form-text text-muted">Contoh: 2024</small>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label required">Tahun Akhir</label>
                                                    <input type="text"
                                                           name="tahun_akhir"
                                                           class="form-control"
                                                           value="{{ old('tahun_akhir', $t->tahun_akhir) }}"
                                                           required
                                                           pattern="[0-9]{4}"
                                                           placeholder="YYYY"
                                                           maxlength="4">
                                                    <small class="form-text text-muted">Contoh: 2025</small>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label required">Semester</label>
                                                <select name="semester" class="form-select" required>
                                                    <option value="">-- Pilih Semester --</option>
                                                    <option value="Ganjil" {{ old('semester', $t->semester) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                                    <option value="Genap" {{ old('semester', $t->semester) == 'Genap' ? 'selected' : '' }}>Genap</option>
                                                </select>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label required">Tanggal Mulai</label>
                                                    <input type="date"
                                                           name="tanggal_mulai"
                                                           class="form-control"
                                                           value="{{ old('tanggal_mulai', $t->tanggal_mulai) }}"
                                                           required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label required">Tanggal Selesai</label>
                                                    <input type="date"
                                                           name="tanggal_selesai"
                                                           class="form-control"
                                                           value="{{ old('tanggal_selesai', $t->tanggal_selesai) }}"
                                                           required>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label required">Status</label>
                                                <select name="status_aktif" class="form-select" required>
                                                    <option value="1" {{ old('status_aktif', $t->status_aktif) ? 'selected' : '' }}>Aktif</option>
                                                    <option value="0" {{ !old('status_aktif', $t->status_aktif) ? 'selected' : '' }}>Tidak Aktif</option>
                                                </select>
                                                <div class="form-text text-warning">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                                    Hanya satu tahun akademik yang bisa aktif.
                                                    Mengaktifkan ini akan menonaktifkan yang lain.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle me-1"></i>Batal
                                            </button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="bi bi-check2-circle me-1"></i>Update Data
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Hapus -->
                        <div class="modal fade" id="modalHapusTA{{ $t->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('tahun-akademik.destroy', $t->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">
                                                <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-warning mb-3">
                                                <i class="bi bi-info-circle me-2"></i>
                                                <strong>Perhatian!</strong> Data yang sudah dihapus tidak dapat dikembalikan.
                                            </div>
                                            <p class="mb-0">
                                                Apakah Anda yakin ingin menghapus data tahun akademik:<br>
                                                <strong>{{ $t->tahun_awal }}/{{ $t->tahun_akhir }} - {{ $t->semester }}</strong>?
                                            </p>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle me-1"></i>Batal
                                            </button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-trash me-1"></i>Ya, Hapus
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1"></i>
                                <p class="mb-0">Belum ada data tahun akademik</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ✅ FIXED: Modal Tambah Tahun Akademik -->
<div class="modal fade" id="modalTambahTA" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form method="POST" action="{{ route('tahun-akademik.store') }}">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Tahun Akademik
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- ✅ FIX: Correct field names -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Tahun Awal</label>
                            <input type="text"
                                   name="tahun_awal"
                                   class="form-control"
                                   value="{{ old('tahun_awal') }}"
                                   required
                                   pattern="[0-9]{4}"
                                   placeholder="YYYY"
                                   maxlength="4">
                            <small class="form-text text-muted">Contoh: 2024</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Tahun Akhir</label>
                            <input type="text"
                                   name="tahun_akhir"
                                   class="form-control"
                                   value="{{ old('tahun_akhir') }}"
                                   required
                                   pattern="[0-9]{4}"
                                   placeholder="YYYY"
                                   maxlength="4">
                            <small class="form-text text-muted">Contoh: 2025</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Semester</label>
                        <select name="semester" class="form-select" required>
                            <option value="">-- Pilih Semester --</option>
                            <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ old('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Tanggal Mulai</label>
                            <input type="date"
                                   name="tanggal_mulai"
                                   class="form-control"
                                   value="{{ old('tanggal_mulai') }}"
                                   required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Tanggal Selesai</label>
                            <input type="date"
                                   name="tanggal_selesai"
                                   class="form-control"
                                   value="{{ old('tanggal_selesai') }}"
                                   required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Status</label>
                        <select name="status_aktif" class="form-select" required>
                            <option value="1" {{ old('status_aktif') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status_aktif') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        <div class="form-text text-warning">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Hanya satu tahun akademik yang bisa aktif.
                            Mengaktifkan ini akan menonaktifkan yang lain.
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1"></i>Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.required:after {
    content: " *";
    color: red;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
        },
        "order": [[1, 'desc']], // Sort by tahun akademik descending
        "pageLength": 10,
        "responsive": true
    });

    // Auto dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Validation: Tahun Akhir must be >= Tahun Awal
    $('form').on('submit', function(e) {
        const tahunAwal = parseInt($(this).find('input[name="tahun_awal"]').val());
        const tahunAkhir = parseInt($(this).find('input[name="tahun_akhir"]').val());

        if (tahunAkhir < tahunAwal) {
            e.preventDefault();
            alert('Tahun Akhir tidak boleh lebih kecil dari Tahun Awal');
            return false;
        }

        // Validation: Tanggal Selesai must be >= Tanggal Mulai
        const tanggalMulai = new Date($(this).find('input[name="tanggal_mulai"]').val());
        const tanggalSelesai = new Date($(this).find('input[name="tanggal_selesai"]').val());

        if (tanggalSelesai < tanggalMulai) {
            e.preventDefault();
            alert('Tanggal Selesai tidak boleh lebih awal dari Tanggal Mulai');
            return false;
        }
    });

    // Auto-populate tahun_akhir when tahun_awal is entered
    $('input[name="tahun_awal"]').on('blur', function() {
        const tahunAwal = parseInt($(this).val());
        if (!isNaN(tahunAwal)) {
            const $form = $(this).closest('form');
            const semester = $form.find('select[name="semester"]').val();

            // If Ganjil: tahun_akhir = tahun_awal + 1
            // If Genap: tahun_akhir = tahun_awal
            if (semester === 'Ganjil') {
                $form.find('input[name="tahun_akhir"]').val(tahunAwal + 1);
            } else if (semester === 'Genap') {
                $form.find('input[name="tahun_akhir"]').val(tahunAwal);
            }
        }
    });
});
</script>
@endpush
