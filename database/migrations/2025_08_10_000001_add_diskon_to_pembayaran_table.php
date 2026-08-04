<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('pembayaran', 'diskon')) {
            return;
        }

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->decimal('diskon', 15, 2)->default(0)->after('jumlah_bayar')->comment('Jumlah diskon yang diberikan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('pembayaran', 'diskon')) {
            return;
        }

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn('diskon');
        });
    }
};
