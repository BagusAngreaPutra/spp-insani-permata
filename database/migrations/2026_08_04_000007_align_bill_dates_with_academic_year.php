<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('jenis_pembayaran', 'tahun_ajaran_id')
            || !Schema::hasColumn('tagihan', 'tahun_ajaran_id')) {
            return;
        }

        $academicYears = DB::table('tahun_ajaran')->pluck('nama_tahun', 'id');

        DB::table('jenis_pembayaran')
            ->whereNotNull('tahun_ajaran_id')
            ->orderBy('id')
            ->get()
            ->each(function ($paymentType) use ($academicYears) {
                $bounds = $this->periodBounds($academicYears->get($paymentType->tahun_ajaran_id));
                if (!$bounds || !$paymentType->jatuh_tempo) {
                    return;
                }

                [$startYear, $endYear] = $bounds;
                $template = Carbon::parse($paymentType->jatuh_tempo);

                if ($paymentType->tipe === 'bulanan') {
                    $canonicalTemplate = $this->safeDate($startYear, 7, $template->day);
                    DB::table('jenis_pembayaran')->where('id', $paymentType->id)->update([
                        'jatuh_tempo' => $canonicalTemplate->toDateString(),
                    ]);

                    DB::table('tagihan')
                        ->where('jenis_pembayaran_id', $paymentType->id)
                        ->where('tipe', 'bulanan')
                        ->select(['id', 'periode'])
                        ->orderBy('id')
                        ->get()
                        ->each(function ($bill) use ($template) {
                            if (!preg_match('/^(\d{4})-(\d{2})$/', (string) $bill->periode, $period)) {
                                return;
                            }

                            DB::table('tagihan')->where('id', $bill->id)->update([
                                'tanggal_jatuh_tempo' => $this->safeDate((int) $period[1], (int) $period[2], $template->day)->toDateString(),
                            ]);
                        });

                    return;
                }

                if ($paymentType->tipe === 'semester') {
                    $selectedMonth = $template->month;
                    $templateYear = $selectedMonth >= 7 ? $startYear : $endYear;
                    DB::table('jenis_pembayaran')->where('id', $paymentType->id)->update([
                        'jatuh_tempo' => $this->safeDate($templateYear, $selectedMonth, 1)->toDateString(),
                    ]);

                    DB::table('tagihan')
                        ->where('jenis_pembayaran_id', $paymentType->id)
                        ->where('tipe', 'semester')
                        ->select(['id', 'periode'])
                        ->orderBy('id')
                        ->get()
                        ->each(function ($bill) {
                            if (!preg_match('/^(\d{4})-(\d{2})$/', (string) $bill->periode, $period)) {
                                return;
                            }

                            DB::table('tagihan')->where('id', $bill->id)->update([
                                'tanggal_jatuh_tempo' => $this->safeDate((int) $period[1], (int) $period[2], 10)->toDateString(),
                            ]);
                        });

                    return;
                }

                $dueYear = $template->month >= 7 ? $startYear : $endYear;
                $canonicalDueDate = $this->safeDate($dueYear, $template->month, $template->day)->toDateString();

                DB::table('jenis_pembayaran')->where('id', $paymentType->id)->update([
                    'jatuh_tempo' => $canonicalDueDate,
                ]);
                DB::table('tagihan')->where('jenis_pembayaran_id', $paymentType->id)->update([
                    'tanggal_jatuh_tempo' => $canonicalDueDate,
                ]);
            });
    }

    public function down(): void
    {
        // Tanggal lama yang berada di luar tahun ajaran tidak dikembalikan.
    }

    private function periodBounds(?string $period): ?array
    {
        if (!preg_match('/^(\d{4})\/(\d{4})$/', trim((string) $period), $matches)) {
            return null;
        }

        $startYear = (int) $matches[1];
        $endYear = (int) $matches[2];

        return $endYear === $startYear + 1 ? [$startYear, $endYear] : null;
    }

    private function safeDate(int $year, int $month, int $day): Carbon
    {
        $lastDay = Carbon::create($year, $month, 1)->endOfMonth()->day;

        return Carbon::create($year, $month, min($day, $lastDay))->startOfDay();
    }
};
