<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Member;
use App\Models\Saver;
use App\Models\Rekening;
use App\Models\LaporanHarian;
use App\Models\PerjanjianPenarikan;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PenarikanH1DanLimitRegulerTest extends TestCase
{
    private function createTellerUser()
    {
        $teller = User::create([
            'username' => 'teller_' . uniqid() . rand(100, 999),
            'nama_petugas' => 'Teller Test',
            'role' => 'teller',
            'password' => Hash::make('password123'),
            'status' => 'aktif',
        ]);

        // Beri modal awal hari ini agar laci kas tidak 0
        LaporanHarian::create([
            'user_id' => $teller->id,
            'tanggal' => date('Y-m-d'),
            'saldo_awal' => 50000000, // 50 juta
            'status' => 'pending',
        ]);

        return $teller;
    }

    private function createNasabahUser($saldo = 2000000, $pin = '123456')
    {
        $nis = 'NIS_' . uniqid() . rand(100, 999);
        $user = User::create([
            'username' => $nis,
            'nama_petugas' => 'Nasabah Test',
            'role' => 'nasabah',
            'password' => Hash::make('password123'),
            'status' => 'aktif',
        ]);

        $member = Member::create([
            'user_id' => $user->id,
            'student_name' => 'Nasabah Test',
            'nis' => $nis,
            'email' => $nis . '@example.com',
            'class_grade' => 'XII RPL',
            'graduation_year' => '2026',
            'status' => 'rekening aktif',
        ]);

        $saver = Saver::create([
            'user_id' => $user->id,
            'account_number' => '100' . rand(100000, 999999) . rand(10, 99),
            'nis' => $nis,
            'student_name' => 'Nasabah Test',
            'class_grade' => 'XII RPL',
            'email' => $nis . '@example.com',
            'security_pin' => Hash::make($pin),
        ]);

        $rekening = Rekening::create([
            'saver_id' => $saver->id,
            'no_rekening' => $saver->account_number,
            'saldo' => $saldo,
            'status' => 'aktif',
        ]);

        return [$user, $member, $saver, $rekening];
    }

    /**
     * KASUS A: Penarikan reguler <= 1 juta dengan sisa saldo >= 10.000
     * Saldo: 500.000, Tarik: 400.000 -> Sukses tanpa izin admin & tanpa perjanjian.
     */
    public function test_kasus_a_penarikan_reguler_di_bawah_1_juta_sukses_tanpa_izin_dan_perjanjian()
    {
        $teller = $this->createTellerUser();
        [$user, $member, $saver, $rekening] = $this->createNasabahUser(500000);

        $res = $this->actingAs($teller)->post(route('teller.penarikan'), [
            'id_nasabah' => $saver->id,
            'nominal' => 400000,
            'pin' => '123456',
            'keterangan' => 'Penarikan Kasus A',
        ]);

        $res->assertSessionHas('success');
        $rekening->refresh();
        $this->assertEquals(100000, $rekening->saldo);
    }

    /**
     * KASUS B: Nasabah saldo pas 1.000.000, menarik 990.000
     * Sisa saldo pas Rp 10.000 -> Sukses.
     */
    public function test_kasus_b_nasabah_saldo_pas_1_juta_menarik_990_ribu_sukses_sisa_10_ribu()
    {
        $teller = $this->createTellerUser();
        [$user, $member, $saver, $rekening] = $this->createNasabahUser(1000000);

        $res = $this->actingAs($teller)->post(route('teller.penarikan'), [
            'id_nasabah' => $saver->id,
            'nominal' => 990000,
            'pin' => '123456',
            'keterangan' => 'Penarikan Kasus B',
        ]);

        $res->assertSessionHas('success');
        $rekening->refresh();
        $this->assertEquals(10000, $rekening->saldo);
    }

    /**
     * KASUS C: Nasabah saldo pas 1.000.000, menarik 1.000.000
     * Karena saldo sisa menjadi 0 (< 10.000), sistem wajib menolak.
     */
    public function test_kasus_c_nasabah_saldo_pas_1_juta_menarik_1_juta_ditolak_karena_sisa_kurang_dari_10_ribu()
    {
        $teller = $this->createTellerUser();
        [$user, $member, $saver, $rekening] = $this->createNasabahUser(1000000);

        $res = $this->actingAs($teller)->post(route('teller.penarikan'), [
            'id_nasabah' => $saver->id,
            'nominal' => 1000000,
            'pin' => '123456',
            'keterangan' => 'Penarikan Kasus C',
        ]);

        $res->assertSessionHas('error');
        $this->assertStringContainsString('10.000', session('error'));
        $rekening->refresh();
        $this->assertEquals(1000000, $rekening->saldo); // saldo tidak berkurang
    }

    /**
     * KASUS D: Penarikan di atas 1 juta (> 1.000.000) tanpa perjanjian H-1
     * Saldo: 2.500.000, ingin tarik 1.500.000 tiba-tiba tanpa perjanjian -> Ditolak.
     */
    public function test_kasus_d_penarikan_di_atas_1_juta_tanpa_perjanjian_ditolak()
    {
        $teller = $this->createTellerUser();
        [$user, $member, $saver, $rekening] = $this->createNasabahUser(2500000);

        $res = $this->actingAs($teller)->post(route('teller.penarikan'), [
            'id_nasabah' => $saver->id,
            'nominal' => 1500000,
            'pin' => '123456',
            'keterangan' => 'Penarikan Kasus D Tanpa Janji',
        ]);

        $res->assertSessionHas('error');
        $this->assertStringContainsString('perjanjian', session('error'));
        $this->assertStringContainsString('H-1', session('error'));
        $rekening->refresh();
        $this->assertEquals(2500000, $rekening->saldo);
    }

    /**
     * KASUS E: Penarikan di atas 1 juta dengan perjanjian H-1 yang disetujui
     * Saldo: 2.500.000, Perjanjian: 1.500.000 -> Sukses, saldo sisa 1.000.000, status perjanjian 'selesai'.
     */
    public function test_kasus_e_penarikan_di_atas_1_juta_dengan_perjanjian_h1_disetujui_berhasil_dan_status_selesai()
    {
        $teller = $this->createTellerUser();
        [$user, $member, $saver, $rekening] = $this->createNasabahUser(2500000);

        // Buat perjanjian yang diajukan kemarin (H-1) untuk hari ini
        $pj = PerjanjianPenarikan::create([
            'saver_id' => $saver->id,
            'nominal' => 1500000,
            'tanggal_rencana' => date('Y-m-d'),
            'alasan' => 'Beli laptop untuk ujian',
            'status' => 'disetujui',
            'teller_id' => $teller->id,
            'created_at' => Carbon::yesterday(),
        ]);

        $res = $this->actingAs($teller)->post(route('teller.penarikan'), [
            'id_nasabah' => $saver->id,
            'nominal' => 1500000,
            'pin' => '123456',
            'id_perjanjian' => $pj->id,
            'keterangan' => 'Penarikan Kasus E dengan Perjanjian',
        ]);

        $res->assertSessionHas('success');
        $rekening->refresh();
        $this->assertEquals(1000000, $rekening->saldo);

        $pj->refresh();
        $this->assertEquals('selesai', $pj->status);
    }

    /**
     * KASUS F: Penarikan di atas 1 juta dengan konfirmasi manual teller (perjanjian lisan/WA H-1)
     */
    public function test_kasus_f_penarikan_di_atas_1_juta_dengan_konfirmasi_manual_teller_berhasil()
    {
        $teller = $this->createTellerUser();
        [$user, $member, $saver, $rekening] = $this->createNasabahUser(2500000);

        $res = $this->actingAs($teller)->post(route('teller.penarikan'), [
            'id_nasabah' => $saver->id,
            'nominal' => 1500000,
            'pin' => '123456',
            'konfirmasi_perjanjian_h1' => 1,
            'keterangan' => 'Penarikan Kasus F Perjanjian Manual',
        ]);

        $res->assertSessionHas('success');
        $rekening->refresh();
        $this->assertEquals(1000000, $rekening->saldo);
    }

    /**
     * KASUS G: Batas reguler kumulatif harian Rp 1.000.000
     * Nasabah tarik 600.000 sukses -> lalu tarik 500.000 lagi di hari yang sama -> Ditolak (total 1.100.000 > 1.000.000).
     */
    public function test_kasus_g_limit_harian_reguler_kumulatif_1_juta()
    {
        $teller = $this->createTellerUser();
        [$user, $member, $saver, $rekening] = $this->createNasabahUser(3000000);

        // Tarik 600.000 pertama
        $res1 = $this->actingAs($teller)->post(route('teller.penarikan'), [
            'id_nasabah' => $saver->id,
            'nominal' => 600000,
            'pin' => '123456',
            'keterangan' => 'Tarik Reguler 1',
        ]);
        $res1->assertSessionHas('success');

        // Tarik 500.000 kedua di hari yang sama tanpa perjanjian
        $res2 = $this->actingAs($teller)->post(route('teller.penarikan'), [
            'id_nasabah' => $saver->id,
            'nominal' => 500000,
            'pin' => '123456',
            'keterangan' => 'Tarik Reguler 2',
        ]);

        $res2->assertSessionHas('error');
        $this->assertStringContainsString('1.000.000', session('error'));
        $this->assertStringContainsString('perjanjian', session('error'));

        $rekening->refresh();
        $this->assertEquals(2400000, $rekening->saldo); // hanya 600rb yang terpotong
    }

    /**
     * KASUS H: Nasabah mengajukan permohonan perjanjian penarikan H-1 via web portal nasabah
     */
    public function test_nasabah_bisa_mengajukan_perjanjian_penarikan_h1_melalui_web()
    {
        [$user, $member, $saver, $rekening] = $this->createNasabahUser(3000000);

        // Sukses: nominal > 1 jt, tanggal besok (H-1)
        $besok = date('Y-m-d', strtotime('+1 day'));
        $res = $this->actingAs($user)->post(route('nasabah.perjanjian-penarikan.store'), [
            'nominal' => 2000000,
            'tanggal_rencana' => $besok,
            'alasan' => 'Untuk biaya praktikum dan sertifikasi',
        ]);

        $res->assertSessionHas('success');
        $this->assertDatabaseHas('perjanjian_penarikan', [
            'saver_id' => $saver->id,
            'nominal' => 2000000,
            'tanggal_rencana' => $besok,
            'status' => 'menunggu',
        ]);

        // Gagal: nominal <= 1 juta (karena <= 1jt reguler tanpa perjanjian)
        $resGagalNominal = $this->actingAs($user)->post(route('nasabah.perjanjian-penarikan.store'), [
            'nominal' => 1000000,
            'tanggal_rencana' => $besok,
            'alasan' => 'Coba 1 juta',
        ]);
        $resGagalNominal->assertSessionHasErrors('nominal');

        // Gagal: tanggal hari ini (bukan H-1)
        $resGagalTanggal = $this->actingAs($user)->post(route('nasabah.perjanjian-penarikan.store'), [
            'nominal' => 1500000,
            'tanggal_rencana' => date('Y-m-d'),
            'alasan' => 'Minta hari ini',
        ]);
        $resGagalTanggal->assertSessionHasErrors('tanggal_rencana');

        // Gagal: nominal melebihi sisa saldo - 10.000
        $resGagalSaldo = $this->actingAs($user)->post(route('nasabah.perjanjian-penarikan.store'), [
            'nominal' => 3000000, // saldo pas 3 juta, jika tarik 3 juta sisa 0
            'tanggal_rencana' => $besok,
            'alasan' => 'Tarik habis',
        ]);
        $resGagalSaldo->assertSessionHas('error');
    }

    /**
     * KASUS I: Teller menyetujui (approve) dan menolak (reject) perjanjian penarikan
     */
    public function test_teller_bisa_approve_dan_reject_perjanjian_penarikan()
    {
        $teller = $this->createTellerUser();
        [$user, $member, $saver, $rekening] = $this->createNasabahUser(3000000);

        $pj1 = PerjanjianPenarikan::create([
            'saver_id' => $saver->id,
            'nominal' => 1500000,
            'tanggal_rencana' => date('Y-m-d', strtotime('+1 day')),
            'alasan' => 'Beli laptop',
            'status' => 'menunggu',
        ]);

        // Teller Approve pj1
        $resApprove = $this->actingAs($teller)->post(route('teller.perjanjian.approve', $pj1->id));
        $resApprove->assertSessionHas('success');
        $pj1->refresh();
        $this->assertEquals('disetujui', $pj1->status);
        $this->assertEquals($teller->id, $pj1->teller_id);

        $pj2 = PerjanjianPenarikan::create([
            'saver_id' => $saver->id,
            'nominal' => 2000000,
            'tanggal_rencana' => date('Y-m-d', strtotime('+1 day')),
            'alasan' => 'Keperluan mendadak',
            'status' => 'menunggu',
        ]);

        // Teller Reject pj2
        $resReject = $this->actingAs($teller)->post(route('teller.perjanjian.reject', $pj2->id), [
            'catatan_teller' => 'Kas laci pada tanggal tersebut diprioritaskan untuk operasional lain',
        ]);
        $resReject->assertSessionHas('success');
        $pj2->refresh();
        $this->assertEquals('ditolak', $pj2->status);
        $this->assertEquals('Kas laci pada tanggal tersebut diprioritaskan untuk operasional lain', $pj2->catatan_teller);
    }

    /**
     * KASUS J: Endpoint API Mobile Nasabah untuk Perjanjian Penarikan
     */
    public function test_api_mobile_perjanjian_penarikan()
    {
        [$user, $member, $saver, $rekening] = $this->createNasabahUser(4000000);

        // POST /api/nasabah/perjanjian
        $besok = date('Y-m-d', strtotime('+1 day'));
        $resPost = $this->postJson('/api/nasabah/perjanjian', [
            'user_id' => $user->id,
            'nominal' => 2500000,
            'tanggal_rencana' => $besok,
            'alasan' => 'Via Mobile API',
        ]);

        $resPost->assertStatus(200);
        $resPost->assertJson([
            'success' => true,
        ]);

        // GET /api/nasabah/perjanjian/{id}
        $resGet = $this->getJson('/api/nasabah/perjanjian/' . $user->id);
        $resGet->assertStatus(200);
        $resGet->assertJsonStructure([
            'success',
            'data',
            'aturan' => ['max_reguler_harian', 'min_sisa_saldo', 'min_nominal_perjanjian']
        ]);

        // GET /api/nasabah/dashboard/{id}
        $resDash = $this->getJson('/api/nasabah/dashboard/' . $user->id);
        $resDash->assertStatus(200);
        $resDash->assertJsonStructure([
            'success',
            'data' => [
                'aturan_penarikan',
                'perjanjian_list',
            ]
        ]);
    }
}
