<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerjanjianPenarikan extends Model
{
    use HasFactory;

    protected $table = 'perjanjian_penarikan';

    protected $fillable = [
        'saver_id',
        'nominal',
        'tanggal_rencana',
        'alasan',
        'status',
        'teller_id',
        'diajukan_pada',
        'disetujui_pada',
        'selesai_pada',
        'catatan_teller',
    ];

    protected $casts = [
        'nominal' => 'integer',
        'tanggal_rencana' => 'date',
        'diajukan_pada' => 'datetime',
        'disetujui_pada' => 'datetime',
        'selesai_pada' => 'datetime',
    ];

    public function saver()
    {
        return $this->belongsTo(Saver::class, 'saver_id');
    }

    public function teller()
    {
        return $this->belongsTo(User::class, 'teller_id');
    }
}
