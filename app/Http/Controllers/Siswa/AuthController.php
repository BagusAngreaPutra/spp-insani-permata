<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogAktivitas;
use App\Models\Siswa; // tambahkan model Siswa

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('siswa.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Ambil siswa berdasarkan username
        $siswa = Siswa::where('username', $request->username)->first();

        if ($siswa && $siswa->password_raw === $request->password) {
            // Login manual
            Auth::guard('siswa')->login($siswa);
            $request->session()->regenerate();

            // Catat log aktivitas
            LogAktivitas::create([
                'aktor_type' => 'siswa',
                'aktor_id'   => $siswa->id,
                'aktivitas'  => 'Login ke sistem',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->intended(route('siswa.dashboard'));
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        $idSiswa = Auth::guard('siswa')->id();

        Auth::guard('siswa')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        LogAktivitas::create([
            'aktor_type' => 'siswa',
            'aktor_id'   => $idSiswa,
            'aktivitas'  => 'Logout dari sistem',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('siswa.login');
    }
}
