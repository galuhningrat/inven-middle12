<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk menjalankan raw query

class FakultasController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $fakultas = Fakultas::with('dekan')->withCount('prodi')->get();

        $dekans = User::where('id_role', 5)
            ->where('status', 'Aktif')
            ->get(['id', 'nama', 'email']);

        return view('data-master.fakultas', compact('fakultas', 'dekans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_fakultas' => 'required|string|max:10|unique:fakultas,kode_fakultas',
            'nama_fakultas' => 'required|string|max:50',
            'id_dekan' => 'required|exists:users,id',
        ]);

        // EKSEKUSI PERBAIKAN SEQUENCE POSTGRESQL DI SINI
        // Kode ini akan mereset ID sequence ke nilai maksimal yang ada di tabel saat ini
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("SELECT setval(pg_get_serial_sequence('fakultas', 'id'), coalesce(max(id),0) + 1, false) FROM fakultas");
        }

        Fakultas::create([
            'kode_fakultas' => $request->kode_fakultas,
            'nama_fakultas' => $request->nama_fakultas,
            'id_dekan' => $request->id_dekan,
        ]);

        return redirect()->route('fakultas.index')
            ->with('success', 'Data fakultas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $fakultas = Fakultas::findOrFail($id);

        $request->validate([
            'kode_fakultas' => 'required|string|max:10|unique:fakultas,kode_fakultas,' . $fakultas->id,
            'nama_fakultas' => 'required|string|max:50',
            'id_dekan' => 'required|exists:users,id',
        ]);

        $fakultas->update([
            'kode_fakultas' => $request->kode_fakultas,
            'nama_fakultas' => $request->nama_fakultas,
            'id_dekan' => $request->id_dekan,
        ]);

        return redirect()->route('fakultas.index')
            ->with('success', 'Data fakultas berhasil diupdate.');
    }

    public function destroy($id)
    {
        $fakultas = Fakultas::findOrFail($id);

        if ($fakultas->prodi->count() > 0) {
            return redirect()->back()->with([
                'alert_type' => 'danger',
                'message' => 'Terdapat Prodi, Tidak bisa menghapus Fakultas!'
            ]);
        }

        $fakultas->delete();
        return redirect()->route('fakultas.index')
            ->with('danger', 'Data fakultas berhasil dihapus.');
    }
}
