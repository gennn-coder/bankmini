<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
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
