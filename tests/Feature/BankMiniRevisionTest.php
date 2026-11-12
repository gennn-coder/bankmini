<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Member;
use App\Models\Saver;
use App\Models\Rekening;
use App\Models\Transaksi;
use App\Models\LaporanHarian;
use App\Models\PengajuanKas;
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
            'email' => 'siswa.uji@gmail.com',
            'class_grade' => 'PPLG',
            'graduation_year' => '2028',
            'password' => '123456',
            'pin' => '123456',
        ]);
        $response->assertSessionHas('success');

        $member = Member::where('nis', $nis)->first();
        $this->assertNotNull($member);
        $this->assertEquals('siswa.uji@gmail.com', $member->email);
        $this->assertEquals('rekening tidak aktif', $member->status);

        // UPDATE
        $responseUpdate = $this->post(route('admin.members.update', $member->user_id), [
            'student_name' => 'Siswa Uji Coba Update',
            'nis' => $nis,
            'email' => 'siswa.uji.update@gmail.com',
            'class_grade' => 'RPL',
            'graduation_year' => '2028',
            'status' => 'rekening tidak aktif',
        ]);
        $responseUpdate->assertSessionHas('success');
        $this->assertEquals('Siswa Uji Coba Update', $member->fresh()->student_name);
        $this->assertEquals('siswa.uji.update@gmail.com', $member->fresh()->email);

        // Pastikan sinkronisasi juga berlaku jika ada Saver
        $testSaver = Saver::create([
            'user_id' => $member->user_id,
            'account_number' => 'TEST9999',
            'nis' => $nis,
            'student_name' => 'Siswa Uji Coba Update',
            'class_grade' => 'X PPLG 2',
            'email' => 'siswa.uji.update@gmail.com',
            'security_pin' => bcrypt('123456'),
        ]);
        $responseUpdateSaver = $this->post(route('admin.members.update', $member->user_id), [
            'student_name' => 'Nama Baru Sinkron Saver',
            'nis' => $nis,
            'email' => 'nama.baru@gmail.com',
            'class_grade' => 'RPL 1',
            'graduation_year' => '2028',
        ]);
        $responseUpdateSaver->assertSessionHas('success');
        $this->assertEquals('Nama Baru Sinkron Saver', $testSaver->fresh()->student_name);
        $this->assertEquals('RPL 1', $testSaver->fresh()->class_grade);
        $testSaver->forceDelete();

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

    public function test_monthly_report_filter_by_jenis_trans()
    {
        $admin = User::where('role', 'administrator')->first();
        $this->actingAs($admin);

        // Filter Setoran
        $response = $this->get(route('admin.dashboard', [
            'tab' => 'tab-laporan-bulanan',
            'month' => date('n'),
            'year' => date('Y'),
            'jenis_trans' => 'setoran',
        ]));
        $response->assertStatus(200);
        $response->assertViewHas('selectedJenis', 'setoran');

        // Filter Penarikan
        $responsePenarikan = $this->get(route('admin.dashboard', [
            'tab' => 'tab-laporan-bulanan',
            'month' => date('n'),
            'year' => date('Y'),
            'jenis_trans' => 'penarikan',
        ]));
        $responsePenarikan->assertStatus(200);
        $responsePenarikan->assertViewHas('selectedJenis', 'penarikan');

        // PDF dengan filter jenis_trans
        $responsePdf = $this->get(route('admin.laporan-bulanan.pdf', [
            'month' => date('n'),
            'year' => date('Y'),
            'jenis_trans' => 'setoran',
        ]));
        $responsePdf->assertStatus(200);
        $responsePdf->assertHeader('content-type', 'application/pdf');
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
        $tellerUsername = 't_' . rand(1000, 9999);
        $responseTeller = $this->post(route('admin.users.store'), [
            'nama_petugas' => 'Teller Uji Coba',
            'username' => $tellerUsername,
            'email' => $tellerUsername . '@gmail.com',
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
        $nis = 'T' . rand(100000, 999999);
        $this->post(route('admin.members.store'), [
            'student_name' => 'Siswa Baru Belum Aktif',
            'nis' => $nis,
            'email' => 's.' . $nis . '@gmail.com',
            'class_grade' => 'PPLG',
            'graduation_year' => '2028',
            'password' => '123456',
            'pin' => '123456',
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
            'class_grade' => 'PPLG',
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
            'class_grade' => 'PPLG',
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
            'email' => 'siswa.reaktif@gmail.com',
        ]);
        $responseAktivasiTeller->assertSessionHas('success');
        $member->refresh();
        $this->assertEquals('rekening aktif', $member->status);
        $this->assertEquals(25000, $member->saver->rekening->saldo);
    }

    /**
     * Test 12: Validasi Minimal Penarikan Rp 10.000 & Batas Saldo Mengendap
     */
    public function test_minimum_penarikan_enforced_at_10000()
    {
        $teller = User::where('role', 'teller')->first();
        $saver = Saver::with('rekening')->first();
        $this->actingAs($teller);

        // Set saldo penabung cukup
        $saver->rekening->update(['saldo' => 50000]);
        $saver->update(['security_pin' => Hash::make('123456')]);

        // Coba tarik di bawah 10.000 (misal 5.000) -> harus ditolak
        $response = $this->post(route('teller.penarikan'), [
            'id_nasabah' => $saver->id,
            'nominal' => 5000,
            'pin' => '123456',
        ]);
        $response->assertSessionHasErrors('nominal');

        // Tarik 10.000 dengan benar -> harus sukses dan menghasilkan session transaksi_sukses
        $responseSukses = $this->post(route('teller.penarikan'), [
            'id_nasabah' => $saver->id,
            'nominal' => 10000,
            'pin' => '123456',
        ]);
        $responseSukses->assertSessionHas('transaksi_sukses');
        $saver->rekening->refresh();
        $this->assertEquals(40000, $saver->rekening->saldo);
    }

    /**
     * Test 13: Penolakan Laporan Kas oleh Admin Wajib Ada Catatan Penolakan
     */
    public function test_admin_rejection_requires_catatan_penolakan()
    {
        $admin = User::where('role', 'administrator')->first();
        $teller = User::where('role', 'teller')->first();
        $this->actingAs($admin);

        $laporan = \App\Models\LaporanHarian::create([
            'tanggal' => date('Y-m-d'),
            'user_id' => $teller->id,
            'saldo_awal' => 0,
            'total_setoran' => 300000,
            'total_penarikan' => 0,
            'saldo_akhir_sistem' => 300000,
            'uang_fisik' => 50000,
            'selisih' => -250000,
            'status_approval' => 'pending',
        ]);

        // Tolak tanpa catatan -> validasi error
        $responseErr = $this->post(route('admin.laporan.reject', $laporan->id), []);
        $responseErr->assertSessionHasErrors('catatan_penolakan');

        // Tolak dengan catatan -> sukses dan tersimpan ke database
        $responseOk = $this->post(route('admin.laporan.reject', $laporan->id), [
            'catatan_penolakan' => 'Uang fisik di laci kurang Rp 250.000, harap cek kembali.',
        ]);
        $responseOk->assertSessionHas('success');
        $laporan->refresh();
        $this->assertEquals('rejected', $laporan->status_approval);
        $this->assertEquals('Uang fisik di laci kurang Rp 250.000, harap cek kembali.', $laporan->catatan_penolakan);
    }

    /**
     * Test 14: Lupa Sandi & Lupa PIN di Halaman Login Berhasil Memperbarui Kredensial
     */
    public function test_lupa_sandi_and_pin_updates_credentials()
    {
        $saver = Saver::first();
        $saver->update(['email' => 'siswa.test@gmail.com']);
        $user = User::find($saver->user_id);
        $oldPassword = $user->password;
        $oldPin = $saver->security_pin;

        // 1. Lupa Sandi
        $responseSandi = $this->post(route('lupa-sandi'), [
            'email' => 'siswa.test@gmail.com',
        ]);
        $responseSandi->assertSessionHas('success_lupa');
        $responseSandi->assertSessionMissing('kredensial_info');
        $user->refresh();
        $this->assertNotEquals($oldPassword, $user->password);

        // 2. Lupa PIN
        $responsePin = $this->post(route('lupa-pin'), [
            'email' => 'siswa.test@gmail.com',
        ]);
        $responsePin->assertSessionHas('success_lupa');
        $responsePin->assertSessionMissing('kredensial_info');
        $saver->refresh();
        $this->assertNotEquals($oldPin, $saver->security_pin);
    }

    /**
     * Test 15: Pendaftaran Petugas Teller & Anggota Nasabah Mewajibkan Alamat Gmail
     */
    public function test_registration_requires_email_for_both_teller_and_nasabah()
    {
        $admin = User::where('role', 'administrator')->first();
        $this->actingAs($admin);

        // 1. Tambah Teller tanpa email -> Gagal validasi
        $resTellerErr = $this->post(route('admin.users.store'), [
            'username' => 'teller_no_email_' . rand(100, 999),
            'nama_petugas' => 'Teller No Email',
            'role' => 'teller',
            'password' => 'secret123',
        ]);
        $resTellerErr->assertSessionHasErrors('email');

        // 2. Tambah Teller dengan email -> Sukses
        $tellerUsername = 'tel_' . rand(1000, 9999);
        $tellerEmail = $tellerUsername . '@gmail.com';
        $resTellerOk = $this->post(route('admin.users.store'), [
            'username' => $tellerUsername,
            'nama_petugas' => 'Teller Sukses Email',
            'email' => $tellerEmail,
            'role' => 'teller',
            'password' => 'secret123',
        ]);
        $resTellerOk->assertSessionHas('success');
        $tellerUser = User::where('username', $tellerUsername)->first();
        $this->assertNotNull($tellerUser);
        $this->assertEquals($tellerEmail, $tellerUser->email);

        // 3. Tambah Anggota tanpa email -> Gagal validasi
        $resMemberErr = $this->post(route('admin.members.store'), [
            'student_name' => 'Siswa No Email',
            'nis' => 'NO_' . rand(1000, 9999),
            'class_grade' => 'RPL',
            'graduation_year' => '2028',
        ]);
        $resMemberErr->assertSessionHasErrors('email');

        // 4. Tambah Anggota dengan email -> Sukses
        $nis = 'OK_' . rand(1000, 9999);
        $memberEmail = 's_' . $nis . '@gmail.com';
        $resMemberOk = $this->post(route('admin.members.store'), [
            'student_name' => 'Siswa Sukses Email',
            'nis' => $nis,
            'email' => $memberEmail,
            'class_grade' => 'RPL',
            'graduation_year' => '2028',
            'password' => '123456',
            'pin' => '123456',
        ]);
        $resMemberOk->assertSessionHas('success');
        $member = Member::where('nis', $nis)->first();
        $this->assertNotNull($member);
        $this->assertEquals($memberEmail, $member->email);
        $this->assertEquals($memberEmail, $member->user->email);

        // 5. Validasi PIN harus tepat 6 digit dan Password minimal 6 karakter
        $resPinShort = $this->post(route('admin.members.store'), [
            'student_name' => 'Siswa PIN Pendek',
            'nis' => 'PIN_ERR_' . rand(1000, 9999),
            'email' => 'siswa.pin.err@gmail.com',
            'class_grade' => 'X RPL 1',
            'graduation_year' => '2028',
            'password' => '12345', // kurang dari 6
            'pin' => '12345', // kurang dari 6 digit
        ]);
        $resPinShort->assertSessionHasErrors(['password', 'pin']);
    }

    /**
     * Test 16: Pemulihan Sandi via OTP dengan NISN / Username dan Alamat Gmail
     */
    public function test_lupa_sandi_otp_flow_with_nisn_and_username()
    {
        $saver = Saver::first();
        $saver->update(['email' => 'siswa.otp@gmail.com']);
        $user = User::find($saver->user_id);
        $user->update(['email' => 'siswa.otp@gmail.com']);

        // 1. Kirim OTP dengan NISN
        $resOtp = $this->postJson(route('lupa-sandi.kirim-otp'), [
            'identifier' => $saver->nis,
            'email' => 'siswa.otp@gmail.com',
        ]);
        $resOtp->assertStatus(200);
        $resOtp->assertJson(['success' => true]);

        // Ambil OTP dari cache
        $cachedData = \Illuminate\Support\Facades\Cache::get('otp_reset_' . $user->id);
        $this->assertNotNull($cachedData);
        $otp = $cachedData['otp'];
        $this->assertEquals(6, strlen($otp));

        // 2. Verifikasi OTP yang salah -> Gagal
        $resVerifFail = $this->postJson(route('lupa-sandi.verifikasi-otp'), [
            'identifier' => $saver->nis,
            'email' => 'siswa.otp@gmail.com',
            'otp' => '000000',
        ]);
        $resVerifFail->assertStatus(422);

        // 3. Verifikasi OTP yang benar -> Sukses & menampilkan nama akun
        $resVerifOk = $this->postJson(route('lupa-sandi.verifikasi-otp'), [
            'identifier' => $saver->nis,
            'email' => 'siswa.otp@gmail.com',
            'otp' => $otp,
        ]);
        $resVerifOk->assertStatus(200);
        $resVerifOk->assertJson([
            'success' => true,
            'nama' => $saver->student_name,
        ]);
        $resetToken = $resVerifOk->json('reset_token');
        $this->assertNotEmpty($resetToken);

        // 4. Simpan Sandi Baru
        $newPass = 'sandiBaru999';
        $resSimpan = $this->postJson(route('lupa-sandi.simpan-sandi'), [
            'reset_token' => $resetToken,
            'password' => $newPass,
            'password_confirmation' => $newPass,
        ]);
        $resSimpan->assertStatus(200);
        $resSimpan->assertJson(['success' => true]);

        // 5. Cek bahwa user bisa login dengan sandi baru
        $resLogin = $this->post(route('login'), [
            'username' => $saver->nis,
            'password' => $newPass,
        ]);
        $resLogin->assertRedirect(route('nasabah.dashboard'));
        $this->assertAuthenticatedAs($user);

        // 6. Test juga dengan Username Petugas Teller
        $teller = User::where('role', 'teller')->first();
        $this->assertNotNull($teller);
        $teller->update(['email' => 'teller.otp@gmail.com']);

        $resOtpTeller = $this->postJson(route('lupa-sandi.kirim-otp'), [
            'identifier' => $teller->username,
            'email' => 'teller.otp@gmail.com',
        ]);
        $resOtpTeller->assertStatus(200);

        $cachedTeller = \Illuminate\Support\Facades\Cache::get('otp_reset_' . $teller->id);
        $this->assertNotNull($cachedTeller);

        $resVerifTeller = $this->postJson(route('lupa-sandi.verifikasi-otp'), [
            'identifier' => $teller->username,
            'email' => 'teller.otp@gmail.com',
            'otp' => $cachedTeller['otp'],
        ]);
        $resVerifTeller->assertStatus(200);
        $resVerifTeller->assertJson([
            'success' => true,
            'nama' => $teller->nama_petugas,
        ]);
    }

    /**
     * Test 17: Alur Lupa PIN Transaksi Nasabah via OTP
     */
    public function test_lupa_pin_transaction_reset_flow()
    {
        $teller = User::where('role', 'teller')->first();
        $this->actingAs($teller);

        // 1. Setup Nasabah Saver
        $nis = 'PIN' . rand(10000, 99999);
        $user = User::create([
            'username' => $nis,
            'nama_petugas' => 'Siswa Test PIN',
            'role' => 'nasabah',
            'email' => 'nasabah.pin@example.com',
            'password' => Hash::make('password123'),
            'status' => 'aktif',
        ]);

        $saver = Saver::create([
            'user_id' => $user->id,
            'student_name' => 'Siswa Test PIN',
            'nis' => $nis,
            'account_number' => 'ACC' . rand(10000, 99999),
            'class_grade' => 'XI AKL 1',
            'email' => 'nasabah.pin@example.com',
            'security_pin' => Hash::make('111111'),
        ]);

        // 2. Kirim OTP
        $resOtp = $this->postJson(route('lupa-sandi.kirim-otp'), [
            'identifier' => $saver->nis,
            'email' => 'nasabah.pin@example.com',
        ]);
        $resOtp->assertStatus(200)->assertJson(['success' => true]);

        $cachedOtp = \Illuminate\Support\Facades\Cache::get('otp_reset_' . $user->id);
        $this->assertNotNull($cachedOtp);

        // 3. Verifikasi OTP
        $resVerif = $this->postJson(route('lupa-sandi.verifikasi-otp'), [
            'identifier' => $saver->nis,
            'email' => 'nasabah.pin@example.com',
            'otp' => $cachedOtp['otp'],
        ]);
        $resVerif->assertStatus(200)->assertJson(['success' => true]);
        $resetToken = $resVerif->json('reset_token');
        $this->assertNotEmpty($resetToken);

        // 4. Simpan PIN Baru
        $resSimpan = $this->postJson(route('lupa-pin.simpan-pin'), [
            'reset_token' => $resetToken,
            'pin' => '987654',
            'pin_confirmation' => '987654',
        ]);
        $resSimpan->assertStatus(200)->assertJson([
            'success' => true,
            'pin' => '987654',
        ]);

        // 5. Cek PIN baru terupdate di database
        $this->assertTrue(Hash::check('987654', $saver->fresh()->security_pin));
    }

    /**
     * Test 21: Mencegah Dobel Entri Transaksi pada Penarikan dan Setoran
     */
    public function test_anti_double_entry_prevents_duplicate_transactions_on_penarikan_and_setoran()
    {
        $teller = User::where('role', 'teller')->first();
        $saver = Saver::with('rekening')->first();
        $this->actingAs($teller);

        $saver->rekening->update(['saldo' => 100000]);
        $saver->update(['security_pin' => Hash::make('123456')]);

        $tokenPenarikan = (string) \Illuminate\Support\Str::uuid();

        // 1. Klik pertama penarikan Rp 10.000 -> Sukses
        $res1 = $this->post(route('teller.penarikan'), [
            '_token_transaksi' => $tokenPenarikan,
            'id_nasabah' => $saver->id,
            'nominal' => 10000,
            'pin' => '123456',
        ]);
        $res1->assertSessionHas('transaksi_sukses');

        // 2. Klik kedua (dobel entri dengan token sama atau dalam rentang waktu cepat) -> Wajib Ditolak
        $res2 = $this->post(route('teller.penarikan'), [
            '_token_transaksi' => $tokenPenarikan,
            'id_nasabah' => $saver->id,
            'nominal' => 10000,
            'pin' => '123456',
        ]);
        $res2->assertSessionHas('error');

        $saver->rekening->refresh();
        // Saldo awal 100.000 hanya berkurang 1x (10.000) menjadi 90.000, BUKAN 80.000!
        $this->assertEquals(90000, $saver->rekening->saldo);

        // Uji hal yang sama untuk SETORAN
        $tokenSetoran = (string) \Illuminate\Support\Str::uuid();

        // 3. Klik pertama setoran Rp 20.000 -> Sukses
        $resSetor1 = $this->post(route('teller.setoran'), [
            '_token_transaksi' => $tokenSetoran,
            'id_nasabah' => $saver->id,
            'nominal' => 20000,
        ]);
        $resSetor1->assertSessionHas('transaksi_sukses');

        // 4. Klik kedua setoran (dobel entri) -> Wajib Ditolak
        $resSetor2 = $this->post(route('teller.setoran'), [
            '_token_transaksi' => $tokenSetoran,
            'id_nasabah' => $saver->id,
            'nominal' => 20000,
        ]);
        $resSetor2->assertSessionHas('error');

        $saver->rekening->refresh();
        // Saldo 90.000 + 20.000 = 110.000 (hanya bertambah 1x, BUKAN 130.000!)
        $this->assertEquals(110000, $saver->rekening->saldo);

        // 5. Uji pencegahan dobel entri berbasis jendela waktu (rapid click tanpa token)
        $resRapid1 = $this->post(route('teller.setoran'), [
            'id_nasabah' => $saver->id,
            'nominal' => 15000,
        ]);
        $resRapid1->assertSessionHas('transaksi_sukses');

        $resRapid2 = $this->post(route('teller.setoran'), [
            'id_nasabah' => $saver->id,
            'nominal' => 15000,
        ]);
        $resRapid2->assertSessionHas('error');

        $saver->rekening->refresh();
        $this->assertEquals(125000, $saver->rekening->saldo);
    }

    /**
     * Test 20: Input Modal Awal Kas 1x Per Hari & Alur Pengajuan Tambahan Kas ke Admin
     */
    public function test_modal_awal_harian_dan_pengajuan_tambahan_kas_flow()
    {
        $teller = User::where('role', 'teller')->first();
        $admin = User::where('role', 'administrator')->first();
        $this->assertNotNull($teller);
        $this->assertNotNull($admin);

        // Bersihkan laporan harian, transaksi, & pengajuan kas hari ini untuk teller uji agar mulai dari state bersih
        LaporanHarian::where('tanggal', date('Y-m-d'))
            ->where('user_id', $teller->id)
            ->delete();
        Transaksi::where('id_user', $teller->id)
            ->whereDate('tanggal', date('Y-m-d'))
            ->delete();
        PengajuanKas::where('user_id', $teller->id)
            ->delete();

        // 1. Teller input modal awal Rp 100.000 pertama kali hari ini -> Sukses
        $this->actingAs($teller);
        $resModal1 = $this->post(route('teller.modal-awal'), [
            'modal_awal' => 100000,
        ]);
        $resModal1->assertSessionHas('success');

        $lap = LaporanHarian::where('tanggal', date('Y-m-d'))
            ->where('user_id', $teller->id)
            ->first();
        $this->assertNotNull($lap);
        $this->assertEquals(100000, $lap->saldo_awal);

        // 2. Teller input modal awal LAGI di hari yang sama -> DITOLAK (Hanya 1x per hari)
        $resModal2 = $this->post(route('teller.modal-awal'), [
            'modal_awal' => 50000,
        ]);
        $resModal2->assertSessionHas('error');
        $lap->refresh();
        $this->assertEquals(100000, $lap->saldo_awal); // Saldo awal tetap 100.000

        // Siapkan akun saver dengan saldo cukup (Rp 200.000)
        $saver = Saver::first();
        $saver->rekening->update(['saldo' => 200000]);

        // 3. Teller mencoba mencairkan penarikan Rp 110.000
        // Karena modal awal di kas laci hanya Rp 100.000, kas laci tidak cukup -> Wajib Ditolak
        $resTarikKurang = $this->post(route('teller.penarikan'), [
            'id_nasabah' => $saver->id,
            'nominal' => 110000,
            'pin' => '123456',
        ]);
        $resTarikKurang->assertSessionHas('error');
        $saver->rekening->refresh();
        $this->assertEquals(200000, $saver->rekening->saldo); // Saldo nasabah belum berkurang

        // 4. Teller mengajukan tambahan kas ke admin (Nominal: 50.000, Alasan: Dana kurang untuk penarikan)
        $resAjukan = $this->post(route('teller.ajukan-kas'), [
            'nominal' => 50000,
            'alasan' => 'Kas laci kurang untuk penarikan nasabah',
        ]);
        $resAjukan->assertSessionHas('success');

        $pengajuan = PengajuanKas::where('user_id', $teller->id)
            ->where('status', 'pending')
            ->first();
        $this->assertNotNull($pengajuan);
        $this->assertEquals(50000, $pengajuan->nominal);
        $this->assertEquals('Kas laci kurang untuk penarikan nasabah', $pengajuan->alasan);

        // 5. Admin login dan melakukan ACC (Approve) pengajuan kas
        $this->actingAs($admin);
        $resApprove = $this->post(route('admin.pengajuan-kas.approve', $pengajuan->id));
        $resApprove->assertSessionHas('success');

        $pengajuan->refresh();
        $this->assertEquals('approved', $pengajuan->status);
        $this->assertEquals($admin->id, $pengajuan->admin_id);

        // 6. Sekarang teller login lagi. Kas laci = 100.000 + 50.000 = 150.000
        // Penarikan Rp 110.000 sekarang SUKSES!
        $this->actingAs($teller);
        $resTarikSukses = $this->post(route('teller.penarikan'), [
            'id_nasabah' => $saver->id,
            'nominal' => 110000,
            'pin' => '123456',
        ]);
        $resTarikSukses->assertSessionHas('transaksi_sukses');

        $saver->rekening->refresh();
        $this->assertEquals(90000, $saver->rekening->saldo); // 200.000 - 110.000 = 90.000

        // 7. Uji jika admin menolak (reject) pengajuan kas lain
        $resAjukan2 = $this->post(route('teller.ajukan-kas'), [
            'nominal' => 20000,
            'alasan' => 'Permintaan tambahan kas kedua',
        ]);
        $resAjukan2->assertSessionHas('success');

        $pengajuan2 = PengajuanKas::where('user_id', $teller->id)
            ->where('status', 'pending')
            ->first();
        $this->assertNotNull($pengajuan2);

        $this->actingAs($admin);
        // Tolak tanpa alasan harus gagal validasi
        $resRejectFail = $this->post(route('admin.pengajuan-kas.reject', $pengajuan2->id), []);
        $resRejectFail->assertSessionHasErrors('catatan_admin');

        // Tolak dengan alasan harus berhasil
        $resReject = $this->post(route('admin.pengajuan-kas.reject', $pengajuan2->id), [
            'catatan_admin' => 'Kas utama sedang limit, tunda penarikan.',
        ]);
        $resReject->assertSessionHas('success');

        $pengajuan2->refresh();
        $this->assertEquals('rejected', $pengajuan2->status);
        $this->assertEquals('Kas utama sedang limit, tunda penarikan.', $pengajuan2->catatan_admin);

        // 8. Verifikasi: Modal awal TIDAK langsung masuk antrean approval admin sebelum teller tutup kas
        $resAdminDash = $this->get(route('admin.dashboard'));
        $resAdminDash->assertStatus(200);
        $pendingLaporan = $resAdminDash->viewData('laporanPending');
        $this->assertFalse($pendingLaporan->contains('id', $lap->id), 'Laporan teller tidak boleh muncul di pending admin sebelum tutup kas');

        // 9. Teller melakukan Penutupan Kas Harian (1x per hari)
        // Kas laci saat ini: 100.000 (modal) + 50.000 (tambahan ACC) - 110.000 (tarik) = 40.000
        $this->actingAs($teller);
        $resSubmitLaporan = $this->post(route('teller.laporan-harian'), [
            'uang_fisik' => 40000,
        ]);
        $resSubmitLaporan->assertSessionHas('success');

        $lap->refresh();
        $this->assertNotNull($lap->submitted_at);
        $this->assertEquals(40000, $lap->saldo_akhir_sistem);
        $this->assertEquals(40000, $lap->uang_fisik);
        $this->assertEquals(0, $lap->selisih);

        // 10. Teller mencoba submit laporan harian LAGI di hari yang sama -> DITOLAK (Hanya 1x per hari)
        $resSubmitLaporan2 = $this->post(route('teller.laporan-harian'), [
            'uang_fisik' => 40000,
        ]);
        $resSubmitLaporan2->assertSessionHas('error');

        // 11. Setelah disubmit oleh teller, laporan SEKARANG muncul di admin menunggu approval
        $this->actingAs($admin);
        $resAdminDash2 = $this->get(route('admin.dashboard'));
        $pendingLaporan2 = $resAdminDash2->viewData('laporanPending');
        $this->assertTrue($pendingLaporan2->contains('id', $lap->id), 'Laporan teller harus muncul di pending admin setelah disubmit');

        // 12. Admin melakukan Approve (ACC) penutupan kas -> Uang kas dipindahkan ke admin
        $resApproveLap = $this->post(route('admin.laporan.approve', $lap->id));
        $resApproveLap->assertSessionHas('success');

        $lap->refresh();
        $this->assertEquals('approved', $lap->status_approval);

        // 13. Setelah di-ACC admin, laporan berpindah ke arsip disetujui admin dan kas laci teller menjadi 0 (pindah ke admin)
        $resAdminDash3 = $this->get(route('admin.dashboard'));
        $approvedLaporan = $resAdminDash3->viewData('laporanApproved');
        $this->assertTrue($approvedLaporan->contains('id', $lap->id), 'Laporan harus berpindah ke laporan disetujui');

        $this->actingAs($teller);
        $resTellerDash = $this->get(route('teller.dashboard'));
        $this->assertEquals(0, $resTellerDash->viewData('sisaKasLaci'), 'Sisa kas teller harus 0 karena kas fisik sudah diserahkan ke admin');
    }

    /**
     * Test 21: Unduh Template Excel / CSV & Impor Data Anggota Massal
     */
    public function test_admin_can_download_member_templates_and_import_csv()
    {
        $admin = User::where('role', 'administrator')->first();
        $this->actingAs($admin);

        // 1. Download template Excel
        $resTplXlsx = $this->get(route('admin.members.template', ['format' => 'xlsx']));
        $resTplXlsx->assertStatus(200);

        // 2. Download template CSV
        $resTplCsv = $this->get(route('admin.members.template', ['format' => 'csv']));
        $resTplCsv->assertStatus(200);

        // 3. Impor data anggota melalui file CSV
        $nis1 = '202699001';
        $nis2 = '202699002';
        
        // Hapus jika sudah pernah ada
        Member::whereIn('nis', [$nis1, $nis2])->forceDelete();
        User::whereIn('username', [$nis1, $nis2])->forceDelete();

        $csvContent = "nama_siswa,nis,email,kelas,angkatan_keluar,password\n"
                    . "Budi Santoso,{$nis1},budi.santoso@gmail.com,X RPL 1,2028,123456\n"
                    . "Dewi Lestari,{$nis2},dewi.lestari@gmail.com,X RPL 2,2028,123456\n";

        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('anggota.csv', $csvContent);

        $resImport = $this->post(route('admin.members.import'), [
            'file' => $file,
        ]);

        $resImport->assertSessionHas('success');

        // Pastikan kedua data masuk ke tabel users dan members (status rekening tidak aktif, pin null)
        $this->assertDatabaseHas('users', ['username' => $nis1, 'nama_petugas' => 'Budi Santoso']);
        $this->assertDatabaseHas('members', ['nis' => $nis1, 'student_name' => 'Budi Santoso', 'status' => 'rekening tidak aktif', 'pin' => null]);

        $this->assertDatabaseHas('users', ['username' => $nis2, 'nama_petugas' => 'Dewi Lestari']);
        $this->assertDatabaseHas('members', ['nis' => $nis2, 'student_name' => 'Dewi Lestari', 'status' => 'rekening tidak aktif', 'pin' => null]);

        // 4. Impor data anggota melalui file CSV dengan format baru (nisn,nama,jurusan,tahun_keluar)
        $nis3 = '202699003';
        Member::where('nis', $nis3)->forceDelete();
        User::where('username', $nis3)->forceDelete();

        $csvNewFormat = "nisn,nama,jurusan,tahun_keluar\n"
                      . "{$nis3},Siti Aminah,AKL,2028\n";
        $fileNew = \Illuminate\Http\UploadedFile::fake()->createWithContent('anggota_baru.csv', $csvNewFormat);

        $resImportNew = $this->post(route('admin.members.import'), [
            'file' => $fileNew,
        ]);
        $resImportNew->assertSessionHas('success');
        $this->assertDatabaseHas('members', ['nis' => $nis3, 'student_name' => 'Siti Aminah', 'class_grade' => 'AKL', 'graduation_year' => '2028']);

        // 5. Test aktivasi teller dengan pin dan konfirmasi pin cocok
        $teller = User::where('role', 'teller')->first();
        $this->actingAs($teller);
        $member3 = Member::where('nis', $nis3)->first();

        // 5a. Konfirmasi pin salah harus gagal
        $resFailPin = $this->post(route('teller.aktivasi-anggota'), [
            'user_id' => $member3->user_id,
            'nominal' => 10000,
            'pin' => '123456',
            'pin_confirmation' => '654321',
            'email' => 'siti.aminah@gmail.com',
        ]);
        $resFailPin->assertSessionHasErrors('pin');

        // 5b. Konfirmasi pin cocok harus berhasil
        $resSuccessPin = $this->post(route('teller.aktivasi-anggota'), [
            'user_id' => $member3->user_id,
            'nominal' => 10000,
            'pin' => '123456',
            'pin_confirmation' => '123456',
            'email' => 'siti.aminah@gmail.com',
        ]);
        $resSuccessPin->assertSessionHas('success');
        $this->assertDatabaseHas('savers', ['nis' => $nis3, 'email' => 'siti.aminah@gmail.com']);

        // 6. Impor ulang file yang sama -> Data harus dilewati (skip) karena duplikat
        $this->actingAs($admin);
        $resImportDup = $this->post(route('admin.members.import'), [
            'file' => $file,
        ]);
        $resImportDup->assertSessionHas('success');
    }

    /**
     * Test 22: Akses Halaman Landing Page APK Nasabah & Rute Unduh
     */
    public function test_guest_can_access_landing_page_and_download_apk()
    {
        // 1. Akses landing page publik
        $response = $this->get(route('landing.apk'));
        $response->assertStatus(200);
        $response->assertSee('Tabungan Sekolah dalam Genggaman');
        $response->assertSee('Pindai Kode QR untuk Mengunduh');

        // 2. Akses halaman login memastikan tautan Unduh APK ada
        $resLogin = $this->get(route('login'));
        $resLogin->assertStatus(200);
        $resLogin->assertSee(route('landing.apk'));
        $resLogin->assertSee('Unduh Aplikasi Nasabah (APK)');

        // 3. Tes rute download APK
        $resDownload = $this->get(route('app.download'));
        $this->assertTrue(in_array($resDownload->getStatusCode(), [200, 302]));

        // Coba letakkan dummy file APK untuk memastikan download file berfungsi
        $dummyPath = public_path('downloads/test-dummy-download.apk');
        file_put_contents($dummyPath, 'DUMMY_APK_DATA');
        
        $resDownloadOk = $this->get(route('app.download'));
        $this->assertTrue(in_array($resDownloadOk->getStatusCode(), [200, 302]));
        
        // Bersihkan dummy
        @unlink($dummyPath);
    }

    /**
     * Test 23: API Pemulihan Sandi Nasabah via OTP
     */
    public function test_api_nasabah_lupa_sandi_otp_flow()
    {
        $user = User::create([
            'nama_petugas' => 'Nasabah OTP Test',
            'role' => 'nasabah',
            'email' => 'nasabah.otp@gmail.com',
            'username' => 'nasabah_otp_user_' . rand(1000, 9999),
            'password' => \Illuminate\Support\Facades\Hash::make('passwordLama123'),
        ]);

        // 1. Kirim OTP
        $resOtp = $this->postJson('/api/nasabah/lupa-sandi/kirim-otp', [
            'identifier' => $user->username,
            'email' => 'nasabah.otp@gmail.com',
        ]);
        $resOtp->assertStatus(200);
        $resOtp->assertJson(['success' => true]);

        // Cek Cache OTP
        $cached = \Illuminate\Support\Facades\Cache::get('otp_reset_' . $user->id);
        $this->assertNotNull($cached);
        $otp = $cached['otp'];

        // 2. Verifikasi OTP Salah
        $resWrong = $this->postJson('/api/nasabah/lupa-sandi/verifikasi-otp', [
            'identifier' => $user->username,
            'email' => 'nasabah.otp@gmail.com',
            'otp' => '000000',
        ]);
        $resWrong->assertStatus(422);

        // 3. Verifikasi OTP Benar
        $resVerify = $this->postJson('/api/nasabah/lupa-sandi/verifikasi-otp', [
            'identifier' => $user->username,
            'email' => 'nasabah.otp@gmail.com',
            'otp' => $otp,
        ]);
        $resVerify->assertStatus(200);
        $resVerify->assertJson(['success' => true]);
        $resetToken = $resVerify->json('reset_token');
        $this->assertNotEmpty($resetToken);

        // 4. Simpan Sandi Baru
        $resSave = $this->postJson('/api/nasabah/lupa-sandi/simpan-sandi', [
            'reset_token' => $resetToken,
            'password' => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123',
        ]);
        $resSave->assertStatus(200);
        $resSave->assertJson(['success' => true]);

        // 5. Cek login dengan sandi baru
        $user->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('passwordBaru123', $user->password));
    }
}


