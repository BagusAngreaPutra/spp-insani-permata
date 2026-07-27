<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Sekolah;
use App\Models\RiwayatKelulusan;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Log;

class KelulusanKelasController extends Controller
{
    /**
     * Menampilkan daftar siswa yang berada di kelas tingkat akhir
     * sesuai durasi pendidikan sekolahnya.
     */
    public function index(Request $request)
    {
        $sekolah = Sekolah::all();
        $tahunAjaran = TahunAjaran::all();

        // Ambil input filter dari request
        $selectedSekolah = $request->input('sekolah_id');
        $selectedKelas = $request->input('kelas_id');
        $selectedTahunAjaran = $request->input('tahun_ajaran_id');
        $search = $request->input('search');

        // Ambil kelas hanya jika sekolah dipilih
        $kelas = collect();
        if (!empty($selectedSekolah)) {
            $kelas = Kelas::where('sekolah_id', $selectedSekolah)->get();
        }

        // Query siswa dengan relasi
        $query = Siswa::with(['kelas.sekolah', 'tahunAjaran'])
            ->where('status', '!=', 'lulus');

        if (!empty($selectedSekolah)) {
            $query->whereHas('kelas', function ($q) use ($selectedSekolah) {
                $q->where('sekolah_id', $selectedSekolah);
            });
        }

        if (!empty($selectedKelas)) {
            $query->where('kelas_id', $selectedKelas);
        }

        if (!empty($selectedTahunAjaran)) {
            $query->where('tahun_ajaran_id', $selectedTahunAjaran);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                ->orWhere('nis', 'like', "%{$search}%")
                ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // Filter manual: siswa yang tingkatnya = durasi sekolah
        $siswaSiapLulus = $query->get()->filter(function ($siswa) {
            $kelas = $siswa->kelas;
            $sekolah = $kelas?->sekolah;

            if (!$kelas || !$sekolah) return false;

            $tingkat = intval($kelas->tingkat);
            $durasi = intval($sekolah->durasi_pendidikan);

            Log::info('Cek kelulusan:', [
                'siswa' => $siswa->nama,
                'tingkat' => $tingkat,
                'durasi' => $durasi,
                'lolos' => $tingkat === $durasi
            ]);

            return $tingkat === $durasi;
        });

        return view('kelulusan_kelas.index', [
            'siswaSiapLulus' => $siswaSiapLulus,
            'sekolah' => $sekolah,
            'kelas' => $kelas,
            'tahunAjaran' => $tahunAjaran,
            'selectedSekolah' => $selectedSekolah,
            'selectedKelas' => $selectedKelas,
            'selectedTahunAjaran' => $selectedTahunAjaran,
            'search' => $search,
        ]);
    }


    /**
     * Mengubah status siswa menjadi "lulus" dan menyimpan ke riwayat_kelulusan.
     */
    public function updateStatus(Request $request, $id)
    {
        $siswa = Siswa::with(['kelas.sekolah'])->findOrFail($id);

        $kelas = $siswa->kelas;
        $sekolah = $kelas?->sekolah;

        if ($kelas && $sekolah && (int) $kelas->tingkat === (int) $sekolah->durasi_pendidikan) {
            $siswa->status = 'lulus';
            $siswa->save();

            RiwayatKelulusan::create([
                'siswa_id'        => $siswa->id,
                'sekolah_id'      => $sekolah->id, // Menambahkan sekolah_id
                'kelas_id'        => $siswa->kelas_id,
                'tahun_ajaran_id' => $siswa->tahun_ajaran_id ?? 0,
                'tanggal_lulus'   => now()->toDateString(),
                'keterangan'      => 'Lulus melalui proses kelulusan kelas',
            ]);

            return redirect()->route('kelulusan.index')
                ->with('success', 'Status siswa berhasil diubah menjadi Lulus dan dicatat di riwayat.');
        }

        return redirect()->route('kelulusan.index')
            ->with('error', 'Siswa ini belum berada di tingkat akhir sesuai durasi pendidikan.');
    }
}
