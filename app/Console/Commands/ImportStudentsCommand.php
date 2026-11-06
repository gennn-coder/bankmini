<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\Member;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ImportStudentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-students {--file= : Path to CSV file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import student data from CSV into users and members tables efficiently';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->option('file') ?: database_path('data_siswa_impor.csv');

        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return 1;
        }

        $this->info("Membaca file data siswa: {$filePath}");

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            $this->error("Gagal membuka file: {$filePath}");
            return 1;
        }

        // Baca header
        $header = fgetcsv($handle);
        if (!$header) {
            $this->error("File CSV kosong.");
            fclose($handle);
            return 1;
        }

        // Hapus BOM jika ada
        $header[0] = preg_replace('/[\x{EF}\x{BB}\x{BF}]/u', '', $header[0]);

        $colMap = [];
        foreach ($header as $idx => $colName) {
            $clean = strtolower(trim((string)$colName));
            if (in_array($clean, ['student_number', 'nisn', 'nis', 'no_induk', 'username'])) {
                $colMap['nis'] = $idx;
            } elseif (in_array($clean, ['full_name', 'nama', 'nama_siswa', 'name'])) {
                $colMap['nama'] = $idx;
            } elseif (in_array($clean, ['graduation_year', 'tahun_keluar', 'angkatan_keluar', 'tahun_lulus', 'angkatan'])) {
                $colMap['tahun_keluar'] = $idx;
            } elseif (in_array($clean, ['major', 'jurusan', 'kelas', 'class_grade'])) {
                $colMap['jurusan'] = $idx;
            } elseif (in_array($clean, ['email', 'gmail'])) {
                $colMap['email'] = $idx;
            }
        }

        if (!isset($colMap['nis']) || !isset($colMap['nama'])) {
            $this->error("Header CSV tidak valid. Harus mengandung kolom NIS / student_number dan Nama / full_name.");
            fclose($handle);
            return 1;
        }

        // Preload existing NIS / Usernames to avoid thousands of DB queries
        $existingUsernames = array_fill_keys(User::pluck('username')->all(), true);
        $existingMembers = array_fill_keys(Member::pluck('nis')->all(), true);

        // Pre-compute default password hash once
        $defaultPasswordHash = Hash::make('123456');

        $importedCount = 0;
        $skippedCount = 0;
        $totalRows = 0;
        $byMajor = [];
        $byYear = [];
        $now = now();

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $totalRows++;
                $nis = isset($colMap['nis']) && isset($row[$colMap['nis']]) ? trim((string)$row[$colMap['nis']]) : '';
                $nama = isset($colMap['nama']) && isset($row[$colMap['nama']]) ? trim((string)$row[$colMap['nama']]) : '';
                $tahunKeluar = isset($colMap['tahun_keluar']) && isset($row[$colMap['tahun_keluar']]) ? trim((string)$row[$colMap['tahun_keluar']]) : (string)(date('Y') + 3);
                $jurusan = isset($colMap['jurusan']) && isset($row[$colMap['jurusan']]) ? trim((string)$row[$colMap['jurusan']]) : 'RPL';
                $email = isset($colMap['email']) && isset($row[$colMap['email']]) ? trim((string)$row[$colMap['email']]) : null;

                if ($nis === '' && $nama === '') {
                    continue;
                }

                if ($nis === '' || $nama === '') {
                    $skippedCount++;
                    continue;
                }

                if ($tahunKeluar === '') {
                    $tahunKeluar = (string)(date('Y') + 3);
                }

                if ($jurusan === '') {
                    $jurusan = 'RPL';
                }

                // Cek duplikasi NIS / Username di memory
                if (isset($existingUsernames[$nis]) || isset($existingMembers[$nis])) {
                    $skippedCount++;
                    continue;
                }

                // Tandai sudah diproses agar baris duplikat di CSV juga tersaring
                $existingUsernames[$nis] = true;
                $existingMembers[$nis] = true;

                // Buat User Nasabah
                $user = User::create([
                    'username' => $nis,
                    'nama_petugas' => $nama,
                    'email' => $email,
                    'role' => 'nasabah',
                    'password' => $defaultPasswordHash,
                    'status' => 'aktif',
                ]);

                // Buat Member (Status rekening tidak aktif menunggu aktivasi di loket teller)
                Member::create([
                    'user_id' => $user->id,
                    'student_name' => $nama,
                    'nis' => $nis,
                    'class_grade' => $jurusan,
                    'email' => $email,
                    'pin' => null,
                    'graduation_year' => $tahunKeluar,
                    'status' => 'rekening tidak aktif',
                ]);

                $importedCount++;

                $byMajor[$jurusan] = ($byMajor[$jurusan] ?? 0) + 1;
                $byYear[$tahunKeluar] = ($byYear[$tahunKeluar] ?? 0) + 1;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            fclose($handle);
            $this->error("Terjadi kesalahan saat mengimpor data: " . $e->getMessage());
            return 1;
        }

        fclose($handle);

        // Catat di Audit Log jika ada user admin
        $adminUser = User::where('role', 'administrator')->first();
        if ($adminUser) {
            AuditLog::create([
                'user_id' => $adminUser->id,
                'aktivitas' => 'Impor Data Siswa Massal CLI',
                'detail' => "Berhasil mengimpor {$importedCount} data siswa baru ({$skippedCount} data dilewati) dari file CSV",
                'ip_address' => '127.0.0.1',
            ]);
        }

        $this->info("=== HASIL IMPOR DATA SISWA ===");
        $this->info("Total Baris CSV: {$totalRows}");
        $this->info("Berhasil Diimpor: {$importedCount} siswa");
        $this->info("Dilewati / Sudah Terdaftar: {$skippedCount} siswa");

        $this->info("\n--- Rincian Berdasarkan Jurusan ---");
        ksort($byMajor);
        foreach ($byMajor as $maj => $cnt) {
            $this->line("  * {$maj}: {$cnt} siswa");
        }

        $this->info("\n--- Rincian Berdasarkan Tahun Keluar / Angkatan ---");
        ksort($byYear);
        foreach ($byYear as $yr => $cnt) {
            $this->line("  * Angkatan {$yr}: {$cnt} siswa");
        }

        return 0;
    }
}
