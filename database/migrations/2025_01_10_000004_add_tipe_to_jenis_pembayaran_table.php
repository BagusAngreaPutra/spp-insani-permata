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
        if (!Schema::hasTable('jenis_pembayaran') || Schema::hasColumn('jenis_pembayaran', 'tipe')) {
            return;
        }

        Schema::table('jenis_pembayaran', function (Blueprint $table) {
            $table->enum('tipe', ['bulanan', 'sekali', 'setahun', 'semester'])
                  ->default('bulanan')
                  ->after('nominal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('jenis_pembayaran', 'tipe')) {
            return;
        }

        Schema::table('jenis_pembayaran', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};
