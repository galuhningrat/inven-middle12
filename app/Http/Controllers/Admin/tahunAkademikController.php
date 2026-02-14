<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use App\Models\Rombel;
use App\Models\Mahasiswa;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TahunAkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahunAkademik = TahunAkademik::orderBy('tahun_awal', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        return view('data-master.tahun-akademik', compact('tahunAkademik'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_awal' => 'required|string|max:4',
            'tahun_akhir' => 'required|string|max:4',
            'semester' => 'required|in:Ganjil,Genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status_aktif' => 'required|boolean',
        ], [
            'tahun_awal.required' => 'Tahun akademik Awal wajib diisi',
            'tahun_akhir.required' => 'Tahun akademik Akhir wajib diisi',
            'semester.required' => 'Semester wajib dipilih',
            'semester.in' => 'Semester harus Ganjil atau Genap',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai',
            'status_aktif.required' => 'Status wajib dipilih',
        ]);

        // ✅ CHECK: Cegah duplikasi tahun+semester
        $exists = TahunAkademik::where('tahun_awal', $request->tahun_awal)
            ->where('semester', $request->semester)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['tahun_awal' => 'Kombinasi tahun akademik dan semester sudah ada'])
                ->withInput();
        }

        // ✅ CHECK: Cegah overlap tanggal untuk semester yang sama
        $overlap = TahunAkademik::where('semester', $request->semester)
            ->where(function($query) use ($request) {
                $query->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai])
                    ->orWhereBetween('tanggal_selesai', [$request->tanggal_mulai, $request->tanggal_selesai])
                    ->orWhere(function($q) use ($request) {
                        $q->where('tanggal_mulai', '<=', $request->tanggal_mulai)
                          ->where('tanggal_selesai', '>=', $request->tanggal_selesai);
                    });
            })
            ->exists();

        if ($overlap) {
            return redirect()->back()
                ->withErrors(['tanggal_mulai' => 'Periode tanggal bertumpang tindih dengan tahun akademik lain'])
                ->withInput();
        }

        // ✅ AUTO-TOGGLE: Jika status_aktif = true, nonaktifkan yang lain
        if ($request->status_aktif == 1) {
            TahunAkademik::where('status_aktif', 1)->update(['status_aktif' => 0]);
        }

        try {
            TahunAkademik::create([
                'tahun_awal' => $request->tahun_awal,
                'tahun_akhir' => $request->tahun_akhir,
                'semester' => $request->semester,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status_aktif' => $request->status_aktif,
            ]);

            Log::info('Tahun Akademik Created', [
                'tahun' => $request->tahun_awal . '/' . $request->tahun_akhir,
                'semester' => $request->semester,
                'status_aktif' => $request->status_aktif,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('tahun-akademik.index')
                ->with('sukses', 'Data berhasil ditambahkan.');

        } catch (\Exception $e) {
            Log::error('Failed to create Tahun Akademik', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ta = TahunAkademik::findOrFail($id);

        $request->validate([
            'tahun_awal' => 'required|string|max:4',
            'tahun_akhir' => 'required|string|max:4',
            'semester' => 'required|in:Ganjil,Genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status_aktif' => 'required|boolean',
        ], [
            'tahun_awal.required' => 'Tahun akademik Awal wajib diisi',
            'tahun_akhir.required' => 'Tahun akademik Akhir wajib diisi',
            'semester.required' => 'Semester wajib dipilih',
            'semester.in' => 'Semester harus Ganjil atau Genap',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai',
            'status_aktif.required' => 'Status wajib dipilih',
        ]);

        // ✅ CHECK: Cegah duplikasi tahun+semester (kecuali record sendiri)
        $exists = TahunAkademik::where('tahun_awal', $request->tahun_awal)
            ->where('semester', $request->semester)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['tahun_awal' => 'Kombinasi tahun akademik dan semester sudah ada'])
                ->withInput();
        }

        // ✅ CHECK: Cegah overlap tanggal (kecuali record sendiri)
        $overlap = TahunAkademik::where('semester', $request->semester)
            ->where('id', '!=', $id)
            ->where(function($query) use ($request) {
                $query->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai])
                    ->orWhereBetween('tanggal_selesai', [$request->tanggal_mulai, $request->tanggal_selesai])
                    ->orWhere(function($q) use ($request) {
                        $q->where('tanggal_mulai', '<=', $request->tanggal_mulai)
                          ->where('tanggal_selesai', '>=', $request->tanggal_selesai);
                    });
            })
            ->exists();

        if ($overlap) {
            return redirect()->back()
                ->withErrors(['tanggal_mulai' => 'Periode tanggal bertumpang tindih dengan tahun akademik lain'])
                ->withInput();
        }

        // ✅ AUTO-TOGGLE: Jika status_aktif = true, nonaktifkan yang lain
        if ($request->status_aktif == 1) {
            TahunAkademik::where('status_aktif', 1)
                ->where('id', '!=', $id)
                ->update(['status_aktif' => 0]);
        }

        try {
            $oldStatus = $ta->status_aktif;

            $ta->update([
                'tahun_awal' => $request->tahun_awal,
                'tahun_akhir' => $request->tahun_akhir,
                'semester' => $request->semester,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status_aktif' => $request->status_aktif,
            ]);

            Log::info('Tahun Akademik Updated', [
                'id' => $id,
                'tahun' => $request->tahun_awal . '/' . $request->tahun_akhir,
                'semester' => $request->semester,
                'old_status' => $oldStatus,
                'new_status' => $request->status_aktif,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('tahun-akademik.index')
                ->with('sukses', 'Tahun akademik berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Failed to update Tahun Akademik', [
                'id' => $id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ta = TahunAkademik::findOrFail($id);

        // ✅ CHECK: Jangan hapus tahun akademik yang sedang aktif
        if ($ta->status_aktif) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus tahun akademik yang sedang aktif. Nonaktifkan terlebih dahulu.');
        }

        // ✅ CHECK: Foreign key constraint - Rombel
        $usedInRombel = Rombel::where('tahun_masuk', $id)->exists();
        if ($usedInRombel) {
            return redirect()->back()
                ->with('error', 'Gagal! Tahun akademik ini sudah memiliki data Rombel. Data tidak dapat dihapus.');
        }

        // ✅ CHECK: Foreign key constraint - Mahasiswa
        $usedInMahasiswa = Mahasiswa::where('tahun_masuk', $id)->exists();
        if ($usedInMahasiswa) {
            return redirect()->back()
                ->with('error', 'Gagal! Tahun akademik ini sudah memiliki data Mahasiswa. Data tidak dapat dihapus.');
        }

        // ✅ CHECK: Foreign key constraint - Jadwal
        $usedInJadwal = Jadwal::where('tahun_akademik', $id)->exists();
        if ($usedInJadwal) {
            return redirect()->back()
                ->with('error', 'Gagal! Tahun akademik ini sudah memiliki data Jadwal Kuliah. Data tidak dapat dihapus.');
        }

        try {
            $tahunInfo = $ta->tahun_awal . '/' . $ta->tahun_akhir . ' - ' . $ta->semester;

            $ta->delete();

            Log::info('Tahun Akademik Deleted', [
                'id' => $id,
                'tahun' => $tahunInfo,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('tahun-akademik.index')
                ->with('sukses', 'Data berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Failed to delete Tahun Akademik', [
                'id' => $id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Get currently active academic year.
     */
    public function getActive()
    {
        $active = TahunAkademik::where('status_aktif', 1)->first();

        if (!$active) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada tahun akademik yang aktif'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $active
        ]);
    }

    /**
     * Activate a specific academic year.
     */
    public function activate($id)
    {
        try {
            // Deactivate all others
            TahunAkademik::where('status_aktif', 1)->update(['status_aktif' => 0]);

            // Activate the selected one
            $ta = TahunAkademik::findOrFail($id);
            $ta->update(['status_aktif' => 1]);

            Log::info('Tahun Akademik Activated', [
                'id' => $id,
                'tahun' => $ta->tahun_awal . '/' . $ta->tahun_akhir,
                'semester' => $ta->semester,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('sukses', 'Tahun akademik berhasil diaktifkan.');

        } catch (\Exception $e) {
            Log::error('Failed to activate Tahun Akademik', [
                'id' => $id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
