<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKelulusan extends Model
{
    use HasFactory;

    // 👉 Nama tabel
    protected $table = 'riwayat_kelulusan';

    // 👉 Kolom yang boleh diisi massal
    protected $fillable = [
        'siswa_id',
        'sekolah_id',       // ✅ Tambahkan
        'kelas_id',
        'tahun_ajaran_id',
        'tanggal_lulus',
        'keterangan',
    ];

    // 👉 Casting tanggal otomatis jadi instance Carbon
    protected $casts = [
        'tanggal_lulus' => 'date',
    ];

    // 🔗 Relasi ke tabel siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    // 🔗 Relasi ke tabel kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // 🔗 Relasi ke tabel tahun_ajaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    // 🔗 Relasi langsung ke tabel sekolah
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }
}
