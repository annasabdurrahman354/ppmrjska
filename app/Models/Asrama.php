<?php

namespace App\Models;

use App\Enums\JenisKelamin;
use App\Enums\StatusKepemilikan;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asrama extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'asrama';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'kapasitas_per_kamar',
        'biaya_kamar',
        'pemilik',
        'kontak_pemilik',
        'status_kepemilikan',
        
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $casts = [
        'jenis_kelamin' => JenisKelamin::class,
        'kapasitas_per_kamar' => 'integer',
        'biaya_kamar' => 'array',
        'status_kepemilikan' => StatusKepemilikan::class,
    ];
   
}
