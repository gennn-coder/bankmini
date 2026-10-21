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
        if (Schema::hasTable('members') && !Schema::hasColumn('members', 'pin')) {
            Schema::table('members', function (Blueprint $table) {
                $table->string('pin', 255)->nullable()->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('members') && Schema::hasColumn('members', 'pin')) {
            Schema::table('members', function (Blueprint $table) {
                $table->dropColumn('pin');
            });
        }
    }
};
