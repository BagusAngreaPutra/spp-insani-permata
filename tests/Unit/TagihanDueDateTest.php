<?php

namespace Tests\Unit;

use App\Models\Tagihan;
use Carbon\Carbon;
use Tests\TestCase as BaseTestCase;

class TagihanDueDateTest extends BaseTestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_only_a_date_after_today_is_not_yet_due(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 8, 4, 23, 30, 0, 'Asia/Jakarta'));

        $future = new Tagihan(['tanggal_jatuh_tempo' => '2026-08-05']);
        $today = new Tagihan(['tanggal_jatuh_tempo' => '2026-08-04']);
        $past = new Tagihan(['tanggal_jatuh_tempo' => '2026-08-03']);

        $this->assertTrue($future->isBelumJatuhTempo());
        $this->assertFalse($today->isBelumJatuhTempo());
        $this->assertFalse($past->isBelumJatuhTempo());
    }

    public function test_a_bill_without_a_due_date_is_not_classified_as_upcoming(): void
    {
        $tagihan = new Tagihan(['tanggal_jatuh_tempo' => null]);

        $this->assertFalse($tagihan->isBelumJatuhTempo());
    }
}
