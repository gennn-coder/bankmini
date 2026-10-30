<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Member;
use App\Models\Saver;
use App\Models\Rekening;
use App\Models\Transaksi;
use App\Models\LaporanHarian;
use App\Models\AuditLog;
use App\Models\JurnalAkuntansi;
use App\Models\PengajuanKas;

class DashboardController extends Controller
{
    public function admin(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'administrator' && $user->role !== 'supervisor') {
            return redirect('/');
        }

        // Tab Manajemen User (Daftar Pengguna Sistem):
        // Hanya menampilkan Petugas (Admin, Teller, Supervisor) dan Nasabah yang SUDAH AKTIF (rekening aktif / sudah pernah menabung).
        // Nasabah yang belum pernah ada transaksi menabung tetap berada di Data Anggota Siswa dengan status tidak aktif.
        $users = User::where(function ($query) {
            $query->whereIn('role', ['administrator', 'teller', 'supervisor'])
                  ->orWhere(function ($q) {
                      $q->whereIn('role', ['nasabah', 'saver'])
                        ->whereHas('member', function ($m) {
                            $m->where('status', 'rekening aktif');
                        });
                  });
        })
        ->orderByRaw("
            CASE 
                WHEN role = 'administrator' THEN 1
                WHEN role = 'teller' THEN 2
                WHEN role = 'supervisor' THEN 3
                WHEN role IN ('nasabah', 'saver') THEN 4
                ELSE 5
            END ASC
        ")
        ->orderBy('created_at', 'asc')
        ->orderBy('id', 'asc')
        ->get();

        $totalUsers = $users->count();
        $totalMembers = Member::count();
        $totalBelumAktif = Member::where('status', '!=', 'rekening aktif')->count();
        $totalSavers = Saver::count();
        $totalRekening = Rekening::count();
        $totalSaldo = Rekening::sum('saldo');

        $filterMember = $request->query('filter_member', 'belum_aktif');
        if ($filterMember === 'semua') {
            $members = Member::orderBy('created_at', 'desc')->get();
        } else {
            // Default: hanya menampilkan anggota siswa yang statusnya BELUM AKTIF
            $members = Member::where('status', '!=', 'rekening aktif')->orderBy('created_at', 'desc')->get();
        }

        $savers = Saver::with('rekening')->orderBy('created_at', 'desc')->get();

        // Fitur Supervisor yang dipindah ke Admin
        $laporanPending = LaporanHarian::whereNotNull('submitted_at')
            ->where('status_approval', 'pending')
            ->with(['teller' => fn($q) => $q->withTrashed()])
            ->orderBy('tanggal', 'desc')
            ->get();

        $laporanApproved = LaporanHarian::where('status_approval', 'approved')
            ->with([
                'teller' => fn($q) => $q->withTrashed(),
                'supervisor' => fn($q) => $q->withTrashed()
            ])
            ->orderBy('tanggal', 'desc')
            ->take(30)
            ->get();

        // Fitur Laporan Bulanan
        $selectedMonth = (int) $request->query('month', date('n'));
        $selectedYear = (int) $request->query('year', date('Y'));

        $monthlyTransactions = Transaksi::with([
            'saver' => fn($q) => $q->withTrashed(),
            'teller' => fn($q) => $q->withTrashed()
        ])
        ->whereYear('tanggal', $selectedYear)
        ->whereMonth('tanggal', $selectedMonth)
        ->orderBy('tanggal', 'desc')
        ->get();

        $monthlySetoran = $monthlyTransactions->where('jenis_trans', 'setoran')->sum('nominal');
        $monthlyPenarikan = $monthlyTransactions->where('jenis_trans', 'penarikan')->sum('nominal');

        $auditLogs = AuditLog::with(['user' => fn($q) => $q->withTrashed()])
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get();

        $jurnals = JurnalAkuntansi::with('transaksi', 'akun')
            ->orderBy('id', 'desc')
            ->take(30)
            ->get();

        $pengajuanKasList = PengajuanKas::with(['teller' => fn($q) => $q->withTrashed()])
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get();

        return view('admin.dashboard', compact(
            'user', 'totalUsers', 'totalMembers', 'totalBelumAktif', 'filterMember', 'totalSavers', 'totalRekening', 'totalSaldo',
            'users', 'members', 'savers', 'laporanPending', 'laporanApproved',
            'selectedMonth', 'selectedYear', 'monthlyTransactions', 'monthlySetoran', 'monthlyPenarikan',
            'auditLogs', 'jurnals', 'pengajuanKasList'
        ));
    }

