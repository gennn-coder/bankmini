<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NasabahApiController;

/*
|--------------------------------------------------------------------------
| API Routes - Mobile E-Teller Bank Mini (Aplikasi Flutter Nasabah)
|--------------------------------------------------------------------------
| Seluruh endpoint RESTful API untuk melayani aplikasi mobile nasabah siswa:
| Otentikasi, Dashboard Realtime, Mutasi Rekening, Antrean Online,
| Celengan Impian (Target Tabungan), Perjanjian H-1, dan Pengaduan.
*/

Route::prefix('nasabah')->group(function () {
    // Otentikasi Login & Profil Akun
    Route::post('/login', [NasabahApiController::class, 'login']);
    Route::get('/dashboard/{id}', [NasabahApiController::class, 'dashboard']);
    Route::get('/mutasi/{id}', [NasabahApiController::class, 'mutasi']);

    // Layanan Antrean Loket Digital
    Route::post('/ambil-antrean', [NasabahApiController::class, 'ambilAntrean']);

    // Celengan Impian (Target Tabungan Siswa)
    Route::post('/target-tabungan', [NasabahApiController::class, 'simpanTargetTabungan']);
    Route::post('/target-tabungan/delete', [NasabahApiController::class, 'hapusTargetTabungan']);

    // Rekapitulasi Keuangan Bulanan Nasabah
    Route::get('/laporan-bulanan/{id}', [NasabahApiController::class, 'laporanBulanan']);

    // Perjanjian Penarikan H-1 (> Rp 1.000.000)
    Route::get('/perjanjian/{id}', [NasabahApiController::class, 'listPerjanjian']);
    Route::post('/perjanjian', [NasabahApiController::class, 'simpanPerjanjian']);

    // Aspirasi & Pengaduan Nasabah ke Administrator
    Route::post('/pengaduan', [NasabahApiController::class, 'kirimPengaduan']);

    // Keamanan Akun: Pembaruan Kata Sandi
    Route::post('/ganti-password', [NasabahApiController::class, 'gantiPassword']);

    // Pemulihan Kredensial via Kode Keamanan OTP Email
    Route::post('/lupa-sandi', [NasabahApiController::class, 'lupaSandi']);
    Route::post('/lupa-sandi/kirim-otp', [\App\Http\Controllers\AuthController::class, 'kirimOtp']);
    Route::post('/lupa-sandi/verifikasi-otp', [\App\Http\Controllers\AuthController::class, 'verifikasiOtp']);
    Route::post('/lupa-sandi/simpan-sandi', [\App\Http\Controllers\AuthController::class, 'simpanSandiBaru']);
    Route::post('/lupa-pin', [NasabahApiController::class, 'lupaPin']);
});
