<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';

    protected $fillable = [
        'user_id',
        'nama_pengadu',
        'nis',
        'kategori',
        'judul',
        'isi_pesan',
        'tanggapan_admin',
        'status',
        'ditanggapi_pada',
    ];

    protected $casts = [
        'ditanggapi_pada' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

