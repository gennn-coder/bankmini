<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Member;
use App\Models\Saver;
use App\Models\Rekening;
use App\Models\Transaksi;
use App\Models\LaporanHarian;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class BankMiniRevisionTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;
    /**
     * Test 1: Verifikasi tabel members dan savers ada dengan penamaan bahasa Inggris
     */
    public function test_tables_and_english_naming_exist()
    {
        $this->assertTrue(Schema::hasTable('members'), 'Tabel members harus ada');
        $this->assertTrue(Schema::hasTable('savers'), 'Tabel savers harus ada');
        $this->assertTrue(Schema::hasColumn('members', 'user_id'));
        $this->assertTrue(Schema::hasColumn('members', 'student_name'));
        $this->assertTrue(Schema::hasColumn('members', 'graduation_year'));
        $this->assertTrue(Schema::hasColumn('members', 'status'));
        $this->assertTrue(Schema::hasColumn('savers', 'account_number'));
        $this->assertTrue(Schema::hasColumn('savers', 'student_name'));
        $this->assertTrue(Schema::hasColumn('savers', 'deleted_at'));
    }

    /**
     * Test 2: Admin bisa CRUD tabel members / anggota
     */
    public function test_admin_can_crud_members()
    {
        $admin = User::where('role', 'administrator')->first();
        $this->actingAs($admin);

        // CREATE
        $nis = 'TEST_' . rand(10000, 99999);
        $response = $this->post(route('admin.members.store'), [
            'student_name' => 'Siswa Uji Coba',
            'nis' => $nis,
            'class_grade' => 'X PPLG 1',
            'graduation_year' => '2028',
        ]);
        $response->assertSessionHas('success');

        $member = Member::where('nis', $nis)->first();
        $this->assertNotNull($member);
        $this->assertEquals('rekening tidak aktif', $member->status);

        // UPDATE
        $responseUpdate = $this->post(route('admin.members.update', $member->user_id), [
            'student_name' => 'Siswa Uji Coba Update',
            'nis' => $nis,
            'class_grade' => 'X PPLG 2',
            'graduation_year' => '2028',
            'status' => 'rekening tidak aktif',
        ]);
        $responseUpdate->assertSessionHas('success');
        $this->assertEquals('Siswa Uji Coba Update', $member->fresh()->student_name);

        // DELETE
        $responseDelete = $this->post(route('admin.members.destroy', $member->user_id));
        $responseDelete->assertSessionHas('success');
        $this->assertSoftDeleted('members', ['user_id' => $member->user_id]);
    }

    /**
     * Test 3: Sistem otomatis jika anggota menabung minimal 10rb data otomatis pindah ke saver
     */
    public function test_automated_member_activation_moves_data_to_saver()
    {
        $admin = User::where('role', 'administrator')->first();
        $teller = User::where('role', 'teller')->first();

        // Admin buat anggota baru
        $nis = 'AKT_' . rand(10000, 99999);
        $user = User::create([
            'username' => $nis,
            'nama_petugas' => 'Calon Penabung Sukses',
            'role' => 'nasabah',
            'password' => Hash::make('123456'),
            'status' => 'aktif',
        ]);

        $member = Member::create([
            'user_id' => $user->id,
            'student_name' => 'Calon Penabung Sukses',
            'nis' => $nis,
            'class_grade' => 'X AKL 2',
            'graduation_year' => '2028',
            'status' => 'rekening tidak aktif',
        ]);

        // Teller mengaktifkan dengan setoran awal Rp 25.000 (minimal 10.000)
        $this->actingAs($teller);
        $response = $this->post(route('teller.aktivasi-anggota'), [
            'user_id' => $member->user_id,
            'nominal' => 25000,
            'pin' => '654321',
            'email' => 'sukses@gmail.com',
        ]);

        $response->assertSessionHas('success');

        // Verifikasi status member menjadi aktif
        $this->assertEquals('rekening aktif', $member->fresh()->status);

        // Verifikasi data otomatis masuk ke tabel savers (kloning)
        $saver = Saver::where('user_id', $member->user_id)->first();
        $this->assertNotNull($saver);
        $this->assertEquals('Calon Penabung Sukses', $saver->student_name);
        $this->assertEquals($nis, $saver->nis);
        $this->assertNotNull($saver->account_number);

        // Verifikasi saldo awal 25.000
        $this->assertEquals(25000, $saver->rekening->saldo);

        // Verifikasi ada transaksi setoran awal
        $transaksi = Transaksi::where('saver_id', $saver->id)->first();
        $this->assertNotNull($transaksi);
        $this->assertEquals(25000, $transaksi->nominal);
    }

    /**
     * Test 4: Ketika akun penabung dihapus dan kas ditutup belum di-acc, setelah akun dihapus
     * dan laporan di-acc, transaksi akun yang dihapus TIDAK BOLEH IKUT TERHAPUS pada laporan PDF
     */
    public function test_deleted_saver_transactions_preserved_in_approved_report_pdf()
    {
        $admin = User::where('role', 'administrator')->first();
        $teller = User::create([
            'username' => 'teller_t4_' . rand(1000, 9999),
            'nama_petugas' => 'Teller Uji Coba 4',
            'role' => 'teller',
            'password' => Hash::make('123456'),
            'status' => 'aktif',
        ]);

        // 1. Buat saver dan transaksi
        $nis = 'DEL_' . rand(10000, 99999);
        $accountNum = '999' . rand(10000, 99999);
        $user = User::create([
            'username' => $nis,
            'nama_petugas' => 'Siswa Yang Akan Dihapus',
            'role' => 'nasabah',
            'password' => Hash::make('123456'),
            'status' => 'aktif',
        ]);

        $saver = Saver::create([
            'user_id' => $user->id,
            'account_number' => $accountNum,
            'nis' => $nis,
            'student_name' => 'Siswa Yang Akan Dihapus',
            'class_grade' => 'XII RPL 1',
            'security_pin' => Hash::make('123456'),
        ]);

        Rekening::create([
            'saver_id' => $saver->id,
            'account_number' => $accountNum,
            'saldo' => 50000,
            'status' => 'aktif',
        ]);

        // Transaksi setoran oleh teller hari ini
        $transaksi = Transaksi::create([
            'no_transaksi' => 'TEST-DEL-' . uniqid(),
            'saver_id' => $saver->id,
            'id_user' => $teller->id,
            'saver_name' => $saver->student_name,
            'account_number' => $saver->account_number,
            'tanggal' => now(),
            'jenis_trans' => 'setoran',
            'nominal' => 50000,
            'keterangan' => 'Uji Coba Preservasi Hapus',
        ]);

        // 2. Teller menutup kas (Laporan Harian pending)
        $this->actingAs($teller);
        $this->post(route('teller.laporan-harian'), [
            'uang_fisik' => 50000,
        ]);

        $laporan = LaporanHarian::where('user_id', $teller->id)
            ->whereDate('tanggal', date('Y-m-d'))
            ->first();
        $this->assertNotNull($laporan);
        $this->assertEquals('pending', $laporan->status_approval);

        // 3. Admin menghapus akun penabung (Soft Delete)
        $this->actingAs($admin);
        $this->post(route('admin.savers.destroy', $saver->id));
        $this->assertSoftDeleted('savers', ['id' => $saver->id]);

        // Transaksi tidak boleh hilang
        $this->assertDatabaseHas('transaksi', ['id' => $transaksi->id]);

        // 4. Admin menyetujui (ACC) laporan harian
        $this->post(route('admin.laporan.approve', $laporan->id));
        $this->assertEquals('approved', $laporan->fresh()->status_approval);

        // 5. Download PDF Laporan Harian
        $responsePdf = $this->get(route('admin.laporan.pdf', $laporan->id));
        $responsePdf->assertStatus(200);
        $responsePdf->assertHeader('content-type', 'application/pdf');

        // Pastikan transaksi tetap terhubung ke model dengan withTrashed
        $tx = Transaksi::with(['saver' => fn($q) => $q->withTrashed()])->find($transaksi->id);
        $this->assertNotNull($tx->saver);
        $this->assertStringContainsString('Siswa Yang Akan Dihapus', $tx->student_name_display);
    }

    /**
     * Test 5: Laporan Bulanan dan PDF Laporan Bulanan
     */
    public function test_monthly_report_pdf_generation()
    {
        $admin = User::where('role', 'administrator')->first();
        $this->actingAs($admin);

        $response = $this->get(route('admin.laporan-bulanan.pdf', [
            'month' => date('n'),
            'year' => date('Y'),
        ]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    /**
     * Test 6: Fitur Lupa PIN (Admin & Teller bisa reset PIN)
     */
    public function test_lupa_pin_and_reset_pin()
    {
        $admin = User::where('role', 'administrator')->first();
        $saver = Saver::first();

        // Admin reset PIN
        $this->actingAs($admin);
        $response = $this->post(route('admin.savers.reset-pin', $saver->id), [
            'pin' => '998877',
        ]);
        $response->assertSessionHas('success');

        $this->assertTrue(Hash::check('998877', $saver->fresh()->security_pin));

        // API Lupa PIN
        $responseApi = $this->postJson('/api/nasabah/lupa-pin', [
            'nis' => $saver->nis,
        ]);
        $responseApi->assertStatus(200);
        $responseApi->assertJson(['success' => true]);
    }

    /**
     * Test 7: Supervisor dijadikan satu dengan Administrator
     */
    public function test_supervisor_merged_with_admin()
    {
        $admin = User::where('role', 'administrator')->first();
        $this->actingAs($admin);

        // Akses supervisor/dashboard otomatis redirect ke admin/dashboard
        $response = $this->get(route('supervisor.dashboard'));
        $response->assertRedirect(route('admin.dashboard'));

        // Admin memiliki fungsi approval supervisor
        $this->assertTrue($admin->isSupervisor());
        $this->assertTrue($admin->isAdmin());
    }

    /**
     * Test 8: Admin hanya 1, form user khusus untuk teller
     */
    public function test_admin_is_single_and_only_teller_can_be_added()
    {
        $admin = User::where('role', 'administrator')->first();
        $this->actingAs($admin);

        // 1. Mencoba menambah admin baru -> harus ditolak
        $responseAdmin = $this->post(route('admin.users.store'), [
            'nama_petugas' => 'Admin Cadangan',
            'username' => 'admin2',
            'role' => 'administrator',
            'password' => '123456',
        ]);
        $responseAdmin->assertSessionHas('error');
        $this->assertNull(User::where('username', 'admin2')->first());

        // 2. Menambah Teller -> Berhasil
        $tellerUsername = 'teller_test_' . rand(100, 999);
        $responseTeller = $this->post(route('admin.users.store'), [
            'nama_petugas' => 'Teller Uji Coba',
            'username' => $tellerUsername,
            'role' => 'teller',
            'password' => '123456',
        ]);
        $responseTeller->assertSessionHas('success');
        $teller = User::where('username', $tellerUsername)->first();
        $this->assertNotNull($teller);
        $this->assertEquals('teller', $teller->role);

        // 3. Akun Admin tidak dapat diblokir
        $responseBlockAdmin = $this->post(route('admin.users.toggle-status', $admin->id));
        $responseBlockAdmin->assertSessionHas('error');
        $this->assertEquals('aktif', $admin->fresh()->status);
    }

    /**
     * Test 9: Alur Akun Nasabah: Belum Aktif -> Diaktifkan Otomatis Mendapat No Rekening Unik & QR
     */
    public function test_member_activation_lifecycle_and_unique_account_number()
    {
        $admin = User::where('role', 'administrator')->first();
        $teller = User::where('role', 'teller')->first();
        $this->actingAs($admin);

        // 1. Buat data siswa baru di Admin -> status awal 'rekening tidak aktif'
        $nis = 'TEST_' . rand(100000, 999999);
        $this->post(route('admin.members.store'), [
            'student_name' => 'Siswa Baru Belum Aktif',
            'nis' => $nis,
            'class_grade' => 'X PPLG 1',
            'graduation_year' => '2028',
        ]);

        $member = Member::where('nis', $nis)->first();
        $this->assertNotNull($member);
        $this->assertEquals('rekening tidak aktif', $member->status);
        $this->assertNull($member->saver); // Belum ada penabung / rekening

        // 2. Saat Teller mencari siswa ini untuk menabung -> ditolak dengan info belum aktif
        $this->actingAs($teller);
        $responseCari = $this->getJson(route('teller.cari-nasabah', ['q' => $nis]));
        $responseCari->assertJson(['success' => false]);
        $this->assertStringContainsString('belum diaktifkan', $responseCari->json('message'));

        // 3. Saat siswa login ke dashboard sebelum aktif -> rekening Belum Aktif
        $userSiswa = $member->user;
        $this->actingAs($userSiswa);
        $responseDashboard = $this->get(route('nasabah.dashboard'));
        $responseDashboard->assertSee('Belum Aktif');
        $responseDashboard->assertSee('Status: Rekening Belum Aktif');

        // 4. Admin mengaktifkan status rekening siswa ini
        $this->actingAs($admin);
        $this->post(route('admin.members.update', $member->user_id), [
            'student_name' => 'Siswa Baru Belum Aktif',
            'nis' => $nis,
            'class_grade' => 'X PPLG 1',
            'graduation_year' => '2028',
            'status' => 'rekening aktif',
        ]);

        $member->refresh();
        $this->assertEquals('rekening aktif', $member->status);
        $this->assertNotNull($member->saver);
        $this->assertNotNull($member->saver->rekening);
        $this->assertNotEmpty($member->saver->account_number);

        // 5. Sekarang Teller mencari kembali -> data penabung ditemukan dan siap setor saldo
        $this->actingAs($teller);
        $responseCariAktif = $this->getJson(route('teller.cari-nasabah', ['q' => $nis]));
        $responseCariAktif->assertJson(['success' => true]);
        $this->assertEquals($member->saver->account_number, $responseCariAktif->json('data.no_rekening'));

        // 6. Sekarang siswa login ke dashboard -> nomor rekening unik dan QR aktif tampil
        $this->actingAs($userSiswa);
        $responseDashboardAktif = $this->get(route('nasabah.dashboard'));
        $responseDashboardAktif->assertSee($member->saver->account_number);
        $responseDashboardAktif->assertSee('Status: Rekening Aktif');

        // 7. Nonaktifkan kembali di Admin
        $this->actingAs($admin);
        $this->post(route('admin.members.update', $member->user_id), [
            'student_name' => 'Siswa Baru Belum Aktif',
            'nis' => $nis,
            'class_grade' => 'X PPLG 1',
            'graduation_year' => '2028',
            'status' => 'rekening tidak aktif',
        ]);
        $member->refresh();
        $this->assertEquals('rekening tidak aktif', $member->status);

        // 8. Reaktivasi lewat Teller -> harus berhasil tanpa SQL duplicate entry 1062
        $this->actingAs($teller);
        $responseAktivasiTeller = $this->post(route('teller.aktivasi-anggota'), [
            'user_id' => $member->user_id,
            'nominal' => 25000,
            'pin' => '654321',
        ]);
        $responseAktivasiTeller->assertSessionHas('success');
        $member->refresh();
        $this->assertEquals('rekening aktif', $member->status);
        $this->assertEquals(25000, $member->saver->rekening->saldo);
    }
}
