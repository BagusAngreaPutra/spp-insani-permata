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
        $table->id(); // id
        $table->unsignedBigInteger('sekolah_id'); // foreign ke sekolah
        $table->unsignedBigInteger('tahun_ajaran_id')->nullable(); // foreign ke tahun ajaran
        $table->string('nama_kelas', 255); // nama_kelas
        $table->integer('tingkat'); // tingkat
        $table->timestamps();

        // foreign key
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
