<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $pages = 'role';
        $role = Role::all();
        return view('pengguna.role', compact('pages', 'role'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_role' => ['required'],
            'deskripsi_role' => ['required'],


        ]);

        Role::create([
            'nama_role' => $request->nama_role,
            'deskripsi_role' => $request->deskripsi_role,

        ]);
        return redirect()->route('master-role.index')->with(['alert_type' => 'primary', 'message' => 'Data berhasil disimpan.']);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_role' => 'required|max:50',
            'deskripsi_role' => 'required',
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'nama_role' => $request->nama_role,
            'deskripsi_role' => $request->deskripsi_role,
        ]);

        return redirect()->route('master-role.index')->with([
            'alert_type' => 'warning',
            'message' => 'Data telah diubah.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->users()->count() > 0) {
            return redirect()->back()
                ->with([
                    'alert_type' => 'danger',
                    'message' => 'Role ini masih digunakan oleh user, tidak bisa dihapus.'
                ]);
        }

        $role->delete();

        return redirect()->route('master-role.index')->with([
            'alert_type' => 'danger',
            'message' => 'Data berhasil dihapus.'
        ]);
    }
}
