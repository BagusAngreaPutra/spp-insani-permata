<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class SiswaImportController extends Controller
{
    /**
     * Tampilkan halaman import (upload excel).
     */
    public function showImportForm()
    {
        // Karena hanya import siswa, kita tidak perlu kirim sekolah/kelas jika tidak dipakai di view
        return view('import_excel.index');
    }

    /**
     * Download template Excel (file statis dari folder resources/views/import_excel/templates).
     */
    public function downloadTemplate()
    {
        // Path file template
        $file = resource_path('views/import_excel/templates/excel_form_template.xlsx');

        // Pastikan file ada
        if (!file_exists($file)) {
            abort(404, 'Template tidak ditemukan.');
        }

        // Kembalikan file sebagai download
        return response()->download($file, 'template_import_siswa.xlsx');
    }

    /**
     * Proses import data siswa dari Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls'
        ]);

        $path = $request->file('file_excel')->getRealPath();
        $data = Excel::toArray([], $path);

        if (count($data) > 0) {
            $rows = $data[0]; // sheet pertama
            $header = array_shift($rows); // baris pertama header

            // minimal cek kolom wajib
            if (!in_array('nis', $header) || !in_array('nama', $header) || !in_array('username', $header) || !in_array('password', $header)) {
                return back()->with('error', 'Template excel tidak sesuai. Pastikan header sudah benar.');
            }

            $insertCount = 0;

            foreach ($rows as $row) {
                if (empty(array_filter($row))) {
                    // baris kosong, skip
                    continue;
                }

                $rowData = array_combine($header, $row);

                if (!isset($rowData['nis']) || !isset($rowData['nama']) || !isset($rowData['username']) || !isset($rowData['password'])) {
                    continue;
                }

                // cek nis sudah ada atau belum
                if (Siswa::where('nis', $rowData['nis'])->exists()) {
                    continue;
                }

                Siswa::create([
                    'id_sekolah'      => $rowData['id_sekolah'] ?? null,
                    'tahun_ajaran_id' => $rowData['tahun_ajaran_id'] ?? null,
                    'kelas_id'        => $rowData['kelas_id'] ?? null,
                    'nis'             => $rowData['nis'],
                    'nama'            => $rowData['nama'],
                    'jenis_kelamin'   => $rowData['jenis_kelamin'] ?? null,
                    'agama'           => $rowData['agama'] ?? null,
                    'tempat_tinggal'  => $rowData['tempat_tinggal'] ?? null,
                    'moda_transportasi' => $rowData['moda_transportasi'] ?? null,
                    'nama_ayah'       => $rowData['nama_ayah'] ?? null,
                    'nik_ayah'        => $rowData['nik_ayah'] ?? null,
                    'pekerjaan_ayah'  => $rowData['pekerjaan_ayah'] ?? null,
                    'penghasilan_ayah'=> $rowData['penghasilan_ayah'] ?? null,
                    'nama_ibu'        => $rowData['nama_ibu'] ?? null,
                    'nik_ibu'         => $rowData['nik_ibu'] ?? null,
                    'pekerjaan_ibu'   => $rowData['pekerjaan_ibu'] ?? null,
                    'penghasilan_ibu' => $rowData['penghasilan_ibu'] ?? null,
                    'no_telp_rumah'   => $rowData['no_telp_rumah'] ?? null,
                    'no_hp'           => $rowData['no_hp'] ?? null,
                    'email'           => $rowData['email'] ?? null,
                    'username'        => $rowData['username'],
                    'password'        => Hash::make($rowData['password']),
                    'alamat'          => $rowData['alamat'] ?? null,
                    'tanggal_lahir'   => $rowData['tanggal_lahir'] ?? null,
                    'status'          => $rowData['status'] ?? 'aktif',
                    'nominal_spp'     => $rowData['nominal_spp'] ?? 325000.00,
                ]);

                $insertCount++;
            }

            return back()->with('success', "Import berhasil! {$insertCount} data siswa berhasil ditambahkan.");
        }

        return back()->with('error', 'File kosong atau tidak terbaca.');
    }
}
