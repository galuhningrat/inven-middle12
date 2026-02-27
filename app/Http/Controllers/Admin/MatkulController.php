<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Matkul;
use App\Models\MatkulProdiSemester;
use App\Models\Prodi;
use App\Models\Rombel;
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
        // ── Filter opsional ───────────────────────────────────────────────
        $filterProdi    = $request->filled('filter_prodi')    ? (int) $request->filter_prodi    : null;
        $filterRombel   = $request->filled('filter_rombel')   ? (int) $request->filter_rombel   : null;
        $filterSemester = $request->filled('filter_semester') ? (int) $request->filter_semester : null;
        $search         = $request->filled('search')          ? trim($request->search)           : null;
        $isFiltered     = (bool) ($filterProdi || $filterRombel || $filterSemester || $search);

        // ── STEP 1: Load Fakultas → Prodi → Rombel (dengan filter) ───────
        //
        // FIX BUG 1: Saat filter_rombel aktif, gunakan whereHas agar hanya
        // Prodi yang MEMILIKI rombel tersebut yang masuk ke dalam collection.
        // Tanpa whereHas, Prodi lain tetap muncul dengan rombel kosong,
        // menghasilkan accordion header tanpa isi.
        //
        // FIX BUG 5 (PostgreSQL): Kualifikasi nama kolom 'prodi.id' dan
        // 'rombel.id' agar tidak ambiguous saat Eloquent men-generate subquery.
        // ─────────────────────────────────────────────────────────────────
        $fakultas = Fakultas::with([
            'prodi' => function ($prodiQuery) use ($filterProdi, $filterRombel) {
                if ($filterProdi) {
                    $prodiQuery->where('prodi.id', $filterProdi);
                }
                if ($filterRombel) {
                    // Hanya Prodi yang PUNYA rombel yang dimaksud
                    $prodiQuery->whereHas(
                        'rombel',
                        fn($q) => $q->where('rombel.id', $filterRombel)
                    );
                }
                $prodiQuery->orderBy('prodi.nama_prodi');

                $prodiQuery->with([
                    'rombel' => function ($rombelQuery) use ($filterRombel) {
                        if ($filterRombel) {
                            $rombelQuery->where('rombel.id', $filterRombel);
                        }
                        $rombelQuery->with('tahunMasuk')->orderBy('nama_rombel');
                    },
                ]);
            },
        ])->orderBy('nama_fakultas')->get();

        // Pangkas Fakultas tanpa Prodi setelah filter Prodi/Rombel
        $fakultas = $fakultas->filter(fn($f) => $f->prodi->isNotEmpty())->values();

        // ── STEP 2: Load MatkulProdiSemester yang relevan ─────────────────
        //
        // MK di kurikulum terikat ke (Prodi + Semester), BUKAN ke Rombel.
        // Semua Rombel dalam satu Prodi berbagi kurikulum MK yang sama.
        //
        // FIX BUG 4 (PostgreSQL): Gunakan ILIKE (bukan LIKE) untuk pencarian
        // case-insensitive. PostgreSQL membedakan huruf besar/kecil pada LIKE.
        //
        // FIX BUG 5 (PostgreSQL): Kualifikasi kolom 'semester' agar tidak
        // ambiguous saat whereHas men-generate subquery dengan join.
        // ─────────────────────────────────────────────────────────────────
        $prodiIds = $fakultas
            ->flatMap(fn($f) => $f->prodi->pluck('id'))
            ->unique()
            ->values()
            ->all();

        $pivotQuery = MatkulProdiSemester::with([
            'matkul' => fn($q) => $q->with(['dosen.user', 'prodiMappings.prodi']),
        ])->whereIn('matkul_prodi_semester.id_prodi', $prodiIds);

        if ($filterSemester) {
            // Kualifikasi tabel agar tidak ambiguous di PostgreSQL
            $pivotQuery->where('matkul_prodi_semester.semester', $filterSemester);
        }

        if ($search) {
            $pivotQuery->whereHas(
                'matkul',
                fn($q) => $q->where('kode_mk', 'ILIKE', "%{$search}%")
                    ->orWhere('nama_mk',  'ILIKE', "%{$search}%")
            );
        }

        // Bangun array: $mkByProdi[id_prodi][semester] = [ ...MatkulProdiSemester ]
        $mkByProdi = [];
        foreach ($pivotQuery->orderBy('matkul_prodi_semester.semester')->get() as $mp) {
            $mkByProdi[$mp->id_prodi][$mp->semester][] = $mp;
        }

        // ── STEP 3: FIX BUG 2 — Pangkas Prodi/Fakultas tanpa MK ──────────
        //
        // Filter semester atau search bisa menghasilkan $mkByProdi yang
        // tidak memuat semua Prodi dari $fakultas. Prodi tersebut akan
        // menampilkan accordion kosong di view — pangkas di sini.
        // ─────────────────────────────────────────────────────────────────
        if ($isFiltered) {
            foreach ($fakultas as $fak) {
                $fak->setRelation(
                    'prodi',
                    $fak->prodi->filter(fn($p) => isset($mkByProdi[$p->id]))->values()
                );
            }
            $fakultas = $fakultas->filter(fn($f) => $f->prodi->isNotEmpty())->values();
        }

        // ── STEP 4: Statistik ringkas per Prodi (untuk badge di pill) ──────
        $statsByProdi = [];
        foreach ($mkByProdi as $prodiId => $bySemester) {
            $total = $totalSks = 0;
            foreach ($bySemester as $semMappings) {
                foreach ($semMappings as $mp) {
                    $total++;
                    $totalSks += $mp->matkul?->bobot ?? 0;
                }
            }
            $statsByProdi[$prodiId] = [
                'total'     => $total,
                'total_sks' => $totalSks,
            ];
        }

        // ── STEP 5: FIX BUG 3 — Hitung stat cards SESUAI filter ──────────
        //
        // Sebelumnya stat cards selalu menampilkan count global (Matkul::count(),
        // $allProdi->count(), dll) — tidak pernah berubah saat filter aktif.
        // Sekarang kita hitung dari data yang sudah dipangkas.
        // ─────────────────────────────────────────────────────────────────

        // Unique MK dari hasil filter (deduplikasi karena 1 MK bisa di N prodi)
        $filteredMkIds = collect();
        foreach ($mkByProdi as $bySemester) {
            foreach ($bySemester as $semMappings) {
                foreach ($semMappings as $mp) {
                    if ($mp->matkul) {
                        $filteredMkIds->push($mp->matkul->id);
                    }
                }
            }
        }

        $statTotalMatkul   = $isFiltered
            ? $filteredMkIds->unique()->count()
            : Matkul::count();

        $statFakultasCount = $fakultas->count();

        $statProdiCount    = $fakultas->sum(fn($f) => $f->prodi->count());

        // Rombel: hitung dari prodi yang tampil (sudah di-filter di eager load)
        $statRombelCount   = $isFiltered
            ? $fakultas->sum(fn($f) => $f->prodi->sum(fn($p) => $p->rombel->count()))
            : Rombel::count();

        // ── STEP 6: Data pendukung (dropdown filter & modal — selalu full) ─
        //
        // $allProdi dan $allRombel TETAP full list agar dropdown filter
        // menampilkan semua pilihan. Stats cards menggunakan variabel stat* di atas.
        // $allRombel membawa id_prodi (via relasi prodi) untuk JS dynamic filter.
        // ─────────────────────────────────────────────────────────────────
        $allProdi  = Prodi::orderBy('nama_prodi')->get();
        $allRombel = Rombel::with(['prodi', 'tahunMasuk'])->orderBy('nama_rombel')->get();
        $dosen     = Dosen::with('user')->get();

        return view('matakuliah.index', compact(
            'fakultas',
            'mkByProdi',
            'statsByProdi',
            // Stat cards (filter-aware)
            'statTotalMatkul',
            'statFakultasCount',
            'statProdiCount',
            'statRombelCount',
            // Dropdown data (selalu full list)
            'allProdi',
            'allRombel',
            'dosen',
            // Nilai filter aktif (untuk pre-select dropdown & badge)
            'filterProdi',
            'filterRombel',
            'filterSemester',
            'search',
            'isFiltered',
        ));
    }

    public function allData(Request $request)
    {
        $matakuliah = Matkul::with(['dosen.user', 'prodiMappings.prodi'])
            ->orderBy('kode_mk')
            ->get();

        $prodi       = Prodi::orderBy('nama_prodi')->get();
        $dosen       = Dosen::with('user')->get();
        $totalOrphan = Matkul::doesntHave('prodiMappings')->count();

        return view('matakuliah.all-data', compact(
            'matakuliah',
            'prodi',
            'dosen',
            'totalOrphan'
        ));
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
            return redirect()->back()->with('success', "Mata kuliah '{$matkul->nama_mk}' berhasil disimpan!");
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
                    'data'    => [
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
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus mata kuliah yang sudah digunakan di jadwal!');
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
