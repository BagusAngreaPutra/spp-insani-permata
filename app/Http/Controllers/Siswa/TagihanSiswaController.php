<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use Illuminate\Support\Facades\Auth;
use App\Services\TagihanPeriodReconciler;
use App\Models\LogAktivitas; // ✅ tambahkan import

class TagihanSiswaController extends Controller
{
    public function index(Request $request)
    {
        // ✅ Ambil data siswa yang sedang login
        $siswa = Auth::guard('siswa')->user();

        // ✅ Catat log aktivitas siswa
        LogAktivitas::create([
            'aktor_type' => 'siswa',
            'aktor_id'   => $siswa->id,
            'aktivitas'  => 'Membuka halaman daftar Tagihan Siswa',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // ✅ Ambil tagihan hanya milik siswa ini
        $periodReconciler = app(TagihanPeriodReconciler::class);
        $tagihanSaya = Tagihan::with(['jenisPembayaran.tahunAjaran', 'pembayaran'])
                        ->where('siswa_id', $siswa->id)
                        ->orderBy('tanggal_jatuh_tempo', 'asc')
                        ->get()
                        ->filter(fn (Tagihan $tagihan) =>
                            $periodReconciler->belongsToConfiguredPeriod($tagihan)
                        );

        // ✅ Kirim ke view
        return view('siswa.tagihan.index', [
            'siswa' => $siswa,
            'tagihanSaya' => $tagihanSaya,
        ]);
    }
}
