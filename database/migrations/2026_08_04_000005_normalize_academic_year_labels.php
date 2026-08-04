<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tahun_ajaran')) {
            return;
        }

        $validYears = collect();

        DB::table('tahun_ajaran')
            ->select(['id', 'nama_tahun', 'aktif'])
            ->orderBy('id')
            ->get()
            ->each(function ($year) use ($validYears) {
                $canonical = $this->canonicalize((string) $year->nama_tahun);

                if (!$canonical) {
                    return;
                }

                if ($canonical !== $year->nama_tahun) {
                    DB::table('tahun_ajaran')
                        ->where('id', $year->id)
                        ->update(['nama_tahun' => $canonical]);
                }

                $validYears->push([
                    'id' => (int) $year->id,
                    'start' => (int) substr($canonical, 0, 4),
                    'active' => (bool) $year->aktif,
                ]);
            });

        $activeYears = $validYears->where('active', true);
        if ($activeYears->count() > 1) {
            $activeId = $activeYears->sortByDesc('start')->first()['id'];

            DB::table('tahun_ajaran')
                ->where('aktif', true)
                ->where('id', '!=', $activeId)
                ->update(['aktif' => false]);
        }
    }

    public function down(): void
    {
        // Normalisasi label tidak dikembalikan menjadi nilai bebas yang tidak valid.
    }

    private function canonicalize(string $value): ?string
    {
        $value = trim($value);

        if (!preg_match('/^(\d{4})\D+(\d{4})\d*$/', $value, $matches)) {
            return null;
        }

        $startYear = (int) $matches[1];
        $endYear = (int) $matches[2];

        return $startYear >= 2000 && $endYear === $startYear + 1
            ? $startYear . '/' . $endYear
            : null;
    }
};
