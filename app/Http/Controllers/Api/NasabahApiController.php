<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Saver;
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

        // Cari user berdasarkan username, nama_petugas, atau tabel savers (student_name, nis, account_number)
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

        if (!$saverData) {
            return response()->json([
                'success' => false,
                'message' => 'Data penabung belum diaktifkan oleh teller untuk akun ini.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'user_id' => $user->id,
                'nasabah_id' => $saverData->id,
                'saver_id' => $saverData->id,
                'nama_siswa' => $saverData->student_name,
                'student_name' => $saverData->student_name,
                'no_rekening' => $saverData->account_number,
                'account_number' => $saverData->account_number,
                'nis' => $saverData->nis,
                'kelas' => $saverData->class_grade,
                'class_grade' => $saverData->class_grade,
                'email' => $saverData->email,
                'saldo' => (int) ($saverData->rekening->saldo ?? 0),
                'status_rekening' => $saverData->rekening->status ?? 'aktif',
                'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=15&data=' . $saverData->account_number,
            ]
        ]);
    }

    /**
     * API Dashboard Real-Time Nasabah/Penabung
     */
    public function dashboard($id)
    {
        $saver = Saver::with('rekening')->find($id);

        if (!$saver) {
            return response()->json([
                'success' => false,
                'message' => 'Data penabung tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $saver->id,
                'nama_siswa' => $saver->student_name,
                'student_name' => $saver->student_name,
                'no_rekening' => $saver->account_number,
                'account_number' => $saver->account_number,
                'nis' => $saver->nis,
                'kelas' => $saver->class_grade,
                'class_grade' => $saver->class_grade,
                'email' => $saver->email,
                'saldo' => (int) ($saver->rekening->saldo ?? 0),
                'status_rekening' => $saver->rekening->status ?? 'aktif',
                'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=15&data=' . $saver->account_number,
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
            return response()->json([
                'success' => false,
                'message' => 'Data penabung tidak ditemukan.',
            ], 404);
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
