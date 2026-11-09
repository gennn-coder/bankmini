<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class ProfilDanLupaSandiPetugasTest extends TestCase
{
    private function createAdmin($unique = null)
    {
        $id = $unique ?: uniqid();
        return User::create([
            'username' => 'adm_' . $id,
            'password' => Hash::make('password123'),
            'nama_petugas' => 'Admin ' . $id,
            'email' => 'admin_' . $id . '@bankmini.sch.id',
            'role' => 'administrator',
            'status' => 'aktif',
        ]);
    }

    private function createTeller($unique = null)
    {
        $id = $unique ?: uniqid();
        return User::create([
            'username' => 'tlr_' . $id,
            'password' => Hash::make('password123'),
            'nama_petugas' => 'Teller ' . $id,
            'email' => 'teller_' . $id . '@bankmini.sch.id',
            'role' => 'teller',
            'status' => 'aktif',
        ]);
    }

    public function test_admin_dan_teller_dapat_melihat_tab_profil_pada_dashboard()
    {
        $admin = $this->createAdmin();
        $teller = $this->createTeller();

        // Cek Admin Dashboard render tombol profil pada dropdown nama & konten tab profil
        $responseAdmin = $this->actingAs($admin)->get('/admin/dashboard');
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('btn-user-profile-trigger');
        $responseAdmin->assertSee('Masuk ke Profil / Edit Profil');
        $responseAdmin->assertSee('tab-profil');
        $responseAdmin->assertSee('name="username"', false);
        $responseAdmin->assertSee($admin->email);

        // Cek Teller Dashboard render tombol profil pada dropdown nama & konten tab profil
        $responseTeller = $this->actingAs($teller)->get('/teller/dashboard');
        $responseTeller->assertStatus(200);
        $responseTeller->assertSee('btn-user-profile-trigger');
        $responseTeller->assertSee('Masuk ke Profil / Edit Profil');
        $responseTeller->assertSee('tab-profil');
        $responseTeller->assertSee('name="username"', false);
        $responseTeller->assertSee($teller->email);
    }

    public function test_petugas_dapat_memperbarui_nama_username_dan_email_profil_mandiri()
    {
        $admin = $this->createAdmin();
        $newUsername = 'genta_new_' . uniqid();

        $response = $this->actingAs($admin)->post('/profil/update', [
            'nama_petugas' => 'Genta Nilam Prana Updated',
            'username' => $newUsername,
            'email' => 'genta_updated_' . uniqid() . '@bankmini.sch.id',
        ]);

        $response->assertSessionHas('success_profile');
        $admin->refresh();
        $this->assertEquals('Genta Nilam Prana Updated', $admin->nama_petugas);
        $this->assertEquals($newUsername, $admin->username);
        $this->assertStringContainsString('genta_updated_', $admin->email);
    }

    public function test_validasi_update_profil_menolak_username_duplikat()
    {
        $admin1 = $this->createAdmin();
        $admin2 = $this->createAdmin();

        $response = $this->actingAs($admin2)->post('/profil/update', [
            'nama_petugas' => 'Admin Dua',
            'username' => $admin1->username, // Duplikat
            'email' => 'admindua_new@bankmini.sch.id',
        ]);

        $response->assertSessionHasErrors(['username']);
    }

    public function test_petugas_dapat_mengubah_kata_sandi_akun_secara_mandiri()
    {
        $teller = $this->createTeller();

        // Gagal jika sandi lama salah
        $resWrong = $this->actingAs($teller)->post('/profil/ganti-password', [
            'sandi_lama' => 'salah123',
            'sandi_baru' => 'newpassword123',
            'sandi_baru_confirmation' => 'newpassword123',
        ]);
        $resWrong->assertSessionHas('error_password');

        // Berhasil dengan sandi lama yang benar
        $resSuccess = $this->actingAs($teller)->post('/profil/ganti-password', [
            'sandi_lama' => 'password123',
            'sandi_baru' => 'newpassword123',
            'sandi_baru_confirmation' => 'newpassword123',
        ]);
        $resSuccess->assertSessionHas('success_password');

        $teller->refresh();
        $this->assertTrue(Hash::check('newpassword123', $teller->password));
    }

    public function test_admin_dan_teller_dapat_lupa_sandi_di_awal_login_via_otp()
    {
        $admin = $this->createAdmin();

        // 1. Admin request OTP menggunakan username
        $otpRes = $this->postJson('/lupa-sandi/kirim-otp', [
            'identifier' => $admin->username,
            'email' => $admin->email,
        ]);
        $otpRes->assertStatus(200);
        $otpRes->assertJson(['success' => true]);

        // Ambil OTP dari cache
        $cachedData = Cache::get('otp_reset_' . $admin->id);
        $this->assertNotNull($cachedData);
        $otpCode = $cachedData['otp'];

        // 2. Verifikasi OTP
        $verifRes = $this->postJson('/lupa-sandi/verifikasi-otp', [
            'identifier' => $admin->username,
            'email' => $admin->email,
            'otp' => $otpCode,
        ]);
        $verifRes->assertStatus(200);
        $verifRes->assertJson(['success' => true]);
        $resetToken = $verifRes->json('reset_token');
        $this->assertNotEmpty($resetToken);

        // 3. Simpan Sandi Baru
        $simpanRes = $this->postJson('/lupa-sandi/simpan-sandi', [
            'reset_token' => $resetToken,
            'password' => 'adminbaru2026',
            'password_confirmation' => 'adminbaru2026',
        ]);
        $simpanRes->assertStatus(200);
        $simpanRes->assertJson(['success' => true]);

        // 4. Verifikasi bisa login dengan sandi baru
        $admin->refresh();
        $this->assertTrue(Hash::check('adminbaru2026', $admin->password));

        $loginRes = $this->post('/login', [
            'username' => $admin->username,
            'password' => 'adminbaru2026',
        ]);
        $loginRes->assertRedirect('/admin/dashboard');
    }

    public function test_teller_dapat_lupa_sandi_menggunakan_email_sebagai_identifier()
    {
        $teller = $this->createTeller();

        // Request OTP menggunakan email langsung di kolom identifier
        $otpRes = $this->postJson('/lupa-sandi/kirim-otp', [
            'identifier' => $teller->email,
            'email' => $teller->email,
        ]);
        $otpRes->assertStatus(200);
        $otpRes->assertJson(['success' => true]);

        $cachedData = Cache::get('otp_reset_' . $teller->id);
        $otpCode = $cachedData['otp'];

        $verifRes = $this->postJson('/lupa-sandi/verifikasi-otp', [
            'identifier' => $teller->email,
            'email' => $teller->email,
            'otp' => $otpCode,
        ]);
        $verifRes->assertStatus(200);
        $resetToken = $verifRes->json('reset_token');

        $simpanRes = $this->postJson('/lupa-sandi/simpan-sandi', [
            'reset_token' => $resetToken,
            'password' => 'tellerbaru2026',
            'password_confirmation' => 'tellerbaru2026',
        ]);
        $simpanRes->assertStatus(200);

        $teller->refresh();
        $this->assertTrue(Hash::check('tellerbaru2026', $teller->password));
    }
}
