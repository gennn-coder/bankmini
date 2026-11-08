<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Member;
use App\Models\Saver;
use App\Models\Rekening;
use App\Models\TargetTabungan;
use App\Models\Antrean;
use App\Models\Pengaduan;
use App\Models\PengajuanKas;
use App\Models\LaporanHarian;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ComprehensiveFeatureAuditTest extends TestCase
{
    private function getOrCreateAdmin()
    {
        $admin = User::where('role', 'administrator')->first();
        if (!$admin) {
            $admin = User::create([
                'username' => 'admin_audit',
                'nama_petugas' => 'Admin Audit Test',
                'role' => 'administrator',
                'password' => Hash::make('password123'),
                'status' => 'aktif',
                'email' => 'admin_audit@example.com',
            ]);
        }
        return $admin;
    }

    private function getOrCreateSupervisor()
    {
        $supervisor = User::where('role', 'supervisor')->first();
        if (!$supervisor) {
            $supervisor = User::create([
                'username' => 'spv_audit',
                'nama_petugas' => 'Supervisor Audit Test',
                'role' => 'supervisor',
                'password' => Hash::make('password123'),
                'status' => 'aktif',
                'email' => 'spv_audit@example.com',
            ]);
        }
        return $supervisor;
    }

    private function createTellerUser($suffix = null)
    {
        $suffix = $suffix ?: (uniqid() . rand(100, 999));
        return User::create([
            'username' => 'teller_' . $suffix,
            'nama_petugas' => 'Teller ' . $suffix,
            'role' => 'teller',
            'password' => Hash::make('password123'),
            'status' => 'aktif',
            'email' => 'teller_' . $suffix . '@example.com',
        ]);
    }

    private function createNasabahUser($nis = null, $name = 'Siswa Audit')
    {
        $nis = $nis ?: ('NIS_' . uniqid() . rand(100, 999));
        $user = User::create([
            'username' => $nis,
            'nama_petugas' => $name,
            'role' => 'nasabah',
            'password' => Hash::make('password123'),
            'status' => 'aktif',
            'email' => $nis . '@example.com',
        ]);

        $member = Member::create([
            'user_id' => $user->id,
            'student_name' => $name,
            'nis' => $nis,
            'email' => $nis . '@example.com',
            'class_grade' => 'XII RPL',
            'graduation_year' => '2027',
            'status' => 'rekening aktif',
            'pin' => Hash::make('123456'),
        ]);

        $accountNumber = '100' . rand(10000, 99999);
        $saver = Saver::create([
            'user_id' => $user->id,
            'account_number' => $accountNumber,
            'nis' => $nis,
            'student_name' => $name,
            'class_grade' => 'XII RPL',
            'email' => $nis . '@example.com',
            'security_pin' => Hash::make('123456'),
        ]);

        $rekening = Rekening::create([
            'saver_id' => $saver->id,
            'no_rekening' => $accountNumber,
            'saldo' => 500000,
            'status' => 'aktif',
        ]);

        return [$user, $member, $saver, $rekening];
    }

    /**
     * AUDIT 1: Akses Halaman Publik & Tamu (Guest)
     */
    public function test_audit_01_akses_halaman_publik_dan_landing()
    {
        $resWelcome = $this->get('/');
        $resWelcome->assertStatus(200);
        $resWelcome->assertSee('Bank Mini');

        $resLogin = $this->get('/login');
        $resLogin->assertStatus(200);

        $resLandingApk = $this->get('/unduh-aplikasi');
        $resLandingApk->assertStatus(200);
    }

    /**
     * AUDIT 2: Login, Autentikasi, Hak Akses & Logout
     */
    public function test_audit_02_autentikasi_dan_role_permissions()
    {
        $admin = $this->getOrCreateAdmin();
        $teller = $this->createTellerUser();
        [$nasabah] = $this->createNasabahUser();

        // 1. Guest tidak bisa akses dashboard (dilempar ke /login)
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/teller/dashboard')->assertRedirect('/login');
        $this->get('/nasabah/dashboard')->assertRedirect('/login');

        // 2. Nasabah tidak berhak akses admin / teller (dilempar ke /)
        $this->actingAs($nasabah)->get('/admin/dashboard')->assertRedirect('/');
        $this->actingAs($nasabah)->get('/teller/dashboard')->assertRedirect('/');

        // 3. Teller tidak berhak akses admin (dilempar ke /)
        $this->actingAs($teller)->get('/admin/dashboard')->assertRedirect('/');

        // 4. Role masing-masing berhasil masuk dashboard
        $this->actingAs($admin)->get('/admin/dashboard')->assertStatus(200);
        $this->actingAs($teller)->get('/teller/dashboard')->assertStatus(200);
        $this->actingAs($nasabah)->get('/nasabah/dashboard')->assertStatus(200);

        // 5. Test login via form dengan password salah
        $resFail = $this->post('/login', [
            'username' => $nasabah->username,
            'password' => 'passwordsalah123',
        ]);
        $resFail->assertSessionHasErrors('username');

        // 6. Test login via form dengan password benar
        $resSuccess = $this->post('/login', [
            'username' => $nasabah->username,
            'password' => 'password123',
        ]);
        $resSuccess->assertRedirect('/nasabah/dashboard');

        // 7. Test user terblokir ditolak login
        $blockedUser = User::create([
            'username' => 'blocked_' . rand(1000, 9999),
            'nama_petugas' => 'User Terblokir',
            'role' => 'nasabah',
            'password' => Hash::make('password123'),
            'status' => 'terblokir',
            'email' => 'blocked_' . rand(1000, 9999) . '@example.com',
        ]);
        $resBlocked = $this->post('/login', [
            'username' => $blockedUser->username,
            'password' => 'password123',
        ]);
        $resBlocked->assertSessionHasErrors('username');

        // 8. Logout
        $resLogout = $this->actingAs($nasabah)->post('/logout');
        $resLogout->assertRedirect('/');
        $this->assertGuest();
    }

    /**
     * AUDIT 3: Manajemen User Petugas oleh Admin
     */
    public function test_audit_03_admin_manajemen_user_petugas()
    {
        $admin = $this->getOrCreateAdmin();

        $randomSuffix = rand(1000, 9999);
        $username = 'teller_new_' . $randomSuffix;

        // Tambah Petugas Teller Baru
        $resStore = $this->actingAs($admin)->post(route('admin.users.store'), [
            'nama_petugas' => 'Petugas Baru ' . $randomSuffix,
            'username' => $username,
            'role' => 'teller',
            'password' => 'teller123456',
            'email' => $username . '@example.com',
        ]);
        $resStore->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'username' => $username,
            'role' => 'teller',
            'status' => 'aktif',
        ]);

        $newUser = User::where('username', $username)->first();

        // Toggle Status: Blokir User
        $resToggle1 = $this->actingAs($admin)->post(route('admin.users.toggle-status', $newUser->id));
        $resToggle1->assertSessionHas('success');
        $this->assertEquals('terblokir', $newUser->fresh()->status);

        // Toggle Status: Buka Blokir User
        $resToggle2 = $this->actingAs($admin)->post(route('admin.users.toggle-status', $newUser->id));
        $resToggle2->assertSessionHas('success');
        $this->assertEquals('aktif', $newUser->fresh()->status);
    }

    /**
     * AUDIT 4: Manajemen Anggota Siswa (Members) & Data Penabung (Savers)
     */
    public function test_audit_04_admin_manajemen_anggota_dan_penabung()
    {
        $admin = $this->getOrCreateAdmin();
        $nis = 'NIS_' . rand(10000, 99999);

        // 1. Admin Tambah Anggota Siswa Baru
        $resStoreMember = $this->actingAs($admin)->post(route('admin.members.store'), [
            'student_name' => 'Anggota Audit ' . $nis,
            'nis' => $nis,
            'email' => $nis . '@sekolah.sch.id',
            'class_grade' => 'X RPL 2',
            'graduation_year' => '2028',
            'password' => 'siswa123',
            'pin' => '654321',
        ]);
        $resStoreMember->assertSessionHas('success');
        $this->assertDatabaseHas('members', [
            'nis' => $nis,
            'student_name' => 'Anggota Audit ' . $nis,
        ]);

        $member = Member::where('nis', $nis)->first();

        // 2. Admin Edit Data Anggota Siswa
        $resUpdate = $this->actingAs($admin)->post(route('admin.members.update', $member->user_id), [
            'student_name' => 'Anggota Audit Updated',
            'nis' => $nis,
            'email' => $nis . '_updated@sekolah.sch.id',
            'class_grade' => 'XI RPL 2',
            'graduation_year' => '2028',
        ]);
        $resUpdate->assertSessionHas('success');
        $this->assertEquals('Anggota Audit Updated', $member->fresh()->student_name);

        // 3. Admin Reset PIN Penabung
        [$nasabahUser, $m, $saver] = $this->createNasabahUser();
        $newPin = '889900';
        $resResetPin = $this->actingAs($admin)->post(route('admin.savers.reset-pin', $saver->id), [
            'pin' => $newPin,
        ]);
        $resResetPin->assertSessionHas('success');
        $this->assertTrue(Hash::check($newPin, $saver->fresh()->security_pin));

        // 4. Admin Hapus Data Anggota (Soft Delete)
        $resDestroy = $this->actingAs($admin)->post(route('admin.members.destroy', $member->user_id));
        $resDestroy->assertSessionHas('success');
        $this->assertSoftDeleted('members', ['user_id' => $member->user_id]);
    }

    /**
     * AUDIT 5: Alur Approval Kas Teller & Tutup Kas Harian oleh Admin
     */
    public function test_audit_05_alur_approval_kas_dan_tutup_kas()
    {
        $admin = $this->getOrCreateAdmin();
        $teller = $this->createTellerUser();

        // 1. Teller mengajukan tambahan kas
        $pengajuan1 = PengajuanKas::create([
            'user_id' => $teller->id,
            'tanggal' => date('Y-m-d'),
            'nominal' => 1500000,
            'alasan' => 'Antrean penarikan siswa tinggi',
            'status' => 'pending',
        ]);

        // Admin Setujui (ACC) Pengajuan Kas
        $resApproveKas = $this->actingAs($admin)->post(route('admin.pengajuan-kas.approve', $pengajuan1->id));
        $resApproveKas->assertSessionHas('success');
        $this->assertEquals('approved', $pengajuan1->fresh()->status);
        $this->assertEquals($admin->id, $pengajuan1->fresh()->admin_id);

        // 2. Pengajuan kas kedua ditolak oleh admin
        $pengajuan2 = PengajuanKas::create([
            'user_id' => $teller->id,
            'tanggal' => date('Y-m-d'),
            'nominal' => 5000000,
            'alasan' => 'Permintaan modal lebih',
            'status' => 'pending',
        ]);
        $resRejectKas = $this->actingAs($admin)->post(route('admin.pengajuan-kas.reject', $pengajuan2->id), [
            'catatan_admin' => 'Saldo brankas pusat dialokasikan untuk operasional lain',
        ]);
        $resRejectKas->assertSessionHas('success');
        $this->assertEquals('rejected', $pengajuan2->fresh()->status);
        $this->assertNotNull($pengajuan2->fresh()->catatan_admin);

        // 3. Laporan Harian Tutup Kas
        $laporanHarian = LaporanHarian::create([
            'user_id' => $teller->id,
            'tanggal' => date('Y-m-d'),
            'saldo_awal' => 2000000,
            'total_setoran' => 500000,
            'total_penarikan' => 200000,
            'saldo_akhir_sistem' => 2300000,
            'uang_fisik' => 2300000,
            'selisih' => 0,
            'status_approval' => 'pending',
        ]);

        // Admin Approve & Vault Laporan Tutup Kas
        $resApproveLaporan = $this->actingAs($admin)->post(route('admin.laporan.approve', $laporanHarian->id));
        $resApproveLaporan->assertSessionHas('success');
        $this->assertEquals('approved', $laporanHarian->fresh()->status_approval);
    }

    /**
     * AUDIT 6: Loket Teller - Modal Awal, Setoran, Penarikan, dan Validasi PIN Salah
     */
    public function test_audit_06_transaksi_teller_setoran_dan_penarikan()
    {
        $teller = $this->createTellerUser();
        [$nasabah, $member, $saver, $rekening] = $this->createNasabahUser();

        // 1. Teller input modal awal kas laci
        $resModal = $this->actingAs($teller)->post(route('teller.modal-awal'), [
            'modal_awal' => 2000000,
        ]);
        $resModal->assertSessionHas('success');

        // 2. Setoran Tunai Rp 100.000
        $saldoAwal = $rekening->fresh()->saldo;
        $resSetoran = $this->actingAs($teller)->post(route('teller.setoran'), [
            'id_nasabah' => $saver->id,
            'nominal' => 100000,
            'keterangan' => 'Setoran tabungan audit',
        ]);
        $resSetoran->assertSessionHas('transaksi_sukses');
        $this->assertEquals($saldoAwal + 100000, $rekening->fresh()->saldo);

        // 3. Penarikan Tunai GAGAL jika PIN SALAH
        $resPenarikanSalahPin = $this->actingAs($teller)->post(route('teller.penarikan'), [
            'id_nasabah' => $saver->id,
            'nominal' => 50000,
            'pin' => '000000', // PIN salah (aslinya 123456)
        ]);
        $resPenarikanSalahPin->assertSessionHas('error');
        $resPenarikanSalahPin->assertSessionHas('error_pin_salah', true);
        // Saldo tidak boleh berkurang
        $this->assertEquals($saldoAwal + 100000, $rekening->fresh()->saldo);

        // 4. Penarikan Tunai GAGAL jika Saldo Sisa < Rp 10.000
        $saldoSekarang = $rekening->fresh()->saldo; // 600.000
        $resTarikHabis = $this->actingAs($teller)->post(route('teller.penarikan'), [
            'id_nasabah' => $saver->id,
            'nominal' => $saldoSekarang, // menyisakan 0
            'pin' => '123456',
        ]);
        $resTarikHabis->assertSessionHas('error');
        $this->assertEquals($saldoSekarang, $rekening->fresh()->saldo);

        // 5. Penarikan Tunai BERHASIL jika PIN Benar dan saldo cukup
        $resPenarikanSukses = $this->actingAs($teller)->post(route('teller.penarikan'), [
            'id_nasabah' => $saver->id,
            'nominal' => 50000,
            'pin' => '123456',
            'keterangan' => 'Penarikan uji coba audit',
        ]);
        $resPenarikanSukses->assertSessionHas('transaksi_sukses');
        $this->assertEquals($saldoSekarang - 50000, $rekening->fresh()->saldo);
    }

    /**
     * AUDIT 7: Loket Teller - Antrean, Cari Data & Aktivasi Rekening
     */
    public function test_audit_07_teller_antrean_cari_dan_aktivasi()
    {
        $teller = $this->createTellerUser();
        [$nasabah, $member, $saver] = $this->createNasabahUser();

        // 1. Teller ubah status layanan (buka / tutup)
        $resStatus = $this->actingAs($teller)->post(route('teller.status-layanan'), [
            'status' => 'buka',
        ]);
        $resStatus->assertJson(['success' => true]);

        // 2. API Cari Penabung
        $resCariSaver = $this->actingAs($teller)->get(route('teller.cari-nasabah', ['query' => $saver->account_number]));
        $resCariSaver->assertJson(['success' => true]);

        // 3. Nasabah ambil nomor antrean (pastikan antrean sebelumnya hari ini selesai)
        Antrean::where('tanggal', date('Y-m-d'))->update(['status' => 'selesai']);
        $resAmbilAntrean = $this->actingAs($nasabah)->post(route('nasabah.ambil-antrean'));
        $resAmbilAntrean->assertSessionHas('success');
        $antrean = Antrean::where('user_id', $nasabah->id)->whereDate('tanggal', date('Y-m-d'))->first();
        $this->assertNotNull($antrean);

        // 4. Teller panggil antrean
        $resPanggil = $this->actingAs($teller)->post(route('teller.antrean.panggil'));
        $resPanggil->assertSessionHas('success');
        $this->assertEquals('dilayani', $antrean->fresh()->status);

        // 5. Teller selesaikan antrean
        $resSelesai = $this->actingAs($teller)->post(route('teller.antrean.selesai', $antrean->id));
        $resSelesai->assertSessionHas('success');
        $this->assertEquals('selesai', $antrean->fresh()->status);
    }

    /**
     * AUDIT 8: Portal Siswa (Nasabah) - Dashboard, Target Tabungan, Pengaduan, Ganti Password
     */
    public function test_audit_08_portal_siswa_mandiri()
    {
        [$nasabah, $member, $saver, $rekening] = $this->createNasabahUser();
        $admin = $this->getOrCreateAdmin();

        // 1. Dashboard Nasabah
        $resDash = $this->actingAs($nasabah)->get(route('nasabah.dashboard'));
        $resDash->assertStatus(200);
        $resDash->assertSee($saver->account_number);

        // 2. Simpan & Hapus Target Tabungan
        $resTarget = $this->actingAs($nasabah)->post(route('nasabah.target-tabungan.store'), [
            'judul_target' => 'Beli Buku Pelajaran',
            'nominal_target' => 300000,
        ]);
        $resTarget->assertSessionHas('success');
        $target = TargetTabungan::where('user_id', $nasabah->id)->first();
        $this->assertNotNull($target);

        $resDelTarget = $this->actingAs($nasabah)->post(route('nasabah.target-tabungan.delete', $target->id));
        $resDelTarget->assertSessionHas('success');
        $this->assertNull(TargetTabungan::find($target->id));

        // 3. Kirim Pengaduan Siswa
        $resPengaduan = $this->actingAs($nasabah)->post(route('nasabah.pengaduan.store'), [
            'kategori' => 'Kendala Saldo & Mutasi',
            'judul' => 'Pertanyaan saldo tabungan',
            'isi_pesan' => 'Mohon cek saldo saya setelah menabung kemarin',
        ]);
        $resPengaduan->assertSessionHas('success');
        $pengaduan = Pengaduan::where('user_id', $nasabah->id)->first();
        $this->assertNotNull($pengaduan);

        // 4. Admin menanggapi pengaduan
        $resTanggapi = $this->actingAs($admin)->post(route('admin.pengaduan.tanggapi', $pengaduan->id), [
            'tanggapan_admin' => 'Halo, saldo Anda sudah diverifikasi dan sesuai.',
            'status' => 'selesai',
        ]);
        $resTanggapi->assertSessionHas('success');
        $this->assertEquals('selesai', $pengaduan->fresh()->status);
        $this->assertEquals('Halo, saldo Anda sudah diverifikasi dan sesuai.', $pengaduan->fresh()->tanggapan_admin);

        // 5. Ganti Kata Sandi Mandiri (Sandi lama salah)
        $resGantiPassFail = $this->actingAs($nasabah)->post(route('nasabah.ganti-password'), [
            'sandi_lama' => 'sandisalah123',
            'sandi_baru' => 'sandiBaru456',
            'sandi_baru_confirmation' => 'sandiBaru456',
        ]);
        $resGantiPassFail->assertSessionHas('error_password');

        // 6. Ganti Kata Sandi Mandiri (Sandi lama benar)
        $resGantiPassOk = $this->actingAs($nasabah)->post(route('nasabah.ganti-password'), [
            'sandi_lama' => 'password123',
            'sandi_baru' => 'sandiBaru456',
            'sandi_baru_confirmation' => 'sandiBaru456',
        ]);
        $resGantiPassOk->assertSessionHas('success_password');
        $this->assertTrue(Hash::check('sandiBaru456', $nasabah->fresh()->password));
    }

    /**
     * AUDIT 9: REST API Mobile Nasabah (Untuk Aplikasi Mobile APK)
     */
    public function test_audit_09_mobile_api_nasabah_endpoints()
    {
        [$nasabah, $member, $saver, $rekening] = $this->createNasabahUser();

        // 1. API Login
        $resApiLogin = $this->postJson('/api/nasabah/login', [
            'username' => $nasabah->username,
            'password' => 'password123',
        ]);
        $resApiLogin->assertStatus(200);
        $resApiLogin->assertJson(['success' => true]);

        // 2. API Dashboard
        $resApiDashboard = $this->getJson('/api/nasabah/dashboard/' . $nasabah->id);
        $resApiDashboard->assertStatus(200);
        $resApiDashboard->assertJsonStructure(['success', 'data']);

        // 3. API Mutasi
        $resApiMutasi = $this->getJson('/api/nasabah/mutasi/' . $nasabah->id);
        $resApiMutasi->assertStatus(200);
        $resApiMutasi->assertJsonStructure(['success', 'data']);

        // 4. API Ambil Antrean
        $resApiAntrean = $this->postJson('/api/nasabah/ambil-antrean', [
            'user_id' => $nasabah->id,
        ]);
        $resApiAntrean->assertStatus(200);
        $resApiAntrean->assertJson(['success' => true]);

        // 5. API Target Tabungan
        $resApiTarget = $this->postJson('/api/nasabah/target-tabungan', [
            'user_id' => $nasabah->id,
            'judul_target' => 'Beli Gadget',
            'nominal_target' => 1200000,
        ]);
        $resApiTarget->assertStatus(200);
        $resApiTarget->assertJson(['success' => true]);

        // 6. API Kirim Pengaduan
        $resApiPengaduan = $this->postJson('/api/nasabah/pengaduan', [
            'user_id' => $nasabah->id,
            'kategori' => 'Lainnya',
            'judul' => 'Uji Coba API',
            'isi_pesan' => 'Pesan dari mobile app',
        ]);
        $resApiPengaduan->assertStatus(200);
        $resApiPengaduan->assertJson(['success' => true]);
    }
}
