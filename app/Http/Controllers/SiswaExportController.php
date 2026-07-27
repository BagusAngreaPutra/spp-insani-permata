<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SiswaExportController extends Controller
{
    /**
     * Menampilkan halaman export Excel.
     */
    public function index()
    {
        return view('export_excel.index');
    }

    /**
     * Proses export data siswa ke Excel.
     */
    public function export()
    {
        $siswa = Siswa::all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $headers = [
            'id_sekolah','tahun_ajaran_id','kelas_id','nis','nama',
            'jenis_kelamin','agama','tempat_tinggal','moda_transportasi',
            'nama_ayah','nik_ayah','pekerjaan_ayah','penghasilan_ayah',
            'nama_ibu','nik_ibu','pekerjaan_ibu','penghasilan_ibu',
            'no_telp_rumah','no_hp','email','username','password',
            'alamat','tanggal_lahir','status','nominal_spp'
        ];

        // Tulis header
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValueExplicit($col.'1', $header, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getStyle($col.'1')->getFont()->setBold(true);
            $col++;
        }

        // Tulis data
        $rowNumber = 2;
        foreach ($siswa as $row) {
            $col = 'A';
            $sheet->setCellValueExplicit($col++.$rowNumber, (string)$row->id_sekolah, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, (string)$row->tahun_ajaran_id, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, (string)$row->kelas_id, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, (string)$row->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, $row->nama, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, $row->jenis_kelamin, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, $row->agama, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, $row->tempat_tinggal, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, $row->moda_transportasi, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, $row->nama_ayah, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, (string)$row->nik_ayah, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, $row->pekerjaan_ayah, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, (string)$row->penghasilan_ayah, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, $row->nama_ibu, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, (string)$row->nik_ibu, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, $row->pekerjaan_ibu, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, (string)$row->penghasilan_ibu, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, (string)$row->no_telp_rumah, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, (string)$row->no_hp, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, $row->email, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, $row->username, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            // ✅ gunakan password_raw supaya dapat password asli
            $sheet->setCellValueExplicit($col++.$rowNumber, $row->password_raw, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            $sheet->setCellValueExplicit($col++.$rowNumber, $row->alamat, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            // Format tanggal lahir
            $tanggal = $row->tanggal_lahir ? date('Y-m-d', strtotime($row->tanggal_lahir)) : '';
            $sheet->setCellValueExplicit($col++.$rowNumber, $tanggal, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            $sheet->setCellValueExplicit($col++.$rowNumber, $row->status, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++.$rowNumber, (string)$row->nominal_spp, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            $rowNumber++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'data_siswa_export.xlsx';

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
