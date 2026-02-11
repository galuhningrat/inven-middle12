<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\Matkul;
use App\Models\Prodi;
use App\Models\Rombel;
use App\Models\Ruangan;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function __construct()
    {
        // Admin Akademik: FULL ACCESS
        // Kaprodi: READ ONLY
        $this->middleware('permission:jadwal,create')->only(['store']);
        $this->middleware('permission:jadwal,update')->only(['update']);
        $this->middleware('permission:jadwal,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Jadwal::with(['matkul', 'dosen.user', 'rombel', 'ruangan', 'tahunAkademik', 'prodi']);

        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }
        if ($request->filled('rombel')) {
            $query->where('id_rombel', $request->rombel);
        }

        $jadwal = $query->get();

        $matkul = Matkul::all();
        $dosen = Dosen::with('user')->get();
        $rombel = Rombel::all();
        $ruangan = Ruangan::all();
        $tahun_akademik = TahunAkademik::all();
        $prodi = Prodi::all();

        return view('jadwal.index', compact('jadwal', 'matkul', 'dosen', 'rombel', 'ruangan', 'tahun_akademik', 'prodi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_matkul' => 'required|exists:matkul,id',
            'id_dosen' => 'required|exists:dosen,id',
            'id_rombel' => 'required|exists:rombel,id',
            'id_ruangan' => 'required|exists:ruangan,id',
            'id_prodi' => 'required|exists:prodi,id',
            'tahun_akademik' => 'required|exists:tahun_akademik,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        Jadwal::create([
            'id_matkul' => $request->id_matkul,
            'id_dosen' => $request->id_dosen,
            'id_rombel' => $request->id_rombel,
            'id_ruangan' => $request->id_ruangan,
            'id_prodi' => $request->id_prodi,
            'tahun_akademik' => $request->tahun_akademik,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('jadwal.index')->with(['alert_type' => 'primary', 'message' => 'Data jadwal kuliah berhasil ditambahkan.']);
    }

    public function show($id)
    {
        $jadwal = Jadwal::with(['matkul', 'dosen.user', 'rombel', 'ruangan', 'tahunAkademik', 'prodi'])->findOrFail($id);
        return view('jadwal.detail-jadwal', compact('jadwal'));
    }
    public function update(Request $request, $id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);

            $validatedData = $request->validate([
                'id_matkul' => 'required|exists:matkul,id',
                'id_dosen' => 'required|exists:dosen,id',
                'id_rombel' => 'required|exists:rombel,id',
                'tahun_akademik' => 'required|exists:tahun_akademik,id',
                'id_ruangan' => 'required|exists:ruangan,id',
                'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
                'jam_mulai' => 'required',
                'jam_selesai' => 'required',
            ]);

            $jadwal->update($validatedData);

            return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate jadwal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with(['alert_type' => 'danger', 'message' => 'Data jadwal kuliah berhasil dihapus!']);
    }
}
