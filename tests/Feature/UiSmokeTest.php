<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UiSmokeTest extends TestCase
{
    use DatabaseTransactions;

    public function test_public_entry_pages_render(): void
    {
        $this->get('/')->assertOk()->assertSee('Pilih akun');
        $this->get('/login')->assertOk()->assertSee('Masuk Guru');
        $this->get('/siswa/login')->assertOk()->assertSee('Masuk Siswa');
    }

    public function test_admin_dashboard_renders_with_the_new_shell(): void
    {
        $admin = Admin::query()->firstOrFail();

        $this->actingAs($admin, 'web')
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Permata Insani')
            ->assertSee('Ringkasan')
            ->assertSee('Cari menu')
            ->assertSee('Pusat laporan')
            ->assertSee('fa-school', false)
            ->assertSee('fa-file-import', false)
            ->assertSee('app-sidebar-footer', false);
    }

    public function test_primary_admin_feature_pages_render(): void
    {
        $admin = Admin::query()->firstOrFail();
        $this->actingAs($admin, 'web');

        $routes = [
            'sekolah.index',
            'tahun_ajaran.index',
            'kelas.index',
            'siswa.index',
            'admin.index',
            'jenis_pembayaran.index',
            'tagihan.index.grouped',
            'riwayat.index',
            'koperasi.index',
            'koperasi.penjualan.index',
            'pemasukan.index',
            'pengeluaran.index',
            'keuangan.kas.index',
            'kenaikan.index',
            'kelulusan.index',
            'log_aktivitas.index',
            'import.form',
            'export_excel.index',
            'backup.index',
            'laporan.pembayaran',
            'laporan.pemasukan',
            'laporan.pengeluaran',
            'laporan.siswa',
            'laporan.kelas',
            'laporan.sekolah',
            'laporan.admin',
            'laporan.jenis_pembayaran',
            'laporan.kenaikan',
            'laporan.kelulusan',
            'laporan.koperasi',
            'laporan.tahun_ajaran',
        ];

        foreach ($routes as $routeName) {
            $response = $this->get(route($routeName));
            $this->assertTrue(
                $response->isSuccessful(),
                "{$routeName} returned HTTP {$response->getStatusCode()}"
            );
            $response->assertSee('permata-design-system', false);
        }
    }

    public function test_primary_admin_create_forms_render(): void
    {
        $admin = Admin::query()->firstOrFail();
        $this->actingAs($admin, 'web');

        foreach ([
            'sekolah.create',
            'tahun_ajaran.create',
            'kelas.create',
            'siswa.create',
            'admin.create',
            'jenis_pembayaran.create',
            'koperasi.create',
            'koperasi.penjualan.create',
            'pemasukan.create',
            'pengeluaran.create',
        ] as $routeName) {
            $response = $this->get(route($routeName));
            $this->assertTrue(
                $response->isSuccessful(),
                "{$routeName} returned HTTP {$response->getStatusCode()}"
            );
            $response->assertSee('permata-design-system', false);
        }
    }

    public function test_primary_student_pages_render(): void
    {
        $siswa = Siswa::query()->firstOrFail();
        $this->actingAs($siswa, 'siswa');

        foreach ([
            'siswa.dashboard',
            'siswa.tagihan.index',
            'siswa.riwayat.index',
            'siswa.profil.index',
        ] as $routeName) {
            $response = $this->get(route($routeName));
            $this->assertTrue(
                $response->isSuccessful(),
                "{$routeName} returned HTTP {$response->getStatusCode()}"
            );
            $response->assertSee('permata-design-system', false);
        }
    }
}
