<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $table = 'sekolah';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nama_sekolah',
        'kode_sekolah',
        'alamat',
        'kontak',
        'telepon',
        'email',
        'jenjang',
        'durasi_pendidikan',
    ];

    /**
     * Relasi ke tabel siswa
     * Satu sekolah bisa punya banyak siswa
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_sekolah'); // pastikan foreign key ini benar
    }

    /**
     * Relasi ke tabel kelas
     * Satu sekolah bisa punya banyak kelas
     */
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'sekolah_id');
    }
    // ✅ Method untuk generate nomor kwitansi berikutnya
    public function getNextNomorKwitansi()
    {
        $tahun = date('Y');
        $kodeSekolah = $this->kode_sekolah;

        // Cari nomor terakhir untuk sekolah dan tahun ini
        $lastNumber = \DB::table('pembayaran')
            ->join('siswa', 'pembayaran.siswa_id', '=', 'siswa.id')
            ->where('siswa.id_sekolah', $this->id)
            ->where('pembayaran.nomor_kwitansi', 'LIKE', "%/{$kodeSekolah}/{$tahun}")
            ->max(\DB::raw('CAST(SUBSTRING_INDEX(pembayaran.nomor_kwitansi, "/", 1) AS UNSIGNED)'));

        // Nomor berikutnya
        $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

        // Format nomor kwitansi: 000001/SDIT/2025
        return sprintf('%06d/%s/%s', $nextNumber, $kodeSekolah, $tahun);
    }
}