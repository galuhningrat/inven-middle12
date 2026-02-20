<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Matkul;
use App\Models\MatkulProdiSemester;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MatkulController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:kurikulum,read')->only(['index', 'show', 'allData']);
        $this->middleware('permission:kurikulum,create')->only(['store']);
        $this->middleware('permission:kurikulum,update')->only(['update']);
        $this->middleware('permission:kurikulum,delete')->only(['destroy']);
    }

    // ============================================================
    // INDEX — Tampilan Kurikulum per Prodi & Semester
    // ============================================================
    public function index(Request $request)
    {
        $prodi = Prodi::orderBy('kode_prodi')->get();
        $dosen = Dosen::with('user')->get();

        // Build query mapping dengan filter
        $mappingQuery = MatkulProdiSemester::with([
            'matkul' => fn($q) => $q->with(['dosen.user', 'prodiMappings.prodi']),
        ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $mappingQuery->whereHas('matkul', function ($q) use ($search) {
                $q->where('kode_mk', 'LIKE', "%{$search}%")
                    ->orWhere('nama_mk', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('filter_prodi')) {
            $mappingQuery->where('id_prodi', (int) $request->filter_prodi);
        }

        if ($request->filled('filter_semester')) {
            $mappingQuery->where('semester', (int) $request->filter_semester);
        }

        $allMappings = $mappingQuery->get();

        // Kelompokkan: prodi_id → semester → Collection<MatkulProdiSemester>
        $matkulByProdiSemester = [];
        $statsByProdi          = [];

        foreach ($prodi as $p) {
            $prodiMappings = $allMappings->where('id_prodi', $p->id);

            $matkulByProdiSemester[$p->id] = $prodiMappings
                ->groupBy('semester')
                ->sortKeys();

            $statsByProdi[$p->id] = [
                'total'     => $prodiMappings->count(),
                'total_sks' => $prodiMappings->sum(fn($mp) => $mp->matkul?->bobot ?? 0),
            ];
        }

        $totalUniqueMatkul = $allMappings->pluck('id_matkul')->unique()->count();

        return view('matakuliah.index', compact(
            'prodi',
            'dosen',
            'matkulByProdiSemester',
            'statsByProdi',
            'totalUniqueMatkul',
            'allMappings'
        ));
    }

    // ============================================================
    // ALL DATA — Master List semua MK tanpa filter prodi/semester
    // ============================================================
    public function allData(Request $request)
    {
        $query = Matkul::with(['prodiMappings.prodi', 'dosen.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_mk', 'LIKE', "%{$search}%")
                    ->orWhere('nama_mk', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('filter_jenis')) {
            $query->where('jenis', $request->filter_jenis);
        }

        // Filter: MK tanpa mapping (orphan)
        if ($request->filled('filter_orphan') && $request->filter_orphan === '1') {
            $query->withoutMapping();
        }

        $matakuliah = $query->orderBy('kode_mk')->paginate(20);
        $prodi      = Prodi::all();
        $dosen      = Dosen::with('user')->get();

        // Hitung MK orphan untuk notifikasi
        $totalOrphan = Matkul::withoutMapping()->count();

        return view('matakuliah.all-data', compact('matakuliah', 'prodi', 'dosen', 'totalOrphan'));
    }

    // ============================================================
    // STORE
    // ============================================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mk'              => 'required|max:15|unique:matkul,kode_mk',
            'nama_mk'              => 'required|string|max:100',
            'bobot'                => 'required|integer|min:1|max:9',
            'jenis'                => 'required|in:wajib,pilihan,umum',
            'id_dosen'             => 'required|exists:dosen,id',

            // Mappings: wajib minimal 1 untuk semua jenis MK
            'mappings'             => 'required|array|min:1',
            'mappings.*.prodi_id'  => 'required|exists:prodi,id',
            'mappings.*.semester'  => 'required|integer|min:1|max:14',
            'mappings.*.angkatan'  => 'nullable|digits:4',
        ], [
            'mappings.required'            => 'Minimal satu mapping Prodi & Semester harus diisi.',
            'mappings.min'                 => 'Minimal satu mapping Prodi & Semester harus diisi.',
            'mappings.*.prodi_id.required' => 'Pilih Program Studi untuk setiap mapping.',
            'mappings.*.semester.required' => 'Pilih Semester untuk setiap mapping.',
        ]);

        // Validasi tidak ada duplikat (prodi + semester) dalam input yang sama
        $pairs = array_map(
            fn($m) => $m['prodi_id'] . '_' . $m['semester'],
            $validated['mappings']
        );
        if (count($pairs) !== count(array_unique($pairs))) {
            return redirect()->back()->withInput()
                ->withErrors(['mappings' => 'Terdapat duplikat kombinasi Prodi & Semester.']);
        }

        DB::beginTransaction();
        try {
            $matkul = Matkul::create([
                'kode_mk'  => strtoupper($validated['kode_mk']),
                'nama_mk'  => $validated['nama_mk'],
                'bobot'    => $validated['bobot'],
                'jenis'    => $validated['jenis'],
                'id_dosen' => $validated['id_dosen'],
            ]);

            foreach ($validated['mappings'] as $mapping) {
                MatkulProdiSemester::create([
                    'id_matkul' => $matkul->id,
                    'id_prodi'  => $mapping['prodi_id'],
                    'semester'  => $mapping['semester'],
                    'angkatan'  => $mapping['angkatan'] ?? null,
                ]);
            }

            DB::commit();
            Log::info('Matkul created', ['id' => $matkul->id, 'user_id' => Auth::id()]);

            return redirect()->back()
                ->with('success', "Mata kuliah '{$matkul->nama_mk}' berhasil disimpan!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating matkul', ['error' => $e->getMessage()]);

            return redirect()->back()->withInput()
                ->with('error', 'Gagal menyimpan mata kuliah: ' . $e->getMessage());
        }
    }

    // ============================================================
    // SHOW
    // ============================================================
    public function show($id)
    {
        $m = Matkul::with(['prodiMappings.prodi', 'dosen.user'])->findOrFail($id);
        return view('matakuliah.detail-matkul', compact('m'));
    }

    // ============================================================
    // UPDATE
    // ============================================================
    // ============================================================
    // UPDATE — support AJAX (dari modal) dan regular form submit
    // ============================================================
    public function update(Request $request, $id)
    {
        $matakuliah = Matkul::findOrFail($id);

        $validated = $request->validate([
            'kode_mk'              => 'required|max:15|unique:matkul,kode_mk,' . $matakuliah->id,
            'nama_mk'              => 'required|string|max:100',
            'bobot'                => 'required|integer|min:1|max:9',
            'jenis'                => 'required|in:wajib,pilihan,umum',
            'id_dosen'             => 'required|exists:dosen,id',
            'mappings'             => 'required|array|min:1',
            'mappings.*.prodi_id'  => 'required|exists:prodi,id',
            'mappings.*.semester'  => 'required|integer|min:1|max:14',
            'mappings.*.angkatan'  => 'nullable|digits:4',
        ], [
            'kode_mk.unique'               => 'Kode Mata Kuliah sudah digunakan oleh mata kuliah lain!',
            'mappings.required'            => 'Minimal satu mapping Prodi & Semester harus diisi.',
            'mappings.min'                 => 'Minimal satu mapping Prodi & Semester harus diisi.',
            'mappings.*.prodi_id.required' => 'Pilih Program Studi untuk setiap mapping.',
            'mappings.*.semester.required' => 'Pilih Semester untuk setiap mapping.',
        ]);

        // Validasi duplikat kombinasi
        $pairs = array_map(
            fn($m) => $m['prodi_id'] . '_' . $m['semester'],
            $validated['mappings']
        );
        if (count($pairs) !== count(array_unique($pairs))) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'errors' => ['mappings' => ['Terdapat duplikat kombinasi Prodi & Semester.']]
                ], 422);
            }
            return redirect()->back()->withInput()
                ->withErrors(['mappings' => 'Terdapat duplikat kombinasi Prodi & Semester.']);
        }

        DB::beginTransaction();
        try {
            $matakuliah->update([
                'kode_mk'  => strtoupper($validated['kode_mk']),
                'nama_mk'  => $validated['nama_mk'],
                'bobot'    => $validated['bobot'],
                'jenis'    => $validated['jenis'],
                'id_dosen' => $validated['id_dosen'],
            ]);

            // Hapus semua mapping lama, ganti dengan yang baru
            $matakuliah->prodiMappings()->delete();

            foreach ($validated['mappings'] as $mapping) {
                MatkulProdiSemester::create([
                    'id_matkul' => $matakuliah->id,
                    'id_prodi'  => $mapping['prodi_id'],
                    'semester'  => $mapping['semester'],
                    'angkatan'  => $mapping['angkatan'] ?? null,
                ]);
            }

            DB::commit();
            Log::info('Matkul updated', ['id' => $matakuliah->id, 'user_id' => Auth::id()]);

            if ($request->ajax() || $request->wantsJson()) {
                $matakuliah->load(['dosen.user', 'prodiMappings']);
                return response()->json([
                    'success' => true,
                    'message' => "Mata kuliah '{$matakuliah->nama_mk}' berhasil diupdate!",
                    'data' => [
                        'kode_mk'    => $matakuliah->kode_mk,
                        'nama_mk'    => $matakuliah->nama_mk,
                        'bobot'      => $matakuliah->bobot,
                        'jenis'      => $matakuliah->jenis,
                        'id_dosen'   => $matakuliah->id_dosen,
                        'dosen_nama' => $matakuliah->dosen->user->nama ?? '-',
                        'mappings'   => $matakuliah->prodiMappings->map(fn($mp) => [
                            'prodi_id' => $mp->id_prodi,
                            'semester' => $mp->semester,
                        ]),
                    ],
                ]);
            }

            return redirect()->back()
                ->with('success', "Mata kuliah '{$matakuliah->nama_mk}' berhasil disimpan!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating matkul', ['id' => $id, 'error' => $e->getMessage()]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'errors' => ['server' => [$e->getMessage()]]
                ], 500);
            }

            return redirect()->back()->withInput()
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

            // Cascade delete otomatis menghapus semua mapping di matkul_prodi_semester
            $matakuliah->delete();

            DB::commit();
            Log::info('Matkul deleted', ['id' => $id, 'user_id' => Auth::id()]);

            return redirect()->back()
                ->with('success', "Mata kuliah '{$matakuliah->nama_mk}' berhasil disimpan!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting matkul', ['id' => $id, 'error' => $e->getMessage()]);

            return redirect()->back()
                ->with('error', 'Gagal menghapus mata kuliah: ' . $e->getMessage());
        }
    }
}
