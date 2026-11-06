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
        // 1. Tabel Target Tabungan (Celengan Impian)
        if (!Schema::hasTable('target_tabungan')) {
            Schema::create('target_tabungan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->unsignedBigInteger('saver_id')->nullable();
                $table->string('judul_target');
                $table->bigInteger('nominal_target');
                $table->text('keterangan')->nullable();
                $table->date('target_tanggal')->nullable();
                $table->enum('status', ['berjalan', 'tercapai'])->default('berjalan');
                $table->timestamps();
            });
        }

        // 2. Tabel Antrean Bank Mini Live
        if (!Schema::hasTable('antrean')) {
            Schema::create('antrean', function (Blueprint $table) {
                $table->id();
                $table->string('nomor_antrean');
                $table->integer('angka_antrean');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('nama_siswa');
                $table->string('nis')->nullable();
                $table->date('tanggal');
                $table->enum('status', ['menunggu', 'dilayani', 'selesai', 'batal'])->default('menunggu');
                $table->timestamp('dipanggil_pada')->nullable();
                $table->timestamp('selesai_pada')->nullable();
                $table->timestamps();

                $table->index(['tanggal', 'status']);
            });
        }

        // 3. Tabel Pengaduan Nasabah ke Admin
        if (!Schema::hasTable('pengaduan')) {
            Schema::create('pengaduan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('nama_pengadu');
                $table->string('nis');
                $table->string('kategori')->default('Umum');
                $table->string('judul');
                $table->text('isi_pesan');
                $table->text('tanggapan_admin')->nullable();
                $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
                $table->timestamp('ditanggapi_pada')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
        Schema::dropIfExists('antrean');
        Schema::dropIfExists('target_tabungan');
    }
};
