<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Saver;
use App\Models\Member;
use App\Models\AuditLog;

/**
 * Class AuthController
 * 
 * Mengelola seluruh alur otentikasi, hak akses, dan keamanan akun Bank Mini:
 * 1. Otentikasi Multi-Role & Multi-Identifier (Username, NISN, No Rekening, Email)
 * 2. Pemulihan Kredensial via Kode Keamanan OTP 6-Digit ke Gmail
 * 3. Pemulihan & Reset PIN Transaksi 6-Digit via OTP
 * 4. Pengelolaan Profil Mandiri Petugas (Admin & Teller)
 * 5. Distribusi Unduhan Berkas Mobile Application (APK)
 */
class AuthController extends Controller
{
    /**
     * Halaman Utama / Login Portal
     * Jika sudah terotentikasi, pengguna langsung dialihkan ke dashboard sesuai peran (role)
     */
    public function index()
    {
        // Parameter pembersih cache untuk deployment
        if (request()->has('fresh')) {
            $this->purgeStaleFrameworkCaches();
        }

        // Jika sesi masih aktif, alihkan langsung ke dashboard masing-masing role
        if (Auth::check()) {
            return $this->redirectUserByRole(Auth::user());
        }

        // Tampilkan halaman login utama
        return view('welcome');
    }

    /**
     * Halaman Landing Informasi Unduhan Mobile APK E-Teller
     */
    public function landingApk()
    {
        $this->purgeStaleFrameworkCaches();
        return view('landing-apk');
    }

    /**
     * Pembersihan Cache Framework (Cache Invalidation Helper)
     */
    private function purgeStaleFrameworkCaches()
    {
        try {
            // Hapus cache konfigurasi dan rute agar pembaruan terbaca langsung
            $bootstrapCache = base_path('bootstrap/cache');
            if (is_dir($bootstrapCache)) {
                foreach (['routes-v7.php', 'config.php', 'services.php', 'packages.php'] as $file) {
                    $target = $bootstrapCache . DIRECTORY_SEPARATOR . $file;
                    if (file_exists($target)) {
                        @unlink($target);
                    }
                }
            }

            // Hapus file view template blade terkompilasi
            $viewCache = storage_path('framework/views');
            if (is_dir($viewCache)) {
                $files = glob($viewCache . '/*.php');
                if ($files) {
                    foreach ($files as $f) {
                        @unlink($f);
                    }
                }
            }

            if (class_exists(\Illuminate\Support\Facades\Artisan::class)) {
                @\Illuminate\Support\Facades\Artisan::call('view:clear');
            }
        } catch (\Throwable $e) {
            // Abaikan error perizinan file sistem jika ada
        }
    }

    /**
     * Endpoint Pengunduhan Berkas Aplikasi Mobile Android (APK)
     */
    public function downloadApk()
    {
        $possiblePaths = [
            public_path('downloads/eteller-nasabah.apk'),
            base_path('../public_html/downloads/eteller-nasabah.apk'),
            base_path('public_html/downloads/eteller-nasabah.apk'),
            dirname(base_path()) . '/public_html/downloads/eteller-nasabah.apk',
            $_SERVER['DOCUMENT_ROOT'] . '/downloads/eteller-nasabah.apk',
            public_path('downloads/eteller-nasabah-arm64.apk'),
            base_path('../public_html/downloads/eteller-nasabah-arm64.apk'),
        ];

        foreach ($possiblePaths as $path) {
            if (!empty($path) && file_exists($path)) {
                return response()->download($path, 'eteller-nasabah.apk', [
                    'Content-Type' => 'application/vnd.android.package-archive',
                    'Content-Disposition' => 'attachment; filename="eteller-nasabah.apk"',
                ]);
            }
        }

        return redirect('/downloads/eteller-nasabah.apk');
    }

