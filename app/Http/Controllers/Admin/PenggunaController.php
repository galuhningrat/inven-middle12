<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PenggunaController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $pages = 'user';
        $users = User::with('role')->get();
        $roles = Role::all();
        return view('pengguna.user', compact('pages', 'users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4',
            'nama' => 'required|string|max:100',
            'id_role' => 'required|exists:roles,id',
            'img' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Upload foto LANGSUNG ke public/foto_users
            $fotoPath = 'foto_users/default.png';
            
            if ($request->hasFile('img')) {
                $file = $request->file('img');
                $namaFile = Str::slug($request->nama) . '-' . time() . '.' . $file->getClientOriginalExtension();
                
                // SIMPAN LANGSUNG KE public/foto_users
                $file->move(public_path('foto_users'), $namaFile);
                
                $fotoPath = 'foto_users/' . $namaFile;
            }

            User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'nama' => $request->nama,
                'id_role' => $request->id_role,
                'status' => $request->status ? 'Aktif' : 'Nonaktif',
                'img' => $fotoPath,
            ]);

            DB::commit();

            return redirect()->route('master-pengguna.index')->with([
                'message' => 'Pengguna berhasil ditambahkan!',
                'alert_type' => 'primary'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with([
                'message' => 'Error: ' . $e->getMessage(),
                'alert_type' => 'danger'
            ])->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:4',
            'nama' => 'required|string|max:100',
            'id_role' => 'required|exists:roles,id',
            'img' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $fotoPath = $user->img;
            
            if ($request->hasFile('img')) {
                // Hapus foto lama
                if ($user->img !== 'foto_users/default.png' && File::exists(public_path($user->img))) {
                    File::delete(public_path($user->img));
                }
                
                // Upload foto baru
                $file = $request->file('img');
                $namaFile = Str::slug($request->nama) . '-' . time() . '.' . $file->getClientOriginalExtension();
                
                // SIMPAN LANGSUNG KE public/foto_users
                $file->move(public_path('foto_users'), $namaFile);
                
                $fotoPath = 'foto_users/' . $namaFile;
            }

            $user->update([
                'email' => $request->email,
                'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
                'nama' => $request->nama,
                'id_role' => $request->id_role,
                'status' => $request->status ? 'Aktif' : 'Nonaktif',
                'img' => $fotoPath,
            ]);

            DB::commit();

            return redirect()->route('master-pengguna.index')->with([
                'message' => 'Pengguna berhasil diupdate!',
                'alert_type' => 'success'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with([
                'message' => 'Error: ' . $e->getMessage(),
                'alert_type' => 'danger'
            ])->withInput();
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = $request->status === 'true' ? 'Aktif' : 'Nonaktif';
        $user->save();

        return redirect()->route('master-pengguna.index')
            ->with('message', "Status pengguna berhasil diubah menjadi {$user->status}")
            ->with('alert_type', 'success');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->status === 'Aktif') {
            return redirect()->back()->with([
                'message' => 'Tidak bisa dihapus. Status pengguna aktif!',
                'alert_type' => 'warning'
            ]);
        }

        $usedInMahasiswa = DB::table('mahasiswa')->where('id_users', $user->id)->exists();
        $usedInDosen     = DB::table('dosen')->where('id_users', $user->id)->exists();
        $usedInKaryawan  = DB::table('karyawan')->where('id_users', $user->id)->exists();

        if ($usedInMahasiswa || $usedInDosen || $usedInKaryawan) {
            return redirect()->back()->with([
                'message' => 'Tidak bisa dihapus. Pengguna masih digunakan!',
                'alert_type' => 'warning'
            ]);
        }

        DB::beginTransaction();
        try {
            // Hapus foto
            if ($user->img !== 'foto_users/default.png' && File::exists(public_path($user->img))) {
                File::delete(public_path($user->img));
            }

            $user->delete();

            DB::commit();

            return redirect()->route('master-pengguna.index')->with([
                'message' => 'Pengguna berhasil dihapus!',
                'alert_type' => 'danger'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with([
                'message' => 'Error: ' . $e->getMessage(),
                'alert_type' => 'danger'
            ]);
        }
    }
}