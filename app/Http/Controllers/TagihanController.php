<?php
namespace App\Http\Controllers;
use App\Models\Tagihan;
use App\Models\Siswa;
use App\Models\JenisPembayaran;
use App\Models\Pembayaran;
use App\Models\LogAktivitas; // 👉 tambahkan
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Sekolah;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;

class TagihanController extends Controller
{

    public function index(Request $request)
    {
        // Redirect to the new grouped view
        return redirect()->route('tagihan.index.grouped');
    }

    /**
     * Show the original tagihan list view
     */
    public function indexOriginal(Request $request)
    {
        return redirect()->route('tagihan.index.grouped', $request->query());
    }




    /**
     * Method untuk sinkronisasi nama tagihan dengan jenis pembayaran
     * Panggil ini jika ada perubahan nama di jenis_pembayaran
     */
    public function sinkronisasiNama(Request $request)
    {
        $tagihan = Tagihan::whereNotNull('jenis_pembayaran_id')
            ->with('jenisPembayaran')
            ->get();

        foreach ($tagihan as $t) {
            if ($t->jenisPembayaran) {
                $t->update([
                    'nama_tagihan' => $t->jenisPembayaran->nama_pembayaran
                ]);
            }
        }

        // ✅ log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Melakukan sinkronisasi nama tagihan',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()
            ->route('tagihan.index')
            ->with('success', 'Nama tagihan berhasil disinkronisasi!');
    }

