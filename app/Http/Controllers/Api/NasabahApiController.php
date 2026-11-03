<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Saver;
use App\Models\Member;
use App\Models\Transaksi;
use App\Models\Rekening;

class NasabahApiController extends Controller
{
    /**
     * API Login Nasabah/Penabung via Mobile
     * Menerima username / nama_siswa / nis / no_rekening dan password
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $input = trim($request->username);
        $password = $request->password;

        // Cari user berdasarkan username, nama_petugas, atau tabel savers/members
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

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password yang Anda masukkan salah.',
            ], 401);
        }

        if ($user->status === 'terblokir') {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda sedang terblokir. Silakan hubungi Administrator.',
            ], 403);
        }

        $saverData = Saver::where('user_id', $user->id)->with('rekening')->first();
        $memberData = Member::where('user_id', $user->id)->first();

        if (!$saverData && !$memberData) {
            return response()->json([
                'success' => false,
                'message' => 'Data akun siswa belum terdaftar.',
            ], 404);
        }

        $namaSiswa = $saverData ? $saverData->student_name : ($memberData->student_name ?? $user->nama_petugas);
        $noRekening = $saverData ? $saverData->account_number : '-';
        $nis = $saverData ? $saverData->nis : ($memberData->nis ?? $user->username);
        $kelas = $saverData ? $saverData->class_grade : ($memberData->class_grade ?? '-');
        $email = $saverData ? $saverData->email : ($memberData->email ?? $user->email);
        $saldo = $saverData ? (int) ($saverData->rekening->saldo ?? 0) : 0;
        $statusRekening = $saverData && $saverData->rekening ? $saverData->rekening->status : 'rekening tidak aktif';

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'user_id' => $user->id,
                'nasabah_id' => $saverData ? $saverData->id : $user->id,
                'saver_id' => $saverData ? $saverData->id : null,
                'nama_siswa' => $namaSiswa,
                'student_name' => $namaSiswa,
                'no_rekening' => $noRekening,
                'account_number' => $noRekening,
                'nis' => $nis,
                'kelas' => $kelas,
                'class_grade' => $kelas,
                'email' => $email,
                'saldo' => $saldo,
                'status_rekening' => $statusRekening,
                'is_aktif' => ($saverData && $statusRekening === 'aktif'),
                'status_layanan' => \App\Models\Pengaturan::getStatusLayanan(),
                'qr_url' => $saverData ? ('https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=15&data=' . $saverData->account_number) : '',
            ]
        ]);
    }

    /**
     * API Dashboard Real-Time Nasabah/Penabung
     */
    public function dashboard($id)
    {
        $saver = Saver::with('rekening')->find($id);
        $member = null;
        if (!$saver) {
            $member = Member::where('user_id', $id)->orWhere('nis', $id)->first();
        }

        if (!$saver && !$member) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa belum ditemukan.',
            ], 404);
        }

        $namaSiswa = $saver ? $saver->student_name : $member->student_name;
        $noRekening = $saver ? $saver->account_number : '-';
        $nis = $saver ? $saver->nis : $member->nis;
        $kelas = $saver ? $saver->class_grade : $member->class_grade;
        $email = $saver ? $saver->email : $member->email;
        $saldo = $saver ? (int) ($saver->rekening->saldo ?? 0) : 0;
        $statusRekening = $saver && $saver->rekening ? $saver->rekening->status : 'rekening tidak aktif';

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $saver ? $saver->id : $member->user_id,
                'nama_siswa' => $namaSiswa,
                'student_name' => $namaSiswa,
                'no_rekening' => $noRekening,
                'account_number' => $noRekening,
                'nis' => $nis,
                'kelas' => $kelas,
                'class_grade' => $kelas,
                'email' => $email,
                'saldo' => $saldo,
                'status_rekening' => $statusRekening,
                'is_aktif' => ($saver && $statusRekening === 'aktif'),
                'status_layanan' => \App\Models\Pengaturan::getStatusLayanan(),
                'qr_url' => $saver ? ('https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=15&data=' . $saver->account_number) : '',
            ]
        ]);
    }

    /**
     * API Riwayat Mutasi Rekening Penabung
     */
    public function mutasi($id)
    {
        $saver = Saver::find($id);

        if (!$saver) {
            // Siswa belum aktif menabung, riwayat mutasi kosong
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        $transaksi = Transaksi::where(function ($q) use ($saver) {
                $q->where('saver_id', $saver->id)
                  ->orWhere('id_nasabah', $saver->id);
            })
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'no_transaksi' => $t->no_transaksi,
                    'jenis_trans' => $t->jenis_trans,
                    'nominal' => (int) $t->nominal,
                    'keterangan' => $t->keterangan ?? '-',
                    'tanggal' => $t->tanggal,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $transaksi
        ]);
    }

    /**
     * API Lupa Sandi Akun
     */
    public function lupaSandi(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $saver = Saver::where('email', $request->email)->first();

        return response()->json([
            'success' => true,
            'message' => 'Jika alamat Gmail terdaftar, petunjuk pemulihan kata sandi telah dikirimkan ke email Anda.',
        ]);
    }

    /**
     * API Lupa PIN Keamanan Penabung
     */
    public function lupaPin(Request $request)
    {
        $request->validate([
            'nis' => 'required|string',
        ]);

        $saver = Saver::where('nis', $request->nis)->first();

        if (!$saver) {
            return response()->json([
                'success' => false,
                'message' => 'Data penabung dengan NIS tersebut tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Permintaan reset PIN diterima. Silakan kunjungi Teller Bank Mini untuk verifikasi identitas dan pengaturan PIN 6-digit baru Anda.',
        ]);
    }
}
