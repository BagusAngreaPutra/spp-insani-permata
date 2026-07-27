<?php
// app/Http/Controllers/SekolahController.php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SekolahController extends Controller
{
    public function index(Request $request)
    {
        $query = Sekolah::with(['siswa']);

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_sekolah', 'LIKE', "%{$search}%")
                  ->orWhere('kode_sekolah', 'LIKE', "%{$search}%");
            });
        }

        // Order by nama sekolah
        $query->orderBy('nama_sekolah', 'asc');

        // Gunakan pagination
        $sekolah = $query->paginate(10);

        // Statistics
        $totalSekolah = Sekolah::count();
        $sekolahDenganKode = Sekolah::whereNotNull('kode_sekolah')->count();
        $totalSiswa = Siswa::count();

        return view('sekolah.index', compact(
            'sekolah',
            'totalSekolah',
            'sekolahDenganKode',
            'totalSiswa'
        ));
    }

    public function create()
    {
        return view('sekolah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'kode_sekolah' => 'required|string|max:10|unique:sekolah,kode_sekolah',
            'alamat' => 'required|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'durasi_pendidikan' => 'nullable|integer|min:1|max:12'
        ], [
            'nama_sekolah.required' => 'Nama sekolah wajib diisi.',
            'kode_sekolah.required' => 'Kode sekolah wajib diisi.',
            'kode_sekolah.unique' => 'Kode sekolah sudah digunakan, silakan pilih kode lain.',
            'kode_sekolah.max' => 'Kode sekolah maksimal 10 karakter.',
            'alamat.required' => 'Alamat sekolah wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'durasi_pendidikan.integer' => 'Durasi pendidikan harus berupa angka.',
            'durasi_pendidikan.min' => 'Durasi pendidikan minimal 1 tahun.',
            'durasi_pendidikan.max' => 'Durasi pendidikan maksimal 12 tahun.'
        ]);

        Sekolah::create([
            'nama_sekolah' => $request->nama_sekolah,
            'kode_sekolah' => strtoupper($request->kode_sekolah),
            'alamat' => $request->alamat,
            'kontak' => '', // Menambahkan field kontak dengan nilai default kosong
            'telepon' => $request->telepon,
            'email' => $request->email,
            'durasi_pendidikan' => $request->durasi_pendidikan
        ]);

        return redirect()
            ->route('sekolah.index')
            ->with('success', 'Sekolah berhasil ditambahkan!');
    }

    public function show(Sekolah $sekolah)
    {
        $sekolah->load(['siswa', 'kelas']);

        return view('sekolah.show', compact('sekolah'));
    }

    public function edit(Sekolah $sekolah)
    {
        return view('sekolah.edit', compact('sekolah'));
    }

    public function update(Request $request, Sekolah $sekolah)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'kode_sekolah' => 'required|string|max:10|unique:sekolah,kode_sekolah,' . $sekolah->id,
            'alamat' => 'required|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'durasi_pendidikan' => 'nullable|integer|min:1|max:12'
        ], [
            'nama_sekolah.required' => 'Nama sekolah wajib diisi.',
            'kode_sekolah.required' => 'Kode sekolah wajib diisi.',
            'kode_sekolah.unique' => 'Kode sekolah sudah digunakan, silakan pilih kode lain.',
            'kode_sekolah.max' => 'Kode sekolah maksimal 10 karakter.',
            'alamat.required' => 'Alamat sekolah wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'durasi_pendidikan.integer' => 'Durasi pendidikan harus berupa angka.',
            'durasi_pendidikan.min' => 'Durasi pendidikan minimal 1 tahun.',
            'durasi_pendidikan.max' => 'Durasi pendidikan maksimal 12 tahun.'
        ]);

        $sekolah->update([
            'nama_sekolah' => $request->nama_sekolah,
            'kode_sekolah' => strtoupper($request->kode_sekolah),
            'alamat' => $request->alamat,
            'kontak' => '', // Menambahkan field kontak dengan nilai default kosong
            'telepon' => $request->telepon,
            'email' => $request->email,
            'durasi_pendidikan' => $request->durasi_pendidikan
        ]);

        return redirect()
            ->route('sekolah.index')
            ->with('success', 'Data sekolah berhasil diperbarui!');
    }

    public function destroy(Sekolah $sekolah)
    {
        // Cek apakah sekolah memiliki siswa
        if ($sekolah->siswa()->count() > 0) {
            return redirect()
                ->route('sekolah.index')
                ->with('error', 'Tidak dapat menghapus sekolah yang masih memiliki siswa!');
        }

        $namaSekolah = $sekolah->nama_sekolah;
        $sekolah->delete();

        return redirect()
            ->route('sekolah.index')
            ->with('success', "Sekolah '{$namaSekolah}' berhasil dihapus!");
    }
}