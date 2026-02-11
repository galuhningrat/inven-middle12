@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Data Pengguna</h1>
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
                    <li class="breadcrumb-item text-dark">Data Pengguna</li>
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
                                    data-bs-target="#modalTambahUser">Tambah</a>
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
                                    <th class="py-4 px-3 min-w-200px">User</th>
                                    <th class="py-4 px-3 min-w-125px">Role</th>
                                    <th class="py-4 px-3 min-w-125px">Terakhir Login</th>
                                    <th class="py-4 px-3 min-w-125px">Dibuat</th>
                                    <th class="py-4 px-3 min-w-125px">Status</th>
                                    <th class="text-center py-4 px-2 min-w-100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                                @foreach ($users as $u)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        {{-- FIX BAGIAN 1: Update Avatar di Tabel [cite: 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28] --}}
                                        <td class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                <div class="symbol-label">
                                                    @php
                                                        $imgPath = $u->img ?? 'foto_users/default.png';
                                                        $fullPath = public_path($imgPath);
                                                    @endphp

                                                    @if (file_exists($fullPath))
                                                        <img src="{{ asset($imgPath) }}" alt="{{ $u->nama }}"
                                                            class="w-100" style="object-fit: cover;" />
                                                    @else
                                                        <img src="{{ asset('foto_users/default.png') }}" alt="Default"
                                                            class="w-100" style="object-fit: cover;" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold">{{ $u->nama }}</span>
                                                <span>{{ $u->email }}</span>
                                            </div>
                                        </td>

                                        <td class="text-center">{{ $u->role->nama_role }}</td>
                                        <td class="text-center">
                                            <div class="badge badge-light fw-bolder">{{ $u->last_login ?? '-' }}</div>
                                        </td>
                                        <td class="text-center">{{ $u->created_at }}</td>
                                        <td class="text-center">
                                            <div class="form-check form-check-solid form-switch d-inline-block">
                                                <input class="form-check-input w-45px h-25px toggle-status" type="checkbox"
                                                    id="allowuser-{{ $u->id }}" data-id="{{ $u->id }}"
                                                    {{ $u->status === 'Aktif' ? 'checked' : '' }} />
                                                <label class="form-check-label"
                                                    for="allowuser-{{ $u->id }}"></label>
                                            </div>

                                            {{-- Form untuk update status --}}
                                            <form id="form-status-{{ $u->id }}"
                                                action="{{ route('master-pengguna.updateStatus', $u->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" id="status-input-{{ $u->id }}"
                                                    value="{{ $u->status === 'Aktif' ? 'true' : 'false' }}">
                                            </form>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-icon btn-sm btn-light-primary me-2"
                                                title="Detail" data-bs-toggle="modal"
                                                data-bs-target="#modalDetailUser{{ $u->id }}">
                                                <i class="bi bi-eye-fill fs-5"></i>
                                            </button>
                                            <button type="button" class="btn btn-icon btn-sm btn-light-success me-2"
                                                title="Ubah" data-bs-toggle="modal"
                                                data-bs-target="#modalEditUser{{ $u->id }}">
                                                <i class="bi bi-pencil-fill fs-5"></i>
                                            </button>
                                            <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-hapus"
                                                title="Hapus" data-id="{{ $u->id }}">
                                                <i class="bi bi-trash-fill fs-5"></i>
                                            </button>

                                            <form id="form-hapus-{{ $u->id }}"
                                                action="{{ route('master-pengguna.destroy', $u->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalDetailUser{{ $u->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Pengguna</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <dl class="row">
                                                        <dt class="col-sm-4">Nama Lengkap</dt>
                                                        <dd class="col-sm-8">{{ $u->nama }}</dd>
                                                        <dt class="col-sm-4">Email</dt>
                                                        <dd class="col-sm-8">{{ $u->email }}</dd>
                                                        <dt class="col-sm-4">Role</dt>
                                                        <dd class="col-sm-8">{{ $u->role->nama_role }}</dd>
                                                        <dt class="col-sm-4">Status</dt>
                                                        <dd class="col-sm-8">{{ $u->status }}</dd>
                                                        <dt class="col-sm-4">Terakhir Login</dt>
                                                        <dd class="col-sm-8">{{ $u->last_login ?? '-' }}</dd>
                                                        <dt class="col-sm-4">Dibuat</dt>
                                                        <dd class="col-sm-8">{{ $u->created_at }}</dd>
                                                        <dt class="col-sm-4">Foto</dt>
                                                        <dd class="col-sm-8">
                                                            @php
                                                                $imgPath = $u->img ?? 'foto_users/default.png';
                                                                $fullPath = public_path($imgPath);
                                                            @endphp

                                                            @if (file_exists($fullPath))
                                                                <img src="{{ asset($imgPath) }}"
                                                                    alt="{{ $u->nama }}" class="img-thumbnail"
                                                                    style="max-width:150px;">
                                                            @else
                                                                <img src="{{ asset('foto_users/default.png') }}"
                                                                    alt="default" class="img-thumbnail"
                                                                    style="max-width:150px;">
                                                            @endif
                                                        </dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Edit User -->
                                    <div class="modal fade" id="modalEditUser{{ $u->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2>Edit Pengguna</h2>
                                                    <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                        data-bs-dismiss="modal">
                                                        <span class="svg-icon svg-icon-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <rect opacity="0.5" x="6" y="17.314" width="16"
                                                                    height="2" rx="1"
                                                                    transform="rotate(-45 6 17.314)" fill="black" />
                                                                <rect x="7.414" y="6" width="16" height="2"
                                                                    rx="1" transform="rotate(45 7.414 6)"
                                                                    fill="black" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                    <form id="form-edit-pengguna-{{ $u->id }}"
                                                        action="{{ route('user.update', $u->id) }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')

                                                        <!-- Preview Foto di Tengah -->
                                                        <div class="text-center mb-7">
                                                            <div
                                                                class="symbol symbol-100px symbol-circle mb-4 mx-auto position-relative">
                                                                @if ($u->img && file_exists(public_path('storage/' . $u->img)))
                                                                    <img id="preview-foto-edit-{{ $u->id }}"
                                                                        src="{{ asset('storage/' . $u->img) }}"
                                                                        alt="{{ $u->nama }}"
                                                                        class="w-100 rounded-circle"
                                                                        style="object-fit: cover;" />
                                                                @else
                                                                    <img id="preview-foto-edit-{{ $u->id }}"
                                                                        src="{{ asset('storage/foto_users/default.png') }}"
                                                                        alt="Default" class="w-100 rounded-circle"
                                                                        style="object-fit: cover;" />
                                                                @endif
                                                                <div class="position-absolute bottom-0 end-0">
                                                                    <label
                                                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                                        style="cursor: pointer;">
                                                                        <i class="bi bi-pencil-fill fs-7"></i>
                                                                        <input type="file" class="d-none"
                                                                            name="img" accept="image/*"
                                                                            onchange="previewImageEdit{{ $u->id }}(event)">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-text">Klik ikon pensil untuk mengganti foto
                                                            </div>
                                                        </div>

                                                        <div class="d-flex flex-column mb-7 fv-row">
                                                            <label class="required fs-6 fw-bold form-label mb-2">Nama
                                                                Lengkap</label>
                                                            <input type="text" class="form-control form-control-solid"
                                                                name="nama" value="{{ $u->nama }}" required />
                                                        </div>

                                                        <div class="d-flex flex-column mb-7 fv-row">
                                                            <label
                                                                class="required fs-6 fw-bold form-label mb-2">Email</label>
                                                            <input type="email" class="form-control form-control-solid"
                                                                name="email" value="{{ $u->email }}" required />
                                                        </div>

                                                        <div class="d-flex flex-column mb-7 fv-row">
                                                            <label class="fs-6 fw-bold form-label mb-2">Password <small
                                                                    class="text-muted">(isi jika ingin
                                                                    ganti)</small></label>
                                                            <input type="password" class="form-control form-control-solid"
                                                                name="password" placeholder="Masukkan password baru" />
                                                        </div>

                                                        <div class="row mb-7">
                                                            <div class="col-md-8 fv-row">
                                                                <label
                                                                    class="required fs-6 fw-bold form-label mb-2">Role</label>
                                                                <select name="id_role"
                                                                    class="form-select form-select-solid" required>
                                                                    @foreach ($roles as $role)
                                                                        <option value="{{ $role->id }}"
                                                                            {{ $role->id == $u->id_role ? 'selected' : '' }}>
                                                                            {{ $role->nama_role }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 fv-row">
                                                                <label
                                                                    class="required fs-6 fw-bold form-label mb-2">Status</label>
                                                                <div class="form-check form-check-solid form-switch pt-2">
                                                                    <input class="form-check-input w-45px h-25px"
                                                                        type="checkbox" name="status" value="Aktif"
                                                                        {{ $u->status === 'Aktif' ? 'checked' : '' }}>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="text-center pt-5 d-flex justify-content-between">
                                                            <button type="button" class="btn btn-light-danger"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="bi bi-check-circle"></i> Simpan Perubahan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        function previewImageEdit{{ $u->id }}(event) {
                                            const preview = document.getElementById('preview-foto-edit-{{ $u->id }}');
                                            const file = event.target.files[0];
                                            if (file) {
                                                if (file.size > 2048000) {
                                                    alert('Ukuran file terlalu besar! Maksimal 2MB');
                                                    event.target.value = '';
                                                    return;
                                                }
                                                preview.src = URL.createObjectURL(file);
                                            }
                                        }
                                    </script>

                                    <div class="modal fade" id="modalDeleteUser{{ $u->id }}" tabindex="-1"
                                        aria-labelledby="modalDeleteUserLabel{{ $u->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2>Hapus Pengguna</h2>
                                                    <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                        data-bs-dismiss="modal">
                                                        <span class="svg-icon svg-icon-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <rect opacity="0.5" x="6" y="17.314" width="16"
                                                                    height="2" rx="1"
                                                                    transform="rotate(-45 6 17.314)" fill="black" />
                                                                <rect x="7.414" y="6" width="16" height="2"
                                                                    rx="1" transform="rotate(45 7.414 6)"
                                                                    fill="black" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </div>
                                                <form action="{{ route('user.destroy', $u->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus pengguna
                                                            <b>{{ $u->nama }}</b>?
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

    <!-- Modal Tambah User -->
    <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Tambah Pengguna</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.314" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.314)" fill="black" />
                                <rect x="7.414" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.414 6)" fill="black" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <form id="form-tambah-pengguna" class="form" action="{{ route('user.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- Preview Foto di Tengah -->
                        <div class="text-center mb-7">
                            <div class="symbol symbol-100px symbol-circle mb-4 mx-auto">
                                <img id="preview-foto-tambah" src="{{ asset('storage/foto_users/default.png') }}"
                                    alt="Preview" class="w-100 rounded-circle" style="object-fit: cover;" />
                            </div>
                            <label class="btn btn-sm btn-light-primary">
                                <i class="bi bi-upload fs-5"></i> Pilih Foto Profil
                                <input type="file" class="d-none" name="img" accept="image/*"
                                    onchange="previewImageTambah(event)">
                            </label>
                            <div class="form-text">Format: JPG, JPEG, PNG, GIF, WEBP (Max 2MB)</div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="required fs-6 fw-bold form-label mb-2">Nama Lengkap</label>
                            <input type="text" class="form-control form-control-solid"
                                placeholder="Masukkan nama lengkap" name="nama" required />
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="required fs-6 fw-bold form-label mb-2">Email</label>
                            <input type="email" class="form-control form-control-solid" placeholder="Masukkan email"
                                name="email" required />
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="required fs-6 fw-bold form-label mb-2">Password</label>
                            <input type="password" class="form-control form-control-solid"
                                placeholder="Masukkan password" name="password" required />
                        </div>

                        <div class="row mb-7">
                            <div class="col-md-8 fv-row">
                                <label class="required fs-6 fw-bold form-label mb-2">Role</label>
                                <select name="id_role" class="form-select form-select-solid" required>
                                    <option value="">Pilih Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="required fs-6 fw-bold form-label mb-2">Status</label>
                                <div class="form-check form-check-solid form-switch pt-2">
                                    <input class="form-check-input w-45px h-25px" type="checkbox" name="status"
                                        value="Aktif" checked>
                                </div>
                            </div>
                        </div>

                        <div class="text-center pt-5 d-flex justify-content-between">
                            <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImageTambah(event) {
            const preview = document.getElementById('preview-foto-tambah');
            const file = event.target.files[0];
            if (file) {
                // Validasi ukuran file
                if (file.size > 2048000) { // 2MB
                    alert('Ukuran file terlalu besar! Maksimal 2MB');
                    event.target.value = '';
                    return;
                }
                preview.src = URL.createObjectURL(file);
            }
        }
    </script>
@endsection

{{-- @foreach ($users as $u)
    <tr>
        <td>
            <!-- DEBUG INFO -->
            <div style="background: #f0f0f0; padding: 10px; margin-bottom: 10px; font-size: 11px;">
                <strong>DEBUG:</strong><br>
                📁 Path DB: <code>{{ $u->img }}</code><br>
                🌐 Full URL: <code>{{ asset('storage/' . $u->img) }}</code><br>
                ✅ File exists: {{ file_exists(public_path('storage/' . $u->img)) ? 'YES' : 'NO' }}<br>
                📂 Full path: <code>{{ public_path('storage/' . $u->img) }}</code>
            </div>

            <!-- AVATAR -->
            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                <div class="symbol-label">
                    @if ($u->img && file_exists(public_path('storage/' . $u->img)))
                        <img src="{{ asset('storage/' . $u->img) }}" alt="{{ $u->nama }}" class="w-100" />
                    @else
                        <img src="{{ asset('storage/foto_users/default.png') }}" alt="Default" class="w-100" />
                    @endif
                </div>
            </div>
        </td>
        ...
    </tr>
@endforeach --}}
