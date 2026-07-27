<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('koperasi_penjualan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('koperasi_penjualan_id')->constrained('koperasi_penjualan')->cascadeOnDelete();
            $table->foreignId('koperasi_id')->constrained('koperasi')->cascadeOnDelete();
            $table->string('nama_barang');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('koperasi_penjualan_detail');
    }
};
