<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:pegawai_nondosen,read')->only(['index', 'show']);
        $this->middleware('permission:pegawai_nondosen,create')->only(['create', 'store', 'redirectToForm']);
        $this->middleware('permission:pegawai_nondosen,update')->only(['edit', 'update']);
        $this->middleware('permission:pegawai_nondosen,delete')->only(['destroy']);
    }
    public function index()
    {
        $karyawan = Karyawan::with('user')->get();
        $users = User::where('id_role', 9)->get(['id', 'nama', 'email']);
        return view('karyawan.index', compact('karyawan', 'users'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $karyawan = Karyawan::with('user')->findOrFail($id);
        return view('karyawan.detail-karyawan', compact('karyawan'));
    }

    /**
     * Redirect setelah memilih user di modal.
     */
    public function redirectToForm(Request $request)
    {
        $request->validate([
            'id_users' => 'required|exists:users,id',
        ]);

        return redirect()->route('karyawan.create', ['id_users' => $request->id_users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id_users)
    {
        $user = User::findOrFail($id_users);

        return view('karyawan.create-karyawan', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_users' => 'required|exists:users,id',
            'nip' => 'required|unique:karyawan,nip',
            'nik' => 'required|unique:karyawan,nik|digits:16',
            'no_kk' => 'required|unique:karyawan,no_kk|digits:16',
            'npwp' => 'nullable|string|max:20',
            'tempat_lahir' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|in:Islam,Katolik,Protestan,Budha,Hindu,Konghucu',
            'dusun' => 'required|string|max:30',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'ds_kel' => 'required|string|max:20',
            'kec' => 'required|string|max:20',
            'kab' => 'required|string|max:20',
            'prov' => 'required|string|max:20',
            'kode_pos' => 'required|string|max:5',
            'hp' => 'required|string|max:14',
            'marital_status' => 'required|in:Lajang,Menikah,Cerai Hidup,Cerai Mati',
            'status' => 'required|in:Karyawan Tetap,Karyawan Tidak Tetap',
            'pend_terakhir' => 'required|in:SD,SMP,SMA,D1,D2,D3,D4,S1,S2,S3',
            'gol_darah' => 'nullable|in:A,B,AB,O',
        ]);

        Karyawan::create($request->all());

        return redirect()->route('karyawan.index')->with(['alert_type' => 'primary', 'message' => 'Data karyawan berhasil ditambahkan.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $karyawan = Karyawan::with('user')->findOrFail($id);
        return view('karyawan.edit-karyawan', compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $karyawan->id_users,
            'nip' => 'required|unique:karyawan,nip,' . $karyawan->id,
            'nik' => 'required|digits:16|unique:karyawan,nik,' . $karyawan->id,
            'no_kk' => 'required|digits:16|unique:karyawan,no_kk,' . $karyawan->id,
            'npwp' => 'nullable|string|max:20',
            'tempat_lahir' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|in:Islam,Katolik,Protestan,Budha,Hindu,Konghucu',
            'dusun' => 'required|string|max:30',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'ds_kel' => 'required|string|max:20',
            'kec' => 'required|string|max:20',
            'kab' => 'required|string|max:20',
            'prov' => 'required|string|max:20',
            'kode_pos' => 'required|string|max:5',
            'hp' => 'required|string|max:14',
            'marital_status' => 'required|in:Lajang,Menikah,Cerai Hidup,Cerai Mati',
            'status' => 'required|in:Karyawan Tetap,Karyawan Tidak Tetap',
            'pend_terakhir' => 'required|in:SD,SMP,SMA,D1,D2,D3,D4,S1,S2,S3',
            'gol_darah' => 'nullable|in:A,B,AB,O',
        ]);

        // Update tabel users (nama & email)
        $user = $karyawan->user;
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->save();

        // Update tabel karyawan (tanpa nama & email)
        $karyawan->update($request->except(['nama', 'email']));

        return redirect()->route('karyawan.index')->with(['alert_type' => 'success', 'message' => 'Data karyawan berhasil diupdate.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();

        return redirect()->route('karyawan.index')->with(['alert_type' => 'danger', 'message' => 'Data karyawan berhasil dihapus!']);
    }
}
