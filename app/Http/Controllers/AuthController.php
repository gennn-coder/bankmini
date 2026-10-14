<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        $saver = Saver::where('email', $request->email)->first();

        if ($saver) {
            return back()->with('success_lupa', 'Instruksi pemulihan kata sandi telah berhasil dikirimkan ke alamat Gmail: ' . $request->email . '. Silakan periksa inbox Gmail Anda.');
        }

        return back()->with('success_lupa', 'Jika alamat Gmail ' . $request->email . ' terdaftar, instruksi pemulihan kata sandi telah dikirimkan. Silakan periksa inbox Gmail Anda.');
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
