<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userRole = strtolower($user->role->nama_role);

        // ✅ MAPPING 9 ROLE BARU KE VIEW DASHBOARD
        $roleViewMap = [
            'super admin'       => 'dashboard.dashboard-admin',
            'admin akademik'    => 'dashboard.dashboard-admin',
            'bagian keuangan'   => 'dashboard.dashboard-keuangan',
            'rektor'            => 'dashboard.dashboard-rektor',
            'dekan'             => 'dashboard.dashboard-dekan',
            'kaprodi'           => 'dashboard.dashboard-kaprodi',
            'dosen'             => 'dashboard.dashboard-dosen',
            'mahasiswa'         => 'dashboard.dashboard-mahasiswa',
            'karyawan'          => 'dashboard.dashboard-karyawan',
        ];

        // ✅ CEK APAKAH ROLE ADA DI MAPPING
        if (isset($roleViewMap[$userRole])) {
            // Jika view belum ada, gunakan dashboard admin sebagai default
            $viewName = $roleViewMap[$userRole];

            // Untuk role yang belum punya view khusus, pakai dashboard admin dulu
            if (!view()->exists($viewName)) {
                $viewName = 'dashboard.dashboard-admin';
            }

            return view($viewName, compact('user'));
        }

        // ✅ JIKA ROLE TIDAK DIKENALI
        abort(403, 'Forbidden - Role Anda (' . $user->role->nama_role . ') tidak memiliki akses dashboard');
    }
}
