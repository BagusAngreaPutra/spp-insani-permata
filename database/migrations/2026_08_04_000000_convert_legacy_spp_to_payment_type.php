<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tagihan')
            || !Schema::hasTable('siswa')
            || !Schema::hasTable('jenis_pembayaran')) {
            return;
        }

        DB::transaction(function (): void {
            $schoolIds = DB::table('tagihan as tagihan')
                ->join('siswa', 'siswa.id', '=', 'tagihan.siswa_id')
                ->whereNull('tagihan.jenis_pembayaran_id')
                ->where('tagihan.nama_tagihan', 'like', 'SPP%')
                ->whereNotNull('siswa.id_sekolah')
                ->distinct()
                ->pluck('siswa.id_sekolah');

            foreach ($schoolIds as $schoolId) {
                $paymentTypeId = DB::table('jenis_pembayaran')
                    ->where('sekolah_id', $schoolId)
                    ->whereRaw('LOWER(nama_pembayaran) = ?', ['spp'])
                    ->value('id');

                if (!$paymentTypeId) {
                    $nominal = DB::table('tagihan as tagihan')
                        ->join('siswa', 'siswa.id', '=', 'tagihan.siswa_id')
                        ->whereNull('tagihan.jenis_pembayaran_id')
                        ->where('tagihan.nama_tagihan', 'like', 'SPP%')
                        ->where('siswa.id_sekolah', $schoolId)
                        ->selectRaw('tagihan.nominal, COUNT(*) as jumlah')
                        ->groupBy('tagihan.nominal')
                        ->orderByDesc('jumlah')
                        ->value('tagihan.nominal') ?? 0;

                    $dueDate = DB::table('tagihan as tagihan')
                        ->join('siswa', 'siswa.id', '=', 'tagihan.siswa_id')
                        ->whereNull('tagihan.jenis_pembayaran_id')
                        ->where('tagihan.nama_tagihan', 'like', 'SPP%')
                        ->where('siswa.id_sekolah', $schoolId)
                        ->whereNotNull('tagihan.tanggal_jatuh_tempo')
                        ->orderBy('tagihan.tanggal_jatuh_tempo')
                        ->value('tagihan.tanggal_jatuh_tempo') ?? now()->format('Y-07-10');

                    $paymentTypeId = DB::table('jenis_pembayaran')->insertGetId([
                        'sekolah_id' => $schoolId,
                        'nama_pembayaran' => 'SPP',
                        'tipe' => 'bulanan',
                        'nominal' => $nominal,
                        'jatuh_tempo' => $dueDate,
                        'target_type' => 'all',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $legacyIds = DB::table('tagihan as tagihan')
                    ->join('siswa', 'siswa.id', '=', 'tagihan.siswa_id')
                    ->whereNull('tagihan.jenis_pembayaran_id')
                    ->where('tagihan.nama_tagihan', 'like', 'SPP%')
                    ->where('siswa.id_sekolah', $schoolId)
                    ->pluck('tagihan.id');

                foreach ($legacyIds->chunk(500) as $ids) {
                    DB::table('tagihan')
                        ->whereIn('id', $ids->all())
                        ->update([
                            'id_sekolah' => $schoolId,
                            'jenis_pembayaran_id' => $paymentTypeId,
                            'updated_at' => now(),
                        ]);
                }
            }
        });
    }

    public function down(): void
    {
        // Konversi ini mempertahankan relasi pembayaran lama dan sengaja tidak
        // dibalik agar rollback tidak mengubah kembali data ke generator khusus.
    }
};
