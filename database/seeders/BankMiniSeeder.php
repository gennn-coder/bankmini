<?php

namespace Database\Seeders;

use App\Models\AkunJurnal;
use App\Models\LaporanHarian;
use App\Models\Member;
use App\Models\Saver;
use App\Models\Rekening;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BankMiniSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Jurnal Standar
        AkunJurnal::firstOrCreate(['kode_akun' => '101'], [
            'nama_akun' => 'Kas Bank Mini',
            'jenis_akun' => 'aset',
        ]);

        AkunJurnal::firstOrCreate(['kode_akun' => '201'], [
            'nama_akun' => 'Simpanan Tabungan Nasabah',
            'jenis_akun' => 'liabilitas',
        ]);

        // 2. Users (Role Utama) - PASSWORD SEMUA USER DIBUAT '123456'
        $admin = User::firstOrCreate(['username' => 'admin'], [
            'password' => Hash::make('123456'),
            'nama_petugas' => 'Genta Nilam Prana (Admin)',
            'role' => 'administrator',
            'status' => 'aktif',
        ]);

        $teller = User::firstOrCreate(['username' => 'teller1'], [
            'password' => Hash::make('123456'),
            'nama_petugas' => 'Siti Rahmawati (Teller 1)',
            'role' => 'teller',
            'status' => 'aktif',
        ]);

        // 3. User Siswa 1 (Sudah aktif menabung)
        $userSiswa1 = User::firstOrCreate(['username' => 'siswa1'], [
            'password' => Hash::make('123456'),
            'nama_petugas' => 'Ahmad Rizky (Penabung)',
            'role' => 'nasabah',
            'status' => 'aktif',
        ]);

        // Member 1 (Status: rekening aktif)
        $member1 = Member::firstOrCreate(['user_id' => $userSiswa1->id], [
            'student_name' => 'Ahmad Rizky',
            'nis' => '202611001',
            'class_grade' => 'XI RPL 2',
            'graduation_year' => '2027',
            'status' => 'rekening aktif',
        ]);

        // Saver 1
        $saver1 = Saver::firstOrCreate(['account_number' => '10001001'], [
            'user_id' => $userSiswa1->id,
            'nis' => '202611001',
            'student_name' => 'Ahmad Rizky',
            'class_grade' => 'XI RPL 2',
            'email' => 'ahmad.rizky@gmail.com',
            'security_pin' => Hash::make('123456'),
        ]);

        // Rekening Saldo awal Rp 50.000
        Rekening::firstOrCreate(['account_number' => '10001001'], [
            'saver_id' => $saver1->id,
            'id_nasabah' => $saver1->id,
            'saldo' => 50000,
            'status' => 'aktif',
        ]);

        // 4. Siswa 2 & 3 (Anggota baru yang belum menabung / status rekening tidak aktif)
        $userSiswa2 = User::firstOrCreate(['username' => '202611002'], [
            'password' => Hash::make('123456'),
            'nama_petugas' => 'Bintang Pratama',
            'role' => 'nasabah',
            'status' => 'aktif',
        ]);

        Member::firstOrCreate(['user_id' => $userSiswa2->id], [
            'student_name' => 'Bintang Pratama',
            'nis' => '202611002',
            'class_grade' => 'X AKL 1',
            'graduation_year' => '2028',
            'status' => 'rekening tidak aktif',
        ]);

        $userSiswa3 = User::firstOrCreate(['username' => '202611003'], [
            'password' => Hash::make('123456'),
            'nama_petugas' => 'Citra Dewi Lestari',
            'role' => 'nasabah',
            'status' => 'aktif',
        ]);

        Member::firstOrCreate(['user_id' => $userSiswa3->id], [
            'student_name' => 'Citra Dewi Lestari',
            'nis' => '202611003',
            'class_grade' => 'XI TKJ 1',
            'graduation_year' => '2027',
            'status' => 'rekening tidak aktif',
        ]);

        // 5. Laporan Harian Contoh yang di-approve oleh Admin
        LaporanHarian::firstOrCreate(['tanggal' => '2026-07-29', 'user_id' => $teller->id], [
            'saldo_awal' => 0,
            'total_setoran' => 60000,
            'total_penarikan' => 10000,
            'saldo_akhir_sistem' => 50000,
            'uang_fisik' => 50000,
            'selisih' => 0,
            'status_approval' => 'approved',
            'supervisor_id' => $admin->id,
        ]);
    }
}
