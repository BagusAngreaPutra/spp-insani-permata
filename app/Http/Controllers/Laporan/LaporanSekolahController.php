<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sekolah; // pastikan model Sekolah sudah ada
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanSekolahController extends Controller
{
    public function index()
    {
        $sekolahInfo = [
            'nama'   => 'SD IT Permata Insani',
            'alamat' => 'Jl. Kenanga No. 123, Kota Depok, Jawa Barat',
            'telepon'=> '(021) 1234567',
            'email'  => 'info@permatainsani.sch.id'
        ];

        $sekolahs = Sekolah::all();
        $tanggalLaporan = Carbon::now()->translatedFormat('d F Y');

        return view('laporan.sekolah', compact('sekolahInfo','sekolahs','tanggalLaporan'));
    }

    public function exportExcel()
    {
        $sekolahs = Sekolah::all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul
        $sheet->setCellValue('A1', 'Laporan Data Sekolah');
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Header
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Nama Sekolah');
        $sheet->setCellValue('C3', 'Alamat');
        $sheet->setCellValue('D3', 'Kontak');
        $sheet->setCellValue('E3', 'Jenjang');
        $sheet->getStyle('A3:E3')->getFont()->setBold(true);

        // Isi data
        $row = 4;
        $no = 1;
        foreach ($sekolahs as $s) {
            $sheet->setCellValue('A'.$row, $no++);
            $sheet->setCellValue('B'.$row, $s->nama_sekolah);
            $sheet->setCellValue('C'.$row, $s->alamat);
            $sheet->setCellValue('D'.$row, $s->kontak);
            $sheet->setCellValue('E'.$row, $s->jenjang);
            $row++;
        }

        foreach (range('A','E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Laporan_Sekolah_'.Carbon::now()->format('Ymd_His').'.xlsx';
        $writer = new Xlsx($spreadsheet);
        $temp_file = tempnam(sys_get_temp_dir(), 'excel');
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }
}
