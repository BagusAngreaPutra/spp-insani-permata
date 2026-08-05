<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kelas') || !Schema::hasColumn('kelas', 'tingkat')) {
            return;
        }

        $driver = DB::getDriverName();

        // Instalasi baru SQLite sudah mendapatkan kolom nullable dari migrasi dasar.
        // SQLite tidak mendukung ALTER COLUMN tanpa membangun ulang tabel.
        if ($driver === 'sqlite') {
            return;
        }

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE `kelas` MODIFY `tingkat` INT NULL');

            return;
        }

        Schema::table('kelas', function (Blueprint $table) {
            $table->integer('tingkat')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('kelas') || !Schema::hasColumn('kelas', 'tingkat')) {
            return;
        }

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        DB::table('kelas')->whereNull('tingkat')->update(['tingkat' => 0]);

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE `kelas` MODIFY `tingkat` INT NOT NULL');

            return;
        }

        Schema::table('kelas', function (Blueprint $table) {
            $table->integer('tingkat')->nullable(false)->change();
        });
    }
};
