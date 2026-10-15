<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanHarian extends Model
{
    use HasFactory;

    protected $table = 'laporan_harian';

    protected $fillable = [
        'tanggal',
        'user_id',
        'saldo_awal',
        'total_setoran',
        'total_penarikan',
        'saldo_akhir_sistem',
        'uang_fisik',
        'selisih',
        'status_approval',
        'catatan_penolakan',
        'supervisor_id',
    ];

    public function teller()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id')->withTrashed();
    }

    /**
     * Get all transactions on this report date by teller, preserving soft-deleted savers
     */
    public function getTransaksisAttribute()
    {
        return Transaksi::with(['saver' => function ($query) {
            $query->withTrashed();
        }])
        ->where('id_user', $this->user_id)
        ->whereDate('tanggal', $this->tanggal)
        ->orderBy('tanggal', 'asc')
        ->get();
    }
}
