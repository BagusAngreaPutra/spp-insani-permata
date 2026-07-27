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
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->enum('kategori', ['spp', 'lainnya']); // kategori tagihan
            $table->foreignId('jenis_pembayaran_id')->nullable()->constrained('jenis_pembayaran')->onDelete('set null');
            $table->tinyInteger('bulan')->nullable(); // khusus untuk SPP
            $table->decimal('nominal_tagihan', 12, 2)->default(0.00);
            $table->enum('status', ['belum_lunas', 'lunas', 'angsuran'])->default('belum_lunas');
            $table->date('jatuh_tempo')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};
