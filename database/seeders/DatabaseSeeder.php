<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $realDataPath = database_path('seeders/data/db_spp_real.sql');

        if (! is_file($realDataPath)) {
            $this->command?->warn(
                'Seeder data nyata tidak disertakan dalam repository publik. '
                .'Letakkan db_spp_real.sql di database/seeders/data untuk menjalankan impor lokal.'
            );

            return;
        }

        $this->call(DbSppSqlSeeder::class);
    }
}
