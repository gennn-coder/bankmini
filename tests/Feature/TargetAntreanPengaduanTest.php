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
use Illuminate\Support\Facades\Hash;

class TargetAntreanPengaduanTest extends TestCase
{
    private function createNasabahUser($nis = null, $name = 'Siswa Test')
    {
        $nis = $nis ?: 'NIS_' . rand(10000, 99999);
        $user = User::create([
            'username' => $nis,
            'nama_petugas' => $name,
            'role' => 'nasabah',
            'password' => Hash::make('password123'),
            'status' => 'aktif',
        ]);
        $member = Member::create([
            'user_id' => $user->id,
            'student_name' => $name,
            'nis' => $nis,
            'email' => $nis . '@example.com',
            'class_grade' => 'X RPL 1',
            'graduation_year' => '2027',
            'status' => 'rekening aktif',
        ]);
        $saver = Saver::create([
            'user_id' => $user->id,
            'account_number' => '100' . rand(10000, 99999),
            'nis' => $nis,
            'student_name' => $name,
            'class_grade' => 'X RPL 1',
            'email' => $nis . '@example.com',
            'security_pin' => Hash::make('123456'),
        ]);
        $rekening = Rekening::create([
            'saver_id' => $saver->id,
            'no_rekening' => $saver->account_number,
            'saldo' => 500000,
        ]);

        return [$user, $member, $saver, $rekening];
    }

    public function test_nasabah_bisa_simpan_dan_hapus_target_tabungan()
    {
        [$user, $member, $saver] = $this->createNasabahUser();

        // Simpan target
        $res = $this->actingAs($user)->post(route('nasabah.target-tabungan.store'), [
            'judul_target' => 'Beli Sepatu Sekolah',
            'nominal_target' => 750000,
            'keterangan' => 'Untuk semester depan',
        ]);
        $res->assertSessionHas('success');
        $this->assertDatabaseHas('target_tabungan', [
            'user_id' => $user->id,
            'judul_target' => 'Beli Sepatu Sekolah',
            'nominal_target' => 750000,
        ]);

        // Hapus target
        $target = TargetTabungan::where('user_id', $user->id)->first();
        $resDelete = $this->actingAs($user)->post(route('nasabah.target-tabungan.delete', $target->id));
        $resDelete->assertSessionHas('success');
        $this->assertDatabaseMissing('target_tabungan', [
            'id' => $target->id,
        ]);
    }

    public function test_alur_antrean_nasabah_dan_teller()
    {
        [$siswa, $member] = $this->createNasabahUser();
        $teller = User::create([
            'username' => 'teller_' . rand(1000, 9999),
            'nama_petugas' => 'Petugas Teller',
            'role' => 'teller',
            'password' => Hash::make('password123'),
            'status' => 'aktif',
        ]);

        // Siswa ambil antrean
        $resAmbil = $this->actingAs($siswa)->post(route('nasabah.ambil-antrean'));
        $resAmbil->assertSessionHas('success');
        $this->assertDatabaseHas('antrean', [
            'user_id' => $siswa->id,
            'status' => 'menunggu',
            'tanggal' => date('Y-m-d'),
        ]);

        $antrean = Antrean::where('user_id', $siswa->id)->first();
        $this->assertNotNull($antrean);
        $this->assertStringStartsWith('A-', $antrean->nomor_antrean);

        // Teller panggil antrean berikutnya
        $resPanggil = $this->actingAs($teller)->post(route('teller.antrean.panggil'));
        $resPanggil->assertSessionHas('success');
        $this->assertDatabaseHas('antrean', [
            'id' => $antrean->id,
            'status' => 'dilayani',
        ]);

        // Teller selesaikan antrean
        $resSelesai = $this->actingAs($teller)->post(route('teller.antrean.selesai', $antrean->id));
        $resSelesai->assertSessionHas('success');
        $this->assertDatabaseHas('antrean', [
            'id' => $antrean->id,
            'status' => 'selesai',
        ]);
    }

    public function test_alur_pengaduan_nasabah_dan_tanggapan_admin()
    {
        [$siswa, $member] = $this->createNasabahUser();
        $admin = User::create([
            'username' => 'admin_' . rand(1000, 9999),
            'nama_petugas' => 'Administrator',
            'role' => 'administrator',
            'password' => Hash::make('password123'),
            'status' => 'aktif',
        ]);

        // Siswa kirim pengaduan
        $resKirim = $this->actingAs($siswa)->post(route('nasabah.pengaduan.store'), [
            'kategori' => 'Layanan Loket',
            'judul' => 'Buku Tabungan Hilang',
            'isi_pesan' => 'Mohon bantuan bagaimana cetak ulang buku tabungan saya.',
        ]);
        $resKirim->assertSessionHas('success');
        $this->assertDatabaseHas('pengaduan', [
            'user_id' => $siswa->id,
            'judul' => 'Buku Tabungan Hilang',
            'status' => 'menunggu',
        ]);

        $pengaduan = Pengaduan::where('user_id', $siswa->id)->first();

        // Admin beri tanggapan
        $resTanggapi = $this->actingAs($admin)->post(route('admin.pengaduan.tanggapi', $pengaduan->id), [
            'tanggapan_admin' => 'Silakan datang ke loket Teller membawa kartu pelajar untuk cetak buku baru.',
            'status' => 'selesai',
        ]);
        $resTanggapi->assertSessionHas('success');
        $this->assertDatabaseHas('pengaduan', [
            'id' => $pengaduan->id,
            'status' => 'selesai',
            'tanggapan_admin' => 'Silakan datang ke loket Teller membawa kartu pelajar untuk cetak buku baru.',
        ]);
    }

    public function test_nasabah_dashboard_view_renders_3_tabs()
    {
        [$user, $member, $saver] = $this->createNasabahUser();

        $res = $this->actingAs($user)->get(route('nasabah.dashboard'));
        $res->assertStatus(200);
        $res->assertSee('tab-dashboard');
        $res->assertSee('tab-mutasi');
        $res->assertSee('tab-profil');
        $res->assertSee('btn-tab-mutasi');
        $res->assertSee('Target Tabungan');
        $res->assertSee('Sistem Antrean Loket Bank Mini');
    }
}
