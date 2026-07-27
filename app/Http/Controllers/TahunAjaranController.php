<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use App\Models\LogAktivitas; // 👉 tambahkan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TahunAjaranController extends Controller
{
    // 📌 Menampilkan daftar tahun ajaran
    public function index(Request $request)
    {
        $tahunAjaran = TahunAjaran::all();

        // ✅ log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Melihat daftar tahun ajaran',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('tahun_ajaran.index', compact('tahunAjaran'));
    }

    // 📌 Form tambah tahun ajaran
    public function create(Request $request)
    {
        // ✅ log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Membuka form tambah tahun ajaran',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('tahun_ajaran.create');
    }

    // 📌 Proses simpan
    public function store(Request $request)
    {
        $request->validate([
            'nama_tahun' => 'required',
            'aktif'      => 'boolean'
        ]);

        $tahun = TahunAjaran::create([
            'nama_tahun' => $request->nama_tahun,
            'aktif'      => $request->aktif ? true : false,
        ]);

        // ✅ log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Menambahkan tahun ajaran: ' . $tahun->nama_tahun,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('tahun_ajaran.index')->with('success','Tahun ajaran ditambahkan.');
    }

    // 📌 Form edit
    public function edit(Request $request, TahunAjaran $tahun_ajaran)
    {
        // ✅ log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Membuka form edit tahun ajaran: ' . $tahun_ajaran->nama_tahun,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('tahun_ajaran.edit', compact('tahun_ajaran'));
    }

    // 📌 Proses update
    public function update(Request $request, TahunAjaran $tahun_ajaran)
    {
        $request->validate([
            'nama_tahun' => 'required',
            'aktif'      => 'boolean'
        ]);

        // Simpan data lama sebelum update
        $oldData = $tahun_ajaran->only(['nama_tahun', 'aktif']);

        // Update data
        $tahun_ajaran->update([
            'nama_tahun' => $request->nama_tahun,
            'aktif'      => $request->aktif ? true : false,
        ]);

        // Bandingkan perubahan
        $changes = [];
        $newData = [
            'nama_tahun' => $request->nama_tahun,
            'aktif'      => $request->aktif ? true : false,
        ];

        foreach ($newData as $field => $newValue) {
            $oldValue = $oldData[$field];
            // Konversi boolean ke string biar mudah dibaca
            if ($field === 'aktif') {
                $oldValue = $oldValue ? 'Aktif' : 'Tidak aktif';
                $newValue = $newValue ? 'Aktif' : 'Tidak aktif';
            }
            if ($oldValue != $newValue) {
                $label = ucfirst(str_replace('_',' ', $field));
                $changes[] = "{$label}: '{$oldValue}' → '{$newValue}'";
            }
        }

        $detailLog = count($changes) > 0
            ? implode(', ', $changes)
            : 'Tidak ada perubahan data.';

        // ✅ log aktivitas detail
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => "Memperbarui tahun ajaran ID {$tahun_ajaran->id} ({$tahun_ajaran->nama_tahun}). Perubahan: {$detailLog}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('tahun_ajaran.index')->with('success','Tahun ajaran diperbarui.');
    }


    // 📌 Hapus
    public function destroy(Request $request, TahunAjaran $tahun_ajaran)
    {
        $nama = $tahun_ajaran->nama_tahun;
        $tahun_ajaran->delete();

        // ✅ log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Menghapus tahun ajaran: ' . $nama,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('tahun_ajaran.index')->with('success','Tahun ajaran dihapus.');
    }
}
