<?php

namespace App\Models;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Carousel extends Model implements HasMedia
{
    use InteractsWithMedia, HasUlids;

    protected $table = 'carousel';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'judul',
        'link_tujuan',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    protected function linkTujuan(): Attribute
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
        foreach( $this->getMedia('carousel_cover') as $media){
            $media->file_name = getMediaFilename($this, $media);
            $media->save();
        }
    }

    public static function getForm()
    {
        return [
            Section::make('Detail Carousel')
                ->schema([
                    TextInput::make('judul')
                        ->label('Judul')
                        ->live(true)
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    TextInput::make('link_tujuan')
                        ->label('Link Tujuan')
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Toggle::make('status_aktif')
                        ->label('Status Aktif')
                        ->live()
                        ->inline()
                        ->required(),
                    SpatieMediaLibraryFileUpload::make('cover')
                        ->label('Cover')
                        ->collection('carousel_cover')
                        ->conversion('thumb')
                        ->moveFiles()
                        ->image()
                        ->imageEditor()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('3:2')
                        ->optimize('jpg')
                        ->maxSize(1024 * 3)
                        ->required()
                        ->columnSpanFull(),
                ]),
        ];
    }
}
