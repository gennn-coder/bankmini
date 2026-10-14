<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
            if (empty($model->account_number) && !empty($model->no_rekening)) {
                $model->account_number = $model->no_rekening;
            }
            if (empty($model->no_rekening) && !empty($model->account_number)) {
                $model->no_rekening = $model->account_number;
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

    public function getNoRekeningAttribute()
    {
        return $this->account_number ?? $this->attributes['no_rekening'] ?? null;
    }

    public function setNoRekeningAttribute($value)
    {
        $this->attributes['account_number'] = $value;
        if (\Illuminate\Support\Facades\Schema::hasColumn('rekening', 'no_rekening')) {
            $this->attributes['no_rekening'] = $value;
        }
    }
}
