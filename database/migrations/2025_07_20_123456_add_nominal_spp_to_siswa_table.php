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
        if (Schema::hasColumn('siswa', 'nominal_spp')) {
            return;
        }

        Schema::table('siswa', function (Blueprint $table) {
            $table->decimal('nominal_spp', 10, 2)->default(325000)->after('status');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('siswa', 'nominal_spp')) {
            return;
        }

        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn('nominal_spp');
        });
    }

};
