<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Transaksi
 * 
 * Model inti pencatatan finansial Bank Mini Sekolah:
 * - Mencatat mutasi kas (Setoran & Penarikan)
 * - Terkoneksi dengan Database Trigger MySQL untuk pembaruan otomatis saldo rekening
 * - Terhubung dengan sistem akuntansi double-entry (JurnalAkuntansi)
 * - Menerapkan Soft Deletes untuk menjaga keutuhan jejak audit (Audit Trail)
 */
class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    // Nama tabel database
    protected $table = 'transaksi';

    // Kolom yang dapat diisi secara massal (Mass Assignment)
    protected $fillable = [
        'no_transaksi',
        'saver_id',
        'id_nasabah',
        'id_user',
        'saver_name',
        'account_number',
        'tanggal',
        'jenis_trans',
        'nominal',
        'keterangan',
    ];

    /**
     * Boot Model Event Listener
     * Menjaga kompatibilitas dua arah antara `saver_id` dan `id_nasabah`
     */
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            // Sinkronisasi otomatis saver_id dan id_nasabah sebelum data disimpan
            if (empty($model->id_nasabah) && !empty($model->saver_id)) {
                $model->id_nasabah = $model->saver_id;
            }
            if (empty($model->saver_id) && !empty($model->id_nasabah)) {
                $model->saver_id = $model->id_nasabah;
            }
        });
    }

    /**
     * Relasi ke model Penabung (Saver)
     * Tetap menampilkan data penabung meskipun akun telah di-soft-delete
     */
    public function saver()
    {
        return $this->belongsTo(Saver::class, 'saver_id')->withTrashed();
    }

    /**
     * Alias relasi ke model Nasabah (Kompatibilitas mundur)
     */
    public function nasabah()
    {
        return $this->belongsTo(Saver::class, 'saver_id')->withTrashed();
    }

    /**
     * Relasi ke Petugas Teller yang memproses transaksi
     */
    public function teller()
    {
        return $this->belongsTo(User::class, 'id_user')->withTrashed();
    }

    /**
     * Relasi ke entri Jurnal Akuntansi double-entry (Buku Besar)
     */
    public function jurnal()
    {
        return $this->hasMany(JurnalAkuntansi::class, 'id_transaksi');
    }

    /**
     * Aksesor: Nama siswa yang dijamin selalu tampil bahkan saat akun penabung terhapus
     */
    public function getStudentNameDisplayAttribute()
    {
        if ($this->saver && $this->saver->student_name) {
            return $this->saver->student_name . ($this->saver->trashed() ? ' (Akun Terhapus)' : '');
        }
        return $this->saver_name ?? 'Nasabah #' . ($this->saver_id ?? $this->id_nasabah);
    }

    /**
     * Aksesor: Nomor rekening yang dijamin selalu tampil
     */
    public function getAccountNumberDisplayAttribute()
    {
        return $this->saver->account_number ?? $this->account_number ?? '-';
    }
}
