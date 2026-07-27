<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Tagihan;
use App\Models\Pembayaran;


class DashboardController extends Controller
{
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();

        // Total seluruh tagihan untuk siswa ini
        $totalTagihan = Tagihan::where('siswa_id', $siswa->id)->sum('nominal');

        // Total seluruh pembayaran yang telah dilakukan siswa
        $totalPembayaran = Pembayaran::where('siswa_id', $siswa->id)->sum('jumlah_bayar');

        // Tagihan yang statusnya belum lunas
        $tagihanAktif = Tagihan::where('siswa_id', $siswa->id)
                            ->where('status', 'belum lunas')
                            ->get();

        // Riwayat pembayaran terakhir (5 terbaru)
        $riwayatPembayaran = Pembayaran::where('siswa_id', $siswa->id)
                                ->latest()
                                ->take(5)
                                ->get();

        return view('siswa.dashboard', [
            'total_tagihan'      => $totalTagihan,
            'total_pembayaran'   => $totalPembayaran,
            'tagihan_aktif'      => $tagihanAktif,
            'riwayat_pembayaran' => $riwayatPembayaran,
        ]);
    }
}
