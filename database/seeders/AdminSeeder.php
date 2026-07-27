<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        // Hapus dulu data lama dengan username yang sama (opsional)
        DB::table('admin')->where('username', 'superadmin')->delete();

        // Insert data admin baru
        DB::table('admin')->insert([
            'nama_admin' => 'Admin',
            'username'   => 'admin',
            'password'   => Hash::make('admin123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
