<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Matkul;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatkulController extends Controller
{
    public function __construct()
    {
        // Admin Akademik: READ ONLY
        $this->middleware('permission:kurikulum,read')->only(['index', 'show']);

        // Kaprodi: FULL ACCESS
        $this->middleware('permission:kurikulum,create')->only(['store']);
        $this->middleware('permission:kurikulum,update')->only(['update']);
        $this->middleware('permission:kurikulum,delete')->only(['destroy']);
    }

    /**
     * Tampilkan mata kuliah dikelompokkan per Prodi → per Semester.
     */
    public function index(Request $request)
    {
        // Base query dengan eager loading
        $query = Matkul::with(['prodi', 'dosen.user']);

        // ✅ FITUR SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_mk', 'LIKE', "%{$search}%")
                  ->orWhere('nama_mk', 'LIKE', "%{$search}%");
            });
        }

        // Filter by Prodi (jika ada)
        if ($request->filled('filter_prodi')) {
            $query->where('id_prodi', $request->filter_prodi);
        }

        // Filter by Semester (jika ada)
        if ($request->filled('filter_semester')) {
            $query->where('semester', $request->filter_semester);
        }

        $matakuliah = $query->orderBy('id_prodi')
                            ->orderBy('semester')
                            ->orderBy('kode_mk')
                            ->get();

        $prodi = Prodi::orderBy('kode_prodi')->get();
        $dosen = Dosen::with('user')->get();

        // Kelompokkan: prodi_id → semester → collection matkul
        $matkulByProdiSemester = [];

        foreach ($prodi as $p) {
            $matkulProdi = $matakuliah->where('id_prodi', $p->id);

            if ($matkulProdi->isEmpty()) {
                $matkulByProdiSemester[$p->id] = [];
                continue;
            }

            $grouped = $matkulProdi->groupBy('semester')->sortKeys();
            $matkulByProdiSemester[$p->id] = $grouped;
        }

        // Statistik per prodi
        $statsByProdi = [];
        foreach ($prodi as $p) {
            $statsByProdi[$p->id] = [
                'total'     => $matakuliah->where('id_prodi', $p->id)->count(),
                'total_sks' => $matakuliah->where('id_prodi', $p->id)->sum('bobot'),
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

    public function store(Request $request)
{
    // ===== DEBUG: Log semua input =====
    \Log::info('🔥 MATKUL STORE DIPANGGIL');
    \Log::info('Input:', $request->all());

    // ===== CEK VALIDASI =====
    try {
        $validated = $request->validate([
            'kode_mk'   => 'required|max:15',
            'nama_mk'   => 'required|string|max:100',
            'bobot'     => 'required|integer|min:1|max:9',
            'jenis'     => 'required|in:wajib,pilihan,umum',
            'id_prodi'  => 'required|exists:prodi,id',
            'id_dosen'  => 'required|exists:dosen,id',
            'semester'  => 'required|integer|min:1',
        ]);

        \Log::info('✅ Validasi OK:', $validated);

    } catch (\Exception $e) {
        \Log::error('❌ Validasi GAGAL:', [
            'error' => $e->getMessage()
        ]);

        return redirect()->back()
            ->withInput()
            ->with('error', 'Validasi gagal: ' . $e->getMessage());
    }

    // ===== COBA INSERT =====
    try {
        \Log::info('🔄 Mencoba insert...');

        $matkul = Matkul::create($validated);

        \Log::info('✅ INSERT BERHASIL! ID: ' . $matkul->id);

        return redirect()->route('matakuliah.index')
            ->with('success', 'Data berhasil disimpan!');

    } catch (\Exception $e) {
        \Log::error('❌ INSERT GAGAL:', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);

        return redirect()->back()
            ->withInput()
            ->with('error', 'Insert gagal: ' . $e->getMessage());
    }
}

    public function show($id)
    {
        $matakuliah = Matkul::with(['prodi', 'dosen.user'])->findOrFail($id);
        return view('matakuliah.detail-matkul', compact('matakuliah'));
    }

    public function update(Request $request, $id)
    {
        $matakuliah = Matkul::findOrFail($id);

        // ✅ VALIDASI UPDATE (ignore current ID untuk unique)
        $validated = $request->validate([
            'kode_mk'   => 'required|max:15|unique:matkul,kode_mk,' . $matakuliah->id,
            'nama_mk'   => 'required|string|max:100',
            'bobot'     => 'required|integer|min:1|max:9',
            'jenis'     => 'required|in:wajib,pilihan,umum',
            'id_prodi'  => 'required|exists:prodi,id',
            'id_dosen'  => 'required|exists:dosen,id',
            'semester'  => 'required|integer|min:1|max:14',
        ], [
            'kode_mk.unique' => 'Kode Mata Kuliah sudah digunakan oleh mata kuliah lain!',
        ]);

        DB::beginTransaction();
        try {
            $matakuliah->update([
                'kode_mk'  => strtoupper($validated['kode_mk']),
                'nama_mk'  => $validated['nama_mk'],
                'bobot'    => $validated['bobot'],
                'jenis'    => $validated['jenis'],
                'id_prodi' => $validated['id_prodi'],
                'id_dosen' => $validated['id_dosen'],
                'semester' => $validated['semester'],
            ]);

            DB::commit();

            Log::info('Matakuliah updated', [
                'id' => $matakuliah->id,
                'kode_mk' => $validated['kode_mk'],
                'user_id' => auth()->id()
            ]);

            return redirect()->route('matakuliah.index')
                ->with([
                    'alert_type' => 'success',
                    'message' => "Mata kuliah '{$validated['nama_mk']}' berhasil diupdate!"
                ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error updating matakuliah', [
                'id' => $matakuliah->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with([
                    'alert_type' => 'danger',
                    'message' => 'Gagal mengupdate mata kuliah: ' . $e->getMessage()
                ]);
        }
    }

    public function destroy($id)
    {
        $matakuliah = Matkul::findOrFail($id);

        DB::beginTransaction();
        try {
            // ✅ CEK DEPENDENSI (jika ada jadwal menggunakan matkul ini)
            $hasJadwal = \App\Models\Jadwal::where('id_matkul', $id)->exists();
            if ($hasJadwal) {
                return redirect()->back()->with([
                    'alert_type' => 'warning',
                    'message' => 'Tidak dapat menghapus mata kuliah yang sudah digunakan di jadwal!'
                ]);
            }

            $nama_mk = $matakuliah->nama_mk;
            $matakuliah->delete();

            DB::commit();

            Log::info('Matakuliah deleted', [
                'id' => $id,
                'nama_mk' => $nama_mk,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('matakuliah.index')
                ->with([
                    'alert_type' => 'danger',
                    'message' => "Mata kuliah '{$nama_mk}' berhasil dihapus!"
                ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error deleting matakuliah', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with([
                'alert_type' => 'danger',
                'message' => 'Gagal menghapus mata kuliah: ' . $e->getMessage()
            ]);
        }
    }
}
