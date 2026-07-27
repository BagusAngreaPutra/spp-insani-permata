<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Ambil semua data siswa
        return Siswa::all([
            'id_sekolah','tahun_ajaran_id','kelas_id','nis','nama',
            'jenis_kelamin','agama','tempat_tinggal','moda_transportasi',
            'nama_ayah','nik_ayah','pekerjaan_ayah','penghasilan_ayah',
            'nama_ibu','nik_ibu','pekerjaan_ibu','penghasilan_ibu',
            'no_telp_rumah','no_hp','email','username','alamat',
            'tanggal_lahir','status','nominal_spp'
        ]);
    }

    public function headings(): array
    {
        return [
            'id_sekolah','tahun_ajaran_id','kelas_id','nis','nama',
            'jenis_kelamin','agama','tempat_tinggal','moda_transportasi',
            'nama_ayah','nik_ayah','pekerjaan_ayah','penghasilan_ayah',
            'nama_ibu','nik_ibu','pekerjaan_ibu','penghasilan_ibu',
            'no_telp_rumah','no_hp','email','username','alamat',
            'tanggal_lahir','status','nominal_spp'
        ];
    }
}
