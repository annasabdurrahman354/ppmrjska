<?php

namespace App\Models;

use App\Enums\JenisKelamin;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class DewanGuru extends Model implements FilamentUser, HasMedia, HasAvatar, HasName
{
    use InteractsWithMedia, HasUlids, SoftDeletes, Notifiable;
    use HasPanelShield;
    use HasRoles, HasApiTokens;

    protected $table = 'dewan_guru';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'nama_panggilan',
        'jenis_kelamin',
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
        'jenis_kelamin' => JenisKelamin::class
    ];

    public function getFilamentName(): string
    {
        return $this->nama;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getFirstMediaUrl('dewan_guru_avatar', 'thumb')
            ??
            "https://ui-avatars.com/api/?background=random&size=256&rounded=true&name=".str_replace(" ", "+", $this->nama);
    }

    public function getAvatarUrl()
    {
        return filament()->getUserAvatarUrl($this);
    }

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
                        ->required(),
                    TextInput::make('nama_panggilan')
                        ->label('Nama Panggilan')
                        ->required()
                        ->maxLength(64),
                    Select::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->options(JenisKelamin::class)
                        ->disabled(fn (string $operation) => isNotAdmin() && $operation != 'create')
                        ->required(),
                    TextInput::make('nomor_telepon')
                        ->label('Nomor Telepon')
                        ->tel()
                        ->required()
                        ->maxLength(16),
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->default(null),
                    TextInput::make('alamat')
                        ->label('Alamat')
                        ->default(null),
                    Toggle::make('status_aktif')
                        ->label('Status Aktif')
                        ->required(),
                ])

        ];
    }
    protected static function booted(): void
    {
        parent::boot();
        static::softDeleted(function ($record) {
            JurnalKelas::where('dewan_guru_type', get_class($record))->where('dewan_guru_id', $record->id)->update(['dewan_guru_type' => null, 'dewan_guru_id' => null]);
        });
    }
}
