<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Saver extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'savers';

    protected $fillable = [
        'user_id',
        'account_number',
        'nis',
        'student_name',
        'class_grade',
        'email',
        'security_pin',
    ];

    protected $hidden = [
        'security_pin',
    ];

    protected $appends = [
        'no_rekening',
        'nama_siswa',
        'kelas',
        'pin_keamanan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rekening()
    {
        return $this->hasOne(Rekening::class, 'saver_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'user_id', 'user_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'saver_id');
    }

    // Indonesian attribute backward compatibility
    public function getNoRekeningAttribute()
    {
        return $this->account_number;
    }

    public function setNoRekeningAttribute($value)
    {
        $this->attributes['account_number'] = $value;
    }

    public function getNamaSiswaAttribute()
    {
        return $this->student_name;
    }

    public function setNamaSiswaAttribute($value)
    {
        $this->attributes['student_name'] = $value;
    }

    public function getKelasAttribute()
    {
        return $this->class_grade;
    }

    public function setKelasAttribute($value)
    {
        $this->attributes['class_grade'] = $value;
    }

    public function getPinKeamananAttribute()
    {
        return $this->security_pin;
    }

    public function setPinKeamananAttribute($value)
    {
        $this->attributes['security_pin'] = $value;
    }

    public static function generateNomorRekening()
    {
        $lastRek = self::withTrashed()
            ->whereRaw('account_number REGEXP "^[0-9]+$"')
            ->orderByRaw('CAST(account_number AS UNSIGNED) DESC')
            ->value('account_number');

        if ($lastRek && is_numeric($lastRek) && (int)$lastRek >= 10001000) {
            $nextNumber = (int)$lastRek + 1;
        } else {
            $nextNumber = 10001001;
        }

        while (
            self::withTrashed()->where('account_number', (string)$nextNumber)->exists() ||
            Rekening::where('account_number', (string)$nextNumber)->exists()
        ) {
            $nextNumber++;
        }

        return (string)$nextNumber;
    }
}
