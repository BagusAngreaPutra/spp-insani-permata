<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tagihan')
            || !Schema::hasTable('pembayaran')
            || !Schema::hasTable('jenis_pembayaran')
            || !Schema::hasTable('tahun_ajaran')) {
            return;
        }

        $candidates = DB::table('tagihan as tagihan')
            ->join('jenis_pembayaran as jenis', 'jenis.id', '=', 'tagihan.jenis_pembayaran_id')
            ->join('siswa', 'siswa.id', '=', 'tagihan.siswa_id')
            ->join('tahun_ajaran', 'tahun_ajaran.id', '=', 'siswa.tahun_ajaran_id')
            ->leftJoin('pembayaran', 'pembayaran.tagihan_id', '=', 'tagihan.id')
            ->whereRaw('LOWER(jenis.nama_pembayaran) = ?', ['spp'])
            ->where('tagihan.tipe', 'bulanan')
            ->whereNull('pembayaran.id')
            ->whereNotNull('tagihan.periode')
            ->select('tagihan.id', 'tagihan.periode', 'tahun_ajaran.nama_tahun')
            ->get();

        $futureBillIds = $candidates
            ->filter(function ($tagihan): bool {
                if (!preg_match('/(\d{4})\D+(\d{4})/', (string) $tagihan->nama_tahun, $academicYear)) {
                    return false;
                }

                if (!preg_match('/^(\d{4})-(0[1-9]|1[0-2])$/', (string) $tagihan->periode, $period)) {
                    return false;
                }

                $assignedStartYear = (int) $academicYear[1];
                $periodYear = (int) $period[1];
                $periodMonth = (int) $period[2];
                $periodStartYear = $periodMonth >= 7 ? $periodYear : $periodYear - 1;

                return $periodStartYear > $assignedStartYear;
            })
            ->pluck('id');

        DB::transaction(function () use ($futureBillIds): void {
            foreach ($futureBillIds->chunk(500) as $ids) {
                DB::table('tagihan')->whereIn('id', $ids->all())->delete();
            }
        });
    }

    public function down(): void
    {
        // Tagihan tanpa pembayaran dapat dibuat kembali melalui tombol Buat
        // Tagihan setelah siswa masuk ke tahun ajaran yang sesuai.
    }
};
