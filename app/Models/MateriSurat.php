<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriSurat extends Model
{
    use HasFactory;

    protected $table = 'materi_surat';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomor',
        'nama',
        'jumlah_ayat',
        'jumlah_halaman',
        'halaman_awal',
        'halaman_akhir',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'nomor' => 'integer',
        'nama' => 'string',
        'jumlah_ayat' => 'integer',
        'jumlah_halaman' => 'integer',
        'halaman_awal' => 'integer',
        'halaman_akhir' => 'integer',
    ];
}
