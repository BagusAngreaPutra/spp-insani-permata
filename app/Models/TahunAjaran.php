<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    // pakai nama tabel custom
    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'nama_tahun',
        'aktif',
    ];

    public function getLabelAttribute(): string
    {
        return static::canonicalizePeriod((string) $this->nama_tahun)
            ?? trim((string) $this->nama_tahun);
    }

    public function hasValidPeriod(): bool
    {
        return $this->periodBounds() !== null;
    }

    public function periodBounds(): ?array
    {
        $canonical = static::canonicalizePeriod((string) $this->nama_tahun);

        if (!$canonical) {
            return null;
        }

        [$startYear, $endYear] = array_map('intval', explode('/', $canonical));

        return [$startYear, $endYear];
    }

    public static function canonicalizePeriod(?string $value): ?string
    {
        if (!preg_match('/^(\d{4})\s*[\/-]\s*(\d{4})$/', trim((string) $value), $matches)) {
            return null;
        }

        $startYear = (int) $matches[1];
        $endYear = (int) $matches[2];

        if ($startYear < 2000 || $endYear !== $startYear + 1) {
            return null;
        }

        return $startYear . '/' . $endYear;
    }

    public static function currentStartYear(): int
    {
        $today = Carbon::today('Asia/Jakarta');

        return $today->month >= 7 ? $today->year : $today->year - 1;
    }

    /**
     * Daftar periode dari tahun ajaran berjalan sampai 20 tahun ke depan.
     */
    public static function periodOptions(int $yearsAhead = 20, ?string $include = null): array
    {
        $startYear = static::currentStartYear();
        $options = collect(range($startYear, $startYear + $yearsAhead))
            ->mapWithKeys(fn (int $year) => [$year => $year . '/' . ($year + 1)]);

        $included = static::canonicalizePeriod($include);
        if ($included) {
            $includedStart = (int) explode('/', $included)[0];
            $options->put($includedStart, $included);
        }

        return $options->sortKeys()->values()->all();
    }

    public static function validPeriods()
    {
        return static::query()
            ->get()
            ->filter(fn (TahunAjaran $tahun) => $tahun->hasValidPeriod())
            ->sortByDesc(fn (TahunAjaran $tahun) => $tahun->periodBounds()[0])
            ->sortByDesc('aktif')
            ->values();
    }

    public function jenisPembayaran()
    {
        return $this->hasMany(JenisPembayaran::class, 'tahun_ajaran_id');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'tahun_ajaran_id');
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'tahun_ajaran_id');
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'tahun_ajaran_id');
    }

    public function isInUse(): bool
    {
        return $this->jenisPembayaran()->exists()
            || $this->siswa()->exists()
            || $this->kelas()->exists()
            || $this->tagihan()->exists();
    }
}
