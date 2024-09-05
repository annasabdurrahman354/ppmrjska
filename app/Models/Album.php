<?php

namespace App\Models;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Tags\HasTags;

class Album extends Model
{
    use HasTags, HasUlids;

    protected $table = 'album';

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'kategori_id',
        'pengunggah_id',
        'highlight',
        'seo_judul',
        'seo_deskripsi',
        'seo_keyword',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function pengunggah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengunggah_id');
    }

    public function fotoAlbum(): HasMany
    {
        return $this->hasMany(FotoAlbum::class);
    }


    public function relatedAlbumByTag($take = 3)
    {
        return $this->withAnyTags($this->tags()->pluck('name'))
            ->where('id', '!=', $this->id)
            ->latest()
            ->take($take)
            ->get();
    }

    public static function getForm()
    {
        return [
            Section::make('Detail Album')
                ->schema([
                    TextInput::make('judul')
                        ->label('Judul')
                        ->live(true)
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            if (!filled($get('slug')) || $get('slug') === Str::slug($state)){
                                $set('slug', Str::slug($state));
                            }
                        })
                        ->required()
                        ->maxLength(255),
                    TextInput::make('slug')
                        ->label('Slug')
                        ->unique(ignoreRecord: true)
                        ->required(),
                    Textarea::make('deskripsi')
                        ->label('Deskripsi')
                        ->live(true)
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            if (!filled($get('seo_deskripsi')) || $get('seo_deskripsi') === $state){
                                $set('seo_deskripsi',$state);
                            }
                        })
                        ->required()
                        ->columnSpanFull(),
                    Select::make('kategori_id')
                        ->label('Kategori')
                        ->createOptionForm(Kategori::getForm())
                        ->relationship('kategori', 'nama')
                        ->searchable()
                        ->preload()
                        ->required(),
                    SpatieTagsInput::make('tag')
                        ->label('Tag')
                        ->live(true)
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            if (!filled($get('seo_keyword'))){
                                $set('seo_keyword',$state);
                            }
                        })
                        ->required(),
                    Select::make('pengunggah_id')
                        ->label('Pengunggah')
                        ->relationship('pengunggah', 'nama')
                        ->default(auth()->id())
                        ->searchable()
                        ->preload()
                        ->required(),
                    Repeater::make('fotoAlbum')
                        ->label('Foto Album')
                        ->relationship('fotoAlbum')
                        ->addable()
                        ->addActionLabel('+ Foto Album')
                        ->deletable()
                        ->reorderable()
                        ->minItems(1)
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('foto')
                                ->label('Cover')
                                ->collection('album_foto')
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
                            Textarea::make('deskripsi')
                                ->label('Deskripsi')
                                ->columnSpanFull(),
                        ]),
                    ToggleButtons::make('highlight')
                        ->label('Highlight')
                        ->helperText('Pilih "Ya" jika ingin menampilkan di landing page.')
                        ->options([
                            true => 'Ya',
                            false => 'Tidak'
                        ])
                        ->default(false)
                        ->inline()
                        ->grouped(),
                    TextInput::make('seo_judul')
                        ->label('SEO Judul')
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Textarea::make('seo_deskripsi')
                        ->label('SEO Deskripsi')
                        ->columnSpanFull(),
                    SpatieTagsInput::make('seo_keyword')
                        ->label('SEO Keyword')
                        ->columnSpanFull(),

                ]),
        ];
    }
}
