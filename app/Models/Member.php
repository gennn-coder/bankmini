<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'members';
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'student_name',
        'nis',
        'class_grade',
        'email',
        'graduation_year',
        'status',
    ];

    protected $appends = [
        'nama_siswa',
        'kelas',
        'angkatan_keluar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function saver()
    {
        return $this->hasOne(Saver::class, 'user_id', 'user_id');
    }

    // Indonesian attribute aliases
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

    public function getAngkatanKeluarAttribute()
    {
        return $this->graduation_year;
    }

    public function setAngkatanKeluarAttribute($value)
    {
        $this->attributes['graduation_year'] = $value;
    }
}
