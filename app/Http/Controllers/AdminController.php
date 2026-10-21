<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Member;
use App\Models\Saver;
use App\Models\Rekening;
use App\Models\Transaksi;
use App\Models\LaporanHarian;
use App\Models\AuditLog;
use App\Models\JurnalAkuntansi;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    // ==========================================
    // 1. MANAJEMEN PENGGUNA (USERS)
    // ==========================================
    public function storeUser(Request $request)
    {
        // Admin sistem bersifat tunggal (1 akun utama), tidak dapat ditambah lagi
        if ($request->role === 'administrator') {
            return back()->with('error', 'Admin sistem hanya diperbolehkan 1 akun utama. Form ini khusus untuk menambah akun Teller.');
        }

        $request->validate([
            'username' => 'required|string|unique:users,username',
            'nama_petugas' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:teller',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'username' => $request->username,
            'nama_petugas' => $request->nama_petugas,
            'email' => $request->email,
            'role' => 'teller',
            'password' => Hash::make($request->password),
            'status' => 'aktif',
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Tambah User Petugas',
            'detail' => 'Membuat user teller ' . $user->username . ' (' . $user->nama_petugas . ') dengan email ' . $user->email,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Petugas Teller ' . $user->nama_petugas . ' (' . $user->email . ') berhasil ditambahkan.');
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id() || $user->role === 'administrator') {
            return back()->with('error', 'Tidak dapat memblokir akun Administrator utama.');
        }

        $user->status = $user->status === 'aktif' ? 'terblokir' : 'aktif';
        $user->save();

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Ubah Status User',
            'detail' => 'Mengubah status user ' . $user->username . ' menjadi ' . $user->status,
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Status user ' . $user->username . ' berhasil diubah menjadi ' . $user->status);
    }

    // ==========================================
    // 2. CRUD TABEL ANGGOTA / MEMBER
    // ==========================================
    public function storeMember(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string',
            'nis' => 'required|string|unique:members,nis|unique:users,username',
            'class_grade' => 'required|string',
            'email' => 'required|email',
            'graduation_year' => 'required|string',
            'password' => 'required|string|min:6',
            'pin' => 'required|numeric|digits:6',
        ], [
            'password.required' => 'Password akun login siswa wajib diisi (minimal 6 karakter).',
            'password.min' => 'Password akun login siswa minimal 6 karakter.',
            'pin.required' => 'PIN keamanan transaksi penarikan wajib diisi.',
            'pin.digits' => 'PIN keamanan transaksi penarikan harus tepat 6 digit angka (tidak boleh kurang atau lebih).',
            'pin.numeric' => 'PIN transaksi penarikan harus berupa 6 angka digit.',
        ]);

        // Buat Akun Login untuk Siswa / Anggota
        $user = User::create([
            'username' => $request->nis,
            'nama_petugas' => $request->student_name,
            'email' => $request->email,
            'role' => 'nasabah',
            'password' => Hash::make($request->password),
            'status' => 'aktif',
        ]);

        // Buat Data Anggota (Status awal: rekening tidak aktif sampai aktivasi oleh teller)
        $member = Member::create([
            'user_id' => $user->id,
            'student_name' => $request->student_name,
            'nis' => $request->nis,
            'class_grade' => $request->class_grade,
            'email' => $request->email,
            'pin' => Hash::make($request->pin ?? '123456'),
            'graduation_year' => $request->graduation_year,
            'status' => 'rekening tidak aktif',
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Tambah Anggota Baru',
            'detail' => 'Menambahkan data siswa ' . $member->student_name . ' (NIS: ' . $member->nis . ', Email: ' . $member->email . ')',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Anggota ' . $member->student_name . ' (' . $member->email . ') berhasil ditambahkan dengan status: Rekening Tidak Aktif. Akun login NIS: ' . $member->nis);
    }

    public function updateMember(Request $request, $userId)
    {
        $request->validate([
            'student_name' => 'required|string',
            'nis' => 'required|string|unique:members,nis,' . $userId . ',user_id',
            'class_grade' => 'required|string',
            'email' => 'nullable|email',
            'graduation_year' => 'required|string',
            'status' => 'required|in:rekening aktif,rekening tidak aktif',
        ]);

        $member = Member::where('user_id', $userId)->firstOrFail();
        $member->update([
            'student_name' => $request->student_name,
            'nis' => $request->nis,
            'class_grade' => $request->class_grade,
            'email' => $request->email ?? $member->email,
            'graduation_year' => $request->graduation_year,
            'status' => $request->status,
        ]);

        // Sinkronkan nama dan email di akun user
        if ($member->user) {
            $member->user->update([
                'nama_petugas' => $request->student_name,
                'username' => $request->nis,
                'email' => $request->email ?? $member->user->email,
            ]);
        }

        // Jika status diubah menjadi "rekening aktif", pastikan Saver dan Rekening otomatis dibuat atau dipulihkan
        if ($request->status === 'rekening aktif') {
            $saver = Saver::withTrashed()->where('user_id', $member->user_id)->orWhere('nis', $member->nis)->first();
            if (!$saver) {
                $accountNumber = Saver::generateNomorRekening();
                $saver = Saver::create([
                    'user_id' => $member->user_id,
                    'account_number' => $accountNumber,
                    'nis' => $member->nis,
                    'student_name' => $member->student_name,
                    'class_grade' => $member->class_grade,
                    'email' => $member->email,
                    'security_pin' => Hash::make('123456'),
                ]);

                Rekening::create([
                    'saver_id' => $saver->id,
                    'id_nasabah' => $saver->id,
                    'account_number' => $accountNumber,
                    'no_rekening' => $accountNumber,
                    'saldo' => 0,
                    'status' => 'aktif',
                ]);
            } else {
                if ($saver->trashed()) {
                    $saver->restore();
                }
                $saver->update([
                    'student_name' => $request->student_name,
                    'nis' => $request->nis,
                    'class_grade' => $request->class_grade,
                    'email' => $request->email ?? $saver->email,
                ]);
                $rekening = Rekening::withTrashed()->where('saver_id', $saver->id)->first();
                if ($rekening) {
                    if ($rekening->trashed()) {
                        $rekening->restore();
                    }
                    $rekening->update(['status' => 'aktif']);
                } else {
                    $accountNumber = $saver->account_number ?: Saver::generateNomorRekening();
                    Rekening::create([
                        'saver_id' => $saver->id,
                        'id_nasabah' => $saver->id,
                        'account_number' => $accountNumber,
                        'no_rekening' => $accountNumber,
                        'saldo' => 0,
                        'status' => 'aktif',
                    ]);
                }
            }
        } else {
            // Jika status rekening tidak aktif, ubah status rekening menjadi nonaktif
            if ($member->saver && $member->saver->rekening) {
                $member->saver->rekening->update(['status' => 'nonaktif']);
            }
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Update Data Anggota',
            'detail' => 'Memperbarui data anggota ' . $member->student_name . ' (NIS: ' . $member->nis . ', Status: ' . $member->status . ')',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Data anggota ' . $member->student_name . ' berhasil diperbarui.');
    }

    public function destroyMember($userId)
    {
        $member = Member::where('user_id', $userId)->firstOrFail();
        $nama = $member->student_name;

        // Soft delete member
        $member->delete();

        // Soft delete saver & rekening jika ada
        if ($member->saver) {
            if ($member->saver->rekening) {
                $member->saver->rekening->delete();
            }
            $member->saver->delete();
        }

        // Soft delete user jika ada
        if ($member->user) {
            $member->user->delete();
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Hapus Anggota',
            'detail' => 'Menghapus anggota ' . $nama,
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Data anggota ' . $nama . ' berhasil dihapus.');
    }

    // ==========================================
    // 3. AKSI TABEL PENABUNG / SAVER
    // ==========================================
    public function resetPinSaver(Request $request, $id)
    {
        $request->validate([
            'pin' => 'required|numeric|digits:6',
        ]);

        $saver = Saver::findOrFail($id);
        $saver->security_pin = Hash::make($request->pin);
        $saver->save();

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Reset PIN Penabung',
            'detail' => 'Mereset PIN 6 digit untuk penabung ' . $saver->student_name . ' (No Rek: ' . $saver->account_number . ')',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'PIN 6-Digit penabung ' . $saver->student_name . ' berhasil direset.');
    }

    public function destroySaver($id)
    {
        $saver = Saver::findOrFail($id);
        $nama = $saver->student_name;

        // Nonaktifkan rekening jika ada
        if ($saver->rekening) {
            $saver->rekening->update(['status' => 'nonaktif']);
        }

        // Update status di member jika ada
        if ($saver->member) {
            $saver->member->update(['status' => 'rekening tidak aktif']);
        }

        // Soft delete saver (transaksi tetap aman dan tidak akan hilang!)
        $saver->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Hapus Penabung',
            'detail' => 'Menonaktifkan dan menghapus data penabung ' . $nama . '. Catatan transaksi tetap tersimpan utuh untuk rekonsiliasi laporan kas.',
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Akun penabung ' . $nama . ' berhasil dinonaktifkan/dihapus. Data histori transaksi tetap aman tersimpan.');
    }

    // ==========================================
    // 4. FITUR SUPERVISOR (SEKARANG MENYATU DI ADMIN)
    // ==========================================
    public function approveLaporan($id)
    {
        $laporan = LaporanHarian::findOrFail($id);

        if ($laporan->selisih != 0) {
            return back()->with('error', 'Peringatan: Terdapat selisih kas fisik sebesar Rp ' . number_format($laporan->selisih) . '. Laporan memerlukan penanganan fisik kas sebelum disetujui.');
        }

        $laporan->status_approval = 'approved';
        $laporan->supervisor_id = auth()->id();
        $laporan->save();

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Approval Tutup Kas Teller',
            'detail' => 'Administrator menyetujui laporan penutupan kas Teller ID ' . $laporan->user_id . ' tanggal ' . $laporan->tanggal,
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Laporan penutupan kas teller tanggal ' . $laporan->tanggal . ' berhasil disetujui (Approved). Uang kas dipindahkan ke brankas utama.');
    }

    public function rejectLaporan(Request $request, $id)
    {
        $request->validate([
            'catatan_penolakan' => 'required|string|max:500',
        ]);

        $laporan = LaporanHarian::findOrFail($id);
        $laporan->status_approval = 'rejected';
        $laporan->catatan_penolakan = $request->catatan_penolakan;
        $laporan->supervisor_id = auth()->id();
        $laporan->save();

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Penolakan Laporan Tutup Kas',
            'detail' => 'Administrator menolak laporan harian Teller ID ' . $laporan->user_id . ' tanggal ' . $laporan->tanggal . ' (Alasan: ' . $request->catatan_penolakan . ')',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Laporan harian ditolak dengan catatan: "' . $request->catatan_penolakan . '". Catatan penolakan otomatis masuk ke dashboard Teller.');
    }

    // ==========================================
    // 5. EXPORT LAPORAN KE PDF
    // ==========================================
    public function downloadLaporanHarianPdf($id)
    {
        $laporan = LaporanHarian::with([
            'teller' => fn($q) => $q->withTrashed(),
            'supervisor' => fn($q) => $q->withTrashed(),
        ])->findOrFail($id);

        // Ambil semua transaksi hari tersebut dengan saver yang soft-deleted
        $transaksis = Transaksi::with(['saver' => function ($query) {
            $query->withTrashed();
        }])
        ->where('id_user', $laporan->user_id)
        ->whereDate('tanggal', $laporan->tanggal)
        ->orderBy('tanggal', 'asc')
        ->get();

        $pdf = Pdf::loadView('reports.laporan_harian_pdf', compact('laporan', 'transaksis'));
        return $pdf->download('Laporan_Harian_Kas_' . $laporan->tanggal . '.pdf');
    }

    public function downloadLaporanBulananPdf(Request $request)
    {
        $month = $request->query('month', date('n'));
        $year = $request->query('year', date('Y'));

        $namaBulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $namaBulan = $namaBulanList[(int)$month] ?? 'Bulan ' . $month;

        $transaksis = Transaksi::with([
            'saver' => fn($q) => $q->withTrashed(),
            'teller' => fn($q) => $q->withTrashed(),
        ])
        ->whereYear('tanggal', $year)
        ->whereMonth('tanggal', $month)
        ->orderBy('tanggal', 'asc')
        ->get();

        $totalSetoran = $transaksis->where('jenis_trans', 'setoran')->sum('nominal');
        $totalPenarikan = $transaksis->where('jenis_trans', 'penarikan')->sum('nominal');

        $pdf = Pdf::loadView('reports.laporan_bulanan_pdf', compact(
            'transaksis', 'month', 'year', 'namaBulan', 'totalSetoran', 'totalPenarikan'
        ));

        return $pdf->download('Laporan_Bulanan_' . $namaBulan . '_' . $year . '.pdf');
    }
}
