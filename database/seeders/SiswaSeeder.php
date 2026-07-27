<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Siswa;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Siswa::create([
            'id_sekolah'   => 1,                   // sesuaikan dengan data sekolah yang ada
            'kelas_id'     => 1,                   // sesuaikan dengan data kelas yang ada
            'nis'          => '1234567890',
            'nama'         => 'Budi Santoso',
            'username'     => 'budi123',           // 👈 username untuk login
            'password'     => Hash::make('password123'), // 👈 password (harus di-hash)
            'alamat'       => 'Jl. Mawar No. 1',
            'tanggal_lahir'=> '2010-05-10',
            'status'       => 'aktif',
            'nominal_spp'  => 325000.00,
        ]);

        // kamu bisa tambah data lain juga
        Siswa::create([
            'id_sekolah'   => 1,
            'kelas_id'     => 2,
            'nis'          => '9876543210',
            'nama'         => 'Siti Aminah',
            'username'     => 'siti456',
            'password'     => Hash::make('password456'),
            'alamat'       => 'Jl. Melati No. 2',
            'tanggal_lahir'=> '2011-08-21',
            'status'       => 'aktif',
            'nominal_spp'  => 325000.00,
        ]);
    }
}
