<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $data = Prodi::with(['fakultas', 'kaprodi'])->withCount('mahasiswa')->get();
        $fakultas = Fakultas::all();

        $kaprodis = User::where('id_role', 6)
            ->where('status', 'Aktif')
            ->get(['id', 'nama', 'email']);

        return view('data-master.prodi', compact('data', 'fakultas', 'kaprodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_prodi' => 'required|unique:prodi,kode_prodi',
            'nama_prodi' => 'required',
            'id_fakultas' => 'required|exists:fakultas,id',
            'id_kaprodi' => 'required|exists:users,id',
            'status_akre' => 'required|in:Unggul,Baik Sekali,Baik'
        ]);

        Prodi::create([
            'kode_prodi' => $request->kode_prodi,
            'nama_prodi' => $request->nama_prodi,
            'id_fakultas' => $request->id_fakultas,
            'id_kaprodi' => $request->id_kaprodi,
            'status_akre' => $request->status_akre
        ]);

        return redirect()->route('data-prodi.index')
            ->with(['alert_type' => 'primary', 'message' => 'Data berhasil disimpan.']);
    }

    public function show($id)
    {
        $prodi = Prodi::with(['fakultas', 'kaprodi', 'mahasiswa'])->findOrFail($id);
        return view('data-master.detail-prodi', compact('prodi'));
    }

    public function update(Request $request, $id)
    {
        $prodi = Prodi::findOrFail($id);

        $request->validate([
            'kode_prodi' => 'required|unique:prodi,kode_prodi,' . $prodi->id,
            'nama_prodi' => 'required',
            'id_fakultas' => 'required|exists:fakultas,id',
            'id_kaprodi' => 'required|exists:users,id', // ✅ VALIDASI KE USERS
            'status_akre' => 'required|in:Unggul,Baik Sekali,Baik'
        ]);

        $prodi->update([
            'kode_prodi' => $request->kode_prodi,
            'nama_prodi' => $request->nama_prodi,
            'id_fakultas' => $request->id_fakultas,
            'id_kaprodi' => $request->id_kaprodi,
            'status_akre' => $request->status_akre
        ]);

        return redirect()->route('data-prodi.index')
            ->with(['alert_type' => 'success', 'message' => 'Data berhasil diupdate.']);
    }

    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);

        // 1. Cek relasi ke Rombel (Ini yang menyebabkan error tadi)
        // Jika modelnya bukan \App\Models\Rombel, sesuaikan namespace-nya
        $hasRombel = \App\Models\Rombel::where('id_prodi', $id)->exists();

        // 2. Cek juga relasi ke Mahasiswa (jika ada)
        $hasMahasiswa = \App\Models\Mahasiswa::where('id_prodi', $id)->exists();

        // 3. Cek juga relasi ke Kurikulum/Matkul Mapping
        $hasMatkul = \App\Models\MatkulProdiSemester::where('id_prodi', $id)->exists();

        // Jika ada salah satu data terkait, batalkan penghapusan
        if ($hasRombel || $hasMahasiswa || $hasMatkul) {
            return redirect()->back()->with([
                'alert_type' => 'warning',
                'message' => 'Gagal menghapus! Prodi "' . $prodi->nama_prodi . '" masih digunakan pada data Rombel, Mahasiswa, atau Kurikulum.'
            ]);
        }

        try {
            $prodi->delete();
            return redirect()->route('data-prodi.index')
                ->with(['alert_type' => 'danger', 'message' => 'Data prodi berhasil dihapus.']);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'danger',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }
}
