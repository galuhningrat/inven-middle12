<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Fakultas;
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
        $this->middleware('permission:kurikulum,read')->only(['index', 'show', 'allData', 'editData']);
        $this->middleware('permission:kurikulum,create')->only(['store']);
        $this->middleware('permission:kurikulum,update')->only(['update']);
        $this->middleware('permission:kurikulum,delete')->only(['destroy']);
    }

    // ============================================================
    // INDEX — Hierarki: Fakultas > Prodi > Rombel > Semester > MK
    // ============================================================
    public function index(Request $request)
    {
        $fakultas = Fakultas::with([
            'prodi' => function ($q) use ($request) {
                $q->with([
                    'matkulMappings' => function ($mq) use ($request) {
                        $mq->with([
                            'matkul' => function ($mkq) {
                                $mkq->with(['dosen.user', 'prodiMappings.prodi']);
                            },
                        ]);

                        if ($request->filled('filter_semester')) {
                            $mq->where('semester', (int) $request->filter_semester);
                        }
                        if ($request->filled('search')) {
                            $search = $request->search;
                            $mq->whereHas('matkul', function ($sq) use ($search) {
                                $sq->where('kode_mk', 'LIKE', "%{$search}%")
                                    ->orWhere('nama_mk', 'LIKE', "%{$search}%");
                            });
                        }
                    },
                ]);

                if ($request->filled('filter_prodi')) {
                    $q->where('id', (int) $request->filter_prodi);
                }
            },
        ])->get();

        $statsByProdi = [];
        foreach ($fakultas as $fak) {
            foreach ($fak->prodi as $p) {
                $statsByProdi[$p->id] = [
                    'total'     => $p->matkulMappings->count(),
                    'total_sks' => $p->matkulMappings->sum(fn($mp) => $mp->matkul?->bobot ?? 0),
                ];
            }
        }

        $totalUniqueMatkul = Matkul::count();
        $allProdi          = Prodi::orderBy('kode_prodi')->get();
        $dosen             = Dosen::with('user')->get();

        return view('matakuliah.index', compact(
            'fakultas',
            'statsByProdi',
            'totalUniqueMatkul',
            'allProdi',
            'dosen'
        ));
    }

    // ============================================================
    // ALL DATA — Master List (dengan AJAX support)
    // ============================================================
    public function allData(Request $request)
    {
        // DataTables bekerja client-side → load semua data sekaligus
        // Server tidak perlu filter/search — DataTables yang handle
        $matakuliah  = Matkul::with(['prodiMappings.prodi', 'dosen.user'])
            ->orderBy('kode_mk')
            ->get();

        $prodi       = Prodi::orderBy('kode_prodi')->get();
        $dosen       = Dosen::with('user')->get();
        $totalOrphan = Matkul::withoutMapping()->count();

        return view('matakuliah.all-data', compact('matakuliah', 'prodi', 'dosen', 'totalOrphan'));
    }

    // ============================================================
    // EDIT DATA — JSON untuk populate modal edit (AJAX)
    // ============================================================
    public function editData($id)
    {
        $mk = Matkul::with(['prodiMappings'])->findOrFail($id);

        return response()->json([
            'id'       => $mk->id,
            'kode_mk'  => $mk->kode_mk,
            'nama_mk'  => $mk->nama_mk,
            'bobot'    => $mk->bobot,
            'jenis'    => $mk->jenis,
            'id_dosen' => $mk->id_dosen,
            'mappings' => $mk->prodiMappings->map(fn($mp) => [
                'prodi_id' => $mp->id_prodi,
                'semester' => $mp->semester,
                'angkatan' => $mp->angkatan,
            ]),
        ]);
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

        $pairs = array_map(fn($m) => $m['prodi_id'] . '_' . $m['semester'], $validated['mappings']);
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

            if ($request->ajax() || $request->wantsJson() || $request->get('ajax') === '1') {
                return response()->json([
                    'success' => true,
                    'message' => "Mata kuliah '{$matkul->nama_mk}' berhasil disimpan!",
                ]);
            }

            return redirect()->back()
                ->with('success', "Mata kuliah '{$matkul->nama_mk}' berhasil disimpan!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating matkul', ['error' => $e->getMessage()]);

            if ($request->ajax() || $request->wantsJson() || $request->get('ajax') === '1') {
                return response()->json(['errors' => ['server' => [$e->getMessage()]]], 500);
            }

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
    // UPDATE — Support AJAX
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

        $pairs = array_map(fn($m) => $m['prodi_id'] . '_' . $m['semester'], $validated['mappings']);
        if (count($pairs) !== count(array_unique($pairs))) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => ['mappings' => ['Terdapat duplikat kombinasi Prodi & Semester.']]], 422);
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

            $matakuliah->load(['dosen.user', 'prodiMappings']);

            if ($request->ajax() || $request->wantsJson()) {
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
                ->with('success', "Mata kuliah '{$matakuliah->nama_mk}' berhasil diupdate!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating matkul', ['id' => $id, 'error' => $e->getMessage()]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => ['server' => [$e->getMessage()]]], 500);
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
            $matakuliah->delete();

            DB::commit();
            Log::info('Matkul deleted', ['id' => $id, 'user_id' => Auth::id()]);

            return redirect()->back()
                ->with('success', "Mata kuliah '{$nama}' berhasil dihapus!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting matkul', ['id' => $id, 'error' => $e->getMessage()]);

            return redirect()->back()
                ->with('error', 'Gagal menghapus mata kuliah: ' . $e->getMessage());
        }
    }
}
