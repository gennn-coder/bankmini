<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    public function member()
    {
        return $this->hasOne(Member::class, 'user_id');
    }

    public function saver()
    {
        return $this->hasOne(Saver::class, 'user_id');
    }

    public function nasabah()
    {
        return $this->hasOne(Saver::class, 'user_id');
    }

    public function transaksiTeller()
    {
        return $this->hasMany(Transaksi::class, 'id_user');
    }

    public function laporanHarianTeller()
    {
        return $this->hasMany(LaporanHarian::class, 'user_id');
    }

    public function laporanHarianSupervisor()
    {
        return $this->hasMany(LaporanHarian::class, 'supervisor_id');
    }

    public function isAdmin()
    {
        return $this->role === 'administrator';
    }

    public function isTeller()
    {
        return $this->role === 'teller';
    }

    public function isSupervisor()
    {
        // Admin acts as supervisor since roles are unified
        return in_array($this->role, ['supervisor', 'administrator']);
    }

    public function isNasabah()
    {
        return in_array($this->role, ['nasabah', 'saver']);
    }
}
