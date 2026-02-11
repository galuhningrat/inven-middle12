@extends('master.app')

@section('toolbar')
    <!-- Toolbar sama seperti sebelumnya -->
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Data Tahun Akademik</h1>
                <span class="h-20px border-gray-200 border-start mx-4"></span>
            </div>
            <div class="d-flex align-items-center py-1">
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="/dashboard" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">Data Tahun Akademik</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-fluid">
        @include('master.notification')
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="d-flex flex-wrap justify-content-between align-items-center w-100">
                    <div id="custom-search-container" class="mb-2 mb-md-0"></div>
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <div id="custom-button-container" class="d-flex gap-2 flex-wrap align-items-center"></div>
                        <div class="ms-auto">
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTA">Tambah</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table id="tabel-custom" class="table table-bordered table-striped table-sm align-middle fs-6 gy-2 w-100">
                        <thead class="bg-gray-100 text-gray-800 border-bottom border-gray-300">
                            <tr class="fw-bold fs-6 text-uppercase">
                                <th class="text-center py-4 px-2 min-w-30px">No</th>
                                <th class="py-4 px-3 min-w-120px">Tahun Awal</th>
                                <th class="py-4 px-3 min-w-120px">Tahun Akhir</th>
                                <th class="py-4 px-3 min-w-80px">Semester</th>
                                <th class="py-4 px-3 min-w-120px">Tanggal Mulai</th>
                                <th class="py-4 px-3 min-w-120px">Tanggal Selesai</th>
                                <th class="py-4 px-3 min-w-80px">Status</th>
                                <th class="text-center py-4 px-2 min-w-130px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 fw-semibold">
                            @foreach ($tahunAkademik as $t)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $t->tahun_awal }}</td>
                                <td>{{ $t->tahun_akhir }}</td>
                                <td>{{ $t->semester }}</td>
                                <td>{{ \Carbon\Carbon::parse($t->tanggal_mulai)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($t->tanggal_selesai)->format('d-m-Y') }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $t->status_aktif ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $t->status_aktif ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <!-- DETAIL -->
                                    <button type="button" class="btn btn-icon btn-sm btn-light-primary me-2" title="Detail"
                                        data-bs-toggle="modal" data-bs-target="#modalDetailTA{{ $t->id }}">
                                        <i class="bi bi-eye-fill fs-5"></i>
                                    </button>
                                    <!-- EDIT -->
                                    <button type="button" class="btn btn-icon btn-sm btn-light-success me-2" title="Ubah"
                                        data-bs-toggle="modal" data-bs-target="#modalEditTA{{ $t->id }}">
                                        <i class="bi bi-pencil-fill fs-5"></i>
                                    </button>
                                    <!-- DELETE -->
                                    <button type="button" class="btn btn-icon btn-sm btn-light-danger" title="Hapus"
                                        data-bs-toggle="modal" data-bs-target="#modalDeleteTA{{ $t->id }}">
                                        <i class="bi bi-trash-fill fs-5"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Detail Tahun Akademik -->
                            <div class="modal fade" id="modalDetailTA{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="modal-title">Detail Tahun Akademik</h2>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <dl class="row">
                                                <dt class="col-sm-4">Tahun Awal</dt>
                                                <dd class="col-sm-8">{{ $t->tahun_awal }}</dd>
                                                <dt class="col-sm-4">Tahun Akhir</dt>
                                                <dd class="col-sm-8">{{ $t->tahun_akhir }}</dd>
                                                <dt class="col-sm-4">Semester</dt>
                                                <dd class="col-sm-8">{{ $t->semester }}</dd>
                                                <dt class="col-sm-4">Tanggal Mulai</dt>
                                                <dd class="col-sm-8">{{ \Carbon\Carbon::parse($t->tanggal_mulai)->format('d F Y') }}</dd>
                                                <dt class="col-sm-4">Tanggal Selesai</dt>
                                                <dd class="col-sm-8">{{ \Carbon\Carbon::parse($t->tanggal_selesai)->format('d F Y') }}</dd>
                                                <dt class="col-sm-4">Status</dt>
                                                <dd class="col-sm-8">
                                                    <span class="badge {{ $t->status_aktif ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $t->status_aktif ? 'Aktif' : 'Tidak Aktif' }}
                                                    </span>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Edit Tahun Akademik -->
                            <div class="modal fade" id="modalEditTA{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('tahun-akademik.update', $t->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Tahun Akademik</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label required">Tahun Awal</label>
                                                    <input type="text" name="tahun_akademik" class="form-control" value="{{ $t->tahun_awal }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label required">Tahun Akhir</label>
                                                    <input type="text" name="tahun_akademik" class="form-control" value="{{ $t->tahun_akhir }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label required">Semester</label>
                                                    <select name="semester" class="form-select" required>
                                                        <option value="Ganjil" {{ $t->semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                                        <option value="Genap" {{ $t->semester == 'Genap' ? 'selected' : '' }}>Genap</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label required">Tanggal Mulai</label>
                                                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ $t->tanggal_mulai }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label required">Tanggal Selesai</label>
                                                    <input type="date" name="tanggal_selesai" class="form-control" value="{{ $t->tanggal_selesai }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label required">Status</label>
                                                    <select name="status_aktif" class="form-select" required>
                                                        <option value="1" {{ $t->status_aktif ? 'selected' : '' }}>Aktif</option>
                                                        <option value="0" {{ !$t->status_aktif ? 'selected' : '' }}>Tidak Aktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Delete Tahun Akademik -->
                            <div class="modal fade" id="modalDeleteTA{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('tahun-akademik.destroy', $t->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Hapus Tahun Akademik</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Yakin ingin menghapus tahun akademik <b>{{ $t->tahun_awal }}/{{ $t->tahun_akhir }} - {{ $t->semester }}</b>?</p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Tahun Akademik -->
<div class="modal fade" id="modalTambahTA" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form method="POST" action="{{ route('tahun-akademik.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tahun Akademik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Tahun Awal</label>
                        <input type="text" name="tahun_awal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Tahun Akhir</label>
                        <input type="text" name="tahun_akhir" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Semester</label>
                        <select name="semester" class="form-select" required>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Status</label>
                        <select name="status_aktif" class="form-select" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
