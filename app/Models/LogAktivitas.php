<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    // nama tabel
    protected $table = 'log_aktivitas';

    // kolom yang boleh diisi
    protected $fillable = [
        'aktor_type',
        'aktor_id',
        'aktivitas',
        'ip_address',
        'user_agent',
    ];

    /**
     * Relasi ke Admin
     * Akan bernilai hanya jika aktor_type = admin
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'aktor_id');
    }

    /**
     * Relasi ke Siswa
     * Akan bernilai hanya jika aktor_type = siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'aktor_id');
    }

    /**
     * Scope untuk filter berdasarkan aktor admin saja
     */
    public function scopeAdmin($query)
    {
        return $query->where('aktor_type', 'admin');
    }

    /**
     * Scope untuk filter berdasarkan aktor siswa saja
     */
    public function scopeSiswa($query)
    {
        return $query->where('aktor_type', 'siswa');
    }

    /**
     * Scope untuk filter aktivitas tertentu
     */
    public function scopeCariAktivitas($query, $keyword)
    {
        return $query->where('aktivitas', 'like', "%{$keyword}%");
    }
}
