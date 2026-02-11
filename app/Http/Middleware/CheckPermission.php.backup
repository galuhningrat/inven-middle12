<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $module, string $action = 'read'): Response
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with([
                'alert_type' => 'danger',
                'message' => 'Silakan login terlebih dahulu.'
            ]);
        }

        $user = Auth::user();

        if ($user->id_role == 1) {
            return $next($request);
        }

        // Cek permission dari database untuk role lain
        $permission = DB::table('permissions')
            ->where('id_role', $user->id_role)
            ->where('module', $module)
            ->first();

        // Jika tidak ada permission untuk module ini
        if (!$permission) {
            abort(403, "Anda tidak memiliki akses ke modul: $module");
        }

        // Mapping action ke kolom database
        $actionMap = [
            'create' => 'can_create',
            'read' => 'can_read',
            'update' => 'can_update',
            'delete' => 'can_delete',
        ];

        // Validasi action yang diminta
        if (!isset($actionMap[$action])) {
            abort(403, "Action tidak valid: $action");
        }

        // Cek apakah user punya permission untuk action ini
        if (!$permission->{$actionMap[$action]}) {
            abort(403, "Anda tidak memiliki izin untuk '$action' pada modul '$module'");
        }

        return $next($request);
    }
}
