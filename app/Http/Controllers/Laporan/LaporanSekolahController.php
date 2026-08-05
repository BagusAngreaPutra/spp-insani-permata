<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanSekolahController extends Controller
{
    public function index(Request $request)
    {
        $sekolahs = $this->reportQuery($request)->get();
        $daftarSekolah = Sekolah::query()->orderBy('nama_sekolah')->get();
        $daftarTahun = TahunAjaran::validPeriods();
        $daftarTingkat = Kelas::query()
            ->whereNotNull('tingkat')
            ->distinct()
            ->orderBy('tingkat')
            ->pluck('tingkat');
        $adaKelasTanpaTingkat = Kelas::query()->whereNull('tingkat')->exists();

        $ringkasan = [
            'sekolah' => $sekolahs->count(),
            'kelas' => $sekolahs->sum(fn (Sekolah $sekolah) => $sekolah->kelas->count()),
            'siswa' => $sekolahs->sum(
                fn (Sekolah $sekolah) => $sekolah->kelas->sum('siswa_count')
            ),
            'tanpa_kelas' => $sekolahs->filter(fn (Sekolah $sekolah) => $sekolah->kelas->isEmpty())->count(),
        ];

        $tanggalLaporan = Carbon::now()->translatedFormat('d F Y');

        return view('laporan.sekolah', compact(
            'sekolahs',
            'daftarSekolah',
            'daftarTahun',
            'daftarTingkat',
            'adaKelasTanpaTingkat',
            'ringkasan',
            'tanggalLaporan'
        ));
    }

    public function exportExcel(Request $request)
    {
        $sekolahs = $this->reportQuery($request)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sekolah dan Kelas');

        $sheet->setCellValue('A1', 'LAPORAN DATA SEKOLAH DAN KELAS');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $headers = [
            'No',
            'Nama Sekolah',
            'Kode / Jenjang',
            'Alamat / Kontak',
            'Kelas',
            'Tahun Ajaran',
            'Jumlah Siswa',
        ];

        foreach ($headers as $index => $header) {
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($index + 1).'3', $header);
        }

        $sheet->getStyle('A3:G3')->getFont()->setBold(true);
        $sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row = 4;
        $number = 1;

        foreach ($sekolahs as $sekolah) {
            $kelasRows = $sekolah->kelas->isNotEmpty() ? $sekolah->kelas : collect([null]);

            foreach ($kelasRows as $kelas) {
                $contact = $sekolah->kontak ?: ($sekolah->telepon ?: '-');
                $classLabel = $kelas?->kelas ?? 'Belum ada kelas';

                $sheet->setCellValue('A'.$row, $number++);
                $sheet->setCellValue('B'.$row, $sekolah->nama_sekolah);
                $sheet->setCellValue('C'.$row, trim(($sekolah->kode_sekolah ?: '-').' / '.($sekolah->jenjang ?: '-')));
                $sheet->setCellValue('D'.$row, trim(($sekolah->alamat ?: '-').' / '.$contact));
                $sheet->setCellValue('E'.$row, $classLabel);
                $sheet->setCellValue('F'.$row, $kelas?->tahunAjaran?->label ?? '-');
                $sheet->setCellValue('G'.$row, $kelas?->siswa_count ?? 0);
                $row++;
            }
        }

        if ($row === 4) {
            $sheet->setCellValue('A4', 'Tidak ada data untuk filter yang dipilih.');
            $sheet->mergeCells('A4:G4');
            $row = 5;
        }

        $lastRow = $row - 1;
        $sheet->getStyle('A3:G'.$lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A3:G'.$lastRow)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        $sheet->getStyle('A3:G'.$lastRow)->getAlignment()->setWrapText(true);
        $sheet->setAutoFilter('A3:G'.$lastRow);
        $sheet->freezePane('A4');

        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $filename = 'Laporan_Sekolah_dan_Kelas_'.Carbon::now()->format('Ymd_His').'.xlsx';
        $writer = new Xlsx($spreadsheet);
        $temporaryFile = tempnam(sys_get_temp_dir(), 'laporan_sekolah_kelas_');
        $writer->save($temporaryFile);

        return response()->download($temporaryFile, $filename)->deleteFileAfterSend(true);
    }

    private function reportQuery(Request $request): Builder
    {
        $query = Sekolah::query()
            ->withCount('siswa')
            ->with([
                'kelas' => function ($kelasQuery) use ($request) {
                    $this->applyClassFilters($kelasQuery, $request);
                    $kelasQuery
                        ->with('tahunAjaran')
                        ->withCount('siswa')
                        ->orderBy('tingkat')
                        ->orderBy('nama_kelas');
                },
            ])
            ->orderBy('nama_sekolah');

        if ($request->filled('sekolah_id')) {
            $query->whereKey($request->integer('sekolah_id'));
        }

        if ($request->filled('tahun_ajaran_id') || $request->filled('tingkat')) {
            $query->whereHas('kelas', function ($kelasQuery) use ($request) {
                $this->applyClassFilters($kelasQuery, $request);
            });
        }

        if ($request->filled('search')) {
            $term = trim((string) $request->input('search'));
            $query->where(function (Builder $schoolQuery) use ($term) {
                $schoolQuery
                    ->where('nama_sekolah', 'like', '%'.$term.'%')
                    ->orWhere('kode_sekolah', 'like', '%'.$term.'%')
                    ->orWhere('jenjang', 'like', '%'.$term.'%')
                    ->orWhereHas('kelas', function (Builder $classQuery) use ($term) {
                        $classQuery
                            ->where('nama_kelas', 'like', '%'.$term.'%')
                            ->orWhere('tingkat', 'like', '%'.$term.'%');
                    });
            });
        }

        return $query;
    }

    private function applyClassFilters(Builder|Relation $query, Request $request): void
    {
        if ($request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->integer('tahun_ajaran_id'));
        }

        if ($request->filled('tingkat')) {
            $tingkat = trim((string) $request->input('tingkat'));

            if ($tingkat === 'none') {
                $query->whereNull('tingkat');
            } else {
                $query->where('tingkat', $tingkat);
            }
        }
    }
}
