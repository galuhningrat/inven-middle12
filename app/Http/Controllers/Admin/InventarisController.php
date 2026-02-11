<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventaris; // Pastikan Model sudah dibuat
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    /**
     * Constructor untuk Permission Check
     * Sesuai dokumen: Admin Akademik (No Access), Karyawan (Full Control)
     */
    public function __construct()
    {
        // Hanya user dengan permission 'inventaris' yang boleh masuk
        $this->middleware('permission:inventaris,read')->only(['index']);
        $this->middleware('permission:inventaris,create')->only(['store']); // Create
        $this->middleware('permission:inventaris,update')->only(['update']); // Edit
        $this->middleware('permission:inventaris,delete')->only(['destroy']); // Delete
    }

    /**
     * Menampilkan daftar aset/inventaris
     */
    public function index()
    {
        // Mengambil data terbaru dengan pagination
        $inventaris = Inventaris::latest()->paginate(10);

        return view('admin.inventaris.index', compact('inventaris'));
    }

    /**
     * Menyimpan data inventaris baru
     * Tugas Utama Karyawan: "Mencatat inventaris kampus" 
     */
    public function store(Request $request)
    {
        // Validasi Input
        $request->validate([
            'kode_barang'     => 'required|unique:inventaris,kode_barang|max:50',
            'nama_barang'     => 'required|string|max:150',
            'kategori'        => 'required|in:Elektronik,Furniture,Kendaraan,Alat Tulis,Lainnya',
            'jumlah'          => 'required|integer|min:1',
            'kondisi'         => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'tanggal_perolehan' => 'required|date',
            'harga_perolehan' => 'nullable|numeric|min:0',
            'lokasi'          => 'required|string|max:100', // e.g., "Gedung A Ruang 101"
            'keterangan'      => 'nullable|string',
        ]);

        // Simpan ke Database
        Inventaris::create($request->all());

        return redirect()->back()
            ->with(['alert_type' => 'success', 'message' => 'Data Aset berhasil ditambahkan.']);
    }

    /**
     * Mengupdate data inventaris
     * Karyawan memiliki akses "Maintenance" data aset 
     */
    public function update(Request $request, $id)
    {
        $item = Inventaris::findOrFail($id);

        $request->validate([
            'kode_barang'     => 'required|max:50|unique:inventaris,kode_barang,' . $item->id, // Ignore current ID
            'nama_barang'     => 'required|string|max:150',
            'kategori'        => 'required|in:Elektronik,Furniture,Kendaraan,Alat Tulis,Lainnya',
            'jumlah'          => 'required|integer|min:1',
            'kondisi'         => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'tanggal_perolehan' => 'required|date',
            'harga_perolehan' => 'nullable|numeric|min:0',
            'lokasi'          => 'required|string|max:100',
        ]);

        $item->update($request->all());

        return redirect()->back()
            ->with(['alert_type' => 'info', 'message' => 'Data Aset berhasil diperbarui.']);
    }

    /**
     * Menghapus data inventaris
     */
    public function destroy($id)
    {
        $item = Inventaris::findOrFail($id);
        $item->delete();

        return redirect()->back()
            ->with(['alert_type' => 'danger', 'message' => 'Data Aset berhasil dihapus!']);
    }
}
