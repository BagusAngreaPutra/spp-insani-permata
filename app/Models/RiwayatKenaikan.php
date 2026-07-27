<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKenaikan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_kenaikan';

    protected $fillable = [
        'siswa_id',
        'kelas_awal_id',
        'kelas_baru_id',
        'tahun_ajaran_id',
        'tanggal_kenaikan',
        'keterangan',
    ];

    // 🔗 Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    // 🔗 Relasi ke kelas awal
    public function kelasAwal()
    {
        return $this->belongsTo(Kelas::class, 'kelas_awal_id');
    }

    // 🔗 Relasi ke kelas baru
    public function kelasBaru()
    {
        return $this->belongsTo(Kelas::class, 'kelas_baru_id');
    }

    // 🔗 Relasi ke tahun ajaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }
}
