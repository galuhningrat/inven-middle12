<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Redirect ke login jika belum login
        if (!Auth::check()) {
            return redirect()->route('login')->with([
                'alert_type' => 'danger',
                'message' => 'Silakan login terlebih dahulu.'
            ]);
        }

        $user = Auth::user();

        // Ambil nama role user (case-insensitive)
        $userRole = strtolower($user->role->nama_role ?? '');

        // ✅ SUPER ADMIN BYPASS - Akses ke Semua Route
        if ($user->id_role == 1 || $userRole === 'super admin') {
            return $next($request);
        }

        // Normalisasi roles yang diizinkan (lowercase)
        $roles = array_map('strtolower', $roles);

        // Cek apakah user role ada dalam daftar yang diizinkan
        if (!in_array($userRole, $roles)) {
            abort(403, 'Forbidden - Anda tidak memiliki akses ke halaman ini!');
        }

        return $next($request);
    }
}
