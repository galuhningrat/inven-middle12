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
        $rombels = Rombel::withCount('mahasiswa')->with(['tahunMasuk', 'prodi', 'dosen.user'])->get();
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

        $rombelTahunMasukId = $rombel->getRawOriginal('tahun_masuk');

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
        $rombel = Rombel::with(['prodi', 'tahunMasuk', 'dosen.user'])
            ->withCount('mahasiswa')
            ->findOrFail($id);

        $mahasiswa = Mahasiswa::with('user')
            ->where('id_rombel', $id)
            ->get();

        return view('data-master.detail-rombel', compact('rombel', 'mahasiswa'));
    }

    /*
    |--------------------------------------------------------------------------
    | BUG FIX #1 — method update()
    |--------------------------------------------------------------------------
    | MASALAH SEBELUMNYA:
    |   1. Validasi mewajibkan field 'kapasitas' → required|integer|min:1
    |      Padahal field 'kapasitas' TIDAK ADA di form edit rombel maupun
    |      di $fillable model Rombel, sehingga validasi SELALU GAGAL dan
    |      data tidak pernah tersimpan.
    |
    |   2. Field 'id_dosen' tidak ikut di-update, padahal form mengirimnya.
    |
    | PERBAIKAN:
    |   1. Hapus 'kapasitas' dari validasi dan dari array update.
    |   2. Tambahkan 'id_dosen' ke validasi dan array update.
    |   3. Tambah validasi 'id_dosen' → required|exists:dosen,id
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $rombel = Rombel::findOrFail($id);

        $request->validate([
            'kode_rombel' => 'required|max:6|unique:rombel,kode_rombel,' . $rombel->id,
            'nama_rombel' => 'required|string',
            // DIHAPUS: 'kapasitas' → field ini tidak ada di form & tidak di fillable
            'tahun_masuk' => 'required|exists:tahun_akademik,id',
            'id_prodi'    => 'required|exists:prodi,id',
            'id_dosen'    => 'required|exists:dosen,id', // DITAMBAH: ada di form tapi belum divalidasi
        ]);

        $rombel->update([
            'kode_rombel' => $request->kode_rombel,
            'nama_rombel' => $request->nama_rombel,
            // DIHAPUS: 'kapasitas' → tidak ada di $fillable model Rombel
            'tahun_masuk' => $request->tahun_masuk,
            'id_prodi'    => $request->id_prodi,
            'id_dosen'    => $request->id_dosen, // DITAMBAH: supaya perubahan DPA tersimpan
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
