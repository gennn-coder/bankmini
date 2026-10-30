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
        Schema::table('laporan_harian', function (Blueprint $table) {
            $table->timestamp('submitted_at')->nullable()->after('selisih');
        });

        // Set existing records that were already approved or closed to have submitted_at
        DB::table('laporan_harian')
            ->where(function ($query) {
                $query->where('status_approval', 'approved')
                      ->orWhere('saldo_akhir_sistem', '>', 0)
                      ->orWhere('uang_fisik', '>', 0);
            })
            ->update([
                'submitted_at' => DB::raw('created_at')
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_harian', function (Blueprint $table) {
            $table->dropColumn('submitted_at');
        });
    }
};
