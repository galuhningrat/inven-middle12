@extends('master.app')

@section('title', 'Master Data Mata Kuliah')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Master Data Mata Kuliah</h4>
            <p class="text-muted mb-0">Daftar seluruh mata kuliah yang terdaftar di sistem</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('matakuliah.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-layer-group me-1"></i> Tampilan Kurikulum
            </a>
            @can('kurikulum', 'create')
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahMatkul">
                <i class="fas fa-plus me-1"></i> Tambah Mata Kuliah
            </button>
            @endcan
        </div>
    </div>

    {{-- Alert Orphan --}}
    @if($totalOrphan > 0)
    <div class="alert alert-warning d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>{{ $totalOrphan }} mata kuliah</strong> belum dipetakan ke Prodi & Semester manapun.
            <a href="{{ request()->fullUrlWithQuery(['filter_orphan' => '1']) }}" class="alert-link ms-1">Tampilkan saja yang belum dipetakan →</a>
        </div>
    </div>
    @endif

    {{-- Session Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Filter & Search --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ url()->current() }}" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small text-muted mb-1">Cari Mata Kuliah</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Kode MK atau nama mata kuliah..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Jenis</label>
                    <select name="filter_jenis" class="form-select form-select-sm">
                        <option value="">-- Semua Jenis --</option>
                        <option value="wajib" {{ request('filter_jenis') === 'wajib' ? 'selected' : '' }}>Wajib</option>
                        <option value="pilihan" {{ request('filter_jenis') === 'pilihan' ? 'selected' : '' }}>Pilihan</option>
                        <option value="umum" {{ request('filter_jenis') === 'umum' ? 'selected' : '' }}>Umum (MKU)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Status</label>
                    <select name="filter_orphan" class="form-select form-select-sm">
                        <option value="">-- Semua --</option>
                        <option value="1" {{ request('filter_orphan') === '1' ? 'selected' : '' }}>Belum dipetakan</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width:40px">#</th>
                            <th>Kode MK</th>
                            <th>Nama Mata Kuliah</th>
                            <th class="text-center">SKS</th>
                            <th class="text-center">Jenis</th>
                            <th>Dosen Pengampu</th>
                            <th>Prodi & Semester</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matakuliah as $index => $mk)
                        @php
                            $mappings = $mk->prodiMappings->sortBy('semester');
                            $isOrphan = $mappings->isEmpty();
                        @endphp
                        <tr class="{{ $isOrphan ? 'table-warning' : '' }}">
                            <td class="ps-4 text-muted small">{{ $matakuliah->firstItem() + $index }}</td>
                            <td>
                                <code class="fw-semibold">{{ $mk->kode_mk }}</code>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $mk->nama_mk }}</div>
                                @if($isOrphan)
                                <small class="text-warning"><i class="fas fa-exclamation-circle"></i> Belum dipetakan</small>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $mk->bobot }} SKS</span>
                            </td>
                            <td class="text-center">
                                @php
                                    $badgeClass = match($mk->jenis) {
                                        'wajib'   => 'bg-primary',
                                        'pilihan' => 'bg-success',
                                        'umum'    => 'bg-info',
                                        default   => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($mk->jenis) }}</span>
                            </td>
                            <td>
                                @if($mk->dosen)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
                                            <i class="fas fa-user-tie text-muted small"></i>
                                        </div>
                                        <div>
                                            <div class="small fw-semibold">{{ $mk->dosen->user->nama ?? '-' }}</div>
                                            <div class="text-muted" style="font-size:0.75rem">{{ $mk->dosen->nidn ?? '' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if($mappings->isNotEmpty())
                                    @foreach($mappings->groupBy('id_prodi') as $prodiId => $prodiMappings)
                                    <div class="mb-1">
                                        <span class="badge bg-light text-dark border me-1" style="font-size:0.72rem">
                                            {{ $prodiMappings->first()->prodi->kode_prodi ?? '—' }}
                                        </span>
                                        @foreach($prodiMappings->sortBy('semester') as $pm)
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary" style="font-size:0.72rem">
                                            Smt {{ $pm->semester }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @endforeach
                                @else
                                    <span class="text-muted small fst-italic">Belum dipetakan</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('matakuliah.show', $mk->id) }}">
                                                <i class="fas fa-eye text-info me-2"></i> Detail
                                            </a>
                                        </li>
                                        @can('kurikulum', 'update')
                                        <li>
                                            <button class="dropdown-item" onclick="editMatkul({{ json_encode($mk) }}, {{ json_encode($mk->prodiMappings) }})">
                                                <i class="fas fa-edit text-warning me-2"></i> Edit
                                            </button>
                                        </li>
                                        @endcan
                                        @can('kurikulum', 'delete')
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button class="dropdown-item text-danger"
                                                onclick="confirmDelete({{ $mk->id }}, '{{ addslashes($mk->nama_mk) }}')">
                                                <i class="fas fa-trash me-2"></i> Hapus
                                            </button>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-book-open fa-2x mb-2 d-block opacity-50"></i>
                                Tidak ada mata kuliah ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($matakuliah->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                <div class="text-muted small">
                    Menampilkan {{ $matakuliah->firstItem() }}–{{ $matakuliah->lastItem() }}
                    dari {{ $matakuliah->total() }} mata kuliah
                </div>
                <div>{{ $matakuliah->withQueryString()->links() }}</div>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                <h6 class="fw-bold">Hapus Mata Kuliah?</h6>
                <p class="text-muted small mb-3" id="deleteMatkulName"></p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <form id="formHapus" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function confirmDelete(id, nama) {
    document.getElementById('deleteMatkulName').textContent = nama;
    document.getElementById('formHapus').action = `/matakuliah/${id}`;
    new bootstrap.Modal(document.getElementById('modalHapus')).show();
}

function editMatkul(mk, mappings) {
    // Redirect ke halaman edit atau buka modal edit
    // Sesuaikan dengan implementasi modal edit yang ada
    console.log('Edit:', mk, mappings);
    // Contoh: window.location.href = `/matakuliah/${mk.id}/edit`;
}
</script>
@endpush
