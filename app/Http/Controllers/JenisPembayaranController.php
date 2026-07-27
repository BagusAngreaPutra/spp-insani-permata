<?php

namespace App\Http\Controllers;

use App\Models\JenisPembayaran;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JenisPembayaranController extends Controller
{
    // Tampilkan daftar
    public function index(Request $request)
    {
        $sekolah = Sekolah::all();
        $selectedSekolah = $request->input('sekolah_id');
        $search = $request->input('search');

        $query = JenisPembayaran::with(['sekolah', 'siswa', 'kelas']);

        if (!empty($selectedSekolah)) {
            $query->where('sekolah_id', $selectedSekolah);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pembayaran', 'like', "%{$search}%")
                  ->orWhere('tipe', 'like', "%{$search}%");
            });
        }

        $jenis = $query->paginate(10)->appends($request->query());

        return view('jenis_pembayaran.index', [
            'jenis'           => $jenis,
            'sekolah'         => $sekolah,
            'selectedSekolah' => $selectedSekolah,
            'search'          => $search,
        ]);
    }

    public function create()
    {
        $sekolah = Sekolah::all();
        $siswa = collect();
        $kelas = collect();
        
        return view('jenis_pembayaran.create', compact('sekolah', 'siswa', 'kelas'));
    }

    public function store(Request $request)
    {
        // ✅ TAMBAH handling untuk semester
        if ($request->tipe === 'bulanan' && is_numeric($request->jatuh_tempo)) {
            $request->merge([
                'jatuh_tempo' => date('Y') . '-01-' . str_pad($request->jatuh_tempo, 2, '0', STR_PAD_LEFT),
            ]);
        }

        // ✅ TAMBAH handling untuk semester
        if ($request->tipe === 'semester' && is_numeric($request->jatuh_tempo)) {
            $request->merge([
                'jatuh_tempo' => date('Y') . '-' . str_pad($request->jatuh_tempo, 2, '0', STR_PAD_LEFT) . '-01',
            ]);
        }

        $validated = $request->validate([
            'sekolah_id'      => 'required|exists:sekolah,id',
            'nama_pembayaran' => 'required|string|max:255',
            'tipe'            => 'required|in:sekali,bulanan,setahun,semester', // ✅ TAMBAH semester
            'nominal'         => 'required|numeric|min:0',
            'jatuh_tempo'     => 'required|date',
            'target_type'     => 'required|in:all,specific_students,specific_classes',
            'siswa_ids'       => 'required_if:target_type,specific_students|array',
            'siswa_ids.*'     => 'exists:siswa,id',
            'kelas_ids'       => 'required_if:target_type,specific_classes|array',
            'kelas_ids.*'     => 'exists:kelas,id',
        ]);

        DB::beginTransaction();
        try {
            $data = JenisPembayaran::create($validated);

            if ($request->target_type === 'specific_students' && $request->has('siswa_ids')) {
                $data->siswa()->attach($request->siswa_ids);
            }

            if ($request->target_type === 'specific_classes' && $request->has('kelas_ids')) {
                $data->kelas()->attach($request->kelas_ids);
            }

            LogAktivitas::create([
                'aktor_type' => 'admin',
                'aktor_id'   => Auth::guard('web')->id(),
                'aktivitas'  => 'Menambahkan jenis pembayaran: ' . $data->nama_pembayaran,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            // Generate bills automatically
            $this->_generateBillsFor($data);

            return redirect()->route('jenis_pembayaran.index')->with('success', 'Jenis pembayaran berhasil ditambahkan dan tagihan sedang dibuat.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])->withInput();
        }
    }


    public function edit($id)
    {
        $jenis = JenisPembayaran::with(['siswa', 'kelas'])->findOrFail($id);
        $sekolah = Sekolah::all();

        // ✅ PERBAIKAN: Langsung filter siswa berdasarkan id_sekolah
        $siswa = Siswa::where('id_sekolah', $jenis->sekolah_id)  // <- PERBAIKAN!
                    ->get();

        $kelas = Kelas::where('sekolah_id', $jenis->sekolah_id)->get();

        return view('jenis_pembayaran.edit', compact('jenis', 'sekolah', 'siswa', 'kelas'));
    }

    
    public function update(Request $request, $id)
    {
        // ✅ TAMBAH handling untuk semester (sama seperti store)
        if ($request->tipe === 'bulanan' && is_numeric($request->jatuh_tempo)) {
            $request->merge([
                'jatuh_tempo' => date('Y') . '-01-' . str_pad($request->jatuh_tempo, 2, '0', STR_PAD_LEFT),
            ]);
        }

        // ✅ TAMBAH handling untuk semester
        if ($request->tipe === 'semester' && is_numeric($request->jatuh_tempo)) {
            $request->merge([
                'jatuh_tempo' => date('Y') . '-' . str_pad($request->jatuh_tempo, 2, '0', STR_PAD_LEFT) . '-01',
            ]);
        }

        $validated = $request->validate([
            'sekolah_id'      => 'required|exists:sekolah,id',
            'nama_pembayaran' => 'required|string|max:255',
            'tipe'            => 'required|in:sekali,bulanan,setahun,semester', // ✅ TAMBAH semester
            'nominal'         => 'required|numeric|min:0',
            'jatuh_tempo'     => 'required|date',
            'target_type'     => 'required|in:all,specific_students,specific_classes',
            'siswa_ids'       => 'required_if:target_type,specific_students|array',
            'siswa_ids.*'     => 'exists:siswa,id',
            'kelas_ids'       => 'required_if:target_type,specific_classes|array',
            'kelas_ids.*'     => 'exists:kelas,id',
        ]);

        DB::beginTransaction();
        try {
            $data = JenisPembayaran::findOrFail($id);
            $data->update($validated);

            if ($request->target_type === 'specific_students') {
                $data->siswa()->sync($request->siswa_ids ?? []);
            } else {
                $data->siswa()->detach();
            }

            if ($request->target_type === 'specific_classes') {
                $data->kelas()->sync($request->kelas_ids ?? []);
            } else {
                $data->kelas()->detach();
            }

            DB::commit();

            // Generate bills automatically on update
            $this->_generateBillsFor($data);

            return redirect()->route('jenis_pembayaran.index')->with('success', 'Jenis pembayaran berhasil diupdate dan tagihan sedang dibuat ulang.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $jenis = JenisPembayaran::findOrFail($id);
            $namaPembayaran = $jenis->nama_pembayaran;

            // Hapus relasi sebelum menghapus item utama
            $jenis->siswa()->detach();
            $jenis->kelas()->detach();
            $jenis->delete();

            LogAktivitas::create([
                'aktor_type' => 'admin',
                'aktor_id'   => Auth::guard('web')->id(),
                'aktivitas'  => 'Menghapus jenis pembayaran: ' . $namaPembayaran,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            return redirect()->route('jenis_pembayaran.index')->with('success', 'Jenis pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }
    }

    public function getDataBySekolah($sekolahId)
    {
        // ✅ PERBAIKAN: Gunakan 'id_sekolah' bukan 'sekolah_id'
        $siswa = Siswa::where('id_sekolah', $sekolahId)  // <- PERBAIKAN!
                    ->with('kelas:id,nama_kelas,tingkat')
                    ->select('id', 'nama', 'nis', 'kelas_id')
                    ->orderBy('nama')
                    ->get()
                    ->map(function($s) {
                        return [
                            'id' => $s->id,
                            'nama' => $s->nama,
                            'nis' => $s->nis,
                            'kelas_id' => $s->kelas_id,
                            'kelas_nama' => $s->kelas ? "Kelas {$s->kelas->tingkat} - {$s->kelas->nama_kelas}" : 'Kelas tidak diketahui'
                        ];
                    });

        $kelas = Kelas::where('sekolah_id', $sekolahId)  // <- Ini sudah benar
                    ->select('id', 'nama_kelas', 'tingkat')
                    ->orderBy('tingkat')
                    ->orderBy('nama_kelas')
                    ->get();

        return response()->json([
            'siswa' => $siswa,
            'kelas' => $kelas
        ]);
    }

    
    /**
     * Generate bills for a given payment type.
     *
     * @param JenisPembayaran $jenis
     * @return void
     */
    private function _generateBillsFor(JenisPembayaran $jenis)
    {
        $eligibleSiswa = $jenis->getEligibleSiswa();

        foreach ($eligibleSiswa as $siswa) {
            if ($jenis->tipe === 'bulanan') {
                for ($month = 1; $month <= 12; $month++) {
                    $periode = Carbon::now()->year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);

                    \App\Models\Tagihan::updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                            'jenis_pembayaran_id' => $jenis->id,
                            'periode' => $periode,
                        ],
                        [
                            'id_sekolah' => $siswa->id_sekolah,
                            'nama_tagihan' => $jenis->nama_pembayaran . ' - ' . Carbon::parse($periode)->format('F Y'),
                            'nominal' => $jenis->nominal,
                            'tipe' => $jenis->tipe,
                            'tanggal_jatuh_tempo' => Carbon::parse($periode)->day(10),
                            'status' => 'belum',
                        ]
                    );
                }
            } 
            // ✅ TAMBAH handling untuk semester
            elseif ($jenis->tipe === 'semester') {
                $jatuhTempo = Carbon::parse($jenis->jatuh_tempo);
                $bulanPertama = $jatuhTempo->month;
                $bulanKedua = $bulanPertama + 6; // 6 bulan kemudian

                // Semester 1
                $periode1 = Carbon::now()->year . '-' . str_pad($bulanPertama, 2, '0', STR_PAD_LEFT);
                \App\Models\Tagihan::updateOrCreate(
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
                        'tanggal_jatuh_tempo' => Carbon::parse($periode1)->day(10),
                        'status' => 'belum',
                    ]
                );

                // Semester 2
                $periode2 = Carbon::now()->year . '-' . str_pad($bulanKedua, 2, '0', STR_PAD_LEFT);
                \App\Models\Tagihan::updateOrCreate(
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
                        'tanggal_jatuh_tempo' => Carbon::parse($periode2)->day(10),
                        'status' => 'belum',
                    ]
                );
            }
            else { // For 'sekali', 'setahun', etc.
                \App\Models\Tagihan::updateOrCreate(
                    [
                        'siswa_id' => $siswa->id,
                        'jenis_pembayaran_id' => $jenis->id,
                    ],
                    [
                        'id_sekolah' => $siswa->id_sekolah,
                        'nama_tagihan' => $jenis->nama_pembayaran,
                        'nominal' => $jenis->nominal,
                        'tipe' => $jenis->tipe,
                        'periode' => Carbon::now()->format('Y-m'),
                        'tanggal_jatuh_tempo' => $jenis->jatuh_tempo,
                        'status' => 'belum',
                    ]
                );
            }
        }
    }
}
