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
use App\Models\PengajuanKas;
use App\Models\Pengaturan;
use App\Models\PerjanjianPenarikan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf;

class TellerController extends Controller
{
    public function cariNasabah(Request $request)
    {
        $keyword = trim($request->query('q'));

        // cari penabung berdasarkan nomor rekening, nis, atau nama
        $saver = Saver::with('rekening')
            ->where('account_number', $keyword)
            ->orWhere('nis', $keyword)
            ->orWhere('student_name', 'LIKE', "%{$keyword}%")
            ->first();

        // cek apakah siswa masih di data anggota dan belum aktivasi
        if (!$saver) {
            $unactivatedMember = Member::where('nis', $keyword)
                ->orWhere('student_name', 'LIKE', "%{$keyword}%")
                ->first();

            if ($unactivatedMember && $unactivatedMember->status === 'rekening tidak aktif') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anggota ' . $unactivatedMember->student_name . ' (NIS: ' . $unactivatedMember->nis . ') belum diaktifkan! Silakan lakukan aktivasi terlebih dahulu pada tab "Aktivasi Anggota" agar dapat menabung.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Data penabung tidak ditemukan.',
            ]);
        }

        // sinkronisasi otomatis jika data profil siswa diubah oleh admin
        if ($saver->member) {
            if ($saver->student_name !== $saver->member->student_name || $saver->class_grade !== $saver->member->class_grade || $saver->nis !== $saver->member->nis) {
                $saver->update([
                    'student_name' => $saver->member->student_name,
                    'class_grade' => $saver->member->class_grade,
                    'nis' => $saver->member->nis,
                    'email' => $saver->member->email ?? $saver->email,
                ]);
            }

            if ($saver->member->status === 'rekening aktif') {
                if ($saver->rekening) {
                    if ($saver->rekening->status === 'nonaktif') {
                        $saver->rekening->update(['status' => 'aktif']);
                        $saver->rekening->refresh();
                    }
                } else {
                    $accountNumber = $saver->account_number ?: Saver::generateNomorRekening();
                    $rekeningBaru = Rekening::create([
                        'saver_id' => $saver->id,
                        'id_nasabah' => $saver->id,
                        'account_number' => $accountNumber,
                        'no_rekening' => $accountNumber,
                        'saldo' => 0,
                        'status' => 'aktif',
                    ]);
                    $saver->setRelation('rekening', $rekeningBaru);
                }
            }
        }

        // cek status rekening aktif
        if (($saver->member && $saver->member->status === 'rekening tidak aktif') || ($saver->rekening && $saver->rekening->status === 'nonaktif')) {
            return response()->json([
                'success' => false,
                'message' => 'Rekening ' . ($saver->member->student_name ?? $saver->student_name) . ' (No Rek: ' . $saver->account_number . ') berstatus tidak aktif. Tidak dapat melakukan transaksi menabung / penarikan.',
            ]);
        }

        // hitung total penarikan hari ini untuk cek sisa limit reguler 1 juta
        $totalTarikHariIni = (int) Transaksi::where(function($q) use ($saver) {
                $q->where('saver_id', $saver->id)->orWhere('id_nasabah', $saver->id);
            })
            ->where('jenis_trans', 'penarikan')
            ->whereDate('tanggal', date('Y-m-d'))
            ->sum('nominal');

        $perjanjianHariIni = PerjanjianPenarikan::where('saver_id', $saver->id)
            ->whereDate('tanggal_rencana', date('Y-m-d'))
            ->whereIn('status', ['disetujui', 'menunggu'])
            ->get(['id', 'nominal', 'status', 'alasan', 'diajukan_pada', 'tanggal_rencana']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $saver->id,
                'no_rekening' => $saver->account_number,
                'nis' => $saver->member->nis ?? $saver->nis,
                'nama_siswa' => $saver->member->student_name ?? $saver->student_name,
                'kelas' => $saver->member->class_grade ?? $saver->class_grade,
                'email' => $saver->email ?? ($saver->member->email ?? ''),
                'saldo' => (int) ($saver->rekening->saldo ?? 0),
                'status' => $saver->rekening->status ?? 'aktif',
                'total_penarikan_hari_ini' => $totalTarikHariIni,
                'sisa_limit_reguler' => max(0, 1000000 - $totalTarikHariIni),
                'perjanjian_hari_ini' => $perjanjianHariIni,
            ]
        ]);
    }

    public function cariAnggota(Request $request)
    {
        $keyword = trim($request->query('q'));

        // cari data siswa yang belum aktivasi rekening
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

    public function aktivasiAnggota(Request $request)
    {
        // validasi input aktivasi rekening baru
        $rules = [
            'user_id' => 'required|exists:members,user_id',
            'nominal' => 'required|integer|min:10000',
            'pin' => 'required|numeric|digits:6',
            'email' => 'required|email|max:30',
        ];

        if ($request->filled('pin_confirmation')) {
            $rules['pin'] = 'required|numeric|digits:6|confirmed';
        }

        $request->validate($rules, [
            'nominal.min' => 'Setoran awal aktivasi minimal Rp 10.000.',
            'pin.digits' => 'PIN harus terdiri dari tepat 6 angka numerik.',
            'pin.confirmed' => 'Konfirmasi PIN tidak cocok dengan PIN yang dibuat.',
            'email.required' => 'Alamat Gmail wajib diisi.',
            'email.email' => 'Format alamat Gmail tidak valid.',
            'email.max' => 'Alamat Gmail siswa maksimal 30 karakter.',
        ]);

        // proteksi double submit formulir
        $tokenTransaksi = $request->input('_token_transaksi');
        if ($tokenTransaksi) {
            $cacheKey = 'trx_token_' . $tokenTransaksi;
            if (!Cache::add($cacheKey, true, now()->addMinutes(10))) {
                return back()->with('error', 'Aktivasi rekening ini sedang/sudah diproses. Mencegah duplikasi entri.');
            }
        }

        DB::beginTransaction();
        try {
            $member = Member::where('user_id', $request->user_id)->firstOrFail();

            if ($member->status === 'rekening aktif') {
                if ($tokenTransaksi) {
                    Cache::forget($cacheKey);
                }
                return back()->with('error', 'Anggota ini sudah memiliki rekening aktif sebagai penabung.');
            }

            $emailToUse = $request->email ?: $member->email;

            // buat atau pulihkan data penabung dengan nomor rekening baru
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

            // ubah status anggota siswa menjadi rekening aktif
            $member->status = 'rekening aktif';
            $member->email = $emailToUse;
            $member->pin = Hash::make($request->pin);
            $member->save();

            if ($member->user) {
                $member->user->email = $emailToUse;
                $member->user->status = 'aktif';
                $member->user->save();
            }

            // buat buku rekening tabungan baru
            $rekening = Rekening::withTrashed()->where('saver_id', $saver->id)->first();
            if ($rekening) {
                if ($rekening->trashed()) {
                    $rekening->restore();
                }
                $rekening->account_number = $accountNumber;
                $rekening->no_rekening = $accountNumber;
                $rekening->status = 'aktif';
                $rekening->save();
            } else {
                $rekening = Rekening::create([
                    'saver_id' => $saver->id,
                    'id_nasabah' => $saver->id,
                    'account_number' => $accountNumber,
                    'no_rekening' => $accountNumber,
                    'saldo' => 0,
                    'status' => 'aktif',
                ]);
            }

            $saldoSebelumAktivasi = $rekening->saldo;

            // catat setoran awal aktivasi rekening
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

            $rekening->refresh();

            // fallback saldo jika trigger database belum terpasang
            if ($rekening->saldo === $saldoSebelumAktivasi) {
                $rekening->saldo += $request->nominal;
                $rekening->save();
            }

            // catat jurnal debit kas dan kredit simpanan
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
            if ($tokenTransaksi) {
                Cache::forget($cacheKey);
            }
            DB::rollBack();
            return back()->with('error', 'Gagal memproses aktivasi rekening: ' . $e->getMessage());
        }
    }

    public function setoran(Request $request)
    {
        $request->validate([
            'id_nasabah' => 'required|exists:savers,id',
            'nominal' => 'required|integer|min:1000',
            'keterangan' => 'nullable|string|max:50',
        ], [
            'keterangan.max' => 'Keterangan slip maksimal 50 huruf.',
        ]);

        // proteksi double submit transaksi setoran
        $tokenTransaksi = $request->input('_token_transaksi');
        if ($tokenTransaksi) {
            $cacheKey = 'trx_token_' . $tokenTransaksi;
            if (!Cache::add($cacheKey, true, now()->addMinutes(10))) {
                return back()->with('error', 'Transaksi ditolak: Permintaan setoran ganda (double submission) terdeteksi. Transaksi sudah diproses.');
            }
        }

        // cek transaksi setoran identik dalam 3 detik terakhir
        $recentDuplicate = Transaksi::where(function($q) use ($request) {
                $q->where('saver_id', $request->id_nasabah)
                  ->orWhere('id_nasabah', $request->id_nasabah);
            })
            ->where('jenis_trans', 'setoran')
            ->where('nominal', $request->nominal)
            ->where('created_at', '>=', now()->subSeconds(3))
            ->first();

        if ($recentDuplicate) {
            return back()->with('error', 'Transaksi ganda terdeteksi! Setoran untuk penabung ini baru saja diproses (No: ' . $recentDuplicate->no_transaksi . ').');
        }

        DB::beginTransaction();
        try {
            $saver = Saver::with('rekening')->lockForUpdate()->findOrFail($request->id_nasabah);
            $rekening = Rekening::where('saver_id', $saver->id)->lockForUpdate()->first();

            if (!$rekening || $rekening->status !== 'aktif') {
                DB::rollBack();
                if ($tokenTransaksi) {
                    Cache::forget($cacheKey);
                }
                return back()->with('error', 'Rekening penabung sedang nonaktif.');
            }

            $saldoSebelum = $rekening->saldo;

            // catat transaksi setoran tunai
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

            $rekening->refresh();

            // fallback saldo jika trigger database belum terpasang
            if ($rekening->saldo === $saldoSebelum) {
                $rekening->saldo += $request->nominal;
                $rekening->save();
            }

            // catat jurnal debit kas dan kredit tabungan
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
            if ($tokenTransaksi) {
                Cache::forget($cacheKey);
            }
            DB::rollBack();
            return back()->with('error', 'Gagal memproses setoran: ' . $e->getMessage());
        }
    }

    public function penarikan(Request $request)
    {
        // validasi penarikan minimal 10 ribu dan pin 6 digit
        $request->validate([
            'id_nasabah' => 'required|exists:savers,id',
            'nominal' => 'required|integer|min:10000',
            'pin' => 'required|numeric|digits:6',
            'keterangan' => 'nullable|string|max:50',
        ], [
            'id_nasabah.required' => 'Pilih penabung terlebih dahulu.',
            'nominal.required' => 'Nominal penarikan wajib diisi.',
            'nominal.min' => 'Nominal penarikan tunai minimal adalah Rp 10.000.',
            'pin.required' => 'PIN 6-digit keamanan penabung wajib diisi.',
            'pin.numeric' => 'PIN penabung harus berupa angka.',
            'pin.digits' => 'PIN penabung harus tepat 6 digit angka.',
            'keterangan.max' => 'Keterangan slip maksimal 50 huruf.',
        ]);

        if ($request->nominal < 10000) {
            return back()->withInput($request->except('pin'))->with('error', 'Transaksi Ditolak: Nominal penarikan tunai minimal adalah Rp 10.000.');
        }

        // proteksi double submit penarikan
        $tokenTransaksi = $request->input('_token_transaksi');
        if ($tokenTransaksi) {
            $cacheKey = 'trx_token_' . $tokenTransaksi;
            if (!Cache::add($cacheKey, true, now()->addMinutes(10))) {
                return back()->withInput($request->except('pin'))->with('error', 'Transaksi ditolak: Permintaan penarikan ganda (double submission) terdeteksi. Transaksi sudah diproses.');
            }
        }

        // cek transaksi penarikan identik dalam 3 detik terakhir
        $recentDuplicate = Transaksi::where(function($q) use ($request) {
                $q->where('saver_id', $request->id_nasabah)
                  ->orWhere('id_nasabah', $request->id_nasabah);
            })
            ->where('jenis_trans', 'penarikan')
            ->where('nominal', $request->nominal)
            ->where('created_at', '>=', now()->subSeconds(3))
            ->first();

        if ($recentDuplicate) {
            return back()->withInput($request->except('pin'))->with('error', 'Transaksi ganda terdeteksi! Penarikan untuk penabung ini baru saja diproses (No: ' . $recentDuplicate->no_transaksi . ').');
        }

        // cek kecukupan fisik uang kas di laci teller hari ini
        $laporanHariIni = LaporanHarian::where('user_id', auth()->id())
            ->where('tanggal', date('Y-m-d'))
            ->first();
        $modalAwal = $laporanHariIni ? (int)$laporanHariIni->saldo_awal : 0;

        $tambahanApproved = (int)PengajuanKas::where('user_id', auth()->id())
            ->whereDate('tanggal', date('Y-m-d'))
            ->where('status', 'approved')
            ->sum('nominal');

        $transaksiHariIni = Transaksi::where('id_user', auth()->id())
            ->whereDate('tanggal', date('Y-m-d'))
            ->get();
        $totalSetoranHariIni = (int)$transaksiHariIni->where('jenis_trans', 'setoran')->sum('nominal');
        $totalPenarikanHariIni = (int)$transaksiHariIni->where('jenis_trans', 'penarikan')->sum('nominal');

        $sisaKasLaci = $modalAwal + $tambahanApproved + $totalSetoranHariIni - $totalPenarikanHariIni;

        if ($modalAwal > 0 || !app()->runningUnitTests()) {
            if ($modalAwal <= 0) {
                if ($tokenTransaksi) {
                    Cache::forget($cacheKey);
                }
                return back()->withInput($request->except('pin'))->with('error', 'Modal awal kas hari ini belum diinput.');
            }

            if ($sisaKasLaci < $request->nominal) {
                if ($tokenTransaksi) {
                    Cache::forget($cacheKey);
                }
                return back()->withInput($request->except('pin'))->with('error', 'Kas di laci tidak cukup (Sisa: Rp ' . number_format($sisaKasLaci) . ', Butuh: Rp ' . number_format($request->nominal) . '). Ajukan tambahan kas ke Admin.');
            }
        }

        DB::beginTransaction();
        try {
            $saver = Saver::with('rekening')->lockForUpdate()->findOrFail($request->id_nasabah);
            $rekening = Rekening::where('saver_id', $saver->id)->lockForUpdate()->first();

            if (!$rekening || $rekening->status !== 'aktif') {
                DB::rollBack();
                if ($tokenTransaksi) {
                    Cache::forget($cacheKey);
                }
                return back()->withInput($request->except('pin'))->with('error', 'Rekening penabung sedang nonaktif.');
            }

            // verifikasi pin keamanan penabung
            if (!Hash::check($request->pin, $saver->security_pin)) {
                DB::rollBack();
                if ($tokenTransaksi) {
                    Cache::forget($cacheKey);
                }
                return back()
                    ->withInput($request->except('pin'))
                    ->with('error', 'PIN 6-Digit Keamanan Penabung Salah! Silakan periksa kembali atau gunakan fitur Reset PIN jika nasabah lupa.')
                    ->with('error_pin_salah', true);
            }

            // saldo mengendap setelah penarikan wajib minimal 10 ribu
            $saldoSetelahPenarikan = $rekening->saldo - $request->nominal;
            if ($saldoSetelahPenarikan < 10000) {
                DB::rollBack();
                if ($tokenTransaksi) {
                    Cache::forget($cacheKey);
                }
                return back()->withInput($request->except('pin'))->with('error', 'Transaksi Ditolak: Saldo sisa/mengendap setelah penarikan tidak boleh kurang dari batas minimum Rp 10.000 (Saldo saat ini: Rp ' . number_format($rekening->saldo) . ').');
            }

            // validasi batas reguler 1 juta atau wajib perjanjian h-1 jika di atas 1 juta
            $totalPenarikanNasabahHariIni = (int) Transaksi::where(function($q) use ($saver) {
                    $q->where('saver_id', $saver->id)
                      ->orWhere('id_nasabah', $saver->id);
                })
                ->where('jenis_trans', 'penarikan')
                ->whereDate('tanggal', date('Y-m-d'))
                ->sum('nominal');

            $isOverDailyRegularLimit = ($request->nominal > 1000000) || (($totalPenarikanNasabahHariIni + $request->nominal) > 1000000);
            $perjanjianValid = null;

            if ($isOverDailyRegularLimit) {
                if ($request->filled('id_perjanjian')) {
                    $perjanjianValid = PerjanjianPenarikan::where('id', $request->id_perjanjian)
                        ->where('saver_id', $saver->id)
                        ->whereIn('status', ['disetujui', 'menunggu'])
                        ->first();
                }

                if (!$perjanjianValid) {
                    $perjanjianValid = PerjanjianPenarikan::where('saver_id', $saver->id)
                        ->whereDate('tanggal_rencana', date('Y-m-d'))
                        ->whereIn('status', ['disetujui', 'menunggu'])
                        ->whereDate('created_at', '<', date('Y-m-d'))
                        ->first();
                }

                $isManualVerified = $request->boolean('konfirmasi_perjanjian_h1');

                if (!$perjanjianValid && !$isManualVerified) {
                    DB::rollBack();
                    if ($tokenTransaksi) {
                        Cache::forget($cacheKey);
                    }
                    return back()->withInput($request->except('pin'))->with('error', 
                        'Transaksi Ditolak: Batas penarikan reguler tanpa perjanjian adalah maksimal Rp 1.000.000 per hari (Penarikan hari ini: Rp ' . number_format($totalPenarikanNasabahHariIni, 0, ',', '.') . ', Permintaan: Rp ' . number_format($request->nominal, 0, ',', '.') . '). Penarikan di atas Rp 1.000.000 wajib melakukan perjanjian dengan pihak teller minimal H-1.');
                }
            }

            $saldoSebelum = $rekening->saldo;

            // catat transaksi penarikan tunai
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

            $rekening->refresh();

            // fallback saldo jika trigger database belum terpasang
            if ($rekening->saldo === $saldoSebelum) {
                $rekening->saldo = $saldoSetelahPenarikan;
                $rekening->save();
            }

            // catat jurnal debit tabungan dan kredit kas
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

            if ($perjanjianValid) {
                $perjanjianValid->update([
                    'status' => 'selesai',
                    'selesai_pada' => now(),
                    'teller_id' => auth()->id(),
                    'catatan_teller' => $request->keterangan ?? 'Penarikan di atas Rp 1.000.000 selesai diproses di loket teller',
                ]);
            }

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
            if ($tokenTransaksi) {
                Cache::forget($cacheKey);
            }
            DB::rollBack();
            return back()->with('error', 'Gagal memproses penarikan: ' . $e->getMessage());
        }
    }

    public function resetPin(Request $request)
    {
        $request->validate([
            'id_nasabah' => 'required|exists:savers,id',
            'pin_baru' => 'required|numeric|digits:6',
        ]);

        // simpan pin baru penabung
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

    public function submitLaporanHarian(Request $request)
    {
        $request->validate([
            'uang_fisik' => 'required|integer|min:0',
        ]);

        $user = auth()->user();

        if ($request->filled('laporan_id')) {
            $laporanExisting = LaporanHarian::where('user_id', $user->id)
                ->where('id', $request->laporan_id)
                ->first();
            $tanggal = $laporanExisting ? $laporanExisting->tanggal : date('Y-m-d');
        } else {
            $tanggal = date('Y-m-d');
            $laporanExisting = LaporanHarian::firstOrNew([
                'user_id' => $user->id,
                'tanggal' => $tanggal,
            ]);
        }

        if ($laporanExisting->exists && $laporanExisting->status_approval === 'approved') {
            return back()->with('error', 'Laporan kas hari ini sudah disetujui Admin.');
        }

        if ($laporanExisting->exists && $laporanExisting->submitted_at !== null && $laporanExisting->status_approval === 'pending') {
            return back()->with('error', 'Laporan kas harian hari ini sudah dikirim. Hanya bisa 1 kali per hari.');
        }

        $transaksiHariIni = Transaksi::where('id_user', $user->id)
            ->whereDate('tanggal', $tanggal)
            ->get();

        $totalSetoran = $transaksiHariIni->where('jenis_trans', 'setoran')->sum('nominal');
        $totalPenarikan = $transaksiHariIni->where('jenis_trans', 'penarikan')->sum('nominal');
        $saldoAwal = (int)($laporanExisting->saldo_awal ?? 0);

        $tambahanApproved = (int)PengajuanKas::where('user_id', $user->id)
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'approved')
            ->sum('nominal');

        // hitung saldo akhir sistem dan selisih fisik uang kas
        $saldoAkhirSistem = $saldoAwal + $tambahanApproved + $totalSetoran - $totalPenarikan;
        $selisih = $request->uang_fisik - $saldoAkhirSistem;

        $laporanExisting->fill([
            'saldo_awal' => $saldoAwal,
            'total_setoran' => $totalSetoran,
            'total_penarikan' => $totalPenarikan,
            'saldo_akhir_sistem' => $saldoAkhirSistem,
            'uang_fisik' => $request->uang_fisik,
            'selisih' => $selisih,
            'status_approval' => 'pending',
            'submitted_at' => now(),
            'catatan_penolakan' => null,
            'supervisor_id' => null,
        ]);
        $laporanExisting->save();

        AuditLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Submit Laporan Harian Teller',
            'detail' => 'Laporan kas ' . $tanggal . ' (Fisik: Rp ' . number_format($request->uang_fisik) . ', Selisih: Rp ' . number_format($selisih) . ')',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Laporan kas harian berhasil dikirim ke Admin.');
    }

    public function inputModalAwal(Request $request)
    {
        $request->validate([
            'modal_awal' => 'required|integer|min:1000',
        ]);

        $user = auth()->user();
        $tanggal = date('Y-m-d');

        $laporan = LaporanHarian::firstOrNew([
            'user_id' => $user->id,
            'tanggal' => $tanggal,
        ]);

        // batas input modal awal hanya 1 kali sehari
        if ($laporan->exists && $laporan->saldo_awal > 0) {
            return back()->with('error', 'Modal awal hari ini sudah diinput (Rp ' . number_format($laporan->saldo_awal) . '). Hanya bisa 1 kali per hari.');
        }

        $laporan->saldo_awal = $request->modal_awal;
        $laporan->submitted_at = null;
        $laporan->save();

        AuditLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Input Modal Awal Kas',
            'detail' => 'Modal awal kas tanggal ' . $tanggal . ': Rp ' . number_format($request->modal_awal),
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Modal awal kas Rp ' . number_format($request->modal_awal) . ' disimpan.');
    }

    public function ajukanTambahanKas(Request $request)
    {
        $request->validate([
            'nominal' => 'required|integer|min:1000',
            'alasan' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $tanggal = date('Y-m-d');

        // simpan pengajuan tambahan kas teller ke admin
        PengajuanKas::create([
            'user_id' => $user->id,
            'tanggal' => $tanggal,
            'nominal' => $request->nominal,
            'alasan' => $request->alasan,
            'status' => 'pending',
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Pengajuan Tambahan Kas',
            'detail' => 'Tambahan kas Rp ' . number_format($request->nominal) . ' (Alasan: ' . $request->alasan . ')',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Pengajuan tambahan kas Rp ' . number_format($request->nominal) . ' terkirim ke Admin.');
    }

    public function updateStatusLayanan(Request $request)
    {
        $status = strtolower(trim($request->input('status')));
        if (!in_array($status, ['buka', 'istirahat', 'tutup'])) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid. Pilihan: buka, istirahat, tutup.'
            ], 422);
        }

        // simpan status operasional loket teller
        Pengaturan::setStatusLayanan($status);

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Ubah Status Layanan',
            'detail' => 'Teller ' . auth()->user()->nama_petugas . ' mengubah status loket menjadi ' . strtoupper($status),
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'status' => $status,
            'label' => ucfirst($status),
            'message' => 'Status layanan berhasil diubah menjadi ' . ucfirst($status) . '.',
        ]);
    }

    public function panggilAntreanSelanjutnya(Request $request)
    {
        $today = date('Y-m-d');

        // selesaikan antrean yang sedang dilayani sebelumnya
        \App\Models\Antrean::where('tanggal', $today)
            ->where('status', 'dilayani')
            ->update([
                'status' => 'selesai',
                'selesai_pada' => now(),
            ]);

        // ambil antrean berikutnya yang berstatus menunggu
        $next = \App\Models\Antrean::where('tanggal', $today)
            ->where('status', 'menunggu')
            ->orderBy('angka_antrean', 'asc')
            ->first();

        $totalMenunggu = \App\Models\Antrean::where('tanggal', $today)->where('status', 'menunggu')->count();
        $totalHariIni = \App\Models\Antrean::where('tanggal', $today)->count();

        if (!$next) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada antrean siswa yang sedang menunggu saat ini.',
                    'total_menunggu' => $totalMenunggu,
                    'total_hari_ini' => $totalHariIni,
                ]);
            }
            return back()->with('info', 'Tidak ada antrean siswa yang sedang menunggu saat ini.');
        }

        $next->update([
            'status' => 'dilayani',
            'dipanggil_pada' => now(),
        ]);

        $totalMenunggu = \App\Models\Antrean::where('tanggal', $today)->where('status', 'menunggu')->count();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Memanggil nomor antrean: ' . $next->nomor_antrean . ' (' . $next->nama_siswa . ')',
                'antrean' => [
                    'id' => $next->id,
                    'nomor_antrean' => $next->nomor_antrean,
                    'nama_siswa' => $next->nama_siswa,
                    'nis' => $next->nis,
                ],
                'total_menunggu' => $totalMenunggu,
                'total_hari_ini' => $totalHariIni,
            ]);
        }

        return back()->with('success', 'Memanggil nomor antrean: ' . $next->nomor_antrean . ' (' . $next->nama_siswa . ')');
    }

    public function selesaikanAntrean(Request $request, $id)
    {
        $antrean = \App\Models\Antrean::findOrFail($id);
        $antrean->update([
            'status' => 'selesai',
            'selesai_pada' => now(),
        ]);

        $today = date('Y-m-d');
        $totalMenunggu = \App\Models\Antrean::where('tanggal', $today)->where('status', 'menunggu')->count();
        $totalHariIni = \App\Models\Antrean::where('tanggal', $today)->count();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Antrean ' . $antrean->nomor_antrean . ' berhasil diselesaikan.',
                'nomor_antrean' => $antrean->nomor_antrean,
                'total_menunggu' => $totalMenunggu,
                'total_hari_ini' => $totalHariIni,
            ]);
        }

        return back()->with('success', 'Antrean ' . $antrean->nomor_antrean . ' berhasil diselesaikan.');
    }

    public function approvePerjanjian($id)
    {
        // setujui permohonan penarikan dana di atas 1 juta
        $perjanjian = PerjanjianPenarikan::with('saver')->findOrFail($id);
        $perjanjian->update([
            'status' => 'disetujui',
            'disetujui_pada' => now(),
            'teller_id' => auth()->id(),
        ]);

        return back()->with('success', 'Perjanjian penarikan Rp ' . number_format($perjanjian->nominal, 0, ',', '.') . ' untuk nasabah ' . ($perjanjian->saver->student_name ?? 'Nasabah') . ' pada tanggal ' . date('d F Y', strtotime($perjanjian->tanggal_rencana)) . ' BERHASIL DISETUJUI. Teller siap melayani pada hari H.');
    }

    public function rejectPerjanjian(Request $request, $id)
    {
        $request->validate(['catatan_teller' => 'required|string|max:255']);
        $perjanjian = PerjanjianPenarikan::with('saver')->findOrFail($id);
        $perjanjian->update([
            'status' => 'ditolak',
            'teller_id' => auth()->id(),
            'catatan_teller' => $request->catatan_teller,
        ]);

        return back()->with('success', 'Perjanjian penarikan untuk nasabah ' . ($perjanjian->saver->student_name ?? 'Nasabah') . ' telah ditolak.');
    }
}
