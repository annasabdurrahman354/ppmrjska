<?php

namespace App\Models;

use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Universitas extends Model implements HasMedia
{
    use InteractsWithMedia, HasUlids;

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

    public static function getForm()
    {
        return [
            TextInput::make('nama')
                ->label('Nama Universitas')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                    if (($get('slug') ?? '') !== Str::slug($old)) {
                        return;
                    }

                    $set('slug', Str::slug($state));
                }),
            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(255),
            TextInput::make('alamat')
                ->label('Alamat')
                ->required(),
            TextInput::make('link_website')
                ->label('Link Website')
                ->required()
                ->url(),
           SpatieMediaLibraryFileUpload::make('foto')
                ->label('Foto')
                ->collection('universitas_foto')
                ->conversion('thumb')
                ->moveFiles()
                ->image()
                ->imageEditor()
                ->imageResizeMode('cover')
                ->imageCropAspectRatio('3:2')
                ->optimize('jpg')
                ->maxSize(1024 * 3)
                ->rules('dimensions:max_width=1080,max_height=720')
                ->required()
                ->columnSpanFull(),
            Map::make('lokasi')
                ->label('Lokasi')
                ->required()
                ->columnSpanFull()
                ->afterStateUpdated(function (Get $get, Set $set, string|array|null $old, ?array $state): void {
                    $set('latitude', $state['lat']);
                    $set('longitude', $state['lng']);
                })
                ->afterStateHydrated(function ($state, $record, Set $set): void {
                    if ($record){
                        $set('lokasi', ['lat' => $record->latitude, 'lng' => $record->longitude]);
                    }
                })
                ->extraStyles([
                    'min-height: 100vh',
                    'border-radius: 12px'
                ])
                ->liveLocation()
                ->showMarker()
                ->markerColor("#22c55eff")
                ->showFullscreenControl()
                ->showZoomControl()
                ->draggable()
                ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                ->zoom(15)
                ->detectRetina()
                ->showMyLocationButton()
                ->extraTileControl([])
                ->extraControl([
                    'zoomDelta'           => 1,
                    'zoomSnap'            => 2,
                ]),
            TextInput::make('latitude')
                ->required()
                ->numeric(),
            TextInput::make('longitude')
                ->required()
                ->numeric(),
        ];
    }

    public static function getInfolist()
    {

    }
}
