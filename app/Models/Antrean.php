<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrean extends Model
{
    use HasFactory;

    protected $table = 'antrean';

    protected $fillable = [
        'nomor_antrean',
        'angka_antrean',
        'user_id',
        'nama_siswa',
        'nis',
        'tanggal',
        'status',
        'dipanggil_pada',
        'selesai_pada',
    ];

    protected $casts = [
        'angka_antrean' => 'integer',
        'tanggal' => 'date',
        'dipanggil_pada' => 'datetime',
        'selesai_pada' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper untuk mengambil nomor antrean berikutnya hari ini
     */
    public static function generateNomorHariIni(): array
    {
        $today = date('Y-m-d');
        $maxAngka = self::where('tanggal', $today)->max('angka_antrean') ?: 0;
        $nextAngka = $maxAngka + 1;
        $nomor = 'A-' . str_pad($nextAngka, 2, '0', STR_PAD_LEFT);

        return [
            'angka' => $nextAngka,
            'nomor' => $nomor,
        ];
    }
}

