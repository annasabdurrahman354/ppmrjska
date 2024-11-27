<?php

namespace App\Models;

use App\Enums\JenisLokasi;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Lokasi extends Model implements HasMedia
{
    use InteractsWithMedia, HasUlids;

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

    public static function getForm()
    {
        return [
            Forms\Components\TextInput::make('nama')
                ->label('Nama Lokasi')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                    if (($get('slug') ?? '') !== Str::slug($old)) {
                        return;
                    }

                    $set('slug', Str::slug($state));
                }),
            Forms\Components\TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('alamat')
                ->label('Alamat')
                ->required(),
            Forms\Components\ToggleButtons::make('jenis_lokasi')
                ->label('Jenis Lokasi')
                ->required()
                ->inline()
                ->options(JenisLokasi::class),
            Forms\Components\Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->required()
                ->columnSpanFull(),
            Forms\Components\SpatieMediaLibraryFileUpload::make('foto')
                ->label('Foto')
                ->collection('lokasi_foto')
                ->conversion('thumb')
                ->moveFiles()
                ->image()
                ->imageEditor()
                ->imageResizeMode('cover')
                ->imageCropAspectRatio('3:2')
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
            Forms\Components\TextInput::make('latitude')
                ->required()
                ->numeric(),
            Forms\Components\TextInput::make('longitude')
                ->required()
                ->numeric(),
        ];
    }
}
