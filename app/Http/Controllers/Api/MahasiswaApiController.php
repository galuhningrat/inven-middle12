<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaApiController extends Controller
{
    /**
     * Menampilkan list mahasiswa untuk API Mobile
     */
    public function index()
    {
        $mahasiswa = Mahasiswa::with(['user', 'prodi'])->get();
        return response()->json([
            'success' => true,
            'message' => 'List Data Mahasiswa',
            'data'    => $mahasiswa
        ], 200);
    }

    /**
     * Menampilkan detail satu mahasiswa berdasarkan NIM
     */
    public function show($nim)
    {
        $mahasiswa = Mahasiswa::with(['user', 'prodi', 'pendidikan', 'orangtua', 'riwayatKuliah', 'pembayaran', 'dokumen'])
            ->where('nim', $nim)
            ->first();

        if ($mahasiswa) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Mahasiswa',
                'data'    => $mahasiswa
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Mahasiswa Tidak Ditemukan',
        ], 404);
    }

    // Fungsi store, update, destroy untuk API bisa ditambahkan jika Mobile butuh fitur Input Data
}
