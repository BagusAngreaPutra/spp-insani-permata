<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jenis_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolah')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->nullable()->constrained('tahun_ajaran')->nullOnDelete();
            $table->string('nama_pembayaran');
            $table->enum('tipe', ['sekali', 'bulanan', 'setahun', 'semester']);
            $table->decimal('nominal', 12, 2)->default(0);
            $table->date('jatuh_tempo')->nullable();
            $table->enum('target_type', ['all', 'specific_students', 'specific_classes'])->default('all');
            $table->timestamps();
        });

        Schema::create('jenis_pembayaran_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_pembayaran_id')->constrained('jenis_pembayaran')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['jenis_pembayaran_id', 'siswa_id']);
        });

        Schema::create('jenis_pembayaran_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_pembayaran_id')->constrained('jenis_pembayaran')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['jenis_pembayaran_id', 'kelas_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_pembayaran_kelas');
        Schema::dropIfExists('jenis_pembayaran_siswa');
        Schema::dropIfExists('jenis_pembayaran');
    }
};
