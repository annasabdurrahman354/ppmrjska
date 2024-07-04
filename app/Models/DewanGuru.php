<?php

namespace App\Models;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class DewanGuru extends Model implements HasMedia
{
    use InteractsWithMedia, HasUlids, SoftDeletes;

    protected $table = 'dewan_guru';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'nama_panggilan',
        'nomor_telepon',
        'email',
        'alamat',
        'status_aktif',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status_aktif' => 'boolean',
    ];


    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 256, 256)
            ->nonQueued();
    }

    public function syncMediaName(){
        foreach( $this->getMedia('dewan_guru_avatar') as $media){
            $media->file_name = getMediaFilename($this, $media);
            $media->save();
        }
    }

    public static function getForm(): array
    {
        return [
            Section::make('Data Dewan Guru')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('avatar')
                        ->label('Avatar')
                        ->avatar()
                        ->collection('dewan_guru_avatar')
                        ->conversion('thumb')
                        ->moveFiles()
                        ->image()
                        ->imageEditor()
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('nama')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(96),
                    TextInput::make('nama_panggilan')
                        ->label('Nama Panggilan')
                        ->required()
                        ->maxLength(64),
                    TextInput::make('nomor_telepon')
                        ->label('Nomor Telepon')
                        ->tel()
                        ->required()
                        ->maxLength(16),
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->maxLength(96)
                        ->default(null),
                    TextInput::make('alamat')
                        ->label('Alamat')
                        ->maxLength(255)
                        ->default(null),
                    Toggle::make('status_aktif')
                        ->label('Status Aktif')
                        ->required(),
                ])

        ];
    }

}