    public function teller(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'teller') {
            return redirect('/');
        }

        $transaksiHariIni = Transaksi::with(['saver' => fn($q) => $q->withTrashed()])
            ->where('id_user', $user->id)
            ->whereDate('tanggal', date('Y-m-d'))
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalSetoran = $transaksiHariIni->where('jenis_trans', 'setoran')->sum('nominal');
        $totalPenarikan = $transaksiHariIni->where('jenis_trans', 'penarikan')->sum('nominal');

        $savers = Saver::with('rekening')->orderBy('student_name', 'asc')->get();
        $membersUnactivated = Member::where('status', 'rekening tidak aktif')->orderBy('student_name', 'asc')->get();

        $laporanHariIni = LaporanHarian::where('user_id', $user->id)
            ->where('tanggal', date('Y-m-d'))
            ->first();

        // Data Modal Awal & Pengajuan Tambahan Kas Hari Ini
        $modalAwal = $laporanHariIni ? (int)$laporanHariIni->saldo_awal : 0;
        $isModalAwalSet = $laporanHariIni && $laporanHariIni->saldo_awal > 0;

        $tambahanKasHariIni = PengajuanKas::where('user_id', $user->id)
            ->whereDate('tanggal', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->get();

        $tambahanKasApproved = (int)$tambahanKasHariIni->where('status', 'approved')->sum('nominal');

        $isLaporanSubmitted = $laporanHariIni && $laporanHariIni->submitted_at !== null;
        $isLaporanApproved = $laporanHariIni && $laporanHariIni->status_approval === 'approved';

        if ($isLaporanApproved) {
            // Setelah laporan di-ACC admin, kas fisik laci telah diserahkan / dipindahkan ke Admin
            $sisaKasLaci = 0;
        } else {
            $sisaKasLaci = $modalAwal + $tambahanKasApproved + $totalSetoran - $totalPenarikan;
        }

        // Laporan Bulanan untuk Teller
        $selectedMonth = (int) $request->query('month', date('n'));
        $selectedYear = (int) $request->query('year', date('Y'));

        $monthlyTransactions = Transaksi::with(['saver' => fn($q) => $q->withTrashed()])
            ->where('id_user', $user->id)
            ->whereYear('tanggal', $selectedYear)
            ->whereMonth('tanggal', $selectedMonth)
            ->orderBy('tanggal', 'desc')
            ->get();

        $monthlySetoran = $monthlyTransactions->where('jenis_trans', 'setoran')->sum('nominal');
        $monthlyPenarikan = $monthlyTransactions->where('jenis_trans', 'penarikan')->sum('nominal');

        return view('teller.dashboard', compact(
            'user', 'transaksiHariIni', 'totalSetoran', 'totalPenarikan',
            'savers', 'membersUnactivated', 'laporanHariIni',
            'modalAwal', 'isModalAwalSet', 'tambahanKasHariIni', 'tambahanKasApproved', 'sisaKasLaci',
            'isLaporanSubmitted', 'isLaporanApproved',
            'selectedMonth', 'selectedYear', 'monthlyTransactions', 'monthlySetoran', 'monthlyPenarikan'
        ));
    }

    public function supervisor()
    {
        // Fitur Supervisor sudah dijadikan satu dengan Administrator
        return redirect()->route('admin.dashboard');
    }

    public function nasabah()
    {
        $user = Auth::user();
        if ($user->role !== 'nasabah' && $user->role !== 'saver') {
            return redirect('/');
        }

        $saver = Saver::where('user_id', $user->id)->with(['rekening', 'member'])->first();
        $nasabah = $saver;
        $member = Member::where('user_id', $user->id)->first();
        $transaksi = $saver
            ? Transaksi::where('saver_id', $saver->id)->orderBy('tanggal', 'desc')->get()
            : collect();

        return view('nasabah.dashboard', compact('user', 'saver', 'nasabah', 'member', 'transaksi'));
    }
}
