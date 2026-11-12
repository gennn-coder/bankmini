<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * 
 * Model otentikasi akun pengguna sistem Bank Mini:
 * - Mendukung multi-role: administrator, teller, nasabah
 * - Menyimpan username login, password hash (Bcrypt), nama lengkap, dan status akun (aktif/terblokir)
 * - Berelasi dengan Member, Saver, Transaksi Teller, dan Laporan Harian
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'username',
        'password',
        'nama_petugas',
        'email',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi ke data Anggota Siswa (Member)
     */
    public function member()
    {
        return $this->hasOne(Member::class, 'user_id');
    }

    /**
     * Relasi ke data Penabung Aktif (Saver)
     */
    public function saver()
    {
        return $this->hasOne(Saver::class, 'user_id');
    }

    /**
     * Alias relasi ke data Nasabah
     */
    public function nasabah()
    {
        return $this->hasOne(Saver::class, 'user_id');
    }

    /**
     * Relasi ke transaksi yang ditangani oleh User ini sebagai Teller
     */
    public function transaksiTeller()
    {
        return $this->hasMany(Transaksi::class, 'id_user');
    }

    /**
     * Relasi ke laporan penutupan kas harian teller
     */
    public function laporanHarianTeller()
    {
        return $this->hasMany(LaporanHarian::class, 'user_id');
    }

    /**
     * Relasi ke laporan yang disetujui / diverifikasi oleh User ini sebagai Supervisor/Admin
     */
    public function laporanHarianSupervisor()
    {
        return $this->hasMany(LaporanHarian::class, 'supervisor_id');
    }

    /**
     * Helper pengecekan peran Administrator
     */
    public function isAdmin()
    {
        return $this->role === 'administrator';
    }

    /**
     * Helper pengecekan peran Teller
     */
    public function isTeller()
    {
        return $this->role === 'teller';
    }

    /**
     * Helper pengecekan peran Supervisor (Menyatu dengan Administrator)
     */
    public function isSupervisor()
    {
        return in_array($this->role, ['supervisor', 'administrator']);
    }

    /**
     * Helper pengecekan peran Nasabah / Siswa Penabung
     */
    public function isNasabah()
    {
        return in_array($this->role, ['nasabah', 'saver']);
    }
}
