<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sekolah')->nullable()->constrained('sekolah')->nullOnDelete();
            $table->foreignId('tahun_ajaran_id')->nullable()->constrained('tahun_ajaran')->nullOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->string('nis')->unique();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('agama', 50)->nullable();
            $table->string('tempat_tinggal', 50)->nullable();
            $table->string('moda_transportasi', 50)->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nik_ayah', 20)->nullable();
            $table->string('pekerjaan_ayah', 100)->nullable();
            $table->decimal('penghasilan_ayah', 12, 2)->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nik_ibu', 20)->nullable();
            $table->string('pekerjaan_ibu', 100)->nullable();
            $table->decimal('penghasilan_ibu', 12, 2)->nullable();
            $table->string('no_telp_rumah', 20)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('password_raw')->nullable();
            $table->rememberToken();
            $table->text('alamat')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('status', ['aktif', 'lulus', 'dropout'])->default('aktif');
            $table->decimal('nominal_spp', 10, 2)->default(325000);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
