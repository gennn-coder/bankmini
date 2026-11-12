<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Member (Anggota Siswa)
 * 
 * Model data induk siswa sekolah:
 * - Menyimpan data identitas siswa (Nama, NIS/NISN, Kelas/Jurusan, Angkatan Keluar)
 * - Berstatus 'rekening tidak aktif' saat baru didaftarkan atau diimpor
 * - Berubah status menjadi 'rekening aktif' setelah aktivasi dan setoran awal di loket Teller
 * - Berelasi satu-ke-satu dengan akun User dan entri Penabung (Saver)
 */
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
        'pin',
        'graduation_year',
        'status',
    ];

    protected $appends = [
        'nama_siswa',
        'kelas',
        'angkatan_keluar',
    ];

    /**
     * Relasi ke akun User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke profil Penabung (Saver)
     */
    public function saver()
    {
        return $this->hasOne(Saver::class, 'user_id', 'user_id');
    }

    /**
     * Aksesor dan Mutator Dwibahasa (Indonesian Aliases)
     */
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
