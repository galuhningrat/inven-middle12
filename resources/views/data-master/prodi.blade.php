@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Data Program Studi</h1>
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
                    <li class="breadcrumb-item text-dark">Data Program Studi</li>
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
                                    data-bs-target="#modalTambahProdi">Tambah</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="tabel-custom"
                            class="table table-bordered table-striped table-sm align-middle fs-6 gy-2 w-100">
                            <thead class="bg-gray-100 text-gray-800 border-bottom border-gray-300">
                                <tr class="fw-bold fs-6 text-uppercase">
                                    <th class="text-center py-4 px-2 min-w-20px">No</th>
                                    <th class="py-4 px-3 min-w-40px">Kode</th>
                                    <th class="py-4 px-3 min-w-100px">Nama Program Studi</th>
                                    <th class="py-4 px-3 min-w-50px">Fakultas</th>
                                    <th class="py-4 px-3 min-w-300px">Nama Kaprodi</th>
                                    <th class="py-4 px-3 min-w-50px">Akreditasi</th>
                                    <th class="py-4 px-3 min-w-75px">Jml Mhs</th>
                                    <th class="text-center py-4 px-2 min-w-50px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                                @foreach ($data as $p)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $p->kode_prodi }}</td>
                                        <td>{{ $p->nama_prodi }}</td>
                                        <td class="text-center">{{ $p->fakultas->kode_fakultas }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    {{-- ✅ PERBAIKAN: Gunakan kaprodi, bukan dosen->user --}}
                                                    <img src="{{ asset($p->kaprodi->img ?? 'foto_users/default.png') }}"
                                                        alt="foto" class="w-100" />
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-800 fw-bold">{{ $p->kaprodi->nama }}</span>
                                                    <span>{{ $p->kaprodi->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $p->status_akre }}</span>
                                        </td>
                                        <td class="text-center">{{ $p->mahasiswa_count }}</td>
                                        <td class="text-center">
                                            <!-- READ/DETAIL -->
                                            <button type="button" class="btn btn-icon btn-sm btn-light-primary me-2"
                                                title="Detail" data-bs-toggle="modal"
                                                data-bs-target="#modalDetailProdi{{ $p->id }}">
                                                <i class="bi bi-eye-fill fs-5"></i>
                                            </button>
                                            <!-- UPDATE/EDIT -->
                                            <button type="button" class="btn btn-icon btn-sm btn-light-success me-2"
                                                title="Ubah" data-bs-toggle="modal"
                                                data-bs-target="#modalEditProdi{{ $p->id }}">
                                                <i class="bi bi-pencil-fill fs-5"></i>
                                            </button>
                                            <!-- DELETE -->
                                            <button type="button" class="btn btn-icon btn-sm btn-light-danger"
                                                title="Hapus" data-bs-toggle="modal"
                                                data-bs-target="#modalDeleteProdi{{ $p->id }}">
                                                <i class="bi bi-trash-fill fs-5"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Detail Prodi -->
                                    <div class="modal fade" id="modalDetailProdi{{ $p->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Program Studi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <dl class="row">
                                                        <dt class="col-sm-4">Kode Prodi</dt>
                                                        <dd class="col-sm-8">{{ $p->kode_prodi }}</dd>
                                                        <dt class="col-sm-4">Nama Prodi</dt>
                                                        <dd class="col-sm-8">{{ $p->nama_prodi }}</dd>
                                                        <dt class="col-sm-4">Fakultas</dt>
                                                        <dd class="col-sm-8">{{ $p->fakultas->nama_fakultas }}</dd>
                                                        <dt class="col-sm-4">Kaprodi</dt>
                                                        {{-- ✅ PERBAIKAN: Gunakan kaprodi->nama, bukan dosen->user->nama --}}
                                                        <dd class="col-sm-8">{{ $p->kaprodi->nama }}</dd>
                                                        <dt class="col-sm-4">Akreditasi</dt>
                                                        <dd class="col-sm-8">{{ $p->status_akre }}</dd>
                                                        <dt class="col-sm-4">Jumlah Mahasiswa</dt>
                                                        <dd class="col-sm-8">{{ $p->mahasiswa_count }}</dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Edit Prodi -->
                                    <div class="modal fade" id="modalEditProdi{{ $p->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('data-prodi.update', $p->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Program Studi</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-10">
                                                            <label class="required form-label">Nama Fakultas</label>
                                                            <select name="id_fakultas"
                                                                class="form-select form-select-solid" required>
                                                                @foreach ($fakultas as $f)
                                                                    <option value="{{ $f->id }}"
                                                                        {{ $p->id_fakultas == $f->id ? 'selected' : '' }}>
                                                                        {{ $f->nama_fakultas }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4 mb-10">
                                                                <label class="required form-label">Kode Prodi</label>
                                                                <input type="text"
                                                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                                    name="kode_prodi" value="{{ $p->kode_prodi }}"
                                                                    maxlength="6" required />
                                                            </div>
                                                            <div class="col-md-8 mb-10">
                                                                <label class="required form-label">Nama Program
                                                                    Studi</label>
                                                                <input type="text"
                                                                    class="form-control form-control-solid"
                                                                    name="nama_prodi" value="{{ $p->nama_prodi }}"
                                                                    required />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-10">
                                                                <label class="required form-label">Nama Kaprodi</label>
                                                                <select name="id_kaprodi"
                                                                    class="form-select form-select-solid" required>
                                                                    @foreach ($kaprodis as $kaprodi)
                                                                        <option value="{{ $kaprodi->id }}"
                                                                            {{ $p->id_kaprodi == $kaprodi->id ? 'selected' : '' }}>
                                                                            {{ $kaprodi->nama }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-10">
                                                                <label class="form-label">Status Akreditasi</label>
                                                                <select name="status_akre"
                                                                    class="form-select form-select-solid" required>
                                                                    <option value="Unggul"
                                                                        {{ $p->status_akre == 'Unggul' ? 'selected' : '' }}>
                                                                        Unggul</option>
                                                                    <option value="Baik Sekali"
                                                                        {{ $p->status_akre == 'Baik Sekali' ? 'selected' : '' }}>
                                                                        Baik Sekali</option>
                                                                    <option value="Baik"
                                                                        {{ $p->status_akre == 'Baik' ? 'selected' : '' }}>
                                                                        Baik</option>
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

                                    <!-- Modal Delete Prodi -->
                                    <div class="modal fade" id="modalDeleteProdi{{ $p->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('data-prodi.destroy', $p->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Hapus Program Studi</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Yakin ingin menghapus Prodi <b>{{ $p->nama_prodi }}</b>?</p>
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

    <!-- Modal Tambah Prodi -->
    <div class="modal fade" id="modalTambahProdi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <form id="form_tambah_prodi" method="POST" action="{{ route('data-prodi.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Program Studi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Fakultas -->
                        <div class="mb-10">
                            <label for="id_fakultas" class="required form-label">Nama Fakultas</label>
                            <select name="id_fakultas" id="id_fakultas" class="form-select form-select-solid"
                                data-control="select2" data-placeholder="Pilih Fakultas" required>
                                <option></option>
                                @foreach ($fakultas as $f)
                                    <option value="{{ $f->id }}">{{ $f->nama_fakultas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-10">
                                <label for="kode_prodi" class="required form-label">Kode Prodi</label>
                                <input type="text" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                    inputmode="numeric" pattern="[0-9]*"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)" maxlength="6"
                                    name="kode_prodi" id="kode_prodi" placeholder="Kode Prodi dari PDDIKTI" required />
                            </div>
                            <div class="col-md-8 mb-10">
                                <label for="nama_prodi" class="required form-label">Nama Program Studi</label>
                                <input type="text" class="form-control form-control-solid" name="nama_prodi"
                                    id="nama_prodi" placeholder="S1 Teknik Informatika / Manajemen, dll" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-10">
                                <label for="id_kaprodi" class="required form-label">Nama Kaprodi</label>
                                <select name="id_kaprodi" id="id_kaprodi" class="form-select form-select-solid" required>
                                    <option value="">-- Pilih Kaprodi --</option>
                                    @foreach ($kaprodis as $kaprodi)
                                        <option value="{{ $kaprodi->id }}">{{ $kaprodi->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-10">
                                <label for="status_akre" class="form-label">Status Akreditasi</label>
                                <select name="status_akre" id="status_akre" class="form-select form-select-solid"
                                    data-control="select2" data-placeholder="Pilih Status Akreditasi" required>
                                    <option></option>
                                    <option value="Unggul">Unggul</option>
                                    <option value="Baik Sekali">Baik Sekali</option>
                                    <option value="Baik">Baik</option>
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
