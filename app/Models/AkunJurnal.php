<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunJurnal extends Model
{
    use HasFactory;

    protected $table = 'akun_jurnal';
    protected $primaryKey = 'kode_akun';
    public $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_akun',
        'nama_akun',
        'jenis_akun',
    ];

    public function jurnalEntries()
    {
        return $this->hasMany(JurnalAkuntansi::class, 'kode_akun', 'kode_akun');
    }
}
