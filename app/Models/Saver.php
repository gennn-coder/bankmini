<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Saver (Penabung)
 * 
 * Model penabung aktif yang telah memiliki buku rekening tabungan:
 * - Terhubung langsung dengan akun pengguna (User) dan data identitas siswa (Member)
 * - Menyimpan nomor rekening, PIN 6-digit keamanan transaksi, dan profil siswa
 * - Menangani relasi ke mutasi transaksi dan perjanjian penarikan H-1
 * - Menyediakan generator nomor rekening berurutan yang unik
 */
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

    /**
     * Relasi ke akun User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke buku Rekening tabungan
     */
    public function rekening()
    {
        return $this->hasOne(Rekening::class, 'saver_id');
    }

    /**
     * Relasi ke data Anggota Siswa (Member)
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke seluruh riwayat mutasi transaksi kas
     */
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'saver_id');
    }

    /**
     * Relasi ke daftar permohonan perjanjian penarikan H-1 (> Rp 1.000.000)
     */
    public function perjanjianPenarikan()
    {
        return $this->hasMany(PerjanjianPenarikan::class, 'saver_id');
    }

    /**
     * Aksesor tahun kelulusan siswa
     */
    public function getGraduationYearAttribute()
    {
        if ($this->relationLoaded('member') && $this->member) {
            return $this->member->graduation_year ?: '-';
        }
        $rel = $this->member;
        if ($rel && $rel->graduation_year) {
            return $rel->graduation_year;
        }
        $byNis = Member::where('nis', $this->nis)->first();
        return $byNis?->graduation_year ?? '-';
    }

    public function getAngkatanKeluarAttribute()
    {
        return $this->graduation_year;
    }

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

    /**
     * Generator Nomor Rekening Unik & Berurutan
     * Format: Angka berurutan dimulai dari 10001001
     * Menjamin tidak ada nomor rekening ganda bahkan jika ada data yang terhapus
     */
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
