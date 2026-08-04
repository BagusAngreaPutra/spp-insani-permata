<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('jenis_pembayaran', 'tahun_ajaran_id')) {
            Schema::table('jenis_pembayaran', function (Blueprint $table) {
                $table->foreignId('tahun_ajaran_id')
                    ->nullable()
                    ->after('sekolah_id')
                    ->constrained('tahun_ajaran')
                    ->nullOnDelete();
            });
        }

        $jenisPembayaran = DB::table('jenis_pembayaran')->get(['id', 'nama_pembayaran']);

        foreach ($jenisPembayaran as $jenis) {
            $tahunAjaranId = DB::table('tagihan')
                ->join('siswa', 'siswa.id', '=', 'tagihan.siswa_id')
                ->where('tagihan.jenis_pembayaran_id', $jenis->id)
                ->whereNotNull('siswa.tahun_ajaran_id')
                ->select('siswa.tahun_ajaran_id', DB::raw('COUNT(*) as total'))
                ->groupBy('siswa.tahun_ajaran_id')
                ->orderByDesc('total')
                ->value('siswa.tahun_ajaran_id');

            $tahunAjaranId ??= DB::table('tahun_ajaran')
                ->where('aktif', true)
                ->orderBy('id')
                ->value('id');

            $tahunAjaranId ??= DB::table('tahun_ajaran')->orderBy('id')->value('id');

            if ($tahunAjaranId) {
                DB::table('jenis_pembayaran')
                    ->where('id', $jenis->id)
                    ->update(['tahun_ajaran_id' => $tahunAjaranId]);
            }
        }

        $tahunAjaran = DB::table('tahun_ajaran')->pluck('nama_tahun', 'id');
        $jenisPembayaran = DB::table('jenis_pembayaran')->get([
            'id',
            'nama_pembayaran',
            'tahun_ajaran_id',
        ])->keyBy('id');

        DB::table('tagihan')
            ->whereNotNull('jenis_pembayaran_id')
            ->orderBy('id')
            ->chunkById(250, function ($tagihan) use ($jenisPembayaran, $tahunAjaran) {
                foreach ($tagihan as $item) {
                    $jenis = $jenisPembayaran->get($item->jenis_pembayaran_id);

                    if (!$jenis) {
                        continue;
                    }

                    $tahunLabel = $tahunAjaran->get($jenis->tahun_ajaran_id);

                    if (preg_match('/^(\d{4})-(0[1-9]|1[0-2])$/', (string) $item->periode, $periode)) {
                        $tahun = (int) $periode[1];
                        $bulan = (int) $periode[2];
                        $tahunAwal = $bulan >= 7 ? $tahun : $tahun - 1;
                        $tahunLabel = $tahunAwal . '/' . ($tahunAwal + 1);
                    } elseif (preg_match('/^(\d{4})$/', (string) $item->periode, $periode)) {
                        $tahunAwal = (int) $periode[1];
                        $tahunLabel = $tahunAwal . '/' . ($tahunAwal + 1);
                    }

                    $namaTagihan = $jenis->nama_pembayaran;
                    if ($tahunLabel) {
                        $namaTagihan .= ' - Tahun Ajaran ' . $tahunLabel;
                    }

                    if (preg_match('/Semester\s*([12])/i', (string) $item->nama_tagihan, $semester)) {
                        $namaTagihan .= ' - Semester ' . $semester[1];
                    }

                    DB::table('tagihan')
                        ->where('id', $item->id)
                        ->update(['nama_tagihan' => $namaTagihan]);
                }
            }, 'id');
    }

    public function down(): void
    {
        if (Schema::hasColumn('jenis_pembayaran', 'tahun_ajaran_id')) {
            Schema::table('jenis_pembayaran', function (Blueprint $table) {
                $table->dropConstrainedForeignId('tahun_ajaran_id');
            });
        }
    }
};
