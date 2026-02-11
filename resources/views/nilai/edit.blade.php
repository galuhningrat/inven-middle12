@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Edit Nilai Individu</h1>
            </div>
            <div class="d-flex align-items-center py-1">
                <a href="{{ route('nilai.index', ['id_jadwal' => $nilai->id_jadwal]) }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Mahasiswa: {{ $nilai->mahasiswa->user->nama }} ({{ $nilai->mahasiswa->nim }})
                    </h3>
                </div>
                <form action="{{ route('nilai.update', $nilai->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row g-9 mb-8">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nilai Tugas (20%)</label>
                                <input type="number" name="nilai_tugas" class="form-control"
                                    value="{{ $nilai->nilai_tugas }}" step="0.01" min="0" max="100">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nilai UTS (30%)</label>
                                <input type="number" name="nilai_uts" class="form-control" value="{{ $nilai->nilai_uts }}"
                                    step="0.01" min="0" max="100">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nilai UAS (35%)</label>
                                <input type="number" name="nilai_uas" class="form-control" value="{{ $nilai->nilai_uas }}"
                                    step="0.01" min="0" max="100">
                            </div>
                        </div>

                        <div class="row g-9 mb-8">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nilai Praktikum (10%)</label>
                                <input type="number" name="nilai_praktikum" class="form-control"
                                    value="{{ $nilai->nilai_praktikum }}" step="0.01">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nilai Kehadiran (5%)</label>
                                <input type="number" name="nilai_kehadiran" class="form-control"
                                    value="{{ $nilai->nilai_kehadiran }}" step="0.01">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Absensi (Format: 12/14)</label>
                                <input type="text" name="jumlah_kehadiran_input" class="form-control"
                                    placeholder="Contoh: 14/14"
                                    value="{{ $nilai->jumlah_kehadiran }}/{{ $nilai->jumlah_pertemuan }}">
                            </div>
                        </div>

                        <div class="fv-row mb-8">
                            <label class="form-label fw-bold">Catatan Dosen</label>
                            <textarea name="catatan_dosen" class="form-control" rows="3">{{ $nilai->catatan_dosen }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update Nilai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
