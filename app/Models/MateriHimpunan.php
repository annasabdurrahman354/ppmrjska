<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriHimpunan extends Model
{
    use HasFactory;

    protected $table = 'materi_himpunan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
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
        'nama' => 'string',
        'jumlah_halaman' => 'integer',
        'halaman_awal' => 'integer',
        'halaman_akhir' => 'integer',
    ];
}
