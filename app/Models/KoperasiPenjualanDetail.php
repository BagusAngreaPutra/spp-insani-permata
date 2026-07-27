<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KoperasiPenjualanDetail extends Model
{
    use HasFactory;

    protected $table = 'koperasi_penjualan_detail';

    protected $fillable = [
        'koperasi_penjualan_id',
        'koperasi_id',
        'nama_barang',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function penjualan()
    {
        return $this->belongsTo(KoperasiPenjualan::class, 'koperasi_penjualan_id');
    }

    public function barang()
    {
        return $this->belongsTo(Koperasi::class, 'koperasi_id');
    }
}
