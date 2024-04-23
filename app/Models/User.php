<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\JenisKelamin;
use App\Enums\StatusPondok;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasName, HasMedia
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasPanelShield;
    use HasFactory, HasRoles, HasUlids, SoftDeletes;
    use InteractsWithMedia, HasApiTokens, Notifiable;

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
        'kelas',
        'angkatan_pondok',
        'status_pondok',
        'tanggal_lulus_pondok',
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
        return $this->getMedia('avatars')?->first()?->getUrl('thumb') ?? '';
        // return $this->avatar_url;
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

    public function isKetuaKelas(): bool
    {
        return $this->hasRole('ketua_kelas');
    }

    public function isDmcPasus(): bool
    {
        return $this->hasRole('dmcp%');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function biodataSantri(): HasOne
    {
        return $this->hasOne(BiodataSantri::class);
    }
}
