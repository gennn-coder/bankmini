<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Saver;
use App\Models\Rekening;
use App\Models\Transaksi;
use App\Models\JurnalAkuntansi;
use App\Models\LaporanHarian;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class TellerController extends Controller
{
    /**
     * Cari data Penabung (Saver) untuk Transaksi Setoran / Penarikan / Reset PIN
     */
    public function cariNasabah(Request $request)
    {
        $keyword = trim($request->query('q'));

        $saver = Saver::with('rekening')
            ->where('account_number', $keyword)
            ->orWhere('nis', $keyword)
            ->orWhere('student_name', 'LIKE', "%{$keyword}%")
            ->first();

        if (!$saver) {
            $unactivatedMember = Member::where('nis', $keyword)
                ->orWhere('student_name', 'LIKE', "%{$keyword}%")
                ->first();

            if ($unactivatedMember && $unactivatedMember->status === 'rekening tidak aktif') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anggota ' . $unactivatedMember->student_name . ' (NIS: ' . $unactivatedMember->nis . ') belum diaktifkan! Silakan lakukan aktivasi terlebih dahulu pada tab "Aktivasi Anggota (Otomatis)" agar dapat menabung.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Data penabung tidak ditemukan.',
            ]);
        }

        if (($saver->member && $saver->member->status === 'rekening tidak aktif') || ($saver->rekening && $saver->rekening->status === 'nonaktif')) {
            return response()->json([
                'success' => false,
                'message' => 'Rekening ' . $saver->student_name . ' (No Rek: ' . $saver->account_number . ') berstatus tidak aktif. Tidak dapat melakukan transaksi menabung / penarikan.',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $saver->id,
                'no_rekening' => $saver->account_number,
                'nis' => $saver->nis,
                'nama_siswa' => $saver->student_name,
                'kelas' => $saver->class_grade,
                'saldo' => (int) ($saver->rekening->saldo ?? 0),
                'status' => $saver->rekening->status ?? 'aktif',
            ]
        ]);
    }

    /**
     * Cari data Anggota (Member) yang belum memiliki rekening / belum pernah menabung
     */
    public function cariAnggota(Request $request)
    {
        $keyword = trim($request->query('q'));

        $member = Member::where(function ($q) use ($keyword) {
            $q->where('nis', $keyword)
              ->orWhere('student_name', 'LIKE', "%{$keyword}%");
        })
        ->where('status', 'rekening tidak aktif')
        ->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Data anggota (rekening belum aktif) tidak ditemukan.',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $member->user_id,
                'nama_siswa' => $member->student_name,
                'nis' => $member->nis,
                'kelas' => $member->class_grade,
                'angkatan_keluar' => $member->graduation_year,
                'status' => $member->status,
            ]
        ]);
    }

    /**
     * SISTEM OTOMATIS: Aktivasi Rekening Anggota Baru & Klon Data ke Penabung (Saver)
     * Syarat: Menabung setoran awal minimal Rp 10.000
     */
    public function aktivasiAnggota(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:members,user_id',
            'nominal' => 'required|integer|min:10000',
            'pin' => 'required|numeric|digits:6',
            'email' => 'required|email',
        ]);

        DB::beginTransaction();
        try {
            $member = Member::where('user_id', $request->user_id)->firstOrFail();

            if ($member->status === 'rekening aktif') {
                return back()->with('error', 'Anggota ini sudah memiliki rekening aktif sebagai penabung.');
            }

            $emailToUse = $request->email ?: $member->email;

            // 1. Periksa apakah data Saver sudah ada sebelumnya atau buat baru
            $saver = Saver::withTrashed()->where('user_id', $member->user_id)->orWhere('nis', $member->nis)->first();

            if ($saver) {
                if ($saver->trashed()) {
                    $saver->restore();
                }
                $accountNumber = $saver->account_number ?: Saver::generateNomorRekening();
                $saver->update([
                    'account_number' => $accountNumber,
                    'nis' => $member->nis,
                    'student_name' => $member->student_name,
                    'class_grade' => $member->class_grade,
                    'email' => $emailToUse,
                    'security_pin' => Hash::make($request->pin),
                ]);
            } else {
                $accountNumber = Saver::generateNomorRekening();
                $saver = Saver::create([
                    'user_id' => $member->user_id,
                    'account_number' => $accountNumber,
                    'nis' => $member->nis,
                    'student_name' => $member->student_name,
                    'class_grade' => $member->class_grade,
                    'email' => $emailToUse,
                    'security_pin' => Hash::make($request->pin),
                ]);
            }

            // 2. Update status anggota menjadi "rekening aktif" dan simpan email
            $member->status = 'rekening aktif';
            $member->email = $emailToUse;
            $member->save();

            if ($member->user) {
                $member->user->email = $emailToUse;
                $member->user->save();
            }

            // 3. Buat atau update Rekening dengan saldo awal dari setoran minimal 10rb
            $rekening = Rekening::withTrashed()->where('saver_id', $saver->id)->first();
            if ($rekening) {
                if ($rekening->trashed()) {
                    $rekening->restore();
                }
                $rekening->account_number = $accountNumber;
                $rekening->no_rekening = $accountNumber;
                $rekening->saldo += $request->nominal;
                $rekening->status = 'aktif';
                $rekening->save();
            } else {
                $rekening = Rekening::create([
                    'saver_id' => $saver->id,
                    'id_nasabah' => $saver->id,
                    'account_number' => $accountNumber,
                    'no_rekening' => $accountNumber,
                    'saldo' => $request->nominal,
                    'status' => 'aktif',
                ]);
            }

            // 5. Buat Catatan Transaksi Setoran Awal
            $noTrans = 'STR-AKT-' . date('YmdHis') . '-' . rand(100, 999);
            $transaksi = Transaksi::create([
                'no_transaksi' => $noTrans,
                'saver_id' => $saver->id,
                'id_nasabah' => $saver->id,
                'id_user' => auth()->id(),
                'saver_name' => $member->student_name,
                'account_number' => $accountNumber,
                'tanggal' => now(),
                'jenis_trans' => 'setoran',
                'nominal' => $request->nominal,
                'keterangan' => 'Setoran Awal Aktivasi Rekening Anggota',
            ]);

            // 6. Jurnal Akuntansi Double-Entry: Debit Kas 101, Kredit Simpanan 201
            JurnalAkuntansi::create([
                'id_transaksi' => $transaksi->id,
                'kode_akun' => '101',
                'posisi' => 'debit',
                'jumlah' => $request->nominal,
                'tanggal' => now(),
            ]);

            JurnalAkuntansi::create([
                'id_transaksi' => $transaksi->id,
                'kode_akun' => '201',
                'posisi' => 'kredit',
                'jumlah' => $request->nominal,
                'tanggal' => now(),
            ]);

            AuditLog::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Aktivasi Rekening Anggota Baru',
                'detail' => 'Mengaktifkan rekening siswa ' . $member->student_name . ' menjadi Penabung (No Rek: ' . $accountNumber . ', Setoran Awal: Rp ' . number_format($request->nominal) . ')',
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            return back()->with('success', 'Aktivasi Berhasil! Data siswa ' . $member->student_name . ' otomatis berpindah ke Tabel Penabung dengan No Rekening: ' . $accountNumber . ' dan saldo awal Rp ' . number_format($request->nominal))
                ->with('transaksi_sukses', [
                    'no_transaksi' => $noTrans,
                    'jenis_trans' => 'setoran',
                    'judul' => 'Aktivasi Rekening & Setoran Awal Berhasil',
                    'nama_siswa' => $member->student_name,
                    'no_rekening' => $accountNumber,
                    'nominal' => $request->nominal,
                    'saldo_akhir' => $rekening->saldo,
                    'waktu' => now()->format('d/m/Y H:i:s'),
                    'keterangan' => 'Setoran Awal Aktivasi Rekening Anggota',
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses aktivasi rekening: ' . $e->getMessage());
        }
    }

    /**
     * Transaksi Setoran Tunai Penabung
     */
    public function setoran(Request $request)
    {
        $request->validate([
            'id_nasabah' => 'required|exists:savers,id',
            'nominal' => 'required|integer|min:1000',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $saver = Saver::with('rekening')->findOrFail($request->id_nasabah);
            $rekening = $saver->rekening;

            if (!$rekening || $rekening->status !== 'aktif') {
                return back()->with('error', 'Rekening penabung sedang nonaktif.');
            }

            // Update Saldo
            $rekening->saldo += $request->nominal;
            $rekening->save();

            // Create Transaksi
            $noTrans = 'STR-' . date('YmdHis') . '-' . rand(100, 999);
            $transaksi = Transaksi::create([
                'no_transaksi' => $noTrans,
                'saver_id' => $saver->id,
                'id_nasabah' => $saver->id,
                'id_user' => auth()->id(),
                'saver_name' => $saver->student_name,
                'account_number' => $saver->account_number,
                'tanggal' => now(),
                'jenis_trans' => 'setoran',
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan ?? 'Setoran Tunai Teller',
            ]);

            // Jurnal Double-Entry: Debit Kas 101, Kredit Tabungan 201
            JurnalAkuntansi::create([
                'id_transaksi' => $transaksi->id,
                'kode_akun' => '101',
                'posisi' => 'debit',
                'jumlah' => $request->nominal,
                'tanggal' => now(),
            ]);

            JurnalAkuntansi::create([
                'id_transaksi' => $transaksi->id,
                'kode_akun' => '201',
                'posisi' => 'kredit',
                'jumlah' => $request->nominal,
                'tanggal' => now(),
            ]);

            AuditLog::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Setoran Tunai',
                'detail' => 'Setoran Rp ' . number_format($request->nominal) . ' ke No Rek ' . $saver->account_number . ' (' . $saver->student_name . ')',
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return back()->with('success', 'Setoran tunai Rp ' . number_format($request->nominal) . ' berhasil diproses. No Transaksi: ' . $noTrans)
                ->with('transaksi_sukses', [
                    'no_transaksi' => $noTrans,
                    'jenis_trans' => 'setoran',
                    'judul' => 'Transaksi Setoran Tunai Berhasil',
                    'nama_siswa' => $saver->student_name,
                    'no_rekening' => $saver->account_number,
                    'nominal' => $request->nominal,
                    'saldo_akhir' => $rekening->saldo,
                    'waktu' => now()->format('d/m/Y H:i:s'),
                    'keterangan' => $transaksi->keterangan,
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses setoran: ' . $e->getMessage());
        }
    }

    /**
     * Transaksi Penarikan Tunai Penabung (Wajib Verifikasi PIN 6-Digit, Min Penarikan 10.000, & Saldo Sisa Min 10.000)
     */
    public function penarikan(Request $request)
    {
        $request->validate([
            'id_nasabah' => 'required|exists:savers,id',
            'nominal' => 'required|integer|min:10000',
            'pin' => 'required|numeric|digits:6',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->nominal < 10000) {
            return back()->with('error', 'Transaksi Ditolak: Nominal penarikan tunai minimal adalah Rp 10.000.');
        }

        DB::beginTransaction();
        try {
            $saver = Saver::with('rekening')->findOrFail($request->id_nasabah);
            $rekening = $saver->rekening;

            if (!$rekening || $rekening->status !== 'aktif') {
                return back()->with('error', 'Rekening penabung sedang nonaktif.');
            }

            // Verifikasi PIN 6 Digit
            if (!Hash::check($request->pin, $saver->security_pin)) {
                return back()->with('error', 'PIN 6-Digit Keamanan Penabung Salah! Silakan gunakan fitur Reset PIN jika nasabah lupa PIN.');
            }

            // Validasi Saldo Minimum Mengendap Rp 10.000
            $saldoSetelahPenarikan = $rekening->saldo - $request->nominal;
            if ($saldoSetelahPenarikan < 10000) {
                return back()->with('error', 'Transaksi Ditolak: Saldo sisa/mengendap setelah penarikan tidak boleh kurang dari batas minimum Rp 10.000 (Saldo saat ini: Rp ' . number_format($rekening->saldo) . ').');
            }

            // Update Saldo
            $rekening->saldo = $saldoSetelahPenarikan;
            $rekening->save();

            // Create Transaksi
            $noTrans = 'TRK-' . date('YmdHis') . '-' . rand(100, 999);
            $transaksi = Transaksi::create([
                'no_transaksi' => $noTrans,
                'saver_id' => $saver->id,
                'id_nasabah' => $saver->id,
                'id_user' => auth()->id(),
                'saver_name' => $saver->student_name,
                'account_number' => $saver->account_number,
                'tanggal' => now(),
                'jenis_trans' => 'penarikan',
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan ?? 'Penarikan Tunai Teller',
            ]);

            // Jurnal Double-Entry: Debit Tabungan 201, Kredit Kas 101
            JurnalAkuntansi::create([
                'id_transaksi' => $transaksi->id,
                'kode_akun' => '201',
                'posisi' => 'debit',
                'jumlah' => $request->nominal,
                'tanggal' => now(),
            ]);

            JurnalAkuntansi::create([
                'id_transaksi' => $transaksi->id,
                'kode_akun' => '101',
                'posisi' => 'kredit',
                'jumlah' => $request->nominal,
                'tanggal' => now(),
            ]);

            AuditLog::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Penarikan Tunai',
                'detail' => 'Penarikan Rp ' . number_format($request->nominal) . ' dari No Rek ' . $saver->account_number . ' (' . $saver->student_name . ')',
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return back()->with('success', 'Penarikan tunai Rp ' . number_format($request->nominal) . ' berhasil diproses. No Transaksi: ' . $noTrans)
                ->with('transaksi_sukses', [
                    'no_transaksi' => $noTrans,
                    'jenis_trans' => 'penarikan',
                    'judul' => 'Transaksi Penarikan Tunai Berhasil',
                    'nama_siswa' => $saver->student_name,
                    'no_rekening' => $saver->account_number,
                    'nominal' => $request->nominal,
                    'saldo_akhir' => $rekening->saldo,
                    'waktu' => now()->format('d/m/Y H:i:s'),
                    'keterangan' => $transaksi->keterangan,
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses penarikan: ' . $e->getMessage());
        }
    }

    /**
     * FITUR LUPA PIN: Teller Melakukan Reset PIN Baru untuk Siswa/Penabung
     */
    public function resetPin(Request $request)
    {
        $request->validate([
            'id_nasabah' => 'required|exists:savers,id',
            'pin_baru' => 'required|numeric|digits:6',
        ]);

        $saver = Saver::findOrFail($request->id_nasabah);
        $saver->security_pin = Hash::make($request->pin_baru);
        $saver->save();

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Reset PIN Penabung oleh Teller',
            'detail' => 'Teller mereset PIN keamanan untuk ' . $saver->student_name . ' (No Rek: ' . $saver->account_number . ')',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'PIN Keamanan untuk ' . $saver->student_name . ' berhasil diperbarui menjadi PIN baru.');
    }

    /**
     * Submit Laporan Harian Penutupan Kas Teller ke Administrator
     */
    public function submitLaporanHarian(Request $request)
    {
        $request->validate([
            'uang_fisik' => 'required|integer|min:0',
        ]);

        $user = auth()->user();
        $tanggal = date('Y-m-d');

        $transaksiHariIni = Transaksi::where('id_user', $user->id)
            ->whereDate('tanggal', $tanggal)
            ->get();

        $totalSetoran = $transaksiHariIni->where('jenis_trans', 'setoran')->sum('nominal');
        $totalPenarikan = $transaksiHariIni->where('jenis_trans', 'penarikan')->sum('nominal');
        $saldoAwal = 0; // Kas awal harian laci teller
        $saldoAkhirSistem = $saldoAwal + $totalSetoran - $totalPenarikan;
        $selisih = $request->uang_fisik - $saldoAkhirSistem;

        $laporan = LaporanHarian::updateOrCreate(
            ['tanggal' => $tanggal, 'user_id' => $user->id],
            [
                'saldo_awal' => $saldoAwal,
                'total_setoran' => $totalSetoran,
                'total_penarikan' => $totalPenarikan,
                'saldo_akhir_sistem' => $saldoAkhirSistem,
                'uang_fisik' => $request->uang_fisik,
                'selisih' => $selisih,
                'status_approval' => 'pending',
                'supervisor_id' => null,
            ]
        );

        AuditLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Submit Laporan Harian Teller',
            'detail' => 'Mengirim laporan penutupan kas tanggal ' . $tanggal . ' (Uang Fisik: Rp ' . number_format($request->uang_fisik) . ', Selisih: Rp ' . number_format($selisih) . ')',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Laporan penutupan kas harian berhasil dikirim ke Administrator untuk approval.');
    }
}
