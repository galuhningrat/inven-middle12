<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Matkul;
use App\Models\Prodi;
use Illuminate\Http\Request;

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
    public function index()
    {
        $matakuliah = Matkul::with(['prodi', 'dosen.user'])
                            ->orderBy('id_prodi')
                            ->orderBy('semester')
                            ->orderBy('kode_mk')
                            ->get();

        $prodi = Prodi::orderBy('kode_prodi')->get();
        $dosen = Dosen::with('user')->get();

        // Kelompokkan: prodi_id → semester → collection matkul
        // Struktur: [ prodi_id => [ semester => [matkul, ...] ] ]
        $matkulByProdiSemester = [];

        foreach ($prodi as $p) {
            $matkulProdi = $matakuliah->where('id_prodi', $p->id);

            if ($matkulProdi->isEmpty()) {
                // Tetap masukkan prodi tanpa matkul agar tab tetap muncul
                $matkulByProdiSemester[$p->id] = [];
                continue;
            }

            // Kelompokkan per semester, urutkan kunci ascending
            $grouped = $matkulProdi->groupBy('semester')->sortKeys();
            $matkulByProdiSemester[$p->id] = $grouped;
        }

        // Statistik per prodi untuk ditampilkan di badge tab
        $statsByProdi = [];
        foreach ($prodi as $p) {
            $statsByProdi[$p->id] = [
                'total'    => $matakuliah->where('id_prodi', $p->id)->count(),
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
        $request->validate([
            'kode_mk'   => 'required|unique:matkul,kode_mk|max:15',
            'nama_mk'   => 'required|string|max:100',
            'bobot'     => 'required|integer|min:1|max:9',
            'jenis'     => 'required|in:wajib,pilihan,umum',
            'id_prodi'  => 'required|exists:prodi,id',
            'id_dosen'  => 'required|exists:dosen,id',
            'semester'  => 'required|integer|min:1|max:14',
        ]);

        Matkul::create([
            'kode_mk'  => $request->kode_mk,
            'nama_mk'  => $request->nama_mk,
            'bobot'    => $request->bobot,
            'jenis'    => $request->jenis,
            'id_prodi' => $request->id_prodi,
            'id_dosen' => $request->id_dosen,
            'semester' => $request->semester,
        ]);

        return redirect()->route('matakuliah.index')
            ->with(['alert_type' => 'primary', 'message' => 'Mata kuliah berhasil ditambahkan.']);
    }

    public function show($id)
    {
        $matakuliah = Matkul::with(['prodi', 'dosen.user'])->findOrFail($id);
        return view('matakuliah.detail-matkul', compact('matakuliah'));
    }

    public function update(Request $request, $id)
    {
        $matakuliah = Matkul::findOrFail($id);

        $request->validate([
            'kode_mk'   => 'required|max:15|unique:matkul,kode_mk,' . $matakuliah->id,
            'nama_mk'   => 'required|string|max:100',
            'bobot'     => 'required|integer|min:1|max:9',
            'jenis'     => 'required|in:wajib,pilihan,umum',
            'id_prodi'  => 'required|exists:prodi,id',
            'id_dosen'  => 'required|exists:dosen,id',
            'semester'  => 'required|integer|min:1|max:14',
        ]);

        $matakuliah->update([
            'kode_mk'  => $request->kode_mk,
            'nama_mk'  => $request->nama_mk,
            'bobot'    => $request->bobot,
            'jenis'    => $request->jenis,
            'id_prodi' => $request->id_prodi,
            'id_dosen' => $request->id_dosen,
            'semester' => $request->semester,
        ]);

        return redirect()->route('matakuliah.index')
            ->with(['alert_type' => 'success', 'message' => 'Data mata kuliah berhasil diupdate.']);
    }

    public function destroy($id)
    {
        $matakuliah = Matkul::findOrFail($id);
        $matakuliah->delete();

        return redirect()->route('matakuliah.index')
            ->with(['alert_type' => 'danger', 'message' => 'Data mata kuliah berhasil dihapus!']);
    }
}
