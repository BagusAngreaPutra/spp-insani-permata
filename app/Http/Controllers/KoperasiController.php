<?php

namespace App\Http\Controllers;

use App\Models\Koperasi;
use App\Models\LogAktivitas;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KoperasiController extends Controller
{
    public function index(Request $request)
    {
        $sekolah = Sekolah::orderBy('nama_sekolah')->get();
        $selectedSekolah = $request->input('sekolah_id');
        $selectedKategori = $request->input('kategori');
        $selectedStatus = $request->input('status');
        $search = $request->input('search');

        $query = Koperasi::with('sekolah')->latest();

        if (!empty($selectedSekolah)) {
            $query->where('sekolah_id', $selectedSekolah);
        }

        if (!empty($selectedKategori)) {
            $query->where('kategori', $selectedKategori);
        }

        if (!empty($selectedStatus)) {
            $query->where('status', $selectedStatus);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                  ->orWhere('nama_barang', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $koperasi = $query->paginate(10)->appends($request->query());
        $kategoriList = $this->kategoriList();

        return view('koperasi.index', compact(
            'koperasi',
            'sekolah',
            'kategoriList',
            'selectedSekolah',
            'selectedKategori',
            'selectedStatus',
            'search'
        ));
    }

    public function create()
    {
        $sekolah = Sekolah::orderBy('nama_sekolah')->get();
        $kategoriList = $this->kategoriList();

        return view('koperasi.create', compact('sekolah', 'kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        $koperasi = Koperasi::create($validated);

        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id' => Auth::guard('web')->id(),
            'aktivitas' => 'Menambahkan barang koperasi: ' . $koperasi->nama_barang,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('koperasi.index')->with('success', 'Barang koperasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $koperasi = Koperasi::findOrFail($id);
        $sekolah = Sekolah::orderBy('nama_sekolah')->get();
        $kategoriList = $this->kategoriList();

        return view('koperasi.edit', compact('koperasi', 'sekolah', 'kategoriList'));
    }

    public function editStok($id)
    {
        $koperasi = Koperasi::with('sekolah')->findOrFail($id);

        return view('koperasi.stok', compact('koperasi'));
    }

    public function updateStok(Request $request, $id)
    {
        $validated = $request->validate([
            'tipe' => 'required|in:tambah,kurang,set',
            'jumlah' => 'required|integer|min:0',
            'catatan' => 'nullable|string|max:255',
        ]);

        $koperasi = Koperasi::findOrFail($id);
        $stokAwal = $koperasi->stok;

        if ($validated['tipe'] === 'tambah') {
            $koperasi->stok += $validated['jumlah'];
            $aktivitas = 'Menambahkan stok barang koperasi: ';
        } elseif ($validated['tipe'] === 'kurang') {
            if ($validated['jumlah'] > $koperasi->stok) {
                return back()
                    ->withInput()
                    ->withErrors(['jumlah' => 'Jumlah pengurangan tidak boleh melebihi stok saat ini.']);
            }

            $koperasi->stok -= $validated['jumlah'];
            $aktivitas = 'Mengurangi stok barang koperasi: ';
        } else {
            $koperasi->stok = $validated['jumlah'];
            $aktivitas = 'Mengatur ulang stok barang koperasi: ';
        }

        $koperasi->save();

        $catatan = $validated['catatan'] ? ' Catatan: ' . $validated['catatan'] : '';

        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id' => Auth::guard('web')->id(),
            'aktivitas' => $aktivitas . $koperasi->nama_barang . " ({$stokAwal} menjadi {$koperasi->stok})." . $catatan,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('koperasi.index')->with('success', 'Stok barang koperasi berhasil diperbarui.');
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateData($request, $id);
        $koperasi = Koperasi::findOrFail($id);
        $koperasi->update($validated);

        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id' => Auth::guard('web')->id(),
            'aktivitas' => 'Memperbarui barang koperasi: ' . $koperasi->nama_barang,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('koperasi.index')->with('success', 'Barang koperasi berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $koperasi = Koperasi::findOrFail($id);
        $namaBarang = $koperasi->nama_barang;
        $koperasi->delete();

        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id' => Auth::guard('web')->id(),
            'aktivitas' => 'Menghapus barang koperasi: ' . $namaBarang,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('koperasi.index')->with('success', 'Barang koperasi berhasil dihapus.');
    }

    private function validateData(Request $request, $id = null)
    {
        $this->normalizeCurrencyFields($request, ['harga_beli', 'harga_jual']);

        $uniqueRule = 'unique:koperasi,kode_barang';

        if ($id) {
            $uniqueRule .= ',' . $id;
        }

        return $request->validate([
            'sekolah_id' => 'required|exists:sekolah,id',
            'kode_barang' => ['nullable', 'string', 'max:50', $uniqueRule],
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|in:buku,seragam,alat_tulis,atribut,makanan_minuman,lainnya',
            'satuan' => 'required|string|max:30',
            'harga_beli' => 'nullable|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);
    }

    private function kategoriList()
    {
        return [
            'buku' => 'Buku',
            'seragam' => 'Seragam',
            'alat_tulis' => 'Alat Tulis',
            'atribut' => 'Atribut Sekolah',
            'makanan_minuman' => 'Makanan & Minuman',
            'lainnya' => 'Lainnya',
        ];
    }
}
