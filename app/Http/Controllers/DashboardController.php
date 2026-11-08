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
use App\Models\Pengaturan;
use App\Models\TargetTabungan;
use App\Models\Antrean;
use App\Models\Pengaduan;
use App\Models\PerjanjianPenarikan;
use Illuminate\Support\Facades\Hash;

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
        $users = User::with(['member', 'saver'])->where(function ($query) {
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
        $members = Member::orderBy('created_at', 'desc')->get();

        $savers = Saver::with(['rekening', 'member'])->orderBy('created_at', 'desc')->get();

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

        $pengaduanList = Pengaduan::with(['user' => fn($q) => $q->withTrashed()])
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        return view('admin.dashboard', compact(
            'user', 'totalUsers', 'totalMembers', 'totalBelumAktif', 'filterMember', 'totalSavers', 'totalRekening', 'totalSaldo',
            'users', 'members', 'savers', 'laporanPending', 'laporanApproved',
            'selectedMonth', 'selectedYear', 'monthlyTransactions', 'monthlySetoran', 'monthlyPenarikan',
            'auditLogs', 'jurnals', 'pengajuanKasList', 'pengaduanList'
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

        $savers = Saver::with([
            'rekening',
            'transaksi' => fn($q) => $q->whereDate('tanggal', date('Y-m-d'))->where('jenis_trans', 'penarikan'),
            'perjanjianPenarikan' => fn($q) => $q->whereDate('tanggal_rencana', date('Y-m-d'))->whereIn('status', ['disetujui', 'menunggu'])
        ])->orderBy('student_name', 'asc')->get();
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

        $statusLayanan = Pengaturan::getStatusLayanan();

        // Antrean Live untuk Teller
        $antreanHariIni = Antrean::where('tanggal', date('Y-m-d'))->orderBy('angka_antrean', 'asc')->get();
        $antreanSedangDilayani = $antreanHariIni->where('status', 'dilayani')->first();
        $totalAntreanMenunggu = $antreanHariIni->where('status', 'menunggu')->count();

        // Perjanjian Penarikan H-1 (> 1 Juta) untuk Teller
        $perjanjianList = PerjanjianPenarikan::with(['saver' => fn($q) => $q->withTrashed()])
            ->orderBy('tanggal_rencana', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        $totalPerjanjianPending = $perjanjianList->where('status', 'menunggu')->count();

        return view('teller.dashboard', compact(
            'user', 'transaksiHariIni', 'totalSetoran', 'totalPenarikan',
            'savers', 'membersUnactivated', 'laporanHariIni',
            'modalAwal', 'isModalAwalSet', 'tambahanKasHariIni', 'tambahanKasApproved', 'sisaKasLaci',
            'isLaporanSubmitted', 'isLaporanApproved',
            'selectedMonth', 'selectedYear', 'monthlyTransactions', 'monthlySetoran', 'monthlyPenarikan',
            'statusLayanan', 'antreanHariIni', 'antreanSedangDilayani', 'totalAntreanMenunggu',
            'perjanjianList', 'totalPerjanjianPending'
        ));
    }

    public function supervisor()
    {
        // Fitur Supervisor sudah dijadikan satu dengan Administrator
        return redirect()->route('admin.dashboard');
    }

    public function nasabah(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'nasabah' && $user->role !== 'saver') {
            return redirect('/');
        }

        $saver = Saver::where('user_id', $user->id)->with(['rekening', 'member'])->first();
        $member = Member::where('user_id', $user->id)->first();

        // Auto-sinkronkan data Saver dari Member jika ada perbedaan nama, kelas, atau NIS (Self-Healing)
        if ($saver && $member) {
            $changed = false;
            if ($saver->student_name !== $member->student_name) {
                $saver->student_name = $member->student_name;
                $changed = true;
            }
            if ($saver->class_grade !== $member->class_grade) {
                $saver->class_grade = $member->class_grade;
                $changed = true;
            }
            if ($saver->nis !== $member->nis) {
                $saver->nis = $member->nis;
                $changed = true;
            }
            if ($member->email && $saver->email !== $member->email) {
                $saver->email = $member->email;
                $changed = true;
            }
            if ($member->status === 'rekening aktif' && $saver->rekening && $saver->rekening->status === 'nonaktif') {
                $saver->rekening->update(['status' => 'aktif']);
            }
            if ($changed) {
                $saver->save();
            }
        }

        $nasabah = $saver;
        $transaksi = $saver
            ? Transaksi::where('saver_id', $saver->id)->orderBy('tanggal', 'desc')->get()
            : collect();
        $statusLayanan = Pengaturan::getStatusLayanan();

        // 1. Target Tabungan (Celengan Impian)
        $targetTabungan = TargetTabungan::where('user_id', $user->id)->first();

        // 2. Antrean Bank Mini Live Hari Ini
        $today = date('Y-m-d');
        $antreanSaya = Antrean::where('tanggal', $today)
            ->where('user_id', $user->id)
            ->whereIn('status', ['menunggu', 'dilayani'])
            ->first();
        $antreanSedangDilayani = Antrean::where('tanggal', $today)->where('status', 'dilayani')->first();
        $totalAntreanMenunggu = Antrean::where('tanggal', $today)->where('status', 'menunggu')->count();
        $antreanSebelumSaya = ($antreanSaya && $antreanSaya->status === 'menunggu')
            ? Antrean::where('tanggal', $today)->where('status', 'menunggu')->where('angka_antrean', '<', $antreanSaya->angka_antrean)->count()
            : 0;

        // 3. Laporan Keuangan Bulanan (Ringkasan Akhir Bulan)
        $selectedBulan = (int) $request->query('bulan', date('n'));
        $selectedTahun = (int) $request->query('tahun', date('Y'));

        $saldoAwalBulan = 0;
        $saldoAkhirBulan = 0;
        $totalSetoranBulanIni = 0;
        $totalPenarikanBulanIni = 0;

        if ($saver) {
            $startOfMonth = \Carbon\Carbon::createFromDate($selectedTahun, $selectedBulan, 1)->startOfMonth();
            $endOfMonth = \Carbon\Carbon::createFromDate($selectedTahun, $selectedBulan, 1)->endOfMonth();

            $trxBulanIni = Transaksi::where('saver_id', $saver->id)
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->get();

            $totalSetoranBulanIni = (int) $trxBulanIni->where('jenis_trans', 'setoran')->sum('nominal');
            $totalPenarikanBulanIni = (int) $trxBulanIni->where('jenis_trans', 'penarikan')->sum('nominal');

            $setoranSebelum = (int) Transaksi::where('saver_id', $saver->id)
                ->where('tanggal', '<', $startOfMonth)
                ->where('jenis_trans', 'setoran')
                ->sum('nominal');
            $penarikanSebelum = (int) Transaksi::where('saver_id', $saver->id)
                ->where('tanggal', '<', $startOfMonth)
                ->where('jenis_trans', 'penarikan')
                ->sum('nominal');

            $saldoAwalBulan = $setoranSebelum - $penarikanSebelum;
            $saldoAkhirBulan = $saldoAwalBulan + ($totalSetoranBulanIni - $totalPenarikanBulanIni);
        }

        // 4. Riwayat Pengaduan Nasabah
        $pengaduanList = Pengaduan::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        // 5. Riwayat Perjanjian Penarikan H-1 (> 1 Juta) Nasabah
        $perjanjianSaya = $saver
            ? PerjanjianPenarikan::where('saver_id', $saver->id)->orderBy('created_at', 'desc')->get()
            : collect();

        return view('nasabah.dashboard', compact(
            'user', 'saver', 'nasabah', 'member', 'transaksi', 'statusLayanan',
            'targetTabungan', 'antreanSaya', 'antreanSedangDilayani', 'totalAntreanMenunggu', 'antreanSebelumSaya',
            'selectedBulan', 'selectedTahun', 'saldoAwalBulan', 'saldoAkhirBulan', 'totalSetoranBulanIni', 'totalPenarikanBulanIni',
            'pengaduanList', 'perjanjianSaya'
        ));
    }

    public function simpanPerjanjianPenarikan(Request $request)
    {
        $user = Auth::user();
        $saver = Saver::where('user_id', $user->id)->with('rekening')->first();
        if (!$saver || !$saver->rekening) {
            return back()->with('error', 'Rekening penabung Anda belum aktif atau tidak ditemukan.');
        }

        $request->validate([
            'nominal' => 'required|integer|min:1000001',
            'tanggal_rencana' => 'required|date|after_or_equal:tomorrow',
            'alasan' => 'required|string|max:255',
        ], [
            'nominal.required' => 'Nominal penarikan wajib diisi.',
            'nominal.min' => 'Perjanjian H-1 khusus untuk penarikan di atas Rp 1.000.000 (minimal Rp 1.000.001). Untuk penarikan Rp 1.000.000 ke bawah, silakan langsung datang ke loket Teller tanpa perlu perjanjian.',
            'tanggal_rencana.required' => 'Tanggal rencana penarikan wajib diisi.',
            'tanggal_rencana.after_or_equal' => 'Tanggal rencana penarikan wajib minimal H-1 (besok atau hari selanjutnya).',
            'alasan.required' => 'Keperluan atau alasan penarikan dana wajib diisi.',
        ]);

        // Cek kecukupan saldo rekening (saldo - nominal >= 10000)
        if (($saver->rekening->saldo - $request->nominal) < 10000) {
            return back()->withInput()->with('error', 'Saldo tabungan tidak mencukupi untuk rencana penarikan ini. Saldo saat ini: Rp ' . number_format($saver->rekening->saldo, 0, ',', '.') . ', wajib menyisakan saldo minimal Rp 10.000 (maksimal yang bisa ditarik: Rp ' . number_format(max(0, $saver->rekening->saldo - 10000), 0, ',', '.') . ').');
        }

        PerjanjianPenarikan::create([
            'saver_id' => $saver->id,
            'nominal' => $request->nominal,
            'tanggal_rencana' => $request->tanggal_rencana,
            'alasan' => $request->alasan,
            'status' => 'menunggu',
            'diajukan_pada' => now(),
        ]);

        return back()->with('success', 'Permohonan perjanjian penarikan Rp ' . number_format($request->nominal, 0, ',', '.') . ' untuk tanggal ' . date('d F Y', strtotime($request->tanggal_rencana)) . ' berhasil diajukan! Pihak teller akan mempersiapkan fisik dana kas di loket.');
    }

    public function simpanTargetTabungan(Request $request)
    {
        $request->validate([
            'judul_target' => 'required|string|max:100',
            'nominal_target' => 'required|integer|min:10000',
            'target_tanggal' => 'nullable|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $saver = Saver::where('user_id', $user->id)->first();

        TargetTabungan::updateOrCreate(
            ['user_id' => $user->id],
            [
                'saver_id' => $saver?->id,
                'judul_target' => $request->judul_target,
                'nominal_target' => $request->nominal_target,
                'target_tanggal' => $request->target_tanggal,
                'keterangan' => $request->keterangan,
                'status' => 'berjalan',
            ]
        );

        return back()->with('success', 'Target tabungan berhasil disimpan.');
    }

    public function hapusTargetTabungan($id)
    {
        $user = Auth::user();
        $target = TargetTabungan::where('user_id', $user->id)->where('id', $id)->firstOrFail();
        $target->delete();

        return back()->with('success', 'Target tabungan berhasil dihapus.');
    }

    public function ambilAntrean(Request $request)
    {
        $user = Auth::user();
        $today = date('Y-m-d');

        $existing = Antrean::where('tanggal', $today)
            ->where('user_id', $user->id)
            ->whereIn('status', ['menunggu', 'dilayani'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memiliki nomor antrean aktif hari ini: ' . $existing->nomor_antrean);
        }

        $next = Antrean::generateNomorHariIni();

        Antrean::create([
            'nomor_antrean' => $next['nomor'],
            'angka_antrean' => $next['angka'],
            'user_id' => $user->id,
            'nama_siswa' => $user->member->student_name ?? ($user->saver->student_name ?? $user->nama_petugas),
            'nis' => $user->member->nis ?? ($user->saver->nis ?? $user->username),
            'tanggal' => $today,
            'status' => 'menunggu',
        ]);

        return back()->with('success', 'Nomor antrean Anda berhasil dibuat: ' . $next['nomor']);
    }

    public function statusAntrean()
    {
        $today = date('Y-m-d');
        $user = Auth::user();

        $antreanSaya = $user ? Antrean::where('tanggal', $today)->where('user_id', $user->id)->whereIn('status', ['menunggu', 'dilayani'])->first() : null;
        $sedangDilayani = Antrean::where('tanggal', $today)->where('status', 'dilayani')->first();
        $totalMenunggu = Antrean::where('tanggal', $today)->where('status', 'menunggu')->count();

        $sisaAntrean = ($antreanSaya && $antreanSaya->status === 'menunggu')
            ? Antrean::where('tanggal', $today)->where('status', 'menunggu')->where('angka_antrean', '<', $antreanSaya->angka_antrean)->count()
            : 0;

        return response()->json([
            'status_layanan' => Pengaturan::getStatusLayanan(),
            'sedang_dilayani' => $sedangDilayani ? $sedangDilayani->nomor_antrean : '-',
            'total_menunggu' => $totalMenunggu,
            'nomor_saya' => $antreanSaya ? $antreanSaya->nomor_antrean : null,
            'status_saya' => $antreanSaya ? $antreanSaya->status : null,
            'sisa_antrean' => $sisaAntrean,
        ]);
    }

    public function kirimPengaduan(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:50',
            'judul' => 'required|string|max:150',
            'isi_pesan' => 'required|string',
        ]);

        $user = Auth::user();

        Pengaduan::create([
            'user_id' => $user->id,
            'nama_pengadu' => $user->member->student_name ?? ($user->saver->student_name ?? $user->nama_petugas),
            'nis' => $user->member->nis ?? ($user->saver->nis ?? $user->username),
            'kategori' => $request->kategori,
            'judul' => $request->judul,
            'isi_pesan' => $request->isi_pesan,
            'status' => 'menunggu',
        ]);

        return back()->with('success', 'Pengaduan berhasil dikirim ke Administrator.');
    }

    public function gantiPassword(Request $request)
    {
        $request->validate([
            'sandi_lama' => 'required|string',
            'sandi_baru' => 'required|string|min:6|confirmed',
        ], [
            'sandi_lama.required' => 'Kata sandi saat ini wajib diisi.',
            'sandi_baru.required' => 'Kata sandi baru wajib diisi.',
            'sandi_baru.min' => 'Kata sandi baru minimal 6 karakter.',
            'sandi_baru.confirmed' => 'Konfirmasi kata sandi baru tidak cocok dengan kata sandi baru.',
        ]);

        $user = \App\Models\User::findOrFail(Auth::id());

        if (!Hash::check($request->sandi_lama, $user->password)) {
            return back()->with('error_password', 'Kata sandi saat ini yang Anda masukkan salah.')->withInput();
        }

        $user->password = Hash::make($request->sandi_baru);
        $user->save();

        try {
            \App\Models\AuditLog::create([
                'user_id' => $user->id,
                'aktivitas' => 'Ganti Kata Sandi Mandiri',
                'detail' => 'Pengguna ' . ($user->nama_petugas ?? $user->username) . ' berhasil mengubah kata sandi akun.',
                'ip_address' => $request->ip(),
            ]);
        } catch (\Throwable $e) {}

        return back()->with('success_password', 'Kata sandi akun Anda berhasil diperbarui!');
    }
}
