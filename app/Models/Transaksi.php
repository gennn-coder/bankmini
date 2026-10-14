<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksi';

    protected $fillable = [
        'no_transaksi',
        'saver_id',
        'id_nasabah',
        'id_user',
        'saver_name',
        'account_number',
        'tanggal',
        'jenis_trans',
        'nominal',
        'keterangan',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (empty($model->id_nasabah) && !empty($model->saver_id)) {
                $model->id_nasabah = $model->saver_id;
            }
            if (empty($model->saver_id) && !empty($model->id_nasabah)) {
                $model->saver_id = $model->id_nasabah;
            }
        });
    }

    public function saver()
    {
        return $this->belongsTo(Saver::class, 'saver_id')->withTrashed();
    }

    public function nasabah()
    {
        return $this->belongsTo(Saver::class, 'saver_id')->withTrashed();
    }

    public function teller()
    {
        return $this->belongsTo(User::class, 'id_user')->withTrashed();
    }

    public function jurnal()
    {
        return $this->hasMany(JurnalAkuntansi::class, 'id_transaksi');
    }

    /**
     * Get guaranteed student name even if account was deleted
     */
    public function getStudentNameDisplayAttribute()
    {
        if ($this->saver && $this->saver->student_name) {
            return $this->saver->student_name . ($this->saver->trashed() ? ' (Akun Terhapus)' : '');
        }
        return $this->saver_name ?? 'Nasabah #' . ($this->saver_id ?? $this->id_nasabah);
    }

    /**
     * Get guaranteed account number even if account was deleted
     */
    public function getAccountNumberDisplayAttribute()
    {
        return $this->saver->account_number ?? $this->account_number ?? '-';
    }
}
