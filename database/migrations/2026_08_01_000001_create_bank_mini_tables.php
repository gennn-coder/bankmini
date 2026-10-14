<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel Nasabah
        Schema::create('nasabah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->onDelete('cascade');
            $table->string('no_rekening', 20)->unique();
            $table->string('nis', 20)->unique();
            $table->string('nama_siswa', 100);
            $table->string('kelas', 20);
            $table->string('email', 100)->nullable();
            $table->string('pin_keamanan'); // PIN 6 Digit
            $table->timestamps();
        });

        // 2. Tabel Rekening
        Schema::create('rekening', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->constrained('nasabah')->onDelete('restrict');
            $table->string('no_rekening', 20)->unique();
            $table->bigInteger('saldo')->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        // 3. Tabel Transaksi
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi', 30)->unique();
            $table->foreignId('id_nasabah')->constrained('nasabah')->onDelete('restrict');
            $table->foreignId('id_user')->comment('ID Teller')->constrained('users')->onDelete('restrict');
            $table->dateTime('tanggal')->useCurrent();
            $table->enum('jenis_trans', ['setoran', 'penarikan']);
            $table->bigInteger('nominal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 4. Tabel Akun Jurnal
        Schema::create('akun_jurnal', function (Blueprint $table) {
            $table->string('kode_akun', 10)->primary();
            $table->string('nama_akun', 100);
            $table->enum('jenis_akun', ['aset', 'liabilitas', 'ekuitas', 'pendapatan', 'beban']);
        });

        // 5. Tabel Jurnal Akuntansi
        Schema::create('jurnal_akuntansi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_transaksi')->constrained('transaksi')->onDelete('restrict');
            $table->string('kode_akun', 10);
            $table->foreign('kode_akun')->references('kode_akun')->on('akun_jurnal')->onDelete('restrict');
            $table->enum('posisi', ['debit', 'kredit']);
            $table->bigInteger('jumlah');
            $table->dateTime('tanggal')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
        });

        // 6. Tabel Laporan Harian
        Schema::create('laporan_harian', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('user_id')->comment('ID Teller')->constrained('users')->onDelete('restrict');
            $table->bigInteger('saldo_awal')->default(0);
            $table->bigInteger('total_setoran')->default(0);
            $table->bigInteger('total_penarikan')->default(0);
            $table->bigInteger('saldo_akhir_sistem')->default(0);
            $table->bigInteger('uang_fisik')->default(0);
            $table->bigInteger('selisih')->default(0);
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // 7. Tabel Audit Logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('aktivitas', 255);
            $table->text('detail')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('laporan_harian');
        Schema::dropIfExists('jurnal_akuntansi');
        Schema::dropIfExists('akun_jurnal');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('rekening');
        Schema::dropIfExists('nasabah');
    }
};
