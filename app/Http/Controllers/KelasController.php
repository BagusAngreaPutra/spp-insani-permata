<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\TahunAjaran;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        // 🔹 Ambil semua sekolah untuk dropdown
        $sekolah = Sekolah::all();

        // 🔹 Ambil input dari form
        $selectedSekolah = $request->input('sekolah_id'); // filter sekolah
        $search          = $request->input('search');     // satu input untuk nama_kelas atau tingkat

        // 🔹 Query awal
        $query = Kelas::with(['sekolah', 'tahunAjaran']);

        // 🔹 Filter sekolah
        if (!empty($selectedSekolah)) {
            $query->where('sekolah_id', $selectedSekolah);
        }

        // 🔹 Search gabungan: nama_kelas atau tingkat
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama_kelas', 'like', "%{$search}%")
                  ->orWhere('tingkat', 'like', "%{$search}%");
            });
        }

        // 🔹 Eksekusi query
        $kelas = $query->paginate(10)->appends($request->query());

        return view('kelas.index', [
            'kelas'           => $kelas,
            'sekolah'         => $sekolah,
            'selectedSekolah' => $selectedSekolah,
            'search'          => $search,
        ]);
    }

    public function create()
    {
        $sekolah = Sekolah::all();
        $tahunAjaran = TahunAjaran::validPeriods()->where('aktif', true)->values();

        // buat array tingkat untuk ditampilkan di dropdown
        $tingkatOptions = range(1, 6);

        return view('kelas.create', compact('sekolah', 'tahunAjaran', 'tingkatOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sekolah_id'      => 'required|exists:sekolah,id',
            'nama_kelas'      => 'required|string|max:100',
            'tingkat'         => ['required', Rule::in([1,2,3,4,5,6])],
            'tahun_ajaran_id' => 'nullable|exists:tahun_ajaran,id',
        ]);

        $namaKelas = $request->nama_kelas === '-' ? '' : $request->nama_kelas;

        $kelas = Kelas::create([
            'sekolah_id'      => $request->sekolah_id,
            'nama_kelas'      => $namaKelas,
            'tingkat'         => $request->tingkat,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
        ]);

        // ✅ Catat log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Menambahkan kelas: ' . $kelas->nama_kelas . ' (Tingkat ' . $kelas->tingkat . ')',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        $sekolah = Sekolah::all();
        $tahunAjaran = TahunAjaran::validPeriods();
        $tingkatOptions = range(1, 6);

        return view('kelas.edit', compact('kela', 'sekolah', 'tahunAjaran', 'tingkatOptions'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'sekolah_id'      => 'required|exists:sekolah,id',
            'nama_kelas'      => 'required|string|max:100',
            'tingkat'         => ['required', Rule::in([1,2,3,4,5,6])],
            'tahun_ajaran_id' => 'nullable|exists:tahun_ajaran,id',
        ]);

        // ✅ Simpan nilai lama
        $oldData = $kela->only(['sekolah_id','nama_kelas','tingkat','tahun_ajaran_id']);

        // ✅ Update data
        $namaKelas = $request->nama_kelas === '-' ? '' : $request->nama_kelas;
        
        $kela->update([
            'sekolah_id'      => $request->sekolah_id,
            'nama_kelas'      => $namaKelas,
            'tingkat'         => $request->tingkat,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
        ]);

        // ✅ Cari field yang berubah
        $changes = [];
        $newData = [
            'sekolah_id'      => $request->sekolah_id,
            'nama_kelas'      => $namaKelas,
            'tingkat'         => $request->tingkat,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
        ];

        foreach ($newData as $field => $newValue) {
            $oldValue = $oldData[$field];
            if ($newValue != $oldValue) {
                $changes[] = ucfirst(str_replace('_',' ',$field)).': "'.$oldValue.'" → "'.$newValue.'"';
            }
        }

        // ✅ Rangkai deskripsi aktivitas
        $aktivitas = 'Memperbarui kelas: '.$kela->nama_kelas.' (ID: '.$kela->id.')';
        if (!empty($changes)) {
            $aktivitas .= ' | Perubahan: '.implode('; ', $changes);
        }

        // ✅ Catat log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => $aktivitas,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }


    public function destroy(Kelas $kela)
    {
        $nama = $kela->nama_kelas;
        $tingkat = $kela->tingkat;

        $kela->delete();

        // ✅ Catat log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Menghapus kelas: ' . $nama . ' (Tingkat ' . $tingkat . ')',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil dihapus.');
    }
    
}
