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
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'email')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('email', 100)->nullable()->after('nama_petugas');
            });
        }

        if (Schema::hasTable('members') && !Schema::hasColumn('members', 'email')) {
            Schema::table('members', function (Blueprint $table) {
                $table->string('email', 100)->nullable()->after('class_grade');
            });
        }

        // Sinkronisasi data email yang sudah ada di tabel savers ke users dan members
        try {
            $savers = DB::table('savers')->whereNotNull('email')->where('email', '!=', '')->get();
            foreach ($savers as $saver) {
                if ($saver->user_id) {
                    DB::table('users')->where('id', $saver->user_id)->update(['email' => $saver->email]);
                    DB::table('members')->where('user_id', $saver->user_id)->update(['email' => $saver->email]);
                }
            }
        } catch (\Exception $e) {
            // Abaikan jika tabel belum siap
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('members') && Schema::hasColumn('members', 'email')) {
            Schema::table('members', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'email')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }
    }
};
