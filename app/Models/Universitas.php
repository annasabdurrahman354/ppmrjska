<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Universitas extends Model implements HasMedia
{
    use InteractsWithMedia, HasUlids, SoftDeletes;

    protected $table = 'universitas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'slug',
        'alamat',
        'link_website',
        'latitude',
        'longitude',
    ];

    protected function linkWebsite(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? preg_replace("(^https?://)", "", $value) : null,
        );
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 270, 180)
            ->nonQueued();
    }

    public function syncMediaName(){
        foreach( $this->getMedia('universitas_foto') as $media){
            $media->file_name = getMediaFilename($this, $media);
            $media->save();
        }
    }
}
