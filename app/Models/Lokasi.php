<?php

namespace App\Models;

use App\Enums\JenisLokasi;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Lokasi extends Model implements HasMedia
{
    use InteractsWithMedia, HasUlids, SoftDeletes;

    protected $table = 'lokasi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'slug',
        'alamat',
        'jenis_lokasi',
        'deskripsi',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'jenis_lokasi' => JenisLokasi::class
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 270, 180)
            ->nonQueued();
    }

    public function syncMediaName(){
        foreach( $this->getMedia('lokasi_foto') as $media){
            $media->file_name = getMediaFilename($this, $media);
            $media->save();
        }
    }
}
