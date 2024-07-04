<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Kategori extends Model
{
    use HasFactory;
    protected $table = 'kategori';

    protected $fillable = [
        'nama',
        'slug',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function blogs() : HasMany
    {
        return $this->hasMany(Blog::class, 'kategori_id');
    }

    public function media() : HasMany
    {
        return $this->hasMany(Media::class, 'kategori_id');
    }

    public static function getForm()
    {
        return [
            TextInput::make('nama')
                ->live(true)
                ->afterStateUpdated(function (Get $get, Set $set, ?string $operation, ?string $old, ?string $state) {
                    $set('slug', Str::slug($state));
                })
                ->unique('kategori', 'nama', null, 'id')
                ->required()
                ->maxLength(64),

            TextInput::make('slug')
                ->unique('kategori', 'slug', null, 'id')
                ->readOnly()
        ];
    }

    public function getTable()
    {
        return 'kategori';
    }
}
