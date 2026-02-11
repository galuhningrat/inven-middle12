<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dosen = Dosen::with('user')->get();
        $users = User::where('id_role', 7)
            ->whereNotIn('id', function ($query) {
                $query->select('id_users')->from('dosen');
            })
            ->get(['id', 'nama', 'email']);

        return view('dosen.index', compact('dosen', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_users' => 'required|exists:users,id',
            'nip' => 'required|string|unique:dosen,nip',
        ], [
            'id_users.required' => 'Pilih akun pengguna terlebih dahulu',
            'id_users.exists' => 'Akun pengguna tidak ditemukan',
            'nip.required' => 'NIP wajib diisi',
            'nip.unique' => 'NIP sudah terdaftar',
        ]);

        DB::beginTransaction();
        try {
            // Cek apakah user sudah jadi dosen
            $existingDosen = Dosen::where('id_users', $request->id_users)->first();
            if ($existingDosen) {
                return back()->with([
                    'alert_type' => 'danger',
                    'message' => 'User ini sudah terdaftar sebagai dosen!'
                ]);
            }

            // Simpan data awal dosen
            $dosen = Dosen::create([
                'id_users' => $request->id_users,
                'nip' => $request->nip,
                // 'nik' => '0',
            ]);

            DB::commit();

            // Redirect ke form biodata
            return redirect()->route('dosen.biodata', $dosen->id)
                ->with([
                    'alert_type' => 'success',
                    'message' => 'Data awal dosen berhasil ditambahkan. Silakan lengkapi biodata.'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error create dosen: ' . $e->getMessage());

            return back()->with([
                'alert_type' => 'danger',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // Tab Biodata
    public function biodata(Dosen $dosen)
    {
        $dosen->load('user');
        return view('dosen.biodata-dosen', compact('dosen'));
    }

    // Tab Pendidikan
    public function pendidikan(Dosen $dosen)
    {
        $dosen->load(['user', 'pendidikan']);
        $pendidikan = $dosen->pendidikan;
        return view('dosen.pendidikan-dosen', compact('dosen', 'pendidikan'));
    }

    // Tab Dokumen
    public function dokumen(Dosen $dosen)
    {
        $dosen->load(['user', 'dokumen']);
        $dokumen = $dosen->dokumen;
        return view('dosen.dokumen-dosen', compact('dosen', 'dokumen'));
    }

    // Tab Jadwal Mengajar
    public function jadwal(Dosen $dosen)
    {
        $dosen->load('user');
        $jadwal = $dosen->jadwal()->with(['matkul', 'rombel'])->get();
        return view('dosen.jadwal-dosen', compact('dosen', 'jadwal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dosen $dosen)
    {
        $dosen->load('user');
        return view('dosen.edit-dosen', compact('dosen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dosen $dosen)
    {
        $request->validate([
            'nip' => 'required|unique:dosen,nip,' . $dosen->id,
            'nik' => 'required|digits:16|unique:dosen,nik,' . $dosen->id,
            'no_kk' => 'required|digits:16|unique:dosen,no_kk,' . $dosen->id,
            'nidn' => 'nullable|digits:10|unique:dosen,nidn,' . $dosen->id,
            'nuptk' => 'nullable|digits:16|unique:dosen,nuptk,' . $dosen->id,
            'npwp' => 'nullable|string|max:20',
            'tempat_lahir' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date|before:today',
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
            'no_hp' => 'nullable|string|max:14',
            'marital_status' => 'required|in:Lajang,Menikah,Cerai Hidup,Cerai Mati',
            'status' => 'required|in:Dosen Tetap,Dosen Tidak Tetap',
            'kewarganegaraan' => 'required|in:WNI,WNA',
            'gol_darah' => 'nullable|in:A,B,AB,O',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $dosen->id_users,
        ], [
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
            'nik.digits' => 'NIK harus 16 digit',
            'no_kk.digits' => 'No KK harus 16 digit',
            'nidn.digits' => 'NIDN harus 10 digit',
            'nuptk.digits' => 'NUPTK harus 16 digit',
        ]);

        DB::beginTransaction();
        try {
            // Update data User (nama & email)
            $user = $dosen->user;
            $user->update([
                'nama' => $request->nama,
                'email' => $request->email,
            ]);

            // Update data dosen
            $dosen->update($request->except(['nama', 'email']));

            DB::commit();

            return redirect()->route('dosen.biodata', $dosen->id)
                ->with([
                    'alert_type' => 'success',
                    'message' => 'Data dosen berhasil diupdate.'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update dosen: ' . $e->getMessage());

            return back()->with([
                'alert_type' => 'danger',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dosen $dosen)
    {
        DB::beginTransaction();
        try {
            // Cek apakah dosen punya jadwal aktif
            if ($dosen->jadwal()->count() > 0) {
                return back()->with([
                    'alert_type' => 'danger',
                    'message' => 'Tidak dapat menghapus dosen yang masih memiliki jadwal mengajar!'
                ]);
            }

            // Hapus dokumen fisik
            foreach ($dosen->dokumen as $dok) {
                if ($dok->berkas && Storage::disk('public')->exists($dok->berkas)) {
                    Storage::disk('public')->delete($dok->berkas);
                }
            }

            // Hapus ijazah fisik
            foreach ($dosen->pendidikan as $pend) {
                if ($pend->ijazah && Storage::disk('public')->exists($pend->ijazah)) {
                    Storage::disk('public')->delete($pend->ijazah);
                }
            }

            // Hapus relasi (cascade sudah diatur di migration, tapi lebih aman manual)
            $dosen->dokumen()->delete();
            $dosen->pendidikan()->delete();
            $dosen->delete();

            DB::commit();

            return redirect()->route('dosen.index')->with([
                'alert_type' => 'success',
                'message' => 'Data dosen berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error delete dosen: ' . $e->getMessage());

            return back()->with([
                'alert_type' => 'danger',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
