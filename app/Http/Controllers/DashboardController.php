<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\JenisPembayaran;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\LogAktivitas;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\TahunAjaran;
use App\Models\KoperasiPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ✅ Catat log aktivitas membuka dashboard
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::id(),
            'aktivitas'  => 'Mengakses halaman dashboard',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        // Ambil parameter filter dari request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $tahun = $request->input('tahun');

        $sekolahList = Sekolah::all();
        $hasKoperasiSales = Schema::hasTable('koperasi_penjualan');
        $data = [];
        $setupStats = [
            'sekolah' => Sekolah::count(),
            'tahun_ajaran' => TahunAjaran::count(),
            'kelas' => Kelas::count(),
            'siswa' => Siswa::count(),
            'jenis_pembayaran' => JenisPembayaran::count(),
            'tagihan' => Tagihan::count(),
            'pembayaran' => Pembayaran::count(),
        ];

        foreach ($sekolahList as $sekolah) {
            $jumlahSiswa = Siswa::where('id_sekolah', $sekolah->id)->count();
            $jumlahKelas = Kelas::where('sekolah_id', $sekolah->id)->count();

            // ✅ Total dari pembayaran siswa
            $pembayaranQuery = Pembayaran::whereHas('siswa', function ($query) use ($sekolah) {
                $query->where('id_sekolah', $sekolah->id);
            });

            // ✅ Total dari tabel pemasukan (manual)
            $pemasukanQuery = Pemasukan::where('sekolah_id', $sekolah->id);
            $koperasiQuery = $hasKoperasiSales
                ? KoperasiPenjualan::where('sekolah_id', $sekolah->id)
                : null;

            // ✅ Total pengeluaran dari tabel pengeluaran
            $pengeluaranQuery = Pengeluaran::where('sekolah_id', $sekolah->id);

            // Terapkan filter berdasarkan parameter yang diberikan
            if ($tahun) {
                $pembayaranQuery->whereYear('tanggal_bayar', $tahun);
                $pemasukanQuery->whereYear('tanggal', $tahun);
                $koperasiQuery?->whereYear('tanggal', $tahun);
                $pengeluaranQuery->whereYear('tanggal', $tahun);
            } elseif ($startDate && $endDate) {
                // Filter berdasarkan rentang tanggal
                $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
                
                $pembayaranQuery->whereBetween('tanggal_bayar', [$start, $end]);
                $pemasukanQuery->whereBetween('tanggal', [$start, $end]);
                $koperasiQuery?->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()]);
                $pengeluaranQuery->whereBetween('tanggal', [$start, $end]);
            }

            $totalPembayaranSiswa = $pembayaranQuery->sum('jumlah_bayar');
            $totalPemasukanManual = $pemasukanQuery->sum('jumlah');
            $totalPenjualanKoperasi = $koperasiQuery?->sum('total') ?? 0;
            $totalPengeluaran = $pengeluaranQuery->sum('jumlah');

            // ✅ Total pemasukan = dari pembayaran siswa + dari tabel pemasukan
            $totalPemasukan = $totalPembayaranSiswa + $totalPemasukanManual + $totalPenjualanKoperasi;

            // ✅ Saldo kas = pemasukan - pengeluaran
            $saldoKas = $totalPemasukan - $totalPengeluaran;

            $data[] = [
                'id'           => $sekolah->id,
                'nama'         => $sekolah->nama_sekolah,
                'jenjang'      => strtoupper($sekolah->jenjang),
                'jumlah_siswa' => $jumlahSiswa,
                'jumlah_kelas' => $jumlahKelas,
                'pemasukan'    => $totalPemasukan,
                'pemasukan_koperasi' => $totalPenjualanKoperasi,
                'pengeluaran'  => $totalPengeluaran,
                'saldo_kas'    => $saldoKas,
            ];
        }

        return view('dashboard', compact('data', 'setupStats', 'startDate', 'endDate', 'tahun'));
    }
}
