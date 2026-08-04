<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sekolah;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\LogAktivitas;
use App\Models\RiwayatKenaikan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class KenaikanKelasController extends Controller
{
    // 👉 Menampilkan form pilih sekolah & tahun ajaran baru
    public function index()
    {
        $semuaSekolah = Sekolah::all();
        $tahunAjaran = TahunAjaran::validPeriods();

        $sekolah = $semuaSekolah->first(); // default, bisa diganti logic-nya kalau perlu
        return view('kenaikan.index', compact('semuaSekolah', 'sekolah', 'tahunAjaran'));
    }

    // 👉 Proses kenaikan kelas dan catat riwayat
    public function proses(Request $request)
    {
        $request->validate([
            'sekolah_id'      => 'required|exists:sekolah,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);

        $sekolahId       = $request->sekolah_id;
        $tahunAjaranBaru = $request->tahun_ajaran_id;
        $jumlahNaik      = 0;
        $jumlahLulus     = 0;
        $jumlahGagal     = 0;

        // Array untuk menyimpan data siswa yang diproses
        $promotedStudents = [];
        $graduatedStudents = [];

        DB::transaction(function () use ($sekolahId, $tahunAjaranBaru, &$jumlahNaik, &$jumlahLulus, &$jumlahGagal, &$promotedStudents, &$graduatedStudents) {
            // Ambil semua siswa aktif pada sekolah yang dipilih
            $siswaList = Siswa::where('id_sekolah', $sekolahId)
                ->where('status', 'aktif')
                ->with(['kelas', 'kelas.sekolah'])
                ->get();

            foreach ($siswaList as $siswa) {
                $kelasLama = $siswa->kelas;
                if (!$kelasLama) {
                    Log::error("❌ Kelas lama tidak ditemukan untuk siswa ID {$siswa->id}");
                    $jumlahGagal++;
                    continue;
                }

                $durasiPendidikan = $kelasLama->sekolah->durasi_pendidikan;

                // ✅ Jika kelas tingkat terakhir → lulus
                if ($kelasLama->tingkat >= $durasiPendidikan) {
                    $siswa->status = 'lulus';
                    $siswa->save();
                    $jumlahLulus++;

                    $graduatedStudents[] = [
                        'id' => $siswa->id, // tambahkan ini
                        'nis' => $siswa->nis,
                        'nama' => $siswa->nama,
                        'kelas_lama_tingkat' => $kelasLama->tingkat,
                        'kelas_lama_nama' => $kelasLama->nama_kelas,
                        'status' => 'Lulus'
                    ];


                    RiwayatKenaikan::create([
                        'siswa_id'        => $siswa->id,
                        'kelas_awal_id'   => $kelasLama->id,
                        'kelas_baru_id'   => $kelasLama->id,
                        'tahun_ajaran_id' => $tahunAjaranBaru,
                        'tanggal_kenaikan'=> now()->toDateString(),
                        'keterangan'      => 'Lulus dari tingkat ' . $kelasLama->tingkat,
                    ]);
                    continue;
                }

                // ✅ Cari kelas baru dengan tingkat +1 dan nama kelas sama
                $kelasBaru = Kelas::where('sekolah_id', $sekolahId)
                    ->where('tingkat', $kelasLama->tingkat + 1)
                    ->where('nama_kelas', $kelasLama->nama_kelas)
                    ->where('tahun_ajaran_id', $tahunAjaranBaru)
                    ->first();

                // Jika tidak ketemu nama_kelas yang sama, cari kelas lain dengan tingkat yg sesuai
                if (!$kelasBaru) {
                    $kelasBaru = Kelas::where('sekolah_id', $sekolahId)
                        ->where('tingkat', $kelasLama->tingkat + 1)
                        ->where('tahun_ajaran_id', $tahunAjaranBaru)
                        ->first();
                }

                // Jika ketemu kelas baru
                if ($kelasBaru) {
                    $siswa->kelas_id = $kelasBaru->id;
                    $siswa->save();
                    $jumlahNaik++;

                    $promotedStudents[] = [
                        'id' => $siswa->id, // tambahkan ini
                        'nis' => $siswa->nis,
                        'nama' => $siswa->nama,
                        'kelas_lama_tingkat' => $kelasLama->tingkat,
                        'kelas_lama_nama' => $kelasLama->nama_kelas,
                        'kelas_baru_tingkat' => $kelasBaru->tingkat,
                        'kelas_baru_nama' => $kelasBaru->nama_kelas,
                        'status' => 'Naik Kelas'
                    ];


                    RiwayatKenaikan::create([
                        'siswa_id'        => $siswa->id,
                        'kelas_awal_id'   => $kelasLama->id,
                        'kelas_baru_id'   => $kelasBaru->id,
                        'tahun_ajaran_id' => $tahunAjaranBaru,
                        'tanggal_kenaikan'=> now()->toDateString(),
                        'keterangan'      => 'Naik dari tingkat ' . $kelasLama->tingkat . ' ke ' . $kelasBaru->tingkat,
                    ]);
                } else {
                    Log::error("❌ Kelas baru tidak ditemukan (tingkat ".($kelasLama->tingkat+1).") untuk siswa ID {$siswa->id}");
                    $jumlahGagal++;
                }
            }
        });

        // ✅ Catat log aktivitas setelah proses selesai
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => "Kenaikan kelas sekolah {$sekolahId} (Tahun ajaran baru {$tahunAjaranBaru}) | Naik: {$jumlahNaik} | Lulus: {$jumlahLulus} | Gagal: {$jumlahGagal}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->back()
            ->with('success', '✅ Kenaikan kelas berhasil diproses!')
            ->with('promoted_students', $promotedStudents)
            ->with('graduated_students', $graduatedStudents);
    }
    public function cancelPromotion($siswa_id)
    {
        DB::beginTransaction();

        try {
            $siswa = Siswa::findOrFail($siswa_id);

            // Ambil riwayat kenaikan terakhir
            $riwayat = RiwayatKenaikan::where('siswa_id', $siswa_id)
                ->orderBy('tanggal_kenaikan', 'desc')
                ->first();

            if (!$riwayat) {
                return redirect()->back()->with('error', '❌ Riwayat kenaikan tidak ditemukan.');
            }

            // Kembalikan kelas dan status siswa
            $siswa->kelas_id = $riwayat->kelas_awal_id;

            // Jika siswa sebelumnya diluluskan, kembalikan ke status aktif
            if ($siswa->status === 'lulus' && $riwayat->kelas_awal_id !== $riwayat->kelas_baru_id) {
                // Kasus aneh, tidak terjadi biasanya
                $siswa->status = 'aktif';
            } elseif ($siswa->status === 'lulus') {
                // Lulus → rollback
                $siswa->status = 'aktif';
            }

            $siswa->save();

            // Hapus riwayat kenaikan
            $riwayat->delete();

            // Catat log
            LogAktivitas::create([
                'aktor_type' => 'admin',
                'aktor_id'   => Auth::guard('web')->id(),
                'aktivitas'  => "Membatalkan kenaikan/lulusan siswa ID {$siswa_id}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', '✅ Kenaikan/lulusan siswa berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal membatalkan kenaikan: " . $e->getMessage());
            return redirect()->back()->with('error', '❌ Terjadi kesalahan saat membatalkan kenaikan.');
        }
    }
}
