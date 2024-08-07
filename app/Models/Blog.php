<?php

namespace App\Models;

use App\Enums\StatusBlog;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Forms\Set;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Blog extends Model implements HasMedia
{
    use HasTags, HasUlids;
    use InteractsWithMedia;

    protected $table = 'blog';

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'konten',
        'kategori_id',
        'penulis_id',
        'highlight',
        'seo_judul',
        'seo_deskripsi',
        'seo_keyword',
        'status',
        'diterbitkan_pada',
        'dijadwalkan_pada',
    ];

    protected $dates = [
        'diterbitkan_pada',
        'dijadwalkan_pada',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'diterbitkan_pada' => 'datetime',
        'dijadwalkan_pada' => 'datetime',
        'status' => StatusBlog::class,
        'seo_keyword' => 'array'
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    //public function komentar(): HasMany
    //{
    //    return $this->hasMany(Comment::class);
    //}

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penulis_id');
    }

    public function isBelumTerbit()
    {
        return ! $this->isTerbit();
    }

    public function scopeTerbit(Builder $query)
    {
        return $query->where('status', StatusBlog::TERBIT)->latest('diterbitkan_pada');
    }

    public function scopeTerjadwal(Builder $query)
    {
        return $query->where('status', StatusBlog::TERJADWAL)->latest('dijadwalkan_pada');
    }

    public function scopeTertunda(Builder $query)
    {
        return $query->where('status', StatusBlog::TERTUNDA)->latest('created_at');
    }

    public function tanggalTerbit()
    {
        return $this->diterbitkan_pada?->format('d M Y');
    }

    public function isTerjadwal()
    {
        return $this->status === StatusBlog::TERJADWAL;
    }

    public function isTerbit()
    {
        return $this->status === StatusBlog::TERBIT;
    }

    public function relatedBlogByTag($take = 3)
    {
        return $this->withAnyTags($this->tags()->pluck('name'))
            ->where('id', '!=', $this->id)
            ->where('status', StatusBlog::TERBIT)
            ->latest('diterbitkan_pada')
            ->with('penulis')
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
        foreach( $this->getMedia('blog_cover') as $media){
            $media->file_name = getMediaFilename($this, $media);
            $media->save();
        }
    }

    public static function getForm()
    {
        return [
            Section::make('Detail Blog')
                ->schema([
                    TextInput::make('judul')
                        ->label('Judul')
                        ->live(true)
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            if (!filled($get('slug')) || $get('slug') === Str::slug($state)){
                                $set('slug', Str::slug($state));
                            }
                            if (!filled($get('seo_judul')) || $get('seo_judul') === $state){
                                $set('seo_judul', $state);
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

                    SpatieMediaLibraryFileUpload::make('cover')
                        ->label('Cover')
                        ->collection('blog_cover')
                        ->conversion('thumb')
                        ->moveFiles()
                        ->image()
                        ->optimize('jpg')
                        ->imageEditor()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('3:2')
                        ->maxSize(1024 * 3)
                        ->rules('dimensions:max_width=1080,max_height=720')
                        ->required()
                        ->columnSpanFull(),

                    TiptapEditor::make('konten')
                        ->profile('default')
                        ->disableFloatingMenus()
                        ->extraInputAttributes(['style' => 'max-height: 30rem; min-height: 24rem'])
                        ->directory('blog') // optional, defaults to config setting
                        ->maxSize(1024 * 3) // optional, defaults to config setting
                        ->required(),

                    Select::make('kategori_id')
                        ->label('Kategori')
                        ->preload()
                        ->createOptionForm(Kategori::getForm())
                        ->searchable()
                        ->relationship('kategori', 'nama')
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

                    Select::make('penulis_id')
                        ->relationship('penulis', 'nama')
                        ->nullable(false)
                        ->default(auth()->id())
                        ->required(),

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

                    ToggleButtons::make('status')
                        ->live()
                        ->inline()
                        ->options(StatusBlog::class)
                        ->required(),

                    DateTimePicker::make('dijadwalkan_pada')
                        ->visible(function ($get) {
                            return $get('status') === StatusBlog::TERJADWAL->value;
                        })
                        ->required(function ($get) {
                            return $get('status') === StatusBlog::TERJADWAL->value;
                        })
                        ->minDate(now()->addMinutes(10))
                        ->native(false),
                ]),

        ];
    }
}
