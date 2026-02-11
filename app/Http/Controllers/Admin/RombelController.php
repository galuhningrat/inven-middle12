<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Rombel;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class RombelController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }
    public function index()
    {
        $rombels = Rombel::withCount('mahasiswa')->with(['tahunMasuk', 'prodi','dosen.user'])->get();
        $tahunAkademiks = TahunAkademik::all();
        $prodis = Prodi::all();
        $dosen = Dosen::with('user')->get();
        return view('data-master.rombel', compact('rombels', 'tahunAkademiks', 'prodis', 'dosen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_rombel' => 'required|max:6|unique:rombel,kode_rombel',
            'nama_rombel' => 'required|string',
            'tahun_masuk' => 'required|exists:tahun_akademik,id',
            'id_prodi'    => 'required|exists:prodi,id',
            'id_dosen'    => 'required|exists:dosen,id',
        ]);

        Rombel::create([
            'kode_rombel' => $request->kode_rombel,
            'nama_rombel' => $request->nama_rombel,
            'tahun_masuk' => $request->tahun_masuk,
            'id_prodi'    => $request->id_prodi,
            'id_dosen'    => $request->id_dosen,
        ]);

        return redirect()->route('rombel.index')->with('sukses', 'Data rombel berhasil ditambahkan.');
    }

    public function tambahMahasiswa($id)
    {
        $rombel = Rombel::with(['tahunMasuk', 'prodi'])->withCount('mahasiswa')->findOrFail($id);

        // Ambil ID tahun_masuk dari FK
        $rombelTahunMasukId = $rombel->getRawOriginal('tahun_masuk');

        // Ambil mahasiswa dengan filter prodi & tahun_masuk
        $mahasiswa = Mahasiswa::with(['user', 'prodi'])
            ->where(function ($q) {
                $q->whereNull('id_rombel')->orWhere('id_rombel', 0);
            })
            ->where('id_prodi', $rombel->id_prodi)
            ->where('tahun_masuk', $rombelTahunMasukId)
            ->get();

        $tahunAwal = $rombel->tahunMasuk?->tahun_awal;

        return view('data-master.tambahmhs-rombel', compact('rombel', 'mahasiswa', 'tahunAwal'));
    }



    public function storeMahasiswa(Request $request, $id)
    {
        $rombel = Rombel::findOrFail($id);

        $request->validate([
            'mahasiswa_ids'   => 'required|array',
            'mahasiswa_ids.*' => 'exists:mahasiswa,id',
        ], [
            'mahasiswa_ids.required' => 'Pilih minimal satu mahasiswa.',
        ]);

        Mahasiswa::whereIn('id', $request->mahasiswa_ids)
            ->update(['id_rombel' => $rombel->id]);

        return redirect()
            ->route('rombel.index')
            ->with('sukses', 'Mahasiswa berhasil dimasukkan ke rombel: ' . $rombel->nama_rombel);
    }

    public function detail($id)
    {
        // Ambil rombel beserta relasi prodi, tahunMasuk, dan dosen
        $rombel = Rombel::with(['prodi', 'tahunMasuk', 'dosen.user'])
            ->withCount('mahasiswa')
            ->findOrFail($id);

        // Ambil semua mahasiswa yang ada di rombel ini
        $mahasiswa = Mahasiswa::with('user')
            ->where('id_rombel', $id)
            ->get();

        return view('data-master.detail-rombel', compact('rombel', 'mahasiswa'));
    }

    public function update(Request $request, $id)
    {
        $rombel = Rombel::findOrFail($id);
        $request->validate([
            'kode_rombel' => 'required|max:6|unique:rombel,kode_rombel,'.$rombel->id,
            'nama_rombel' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
            'tahun_masuk' => 'required|exists:tahun_akademik,id',
            'id_prodi' => 'required|exists:prodi,id',
        ]);

        $rombel->update([
            'kode_rombel' => $request->kode_rombel,
            'nama_rombel' => $request->nama_rombel,
            'kapasitas' => $request->kapasitas,
            'tahun_masuk' => $request->tahun_masuk,
            'id_prodi' => $request->id_prodi,
        ]);

        return redirect()->route('rombel.index')->with('success', 'Data rombel berhasil diupdate.');
    }

    public function destroy($id)
    {
        $rombel = Rombel::findOrFail($id);
        $rombel->delete();
        return redirect()->route('rombel.index')->with('success', 'Data rombel berhasil dihapus.');
    }
}
