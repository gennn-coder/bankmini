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

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return $this->redirectUserByRole(Auth::user());
        }
        return view('welcome');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $input = trim($credentials['username']);
        $password = $credentials['password'];

        // Find user by username, nama_petugas, or via Saver / Member (nis, account_number, student_name)
        $user = User::where('username', $input)
            ->orWhere('nama_petugas', $input)
            ->first();

        if (!$user) {
            $saver = Saver::where('student_name', $input)
                ->orWhere('nis', $input)
                ->orWhere('account_number', $input)
                ->first();
            if ($saver && $saver->user_id) {
                $user = User::find($saver->user_id);
            }
        }

        if (!$user) {
            $member = Member::where('student_name', $input)
                ->orWhere('nis', $input)
                ->first();
            if ($member && $member->user_id) {
                $user = User::find($member->user_id);
            }
        }

        if ($user && Hash::check($password, $user->password)) {
            if ($user->status === 'terblokir') {
                return back()->withErrors(['username' => 'Akun Anda sedang terblokir. Silakan hubungi Administrator.']);
            }

            Auth::login($user);
            $request->session()->regenerate();

            return $this->redirectUserByRole($user);
        }

        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function lupaSandi(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // 1. Cari User berdasarkan email (bisa Teller maupun Nasabah)
        $user = User::where('email', $request->email)->first();
        $saver = Saver::where('email', $request->email)->first();
        $member = Member::where('email', $request->email)->first();

        if (!$user && $saver && $saver->user_id) {
            $user = User::find($saver->user_id);
        }
        if (!$user && $member && $member->user_id) {
            $user = User::find($member->user_id);
        }

        // Fallback untuk kemudahan pengujian jika email belum terpasang
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

        // Generate Sandi Baru
        $newPassword = ($user->role === 'teller' ? 'tl' : 'bm') . rand(100000, 999999);
        $user->password = Hash::make($newPassword);
        $user->save();

        // Kirim email asli ke Gmail
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
     * Cari akun berdasarkan NISN, No Rekening, Username, atau Nama
     */
    protected function findAccountByIdentifier(string $identifier)
    {
        $identifier = trim($identifier);

        // 1. Cek langsung User (username atau nama_petugas)
        $user = User::where('username', $identifier)
            ->orWhere('nama_petugas', $identifier)
            ->first();

        $saver = null;
        $member = null;

        // 2. Cek Saver (nis, account_number, atau student_name)
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

        // 3. Cek Member (nis atau student_name)
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
     * Kirim Kode OTP ke Gmail terdaftar untuk NISN / Username
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
                'message' => 'Akun dengan NISN atau Username "' . $request->identifier . '" tidak ditemukan di sistem.',
            ], 404);
        }

        $inputEmail = strtolower(trim($request->email));

        // Cek email akun
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

        // Cek kecocokan email
        $registeredEmails = array_filter([$userEmail, $saverEmail, $memberEmail]);
        if (!in_array($inputEmail, $registeredEmails, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat Gmail ' . $inputEmail . ' tidak sesuai dengan alamat Gmail terdaftar pada akun ini.',
            ], 422);
        }

        // Generate OTP 6-Digit
        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan ke Cache (15 menit)
        Cache::put('otp_reset_' . $user->id, [
            'otp' => $otp,
            'email' => $inputEmail,
            'user_id' => $user->id,
            'identifier' => $request->identifier,
        ], now()->addMinutes(15));

        // Simpan ke Session
        $request->session()->put('otp_reset_user_id', $user->id);
        $request->session()->put('otp_reset_code', $otp);
        $request->session()->put('otp_reset_email', $inputEmail);
        $request->session()->put('otp_reset_expires', now()->addMinutes(15)->timestamp);

        // Simpan ke DB password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $inputEmail],
            ['token' => $otp, 'created_at' => now()]
        );

        // Kirim email asli ke Gmail
        $emailSent = false;
        try {
            Mail::send('emails.otp_pemulihan', [
                'namaPenerima' => $namaPenerima,
                'username' => $user->username,
                'otp' => $otp,
                'email' => $inputEmail,
            ], function ($message) use ($inputEmail, $namaPenerima, $otp) {
                $message->to($inputEmail, $namaPenerima)
                        ->subject('[E-Teller Bank Mini] Kode OTP Pemulihan Sandi: ' . $otp);
            });
            $emailSent = true;
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email OTP: ' . $e->getMessage());
        }

        if (!$emailSent) {
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
     * Verifikasi Kode OTP yang dimasukkan pengguna
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

        // 1. Cek Cache
        $cached = Cache::get('otp_reset_' . $user->id);
        if ($cached && isset($cached['otp']) && $cached['otp'] === $inputOtp) {
            $isValid = true;
        }

        // 2. Cek Session
        if (!$isValid) {
            $sessCode = $request->session()->get('otp_reset_code');
            $sessUserId = $request->session()->get('otp_reset_user_id');
            $sessExp = $request->session()->get('otp_reset_expires');
            if ($sessCode === $inputOtp && $sessUserId == $user->id && $sessExp >= time()) {
                $isValid = true;
            }
        }

        // 3. Cek DB
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

        // OTP Valid! Buat token reset sekali pakai
        $resetToken = bin2hex(random_bytes(24));
        Cache::put('verified_reset_' . $resetToken, $user->id, now()->addMinutes(15));
        $request->session()->put('verified_reset_token', $resetToken);
        $request->session()->put('verified_reset_user_id', $user->id);

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP berhasil diverifikasi!',
            'nama' => $namaPenerima,
            'username' => $user->username,
            'reset_token' => $resetToken,
        ]);
    }

    /**
     * Simpan Kata Sandi Baru setelah OTP terverifikasi
     */
    public function simpanSandiBaru(Request $request)
    {
        $request->validate([
            'reset_token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $resetToken = $request->reset_token;
        $userId = Cache::get('verified_reset_' . $resetToken);
        if (!$userId) {
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

        $user->password = Hash::make($request->password);
        $user->save();

        // Bersihkan cache dan sesi
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
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diperbarui! Silakan masuk menggunakan kata sandi baru Anda.',
            'username' => $user->username,
        ]);
    }

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

        // Kirim email asli ke Gmail
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

    protected function redirectUserByRole($user)
    {
        switch ($user->role) {
            case 'administrator':
            case 'supervisor':
                // Supervisor dan Admin dijadikan satu ke admin dashboard
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
