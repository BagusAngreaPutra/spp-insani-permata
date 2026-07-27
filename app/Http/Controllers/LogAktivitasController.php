<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LogAktivitasController extends Controller
{
    /**
     * Tampilkan daftar log aktivitas.
     */
    public function index(Request $request)
    {
        $query = LogAktivitas::query()->latest();

        // Jika ada pencarian
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('aktivitas', 'like', "%{$searchTerm}%")
                  ->orWhereHas('admin', function($adminQuery) use ($searchTerm) {
                      $adminQuery->where('nama_admin', 'like', "%{$searchTerm}%")
                                 ->orWhere('username', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('siswa', function($siswaQuery) use ($searchTerm) {
                      $siswaQuery->where('nama', 'like', "%{$searchTerm}%")
                                 ->orWhere('nis', 'like', "%{$searchTerm}%");
                  });
            });
        }

        // Jika mau filter berdasarkan aktor_type (opsional)
        if ($request->filled('aktor_type')) {
            $query->where('aktor_type', $request->aktor_type);
        }

        $logAktivitas = $query->paginate(20);

        // ✅ Catat log bahwa admin membuka daftar log
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Mengakses daftar log aktivitas',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('log_aktivitas.index', compact('logAktivitas'));
    }

    /**
     * Tampilkan detail log aktivitas tertentu.
     */
    public function show(Request $request, $id)
    {
        $log = LogAktivitas::findOrFail($id);

        // Ambil nama aktor
        $namaAktor = '-';
        if ($log->aktor_type === 'admin') {
            $namaAktor = $log->admin?->nama_admin ?? 'Admin tidak ditemukan';
        } elseif ($log->aktor_type === 'siswa') {
            $namaAktor = $log->siswa?->nama ?? 'Siswa tidak ditemukan';
        }

        // ✅ Catat log bahwa admin membuka detail log tertentu
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Membuka detail log aktivitas (ID Log: ' . $log->id . ')',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('log_aktivitas.show', compact('log', 'namaAktor'));
    }

    public function destroy(Request $request, $id): RedirectResponse
    {
        // Cari log yang akan dihapus
        $log = LogAktivitas::findOrFail($id);

        // Simpan informasi untuk log aktivitas admin
        $aktivitasDetail = "Menghapus log aktivitas (ID Log: {$log->id}, Aktivitas: {$log->aktivitas})";

        // Hapus log tersebut
        $log->delete();

        // Catat log bahwa admin melakukan penghapusan log
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => $aktivitasDetail,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('log_aktivitas.index')->with('success', 'Log aktivitas berhasil dihapus.');
    }
    
    public function destroyAll(Request $request)
    {
        // hapus semua log
        LogAktivitas::truncate();

        // catat log bahwa admin menghapus semua log
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::guard('web')->id(),
            'aktivitas'  => 'Menghapus SEMUA log aktivitas',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('log_aktivitas.index')->with('success', 'Semua log aktivitas berhasil dihapus.');
    }
}
