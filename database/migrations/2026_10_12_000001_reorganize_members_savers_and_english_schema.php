<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Rename tabel 'nasabah' menjadi 'savers' jika ada, atau buat jika belum ada
        if (Schema::hasTable('nasabah') && !Schema::hasTable('savers')) {
            Schema::rename('nasabah', 'savers');
        }

        // 2. Sesuaikan tabel savers ke bahasa Inggris & tambah SoftDeletes
        if (Schema::hasTable('savers')) {
            Schema::table('savers', function (Blueprint $table) {
                if (!Schema::hasColumn('savers', 'account_number') && Schema::hasColumn('savers', 'no_rekening')) {
                    $table->renameColumn('no_rekening', 'account_number');
                }
                if (!Schema::hasColumn('savers', 'student_name') && Schema::hasColumn('savers', 'nama_siswa')) {
                    $table->renameColumn('nama_siswa', 'student_name');
                }
                if (!Schema::hasColumn('savers', 'class_grade') && Schema::hasColumn('savers', 'kelas')) {
                    $table->renameColumn('kelas', 'class_grade');
                }
                if (!Schema::hasColumn('savers', 'security_pin') && Schema::hasColumn('savers', 'pin_keamanan')) {
                    $table->renameColumn('pin_keamanan', 'security_pin');
                }
                if (!Schema::hasColumn('savers', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        // 3. Buat Tabel 'members' (Anggota Siswa yang belum menabung / mengaktifkan rekening)
        if (!Schema::hasTable('members')) {
            Schema::create('members', function (Blueprint $table) {
                // user_id sebagai Primary Key + Foreign Key ke tabel users
                $table->unsignedBigInteger('user_id')->primary();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->string('student_name', 100);
                $table->string('nis', 20)->unique();
                $table->string('class_grade', 20)->nullable();
                $table->string('graduation_year', 10)->comment('Angkatan Keluar');
                $table->enum('status', ['rekening aktif', 'rekening tidak aktif'])->default('rekening tidak aktif');
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // 4. Update tabel 'transaksi' untuk menambahkan saver_id, snapshot saver_name & account_number, dan softDeletes
        if (Schema::hasTable('transaksi')) {
            Schema::table('transaksi', function (Blueprint $table) {
                if (!Schema::hasColumn('transaksi', 'saver_id')) {
                    $table->unsignedBigInteger('saver_id')->nullable()->after('no_transaksi');
                }
                if (!Schema::hasColumn('transaksi', 'saver_name')) {
                    $table->string('saver_name', 100)->nullable()->after('saver_id');
                }
                if (!Schema::hasColumn('transaksi', 'account_number')) {
                    $table->string('account_number', 20)->nullable()->after('saver_name');
                }
                if (!Schema::hasColumn('transaksi', 'deleted_at')) {
                    $table->softDeletes();
                }
            });

            // Isi nilai awal saver_id, saver_name, account_number dari data nasabah/saver lama jika ada id_nasabah
            if (Schema::hasColumn('transaksi', 'id_nasabah')) {
                DB::statement("UPDATE transaksi t 
                    INNER JOIN savers s ON t.id_nasabah = s.id 
                    SET t.saver_id = s.id, 
                        t.saver_name = s.student_name, 
                        t.account_number = s.account_number 
                    WHERE t.saver_id IS NULL");
            }
        }

        // 5. Update tabel 'rekening' untuk menambahkan saver_id jika belum ada
        if (Schema::hasTable('rekening')) {
            Schema::table('rekening', function (Blueprint $table) {
                if (!Schema::hasColumn('rekening', 'saver_id')) {
                    $table->unsignedBigInteger('saver_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('rekening', 'account_number') && Schema::hasColumn('rekening', 'no_rekening')) {
                    $table->renameColumn('no_rekening', 'account_number');
                }
                if (!Schema::hasColumn('rekening', 'deleted_at')) {
                    $table->softDeletes();
                }
            });

            if (Schema::hasColumn('rekening', 'id_nasabah')) {
                DB::statement("UPDATE rekening SET saver_id = id_nasabah WHERE saver_id IS NULL");
            }
        }

        // 6. Update tabel 'users' untuk menambahkan softDeletes
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
        // Rollback columns if needed
    }
};
