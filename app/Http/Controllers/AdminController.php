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
use App\Models\PengajuanKas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Writer\Csv as CsvWriter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Reader\Xls as XlsReader;
use PhpOffice\PhpSpreadsheet\Reader\Csv as CsvReader;

class AdminController extends Controller
{
    public function storeUser(Request $request)
    {
        // admin sistem hanya boleh 1 akun utama
        if ($request->role === 'administrator') {
            return back()->with('error', 'Admin sistem hanya diperbolehkan 1 akun utama. Form ini khusus untuk menambah akun Teller.');
        }

        $request->validate([
            'username' => 'required|string|max:10|unique:users,username',
            'nama_petugas' => 'required|string|max:50',
            'email' => 'required|email|max:30|unique:users,email',
            'role' => 'required|in:teller',
            'password' => 'required|string|min:6',
        ], [
            'username.max' => 'Username maksimal 10 karakter.',
            'nama_petugas.max' => 'Nama petugas maksimal 50 huruf.',
            'email.max' => 'Alamat Gmail petugas maksimal 30 karakter.',
        ]);

        // simpan akun petugas teller baru
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

        // akun admin utama tidak boleh diblokir
        if ($user->id === auth()->id() || $user->role === 'administrator') {
            return back()->with('error', 'Tidak dapat memblokir akun Administrator utama.');
        }

        // ganti status antara aktif dan terblokir
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

    public function storeMember(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string|max:50',
            'nis' => 'required|string|max:10|unique:members,nis|unique:users,username',
            'class_grade' => 'required|string|max:5',
            'email' => 'required|email|max:30',
            'graduation_year' => 'required|string|max:4',
            'password' => 'required|string|min:6',
            'pin' => 'required|numeric|digits:6',
        ], [
            'student_name.max' => 'Nama siswa maksimal 50 huruf.',
            'nis.max' => 'NIS/NISN maksimal 10 karakter.',
            'class_grade.max' => 'Jurusan maksimal 5 huruf.',
            'email.max' => 'Alamat Gmail siswa maksimal 30 karakter.',
            'graduation_year.max' => 'Tahun kelulusan maksimal 4 karakter.',
            'password.required' => 'Kata sandi wajib diisi (minimal 6 karakter).',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'pin.required' => 'PIN transaksi penarikan wajib diisi.',
            'pin.digits' => 'PIN harus tepat 6 digit angka numerik.',
            'pin.numeric' => 'PIN transaksi penarikan harus berupa 6 angka digit.',
        ]);

        // buat akun login siswa
        $user = User::create([
            'username' => $request->nis,
            'nama_petugas' => $request->student_name,
            'email' => $request->email,
            'role' => 'nasabah',
            'password' => Hash::make($request->password),
            'status' => 'aktif',
        ]);

        // simpan data anggota siswa dengan status rekening belum aktif
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

