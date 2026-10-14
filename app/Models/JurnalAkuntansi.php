<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalAkuntansi extends Model
{
    use HasFactory;

    protected $table = 'jurnal_akuntansi';
    public $timestamps = false;

    protected $fillable = [
        'id_transaksi',
        'kode_akun',
        'posisi',
        'jumlah',
        'tanggal',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }

    public function akun()
    {
        return $this->belongsTo(AkunJurnal::class, 'kode_akun', 'kode_akun');
    }
}
