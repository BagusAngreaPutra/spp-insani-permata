<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use App\Models\LogAktivitas; // 👉 tambahkan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TahunAjaranController extends Controller
{
    // 📌 Menampilkan daftar tahun ajaran
    public function index(Request $request)
    {
        $tahunAjaran = TahunAjaran::query()
            ->get()
            ->sortByDesc(fn (TahunAjaran $tahun) => $tahun->periodBounds()[0] ?? -1)
            ->values();

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

        return view('tahun_ajaran.create', $this->formData());
    }

    // 📌 Proses simpan
    public function store(Request $request)
    {
        $validated = $this->validatePeriod($request);

        $tahun = DB::transaction(function () use ($validated) {
            if ($validated['aktif']) {
                TahunAjaran::query()->update(['aktif' => false]);
            }

            return TahunAjaran::create($validated);
        });

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

        return view('tahun_ajaran.create', $this->formData($tahun_ajaran));
    }

    // 📌 Proses update
    public function update(Request $request, TahunAjaran $tahun_ajaran)
    {
        $validated = $this->validatePeriod($request, $tahun_ajaran);

        // Simpan data lama sebelum update
        $oldData = $tahun_ajaran->only(['nama_tahun', 'aktif']);

        // Update data
        DB::transaction(function () use ($tahun_ajaran, $validated) {
            if ($validated['aktif']) {
                TahunAjaran::whereKeyNot($tahun_ajaran->id)->update(['aktif' => false]);
            }

            $tahun_ajaran->update($validated);
        });

        // Bandingkan perubahan
        $changes = [];
        $newData = [
            'nama_tahun' => $validated['nama_tahun'],
            'aktif'      => $validated['aktif'],
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
        if ($tahun_ajaran->isInUse()) {
            return redirect()
                ->route('tahun_ajaran.index')
                ->with('error', 'Tahun ajaran tidak dapat dihapus karena sudah digunakan oleh kelas, siswa, jenis pembayaran, atau tagihan.');
        }

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

    private function formData(?TahunAjaran $tahunAjaran = null): array
    {
        $periodLocked = $tahunAjaran?->isInUse() ?? false;
        $academicYearOptions = TahunAjaran::periodOptions(20, $tahunAjaran?->nama_tahun);
        $usedAcademicYears = TahunAjaran::query()
            ->when($tahunAjaran, fn ($query) => $query->whereKeyNot($tahunAjaran->id))
            ->pluck('nama_tahun')
            ->map(fn ($period) => TahunAjaran::canonicalizePeriod($period))
            ->filter()
            ->values()
            ->all();

        return compact('tahunAjaran', 'academicYearOptions', 'usedAcademicYears', 'periodLocked');
    }

    private function validatePeriod(Request $request, ?TahunAjaran $tahunAjaran = null): array
    {
        $canonical = TahunAjaran::canonicalizePeriod($request->input('nama_tahun'));
        if ($canonical) {
            $request->merge(['nama_tahun' => $canonical]);
        }

        $allowedPeriods = $tahunAjaran?->isInUse()
            ? array_filter([$tahunAjaran->label])
            : TahunAjaran::periodOptions(20, $tahunAjaran?->nama_tahun);
        $validated = $request->validate([
            'nama_tahun' => [
                'required',
                Rule::in($allowedPeriods),
                Rule::unique('tahun_ajaran', 'nama_tahun')->ignore($tahunAjaran?->id),
            ],
            'aktif' => ['nullable', 'boolean'],
        ], [
            'nama_tahun.in' => 'Pilih tahun ajaran yang tersedia pada daftar.',
            'nama_tahun.unique' => 'Tahun ajaran tersebut sudah tersimpan.',
        ]);

        return [
            'nama_tahun' => $validated['nama_tahun'],
            'aktif' => (bool) ($validated['aktif'] ?? false),
        ];
    }
}