    public function bayar(Request $request, Tagihan $tagihan)
    {
        $pembayaranHistory = $tagihan->pembayaran()->orderBy('cicilan_ke')->get();
        $totalBayar = $pembayaranHistory->sum('jumlah_bayar');
        $sisaBayar = $tagihan->nominal - $totalBayar;
        
        // Hitung cicilan untuk tipe bulanan
        $maxCicilan = $pembayaranHistory->max('cicilan_ke') ?? 0;
        $cicilanSelanjutnya = $maxCicilan + 1;
        
        // Tentukan berapa cicilan yang tersisa
        $totalCicilanDirencanakan = $this->hitungTotalCicilan($tagihan);
        $sisaCicilan = max(0, $totalCicilanDirencanakan - $maxCicilan);

        // ✅ log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Membuka halaman bayar tagihan: ' . $tagihan->nama_tagihan,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('tagihan.bayar', compact(
            'tagihan', 
            'pembayaranHistory', 
            'totalBayar', 
            'sisaBayar', 
            'cicilanSelanjutnya', 
            'sisaCicilan',
            'totalCicilanDirencanakan'
        ));
    }

    public function storePembayaran(Request $request, Tagihan $tagihan)
    {
        // ✅ Mode pembayaran bulanan
        if ($tagihan->tipe === 'bulanan' && $request->has('bulan')) {
            $errors = [];

            // minimal satu bulan harus dicentang
            $bulanDipilih = false;

            foreach ($request->input('bulan', []) as $index => $item) {
                // jika checkbox bulan ini dicentang
                if (!empty($item['periode'])) {
                    $bulanDipilih = true;

                    // cek tanggal_bayar
                    if (empty($item['tanggal_bayar'])) {
                        $errors["bulan.$index.tanggal_bayar"] = "Tanggal bayar wajib diisi untuk bulan ke-$index.";
                    }
                }
            }

            // jika tidak ada bulan yang dipilih
            if (!$bulanDipilih) {
                $errors['bulan'] = 'Silakan pilih minimal satu bulan untuk dibayar.';
            }

            // kalau ada error, balikin ke view
            if (!empty($errors)) {
                return back()->withErrors($errors)->withInput();
            }

            // simpan tiap bulan yang dipilih
            foreach ($request->input('bulan', []) as $item) {
                if (!empty($item['periode']) && !empty($item['tanggal_bayar'])) {
                    // cek apakah periode ini sudah ada pembayaran
                    $sudahAda = Pembayaran::where('tagihan_id', $tagihan->id)
                        ->where('periode', $item['periode'])
                        ->exists();

                    if (!$sudahAda) {
                        Pembayaran::create([
                            'tagihan_id'   => $tagihan->id,
                            'siswa_id'     => $tagihan->siswa_id,
                            'jumlah_bayar' => $tagihan->nominal,
                            'tanggal_bayar'=> $item['tanggal_bayar'],
                            'periode'      => $item['periode'],
                            'metode_bayar' => 'tunai', // bisa disesuaikan dari input jika ada
                            'keterangan'   => 'Bayar bulan '.$item['periode'],
                        ]);
                    }
                }
            }

            // ✅ log aktivitas
            LogAktivitas::create([
                'aktor_type' => 'admin',
                'aktor_id'   => Auth::guard('web')->id(),
                'aktivitas'  => 'Menyimpan pembayaran bulanan untuk tagihan: ' . $tagihan->nama_tagihan,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()
                ->route('tagihan.bayar', $tagihan)
                ->with('success', 'Pembayaran per bulan berhasil disimpan!');
        }

        // ✅ Mode pembayaran biasa (non-bulanan)
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
            'metode_bayar' => 'required|in:tunai,transfer,kjc,tabungan',
            'keterangan' => 'nullable|string|max:500',
            'total_cicilan' => 'nullable|integer|min:1',
            'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $totalBayarSebelumnya = $tagihan->pembayaran()->sum('jumlah_bayar');
        $sisaBayar = $tagihan->nominal - $totalBayarSebelumnya;

        // Validasi jumlah bayar tidak melebihi sisa tagihan
        if ($request->jumlah_bayar > $sisaBayar) {
            return back()->withErrors([
                'jumlah_bayar' => 'Jumlah bayar tidak boleh melebihi sisa tagihan: Rp ' . number_format($sisaBayar, 0, ',', '.')
            ])->withInput();
        }

        // Tentukan cicilan ke berapa
        $cicilanTerakhir = $tagihan->pembayaran()->max('cicilan_ke') ?? 0;
        $cicilanKe = $cicilanTerakhir + 1;

        // Tentukan total cicilan
        $totalCicilan = $this->hitungTotalCicilan($tagihan);
        if ($request->filled('total_cicilan') && $tagihan->tipe === 'bulanan') {
            $totalCicilan = $request->total_cicilan;
        }

        // Handle upload bukti bayar
        $buktiBayar = null;
        if ($request->hasFile('bukti_bayar')) {
            $buktiBayar = $request->file('bukti_bayar')->store('bukti-bayar', 'public');
        }

        // Simpan pembayaran sekali
        Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'siswa_id' => $tagihan->siswa_id,
            'jumlah_bayar' => $request->jumlah_bayar,
            'tanggal_bayar' => $request->tanggal_bayar,
            'keterangan' => $request->keterangan,
            'cicilan_ke' => $cicilanKe,
            'total_cicilan' => $totalCicilan,
            'metode_bayar' => $request->metode_bayar,
            'bukti_bayar' => $buktiBayar
        ]);

        // Update status tagihan jika sudah lunas
        $totalBayarSekarang = $tagihan->pembayaran()->sum('jumlah_bayar');
        if ($totalBayarSekarang >= $tagihan->nominal) {
            $tagihan->update(['status' => 'lunas']);
        }

        // ✅ log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Menyimpan pembayaran tagihan: ' . $tagihan->nama_tagihan . ' sebesar Rp ' . number_format($request->jumlah_bayar, 0, ',', '.'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()
            ->route('tagihan.bayar', $tagihan)
            ->with('success', 'Pembayaran berhasil disimpan!');
    }

    private function hitungTotalCicilan(Tagihan $tagihan): int
    {
        switch ($tagihan->tipe) {
            case 'sekali':
                return 1;
            case 'bulanan':
                // Default 12 bulan dalam setahun, bisa disesuaikan
                return 12;
            case 'setahun':
                return 2; // 2 semester dalam setahun
            default:
                return 1;
        }
    }

    public function destroy(Request $request, Tagihan $tagihan)
    {
        $namaTagihan = $tagihan->nama_tagihan;
        $tagihan->delete();

        // ✅ log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Menghapus tagihan: ' . $namaTagihan,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('tagihan.index')->with('success','Tagihan berhasil dihapus!');
    }
    
    public function storePembayaranBulan(Request $request, $tagihanId)
    {
        $tagihan = Tagihan::findOrFail($tagihanId);

        foreach ($request->input('bulan', []) as $bulanData) {
            if (!empty($bulanData['periode'])) {
                // cek dulu apakah sudah pernah dibayar
                $sudahAda = Pembayaran::where('tagihan_id', $tagihanId)
                            ->where('periode', $bulanData['periode'])
                            ->exists();
                if (!$sudahAda) {
                    Pembayaran::create([
                        'tagihan_id'    => $tagihanId,
                        'siswa_id'      => $tagihan->siswa_id,
                        'jumlah_bayar'  => $tagihan->nominal,
                        'tanggal_bayar' => $bulanData['tanggal_bayar'] ?? now(),
                        'periode'       => $bulanData['periode'],
                        'metode_bayar'  => 'tunai',
                        'keterangan'    => 'Bayar bulan '.$bulanData['periode'],
                        'cicilan_ke'    => ($tagihan->pembayaran()->max('cicilan_ke') ?? 0) + 1,
                        'total_cicilan' => 12, // kalau memang 12 bulan
                    ]);
                }
            }
        }

        // ✅ Cek jumlah periode unik yang sudah dibayar
        $jumlahPeriodeBayar = $tagihan->pembayaran()->distinct('periode')->count('periode');

        // Jika sudah semua bulan (contoh: 12 bulan), update status ke lunas
        if ($jumlahPeriodeBayar >= 12) {
            $tagihan->update(['status' => 'lunas']);
        }

        // ✅ log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Menyimpan pembayaran bulanan untuk tagihan: ' . $tagihan->nama_tagihan,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success','Pembayaran bulanan berhasil disimpan!');
    }

    public function bayarSekali(Request $request, $id)
    {
        $tagihan = Tagihan::with(['siswa','jenisPembayaran'])->findOrFail($id);

        // Pastikan ini memang tipe sekali
        if ($tagihan->tipe !== 'sekali') {
            abort(404, 'Tagihan ini bukan tipe sekali bayar.');
        }

        // Ambil histori pembayaran untuk tagihan ini
        $pembayaranHistory = \App\Models\Pembayaran::where('tagihan_id', $id)
            ->orderBy('tanggal_bayar', 'asc')
            ->get();

        $totalBayar = $pembayaranHistory->sum('jumlah_bayar');

        // Hitung sisa bayar
        $totalPembayaran = $tagihan->nominal; // hanya sekali
        $sisaBayar = $totalPembayaran - $totalBayar;

        // ✅ log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Membuka halaman bayar sekali untuk tagihan: ' . $tagihan->nama_tagihan,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('tagihan.bayar-sekalibayar', compact(
            'tagihan',
            'pembayaranHistory',
            'totalBayar',
            'sisaBayar'
        ));
    }


    public function storePembayaranSekali(Request $request, $id)
    {
        $request->validate([
            'tanggal_bayar' => 'required|date',
            'keterangan'    => 'nullable|string|max:255',
        ]);

        $tagihan = Tagihan::findOrFail($id);

        // Pastikan tipe sekali
        if ($tagihan->tipe !== 'sekali') {
            abort(404, 'Tagihan ini bukan tipe sekali bayar.');
        }

        // Nominal selalu diambil dari tagihan
        $jumlahBayar = $tagihan->nominal;

        // Simpan pembayaran
        $pembayaran = new \App\Models\Pembayaran();
        $pembayaran->siswa_id     = $tagihan->siswa_id;
        $pembayaran->tagihan_id   = $tagihan->id;
        $pembayaran->metode_bayar = 'tunai';
        $pembayaran->tanggal_bayar = $request->tanggal_bayar;
        $pembayaran->jumlah_bayar = $jumlahBayar;
        $pembayaran->keterangan   = $request->keterangan;
        $pembayaran->save();

        // Hitung total bayar dan update status
        $totalBayar = \App\Models\Pembayaran::where('tagihan_id', $id)->sum('jumlah_bayar');
        if ($totalBayar >= $tagihan->nominal) {
            $tagihan->status = 'lunas';
            $tagihan->save();
        }

        // ✅ log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Menyimpan pembayaran sekali untuk tagihan: ' . $tagihan->nama_tagihan . ' sebesar Rp ' . number_format($jumlahBayar, 0, ',', '.'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()
            ->route('tagihan.bayarSekali', $tagihan->id)
            ->with('success', 'Pembayaran berhasil disimpan.');
    }

    // 👉 tampilkan halaman bayar tahunan
    public function bayarTahunan(Request $request, Tagihan $tagihan)
    {
        // Hitung total bayar sebelumnya
        $totalBayar = $tagihan->pembayaran()->sum('jumlah_bayar');
        $sisaBayar = $tagihan->nominal - $totalBayar;

        // Ambil aka pembayaran tahunan (filter kalau kamu pakai kolom periode_tahun atau tipe tertentu)
        $pembayaranHistory = $tagihan->pembayaran()
            ->orderBy('tanggal_bayar', 'asc')
            ->get();

        // ✅ log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Membuka halaman bayar tahunan untuk tagihan: ' . $tagihan->nama_tagihan,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('tagihan.bayar-tahunan', compact('tagihan','totalBayar','sisaBayar','pembayaranHistory'));
    }

   // 👉 proses simpan pembayaran tahunan
    public function storePembayaranTahunan(Request $request, Tagihan $tagihan)
    {
        // ✅ Validasi input
        $request->validate([
            'tanggal_bayar' => 'required|date',
            'metode_bayar'  => 'required|in:tunai,transfer,kjc,tabungan',
            'keterangan'    => 'nullable|string|max:500',
            'bukti_bayar'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'tanggal_bayar.required' => 'Tanggal bayar wajib diisi.',
            'metode_bayar.required'  => 'Metode bayar wajib dipilih.',
        ]);

        // ✅ Ambil tahun dari tanggal_bayar
        $periodeTahun = date('Y', strtotime($request->tanggal_bayar));

        // ✅ Cek apakah sudah ada pembayaran untuk tahun ini
        $sudahAda = $tagihan->pembayaran()
            ->where('periode_tahun', $periodeTahun)
            ->exists();
        if ($sudahAda) {
            return back()->withErrors([
                'tanggal_bayar' => 'Tahun ' . $periodeTahun . ' sudah dibayar sebelumnya.'
            ]);
        }

        // ✅ Hitung total bayar sebelumnya
        $totalBayarSebelumnya = $tagihan->pembayaran()->sum('jumlah_bayar');
        $sisaBayar = $tagihan->nominal - $totalBayarSebelumnya;

        // ✅ Jika sudah lunas, jangan terima pembayaran lagi
        if ($sisaBayar <= 0) {
            return back()->withErrors([
                'tanggal_bayar' => 'Tagihan ini sudah lunas.'
            ]);
        }

        // ✅ Handle upload bukti bayar
        $buktiBayar = null;
        if ($request->hasFile('bukti_bayar')) {
            $buktiBayar = $request->file('bukti_bayar')->store('bukti-bayar', 'public');
        }

        // ✅ Nominal pembayaran tahunan diambil dari tagihan (per tahun)
        $jumlahBayar = $tagihan->nominal;

        // ✅ Simpan pembayaran tahunan
        \App\Models\Pembayaran::create([
            'tagihan_id'    => $tagihan->id,
            'siswa_id'      => $tagihan->siswa_id,
            'jumlah_bayar'  => $jumlahBayar,
            'tanggal_bayar' => $request->tanggal_bayar,
            'periode_tahun' => $periodeTahun,
            'metode_bayar'  => $request->metode_bayar,
            'keterangan'    => $request->keterangan,
            'bukti_bayar'   => $buktiBayar,
        ]);

        // ✅ Update status tagihan jika total bayar sudah memenuhi
        $totalBayarSekarang = $tagihan->pembayaran()->sum('jumlah_bayar');
        if ($totalBayarSekarang >= $tagihan->nominal) {
            $tagihan->update(['status' => 'lunas']);
        }

        // 📌 Log aktivitas simpan pembayaran tahunan
        \App\Models\LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => \Illuminate\Support\Facades\Auth::guard('web')->id(),
            'aktivitas'  => 'Melakukan pembayaran tahunan untuk tagihan ID: ' . $tagihan->id . 
                            ' (Siswa ID: ' . $tagihan->siswa_id . ') sebesar Rp' . number_format($jumlahBayar,0,',','.'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()
            ->route('tagihan.bayarTahunan', $tagihan)
            ->with('success', '✅ Pembayaran tahunan berhasil disimpan!');
    }

    /**
     * New index method with grouped view by school and class
     */
    public function indexGrouped(Request $request)
    {
        $sekolahData = Sekolah::query()
            ->orderBy('nama_sekolah')
            ->get();

        $selectedSekolah = $request->filled('sekolah')
            ? $sekolahData->firstWhere('id', $request->integer('sekolah'))
            : null;

        $availableClasses = Kelas::query()
            ->with('sekolah')
            ->withCount('siswa')
            ->when(
                $selectedSekolah,
                fn ($query) => $query->where('sekolah_id', $selectedSekolah->id)
            )
            ->orderBy('sekolah_id')
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        $selectedKelas = $request->filled('kelas')
            ? $availableClasses->firstWhere('id', $request->integer('kelas'))
            : null;

        $studentRows = Siswa::query()
            ->when(
                $selectedSekolah,
                fn ($query) => $query->where('id_sekolah', $selectedSekolah->id)
            )
            ->when(
                $selectedKelas,
                fn ($query) => $query->where('kelas_id', $selectedKelas->id)
            )
            ->with([
                'sekolah',
                'kelas',
                'tagihan' => fn ($query) => $query
                    ->withSum('pembayaran as total_dibayar', 'jumlah_bayar')
                    ->withSum('pembayaran as total_diskon', 'diskon'),
            ])
            ->orderBy('nama')
            ->get()
            ->map(function (Siswa $siswa) {
                $totalTagihan = $siswa->tagihan->count();
                $totalNominal = 0;
                $totalDibayar = 0;
                $totalDiskon = 0;
                $sisaBayar = 0;

                foreach ($siswa->tagihan as $tagihan) {
                    $nominal = (float) $tagihan->nominal;
                    $dibayar = (float) ($tagihan->total_dibayar ?? 0);
                    $diskon = (float) ($tagihan->total_diskon ?? 0);

                    $totalNominal += $nominal;
                    $totalDibayar += $dibayar;
                    $totalDiskon += $diskon;
                    $sisaBayar += max(0, $nominal - ($dibayar + $diskon));
                }

                if ($totalTagihan === 0) {
                    $status = 'kosong';
                } elseif ($sisaBayar <= 0) {
                    $status = 'lunas';
                } elseif (($totalDibayar + $totalDiskon) > 0) {
                    $status = 'sebagian';
                } else {
                    $status = 'belum';
                }

                $className = trim((string) ($siswa->kelas?->nama_kelas ?? ''));
                $classLabel = !$siswa->kelas
                    ? 'Kelas belum diatur'
                    : (in_array($className, ['', '-', '–'], true)
                        ? 'Tingkat ' . $siswa->kelas->tingkat
                        : 'Tingkat ' . $siswa->kelas->tingkat . ' · ' . $className);

                return [
                    'id' => $siswa->id,
                    'nama' => $siswa->nama,
                    'nis' => $siswa->nis,
                    'initial' => strtoupper(substr($siswa->nama, 0, 1)),
                    'school_name' => $siswa->sekolah?->nama_sekolah ?? 'Sekolah belum diatur',
                    'class_name' => $classLabel,
                    'total_tagihan' => $totalTagihan,
                    'total_nominal' => $totalNominal,
                    'total_dibayar' => $totalDibayar,
                    'total_diskon' => $totalDiskon,
                    'sisa_bayar' => $sisaBayar,
                    'status' => $status,
                ];
            });

        $workspaceSummary = [
            'total_siswa' => $studentRows->count(),
            'total_tagihan' => $studentRows->sum('total_tagihan'),
            'total_nominal' => $studentRows->sum('total_nominal'),
            'total_dibayar' => $studentRows->sum('total_dibayar'),
            'sisa_bayar' => $studentRows->sum('sisa_bayar'),
            'siswa_lunas' => $studentRows->where('status', 'lunas')->count(),
        ];

        return view('tagihan.index_new', compact(
            'sekolahData',
            'availableClasses',
            'selectedSekolah',
            'selectedKelas',
            'studentRows',
            'workspaceSummary'
        ));
    }

    /**
     * Get students summary data for a specific class
     */
    public function getStudentsSummary(Request $request, $sekolahId, $kelasId)
    {
        $search = $request->get('search');
        
        $query = \App\Models\Siswa::where('kelas_id', $kelasId)
            ->where('id_sekolah', $sekolahId);
            
        // Tambahkan filter pencarian jika ada
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }
        
        $students = $query->with(['tagihan' => function($q) {
            $q->with('pembayaran');
        }])
        ->get()
        ->map(function($siswa) {
            $totalTagihan = $siswa->tagihan->count();
            $totalNominal = $siswa->tagihan->sum('nominal');
            $totalDibayar = 0;
            $totalDiskon = 0; // ✅ TAMBAHAN
            $sisaBayar = 0;

            // ✅ PERBAIKAN: Calculate total paid and discount
            foreach ($siswa->tagihan as $tagihan) {
                $totalBayarTagihan = $tagihan->pembayaran->sum('jumlah_bayar');
                $totalDiskonTagihan = $tagihan->pembayaran->sum('diskon'); // ✅ TAMBAHAN

                $totalDibayar += $totalBayarTagihan;
                $totalDiskon += $totalDiskonTagihan;
                $sisaBayar += max(0, $tagihan->nominal - ($totalBayarTagihan + $totalDiskonTagihan)); // ✅ PERBAIKAN
            }

            return [
                'id' => $siswa->id,
                'nama' => $siswa->nama,
                'nis' => $siswa->nis,
                'total_tagihan' => $totalTagihan,
                'total_nominal' => number_format($totalNominal, 0, ',', '.'),
                'total_dibayar' => number_format($totalDibayar, 0, ',', '.'),
                'total_diskon' => number_format($totalDiskon, 0, ',', '.'), // ✅ TAMBAHAN
                'sisa_bayar' => number_format($sisaBayar, 0, ',', '.'),
            ];
        });

        return response()->json([
            'students' => $students
        ]);
    }

    /**
     * Process tagihan for a specific student
     */
    public function prosesSiswa(Request $request, $siswaId)
    {
        $siswa = \App\Models\Siswa::with(['kelas', 'sekolah', 'tahunAjaran'])->findOrFail($siswaId);

        // Get all tagihan for this student
        $tagihanData = \App\Models\Tagihan::where('siswa_id', $siswaId)
            ->with(['jenisPembayaran', 'pembayaran'])
            ->orderBy('nama_tagihan')
            ->orderBy('periode')
            ->get();

        $tagihanList = [];
        $processedBulanan = []; // Untuk melacak ID jenis pembayaran bulanan yg sudah diproses

        foreach ($tagihanData as $tagihan) {
            // ✅ PERBAIKAN UTAMA: Hitung total bayar termasuk diskon
            $totalBayar = $tagihan->pembayaran->sum('jumlah_bayar');
            $totalDiskon = $tagihan->pembayaran->sum('diskon');
            $sisaBayar = $tagihan->nominal - ($totalBayar + $totalDiskon);

            // Logika untuk SPP dan tagihan bulanan lainnya
            if ($tagihan->tipe === 'bulanan') {
                $baseGroupKey = is_null($tagihan->jenis_pembayaran_id) ? 'spp' : 'jp-' . $tagihan->jenis_pembayaran_id;
                [$fallbackAcademicYear] = $this->getAcademicYearBounds($siswa);
                $academicYearStart = $this->getAcademicYearStartFromPeriod($tagihan->periode)
                    ?? $fallbackAcademicYear;
                $academicYearLabel = $academicYearStart . '/' . ($academicYearStart + 1);
                $groupKey = $baseGroupKey . '-ta-' . $academicYearStart;

                // Jika grup ini belum diproses, buat grup baru
                if (!isset($processedBulanan[$groupKey])) {
                    $namaTagihanGroup = is_null($tagihan->jenis_pembayaran_id)
                        ? 'SPP'
                        : ($tagihan->jenisPembayaran->nama_pembayaran ?? 'Tagihan Bulanan');

                    $tagihanBulanans = $tagihanData->where('tipe', 'bulanan')
                        ->where('jenis_pembayaran_id', $tagihan->jenis_pembayaran_id)
                        ->filter(
                            fn ($item) => $this->getAcademicYearStartFromPeriod($item->periode)
                                === $academicYearStart
                        )
                        ->sortBy(fn ($item) => $this->getAcademicMonthSortKey($item->periode));

                    // ✅ PERBAIKAN: Map setiap bulan dengan nama Indonesia dan sorting + diskon
                    $bulanTagihan = $tagihanBulanans->map(function ($item) {
                        $totalBayarItem = $item->pembayaran->sum('jumlah_bayar');
                        $totalDiskonItem = $item->pembayaran->sum('diskon'); // ✅ TAMBAHAN
                        $sisaBayarItem = $item->nominal - ($totalBayarItem + $totalDiskonItem); // ✅ PERBAIKAN

                        // ✅ Convert ke bahasa Indonesia
                        $periodeBulan = \Carbon\Carbon::parse($item->periode . '-01');
                        $namaBulanIndonesia = $this->getBulanIndonesia($periodeBulan->format('F')) . ' ' . $periodeBulan->format('Y');

                        return [
                            'id' => $item->id,
                            'periode' => $item->periode, // Untuk sorting
                            'periode_display' => $namaBulanIndonesia, // Untuk tampilan
                            'nama_tagihan' => $item->nama_tagihan,
                            'nominal' => $item->nominal,
                            'total_bayar' => $totalBayarItem,
                            'total_diskon' => $totalDiskonItem, // ✅ TAMBAHAN
                            'sisa_bayar' => $sisaBayarItem,
                            'status' => $sisaBayarItem <= 0 ? 'lunas' : 'belum lunas',
                            'tanggal_jatuh_tempo' => $item->tanggal_jatuh_tempo,
                        ];
                    })->sortBy(fn ($item) => $this->getAcademicMonthSortKey($item['periode']));

                    // Tambahkan grup ke daftar tagihan utama
                    $tagihanList[] = [
                        'id' => 'group-' . $groupKey,
                        'nama_tagihan' => $namaTagihanGroup,
                        'tipe' => 'bulanan',
                        'is_grouped' => true,
                        'academic_year_start' => $academicYearStart,
                        'academic_year_label' => $academicYearLabel,
                        'periode' => 'Tahunan',
                        'nominal' => $tagihanBulanans->sum('nominal'),
                        'total_bayar' => $bulanTagihan->sum('total_bayar'),
                        'total_diskon' => $bulanTagihan->sum('total_diskon'), // ✅ TAMBAHAN
                        'sisa_bayar' => $bulanTagihan->sum('sisa_bayar'),
                        'status' => $bulanTagihan->sum('sisa_bayar') <= 0 ? 'lunas' : 'belum lunas',
                        'tanggal_jatuh_tempo' => null,
                        'bulan_tagihan' => $bulanTagihan->values()->all(), // ✅ Convert ke array terurut
                    ];

                    $processedBulanan[$groupKey] = true; // Tandai grup ini sudah diproses
                }
            } else {
                // ✅ PERBAIKAN: Untuk tagihan non-bulanan, juga convert periode ke Indonesia
                $periodeDisplay = $tagihan->periode;
                if ($tagihan->tipe === 'semester') {
                    // Extract bulan dari periode untuk semester
                    if (strpos($tagihan->nama_tagihan, 'Semester 1') !== false) {
                        $periodeDisplay = 'Semester 1';
                    } elseif (strpos($tagihan->nama_tagihan, 'Semester 2') !== false) {
                        $periodeDisplay = 'Semester 2';
                    }
                } elseif ($tagihan->tipe === 'setahun') {
                    // Gunakan tahun dari periode jika tersedia, jika tidak gunakan tahun saat ini
                    $tahun = $tagihan->periode ?? date('Y');
                    $periodeDisplay = 'Tahun ' . $tahun;
                }

                // Untuk tagihan non-bulanan
                $tagihanList[] = [
                    'id' => $tagihan->id,
                    'nama_tagihan' => $tagihan->nama_tagihan,
                    'tipe' => $tagihan->tipe,
                    'is_grouped' => false,
                    'periode' => $periodeDisplay,
                    'nominal' => $tagihan->nominal,
                    'total_bayar' => $totalBayar,
                    'total_diskon' => $totalDiskon, // ✅ TAMBAHAN
                    'sisa_bayar' => $sisaBayar,
                    'status' => $sisaBayar <= 0 ? 'lunas' : 'belum lunas',
                    'tanggal_jatuh_tempo' => $tagihan->tanggal_jatuh_tempo,
                ];
            }
        }

        $groupedBills = array_values(array_filter($tagihanList, fn ($item) => $item['is_grouped']));
        $singleBills = array_values(array_filter($tagihanList, fn ($item) => !$item['is_grouped']));

        usort($groupedBills, function ($left, $right) {
            $yearComparison = ($right['academic_year_start'] ?? 0) <=> ($left['academic_year_start'] ?? 0);

            return $yearComparison !== 0
                ? $yearComparison
                : strcasecmp($left['nama_tagihan'], $right['nama_tagihan']);
        });

        $tagihanList = array_merge($groupedBills, $singleBills);

        $billItems = collect($tagihanList)->flatMap(function ($tagihan) {
            return $tagihan['is_grouped']
                ? collect($tagihan['bulan_tagihan'])
                : collect([$tagihan]);
        });

        $studentSummary = [
            'total_tagihan' => $billItems->count(),
            'total_nominal' => $billItems->sum('nominal'),
            'total_dibayar' => $billItems->sum('total_bayar'),
            'total_diskon' => $billItems->sum('total_diskon'),
            'sisa_bayar' => $billItems->sum(fn ($item) => max(0, (float) $item['sisa_bayar'])),
            'belum_lunas' => $billItems->filter(fn ($item) => (float) $item['sisa_bayar'] > 0)->count(),
        ];

        return view('tagihan.proses_siswa', compact('siswa', 'tagihanList', 'studentSummary'));
    }


    /**
     * Resolve the July-June range from the student's academic-year label.
     */
    private function getAcademicYearBounds(Siswa $siswa): array
    {
        $academicYearName = trim((string) ($siswa->tahunAjaran?->nama_tahun ?? ''));

        if (preg_match('/(\d{4})\D+(\d{4})/', $academicYearName, $matches)) {
            $startYear = (int) $matches[1];
            $endYear = (int) $matches[2];

            if ($endYear > $startYear) {
                return [$startYear, $endYear];
            }
        }

        $today = Carbon::today();
        $startYear = $today->month >= 7 ? $today->year : $today->year - 1;

        return [$startYear, $startYear + 1];
    }

    /**
     * Build the twelve monthly periods in school-year order: July through June.
     */
    private function getAcademicYearMonths(Siswa $siswa): array
    {
        [$startYear] = $this->getAcademicYearBounds($siswa);
        $firstMonth = Carbon::create($startYear, 7, 1)->startOfDay();

        return array_map(
            fn (int $offset) => $firstMonth->copy()->addMonths($offset),
            range(0, 11)
        );
    }

    /**
     * Keep existing monthly data readable in July-June order.
     */
    private function getAcademicMonthSortKey(?string $period): string
    {
        $academicYearStart = $this->getAcademicYearStartFromPeriod($period);

        if ($academicYearStart === null) {
            return '9999-99-' . (string) $period;
        }

        $month = (int) substr($period, 5, 2);
        $position = ($month + 5) % 12;

        return sprintf('%04d-%02d', $academicYearStart, $position);
    }

    private function getAcademicYearStartFromPeriod(?string $period): ?int
    {
        if (!preg_match('/^(\d{4})-(0[1-9]|1[0-2])$/', (string) $period, $matches)) {
            return null;
        }

        $year = (int) $matches[1];
        $month = (int) $matches[2];

        return $month >= 7 ? $year : $year - 1;
    }

    /**
     * Convert an English month name to Indonesian.
     */
    private function getBulanIndonesia($bulanInggris)
    {
        $bulanMap = [
            'January'   => 'Januari',
            'February'  => 'Februari', 
            'March'     => 'Maret',
            'April'     => 'April',
            'May'       => 'Mei',
            'June'      => 'Juni',
            'July'      => 'Juli',
            'August'    => 'Agustus',
            'September' => 'September',
            'October'   => 'Oktober',
            'November'  => 'November',
            'December'  => 'Desember'
        ];

        return $bulanMap[$bulanInggris] ?? $bulanInggris;
    }


    /**
     * Auto-generate tagihan untuk siswa tertentu
     */
    private function autoGenerateTagihanForSiswa($siswaId)
    {
        $siswa = Siswa::with('tahunAjaran')->findOrFail($siswaId);
        $periodeTahunAjaran = $this->getAcademicYearMonths($siswa);
        [$tahunAwal] = $this->getAcademicYearBounds($siswa);

        // Generate SPP untuk Juli sampai Juni pada tahun ajaran siswa.
        foreach ($periodeTahunAjaran as $bulanTagihan) {
            $periode = $bulanTagihan->format('Y-m');
            $namaBulan = $bulanTagihan->format('F Y');

            Tagihan::updateOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'periode' => $periode,
                    'tipe' => 'bulanan',
                    'jenis_pembayaran_id' => null,
                ],
                [
                    'id_sekolah' => $siswa->id_sekolah,
                    'nama_tagihan' => 'SPP - ' . $namaBulan,
                    'nominal' => $siswa->nominal_spp ?? 150000,
                    'tanggal_jatuh_tempo' => $bulanTagihan->copy()->day(10),
                    'status' => 'belum',
                ]
            );
        }

        // ✅ Generate dari jenis pembayaran yang eligible untuk siswa ini
        $jenisPembayaran = JenisPembayaran::all();
        foreach ($jenisPembayaran as $jenis) {
            if ($jenis->isStudentEligible($siswaId)) {

                if ($jenis->tipe === 'bulanan') {
                    // Generate tagihan bulanan mengikuti Juli-Juni.
                    foreach ($periodeTahunAjaran as $bulanTagihan) {
                        $periode = $bulanTagihan->format('Y-m');
                        $namaBulan = $bulanTagihan->format('F Y');

                        Tagihan::updateOrCreate(
                            [
                                'siswa_id' => $siswa->id,
                                'jenis_pembayaran_id' => $jenis->id,
                                'periode' => $periode,
                            ],
                            [
                                'id_sekolah' => $siswa->id_sekolah,
                                'nama_tagihan' => $jenis->nama_pembayaran . ' - ' . $namaBulan,
                                'nominal' => $jenis->nominal,
                                'tipe' => $jenis->tipe,
                                'tanggal_jatuh_tempo' => $bulanTagihan->copy()->day(10),
                                'status' => 'belum',
                            ]
                        );
                    }
                }
                elseif ($jenis->tipe === 'semester') {
                    // ✅ Generate tagihan semester (2 semester)
                    $jatuhTempo = \Carbon\Carbon::parse($jenis->jatuh_tempo);
                    $bulanPertama = $jatuhTempo->month;
                    $bulanKedua = $bulanPertama + 6;

                    // Handle jika bulan kedua > 12 (lewat tahun)
                    if ($bulanKedua > 12) {
                        $bulanKedua -= 12;
                    }

                    // Semester 1
                    $tahunPeriodePertama = $bulanPertama >= 7 ? $tahunAwal : $tahunAwal + 1;
                    $periode1 = $tahunPeriodePertama . '-' . str_pad($bulanPertama, 2, '0', STR_PAD_LEFT);
                    Tagihan::updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                            'jenis_pembayaran_id' => $jenis->id,
                            'periode' => $periode1,
                        ],
                        [
                            'id_sekolah' => $siswa->id_sekolah,
                            'nama_tagihan' => $jenis->nama_pembayaran . ' - Semester 1',
                            'nominal' => $jenis->nominal,
                            'tipe' => $jenis->tipe,
                            'tanggal_jatuh_tempo' => \Carbon\Carbon::parse($periode1)->day(10),
                            'status' => 'belum',
                        ]
                    );

                    // Semester 2
                    $tahunPeriodeKedua = $bulanKedua >= 7 ? $tahunAwal : $tahunAwal + 1;
                    $periode2 = $tahunPeriodeKedua . '-' . str_pad($bulanKedua, 2, '0', STR_PAD_LEFT);
                    Tagihan::updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                            'jenis_pembayaran_id' => $jenis->id,
                            'periode' => $periode2,
                        ],
                        [
                            'id_sekolah' => $siswa->id_sekolah,
                            'nama_tagihan' => $jenis->nama_pembayaran . ' - Semester 2',
                            'nominal' => $jenis->nominal,
                            'tipe' => $jenis->tipe,
                            'tanggal_jatuh_tempo' => \Carbon\Carbon::parse($periode2)->day(10),
                            'status' => 'belum',
                        ]
                    );
                }
                elseif ($jenis->tipe === 'setahun') {
                    // ✅ Generate tagihan tahunan (1x per tahun)
                    Tagihan::updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                            'jenis_pembayaran_id' => $jenis->id,
                            'periode' => $tahunAwal,
                        ],
                        [
                            'id_sekolah' => $siswa->id_sekolah,
                            'nama_tagihan' => $jenis->nama_pembayaran . ' - Tahun Ajaran ' . $tahunAwal . '/' . ($tahunAwal + 1),
                            'nominal' => $jenis->nominal,
                            'tipe' => $jenis->tipe,
                            'tanggal_jatuh_tempo' => $jenis->jatuh_tempo,
                            'status' => 'belum',
                        ]
                    );
                }
                elseif ($jenis->tipe === 'sekali') {
                    // ✅ Generate tagihan sekali bayar
                    Tagihan::updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                            'jenis_pembayaran_id' => $jenis->id,
                        ],
                        [
                            'id_sekolah' => $siswa->id_sekolah,
                            'nama_tagihan' => $jenis->nama_pembayaran,
                            'nominal' => $jenis->nominal,
                            'tipe' => $jenis->tipe,
                            'periode' => now()->format('Y-m'),
                            'tanggal_jatuh_tempo' => $jenis->jatuh_tempo,
                            'status' => 'belum',
                        ]
                    );
                }
            }
        }
    }

    /**
     * Process multiple tagihan payment with discount and receipt number generation
     */
    public function prosesMultiPembayaran(Request $request)
    {
        // ✅ VALIDASI INPUT
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'pembayaran' => 'required|array',
            'pembayaran.*' => 'nullable|numeric|min:0',
            'tanggal_bayar' => 'required|date',
            'metode_bayar' => 'required|in:tunai,transfer,kjc,tabungan',
            'keterangan' => 'nullable|string|max:500',
            // Data diskon dari frontend
            'original_amount' => 'nullable|array',
            'discount_amount' => 'nullable|array',
            'has_discount' => 'nullable|array',
        ], [
            'siswa_id.required' => 'Data siswa tidak valid.',
            'siswa_id.exists' => 'Siswa tidak ditemukan.',
            'pembayaran.required' => 'Pilih minimal satu tagihan untuk dibayar.',
            'tanggal_bayar.required' => 'Tanggal bayar wajib diisi.',
            'tanggal_bayar.date' => 'Format tanggal tidak valid.',
            'metode_bayar.required' => 'Pilih metode pembayaran.',
            'metode_bayar.in' => 'Metode pembayaran tidak valid.',
        ]);

        // ✅ AMBIL DATA SISWA DENGAN SEKOLAH
        $siswa = \App\Models\Siswa::with(['sekolah', 'kelas'])->findOrFail($request->siswa_id);

        // ✅ CEK SEKOLAH MEMILIKI KODE
        if (!$siswa->sekolah || !$siswa->sekolah->kode_sekolah) {
            return back()->withErrors([
                'general' => 'Sekolah siswa belum memiliki kode sekolah. Silakan hubungi administrator untuk mengatur kode sekolah terlebih dahulu.'
            ])->withInput();
        }

        $pembayaranData = $request->pembayaran;
        $pembayaranList = [];
        $totalBayarTransaksi = 0;
        $totalDiskonTransaksi = 0;
        $errorMessages = [];

        // ✅ GENERATE TRANSACTION ID
        $transactionId = \Illuminate\Support\Str::uuid()->toString();

        // ✅ BEGIN TRANSACTION UNTUK KONSISTENSI DATA
        \DB::beginTransaction();

        try {
            foreach ($pembayaranData as $tagihanId => $jumlahBayar) {
                // Skip jika tidak ada input bayar atau 0
                if (is_null($jumlahBayar) || $jumlahBayar <= 0) {
                    continue;
                }

                // ✅ AMBIL DATA TAGIHAN DENGAN PEMBAYARAN
                $tagihan = \App\Models\Tagihan::with(['pembayaran', 'jenisPembayaran'])
                    ->findOrFail($tagihanId);

                // ✅ VALIDASI TAGIHAN MILIK SISWA YANG BENAR
                if ($tagihan->siswa_id != $siswa->id) {
                    throw new \Exception("Tagihan ID {$tagihanId} bukan milik siswa {$siswa->nama}.");
                }

                // ✅ AMBIL DATA DISKON DARI REQUEST
                $originalAmount = floatval($request->input("original_amount.{$tagihanId}", 0));
                $discountAmount = floatval($request->input("discount_amount.{$tagihanId}", 0));
                $hasDiscount = $request->input("has_discount.{$tagihanId}") === 'true';

                // ✅ HITUNG SISA TAGIHAN SAAT INI
                $totalBayarSebelumnya = $tagihan->pembayaran->sum('jumlah_bayar');
                $totalDiskonSebelumnya = $tagihan->pembayaran->sum('diskon');
                $sisaBayarSebelumnya = $tagihan->nominal - ($totalBayarSebelumnya + $totalDiskonSebelumnya);

                // ✅ VALIDASI SISA TAGIHAN
                if ($sisaBayarSebelumnya <= 0) {
                    $errorMessages[] = "Tagihan '{$tagihan->nama_tagihan}' sudah lunas.";
                    continue;
                }

                // ✅ VALIDASI JUMLAH PEMBAYARAN
                if ($hasDiscount && $discountAmount > 0) {
                    // Jika ada diskon, total coverage adalah jumlah bayar + diskon
                    $totalCoverage = $jumlahBayar + $discountAmount;

                    if ($totalCoverage > $sisaBayarSebelumnya) {
                        $errorMessages[] = "Jumlah bayar + diskon untuk '{$tagihan->nama_tagihan}' melebihi sisa tagihan: Rp " . number_format($sisaBayarSebelumnya, 0, ',', '.');
                        continue;
                    }

                    // ✅ VALIDASI DISKON SPP (hanya berlaku untuk pembayaran lunas)
                    if ($discountAmount > 0) {
                        // Cek apakah ini tagihan SPP
                        $isSPP = is_null($tagihan->jenis_pembayaran_id);

                        if ($isSPP) {
                            // Validasi periode diskon (sampai tanggal 10)
                            $tanggalBayar = \Carbon\Carbon::parse($request->tanggal_bayar);
                            $periodeBulan = \Carbon\Carbon::parse($tagihan->periode . '-01');
                            $batasDiskon = \Carbon\Carbon::create($periodeBulan->year, $periodeBulan->month, 10);

                            if ($tanggalBayar > $batasDiskon) {
                                $errorMessages[] = "Diskon untuk '{$tagihan->nama_tagihan}' tidak berlaku karena lewat tanggal 10.";
                                continue;
                            }

                            // Validasi pembayaran harus lunas untuk dapat diskon
                            if ($totalCoverage < $sisaBayarSebelumnya) {
                                $errorMessages[] = "Diskon untuk '{$tagihan->nama_tagihan}' hanya berlaku untuk pembayaran lunas.";
                                continue;
                            }

                            // Validasi maksimal diskon Rp 25.000
                            if ($discountAmount > 25000) {
                                $errorMessages[] = "Maksimal diskon untuk SPP adalah Rp 25.000.";
                                continue;
                            }
                        }
                    }
                } else {
                    // Tanpa diskon, validasi normal
                    if ($jumlahBayar > $sisaBayarSebelumnya) {
                        $errorMessages[] = "Jumlah bayar untuk '{$tagihan->nama_tagihan}' melebihi sisa tagihan: Rp " . number_format($sisaBayarSebelumnya, 0, ',', '.');
                        continue;
                    }
                }

                // ✅ SIMPAN PEMBAYARAN DENGAN NOMOR KWITANSI AUTO-GENERATE
                $keteranganPembayaran = $request->keterangan ?? 'Pembayaran multi-tagihan';

                if ($hasDiscount && $discountAmount > 0) {
                    $keteranganPembayaran .= " (Diskon: Rp " . number_format($discountAmount, 0, ',', '.') . ")";
                }

                // Gunakan nomor transaksi sebagai referensi untuk kwitansi yang sama
                $pembayaran = \App\Models\Pembayaran::create([
                    'tagihan_id' => $tagihan->id,
                    'siswa_id' => $siswa->id,
                    'jumlah_bayar' => $jumlahBayar,
                    'diskon' => $hasDiscount ? $discountAmount : 0,
                    'tanggal_bayar' => $request->tanggal_bayar,
                    'metode_bayar' => $request->metode_bayar,
                    'keterangan' => $keteranganPembayaran,
                    'transaction_id' => $transactionId, // ✅ TAMBAHKAN TRANSACTION ID
                    // nomor_kwitansi akan auto-generate via model boot
                ]);

                // ✅ RELOAD PEMBAYARAN UNTUK MENDAPATKAN NOMOR KWITANSI
                $pembayaran->refresh();

                // ✅ TAMBAHKAN KE DAFTAR UNTUK KWITANSI
                $totalCoverageActual = $jumlahBayar + ($hasDiscount ? $discountAmount : 0);
                $sisaBayarSetelahTransaksi = $sisaBayarSebelumnya - $totalCoverageActual;

                $pembayaranList[] = [
                    'pembayaran_id' => $pembayaran->id,
                    'nomor_kwitansi' => $pembayaran->nomor_kwitansi,
                    'tagihan_id' => $tagihan->id,
                    'nama_tagihan' => $tagihan->nama_tagihan,
                    'periode' => $tagihan->periode,
                    'nominal_tagihan' => $tagihan->nominal,
                    'sisa_sebelumnya' => $sisaBayarSebelumnya,
                    'jumlah_bayar' => $jumlahBayar,
                    'jumlah_asli' => $jumlahBayar + ($hasDiscount ? $discountAmount : 0), // Jumlah sebelum diskon
                    'diskon' => $hasDiscount ? $discountAmount : 0,
                    'total_coverage' => $totalCoverageActual,
                    'sisa_setelah' => max(0, $sisaBayarSetelahTransaksi),
                    'is_angsuran' => $sisaBayarSetelahTransaksi > 0,
                    'is_lunas' => $sisaBayarSetelahTransaksi <= 0,
                    'metode_bayar' => $request->metode_bayar,
                ];

                // ✅ UPDATE TOTAL TRANSAKSI
                $totalBayarTransaksi += $jumlahBayar;
                $totalDiskonTransaksi += ($hasDiscount ? $discountAmount : 0);

                // ✅ UPDATE STATUS TAGIHAN JIKA LUNAS
                $totalPembayaranSekarang = $totalBayarSebelumnya + $jumlahBayar;
                $totalDiskonSekarang = $totalDiskonSebelumnya + ($hasDiscount ? $discountAmount : 0);

                if (($totalPembayaranSekarang + $totalDiskonSekarang) >= $tagihan->nominal) {
                    $tagihan->update(['status' => 'lunas']);

                    // Log khusus untuk tagihan lunas
                    \App\Models\LogAktivitas::create([
                        'aktor_type' => 'admin',
                        'aktor_id'   => \Illuminate\Support\Facades\Auth::guard('web')->id(),
                        'aktivitas'  => "Tagihan '{$tagihan->nama_tagihan}' siswa {$siswa->nama} menjadi LUNAS",
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }
            }

            // ✅ CEK APAKAH ADA ERROR
            if (!empty($errorMessages)) {
                \DB::rollback();
                return back()->withErrors(['pembayaran' => $errorMessages])->withInput();
            }

            // ✅ CEK APAKAH ADA PEMBAYARAN YANG BERHASIL
            if (empty($pembayaranList)) {
                \DB::rollback();
                return redirect()
                    ->route('tagihan.proses.siswa', $siswa->id)
                    ->with('error', 'Tidak ada pembayaran yang diproses. Harap masukkan jumlah bayar yang valid.');
            }

            // ✅ COMMIT TRANSACTION
            \DB::commit();

            // ✅ GENERATE DATA KWITANSI
            $kwitansiData = [
                'siswa' => $siswa,
                'pembayaran_list' => $pembayaranList,
                'total_bayar' => $totalBayarTransaksi,
                'total_diskon' => $totalDiskonTransaksi,
                'grand_total' => $totalBayarTransaksi + $totalDiskonTransaksi,
                'tanggal_bayar' => $request->tanggal_bayar,
                'metode_bayar' => $request->metode_bayar,
                'keterangan' => $request->keterangan,
                'admin' => \Illuminate\Support\Facades\Auth::user(),
                'generated_at' => now(),
            ];

            // ✅ LOG AKTIVITAS UTAMA
            \App\Models\LogAktivitas::create([
                'aktor_type' => 'admin',
                'aktor_id'   => \Illuminate\Support\Facades\Auth::guard('web')->id(),
                'aktivitas'  => "Pembayaran multi-tagihan siswa {$siswa->nama}: " . count($pembayaranList) . " tagihan, total Rp " . number_format($totalBayarTransaksi, 0, ',', '.') . ($totalDiskonTransaksi > 0 ? " + diskon Rp " . number_format($totalDiskonTransaksi, 0, ',', '.') : ""),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // ✅ GET PAYMENT IDs FOR REDIRECT
            $paymentIds = collect($pembayaranList)->pluck('pembayaran_id')->implode(',');

            // ✅ REDIRECT TO THE NEW GROUP RECEIPT ROUTE
            return redirect()
                ->route('pembayaran.kwitansi.grup', ['ids' => $paymentIds])
                ->with('success', '✅ Pembayaran multi-tagihan berhasil diproses! ' . count($pembayaranList) . ' tagihan telah dibayar.');

        } catch (\Exception $e) {
            // ✅ ROLLBACK JIKA ADA ERROR
            \DB::rollback();

            // Log error
            \App\Models\LogAktivitas::create([
                'aktor_type' => 'admin',
                'aktor_id'   => \Illuminate\Support\Facades\Auth::guard('web')->id(),
                'aktivitas'  => "ERROR pembayaran multi-tagihan siswa {$siswa->nama}: " . $e->getMessage(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            \Log::error('Error dalam prosesMultiPembayaran', [
                'siswa_id' => $siswa->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withErrors(['general' => 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.'])
                ->with('error', 'Pembayaran gagal diproses: ' . $e->getMessage())
                ->withInput();
        }
}



    /**
     * Auto-generate semua tagihan (SPP + Jenis Pembayaran)
     */
    private function autoGenerateAllTagihan()
    {
        // ✅ 1. Generate Tagihan SPP (Bulanan)
        $this->generateTagihanSPP();

        // ✅ 2. Generate Tagihan dari Jenis Pembayaran
        $this->generateTagihanFromJenisPembayaran();
    }

    /**
     * Generate tagihan SPP untuk semua siswa aktif
     */
    private function generateTagihanSPP()
    {
        $siswaAktif = Siswa::with('tahunAjaran')->where('status', 'aktif')->get();

        foreach ($siswaAktif as $siswa) {
            // Generate SPP dari Juli sampai Juni pada tahun ajaran siswa.
            foreach ($this->getAcademicYearMonths($siswa) as $bulanTagihan) {
                $periode = $bulanTagihan->format('Y-m');
                $namaBulan = $bulanTagihan->format('F Y');

                Tagihan::updateOrCreate(
                    [
                        'siswa_id' => $siswa->id,
                        'periode' => $periode,
                        'tipe' => 'bulanan',
                        'jenis_pembayaran_id' => null, // ✅ NULL = SPP
                    ],
                    [
                        'id_sekolah' => $siswa->id_sekolah,
                        'nama_tagihan' => 'SPP - ' . $namaBulan,
                        'nominal' => $siswa->nominal_spp ?? 150000, // Default atau dari siswa
                        'tanggal_jatuh_tempo' => $bulanTagihan->copy()->day(10),
                        'status' => 'belum',
                    ]
                );
            }
        }
    }

    /**
     * Generate tagihan dari jenis pembayaran yang sudah ada
     */
    private function generateTagihanFromJenisPembayaran()
    {
        $jenisPembayaran = JenisPembayaran::all();

        foreach ($jenisPembayaran as $jenis) {
            $eligibleSiswa = $jenis->getEligibleSiswa();

            foreach ($eligibleSiswa as $siswa) {
                $siswa->loadMissing('tahunAjaran');
                $periodeTahunAjaran = $this->getAcademicYearMonths($siswa);
                [$tahunAwal] = $this->getAcademicYearBounds($siswa);

                if ($jenis->tipe === 'bulanan') {
                    // Generate untuk 12 bulan dalam urutan Juli-Juni.
                    foreach ($periodeTahunAjaran as $bulanTagihan) {
                        $periode = $bulanTagihan->format('Y-m');
                        $namaBulan = $bulanTagihan->format('F Y');

                        Tagihan::updateOrCreate(
                            [
                                'siswa_id' => $siswa->id,
                                'jenis_pembayaran_id' => $jenis->id,
                                'periode' => $periode,
                            ],
                            [
                                'id_sekolah' => $siswa->id_sekolah,
                                'nama_tagihan' => $jenis->nama_pembayaran . ' - ' . $namaBulan,
                                'nominal' => $jenis->nominal,
                                'tipe' => $jenis->tipe,
                                'tanggal_jatuh_tempo' => $bulanTagihan->copy()->day(10),
                                'status' => 'belum',
                            ]
                        );
                    }
                } 
                elseif ($jenis->tipe === 'semester') {
                    // ✅ Generate untuk 2 semester
                    $jatuhTempo = \Carbon\Carbon::parse($jenis->jatuh_tempo);
                    $bulanPertama = $jatuhTempo->month;
                    $bulanKedua = $bulanPertama + 6;

                    // Semester 1
                    $tahunPeriodePertama = $bulanPertama >= 7 ? $tahunAwal : $tahunAwal + 1;
                    $periode1 = $tahunPeriodePertama . '-' . str_pad($bulanPertama, 2, '0', STR_PAD_LEFT);
                    Tagihan::updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                            'jenis_pembayaran_id' => $jenis->id,
                            'periode' => $periode1,
                        ],
                        [
                            'id_sekolah' => $siswa->id_sekolah,
                            'nama_tagihan' => $jenis->nama_pembayaran . ' - Semester 1',
                            'nominal' => $jenis->nominal,
                            'tipe' => $jenis->tipe,
                            'tanggal_jatuh_tempo' => \Carbon\Carbon::parse($periode1)->day(10),
                            'status' => 'belum',
                        ]
                    );

                    // Semester 2
                    if ($bulanKedua > 12) {
                        $bulanKedua -= 12;
                    }
                    $tahunPeriodeKedua = $bulanKedua >= 7 ? $tahunAwal : $tahunAwal + 1;
                    $periode2 = $tahunPeriodeKedua . '-' . str_pad($bulanKedua, 2, '0', STR_PAD_LEFT);
                    Tagihan::updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                            'jenis_pembayaran_id' => $jenis->id,
                            'periode' => $periode2,
                        ],
                        [
                            'id_sekolah' => $siswa->id_sekolah,
                            'nama_tagihan' => $jenis->nama_pembayaran . ' - Semester 2',
                            'nominal' => $jenis->nominal,
                            'tipe' => $jenis->tipe,
                            'tanggal_jatuh_tempo' => \Carbon\Carbon::parse($periode2)->day(10),
                            'status' => 'belum',
                        ]
                    );
                }
                elseif ($jenis->tipe === 'setahun') {
                    // ✅ Generate tagihan tahunan (1x per tahun)
                    Tagihan::updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                            'jenis_pembayaran_id' => $jenis->id,
                            // Tambahkan periode tahunan untuk memastikan tidak duplikat
                            'periode' => $tahunAwal,
                        ],
                        [
                            'id_sekolah' => $siswa->id_sekolah,
                            'nama_tagihan' => $jenis->nama_pembayaran . ' - Tahun Ajaran ' . $tahunAwal . '/' . ($tahunAwal + 1),
                            'nominal' => $jenis->nominal,
                            'tipe' => $jenis->tipe,
                            'tanggal_jatuh_tempo' => $jenis->jatuh_tempo,
                            'status' => 'belum',
                        ]
                    );
                }
                else {
                    // Untuk 'sekali' dan tipe lainnya
                    Tagihan::updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                            'jenis_pembayaran_id' => $jenis->id,
                            // Hanya tambahkan periode untuk tipe selain 'sekali'
                            // Untuk tipe 'sekali', tidak perlu periode karena hanya dibuat sekali
                        ] + ($jenis->tipe !== 'sekali' ? ['periode' => $tahunAwal] : []),
                        [
                            'id_sekolah' => $siswa->id_sekolah,
                            'nama_tagihan' => $jenis->nama_pembayaran,
                            'nominal' => $jenis->nominal,
                            'tipe' => $jenis->tipe,
                            'tanggal_jatuh_tempo' => $jenis->jatuh_tempo,
                            'status' => 'belum',
                        ]
                    );
                }
            }
        }
    }

    /**
     * Generate tagihan manually via button
     */
    public function generateTagihanManual(Request $request)
    {
        // Jalankan proses generate tagihan
        $this->autoGenerateAllTagihan();
        
        // Log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Generate tagihan manual untuk semua siswa',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()->with('success', '✅ Tagihan untuk semua siswa berhasil digenerate secara otomatis!');
    }

    /**
     * Generate tagihan manually via button for specific student
     */
    public function generateTagihanManualSiswa(Request $request, $siswaId)
    {
        // Jalankan proses generate tagihan untuk siswa tertentu
        $this->autoGenerateTagihanForSiswa($siswaId);
        
        // Log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Generate tagihan manual untuk siswa ID: ' . $siswaId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()->with('success', '✅ Tagihan untuk siswa berhasil digenerate!');
    }
}
