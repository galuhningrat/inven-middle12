<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Matkul;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MatkulController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:kurikulum,read')->only(['index', 'show']);
        $this->middleware('permission:kurikulum,create')->only(['store']);
        $this->middleware('permission:kurikulum,update')->only(['update']);
        $this->middleware('permission:kurikulum,delete')->only(['destroy']);
    }

    // ============================================================
    // INDEX
    // ============================================================
    public function index(Request $request)
    {
        $query = Matkul::with(['prodis', 'dosen.user']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_mk', 'LIKE', "%{$search}%")
                  ->orWhere('nama_mk', 'LIKE', "%{$search}%");
            });
        }

        // Filter by Prodi → ambil MK spesifik prodi + semua MK Umum
        if ($request->filled('filter_prodi')) {
            $query->forProdi((int) $request->filter_prodi);
        }

        // Filter by Semester
        if ($request->filled('filter_semester')) {
            $query->where('semester', $request->filter_semester);
        }

        $matakuliah = $query->orderBy('semester')->orderBy('kode_mk')->get();
        $prodi      = Prodi::orderBy('kode_prodi')->get();
        $dosen      = Dosen::with('user')->get();

        // ============================================================
        // Kelompokkan: prodi_id → semester → collection matkul
        // MK Umum (jenis='umum') masuk ke SEMUA prodi
        // ============================================================
        $mkUmum = $matakuliah->where('jenis', 'umum');

        $matkulByProdiSemester = [];
        $statsByProdi          = [];

        foreach ($prodi as $p) {
            // MK spesifik prodi ini
            $mkSpesifik = $matakuliah->filter(function ($mk) use ($p) {
                return $mk->jenis !== 'umum'
                    && $mk->prodis->contains('id', $p->id);
            });

            // Gabung dengan MK Umum
            $mkGabungan = $mkSpesifik->merge($mkUmum)
                ->sortBy('semester')
                ->sortBy('kode_mk');

            $matkulByProdiSemester[$p->id] = $mkGabungan->groupBy('semester')->sortKeys();

            $statsByProdi[$p->id] = [
                'total'     => $mkGabungan->count(),
                'total_sks' => $mkGabungan->sum('bobot'),
            ];
        }

        return view('matakuliah.index', compact(
            'matakuliah',
            'prodi',
            'dosen',
            'matkulByProdiSemester',
            'statsByProdi'
        ));
    }

    // ============================================================
    // STORE
    // ============================================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mk'   => 'required|max:15|unique:matkul,kode_mk',
            'nama_mk'   => 'required|string|max:100',
            'bobot'     => 'required|integer|min:1|max:9',
            'jenis'     => 'required|in:wajib,pilihan,umum',
            'id_dosen'  => 'required|exists:dosen,id',
            'semester'  => 'required|integer|min:1|max:14',
            // Jika umum: id_prodi[] bisa kosong (berlaku semua prodi)
            // Jika wajib/pilihan: wajib pilih minimal 1 prodi
            'id_prodi'  => 'nullable|array',
            'id_prodi.*'=> 'exists:prodi,id',
        ], [
            'id_prodi.required_unless' => 'Pilih minimal 1 Program Studi untuk MK Wajib/Pilihan.',
        ]);

        // MK Wajib/Pilihan wajib punya prodi
        if ($validated['jenis'] !== 'umum' && empty($validated['id_prodi'])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['id_prodi' => 'Pilih minimal 1 Program Studi untuk MK Wajib/Pilihan.']);
        }

        DB::beginTransaction();
        try {
            // Simpan matkul (tanpa id_prodi)
            $matkul = Matkul::create([
                'kode_mk'  => strtoupper($validated['kode_mk']),
                'nama_mk'  => $validated['nama_mk'],
                'bobot'    => $validated['bobot'],
                'jenis'    => $validated['jenis'],
                'id_dosen' => $validated['id_dosen'],
                'semester' => $validated['semester'],
            ]);

            // Simpan ke pivot
            // MK Umum: tidak perlu sync (kosong = berlaku semua prodi via scope)
            // MK Wajib/Pilihan: sync ke prodi yang dipilih
            if ($validated['jenis'] !== 'umum' && !empty($validated['id_prodi'])) {
                $matkul->prodis()->sync($validated['id_prodi']);
            }

            DB::commit();

            Log::info('Matkul created', ['id' => $matkul->id, 'user_id' => Auth::id()]);

            return redirect()->route('matakuliah.index')
                ->with('success', "Mata kuliah '{$matkul->nama_mk}' berhasil disimpan!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating matkul', ['error' => $e->getMessage()]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan mata kuliah: ' . $e->getMessage());
        }
    }

    // ============================================================
    // SHOW
    // ============================================================
    public function show($id)
    {
        $matakuliah = Matkul::with(['prodis', 'dosen.user'])->findOrFail($id);
        return view('matakuliah.detail-matkul', compact('matakuliah'));
    }

    // ============================================================
    // UPDATE
    // ============================================================
    public function update(Request $request, $id)
    {
        $matakuliah = Matkul::findOrFail($id);

        $validated = $request->validate([
            'kode_mk'   => 'required|max:15|unique:matkul,kode_mk,' . $matakuliah->id,
            'nama_mk'   => 'required|string|max:100',
            'bobot'     => 'required|integer|min:1|max:9',
            'jenis'     => 'required|in:wajib,pilihan,umum',
            'id_dosen'  => 'required|exists:dosen,id',
            'semester'  => 'required|integer|min:1|max:14',
            'id_prodi'  => 'nullable|array',
            'id_prodi.*'=> 'exists:prodi,id',
        ], [
            'kode_mk.unique' => 'Kode Mata Kuliah sudah digunakan oleh mata kuliah lain!',
        ]);

        if ($validated['jenis'] !== 'umum' && empty($validated['id_prodi'])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['id_prodi' => 'Pilih minimal 1 Program Studi untuk MK Wajib/Pilihan.']);
        }

        DB::beginTransaction();
        try {
            $matakuliah->update([
                'kode_mk'  => strtoupper($validated['kode_mk']),
                'nama_mk'  => $validated['nama_mk'],
                'bobot'    => $validated['bobot'],
                'jenis'    => $validated['jenis'],
                'id_dosen' => $validated['id_dosen'],
                'semester' => $validated['semester'],
            ]);

            // Sync pivot:
            // MK Umum → kosongkan pivot (tidak perlu prodi spesifik)
            // MK Wajib/Pilihan → sync prodi yang dipilih
            if ($validated['jenis'] === 'umum') {
                $matakuliah->prodis()->detach();
            } else {
                $matakuliah->prodis()->sync($validated['id_prodi'] ?? []);
            }

            DB::commit();

            Log::info('Matkul updated', ['id' => $matakuliah->id, 'user_id' => Auth::id()]);

            return redirect()->route('matakuliah.index')
                ->with('success', "Mata kuliah '{$matakuliah->nama_mk}' berhasil diupdate!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating matkul', ['id' => $id, 'error' => $e->getMessage()]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate mata kuliah: ' . $e->getMessage());
        }
    }

    // ============================================================
    // DESTROY
    // ============================================================
    public function destroy($id)
    {
        $matakuliah = Matkul::findOrFail($id);

        DB::beginTransaction();
        try {
            $hasJadwal = \App\Models\Jadwal::where('id_matkul', $id)->exists();
            if ($hasJadwal) {
                return redirect()->back()->with(
                    'error',
                    'Tidak dapat menghapus mata kuliah yang sudah digunakan di jadwal!'
                );
            }

            $nama = $matakuliah->nama_mk;

            // Detach pivot dulu sebelum delete (opsional, cascade sudah handle ini)
            $matakuliah->prodis()->detach();
            $matakuliah->delete();

            DB::commit();

            Log::info('Matkul deleted', ['id' => $id, 'user_id' => Auth::id()]);

            return redirect()->route('matakuliah.index')
                ->with('success', "Mata kuliah '{$nama}' berhasil dihapus!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting matkul', ['id' => $id, 'error' => $e->getMessage()]);

            return redirect()->back()
                ->with('error', 'Gagal menghapus mata kuliah: ' . $e->getMessage());
        }
    }
}
