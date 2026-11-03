<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';
    protected $fillable = ['key', 'value', 'keterangan'];

    public static function getStatusLayanan(): string
    {
        try {
            return DB::table('pengaturan')->where('key', 'status_layanan_teller')->value('value') ?? 'buka';
        } catch (\Throwable $e) {
            return 'buka';
        }
    }

    public static function setStatusLayanan(string $status): void
    {
        $status = in_array($status, ['buka', 'istirahat', 'tutup']) ? $status : 'buka';
        
        DB::table('pengaturan')->updateOrInsert(
            ['key' => 'status_layanan_teller'],
            [
                'value' => $status,
                'keterangan' => 'Status operasional layanan loket teller (buka, istirahat, tutup)',
                'updated_at' => now(),
            ]
        );

        Cache::forget('status_layanan_teller');
    }
}
