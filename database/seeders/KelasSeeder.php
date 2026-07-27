<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kelas')->insert([
            [
                'nama_kelas' => 'Kelas 1A',
                'tingkat' => 1,
                'sekolah_id' => 1, // pastikan id 1 ada di tabel sekolah
                'tahun_ajaran_id' => 1, // pastikan id 1 ada di tabel tahun_ajaran
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_kelas' => 'Kelas 1B',
                'tingkat' => 1,
                'sekolah_id' => 1,
                'tahun_ajaran_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_kelas' => 'Kelas 2A',
                'tingkat' => 2,
                'sekolah_id' => 1,
                'tahun_ajaran_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
