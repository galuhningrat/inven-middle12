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

    public function index()
    {
        $matakuliah = Matkul::with(['prodi', 'dosen.user'])->get();
        $prodi = Prodi::all();
        $dosen = Dosen::with('user')->get();

        return view('matakuliah.index', compact('matakuliah', 'prodi', 'dosen'));
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
        ]);

        Matkul::create([
            'kode_mk'  => $request->kode_mk,
            'nama_mk'  => $request->nama_mk,
            'bobot'    => $request->bobot,
            'jenis'    => $request->jenis,
            'id_prodi' => $request->id_prodi,
            'id_dosen' => $request->id_dosen,
        ]);

        return redirect()->route('matakuliah.index')->with(['alert_type' => 'primary', 'message' => 'Data mata kuliah berhasil ditambahkan.']);
    }

    public function show($id)
    {
        // Tidak perlu jika detail via modal, atau bisa pakai jika ingin ke halaman detail
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
        ]);

        $matakuliah->update([
            'kode_mk'  => $request->kode_mk,
            'nama_mk'  => $request->nama_mk,
            'bobot'    => $request->bobot,
            'jenis'    => $request->jenis,
            'id_prodi' => $request->id_prodi,
            'id_dosen' => $request->id_dosen,
        ]);

        return redirect()->route('matakuliah.index')->with(['alert_type' => 'success', 'message' => 'Data mata kuliah berhasil diupdate.']);
    }

    public function destroy($id)
    {
        $matakuliah = Matkul::findOrFail($id);
        $matakuliah->delete();

        return redirect()->route('matakuliah.index')->with(['alert_type' => 'danger', 'message' => 'Data mata kuliah berhasil dihapus!']);
    }
}
