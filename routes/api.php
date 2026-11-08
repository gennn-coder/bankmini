<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NasabahApiController;

/*
|--------------------------------------------------------------------------
| API Routes for Mobile E-Teller Bank Mini
|--------------------------------------------------------------------------
*/

Route::prefix('nasabah')->group(function () {
    Route::post('/login', [NasabahApiController::class, 'login']);
    Route::get('/dashboard/{id}', [NasabahApiController::class, 'dashboard']);
    Route::get('/mutasi/{id}', [NasabahApiController::class, 'mutasi']);

    // Antrean Mobile
    Route::post('/ambil-antrean', [NasabahApiController::class, 'ambilAntrean']);

    // Target Tabungan Mobile
    Route::post('/target-tabungan', [NasabahApiController::class, 'simpanTargetTabungan']);
    Route::post('/target-tabungan/delete', [NasabahApiController::class, 'hapusTargetTabungan']);

    // Laporan Bulanan Mobile
    Route::get('/laporan-bulanan/{id}', [NasabahApiController::class, 'laporanBulanan']);

    // Perjanjian Penarikan H-1 Mobile
    Route::get('/perjanjian/{id}', [NasabahApiController::class, 'listPerjanjian']);
    Route::post('/perjanjian', [NasabahApiController::class, 'simpanPerjanjian']);

    // Pengaduan Mobile
    Route::post('/pengaduan', [NasabahApiController::class, 'kirimPengaduan']);

    // Ganti Password Mobile
    Route::post('/ganti-password', [NasabahApiController::class, 'gantiPassword']);

    // Pemulihan Sandi & PIN
    Route::post('/lupa-sandi', [NasabahApiController::class, 'lupaSandi']);
    Route::post('/lupa-sandi/kirim-otp', [\App\Http\Controllers\AuthController::class, 'kirimOtp']);
    Route::post('/lupa-sandi/verifikasi-otp', [\App\Http\Controllers\AuthController::class, 'verifikasiOtp']);
    Route::post('/lupa-sandi/simpan-sandi', [\App\Http\Controllers\AuthController::class, 'simpanSandiBaru']);
    Route::post('/lupa-pin', [NasabahApiController::class, 'lupaPin']);
});
