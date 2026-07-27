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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_id')->constrained('tagihan')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->decimal('jumlah_bayar', 12, 2);
            $table->date('tanggal_bayar');
            $table->text('keterangan')->nullable();
            $table->integer('cicilan_ke')->default(1); // untuk tracking cicilan
            $table->integer('total_cicilan')->default(1); // total cicilan yang direncanakan
            $table->enum('metode_bayar', ['tunai', 'transfer', 'kartu'])->default('tunai');
            $table->string('bukti_bayar')->nullable(); // untuk upload bukti transfer
            $table->timestamps();

            // Index untuk query yang sering digunakan
            $table->index(['tagihan_id', 'tanggal_bayar']);
            $table->index(['siswa_id', 'tanggal_bayar']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};