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
        Schema::create('perjanjian_penarikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saver_id')->constrained('savers')->onDelete('cascade');
            $table->unsignedBigInteger('nominal');
            $table->date('tanggal_rencana');
            $table->text('alasan')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'selesai', 'ditolak'])->default('menunggu');
            $table->foreignId('teller_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('diajukan_pada')->useCurrent();
            $table->timestamp('disetujui_pada')->nullable();
            $table->timestamp('selesai_pada')->nullable();
            $table->text('catatan_teller')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perjanjian_penarikan');
    }
};

