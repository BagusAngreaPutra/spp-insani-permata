<?php

namespace Tests\Feature;

use App\Http\Controllers\SekolahController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class SchoolClassWithoutLevelTest extends TestCase
{
    use RefreshDatabase;

    public function test_school_form_can_store_a_class_without_level(): void
    {
        $request = Request::create('/sekolah', 'POST', [
            'nama_sekolah' => 'Sekolah Uji Tanpa Tingkat',
            'kode_sekolah' => 'UTT',
            'alamat' => 'Alamat pengujian',
            'kelas' => [[
                'id' => null,
                'tingkat' => 'none',
                'nama_kelas' => 'Rombel Umum',
                'tahun_ajaran_id' => null,
                'hapus' => 0,
            ]],
        ]);

        (new SekolahController())->store($request);

        $this->assertDatabaseHas('kelas', [
            'tingkat' => null,
            'nama_kelas' => 'Rombel Umum',
        ]);
    }
}
