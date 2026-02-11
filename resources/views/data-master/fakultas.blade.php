@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Data Fakultas</h1>
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
                    <li class="breadcrumb-item text-dark">Data Fakultas</li>
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
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTambahFakultas">Tambah</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="tabel-custom"
                            class="table table-bordered table-striped table-sm align-middle fs-6 gy-2 w-100">
                            <thead class="bg-gray-100 text-gray-800 border-bottom border-gray-300">
                                <tr class="fw-bold fs-5 text-uppercase">
                                    <th class="text-center py-4 px-2 min-w-30px">No</th>
                                    <th class="text-center py-4 px-3 min-w-60px">Kode</th>
                                    <th class="py-4 px-3 min-w-200px">Nama Fakultas</th>
                                    <th class="py-4 px-3 min-w-400px">Nama Dekan</th>
                                    <th class="text-center py-4 px-3 min-w-50px">Jumlah Prodi</th>
                                    <th class="text-center py-4 px-2 min-w-50px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                                @foreach ($fakultas as $f)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $f->kode_fakultas }}</td>
                                        <td>{{ $f->nama_fakultas }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <img src="{{ asset($f->dekan?->img ?? 'foto_users/default.png') }}"
                                                        alt="foto" class="w-100" />
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span
                                                        class="text-gray-800 fw-bold">{{ $f->dekan?->nama ?? 'N/A' }}</span>
                                                    <span>{{ $f->dekan?->email ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $f->prodi_count }}</td>
                                        <td class="text-center">
                                            <!-- READ/DETAIL -->
                                            <button type="button" class="btn btn-icon btn-sm btn-light-primary me-2"
                                                title="Detail" data-bs-toggle="modal"
                                                data-bs-target="#modalDetailFakultas{{ $f->id }}">
                                                <i class="bi bi-eye-fill fs-5"></i>
                                            </button>
                                            <!-- UPDATE/EDIT -->
                                            <button type="button" class="btn btn-icon btn-sm btn-light-success me-2"
                                                title="Ubah" data-bs-toggle="modal"
                                                data-bs-target="#modalEditFakultas{{ $f->id }}">
                                                <i class="bi bi-pencil-fill fs-5"></i>
                                            </button>
                                            <!-- DELETE -->
                                            <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-hapus"
                                                title="Hapus" data-id="{{ $f->id }}">
                                                <i class="bi bi-trash-fill fs-5"></i>
                                            </button>

                                            <!-- Hidden Form -->
                                            <form id="form-hapus-{{ $f->id }}"
                                                action="{{ route('fakultas.destroy', $f->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Modal Detail Fakultas -->
                                    <div class="modal fade" id="modalDetailFakultas{{ $f->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Fakultas</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <dl class="row">
                                                        <dt class="col-sm-4">Kode Fakultas</dt>
                                                        <dd class="col-sm-8">{{ $f->kode_fakultas }}</dd>
                                                        <dt class="col-sm-4">Nama Fakultas</dt>
                                                        <dd class="col-sm-8">{{ $f->nama_fakultas }}</dd>
                                                        <dt class="col-sm-4">Nama Dekan</dt>
                                                        <dd class="col-sm-8">{{ $f->dekan?->nama ?? 'Tidak Ada Data' }}
                                                        </dd>
                                                        <dt class="col-sm-4">Jumlah Prodi</dt>
                                                        <dd class="col-sm-8">{{ $f->prodi_count }}</dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Edit Fakultas -->
                                    <div class="modal fade" id="modalEditFakultas{{ $f->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <form action="{{ route('fakultas.update', $f->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Fakultas</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-4 mb-10">
                                                                <label class="form-label required">Kode Fakultas</label>
                                                                <input type="text" name="kode_fakultas"
                                                                    class="form-control form-control-solid"
                                                                    value="{{ $f->kode_fakultas }}" required />
                                                            </div>
                                                            <div class="col-md-8 mb-10">
                                                                <label class="form-label required">Nama Fakultas</label>
                                                                <input type="text" name="nama_fakultas"
                                                                    class="form-control form-control-solid"
                                                                    value="{{ $f->nama_fakultas }}" required />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 mb-10">
                                                                <label class="form-label required">Nama Dekan</label>
                                                                <select name="id_dekan"
                                                                    class="form-select form-select-solid" required>
                                                                    @foreach ($dekans as $dekan)
                                                                        <option value="{{ $dekan->id }}"
                                                                            {{ $dekan->id == $f->id_dekan ? 'selected' : '' }}>
                                                                            {{ $dekan->nama }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-light-danger"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Delete Fakultas -->
                                    <div class="modal fade" id="modalDeleteFakultas{{ $f->id }}" tabindex="-1"
                                        aria-labelledby="modalDeleteFakultasLabel{{ $f->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('fakultas.destroy', $f->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Hapus Fakultas</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus fakultas
                                                            <b>{{ $f->nama_fakultas }}</b>?
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-light-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
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

    <!-- Modal Tambah Fakultas -->
    <div class="modal fade" id="modalTambahFakultas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <form action="{{ route('fakultas.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Fakultas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 mb-10">
                                <label class="form-label required">Kode Fakultas</label>
                                <input type="text" name="kode_fakultas" class="form-control form-control-solid"
                                    required />
                            </div>
                            <div class="col-md-8 mb-10">
                                <label class="form-label required">Nama Fakultas</label>
                                <input type="text" name="nama_fakultas" class="form-control form-control-solid"
                                    required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-10">
                                <label class="form-label required">Nama Dekan</label>
                                <select name="id_dekan" class="form-select form-select-solid" required>
                                    <option value="">-- Pilih Dekan --</option>
                                    @foreach ($dekans as $dekan)
                                        <option value="{{ $dekan->id }}">{{ $dekan->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
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
