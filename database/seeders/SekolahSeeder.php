<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sekolah')->insert([
            [
                'nama_sekolah' => 'SD IT Permata Insani',
                'alamat'       => 'Jl. Merpati No.123, Kota Contoh',
                'kontak'       => '021-555555',
                'jenjang'      => 'SD',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'nama_sekolah' => 'SMP IT Harapan Bangsa',
                'alamat'       => 'Jl. Kenanga No.45, Kota Contoh',
                'kontak'       => '021-777777',
                'jenjang'      => 'SMP',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'nama_sekolah' => 'SMA IT Cendekia',
                'alamat'       => 'Jl. Anggrek No.88, Kota Contoh',
                'kontak'       => '021-888888',
                'jenjang'      => 'SMA',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
        ]);
    }
}
