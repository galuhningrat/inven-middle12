<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arsip; // Pastikan Model dibuat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Penting untuk hapus/simpan file

class ArsipController extends Controller
{
    /**
     * Constructor Permission
     * Mengunci akses hanya untuk User yang punya izin 'arsip' (Role 9 & Role 1)
     */
    public function __construct()
    {
        $this->middleware('permission:arsip,read')->only(['index', 'download']);
        $this->middleware('permission:arsip,create')->only(['store']);
        $this->middleware('permission:arsip,update')->only(['update']);
        $this->middleware('permission:arsip,delete')->only(['destroy']);
    }

    /**
     * Menampilkan daftar arsip surat
     */
    public function index()
    {
        // Menampilkan surat terbaru dulu (Surat Masuk/Keluar)
        $arsip = Arsip::latest()->paginate(10);
        return view('admin.arsip.index', compact('arsip'));
    }

    /**
     * Menyimpan arsip baru (Upload Digital)
     * Sesuai tugas: "Menangani surat-menyurat dan pengarsipan digital" 
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat' => 'required|string|max:100|unique:arsip,nomor_surat',
            'judul'       => 'required|string|max:200',
            'kategori'    => 'required|in:Surat Masuk,Surat Keluar,SK,Dokumen Penting',
            'tanggal_surat' => 'required|date',
            'file_surat'  => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048', // Max 2MB
            'keterangan'  => 'nullable|string',
        ]);

        // 1. Upload File ke Storage
        // File akan disimpan di folder: storage/app/public/arsip
        $path = $request->file('file_surat')->store('arsip', 'public');

        // 2. Simpan Data ke Database
        Arsip::create([
            'nomor_surat'   => $request->nomor_surat,
            'judul'         => $request->judul,
            'kategori'      => $request->kategori,
            'tanggal_surat' => $request->tanggal_surat,
            'file_path'     => $path, // Simpan lokasi file
            'keterangan'    => $request->keterangan,
        ]);

        return redirect()->back()
            ->with(['alert_type' => 'success', 'message' => 'Dokumen berhasil diarsipkan secara digital.']);
    }

    /**
     * Update Data Arsip (Ganti File jika perlu)
     */
    public function update(Request $request, $id)
    {
        $arsip = Arsip::findOrFail($id);

        $request->validate([
            'nomor_surat' => 'required|string|max:100|unique:arsip,nomor_surat,' . $arsip->id,
            'judul'       => 'required|string|max:200',
            'kategori'    => 'required|in:Surat Masuk,Surat Keluar,SK,Dokumen Penting',
            'tanggal_surat' => 'required|date',
            'file_surat'  => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048', // Nullable: tidak wajib ganti file
        ]);

        $data = $request->except(['file_surat']);

        // Logika Ganti File:
        if ($request->hasFile('file_surat')) {
            // 1. Hapus file lama fisik
            if ($arsip->file_path && Storage::disk('public')->exists($arsip->file_path)) {
                Storage::disk('public')->delete($arsip->file_path);
            }
            // 2. Upload file baru
            $data['file_path'] = $request->file('file_surat')->store('arsip', 'public');
        }

        $arsip->update($data);

        return redirect()->back()
            ->with(['alert_type' => 'info', 'message' => 'Data arsip diperbarui.']);
    }

    /**
     * Hapus Arsip (Database & File Fisik)
     */
    public function destroy($id)
    {
        $arsip = Arsip::findOrFail($id);

        // Hapus file fisik agar storage tidak penuh
        if ($arsip->file_path && Storage::disk('public')->exists($arsip->file_path)) {
            Storage::disk('public')->delete($arsip->file_path);
        }

        $arsip->delete();

        return redirect()->back()
            ->with(['alert_type' => 'danger', 'message' => 'Arsip dan file digital berhasil dihapus.']);
    }
}
