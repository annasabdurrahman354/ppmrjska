<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'pendaftaran';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'tahun_pendaftaran',
        'kontak_panitia',
        'kontak_pengurus',
        'berkas_pendaftaran',
        'indikator_penilaian',
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tahun_pendaftaran' => 'integer',
        'kontak_panitia' => 'array',
        'kontak_pengurus' => 'array',
        'berkas_pendaftaran' => 'array',
        'indikator_penilaian' => 'array',
    ];

    public function gelombangPendaftaran(): HasMany
    {
        return $this->hasMany(GelombangPendaftaran::class);
    }

    function calonSantri(): HasManyThrough
    {
        return $this->hasManyThrough(CalonSantri::class, GelombangPendaftaran::class);
    }
}
