@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div
                data-kt-swapper="true"
                data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0"
            >
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Data Rombel</h1>
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
                    <li class="breadcrumb-item text-dark">Data Rombel</li>
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
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahRombel">Tambah</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="tabel-custom" class="table table-bordered table-striped table-sm align-middle fs-6 gy-2 w-100">
                            <thead class="bg-gray-100 text-gray-800 border-bottom border-gray-300">
                                <tr class="fw-bold fs-5 text-uppercase">
                                    <th class="text-center py-4 px-2 min-w-30px">No</th>
                                    <th class="py-4 px-3 min-w-40px">Kode</th>
                                    <th class="py-4 px-3 min-w-100px">Nama Rombel</th>
                                    <th class="py-4 px-3 min-w-40px">Tahun Masuk</th>
                                    <th class="py-4 px-3 min-w-150px">Prodi</th>
                                    <th class="py-4 px-3 min-w-50px">Jml Mhs</th>
                                    <th class="py-4 px-3 min-w-200px">Dosen PA</th>
                                    <th class="text-center py-4 px-2 min-w-150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                                @foreach ($rombels as $r)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $r->kode_rombel }}</td>
                                        <td>{{ $r->nama_rombel }}</td>
                                        <td>
                                            {{ $r->tahunMasuk->tahun_awal ?? '-' }} - {{ $r->tahunMasuk->semester ?? '-' }}
                                        </td>
                                        <td>{{ $r->prodi->nama_prodi ?? '-' }}</td>
                                        <td class="text-center">{{ $r->mahasiswa_count ?? '-' }}</td>
                                        <td>{{ $r->dosen->user->nama }}</td>
                                        <td class="text-center">
                                            <!-- Detail Button -->
                                            <a href="{{ route('rombel.detail', $r->id) }}"
                                               class="btn btn-icon btn-sm btn-light-primary me-2"
                                               title="Detail">
                                                <i class="bi bi-eye-fill fs-5"></i>
                                            </a>
                                            <!-- Tambah Mahasiswa Button -->
                                            <a href="{{ route('rombel.tambahMahasiswa', $r->id) }}" class="btn btn-icon btn-sm btn-light-info me-2" title="Tambah Mahasiswa ke Rombel">
                                                <i class="bi bi-person-plus-fill fs-5"></i>
                                            </a>
                                            <!-- Edit Button -->
                                            <button type="button"
                                                class="btn btn-icon btn-sm btn-light-success me-2"
                                                title="Ubah"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditRombel{{ $r->id }}">
                                                <i class="bi bi-pencil-fill fs-5"></i>
                                            </button>
                                            <!-- Delete Button -->
                                            <button type="button"
                                                class="btn btn-icon btn-sm btn-light-danger"
                                                title="Hapus"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDeleteRombel{{ $r->id }}">
                                                <i class="bi bi-trash-fill fs-5"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Modal Detail Rombel -->
                                    <div class="modal fade" id="modalDetailRombel{{ $r->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Rombel</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <dl class="row">
                                                        <dt class="col-sm-4">Kode Rombel</dt>
                                                        <dd class="col-sm-8">{{ $r->kode_rombel }}</dd>
                                                        <dt class="col-sm-4">Nama Rombel</dt>
                                                        <dd class="col-sm-8">{{ $r->nama_rombel }}</dd>
                                                        <dt class="col-sm-4">Nama Dosen PA</dt>
                                                        <dd class="col-sm-8">{{ $r->dosen->user->nama }}</dd>
                                                        <dt class="col-sm-4">Tahun Masuk</dt>
                                                        <dd class="col-sm-8">
                                                            {{ $r->tahunMasuk->tahun_akademik ?? '-' }} {{ $r->tahunMasuk->semester ?? '-' }}
                                                        </dd>
                                                        <dt class="col-sm-4">Prodi</dt>
                                                        <dd class="col-sm-8">{{ $r->prodi->nama_prodi ?? '-' }}</dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal Edit Rombel -->
                                    <div class="modal fade" id="modalEditRombel{{ $r->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <form action="{{ route('rombel.update', $r->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Rombel</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-10">
                                                                <label class="form-label required">Kode Rombel</label>
                                                                <input type="text" name="kode_rombel" class="form-control form-control-solid"
                                                                    value="{{ $r->kode_rombel }}" maxlength="6" required />
                                                            </div>
                                                            <div class="col-md-6 mb-10">
                                                                <label class="form-label required">Nama Rombel</label>
                                                                <input type="text" name="nama_rombel" class="form-control form-control-solid"
                                                                    value="{{ $r->nama_rombel }}" required />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-10">
                                                                <label class="form-label required">Tahun Masuk</label>
                                                                <select name="tahun_masuk" class="form-select form-select-solid" required>
                                                                    @foreach($tahunAkademiks as $ta)
                                                                        <option value="{{ $ta->id }}" {{ $r->tahun_akademik == $ta->id ? 'selected' : '' }}>
                                                                            {{ $ta->tahun_awal }} {{ $ta->semester }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-10">
                                                                <label class="form-label required">Program Studi</label>
                                                                <select name="id_prodi" class="form-select form-select-solid" required>
                                                                    @foreach($prodis as $prodi)
                                                                        <option value="{{ $prodi->id }}" {{ $r->id_prodi == $prodi->id ? 'selected' : '' }}>
                                                                            {{ $prodi->nama_prodi }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 mb-10">
                                                                <label class="form-label required">Dosen Pembimbing Akademik</label>
                                                                <select name="id_dosen" class="form-select form-select-solid" required>
                                                                    @foreach($dosen as $dos)
                                                                        <option value="{{ $dos->id }}" {{ $r->id_dosen == $dos->id ? 'selected' : '' }}>
                                                                            {{ $dos->user->nama }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
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
                                    <!-- Modal Delete Rombel -->
                                    <div class="modal fade" id="modalDeleteRombel{{ $r->id }}" tabindex="-1" aria-labelledby="modalDeleteRombelLabel{{ $r->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('rombel.destroy', $r->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Hapus Rombel</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus rombel <b>{{ $r->nama_rombel }}</b>?</p>
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
    <!-- Modal Tambah Rombel -->
    <div class="modal fade" id="modalTambahRombel" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <form action="{{ route('rombel.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Rombel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 mb-10">
                                <label class="form-label required">Kode Rombel</label>
                                <input type="text" name="kode_rombel" class="form-control form-control-solid" maxlength="6" required />
                            </div>
                            <div class="col-md-8 mb-10">
                                <label class="form-label required">Nama Rombel</label>
                                <input type="text" name="nama_rombel" class="form-control form-control-solid" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-10">
                                <label class="form-label required">Prodi</label>
                                <select name="id_prodi" class="form-select form-select-solid" required>
                                    <option value="">-- Pilih Prodi --</option>
                                    @foreach($prodis as $prodi)
                                        <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-10">
                                <label class="form-label required">Tahun Masuk</label>
                                <select name="tahun_masuk" class="form-select form-select-solid" required>
                                    <option value="">-- Pilih Tahun Akademik --</option>
                                    @foreach($tahunAkademiks as $ta)
                                        <option value="{{ $ta->id }}">{{ $ta->tahun_awal }} {{ $ta->semester }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-10">
                                <label class="form-label required">Dosen Pembimbing Akademik</label>
                                <select name="id_dosen" class="form-select form-select-solid" required>
                                    <option value="">-- Pilih Dosen --</option>
                                    @foreach($dosen as $dos)
                                        <option value="{{ $dos->id }}">{{ $dos->user->nama }}</option>
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
