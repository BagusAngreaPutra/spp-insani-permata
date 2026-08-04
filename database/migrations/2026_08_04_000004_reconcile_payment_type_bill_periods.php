<?php

use App\Models\JenisPembayaran;
use App\Services\TagihanPeriodReconciler;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            !Schema::hasTable('jenis_pembayaran')
            || !Schema::hasTable('tagihan')
            || !Schema::hasColumn('jenis_pembayaran', 'tahun_ajaran_id')
        ) {
            return;
        }

        $reconciler = app(TagihanPeriodReconciler::class);

        JenisPembayaran::with(['tahunAjaran', 'siswa', 'kelas'])
            ->orderBy('id')
            ->chunkById(50, function ($jenisPembayaran) use ($reconciler) {
                foreach ($jenisPembayaran as $jenis) {
                    $reconciler->reconcileJenis($jenis);
                }
            });
    }

    public function down(): void
    {
        // Tagihan usang yang belum pernah dibayar tidak dibuat kembali saat rollback.
    }
};
