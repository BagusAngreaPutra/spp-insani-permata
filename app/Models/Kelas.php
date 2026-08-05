<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'sekolah_id',
        'tahun_ajaran_id',
        'nama_kelas',
        'tingkat',
    ];

    // 👉 Relasi ke Sekolah
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    // 👉 Relasi ke Tahun Ajaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    // 👉 Accessor untuk nama sekolah
    protected $appends = ['nama_sekolah', 'nama_tahun', 'label_tingkat', 'kelas'];

    public function getNamaSekolahAttribute()
    {
        return $this->sekolah ? $this->sekolah->nama_sekolah : null;
    }

    public function getNamaTahunAttribute()
    {
        return $this->tahunAjaran ? $this->tahunAjaran->nama_tahun : null;
    }

    // 👉 Accessor untuk gabungan tingkat + nama_kelas
    public function getLabelTingkatAttribute()
    {
        return $this->tingkat === null ? 'Tanpa tingkat' : 'Tingkat ' . $this->tingkat;
    }

    public function getKelasAttribute()
    {
        return trim($this->label_tingkat . ' ' . $this->nama_kelas);
    }

    // 👉 Relasi ke Siswa
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    // 👉 Relasi many-to-many ke JenisPembayaran
    public function jenisPembayaran()
    {
        return $this->belongsToMany(JenisPembayaran::class, 'jenis_pembayaran_kelas', 'kelas_id', 'jenis_pembayaran_id')
                    ->withTimestamps();
    }
}
