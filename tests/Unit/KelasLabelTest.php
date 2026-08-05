<?php

namespace Tests\Unit;

use App\Models\Kelas;
use PHPUnit\Framework\TestCase;

class KelasLabelTest extends TestCase
{
    public function test_class_without_level_has_a_clear_label(): void
    {
        $kelas = new Kelas([
            'tingkat' => null,
            'nama_kelas' => 'Rombel Umum',
        ]);

        $this->assertSame('Tanpa tingkat', $kelas->label_tingkat);
        $this->assertSame('Tanpa tingkat Rombel Umum', $kelas->kelas);
    }

    public function test_numbered_class_keeps_its_level_label(): void
    {
        $kelas = new Kelas([
            'tingkat' => 3,
            'nama_kelas' => 'A',
        ]);

        $this->assertSame('Tingkat 3', $kelas->label_tingkat);
        $this->assertSame('Tingkat 3 A', $kelas->kelas);
    }
}
