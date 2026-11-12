<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Rekening
 * 
 * Model rekening buku tabungan nasabah:
 * - Menyimpan nomor rekening, saldo aktual, dan status rekening (aktif/nonaktif)
 * - Nilai saldo dimutasi secara otomatis oleh Database Trigger MySQL pada tabel `transaksi`
 * - Mendukung sinkronisasi atribut dwibahasa (`account_number` dan `no_rekening`)
 */
class Rekening extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rekening';

    protected $fillable = [
        'saver_id',
        'id_nasabah',
        'account_number',
        'no_rekening',
        'saldo',
        'status',
    ];

    protected $appends = [
        'no_rekening',
    ];

    /**
     * Boot Model Event Listener
     * Sinkronisasi data identitas penabung dan nomor rekening sebelum penyimpanan
     */
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            // Sinkronisasi saver_id dan id_nasabah
            if (empty($model->id_nasabah) && !empty($model->saver_id)) {
                $model->id_nasabah = $model->saver_id;
            }
            if (empty($model->saver_id) && !empty($model->id_nasabah)) {
                $model->saver_id = $model->id_nasabah;
            }
            // Sinkronisasi account_number dan no_rekening
            if (empty($model->account_number) && !empty($model->no_rekening)) {
                $model->account_number = $model->no_rekening;
            }
            if (empty($model->no_rekening) && !empty($model->account_number)) {
                $model->no_rekening = $model->account_number;
            }
        });
    }

    /**
     * Relasi ke model Penabung (Saver)
     */
    public function saver()
    {
        return $this->belongsTo(Saver::class, 'saver_id')->withTrashed();
    }

    /**
     * Alias relasi ke model Nasabah
     */
    public function nasabah()
    {
        return $this->belongsTo(Saver::class, 'saver_id')->withTrashed();
    }

    /**
     * Aksesor nomor rekening
     */
    public function getNoRekeningAttribute()
    {
        return $this->account_number ?? $this->attributes['no_rekening'] ?? null;
    }

    /**
     * Mutator nomor rekening
     */
    public function setNoRekeningAttribute($value)
    {
        $this->attributes['account_number'] = $value;
        if (\Illuminate\Support\Facades\Schema::hasColumn('rekening', 'no_rekening')) {
            $this->attributes['no_rekening'] = $value;
        }
    }
}
