<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('koperasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolah')->cascadeOnDelete();
            $table->string('kode_barang', 50)->nullable()->unique();
            $table->string('nama_barang');
            $table->enum('kategori', ['buku', 'seragam', 'alat_tulis', 'atribut', 'makanan_minuman', 'lainnya'])->default('lainnya');
            $table->string('satuan', 30)->default('pcs');
            $table->decimal('harga_beli', 12, 2)->default(0);
            $table->decimal('harga_jual', 12, 2)->default(0);
            $table->integer('stok')->default(0);
            $table->integer('stok_minimum')->default(5);
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->index(['sekolah_id', 'kategori', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('koperasi');
    }
};
