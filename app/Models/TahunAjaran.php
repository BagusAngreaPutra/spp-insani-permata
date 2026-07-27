<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    // pakai nama tabel custom
    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'nama_tahun',
        'aktif',
    ];
}
