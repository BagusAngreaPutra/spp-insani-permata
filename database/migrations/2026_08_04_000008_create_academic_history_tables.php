<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('riwayat_kenaikan')) {
            Schema::create('riwayat_kenaikan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
                $table->foreignId('sekolah_id')->nullable()->constrained('sekolah')->nullOnDelete();
                $table->foreignId('kelas_awal_id')->constrained('kelas')->cascadeOnDelete();
                $table->foreignId('kelas_baru_id')->constrained('kelas')->cascadeOnDelete();
                $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
                $table->date('tanggal_kenaikan');
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('riwayat_kelulusan')) {
            Schema::create('riwayat_kelulusan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
                $table->foreignId('sekolah_id')->nullable()->constrained('sekolah')->nullOnDelete();
                $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
                $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
                $table->date('tanggal_lulus');
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_kelulusan');
        Schema::dropIfExists('riwayat_kenaikan');
    }
};
