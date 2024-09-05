<?php

namespace App\Models;

use App\Enums\JenisKelamin;
use App\Enums\StatusKehadiran;
use App\Enums\StatusPondok;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Mpyw\ScopedAuth\AuthScopable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasName, HasMedia, AuthScopable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasPanelShield;
    use HasRoles, HasUlids, SoftDeletes;
    use InteractsWithMedia, HasApiTokens, Notifiable;
    use SoftCascadeTrait;

    protected $softCascade = ['biodataSantri'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'nama_panggilan',
        'jenis_kelamin',
        'nis',
        'nomor_telepon',
        'email',
        'angkatan_pondok',
        'status_pondok',
        'tanggal_lulus_pondok',
        'tanggal_keluar_pondok',
        'alasan_keluar_pondok',
        'password',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'angkatan_pondok' => 'integer',
        'tanggal_lulus_pondok' => 'date',
        'tanggal_keluar_pondok' => 'date',
        'email_verified_at' => 'timestamp',
        'password' => 'hashed',
        'jenis_kelamin' => JenisKelamin::class,
        'status_pondok' => StatusPondok::class,
    ];

    public function getFilamentName(): string
    {
        return $this->nama;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->isSuperAdmin();
        }

        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getFirstMediaUrl('avatar', 'thumb')
            ??
            "https://ui-avatars.com/api/?background=random&size=256&rounded=true&name=".str_replace(" ", "+", $this->nama);
    }

    public function getAvatarUrl()
    {
        return filament()->getUserAvatarUrl($this);
    }

    public function angkatanPondok(): BelongsTo
    {
        return $this->belongsTo(AngkatanPondok::class, 'angkatan_pondok', 'angkatan_pondok');
    }

    public function biodataSantri(): HasOne
    {
        return $this->hasOne(BiodataSantri::class);
    }

    public function jurnalKelas(): HasManyThrough
    {
        return $this->hasManyThrough(
            JurnalKelas::class, // The final model you want to access
            PresensiKelas::class, // The intermediate model
            'user_id', // Foreign key on the PresensiKelas table
            'id', // Foreign key on the JurnalKelas table
            'id', // Local key on the User table
            'jurnal_kelas_id' // Local key on the PresensiKelas table
        );
    }

    public function plotKamarAsrama(): HasMany
    {
        return $this->hasMany(PlotKamarAsrama::class);
    }

    public function plotJadwalMunaqosah(): HasMany
    {
        return $this->hasMany(PlotJadwalMunaqosah::class);
    }

    public function presensiKelas(): HasMany
    {
        return $this->hasMany(PresensiKelas::class);
    }

    public function tagihanAdministrasi(): HasMany
    {
        return $this->hasMany(TagihanAdministrasi::class);
    }

    protected function kelas(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->angkatan_pondok != 0 ? $this->angkatanPondok->kelas : config('filament-shield.super_admin.name'),
        );
    }

    protected function tanggalMasukTakmili(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->angkatanPondok()->tanggal_masuk_takmili,
        );
    }

    protected function namaAsramaTerbaru(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->plotKamarAsrama()->latest()->first()?->kamarAsrama?->asrama?->nama ?? 'Belum Diploting',
        );
    }

    protected function nomorKamarAsramaTerbaru(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->plotKamarAsrama()->latest()->first()?->kamarAsrama?->nomor_kamar ?? 'Belum Diploting',
        );
    }

    protected function biayaAsramaTahunanTerbaru(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->plotKamarAsrama()->latest()->first()?->kamarAsrama?->asrama?->biaya_asrama_tahunan ?? 0,
        );
    }

    protected function kepemilikanGedungPlotAsramaTerbaru(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->plotKamarAsrama()->latest()->first()?->kamarAsrama?->asrama?->kepemilikan_gedung,
        );
    }

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->nama_panggilan.' ('.$this->kelas.')',
        );
    }

    public function getNameAttribute()
    {
        return $this->nama;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(config('filament-shield.super_admin.name'));
    }

    public function isNotSuperAdmin(): bool
    {
        return !$this->hasRole(config('filament-shield.super_admin.name'));
    }

    public function scopeWhereKelas(Builder $query, string $kelas): void
    {
        $query->whereHas('angkatanPondok', function ($query) use ($kelas) {
            $query->where('kelas', $kelas);
        });
    }

    public function scopeWhereKelasIn(Builder $query, $kelas): void
    {
        $query->whereHas('angkatanPondok', function ($query) use ($kelas) {
            $query->whereIn('kelas', $kelas);
        });
    }

    public function cekPerekap(JurnalKelas $jurnalKelas): bool
    {
        return $jurnalKelas->perekap_id == $this->id;
    }

    public function cekKelasKBM(JurnalKelas $jurnalKelas): bool
    {
        return $jurnalKelas->presensikelas()->where('user_id', $this->id)->exists();
    }

    public function cekKehadiran(JurnalKelas $jurnalKelas): bool
    {
        return $jurnalKelas->presensikelas()->where('user_id', $this->id)->where('status_kehadiran', StatusKehadiran::HADIR->value)->exists();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 256, 256)
            ->nonQueued();
    }

    public function syncMediaName(){
        foreach( $this->getMedia('user_avatar') as $media){
            $media->file_name = getMediaFilename($this, $media);
            $media->save();
        }
    }

    public static function getForm(): array
    {
        return [
            Section::make('Data Umum')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('avatar')
                        ->label('Avatar')
                        ->avatar()
                        ->collection('user_avatar')
                        ->conversion('thumb')
                        ->moveFiles()
                        ->image()
                        ->imageEditor()
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('nama')
                        ->label('Nama Lengkap')
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_user') && $operation != 'create')
                        ->required(),
                    TextInput::make('nama_panggilan')
                        ->label('Nama Panggilan')
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_user') && $operation != 'create')
                        ->required()
                        ->maxLength(64),
                    Select::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->options(JenisKelamin::class)
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_user') && $operation != 'create')
                        ->required(),
                    TextInput::make('nis')
                        ->label('Nomor Induk Santri')
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_user') && $operation != 'create')
                        ->numeric()
                        ->required()
                        ->length(9),
                    TextInput::make('nomor_telepon')
                        ->label('Nomor Telepon')
                        ->tel()
                        ->required()
                        ->maxLength(16),
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required(),
                    Select::make('roles')
                        ->label('Role')
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_user') && $operation != 'create')
                        ->relationship(name: 'roles', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                            return $query->whereNotIn('name', ['filament_user']);
                        })
                        ->options(fn () => DB::table('roles')->pluck('name', 'id'))
                        ->multiple()
                        ->native(false),
                    DateTimePicker::make('email_verified_at')
                        ->label('Email Terverifikasi Pada')
                        ->disabled(fn (string $operation) => auth()->user()->isNotSuperAdmin()),
                    TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->disabled(fn (string $operation) => auth()->user()->isNotSuperAdmin())
                        ->required(fn (string $operation): bool => $operation === 'create'),
                ])
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                ]),

            Section::make('Data Kesiswaan')
                ->schema([
                    Select::make('angkatan_pondok')
                        ->label('Angkatan Pondok')
                        ->createOptionAction(fn(Action $action) =>
                            $action->form(AngkatanPondok::getForm())
                                ->action(function (array $data, AngkatanPondok $record): void {
                                    $record = AngkatanPondok::updateOrCreate(
                                        ['angkatan_pondok' => $data['angkatan_pondok']],
                                        [
                                            'kelas' => $data['is_takmili'] ? 'Takmili' : (string) $data['angkatan_pondok'],
                                            'tanggal_masuk_takmili' => $data['is_takmili'] ? $data['tanggal_masuk_takmili'] : null
                                        ]
                                    );
                                })
                        )
                        ->options(AngkatanPondok::all()->pluck('angkatan_pondok', 'angkatan_pondok'))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_user') && $operation != 'create'),
                    Select::make('status_pondok')
                        ->label('Status Pondok')
                        ->options(StatusPondok::class)
                        ->required()
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_user') && $operation != 'create'),
                    DatePicker::make('tanggal_lulus_pondok')
                        ->label('Tanggal Lulus Pondok')
                        ->visible(fn(Get $get) => $get('status_pondok') === StatusPondok::LULUS->value)
                        ->required(fn(Get $get) => $get('status_pondok') === StatusPondok::LULUS->value)
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_user') && $operation != 'create'),
                    DatePicker::make('tanggal_keluar_pondok')
                        ->label('Tanggal Keluar Pondok')
                        ->visible(fn(Get $get) => $get('status_pondok') === StatusPondok::KELUAR->value)
                        ->required(fn(Get $get) => $get('status_pondok') === StatusPondok::KELUAR->value)
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_user') && $operation != 'create'),
                    TextInput::make('alasan_keluar_pondok')
                        ->label('Alasan Keluar Pondok')
                        ->visible(fn(Get $get) => $get('status_pondok') === StatusPondok::KELUAR->value)
                        ->required(fn(Get $get) => $get('status_pondok') === StatusPondok::KELUAR->value)
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_user') && $operation != 'create'),
                ])
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                ]),
        ];
    }

    public static function getInfolist()
    {

    }

    protected static function booted(): void
    {
        //static::addGlobalScope('notSuperAdmin', function (Builder $builder) {
        //    $builder->where('angkatan_pondok', '!=', 0);
        //});

        static::softDeleted(function ($record) {
            JurnalKelas::where('dewan_guru_type', $this::class)->where('dewan_guru_id', $record->id)->update(['dewan_guru_type' => null, 'dewan_guru_id' => null]);
        });
    }

    public function scopeForAuthentication(Builder $query): Builder
    {
        return $query->withoutGlobalScope('notSuperAdmin');
    }
}
