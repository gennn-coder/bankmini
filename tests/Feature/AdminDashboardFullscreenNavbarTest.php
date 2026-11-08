<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Member;
use App\Models\Saver;
use App\Models\Rekening;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminDashboardFullscreenNavbarTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::firstOrCreate(
            ['username' => 'admin_test_' . uniqid()],
            [
                'nama_petugas' => 'Admin Penguji Navbar',
                'email' => 'admin_navbar_' . uniqid() . '@example.com',
                'password' => bcrypt('password123'),
                'role' => 'administrator',
                'status' => 'aktif'
            ]
        );
    }

    public function test_admin_dashboard_renders_with_top_navbar_and_no_sidebar()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Pastikan tidak ada wrapper floating window atau sidebar lama
        $response->assertDontSee('class="cryptowall-app"', false);
        $response->assertDontSee('class="sidebar-col', false);
        $response->assertDontSee('id="adminRightPanel"', false);

        // Pastikan ada sticky navbar di atas
        $response->assertSee('class="navbar"', false);
        $response->assertSee('Administrator', false);
        $response->assertSee('E-Teller Bank Mini Sekolah', false);
        $response->assertSee('ADMIN', false);
        $response->assertSee('Keluar', false);

        // Pastikan ada nav-tabs horizontal di atas
        $response->assertSee('class="nav-tabs-bar"', false);
        $response->assertSee('class="nav-tabs"', false);

        // Pastikan kesembilan tab teks tersedia
        $response->assertSee('Dashboard', false);
        $response->assertSee('Manajemen User', false);
        $response->assertSee('Data Anggota Siswa', false);
        $response->assertSee('Data Penabung', false);
        $response->assertSee('Approval Kas & Tutup Kas', false);
        $response->assertSee('Laporan Bulanan', false);
        $response->assertSee('Jurnal Akuntansi', false);
        $response->assertSee('Audit Logs', false);
        $response->assertSee('Pengaduan Nasabah', false);

        // Pastikan tombol tab TIDAK menggunakan icon SVG di dalamnya
        $content = $response->getContent();
        preg_match('/<div class="nav-tabs">(.*?)<\/div>/s', $content, $matches);
        $this->assertNotEmpty($matches);
        $navTabsHtml = $matches[1];

        // Verifikasi tidak ada tag <svg> di dalam horizontal nav-tabs
        $this->assertStringNotContainsString('<svg', $navTabsHtml, 'Nav-tabs must NOT contain any SVG icons!');
    }
}
