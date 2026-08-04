<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('id_sekolah')->nullable()->constrained('sekolah')->nullOnDelete();
            $table->foreignId('jenis_pembayaran_id')->nullable()->constrained('jenis_pembayaran')->onDelete('set null');
            $table->foreignId('tahun_ajaran_id')->nullable()->constrained('tahun_ajaran')->nullOnDelete();
            $table->string('nama_tagihan');
            $table->decimal('nominal', 12, 2)->default(0);
            $table->enum('tipe', ['sekali', 'bulanan', 'setahun', 'semester']);
            $table->string('periode', 10)->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->enum('status', ['belum', 'lunas'])->default('belum');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};
