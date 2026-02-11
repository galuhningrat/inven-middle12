<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\Rombel;
use Illuminate\Http\Request;

class KrsController extends Controller
{
    public function index()
    {
        $krs = Krs::with(['mahasiswa', 'rombel', 'jadwal'])->get();
        $rombels = Rombel::all(['id', 'nama_rombel']); // Untuk modal pilih rombel

        return view('krs.index', compact('krs', 'rombels'));
    }

    public function redirectToForm(Request $request)
    {
        $request->validate([
            'id_rombel' => 'required|exists:rombel,id',
        ]);

        return redirect()->route('krs.create', ['id_rombel' => $request->id_rombel]);
    }

    public function create($id_rombel)
    {
        // Ambil detail rombel beserta prodi
        $rombel = Rombel::with('prodi')->findOrFail($id_rombel);
        // Ambil jadwal untuk rombel ini
        $jadwal = Jadwal::where('id_rombel', $id_rombel)->get();
        // Ambil daftar mahasiswa untuk rombel ini
        $mahasiswa = Mahasiswa::where('id_rombel', $id_rombel)->get();

        return view('krs.create-krs', compact('rombel', 'jadwal', 'mahasiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id',
            'semester'     => 'required|string|max:2',
            'id_rombel'    => 'required|exists:rombel,id',
            'id_jadwal'    => 'required|exists:jadwal,id',
            'status'       => 'required|in:draft,disetujui,ditolak',
            'status_kunci' => 'required|in:0,1',
        ]);

        Krs::create($request->all());

        return redirect()->route('krs.index')
            ->with(['alert_type' => 'primary', 'message' => 'Data KRS berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $krs = Krs::with(['mahasiswa', 'rombel', 'jadwal'])->findOrFail($id);
        $mahasiswa = Mahasiswa::where('id_rombel', $krs->id_rombel)->get();
        $jadwal = Jadwal::where('id_rombel', $krs->id_rombel)->get();

        return view('krs.edit-krs', compact('krs', 'mahasiswa', 'jadwal'));
    }

    public function update(Request $request, $id)
    {
        $krs = Krs::findOrFail($id);

        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id',
            'semester'     => 'required|string|max:2',
            'id_rombel'    => 'required|exists:rombel,id',
            'id_jadwal'    => 'required|exists:jadwal,id',
            'status'       => 'required|in:draft,disetujui,ditolak',
            'status_kunci' => 'required|in:0,1',
        ]);

        $krs->update($request->all());

        return redirect()->route('krs.index')
            ->with(['alert_type' => 'success', 'message' => 'Data KRS berhasil diupdate.']);
    }

    public function destroy($id)
    {
        $krs = Krs::findOrFail($id);
        $krs->delete();

        return redirect()->route('krs.index')
            ->with(['alert_type' => 'danger', 'message' => 'Data KRS berhasil dihapus!']);
    }
}
