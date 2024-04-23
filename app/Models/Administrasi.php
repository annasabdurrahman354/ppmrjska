<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Administrasi extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'administrasi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tahun_ajaran',
        'batas_awal_pembayaran',
        'batas_akhir_pembayaran',
        'biaya_administrasi',
        'biaya_tambahan_santri_baru',
        'nama_bank',
        'nomor_rekening',
        'nama_pemilik_rekening',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $casts = [
        'batas_awal_pembayaran' => 'date',
        'batas_akhir_pembayaran' => 'date',
        'biaya_administrasi' => 'integer',
        'biaya_tambahan_santri_baru' => 'integer',
    ];
   
}