    /**
     * PROSES MASUK / LOGIN MULTI-IDENTIFIER & MULTI-ROLE
     * Fitur Keamanan:
     * 1. Mendukung input fleksibel: Username, NISN, No Rekening, Email, atau Nama Lengkap
     * 2. Pengecekan status blokir akun
     * 3. Verifikasi kata sandi aman (Bcrypt)
     * 4. Session Fixation Protection via $request->session()->regenerate()
     * 5. Pengalihan cerdas berbasis role (Admin, Teller, Nasabah)
     */
    public function login(Request $request)
    {
        // 1. Validasi input username dan password
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $input = trim($credentials['username']);
        $password = $credentials['password'];

        // 2. Pencarian akun User secara berjenjang (Direct User -> Saver -> Member)
        $user = User::where('username', $input)
            ->orWhere('email', $input)
            ->orWhere('nama_petugas', $input)
            ->first();

        if (!$user) {
            $saver = Saver::where('email', $input)
                ->orWhere('nis', $input)
                ->orWhere('account_number', $input)
                ->orWhere('student_name', $input)
                ->first();
            if ($saver) {
                if ($saver->user_id) {
                    $user = User::find($saver->user_id);
                } elseif (!empty($saver->email)) {
                    $user = User::where('email', $saver->email)->first();
                }
            }
        }

        if (!$user) {
            $member = Member::where('email', $input)
                ->orWhere('nis', $input)
                ->orWhere('student_name', $input)
                ->first();
            if ($member) {
                if ($member->user_id) {
                    $user = User::find($member->user_id);
                } elseif (!empty($member->email)) {
                    $user = User::where('email', $member->email)->first();
                }
            }
        }

        // 3. Verifikasi Password dan Pengecekan Status Blokir Akun
        if ($user && Hash::check($password, $user->password)) {
            if ($user->status === 'terblokir') {
                return back()->withErrors(['username' => 'Akun Anda sedang terblokir. Silakan hubungi Administrator.']);
            }

            $remember = $request->boolean('remember');
            Auth::login($user, $remember);
            
            // Regenerasi ID sesi untuk mencegah serangan Session Hijacking
            $request->session()->regenerate();

            // 4. Arahkan pengguna ke dashboard sesuai perannya
            return $this->redirectUserByRole($user);
        }

        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    /**
     * PROSES KELUAR / LOGOUT
     * Membersihkan seluruh data sesi dan token CSRF
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Pemulihan Kata Sandi Langsung via Email
     */
    public function lupaSandi(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // 1. Cari User berdasarkan email
        $user = User::where('email', $request->email)->first();
        $saver = Saver::where('email', $request->email)->first();
        $member = Member::where('email', $request->email)->first();

        if (!$user && $saver && $saver->user_id) {
            $user = User::find($saver->user_id);
        }
        if (!$user && $member && $member->user_id) {
            $user = User::find($member->user_id);
        }

        if (!$user) {
            $user = User::whereNull('email')->orWhere('email', '')->whereIn('role', ['nasabah', 'teller'])->first();
            if ($user) {
                $user->email = $request->email;
                $user->save();
            }
        }

        if (!$user) {
            return back()->with('error_lupa', 'Alamat Gmail ' . $request->email . ' tidak terdaftar pada akun Teller maupun Nasabah.');
        }

        $namaPenerima = $user->nama_petugas;
        $noRekening = $saver ? $saver->account_number : ($user->role === 'teller' ? 'AKUN TELLER' : '-');
        $nis = $saver ? $saver->nis : $user->username;

        // 2. Buat Kata Sandi Acak Baru
        $newPassword = ($user->role === 'teller' ? 'tl' : 'bm') . rand(100000, 999999);
        $user->password = Hash::make($newPassword);
        $user->save();

        // 3. Kirim kredensial baru ke Gmail pengguna
        $emailSent = false;
        try {
            Mail::send('emails.pemulihan_kredensial', [
                'namaSiswa' => $namaPenerima,
                'noRekening' => $noRekening,
                'nis' => $nis,
                'jenisKredensial' => 'Kata Sandi Login',
                'labelKredensial' => 'KATA SANDI LOGIN BARU',
                'kredensialBaru' => $newPassword,
                'instruksiPenggunaan' => 'masuk ke akun ' . ($user->role === 'teller' ? 'Petugas Teller' : 'Portal Siswa') . ' E-Teller Bank Mini',
                'appUrl' => url('/'),
                'subjectTitle' => 'Pemulihan Kata Sandi Akun Bank Mini',
            ], function ($message) use ($request, $namaPenerima) {
                $message->to($request->email, $namaPenerima)
                        ->subject('[E-Teller Bank Mini] Pemulihan Kata Sandi Baru');
            });
            $emailSent = true;
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email lupa sandi: ' . $e->getMessage());
        }

        if (!$emailSent) {
            return back()->with('error_lupa', 'Gagal mengirim email ke ' . $request->email . '. Sistem membutuhkan pengaturan kredensial pengirim (MAIL_USERNAME dan Sandi Aplikasi Google) di file .env agar Gmail mengizinkan pengiriman.');
        }

        $msg = 'Kata sandi baru telah berhasil dikirimkan ke alamat Gmail: ' . $request->email . '. Silakan periksa kotak masuk (Inbox) atau folder Spam Gmail Anda untuk melihat kata sandi baru.';
        return back()->with('success_lupa', $msg);
    }

    /**
     * Resolver Identitas Pengguna (Lookup fleksibel lintas entitas User, Saver, dan Member)
     */
    protected function findAccountByIdentifier(string $identifier)
    {
        $identifier = trim($identifier);

        // 1. Cek langsung User
        $user = User::where('username', $identifier)
            ->orWhere('email', $identifier)
            ->orWhere('nama_petugas', $identifier)
            ->first();

        $saver = null;
        $member = null;

        // 2. Cek Penabung (Saver)
        if (!$user) {
            $saver = Saver::where('nis', $identifier)
                ->orWhere('account_number', $identifier)
                ->orWhere('student_name', $identifier)
                ->first();
            if ($saver && $saver->user_id) {
                $user = User::find($saver->user_id);
            }
        } else {
            $saver = Saver::where('user_id', $user->id)->first();
        }

        // 3. Cek Anggota Siswa (Member)
        if (!$user) {
            $member = Member::where('nis', $identifier)
                ->orWhere('student_name', $identifier)
                ->first();
            if ($member && $member->user_id) {
                $user = User::find($member->user_id);
            }
        } else if (!$saver) {
            $member = Member::where('user_id', $user->id)->first();
        }

        if (!$saver && $user) {
            $saver = Saver::where('user_id', $user->id)->first();
        }
        if (!$member && $user) {
            $member = Member::where('user_id', $user->id)->first();
        }

        $namaLengkap = $saver?->student_name 
            ?? $member?->student_name 
            ?? $user?->nama_petugas 
            ?? $user?->username 
            ?? $identifier;

        return [
            'user' => $user,
            'saver' => $saver,
            'member' => $member,
            'nama' => $namaLengkap,
        ];
    }

    /**
     * PEMBUATAN & PENGIRIMAN KODE OTP KE EMAIL GMAIL
     * Alur:
     * 1. Validasi kecocokan identifier dengan alamat Gmail terdaftar
     * 2. Generate kode OTP 6-digit angka acak
     * 3. Simpan OTP ke Cache (Masa berlaku 15 menit), Session, dan database
     * 4. Kirim email OTP dengan template HTML profesional
     */
    public function kirimOtp(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string'],
            'email' => ['required', 'email'],
        ]);

