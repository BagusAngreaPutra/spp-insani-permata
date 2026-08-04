<?php

namespace Tests\Unit;

use App\Models\TahunAjaran;
use Carbon\Carbon;
use Tests\TestCase;

class AcademicYearTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_options_cover_the_current_academic_year_through_twenty_years_ahead(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 8, 4, 12, 0, 0, 'Asia/Jakarta'));

        $options = TahunAjaran::periodOptions();

        $this->assertCount(21, $options);
        $this->assertSame('2026/2027', $options[0]);
        $this->assertSame('2046/2047', $options[20]);
    }

    public function test_only_an_exact_consecutive_period_is_valid(): void
    {
        $this->assertSame('2026/2027', TahunAjaran::canonicalizePeriod('2026-2027'));
        $this->assertSame('2026/2027', TahunAjaran::canonicalizePeriod('2026/2027'));
        $this->assertNull(TahunAjaran::canonicalizePeriod('2025/20266'));
        $this->assertNull(TahunAjaran::canonicalizePeriod('2026/2028'));
    }

    public function test_model_exposes_the_selected_period_bounds(): void
    {
        $year = new TahunAjaran(['nama_tahun' => '2026/2027']);

        $this->assertSame([2026, 2027], $year->periodBounds());
    }
}
