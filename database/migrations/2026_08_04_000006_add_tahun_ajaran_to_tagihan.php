<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tagihan') || Schema::hasColumn('tagihan', 'tahun_ajaran_id')) {
            return;
        }

        Schema::table('tagihan', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')
                ->nullable()
                ->after('jenis_pembayaran_id')
                ->constrained('tahun_ajaran')
                ->nullOnDelete();
        });

        $paymentTypeYears = DB::table('jenis_pembayaran')
            ->whereNotNull('tahun_ajaran_id')
            ->pluck('tahun_ajaran_id', 'id');
        $studentYears = DB::table('siswa')
            ->whereNotNull('tahun_ajaran_id')
            ->pluck('tahun_ajaran_id', 'id');

        DB::table('tagihan')
            ->select(['id', 'siswa_id', 'jenis_pembayaran_id'])
            ->orderBy('id')
            ->chunkById(250, function ($bills) use ($paymentTypeYears, $studentYears) {
                foreach ($bills as $bill) {
                    $academicYearId = $paymentTypeYears->get($bill->jenis_pembayaran_id)
                        ?? $studentYears->get($bill->siswa_id);

                    if ($academicYearId) {
                        DB::table('tagihan')
                            ->where('id', $bill->id)
                            ->update(['tahun_ajaran_id' => $academicYearId]);
                    }
                }
            });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('tagihan', 'tahun_ajaran_id')) {
            return;
        }

        Schema::table('tagihan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tahun_ajaran_id');
        });
    }
};