        return back()->with('success', 'Anggota ' . $member->student_name . ' (' . $member->email . ') berhasil didaftarkan dengan status Rekening Tidak Aktif. Akun dapat login dan akan otomatis aktif masuk ke Manajemen User setelah menabung pertama kali.');
    }

    public function downloadMemberTemplate(Request $request)
    {
        $format = strtolower($request->query('format', 'xlsx'));

        // buat berkas template excel dan csv untuk impor siswa
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Anggota');

        $headers = [
            'A1' => 'nisn',
            'B1' => 'nama',
            'C1' => 'jurusan',
            'D1' => 'tahun_keluar',
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // data contoh pengisian template
        $sheet->setCellValueExplicit('A2', '1029384756', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('B2', 'Ahmad Fauzi', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('C2', 'RPL', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('D2', '2028', DataType::TYPE_STRING);

        $sheet->setCellValueExplicit('A3', '1029384757', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('B3', 'Siti Nurhaliza', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('C3', 'AKL', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('D3', '2028', DataType::TYPE_STRING);

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        if ($format === 'csv') {
            $filename = 'template_impor_anggota.csv';
            return response()->streamDownload(function () use ($spreadsheet) {
                $writer = new CsvWriter($spreadsheet);
                $writer->setDelimiter(',');
                $writer->setEnclosure('"');
                $writer->setLineEnding("\r\n");
                $writer->setSheetIndex(0);
                $writer->save('php://output');
            }, $filename, [
                'Content-Type' => 'text/csv',
            ]);
        }

        $filename = 'template_impor_anggota.xlsx';
        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new XlsxWriter($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function importMembers(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120',
        ], [
            'file.required' => 'Pilih berkas CSV atau Excel terlebih dahulu.',
            'file.mimes' => 'Format berkas harus berupa .csv, .xlsx, atau .xls.',
            'file.max' => 'Ukuran berkas maksimal 5 MB.',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        // baca berkas upload menggunakan phpspreadsheet
        try {
            if ($extension === 'csv' || $extension === 'txt') {
                $reader = new CsvReader();
            } elseif ($extension === 'xls') {
                $reader = new XlsReader();
            } else {
                $reader = new XlsxReader();
            }

            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal membaca berkas: ' . $e->getMessage());
        }

        if (empty($rows) || count($rows) < 2) {
            return back()->with('error', 'Berkas kosong atau tidak berisi baris data anggota.');
        }

        // deteksi otomatis nama kolom header
        $headerRow = array_shift($rows);
        $columnMap = [];
        foreach ($headerRow as $colLetter => $headerName) {
            $norm = strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace([' ', '-'], '_', (string)$headerName))));
            if (in_array($norm, ['nama', 'nama_siswa', 'namasiswa', 'student_name'])) {
                $columnMap['nama_siswa'] = $colLetter;
            } elseif (in_array($norm, ['nisn', 'nis', 'nomor_induk', 'nomorinduk', 'no_induk'])) {
                $columnMap['nis'] = $colLetter;
            } elseif (in_array($norm, ['jurusan', 'kelas', 'class_grade', 'class', 'tingkat'])) {
                $columnMap['kelas'] = $colLetter;
            } elseif (in_array($norm, ['tahun_keluar', 'tahunkeluar', 'tahun_lulus', 'tahunlulus', 'tahun_kelulusan', 'tahunkelulusan', 'angkatan_keluar', 'angkatankeluar', 'graduation_year'])) {
                $columnMap['angkatan_keluar'] = $colLetter;
            } elseif (in_array($norm, ['email', 'alamat_email', 'gmail'])) {
                $columnMap['email'] = $colLetter;
            } elseif (in_array($norm, ['password', 'sandi', 'kata_sandi'])) {
                $columnMap['password'] = $colLetter;
            }
        }

        if (!isset($columnMap['nama_siswa']) || !isset($columnMap['nis'])) {
            return back()->with('error', 'Format template salah. Kolom nisn (atau nis) dan nama wajib ada di baris pertama template.');
        }

        $importedCount = 0;
        $skippedCount = 0;

        // tampung nis yang sudah ada untuk mencegah duplikat
        $existingMembers = array_fill_keys(Member::pluck('nis')->all(), true);
        $existingUsernames = array_fill_keys(User::pluck('username')->all(), true);
        $defaultPasswordHash = Hash::make('123456');

        DB::beginTransaction();
        try {
            foreach ($rows as $rowIndex => $row) {
                $namaSiswa = trim((string)($row[$columnMap['nama_siswa']] ?? ''));
                $nis = trim((string)($row[$columnMap['nis']] ?? ''));

                if ($namaSiswa === '' && $nis === '') {
                    continue;
                }

                if ($namaSiswa === '' || $nis === '') {
                    $skippedCount++;
                    continue;
                }

                if (isset($existingMembers[$nis]) || isset($existingUsernames[$nis])) {
                    $skippedCount++;
                    continue;
                }

                $existingMembers[$nis] = true;
                $existingUsernames[$nis] = true;

                $email = isset($columnMap['email']) ? trim((string)($row[$columnMap['email']] ?? '')) : null;
                if ($email === '' || ($email !== null && !filter_var($email, FILTER_VALIDATE_EMAIL))) {
                    $email = null;
                }

                $kelas = isset($columnMap['kelas']) ? trim((string)($row[$columnMap['kelas']] ?? '')) : 'RPL';
                if ($kelas === '') $kelas = 'RPL';

                $angkatanKeluar = isset($columnMap['angkatan_keluar']) ? trim((string)($row[$columnMap['angkatan_keluar']] ?? '')) : (string)(date('Y') + 3);
                if ($angkatanKeluar === '') $angkatanKeluar = (string)(date('Y') + 3);

                $passwordRaw = isset($columnMap['password']) ? trim((string)($row[$columnMap['password']] ?? '')) : '123456';
                $passwordHashed = ($passwordRaw === '123456' || strlen($passwordRaw) < 6) ? $defaultPasswordHash : Hash::make($passwordRaw);

                // simpan user nasabah dan data anggota siswa
                $user = User::create([
                    'username' => $nis,
                    'nama_petugas' => $namaSiswa,
                    'email' => $email,
                    'role' => 'nasabah',
                    'password' => $passwordHashed,
                    'status' => 'aktif',
                ]);

                Member::create([
                    'user_id' => $user->id,
                    'student_name' => $namaSiswa,
                    'nis' => $nis,
                    'class_grade' => $kelas,
                    'email' => $email,
                    'pin' => null,
                    'graduation_year' => $angkatanKeluar,
                    'status' => 'rekening tidak aktif',
                ]);

                $importedCount++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses impor: ' . $e->getMessage());
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Impor Data Anggota',
            'detail' => "Berhasil mengimpor {$importedCount} data anggota baru ({$skippedCount} data dilewati)",
            'ip_address' => $request->ip(),
        ]);

        $message = "Berhasil mengimpor {$importedCount} data anggota siswa baru.";
        if ($skippedCount > 0) {
            $message .= " Sebanyak {$skippedCount} data dilewati karena NIS sudah terdaftar atau baris tidak lengkap.";
        }

        return back()->with('success', $message);
    }

    public function updateMember(Request $request, $userId)
    {
        $request->validate([
            'student_name' => 'required|string|max:50',
            'nis' => 'required|string|max:10|unique:members,nis,' . $userId . ',user_id',
            'class_grade' => 'required|string|max:5',
            'email' => 'nullable|email|max:30',
            'graduation_year' => 'required|string|max:4',
            'status' => 'nullable|in:rekening aktif,rekening tidak aktif',
        ], [
            'student_name.max' => 'Nama siswa maksimal 50 huruf.',
            'nis.max' => 'NIS/NISN maksimal 10 karakter.',
            'class_grade.max' => 'Jurusan maksimal 5 huruf.',
            'email.max' => 'Alamat Gmail siswa maksimal 30 karakter.',
            'graduation_year.max' => 'Tahun kelulusan maksimal 4 karakter.',
        ]);

        $member = Member::where('user_id', $userId)->firstOrFail();
        $memberData = [
            'student_name' => $request->student_name,
            'nis' => $request->nis,
            'class_grade' => $request->class_grade,
            'email' => $request->email ?? $member->email,
            'graduation_year' => $request->graduation_year,
        ];
        if ($request->filled('status')) {
            $memberData['status'] = $request->status;
        }
        $member->update($memberData);

        // sinkronkan ke akun user
        if ($member->user) {
            $userData = [
                'nama_petugas' => $request->student_name,
                'username' => $request->nis,
                'email' => $request->email ?? $member->user->email,
            ];
            if ($request->filled('status')) {
                $userData['status'] = 'aktif';
            }
            $member->user->update($userData);
        }

        // sinkronkan ke data penabung saver jika ada
        $saver = Saver::withTrashed()->where('user_id', $member->user_id)->orWhere('nis', $member->nis)->first();
        if ($saver) {
            $saver->update([
                'student_name' => $request->student_name,
                'nis' => $request->nis,
                'class_grade' => $request->class_grade,
                'email' => $request->email ?? $saver->email,
            ]);
        }

        // kelola status rekening penabung
        if ($request->status === 'rekening aktif' || $member->status === 'rekening aktif') {
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
        } elseif ($request->filled('status') && $request->status === 'rekening tidak aktif') {
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

        // hapus data member, saver, rekening, dan user (soft delete)
        $member->delete();

        if ($member->saver) {
            if ($member->saver->rekening) {
                $member->saver->rekening->delete();
            }
            $member->saver->delete();
        }

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

    public function resetPinSaver(Request $request, $id)
    {
        $request->validate([
            'pin' => 'required|numeric|digits:6',
        ]);

        // reset pin penabung oleh admin
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

        // nonaktifkan rekening dan hapus akun penabung (histori transaksi tetap aman)
        if ($saver->rekening) {
            $saver->rekening->update(['status' => 'nonaktif']);
        }

        if ($saver->member) {
            $saver->member->update(['status' => 'rekening tidak aktif']);
        }

        $saver->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Hapus Penabung',
            'detail' => 'Menonaktifkan dan menghapus data penabung ' . $nama . '. Catatan transaksi tetap tersimpan utuh untuk rekonsiliasi laporan kas.',
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Akun penabung ' . $nama . ' berhasil dinonaktifkan/dihapus. Data histori transaksi tetap aman tersimpan.');
    }

    public function approveLaporan($id)
    {
        $laporan = LaporanHarian::findOrFail($id);

        // selisih fisik wajib 0 rupiah sebelum disetujui
        if ($laporan->selisih != 0) {
            return back()->with('error', 'Peringatan: Terdapat selisih kas fisik sebesar Rp ' . number_format($laporan->selisih) . '. Laporan memerlukan penanganan fisik kas sebelum disetujui.');
        }

        // setujui tutup kas teller dan pindahkan uang ke brankas utama
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

        // tolak laporan tutup kas dengan alasan tertulis
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

    public function downloadLaporanHarianPdf($id)
    {
        $laporan = LaporanHarian::with([
            'teller' => fn($q) => $q->withTrashed(),
            'supervisor' => fn($q) => $q->withTrashed(),
        ])->findOrFail($id);

        // ambil data transaksi teller pada tanggal tersebut untuk cetak pdf
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
        $jenisTrans = $request->query('jenis_trans');

        $namaBulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $namaBulan = $namaBulanList[(int)$month] ?? 'Bulan ' . $month;

        // rekap mutasi buku kas bulanan untuk cetak pdf
        $transaksisQuery = Transaksi::with([
            'saver' => fn($q) => $q->withTrashed(),
            'teller' => fn($q) => $q->withTrashed(),
        ])
        ->whereYear('tanggal', $year)
        ->whereMonth('tanggal', $month);

        $totalSetoran = (clone $transaksisQuery)->where('jenis_trans', 'setoran')->sum('nominal');
        $totalPenarikan = (clone $transaksisQuery)->where('jenis_trans', 'penarikan')->sum('nominal');

        if ($jenisTrans && in_array($jenisTrans, ['setoran', 'penarikan'])) {
            $transaksisQuery->where('jenis_trans', $jenisTrans);
        }

        $transaksis = $transaksisQuery->orderBy('tanggal', 'asc')->get();

        $pdf = Pdf::loadView('reports.laporan_bulanan_pdf', compact(
            'transaksis', 'month', 'year', 'namaBulan', 'totalSetoran', 'totalPenarikan'
        ));

        return $pdf->download('Laporan_Bulanan_' . $namaBulan . '_' . $year . '.pdf');
    }

    public function approvePengajuanKas(Request $request, $id)
    {
        $pengajuan = PengajuanKas::findOrFail($id);
        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        // setujui permohonan tambahan modal kas teller
        $pengajuan->update([
            'status' => 'approved',
            'admin_id' => auth()->id(),
            'catatan_admin' => $request->catatan_admin,
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'ACC Tambahan Kas Teller',
            'detail' => 'ACC tambahan kas Rp ' . number_format($pengajuan->nominal) . ' (Teller: ' . ($pengajuan->teller->nama_petugas ?? '-') . ')',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Tambahan kas Rp ' . number_format($pengajuan->nominal) . ' disetujui.');
    }

    public function rejectPengajuanKas(Request $request, $id)
    {
        $pengajuan = PengajuanKas::findOrFail($id);
        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        $request->validate([
            'catatan_admin' => 'required|string|max:500',
        ], [
            'catatan_admin.required' => 'Alasan penolakan pengajuan tambahan kas wajib diisi.',
        ]);

        // tolak permohonan tambahan kas teller
        $pengajuan->update([
            'status' => 'rejected',
            'admin_id' => auth()->id(),
            'catatan_admin' => $request->catatan_admin,
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Tolak Tambahan Kas Teller',
            'detail' => 'Tolak tambahan kas Rp ' . number_format($pengajuan->nominal) . ' (Teller: ' . ($pengajuan->teller->nama_petugas ?? '-') . ') - Alasan: ' . $request->catatan_admin,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Pengajuan tambahan kas berhasil ditolak dengan alasan: "' . $request->catatan_admin . '".');
    }

    public function tanggapiPengaduan(Request $request, $id)
    {
        $request->validate([
            'tanggapan_admin' => 'required|string',
            'status' => 'required|in:diproses,selesai',
        ]);

        // simpan tanggapan admin atas pengaduan nasabah
        $pengaduan = \App\Models\Pengaduan::findOrFail($id);
        $pengaduan->update([
            'tanggapan_admin' => $request->tanggapan_admin,
            'status' => $request->status,
            'ditanggapi_pada' => now(),
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Tanggapi Pengaduan Nasabah',
            'detail' => 'Menanggapi pengaduan dari ' . $pengaduan->nama_pengadu . ' (NIS: ' . $pengaduan->nis . ') - Status: ' . $request->status,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Tanggapan pengaduan berhasil disimpan.');
    }
}
