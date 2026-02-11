<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendidikanDosen;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PendidikanDosenController extends Controller
{
    public function store(Request $request, Dosen $dosen)
    {
        $validated = $request->validate([
            'jenjang' => 'required|string|max:10',
            'nama_pt' => 'required|string|max:100',
            'jurusan' => 'required|string|max:100',
            'gelar' => 'nullable|string|max:50',
            'tahun_lulus' => 'required|digits:4|integer|min:1950|max:' . date('Y'),
            'ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'tahun_lulus.max' => 'Tahun lulus tidak boleh lebih dari tahun sekarang',
            'ijazah.max' => 'Ukuran file maksimal 2MB',
            'ijazah.mimes' => 'File harus berformat PDF, JPG, JPEG, atau PNG',
        ]);

        DB::beginTransaction();
        try {
            $validated['id_dosen'] = $dosen->id;

            if ($request->hasFile('ijazah')) {
                $file = $request->file('ijazah');
                $filename = 'ijazah_' . $dosen->nip . '_' . time() . '.' . $file->getClientOriginalExtension();
                $validated['ijazah'] = $file->storeAs('ijazah_dosen', $filename, 'public');
            }

            PendidikanDosen::create($validated);

            DB::commit();

            return redirect()->route('dosen.pendidikan', $dosen->id)
                ->with('success', 'Riwayat pendidikan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file jika ada error
            if (isset($validated['ijazah']) && Storage::disk('public')->exists($validated['ijazah'])) {
                Storage::disk('public')->delete($validated['ijazah']);
            }

            Log::error('Error create pendidikan dosen: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, Dosen $dosen, PendidikanDosen $pendidikan)
    {
        // Pastikan pendidikan milik dosen ini
        if ($pendidikan->id_dosen !== $dosen->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'jenjang' => 'required|string|max:10',
            'nama_pt' => 'required|string|max:100',
            'jurusan' => 'required|string|max:100',
            'gelar' => 'nullable|string|max:50',
            'tahun_lulus' => 'required|digits:4|integer|min:1950|max:' . date('Y'),
            'ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('ijazah')) {
                // Hapus file lama
                if ($pendidikan->ijazah && Storage::disk('public')->exists($pendidikan->ijazah)) {
                    Storage::disk('public')->delete($pendidikan->ijazah);
                }

                // Upload file baru
                $file = $request->file('ijazah');
                $filename = 'ijazah_' . $dosen->nip . '_' . time() . '.' . $file->getClientOriginalExtension();
                $validated['ijazah'] = $file->storeAs('ijazah_dosen', $filename, 'public');
            }

            $pendidikan->update($validated);

            DB::commit();

            return redirect()->route('dosen.pendidikan', $dosen->id)
                ->with('success', 'Riwayat pendidikan berhasil diupdate.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update pendidikan dosen: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Dosen $dosen, PendidikanDosen $pendidikan)
    {
        // Pastikan pendidikan milik dosen ini
        if ($pendidikan->id_dosen !== $dosen->id) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            // Hapus file fisik
            if ($pendidikan->ijazah && Storage::disk('public')->exists($pendidikan->ijazah)) {
                Storage::disk('public')->delete($pendidikan->ijazah);
            }

            $pendidikan->delete();

            DB::commit();

            return back()->with('success', 'Riwayat pendidikan berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error delete pendidikan dosen: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}