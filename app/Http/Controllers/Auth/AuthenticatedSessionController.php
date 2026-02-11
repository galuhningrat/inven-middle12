<?php

namespace App\Http\Controllers\Auth;

use App\Models\LoginHistory;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Stevebauman\Location\Facades\Location;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('login');
    }

 public function store(LoginRequest $request): RedirectResponse
{
    // Ambil user berdasarkan email
    $user = User::where('email', $request->email)->first();

    // Jika user ditemukan, cek apakah sedang login di perangkat lain
    if (!$user) {
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // Cek status sebelum Auth::attempt()
    if ($user->status !== 'Aktif') {
        return back()->withErrors([
            'email' => 'Akun Anda tidak aktif. Silakan hubungi BAAK.',
        ]);
    }

    // Cek login di perangkat lain
    $activeSession = DB::table('sessions')
        ->where('user_id', $user->id)
        ->where('id', '!=', session()->getId())
        ->exists();

    if ($activeSession) {
        return back()->withErrors([
            'email' => 'Akun ini sedang digunakan di perangkat lain.',
        ]);
    }

    // Lanjut login hanya jika status aktif
    if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // Regenerate session setelah login sukses
    $request->session()->regenerate();

    // Update kolom last_login di tabel users
    auth()->user()->update([
        'last_login' => now()
    ]);

    // Ambil IP address
    $ipAddress = $request->ip();

    // Ambil lokasi berdasarkan IP
    $locationData = Location::get($ipAddress);
    $location = $locationData
        ? $locationData->city . ', ' . $locationData->regionName . ', ' . $locationData->countryName
        : 'Unknown';

    // PENTING: Save session dulu SEBELUM ambil session ID
    $request->session()->save();

    // Tunggu sebentar untuk memastikan session ter-save ke database
    usleep(100000); // 100ms delay

    $sessionId = session()->getId();

    // Simpan riwayat login dengan try-catch untuk handle foreign key error
    try {
        LoginHistory::create([
            'id_users'    => auth()->id(),
            'id_sessions' => $sessionId,
            'ip_address'  => $ipAddress,
            'location'    => $location,
            'login_time'  => now(),
            'user_agent'  => $request->header('User-Agent'),
            'status'      => 'Sukses',
        ]);
    } catch (\Exception $e) {
        // Jika foreign key error, coba tanpa id_sessions
        LoginHistory::create([
            'id_users'    => auth()->id(),
            'id_sessions' => null, // Set null jika error
            'ip_address'  => $ipAddress,
            'location'    => $location,
            'login_time'  => now(),
            'user_agent'  => $request->header('User-Agent'),
            'status'      => 'Sukses',
        ]);

        // Log error untuk debugging (optional)
        \Log::warning('Failed to save session ID in login history: ' . $e->getMessage());
    }

    return redirect()->intended(route('dashboard'))
        ->with('sukses', 'Berhasil login!');
}

    public function destroy(Request $request): RedirectResponse
    {
        $sessionId = session()->getId();

        // Update waktu logout di login_history
        LoginHistory::where('id_sessions', $sessionId)
            ->whereNull('logout_time')
            ->update([
                'logout_time' => now(),
            ]);

        // Hapus session dari DB agar user langsung dianggap logout
        DB::table('sessions')->where('id', $sessionId)->delete();

        // Logout Laravel
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
