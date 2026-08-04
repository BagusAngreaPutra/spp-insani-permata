<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->nullable()->index();
            $table->string('nomor_kwitansi', 30)->nullable();
            $table->foreignId('sekolah_id')->nullable()->constrained('sekolah')->nullOnDelete();
            $table->foreignId('tagihan_id')->constrained('tagihan')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->decimal('jumlah_bayar', 15, 2);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->date('tanggal_bayar');
            $table->string('periode', 7)->nullable();
            $table->string('periode_tahun', 191)->nullable();
            $table->string('metode_bayar', 50)->default('tunai');
            $table->integer('cicilan_ke')->nullable();
            $table->integer('total_cicilan')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->timestamps();

            $table->index(['tagihan_id', 'tanggal_bayar']);
            $table->index(['siswa_id', 'tanggal_bayar']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
