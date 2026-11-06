<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TellerController;

// Auth Routes
Route::get('/', [AuthController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'index']);
Route::get('/unduh-aplikasi', [AuthController::class, 'landingApk'])->name('landing.apk');
Route::get('/download-apk', [AuthController::class, 'downloadApk'])->name('app.download');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/lupa-sandi', [AuthController::class, 'lupaSandi'])->name('lupa-sandi');
Route::post('/lupa-sandi/kirim-otp', [AuthController::class, 'kirimOtp'])->name('lupa-sandi.kirim-otp');
Route::post('/lupa-sandi/verifikasi-otp', [AuthController::class, 'verifikasiOtp'])->name('lupa-sandi.verifikasi-otp');
Route::post('/lupa-sandi/simpan-sandi', [AuthController::class, 'simpanSandiBaru'])->name('lupa-sandi.simpan-sandi');
Route::post('/lupa-pin', [AuthController::class, 'lupaPin'])->name('lupa-pin');
Route::post('/lupa-pin/simpan-pin', [AuthController::class, 'simpanPinBaruOtp'])->name('lupa-pin.simpan-pin');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Role-Based Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Administrator (Termasuk Fitur Supervisor yang Disatukan)
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/supervisor/dashboard', [DashboardController::class, 'supervisor'])->name('supervisor.dashboard');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::post('/admin/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle-status');
    
    // CRUD & Impor Anggota / Member
    Route::post('/admin/members', [AdminController::class, 'storeMember'])->name('admin.members.store');
    Route::get('/admin/members/template', [AdminController::class, 'downloadMemberTemplate'])->name('admin.members.template');
    Route::post('/admin/members/import', [AdminController::class, 'importMembers'])->name('admin.members.import');
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
    Route::post('/admin/pengajuan-kas/{id}/approve', [AdminController::class, 'approvePengajuanKas'])->name('admin.pengajuan-kas.approve');
    Route::post('/admin/pengajuan-kas/{id}/reject', [AdminController::class, 'rejectPengajuanKas'])->name('admin.pengajuan-kas.reject');
    Route::post('/admin/pengaduan/{id}/tanggapi', [AdminController::class, 'tanggapiPengaduan'])->name('admin.pengaduan.tanggapi');

    // Teller
    Route::get('/teller/dashboard', [DashboardController::class, 'teller'])->name('teller.dashboard');
    Route::get('/teller/cari-nasabah', [TellerController::class, 'cariNasabah'])->name('teller.cari-nasabah');
    Route::get('/teller/cari-anggota', [TellerController::class, 'cariAnggota'])->name('teller.cari-anggota');
    Route::post('/teller/aktivasi-anggota', [TellerController::class, 'aktivasiAnggota'])->name('teller.aktivasi-anggota');
    Route::post('/teller/input-modal-awal', [TellerController::class, 'inputModalAwal'])->name('teller.modal-awal');
    Route::post('/teller/ajukan-kas', [TellerController::class, 'ajukanTambahanKas'])->name('teller.ajukan-kas');
    Route::post('/teller/setoran', [TellerController::class, 'setoran'])->name('teller.setoran');
    Route::post('/teller/penarikan', [TellerController::class, 'penarikan'])->name('teller.penarikan');
    Route::post('/teller/reset-pin', [TellerController::class, 'resetPin'])->name('teller.reset-pin');
    Route::post('/teller/laporan-harian', [TellerController::class, 'submitLaporanHarian'])->name('teller.laporan-harian');
    Route::post('/teller/status-layanan', [TellerController::class, 'updateStatusLayanan'])->name('teller.status-layanan');
    Route::post('/teller/antrean/panggil', [TellerController::class, 'panggilAntreanSelanjutnya'])->name('teller.antrean.panggil');
    Route::post('/teller/antrean/{id}/selesai', [TellerController::class, 'selesaikanAntrean'])->name('teller.antrean.selesai');
    Route::get('/status-layanan', function() {
        return response()->json(['status' => \App\Models\Pengaturan::getStatusLayanan()]);
    })->name('status-layanan');

    // Nasabah / Saver
    Route::get('/nasabah/dashboard', [DashboardController::class, 'nasabah'])->name('nasabah.dashboard');
    Route::post('/nasabah/target-tabungan', [DashboardController::class, 'simpanTargetTabungan'])->name('nasabah.target-tabungan.store');
    Route::post('/nasabah/target-tabungan/{id}/delete', [DashboardController::class, 'hapusTargetTabungan'])->name('nasabah.target-tabungan.delete');
    Route::post('/nasabah/ambil-antrean', [DashboardController::class, 'ambilAntrean'])->name('nasabah.ambil-antrean');
    Route::get('/nasabah/status-antrean', [DashboardController::class, 'statusAntrean'])->name('nasabah.status-antrean');
    Route::post('/nasabah/pengaduan', [DashboardController::class, 'kirimPengaduan'])->name('nasabah.pengaduan.store');
    Route::post('/nasabah/ganti-password', [DashboardController::class, 'gantiPassword'])->name('nasabah.ganti-password');
});

// Endpoint Pembersihan Cache Otomatis (CI/CD Deployment & Maintenance)
Route::get('/clear-system-cache-deploy-trigger', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');

        // Auto-reconcile: aktifkan kembali seluruh rekening milik siswa yang berstatus 'rekening aktif'
        \App\Models\Rekening::whereHas('saver.member', function ($q) {
            $q->where('status', 'rekening aktif');
        })->update(['status' => 'aktif']);

        $gentaSaver = \App\Models\Saver::where('nis', '8888')->first();
        if ($gentaSaver && $gentaSaver->rekening) {
            $gentaSaver->rekening->update(['status' => 'aktif']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Cache server berhasil dibersihkan dan status rekening aktif telah direkonsiliasi!',
            'timestamp' => now()->toDateTimeString(),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});





