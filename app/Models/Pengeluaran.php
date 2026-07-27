<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'pengeluaran';

    // Kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'sekolah_id',
        'tanggal',
        'jumlah',
        'keperluan',
        'keterangan',
    ];

    /**
     * Relasi ke tabel sekolah
     * Satu pengeluaran dimiliki oleh satu sekolah
     */
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }
}
