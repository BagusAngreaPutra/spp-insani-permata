<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TahunAjaran;

class TahunAjaranSeeder extends Seeder
{
    public function run(): void
    {
        TahunAjaran::create([
            'nama_tahun' => '2024/2025',
            'aktif' => true,
        ]);

        TahunAjaran::create([
            'nama_tahun' => '2023/2024',
            'aktif' => false,
        ]);
    }
}
