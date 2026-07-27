<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Sekolah;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    // ✅ Tampilkan daftar pengeluaran
    public function index(Request $request)
    {
        $pengeluaran = Pengeluaran::with('sekolah')->latest()->paginate(10);

        // 🔹 Catat log aktivitas: lihat daftar pengeluaran
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Melihat daftar pengeluaran',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('pengeluaran.index', compact('pengeluaran'));
    }

    // ✅ Form tambah pengeluaran
    public function create()
    {
        $sekolah = Sekolah::all();
        return view('pengeluaran.create', compact('sekolah'));
    }

    // ✅ Simpan data baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sekolah_id' => 'required|exists:sekolah,id',
            'tanggal'    => 'required|date',
            'jumlah'     => 'required|numeric',
            'keperluan'  => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $pengeluaran = Pengeluaran::create($validated);

        // 🔹 Catat log aktivitas: tambah pengeluaran
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Menambahkan pengeluaran ID: '.$pengeluaran->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    // ✅ Form edit pengeluaran
    public function edit(Pengeluaran $pengeluaran)
    {
        $sekolah = Sekolah::all();
        return view('pengeluaran.edit', compact('pengeluaran', 'sekolah'));
    }

    // ✅ Update data pengeluaran
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'sekolah_id' => 'required|exists:sekolah,id',
            'tanggal'    => 'required|date',
            'jumlah'     => 'required|numeric',
            'keperluan'  => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        // ✅ Simpan data lama sebelum update
        $oldData = $pengeluaran->only([
            'sekolah_id','tanggal','jumlah','keperluan','keterangan'
        ]);

        // ✅ Update data
        $pengeluaran->update($validated);

        // ✅ Bandingkan perubahan
        $changedFields = [];
        foreach ($validated as $field => $newValue) {
            $oldValue = $oldData[$field] ?? null;
            if ($newValue != $oldValue) {
                // Format khusus untuk jumlah
                if ($field === 'jumlah') {
                    $oldValue = 'Rp ' . number_format((float)$oldValue, 0, ',', '.');
                    $newValue = 'Rp ' . number_format((float)$newValue, 0, ',', '.');
                }
                $changedFields[] = ucfirst($field) . ": '{$oldValue}' → '{$newValue}'";
            }
        }

        $detailPerubahan = count($changedFields) > 0
            ? implode(', ', $changedFields)
            : 'Tidak ada perubahan data.';

        // 🔹 Catat log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => "Memperbarui Pengeluaran ID {$pengeluaran->id}. Perubahan: {$detailPerubahan}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil diperbarui.');
    }


    // ✅ Hapus data pengeluaran
    public function destroy(Request $request, Pengeluaran $pengeluaran)
    {
        $deletedId = $pengeluaran->id;
        $pengeluaran->delete();

        // 🔹 Catat log aktivitas: hapus pengeluaran
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Menghapus pengeluaran ID: '.$deletedId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil dihapus.');
    }
}
