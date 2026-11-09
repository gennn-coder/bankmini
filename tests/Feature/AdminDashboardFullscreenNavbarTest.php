<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Member;
use App\Models\Saver;
use App\Models\Rekening;
use App\Models\Transaksi;
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

        // Pastikan kotak pencarian di sebelah kiri nama/Halo sudah dihapus
        $response->assertDontSee('id="globalSearchInput"', false);
        $response->assertDontSee('class="search-pill-box"', false);

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

    public function test_tab_members_has_inline_form_and_import_box_underneath()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // 1. Box Tambah Anggota Siswa Baru inline (seperti box Tambah User)
        $response->assertSee('Tambah Anggota Siswa Baru', false);
        $response->assertSee('name="student_name"', false);
        $response->assertSee('name="nis"', false);
        $response->assertSee('name="class_grade"', false);
        $response->assertSee('name="graduation_year"', false);
        $response->assertSee('adminMemberPasswordInline', false);
        $response->assertSee('adminMemberPinInline', false);
        $response->assertSee('Simpan Data Anggota', false);

        // 2. Box Impor Data Anggota tepat di bawah box Tambah Anggota
        $response->assertSee('Impor Data Anggota', false);
        $response->assertSee('Template Excel (.xlsx)', false);
        $response->assertSee('Template CSV', false);
        $response->assertSee('name="file"', false);
        $response->assertSee('Unggah &amp; Impor Anggota', false);

        // 3. Box Daftar Anggota Siswa (Members) dengan tabel
        $response->assertSee('Daftar Anggota Siswa (Members)', false);
        $response->assertSee('tableMemberBody', false);
    }

    public function test_recent_transactions_displays_real_amount_and_not_zero()
    {
        $saver = Saver::first();
        if (!$saver) {
            $user = User::create([
                'nama_petugas' => 'Siswa Penguji',
                'username' => 'siswa_' . uniqid(),
                'email' => 'siswa_' . uniqid() . '@example.com',
                'password' => bcrypt('password123'),
                'role' => 'saver',
                'status' => 'aktif',
            ]);
            $saver = Saver::create([
                'user_id' => $user->id,
                'account_number' => '100' . rand(10000, 99999),
                'nis' => (string)rand(10000, 99999),
                'student_name' => 'Siswa Penguji Trx',
                'class_grade' => 'XII RPL',
            ]);
        }

        $uniqueAmount = 876543;
        Transaksi::create([
            'no_transaksi' => 'TEST-TRX-' . uniqid(),
            'saver_id' => $saver->id,
            'id_user' => $this->admin->id,
            'saver_name' => $saver->student_name,
            'account_number' => $saver->account_number,
            'tanggal' => now(),
            'jenis_trans' => 'setoran',
            'nominal' => $uniqueAmount,
            'keterangan' => 'Setoran Uji Coba Trx Terkini',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Pastikan nominal transaksi muncul dengan format rupiah dan bukan 0
        $response->assertSee('+Rp ' . number_format($uniqueAmount, 0, ',', '.'), false);
        $response->assertSee('Transaksi Terkini (Recent Transactions)', false);

        // Pastikan widget Grafik Pemasukan & Pengeluaran tampil menggantikan Kartu Kas
        $response->assertSee('Grafik Pemasukan &amp; Pengeluaran', false);
        $response->assertSee('Pemasukan', false);
        $response->assertSee('Pengeluaran', false);
        $response->assertDontSee('Kartu Kas &amp; Likuiditas Sekolah', false);
        $response->assertDontSee('class="debit-card-blue"', false);
    }

    public function test_recent_transactions_is_limited_to_maximum_five_items()
    {
        $saver = Saver::first();
        if (!$saver) {
            $user = User::create([
                'nama_petugas' => 'Siswa Penguji',
                'username' => 'siswa_' . uniqid(),
                'email' => 'siswa_' . uniqid() . '@example.com',
                'password' => bcrypt('password123'),
                'role' => 'saver',
                'status' => 'aktif',
            ]);
            $saver = Saver::create([
                'user_id' => $user->id,
                'account_number' => '100' . rand(10000, 99999),
                'nis' => (string)rand(10000, 99999),
                'student_name' => 'Siswa Penguji Trx 2',
                'class_grade' => 'XII RPL',
            ]);
        }

        // Buat 7 transaksi baru
        for ($i = 1; $i <= 7; $i++) {
            Transaksi::create([
                'no_transaksi' => 'TEST-LIMIT-' . uniqid() . '-' . $i,
                'saver_id' => $saver->id,
                'id_user' => $this->admin->id,
                'saver_name' => $saver->student_name,
                'account_number' => $saver->account_number,
                'tanggal' => now()->subMinutes($i),
                'jenis_trans' => 'setoran',
                'nominal' => 10000 * $i,
                'keterangan' => 'Trx Test ' . $i,
            ]);
        }

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Hitung jumlah item transaksi yang dirender
        $content = $response->getContent();
        $transItemCount = substr_count($content, 'class="trans-item"');
        $this->assertLessThanOrEqual(5, $transItemCount, 'Recent transactions must be limited to at most 5 items.');
    }
}
