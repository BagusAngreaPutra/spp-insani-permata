<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tagihan') || !Schema::hasTable('jenis_pembayaran')) {
            return;
        }

        DB::table('tagihan')
            ->join('jenis_pembayaran', 'jenis_pembayaran.id', '=', 'tagihan.jenis_pembayaran_id')
            ->where('tagihan.tipe', 'semester')
            ->select('tagihan.id', 'tagihan.periode', 'jenis_pembayaran.nama_pembayaran')
            ->orderBy('tagihan.id')
            ->chunkById(250, function ($tagihan) {
                foreach ($tagihan as $item) {
                    if (!preg_match('/^(\d{4})-(0[1-9]|1[0-2])$/', (string) $item->periode, $periode)) {
                        continue;
                    }

                    $tahun = (int) $periode[1];
                    $bulan = (int) $periode[2];
                    $tahunAwal = $bulan >= 7 ? $tahun : $tahun - 1;
                    $semester = $bulan >= 7 ? 1 : 2;

                    DB::table('tagihan')
                        ->where('id', $item->id)
                        ->update([
                            'nama_tagihan' => $item->nama_pembayaran
                                . ' - Tahun Ajaran ' . $tahunAwal . '/' . ($tahunAwal + 1)
                                . ' - Semester ' . $semester,
                        ]);
                }
            }, 'tagihan.id', 'id');
    }

    public function down(): void
    {
        // Perubahan hanya menormalkan label; data nominal dan pembayaran tidak disentuh.
    }
};
