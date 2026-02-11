<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }
    public function index()
    {
        $ruangan = Ruangan::all();
        return view('data-master.ruangan', compact('ruangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_ruang' => 'required|max:20|unique:ruangan,kode_ruang',
            'nama_ruang' => 'required|max:100',
            'kapasitas' => 'required|integer|min:1',
            'keterangan' => 'nullable|max:255',
        ]);

        Ruangan::create($request->only(['kode_ruang', 'nama_ruang', 'kapasitas', 'keterangan']));
        return redirect()->route('ruangan.index')->with([
            'alert_type' => 'primary',
            'message' => 'Data ruangan berhasil ditambahkan.'
        ]);
    }

    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $request->validate([
            'kode_ruang' => 'required|max:20|unique:ruangan,kode_ruang,' . $ruangan->id,
            'nama_ruang' => 'required|max:100',
            'kapasitas' => 'required|integer|min:1',
            'keterangan' => 'nullable|max:255',
        ]);

        $ruangan->update($request->only(['kode_ruang', 'nama_ruang', 'kapasitas', 'keterangan']));
        return redirect()->route('ruangan.index')->with([
            'alert_type' => 'success',
            'message' => 'Data ruangan berhasil diupdate.'
        ]);
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->delete();
        return redirect()->route('ruangan.index')->with([
            'alert_type' => 'danger',
            'message' => 'Data ruangan berhasil dihapus!'
        ]);
    }
}
