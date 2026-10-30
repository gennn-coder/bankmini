<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanKas extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_kas';

    protected $fillable = [
        'tanggal',
        'user_id',
        'nominal',
        'alasan',
        'status',
        'admin_id',
        'catatan_admin',
    ];

    public function teller()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id')->withTrashed();
    }
}
