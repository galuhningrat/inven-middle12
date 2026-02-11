@extends('master.app')

@section('toolbar')
    <!-- ...toolbar code jika ada, boleh dikosongkan atau disesuaikan... -->
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
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahRuangan">Tambah</a>
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
                                <th class="py-4 px-3 min-w-80px">Kode Ruang</th>
                                <th class="py-4 px-3 min-w-120px">Nama Ruang</th>
                                <th class="py-4 px-3 min-w-90px">Kapasitas</th>
                                <th class="py-4 px-3 min-w-120px">Keterangan</th>
                                <th class="text-center py-4 px-2 min-w-130px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 fw-semibold">
                            @foreach ($ruangan as $r)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $r->kode_ruang }}</td>
                                <td>{{ $r->nama_ruang }}</td>
                                <td class="text-center">{{ $r->kapasitas }}</td>
                                <td>{{ $r->keterangan }}</td>
                                <td class="text-center">
                                    <!-- DETAIL -->
                                    <button type="button" class="btn btn-icon btn-sm btn-light-primary me-2" title="Detail"
                                        data-bs-toggle="modal" data-bs-target="#modalDetailRuangan{{ $r->id }}">
                                        <i class="bi bi-eye-fill fs-5"></i>
                                    </button>
                                    <!-- EDIT -->
                                    <button type="button" class="btn btn-icon btn-sm btn-light-success me-2" title="Ubah"
                                        data-bs-toggle="modal" data-bs-target="#modalEditRuangan{{ $r->id }}">
                                        <i class="bi bi-pencil-fill fs-5"></i>
                                    </button>
                                    <!-- DELETE -->
                                    <button type="button" class="btn btn-icon btn-sm btn-light-danger" title="Hapus"
                                        data-bs-toggle="modal" data-bs-target="#modalDeleteRuangan{{ $r->id }}">
                                        <i class="bi bi-trash-fill fs-5"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Detail Ruangan -->
                            <div class="modal fade" id="modalDetailRuangan{{ $r->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Ruangan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <dl class="row">
                                                <dt class="col-sm-4">Kode Ruang</dt>
                                                <dd class="col-sm-8">{{ $r->kode_ruang }}</dd>
                                                <dt class="col-sm-4">Nama Ruang</dt>
                                                <dd class="col-sm-8">{{ $r->nama_ruang }}</dd>
                                                <dt class="col-sm-4">Kapasitas</dt>
                                                <dd class="col-sm-8">{{ $r->kapasitas }}</dd>
                                                <dt class="col-sm-4">Keterangan</dt>
                                                <dd class="col-sm-8">{{ $r->keterangan }}</dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Edit Ruangan -->
                            <div class="modal fade" id="modalEditRuangan{{ $r->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ url('ruangan/'.$r->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Ruangan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label required">Kode Ruang</label>
                                                    <input type="text" name="kode_ruang" class="form-control" value="{{ $r->kode_ruang }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label required">Nama Ruang</label>
                                                    <input type="text" name="nama_ruang" class="form-control" value="{{ $r->nama_ruang }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label required">Kapasitas</label>
                                                    <input type="number" name="kapasitas" class="form-control" value="{{ $r->kapasitas }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Keterangan</label>
                                                    <input type="text" name="keterangan" class="form-control" value="{{ $r->keterangan }}">
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

                            <!-- Modal Delete Ruangan -->
                            <div class="modal fade" id="modalDeleteRuangan{{ $r->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ url('ruangan/'.$r->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Hapus Ruangan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Yakin ingin menghapus ruangan <b>{{ $r->nama_ruang }}</b>?</p>
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

<!-- Modal Tambah Ruangan -->
<div class="modal fade" id="modalTambahRuangan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form method="POST" action="{{ url('ruangan') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Ruangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Kode Ruang</label>
                        <input type="text" name="kode_ruang" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Nama Ruang</label>
                        <input type="text" name="nama_ruang" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Kapasitas</label>
                        <input type="number" name="kapasitas" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control">
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