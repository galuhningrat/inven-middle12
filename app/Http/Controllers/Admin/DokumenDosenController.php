<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenDosen;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DokumenDosenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_dosen' => 'required|exists:dosen,id',
            'nama' => 'required|string|max:150',
            'berkas' => 'required|file|max:10240', // 10MB
        ], [
            'berkas.max' => 'Ukuran file maksimal 10MB',
        ]);

        DB::beginTransaction();
        try {
            $file = $request->file('berkas');
            $dosen = Dosen::findOrFail($request->id_dosen);

            // Generate nama file unik
            $filename = 'dok_' . $dosen->nip . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('dokumen_dosen', $filename, 'public');

            DokumenDosen::create([
                'id_dosen' => $request->id_dosen,
                'nama' => $request->nama,
                'berkas' => $path,
                'size' => $file->getSize(),
                'ekstensi' => $file->getClientOriginalExtension(),
            ]);

            DB::commit();

            return back()->with('success', 'Dokumen berhasil diupload.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file jika ada error
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            Log::error('Error upload dokumen dosen: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Dosen $dosen, DokumenDosen $dokumen)
    {
        // Pastikan dokumen milik dosen ini
        if ($dokumen->id_dosen !== $dosen->id) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            // Hapus file fisik
            if ($dokumen->berkas && Storage::disk('public')->exists($dokumen->berkas)) {
                Storage::disk('public')->delete($dokumen->berkas);
            }

            $dokumen->delete();

            DB::commit();

            return back()->with('success', 'Dokumen berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error delete dokumen dosen: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
