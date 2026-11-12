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
use App\Models\TargetTabungan;
use App\Models\Antrean;
use App\Models\Pengaduan;
use App\Models\Pengaturan;
use App\Models\PerjanjianPenarikan;

/**
 * Class NasabahApiController
 * 
 * Menyediakan layanan RESTful API untuk aplikasi mobile nasabah (Flutter E-Teller):
 * 1. Otentikasi & Profil Siswa (Login, Sinkronisasi data identitas)
 * 2. Informasi Finansial Realtime (Saldo, Riwayat Mutasi, Ringkasan Bulanan)
 * 3. Fitur Digital: Celengan Impian (Target Tabungan), Tiket Antrean Loket
 * 4. Pengelolaan Perjanjian Penarikan H-1 (> Rp 1.000.000) & Pengaduan
 */
class NasabahApiController extends Controller
{
    /**
     * API Login Nasabah via Mobile
     * Menerima username / nama_siswa / nis / no_rekening dan kata sandi
     */
    public function login(Request $request)
    {
        // 1. Validasi input kredensial
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $input = trim($request->username);
        $password = $request->password;

        // 2. Pencarian akun User secara fleksibel (Username, Nama, NIS, No Rekening)
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

        // 3. Verifikasi kata sandi (Hash::check)
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password yang Anda masukkan salah.',
            ], 401);
        }

        // 4. Verifikasi status blokir akun
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

        // 5. Sinkronisasi data otomatis jika terdapat perubahan identitas oleh admin
        if ($saverData && $memberData) {
            if ($saverData->student_name !== $memberData->student_name || $saverData->class_grade !== $memberData->class_grade || $saverData->nis !== $memberData->nis) {
                $saverData->update([
                    'student_name' => $memberData->student_name,
                    'class_grade' => $memberData->class_grade,
                    'nis' => $memberData->nis,
                    'email' => $memberData->email ?? $saverData->email,
                ]);
            }
        }

        $namaSiswa = $memberData ? $memberData->student_name : ($saverData ? $saverData->student_name : $user->nama_petugas);
        $noRekening = $saverData ? $saverData->account_number : '-';
        $nis = $memberData ? $memberData->nis : ($saverData ? $saverData->nis : $user->username);
        $kelas = $memberData ? $memberData->class_grade : ($saverData ? $saverData->class_grade : '-');
        $email = $memberData ? $memberData->email : ($saverData ? $saverData->email : $user->email);
        $saldo = $saverData ? (int) ($saverData->rekening->saldo ?? 0) : 0;
        $statusRekening = $saverData && $saverData->rekening ? $saverData->rekening->status : 'rekening tidak aktif';

        // 6. Kembalikan payload JSON profil akun dan status rekening
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
                'status_layanan' => Pengaturan::getStatusLayanan(),
                'qr_url' => $saverData ? ('https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=15&data=' . $saverData->account_number) : '',
            ]
        ]);
    }

    /**
     * Helper Penyelesaian Relasi Entitas Pengguna (User, Saver, Member)
     */
    private function resolveUserAndSaver($id)
    {
        $saver = Saver::with('rekening')->find($id);
        $member = null;
        $user = null;

        if ($saver) {
            $user = User::find($saver->user_id);
            $member = Member::where('user_id', $saver->user_id)->orWhere('nis', $saver->nis)->first();
        } else {
            $user = User::find($id);
            if ($user) {
                $saver = Saver::with('rekening')->where('user_id', $user->id)->first();
                $member = Member::where('user_id', $user->id)->first();
            } else {
                $member = Member::where('user_id', $id)->orWhere('nis', $id)->first();
                if ($member) {
                    $user = User::find($member->user_id);
                    $saver = Saver::with('rekening')->where('user_id', $member->user_id)->first();
                }
            }
        }

        return [$user, $saver, $member];
    }

    /**
     * API Payload Lengkap Dashboard Mobile
     * Menghimpun profil rekening, status antrean, celengan target impian, dan laporan bulanan
     */
    public function dashboard(Request $request, $id)
    {
        [$user, $saver, $member] = $this->resolveUserAndSaver($id);

        if (!$saver && !$member && !$user) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa belum ditemukan.',
            ], 404);
        }

        // Sinkronisasi data otomatis jika ada pembaruan
        if ($saver && $member) {
            if ($saver->student_name !== $member->student_name || $saver->class_grade !== $member->class_grade || $saver->nis !== $member->nis) {
                $saver->update([
                    'student_name' => $member->student_name,
                    'class_grade' => $member->class_grade,
                    'nis' => $member->nis,
                    'email' => $member->email ?? $saver->email,
                ]);
            }
        }

        $namaSiswa = $member ? $member->student_name : ($saver ? $saver->student_name : ($user ? $user->nama_petugas : '-'));
        $noRekening = $saver ? $saver->account_number : '-';
        $nis = $member ? $member->nis : ($saver ? $saver->nis : ($user ? $user->username : '-'));
        $kelas = $member ? $member->class_grade : ($saver ? $saver->class_grade : '-');
        $email = $member ? $member->email : ($saver ? $saver->email : ($user ? $user->email : null));
        $saldo = $saver ? (int) ($saver->rekening->saldo ?? 0) : 0;
        $statusRekening = $saver && $saver->rekening ? $saver->rekening->status : 'rekening tidak aktif';
        $userId = $user ? $user->id : ($saver ? $saver->user_id : null);

        // 1. DATA SISTEM ANTREAN REALTIME
        $today = date('Y-m-d');
        $antreanSaya = $userId ? Antrean::where('tanggal', $today)
            ->where('user_id', $userId)
            ->whereIn('status', ['menunggu', 'dilayani'])
            ->first() : null;
        $sedangDilayani = Antrean::where('tanggal', $today)->where('status', 'dilayani')->first();
        $totalMenunggu = Antrean::where('tanggal', $today)->where('status', 'menunggu')->count();
        $sisaAntrean = ($antreanSaya && $antreanSaya->status === 'menunggu')
            ? Antrean::where('tanggal', $today)->where('status', 'menunggu')->where('angka_antrean', '<', $antreanSaya->angka_antrean)->count()
            : 0;

        // 2. DATA TARGET TABUNGAN (CELENGAN IMPIAN)
        $targetTabungan = $userId ? TargetTabungan::where('user_id', $userId)->first() : null;
        $targetData = null;
        if ($targetTabungan) {
            $nomTarget = $targetTabungan->nominal_target > 0 ? $targetTabungan->nominal_target : 1;
            $persen = min(100, round(($saldo / $nomTarget) * 100, 1));
            $sisa = max(0, $targetTabungan->nominal_target - $saldo);

            $targetData = [
                'id' => $targetTabungan->id,
                'judul_target' => $targetTabungan->judul_target,
                'nominal_target' => (int) $targetTabungan->nominal_target,
                'target_tanggal' => $targetTabungan->target_tanggal ? $targetTabungan->target_tanggal->format('Y-m-d') : null,
                'keterangan' => $targetTabungan->keterangan,
                'status' => $targetTabungan->status,
                'persen' => $persen,
                'sisa' => $sisa,
            ];
        }

        // 3. DATA LAPORAN KEUANGAN BULANAN
        $selectedBulan = (int) $request->query('bulan', date('n'));
        $selectedTahun = (int) $request->query('tahun', date('Y'));
        $laporanBulanan = $this->hitungLaporanBulanan($saver, $selectedBulan, $selectedTahun);

        // 4. DATA RIWAYAT PENGADUAN NASABAH
        $pengaduanList = $userId ? Pengaduan::where('user_id', $userId)->orderBy('created_at', 'desc')->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'kategori' => $p->kategori,
                'judul' => $p->judul,
                'isi_pesan' => $p->isi_pesan,
                'status' => $p->status,
                'tanggapan_admin' => $p->tanggapan_admin,
                'created_at' => $p->created_at ? $p->created_at->format('d M Y H:i') : '-',
            ];
        }) : [];

        // 5. DATA RIWAYAT PERJANJIAN PENARIKAN H-1 (> Rp 1.000.000)
        $perjanjianList = $saver ? PerjanjianPenarikan::where('saver_id', $saver->id)->orderBy('created_at', 'desc')->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'nominal' => (int)$p->nominal,
                'tanggal_rencana' => $p->tanggal_rencana,
                'alasan' => $p->alasan,
                'status' => $p->status,
                'catatan_teller' => $p->catatan_teller,
                'created_at' => $p->created_at ? $p->created_at->format('d M Y H:i') : '-',
            ];
        }) : [];

        // 6. Kembalikan seluruh data dalam format JSON
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $saver ? $saver->id : $userId,
                'user_id' => $userId,
                'nasabah_id' => $saver ? $saver->id : $userId,
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
                'status_layanan' => Pengaturan::getStatusLayanan(),
                'qr_url' => $saver ? ('https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=15&data=' . $saver->account_number) : '',
                'antrean' => [
                    'sedang_dilayani' => $sedangDilayani ? $sedangDilayani->nomor_antrean : 'Belum Ada Panggilan',
                    'total_menunggu' => $totalMenunggu,
                    'nomor_saya' => $antreanSaya ? $antreanSaya->nomor_antrean : null,
                    'status_saya' => $antreanSaya ? $antreanSaya->status : null,
                    'sisa_antrean' => $sisaAntrean,
                ],
                'target_tabungan' => $targetData,
                'laporan_bulanan' => $laporanBulanan,
                'profil' => [
                    'nama_lengkap' => $namaSiswa,
                    'nis' => $nis,
                    'jurusan_kelas' => $kelas,
                    'tahun_kelulusan' => $member?->graduation_year ?? '-',
                    'email' => $email ?? '-',
                    'no_rekening' => $noRekening,
                    'status_rekening' => $statusRekening,
                    'is_aktif' => ($saver && $statusRekening === 'aktif'),
                ],
                'pengaduan_list' => $pengaduanList,
                'perjanjian_list' => $perjanjianList,
                'aturan_penarikan' => [
                    'max_reguler_harian' => 1000000,
                    'min_sisa_saldo' => 10000,
                    'min_nominal_perjanjian' => 1000001,
                ],
            ]
        ]);
    }

    /**
     * Helper Perhitungan Laporan Keuangan Bulanan Nasabah
     */
    private function hitungLaporanBulanan($saver, $bulan, $tahun)
    {
        $saldoAwal = 0;
        $saldoAkhir = 0;
        $totalSetoran = 0;
        $totalPenarikan = 0;

        if ($saver) {
            $startOfMonth = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
            $endOfMonth = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

            $trxBulanIni = Transaksi::where('saver_id', $saver->id)
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->get();

            $totalSetoran = (int) $trxBulanIni->where('jenis_trans', 'setoran')->sum('nominal');
            $totalPenarikan = (int) $trxBulanIni->where('jenis_trans', 'penarikan')->sum('nominal');

            // Saldo awal = akumulasi mutasi sebelum awal bulan berjalan
            $setoranSebelum = (int) Transaksi::where('saver_id', $saver->id)
                ->where('tanggal', '<', $startOfMonth)
                ->where('jenis_trans', 'setoran')
                ->sum('nominal');
            $penarikanSebelum = (int) Transaksi::where('saver_id', $saver->id)
                ->where('tanggal', '<', $startOfMonth)
                ->where('jenis_trans', 'penarikan')
                ->sum('nominal');

            $saldoAwal = $setoranSebelum - $penarikanSebelum;
            $saldoAkhir = $saldoAwal + ($totalSetoran - $totalPenarikan);
        }

        return [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'nama_bulan' => \Carbon\Carbon::create(null, $bulan, 1)->translatedFormat('F'),
            'saldo_awal' => $saldoAwal,
            'total_setoran' => $totalSetoran,
            'total_penarikan' => $totalPenarikan,
            'saldo_akhir' => $saldoAkhir,
        ];
    }

    /**
     * API Riwayat Mutasi Rekening Tabungan
     */
    public function mutasi($id)
    {
        [$user, $saver, $member] = $this->resolveUserAndSaver($id);

        if (!$saver) {
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
     * API Ambil Nomor Antrean Loket via Mobile
     */
    public function ambilAntrean(Request $request)
    {
        $id = $request->input('user_id') ?? $request->input('nasabah_id');
        [$user, $saver, $member] = $this->resolveUserAndSaver($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Identitas pengguna tidak ditemukan.',
            ], 404);
        }

        $today = date('Y-m-d');
        // Validasi: 1 antrean aktif per hari
        $existing = Antrean::where('tanggal', $today)
            ->where('user_id', $user->id)
            ->whereIn('status', ['menunggu', 'dilayani'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memiliki nomor antrean aktif hari ini: ' . $existing->nomor_antrean,
                'nomor_antrean' => $existing->nomor_antrean,
            ], 400);
        }

        $next = Antrean::generateNomorHariIni();

        $antrean = Antrean::create([
            'nomor_antrean' => $next['nomor'],
            'angka_antrean' => $next['angka'],
            'user_id' => $user->id,
            'nama_siswa' => $member?->student_name ?? ($saver?->student_name ?? $user->nama_petugas),
            'nis' => $member?->nis ?? ($saver?->nis ?? $user->username),
            'tanggal' => $today,
            'status' => 'menunggu',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nomor antrean berhasil diambil: ' . $next['nomor'],
            'nomor_antrean' => $next['nomor'],
        ]);
    }

    /**
     * API Simpan / Pembaruan Target Tabungan (Celengan Impian)
     */
    public function simpanTargetTabungan(Request $request)
    {
        $request->validate([
            'judul_target' => 'required|string|max:100',
            'nominal_target' => 'required|integer|min:10000',
            'target_tanggal' => 'nullable|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $id = $request->input('user_id') ?? $request->input('nasabah_id');
        [$user, $saver] = $this->resolveUserAndSaver($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Identitas nasabah tidak ditemukan.',
            ], 404);
        }

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

        return response()->json([
            'success' => true,
            'message' => 'Target tabungan berhasil disimpan.',
        ]);
    }

    /**
     * API Hapus Target Tabungan
     */
    public function hapusTargetTabungan(Request $request)
    {
        $id = $request->input('user_id') ?? $request->input('nasabah_id');
        [$user] = $this->resolveUserAndSaver($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Identitas nasabah tidak ditemukan.',
            ], 404);
        }

        $target = TargetTabungan::where('user_id', $user->id)->first();
        if ($target) {
            $target->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Target tabungan berhasil dihapus.',
        ]);
    }

    /**
     * API Data Laporan Keuangan Bulanan
     */
    public function laporanBulanan(Request $request, $id)
    {
        [$user, $saver] = $this->resolveUserAndSaver($id);
        $bulan = (int) $request->query('bulan', date('n'));
        $tahun = (int) $request->query('tahun', date('Y'));

        $data = $this->hitungLaporanBulanan($saver, $bulan, $tahun);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * API Kirim Pesan Pengaduan Nasabah ke Administrator
     */
    public function kirimPengaduan(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:50',
            'judul' => 'required|string|max:150',
            'isi_pesan' => 'required|string',
        ]);

        $id = $request->input('user_id') ?? $request->input('nasabah_id');
        [$user, $saver, $member] = $this->resolveUserAndSaver($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Identitas nasabah tidak ditemukan.',
            ], 404);
        }

        Pengaduan::create([
            'user_id' => $user->id,
            'nama_pengadu' => $member?->student_name ?? ($saver?->student_name ?? $user->nama_petugas),
            'nis' => $member?->nis ?? ($saver?->nis ?? $user->username),
            'kategori' => $request->kategori,
            'judul' => $request->judul,
            'isi_pesan' => $request->isi_pesan,
            'status' => 'menunggu',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan Anda telah berhasil dikirim ke Administrator.',
        ]);
    }

    /**
     * API Ganti Kata Sandi Akun Mandiri
     */
    public function gantiPassword(Request $request)
    {
        $request->validate([
            'sandi_lama' => 'required|string',
            'sandi_baru' => 'required|string|min:6',
        ]);

        $id = $request->input('user_id') ?? $request->input('nasabah_id');
        [$user] = $this->resolveUserAndSaver($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Identitas nasabah tidak ditemukan.',
            ], 404);
        }

        if (!Hash::check($request->sandi_lama, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kata sandi saat ini salah.',
            ], 400);
        }

        $user->password = Hash::make($request->sandi_baru);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diperbarui.',
        ]);
    }

    /**
     * API Permintaan Lupa Kata Sandi
     */
    public function lupaSandi(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jika alamat Gmail terdaftar, petunjuk pemulihan kata sandi telah dikirimkan ke email Anda.',
        ]);
    }

    /**
     * API Permintaan Lupa PIN Keamanan Transaksi
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

    /**
     * API Riwayat Daftar Perjanjian Penarikan Dana H-1
     */
    public function listPerjanjian(Request $request, $id)
    {
        [$user, $saver, $member] = $this->resolveUserAndSaver($id);
        if (!$saver) {
            return response()->json([
                'success' => false,
                'message' => 'Rekening penabung belum aktif atau tidak ditemukan.',
            ], 404);
        }

        $list = PerjanjianPenarikan::where('saver_id', $saver->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $list,
            'aturan' => [
                'max_reguler_harian' => 1000000,
                'min_sisa_saldo' => 10000,
                'min_nominal_perjanjian' => 1000001,
            ]
        ]);
    }

    /**
     * API Simpan Permohonan Perjanjian Penarikan Dana H-1 (> Rp 1.000.000)
     */
    public function simpanPerjanjian(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'nominal' => 'required|integer|min:1000001',
            'tanggal_rencana' => 'required|date|after_or_equal:tomorrow',
            'alasan' => 'required|string|max:255',
        ], [
            'nominal.min' => 'Perjanjian H-1 khusus untuk penarikan di atas Rp 1.000.000 (minimal Rp 1.000.001). Untuk penarikan Rp 1.000.000 ke bawah, silakan langsung datang ke loket Teller.',
            'tanggal_rencana.after_or_equal' => 'Tanggal rencana penarikan wajib minimal H-1 (besok atau hari selanjutnya).',
        ]);

        [$user, $saver, $member] = $this->resolveUserAndSaver($request->user_id);
        if (!$saver || !$saver->rekening) {
            return response()->json([
                'success' => false,
                'message' => 'Rekening tabungan tidak ditemukan atau belum aktif.',
            ], 400);
        }

        // Validasi: Sisa saldo mengendap di rekening wajib minimal Rp 10.000
        if (($saver->rekening->saldo - $request->nominal) < 10000) {
            $maksimalTarik = max(0, $saver->rekening->saldo - 10000);
            return response()->json([
                'success' => false,
                'message' => 'Saldo tidak mencukupi. Penarikan harus menyisakan saldo minimal Rp 10.000 di rekening. Maksimal yang dapat ditarik: Rp ' . number_format($maksimalTarik, 0, ',', '.'),
            ], 422);
        }

        // Simpan permohonan perjanjian penarikan ke database
        $pj = PerjanjianPenarikan::create([
            'saver_id' => $saver->id,
            'nominal' => $request->nominal,
            'tanggal_rencana' => $request->tanggal_rencana,
            'alasan' => $request->alasan,
            'status' => 'menunggu',
            'diajukan_pada' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permohonan perjanjian penarikan berhasil diajukan ke Teller.',
            'data' => $pj,
        ]);
    }
}
