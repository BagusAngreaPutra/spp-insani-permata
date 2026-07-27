<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KoperasiPenjualan extends Model
{
    use HasFactory;

    protected $table = 'koperasi_penjualan';

    protected $fillable = [
        'sekolah_id',
        'siswa_id',
        'kode_transaksi',
        'tanggal',
        'total',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total' => 'decimal:2',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function details()
    {
        return $this->hasMany(KoperasiPenjualanDetail::class, 'koperasi_penjualan_id');
    }
}
