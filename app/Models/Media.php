<?php

namespace App\Models;

use App\Enums\SumberMedia;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Joshembling\ImageOptimizer\Components\SpatieMediaLibraryFileUpload;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Media extends Model implements HasMedia
{
    protected $table = 'redaksi_media';

    use HasTags, HasUlids;
    use InteractsWithMedia;

    protected $fillable = [
        'judul',
        'slug',
        'sumber',
        'link_tujuan',
        'deskripsi',
        'embed',
        'kategori_id',
        'pengunggah_id',
        'highlight'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'string' => SumberMedia::class,
    ];

    protected function linkTujuan(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? preg_replace("(^https?://)", "", $value) : null,
        );
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function pengunggah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengunggah_id');
    }

    public function relatedBlogByTag($take = 3)
    {
        return $this->withAnyTags($this->tags()->pluck('name'))
            ->where('id', '!=', $this->id)
            ->latest()
            ->take($take)
            ->get();
    }

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 270, 180)
            ->nonQueued();
    }

    public function syncMediaName(){
        foreach( $this->getMedia('media_cover') as $media){
            $media->file_name = getMediaFilename($this, $media);
            $media->save();
        }
    }

    public static function getForm()
    {
        return [
            Section::make('Detail Media')
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
                    Select::make('sumber')
                        ->label('Sumber')
                        ->options(SumberMedia::class)
                        ->required(),
                    TextInput::make('link_tujuan')
                        ->label('Link Tujuan')
                        ->required(),
                    Textarea::make('embed')
                        ->label('Embed')
                        ->columnSpanFull(),
                    Textarea::make('deskripsi')
                        ->label('Deskripsi')
                        ->required()
                        ->columnSpanFull(),
                    SpatieMediaLibraryFileUpload::make('cover')
                        ->label('Cover')
                        ->collection('media_cover')
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
                    Select::make('kategori_id')
                        ->label('Kategori')
                        ->createOptionForm(Kategori::getForm())
                        ->createOptionUsing(function($data){
                            $kategori = new Kategori();
                            $kategori->fill($data);
                            $kategori->save();
                            return $kategori->id;
                        })
                        ->relationship('kategori', 'nama')
                        ->searchable()
                        ->preload()
                        ->required(),
                    SpatieTagsInput::make('tag')
                        ->label('Tag')
                        ->required(),
                    Select::make('pengunggah_id')
                        ->relationship('pengunggah', 'nama')
                        ->nullable(false)
                        ->default(auth()->id()),
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
                ]),
        ];
    }
}
