<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginSession;

class LoginSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $session = LoginSession::with('user')->whereHas('user')->orderByDesc('last_activity')->get();

        return view('pengguna.session', compact('session'));
    }

    public function destroy($user_id)
    {
        $ses = LoginSession::where('user_id', $user_id)->delete();

        return redirect()->route('master-session.index')->with([
            'alert_type' => 'danger',
            'message' => 'Sesi pengguna berhasil dihapus!'
        ]);
    }
}
