<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provinsi extends Model
{
    use HasFactory;

    protected $table = 'provinsi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
    ];

    public function semuaKota(): HasMany
    {
        return $this->hasMany(Kota::class);
    }

    public static function getForm()
    {
        return [
            TextInput::make('nama')
                ->required()
                ->maxLength(48),
        ];
    }
}
