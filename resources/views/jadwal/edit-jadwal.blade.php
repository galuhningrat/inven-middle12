{{-- edit-matkul.blade.php --}}
<div class="modal fade" id="modalEditMatkul{{ $m->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{-- ⬇ ACTION ke route update, METHOD harus POST karena HTML form tidak support PUT --}}
            <form action="{{ route('matakuliah.update', $m->id) }}" method="POST" id="formEditMatkul{{ $m->id }}">
                @csrf
                @method('PUT')       {{-- ← WAJIB ada --}}
                <div class="modal-body">
                    {{-- Field kode_mk --}}
                    <div class="mb-3">
                        <label class="form-label">Kode MK</label>
                        <input type="text" name="kode_mk" class="form-control" value="{{ $m->kode_mk }}" required>
                    </div>
                    {{-- Field nama_mk --}}
                    <div class="mb-3">
                        <label class="form-label">Nama Mata Kuliah</label>
                        <input type="text" name="nama_mk" class="form-control" value="{{ $m->nama_mk }}" required>
                    </div>
                    {{-- Field SKS --}}
                    <div class="mb-3">
                        <label class="form-label">SKS</label>
                        <input type="number" name="sks" class="form-control" value="{{ $m->sks }}" min="1" max="6" required>
                    </div>
                    {{-- Field Jenis — Select2, perlu dropdownParent --}}
                    <div class="mb-3">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-select select2-in-modal" required>
                            <option value="Teori"    {{ $m->jenis == 'Teori'     ? 'selected' : '' }}>Teori</option>
                            <option value="Praktikum"{{ $m->jenis == 'Praktikum' ? 'selected' : '' }}>Praktikum</option>
                        </select>
                    </div>
                    {{-- Field Dosen — Select2, perlu dropdownParent --}}
                    <div class="mb-3">
                        <label class="form-label">Dosen Pengampu</label>
                        <select name="id_dosen" class="form-select select2-in-modal">
                            <option value="">-- Pilih Dosen --</option>
                            @foreach($dosen as $d)
                                <option value="{{ $d->id }}"
                                    {{ optional($m->dosen)->id == $d->id ? 'selected' : '' }}>
                                    {{ $d->user->nama ?? $d->nip }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