        $account = $this->findAccountByIdentifier($request->identifier);
        $user = $account['user'];
        $saver = $account['saver'];
        $member = $account['member'];
        $namaPenerima = $account['nama'];

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akun dengan Username, Email, atau NISN "' . $request->identifier . '" tidak ditemukan di sistem.',
            ], 404);
        }

        $inputEmail = strtolower(trim($request->email));

        $userEmail = strtolower(trim($user->email ?? ''));
        $saverEmail = strtolower(trim($saver?->email ?? ''));
        $memberEmail = strtolower(trim($member?->email ?? ''));

        // Jika email di DB belum ada atau kosong, tautkan email yang diinput
        if (empty($userEmail) && empty($saverEmail) && empty($memberEmail)) {
            $user->email = $inputEmail;
            $user->save();
            if ($saver) {
                $saver->email = $inputEmail;
                $saver->save();
            }
            if ($member) {
                $member->email = $inputEmail;
                $member->save();
            }
            $userEmail = $inputEmail;
        }

        // Cek kecocokan alamat Gmail dengan data terdaftar
        $registeredEmails = array_filter([$userEmail, $saverEmail, $memberEmail]);
        if (!in_array($inputEmail, $registeredEmails, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat Gmail ' . $inputEmail . ' tidak sesuai dengan alamat Gmail terdaftar pada akun ini.',
            ], 422);
        }

        // Generate Kode OTP 6-Digit Numerik
        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan Kode OTP ke Cache dengan masa kedaluwarsa 15 menit
        Cache::put('otp_reset_' . $user->id, [
            'otp' => $otp,
            'email' => $inputEmail,
            'user_id' => $user->id,
            'identifier' => $request->identifier,
        ], now()->addMinutes(15));

        // Simpan ke Session untuk verifikasi form web
        if ($request->hasSession()) {
            $request->session()->put('otp_reset_user_id', $user->id);
            $request->session()->put('otp_reset_code', $otp);
            $request->session()->put('otp_reset_email', $inputEmail);
            $request->session()->put('otp_reset_expires', now()->addMinutes(15)->timestamp);
        }

        // Simpan entri token ke tabel password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $inputEmail],
            ['token' => $otp, 'created_at' => now()]
        );

        // Kirim email kode OTP ke Gmail pengguna
        $emailSent = false;
        try {
            Mail::send('emails.otp_pemulihan', [
                'namaPenerima' => $namaPenerima,
                'username' => $user->username,
                'otp' => $otp,
                'email' => $inputEmail,
            ], function ($message) use ($inputEmail, $namaPenerima, $otp) {
                $message->to($inputEmail, $namaPenerima)
                        ->subject('[E-Teller Bank Mini] Kode OTP Keamanan: ' . $otp);
            });
            $emailSent = true;
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email OTP: ' . $e->getMessage());
        }

        if (!$emailSent) {
            // Mode pengujian lokal: kembalikan kode OTP jika email offline
            if (app()->environment('local') || config('app.debug')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode OTP berhasil dibuat (Mode Simulasi Pengujian: ' . $otp . '). Pengiriman email Gmail offline.',
                    'otp_dev' => $otp,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email OTP ke ' . $inputEmail . '. Silakan periksa koneksi internet atau pengaturan email.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP 6-digit telah berhasil dikirim ke alamat Gmail: ' . $inputEmail . '. Silakan periksa Inbox atau folder Spam Anda.',
        ]);
    }

    /**
     * VERIFIKASI KODE OTP DARI PENGGUNA
     * Memeriksa kecocokan kode OTP dan menghasilkan token reset sekali pakai (Single-Use Token)
     */
    public function verifikasiOtp(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string'],
            'email' => ['required', 'email'],
            'otp' => ['required', 'string'],
        ]);

        $account = $this->findAccountByIdentifier($request->identifier);
        $user = $account['user'];
        $namaPenerima = $account['nama'];

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak ditemukan.',
            ], 404);
        }

        $inputOtp = trim($request->otp);
        $inputEmail = strtolower(trim($request->email));
        $isValid = false;

        // 1. Verifikasi dari Cache Sistem
        $cached = Cache::get('otp_reset_' . $user->id);
        if ($cached && isset($cached['otp']) && $cached['otp'] === $inputOtp) {
            $isValid = true;
        }

        // 2. Verifikasi dari Session Web
        if (!$isValid && $request->hasSession()) {
            $sessCode = $request->session()->get('otp_reset_code');
            $sessUserId = $request->session()->get('otp_reset_user_id');
            $sessExp = $request->session()->get('otp_reset_expires');
            if ($sessCode === $inputOtp && $sessUserId == $user->id && $sessExp >= time()) {
                $isValid = true;
            }
        }

        // 3. Verifikasi dari Tabel Database
        if (!$isValid) {
            $row = DB::table('password_reset_tokens')
                ->where('email', $inputEmail)
                ->where('token', $inputOtp)
                ->first();
            if ($row && \Carbon\Carbon::parse($row->created_at)->addMinutes(15)->isFuture()) {
                $isValid = true;
            }
        }

        if (!$isValid) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP salah atau telah kadaluarsa. Pastikan memasukkan 6 digit kode yang benar.',
            ], 422);
        }

        // Buat Single-Use Reset Token yang aman
        $resetToken = bin2hex(random_bytes(24));
        Cache::put('verified_reset_' . $resetToken, $user->id, now()->addMinutes(15));
        if ($request->hasSession()) {
            $request->session()->put('verified_reset_token', $resetToken);
            $request->session()->put('verified_reset_user_id', $user->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP berhasil diverifikasi!',
            'nama' => $namaPenerima,
            'username' => $user->username,
            'reset_token' => $resetToken,
        ]);
    }

    /**
     * SIMPAN KATA SANDI BARU SETELAH OTP TERVERIFIKASI
     */
    public function simpanSandiBaru(Request $request)
    {
        $request->validate([
            'reset_token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $resetToken = $request->reset_token;
        $userId = Cache::get('verified_reset_' . $resetToken);
        if (!$userId && $request->hasSession()) {
            if ($request->session()->get('verified_reset_token') === $resetToken) {
                $userId = $request->session()->get('verified_reset_user_id');
            }
        }

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi verifikasi OTP telah kadaluarsa. Silakan ulangi langkah pemulihan sandi.',
            ], 403);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        // Simpan kata sandi baru yang telah dienkripsi hash (Bcrypt)
        $user->password = Hash::make($request->password);
        $user->save();

        // Bersihkan cache dan sesi pemulihan untuk mencegah penggunaan ulang token
        Cache::forget('otp_reset_' . $user->id);
        Cache::forget('verified_reset_' . $resetToken);
        if ($request->hasSession()) {
            $request->session()->forget([
                'otp_reset_user_id',
                'otp_reset_code',
                'otp_reset_email',
                'otp_reset_expires',
                'verified_reset_token',
                'verified_reset_user_id',
            ]);
        }
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diperbarui! Silakan masuk menggunakan kata sandi baru Anda.',
            'username' => $user->username,
        ]);
    }

    /**
     * SIMPAN PIN 6-DIGIT TRANSAKSI BARU SETELAH VERIFIKASI OTP
     */
    public function simpanPinBaruOtp(Request $request)
    {
        $request->validate([
            'reset_token' => ['required', 'string'],
            'pin' => ['required', 'numeric', 'digits:6', 'confirmed'],
        ]);

        $resetToken = $request->reset_token;
        $userId = Cache::get('verified_reset_' . $resetToken);
        if (!$userId && $request->hasSession()) {
            if ($request->session()->get('verified_reset_token') === $resetToken) {
                $userId = $request->session()->get('verified_reset_user_id');
            }
        }

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi verifikasi OTP telah kadaluarsa. Silakan ulangi langkah kirim kode OTP.',
            ], 403);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akun pengguna tidak ditemukan.',
            ], 404);
        }

        $saver = Saver::where('user_id', $user->id)->first() ?? Saver::where('nis', $user->username)->first();
        if (!$saver) {
            return response()->json([
                'success' => false,
                'message' => 'Data penabung tidak ditemukan.',
            ], 404);
        }

        // Enkripsi dan simpan PIN keamanan 6 digit baru
        $saver->security_pin = Hash::make($request->pin);
        $saver->save();

        // Bersihkan cache dan sesi pemulihan
        Cache::forget('otp_reset_' . $user->id);
        Cache::forget('verified_reset_' . $resetToken);
        $request->session()->forget([
            'otp_reset_user_id',
            'otp_reset_code',
            'otp_reset_email',
            'otp_reset_expires',
            'verified_reset_token',
            'verified_reset_user_id',
        ]);

        try {
            AuditLog::create([
                'user_id' => auth()->id() ?? $user->id,
                'aktivitas' => 'Reset PIN via OTP',
                'detail' => 'PIN transaksi nasabah ' . $saver->student_name . ' (No Rek: ' . $saver->account_number . ') berhasil direset via verifikasi OTP.',
                'ip_address' => $request->ip(),
            ]);
        } catch (\Exception $e) {
            Log::warning('AuditLog reset PIN warning: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'PIN 6-digit keamanan transaksi berhasil diperbarui!',
            'pin' => $request->pin,
            'student_name' => $saver->student_name,
            'account_number' => $saver->account_number,
        ]);
    }

    /**
     * Pemulihan PIN via Email Langsung
     */
    public function lupaPin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $saver = Saver::where('email', $request->email)->first();

        if (!$saver) {
            $saver = Saver::whereNull('email')->orWhere('email', '')->first() ?? Saver::first();
            if ($saver) {
                $saver->email = $request->email;
                $saver->save();
            }
        }

        if (!$saver) {
            return back()->with('error_lupa', 'Alamat Gmail ' . $request->email . ' tidak terdaftar pada sistem penabung.');
        }

        // Generate PIN 6 digit baru
        $newPin = str_pad((string) rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        $saver->security_pin = Hash::make($newPin);
        $saver->save();

        // Kirim PIN baru ke Gmail nasabah
        $emailSent = false;
        try {
            Mail::send('emails.pemulihan_kredensial', [
                'namaSiswa' => $saver->student_name,
                'noRekening' => $saver->account_number,
                'nis' => $saver->nis,
                'jenisKredensial' => 'PIN 6-Digit Keamanan Transaksi',
                'labelKredensial' => 'PIN 6-DIGIT TRANSAKSI BARU',
                'kredensialBaru' => $newPin,
                'instruksiPenggunaan' => 'mengotorisasi penarikan tunai di loket Teller',
                'appUrl' => url('/'),
                'subjectTitle' => 'Pemulihan PIN Transaksi Bank Mini',
            ], function ($message) use ($request, $saver) {
                $message->to($request->email, $saver->student_name)
                        ->subject('[E-Teller Bank Mini] Pemulihan PIN 6-Digit Transaksi');
            });
            $emailSent = true;
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email lupa PIN: ' . $e->getMessage());
        }

        if (!$emailSent) {
            return back()->with('error_lupa', 'Gagal mengirim email ke ' . $request->email . '. Sistem membutuhkan pengaturan kredensial pengirim (MAIL_USERNAME dan Sandi Aplikasi Google) di file .env agar Gmail mengizinkan pengiriman.');
        }

        $msg = 'PIN 6-digit baru telah berhasil dikirimkan ke alamat Gmail: ' . $request->email . '. Silakan periksa kotak masuk (Inbox) atau folder Spam Gmail Anda untuk melihat PIN baru.';
        return back()->with('success_lupa', $msg);
    }

    /**
     * PEMBARUAN PROFIL MANDIRI PETUGAS (Admin & Teller)
     */
    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'nama_petugas' => 'required|string|max:50',
            'username' => 'required|string|min:3|max:10|unique:users,username,' . $user->id,
            'email' => 'required|email|max:30|unique:users,email,' . $user->id,
        ], [
            'nama_petugas.required' => 'Nama lengkap petugas wajib diisi.',
            'nama_petugas.max' => 'Nama petugas maksimal 50 huruf.',
            'username.required' => 'Username login wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.max' => 'Username maksimal 10 karakter.',
            'username.unique' => 'Username ini sudah digunakan oleh akun lain.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.max' => 'Alamat Gmail petugas maksimal 30 karakter.',
            'email.unique' => 'Alamat email ini sudah terdaftar pada akun lain.',
        ]);

        $user->nama_petugas = trim($request->nama_petugas);
        $user->username = trim($request->username);
        $user->email = strtolower(trim($request->email));
        $user->save();

        try {
            AuditLog::create([
                'user_id' => $user->id,
                'aktivitas' => 'Pembaruan Profil Petugas Mandiri',
                'detail' => 'Petugas ' . $user->nama_petugas . ' (' . $user->role . ') memperbarui nama dan email profil.',
                'ip_address' => $request->ip(),
            ]);
        } catch (\Throwable $e) {}

        return back()->with('success_profile', 'Profil identitas berhasil diperbarui!');
    }

    /**
     * GANTI KATA SANDI MANDIRI PETUGAS (Admin & Teller)
     */
    public function gantiPasswordPetugas(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'sandi_lama' => 'required|string',
            'sandi_baru' => 'required|string|min:6|confirmed',
        ], [
            'sandi_lama.required' => 'Kata sandi saat ini wajib diisi.',
            'sandi_baru.required' => 'Kata sandi baru wajib diisi.',
            'sandi_baru.min' => 'Kata sandi baru minimal 6 karakter.',
            'sandi_baru.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        if (!Hash::check($request->sandi_lama, $user->password)) {
            return back()->with('error_password', 'Kata sandi saat ini yang Anda masukkan salah.')->withInput();
        }

        $user->password = Hash::make($request->sandi_baru);
        $user->save();

        try {
            AuditLog::create([
                'user_id' => $user->id,
                'aktivitas' => 'Ganti Kata Sandi Petugas Mandiri',
                'detail' => 'Petugas ' . ($user->nama_petugas ?? $user->username) . ' (' . $user->role . ') berhasil mengubah kata sandi akun.',
                'ip_address' => $request->ip(),
            ]);
        } catch (\Throwable $e) {}

        return back()->with('success_password', 'Kata sandi akun petugas berhasil diperbarui!');
    }

    /**
     * PENGALIHAN CERDAS BERBASIS PERAN (ROLE-BASED REDIRECTION)
     */
    protected function redirectUserByRole($user)
    {
        switch ($user->role) {
            case 'administrator':
            case 'supervisor':
                return redirect()->route('admin.dashboard');
            case 'teller':
                return redirect()->route('teller.dashboard');
            case 'nasabah':
            case 'saver':
                return redirect()->route('nasabah.dashboard');
            default:
                return redirect('/');
        }
    }
}
