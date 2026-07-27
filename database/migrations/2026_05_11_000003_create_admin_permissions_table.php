<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('admin', 'role') && DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE admin MODIFY role VARCHAR(50) NOT NULL DEFAULT 'admin'");
        }

        if (Schema::hasColumn('admin', 'role')) {
            DB::table('admin')->where('role', 'administrator')->update(['role' => 'super_admin']);
            DB::table('admin')->where('role', 'staff')->update(['role' => 'admin']);
        }

        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admin')->cascadeOnDelete();
            $table->string('permission');
            $table->timestamps();
            $table->unique(['admin_id', 'permission']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_permissions');

        if (Schema::hasColumn('admin', 'role')) {
            DB::table('admin')->where('role', 'super_admin')->update(['role' => 'administrator']);
            DB::table('admin')->where('role', 'admin')->update(['role' => 'staff']);

            if (DB::getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE admin MODIFY role ENUM('administrator', 'staff') NOT NULL DEFAULT 'administrator'");
            }
        }
    }
};
