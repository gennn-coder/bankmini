<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TellerController;

// Auth Routes
Route::get('/', [AuthController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/lupa-sandi', [AuthController::class, 'lupaSandi'])->name('lupa-sandi');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Role-Based Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Administrator (Termasuk Fitur Supervisor yang Disatukan)
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::post('/admin/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle-status');
    
    // CRUD Anggota / Member
    Route::post('/admin/members', [AdminController::class, 'storeMember'])->name('admin.members.store');
    Route::post('/admin/members/{user_id}/update', [AdminController::class, 'updateMember'])->name('admin.members.update');
    Route::post('/admin/members/{user_id}/delete', [AdminController::class, 'destroyMember'])->name('admin.members.destroy');
    
    // Penabung / Saver Actions
    Route::post('/admin/savers/{id}/reset-pin', [AdminController::class, 'resetPinSaver'])->name('admin.savers.reset-pin');
    Route::post('/admin/savers/{id}/delete', [AdminController::class, 'destroySaver'])->name('admin.savers.destroy');

    // Tutup Kas & Laporan Approval (Pindahan Supervisor ke Admin)
    Route::post('/admin/laporan/{id}/approve', [AdminController::class, 'approveLaporan'])->name('admin.laporan.approve');
    Route::post('/admin/laporan/{id}/reject', [AdminController::class, 'rejectLaporan'])->name('admin.laporan.reject');
    Route::get('/admin/laporan/{id}/pdf', [AdminController::class, 'downloadLaporanHarianPdf'])->name('admin.laporan.pdf');
    Route::get('/admin/laporan-bulanan/pdf', [AdminController::class, 'downloadLaporanBulananPdf'])->name('admin.laporan-bulanan.pdf');

    // Teller
    Route::get('/teller/dashboard', [DashboardController::class, 'teller'])->name('teller.dashboard');
    Route::get('/teller/cari-nasabah', [TellerController::class, 'cariNasabah'])->name('teller.cari-nasabah');
    Route::get('/teller/cari-anggota', [TellerController::class, 'cariAnggota'])->name('teller.cari-anggota');
    Route::post('/teller/aktivasi-anggota', [TellerController::class, 'aktivasiAnggota'])->name('teller.aktivasi-anggota');
    Route::post('/teller/setoran', [TellerController::class, 'setoran'])->name('teller.setoran');
    Route::post('/teller/penarikan', [TellerController::class, 'penarikan'])->name('teller.penarikan');
    Route::post('/teller/reset-pin', [TellerController::class, 'resetPin'])->name('teller.reset-pin');
    Route::post('/teller/laporan-harian', [TellerController::class, 'submitLaporanHarian'])->name('teller.laporan-harian');

    // Supervisor (Redirect langsung ke Admin Dashboard)
    Route::get('/supervisor/dashboard', [DashboardController::class, 'supervisor'])->name('supervisor.dashboard');
    Route::post('/supervisor/laporan/{id}/approve', [AdminController::class, 'approveLaporan'])->name('supervisor.laporan.approve');
    Route::post('/supervisor/laporan/{id}/reject', [AdminController::class, 'rejectLaporan'])->name('supervisor.laporan.reject');

    // Nasabah / Saver
    Route::get('/nasabah/dashboard', [DashboardController::class, 'nasabah'])->name('nasabah.dashboard');
});
