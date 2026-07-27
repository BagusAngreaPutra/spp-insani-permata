<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class PemasukanController extends Controller
{
    /**
     * Tampilkan semua data pemasukan
     */
    public function index()
    {
        $pemasukan = Pemasukan::with('sekolah')->latest()->paginate(10);
        return view('pemasukan.index', compact('pemasukan'));
    }

    /**
     * Tampilkan form tambah pemasukan
     */
    public function create()
    {
        $sekolah = Sekolah::all();
        return view('pemasukan.create', compact('sekolah'));
    }

    /**
     * Simpan data pemasukan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sekolah_id' => 'required|exists:sekolah,id',
            'tanggal'    => 'required|date',
            'jumlah'     => 'required|numeric|min:0',
            'sumber'     => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $pemasukan = Pemasukan::create($validated);

        // ✅ Log Aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => "Menambahkan pemasukan ID {$pemasukan->id} sejumlah Rp {$pemasukan->jumlah}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('pemasukan.index')->with('success', 'Data pemasukan berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail pemasukan
     */
    public function show($id)
    {
        $pemasukan = Pemasukan::with('sekolah')->findOrFail($id);
        return view('pemasukan.show', compact('pemasukan'));
    }

    /**
     * Tampilkan form edit
     */
    public function edit($id)
    {
        $pemasukan = Pemasukan::findOrFail($id);
        $sekolah   = Sekolah::all();
        return view('pemasukan.edit', compact('pemasukan', 'sekolah'));
    }

    /**
     * Update data pemasukan
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'sekolah_id' => 'required|exists:sekolah,id',
            'tanggal'    => 'required|date',
            'jumlah'     => 'required|numeric|min:0',
            'sumber'     => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $pemasukan = Pemasukan::findOrFail($id);

        // ✅ Simpan data lama untuk perbandingan
        $oldData = $pemasukan->only([
            'sekolah_id','tanggal','jumlah','sumber','keterangan'
        ]);

        // ✅ Update dengan data baru
        $pemasukan->update($validated);

        // ✅ Cari perubahan detail
        $changedFields = [];
        foreach ($validated as $field => $newValue) {
            $oldValue = $oldData[$field] ?? null;
            if ($newValue != $oldValue) {
                // Format angka jika field jumlah
                if ($field === 'jumlah') {
                    $oldValue = 'Rp ' . number_format($oldValue, 0, ',', '.');
                    $newValue = 'Rp ' . number_format($newValue, 0, ',', '.');
                }
                $changedFields[] = ucfirst($field) . ": '{$oldValue}' → '{$newValue}'";
            }
        }

        // ✅ Buat deskripsi perubahan
        $detailPerubahan = count($changedFields) > 0
            ? implode(', ', $changedFields)
            : 'Tidak ada perubahan data.';

        // ✅ Simpan log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => "Memperbarui Pemasukan ID {$pemasukan->id}. Perubahan: {$detailPerubahan}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('pemasukan.index')->with('success', 'Data pemasukan berhasil diperbarui.');
    }


    /**
     * Hapus data pemasukan
     */
    public function destroy(Request $request, $id)
    {
        $pemasukan = Pemasukan::findOrFail($id);
        $pemasukan->delete();

        // ✅ Log Aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => "Menghapus pemasukan ID {$pemasukan->id} sejumlah Rp {$pemasukan->jumlah}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('pemasukan.index')->with('success', 'Data pemasukan berhasil dihapus.');
    }
}
