<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\LogAktivitas;

class ProfileSiswaController extends Controller
{
    // ✅ Menampilkan profil siswa
    public function index(Request $request)
    {
        $siswa = auth('siswa')->user(); // data siswa login

        // 📌 Log aktivitas membuka profil
        LogAktivitas::create([
            'aktor_type' => 'siswa',
            'aktor_id'   => $siswa->id,
            'aktivitas'  => 'Membuka halaman profil',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('siswa.profil.index', compact('siswa'));
    }

    // ✅ Form ubah password
    public function editPassword(Request $request)
    {
        // 📌 Log aktivitas membuka form ubah password
        $siswa = auth('siswa')->user();
        LogAktivitas::create([
            'aktor_type' => 'siswa',
            'aktor_id'   => $siswa->id,
            'aktivitas'  => 'Membuka form ubah password',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('siswa.profil.edit-password');
    }

    // ✅ Proses ubah password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ]);

        $siswa = auth('siswa')->user();

        // cek password lama
        if (!Hash::check($request->current_password, $siswa->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        // update password
        $siswa->password = Hash::make($request->new_password);
        $siswa->save();

        // 📌 Log aktivitas update password
        LogAktivitas::create([
            'aktor_type' => 'siswa',
            'aktor_id'   => $siswa->id,
            'aktivitas'  => 'Mengubah password akun siswa',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('siswa.profil.index')->with('success', 'Password berhasil diubah.');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
