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
    Schema::create('kelas', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('id_tahun_ajaran')->nullable();
        $table->unsignedBigInteger('sekolah_id');
        $table->unsignedBigInteger('tahun_ajaran_id')->nullable();
        $table->string('nama_kelas', 255);
        $table->integer('tingkat');
        $table->timestamps();

        $table->foreign('sekolah_id')->references('id')->on('sekolah')->onDelete('cascade');
        $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajaran')->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
