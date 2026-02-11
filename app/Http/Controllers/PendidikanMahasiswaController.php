<?php

namespace App\Http\Controllers;

use App\Models\PendidikanMahasiswa;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class PendidikanMahasiswaController extends Controller
{
    // Fungsi untuk mengambil data JSON buat Edit & Detail
    public function edit($nim, $id)
    {
        $pendidikan = PendidikanMahasiswa::where('id', $id)->first();

        if (!$pendidikan) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($pendidikan);
    }

    // Fungsi untuk Update data
    public function update(Request $request, $nim, $id)
    {
        $pendidikan = PendidikanMahasiswa::findOrFail($id);
        // Tambahkan logika update kamu di sini...

        return redirect()->back()->with('success', 'Data berhasil diperbarui');
    }
}
