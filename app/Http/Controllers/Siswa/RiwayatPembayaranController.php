<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\KoperasiPenjualan;
use App\Models\Sekolah;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use App\Models\LogAktivitas;
use Illuminate\Pagination\LengthAwarePaginator;

class RiwayatPembayaranController extends Controller
{
    public function index(Request $request)
    {
        // ✅ Pastikan guard siswa
        $siswa = Auth::guard('siswa')->user();

        // ✅ Catat log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'siswa',
            'aktor_id'   => $siswa->id,
            'aktivitas'  => 'Membuka halaman Riwayat Pembayaran',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Get filter parameters
        $search = $request->input('search');
        $kelasId = $request->input('kelas_id');
        $sekolahId = $request->input('sekolah_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $jenisPembayaran = $request->input('jenis_pembayaran');

        // ✅ Ambil pembayaran berdasarkan siswa yang login dengan filter
        return $this->indexWithKoperasi($request, $siswa, $search, $kelasId, $sekolahId, $startDate, $endDate, $jenisPembayaran);

        $query = Pembayaran::with(['tagihan', 'siswa.kelas', 'siswa.sekolah'])
            ->where('siswa_id', $siswa->id);

        // Apply search filter (nama or nis)
        if ($search) {
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Apply class filter
        if ($kelasId) {
            $query->whereHas('siswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        // Apply school filter
        if ($sekolahId) {
            $query->whereHas('siswa', function ($q) use ($sekolahId) {
                $q->where('id_sekolah', $sekolahId);
            });
        }

        // Apply date range filter
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_bayar', [$startDate, $endDate]);
        }

        $riwayatPembayaran = $query->orderBy('tanggal_bayar', 'desc')
            ->paginate(10)
            ->appends($request->except('page'));

        // Get data for filters
        $sekolahList = Sekolah::all();
        $kelasList = Kelas::all();

        return view('siswa.riwayat.index', compact('riwayatPembayaran', 'sekolahList', 'kelasList', 'search', 'kelasId', 'sekolahId', 'startDate', 'endDate'));
    }

    private function indexWithKoperasi(Request $request, $siswa, $search, $kelasId, $sekolahId, $startDate, $endDate, $jenisPembayaran)
    {
        $rows = collect();

        if ($jenisPembayaran !== 'koperasi') {
            $query = Pembayaran::with(['tagihan', 'siswa.kelas', 'siswa.sekolah'])
                ->where('siswa_id', $siswa->id);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('siswa', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%")
                            ->orWhere('nis', 'like', "%{$search}%");
                    })->orWhereHas('tagihan', function ($tq) use ($search) {
                        $tq->where('nama_tagihan', 'like', "%{$search}%");
                    })->orWhere('keterangan', 'like', "%{$search}%");
                });
            }

            if ($kelasId) {
                $query->whereHas('siswa', function ($q) use ($kelasId) {
                    $q->where('kelas_id', $kelasId);
                });
            }

            if ($sekolahId) {
                $query->whereHas('siswa', function ($q) use ($sekolahId) {
                    $q->where('id_sekolah', $sekolahId);
                });
            }

            if ($startDate && $endDate) {
                $query->whereBetween('tanggal_bayar', [$startDate, $endDate]);
            }

            $rows = $rows->merge($query->get()->map(function ($bayar) {
                return [
                    'source_type' => 'sekolah',
                    'source_label' => 'Pembayaran Sekolah',
                    'nama' => $bayar->tagihan?->nama_tagihan_dinamis ?? 'Tagihan #' . $bayar->tagihan_id,
                    'jumlah_bayar' => $bayar->jumlah_bayar,
                    'tanggal_bayar' => $bayar->tanggal_bayar,
                    'metode_bayar' => $bayar->metode_bayar,
                    'cicilan_ke' => $bayar->cicilan_ke,
                    'total_cicilan' => $bayar->total_cicilan,
                    'sisa_cicilan' => $bayar->total_cicilan ? $bayar->sisa_cicilan : null,
                    'siswa' => $bayar->siswa,
                    'keterangan' => $bayar->keterangan,
                ];
            }));
        }

        if ($jenisPembayaran !== 'sekolah') {
            $koperasiQuery = KoperasiPenjualan::with(['siswa.kelas', 'siswa.sekolah', 'details'])
                ->where('siswa_id', $siswa->id);

            if ($search) {
                $koperasiQuery->where(function ($q) use ($search) {
                    $q->where('kode_transaksi', 'like', "%{$search}%")
                        ->orWhere('catatan', 'like', "%{$search}%")
                        ->orWhereHas('details', function ($dq) use ($search) {
                            $dq->where('nama_barang', 'like', "%{$search}%");
                        });
                });
            }

            if ($kelasId) {
                $koperasiQuery->whereHas('siswa', function ($q) use ($kelasId) {
                    $q->where('kelas_id', $kelasId);
                });
            }

            if ($sekolahId) {
                $koperasiQuery->whereHas('siswa', function ($q) use ($sekolahId) {
                    $q->where('id_sekolah', $sekolahId);
                });
            }

            if ($startDate && $endDate) {
                $koperasiQuery->whereBetween('tanggal', [$startDate, $endDate]);
            }

            $rows = $rows->merge($koperasiQuery->get()->map(function ($penjualan) {
                return [
                    'source_type' => 'koperasi',
                    'source_label' => 'Koperasi',
                    'nama' => 'Koperasi - ' . $penjualan->kode_transaksi,
                    'jumlah_bayar' => $penjualan->total,
                    'tanggal_bayar' => $penjualan->tanggal,
                    'metode_bayar' => 'Koperasi',
                    'cicilan_ke' => null,
                    'total_cicilan' => null,
                    'sisa_cicilan' => null,
                    'siswa' => $penjualan->siswa,
                    'keterangan' => $penjualan->catatan ?: $penjualan->details->pluck('nama_barang')->implode(', '),
                ];
            }));
        }

        $rows = $rows->sortByDesc('tanggal_bayar')->values();
        $perPage = 10;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $riwayatPembayaran = new LengthAwarePaginator(
            $rows->forPage($page, $perPage)->values(),
            $rows->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $sekolahList = Sekolah::all();
        $kelasList = Kelas::all();

        return view('siswa.riwayat.index', compact(
            'riwayatPembayaran',
            'sekolahList',
            'kelasList',
            'search',
            'kelasId',
            'sekolahId',
            'startDate',
            'endDate',
            'jenisPembayaran'
        ));
    }
}
