<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class TahunAkademikController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }
    public function index()
    {
        // Menggunakan paginate untuk performa yang lebih baik
        $tahunAkademik = TahunAkademik::orderBy('tahun_awal', 'asc')
            ->orderBy('semester', 'asc')
            ->get();
        return view('data-master.tahun-akademik', compact('tahunAkademik'));
    }

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

        // Cek apakah kombinasi tahun akademik dan semester sudah ada
        $exists = TahunAkademik::where('tahun_awal', $request->tahun_awal)
            ->where('semester', $request->semester)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['tahun_awal' => 'Kombinasi tahun akademik dan semester sudah ada'])
                ->withInput();
        }

        // Jika status aktif = true, nonaktifkan yang lain
        if ($request->status_aktif == 1) {
            TahunAkademik::where('status_aktif', 1)->update(['status_aktif' => 0]);
        }

        TahunAkademik::create([
            'tahun_awal' => $request->tahun_awal,
            'tahun_akhir' => $request->tahun_akhir,
            'semester' => $request->semester,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status_aktif' => $request->status_aktif,
        ]);

        return redirect()->route('tahun-akademik.index')->with('sukses', 'Tahun akademik berhasil ditambahkan.');
    }

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

        // Cek apakah kombinasi tahun akademik dan semester sudah ada (kecuali data yang sedang diedit)
        $exists = TahunAkademik::where('tahun_awal', $request->tahun_awal)
            ->where('semester', $request->semester)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['tahun_awal' => 'Kombinasi tahun akademik dan semester sudah ada'])
                ->withInput();
        }

        // Jika status aktif = true, nonaktifkan yang lain
        if ($request->status_aktif == 1) {
            TahunAkademik::where('status_aktif', 1)
                ->where('id', '!=', $id)
                ->update(['status_aktif' => 0]);
        }

        $ta->update([
            'tahun_awal' => $request->tahun_awal,
            'tahun_akhir' => $request->tahun_akhir,
            'semester' => $request->semester,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status_aktif' => $request->status_aktif,
        ]);

        return redirect()->route('tahun-akademik.index')->with('sukses', 'Tahun akademik berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ta = TahunAkademik::findOrFail($id);

        // Cek apakah tahun akademik yang akan dihapus sedang aktif
        if ($ta->status_aktif) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus tahun akademik yang sedang aktif.');
        }

        $usedInRombel = \App\Models\Rombel::where('tahun_masuk', $id)->exists();
        if ($usedInRombel) {
            return redirect()->back()->with('error', 'Gagal! Tahun akademik ini sudah memiliki data Rombel.');
        }

        $ta->delete();
        return redirect()->route('tahun-akademik.index')->with('sukses', 'Data berhasil dihapus.');
    }
}
