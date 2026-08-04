<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sekolah;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\LogAktivitas;
use App\Models\KoperasiPenjualan;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $filterRules = [
            'sekolah_id' => ['nullable', 'integer', 'exists:sekolah,id'],
            'kelas_id' => ['nullable', 'integer', 'exists:kelas,id'],
            'jenis_pembayaran' => ['nullable', 'in:sekolah,koperasi'],
            'search' => ['nullable', 'string', 'max:120'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ];

        if ($request->filled('start_date')) {
            $filterRules['end_date'][] = 'after_or_equal:start_date';
        }

        $filters = $request->validate($filterRules);

        $selectedSekolah = isset($filters['sekolah_id']) ? (int) $filters['sekolah_id'] : null;
        $selectedKelas = isset($filters['kelas_id']) ? (int) $filters['kelas_id'] : null;
        $selectedJenisPembayaran = $filters['jenis_pembayaran'] ?? null;
        $search = trim((string) ($filters['search'] ?? ''));
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;

        if ($selectedSekolah && $selectedKelas) {
            $classBelongsToSchool = Kelas::query()
                ->whereKey($selectedKelas)
                ->where('sekolah_id', $selectedSekolah)
                ->exists();

            if (!$classBelongsToSchool) {
                $selectedKelas = null;
            }
        }

        $sekolahList = Sekolah::query()
            ->orderBy('nama_sekolah')
            ->get();

        $kelasList = Kelas::query()
            ->with('sekolah')
            ->orderBy('sekolah_id')
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        return $this->indexWithKoperasi(
            $request,
            $sekolahList,
            $kelasList,
            $selectedSekolah,
            $selectedKelas,
            $selectedJenisPembayaran,
            $search,
            $startDate,
            $endDate
        );
    }

    private function indexWithKoperasi(
        Request $request,
        $sekolahList,
        $kelasList,
        $selectedSekolah,
        $selectedKelas,
        $selectedJenisPembayaran,
        $search,
        $startDate,
        $endDate
    ) {
        $transaksi = collect();

        if ($selectedJenisPembayaran !== 'koperasi') {
            $query = Pembayaran::with(['siswa.kelas', 'siswa.sekolah', 'tagihan'])
                ->latest('tanggal_bayar')
                ->latest('id');

            if ($selectedSekolah) {
                $query->whereHas('siswa', function ($q) use ($selectedSekolah) {
                    $q->where('id_sekolah', $selectedSekolah);
                });
            }

            if ($selectedKelas) {
                $query->whereHas('siswa', function ($q) use ($selectedKelas) {
                    $q->where('kelas_id', $selectedKelas);
                });
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('siswa', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%")
                            ->orWhere('nis', 'like', "%{$search}%");
                    })
                        ->orWhere('transaction_id', 'like', "%{$search}%")
                        ->orWhere('nomor_kwitansi', 'like', "%{$search}%")
                        ->orWhere('metode_bayar', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%")
                        ->orWhereHas('tagihan', function ($tagihanQuery) use ($search) {
                            $tagihanQuery->where('nama_tagihan', 'like', "%{$search}%")
                                ->orWhere('periode', 'like', "%{$search}%");
                        });
                });
            }

            $query
                ->when($startDate, fn ($paymentQuery) => $paymentQuery->whereDate('tanggal_bayar', '>=', $startDate))
                ->when($endDate, fn ($paymentQuery) => $paymentQuery->whereDate('tanggal_bayar', '<=', $endDate));

            $transaksi = $transaksi->merge($query->get()->groupBy(function ($item) {
                if (!empty($item->transaction_id)) {
                    return $item->transaction_id;
                }

                return $item->created_at->toDateTimeString() . '-' . $item->siswa_id;
            })->map(function ($group) {
                return [
                    'source_type' => 'sekolah',
                    'source_label' => 'Pembayaran Sekolah',
                    'items' => $group,
                    'total_bayar' => $group->sum('jumlah_bayar'),
                    'total_diskon' => $group->sum(function ($item) { return $item->diskon ?? 0; }),
                    'siswa' => $group->first()->siswa,
                    'tanggal_bayar' => $group->first()->tanggal_bayar,
                    'metode_bayar' => $group->first()->metode_bayar,
                    'keterangan' => $group->first()->keterangan,
                    'ids' => $group->pluck('id')->implode(','),
                    'transaction_id' => $group->first()->transaction_id,
                ];
            })->values());
        }

        if ($selectedJenisPembayaran !== 'sekolah') {
            $koperasiQuery = KoperasiPenjualan::with(['siswa.kelas', 'siswa.sekolah', 'details'])
                ->latest('tanggal')
                ->latest('id');

            if ($selectedSekolah) {
                $koperasiQuery->whereHas('siswa', function ($q) use ($selectedSekolah) {
                    $q->where('id_sekolah', $selectedSekolah);
                });
            }

            if ($selectedKelas) {
                $koperasiQuery->whereHas('siswa', function ($q) use ($selectedKelas) {
                    $q->where('kelas_id', $selectedKelas);
                });
            }

            if ($search) {
                $koperasiQuery->where(function ($q) use ($search) {
                    $q->where('kode_transaksi', 'like', "%{$search}%")
                        ->orWhere('catatan', 'like', "%{$search}%")
                        ->orWhereHas('siswa', function ($sq) use ($search) {
                            $sq->where('nama', 'like', "%{$search}%")
                                ->orWhere('nis', 'like', "%{$search}%");
                        })
                        ->orWhereHas('details', function ($dq) use ($search) {
                            $dq->where('nama_barang', 'like', "%{$search}%");
                        });
                });
            }

            $koperasiQuery
                ->when($startDate, fn ($salesQuery) => $salesQuery->whereDate('tanggal', '>=', $startDate))
                ->when($endDate, fn ($salesQuery) => $salesQuery->whereDate('tanggal', '<=', $endDate));

            $transaksi = $transaksi->merge($koperasiQuery->get()->map(function ($penjualan) {
                return [
                    'source_type' => 'koperasi',
                    'source_label' => 'Koperasi',
                    'items' => $penjualan->details,
                    'total_bayar' => $penjualan->total,
                    'total_diskon' => 0,
                    'siswa' => $penjualan->siswa,
                    'tanggal_bayar' => $penjualan->tanggal,
                    'metode_bayar' => 'Koperasi',
                    'keterangan' => $penjualan->catatan,
                    'ids' => (string) $penjualan->id,
                    'transaction_id' => $penjualan->kode_transaksi,
                ];
            }));
        }

        $transaksi = $transaksi
            ->sortByDesc(fn ($item) => Carbon::parse($item['tanggal_bayar']))
            ->values();

        $transactionSummary = [
            'total' => $transaksi->count(),
            'sekolah' => $transaksi->where('source_type', 'sekolah')->count(),
            'koperasi' => $transaksi->where('source_type', 'koperasi')->count(),
            'nominal' => (float) $transaksi->sum('total_bayar'),
        ];

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 20;
        $transaksi = new LengthAwarePaginator(
            $transaksi->forPage($currentPage, $perPage)->values(),
            $transactionSummary['total'],
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id' => Auth::id(),
            'aktivitas'  => 'Melihat halaman riwayat pembayaran',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('riwayat.index', compact(
            'transaksi',
            'sekolahList',
            'kelasList',
            'selectedSekolah',
            'selectedKelas',
            'selectedJenisPembayaran',
            'search',
            'startDate',
            'endDate',
            'transactionSummary'
        ));
    }

    public function riwayat(Request $request)
    {
        // Get all schools for dropdown
        $sekolah = Sekolah::all();
        $selectedSekolah = $request->input('sekolah_id');
        $selectedKelas = $request->input('kelas_id');
        $search = $request->input('search');

        // Get classes if school is selected
        $kelas = $selectedSekolah ? Kelas::where('sekolah_id', $selectedSekolah)->get() : collect();

        // Build the query with relationships
        $query = Pembayaran::with([
            'siswa.kelas',
            'siswa.sekolah',
            'tagihan.jenisPembayaran'
        ])->orderBy('tanggal_bayar', 'desc');

        // Apply school filter
        if ($selectedSekolah) {
            $query->whereHas('siswa', function ($q) use ($selectedSekolah) {
                $q->where('id_sekolah', $selectedSekolah);
            });
        }

        // Apply class filter
        if ($selectedKelas) {
            $query->whereHas('siswa', function ($q) use ($selectedKelas) {
                $q->where('kelas_id', $selectedKelas);
            });
        }

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('siswa', function ($sub) use ($search) {
                    $sub->where('nama', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                })
                ->orWhere('jumlah_bayar', 'like', "%{$search}%");
            });
        }

        // Execute query
        $riwayat = $query->get();

        // Log activity
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::id(),
            'aktivitas'  => 'Membuka halaman riwayat pembayaran lengkap',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Return view with all necessary data
        return view('riwayat.index', compact(
            'riwayat',
            'sekolah',
            'kelas',
            'selectedSekolah',
            'selectedKelas',
            'search'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id'      => 'required|exists:siswa,id',
            'tagihan_id'    => 'required|exists:tagihan,id',
            'jumlah_bayar'  => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
            'metode_bayar'  => 'nullable|string|max:50',
            'keterangan'    => 'nullable|string',
            'bukti_bayar'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $tagihan = Tagihan::findOrFail($request->tagihan_id);

        // Hitung total cicilan dan cicilan ke
        $existingCicilan = Pembayaran::where('tagihan_id', $tagihan->id)->count();
        $cicilan_ke = $existingCicilan + 1;
        $total_cicilan = $cicilan_ke;

        // Upload bukti bayar jika ada
        $buktiPath = null;
        if ($request->hasFile('bukti_bayar')) {
            $buktiPath = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        }

        $pembayaran = Pembayaran::create([
            'sekolah_id'     => $tagihan->siswa->id_sekolah,
            'tagihan_id'     => $tagihan->id,
            'siswa_id'       => $tagihan->siswa_id,
            'jumlah_bayar'   => $request->jumlah_bayar,
            'tanggal_bayar'  => $request->tanggal_bayar,
            'periode'        => $tagihan->periode,
            'periode_tahun'  => $tagihan->periode_tahun,
            'metode_bayar'   => $request->input('metode_bayar'),
            'cicilan_ke'     => $cicilan_ke,
            'total_cicilan'  => $total_cicilan,
            'keterangan'     => $request->input('keterangan'),
            'bukti_bayar'    => $buktiPath,
        ]);

        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::id(),
            'aktivitas'  => 'Menambahkan pembayaran untuk siswa: ' . $pembayaran->siswa->nama,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    // Tambahan opsional bila ingin menghitung jumlah uang masuk dari cicilan
    private function hitungTotalCicilan($tagihan_id)
    {
        return Pembayaran::where('tagihan_id', $tagihan_id)->sum('jumlah_bayar');
    }

    public function cetakKwitansi($id)
    {
        $pembayaran = Pembayaran::with([
            'siswa' => function($query) {
                $query->with(['kelas', 'sekolah']);
            },
            'tagihan' => function($query) {
                $query->with('jenisPembayaran');
            }
        ])->findOrFail($id);

        $tagihan = $pembayaran->tagihan;
        $billName = $tagihan
            ? convertBulanToIndonesia($tagihan->nama_tagihan)
            : 'Pembayaran';

        $receipt = [
            'student' => $pembayaran->siswa,
            'number' => $pembayaran->nomor_kwitansi ?? str_pad((string) $pembayaran->id, 6, '0', STR_PAD_LEFT),
            'date' => $pembayaran->tanggal_bayar,
            'method' => $this->paymentMethodLabel($pembayaran->metode_bayar),
            'note' => $pembayaran->keterangan,
            'items' => [[
                'name' => $billName,
                'amount' => (float) $pembayaran->jumlah_bayar,
                'discount' => (float) $pembayaran->diskon,
            ]],
            'total_paid' => (float) $pembayaran->jumlah_bayar,
            'total_discount' => (float) $pembayaran->diskon,
        ];

        return view('tagihan.kwitansi.receipt', compact('receipt'));
    }

    public function cetakKwitansiGrup(Request $request)
    {
        $ids = array_values(array_filter(explode(',', (string) $request->query('ids'))));

        if (empty($ids)) {
            return back()->with('error', 'Tidak ada data pembayaran yang dipilih.');
        }

        $pembayaranItems = Pembayaran::with(['siswa.kelas', 'siswa.sekolah', 'tagihan'])
            ->whereIn('id', $ids)
            ->get();

        if ($pembayaranItems->isEmpty()) {
            return back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $firstItem = $pembayaranItems->first();
        $receipt = [
            'student' => $firstItem->siswa,
            'number' => $firstItem->nomor_kwitansi ?? str_pad((string) $firstItem->id, 6, '0', STR_PAD_LEFT),
            'date' => $firstItem->tanggal_bayar,
            'method' => $this->paymentMethodLabel($firstItem->metode_bayar),
            'note' => $firstItem->keterangan,
            'items' => $pembayaranItems->map(function (Pembayaran $item) {
                return [
                    'name' => convertBulanToIndonesia(optional($item->tagihan)->nama_tagihan ?? 'Tagihan dihapus'),
                    'amount' => (float) $item->jumlah_bayar,
                    'discount' => (float) $item->diskon,
                ];
            })->all(),
            'total_paid' => (float) $pembayaranItems->sum('jumlah_bayar'),
            'total_discount' => (float) $pembayaranItems->sum('diskon'),
        ];

        return view('tagihan.kwitansi.receipt', compact('receipt'));
    }

    private function paymentMethodLabel(?string $method): string
    {
        return match ($method) {
            'tunai' => 'Tunai',
            'transfer' => 'Transfer bank',
            'kjc' => 'KJC',
            'tabungan' => 'Potongan tabungan',
            default => ucfirst((string) $method) ?: '-',
        };
    }
}
