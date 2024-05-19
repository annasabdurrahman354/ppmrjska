<?php

namespace App\Models;

use App\Enums\JenisKelamin;
use App\Enums\KepemilikanGedung;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'nama_pemilik',
        'kontak_pemilik',
        'kepemilikan_gedung',

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
        'kepemilikan_gedung' => KepemilikanGedung::class,
    ];


    public function kamarAsrama(): HasMany
    {
        return $this->hasMany(KamarAsrama::class);
    }

    public function biayaAsrama(): HasMany
    {
        return $this->hasMany(BiayaAsrama::class);
    }

    protected function tagihanAsramaTerbaru(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->biayaAsrama()->latest()->first()?->biaya_kamar_tahunan,
        );
    }
}
