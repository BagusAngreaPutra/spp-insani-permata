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
        if (!Schema::hasColumn('pembayaran', 'transaction_id')) {
            Schema::table('pembayaran', function (Blueprint $table) {
                $table->string('transaction_id')->nullable()->after('id');
                $table->index('transaction_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('pembayaran', 'transaction_id')) {
            Schema::table('pembayaran', function (Blueprint $table) {
                $table->dropIndex(['transaction_id']);
                $table->dropColumn('transaction_id');
            });
        }
    }
};
