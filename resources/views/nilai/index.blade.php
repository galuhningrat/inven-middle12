@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                    @if (auth()->user()->id_role == 7)
                        Input Nilai
                    @else
                        Manajemen Nilai
                    @endif
                </h1>
                <span class="h-20px border-gray-200 border-start mx-4"></span>
            </div>
            <div class="d-flex align-items-center py-1">
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="/dashboard" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-200 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-dark">Nilai</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-fluid">

            @include('master.notification')

            <!-- Filter Card -->
            <div class="card mb-5">
                <div class="card-header border-0 pt-6">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Filter Nilai</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Pilih mata kuliah untuk input/melihat nilai</span>
                    </h3>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('nilai.index') }}" method="GET" class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Mata Kuliah / Jadwal</label>
                            <select name="id_jadwal" class="form-select form-select-solid" data-control="select2"
                                data-placeholder="Pilih Mata Kuliah" required>
                                <option></option>
                                @foreach ($jadwal as $j)
                                    <option value="{{ $j->id }}"
                                        {{ request('id_jadwal') == $j->id ? 'selected' : '' }}>
                                        {{ $j->matkul->nama_mk }} - {{ $j->rombel->nama_rombel }} ({{ $j->hari }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select form-select-solid">
                                <option value="">Semua Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published
                                </option>
                                <option value="final" {{ request('status') == 'final' ? 'selected' : '' }}>Final</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Cari Mahasiswa</label>
                            <input type="text" name="search" class="form-control form-control-solid"
                                placeholder="NIM/Nama" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i>Filter
                            </button>
                        </div>
                    </form>

                    @if (request('id_jadwal'))
                        <div class="mt-5 d-flex gap-2">
                            <a href="{{ route('nilai.input-massal', ['id_jadwal' => request('id_jadwal')]) }}"
                                class="btn btn-success">
                                <i class="bi bi-plus-circle me-2"></i>Input Nilai Massal
                            </a>

                            @if (auth()->user()->id_role != 8)
                                <!-- Bukan mahasiswa -->
                                <form action="{{ route('nilai.publish-massal') }}" method="POST"
                                    onsubmit="return confirm('Publish semua nilai draft untuk mata kuliah ini?')">
                                    @csrf
                                    <input type="hidden" name="id_jadwal" value="{{ request('id_jadwal') }}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send me-2"></i>Publish Semua Nilai
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Table Card -->
            <div class="card">
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr class="fw-bold text-uppercase text-center">
                                    <th style="width: 50px;">No</th>
                                    <th style="width: 120px;">NIM</th>
                                    <th class="text-start">Nama Mahasiswa</th>
                                    <th style="width: 80px;">Tugas</th>
                                    <th style="width: 80px;">UTS</th>
                                    <th style="width: 80px;">UAS</th>
                                    <th style="width: 80px;">Praktikum</th>
                                    <th style="width: 80px;">Kehadiran</th>
                                    <th style="width: 80px;">Nilai Akhir</th>
                                    <th style="width: 80px;">Huruf</th>
                                    <th style="width: 100px;">Status</th>
                                    <th style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($nilai as $n)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center fw-bold">{{ $n->mahasiswa->nim }}</td>
                                        <td>{{ $n->mahasiswa->user->nama }}</td>
                                        <td class="text-center">{{ number_format($n->nilai_tugas, 0) }}</td>
                                        <td class="text-center">{{ number_format($n->nilai_uts, 0) }}</td>
                                        <td class="text-center">{{ number_format($n->nilai_uas, 0) }}</td>
                                        <td class="text-center">{{ number_format($n->nilai_praktikum, 0) }}</td>
                                        <td class="text-center">{{ number_format($n->nilai_kehadiran, 0) }}</td>
                                        <td class="text-center fw-bold text-primary">
                                            {{ number_format($n->nilai_akhir, 2) }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $n->grade_badge }} fs-6">{{ $n->nilai_huruf }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $n->status_badge }}">{{ ucfirst($n->status) }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if (auth()->user()->id_role != 8)
                                                <!-- Bukan mahasiswa -->
                                                <a href="{{ route('nilai.edit', $n->id) }}"
                                                    class="btn btn-icon btn-sm btn-light-warning" title="Edit">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>

                                                @if ($n->status == 'draft')
                                                    <form action="{{ route('nilai.publish', $n->id) }}" method="POST"
                                                        onsubmit="return confirm('Publish nilai ini?')">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-icon btn-sm btn-light-success"
                                                            title="Publish Nilai">
                                                            <i class="bi bi-send-fill"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('nilai.destroy', $n->id) }}" method="POST"
                                                    style="display:inline;"
                                                    onsubmit="return confirm('Yakin ingin menghapus data nilai ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-icon btn-sm btn-light-danger"
                                                        title="Hapus">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <a href="#" class="btn btn-icon btn-sm btn-light-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailModal{{ $n->id }}" title="Detail">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-10">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-inbox fs-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum Ada Data Nilai</h5>
                                                <p class="text-muted">Pilih mata kuliah dan klik "Input Nilai Massal" untuk
                                                    memulai</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal untuk Mahasiswa -->
    @foreach ($nilai as $n)
        <div class="modal fade" id="detailModal{{ $n->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Nilai - {{ $n->mahasiswa->user->nama }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" style="width: 150px;">Mata Kuliah</td>
                                    <td>: {{ $n->jadwal->matkul->nama_mk }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Dosen Pengampu</td>
                                    <td>: {{ $n->dosen->user->nama }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <hr>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tugas ({{ $n->bobot_tugas }}%)</td>
                                    <td>: {{ $n->nilai_tugas }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">UTS ({{ $n->bobot_uts }}%)</td>
                                    <td>: {{ $n->nilai_uts }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">UAS ({{ $n->bobot_uas }}%)</td>
                                    <td>: {{ $n->nilai_uas }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Praktikum ({{ $n->bobot_praktikum }}%)</td>
                                    <td>: {{ $n->nilai_praktikum }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Kehadiran ({{ $n->bobot_kehadiran }}%)</td>
                                    <td>: {{ $n->nilai_kehadiran }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <hr>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-5">Nilai Akhir</td>
                                    <td class="fw-bold fs-5 text-primary">: {{ number_format($n->nilai_akhir, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-5">Nilai Huruf</td>
                                    <td>: <span class="badge {{ $n->grade_badge }} fs-5">{{ $n->nilai_huruf }}</span>
                                    </td>
                                </tr>
                                @if ($n->catatan_dosen)
                                    <tr>
                                        <td colspan="2">
                                            <hr>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold" colspan="2">Catatan Dosen:</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">{{ $n->catatan_dosen }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
