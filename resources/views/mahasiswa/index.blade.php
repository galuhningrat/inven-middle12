@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Data Mahasiswa</h1>
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
                    <li class="breadcrumb-item text-dark">Data Mahasiswa</li>
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
                                    data-bs-target="#cari-akun-mahasiswa">Tambah</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="tabel-custom"
                            class="table table-bordered table-striped table-sm align-middle fs-6 gy-2 w-100">
                            <thead class="bg-gray-100 text-gray-800 border-bottom border-gray-300">
                                <tr class="fw-bold fs-5 text-uppercase text-center">
                                    <th class="w-10px py-2 px-2 text-center">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                data-kt-check-target="#tabel-custom .form-check-input" value="1" />
                                        </div>
                                    </th>
                                    <th class="w-10px py-2 px-2 text-center min-w-50px">No</th>
                                    <th class="py-4 px-3 min-w-80px">NIM</th>
                                    <th class="py-4 px-3 min-w-250px">Nama</th>
                                    <th class="py-4 px-3 min-w-150px">Prodi</th>
                                    <th class="py-4 px-3 min-w-100px">No HP/WA</th>
                                    <th class="py-4 px-3 min-w-75px">JK</th>
                                    <th class="py-4 px-3 text-center min-w-100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold">
                                @foreach ($mahasiswa as $row)
                                    <tr>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox" />
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">{{ $row->nim }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    @php
                                                        $avatarPath = $row->user->img ?? 'foto_users/default.png';
                                                        $avatarUrl = file_exists(public_path($avatarPath))
                                                            ? asset($avatarPath)
                                                            : asset('foto_users/default.png');
                                                    @endphp
                                                    <img src="{{ $avatarUrl }}"
                                                        alt="{{ $row->user->nama ?? 'Mahasiswa' }}" class="w-100"
                                                        style="object-fit: cover;" />
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-800 fw-bold">{{ $row->user->nama }}</span>
                                                    <span>{{ $row->user->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">{{ $row->prodi->nama_prodi ?? '-' }}</td>
                                        <td class="text-center align-middle">{{ $row->hp }}</td>
                                        <td class="text-center align-middle">{{ $row->jenis_kelamin }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('mahasiswa.biodata', $row->nim) }}"
                                                class="btn btn-icon btn-sm btn-light-primary me-2" title="Detail">
                                                <i class="bi bi-eye-fill fs-5"></i>
                                            </a>
                                            <a href="{{ route('mahasiswa.edit', $row->id) }}"
                                                class="btn btn-icon btn-sm btn-light-success me-2" title="Ubah">
                                                <i class="bi bi-pencil-fill fs-5"></i>
                                            </a>
                                            <form id="delete-mahasiswa-{{ $row->id }}"
                                                action="{{ route('mahasiswa.destroy', $row->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-icon btn-sm btn-light-danger"
                                                    title="Hapus"
                                                    onclick="if(confirm('Yakin ingin menghapus data ini?')) document.getElementById('delete-mahasiswa-{{ $row->id }}').submit();">
                                                    <i class="bi bi-trash-fill fs-5"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--
    ╔══════════════════════════════════════════════════════════════════╗
    ║  BUG FIX #2 — Modal Tambah Mahasiswa: Dropdown Tahun Akademik  ║
    ║                                                                  ║
    ║  MASALAH SEBELUMNYA:                                             ║
    ║  Field Tahun Akademik hanya menampilkan satu record aktif       ║
    ║  sebagai readonly input. Jika $tahunAkademikAktif = null        ║
    ║  (tidak ada yang aktif / lebih dari satu aktif), field          ║
    ║  menampilkan "Data tidak ditemukan" dan tidak bisa diklik.      ║
    ║                                                                  ║
    ║  PERBAIKAN:                                                      ║
    ║  1. Ubah menjadi <select> dropdown yang menampilkan SEMUA       ║
    ║     tahun akademik dari variabel $tahunAkademiks.               ║
    ║  2. Record yang berstatus aktif di-preselect secara otomatis.   ║
    ║  3. Tetap bisa berfungsi meskipun tidak ada yang aktif.         ║
    ║                                                                  ║
    ║  PERLU DILAKUKAN DI CONTROLLER (MahasiswaController@index):     ║
    ║  Tambahkan $tahunAkademiks ke data yang dikirim ke view:        ║
    ║                                                                  ║
    ║  $tahunAkademiks = TahunAkademik::orderBy('tahun_awal', 'desc') ║
    ║                        ->orderBy('semester')->get();            ║
    ║  return view('mahasiswa.index', compact(                        ║
    ║      'mahasiswa', 'users', 'prodis',                            ║
    ║      'tahunAkademikAktif', 'tahunAkademiks'   // ← tambah ini  ║
    ║  ));                                                             ║
    ╚══════════════════════════════════════════════════════════════════╝
    --}}

    <!-- Modal Tambah Mahasiswa -->
    <div class="modal fade" id="cari-akun-mahasiswa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Tambah Mahasiswa</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.314" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.314)" fill="black" />
                                <rect x="7.414" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.414 6)" fill="black" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <form id="form-tambah-mahasiswa" class="form" action="{{ route('mahasiswa.store') }}" method="POST">
                        @csrf

                        <!-- STEP 1: Pilih User -->
                        <div class="mb-7">
                            <label class="required fs-6 fw-bold form-label mb-2">Cari Akun Pengguna</label>
                            <select name="id_users" id="select-user" class="form-select form-select-solid"
                                data-control="select2" data-hide-search="false"
                                data-placeholder="Pilih Nama Mahasiswa" required>
                                <option></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->nama }} - {{ $user->email }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- STEP 2: Pilih Prodi, NIM, dan Tahun Akademik -->
                        <div id="step-2" style="display: none;">
                            <div class="mb-7">
                                <label class="required fs-6 fw-bold form-label mb-2">Program Studi</label>
                                <select name="id_prodi" class="form-select form-select-solid" required>
                                    <option value="">Pilih Prodi</option>
                                    @foreach ($prodis as $prodi)
                                        <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-7">
                                <label class="required fs-6 fw-bold form-label mb-2">NIM</label>
                                <input type="text" name="nim" id="nim"
                                    class="form-control form-control-solid"
                                    placeholder="Masukkan NIM" required />
                            </div>

                            {{-- =============================================
                                 FIX: Ganti readonly input → dropdown <select>
                                 Menampilkan semua tahun akademik yang tersedia,
                                 dengan record aktif otomatis terpilih.
                            ============================================== --}}
                            <div class="mb-7">
                                <label for="tahun_masuk" class="required fs-6 fw-bold form-label mb-2">
                                    Tahun Akademik
                                </label>

                                @if(isset($tahunAkademiks) && $tahunAkademiks->count() > 0)
                                    {{-- Dropdown lengkap — memerlukan $tahunAkademiks dari controller --}}
                                    <select name="tahun_masuk" id="tahun_masuk"
                                        class="form-select form-select-solid" required>
                                        <option value="">-- Pilih Tahun Akademik --</option>
                                        @foreach($tahunAkademiks as $ta)
                                            <option value="{{ $ta->id }}"
                                                {{ ($tahunAkademikAktif && $tahunAkademikAktif->id == $ta->id) ? 'selected' : '' }}>
                                                {{ $ta->tahun_awal }}/{{ $ta->tahun_akhir }}
                                                - Semester {{ $ta->semester }}
                                                @if($ta->status_aktif)
                                                    ✓ (Aktif)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($tahunAkademikAktif)
                                        <div class="form-text text-success mt-1">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Tahun akademik aktif:
                                            <strong>{{ $tahunAkademikAktif->tahun_awal }}/{{ $tahunAkademikAktif->tahun_akhir }}
                                            - Semester {{ $tahunAkademikAktif->semester }}</strong>
                                            sudah terpilih otomatis.
                                        </div>
                                    @else
                                        <div class="form-text text-warning mt-1">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            Tidak ada tahun akademik aktif. Pilih secara manual.
                                        </div>
                                    @endif

                                @elseif($tahunAkademikAktif)
                                    {{-- Fallback: hanya ada $tahunAkademikAktif (controller belum diupdate) --}}
                                    <input type="text" class="form-control form-control-solid"
                                        value="{{ $tahunAkademikAktif->tahun_awal }}/{{ $tahunAkademikAktif->tahun_akhir }} - Semester {{ $tahunAkademikAktif->semester }}"
                                        readonly>
                                    <input type="hidden" name="tahun_masuk" value="{{ $tahunAkademikAktif->id }}">
                                    <div class="form-text text-muted mt-1">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Terisi otomatis dari tahun akademik aktif.
                                    </div>

                                @else
                                    {{-- Tidak ada tahun akademik sama sekali --}}
                                    <div class="alert alert-danger d-flex align-items-center py-3 mt-1">
                                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger"></i>
                                        <div>
                                            <strong>Tidak ada Tahun Akademik yang aktif!</strong><br>
                                            <small>Silakan aktifkan tahun akademik terlebih dahulu melalui menu
                                            <a href="{{ route('tahun-akademik.index') }}" target="_blank">
                                                Data Master → Tahun Akademik</a>.</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            {{-- END FIX --}}
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex justify-content-between pt-10">
                            <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">Batal</button>
                            <div>
                                <button type="button" id="btn-lanjutkan" class="btn btn-primary">Lanjutkan</button>
                                <button type="submit" id="btn-simpan" class="btn btn-success" style="display: none;">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end Modal Tambah Mahasiswa -->

    <script>
        // Toggle Step 2 visibility saat tombol "Lanjutkan" diklik
        document.addEventListener('DOMContentLoaded', function () {
            const btnLanjutkan = document.getElementById('btn-lanjutkan');
            const btnSimpan    = document.getElementById('btn-simpan');
            const step2        = document.getElementById('step-2');
            const selectUser   = document.getElementById('select-user');

            if (btnLanjutkan) {
                btnLanjutkan.addEventListener('click', function () {
                    if (!selectUser.value) {
                        alert('Silakan pilih akun pengguna terlebih dahulu.');
                        return;
                    }
                    step2.style.display = 'block';
                    btnLanjutkan.style.display = 'none';
                    btnSimpan.style.display = 'inline-block';
                });
            }

            // Reset modal saat ditutup
            const modal = document.getElementById('cari-akun-mahasiswa');
            if (modal) {
                modal.addEventListener('hidden.bs.modal', function () {
                    step2.style.display = 'none';
                    btnLanjutkan.style.display = 'inline-block';
                    btnSimpan.style.display = 'none';
                });
            }
        });
    </script>
@endsection
