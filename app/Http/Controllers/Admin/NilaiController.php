<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:nilai,read')->only(['index', 'show']);
        $this->middleware('permission:nilai,create')->only(['create', 'store', 'inputMassal']);
        $this->middleware('permission:nilai,update')->only(['edit', 'update', 'publish']);
        $this->middleware('permission:nilai,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Nilai::with(['mahasiswa.user', 'jadwal.matkul', 'jadwal.rombel', 'dosen.user']);

        if ($user->id_role == 7) {
            $dosenId = $user->dosen->id ?? null;
            if (!$dosenId) return back()->with('error', 'Data dosen tidak ditemukan');
            $query->where('id_dosen', $dosenId);
        }

        if ($request->filled('id_jadwal')) $query->where('id_jadwal', $request->id_jadwal);
        if ($request->filled('status')) $query->where('status', $request->status);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa.user', function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%");
            })->orWhereHas('mahasiswa', function ($q) use ($search) {
                $q->where('nim', 'LIKE', "%{$search}%");
            });
        }

        $nilai = $query->latest()->get();
        $jadwal = ($user->id_role == 7)
            ? Jadwal::where('id_dosen', $user->dosen->id ?? 0)->with(['matkul', 'rombel'])->get()
            : Jadwal::with(['matkul', 'rombel'])->get();

        return view('nilai.index', compact('nilai', 'jadwal'));
    }

    public function inputMassal(Request $request)
    {
        $jadwalId = $request->id_jadwal;
        if (!$jadwalId) return back()->with('error', 'Pilih mata kuliah terlebih dahulu');

        $jadwal = Jadwal::with(['matkul', 'rombel.mahasiswa.user', 'dosen.user'])->findOrFail($jadwalId);
        $mahasiswaList = $jadwal->rombel->mahasiswa;

        return view('nilai.input-massal', compact('jadwal', 'mahasiswaList'));
    }

    public function storeMassal(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id',
            'nilai' => 'required|array',
        ]);

        $user = Auth::user();
        $jadwal = Jadwal::findOrFail($request->id_jadwal);

        DB::beginTransaction();
        try {
            foreach ($request->nilai as $item) {
                $tugas = (float)($item['nilai_tugas'] ?? 0);
                $uts = (float)($item['nilai_uts'] ?? 0);
                $uas = (float)($item['nilai_uas'] ?? 0);
                $prak = (float)($item['nilai_praktikum'] ?? 0);
                $hadir = (float)($item['nilai_kehadiran'] ?? 0);

                // Hitung Nilai Akhir (Bobot: 20%, 30%, 35%, 10%, 5%)
                $nilaiAkhir = ($tugas * 0.20) + ($uts * 0.30) + ($uas * 0.35) + ($prak * 0.10) + ($hadir * 0.05);

                // Tentukan Grade
                if ($nilaiAkhir >= 85) {
                    $huruf = 'A';
                    $angka = 4.0;
                } elseif ($nilaiAkhir >= 80) {
                    $huruf = 'A-';
                    $angka = 3.7;
                } elseif ($nilaiAkhir >= 75) {
                    $huruf = 'B+';
                    $angka = 3.3;
                } elseif ($nilaiAkhir >= 70) {
                    $huruf = 'B';
                    $angka = 3.0;
                } elseif ($nilaiAkhir >= 65) {
                    $huruf = 'B-';
                    $angka = 2.7;
                } elseif ($nilaiAkhir >= 60) {
                    $huruf = 'C+';
                    $angka = 2.3;
                } elseif ($nilaiAkhir >= 55) {
                    $huruf = 'C';
                    $angka = 2.0;
                } elseif ($nilaiAkhir >= 40) {
                    $huruf = 'D';
                    $angka = 1.0;
                } else {
                    $huruf = 'E';
                    $angka = 0.0;
                }

                // Parsing Kehadiran (Contoh: "12/14")
                $jmlHadir = null;
                $jmlTatapMuka = null;
                if (!empty($item['jumlah_kehadiran'])) {
                    $parts = explode('/', $item['jumlah_kehadiran']);
                    $jmlHadir = (int)($parts[0] ?? 0);
                    $jmlTatapMuka = (int)($parts[1] ?? 14);
                }

                Nilai::updateOrCreate(
                    ['id_mahasiswa' => $item['id_mahasiswa'], 'id_jadwal' => $request->id_jadwal],
                    [
                        'id_dosen' => $jadwal->id_dosen,
                        'nilai_tugas' => $tugas,
                        'nilai_uts' => $uts,
                        'nilai_uas' => $uas,
                        'nilai_praktikum' => $prak,
                        'nilai_kehadiran' => $hadir,
                        'nilai_akhir' => $nilaiAkhir,
                        'nilai_huruf' => $huruf,
                        'nilai_angka' => $angka,
                        'jumlah_kehadiran' => $jmlHadir,
                        'jumlah_pertemuan' => $jmlTatapMuka,
                        'status' => 'draft'
                    ]
                );
            }
            DB::commit();
            return redirect()->route('nilai.index', ['id_jadwal' => $jadwal->id])->with('success', 'Nilai berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function publish($id)
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->update([
            'status' => 'published',
            'published_at' => now(),
            'published_by' => Auth::id()
        ]);

        return back()->with('success', 'Nilai berhasil dipublish.');
    }

    public function publishMassal(Request $request)
    {
        $id_jadwal = $request->id_jadwal;

        if (!$id_jadwal) {
            return back()->with('error', 'Jadwal tidak ditemukan.');
        }

        Nilai::where('id_jadwal', $id_jadwal)
            ->where('status', 'draft')
            ->update([
                'status' => 'published',
                'published_at' => now(),
                'published_by' => Auth::id()
            ]);

        return back()->with('success', 'Semua nilai pada jadwal ini berhasil dipublish.');
    }

    public function edit($id)
    {
        $nilai = Nilai::with(['mahasiswa.user', 'jadwal.matkul', 'dosen.user'])
            ->findOrFail($id);

        $user = Auth::user();

        // AUTHORIZATION: Dosen hanya bisa edit nilai yang diampunya
        if ($user->id_role == 7 && $nilai->id_dosen != ($user->dosen->id ?? 0)) {
            abort(403, 'Unauthorized');
        }

        return view('nilai.edit', compact('nilai'));
    }

    /**
     * Update satu nilai dan hitung ulang otomatis
     */
    public function update(Request $request, $id)
    {
        $nilai = Nilai::findOrFail($id);
        $user = Auth::user();

        // AUTHORIZATION
        if ($user->id_role == 7 && $nilai->id_dosen != ($user->dosen->id ?? 0)) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai_uas' => 'nullable|numeric|min:0|max:100',
            'nilai_praktikum' => 'nullable|numeric|min:0|max:100',
            'nilai_kehadiran' => 'nullable|numeric|min:0|max:100',
        ]);

        // Ambil data input angka
        $tugas = (float)($request->nilai_tugas ?? 0);
        $uts = (float)($request->nilai_uts ?? 0);
        $uas = (float)($request->nilai_uas ?? 0);
        $prak = (float)($request->nilai_praktikum ?? 0);
        $hadir = (float)($request->nilai_kehadiran ?? 0);

        // Hitung Nilai Akhir Otomatis (Bobot: 20%, 30%, 35%, 10%, 5%)
        $nilaiAkhir = ($tugas * 0.20) + ($uts * 0.30) + ($uas * 0.35) + ($prak * 0.10) + ($hadir * 0.05);

        // Tentukan Grade / Huruf Mutu
        if ($nilaiAkhir >= 85) {
            $huruf = 'A';
            $angka = 4.0;
        } elseif ($nilaiAkhir >= 80) {
            $huruf = 'A-';
            $angka = 3.7;
        } elseif ($nilaiAkhir >= 75) {
            $huruf = 'B+';
            $angka = 3.3;
        } elseif ($nilaiAkhir >= 70) {
            $huruf = 'B';
            $angka = 3.0;
        } elseif ($nilaiAkhir >= 65) {
            $huruf = 'B-';
            $angka = 2.7;
        } elseif ($nilaiAkhir >= 60) {
            $huruf = 'C+';
            $angka = 2.3;
        } elseif ($nilaiAkhir >= 55) {
            $huruf = 'C';
            $angka = 2.0;
        } elseif ($nilaiAkhir >= 40) {
            $huruf = 'D';
            $angka = 1.0;
        } else {
            $huruf = 'E';
            $angka = 0.0;
        }

        // Parsing Kehadiran (Format: 12/14)
        $jmlHadir = $nilai->jumlah_kehadiran;
        $jmlPertemuan = $nilai->jumlah_pertemuan;
        if (!empty($request->jumlah_kehadiran_input)) {
            $parts = explode('/', $request->jumlah_kehadiran_input);
            $jmlHadir = (int)($parts[0] ?? 0);
            $jmlPertemuan = (int)($parts[1] ?? 14);
        }

        $nilai->update([
            'nilai_tugas' => $tugas,
            'nilai_uts' => $uts,
            'nilai_uas' => $uas,
            'nilai_praktikum' => $prak,
            'nilai_kehadiran' => $hadir,
            'nilai_akhir' => $nilaiAkhir,
            'nilai_huruf' => $huruf,
            'nilai_angka' => $angka,
            'jumlah_kehadiran' => $jmlHadir,
            'jumlah_pertemuan' => $jmlPertemuan,
            'catatan_dosen' => $request->catatan_dosen,
        ]);

        return redirect()->route('nilai.index', ['id_jadwal' => $nilai->id_jadwal])
            ->with([
                'alert_type' => 'success',
                'message' => 'Nilai mahasiswa berhasil diperbarui dan dihitung ulang!'
            ]);
    }

    /**
     * Delete data nilai
     */
    public function destroy($id)
    {
        $nilai = Nilai::findOrFail($id);
        $user = Auth::user();

        // AUTHORIZATION
        if ($user->id_role == 7 && $nilai->id_dosen != ($user->dosen->id ?? 0)) {
            abort(403, 'Unauthorized');
        }

        // Jangan izinkan hapus jika sudah published (kecuali Admin)
        if ($nilai->status == 'published' && $user->id_role != 1) {
            return back()->with([
                'alert_type' => 'danger',
                'message' => 'Nilai yang sudah dipublish tidak dapat dihapus!'
            ]);
        }

        $nilai->delete();

        return back()->with([
            'alert_type' => 'success',
            'message' => 'Data nilai mahasiswa berhasil dihapus.'
        ]);
    }
}
