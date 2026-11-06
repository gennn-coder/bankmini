<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetTabungan extends Model
{
    use HasFactory;

    protected $table = 'target_tabungan';

    protected $fillable = [
        'user_id',
        'saver_id',
        'judul_target',
        'nominal_target',
        'keterangan',
        'target_tanggal',
        'status',
    ];

    protected $casts = [
        'nominal_target' => 'integer',
        'target_tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function saver()
    {
        return $this->belongsTo(Saver::class, 'saver_id');
    }
}
