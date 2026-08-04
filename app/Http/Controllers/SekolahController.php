<?php
// app/Http/Controllers/SekolahController.php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SekolahController extends Controller
{
    public function index(Request $request)
    {
        $query = Sekolah::query()
            ->with([
                'kelas' => fn ($kelasQuery) => $kelasQuery
                    ->with('tahunAjaran')
                    ->orderBy('tingkat')
                    ->orderBy('nama_kelas'),
            ])
            ->withCount(['kelas', 'siswa']);

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_sekolah', 'LIKE', "%{$search}%")
                  ->orWhere('kode_sekolah', 'LIKE', "%{$search}%")
                  ->orWhereHas('kelas', function ($kelasQuery) use ($search) {
                      $kelasQuery->where('nama_kelas', 'LIKE', "%{$search}%")
                          ->orWhere('tingkat', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Order by nama sekolah
        $query->orderBy('nama_sekolah', 'asc');

        // Gunakan pagination
        $sekolah = $query->paginate(10)->withQueryString();

        return view('sekolah.index', compact('sekolah'));
    }

    public function create()
    {
        $tahunAjaran = TahunAjaran::validPeriods();
        $tahunAjaranAktifId = optional($tahunAjaran->firstWhere('aktif', true))->id;
        $kelasRows = [[
            'id' => null,
            'tingkat' => '',
            'nama_kelas' => '',
            'tahun_ajaran_id' => $tahunAjaranAktifId,
            'hapus' => 0,
        ]];
        $classStudentCounts = collect();

        return view('sekolah.create', compact(
            'tahunAjaran',
            'tahunAjaranAktifId',
            'kelasRows',
            'classStudentCounts'
        ));
    }

    public function store(Request $request)
    {
        [$validated, $kelasRows] = $this->validatePayload($request);

        DB::transaction(function () use ($validated, $kelasRows) {
            $sekolah = Sekolah::create($this->schoolAttributes($validated));
            $this->syncClasses($sekolah, $kelasRows);
        });

        return redirect()
            ->route('sekolah.index')
            ->with('success', 'Sekolah dan data kelas berhasil ditambahkan!');
    }

    public function show(Sekolah $sekolah)
    {
        $sekolah->load(['siswa', 'kelas']);

        return view('sekolah.show', compact('sekolah'));
    }

    public function edit(Sekolah $sekolah)
    {
        $sekolah->load([
            'kelas' => fn ($query) => $query
                ->withCount('siswa')
                ->orderBy('tingkat')
                ->orderBy('nama_kelas'),
        ]);

        $tahunAjaran = TahunAjaran::validPeriods();
        $tahunAjaranAktifId = optional($tahunAjaran->firstWhere('aktif', true))->id;
        $kelasRows = $sekolah->kelas->map(fn (Kelas $kelas) => [
            'id' => $kelas->id,
            'tingkat' => $kelas->tingkat,
            'nama_kelas' => trim((string) $kelas->nama_kelas) === '-' ? '' : $kelas->nama_kelas,
            'tahun_ajaran_id' => $kelas->tahun_ajaran_id,
            'hapus' => 0,
        ])->values()->all();
        $classStudentCounts = $sekolah->kelas->pluck('siswa_count', 'id');

        return view('sekolah.edit', compact(
            'sekolah',
            'tahunAjaran',
            'tahunAjaranAktifId',
            'kelasRows',
            'classStudentCounts'
        ));
    }

    public function update(Request $request, Sekolah $sekolah)
    {
        [$validated, $kelasRows] = $this->validatePayload($request, $sekolah);

        DB::transaction(function () use ($validated, $kelasRows, $sekolah) {
            $sekolah->update($this->schoolAttributes($validated));
            $this->syncClasses($sekolah, $kelasRows);
        });

        return redirect()
            ->route('sekolah.index')
            ->with('success', 'Data sekolah dan kelas berhasil diperbarui!');
    }

    public function destroy(Sekolah $sekolah)
    {
        $namaSekolah = $sekolah->nama_sekolah;
        $jumlahSiswa = $sekolah->siswa()->count();

        try {
            DB::transaction(function () use ($sekolah) {
                // FK siswa.id_sekolah menggunakan RESTRICT. Hapus siswa lebih
                // dahulu agar seluruh tagihan dan transaksi terkait ikut cascade.
                $sekolah->siswa()->delete();
                $sekolah->delete();
            });
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()
                ->route('sekolah.index')
                ->with('error', "Sekolah '{$namaSekolah}' gagal dihapus. Seluruh perubahan telah dibatalkan.");
        }

        $message = $jumlahSiswa > 0
            ? "Sekolah '{$namaSekolah}' beserta {$jumlahSiswa} siswa dan seluruh data terkait berhasil dihapus!"
            : "Sekolah '{$namaSekolah}' berhasil dihapus!";

        return redirect()
            ->route('sekolah.index')
            ->with('success', $message);
    }

    /**
     * Validasi identitas sekolah dan baris kelas dalam satu payload.
     */
    private function validatePayload(Request $request, ?Sekolah $sekolah = null): array
    {
        $validator = Validator::make($request->all(), [
            'nama_sekolah' => ['required', 'string', 'max:255'],
            'kode_sekolah' => [
                'required',
                'string',
                'max:10',
                Rule::unique('sekolah', 'kode_sekolah')->ignore($sekolah?->id),
            ],
            'alamat' => ['required', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'durasi_pendidikan' => ['nullable', 'integer', 'min:1', 'max:12'],
            'kelas' => ['nullable', 'array', 'max:100'],
            'kelas.*.id' => ['nullable', 'integer', 'exists:kelas,id'],
            'kelas.*.tingkat' => ['nullable', 'integer', 'min:1', 'max:12'],
            'kelas.*.nama_kelas' => ['nullable', 'string', 'max:100'],
            'kelas.*.tahun_ajaran_id' => ['nullable', 'integer', 'exists:tahun_ajaran,id'],
            'kelas.*.hapus' => ['nullable', 'boolean'],
        ], [
            'nama_sekolah.required' => 'Nama sekolah wajib diisi.',
            'kode_sekolah.required' => 'Kode sekolah wajib diisi.',
            'kode_sekolah.unique' => 'Kode sekolah sudah digunakan, silakan pilih kode lain.',
            'kode_sekolah.max' => 'Kode sekolah maksimal 10 karakter.',
            'alamat.required' => 'Alamat sekolah wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'durasi_pendidikan.integer' => 'Durasi pendidikan harus berupa angka.',
            'durasi_pendidikan.min' => 'Durasi pendidikan minimal 1 tahun.',
            'durasi_pendidikan.max' => 'Durasi pendidikan maksimal 12 tahun.',
            'kelas.max' => 'Maksimal 100 kelas dapat disimpan dalam satu sekolah.',
            'kelas.*.tingkat.integer' => 'Tingkat kelas harus berupa angka.',
            'kelas.*.tingkat.min' => 'Tingkat kelas minimal 1.',
            'kelas.*.tingkat.max' => 'Tingkat kelas maksimal 12.',
            'kelas.*.nama_kelas.max' => 'Nama kelas maksimal 100 karakter.',
            'kelas.*.tahun_ajaran_id.exists' => 'Tahun ajaran pada salah satu kelas tidak valid.',
        ]);

        $allowedClassIds = $sekolah
            ? $sekolah->kelas()->pluck('id')->map(fn ($id) => (int) $id)->all()
            : [];

        $validator->after(function ($validator) use ($request, $sekolah, $allowedClassIds) {
            $seen = [];

            foreach ($request->input('kelas', []) as $index => $row) {
                if (!is_array($row)) {
                    continue;
                }

                $id = filled($row['id'] ?? null) ? (int) $row['id'] : null;
                $tingkat = filled($row['tingkat'] ?? null) ? (int) $row['tingkat'] : null;
                $namaKelas = trim((string) ($row['nama_kelas'] ?? ''));
                $tahunAjaranId = filled($row['tahun_ajaran_id'] ?? null)
                    ? (int) $row['tahun_ajaran_id']
                    : null;
                $hapus = filter_var($row['hapus'] ?? false, FILTER_VALIDATE_BOOLEAN);
                $isEmpty = !$id && !$tingkat && $namaKelas === '';

                if ($isEmpty || ($hapus && !$id)) {
                    continue;
                }

                if ($id && (!$sekolah || !in_array($id, $allowedClassIds, true))) {
                    $validator->errors()->add("kelas.{$index}.id", 'Data kelas tidak termasuk dalam sekolah ini.');
                    continue;
                }

                if ($hapus) {
                    continue;
                }

                if (!$tingkat) {
                    $validator->errors()->add("kelas.{$index}.tingkat", 'Tingkat wajib dipilih untuk setiap kelas.');
                    continue;
                }

                $normalizedName = mb_strtolower(preg_replace('/\s+/', ' ', $namaKelas));
                $duplicateKey = implode('|', [$tingkat, $normalizedName, $tahunAjaranId ?? 'none']);

                if (isset($seen[$duplicateKey])) {
                    $validator->errors()->add(
                        "kelas.{$index}.nama_kelas",
                        'Kombinasi tingkat, nama kelas, dan tahun ajaran tidak boleh sama.'
                    );
                }

                $seen[$duplicateKey] = true;
            }
        });

        $validated = $validator->validate();

        $kelasRows = collect($request->input('kelas', []))
            ->filter(fn ($row) => is_array($row))
            ->map(function ($row) {
                return [
                    'id' => filled($row['id'] ?? null) ? (int) $row['id'] : null,
                    'tingkat' => filled($row['tingkat'] ?? null) ? (int) $row['tingkat'] : null,
                    'nama_kelas' => trim((string) ($row['nama_kelas'] ?? '')),
                    'tahun_ajaran_id' => filled($row['tahun_ajaran_id'] ?? null)
                        ? (int) $row['tahun_ajaran_id']
                        : null,
                    'hapus' => filter_var($row['hapus'] ?? false, FILTER_VALIDATE_BOOLEAN),
                ];
            })
            ->filter(function ($row) {
                return $row['id']
                    || $row['tingkat']
                    || $row['nama_kelas'] !== '';
            })
            ->values()
            ->all();

        return [$validated, $kelasRows];
    }

    private function schoolAttributes(array $validated): array
    {
        return [
            'nama_sekolah' => $validated['nama_sekolah'],
            'kode_sekolah' => strtoupper($validated['kode_sekolah']),
            'alamat' => $validated['alamat'],
            'kontak' => '',
            'telepon' => $validated['telepon'] ?? null,
            'email' => $validated['email'] ?? null,
            'durasi_pendidikan' => $validated['durasi_pendidikan'] ?? null,
        ];
    }

    /**
     * Tambah, ubah, atau hapus kelas milik sekolah secara atomik.
     */
    private function syncClasses(Sekolah $sekolah, array $kelasRows): void
    {
        foreach ($kelasRows as $index => $row) {
            $kelas = $row['id']
                ? $sekolah->kelas()->whereKey($row['id'])->firstOrFail()
                : null;

            if ($row['hapus']) {
                if (!$kelas) {
                    continue;
                }

                $studentCount = $kelas->siswa()->count();
                if ($studentCount > 0) {
                    throw ValidationException::withMessages([
                        "kelas.{$index}.id" => "Kelas Tingkat {$kelas->tingkat} {$kelas->nama_kelas} tidak dapat dihapus karena masih memiliki {$studentCount} siswa.",
                    ]);
                }

                $kelas->delete();
                continue;
            }

            if (!$row['tingkat']) {
                continue;
            }

            $attributes = [
                'tingkat' => $row['tingkat'],
                'nama_kelas' => $row['nama_kelas'],
                'tahun_ajaran_id' => $row['tahun_ajaran_id'],
            ];

            if ($kelas) {
                $kelas->update($attributes);
            } else {
                $sekolah->kelas()->create($attributes);
            }
        }
    }
}
