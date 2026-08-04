<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use SplFileObject;

class DbSppSqlSeeder extends Seeder
{
    /**
     * Tabel data aplikasi dalam urutan dependensi foreign key.
     * Tabel migrations sengaja tidak disentuh.
     */
    private const TABLES = [
        'admin',
        'sekolah',
        'tahun_ajaran',
        'kelas',
        'siswa',
        'jenis_pembayaran',
        'jenis_pembayaran_siswa',
        'jenis_pembayaran_kelas',
        'tagihan',
        'pembayaran',
        'log_aktivitas',
        'pemasukan',
        'pengeluaran',
        'koperasi',
        'koperasi_penjualan',
        'koperasi_penjualan_detail',
        'riwayat_kenaikan',
        'riwayat_kelulusan',
        'admin_permissions',
        'users',
        'password_reset_tokens',
        'failed_jobs',
        'personal_access_tokens',
    ];

    public function run(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            throw new RuntimeException('Seeder data db_spp hanya mendukung koneksi MySQL/MariaDB.');
        }

        $sqlPath = database_path('seeders/data/db_spp_real.sql');
        if (!is_file($sqlPath) || !is_readable($sqlPath)) {
            throw new RuntimeException("Berkas data nyata tidak dapat dibaca: {$sqlPath}");
        }

        DB::disableQueryLog();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            foreach (array_reverse(self::TABLES) as $table) {
                DB::table($table)->truncate();
            }

            DB::beginTransaction();

            $sqlFile = new SplFileObject($sqlPath, 'r');
            foreach ($sqlFile as $lineNumber => $line) {
                $statement = trim((string) $line);

                if ($statement === '' || str_starts_with($statement, '--') || str_starts_with($statement, '/*')) {
                    continue;
                }

                if (!str_ends_with($statement, ';')) {
                    throw new RuntimeException('Pernyataan SQL tidak lengkap pada baris '.($lineNumber + 1).'.');
                }

                DB::unprepared($statement);
            }

            DB::commit();
        } catch (\Throwable $exception) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            throw $exception;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
