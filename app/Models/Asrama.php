<?php

namespace App\Models;

use App\Enums\JenisKelamin;
use App\Enums\KepemilikanGedung;
use App\Enums\PembebananBiayaAsrama;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Asrama extends Model implements HasMedia
{
    use InteractsWithMedia, HasUlids, SoftDeletes;

    protected $table = 'asrama';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'slug',
        'penghuni',
        'alamat',
        'deskripsi',
        'latitude',
        'longitude',
        'kapasitas_per_kamar',
        'kapasitas_total',
        'kepemilikan_gedung',
        'nama_pemilik',
        'nomor_telepon_pemilik',
        'biaya_asrama_tahunan',
        'pembebanan_biaya_asrama'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $casts = [
        'penghuni' => JenisKelamin::class,
        'kapasitas_per_kamar' => 'integer',
        'kepemilikan_gedung' => KepemilikanGedung::class,
        'biaya_asrama_tahunan' => 'integer',
        'pembebanan_biaya_asrama' => PembebananBiayaAsrama::class
    ];


    public function kamarAsrama(): HasMany
    {
        return $this->hasMany(KamarAsrama::class);
    }

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 270, 180)
            ->nonQueued();
    }

    public function syncMediaName(){
        foreach( $this->getMedia('asrama_foto') as $media){
            $media->file_name = getMediaFilename($this, $media);
            $media->save();
        }
    }
}
