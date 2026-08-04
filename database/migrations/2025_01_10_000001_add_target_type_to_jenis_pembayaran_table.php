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
        if (!Schema::hasTable('jenis_pembayaran') || Schema::hasColumn('jenis_pembayaran', 'target_type')) {
            return;
        }

        Schema::table('jenis_pembayaran', function (Blueprint $table) {
            $table->enum('target_type', ['all', 'specific_students', 'specific_classes'])
                  ->default('all')
                  ->after('jatuh_tempo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('jenis_pembayaran', 'target_type')) {
            return;
        }

        Schema::table('jenis_pembayaran', function (Blueprint $table) {
            $table->dropColumn('target_type');
        });
    }
};
